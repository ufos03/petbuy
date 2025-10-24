
const new_post_url = "https://petbuy-local.ns0.it:8080/wp-json/api/v1/newparticipant";

export function create_new_post_api(form_object, callback_success, callback_error)
{
    jQuery.ajax({
        type: "POST",
        url: new_post_url,
        enctype: 'multipart/form-data',
        data: form_object,
        processData: false,
        contentType: false,
        cache: false,
        success: function (response) {
            callback_success(response);
        },
        error: function (response) {
            callback_error(response);
        }
    });
}