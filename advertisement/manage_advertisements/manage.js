import { delete_user_ad, read_user_ads, update_user_ad } from "../user_advertisements/main.js";


jQuery(document).ready(function () {
    read_user_ads();
    delete_user_ad();
    update_user_ad();
});