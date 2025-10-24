
const endpoint = "https://petbuy-local.ns0.it:8080/wp-json/api/v1/sendemailapi";


function sentEmailTo()
{
    const emailSent = localStorage.getItem('user_email').replaceAll('"', "");
    jQuery(".sendto").html(`Abbiamo inviato un link di verifica a <strong>${emailSent}</strong>`);
}



function sendNewLink()
{
    completeLink = endpoint.concat("=", localStorage.getItem('user_email').replaceAll('"', ""))
    jQuery.ajax({
        type: "GET",
        url: endpoint,
        data: {e : localStorage.getItem('user_email').replaceAll('"', "")},
        success: function (response) {
            jQuery(".server-response").text(response.status);
        },
        error: function (error) {
            console.log(error);
            jQuery(".server-response").text(error.responseJSON.status);
        }
    });
}

jQuery(document).ready(function () {
    sentEmailTo();
    jQuery(document).on("click", "a#verify-link", function () {
        sendNewLink()
    });
});