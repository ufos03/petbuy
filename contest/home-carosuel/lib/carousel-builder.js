import { get_posts_contest_api } from '../../lib/api.js';
import { get_user } from '../../lib/contest-utils.js';
import { build_card } from '../../lib/card-builder.js';
import { share_post } from "../../lib/share-post.js";

const carosuel_container = "#contest-carousel > .content-swiper-contest > .swiper-wrapper";

function initSwiperContest()
{
	const swiper = new Swiper('.content-swiper-contest', {
		clickable:  true,
		spaceBetween : 60,
        slidesPerView: 1,
		direction: "horizontal",
		autoplay : {
			delay : 15000,
			disableOnInteraction: false,
		},
        pagination: {
            el: ".swiper-pagination-contest",
            dynamicBullets: true,
            clickable: true
        },
	})
	return swiper;
}



function build_carousel(data)
{
    jQuery("#contest-carousel > .loader-inner").remove();
    jQuery("#contest-quick-actions").append("<a href='https://petbuy-local.ns0.it:8080/contest/' target='_blank' id='all-post-link' class='quick-action button secondary-button'>Tutti i post</a>");
    if (get_user() != -1)
        jQuery("#contest-quick-actions").append("<a id='new-post' class='quick-action button primary-button'>Nuovo post</a>");

    initSwiperContest();

    data.forEach(card => 
    {
        const new_card = build_card(card);
        jQuery(carosuel_container).append(new_card);
    });
    share_post()
}

function print_error(data)
{
    if (get_user() != -1)
        jQuery("#contest-carousel").append("<a id='new-post' class='button'>Nuovo post</a>");
    jQuery("#contest-carousel > .loader-inner").remove();
    jQuery("#contest-carousel").prepend(`<div class='error-msg'>${data}</div>`);
}

export function create_carousel()
{
    get_posts_contest_api
    (
        build_carousel,
        print_error,
        3,
        get_user()
    );
}