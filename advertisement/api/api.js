
const new_ad_url = "https://petbuy-local.ns0.it:8080/wp-json/api/v1/advertisement/create";
const read_user_ads = "https://petbuy-local.ns0.it:8080/wp-json/api/v1/advertisement/read/user"
const delete_user_ad = "https://petbuy-local.ns0.it:8080/wp-json/api/v1/advertisement/delete"
const update_user_ad = "https://petbuy-local.ns0.it:8080/wp-json/api/v1/advertisement/update"

const get_all_ads = "https://petbuy-local.ns0.it:8080/wp-json/api/v1/advertisement/read/all"

export function call_new_ad_api(form_object = FormData, callback_success, callback_error) {
    jQuery.ajax({
        type: "POST",
        url: new_ad_url,
        enctype: 'multipart/form-data',
        data: form_object,
        processData: false,
        contentType: false,
        cache: false,
        success: function (response) {
            callback_success(response);
        },
        error: function (error) {
            callback_error(error.responseJSON.status);
        }
    });
}


export function call_get_user_ads_api(user_token, callback_success, callback_error) {
    jQuery.ajax({
        type: "GET",
        url: read_user_ads,
        data: { token: user_token },
        success: function (response) {
            callback_success(response);
        },
        error: function (error) {
            callback_error(error.responseJSON.status);
        }
    });
}


export function call_delete_ad_api(user_token, ad_hash, callback_success, callback_error) {
    jQuery.ajax({
        type: "DELETE",
        url: delete_user_ad,
        data: JSON.stringify({ "user_token": user_token, "ad_hash": ad_hash }),
        contentType: "application/json; charset=UTF-8",
        success: function (response) {
            callback_success(response)
        },
        error: function (error) {
            callback_error(error.responseJSON.status);
        }
    });
}

export function call_update_ad_api(user_token, ad_hash, gift_state, sale_state, price, contact, description, new_title, health_descr, callback_success, callback_error) {
    jQuery.ajax({
        type: "PUT",
        url: update_user_ad,
        data: JSON.stringify({ "user_token": user_token, "ad_hash": ad_hash, "gift": gift_state, "on_sale": sale_state, "new_price": Number(price), "new_contact": contact, "description": description, "title": new_title, "health": health_descr }),
        contentType: "application/json; charset=UTF-8",
        success: function (response) {
            callback_success(response)
        },
        error: function (error) {
            callback_error(error.responseJSON.status);
        }
    });
}

export async function call_get_all_ads_api(params, callback_success, callback_error) {
    // Prepare the data to send
    const requestData = {
        min_price: params.min_price || '',
        max_price: params.max_price || '',
        category: params.category || '',
        sub_category: params.sub_category || '',
        sex: params.sex || '',
        gift: params.gift || '',
        page: params.page || 1,
        per_page: params.per_page || 10,
        order_by: params.order_by || 'price',
        order: params.order || 'asc'
    };

    // Construct query string from requestData
    const queryString = new URLSearchParams(requestData).toString();
    const url = `${get_all_ads}?${queryString}`;

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (response.ok) {
            callback_success(data);
        } else {
            callback_error(data.status);
        }
    } catch (error) {
        callback_error(error.message);
    }
}
