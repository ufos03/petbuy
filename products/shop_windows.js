/**
 * Sanitizes and concatenates category slugs for CSS classes.
 * @param {Array} categories - Array of category objects with 'name' and 'url'.
 * @returns {string} - Concatenated category slugs.
 */
function sanitizeCategory(categories) {
    if (!categories || categories.length === 0) return '';
    return categories.map(cat => cat.name.toLowerCase().replace(/\s+/g, '-')).join(' product_cat-');
}

/**
 * Constructs the srcset attribute value from an image object.
 * @param {Object} image - The image object containing srcset and sizes.
 * @returns {string} - The srcset string.
 */
function getSrcSet(image) {
    if (!image || !image.srcset) return '';
    return image.srcset;
}

/**
 * Constructs an HTML <li> element for a single product.
 * @param {Object} product - The product object from the API.
 * @returns {jQuery} - A jQuery object representing the <li> element.
 */
export function build_product_card(product) {
    // Determina la classe di stato di stock
    const stockClass = product.stock_status; // 'instock' o 'outofstock'

    // Determina il badge di vendita
    const saleBadge = product.is_on_sale ? '<span class="onsale">In offerta!</span>' : '';

    // Costruisci i link delle categorie
    let categoryLinks = '';
    if (product.categories && product.categories.length > 0) {
        categoryLinks = product.categories.map(cat => `<a href="${cat.url}" rel="tag">${cat.name}</a>`).join(', ');
    }

    // Bottone Aggiungi al Carrello
    const addToCartButton = `
        <div class="opal-add-to-cart-button">
            <a href="${product.add_to_cart_url}"
               aria-describedby="woocommerce_loop_add_to_cart_link_describedby_${product.id}"
               data-quantity="1"
               class="button product_type_simple add_to_cart_button ajax_add_to_cart"
               data-product_id="${product.id}"
               data-product_sku=""
               aria-label="Aggiungi al carrello: &quot;${product.name}&quot;"
               rel="nofollow">
               Aggiungi al carrello
            </a>
        </div>
        <span id="woocommerce_loop_add_to_cart_link_describedby_${product.id}" class="screen-reader-text"></span>
    `;

    // Bottone Lista Desideri (Assumendo che la funzionalità sia gestita altrove)
    const wishlistButton = `
        <button class="woosw-btn woosw-btn-${product.id} woosw-btn-has-icon woosw-btn-text-icon"
                data-id="${product.id}"
                data-product_name="${product.name}"
                data-product_image="${product.image.url}"
                aria-label="Aggiungi alla Lista desideri">
            <span class="woosw-btn-text">Aggiungi alla Lista desideri</span>
            <span class="woosw-btn-icon woosw-icon-5"></span>
        </button>
    `;

    // Bottone Compara (Assumendo che la funzionalità sia gestita altrove)
    const compareButton = `
        <button class="woosc-btn woosc-btn-${product.id}"
                data-id="${product.id}"
                data-product_name="${product.name}"
                data-product_image="${product.image.url}">
            Compara
        </button>
    `;

    // Costruisci l'elemento <li>
    const product_ui = `
        <li
            class="product type-product post-${product.id} status-publish ${stockClass} product_cat-${sanitizeCategory(product.categories)} has-post-thumbnail ${product.is_on_sale ? 'sale' : ''} shipping-taxable purchasable product-type-${product.product_type}">
            <div class="product-block">
                <span class="product-line"></span>
                ${saleBadge}
                <div class="product-transition">
                    <div class="product-image">
                        <img src="${product.image.url}"
                             alt="${product.image.alt || product.name}"
                             width="300"
                             height="300"
                             class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                             srcset="${getSrcSet(product.image)}"
                             sizes="${product.image.sizes}">
                    </div>
                    <div class="group-action">
                        <div class="shop-action">
                            ${addToCartButton}
                            ${wishlistButton}
                            ${compareButton}
                        </div>
                    </div>
                    <a href="${product.permalink}" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"></a>
                </div>
                <div class="product-caption">
                    ${product.average_rating > 0 ? `<div class="star-rating" role="img" aria-label="Valutato ${product.average_rating} su 5"><span style="width:${(product.average_rating * 100) / 5}%">Valutato <strong class="rating">${product.average_rating}</strong> su 5</span></div>`: ""}
                    <div class="posted-in">${categoryLinks}</div>
                    <h3 class="woocommerce-loop-product__title">
                        <a href="${product.permalink}">${product.name}</a>
                    </h3>
                    <span class="price">
                        ${product.is_on_sale ? `<del aria-hidden="true"><ins>${product.formatted_sale_price}</ins></del>` : ''}
                        ${product.formatted_price}
                        ${product.is_on_sale ? `<span class="screen-reader-text">Il prezzo attuale è: ${product.formatted_sale_price}.</span>` : ''}
                    </span>
                </div>
            </div>
        </li>
    `;

    return product_ui;
}