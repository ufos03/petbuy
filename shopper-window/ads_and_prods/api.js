const get_all_ads_and_prods = "https://petbuy-local.ns0.it:8080/wp-json/api/v1/advertisement/read/mix"

export async function call_get_prods_and_ads(params, callback_success, callback_error) {
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
    const url = `${get_all_ads_and_prods}?${queryString}`;

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
