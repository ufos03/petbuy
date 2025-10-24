import { get_posts_contest_api, search_posts_api } from "../lib/api.js";
import { get_user, clean_board } from "../lib/contest-utils.js";
import { build_card } from "../lib/card-builder.js";
import { share_post } from "../lib/share-post.js";
import { show_loader, hide_loader, construct_loader } from "../lib/fancy-loader.js";


construct_loader(".loader-posts");

export function build_filters_ui(user)
{
    jQuery(".tools").append(`
    ${user == -1 ? "" : "<div><input type='checkbox' id='user-post' name='user-post' value='user-post'>&nbsp<span>I miei post</span></div>"}

    `);

    listener_on_user_post();
    listener_on_search();
}

function listener_on_user_post()
{
    jQuery("#user-post").change(function()
    {
        show_loader(".loader-posts");
        if (this.checked)
            get_posts_contest_api(build_posts, handle_error, -1, get_user(), "user");
        else
            get_posts_contest_api(build_posts, handle_error, -1, get_user(), "all");
    });
}

function handle_error(error)
{
    clean_board();
    hide_loader(".loader-posts")
    jQuery("#posts-grid").append(error);
}

function build_posts(posts)
{
    clean_board();

    if(posts.length == 1)
    {
        jQuery("#posts-grid").css("--grid-column-count","1");
        jQuery("#posts-grid").css("width","50%");
    }
    else if(posts.length == 2)
    {
        jQuery("#posts-grid").css("--grid-column-count","2");
        jQuery("#posts-grid").css("width","inherit");
    }
    else
    {
        jQuery("#posts-grid").css("--grid-column-count","");
        jQuery("#posts-grid").css("width","");
    }

    posts.forEach(post => {
        const card = build_card(post, true);
        jQuery("#posts-grid").append(card);
    });
    share_post();
    hide_loader(".loader-posts");
}

function listener_on_search()
{
    jQuery("#search-input").on("input", function()
    {
        setTimeout(() => {
            search_posts_api(build_posts, console.log, jQuery("#search-input").val(), get_user()); // rivedere la ricerca
        }, 800);
    });
}

