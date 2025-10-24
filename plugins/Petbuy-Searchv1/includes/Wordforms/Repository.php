<?php
namespace Petbuy\Search\Wordforms;

use WP_Error;

/**
 * Gestione file wordforms:
 *  - formato per riga: "canonica = sinonimo1 sinonimo2 ..."
 *  - salvataggio atomico con cache WP
 */
class Repository {

    private const CACHE_KEY = 'petbuy_wordforms';
    private string $file;

    /** Path preso dall’opzione, salvo override esplicito */
    public function __construct(?string $file = null) {
        if (!$file) {
            $opt = get_option( PETBUY_WORDFORMS_OPTION );
            if ($opt && is_string($opt)) {
                $file = $opt;
            } else {
                $file = trailingslashit( WP_CONTENT_DIR ) . 'uploads/petbuy-wordforms/wordforms.txt';
            }
        }
        $this->file = wp_normalize_path($file);
    }

    public function get_file(): string {
        return $this->file;
    }

    /** Ritorna mappa [canon => [canon, syn1, syn2, ...]] */
    public function get_assoc(bool $force_refresh = false): array {
        $cached = !$force_refresh ? wp_cache_get(self::CACHE_KEY, PETBUY_CACHE_GROUP) : false;
        if ($cached !== false && is_array($cached)) {
            return $cached;
        }
        $assoc = $this->parse_file($this->file);
        wp_cache_set(self::CACHE_KEY, $assoc, PETBUY_CACHE_GROUP, 300);
        return $assoc;
    }

    /** Aggiunge nuova canonica con eventuali sinonimi */
    public function add_canonical(string $canonical, string|array $synonyms = []): bool|WP_Error {
        $canonical = $this->norm($canonical);
        if ($canonical === '') {
            return new WP_Error('invalid', 'Canonica vuota');
        }
        $assoc = $this->get_assoc();
        $newSyns = $this->norm_list($synonyms);

        if (!isset($assoc[$canonical])) {
            $assoc[$canonical] = array_merge([$canonical], $newSyns);
        } else {
            // merge senza duplicati
            $assoc[$canonical] = array_values(array_unique(array_merge($assoc[$canonical], $newSyns), SORT_STRING));
        }
        return $this->save($assoc);
    }

    /** Aggiunge sinonimi a canonica esistente */
    public function add_synonyms(string $canonical, string|array $synonyms): bool|WP_Error {
        $canonical = $this->norm($canonical);
        $assoc = $this->get_assoc();
        if (!isset($assoc[$canonical])) {
            return new WP_Error('not_found', 'Canonica non trovata');
        }
        $newSyns = $this->norm_list($synonyms);
        if (!$newSyns) {
            return new WP_Error('invalid', 'Nessun sinonimo valido');
        }
        $assoc[$canonical] = array_values(array_unique(array_merge($assoc[$canonical], $newSyns), SORT_STRING));
        return $this->save($assoc);
    }

    /** Rimuove uno o più sinonimi da una canonica */
    public function remove_synonyms(string $canonical, array $synonyms): bool|WP_Error {
        $canonical = $this->norm($canonical);
        $assoc = $this->get_assoc();
        if (!isset($assoc[$canonical])) {
            return new WP_Error('not_found', 'Canonica non trovata');
        }
        $toRemove = array_flip($this->norm_list($synonyms));
        $assoc[$canonical] = array_values(array_filter(
            $assoc[$canonical],
            fn($s) => !isset($toRemove[$this->norm($s)]) || $this->norm($s) === $canonical
        ));
        return $this->save($assoc);
    }

    /** Elimina completamente una canonica */
    public function remove_canonical(string $canonical): bool|WP_Error {
        $canonical = $this->norm($canonical);
        $assoc = $this->get_assoc();
        if (isset($assoc[$canonical])) {
            unset($assoc[$canonical]);
            return $this->save($assoc);
        }
        return new WP_Error('not_found', 'Canonica non trovata');
    }

    
    /** Rimuove singolo sinonimo (helper retrocompatibile) */
    public function remove_synonym(string $canonical, string $synonym): bool|WP_Error {
        return $this->remove_synonyms($canonical, [$synonym]);
    }

    /* ===================== Private ===================== */


    private function norm(string $s): string {
        $s = trim($s);
        return $s === '' ? '' : mb_strtolower($s);
    }

    /** Accetta stringa "a, b c" o array; ritorna array normalizzato */
    private function norm_list(string|array $list): array {
        if (is_string($list)) {
            $list = preg_split('/[,\s]+/u', $list) ?: [];
        }
        $out = [];
        foreach ($list as $s) {
            $s = $this->norm((string)$s);
            if ($s !== '') $out[] = $s;
        }
        // no dedup here; demand to callers/merge
        return $out;
    }

    private function parse_file(string $path): array {
        $assoc = [];
        if (!file_exists($path) || !is_readable($path)) {
            return $assoc;
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES);
        if (!$lines) return $assoc;

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) continue;
            $parts = explode('=', $line, 2);
            if (count($parts) < 2) continue;
            $canon = $this->norm($parts[0]);
            $syns  = $this->norm_list(trim($parts[1]));
            if ($canon === '') continue;
            $set = array_values(array_unique(array_merge([$canon], $syns), SORT_STRING));
            $assoc[$canon] = $set;
        }
        return $assoc;
    }

    private function save(array $assoc): bool|WP_Error {
        ksort($assoc, SORT_NATURAL | SORT_FLAG_CASE);

        $buf = '';
        foreach ($assoc as $canon => $syns) {
            // assicura canonica presente e non ripetuta
            $syns = array_values(array_unique(array_merge([$canon], $this->norm_list($syns)), SORT_STRING));
            // non includere canonica due volte
            $synsNoCanon = array_values(array_filter($syns, fn($s) => $s !== $canon));
            $buf .= $canon . ' = ' . implode(' ', $synsNoCanon) . "\n";
        }

        require_once ABSPATH . 'wp-admin/includes/file.php';
        \WP_Filesystem();
        global $wp_filesystem;

        $dir = dirname($this->file);
        if (!$wp_filesystem->is_dir($dir) && ! $wp_filesystem->mkdir($dir, \FS_CHMOD_DIR)) {
            return new WP_Error('dir_create', 'Impossibile creare la cartella ' . $dir);
        }

        if (! $wp_filesystem->put_contents($this->file, $buf, \FS_CHMOD_FILE)) {
            return new WP_Error('file_write', 'Impossibile scrivere il file ' . $this->file);
        }

        wp_cache_delete(self::CACHE_KEY, PETBUY_CACHE_GROUP);
        return true;
    }
}
