import { Collector, ImageUploader, RegionSelector, CreateSelectCategory, MessageBox } from "../../../form-utils/main.js";
import { MultiPagePopup } from "../../../pt-popup/main.js";
import { get_form_data_object } from "./process-form-data.js";
import { call_new_ad_api } from "../../api/api.js";
import { SimpleIndexedDB } from "../../../indexedDB/main.js";

function exclude_and_count_chars(element_id, output_id, limit, exclude_regex)
{
    const inputField = document.getElementById(element_id);
    const counter = document.getElementById(output_id);

    inputField.addEventListener('input', function() {
        jQuery(".msg-error").remove();
        
        if (exclude_regex.test(this.value)) 
        {
            const value = this.value;
            this.value = this.value.replace(exclude_regex, '');
            jQuery(".descr-label").append("<span class='msg-error'> Carattere non consentito ( " + value.charAt(value.length - 1) + " )<span>");
        }
        else
        {
            const currentLength = this.value.length;
            counter.textContent = `Caratteri: ${currentLength}/${limit}`;
        }
    });
    
}

function count_char_of(elementID, maxChars, outputID)
{
    setTimeout(() => {
        exclude_and_count_chars(elementID, outputID, maxChars, /[|_]/g);
    }, 600);
}

function clear_session_storage()
{
    for (let index = 1; index <= 5; index++) {
        sessionStorage.removeItem(`step${index}`);
    }
}

function get_session_storage()
{
    let list = [];
    for (let index = 1; index <= 4; index++) {
        list[index - 1] = JSON.parse(sessionStorage.getItem(`step${index}`));
    }

    return list;
}

function dinamyc_pricing()
{
    setTimeout(() => 
    {
        jQuery(document).ready(function() 
        {
            jQuery('input[name="gift"]').change(function() 
            {
                let selectedValue = jQuery('input[name="gift"]:checked').val();

                if (selectedValue == 'F')
                {
                    jQuery('.form').append(`
                                    <div class="section section-popup price-container">
                                        <div class="container-input">
                                            <label for="price" class="form-label">Prezzo (€)</label>
                                            <input type="text" name="price" id="price" class="input-petbuy input-petbuy-popup">
                                        </div>
                                    </div>`
                                )
                }
                else 
                    jQuery(".price-container").remove();
                return;

            });
        });
    }, 300);
}

function showMessage(message, isError = true)
{
    let animation;
    if (isError)
        animation = `<dotlottie-player src="https://lottie.host/d5b3919b-7c2c-4629-a913-746d94d0b9bf/A5UHXLiPs0.json" background="transparent" speed="1" style="width: 120px; height: 120px" direction="1" playMode="normal" loop autoplay></dotlottie-player>`
    else
        animation = `<dotlottie-player src="https://lottie.host/13dff29f-34a2-4bd0-886c-780ea271193e/wgqRyD0xD6.json" background="transparent" speed="1" style="width: 120px; height: 120px" direction="1" playMode="normal" autoplay></dotlottie-player>`;

    const messageToShow = `<span class="message">${message}</span>`
    const content = animation + messageToShow;
    jQuery(".server-response").append(content);
}

function ad_created_success() 
{
    const form = document.querySelector(".form");
    form.innerHTML = '';
    
    const button = document.querySelector(".btn-popup");
    button.innerHTML = '';
    console.log(form);

    const lottie = document.createElement('div')
    lottie.innerHTML = '<dotlottie-player src="https://lottie.host/294fc3a0-d4bb-4e2b-84fa-e15995ec7155/gs0WpbllEV.lottie" background="transparent" speed="1" style="width: 120px; height: 120px" direction="1" playMode="normal" autoplay></dotlottie-player>';
    const message_box = new MessageBox(
        lottie,
        "Annuncio creato, attendi un feedback.",
        {
            textColor: "white",
            backgroundColor: "green",
            borderRadius: "14px",
            width: "40%",
            height: "15%"
        }
    )
    jQuery(".form-container").css("height", "100%");
    message_box.create(form);
}


export async function build_popup_ad(openPopupButtonId)
{
    const db = new SimpleIndexedDB("petbuy", "ad_images", 1);
    const scraper = new Collector();


    const pages = 
    [
        { 
            content: `
                    <link rel="stylesheet" href="https://petbuy-local.ns0.it:8080/wp-content/advertisement/user_advertisements/style.css">
                    <div class="flex-center-center attention-cards">
                        <h1>LEGGI CON ATTENZIONE!</h1>
                        <div class="attention-card">
                            <div class="attention-card-icon flex-center-center">
                               <img src = "https://petbuy-local.ns0.it:8080/wp-content/advertisement/src/clinic.svg">
                            </div>

                            <div class="attention-card-text flex-center-center">
                                <span>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>
                            </div>
                        </div>

                        <div class="attention-card">
                            <div class="attention-card-icon flex-center-center">
                               <img src = "https://petbuy-local.ns0.it:8080/wp-content/advertisement/src/revision.svg">
                            </div>

                            <div class="attention-card-text flex-center-center">
                                <span>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>
                            </div>
                        </div>

                        <div class="attention-card">
                            <div class="attention-card-icon flex-center-center">
                               <img src = "https://petbuy-local.ns0.it:8080/wp-content/advertisement/src/notifications.svg">
                            </div>

                            <div class="attention-card-text flex-center-center">
                                <span>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>
                            </div>
                        </div>

                        <div class="attention-card">
                            <div class="attention-card-icon flex-center-center">
                               <img src = "https://petbuy-local.ns0.it:8080/wp-content/advertisement/src/ban.svg">
                            </div>

                            <div class="attention-card-text flex-center-center">
                                <span>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>
                            </div>
                        </div>
                    </div>
                    `,
            width: '80%',
            height: '90%',
            maxWidth : '80%',
            maxHeight : '90%',
            onShow: async () => {
                try {
                    await db.init();
                    console.log('IndexedDB inizializzato correttamente.');
                } catch (error) {
                    console.error('Errore nell\'inizializzazione di IndexedDB:', error);
                    return; // Interrompe la funzione se l'inizializzazione fallisce
                }
            }
        },
        { 
            content: `
                    <link rel="stylesheet" href="https://petbuy-local.ns0.it:8080/wp-content/advertisement/user_advertisements/style.css">
                    <div class = "flex-center-center form-container">
                        <div class = "form one-column">
                            <h1>Informazioni Generali</h1>
                            <div class="section section-popup">
                                <div class="container-input">
                                    <label for="title" class="form-label">Titolo annuncio</label>
                                    <input type="text" name="title" id="title" class="input-petbuy input-petbuy-popup">
                                </div>
                            </div>

                            <div class="section section-popup">
                                <div class="container-input">
                                    <label for="descr" class="form-label descr-label">Descrizione (min. 200 caratteri)</label>
                                    <textarea id="descr" name="descr" rows="4" cols="50" placeholder="Scrivi qui..." required class="input-petbuy descr-label textarea-big" maxlength="5000"></textarea>
                                    <div id="descr-length"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                    `,
            width: '80%',
            height: '90%',
            maxWidth : '80%',
            maxHeight : '90%',
            onNext: () => { 
                sessionStorage.setItem("step1", JSON.stringify(scraper.getFormData(".form")));
            },
            onShow: () => {
                count_char_of("descr", 5000, "descr-length");
                scraper.populateFormFromSessionStorage("step1");
            },
            validationRules : {
                title: /^(?!.*(--|;|'|\/\*|\*\/|@|#)).{1,100}$/,
                descr: /^(?!.*(--|;|'|\/\*|\*\/|@|#)).{200,5000}$/
            }
        },
        { 
            content: `
                    <link rel="stylesheet" href="https://petbuy-local.ns0.it:8080/wp-content/advertisement/user_advertisements/style.css">
                    <div class = "flex-center-center form-container from-container-100">
                        <div class="page-popup-title">
                            <h1>Scegli la categoria</h1>
                        </div>
                        <div class = "form one-column horizontal">
                            <div class="section section-popup input-horizontal">
                                <div class="container-input">
                                     <select name="category" id="category" class="input-petbuy select-petbuy-popup" title="Seleziona la categoria" required>
                                        <option value="-1" selected="selected">Seleziona la categoria</option>
                                    </select>
                                </div>
                            </div>

                            <div class="section section-popup input-horizontal">
                                <div class="container-input">
                                    <select name="sub-category" id="sub-category" class="input-petbuy select-petbuy-popup" title="Seleziona la sotto categoria" required>
                                        <option value="-1" selected="selected">Seleziona la sotto categoria</option>
                                    </select>
                                </div>
                            </div>

                            <div class="section section-popup radio" style="width:80%">
                                <span class="radio-title">Animale soggetto a CITES</span>
                                <div class="radio-container">
                                    <input type="radio" id="cites-t" name="cites" value="T">
                                    <label for="cites-t">Si</label>

                                    <input type="radio" id="cites-f" name="cites" value="F" checked>
                                    <label for="cites-f">No</label>
                            </div>

                        </div>
                    </div>
                    `,
            width: '80%',
            height: '90%',
            maxWidth : '80%',
            maxHeight : '90%',
            onShow: () => {
                const categories = new CreateSelectCategory(
                    "#category",
                    "#sub-category",
                    "https://petbuy-local.ns0.it:8080/wp-content/uploads/petbuy-menu-special/categorie.json"
                )
                scraper.populateFormFromSessionStorage("step2");
            },
            onNext: () => { 
                sessionStorage.setItem("step2", JSON.stringify(scraper.getFormData(".form")));
            },
            validationRules : {}
        },
        { 
            content: `
                    <link rel="stylesheet" href="https://petbuy-local.ns0.it:8080/wp-content/advertisement/user_advertisements/style.css">
                    <div class = "flex-center-center form-container">
                        <div class = "form one-column">
                            <h1>Informazioni animale</h1>

                            <div class="section section-popup">
                                <div class="container-input">
                                    <select name="sex" id="sex" class="input-petbuy select-petbuy-popup" title="Seleziona il sesso" required>
                                        <option value="-1" selected="selected">Seleziona il sesso</option>
                                        <option value="M">Maschio</option>
                                        <option value="F">Femmina</option>
                                    </select>
                                </div>
                            </div>

                            <div class="section section-popup">
                                <div class="container-input">
                                    <label for="birth" class="form-label">Data di nascita</label>
                                    <input type="date" name="birth" id="birth" class="input-petbuy input-petbuy-popup">
                                </div>
                            </div>

                            <div class="section section-popup">
                                <div class="container-input">
                                    <label for="weight" class="form-label">Peso (Kg)</label>
                                    <input type="number" name="weight" id="weight" step="0.1" class="input-petbuy input-petbuy-popup">
                                </div>
                            </div>

                            <div class="section section-popup">
                                <div class="container-input">
                                    <label for="health" class="form-label descr-label">Stato di salute (min. 100 caratteri)</label>
                                    <textarea id="health" name="health" rows="4" cols="50" placeholder="Scrivi qui..." required class="input-petbuy descr-label" maxlength="1000" style="width:100%"></textarea>
                                    <div id="health-length"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                    `,
            width: '80%',
            height: '95%',
            maxWidth : '80%',
            maxHeight : '95%',
            onNext: () => { 
                sessionStorage.setItem("step3", JSON.stringify(scraper.getFormData(".form")));
            },
            onShow: () => {
                count_char_of("health", 1000, "health-length");
                scraper.populateFormFromSessionStorage("step3");
            },
            validationRules: {
                birth: /^((\d{4})[-\/](0[1-9]|1[0-2])[-\/](0[1-9]|[12][0-9]|3[01]))|((0[1-9]|1[0-2])[-\/](0[1-9]|[12][0-9]|3[01])[-\/](\d{4}))|((0[1-9]|[12][0-9]|3[01])[-\/](0[1-9]|1[0-2])[-\/](\d{4}))$/,
                weight: /^\d{1,10}(\.\d{1,2})?$/,
                health: /^(?!.*(--|;|'|\/\*|\*\/|@|#)).{100,1000}$/,
            }
        },
        { 
            content: `
                    <link rel="stylesheet" href="https://petbuy-local.ns0.it:8080/wp-content/advertisement/user_advertisements/style.css">
                    <link rel="stylesheet" href="https://petbuy-local.ns0.it:8080/wp-content/form-utils/image-component/form-image-list.css">

                    <div class = "flex-center-center form-container">
                        <div class = "form one-column" style="gap:1rem">
                            <h1>Immagini</h1>

                            <div class="upload-container">
                                <div id="dropZone" class="upload-box">
                                    <label for="fileInput" class="full-clickable">
                                        <input type="file" id="fileInput" multiple accept="image/*">
                                        <div class="upload-icon">↑</div>
                                        <p>La prima immagine caricata sarà la copertina</p>
                                    </label>
                                </div>
                                <div class="file-list" id="fileList"></div>
                            </div>

                            <div id="image-error-message" class="error-message" style="display: none; color: red;">
                            </div>

                        </div>
                    </div>
                    `,
            width: '80%',
            height: '95%',
            maxWidth : '80%',
            maxHeight : '95%',
            onNext: async () => { 

                const images = scraper.getFormData(".form");
                console.log(images);
                const res = await db.update({id : 1, base64_images : JSON.stringify(images.imageUrls)});

            },
            onShow: () => {
                db.read(1).then(images => {
                    try {
                        const imageUploader = new ImageUploader(images.base64_images);
                        imageUploader.upload_images_logics();
                    } catch (error) {
                        const imageUploader = new ImageUploader();
                        imageUploader.upload_images_logics();
                    }
                })
            },
            validationRules: {}
        },
        { 
            content: `
                    <link rel="stylesheet" href="https://petbuy-local.ns0.it:8080/wp-content/advertisement/user_advertisements/style.css">
                    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module" async></script>
                    <div class = "flex-center-center form-container">
                        <div class = "form one-column">
                            <h1>Informazioni sull'annuncio</h1>

                            <div class="section section-popup">
                                <div class="container-input">
                                    <select name="region" id="region" class="input-petbuy select-petbuy-popup" title="Seleziona la regione" required>
                                        <option value="-1" selected="selected">Seleziona la regione</option>
                                        <option value="1">Seleziona la regione</option>
                                    </select>
                                </div>
                            </div>

                            <div class="section section-popup">
                                <div class="container-input">
                                    <select name="province" id="province" class="input-petbuy select-petbuy-popup" title="Seleziona la provincia" required>
                                        <option value="-1" selected="selected">Seleziona la provincia</option>
                                        <option value="1">Seleziona la provincia</option>
                                    </select>
                                </div>
                            </div>

                            <div class="section section-popup">
                                <div class="container-input">
                                    <label for="phone" class="form-label">Numero di telefono</label>
                                    <input type="tel" name="phone" id="phone" class="input-petbuy input-petbuy-popup">
                                </div>
                            </div>

                            <div class="section section-popup radio">
                                <span class="radio-title">Vuoi regalarlo?</span>
                                <div class="radio-container">
                                    <input type="radio" id="gift-f" name="gift" value="T" checked>
                                    <label for="gift-f">Si</label>

                                    <input type="radio" id="gift-t" name="gift" value="F">
                                    <label for="gift-t">No</label>
                                </div>
                            </div>

                            <div class="server-response"></div>
                        </div>
                    </div>
                    `,
            width: '80%',
            height: '95%',
            maxWidth : '80%',
            maxHeight : '95%',
            onNext: () => { 
                sessionStorage.setItem("step4", JSON.stringify(scraper.getFormData(".form")));

                db.read(1).then(images => {
                    const form = get_form_data_object(get_session_storage(), JSON.parse(images.base64_images))
                    call_new_ad_api(form, ad_created_success, showMessage);
                    setTimeout(() => {
                        clear_session_storage()
                        popup.closePopup();
                    }, 10100);
                })

                db.delete(1).then(
                    db.close()
                );
            },
            onShow: () => {
                const geos = new RegionSelector(
                    "#region",
                    "#province"
                )
                dinamyc_pricing();
                scraper.populateFormFromSessionStorage("step4");
            },
            validationRules: {
                phone: /^\+?(\d{1,3})?[-.\s]?(\(?\d{3}\)?)[-.\s]?\d{3,4}[-.\s]?\d{3,4}$/,
                price: /^(?!0+(\.0+)?$)\d+(\.\d+)?$/,
            }
        }
    ];

    // Specifica del contenitore e delle configurazioni di stile
    const container = document.getElementById('primary') || document.body;

    const styleConfig = {
        width: '35%',
        height: '50%',
        backgroundColor: '#f8f9fa',
        textColor: '#343a40',
        padding: '30px',
        borderRadius: '15px',
        transitionDuration: '0.3s',
        zIndex: 99999999,
        progressBar: {
            activeColor: 'red',
            inactiveColor: '#ddd',
            dotSize: '12px',
            dotSpacing: '8px'
        },
        buttons: {
            prevText: 'Indietro',
            nextText: 'Prossimo',
            finishText: 'Crea',
            finishColor: '#28a745',
            buttonPosition: 'center',
            prevClass: 'secondary-button', // Classe personalizzata per "Indietro"
            nextClass: 'input-petbuy',        // Classe personalizzata per "Prossimo"
            finishClass: 'input-petbuy finish-button',    // Classe personalizzata per "Fine"
            width: '3rem'
        },
        closeButton: {
            show: true,
            color: '#000',
            size: '3rem'
        }
    };    

    // Creazione del popup con transizione a scelta ('fade' o 'slide') e configurazioni di stile
    const popup = new MultiPagePopup(
        pages, 
        container, 
        'fade', 
        styleConfig, 
        true, 
        null,
        true, 
        false
    );

    // Attivazione del popup chiamando il metodo openPopup()
    document.getElementById(openPopupButtonId).addEventListener('click', () => {
        clear_session_storage()
        popup.openPopup();
    });

     // Aggiungi l'event listener per cancellare il record quando l'utente lascia la pagina
    window.addEventListener('beforeunload', () => {
        db.delete(1).catch(err => console.error('Errore nella cancellazione del record durante beforeunload:', err));
    });

    // Opzionale: utilizzare anche l'evento unload
    window.addEventListener('unload', () => {
        db.delete(1).catch(err => console.error('Errore nella cancellazione del record durante unload:', err));
    });
}