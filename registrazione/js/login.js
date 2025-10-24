

function checkData()
{
    if (isEmpty(jQuery("#user").val()))
        throwErrorTo("#user")
    else
        removeErrorFrom("#user")

    if (isEmpty(jQuery("#psw-login").val()))
        throwErrorTo("#psw-login")
    else
        removeErrorFrom("#psw-login")
}

function callLoginApi()
{
    const data = {
        user: jQuery("#user").val(),
        pass: jQuery("#psw-login").val()
    }

    jQuery.ajax({
        type: "POST",
        url: "https://petbuy-local.ns0.it:8080/wp-json/api/v1/loginuserpt",
        data: JSON.stringify(data),
        contentType: "application/json; charset=UTF-8",
        async: false,
        success: function (response) {
            localStorage.setItem("user", response.user);
            window.location.assign("https://petbuy-local.ns0.it:8080/");
        },
        error: function (error) {
            showError(error.responseJSON.status)
        }
    });
}


jQuery(document).ready(function () {
    verifyData(checkData);
    const errors = jQuery(".error").length;
    
    jQuery(".login-button").on("click", function () 
    {
        if (errors == 0)
            callLoginApi()
        else
            alert("Errore nei dati inseriti");
    });


});