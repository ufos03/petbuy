import { create_carousel } from "./lib/carousel-builder.js";
import { like_post } from "./lib/carousel-liker.js";

jQuery(document).ready(function () {
    create_carousel();
    like_post();
});