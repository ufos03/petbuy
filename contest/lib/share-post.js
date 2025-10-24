import { get_posts_contest_api } from "./api.js";
import { get_user } from "./contest-utils.js";
import { build_card } from "./card-builder.js";

function copy_to_clipboard(text) {
    navigator.clipboard.writeText(text)
}

export function share_post()
{
    jQuery(".share-trigger").click(function()
    {
        const post_link = jQuery(this).attr("data-post-link");
        copy_to_clipboard(post_link);
        jQuery(this).remove();
        jQuery(jQuery(document).find(`[data-post-link-id='${post_link}']`)).append("<span class='material-symbols-outlined copy-ok'>check</span>&nbsp<span class='success-msg'>Copiato!</span>");
    });
}

function show_post_in_popup(data)
{
    jQuery("body").prepend("<div class='new-post-mask' id='shared-post-popup'></div>");
    jQuery("#shared-post-popup").append(`
                <main id="petbuy-forms-container" class="flex-center-center contest">
                    <div class = "pt-popup">
                        ${build_card(data[0], true)}
                    </div>
                </main>
    `);
    jQuery("#pt-header").css("z-index", "0");
    jQuery("#shared-post-popup").addClass("new-post-active");
    console.log(data);
}

function get_post(post_link)
{
    get_posts_contest_api(
        show_post_in_popup,
        console.log,
        1,
        get_user(),
        'single',
        post_link
    )
}

export function see_shared_post()
{
    const urlParams = new URLSearchParams(window.location.search);
    const post_link = urlParams.get('l');

    if (post_link != null)
    {
        get_post(post_link)
    }
}