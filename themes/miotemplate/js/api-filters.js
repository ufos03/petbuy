/**
 * api-filters.js
 * Gestisce tre cataloghi prodotti tramite API (advertisements/mixed/products) in tre sezioni distinte
 * e aggiorna un unico elemento di conteggio nel DOM in base alla sezione attiva.
 * * FIX API: Gestione della sintassi filtro standard (category=Nome) per tutti gli endpoint.
 * * FIX API: Tentativo di caricare le categorie dinamicamente dall'endpoint PRODUCTS (da verificare).
 * * NOVITÀ: Il pulsante del carrello è rimosso per l'API ADVERTISEMENTS.
 */

// =================================================================
// 1. IMPOSTAZIONI E VARIABILI GLOBALI
// =================================================================
const API_BASE_URL = 'https://petbuy-local.ns0.it:8080/wp-json/api/v1/';
const PER_PAGE = 12; // Prodotti per pagina
const PRICE_RANGE_FALLBACK = 5000; // Valore massimo di fallback se l'API non lo fornisce
const CART_URL = '/carrello/'; // DEFINISCI QUI L'URL CORRETTO DEL TUO CARRELLO

// Endpoint API
const ENDPOINTS = {
    ADVERTISEMENTS: 'advertisements/read/all',
    MIXED: 'mixed/read/all',
    PRODUCTS: 'products/read/all'
};

// *** MAPPATURA CHIAVI DATI API (CRITICO PER IL CARICAMENTO E LE CATEGORIE) ***
// Definiamo la chiave in cui si trova l'array dei prodotti per ogni endpoint.
const API_DATA_KEYS = {
    [ENDPOINTS.ADVERTISEMENTS]: 'content', // Usiamo 'content' anche per Advertisements (ipotesi forte)
    [ENDPOINTS.MIXED]: 'mixed',
    // FIX: La chiave dell'array prodotti è "content"
    [ENDPOINTS.PRODUCTS]: 'content'
};

const SORT_OPTIONS = {
  default: { orderby: 'menu_order', order: 'asc' },
  popularity: { orderby: 'popularity', order: 'desc' },
  rating: { orderby: 'rating', order: 'desc' },
  date: { orderby: 'creation_date', order: 'desc' },
  price_asc: { orderby: 'price', order: 'asc' },
  price_desc: { orderby: 'price', order: 'desc' }
};

let currentSortKey = 'default';
// **************************************************************************

// Variabili globali per memorizzare lo stato di tutte le API.
let AD_loadedItemsCount = 0;
let AD_totalApiResults = 0;
let MX_loadedItemsCount = 0;
let MX_totalApiResults = 0;
let MX_lastApiItemsCount = 0;
let PR_loadedItemsCount = 0;
let PR_totalApiResults = 0;

// *** VARIABILI DI STATO DELLO SLIDER (PER MANTENERE IL RANGE SELEZIONATO) ***
let currentMinPriceFilter = 0;
let currentMaxPriceFilter = PRICE_RANGE_FALLBACK;
// **************************************************************************

// Variabili per le istanze dei manager (Globali per accesso da Applica/Reset)
let adManagerInstance;
let mxManagerInstance;
let prManagerInstance;

// assicurati anche che currentSortKey sia definito!
//let currentSortKey = 'default';

// *** VARIABILE DI STATO PER IL FILTRO GIFT ***
let isGiftFilterActive = false;

// Selettori HTML
const SELECTORS = {
    FILTERS_WRAPPER: '#api-filters-wrapper', // Selettore universale per i filtri
    AD_CONTAINER: '#contenuto-1a-desktop .product-list-container-js',
    AD_LOAD_MORE: '#contenuto-1a-desktop .load-more-btn-js',
    AD_COUNT: '#contenuto-conteggio .risultati-conteggio-js',

    MX_CONTAINER: '#contenuto-2a-desktop .product-list-container-js',
    MX_LOAD_MORE: '#contenuto-2a-desktop .load-more-btn-js',
    MX_COUNT: '#contenuto-conteggio .risultati-conteggio-js',

    PR_CONTAINER: '#contenuto-3a-desktop .product-list-container-js',
    PR_LOAD_MORE: '#contenuto-3a-desktop .load-more-btn-js',
    PR_COUNT: '#contenuto-conteggio .risultati-conteggio-js',
};

const MY_TEMPLATE_URI = (typeof window.MyApiSettings !== 'undefined' && window.MyApiSettings.templateUrl)
    ? window.MyApiSettings.templateUrl
    : '';


/**
 * Resetta i filtri di categoria (checkbox o radio) all'interno del wrapper filtri.
 * CORREZIONE: Usa window.MyApiSettings per sicurezza e verifica l'esistenza.
 */
function resetCategoryFilters() {
    // NUOVA CORREZIONE: Evita il ReferenceError assoluto controllando window
    const settings = typeof window.MyApiSettings !== 'undefined' ? window.MyApiSettings : null;
                     
    if (!settings || !settings.apiFiltersWrapper) {
        // Se non trova le impostazioni, esce in modo silenzioso.
        // Questo impedisce il ReferenceError e il TypeError.
        console.warn("[MyApiSettings] Variabile localizzata non ancora disponibile per resetCategoryFilters. Ignorato.");
        return; 
    }

    const wrapperSelector = settings.apiFiltersWrapper;
    const filterWrapper = document.querySelector(wrapperSelector);

    if (filterWrapper) {
        // Selezioniamo tutti gli input all'interno del wrapper che hanno l'attributo data-filter-type="category"
        const categoryInputs = filterWrapper.querySelectorAll('input[data-filter-type="category"]');
        
        categoryInputs.forEach(input => {
            if (input.type === 'checkbox' || input.type === 'radio') {
                input.checked = false;
            }
        });
    }
}

// =================================================================
// 2. FUNZIONE GLOBALE: AGGIUNGI AL CARRELLO (Logica di WooCommerce)
// =================================================================
function addToCart(product_id) {
    const CUSTOM_ACTION = 'custom_api_add_to_cart';

    const data = new URLSearchParams();
    data.append('action', CUSTOM_ACTION);
    data.append('product_id', product_id);
    data.append('quantity', 1);

    const ajaxUrl = window.ajaxurl || '/wp-admin/admin-ajax.php';

    fetch(ajaxUrl, {
        method: 'POST',
        body: data
    })
    .then(response => {
        if (!response.ok) {
            console.error(`Errore AJAX: Risposta HTTP non OK: ${response.status}`);
            throw new Error(`Errore HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            //alert('Prodotto aggiunto al carrello!');
			showNotification('success', 'Prodotto aggiunto al carrello!');
        } else {
            const failedId = result.data && result.data.failed_id_received ? ` (ID: ${result.data.failed_id_received})` : '';
            const message = result.data && result.data.message ? result.data.message : 'Errore sconosciuto.';

            console.error('Errore AJAX: Impossibile aggiungere il prodotto al carrello (WC fallito o ID non valido).', {
                data: { message: message + failedId, success: false }
            });
            //alert(`Errore nell'aggiunta al carrello: ${message}`);
			showNotification('error', 'Errore nell\'aggiunta del prodotto. Riprova.');
        }
    })
    .catch(error => {
        console.error('Errore AJAX di connessione o HTTP:', error);
        alert('Impossibile connettersi al server per aggiungere il prodotto. Controllare URL/CORS.');
    });
}


/**
 * Esegue una singola chiamata API per ottenere il conteggio totale dei regali
 * dall'unico endpoint che lo supporta (ADVERTISEMENTS).
 * @param {string} targetSelector - Il selettore dello span HTML da aggiornare (es: '#gift-count-span').
 */
 async function updateGiftCount(targetSelector) {
   if (!isGiftFilterActive) {
     console.log('[DEBUG] Conteggio Gift ignorato: filtro non attivo');
     return;
   }

   const targetElement = document.querySelector(targetSelector);
   if (!targetElement) {
     console.warn('[DEBUG] Elemento target non trovato:', targetSelector);
     return;
   }

   const apiUrl = `${API_BASE_URL}${ENDPOINTS.ADVERTISEMENTS}?page=1&per_page=12&is_gift=true`;
console.log('[DEBUG] Chiamata conteggio Gift →', apiUrl);

try {
  const response = await fetch(apiUrl);
if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

const data = await response.json();
console.log('[DEBUG] Risposta API Gift:', data);

let totalItems = 0;

if (data.content && Array.isArray(data.content)) {
  totalItems = data.content.filter(item => {
  const val = String(item.is_gift).toUpperCase();
  return val === 'T';
}).length;

  data.content.forEach((item, index) => {
  console.log(`[DEBUG] Annuncio ${index}: is_gift =`, item.is_gift);
});


} else if (data.total_results !== undefined) {
  totalItems = parseInt(data.total_results, 10);
}

targetElement.textContent = totalItems > 0 ? totalItems : '0';
console.log('[DEBUG] Totale annunci regalo filtrati:', totalItems);

} catch (error) {
  console.error('❌ Errore nel conteggio Gift:', error);
  targetElement.textContent = '0';
}

 }




// =================================================================
// X. GESTIONE DELLE NOTIFICHE IN-PAGE (FINALE)
// =================================================================

/**
 * Mostra una notifica personalizzata, scrolla al top e include un link al carrello.
 * @param {string} type - Tipo di notifica ('success' o 'error').
 * @param {string} message - Il messaggio principale da visualizzare.
 * @param {number} duration - Durata in millisecondi (default 3000ms).
 */
function showNotification(type, message, duration = 5000) {
    // Riferimento al contenitore HTML
    const container = document.getElementById('notification-container');

    if (!container) {
        console.error("Contenitore #notification-container non trovato. Uso alert() come fallback.");
        alert(message);
        return;
    }

    // 1. Crea l'elemento notifica
    const notification = document.createElement('div');
    notification.className = `custom-notification ${type}`;

    let contentHTML = `
        <div>${message}</div>
    `;

    // Aggiungi il link se � una notifica di successo E se CART_URL � stata definita
    if (type === 'success' && typeof CART_URL !== 'undefined' && CART_URL) {
        contentHTML += `
            <a href="${CART_URL}" style="color: inherit; font-weight: bold; text-decoration: underline; display: block; margin-top: 5px;">
                Vai al carrello ?
            </a>
        `;
    }

    notification.innerHTML = contentHTML;

    // 2. Aggiungi la notifica al contenitore
    container.appendChild(notification);

    // 3. Forza lo scroll al top della pagina (come richiesto)
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });

    // 4. Visualizza la notifica (dopo un breve timeout per l'animazione)
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);

    // 5. Rimuovi la notifica dopo la durata specificata
    setTimeout(() => {
        notification.classList.remove('show');

        // Rimuovi l'elemento DOM dopo la fine della transizione
        notification.addEventListener('transitionend', () => {
            notification.remove();
        }, { once: true });

        // Timeout di sicurezza per la rimozione
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 600);

    }, duration);
}


// =================================================================
// 3. FUNZIONE FABBRICA (ProductListManager)
// =================================================================

function ProductListManager(endpoint, selectors) {

    let currentPage = 1;

    const isAdManager = endpoint === ENDPOINTS.ADVERTISEMENTS;
    const isMxManager = endpoint === ENDPOINTS.MIXED;
    const isPrManager = endpoint === ENDPOINTS.PRODUCTS;

    // Funzioni getter e setter per i contatori globali
    const currentLoadedItems = () => {
        if (isAdManager) return AD_loadedItemsCount;
        if (isMxManager) return MX_loadedItemsCount;
        if (isPrManager) return PR_loadedItemsCount;
        return 0;
    };
    const currentTotalResults = () => {
        if (isAdManager) return AD_totalApiResults;
        if (isMxManager) return MX_totalApiResults;
        if (isPrManager) return PR_totalApiResults;
        return 0;
    };

    const updateLoadedItems = (value) => {
        if (isAdManager) { AD_loadedItemsCount = value; }
        else if (isMxManager) { MX_loadedItemsCount = value; }
        else if (isPrManager) { PR_loadedItemsCount = value; }
    };
    const updateTotalResults = (value) => {
        if (isAdManager) { AD_totalApiResults = value; }
        else if (isMxManager) { MX_totalApiResults = value; }
        else if (isPrManager) { PR_totalApiResults = value; }
    };
    const updateLastApiItemsCount = (value) => {
        if (isMxManager) { MX_lastApiItemsCount = value; }
    };

    const containerElement = document.querySelector(selectors.container);
    const loadMoreButton = document.querySelector(selectors.loadMore);
    const filterWrapper = document.querySelector(SELECTORS.FILTERS_WRAPPER);

    if (!containerElement || !loadMoreButton) {
        return;
    }


	//


    // --- FUNZIONI DI SUPPORTO ---

    function cartClickHandler(e) {
        e.preventDefault();
        const productId = e.currentTarget.getAttribute('data-product-id');

        // VERIFICA PER L'API ADVERTISEMENTS E ID non validi
        if (productId === 'INVALID') {
             console.error(`Impossibile aggiungere al carrello: ID segnato come INVALIDO dall'API.`);
             alert('Errore: ID prodotto non valido o assente.');
             return;
        }

        if (productId && parseInt(productId) > 0) {
            addToCart(productId);
        } else {
             console.error(`Impossibile aggiungere al carrello: ID nullo o non numerico: ${productId}`);
             alert('Errore: ID prodotto non valido o mancante. Non posso aggiungere al carrello.');
        }
    }


/**
 * Costruisce l'URL completo della chiamata API, includendo paginazione,
 * filtri (categoria, prezzo, gift) e ordinamento, basandosi sulle variabili
 * di stato globali correnti (isGiftFilterActive, currentSortKey, ecc.).
 * * Il filtro GIFT viene aggiunto solo se l'endpoint � ADVERTISEMENTS.
 * * @param {number} pageNumber - Il numero di pagina corrente da caricare.
 * @returns {string} L'URL API finale.
 */
function buildApiUrl(pageNumber) {

    // 1. Inizializza l'URL base con paginazione
    let url = `${API_BASE_URL}${endpoint}?page=${pageNumber}&per_page=${PER_PAGE}`;

    // 2. Raccogli i filtri dalla UI (principalmente categorie)
    const filterData = collectFilterParams();
    const filters = filterData.standardParams;

    // 3. Processa e aggiungi i filtri standard all'URL
    const filterQuery = Object.keys(filters).map(key => {
        // Mappatura specifica per le categorie: category -> categories_slug
        if (key === 'category') {
            return `categories_slug=${encodeURIComponent(filters[key])}`;
        }
        // Altri filtri (es. input di testo)
        return `${key}=${encodeURIComponent(filters[key])}`;
    }).join('&');

    if (filterQuery) {
        url += `&${filterQuery}`;
    }

    // 4. Aggiungi il Filtro Price Range (stato globale dello slider)
    if (currentMaxPriceFilter > 0) {
        const priceRange = `${currentMinPriceFilter}-${currentMaxPriceFilter}`;
        url += `&price_range=${priceRange}`;
    }

    //  5. Aggiungi il Filtro GIFT (Condizionale: SOLO ADVERTISEMENTS)
    /*const supportsGiftFilter = (endpoint === ENDPOINTS.ADVERTISEMENTS);

    if (isGiftFilterActive && supportsGiftFilter) {
        // Usa la stringa API ESATTA richiesta: "is_gift"
        url += `&is_gift=true`;
    }*/

    // 6. Aggiungi la logica di Ordinamento (stato globale del selettore)
    const sortParams = SORT_OPTIONS[currentSortKey] || SORT_OPTIONS.default;
  	url += `&order_by=${sortParams.orderby}&order=${sortParams.order}`;

    console.log(`[API Call] Costruito URL: ${url}`);

    return url;
}





    function attachCartListeners() {
        // Attacca i listener solo ai pulsanti che esistono (quindi, non per advertisements)
        const cartBtns = containerElement.querySelectorAll('.add-to-cart-btn');
        cartBtns.forEach(btn => {
            btn.removeEventListener('click', cartClickHandler);
            btn.addEventListener('click', cartClickHandler);
        });
    }

    /**
     * Raccoglie tutti i parametri di filtro usando la sintassi standard (name=value).
     */
    function collectFilterParams() {
        const params = {};

        if (filterWrapper) {
             const filterInputs = filterWrapper.querySelectorAll('.api-filter-input');

             // Collezione temporanea per gestire checkbox e radio
             const groupedInputs = {};

             filterInputs.forEach(input => {
                 const apiValue = input.getAttribute('data-api-value') || input.value;

                 if (!apiValue || String(apiValue).trim() === '') return;

                 if (input.name === 'category' && input.type === 'checkbox' && input.checked) {
                     // *** GESTIONE CHECKBOX CATEGORIE ***
                     if (!groupedInputs['category']) {
                         groupedInputs['category'] = [];
                     }
                     groupedInputs['category'].push(apiValue);
                 }
                 else if ((input.type === 'radio' || input.type === 'checkbox') && input.checked) {
                     // Gestione di altri checkbox/radio singoli (se esistono)
                     params[input.name] = apiValue;
                 } else if (input.tagName === 'SELECT') {
                     // Gestione Select (se ne aggiungerai in futuro)
                     params[input.name] = apiValue;
                 }
                 else if (input.type !== 'radio' && input.type !== 'checkbox') {
                      // Gestione input text/slider
                      params[input.name] = apiValue.trim();
                 }
             });

             // Unisce i valori dei checkbox in una stringa separata da virgole
             if (groupedInputs['category'] && groupedInputs['category'].length > 0) {
                 // ** Assumiamo che la tua API possa accettare più categorie separate da virgola (category=cat1,cat2) **
                 // Se la tua API vuole solo una categoria per volta, dovrai cambiare questa logica.
                 params['category'] = groupedInputs['category'].join(',');
             }
        }

        return {
            standardParams: params,
            categoryParam: ''
        };
    }

    function updateCount(newItemsLoaded) {
        const prevLoaded = currentLoadedItems();

        updateLoadedItems(prevLoaded + newItemsLoaded);

        if (newItemsLoaded > 0 || prevLoaded > 0) {
            syncCountDisplay(selectors.container);
        }
    }

    function updateLoadMoreButton() {
        const loaded = currentLoadedItems();
        const total = currentTotalResults();
        const lastLoadedCount = isMxManager ? MX_lastApiItemsCount : (loaded % PER_PAGE);

        if (total > 0 && loaded >= total) {
            loadMoreButton.innerHTML = 'Non ci sono più prodotti da caricare.';
            loadMoreButton.disabled = true;
        }
        // Logica Fallback: Se total è 0 ma loaded è > 0 e non siamo all'ultima pagina parziale.
        else if (total === 0 && loaded > 0 && lastLoadedCount < PER_PAGE) {
            loadMoreButton.innerHTML = 'Non ci sono più prodotti da caricare.';
            loadMoreButton.disabled = true;
        }
        else if (total === 0 && loaded === 0) {
            loadMoreButton.innerHTML = 'Nessun prodotto disponibile.';
            loadMoreButton.disabled = true;
        }
        else {
            loadMoreButton.innerHTML = 'Carica altro ⭣';
            loadMoreButton.disabled = false;
        }
    }


    // --- FUNZIONE PRINCIPALE DI CARICAMENTO (Fetch) ---

    function fetchData(reset = false) {
        if (reset) {
            updateLoadedItems(0);
            updateTotalResults(0);
            currentPage = 1;
            containerElement.innerHTML = '';
        }

        const filterData = collectFilterParams();
        const filters = filterData.standardParams;

        const sortParams = SORT_OPTIONS[currentSortKey] || SORT_OPTIONS.default;
		let apiUrl = `${API_BASE_URL}${endpoint}?page=${currentPage}&per_page=${PER_PAGE}&order_by=${sortParams.orderby}&order=${sortParams.order}`;

		console.log(`[DEBUG] Endpoint attivo: ${endpoint}`);
		console.log(`[DEBUG] Ordinamento attivo: ${currentSortKey}`);
		console.log(`[DEBUG] Parametri ordinamento:`, sortParams);
		console.log(`[DEBUG] URL API generato: ${apiUrl}`);

		console.log(`[ORDINAMENTO] Chiave: ${currentSortKey}`);
		console.log(`[ORDINAMENTO] Parametri:`, sortParams);
		console.log(`[ORDINAMENTO] URL finale: ${apiUrl}`);

        const filterQuery = Object.keys(filters).map(key => `${key}=${encodeURIComponent(filters[key])}`).join('&');

        // La variabile category, se presente in filters, viene inclusa qui sotto correttamente
        if (filterQuery) {
            apiUrl += `&${filterQuery}`;
        }

        loadMoreButton.innerHTML = 'Caricamento...';
        loadMoreButton.disabled = true;

        console.log(`[${endpoint}] FETCH URL: ${apiUrl}`);

        fetch(apiUrl)
            .then(response => {
                if (!response.ok && response.status !== 404) {
                    throw new Error(`[${endpoint}] Errore HTTP: ${response.status}. Controllare CORS.`);
                }
                // Aggiunta robustezza per risposte non-JSON
                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    return response.text().then(text => {
                        console.error(`[${endpoint}] Risposta non JSON. Contenuto:`, text.substring(0, 100) + '...');
                        throw new Error(`Risposta API non valida (Status: ${response.status}). Contenuto non JSON.`);
                    });
                }

                return response.json();
            })
            .then(data => {

                //let products = data.content || data.mixed || data.data || data.products || [];
                //const newItemsLoaded = products.length;

				// Vecchio codice:
				// let products = data.content || data.mixed || data.data || data.products || [];

				// Nuovo codice (nella funzione fetchData):
				const dataKey = API_DATA_KEYS[endpoint] || 'content'; // Usa la chiave mappata o 'content' come fallback
				let products = data[dataKey] || data.content || data.products || [];

			console.log(`[DEBUG] Prodotti ricevuti (grezzi): ${products.length}`);
			console.log(`[DEBUG] Prezzi ricevuti:`, products.map(p => parseFloat(p.price)));
			console.log(`[DEBUG] Date ricevute:`, products.map(p => p.date || p.post_date || 'N/D'));

        // Filtro client-side per il prezzo (solo per PRODUCTS)
        if (isPrManager && currentMinPriceFilter >= 0 && currentMaxPriceFilter > 0) {
          products = products.filter(item => {
            const price = parseFloat(item.price);
            return !isNaN(price) && price >= currentMinPriceFilter && price <= currentMaxPriceFilter;
          });
        }


			//  LOG DI VERIFICA
	const isAdManager = (endpoint === ENDPOINTS.ADVERTISEMENTS); // Assicurati di avere accesso a 'endpoint' e 'ENDPOINTS'
	console.log(`[Manager Annunci] Stato Filtro Gift: ${isGiftFilterActive}`);
	console.log(`[Manager Annunci] E' il Manager Annunci: ${isAdManager}`);
	console.log(`[Manager Annunci] Condizione Filtro Attiva: ${isAdManager && isGiftFilterActive}`);

				//  INSERISCI LA LOGICA DEL FILTRO LATO CLIENT QUI
				if (isAdManager && isGiftFilterActive) {

					products = products.filter(item => {
						// Il campo � "is_gift"
						// Il valore � la stringa "T"
						return item.is_gift === 'T'; // <-- Verifica pi� diretta
					});

					// 2. Correzione Mappatura (da applicare solo agli elementi filtrati)
					products.forEach(item => {
						 // Applica la correzione solo se � un annuncio Gift (cio�, se � rimasto nell'array)
						 if (item.is_gift === 'T') {
							 // Risolve "Titolo non disponibile"
							 item.name = item.title;

							 // Assicura che l'ID alfanumerico sia una stringa valida
							 item.id = String(item.id).trim();
						 }
					});
				}
				//  FINE LOGICA FILTRO LATO CLIENT

				// Ricalcola la lunghezza dopo il filtro locale
				const newItemsLoaded = products.length;

                // *** LOGICA DI AGGIORNAMENTO CONTEGGIO TOTALE ***
                if (data.total_results !== undefined && data.total_results !== null) {
                    let newTotal = parseInt(data.total_results);

                    if (reset && newTotal === PER_PAGE && newItemsLoaded > 0 && newItemsLoaded < PER_PAGE) {
                         console.warn(`[${endpoint}] Totale API ambiguo (${newTotal}). Forza total_results a loaded (${newItemsLoaded}).`);
                         updateTotalResults(newItemsLoaded);
                    } else {
                         updateTotalResults(newTotal);
                    }
                } else if (data['X-WP-Total'] !== undefined) {
                    updateTotalResults(parseInt(data['X-WP-Total']));
                }

                updateCount(newItemsLoaded);

                if (isMxManager) {
                    updateLastApiItemsCount(newItemsLoaded);
                }

                // *** LOGICA: INIZIALIZZAZIONE SLIDER DINAMICA (Solo al primo carico) ***
                if (reset) {
                    // Preleva il range di prezzo dall'API anche se l'API non è l'endpoint PRODUCTS
                    const apiMin = data.min_price !== undefined ? parseFloat(data.min_price) : 0;
                    const apiMax = data.max_price !== undefined && parseFloat(data.max_price) > 0 ? parseFloat(data.max_price) : PRICE_RANGE_FALLBACK;

                    if (window.noUiSlider) {
                         // Passa i valori assoluti Min/Max e lascia che la funzione usi lo stato globale per "start"
                         initPriceSlider(apiMin, apiMax);
                    }
                }


				console.log(`[DEBUG] Prodotti pronti per il rendering: ${newItemsLoaded}`);

                // --- Rendering dei Prodotti (Layout 3 per riga) ---
                if (newItemsLoaded > 0) {

                    const euroFormatter = new Intl.NumberFormat('it-IT', { style: 'currency', currency: 'EUR', minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    const cuoreSvgUrl = MY_TEMPLATE_URI + '/ufficiale/cuore-con-sfondo.svg';
                    const carrelloSvgUrl = MY_TEMPLATE_URI + '/ufficiale/carrello-con-sfondo.svg';

                    if (reset) {
                        containerElement.innerHTML = '';
                    }

                    products.forEach(item => {

    // ESTRAZIONE VARIABILI
    // ?? NUOVO: Usa 'title' come prima opzione, poi 'name' come fallback
	const productTitle = item.title || item.name || 'Titolo non disponibile';

    const priceValue = parseFloat(item.price);
    const displayPrice = isNaN(priceValue) ? 'Prezzo non disponibile' : euroFormatter.format(priceValue);

    // *** MODIFICA CHIAVE QUI: cerca in item.image.url ***
    // 1. Prova item.image.url
    // 2. Fallback su item.cover
    // 3. Fallback su placeholder URL
    const imageUrl = (item.image && item.image.url) ? item.image.url : item.cover || 'https://via.placeholder.com/200';

    const imageAlt = productTitle; // Usa il titolo come alt text
    const permalink = item.permalink || '#';

    // *** LOGICA DI ESTRAZIONE E VALIDAZIONE ID (MIGLIORATA) ***
    let productID = '0';

    // 1. Cerca l'ID nel campo standard 'id'
    if (item.id) {
        productID = String(item.id);
    }

    // 2. Controllo critico: ID non valido o assente
    if (!productID || productID === '0' || productID === 'INVALID') {
        if (isAdManager) {
            console.warn(`[${endpoint}] Warning Dati: Chiave 'id' assente per l'elemento "${productTitle}".`);
            productID = '0';
        } else {
            console.error(`[${endpoint}] Errore Critico: ID Prodotto non valido o assente per l'elemento "${productTitle}". ID ricevuto: ${productID}`);
            productID = 'INVALID';
        }
    }

    const productInnerHtml = `
        <div class="scheda-prodotto">
            <a href="${permalink}">
				<div class="image-wrapper">
                	<img src="${imageUrl}" alt="${imageAlt}">
				</div>
            </a>
            <div class="container">
                <div class="row align-items-start pb-3">
                    <div class="col-md-6">
                        <div class="noacapo fortablet2 sideshop">${productTitle}</div>
                        <div class="price-container">${displayPrice}</div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">

                        ${!isAdManager ? `
                            <img src="${carrelloSvgUrl}"
                                class="cornetta-and-co me-2 add-to-cart-btn"
                                alt="Immagine carrello"
                                data-product-id="${productID}"
                                style="cursor: pointer;">
                        ` : ''}
                        <img src="${cuoreSvgUrl}" class="cornetta-and-co" alt="Immagine cuore">
                    </div>
                </div>
            </div>
        </div>
    `;

    const productDiv = document.createElement('div');
    productDiv.className = 'col-4 col-md-4';

    productDiv.innerHTML = productInnerHtml;
    containerElement.appendChild(productDiv);
});

                    attachCartListeners();
                } else if (reset) {
                    containerElement.innerHTML = '<p style="text-align: center; width: 100%;">Nessun risultato trovato con i filtri selezionati.</p>';
                }

                updateLoadMoreButton();

            })
            .catch(error => {
                console.error(error);
                containerElement.innerHTML = `<p style="color: red; text-align: center; width: 100%;">Errore [${endpoint}]: Impossibile caricare i dati. Controllare URL/CORS/Risposta API.</p>`;
                loadMoreButton.innerHTML = 'Errore nel caricamento.';
                loadMoreButton.disabled = true;
            });
    }

    // --- GESTIONE EVENTI INTERNI ---

    loadMoreButton.addEventListener('click', (e) => {
        e.preventDefault();
        if (loadMoreButton.disabled) return;
        currentPage++;
        fetchData(false);
    });

    return {
        init: () => fetchData(true),
        fetchData: fetchData
    };
}


// =================================================================
// 4. FUNZIONE GLOBALE DI SINCRONIZZAZIONE E SUPPORTO
// =================================================================

function resetAllGlobalCounts() {
    AD_loadedItemsCount = 0;
    AD_totalApiResults = 0;
    MX_loadedItemsCount = 0;
    MX_totalApiResults = 0;
    PR_loadedItemsCount = 0;
    PR_totalApiResults = 0;
}

function getActiveManagerInstance() {
    const adContainer = document.querySelector('#contenuto-1a-desktop');
    const mxContainer = document.querySelector('#contenuto-2a-desktop');
    const prContainer = document.querySelector('#contenuto-3a-desktop');

    if (adContainer && window.getComputedStyle(adContainer).display !== 'none') {
        return adManagerInstance;
    } else if (mxContainer && window.getComputedStyle(mxContainer).display !== 'none') {
        return mxManagerInstance;
    } else if (prContainer && window.getComputedStyle(prContainer).display !== 'none') {
        return prManagerInstance;
    } else {
        return adManagerInstance;
    }
}

/**
 * Aggiorna l'unico elemento di conteggio nel DOM.
 */
function syncCountDisplay(activeManagerContainerSelector) {
    const resultsCountElement = document.querySelector(SELECTORS.AD_COUNT);
    if (!resultsCountElement) return;

    let total, loaded;
    let targetEndpoint;
    let targetContainerElement;

    // 1. DEDUZIONE DELL'ENDPOINT E CONTENITORE
    if (activeManagerContainerSelector && activeManagerContainerSelector.includes('contenuto-1a')) {
        targetEndpoint = ENDPOINTS.ADVERTISEMENTS;
        targetContainerElement = document.querySelector('#contenuto-1a-desktop');
    } else if (activeManagerContainerSelector && activeManagerContainerSelector.includes('contenuto-2a')) {
        targetEndpoint = ENDPOINTS.MIXED;
        targetContainerElement = document.querySelector('#contenuto-2a-desktop');
    } else if (activeManagerContainerSelector && activeManagerContainerSelector.includes('contenuto-3a')) {
        targetEndpoint = ENDPOINTS.PRODUCTS;
        targetContainerElement = document.querySelector('#contenuto-3a-desktop');
    } else {
        const activeInstance = getActiveManagerInstance();
        if (!activeInstance) return;
        targetEndpoint = activeInstance.endpoint;
        if (targetEndpoint === ENDPOINTS.ADVERTISEMENTS) targetContainerElement = document.querySelector('#contenuto-1a-desktop');
        else if (targetEndpoint === ENDPOINTS.MIXED) targetContainerElement = document.querySelector('#contenuto-2a-desktop');
        else if (targetEndpoint === ENDPOINTS.PRODUCTS) targetContainerElement = document.querySelector('#contenuto-3a-desktop');
    }

    // 2. CONTROLLO CRITICO DI VISIBILITÀ: NON AGGIORNARE SE NASCOSTO
    if (!targetContainerElement || window.getComputedStyle(targetContainerElement).display === 'none') {
        return;
    }

    // 3. RECUPERO DEI DATI
    if (targetEndpoint === ENDPOINTS.ADVERTISEMENTS) {
        total = AD_totalApiResults;
        loaded = AD_loadedItemsCount;
    } else if (targetEndpoint === ENDPOINTS.MIXED) {
        total = MX_totalApiResults;
        loaded = MX_loadedItemsCount;
    } else if (targetEndpoint === ENDPOINTS.PRODUCTS) {
        total = PR_totalApiResults;
        loaded = PR_loadedItemsCount;
    } else {
        return;
    }

    // 4. AGGIORNAMENTO DEL DOM
    let countText;

    if (total > PER_PAGE) {
        countText = `${loaded} di ${total} Risultati`;
    }
    else if (loaded > 0) {
        countText = `${loaded} Risultati`;
    }
    else {
        countText = `Nessun risultato trovato.`;
    }

    resultsCountElement.textContent = countText;
}


// =================================================================
// 5A. FUNZIONE DI CARICAMENTO CATEGORIE (ESTRAZIONE E INIZIO CONTEGGIO)
// =================================================================

function fetchCategories() {
    const CATEGORIES_ENDPOINT = ENDPOINTS.PRODUCTS;
    const categoriesUrl = `${API_BASE_URL}${CATEGORIES_ENDPOINT}?per_page=1000`;
    const categoryContainer = document.getElementById('category-checkbox-list');

    if (!categoryContainer || categoryContainer.querySelector('input[type="checkbox"]')) {
        return;
    }

    categoryContainer.innerHTML = '<p>Caricamento categorie in corso...</p>';
    const dataKey = API_DATA_KEYS[CATEGORIES_ENDPOINT] || 'content';

    fetch(categoriesUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Errore HTTP: ${response.status} durante il recupero delle categorie.`);
            }
            return response.json();
        })
        .then(data => {

            const products = data[dataKey] || data.content || data.products || data.data || [];
            const uniqueCategories = new Map();

            products.forEach(product => {
                if (Array.isArray(product.categories) && product.categories.length > 0) {
                    const categoryData = product.categories[0];
                    const categoryName = categoryData.name;

                    if (categoryName && categoryName.trim()) {
                        // Memorizza il nome e l'URL immagine
                        uniqueCategories.set(categoryName.trim(), categoryData.image_url || '');
                    }
                }
            });

            const categories = Array.from(uniqueCategories, ([name, imageUrl]) => ({ name, imageUrl })).sort((a, b) => a.name.localeCompare(b.name));

            categoryContainer.innerHTML = '';

            if (categories.length > 0) {

                categories.forEach((category, index) => {
                    const categoryName = category.name;
                    const imageUrl = category.imageUrl || MY_TEMPLATE_URI + '/assets/icons/default-icon.png'; // Usiamo SVG come default
                    const id = `cat-${index}-${categoryName.replace(/\s/g, '-')}`;

                    // --- 1. <div class="form-check category-item">
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'form-check category-item';

                    // --- 2. <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria1">
                    const input = document.createElement('input');
                    input.type = 'checkbox';
                    input.id = id;
                    input.name = 'category';
                    input.value = categoryName;
                    input.className = 'api-filter-input form-check-input me-2';
                    input.style.marginTop = '1px'; // Stile inline richiesto

                    // 🔥 AGGIUNGI QUI IL LISTENER 'CHANGE' 🔥
                    input.addEventListener('change', autoApplyFilter);

                    // --- 3. <img src="..." class="category-icon-placeholder" alt=""/>
                    const icon = document.createElement('img');
                    icon.src = imageUrl;
                    icon.alt = categoryName;
                    icon.className = 'category-icon-placeholder';

                    // --- 4. <label class="form-check-label" for="categoria1">Categoria 1</label>
                    const label = document.createElement('label');
                    label.htmlFor = id;
                    label.textContent = categoryName;
                    label.className = 'form-check-label';

                    // --- 5. <span class="category-count float-end">123</span>
                    const countSpan = document.createElement('span');
                    countSpan.id = `cat-count-${categoryName.replace(/\s/g, '-')}`;
                    countSpan.className = 'category-count float-end';
                    countSpan.textContent = '...'; // Placeholder

                    // Assemblaggio nell'ordine richiesto
                    itemDiv.appendChild(input);
                    itemDiv.appendChild(icon);
                    itemDiv.appendChild(label);
                    itemDiv.appendChild(countSpan);

                    categoryContainer.appendChild(itemDiv);

                    // Avvia il conteggio
                    fetchCategoryCount(categoryName);
                });

                console.log(`[Categories] Checkbox creati. Avvio conteggio prodotti...`);

            } else {
                console.warn(`[Categories] Nessuna categoria valida trovata.`);
                categoryContainer.innerHTML = '<p>Nessuna categoria disponibile.</p>';
            }
        })
        .catch(error => {
            console.error('Errore critico durante il popolamento delle categorie:', error);
            categoryContainer.innerHTML = '<p style="color: red;">Errore nel caricamento categorie.</p>';
        });
}


// =================================================================
// 5Abis. FUNZIONE DI APPLICAZIONE AUTOMATICA DEL FILTRO (Globale)
// =================================================================

function autoApplyFilter() {
    console.log('[AutoFilter] Cambio Categoria Rilevato. Applico i filtri...');

    // Questa funzione riutilizza la logica già definita nel pulsante "Applica Filtri".

    // 1. Ottiene l'istanza del ProductListManager attivo (es. prManagerInstance)
    const activeManager = getActiveManagerInstance();

    // 2. Chiama fetchData(true) per ricaricare la lista e resettare la paginazione
    if (activeManager) {
        activeManager.fetchData(true);
    } else {
        console.warn('[AutoFilter] Impossibile trovare un ProductListManager attivo per applicare il filtro.');
    }
}


// =================================================================
// 5B. FUNZIONE DI INIZIALIZZAZIONE SLIDER PREZZO (noUiSlider)
// =================================================================

/**
 * Inizializza o aggiorna lo slider del prezzo con i valori di range forniti dall'API.
 * @param {number} apiMinPrice Il prezzo minimo totale disponibile (da API).
 * @param {number} apiMaxPrice Il prezzo massimo totale disponibile (da API).
 */
function initPriceSlider(apiMinPrice = 0, apiMaxPrice = PRICE_RANGE_FALLBACK) {
    const slider = document.getElementById('price-slider');

    // Riferimenti agli input hidden per l'API
    const minApiInput = document.getElementById('price-min');
    const maxApiInput = document.getElementById('price-max');

    // Riferimenti agli span per la visualizzazione all'utente
    const minDisplaySpan = document.getElementById('price-display-min');
    const maxDisplaySpan = document.getElementById('price-display-max');


    const MIN_RANGE = Math.floor(apiMinPrice);
    const MAX_RANGE = Math.ceil(apiMaxPrice);

    if (!slider || !minApiInput || !maxApiInput || !minDisplaySpan || !maxDisplaySpan || !window.noUiSlider) {
        return;
    }

    if (slider.noUiSlider) {
        slider.noUiSlider.destroy();
    }

    if (MIN_RANGE >= MAX_RANGE) {
        return;
    }

    // Regola i valori di START se sono fuori dal nuovo range assoluto fornito dall'API
    let startMin = Math.max(MIN_RANGE, currentMinPriceFilter);
    let startMax = Math.min(MAX_RANGE, currentMaxPriceFilter);

    if (startMin === 0 && startMax === PRICE_RANGE_FALLBACK) {
        startMin = MIN_RANGE;
        startMax = MAX_RANGE;
    }

    // Inizializza lo slider
    noUiSlider.create(slider, {
        range: {
            'min': MIN_RANGE,
            'max': MAX_RANGE
        },

        start: [startMin, startMax],
        connect: true,
        step: 1,
        format: {
            to: function (value) {
                return Math.round(value);
            },
            from: function (value) {
                return Number(value);
            }
        }
    });

    // Collega lo slider ai campi di input e span
    slider.noUiSlider.on('update', function (values, handle) {
        const value = values[handle];
        const roundedValue = Math.round(value);

        // Formattazione per la visualizzazione con simbolo € (senza decimali)
        const displayValue = new Intl.NumberFormat('it-IT', {
            style: 'currency',
            currency: 'EUR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(value);

        if (handle === 0) { // Handle sinistro (Min)
            // Aggiorna lo span VISIBILE
            minDisplaySpan.textContent = displayValue;
            // Aggiorna l'input HIDDEN per l'API (leggibile da collectFilterParams)
            minApiInput.setAttribute('data-api-value', roundedValue);
        } else { // Handle destro (Max)
            maxDisplaySpan.textContent = displayValue;
            maxApiInput.setAttribute('data-api-value', roundedValue);
        }
    });

    // Quando lo slider si ferma, salva il nuovo range e ricarica i dati
    slider.noUiSlider.on('set', function (values) {
        // *** SALVA I NUOVI VALORI SELEZIONATI PER MANTENERE LO STATO ***
        currentMinPriceFilter = Math.round(Number(values[0]));
        currentMaxPriceFilter = Math.round(Number(values[1]));

        const activeManager = getActiveManagerInstance();
        if (activeManager) {
            activeManager.fetchData(true);
        }
    });

    // Sincronizza lo stato iniziale del filtro con le variabili globali
    currentMinPriceFilter = startMin;
    currentMaxPriceFilter = startMax;
}


// =================================================================
// 5C. FUNZIONE PER RECUPERARE IL CONTEGGIO PRODOTTI PER SINGOLA CATEGORIA
// =================================================================

function fetchCategoryCount(categoryName) {
    // Usiamo l'endpoint PRODUCTS perché abbiamo il suo formato JSON
    const countUrl = `${API_BASE_URL}${ENDPOINTS.PRODUCTS}?category=${encodeURIComponent(categoryName)}&per_page=1`; // per_page=1 per velocità
    const countElement = document.getElementById(`cat-count-${categoryName.replace(/\s/g, '-')}`);

    if (!countElement) {
        return;
    }

    fetch(countUrl)
        .then(response => response.json())
        .then(data => {
            let totalCount = 0;

            // Il conteggio totale è in 'total_products' (dal JSON precedente)
            if (data.total_products !== undefined) {
                totalCount = parseInt(data.total_products);
            }
            // Fallback se la chiave è 'total_results' o 'X-WP-Total'
            else if (data.total_results !== undefined) {
                totalCount = parseInt(data.total_results);
            } else if (data['X-WP-Total'] !== undefined) {
                totalCount = parseInt(data['X-WP-Total']);
            }

            if (totalCount >= 0) {
                countElement.textContent = totalCount;
            } else {
                countElement.textContent = `0`;
            }

            // Log per debug
            console.log(`[Count] Categoria '${categoryName}': ${totalCount} prodotti.`);
        })
        .catch(error => {
            console.error(`Errore nel recupero del conteggio per '${categoryName}':`, error);
            countElement.textContent = `Err`;
        });
}


// =================================================================
// 5D. FUNZIONE DI GESTIONE RESET CATEGORIE
// =================================================================
function resetCategoryFilters() {
    const categoryContainer = document.getElementById('category-checkbox-list');

    if (categoryContainer) {
        // Trova tutti i checkbox con name="category" all'interno del contenitore
        const checkboxes = categoryContainer.querySelectorAll('input[type="checkbox"][name="category"]');

        // Deseleziona ogni checkbox
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });

        console.log('[Filters] Categorie resettate.');
    }
}


//utile per ordinamento
function initSortSelector() {
  const sortSelect = document.getElementById('product-sort-select');
  if (!sortSelect) return;

	const savedSortKey = localStorage.getItem('selectedSortKey');
if (savedSortKey && SORT_OPTIONS[savedSortKey]) {
  currentSortKey = savedSortKey;
  sortSelect.value = savedSortKey;
}

  sortSelect.addEventListener('change', function () {
	  const selectedKey = this.value;
	  if (SORT_OPTIONS[selectedKey]) {
		currentSortKey = selectedKey;
		localStorage.setItem('selectedSortKey', selectedKey); // salva scelta
		const activeManager = getActiveManagerInstance();
		if (activeManager) {
		  activeManager.fetchData(true);
		}
	  } else {
		console.warn(`[Ordinamento] Chiave non valida: ${selectedKey}`);
	  }
	});
}


//utile per ordinamento default
function resetSortToDefault() {
  currentSortKey = 'default';
  const sortSelect = document.getElementById('product-sort-select');
  if (sortSelect) {
    sortSelect.value = 'default';
    localStorage.setItem('selectedSortKey', 'default'); // opzionale, se usi localStorage
  }
}



// =================================================================
// 6. ESECUZIONE (DOMContentLoaded)
// =================================================================

document.addEventListener('DOMContentLoaded', function() {

    // 1. Inizializzazione Globale
    resetAllGlobalCounts();
    fetchCategories(); // Tentativo di estrarre le categorie dall'API prodotti

	// NUOVA CHIAMATA AGGIORNATA PER IL CONTEGGIO GIFT
    // La funzione ora sa quali endpoint chiamare.
    //updateGiftCount('#gift-count-span');

	//utile per ordinamento
	initSortSelector();

    // 2. Avvio dei Manager
    adManagerInstance = ProductListManager(ENDPOINTS.ADVERTISEMENTS, {
        container: SELECTORS.AD_CONTAINER,
        loadMore: SELECTORS.AD_LOAD_MORE,
        count: SELECTORS.AD_COUNT
    });
    if(adManagerInstance) adManagerInstance.init();

    mxManagerInstance = ProductListManager(ENDPOINTS.MIXED, {
        container: SELECTORS.MX_CONTAINER,
        loadMore: SELECTORS.MX_LOAD_MORE,
        count: SELECTORS.MX_COUNT
    });
    if(mxManagerInstance) mxManagerInstance.init();

    prManagerInstance = ProductListManager(ENDPOINTS.PRODUCTS, {
        container: SELECTORS.PR_CONTAINER,
        loadMore: SELECTORS.PR_LOAD_MORE,
        count: SELECTORS.PR_COUNT
    });
    if(prManagerInstance) prManagerInstance.init();


	// 3. GESTIONE FILTRO GIFT (Annunci regalo) NUOVA LOGICA
	const giftCheckbox = document.getElementById('filter-gift');
	if (giftCheckbox) {
		giftCheckbox.addEventListener('change', function() {
			// ?? VERIFICA CHE QUESTO AVVENGA PRIMA DEL FETCH ??
			isGiftFilterActive = this.checked;

			console.log("Stato Gift Attivo:", isGiftFilterActive); // <<< DEBUG RIGA

      if (isGiftFilterActive) {
    updateGiftCount('#gift-count-span');
  }

			if (adManagerInstance) {
				 adManagerInstance.fetchData(true);
			}
		});
	}


    // 3A. GESTIONE GLOBALE DEI PULSANTI FILTRO

    const filterWrapper = document.querySelector(SELECTORS.FILTERS_WRAPPER);

    if (filterWrapper) {
        const applyBtn = filterWrapper.querySelector('#apply-filters-btn');
        const resetBtn = filterWrapper.querySelector('#reset-filters-btn');

        if (applyBtn) {
            applyBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const activeManager = getActiveManagerInstance();
                if (activeManager) activeManager.fetchData(true);
            });
        }

        if (resetBtn) {
			resetBtn.addEventListener('click', (e) => {
				e.preventDefault();

				// ?? AGGIORNAMENTO: RESET DELLE VARIABILI DI STATO GLOBALE ??
				currentSortKey = 'default';         // 1. Reset Ordinamento
				isGiftFilterActive = false;         // 2. Reset Filtro Gift

				// *** RESET DELLO STATO GLOBALE DELLO SLIDER ***
				currentMinPriceFilter = 0;
				currentMaxPriceFilter = PRICE_RANGE_FALLBACK;

				// Reset di tutti gli input all'interno del wrapper
				filterWrapper.querySelectorAll('.api-filter-input').forEach(input => {
					if (input.type === 'checkbox' || input.type === 'radio') {
						input.checked = false;
					} else if (input.tagName === 'SELECT') {
						input.value = 'default'; // Assumendo che il valore di default sia 'default' o ''
					} else {
						input.value = '';
					}
					// Resetta anche il data-api-value per lo slider
					input.removeAttribute('data-api-value');
				});

				// ?? AGGIORNAMENTO: SINCRONIZZA ELEMENTI ESTERNI (Ordinamento e Gift) ??
				const sortSelect = document.getElementById('product-sort-select');
				const giftCheckbox = document.getElementById('filter-gift');

				if (sortSelect) {
					sortSelect.value = 'default';
				}
				if (giftCheckbox) {
					giftCheckbox.checked = false;
				}

				// Ricarica per resettare i filtri e ri-inizializzare lo slider
				const activeManager = getActiveManagerInstance();
				if (activeManager) activeManager.fetchData(true);
			});
		}
    }


    // 4. AGGANCIO EVENTO SWITCH SEZIONE (per il cambio tab)
    document.querySelectorAll('.section-switch-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            const targetContainerId = e.currentTarget.getAttribute('data-product-container');

            if (!targetContainerId) {
                return;
            }

			resetSortToDefault(); // Reset ordinamento al cambio sezione

			const activeManager = getActiveManagerInstance(); // Manager attivo
			if (activeManager) {
			  activeManager.fetchData(true); // Ricarica dati
			}

			console.log('[DEBUG] Ordinamento resettato e fetch rilanciato dopo switch');

            // Sincronizza il contatore dopo il cambio di visibilità
            setTimeout(() => syncCountDisplay(targetContainerId), 50);
        });
    });

	// GESTIONE DEL RESET DELLE CATEGORIE (Fuori da ProductListManager)
    const categoryResetLink = document.getElementById('category-reset-btn'); // Rinominato per chiarezza
                                                                            // Usa l'ID che hai dato al link
    if (categoryResetLink) {
        categoryResetLink.addEventListener('click', function(event) {
            event.preventDefault();

            // 1. Resetta i checkbox (la funzione deve essere definita prima/dopo questo blocco)
            resetCategoryFilters();

            // 2. Simula il clic sul pulsante principale per aggiornare tutti i manager
            const applyBtn = document.getElementById('apply-filters-btn');
            if (applyBtn) {
                applyBtn.click();
            }
        });
    }
});
