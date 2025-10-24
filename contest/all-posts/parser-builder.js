import { get_posts_contest_api } from "../lib/api.js";
import { get_user } from "../lib/contest-utils.js";
import { build_card } from "../lib/card-builder.js";
import { share_post } from "../lib/share-post.js";
import { build_filters_ui } from "./filter.js";
import { show_loader, hide_loader, construct_loader } from "../lib/fancy-loader.js";

construct_loader(".loader-posts");

function build_posts(posts)
{
    posts.forEach(post => {
        const card = build_card(post, true);
        jQuery("#posts-grid").append(card);
    });
    share_post();
    build_filters_ui(get_user());
    hide_loader(".loader-posts");
}

function print_error(error)
{
    console.log("enter print_error")
    console.log(error);
}

export function get_all_posts()
{
    show_loader(".loader-posts");
    get_posts_contest_api
    (
        build_posts,
        print_error,
        -1,
        get_user(),
        "all"
    );
}