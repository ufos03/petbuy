
const endPointVerify = "https://petbuy-local.ns0.it:8080/wp-json/api/v1/verifyemail";

function showMessage(message, isError = true)
{
    if (isError)
        animation = `<dotlottie-player src="https://lottie.host/d5b3919b-7c2c-4629-a913-746d94d0b9bf/A5UHXLiPs0.json" background="transparent" speed="1" style="width: 120px; height: 120px" direction="1" playMode="normal" loop autoplay></dotlottie-player>`
    else
        animation = `<dotlottie-player src="https://lottie.host/13dff29f-34a2-4bd0-886c-780ea271193e/wgqRyD0xD6.json" background="transparent" speed="1" style="width: 120px; height: 120px" direction="1" playMode="normal" autoplay></dotlottie-player>`;

    const messageToShow = `<span class="message">${message}</span>`
    const content = animation + messageToShow;
    jQuery(".server-response").append(content);
}

function newLinkText()
{
    jQuery(".server-response").append(`<p class="resend-email">Clicca <a id="verify-link" class="petbuy-link" href="#">qui</a> per una nuova email.</p>`)
}

function callApiToVerify()
{
    const urlParams = new URLSearchParams(location.search);
    jQuery.ajax({
        type: "GET",
        url: endPointVerify,
        data: {t: urlParams.get('t')},
        success: function (response) {
            showMessage(response.status, false)
            if (response.action == "TO_LOGIN")
            {
                setTimeout(() => {
                    setTimeout(() => {
                        localStorage.removeItem("user_email")
                    }, 600);
                    location.replace("https://petbuy-local.ns0.it:8080/login/")
                }, 2200);
            }
        },
        error: function (error) {
            showMessage(error.responseJSON.status, true)
            if (error.responseJSON.action == "NEW_LINK")
            {
                newLinkText()
                jQuery(document).on("click", "a#verify-link", function () {
                    sendNewLink()
                });
            }
        }
    });
}

jQuery(document).ready(function () {
    callApiToVerify()
});