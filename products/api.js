const get_all_prods = 'https://petbuy-local.ns0.it:8080/wp-json/api/v1/products/read/all';

export async function call_get_all_prods_api(params, callback_success, callback_error) 
{
    // Prepara i dati da inviare
    const requestData = {
        min_price: params.min_price || '',
        max_price: params.max_price || '',
        category: params.category || '',
        sub_category: params.sub_category || '',
        page: params.page || 1,
        per_page: params.per_page || 10,
        order_by: params.order_by || 'date',
        order: params.order || 'desc'
    };

    // Costruisce la query string
    const queryString = new URLSearchParams(requestData).toString();

    // Costruisce l'URL completo
    const url = `${get_all_prods}?${queryString}`;

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