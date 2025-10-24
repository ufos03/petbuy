

export function show_loader(container)
{
    jQuery(".loader-pt").css("visibility", "visible");
    jQuery(container).removeClass("loader-hidden");
    jQuery(container).addClass("loader-visible");
}

export function hide_loader(container)
{
    jQuery(".loader-pt").css("visibility", "hidden");
    jQuery(container).removeClass("loader-visible");
    jQuery(container).addClass("loader-hidden");
}

export function construct_loader(container)
{
    jQuery(document).ready(function () {
        jQuery(container).html(`<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script><dotlottie-player src="https://lottie.host/bc73059b-e571-4a06-a42b-3f23d474260e/did9zMjB4M.lottie" background="transparent" speed="1" style="width: 200px; height: 200px" direction="1" playMode="normal" loop autoplay class="loader-post"></dotlottie-player>`);
    });
    hide_loader(container);
}