import { call_get_user_ads_api } from "../../lib/api.js";
import {get_user} from "../../../user-logged/main.js"

function build_ad_approved(advertisement)
{
    let advertisement_output = `<article class="advertisement" aria-label="Annuncio ${advertisement.title}">`;

    // immagine
    advertisement_output += `<div class="image-container"><img src="${advertisement.cover}" alt="Immagine dell'annuncio ${advertisement.title}"></div>`;

    // contenuto summary
    advertisement_output += `<div class="status-badge status-approved" aria-label="Annuncio Approvato">Approvato</div>
                            <div class="price-badge" aria-label="Prezzo ${advertisement.price} euro">${advertisement.price} €</div>
                            <h2>${advertisement.title}</h2>
                            <p>Categoria: ${advertisement.category}</p>
                            <p>Pubblicato il: ${advertisement.creation_date}</p>
                            <p>Visite: 0</p>`


    // azioni
    advertisement_output += `<div class="actions">
                            <a href="#" aria-label="Elimina annuncio ${advertisement.title}"><i class="fas fa-trash"></i></a>
                            <a href="#" aria-label="Modifica annuncio ${advertisement.title}"><i class="fas fa-pen"></i></a>
                            </div>`

    advertisement_output += "</article>"

    return advertisement_output;
}

function build_ad_rejected(advertisement)
{
    //console.log(advertisement)
}

function build_ad_waiting(advertisement)
{
    let advertisement_output = `<article class="advertisement" aria-label="Annuncio ${advertisement.title}">`;

    // immagine
    advertisement_output += `<div class="image-container"><img src="${advertisement.cover}" alt="Immagine dell'annuncio ${advertisement.title}"></div>`;

    // contenuto summary
    advertisement_output += `<div class="status-badge status-pending" aria-label="Annuncio in attesa di revisione">In revisione</div>
                            <div class="price-badge" aria-label="Prezzo ${advertisement.price} euro">${advertisement.price} €</div>
                            <h2>${advertisement.title}</h2>
                            <p>Categoria: ${advertisement.category}</p>
                            <p>Pubblicato il: ${advertisement.creation_date}</p>
                            <p>Visite: </p>`
                            


    // azioni
    advertisement_output += `<div class="actions">
                            <a href="#" aria-label="Elimina annuncio ${advertisement.title}"><i class="fas fa-trash"></i></a>
                            <a href="null" aria-label="Modifica annuncio ${advertisement.title}" class="disabled-action"><i class="fas fa-pen"></i></a>
                            </div>`

    advertisement_output += "</article>"

    return advertisement_output;
}

function build_all_user_ads(ads) // implementare un meccanismo che faccia 0/1 per il colore di sfondo
{
    if (ads.status != "OK")
        return;  // errore grafico

    let advertisements = "";

    ads.content.forEach(advertisement => 
    {
        if (advertisement.status == "APPROVED")
            advertisements += build_ad_approved(advertisement);
        
        else if (advertisement.status == "REJECTED")
            build_ad_rejected(advertisement)
        
        else if (advertisement.status == "IN_REVIEW")
            advertisements += build_ad_waiting(advertisement)
        
        else 
            console.log(advertisement) // gestire annuncio chiuso e controllare se API update considera il caso
    });

    jQuery(".ads-container").append(advertisements);
}

export function build_ads_for_user()
{
    call_get_user_ads_api(get_user, build_all_user_ads, console.log);
}