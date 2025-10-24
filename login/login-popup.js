import { login_handler } from "./login.js"; 

function close_popup()
{
    jQuery(".login-popup-mask > * ").css("display", "none");
    setTimeout(() => {
        jQuery("#pt-header").css("z-index", "");
        jQuery(".login-popup-mask").removeClass("login-popup-active");
    }, 150);
}

function close_popup_on_document()
{
    jQuery(document).on('click touch', ".login-popup-mask", function(e) {
        var container = jQuery('#petbuy-formx');
    
        if (jQuery(e.target).closest(container).length == 0) {
          close_popup()
        }
    });
}


function show_popup()
{
    jQuery("#pt-header").css("z-index", "0");
    jQuery(".login-popup-mask").addClass("login-popup-active");
    jQuery(".login-popup-mask > * ").css("display", "flex");

    login_handler();
}

export function popup_handler()
{
    show_popup()
    close_popup_on_document()
}
