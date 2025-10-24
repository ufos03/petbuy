import { call_get_user_ads_api } from "../../api/api.js";
import { get_user } from "../../../user-logged/main.js"
import { AnimalLoader } from "../../../petbuy-loader/main.js";

// Variabile per memorizzare tutti gli annunci
let allAdvertisements = [];

// Crea un'istanza di AnimalLoader con opzioni personalizzate
const loader = new AnimalLoader({
    id: 'custom-animal-loader',
    width: '80px', // Larghezza dello spinner
    height: '80px', // Altezza dello spinner
    containerId: 'container-ads' // ID del contenitore dove inserire il loader (opzionale)
});

// Funzione per aggiornare il valore del prezzo in tempo reale
function updatePriceValue(value) {
    jQuery('#price-value').text(`€${value}`);
    jQuery('#range-tooltip').text(`€${value}`);
}

// Funzione per aggiornare la parte riempita del track
function updateTrackFill(value, max) {
    const percentage = (value / max) * 100;
    jQuery("#price-range").css('background', `linear-gradient(to right, var(--primary-color) ${percentage}%, #ddd ${percentage}%)`);
}

// Funzione helper per costruire il badge del prezzo
function build_price_badge(advertisement) {
    if (advertisement.is_gift === "T") {
        return '<div class="price-badge" aria-label="Annuncio regalo"><i class="fas fa-gift" aria-hidden="true"></i></div>';
    } else if (advertisement.sale_price > 0.00 && parseFloat(advertisement.sale_price) < parseFloat(advertisement.price)) {
        return `
            <div class="price-badge" aria-label="Prezzo scontato">
                <span class="regular-price">€${advertisement.price}</span>
                <span class="sale-price">€${advertisement.sale_price}</span>
            </div>
        `;
    } else {
        return `<div class="price-badge" aria-label="Prezzo">€${advertisement.price}</div>`;
    }
}

// Funzione per costruire gli annunci approvati
function build_ad_approved(advertisement) {
    let advertisement_output = `<article class="advertisement" aria-label="Annuncio ${advertisement.title}" data-hash="${advertisement.hash}" data-price="${advertisement.price}" data-contact="${advertisement.contact}" data-gift="${advertisement.is_gift}" data-descr="${advertisement.description}" data-title="${advertisement.title}" data-on-sale="${advertisement.on_sale}" data-health="${advertisement.health}" data-saleprice="${advertisement.sale_price}">`;

    // Immagine
    advertisement_output += `<div class="image-container"><img loading = "lazy" src="${advertisement.cover}" alt="Immagine dell'annuncio ${advertisement.title}"></div>`;

    // Contenuto summary
    advertisement_output += `
        <div class="status-badge status-approved" aria-label="Annuncio Approvato">Approvato</div>
        ${build_price_badge(advertisement)}
        <h2>${advertisement.title}</h2>
        <p>Categoria: ${advertisement.category}</p>
        <p>Pubblicato il: ${advertisement.date}</p>
        <p>Visite: ${advertisement.visits || 0}</p>
    `;

    // Azioni
    advertisement_output += `
        <div class="actions">
            <a href="#" aria-label="Elimina annuncio ${advertisement.title}" class="delete-user-ad"><i class="fas fa-trash"></i></a>
            <a href="#" aria-label="Modifica annuncio ${advertisement.title}" class="update-user-ad"><i class="fas fa-pen"></i></a>
        </div>
    `;

    advertisement_output += "</article>";

    return advertisement_output;
}

// Funzione per costruire gli annunci rifiutati
function build_ad_rejected(advertisement) {
    let advertisement_output = `<article class="advertisement" aria-label="Annuncio ${advertisement.title}" data-hash="${advertisement.hash}" data-price="${advertisement.price}" data-contact="${advertisement.contact}" data-gift="${advertisement.is_gift}" data-descr="${advertisement.description}" data-title="${advertisement.title}" data-on-sale="${advertisement.on_sale}" data-health="${advertisement.health}" data-saleprice="${advertisement.sale_price}">`;

    // Immagine
    advertisement_output += `<div class="image-container"><img loading = "lazy" src="${advertisement.cover}" alt="Immagine dell'annuncio ${advertisement.title}"></div>`;

    // Contenuto summary
    advertisement_output += `
        <div class="status-badge status-rejected" aria-label="Annuncio Rifiutato">Rifiutato</div>
        ${build_price_badge(advertisement)}
        <h2>${advertisement.title}</h2>
        <p>Categoria: ${advertisement.category}</p>
        <p>Pubblicato il: ${advertisement.date}</p>
        <p>Visite: ${advertisement.visits || 0}</p>
    `;

    // Azioni
    advertisement_output += `
        <div class="actions">
            <a href="#" aria-label="Elimina annuncio ${advertisement.title}" class="delete-user-ad"><i class="fas fa-trash"></i></a>
            <a href="#" aria-label="Visualizza motivazione rifiuto annuncio ${advertisement.title}" class="view-reason"><i class="fas fa-info-circle"></i></a>
        </div>
    `;

    advertisement_output += "</article>";

    return advertisement_output;
}


// Funzione per costruire gli annunci in revisione
function build_ad_waiting(advertisement) {
    let advertisement_output = `<article class="advertisement" aria-label="Annuncio ${advertisement.title}" data-hash="${advertisement.hash}">`;

    // Immagine
    advertisement_output += `<div class="image-container"><img loading = "lazy" src="${advertisement.cover}" alt="Immagine dell'annuncio ${advertisement.title}"></div>`;

    // Contenuto summary
    advertisement_output += `
        <div class="status-badge status-pending" aria-label="Annuncio in attesa di revisione">In Revisione</div>
        ${build_price_badge(advertisement)}
        <h2>${advertisement.title}</h2>
        <p>Categoria: ${advertisement.category}</p>
        <p>Pubblicato il: ${advertisement.date}</p>
        <p>Visite: ${advertisement.visits || 0}</p>
    `;

    // Azioni
    advertisement_output += `
        <div class="actions">
            <a href="#" aria-label="Elimina annuncio ${advertisement.title}" class="delete-user-ad"><i class="fas fa-trash"></i></a>
            <a href="#" aria-label="Modifica annuncio ${advertisement.title}" class="disabled-action"><i class="fas fa-pen"></i></a>
        </div>
    `;

    advertisement_output += "</article>";

    return advertisement_output;
}

// Funzione per costruire tutti gli annunci filtrati
function build_all_user_ads_filtered(filters) {
    const { status, dateFrom, dateTo, maxPrice, showGiftsOnly } = filters;

    // Filtra gli annunci in base ai criteri
    let filteredAds = allAdvertisements.filter(ad => {
        let statusMatch = status === "ALL" || ad.status === status;
        let dateMatch = true;
        let priceMatch = true;
        let giftMatch = !showGiftsOnly || ad.is_gift === "T"; // Logica del filtro

        // Filtraggio per data
        if (dateFrom) {
            dateMatch = dateMatch && new Date(ad.creation_date) >= new Date(dateFrom);
        }

        if (dateTo) {
            dateMatch = dateMatch && new Date(ad.creation_date) <= new Date(dateTo);
        }

        // Filtraggio per prezzo
        if (maxPrice !== null && maxPrice !== undefined && maxPrice !== "") {
            priceMatch = priceMatch && parseFloat(ad.price) <= parseFloat(maxPrice);
        }

        return statusMatch && dateMatch && priceMatch && giftMatch;
    });

    // Pulisci il contenitore prima di aggiungere nuovi annunci
    jQuery(".ads-container").empty();

    if (filteredAds.length === 0) {
        jQuery(".count-advertisement").text(`Nessun annuncio presente.`);
        return;
    }
    else if (filteredAds.length > 1)
        jQuery(".count-advertisement").text(`${filteredAds.length} annunci.`);
    else if (filteredAds.length === 1)
        jQuery(".count-advertisement").text(`${filteredAds.length} annuncio.`);

    // Costruisci gli annunci filtrati
    let advertisements = "";

    // Costruisci gli annunci in base allo stato
    filteredAds.forEach(advertisement => {
        if (advertisement.status === "APPROVED") {
            advertisements += build_ad_approved(advertisement);
        } else if (advertisement.status === "REJECTED") {
            advertisements += build_ad_rejected(advertisement);
        } else if (advertisement.status === "IN_REVIEW") {
            advertisements += build_ad_waiting(advertisement);
        } else {
            console.log(`Stato annuncio non gestito: ${advertisement.status}`, advertisement);
        }
    });

    // Aggiungi gli annunci al contenitore
    jQuery(".ads-container").append(advertisements);
}

// Funzione per costruire tutti gli annunci (senza filtri, all'avvio)
function build_all_user_ads(ads) {
    if (ads.status !== "OK") {
        // Gestione dell'errore (puoi utilizzare OutputMessage o altro)
        console.error("Errore nel recupero degli annunci:", ads);
        return;
    }

    // Memorizza tutti gli annunci
    allAdvertisements = ads.content;

    // Determina il prezzo massimo tra tutti gli annunci
    let maxPrice = allAdvertisements.reduce((max, ad) => {
        const price = parseFloat(ad.price);
        return price > max ? price : max;
    }, 0);


    // Imposta il valore massimo dello slider del prezzo
    const priceRange = jQuery("#price-range");
    priceRange.attr("max", maxPrice);
    priceRange.val(maxPrice);
    updatePriceValue(maxPrice);

    // Aggiorna la parte riempita del track
    updateTrackFill(priceRange.val(), maxPrice);

    // Applica i filtri correnti (inizialmente nessun filtro)
    const currentFilters = {
        status: jQuery("#status-filter").val() || "ALL",
        dateFrom: jQuery("#date-from").val() || null,
        dateTo: jQuery("#date-to").val() || null,
        maxPrice: priceRange.val() || maxPrice,  // Prezzo massimo selezionato
        showGiftsOnly: jQuery("#gift-filter").is(":checked"), // Nuovo filtro
    };

    build_all_user_ads_filtered(currentFilters);

    // disattiva il loader
    loader.hide();
}

// Funzione per applicare i filtri quando l'utente clicca il bottone
function apply_filters(event) {
    event.preventDefault(); // Previene il comportamento di default del bottone

    const filters = {
        status: jQuery("#status-filter").val() || "ALL",
        dateFrom: jQuery("#date-from").val() || null,
        dateTo: jQuery("#date-to").val() || null,
        maxPrice: jQuery("#price-range").val() || null,  // Prezzo massimo selezionato
        showGiftsOnly: jQuery("#gift-filter").is(":checked"), // Nuovo filtro
    };

    build_all_user_ads_filtered(filters);
}

// Funzione per cancellare i filtri
function clear_filters(event) {
    event.preventDefault(); // Previene il comportamento di default del bottone

    // Reimposta i filtri
    jQuery("#status-filter").val("ALL");
    jQuery("#date-from").val("");
    jQuery("#date-to").val("");
    jQuery("#gift-filter").prop("checked", false); // Reimposta il filtro regalo

    // Determina il prezzo massimo corrente
    let maxPrice = allAdvertisements.reduce((max, ad) => {
        const price = parseFloat(ad.price);
        return price > max ? price : max;
    }, 0);

    // Reimposta lo slider del prezzo
    jQuery("#price-range").val(maxPrice);
    updatePriceValue(maxPrice);

    // Aggiorna la parte riempita del track
    updateTrackFill(maxPrice, maxPrice);

    const filters = {
        status: "ALL",
        dateFrom: null,
        dateTo: null,
        maxPrice: maxPrice,
        showGiftsOnly: false, // Nuovo filtro
    };

    build_all_user_ads_filtered(filters);
}

// Funzione per aggiornare il valore del prezzo e il track
function handleRangeInput() {
    const range = jQuery("#price-range");
    const value = range.val();
    const max = range.attr('max');
    updatePriceValue(value);
    updateTrackFill(value, max);
}

// Funzione per inizializzare la costruzione degli annunci
export function build_ads_for_user() {
    loader.build();
    loader.show();
    call_get_user_ads_api(get_user, build_all_user_ads, console.error); // TODO: gestire errore API

    // Event Listeners per i bottoni dei filtri e lo slider del prezzo
    jQuery("#apply-filters").on("click", apply_filters);
    jQuery("#clear-filters").on("click", clear_filters);

    // Aggiorna il valore visualizzato quando lo slider del prezzo cambia
    jQuery("#price-range").on("input change", handleRangeInput);
}