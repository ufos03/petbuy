import { call_login_api } from "./api.js";
import { start_verification_captcha, get_captcha_result } from "../registrazione/js/pt-captcha.js";
import {initTWOFA} from "./two_fa.js";
import { get_user } from "https://petbuy-local.ns0.it:8080/wp-content/user-logged/main.js";
import { reset_password } from "./reset_password.js";

function checkData() {
    if (isEmpty(jQuery("#user").val()))
        throwErrorTo("#user")
    else
        removeErrorFrom("#user")

    if (isEmpty(jQuery("#psw-login").val()))
        throwErrorTo("#psw-login")
    else
        removeErrorFrom("#psw-login")
}

function show_error(text)  
{
    jQuery(".forward").attr("data-valid", "false");
    jQuery(".text-error").text(text);
    jQuery(".error-box").addClass("show-error");

    setTimeout(() => {
        jQuery(".error-box").removeClass("show-error");
        jQuery(".text-error").text("");
    }, 5000);
}

function redirectTO(response) {
    if(window.location.href == "https://petbuy-local.ns0.it:8080/login/" || window.location.href == "https://petbuy-local.ns0.it:8080/login")
    {
        window.location.assign("https://petbuy-local.ns0.it:8080/");
        return;
    }

    window.location.assign(window.location.href);
    return;
}

function start_TWOFA(response)
{
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function () {
        window.history.pushState(null, "", window.location.href);
    };

    localStorage.setItem("user", response.user);
    initTWOFA(redirectTO, show_error, "#registration-form");
}

function showMessage(message, isError = false)
{
    if(jQuery(".response").length)
        return;

    let animation;

    if (isError)
        animation = `<dotlottie-player src="https://lottie.host/d5b3919b-7c2c-4629-a913-746d94d0b9bf/A5UHXLiPs0.json" background="transparent" speed="1" style="width: 120px; height: 120px" direction="1" playMode="normal" loop autoplay></dotlottie-player>`
    else
        animation = `<dotlottie-player src="https://lottie.host/13dff29f-34a2-4bd0-886c-780ea271193e/wgqRyD0xD6.json" background="transparent" speed="1" style="width: 120px; height: 120px" direction="1" playMode="normal" autoplay></dotlottie-player>`;

    const messageToShow = `<span class="message">${message.status}</span>`
    const content = animation + messageToShow;

    jQuery("#registration-form").append(`<div class="server-response">${content}</div>`);
}

export function login_handler() {
    start_verification_captcha()
    jQuery(document).on("click", ".reset-link", function () {
        reset_password(showMessage, show_error)
    })

    verifyData(checkData);
    const errors = jQuery(".error").length;

    jQuery(".login-button").on("click", function () {

        if (errors == 0) 
        {
            const captcha_result = get_captcha_result();

            if (captcha_result == false)
            {
                show_error("Hai fallito il captcha!")
                return;
            }

            
            call_login_api(
                start_TWOFA,
                show_error,
                jQuery("#user").val(),
                jQuery("#psw-login").val(),
            );
        }
    });
}