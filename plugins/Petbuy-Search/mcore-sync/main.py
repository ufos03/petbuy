#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Sync incrementale (stile Amazon)

- ADS/PRODS: MySQL (wp_ads_and_prods) -> Manticore (rt_ads_prods)
  Campi: id, name TEXT, category TEXT, sub_category TEXT, price FLOAT, updated_at TIMESTAMP

- STORES (Dokan, SOLO abilitati): WordPress users/usermeta -> Manticore (rt_dokan_stores)
  Lettura da:
    - wp_usermeta.meta_key='dokan_store_name' (nome)
    - wp_usermeta.meta_key='dokan_profile_settings' (JSON o PHP-serialized con address)
  Campi indicizzati: id, store_name, city, state, zip, country, lat, lon, updated_at
  Se lat/lon assenti o 0 -> geocoding Nominatim (rate limited) prima dell'invio.

Funzionalità:
- Cursori incrementali separati (last_ts, last_id)
- /bulk NDJSON con REPLACE (upsert idempotente)
- Retry/backoff su errori temporanei (429/5xx)
- Preflight via SELECT su /sql (HTTP)
- Scheduler con 'sched' + report periodici
- Log in logs/mcore-sync.log con rotazione
"""

import os
import sys
import re
import json
import time
import sched
import logging
from logging.handlers import TimedRotatingFileHandler
from datetime import datetime
from typing import Dict, Any, List, Tuple, Optional

import pymysql
import requests
import pathlib


# ---------------- Config ----------------
MYSQL = dict(
    host=os.getenv("MYSQL_HOST", "127.0.0.1"),
    user=os.getenv("MYSQL_USER", "max"),
    password=os.getenv("MYSQL_PASS", "qwe7asd8"),
    database=os.getenv("MYSQL_DB", "petbuy_db"),
    port=int(os.getenv("MYSQL_PORT", "3306")),
    charset="utf8mb4",
    autocommit=True,
)

BASE_DIR    = pathlib.Path(__file__).resolve().parent
SRC_TABLE   = os.getenv("SRC_TABLE", "wp_ads_and_prods")

INDEX_ADS   = os.getenv("INDEX_ADS", "rt_ads_prods")
INDEX_STORE = os.getenv("INDEX_STORE", "rt_dokan_stores")
MCORE_HTTP  = os.getenv("MCORE_HTTP", "http://192.168.1.9:9308")

STATE_ADS   = os.getenv("STATE_ADS",   str(BASE_DIR / "state" / "ads_and_prods.state"))
STATE_STORE = os.getenv("STATE_STORE", str(BASE_DIR / "state" / "dokan_stores.state"))

BATCH_SIZE  = int(os.getenv("BATCH_SIZE", "1000"))
TIMEOUT     = int(os.getenv("HTTP_TIMEOUT", "30"))

MAX_RETRIES  = int(os.getenv("MAX_RETRIES", "4"))
BACKOFF_BASE = float(os.getenv("BACKOFF_BASE", "0.8"))  # sec
DEBUG_SINGLE = os.getenv("DEBUG_SINGLE", "0") == "1"
SYNC_DEBUG   = os.getenv("SYNC_DEBUG", "0") == "1"

# Log
LOG_DIR  = BASE_DIR / "logs"
LOG_DIR.mkdir(parents=True, exist_ok=True)
LOG_PATH = LOG_DIR / "mcore-sync.log"

logger = logging.getLogger("mcore_sync")
logger.setLevel(logging.DEBUG if SYNC_DEBUG else logging.INFO)
handler = TimedRotatingFileHandler(str(LOG_PATH), when="midnight", backupCount=7, encoding="utf-8")
formatter = logging.Formatter("%(asctime)s [%(levelname)s] %(module)s:%(funcName)s %(threadName)s %(message)s", datefmt="%Y-%m-%d %H:%M:%S")
handler.setFormatter(formatter)
logger.addHandler(handler)
console = logging.StreamHandler(sys.stdout)
console.setFormatter(formatter)
logger.addHandler(console)


# --------------- Helpers ----------------
def sql_ident(name: str) -> str:
    return "`" + str(name).replace("`", "``") + "`"

def load_state(path: str) -> Dict[str, Any]:
    try:
        with open(path, "r") as f:
            return json.load(f)
    except Exception:
        return {"last_ts": "1970-01-01 00:00:00", "last_id": 0}

def save_state(path: str, st: Dict[str, Any]) -> None:
    os.makedirs(os.path.dirname(path), exist_ok=True)
    tmp = path + ".tmp"
    with open(tmp, "w") as f:
        json.dump(st, f)
    os.replace(tmp, path)

def ts_to_unix(val) -> int:
    if val is None: return 0
    if isinstance(val, datetime): return int(val.timestamp())
    if isinstance(val, (int, float)): return int(val)
    s = str(val).strip()
    try:
        return int(datetime.fromisoformat(s).timestamp())
    except Exception:
        try:
            return int(datetime.fromisoformat(s.replace(" ", "T")).timestamp())
        except Exception:
            return int(time.time())

def http_post(path: str, **kw) -> requests.Response:
    return requests.post(MCORE_HTTP + path, timeout=TIMEOUT, **kw)

def mcore_select(query: str) -> Dict[str, Any]:
    r = http_post("/sql", data=query.encode("utf-8"),
                  headers={"Content-Type": "text/plain"})
    if r.status_code // 100 != 2:
        raise RuntimeError(f"/sql error {r.status_code}: {r.text[:500]}")
    return r.json()


def parse_dokan_profile_settings(raw: Optional[str]) -> Dict[str, Any]:
    """
    Restituisce dict: {store_name, city, state, zip, country}
    Supporta JSON e serialized PHP (estrazione via regex).
    (Non vengono più estratti lat/lon)
    """
    out = {"store_name": "", "city": "", "state": "", "zip": "", "country": ""}
    if not raw:
        return out

    s = str(raw).strip()

    # 1) prova JSON
    if s and s[0] in "[{":
        try:
            data = json.loads(s)
            out["store_name"] = data.get("store_name") or data.get("shop_name") or ""
            addr = data.get("address") or {}
            if isinstance(addr, dict):
                out["city"]    = addr.get("city", "") or ""
                out["state"]   = addr.get("state", "") or ""
                out["zip"]     = addr.get("zip", "") or addr.get("postcode", "") or ""
                out["country"] = addr.get("country", "") or ""
            return out
        except Exception:
            pass

    # 2) estrazione da serialized PHP (regex sui campi comuni)
    def rex(key):
        return re.compile(rf'"{re.escape(key)}";s:\d+:"([^"]*)"', re.IGNORECASE)

    for k in ("store_name", "city", "state", "zip", "postcode", "country"):
        m = rex(k).search(s)
        if m:
            val = m.group(1)
            if k == "postcode":
                k = "zip"
            out[k if k != "postcode" else "zip"] = val

    return out


# --------- Preflight (solo SELECT) ---------
def preflight_index(index_name: str) -> None:
    # Healthcheck: preferiamo /sql ma tolleriamo risposte non standard e facciamo fallback HTTP
    try:
        start = time.time()
        mcore_select("SELECT 1;")
        elapsed = (time.time() - start)
        logger.debug("[preflight] /sql healthcheck OK (%.3fs)", elapsed)
    except Exception as e:
        msg = str(e)
        # se il server risponde ma rifiuta la query in modo non chiaro, proseguiamo (logghiamo)
        if "only select" in msg.lower() or "501" in msg:
            logger.warning("[preflight] /sql responded but refused query (non-fatal): %s", msg)
        else:
            # fallback: semplice GET per verificare reachability HTTP
            try:
                r = requests.get(MCORE_HTTP + "/", timeout=TIMEOUT)
                logger.warning("[preflight] /sql failed (%s) but HTTP root reachable -> status=%s", msg, r.status_code)
            except Exception as e2:
                logger.error("[preflight] Manticore /sql unreachable and HTTP fallback failed: %s ; fallback-exc=%s", msg, e2)
                sys.exit(1)

    ident = sql_ident(index_name)
    try:
        start = time.time()
        mcore_select(f"SELECT id FROM {ident} LIMIT 0;")
        elapsed = (time.time() - start)
        logger.info("[preflight] OK: indice %s presente e interrogabile (check=%.3fs).", index_name, elapsed)
    except RuntimeError as e:
        logger.error("[preflight] ERRORE: indice %s assente o non valido. Dettagli: %s", index_name, e)
        sys.exit(1)

# --------------- NDJSON /bulk ---------------
def build_ndjson_replace(index_name: str, docs: List[Dict[str, Any]]) -> str:
    lines = []
    for d in docs:
        obj = {"replace": {"index": index_name, "id": int(d["id"]), "doc": d["doc"]}}  # upsert idempotente
        lines.append(json.dumps(obj, ensure_ascii=False))
    return "\n".join(lines) + "\n"

def send_bulk_ndjson(index_name: str, docs: List[Dict[str, Any]]) -> None:
    if not docs: return
    body = build_ndjson_replace(index_name, docs)
    delay = BACKOFF_BASE
    last_err = None

    for attempt in range(1, MAX_RETRIES + 1):
        try:
            r = http_post("/bulk",
                          data=body.encode("utf-8"),
                          headers={"Content-Type": "application/x-ndjson"})
            logger.info("[bulk %s] HTTP %s; body_len=%d", index_name, r.status_code, len(body))
            # Log full response snippet always (utile per debug)
            snippet = (r.text[:2000] + "...") if (r.text and len(r.text) > 2000) else (r.text or "")
            logger.info("[bulk %s] response snippet: %s", index_name, snippet)
            if 200 <= r.status_code < 300:
                logger.info("[bulk %s] OK", index_name)
                return
            logger.warning("[bulk %s] attempt %s -> HTTP %s", index_name, attempt, r.status_code)
            if r.status_code in (429, 500, 502, 503, 504):
                time.sleep(delay); delay *= 2
                continue
            # non-retryable error: solleva con dettagli
            raise RuntimeError(f"Bulk error {r.status_code}: {snippet}")
        except requests.RequestException as e:
            last_err = str(e)
            logger.warning("[bulk %s] attempt %s exception: %s", index_name, attempt, last_err)
            time.sleep(delay); delay *= 2

    if DEBUG_SINGLE:
        logger.info("[bulk %s] Debug singolo: provo /insert doc-by-doc…", index_name)
        for d in docs:
            r = http_post("/insert",
                          json={"insert": {"index": index_name, "id": int(d["id"])},
                                "doc": d["doc"]})
            snippet = (r.text[:2000] + "...") if (r.text and len(r.text) > 2000) else (r.text or "")
            logger.info("[insert %s] HTTP %s snippet=%s", index_name, r.status_code, snippet)
            if r.status_code // 100 != 2:
                logger.error("[insert %s] ERROR id=%s -> %s %s", index_name, d['id'], r.status_code, snippet)
                raise RuntimeError(f"Singolo doc fallito id={d['id']}: {snippet}")
        logger.info("[bulk %s] Tutti i /insert singoli OK → problema specifico del /bulk.", index_name)

    raise RuntimeError(f"Bulk failed after retries. Last error: {last_err or 'see logs above'}")

# Funzione di test per verificare la reachability di Manticore e permessi /insert
def send_test_insert(index_name: str) -> None:
    test_id = 999999999
    doc = {"test": "mcore-connect", "ts": int(time.time())}
    try:
        r = http_post("/insert", json={"insert": {"index": index_name, "id": test_id}, "doc": doc})
        snippet = (r.text[:2000] + "...") if (r.text and len(r.text) > 2000) else (r.text or "")
        logger.info("[test-insert %s] HTTP %s snippet=%s", index_name, r.status_code, snippet)
        if r.status_code // 100 != 2:
            logger.error("[test-insert] insert failed: %s", snippet)
            raise RuntimeError(f"Test insert failed: {r.status_code} {snippet}")
        logger.info("[test-insert] OK: index=%s id=%s", index_name, test_id)
    except Exception as e:
        logger.exception("[test-insert] Error: %s", e)
        raise

# --------- Mapping riga SQL -> doc (ADS - essenziale) ----------
def row_to_doc_ads(row: Tuple) -> Dict[str, Any]:
    # row: (id, item_id, name, type, price, creation_date, category, sub_category, updated_at)
    (i, _item_id, name, _typ, price, _cdate, cat, subcat, upd) = row
    return {
        "id": int(i),
        "doc": {
            "name": name or "",
            "category": cat or "",
            "sub_category": subcat or "",
            "price": float(price) if price is not None else 0.0,
            "updated_at": ts_to_unix(upd),
        },
    }

# --------- Mapping riga SQL -> doc (STORES - da usermeta) ----------
def row_to_doc_store(row: Tuple) -> Dict[str, Any]:
    """
    row: (id, store_name_meta, profile_raw, updated_at)
    - profile_raw: JSON o serialized PHP (dokan_profile_settings)
    Note: lat/lon omitted (non indicizziamo coordinate).
    """
    (i, store_name_meta, profile_raw, upd) = row

    prof = parse_dokan_profile_settings(profile_raw)
    store_name = (store_name_meta or "").strip() or prof.get("store_name", "") or ""

    city    = prof.get("city", "")
    state   = prof.get("state", "")
    zipc    = prof.get("zip", "")
    country = prof.get("country", "")

    return {
        "id": int(i),
        "doc": {
            "store_name": store_name,
            "city": city,
            "state": state,
            "zip": zipc,
            "country": country,
            "updated_at": ts_to_unix(upd),
        },
    }


# ---------------- Singolo sync: ADS ----------------
def run_ads_once(full: bool = False) -> Dict[str, Any]:
    preflight_index(INDEX_ADS)

    state = {"last_ts": "1970-01-01 00:00:00", "last_id": 0} if full else load_state(STATE_ADS)
    stats = {"synced": 0, "last_id": state.get("last_id", 0), "last_ts": state.get("last_ts", "1970-01-01 00:00:00"), "ok": True, "error": ""}

    # normalizza last_ts per confronto SQL
    state_ts = normalize_state_ts(state.get("last_ts"))
    logger.info("[ads] starting run_ads_once state_last_ts=%s last_id=%s (normalized=%s)", state.get("last_ts"), state.get("last_id"), state_ts)

    conn = pymysql.connect(**MYSQL)
    try:
        # quick check: mostra max(updated_at) dalla sorgente per capire se abbiamo righe nuove
        try:
            with conn.cursor() as cur:
                cur.execute(f"SELECT MAX(updated_at) FROM {sql_ident(SRC_TABLE)}")
                mx = cur.fetchone()
                logger.info("[ads][debug] source MAX(updated_at) = %s", mx[0] if mx else None)
        except Exception as e:
            logger.debug("[ads][debug] cannot query MAX(updated_at): %s", e)

        total = 0
        while True:
            sql = (
                f"SELECT id,item_id,name,type,price,creation_date,category,sub_category,updated_at "
                f"FROM {sql_ident(SRC_TABLE)} "
                f"WHERE (updated_at > %s) OR (updated_at = %s AND id > %s) "
                f"ORDER BY updated_at ASC, id ASC "
                f"LIMIT %s"
            )

            try:
                with conn.cursor() as cur:
                    logger.debug("[ads] executing SQL: %s params=(%s,%s,%s,%s)", sql, state_ts, state_ts, state.get("last_id", 0), BATCH_SIZE)
                    cur.execute(sql, (state_ts, state_ts, int(state.get("last_id", 0)), BATCH_SIZE))
                    rows = cur.fetchall()
            except Exception as e:
                logger.exception("[ads] SQL execution failed: %s", e)
                debug_recent_src(conn)
                raise

            logger.info("[ads][debug] Rows fetched: %d", len(rows))
            if rows:
                logger.debug("[ads][debug] first row preview: %s", str(rows[0])[:300])

            if not rows:
                logger.info("[ads] Nessun record da sincronizzare. state_last_ts=%s state_last_id=%s", state_ts, state.get("last_id", 0))
                # diagnosi aggiuntiva: confronta MAX(updated_at) con lo stato e mostra righe candidate
                try:
                    with conn.cursor() as cur:
                        cur.execute(f"SELECT MAX(updated_at) FROM {sql_ident(SRC_TABLE)}")
                        mx = cur.fetchone()
                        mx_val = mx[0] if mx else None
                        logger.info("[ads][diag] source MAX(updated_at) = %s (type=%s)", mx_val, type(mx_val).__name__)
                        # confronto in unix per evitare problemi di formato/tz
                        mx_unix = ts_to_unix(mx_val)
                        st_unix = ts_to_unix(state_ts)
                        logger.info("[ads][diag] unix: source_max=%s state_ts=%s", mx_unix, st_unix)

                        if mx_unix > st_unix:
                            logger.info("[ads][diag] SOURCE has newer rows than state but query returned none -> dumping candidate rows for inspection")
                            # righe con updated_at >= state_ts (mostra poche righe)
                            cur.execute(f"SELECT id, updated_at FROM {sql_ident(SRC_TABLE)} WHERE updated_at >= %s ORDER BY updated_at ASC, id ASC LIMIT 50", (state_ts,))
                            cand = cur.fetchall()
                            pretty = [(r[0], repr(r[1])) for r in cand]
                            logger.info("[ads][diag] candidates (id, updated_at): %s", pretty)
                            # inoltre mostra righe con updated_at == state_ts ma id > last_id
                            cur.execute(f"SELECT id, updated_at FROM {sql_ident(SRC_TABLE)} WHERE updated_at = %s AND id > %s ORDER BY id ASC LIMIT 50", (state_ts, int(state.get("last_id", 0))))
                            same_eq = cur.fetchall()
                            logger.info("[ads][diag] equal_ts_and_higher_id (id, updated_at): %s", [(r[0], repr(r[1])) for r in same_eq])
                except Exception as e:
                    logger.exception("[ads][diag] diagnostica fallita: %s", e)

                debug_recent_src(conn)
                break

            try:
                docs = [row_to_doc_ads(r) for r in rows]
                logger.info("[ads][debug] Built docs: %d", len(docs))
                if docs:
                    send_bulk_ndjson(INDEX_ADS, docs)
                    logger.info("[ads][debug] Bulk insert completed for %d docs", len(docs))

                    last_upd = rows[-1][-1]
                    last_id = int(rows[-1][0])
                    # salva lo stato in formato stringa ISO compatibile
                    if isinstance(last_upd, datetime):
                        saved_ts = last_upd.strftime("%Y-%m-%d %H:%M:%S")
                    else:
                        saved_ts = normalize_state_ts(last_upd)
                    state["last_ts"] = saved_ts
                    state["last_id"] = last_id
                    save_state(STATE_ADS, state)

                    # aggiorna anche la variabile per il prossimo loop
                    state_ts = normalize_state_ts(state["last_ts"])

                    total += len(docs)
                    logger.info("[ads] Indicizzati %d record (last_id=%d last_ts=%s)", len(docs), last_id, state["last_ts"])

            except Exception as e:
                logger.exception("[ads] Errore processing batch: %s", e)
                raise

        stats.update({"synced": total, "last_id": state.get("last_id", 0), "last_ts": state.get("last_ts", "")})
        return stats

    except Exception as e:
        stats["ok"] = False
        stats["error"] = str(e)
        logger.exception("[ads] Errore durante il sync: %s", e)
        return stats
    finally:
        conn.close()

# ---------------- Singolo sync: STORES (da usermeta, con geocoding) ----------------
def run_stores_once(full: bool = False) -> Dict[str, Any]:
    preflight_index(INDEX_STORE)

    # ATTENZIONE: non esiste un updated_at affidabile sui meta → usiamo user_registered
    # Questo cattura SOLO nuovi seller; update profilo non sono rilevati in modo incrementale.
    # Se vuoi includere gli update, esegui periodicamente una "--full" o rivedi la fonte dati.
    state = {"last_ts": "1970-01-01 00:00:00", "last_id": 0} if full else load_state(STATE_STORE)
    stats = {"synced": 0, "last_id": state["last_id"], "last_ts": state["last_ts"], "ok": True, "error": ""}

    expr_upd = "u.user_registered"

    sql = f"""
    SELECT
      u.ID AS id,
      MAX(CASE WHEN um_name.meta_key = 'dokan_store_name' THEN um_name.meta_value END) AS store_name,
      MAX(CASE WHEN um_prof.meta_key = 'dokan_profile_settings' THEN um_prof.meta_value END) AS profile_json,
      {expr_upd} AS updated_at
    FROM wp_users u
    INNER JOIN wp_usermeta cap
      ON cap.user_id = u.ID
      AND cap.meta_key = 'wp_capabilities'
      AND cap.meta_value LIKE '%%"seller"%%'
    INNER JOIN wp_usermeta um_sell
      ON um_sell.user_id = u.ID
      AND um_sell.meta_key = 'dokan_enable_selling'
      AND LOWER(um_sell.meta_value) IN ('1','yes','on','true')
    LEFT JOIN wp_usermeta um_name
      ON um_name.user_id = u.ID
      AND um_name.meta_key = 'dokan_store_name'
    LEFT JOIN wp_usermeta um_prof
      ON um_prof.user_id = u.ID
      AND um_prof.meta_key = 'dokan_profile_settings'
    WHERE ({expr_upd} > %s) OR ({expr_upd} = %s AND u.ID > %s)
    GROUP BY u.ID, {expr_upd}
    ORDER BY {expr_upd} ASC, u.ID ASC
    LIMIT %s
    """.strip()

    conn = pymysql.connect(**MYSQL)
    try:
        total = 0
        while True:
            with conn.cursor() as cur:
                cur.execute(sql, (state["last_ts"], state["last_ts"], state["last_id"], BATCH_SIZE))
                rows = cur.fetchall()

            # Debug logs
            logger.info("[stores][debug] Query params: last_ts=%s, last_id=%s, batch=%s", 
                       state["last_ts"], state["last_id"], BATCH_SIZE)
            logger.info("[stores][debug] Rows found: %d", len(rows))
            if rows:
                logger.info("[stores][debug] First row: %s", str(rows[0]))

            if not rows:
                if total == 0:
                    logger.info("[stores] Nessun negozio da sincronizzare.")
                else:
                    logger.info("[stores] Sync negozi completata. Totali: %s.", total)
                break

            try:
                docs = [row_to_doc_store(r) for r in rows]
                logger.info("[stores][debug] Docs built: %d", len(docs))
                if docs:
                    logger.info("[stores][debug] First doc: %s", docs[0])
                    send_bulk_ndjson(INDEX_STORE, docs)
                    logger.info("[stores][debug] Bulk insert completed")

                    last_upd = rows[-1][-1]
                    last_id = int(rows[-1][0])
                    state.update({
                        "last_ts": last_upd.strftime("%Y-%m-%d %H:%M:%S") if isinstance(last_upd, datetime) else str(last_upd),
                        "last_id": last_id
                    })
                    save_state(STATE_STORE, state)

                    total += len(docs)
                    logger.info("[stores] Indicizzati %d record (last_id=%d)", len(docs), last_id)

            except Exception as e:
                logger.exception("[stores] Errore processing batch: %s", e)
                raise

        stats.update({"synced": total, "last_id": state["last_id"], "last_ts": state["last_ts"]})
        return stats

    except Exception as e:
        stats["ok"] = False
        stats["error"] = str(e)
        logger.exception("[stores] Errore durante il sync: %s", e)
        return stats
    finally:
        conn.close()

# ============== MAIN ==============
def run_sync(full_ads: bool = False, full_stores: bool = False) -> None:
    logger.info("Avvio sync: full_ads=%s full_stores=%s", bool(full_ads), bool(full_stores))
    t0 = time.time()
    logger.info("Avvio sync ADS/PRODS...")
    stats_ads = run_ads_once(full=full_ads)
    logger.info("Sync ADS/PRODS completato. Statistiche: %s", stats_ads)

    logger.info("Avvio sync STORES...")
    stats_stores = run_stores_once(full=full_stores)
    logger.info("Sync STORES completato. Statistiche: %s", stats_stores)

    total_elapsed = time.time() - t0
    logger.info("Sync completato. Totale durata: %.3fs. ADS=%s STORES=%s", total_elapsed, stats_ads.get("synced"), stats_stores.get("synced"))

def normalize_state_ts(val) -> str:
    """
    Restituisce una stringa DATETIME compatibile MySQL "YYYY-MM-DD HH:MM:SS".
    Accetta:
     - int/float (unix timestamp)
     - ISO string (YYYY-MM-DD... o epoch-string)
     - valore già corretto -> restituisce come stringa
    """
    if val is None:
        return "1970-01-01 00:00:00"
    try:
        if isinstance(val, (int, float)):
            return datetime.fromtimestamp(int(val)).strftime("%Y-%m-%d %H:%M:%S")
        s = str(val).strip()
        # se è numerico (epoch seconds)
        if re.fullmatch(r"\d{10}", s):
            return datetime.fromtimestamp(int(s)).strftime("%Y-%m-%d %H:%M:%S")
        # prova a parsare ISO
        try:
            dt = datetime.fromisoformat(s)
            return dt.strftime("%Y-%m-%d %H:%M:%S")
        except Exception:
            # se contiene spazio, prova a forzare
            try:
                dt = datetime.fromisoformat(s.replace(" ", "T"))
                return dt.strftime("%Y-%m-%d %H:%M:%S")
            except Exception:
                # fallback: usa stringa così com'è (MySQL accetterà se è compatibile)
                return s
    except Exception:
        return "1970-01-01 00:00:00"

def debug_recent_src(conn, limit: int = 10):
    try:
        with conn.cursor() as cur:
            q = f"SELECT id, updated_at FROM {sql_ident(SRC_TABLE)} ORDER BY updated_at DESC LIMIT %s"
            cur.execute(q, (int(limit),))
            rows = cur.fetchall()
            # mostriamo sia raw che convertito
            pretty = []
            for r in rows:
                rid = r[0]
                upd = r[1]
                try:
                    pretty.append((rid, str(upd)))
                except Exception:
                    pretty.append((rid, repr(upd)))
            logger.debug("[debug-src] recent rows (id, updated_at): %s", pretty)
            # log max updated_at for quick check
            if rows:
                max_ts = rows[0][1]
                logger.info("[debug-src] max updated_at in source: %s", str(max_ts))
    except Exception as e:
        logger.debug("[debug-src] failed: %s", e)

# ---------------- REST (Flask) ----------------
# Usa Flask per esporre endpoint di controllo: /run, /status, /test-insert, /logs
# Optional token auth via env SYNC_REST_TOKEN (header X-SYNC-TOKEN).
try:
    from flask import Flask, request, jsonify, abort
except Exception:
    Flask = None  # Flask non installato

import threading
import io

REST_BIND = os.getenv("SYNC_REST_BIND", "127.0.0.1")
REST_PORT = int(os.getenv("SYNC_REST_PORT", "27000"))
REST_TOKEN = os.getenv("SYNC_REST_TOKEN", "").strip()

_rest_lock = threading.Lock()
_rest_bg_thread = None

def _read_state_json_path(path):
    try:
        with open(path, "r", encoding="utf-8") as f:
            return json.load(f)
    except Exception:
        try:
            with open(path, "r", encoding="utf-8") as f:
                return f.read()
        except Exception:
            return None

def _tail_file(path, lines=200):
    try:
        with open(path, "rb") as f:
            avg_line_len = 200
            to_read = lines * avg_line_len
            try:
                f.seek(-to_read, io.SEEK_END)
            except Exception:
                f.seek(0)
            data = f.read().decode("utf-8", errors="replace")
            return "\n".join(data.splitlines()[-lines:])
    except Exception:
        return ""

def _check_token(req):
    if not REST_TOKEN:
        return True
    header = req.headers.get("X-SYNC-TOKEN") or req.headers.get("Authorization")
    if not header:
        return False
    # accept "Bearer TOKEN" or raw token
    if header.startswith("Bearer "):
        header = header.split(" ", 1)[1].strip()
    return header == REST_TOKEN

def _run_sync_bg(full_ads=False, full_stores=False):
    try:
        run_sync(full_ads=full_ads, full_stores=full_stores)
    except Exception:
        logger.exception("[rest] background run_sync failed")

def start_rest_server_flask(bind: str = REST_BIND, port: int = REST_PORT):
    if Flask is None:
        logger.error("Flask non disponibile. Installa package 'flask' nel venv.")
        return None

    app = Flask("mcore_sync_rest")

    @app.before_request
    def _auth():
        if not _check_token(request):
            return jsonify({"error": "unauthorized"}), 401

    @app.route("/status", methods=["GET"])
    def status():
        ads_state = _read_state_json_path(STATE_ADS)
        stores_state = _read_state_json_path(STATE_STORE)
        log_tail = _tail_file(str(LOG_PATH), lines=int(request.args.get("lines", 200)))
        return jsonify({"ads_state": ads_state, "stores_state": stores_state, "log_tail": log_tail})

    @app.route("/logs", methods=["GET"])
    def logs():
        lines = int(request.args.get("lines", 200))
        lines = max(10, min(2000, lines))
        return _tail_file(str(LOG_PATH), lines=lines), 200, {"Content-Type": "text/plain; charset=utf-8"}

    @app.route("/test-insert", methods=["POST"])
    def test_insert():
        try:
            send_test_insert(INDEX_ADS)
            return jsonify({"ok": True})
        except Exception as e:
            logger.exception("[rest] test-insert failed: %s", e)
            return jsonify({"ok": False, "error": str(e)}), 500

    @app.route("/run", methods=["POST"])
    def run_route():
        if not _rest_lock.acquire(blocking=False):
            return jsonify({"error": "sync already running"}), 409
        try:
            data = request.get_json(silent=True) or {}
            full_ads = bool(data.get("full_ads"))
            full_stores = bool(data.get("full_stores"))
            # esegui in background per non bloccare la request
            thr = threading.Thread(target=_run_sync_bg, args=(full_ads, full_stores), daemon=True)
            thr.start()
            return jsonify({"ok": True, "started_in_background": True})
        finally:
            _rest_lock.release()

    def _serve():
        # disable flask reloader
        app.run(host="0.0.0.0", port=27000, threaded=True, use_reloader=False)

    thr = threading.Thread(target=_serve, daemon=True, name="mcore-flask-rest")
    thr.start()
    logger.info("REST Flask server avviato su http://%s:%s (endpoints: /run /status /test-insert /logs). Use header X-SYNC-TOKEN if configured.", bind, port)
    return thr

if __name__ == "__main__":
    import argparse

    ap = argparse.ArgumentParser(description="mcore-sync scheduler")
    ap.add_argument("--oneshot", action="store_true", help="Esegui una sola volta e esci")
    ap.add_argument("--full-ads", action="store_true", help="Forza reindex completo ADS (usa con --oneshot)")
    ap.add_argument("--full-stores", action="store_true", help="Forza reindex completo STORES (usa con --oneshot)")
    ap.add_argument("--interval", type=int, default=int(os.getenv("SYNC_INTERVAL", "60")), help="Interval sync (sec)")
    ap.add_argument("--report-interval", type=int, default=int(os.getenv("REPORT_INTERVAL", "300")), help="Interval report (sec)")
    ap.add_argument("--no-preflight", action="store_true", help="Salta i controlli preflight (utile per debug)")
    ap.add_argument("--test-insert", action="store_true", help="Prova /insert su Manticore e esci")
    ap.add_argument("--rest", action="store_true", help="Avvia server REST (Flask) per controllo")
    args = ap.parse_args()

    # handle test-insert early
    if args.test_insert:
        try:
            logger.info("Eseguo test-insert su INDEX_ADS: %s", INDEX_ADS)
            send_test_insert(INDEX_ADS)
            logger.info("Test insert completato.")
            sys.exit(0)
        except Exception:
            logger.error("Test insert fallito; vedi log per dettagli.")
            sys.exit(1)

    if args.no_preflight:
        logger.warning("Preflight disabilitato (--no-preflight). Attenzione: il servizio potrebbe fallire più tardi.")
        def _nop_preflight(index_name: str) -> None:
            logger.debug("[preflight] SKIPPED for %s", index_name)
        # Sovrascrivo la funzione per evitare sys.exit dalle preflight
        preflight_index = _nop_preflight  # type: ignore

    # se richiesto, avvia il server REST (non blocca)
    if args.rest:
        thr = start_rest_server_flask()
        if thr is None:
            logger.error("Impossibile avviare REST server: Flask non disponibile nel venv.")
        else:
            logger.info("REST server avviato (thread: %s).", getattr(thr, "name", "unknown"))

    try:
        if args.oneshot:
            logger.info("Esecuzione oneshot started (full_ads=%s full_stores=%s)", args.full_ads, args.full_stores)
            try:
                run_sync(full_ads=args.full_ads, full_stores=args.full_stores)
            except Exception as e:
                logger.exception("Errore durante oneshot: %s", e)
                sys.exit(1)
            logger.info("Oneshot completato.")
            sys.exit(0)

        # Da qui in poi comportamento long-running
        logger.info("Scheduler avviato: sync ogni %ss, report ogni %ss. Log: %s", args.interval, args.report_interval, LOG_PATH)
        next_report = time.time() + args.report_interval

        while True:
            start = time.time()
            try:
                run_sync(full_ads=False, full_stores=False)
            except Exception as e:
                logger.exception("Errore non gestito in run_sync: %s", e)

            # periodic report (stato semplice)
            if time.time() >= next_report:
                try:
                    state_ads = load_state(STATE_ADS)
                    state_stores = load_state(STATE_STORE)
                    logger.info("REPORT: ADS state=%s STORES state=%s", state_ads, state_stores)
                except Exception as e:
                    logger.debug("Errore durante report: %s", e)
                next_report = time.time() + args.report_interval

            # flush handlers to ensure logs on disk immediately
            for h in logger.handlers:
                try:
                    h.flush()
                except Exception:
                    pass

            # sleep preserving interval from loop start
            elapsed = time.time() - start
            to_sleep = max(0, args.interval - elapsed)
            time.sleep(to_sleep)

    except KeyboardInterrupt:
        logger.info("Terminazione richiesta (KeyboardInterrupt).")
        sys.exit(0)
    except Exception as e:
        logger.exception("Errore fatale nello main: %s", e)
        sys.exit(1)
