<!doctype html>
<html>
<head>
<meta charset="utf-8">

</head>

<style>

	.scheda-prodotto .image-wrapper {
		/* Esempio: altezza fissa di 200px. Puoi usare max-height, o percentuali */
		height: 200px; 
		width: 100%; /* Occupa tutta la larghezza disponibile nella colonna */
		overflow: hidden; /* Nasconde le parti dell'immagine che eccedono la cornice */
		display: block;
		margin-bottom: 10px; /* Spazio sotto l'immagine */
	}
	
</style>

<body>


<?php

/*
*
* Template Name: PageShop
* Description: pagina negozio
*
*/

//get header.php file
get_header();


?>


<!--desktop-->
<div class="container spazi-riga-sidebar px-0 d-none d-md-block">
          <div class="row">
              <div class="col-md-4 col-lg-3 sidebar-col"></div>

              <div class="col-md-8 col-lg-9 content-col">

                <div class="">
                  <div class="row ">

                    <div class="col-md-3 text-secondary float-start noacapo" id="contenuto-conteggio">
						<div class="switch-risultati-shop">
							<span id="" class="risultati-conteggio-js">Caricamento...</span>
						</div>
					</div>

                    <div class="col-md-9">

                      <div class="float-end">


                          <div class="btn-group switch-shop2" role="group" aria-label="Switch di opzioni">
                            <input type="radio" class="btn-check section-switch-btn" name="options-desktop" id="option1-desktop" autocomplete="off" checked data-target-a="contenuto-1a-desktop" data-target-b="contenuto-1b" data-product-container="#contenuto-1a-desktop">
                            <label class="btn-outline-secondary d-flex" for="option1-desktop" style="padding-left:3px;border-radius: 20px 0 0 20px;">
                              <img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-utente-grigio.svg'; ?>" style="" class="align-self-center img-fluid" alt=""/>
                            </label>

                            <input type="radio" class="btn-check section-switch-btn" name="options-desktop" id="option2-desktop" autocomplete="off" data-target-a="contenuto-2a-desktop" data-target-b="contenuto-2b" data-product-container="#contenuto-2a-desktop">
                            <label class="btn-outline-secondary d-flex" for="option2-desktop" style="border-radius: 0;">
                              <img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-borsa-spesa.svg'; ?>" style="" class="align-self-center img-fluid" alt=""/>
                            </label>

                            <input type="radio" class="btn-check section-switch-btn" name="options-desktop" id="option3-desktop" autocomplete="off" data-target-a="contenuto-3a-desktop" data-target-b="contenuto-3b" data-product-container="#contenuto-3a-desktop">
                            <label class="btn-outline-secondary d-flex" for="option3-desktop" style="padding-right:3px;border-radius: 0 20px 20px 0; border-left: none;">
                              <img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-negozio.svg'; ?>" style="" class="align-self-center img-fluid" alt=""/>
                            </label>
                          </div>


                        <!--
                        <span class="">
                          <span class="">
                            <img src="ufficiale/filtro-risultati-attivo.svg" class="" alt=""/>
                          </span>
                          <span>
                            <img src="ufficiale/icona-borsa-spesa.svg" style="width:25px;" class="" alt=""/>
                          </span>
                          <span>
                            <img src="ufficiale/icona-negozio.svg" style="width:25px;" class="" alt=""/>
                          </span>
                        </span>
                        -->


                        <span class="text-secondary elementi-in-linea">
                          <div class="px-3 elementi-in-linea">
                            <span id="contenuto-1b" class="opzione-contenuto-b active">Annunci</span>
                            <span id="contenuto-2b" class="opzione-contenuto-b">bella</span>
                            <span id="contenuto-3b" class="opzione-contenuto-b">giornata</span>
                          </div>
                          <!--<span class="px-3">Annunci</span>-->
                          <span class="bordo-rilevanza p-2"></span>
                          <span class=""> Filtra per:

                          <div class="woocommerce-ordering px-1">
    <select id="product-sort-select" class="orderby">
  <option value="default">Predefinito</option>
  <option value="popularity">Popolarità</option>
  <option value="rating">Valutazione</option>
  <option value="date">Data</option>
  <option value="price_asc">Prezzo crescente</option>
  <option value="price_desc">Prezzo decrescente</option>
</select>

</div>

</span>
                        </span>

                      </div>

                    </div>

                  </div>
                </div>


              </div>
          </div>
    </div>



    <!--mobile-->
	<div class="container spazi-riga-sidebar px-0 d-block d-sm-none">
          <div class="row">
              <div class="col-md-4 col-lg-3 sidebar-col"></div>

              <div class="col-md-8 col-lg-9 content-col">

                <div class="container">
                  <div class="row align-items-center">

                    <div class="col text-secondary noacapo float-start">
                      <div class="switch-risultati-shop">
                          <span id="" class="">conteggio mobile</span>
                      </div>
                    </div>

                    <div class="col-auto pe-0">

                      <div class="float-end" id="nascondi_in_mobile">


                          <div class="btn-group" role="group" aria-label="Switch di opzioni">
                            <input type="radio" class="btn-check" name="options-mobile" id="option1-mobile" autocomplete="off" checked data-target-a="contenuto-1a-mobile" data-target-b="contenuto-1b">
                            <label class="btn-outline-secondary d-flex" for="option1-mobile" style="padding-left:3px;border-radius: 20px 0 0 20px;">
                              <img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-utente-grigio.svg'; ?>" style="" class="align-self-center img-fluid" alt=""/>
                            </label>

                            <input type="radio" class="btn-check" name="options-mobile" id="option2-mobile" autocomplete="off" data-target-a="contenuto-2a-mobile" data-target-b="contenuto-2b">
                            <label class="btn-outline-secondary d-flex" for="option2-mobile" style="border-radius: 0;">
                              <img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-borsa-spesa.svg'; ?>" style="" class="align-self-center img-fluid" alt=""/>
                            </label>

                            <input type="radio" class="btn-check" name="options-mobile" id="option3-mobile" autocomplete="off" data-target-a="contenuto-3a-mobile" data-target-b="contenuto-3b">
                            <label class="btn-outline-secondary d-flex" for="option3-mobile" style="padding-right:3px;border-radius: 0 20px 20px 0; border-left: none;">
                              <img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-negozio.svg'; ?>" style="" class="align-self-center img-fluid" alt=""/>
                            </label>
                          </div>


                        <!--
                        <span class="">
                          <span class="">
                            <img src="ufficiale/filtro-risultati-attivo.svg" class="" alt=""/>
                          </span>
                          <span>
                            <img src="ufficiale/icona-borsa-spesa.svg" style="width:25px;" class="" alt=""/>
                          </span>
                          <span>
                            <img src="ufficiale/icona-negozio.svg" style="width:25px;" class="" alt=""/>
                          </span>
                        </span>
                        -->

						<!--
                        <span class="text-secondary elementi-in-linea">
                          <div class="px-3 elementi-in-linea">
                            <span id="contenuto-1b" class="opzione-contenuto-b active">Annunci</span>
                            <span id="contenuto-2b" class="opzione-contenuto-b">bella</span>
                            <span id="contenuto-3b" class="opzione-contenuto-b">giornata</span>
                          </div>

                          <span class="bordo-rilevanza p-2"></span>
                          <span class=""> Filtra per: <a href="" class="px-1">Rilevanza <img src="?php echo get_template_directory_uri() . '/ufficiale/down-arrow.svg'; ?>" class="frecce" alt=""/></a></span>
                        </span>
                        -->

                      </div>

                    </div>

                    <div class="col-auto float-end">
                      <a href="#" class="btn btn-warning noacapo px-4" id="openSidebarBtn" role="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCustom" aria-controls="offcanvasCustom">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/filtri.svg'; ?>" class="filtri" alt=""/>
                      </a>

                      <div style="overflow-y: auto; margin-top:250px;" class="offcanvas offcanvas-start offcanvas-custom" tabindex="-1" id="offcanvasCustom" aria-labelledby="offcanvasCustomLabel">
                        <!--
						<div class="offcanvas-header">
                          <h5 class="offcanvas-title" id="offcanvasCustomLabel">Navigazione Principale</h5>
                          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Chiudi"></button>
                        </div>
						 -->

             <div class="mt-2 mb-5" style="padding-left:45px!important;padding-right:60px!important;" id="mobile-sidebar-col">

               <div class="servizi">
    <a href="#" class="noacapo sideshop font-shop">Filtra per categoria <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>

    <a href="<?php echo esc_url( remove_query_arg( 'product_cat', home_url( '/negozio/' ) ) ); ?>">
        <div class="small float-end color-arancio mt-1">Reset</div>
    </a>
</div>

                  <form action="<?php echo esc_url( home_url( '/negozio/' ) ); ?>" method="get">
  <?php
  // Ottieni tutte le categorie di prodotto di WooCommerce
  $uncategorized_id = 16;

  // Gestisci l'input GET in modo sicuro, sia che sia un array o una stringa
  $selected_cats_input = isset($_GET['product_cat']) ? $_GET['product_cat'] : array();
  $selected_cats = is_array($selected_cats_input) ? $selected_cats_input : (array) $selected_cats_input;

  $product_categories = get_terms( array(
      'taxonomy' => 'product_cat',
      'hide_empty' => true,
      'exclude'    => array( $uncategorized_id )
  ) );

  if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
      foreach ( $product_categories as $category ) {
          $count_products = $category->count;
          $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
          $category_icon_url = '';
          if ( $thumbnail_id ) {
              $image_data = wp_get_attachment_image_src( $thumbnail_id, 'full' );
              if ( $image_data ) {
                  $category_icon_url = $image_data[0];
              }
          }

          $checked = in_array( $category->slug, $selected_cats ) ? 'checked' : '';
          ?>
          <div class="form-check category-item w-100">
              <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria-<?php echo $category->term_id; ?>" name="product_cat[]" value="<?php echo $category->slug; ?>" <?php echo $checked; ?>>
              <?php if ( ! empty( $category_icon_url ) ) : ?>
                  <img src="<?php echo esc_url( $category_icon_url ); ?>" class="category-icon-placeholder" alt="<?php echo $category->name; ?> icon"/>
              <?php endif; ?>
              <label class="form-check-label" for="categoria-<?php echo $category->term_id; ?>"><?php echo $category->name; ?></label>
              <span class="category-count float-end"><?php echo $count_products; ?></span>
          </div>
          <?php
      }
  }
  wc_query_string_form_fields( null, array( 'product_cat', 'submit', 'paged' ) );
  ?>
  <button type="submit" style="display:none;"></button>
 </form>

                 <hr>

                 <div class="servizi">

                   <a href="#">Filtra per prezzo <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                   <!--<div class="small float-end color-arancio">Reset</div>-->

                 </div>

                  <?php custom_mobile_price_filter(); ?>

                 <hr>

                 <div class="servizi">

                   <a href="#">Filtra per tags <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                   <!--<div class="small float-end color-arancio">Reset</div>-->

                 </div>


                 <div class="mt-3">
                     <div class="gallery-shop">
                       <?php
                 // Array degli slug dei tag che vuoi mostrare
                 $tag_slugs = array( 'nuovo', 'offerte', 'per-tutti' );

                 // Recupera l'oggetto della query corrente
                 $queried_object = get_queried_object();
                 $current_tag_slug = '';

                 // Controlla se l'oggetto della query Ã¨ un tag e appartiene alla tassonomia product_tag
                 if ( $queried_object && isset( $queried_object->taxonomy ) && $queried_object->taxonomy === 'product_tag' ) {
                     $current_tag_slug = $queried_object->slug;
                 }

                 $product_tags = get_terms( array(
                     'taxonomy'   => 'product_tag',
                     'hide_empty' => true,
                     'slug'       => $tag_slugs,
                 ) );

                 if ( ! empty( $product_tags ) && ! is_wp_error( $product_tags ) ) {
                     foreach ( $product_tags as $tag ) {
                         // Costruisci l'URL dell'icona (versione base)
                         $tag_icon_url = get_template_directory_uri() . '/ufficiale/tag-' . sanitize_title($tag->slug) . '.svg';

                         // Se lo slug del tag corrente corrisponde allo slug del tag selezionato nell'URL
                         if ( $tag->slug === $current_tag_slug ) {
                             $tag_icon_url = get_template_directory_uri() . '/ufficiale/tag-' . sanitize_title($tag->slug) . '-arancio.svg';
                         }
                         ?>
                         <div class="img-box-shop">
                             <a href="<?php echo get_term_link( $tag->term_id, 'product_tag' ); ?>">
                                 <img src="<?php echo $tag_icon_url; ?>" alt="<?php echo $tag->name; ?>" class="img-uniform-shop">
                             </a>
                         </div>
                         <?php
                     }
                 }
                 ?>
                     </div>
                 </div>

                 <div class="mt-3">
                     <div class="gallery-shop">
                       <?php
                       // Array degli slug dei tag che vuoi mostrare
                       $tag_slugs = array( 'cani-piccoli', 'per-tutti', 'offerte' ); // Sostituisci con gli slug dei tuoi tag


                       $product_tags = get_terms( array(
                           'taxonomy'   => 'product_tag',
                           'hide_empty' => true,
                           'slug'       => $tag_slugs,
                       ) );

                       if ( ! empty( $product_tags ) && ! is_wp_error( $product_tags ) ) {
                           foreach ( $product_tags as $tag ) {
                               // Costruisci l'URL per l'icona basandoti sullo slug del tag
                               $tag_icon_url = get_template_directory_uri() . '/ufficiale/tag-' . sanitize_title($tag->slug) . '.svg';
                               ?>
                               <div class="img-box-shop">
                                   <a href="<?php echo get_term_link( $tag->term_id, 'product_tag' ); ?>">
                                       <img src="<?php echo $tag_icon_url; ?>" alt="<?php echo $tag->name; ?>" class="img-uniform-shop">
                                   </a>
                               </div>
                               <?php
                           }
                       }
                       ?>
                     </div>
                 </div>
                 <div class="spazi-riga-sidebar"></div>
                 <hr>


                 <div class="servizi">

                   <a href="#">Filtra per brand <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                   <!--<div class="small float-end color-arancio">Reset</div>-->

                 </div>

                 <form action="<?php echo esc_url( home_url( '/negozio/' ) ); ?>" method="get">
     <?php
     // Ottieni tutti i brand di prodotto di WooCommerce
     $product_brands = get_terms( array(
         'taxonomy'   => 'product_brand',
         'hide_empty' => true,
     ) );

     if ( ! empty( $product_brands ) && ! is_wp_error( $product_brands ) ) {
         // Gestisci l'input GET in modo sicuro, sia che sia un array o una stringa
         $selected_brands_input = isset($_GET['product_brand']) ? $_GET['product_brand'] : array();
         $selected_brands = is_array($selected_brands_input) ? $selected_brands_input : (array) $selected_brands_input;

         foreach ( $product_brands as $brand ) {
             if ( empty( $brand->slug ) ) {
                 continue;
             }

             $count_products = $brand->count;
             $thumbnail_id = get_term_meta( $brand->term_id, 'thumbnail_id', true );
             $brand_icon_url = '';
             if ( $thumbnail_id ) {
                 $image_data = wp_get_attachment_image_src( $thumbnail_id, 'full' );
                 if ( $image_data ) {
                     $brand_icon_url = $image_data[0];
                 }
             }
             $checked = in_array( $brand->slug, $selected_brands ) ? 'checked' : '';
             ?>
             <div class="form-check category-item w-100">
                 <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="brand-<?php echo $brand->term_id; ?>" name="product_brand[]" value="<?php echo $brand->slug; ?>" <?php echo $checked; ?>>
                 <?php if ( ! empty( $brand_icon_url ) ) : ?>
                     <img src="<?php echo esc_url( $brand_icon_url ); ?>" class="category-icon-placeholder" alt="<?php echo $brand->name; ?> icon"/>
                 <?php endif; ?>
                 <label class="form-check-label" for="brand-<?php echo $brand->term_id; ?>"><?php echo $brand->name; ?></label>
                 <span class="category-count float-end"><?php echo $count_products; ?></span>
             </div>
             <?php
         }
     }
     ?>
     <button type="submit" style="display:none;"></button>
 </form>


                 <hr>


                 <div class="servizi">

                   <a href="#">Filtra per feedbacks <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                   <!--<div class="small float-end color-arancio">Reset</div>-->

                 </div>


                 <div class="star-rating-filter-container">
     <span class="star-container">
         <?php
         // Recupera la valutazione selezionata dall'URL, se presente
         $current_rating_filter = isset( $_GET['rating_filter'] ) ? absint( $_GET['rating_filter'] ) : 0;

         // Loop per generare 5 stelle
         for ( $i = 1; $i <= 5; $i++ ) {
             // Costruisci il link per la valutazione
             $link_url = add_query_arg( 'rating_filter', $i, home_url( '/negozio/' ) );

             // Determina se la stella deve essere "accesa"
             $star_class = ( $i <= $current_rating_filter ) ? 'active' : '';
             ?>
             <a href="<?php echo esc_url( $link_url ); ?>" class="star-link <?php echo $star_class; ?>">
                 <img src="<?php echo get_template_directory_uri() . '/ufficiale/stella-piena.svg'; ?>" class="star-icon" alt="Stella"/>
             </a>
         <?php
         }
         ?>
     </span>

     <?php if ( $current_rating_filter > 0 ) : ?>
         <span class="small float-end color-arancio filtro-numero-stelle"><?php echo $current_rating_filter; ?> stelle</span>
     <?php endif; ?>
 </div>


                 <hr>


                 <div class="servizi">

                   <a href="#">Filtra per annunci regalo <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                   <!--<div class="small float-end color-arancio">Reset</div>-->

                 </div>


                 <?php custom_mobile_gift_announcement_widget(); ?>



             </div>

                      </div>
                    </div>

                  </div>
                </div>


              </div>
          </div>
	</div>
	
	
	<div id="notification-container" class="text-center bg-light fw-bold h-50"></div>



	<div class="d-block d-sm-none"><!--inizio spazio switch mobile-->
  	<div id="contenuto-1a-mobile" class="opzione-contenuto-a active"><!--inizio contenuto1 mobile-->


  		<div class="container mt-3">
    <div class="row g-2">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) : the_post();
                // Aggiungi la classe col-6 qui per mettere i prodotti affiancati
                ?>
                <div <?php wc_product_class('col-6'); ?>>
                    <?php wc_get_template_part( 'content', 'product' ); ?>
                </div>
                <?php
            endwhile;
        else :
            do_action( 'woocommerce_no_products_found' );
        endif;
        ?>
    </div>
</div>
	
	
	<div class="container mt-5 d-block d-sm-none">
  <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6 text-center">
        <?php
        // Inserisce la paginazione di WooCommerce
        do_action( 'woocommerce_after_shop_loop' );
        ?>
        <!--a href="#" class="small">Carica altro &#11107;</a-->
      </div>
  </div>
</div>
		

		

      </div><!--fine contenuto1 mobile-->

      <div id="contenuto-2a-mobile" class="opzione-contenuto-a"><!--inizio contenuto2-->

      	<div class="container mt-2">
    		<div class="row">
      			<div class="col-12">
      				
					
     			
     			
     			
     			
     			<div id="primary" class="content-area">
					
					
					
				</div>
     			
     			
     			
     			
     			
     			
     			
     			
     			
     			
     			
      			</div>
      		</div>
      	</div>

      </div><!--fine contenuto2-->

      <div id="contenuto-3a-mobile" class="opzione-contenuto-a"><!--inizio contenuto3-->

      	<div class="container mt-2">
    		<div class="row">
      			<div class="col-12">
      				vincenzo c. cont. mobile 3
      			</div>
      		</div>
      	</div>

      </div><!--fine contenuto3-->

      </div><!--fine spazio switch mobile-->

      




	<!--desktop-->
	<div class="container spazi-riga-sidebar px-0 d-none d-md-block">
        <div class="row">
            
			
          
          	<div id="desktop-sidebar-col" class="col-md-4 col-lg-3 sidebar-col">
    
				<div id="api-filters-wrapper">

					<div class="filter-group servizi">
					
						<div class="servizi">
							<a href="#">Filtra per categoria <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
							<a href="#" id="category-reset-btn"><div class="small float-end color-arancio mt-1">Reset</div></a>
						</div>
                	
						<div id="category-checkbox-list" class="category-list-container">
							<p>Caricamento categorie...</p>
						</div>
					</div>
					
					<hr>

					<div class="filter-group servizi">
						<label class="servizi">Filtra per Prezzo <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></label>						

						<div class="price-inputs justify-content-between align-items-center servizi">
							<span class="text-secondary">Prezzo da: </span>

							<span id="price-display-min">0 €</span>

							-

							<span id="price-display-max">5000 €</span>

							<input type="hidden" id="price-min" name="min_price" class="api-filter-input">
							<input type="hidden" id="price-max" name="max_price" class="api-filter-input">
						</div>
						
						<div id="price-slider"></div>
					</div>
					
					<hr>

					<div class="filter-group">
						<label for="filter-tags">Tags (Separati da virgola)</label>
						<input type="text" id="filter-tags" name="tags" class="api-filter-input" placeholder="es. cuccioli, giocattoli">
					</div>

					<div class="filter-group">
						<label for="filter-brand">Brand</label>
						<select id="filter-brand" name="brand" class="api-filter-input">
							<option value="">Tutti i brand</option>
							</select>
					</div>

					<div class="filter-group">
						<label for="filter-feedback">Rating Minimo</label>
						<select id="filter-feedback" name="min_rating" class="api-filter-input">
							<option value="">Qualsiasi</option>
							<option value="4">4 Stelle+</option>
							<option value="3">3 Stelle+</option>
						</select>
					</div>
					
					<hr>

					<div class="filter-group servizi">						
						<div class="servizi">
							<a href="#">Filtra per annunci regalo <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
						</div>
						
						<form>
							<div class="form-check category-item">
								<input class="form-check-input me-2 api-filter-input" style="margin-top:1px" type="checkbox" id="filter-gift" name="gift" value="true">
								<label class="form-check-label" for="filter-gift">Annunci regalo</label>
								<span class="category-count filtro-numero-stelle" id="gift-count-span">0</span>
							</div>
						</form>
					</div>

					<button id="apply-filters-btn" class="btn btn-primary mt-3">Applica Filtri</button>
					<button id="reset-filters-btn" class="btn btn-secondary mt-3">Reset</button>

				</div>
			</div>
          
           
           
            <div class="col-md-8 col-lg-9 content-col">

			<div class=""><!--inizio spazio switch-->
            <div id="contenuto-1a-desktop" class="opzione-contenuto-a active"><!--inizio contenuto1-->
            
					<div id="primary" class="content-area">
						<main id="main" class="site-main">

							<!--h1 style="text-align: center;">Elenco Prodotti e Annunci</h1-->

							<div id="" class="row product-list-container-js">
								<p style="text-align: center; width: 100%;">Caricamento dei prodotti...</p>
							</div>

							<div class="container mt-5">
								<div class="row justify-content-center">
									<div class="col-12 col-md-8 col-lg-6 text-center">
										<a href="#" id="" class="small load-more-btn-js">Carica altro &#11107;</a>
									</div>
								</div>
							</div>

						</main>
					</div>

      		</div><!--fine contenuto1-->

			  <div id="contenuto-2a-desktop" class="opzione-contenuto-a"><!--inizio contenuto2-->

				  <div id="primary" class="content-area">
						<main id="main" class="site-main">

							<!--h1 style="text-align: center;">Elenco Prodotti e Annunci</h1-->

							<div id="" class="row product-list-container-js">
								<p style="text-align: center; width: 100%;">Caricamento dei prodotti...</p>
							</div>

							<div class="container mt-5">
								<div class="row justify-content-center">
									<div class="col-12 col-md-8 col-lg-6 text-center">
										<a href="#" id="" class="small load-more-btn-js">Carica altro &#11107;</a>
									</div>
								</div>
							</div>

						</main>
					</div>

			  </div><!--fine contenuto2-->

      <div id="contenuto-3a-desktop" class="opzione-contenuto-a"><!--inizio contenuto3-->      	
		  
		  			<div id="primary" class="content-area">
						<main id="main" class="site-main">

							<!--h1 style="text-align: center;">Elenco Prodotti e Annunci</h1-->

							<div id="" class="row product-list-container-js">
								<p style="text-align: center; width: 100%;">Caricamento dei prodotti...</p>
							</div>

							<div class="container mt-5">
								<div class="row justify-content-center">
									<div class="col-12 col-md-8 col-lg-6 text-center">
										<a href="#" id="" class="small load-more-btn-js">Carica altro &#11107;</a>
									</div>
								</div>
							</div>

						</main>
					</div>
		  		  
      </div><!--fine contenuto3-->


      </div><!--fine spazio switch-->



			





            </div>
        </div>
    </div>







<?php

//get footer.php file
get_footer();


?>





</body>
</html>




