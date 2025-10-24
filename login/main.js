import { logout_handler } from "./logout.js";

jQuery(document).ready(function () {
    jQuery(document).on("click", "#logout-link", function () {
        logout_handler()
	})
});