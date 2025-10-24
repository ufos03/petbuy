import { MultiPagePopup } from "../../../pt-popup/main.js";
import { Collector, MessageBox } from "../../../form-utils/main.js";
import { call_update_ad_api } from "../../api/api.js";
import { get_user } from "../../../user-logged/main.js";
import { build_ads_for_user } from "../read/read_user_ads.js";

const scraper = new Collector();

function show_ui_for_price(has_price)
{
    if (has_price == 'T')
    {
        jQuery(document).find(".price-container").css("display", "none");
        jQuery(document).find("#price").css("display", "none");
        let radioOption = jQuery('input[name="sale"][value="F"]')
        radioOption.prop("checked", true); 

        show_ui_for_sale_price('F');
        return;
    }

    jQuery(document).find(".price-container").css("display", "");
    jQuery(document).find("#price").css("display", "");
}

function set_price_from_input()
{
    jQuery(document).ready(function() 
    {
        jQuery('input[name="gift"]').change(function() 
        {
            let selectedValue = jQuery('input[name="gift"]:checked').val();
            show_ui_for_price(selectedValue);
        });
    });
}

function set_price()
{
    let selectedValue = jQuery('input[name="gift"]:checked').val();
    show_ui_for_price(selectedValue);
}



function show_ui_for_sale_price(has_sale_price)
{
    if (has_sale_price == 'F')
    {
        jQuery(document).find(".sale-price-container").css("display", "none");
        jQuery(document).find("#saleprice").css("display", "none");
        return;
    }

    if (jQuery('input[name="gift"]:checked').val() == 'T')
    {
        let radioOption = jQuery('input[name="sale"][value="F"]')
        radioOption.prop("checked", true); 
        return;
    }

    jQuery(document).find(".sale-price-container").css("display", "");
    jQuery(document).find("#saleprice").css("display", "");
}

function set_sale_price_from_input()
{
    jQuery(document).ready(function() 
    {
        jQuery('input[name="sale"]').change(function() 
        {
            let selectedValue = jQuery('input[name="sale"]:checked').val();
            show_ui_for_sale_price(selectedValue);
        });
    });
}

function set_sale_price()
{
    let selectedValue = jQuery('input[name="sale"]:checked').val();
    show_ui_for_sale_price(selectedValue);
}


function dinamyc_pricing()
{
    set_price();
    set_price_from_input();
}

function dinamyc_sale_pricing()
{
    set_sale_price();
    set_sale_price_from_input();
}


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
    }, 300);
}


function get_new_price()
{
    if (jQuery('input[name="sale"]:checked').val() == 'T')
        return jQuery("#saleprice").val();

    return jQuery("#price").val();
}

const page = [
    {
       content: `
                <div class = "flex-center-center form-container">
                        <div class = "form one-column">
                            <h1>Modifica l'annuncio</h1>

                            <div class="section section-popup">
                                <div class="container-input">
                                    <label for="title" class="form-label">Titolo</label>
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

                            <div class="section section-popup">
                                <div class="container-input">
                                    <label for="health" class="form-label descr-label">Stato di salute (min. 100 caratteri)</label>
                                    <textarea id="health" name="health" rows="4" cols="50" placeholder="Scrivi qui..." required class="input-petbuy descr-label" maxlength="1000"></textarea>
                                    <div id="health-length"></div>
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
                                    <input type="radio" id="gift-f" name="gift" value="T">
                                    <label for="gift-f">Si</label>

                                    <input type="radio" id="gift-t" name="gift" value="F">
                                    <label for="gift-t">No</label>
                                </div>
                            </div>

                            <div class="section section-popup price-container">
                                <div class="container-input">
                                    <label for="price" class="form-label">Prezzo (€)</label>
                                    <input type="text" name="price" id="price" class="input-petbuy input-petbuy-popup">
                                </div>
                            </div>

                            <div class="section section-popup radio">
                                <span class="radio-title">Vuoi metterlo in offerta?</span>
                                <div class="radio-container">
                                    <input type="radio" id="sale-f" name="sale" value="T">
                                    <label for="sale-f">Si</label>

                                    <input type="radio" id="sale-t" name="sale" value="F">
                                    <label for="sale-t">No</label>
                                </div>
                            </div>

                            <div class="section section-popup sale-price-container">
                                <div class="container-input">
                                    <label for="price" class="form-label">Prezzo in offerta(€)</label>
                                    <input type="text" name="saleprice" id="saleprice" class="input-petbuy input-petbuy-popup">
                                </div>
                            </div>

                            <div class="server-response"></div>
                        </div>
                    </div>
                `,
        width: '80%',
        height: '90%',
        maxWidth: '80%',
        maxHeight: '90%',
        onShow: () => {
            count_char_of("descr", 5000, "descr-length");
            count_char_of("health", 1000, "health-length");
            scraper.populateFormFromSessionStorage("update_user_ad");
            dinamyc_pricing();
            dinamyc_sale_pricing();
        },
        validationRules : {
            title: /^(?!.*(--|;|'|\/\*|\*\/|@|#)).{1,100}$/,
            descr: /^(?!.*(--|;|'|\/\*|\*\/|@|#)).{200,5000}$/,
            health: /^(?!.*(--|;|'|\/\*|\*\/|@|#)).{100,1000}$/,
            phone: /^\+?(\d{1,3})?[-.\s]?(\(?\d{3}\)?)[-.\s]?\d{3,4}[-.\s]?\d{3,4}$/,
            price: /^(?!0+(\.0+)?$)\d+(\.\d+)?$/,
            saleprice: /^(?!0+(\.0+)?$)\d+(\.\d+)?$/
        },
        onNext : () => {
            const form_data = scraper.getFormData(".form");
            jQuery(".form-container").css("height", "100%");
            call_update_ad_api(get_user(), 
            sessionStorage.getItem("update_ad_hash"), 
            form_data.gift, 
            form_data.sale, 
            get_new_price(), 
            form_data.phone, 
            form_data.decr, 
            form_data.title, 
            form_data.health, 
            function () {  // success
                const form = document.querySelector(".form");
                form.innerHTML = '';
                const button = document.querySelector(".btn-popup");
                button.innerHTML = '';
            
                const lottie = document.createElement('div')
                lottie.innerHTML = '<dotlottie-player src="https://lottie.host/294fc3a0-d4bb-4e2b-84fa-e15995ec7155/gs0WpbllEV.lottie" background="transparent" speed="1" style="width: 120px; height: 120px" direction="1" playMode="normal" autoplay></dotlottie-player>';
                const message_box = new MessageBox(
                    lottie,
                    "Annuncio aggiornato",
                    {
                        textColor: "white",
                        backgroundColor: "green",
                        borderRadius: "14px",
                        width: "40%",
                        height: "15%"
                    }
                )

                message_box.create(form);
                sessionStorage.removeItem("update_user_ad");
                sessionStorage.removeItem("update_ad_hash");
                build_ads_for_user();
                setTimeout(() => {
                    popup.closePopup();
                }, 3000);
            }, 
            
            function () { // error
                const form = document.querySelector(".form");
                form.innerHTML = '';
            
                const lottie = document.createElement('div')
                lottie.innerHTML = '<dotlottie-player src="https://lottie.host/d5b3919b-7c2c-4629-a913-746d94d0b9bf/A5UHXLiPs0.json" background="transparent" speed="1" style="width: 120px; height: 120px" direction="1" playMode="normal" loop autoplay></dotlottie-player>';
                const message_box = new MessageBox(
                    lottie,
                    "Annuncio non aggiornato",
                    {
                        textColor: "white",
                        backgroundColor: "red",
                        borderRadius: "14px",
                        width: "40%",
                        height: "15%"
                    }
                )

                message_box.create(form);
                sessionStorage.removeItem("update_user_ad");
                sessionStorage.removeItem("update_ad_hash");
            }, )
        }
    }
]

const container = document.getElementById('primary') || document.body;
const styleConfig = {
    width: '80%',
    height: '90%',
    maxWidth: '80%',
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
        finishText: 'Aggiorna',
        finishColor: '#28a745',
        buttonPosition: 'center',
        prevClass: 'input-petbuy secondary-button', // Classe personalizzata per "Indietro"
        nextClass: 'input-petbuy',        // Classe personalizzata per "Prossimo"
        finishClass: 'input-petbuy finish-button',    // Classe personalizzata per "Fine"
        width: 'auto'
    },
    closeButton: {
        show: true,
        color: '#000',
        size: '3rem'
    }
}; 

const popup = new MultiPagePopup(
    page,
    container,
    'fade',
    styleConfig,
    true,
    null,
    true,
    false
)

export function update_advertisement()
{
    jQuery(document).on('click', '.update-user-ad', function () {
        const adElement = jQuery(this).closest(".advertisement");
        sessionStorage.setItem("update_user_ad", JSON.stringify({price : adElement.data('price'), phone : adElement.data('contact'), descr : adElement.data('descr'), title : adElement.data('title'), health : adElement.data('health'), gift : adElement.data('gift'), sale : adElement.data('on-sale'), saleprice : adElement.data('saleprice')}))
        sessionStorage.setItem("update_ad_hash", adElement.data('hash'))
        popup.openPopup()
    });
}