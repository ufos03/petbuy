function isMobile()
{
    if( navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i)
        || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i))
    {
        return true
    }
    return false
}

function hideLogoOnFocus()
{
    jQuery("#cap").focus(function (e) { 
        e.preventDefault();
        jQuery(".logo-container").addClass("hide-logo");
    });

    jQuery("#cap").on("focusout", function (e) { 
        e.preventDefault();
        jQuery(".logo-container").removeClass("hide-logo");
    });

    jQuery("#indirizzo").focus(function (e) { 
        e.preventDefault();
        jQuery(".logo-container").addClass("hide-logo");
    });

    jQuery("#indirizzo").on("focusout", function (e) { 
        e.preventDefault();
        jQuery(".logo-container").removeClass("hide-logo");
    });
}


function isEmpty(element)
{
    if (element.length == 0)
        return true;
    return false;
}

function throwErrorTo(element)
{
    jQuery(element).addClass("error");
}

function removeErrorFrom(element)
{
    jQuery(element).removeClass("error");
}

function cryptAndSaveData(callback, key)
{
    jQuery(".forward").on("click", function () {
        const errors = jQuery(".error").length
        if (errors > 0)
            return
        encodeData(JSON.stringify(callback()) , key);
    });
}

function assembleData()
{
    const step1 = getData("step1");
    const step2 = getData("step2");
    const step3 = getData("step3");   
}

function verifyData(callback)
{
    jQuery(".forward").on("click", function () {
        callback()
        const errors = jQuery(".error").length
        if (errors > 0)
            jQuery(this).attr("data-valid", "false");
        else
            jQuery(this).attr("data-valid", "true");
    });
}

jQuery(document).ready(function () {
    if (isMobile())
        hideLogoOnFocus();
});