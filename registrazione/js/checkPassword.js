
function validatePassword2(psw)
{
    const labels = ["bad", "medium", "good"];
    let indicator = -1;

    if (/[a-z]/.test(psw)) 
        indicator++;

    if (/[A-Z]/.test(psw)) 
        indicator++;

    if (/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[?!&(%$)=@*_#])[a-zA-Z\d?!&(%$)=@*_#]{12,}$/.test(psw))
        indicator++;

    return labels[indicator];
}

function hasInvalidChars(psw)
{
    if (/[£"'\§\[\]*/\+-]/.test(psw))
    {
        jQuery("#pass").addClass("error");
        appendPopup("#info-trigger", "<div class='popup'><span>Caratteri non ammessi: £\"'\\§[]*/+-</span></div>")
        jQuery(".forward").attr("data-valid", "false");
    }
    else
    {
        removePopup("#info-trigger")
        jQuery("#pass").removeClass("error");
    }
}

function showLevelPassword()  // not allowed : £"'\§[]*/+-
{
    jQuery("#pass").on("input", function () {
        const userPsw = jQuery("#pass").val();
        hasInvalidChars(userPsw);
        const level = validatePassword2(userPsw);
        jQuery(".ps-level").addClass(level);
        
        if (level == "medium")
        {
            jQuery(".ps-level").removeClass("bad");
            jQuery(".ps-level").removeClass("good");
        }
        
        else if (level == "good")
        {
            jQuery(".ps-level").removeClass("medium");
            jQuery(".ps-level").removeClass("bad");
        }
        
        else if (level == "bad")
            jQuery(".ps-level").removeClass("medium");
        else if (userPsw.length == 0)
            jQuery(".ps-level").removeClass("bad");

    });
}

function showPassNoMatch()
{
    if (jQuery("#pass").val() != jQuery("#c_pass").val())
        jQuery("#c_pass").addClass("error");
    else if (jQuery("#pass").val() == jQuery("#c_pass").val() && jQuery("#c_pass").hasClass("error"))
        jQuery("#c_pass").removeClass("error");
}

function validateConfirmationPassword()
{
    jQuery("#c_pass").on("input", function () {
        showPassNoMatch()
    });

    jQuery("#pass").on("input", function () {
        showPassNoMatch()
    });
}

function showHidePassword()
{
    jQuery(document).on("click", "#password-visible", function () {
        const inputType = jQuery("#c_pass").attr("type"); 
        if (inputType == "password")
        {
            jQuery("#c_pass").attr("type", "text");
            jQuery("#password-visible").text("visibility");
        }

        else if (inputType == "text")
        {
            jQuery("#c_pass").attr("type", "password");
            jQuery("#password-visible").text("visibility_off");
        }
    });
}

function appendPopup(el, content)
{
    if (jQuery(el).hasClass("has-popup") == true)
        return
    jQuery(el).prepend(content);
    jQuery(el).addClass("has-popup");
}

function removePopup(el)
{
    jQuery(".popup").remove();
    jQuery(el).removeClass("has-popup");
}

function showPopupInfo() {
    jQuery("#info-trigger").hover(function () {
        appendPopup(this, "<div class='popup'><span>Almeno 12 caratteri tra cui 1 carattere speciale, 1 numero e 1 carattere maiuscolo</span></div>")
    }, function () {
        removePopup(this)
    });
}

function checkLenghtOfParamsPass()
{
    if (isEmpty(jQuery("#pass").val()))
        throwErrorTo("#pass")
    else
        removeErrorFrom("#pass")

    if (isEmpty(jQuery("#c_pass").val()))
        throwErrorTo("#c_pass")
    else
        removeErrorFrom("#c_pass")

    if (jQuery(".ps-level").hasClass("good") == false)
        throwErrorTo("#pass")
    else
        removeErrorFrom("#pass")
}

function getpassword()
{
    const data = {password : jQuery("#pass").val()};
    return data;
}

jQuery(document).ready(function () {
    showLevelPassword()
    showHidePassword()
    validateConfirmationPassword()
    showPopupInfo()
    verifyData(checkLenghtOfParamsPass)
    cryptAndSaveData(getpassword, "step2")
});