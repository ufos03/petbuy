# Wishlist Frontend – Documentazione

## Indice
- [Introduzione](#introduzione)
- [Struttura dei file](#struttura-dei-file)
- [Funzionalità principali](#funzionalità-principali)
- [Utilizzo delle funzioni](#utilizzo-delle-funzioni)
- [Esempi di utilizzo](#esempi-di-utilizzo)
- [Note tecniche](#note-tecniche)

---

## Introduzione

Questo modulo gestisce la **wishlist** lato frontend per il tema WordPress _miotemplate_.  
Permette di aggiungere, rimuovere, leggere e sincronizzare elementi della wishlist tra il database locale del browser (IndexedDB) e il server tramite API REST.

---

## Struttura dei file

- **main.js**  
  Contiene tutte le funzioni per la gestione della wishlist locale e la sincronizzazione con il server.
- **wishlistElement.js**  
  Definisce la classe `wishlistElement`, che rappresenta un elemento della wishlist.
- **indexedDB/main.js**  
  (Non incluso qui) Gestisce l’accesso a IndexedDB tramite la classe `SimpleIndexedDB`.

---

## Funzionalità principali

### 1. **Aggiunta di un elemento alla wishlist locale**
- Controlla che l’elemento sia valido e non duplicato.
- Salva l’elemento in IndexedDB.

### 2. **Rimozione di un elemento dalla wishlist locale**
- Rimuove l’elemento tramite il suo ID.

### 3. **Lettura di tutti gli elementi della wishlist locale**
- Restituisce tutti gli elementi salvati in IndexedDB.

### 4. **Push di tutti gli elementi locali al server**
- Invia ogni elemento locale all’API REST `/wp-json/api/v1/wishlist/add`.
- Restituisce il risultato di ogni operazione.

---

## Utilizzo delle funzioni

Tutte le funzioni sono **asincrone** e restituiscono un oggetto `{ success, ... }`.

### `addToLocalWishlist(element)`
Aggiunge un elemento alla wishlist locale.

- **Parametri:**  
  `element` – Istanza di `wishlistElement`
- **Return:**  
  `{ success: true }` oppure `{ success: false, error: ... }`

---

### `removeFromLocalWishlist(elementId)`
Rimuove un elemento dalla wishlist locale tramite ID.

- **Parametri:**  
  `elementId` – Stringa o numero
- **Return:**  
  `{ success: true }` oppure `{ success: false, error: ... }`

---

### `getAllLocalWishlist()`
Restituisce tutti gli elementi della wishlist locale.

- **Return:**  
  `{ success: true, items: [...] }` oppure `{ success: false, error: ... }`

---

### `pushAllWishlistFromServer(userToken)`
Invia tutti gli elementi locali al server tramite API REST.

- **Parametri:**  
  `userToken` – Token utente valido
- **Return:**  
  `{ success: true, results: [...] }`  
  Ogni elemento di `results` contiene `{ id, success, serverResponse }`

---

## Esempi di utilizzo

```javascript
// Creazione di un elemento
const el = new wishlistElement(
    "product",
    "123",
    "Nome prodotto",
    "https://img.example.com/cover.jpg",
    19.99,
    14.99,
    true,
    false,
    "https://petbuy.local/cart/add/123"
);

// Aggiunta alla wishlist locale
await addToLocalWishlist(el);

// Rimozione dalla wishlist locale
await removeFromLocalWishlist("123");

// Lettura di tutti gli elementi
const { items } = await getAllLocalWishlist();

// Push di tutti gli elementi al server
const pushResult = await pushAllWishlistFromServer("USER_TOKEN");
```

---

## Note tecniche

- **Endpoint API:**  
  L’endpoint corretto per il push è `/wp-json/api/v1/wishlist/add` (metodo POST).
- **IndexedDB:**  
  Gli elementi sono salvati localmente tramite la classe `SimpleIndexedDB`.
- **Esportazione funzioni:**  
  Le funzioni principali sono esportate su `window` per essere richiamate da HTML o altri script.
- **Classe `wishlistElement`:**  
  Ogni elemento deve essere un’istanza di questa classe per essere aggiunto correttamente.

---

## Dipendenze

- `SimpleIndexedDB` (modulo custom per IndexedDB)
- `wishlistElement` (classe per rappresentare un elemento della wishlist)

---

## Autore

Petbuy – Team sviluppo  
Giugno 2025
