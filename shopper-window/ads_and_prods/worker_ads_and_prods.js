import { build_single_ad } from "../../advertisement/shop-window/build_shop_ad.js";
import { build_product_card } from "../../products/shop_windows.js";
import { call_get_prods_and_ads } from "./api.js";

let grid = '';

function build_ads_and_prods_ui(combined_ads_and_prods)
{
    grid = '';
    const collection = combined_ads_and_prods.content;

    collection.forEach(item => {
        if (item.type === 'product')
            grid += build_product_card(item);
        else if (item.type === 'advertisement')
            grid += build_single_ad(item);
    });
    postMessage(grid);
}

self.onmessage = async (e) => {
    await call_get_prods_and_ads(e.data, build_ads_and_prods_ui, console.log);
};