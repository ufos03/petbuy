
const siteKey = "6LeXcQoqAAAAAOVFOJsWnV-j_eExQzkFyEg6-ezp";

function captcha_verification(token)
{
    jQuery.ajax({
        type: "POST",
        url: "https://petbuy-local.ns0.it:8080/wp-json/api/v1/verifyhuman",
        data: JSON.stringify({"token" : token}),
        contentType: "application/json; charset=UTF-8",
        async: false,
        success: function (response) {
            sessionStorage.setItem("IS_HUMAN", "TRUE");
        },
        error: function (error) {
            sessionStorage.setItem("IS_HUMAN", "FALSE");
        }
    });
}

export function start_verification_captcha() 
{
    console.log("started")
    grecaptcha.ready(function() 
    {
        grecaptcha.execute(siteKey, {action: 'submit'}).then(function(token) 
        {
            captcha_verification(token);
        });
    });
}

export function get_captcha_result() 
{
    console.log("ended");
    if (sessionStorage.getItem("IS_HUMAN") === "TRUE")
        return true;
    return false;
}