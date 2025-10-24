import { get_all_posts } from "./parser-builder.js";
import { like_post } from "../lib/carousel-liker.js";
import { see_shared_post } from "../lib/share-post.js";

jQuery(document).ready(function () {
    get_all_posts();
    like_post();
    see_shared_post();
});