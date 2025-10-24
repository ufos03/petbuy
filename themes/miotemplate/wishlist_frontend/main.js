import { SimpleIndexedDB } from "../../../indexedDB/main";
import { wishlistElement } from "./wishlistElement";

// 1. Definiamo la variabile, ma NON la inizializziamo asincronamente qui.
const localWishlist = new SimpleIndexedDB('localWishlistDB', 'wishlistStore');

/**
 * @param {wishlistElement} element
 */
async function addToLocalWishlist(element) {
    if (!(element instanceof wishlistElement))
        return { success: false, error: "Elemento non valido" };
    if (!(localWishlist instanceof SimpleIndexedDB))
        return { success: false, error: "Si è verificato un errore con il database" };

    // Controllo duplicati
    const all = await localWishlist.getAll();
    if (all.some(el => el.id === element.id))
        return { success: false, error: "Elemento già presente in wishlist" };

    try {
        await localWishlist.create(element);
        return { success: true };
    } catch (error) {
        return { success: false, error: error.message || String(error) };
    }
}

async function removeFromLocalWishlist(elementId) {
    if (typeof elementId !== "string" && typeof elementId !== "number")
        return { success: false, error: "ID non valido" };
    if (!elementId)
        return { success: false, error: "ID mancante o vuoto" };
    if (!(localWishlist instanceof SimpleIndexedDB))
        return { success: false, error: "Si è verificato un errore con il database" };

    try {
        await localWishlist.delete(elementId);
        return { success: true };
    } catch (error) {
        return { success: false, error: error.message || String(error) };
    }
}

async function getAllLocalWishlist() {
    try {
        const items = await localWishlist.getAll();
        return { success: true, items };
    } catch (error) {
        return { success: false, error: error.message || String(error) };
    }
}

async function pushAllWishlistFromServer(userToken) {
    if (!userToken)
        return { success: false, error: "I parametri non sono validi" };

    const allElements = await getAllLocalWishlist();
    if (allElements.success === false)
        return { success: false, error: allElements.error };

    const items = allElements.items;
    if (items.length === 0)
        return { success: false, error: "La wishlist locale è vuota" };

    let results = [];
    for (const item of items) {
        try {
            const response = await fetch('/api/v1/wishlist/add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    user_token: userToken,
                    element_id: item.id // <-- qui usi sempre .id
                })
            });
            const data = await response.json();
            results.push({ id: item.id, success: response.ok, serverResponse: data });
        } catch (error) {
            results.push({ id: item.id, success: false, error: error.message || String(error) });
        }
    }

    return { success: true, results };
}

// Esporta per test HTML se serve
window.isElementInLocalWishlist = isElementInLocalWishlist;
window.addToLocalWishlist = addToLocalWishlist;
window.removeFromLocalWishlist = removeFromLocalWishlist;
window.getAllLocalWishlist = getAllLocalWishlist;
window.pushAllWishlistFromServer = pushAllWishlistFromServer;
window.wishlistElement = wishlistElement;



// Eseguiamo l'inizializzazione asincrona in un blocco separato.
// Le funzioni sono già su window e useranno 'localWishlist' quando sarà inizializzato.
(async () => {
    try {
        await localWishlist.init();
        console.log('[Wishlist] IndexedDB inizializzato con successo.');
    } catch (error) {
        console.error('[Wishlist] Errore inizializzazione IndexedDB:', error);
        // Le funzioni continueranno a fallire con l'errore del DB, 
        // ma almeno il codice non darà ReferenceError.
    }
})();


