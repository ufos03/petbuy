

function checkLenghtOfParams() {

    if (isEmpty(jQuery("#name").val()))
        throwErrorTo("#name")
    else
        removeErrorFrom("#name")

    if (isEmpty(jQuery("#surname").val()))
        throwErrorTo("#surname")
    else
        removeErrorFrom("#surname")

    if (isEmpty(jQuery("#mail").val()))
        throwErrorTo("#mail")
    else
        removeErrorFrom("#mail")

    if (isEmpty(jQuery("#phone").val()))
        throwErrorTo("#phone")
    else
        removeErrorFrom("#phone")

    if (isEmpty(jQuery("#codice_fiscale").val()))
        throwErrorTo("#codice_fiscale")
    else
        removeErrorFrom("#codice_fiscale")

    if (isEmpty(jQuery("#nickname").val()))
        throwErrorTo("#nickname")
    else
        removeErrorFrom("#nickname")
}

function checkUsernameApi()
{
    const data= {nickname : jQuery("#nickname").val()}
    jQuery.ajax({
        type: "POST",
        url: "https://petbuy-local.ns0.it:8080/wp-json/api/v1/usernamexists",
        data: JSON.stringify(data),
        contentType: "application/json; charset=UTF-8",
        success: function (response) {
            jQuery(".available-nickname").html("");
            jQuery("#nickname").removeClass("error");
            jQuery("#nickname").addClass("success-nick-email");
        },
        error: function (error){
            jQuery("#nickname").removeClass("success-nick-email");
            jQuery("#nickname").addClass("error");
            jQuery(".available-nickname").html("");
            jQuery(".available-nickname").html(error.responseJSON.status);
        }
    });
}

function checkUsername()
{
    const regex = /\s/;
    jQuery(document).on("input", "#nickname", function () {
        setTimeout(() => {

            if(regex.test(jQuery("#nickname").val()))
            {
                jQuery("#nickname").removeClass("success-nick-email");
                jQuery("#nickname").addClass("error");
                jQuery(".available-nickname").html("");
                jQuery(".available-nickname").html("Non sono ammessi spazi");
                return;
            }

            checkUsernameApi()
        }, 600);
    });
}

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

function getDataFromForm()
{
    const data = {name : jQuery("#name").val(), surname :  jQuery("#surname").val(), mail :  jQuery("#mail").val(), phone : jQuery("#phone").val(), cf : jQuery("#codice_fiscale").val(), nickname : jQuery("#nickname").val()};
    return data;
}

jQuery(document).ready(function () {
    verifyData(checkLenghtOfParams)
    cryptAndSaveData(getDataFromForm, "step1")
    popupHandler()
    triggerCalculator()
    checkUsername()
    checkEmail()
});