

export function build_card(card, vertical = false) {
    const liked_map = {
        "true": "liked-post",
        "false": ""
    }

    const card_html = `${vertical ? "" : "<div class='swiper-slide contest-slide'>"}
                            <div class="card-container ${vertical ? "vertical" : ""}">
                                <div class = "wrapper-card">
                                    <div class="card ${vertical ? "vertical-card" : ""}">
                                        ${card.post_content.image}
                                        <div>
                                            <h2>${card.post_content.name}</h2>
                                            <h3>${card.post_author}</h3>
                                            <p>
                                                ${card.post_content.description}
                                            </p>
                                            <div class="contest-actions">
                                                <div class="like-btn">
                                                    <span class="material-symbols-outlined like-trigger ${liked_map[card.has_liked_user]}" data-post-id = "${card.post_id}" data-has-liked = "${card.has_liked_user}">pets</span>
                                                    <span class="num-likes" data-post-id-nums = "${card.post_id}" data-real-likes = ${card.likes}>${card.likes}</span>
                                                </div>
                                                <div class = "share-btn" data-post-link-id = "${card.link}">
                                                    <span class="material-symbols-outlined share-trigger" data-post-link = "${card.link}">share</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ${vertical ? "" : "</div>"}`;

    return card_html;
}