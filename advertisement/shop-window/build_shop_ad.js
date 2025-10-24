export function build_single_ad(ad)
{
    const single_ad_builded = `<li class="product type-product">
                                    <div class="product-block"><span class="product-line"></span>
                                        <div class="product-transition">
                                            <div class="product-image">
                                                <img src="${ad.cover}" style="height:300px">
                                            </div>
                                            <div class="group-action">
                                                <div class="shop-action">
                                                    <div class="opal-contact-button tooltipstered">
                                                        <a href="tel:${ad.contact}"><i aria-hidden="true" class="shopic-icon- shopic-icon-telephone"></i></a>
                                                    </div> 
                                                    <span class="screen-reader-text"></span>
                                                    <button class="woosw-btn woosw-btn-6851 woosw-btn-has-icon woosw-btn-text-icon tooltipstered"
                                                        aria-label="Aggiungi alla Lista desideri">
                                                            <span class="woosw-btn-text">Aggiungi alla Lista desideri</span>
                                                            <span class="woosw-btn-icon woosw-icon-5"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            <a href="LINK PAGINA ANNUNCIO" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"></a>
                                        </div>
                                        <div class="product-caption">
                                            <div class="posted-in">
                                                ${ad.category}
                                            </div>
                                            <h3 class="woocommerce-loop-product__title"><a href="LIJNK PAGINA ANNUNCIO">${ad.title}</a></h3>
                                            <span class="price">
                                                <span class="woocommerce-Price-amount amount">
                                                    <bdi>
                                                        <span class="woocommerce-Price-currencySymbol">€</span>
                                                        ${ad.price}
                                                    </bdi>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </li>`;

    return single_ad_builded;
}