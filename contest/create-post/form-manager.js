import { create_new_post_api } from "./api.js";
import { close_popup } from "./UI.js";

function checkData() {
    if (jQuery("#image").val().length == 0)
        jQuery("#image").addClass("error")
    else
        jQuery("#image").removeClass("error")

    if (jQuery("#descr").val().length == 0)
        jQuery("#descr").addClass("error")
    else
        jQuery("#descr").removeClass("error")

    if (jQuery("#name_animal").val().length == 0)
        jQuery("#name_animal").addClass("error")
    else
        jQuery("#name_animal").removeClass("error")
}

function create_form_object()
{
    const data = new FormData(jQuery("#new-post-form")[0])
    data.append("token", localStorage.getItem('user'))
    return data;
}

function success(data)
{
    jQuery(".new-post-button").remove()
    jQuery(".button-place").empty()
    jQuery(".button-place").append(`<dotlottie-player src="https://lottie.host/13dff29f-34a2-4bd0-886c-780ea271193e/wgqRyD0xD6.json" background="transparent" speed="1" style="width: 80px; height: 80px" direction="1" playMode="normal" autoplay></dotlottie-player>`);  
    setTimeout(() => {
        close_popup()
    }, 3000);
}

function error(data)
{
    jQuery(".new-post-button").remove()
    jQuery(".button-place").empty()
    jQuery(".button-place").append(`<dotlottie-player src="https://lottie.host/a03089b0-d971-42c9-8389-c81cfe6b5cef/NLPPCKDtVi.json" background="transparent" speed="1" style="width: 80px; height: 80px" direction="1" playMode="normal" autoplay></dotlottie-player>`);
    setTimeout(() => {
        close_popup()
    }, 3000);
}

export function new_post_handler()
{
    jQuery(document).on("click", ".new-post-button", function () {
        checkData();
        const errors = jQuery(".error").length;
        if (errors == 0) {
            jQuery(".new-post-button").remove()
            jQuery(".button-place").append("<span class='loader-inner'></span>")
            const form = create_form_object();
            create_new_post_api(
                form,
                success,
                error
            )
        }
    });
}
