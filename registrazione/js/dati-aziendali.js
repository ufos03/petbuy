function checkEmailApi()
{
    const data= {email : jQuery("#mail").val()}
    jQuery.ajax({
        type: "POST",
        url: "https://petbuy-local.ns0.it:8080/wp-json/api/v1/emailexist",
        data: JSON.stringify(data),
        contentType: "application/json; charset=UTF-8",
        success: function (response) {
            jQuery(".available-mail").html("");
            jQuery("#mail").removeClass("error");
            jQuery("#mail").addClass("success-nick-email");
        },
        error: function (error){
            jQuery("#mail").removeClass("success-nick-email");
            jQuery("#mail").addClass("error");
            jQuery(".available-mail").html("");
            jQuery(".available-mail").html(error.responseJSON.status);
        }
    });
}

function checkEmail()
{
    jQuery(document).on("input", "#mail", function () {
        setTimeout(() => {
            checkEmailApi()
        }, 600);
    });
}

function checkUsernameApi()
{
    const data= {nickname : jQuery("#username").val()}
    jQuery.ajax({
        type: "POST",
        url: "https://petbuy-local.ns0.it:8080/wp-json/api/v1/usernamexists",
        data: JSON.stringify(data),
        contentType: "application/json; charset=UTF-8",
        success: function (response) {
            jQuery(".available-nickname").html("");
            jQuery("#username").removeClass("error");
            jQuery("#username").addClass("success-nick-email");
        },
        error: function (error){
            jQuery("#username").removeClass("success-nick-email");
            jQuery("#username").addClass("error");
            jQuery(".available-nickname").html("");
            jQuery(".available-nickname").html(error.responseJSON.status);
        }
    });
}


function checkUsername()
{
    const regex = /\s/;
    jQuery(document).on("input", "#username", function () {
        setTimeout(() => {

            if(regex.test(jQuery("#username").val()))
            {
                jQuery("#username").removeClass("success-nick-email");
                jQuery("#username").addClass("error");
                jQuery(".available-nickname").html("");
                jQuery(".available-nickname").html("Non sono ammessi spazi");
                return;
            }

            checkUsernameApi()
        }, 600);
    });
}

function checkIVAApi()
{
    const packet = {tax_code : jQuery("#pi").val(), business_name : jQuery("#nome-azienda").val()}
    jQuery.ajax({
        type: "GET",
        url: "https://petbuy-local.ns0.it:8080/wp-json/api/v1/verify_p_iva",
        data: packet,
        contentType: "application/json; charset=UTF-8",
        async: false,
        success: function (response) {
            jQuery(".available-nickname").html("");
            jQuery("#pi").removeClass("error");
            jQuery("#pi").addClass("success-nick-email");
        },
        error: function (error){
            jQuery("#pi").removeClass("success-nick-email");
            jQuery("#pi").addClass("error");
        }
    });
}

function checkIVA()
{
    const numericRegex = /^\d{11}$/;
    jQuery(document).on("input", "#pi", function () {
        setTimeout(() => {
            if(jQuery("#pi").val().length == 0)
            {
                jQuery("#pi").removeClass("success-nick-email");
                jQuery("#pi").addClass("error");
                jQuery(".pi_available").html("");
                jQuery(".pi_available").html("Inserisci la partita IVA");
                return;
            }

            if(jQuery("#pi").val().length > 11)
            {
                jQuery("#pi").removeClass("success-nick-email");
                jQuery("#pi").addClass("error");
                jQuery(".pi_available").html("");
                jQuery(".pi_available").html("11 numeri");
                return;
            }

            if(!numericRegex.test(jQuery("#pi").val()) && jQuery("#pi").val().length == 11)
            {
                jQuery("#pi").removeClass("success-nick-email");
                jQuery("#pi").addClass("error");
                jQuery(".pi_available").html("");
                jQuery(".pi_available").html("Sono ammessi solo numeri");
                return;
            }
            else
            {
                jQuery("#pi").removeClass("error");
                jQuery(".pi_available").html("Partita IVA");
            }

        }, 600);
    });
}

function checkLenghtOfParams() {

    if (isEmpty(jQuery("#nome-azienda").val()))
        throwErrorTo("#nome-azienda")
    else
        removeErrorFrom("#nome-azienda")

    if (isEmpty(jQuery("#pi").val()))
        throwErrorTo("#pi")
    else
        removeErrorFrom("#pi")

    if (isEmpty(jQuery("#mail").val()))
        throwErrorTo("#mail")
    else
        removeErrorFrom("#mail")

    if (isEmpty(jQuery("#username").val()))
        throwErrorTo("#username")
    else
        removeErrorFrom("#username")

    checkIVAApi();
}

function getDataFromForm()
{
    const data = {azienda : jQuery("#nome-azienda").val(), pi :  jQuery("#pi").val(), mail :  jQuery("#mail").val(), username : jQuery("#username").val()};
    return data;
}

jQuery(document).ready(function () {
    cryptAndSaveData(getDataFromForm, "step1")
    checkEmail();
    checkUsername();
    checkIVA();
    verifyData(checkLenghtOfParams)
});