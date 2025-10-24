# Wishlist API - Documentazione

Questa API permette la gestione della wishlist per utenti autenticati su Petbuy.  
Le operazioni sono esposte tramite le REST API di WordPress.

---

## Endpoints

### 1. Aggiungi elemento alla wishlist

- **Endpoint:** `/api/v1/wishlist/add`
- **Metodo:** `POST`
- **Body JSON:**
    ```json
    {
      "user_token": "TOKEN_UTENTE",
      "element_id": "ID_PRODOTTO_O_HASH_ANNUNCIO"
    }
    ```
- **Risposte:**
    - `201 Created`  
      `{"status": "Annuncio aggiunto alla wishlist"}`  
      `{"status": "Prodotto aggiunto alla wishlist"}`
    - `400 Bad Request`  
      `{"status": "I parametri sono inesistenti"}`
    - `401 Unauthorized`  
      `{"status": "ID utente non valido"}`
    - `409 Conflict`  
      `{"status": "L'elemento è già presente nella wishlist"}`
    - `422 Unprocessable Entity`  
      `{"status": "I parametri sono vuoti"}`
    - `500 Internal Server Error`  
      `{"status": "Si è verificato un errore"}`

---

### 2. Rimuovi elemento dalla wishlist

- **Endpoint:** `/api/v1/wishlist/remove`
- **Metodo:** `DELETE`
- **Body JSON:**
    ```json
    {
      "user_token": "TOKEN_UTENTE",
      "element_id": "ID_PRODOTTO_O_HASH_ANNUNCIO"
    }
    ```
- **Risposte:**
    - `200 OK`  
      `{"status": "Elemento rimosso dalla wishlist"}`
    - `400 Bad Request`  
      `{"status": "I parametri sono inesistenti"}`
    - `401 Unauthorized`  
      `{"status": "ID utente non valido"}`
    - `404 Not Found`  
      `{"status": "L'elemento non è presente nella wishlist"}`
    - `422 Unprocessable Entity`  
      `{"status": "I parametri sono vuoti"}`

---

### 3. Recupera la wishlist dell'utente

- **Endpoint:** `/api/v1/wishlist/read/all`
- **Metodo:** `GET`
- **Query params:**
    - `user_token` (string) - Token dell'utente autenticato

- **Risposte:**
    - `200 OK`  
      Array di elementi della wishlist:
      ```json
      [
        {
          "type": "advertisement",
          "element": {
            "ad_name": "...",
            "link_cover": "...",
            "price": 0,
            "sale_price": 0,
            "on_sale": "T",
            "gift": "F"
          }
        },
        {
          "type": "product",
          "element": {
            "main_image": "...",
            "product_id": 123,
            "name": "...",
            "price": 0,
            "is_on_sale": false,
            "sale_price": 0,
            "add_to_cart_url": "..."
          }
        }
      ]
      ```
    - `400 Bad Request`  
      `{"status": "I parametri sono inesistenti"}`
    - `401 Unauthorized`  
      `{"status": "ID utente non valido"}`
    - `404 Not Found`  
      `{"status": "La wishlist è vuota"}`
    - `422 Unprocessable Entity`  
      `{"status": "I parametri sono vuoti"}`
    - `500 Internal Server Error`  
      `{"status": "Si è verificato un errore"}`

---

## Note tecniche

- **Autenticazione:**  
  Tutte le chiamate richiedono il parametro `user_token` valido.
- **element_id:**  
  Può essere un ID numerico (prodotto WooCommerce) o una stringa/hash (annuncio custom).
- **Codici di ritorno:**  
  Gli status HTTP sono coerenti con lo standard REST (400, 401, 404, 409, 422, 500, 201, 200).

---

## Esempio di risposta per una wishlist

```json
[
  {
    "type": "advertisement",
    "element": {
      "ad_name": "Cucciolo in regalo",
      "link_cover": "https://...",
      "price": 0,
      "sale_price": 0,
      "on_sale": "T",
      "gift": "T"
    }
  },
  {
    "type": "product",
    "element": {
      "main_image": "https://...",
      "product_id": 11277,
      "name": "Crocchette per cani",
      "price": 19.99,
      "is_on_sale": false,
      "sale_price": 0,
      "add_to_cart_url": "https://..."
    }
  }
]
```

---

**Per domande o segnalazioni:**  
Contatta lo sviluppatore del tema Petbuy.