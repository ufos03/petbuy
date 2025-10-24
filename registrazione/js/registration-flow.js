import { start_verification_captcha, get_captcha_result } from "https://petbuy-local.ns0.it:8080/wp-content/registrazione/js/pt-captcha.js";

const pages = [
    "https://petbuy-local.ns0.it:8080/dati-personali/",
    "https://petbuy-local.ns0.it:8080/password/",
    "https://petbuy-local.ns0.it:8080/localizzazione/"
]

function goForward()
{
    jQuery(".forward").click(function (e) { 
        e.preventDefault();
        const actualPage = jQuery(this).data("location");
        
        if(actualPage  == "MAX" && jQuery(this).attr("data-valid") == "true")
        {
            setTimeout(() => {
                location.replace("https://petbuy-local.ns0.it:8080/email/")
            }, 600);
        }
        
        if (actualPage == "nan1" || actualPage  == "MAX")
            return

        if (jQuery(this).attr("data-valid") == "true")
        {
            const result_captcha = get_captcha_result()

            if (result_captcha == true)
                location.assign(pages[actualPage + 1])
            else
            {
                showError("Hai fallito il captcha!")
                return;
            }

        }

        if (actualPage == -1 && jQuery(this).attr("data-role") == "personal")
        {
            localStorage.setItem("role", "customer");
            location.assign("https://petbuy-local.ns0.it:8080/dati-personali/")
        }

        if (actualPage == -1 && jQuery(this).attr("data-role") == "business")
        {
            localStorage.setItem("role", "vendor");
            location.assign("https://petbuy-local.ns0.it:8080/dati-azienda/")
        }

    });
}

function goBackWard()
{
    jQuery(".backward").click(function (e) { 
        e.preventDefault();
        const actualPage = jQuery(this).data("location");
        if (actualPage == 0)
            location.assign("https://petbuy-local.ns0.it:8080/scelta/")
        else
            location.assign(pages[actualPage - 1])
    });
}

function goToRegistration()
{
    jQuery(".reg-button").click(function (e) {
        e.preventDefault()
        location.assign("https://petbuy-local.ns0.it:8080/scelta/")
    })
}


jQuery(document).ready(function () {
    goToRegistration()

    setTimeout(() => {
        start_verification_captcha();
        goForward()
        goBackWard()
    }, 400);
});