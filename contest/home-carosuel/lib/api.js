
const contest_url = "https://petbuy-local.ns0.it:8080/wp-json/api/v1/getcontestposts";
const like_url = "https://petbuy-local.ns0.it:8080/wp-json/api/v1/likepost";

export function get_posts_contest_api(callback_success, callback_error ,limit = 5, token = -1, mode = "carousel") 
{
    jQuery.ajax({
        type: "GET",
        url: contest_url,
        data: {"limit": limit, "token": token, "mode": mode},
        success: function (response) {
            callback_success(response);
        },
        error: function (error) {
            callback_error(error.responseJSON.status);
        },
    });
}


export function like_post_api(callback_success, callback_error, post_id, token) 
{
    jQuery.ajax({
        type: "POST",
        url: like_url,
        data: {"post_id": post_id, "token": token},
        success: function (response) {
            callback_success(response, post_id);
        },
        error: function (response) {
            callback_error(response, post_id);
        },
    });
}
