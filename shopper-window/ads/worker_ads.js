import { call_get_all_ads_api } from "../../advertisement/api/api.js";
import { build_single_ad } from "../../advertisement/shop-window/build_shop_ad.js";

let ads_grid = '';

function build_ads_ui(ads)
{ 
    ads_grid = '';
    ads.content.forEach(ad => {
        ads_grid += build_single_ad(ad);
    });

    postMessage(ads_grid);
}

self.onmessage = async (e) => {
    await call_get_all_ads_api(e.data, build_ads_ui, console.log);
};