import { call_get_all_prods_api, build_product_card } from "../../products/main.js";

let products = '';

function build_prods_ui(prods)
{
    products = '';
    prods.content.forEach(prod => {
        products += build_product_card(prod);
    });
    postMessage(products);
}

self.onmessage = async (e) => {
    await call_get_all_prods_api(e.data, build_prods_ui, console.log);
};