<?php
/*
Template Name: Pagina Prodotti Personalizzata
*/
// Funzione per enqueue CSS e JS
function inject_dependencies()
{
    // Enqueue del foglio di stile CSS
    wp_enqueue_style(
        'style-shopper-window', // Handle unico per il foglio di stile
        'https://petbuy-local.ns0.it:8080/wp-content/shopper-window/style-shopper-window.css', // Percorso al file CSS personalizzato
        array(), // Dipendenze (se presenti)
        '1.0.0' // Versione
    );

    wp_enqueue_style(
        'style-shopper-skeleton', // Handle unico per il foglio di stile
        'https://petbuy-local.ns0.it:8080/wp-content/shopper-window/skeleton-effect.css', // Percorso al file CSS personalizzato
        array(), // Dipendenze (se presenti)
        '1.0.0' // Versione
    );

    // Enqueue del modulo JavaScript
    wp_enqueue_script(
        'module-shopper-window', // Handle unico per il modulo
        'https://petbuy-local.ns0.it:8080/wp-content/shopper-window/main.js', // Percorso al file JS personalizzato
        array(), // Dipendenze (se presenti)
        '1.0.0', // Versione
        true // Carica nello footer
    );
}
add_action('wp_enqueue_scripts', 'inject_dependencies');

// Funzione per aggiungere attributi ai tag <script>
function add_attributes_to_script_tags($tag, $handle, $src)
{
    // Aggiungi l'attributo 'type="module"' per i moduli
    if ($handle === 'module-shopper-window') {
        return '<script type="module" src="' . esc_url($src) . '"></script>' . "\n";
    }

    // Aggiungi l'attributo 'defer' o 'async' per script specifici
    $defer_scripts = array(); // Handle degli script con defer
    $async_scripts = array(); // Handle degli script con async

    if (in_array($handle, $defer_scripts)) {
        return '<script src="' . esc_url($src) . '" defer></script>' . "\n";
    }

    if (in_array($handle, $async_scripts)) {
        return '<script src="' . esc_url($src) . '" async></script>' . "\n";
    }

    return $tag;
}
add_filter('script_loader_tag', 'add_attributes_to_script_tags', 10, 3);

// Chiama l'header dopo aver aggiunto l'azione
get_header();

?>
<div class="container-header-page">
    <div class="header-page">
        <div class="switcher-container"></div>
        <div class="orders">
            <select name="orders" id="orders-page-shop" class="input-petbuy select">
                <option value="default" data-order-by='price' data-order='DESC'>Ordinamento predefinito</option>
                <option value="price-asc" data-order-by='price' data-order='ASC'>Prezzo crescente</option>
                <option value="price-desc" data-order-by='price' data-order='DESC'>Prezzo decrescente</option>
                <option value="date" data-order-by='date' data-order='DESC'>Più recenti</option>
                <option value="popularity" data-order-by='popularity' data-order='DESC'>Più popolari</option>
                <option value="rating" data-order-by='rating' data-order='DESC'>Valutazione più alta</option>
            </select>
        </div>
    </div>
</div>

<div class="petbuy-page">
    <div id="filters-container">
        <div class="category-list-container">
            <ul class="category-list">
                <li class="category-item" data-id="1" data-category="dogs">
                    <div class="category-content"><img class="category-image" src="https://petbuy-local.ns0.it:8080/wp-content/uploads/2024/11/cane_Sfondocane_sfondo_40x40-1.png.webp" alt="Dogs"><span class="category-name">dogs</span></div>
                    <ul class="subcategory-list">
                        <li class="subcategory-item" data-sub_category="Hound Group">Hound Group</li>
                        <li class="subcategory-item" data-sub_category="Herding Group">Herding Group</li>
                        <li class="subcategory-item" data-sub_category="Non sporting Group">Non sporting Group</li>
                        <li class="subcategory-item" data-sub_category="Sporting Group">Sporting Group</li>
                        <li class="subcategory-item" data-sub_category="Terrier Group">Terrier Group</li>
                        <li class="subcategory-item" data-sub_category="Toy Group">Toy Group</li>
                        <li class="subcategory-item" data-sub_category="Working Group">Working Group</li>
                    </ul>
                </li>
                <li class="category-item" data-id="2" data-category="cats">
                    <div class="category-content"><img class="category-image" src="https://petbuy-local.ns0.it:8080/wp-content/uploads/2024/11/Gatto_sfondo40x40.png.webp" alt="Cats"><span class="category-name">Cats</span></div>
                    <ul class="subcategory-list">
                        <li class="subcategory-item" data-sub_category="Long haired">Long haired</li>
                        <li class="subcategory-item" data-sub_category="Semi longhair">Semi longhair</li>
                        <li class="subcategory-item" data-sub_category="Short haired">Short haired</li>
                    </ul>
                </li>
                <li class="category-item" data-id="3" data-category="rodents">
                    <div class="category-content"><img class="category-image" src="https://petbuy-local.ns0.it:8080/wp-content/uploads/2024/11/criceto_sfondo40x40.png.webp" alt="Rodents"><span class="category-name">Rodents</span></div>
                    <ul class="subcategory-list">
                        <li class="subcategory-item" data-sub_category="Mouse">Mouse</li>
                        <li class="subcategory-item" data-sub_category="Rabbit">Rabbit</li>
                        <li class="subcategory-item" data-sub_category="Hamster">Hamster</li>
                        <li class="subcategory-item" data-sub_category="Guinea pig">Guinea pig</li>
                        <li class="subcategory-item" data-sub_category="Ferret">Ferret</li>
                        <li class="subcategory-item" data-sub_category="Squirrels">Squirrels</li>
                    </ul>
                </li>
                <li class="category-item" data-id="4" data-category="Reptiles">
                    <div class="category-content"><img class="category-image" src="https://petbuy-local.ns0.it:8080/wp-content/uploads/2024/11/serpente_sfondo40x40.png.webp" alt="Reptiles"><span class="category-name">Reptiles</span></div>
                    <ul class="subcategory-list">
                        <li class="subcategory-item" data-sub_category="Snakes">Snakes</li>
                        <li class="subcategory-item" data-sub_category="Iguana">Iguana</li>
                        <li class="subcategory-item" data-sub_category="Gecko">Gecko</li>
                        <li class="subcategory-item" data-sub_category="Saurian">Saurian</li>
                        <li class="subcategory-item" data-sub_category="Amphibians">Amphibians</li>
                        <li class="subcategory-item" data-sub_category="Frogs">Frogs</li>
                    </ul>
                </li>
                <li class="category-item" data-id="5" data-category="Birds">
                    <div class="category-content"><img class="category-image" src="https://petbuy-local.ns0.it:8080/wp-content/uploads/2024/11/falco_sfondo40x40.png.webp" alt="Birds"><span class="category-name">Birds</span></div>
                    <ul class="subcategory-list">
                        <li class="subcategory-item" data-sub_category="Parrots">Parrots</li>
                        <li class="subcategory-item" data-sub_category="Canaries">Canaries</li>
                        <li class="subcategory-item" data-sub_category="Birds of prey">Birds of prey</li>
                        <li class="subcategory-item" data-sub_category="Goldfinches">Goldfinches</li>
                        <li class="subcategory-item" data-sub_category="Lovebirds">Lovebirds</li>
                        <li class="subcategory-item" data-sub_category="Blackbird">Blackbird</li>
                    </ul>
                </li>
                <li class="category-item" data-id="6" data-category="Fish">
                    <div class="category-content"><img class="category-image" src="https://petbuy-local.ns0.it:8080/wp-content/uploads/2024/11/pesce_sfondo40X40.png.webp" alt="Fish"><span class="category-name">Fish</span></div>
                    <ul class="subcategory-list">
                        <li class="subcategory-item" data-sub_category="Freshwater">Freshwater</li>
                        <li class="subcategory-item" data-sub_category="Saltwater">Saltwater</li>
                        <li class="subcategory-item" data-sub_category="Tropical">Tropical</li>
                        <li class="subcategory-item" data-sub_category="Molluscs">Molluscs</li>
                        <li class="subcategory-item" data-sub_category="Turtles">Turtles</li>
                    </ul>
                </li>
                <li class="category-item" data-id="7" data-category="Horses">
                    <div class="category-content"><img class="category-image" src="https://petbuy-local.ns0.it:8080/wp-content/uploads/2024/11/cavallo_sfondo40x40.png.webp" alt="Horses"><span class="category-name">Horses</span></div>
                    <ul class="subcategory-list">
                        <li class="subcategory-item" data-sub_category="Riding">Riding</li>
                        <li class="subcategory-item" data-sub_category="Work">Work</li>
                        <li class="subcategory-item" data-sub_category="Racing">Racing</li>
                        <li class="subcategory-item" data-sub_category="Equestrian Sport">Equestrian Sport</li>
                        <li class="subcategory-item" data-sub_category="Pony">Pony</li>
                        <li class="subcategory-item" data-sub_category="Donkeys">Donkeys</li>
                    </ul>
                </li>
                <li class="category-item" data-id="8" data-category="Puoltry">
                    <div class="category-content"><img class="category-image" src="https://petbuy-local.ns0.it:8080/wp-content/uploads/2024/11/pollo_sfondo40x40.png.webp" alt="Puoltry"><span class="category-name">Puoltry</span></div>
                    <ul class="subcategory-list">
                        <li class="subcategory-item" data-sub_category="Chickens">Chickens</li>
                        <li class="subcategory-item" data-sub_category="Ducks">Ducks</li>
                        <li class="subcategory-item" data-sub_category="Geese">Geese</li>
                        <li class="subcategory-item" data-sub_category="Turkeys">Turkeys</li>
                        <li class="subcategory-item" data-sub_category="Quail">Quail</li>
                    </ul>
                </li>
                <li class="category-item" data-id="9" data-category="Breeding">
                    <div class="category-content"><img class="category-image" src="https://petbuy-local.ns0.it:8080/wp-content/uploads/2024/11/maiale_sfondo40x40.png.webp" alt="Breeding"><span class="category-name">Breeding</span></div>
                    <ul class="subcategory-list">
                        <li class="subcategory-item" data-sub_category="Cattle">Cattle</li>
                        <li class="subcategory-item" data-sub_category="Pigs">Pigs</li>
                        <li class="subcategory-item" data-sub_category="Boars">Boars</li>
                        <li class="subcategory-item" data-sub_category="Goats">Goats</li>
                        <li class="subcategory-item" data-sub_category="Sheeps">Sheeps</li>
                        <li class="subcategory-item" data-sub_category="Rabbits">Rabbits</li>
                        <li class="subcategory-item" data-sub_category="Ares">Ares</li>
                        <li class="subcategory-item" data-sub_category="Ungulates">Ungulates</li>
                    </ul>
                </li>
                <li class="category-item" data-id="10" data-category="Insects">
                    <div class="category-content"><img class="category-image" src="https://petbuy-local.ns0.it:8080/wp-content/uploads/2024/11/formica_sfondo40x40.png.webp" alt="Insects"><span class="category-name">Insects</span></div>
                    <ul class="subcategory-list">
                        <li class="subcategory-item" data-sub_category="Bees">Bees</li>
                        <li class="subcategory-item" data-sub_category="Mantis">Mantis</li>
                        <li class="subcategory-item" data-sub_category="Scorpions">Scorpions</li>
                        <li class="subcategory-item" data-sub_category="Spiders">Spiders</li>
                        <li class="subcategory-item" data-sub_category="Ants">Ants</li>
                    </ul>
                </li>
            </ul>
            <button class="mobile-menu-button">☰</button>
        </div>

        <div class="section price-range-section">
            <div class="container-input">
                <label for="price-range" class="form-label">
                    Prezzo massimo (€): <span id="price-value">€0</span>
                </label>
                <input type="range" id="price-range" min="0" step="1">
            </div>
        </div>

        <div class="buttons">
            <button class="input-petbuy button" id="apply-filters">Applica Filtri</button>
            <button class="input-petbuy button secondary-button" id="clear-filters">Cancella Filtri</button>
        </div>

    </div>

    <div id="skeletons-cards" class="cards-shopper">
        <ul class="products columns-3">
            <li class="product">
                <!-- Skeleton Screen Wrapper -->
                <div class="product-block product-block-skeleton skeleton">
                    <span class="product-line"></span>

                    <div class="product-transition">
                        <div class="product-image skeleton-image shimmer">
                            <!-- Placeholder for Image -->
                        </div>
                    </div>
                    <div class="product-caption">

                        <div class="posted-in-skeleton">
                            <span class="skeleton-text shimmer"></span>
                            <span class="skeleton-text shimmer"></span>
                        </div>

                        <div class="posted-in-skeleton">
                            <span class="skeleton-text shimmer"></span>
                            <span class="skeleton-text shimmer"></span>
                        </div>
                    </div>
                </div>
            </li>

            <li class="product">
                <!-- Skeleton Screen Wrapper -->
                <div class="product-block product-block-skeleton skeleton">
                    <span class="product-line"></span>

                    <div class="product-transition">
                        <div class="product-image skeleton-image shimmer">
                            <!-- Placeholder for Image -->
                        </div>
                    </div>
                    <div class="product-caption">

                        <div class="posted-in-skeleton">
                            <span class="skeleton-text shimmer"></span>
                            <span class="skeleton-text shimmer"></span>
                        </div>

                        <div class="posted-in-skeleton">
                            <span class="skeleton-text shimmer"></span>
                            <span class="skeleton-text shimmer"></span>
                        </div>
                    </div>
                </div>
            </li>

            <li class="product">
                <!-- Skeleton Screen Wrapper -->
                <div class="product-block product-block-skeleton skeleton">
                    <span class="product-line"></span>

                    <div class="product-transition">
                        <div class="product-image skeleton-image shimmer">
                            <!-- Placeholder for Image -->
                        </div>
                    </div>
                    <div class="product-caption">

                        <div class="posted-in-skeleton">
                            <span class="skeleton-text shimmer"></span>
                            <span class="skeleton-text shimmer"></span>
                        </div>

                        <div class="posted-in-skeleton">
                            <span class="skeleton-text shimmer"></span>
                            <span class="skeleton-text shimmer"></span>
                        </div>
                    </div>
                </div>
            </li>

            <li class="product">
                <!-- Skeleton Screen Wrapper -->
                <div class="product-block product-block-skeleton skeleton">
                    <span class="product-line"></span>

                    <div class="product-transition">
                        <div class="product-image skeleton-image shimmer">
                            <!-- Placeholder for Image -->
                        </div>
                    </div>
                    <div class="product-caption">

                        <div class="posted-in-skeleton">
                            <span class="skeleton-text shimmer"></span>
                            <span class="skeleton-text shimmer"></span>
                        </div>

                        <div class="posted-in-skeleton">
                            <span class="skeleton-text shimmer"></span>
                            <span class="skeleton-text shimmer"></span>
                        </div>
                    </div>
                </div>
            </li>

            <li class="product">
                <!-- Skeleton Screen Wrapper -->
                <div class="product-block product-block-skeleton skeleton">
                    <span class="product-line"></span>

                    <div class="product-transition">
                        <div class="product-image skeleton-image shimmer">
                            <!-- Placeholder for Image -->
                        </div>
                    </div>
                    <div class="product-caption">

                        <div class="posted-in-skeleton">
                            <span class="skeleton-text shimmer"></span>
                            <span class="skeleton-text shimmer"></span>
                        </div>

                        <div class="posted-in-skeleton">
                            <span class="skeleton-text shimmer"></span>
                            <span class="skeleton-text shimmer"></span>
                        </div>
                    </div>
                </div>
            </li>

            <li class="product">
                <!-- Skeleton Screen Wrapper -->
                <div class="product-block product-block-skeleton skeleton">
                    <span class="product-line"></span>

                    <div class="product-transition">
                        <div class="product-image skeleton-image shimmer">
                            <!-- Placeholder for Image -->
                        </div>
                    </div>
                    <div class="product-caption-skeleton product-caption">

                        <div class="posted-in-skeleton">
                            <span class="skeleton-text shimmer"></span>
                            <span class="skeleton-text shimmer"></span>
                        </div>

                        <div class="posted-in-skeleton">
                            <span class="skeleton-text shimmer"></span>
                            <span class="skeleton-text shimmer"></span>
                        </div>
                    </div>
                </div>
            </li>

        </ul>
    </div>

    <div id="only-products" class="cards-shopper invisible" data-first-time='false'>
        <ul class="products columns-3"></ul>
        <button class="input-petbuy button pagination" id="only-products-pagination" style="visibility: hidden;" data-page='1'>Carica altri</button>
    </div>

    <div id="only-advertisements" class="cards-shopper invisible" data-first-time='true'>
        <ul class="products columns-3"></ul>
        <button class="input-petbuy button pagination" id="only-advertisements-pagination" style="visibility: hidden;" data-page='1'>Carica altri</button>
    </div>

    <div id="mix-products-advertisements" class="cards-shopper invisible" data-first-time='true'>
        <ul class="products columns-3"></ul>
        <button class="input-petbuy button pagination" id="mix-pagination" style="visibility: hidden;" data-page='1'>Carica altri</button>
    </div>
</div>

<?php get_footer(); ?>