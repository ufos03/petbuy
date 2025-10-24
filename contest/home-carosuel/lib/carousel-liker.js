import { popup_handler } from '../../../login/login-popup.js';
import { like_post_api } from './api.js';


function get_user() {
    const user_token = localStorage.getItem("user");

    if (user_token == null)
        return -1;
    return user_token;
}

function print_error_like(data, post)  // se c'è errore risettare data-has-liked al valore precedente
{
    console.log(data);
    const real_num_likes = parseInt(jQuery(document).find(`[data-post-id-nums='${post}']`).attr("data-real-likes"));
    const has_liked = jQuery(document).find(`[data-post-id='${post}']`).attr("data-has-liked");

    if (has_liked == "true")
        jQuery(document).find(`[data-post-id='${post}']`).addClass("liked-post like_animation");

    else if (has_liked == "false")
        jQuery(document).find(`[data-post-id='${post}']`).removeClass("liked-post like_animation");

    jQuery(document).find(`[data-post-id-nums='${post}']`).html(real_num_likes);
}

function handle_like_frontend(post) {
    const real_num_likes = parseInt(jQuery(document).find(`[data-post-id-nums='${post}']`).attr("data-real-likes"));
    let new_num_likes;
    const has_liked = jQuery(document).find(`[data-post-id='${post}']`).attr("data-has-liked");

    if (has_liked == "true") {
        new_num_likes = real_num_likes - 1;
        jQuery(document).find(`[data-post-id='${post}']`).removeClass("liked-post like_animation");
        setTimeout(() => {
            jQuery(document).find(`[data-post-id='${post}']`).addClass("dislike-animation");
        }, 100);
    }
    else if (has_liked == "false") {
        new_num_likes = real_num_likes + 1;
        jQuery(document).find(`[data-post-id='${post}']`).removeClass("dislike-animation");
        setTimeout(() => {
            jQuery(document).find(`[data-post-id='${post}']`).addClass("liked-post like_animation");
        }, 100);
    }

    jQuery(document).find(`[data-post-id-nums='${post}']`).html(new_num_likes);
}

function save_like_on_server(data, post) {
    const real_num_likes = parseInt(jQuery(document).find(`[data-post-id-nums='${post}']`).attr("data-real-likes"));

    if (data.action == "SUB") {
        jQuery(document).find(`[data-post-id='${post}']`).attr("data-has-liked", "false");
        jQuery(document).find(`[data-post-id-nums='${post}']`).attr("data-real-likes", real_num_likes - 1);
    }
    else if (data.action == "ADD") {
        jQuery(document).find(`[data-post-id='${post}']`).attr("data-has-liked", "true");
        jQuery(document).find(`[data-post-id-nums='${post}']`).attr("data-real-likes", real_num_likes + 1);
    }
}

function handle_like(event) {
    const post_id = jQuery(event.target).data("post-id");
    const user_token = get_user();

    if (user_token == -1)
        return popup_handler(); // non loggato -> popup-login   

    handle_like_frontend(post_id);

    like_post_api
        (
            save_like_on_server,
            print_error_like,
            post_id,
            user_token
        );
}

export function like_post() {
    setTimeout(() => {
        jQuery(document).on("click", ".like-trigger", handle_like);
    }, 850);
}