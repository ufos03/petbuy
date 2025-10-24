
const loginURL = "https://petbuy-local.ns0.it:8080/wp-json/api/v1/loginuserpt";
const logoutURL = "https://petbuy-local.ns0.it:8080/wp-json/api/v1/logoutuserpt";
const twofaURL = "https://petbuy-local.ns0.it:8080/wp-json/api/v1/verify_two_fa";
const resetPasswordURL = "https://petbuy-local.ns0.it:8080/wp-json/api/v1/send_email_reset_password";


export function call_logout_api(user, callback_success, callback_error)
{
    const data = {
        token: user
    }

    jQuery.ajax({
        type: "POST",
        url: logoutURL,
        data: JSON.stringify(data),
        contentType: "application/json; charset=UTF-8",
        success: function (response) {
            callback_success(response)
        },
        error: function (error) {
            callback_error(error.responseJSON.status);
        }
    });
}

export function call_login_api(callback_success, callback_error, user, pass)
{
    const data = {
        user: user,
        pass: pass,
    }

    jQuery.ajax({
        type: "POST",
        url: loginURL,
        data: JSON.stringify(data),
        contentType: "application/json; charset=UTF-8",
        success: function (response) {
            callback_success(response);
        },
        error: function (error) {
            callback_error(error.responseJSON.status);
        }
    });
}

export function call_2fa_api(callback_success, callback_error, otp_code, user_token)
{
    const data = {
        otp: otp_code,
        token: user_token
    }

    jQuery.ajax({
        type: "POST",
        url: twofaURL,
        data: JSON.stringify(data),
        contentType: "application/json; charset=UTF-8",
        success: function (response) {
            callback_success(response);
        },
        error: function (error) {
            callback_error(error.responseJSON.status);
        }
    });
}

export function call_send_mail_to_reset_api(email_user, callback_success, callback_error)
{
    const data = {
        email: email_user
    }

    jQuery.ajax({
        type: "POST",
        url: resetPasswordURL,
        data: JSON.stringify(data),
        contentType: "application/json; charset=UTF-8",
        success: function (response) {
            callback_success(response)
        },
        error: function (error) {
            callback_error(error.responseJSON.status);
        }
    });
}