export class SimpleIndexedDB {
    constructor(dbName, storeName, dbVersion = 1) {
        this.dbName = dbName;
        this.storeName = storeName;
        this.dbVersion = dbVersion;
        this.db = null;
    }

    // Inizializza la connessione al database
    async init() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.dbVersion);

            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                if (!db.objectStoreNames.contains(this.storeName)) {
                    db.createObjectStore(this.storeName, { keyPath: 'id' });
                }
            };

            request.onsuccess = (event) => {
                this.db = event.target.result;
                resolve();
            };

            request.onerror = (event) => {
                reject(`Errore nell'apertura del database: ${event.target.errorCode}`);
            };
        });
    }

    // Crea un nuovo record
    async create(record) {
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.storeName], 'readwrite');
            const store = transaction.objectStore(this.storeName);
            const request = store.add(record);

            request.onsuccess = () => {
                resolve('Record aggiunto con successo.');
            };

            request.onerror = (event) => {
                reject(`Errore nell'aggiungere il record: ${event.target.error}`);
            };
        });
    }

    // Legge un record per chiave
    async read(id) {
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.storeName], 'readonly');
            const store = transaction.objectStore(this.storeName);
            const request = store.get(id);

            request.onsuccess = (event) => {
                resolve(event.target.result);
            };

            request.onerror = (event) => {
                reject(`Errore nella lettura del record: ${event.target.error}`);
            };
        });
    }

    // Aggiorna un record esistente
    async update(record) {
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.storeName], 'readwrite');
            const store = transaction.objectStore(this.storeName);
            const request = store.put(record);

            request.onsuccess = () => {
                resolve('Record aggiornato con successo.');
            };

            request.onerror = (event) => {
                reject(`Errore nell'aggiornare il record: ${event.target.error}`);
            };
        });
    }

    // Elimina un record per chiave
    async delete(id) {
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.storeName], 'readwrite');
            const store = transaction.objectStore(this.storeName);
            const request = store.delete(id);

            request.onsuccess = () => {
                resolve('Record eliminato con successo.');
            };

            request.onerror = (event) => {
                reject(`Errore nell'eliminare il record: ${event.target.error}`);
            };
        });
    }

    // Recupera tutti i record
    async getAll() {
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([this.storeName], 'readonly');
            const store = transaction.objectStore(this.storeName);
            const request = store.getAll();

            request.onsuccess = (event) => {
                resolve(event.target.result);
            };

            request.onerror = (event) => {
                reject(`Errore nel recupero di tutti i record: ${event.target.error}`);
            };
        });
    }

    // Chiude la connessione al database
    close() {
        if (this.db) {
            this.db.close();
        }
    }

    // Cancella l'intero database
    static deleteDatabase(dbName) {
        return new Promise((resolve, reject) => {
            const request = indexedDB.deleteDatabase(dbName);

            request.onsuccess = () => {
                resolve(`Database ${dbName} eliminato con successo.`);
            };

            request.onerror = (event) => {
                reject(`Errore nell'eliminare il database: ${event.target.error}`);
            };

            request.onblocked = () => {
                reject('Eliminazione del database bloccata.');
            };
        });
    }
}
