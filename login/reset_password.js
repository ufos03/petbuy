import { call_send_mail_to_reset_api } from "./api.js";

function call_reset_psw(success, error)
{
    jQuery(document).on("click", ".send-email-button", function () {
        call_send_mail_to_reset_api(
            jQuery("#mail").val(),
            success, 
            error
        )
    })

}

export function reset_password(success_function, error_function)
{
    jQuery("#registration-form").empty();
    jQuery("#registration-form").append(
        `
            <div class="section">
                <div class="container-input">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="mail" id="mail" class="input-petbuy" required="">
                </div>
            </div>

            <div class="section row">
                <input type="button" value="Invia" class="input-petbuy button send-email-button primary-button">
            </div>
        `	
    );

    call_reset_psw(success_function, error_function)
}