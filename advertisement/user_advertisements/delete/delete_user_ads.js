import { OutputMessage } from "../../../form-utils/output-message.js";
import { get_user } from "../../../user-logged/main.js";
import { call_delete_ad_api } from "../../api/api.js";
import { build_ads_for_user } from "../read/read_user_ads.js";

export function delete_advertisement()
{
    jQuery(document).on("click touch", ".delete-user-ad", function () {

        const adElement = jQuery(this).closest('.advertisement');
        const advertisement_hash = adElement.data('hash');

        call_delete_ad_api(
            get_user(),
            advertisement_hash, 
            function (){
                adElement.remove();
                const success_message = new OutputMessage({
                    text: "L'annuncio é stato eliminato con successo.",
                    position: 'bottom-right',
                    type: "success",
                    autoClose: true,
                    duration: 3000,
                    animation: 'zoom',
                    animationDuration: 500
                })
                build_ads_for_user();
            },
            function() {
                const error_message = new OutputMessage({
                    text: "Si é verificato un errore. Riprova.",
                    position: 'bottom-right',
                    type: "error",
                    autoClose: true,
                    duration: 3000,
                    animation: 'zoom',
                    animationDuration: 500
                })
            }
    )    
    });
}