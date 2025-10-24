<!doctype html>
<html <?php language_attributes();?>>
  <head>
    <meta charset="<?php bloginfo('charset');?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php wp_head();?>
    <!--<title>Home page</title>-->

  	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  	<!--<link rel="stylesheet" href="style.css">-->

  	<link rel="preconnect" href="https://fonts.googleapis.com">
  	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <!--utile per filtra per prezzo-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css">

    <!--utile per creare grafici-->
    <!--script src="https://cdn.jsdelivr.net/npm/chart.js"></script-->

  </head>
  <body <?php body_class(); ?>>

  <!--header per desktop-->
  <header class="d-none d-md-block">

	<div class="container topbar small">
	  <div class="row">
		<div class="col-md-8 text-secondary">
		  Nuovi sconti in arrivo per i mesi primaverili! Non perderti gli sconti, <a href="#" class="text-decoration-underline linktopbar">vai subito allo shop</a>
		</div>

		<div class="col-md-4 phone">

		<img src="<?php echo get_template_directory_uri() . '/ufficiale/cornetta.svg'; ?>" class="cornetta-and-co"  alt="Descrizione dell'immagine">

		  <span class="fw-bold">Servizio clienti:</span> <span class="text-secondary">+393371504517</span>
		</div>
	  </div>
	</div>

	<nav id="navbar_top" class="navbar navbar-expand-lg navbar-light bg-white">
	  <div class="container">
    <!--logo desktop-->
    <a class="align-self-end custom-logo" href="#">

      <!--aggiunge la classe e l'id al logo-->
      <?php
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $logo = wp_get_attachment_image( $custom_logo_id, 'full', false, array(
            'class'    => 'custom-logo',
            'itemprop' => 'logo',
            'id'       => 'logo', // Aggiungi qui l'ID
        ) );
        if ( has_custom_logo() ) {
            echo $logo;
        } else {
            echo '<h1>' . get_bloginfo( 'name' ) . '</h1>';
        }
      ?>

    </a>
    <!--fine logo desktop-->


		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
		  <span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse align-self-end" id="navbarNav">

      <?php
        //cerco il file searchform.php e uso il template per personalizzare il form di ricerca
        get_search_form();

      ?>
      <!--
		  <form class="d-flex w-75 mx-auto my-2 my-lg-0 align-self-end">

			<input class="form-control me-2" type="search" id="ricerca" placeholder="Ricerca i tuoi prodotti" aria-label="Cerca">
			<i id="lente" class="fa fa-search"></i>

		  </form>
      -->

		  <!--inizio menu donamico wordpress-->
		  <?php
      // mostra il menu principale con icone personalizzate
      if (has_nav_menu('primary-menu')) {
          wp_nav_menu(array(
              'theme_location' => 'primary-menu',
              'container'      => '',
              'items_wrap'     => '<ul class="navbar-nav ms-auto">%3$s</ul>',
              'walker'         => new Walker_Icon_Menu()
          ));
      }
      ?>
		  <!--fine menu donamico wordpress-->

		  <!--inizio menu statico html/css-->
		  <!--<ul class="navbar-nav ms-auto">
			<li class="nav-item">
			  <a class="nav-link active" aria-current="page" href="#">Home</a>
			</li>

			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
				Shop
			  </a>
			  <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
				<li><a class="dropdown-item" href="#">Servizio 1</a></li>
				<li><a class="dropdown-item" href="#">Servizio 2</a></li>
				<li><hr class="dropdown-divider"></li>
				<li><a class="dropdown-item" href="#">Altro</a></li>
			  </ul>
			</li>

			<li class="nav-item">
			  <a class="nav-link" href="#">
			  	<img src="?php echo get_template_directory_uri() . '/ufficiale/cuore.svg'; ?>" class=""  alt="Descrizione dell'immagine">
			  </a>
			</li>

			<li class="nav-item">
			  <a class="nav-link" href="#">
			  	<img src="?php echo get_template_directory_uri() . '/ufficiale/carrello.svg'; ?>" class=""  alt="Descrizione dell'immagine">

			  	</a>
			</li>

			<li class="nav-item">
			  <a class="nav-link" href="#">
			  	<img src="?php echo get_template_directory_uri() . '/ufficiale/utente.svg'; ?>" class=""  alt="Descrizione dell'immagine">
			  </a>
			</li>

		  </ul>-->
		  <!--fine menu statico html/css-->

		</div>
	  </div>
	</nav>


    </header>

    <!--header per mobile-->
    <header class="d-block d-sm-none">

  	<div class="container topbar small">
  	  <div class="row">
  		<div class="col-md-12 phone text-center">
  		  <img src="<?php echo get_template_directory_uri() . '/ufficiale/cornetta.svg'; ?>" class="cornetta-and-co" alt=""/>
  		  <span class="fw-bold">Servizio clienti:</span> <span class="text-secondary">+393371504517</span>
  		</div>
  	  </div>
  	</div>

    <hr class="my-1">

    <div class="container-fluid pt-2">
      <div class="row align-items-end">
        <div class="col-4 d-flex justify-content-start bottom-align-elements">
          <button class="border-0 p-0 m-0 navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
              <span class="line"></span> </span>
          </button>

          <i id="" class="fa fa-search fa-2x px-3"></i>
        </div>

        <div class="col-4 logo-container">
          <!--logo mobile-->
          <a class="align-self-end custom-logo" href="#">

            <!--aggiunge la classe e l'id al logo-->
            <?php
              $custom_logo_id = get_theme_mod( 'custom_logo' );
              $logo = wp_get_attachment_image( $custom_logo_id, 'full', false, array(
                  'class'    => 'custom-logo',
                  'itemprop' => 'logo',
                  'id'       => 'logo', // Aggiungi qui l'ID
              ) );
              if ( has_custom_logo() ) {
                  echo $logo;
              } else {
                  echo '<h1>' . get_bloginfo( 'name' ) . '</h1>';
              }
            ?>

          </a>
          <!--fine logo mobile-->

          </div>

        <div class="col-4 right-icons">
          <div class="position-relative me-3">
            <a class="nav-link" href="#"><img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore.svg'; ?>" alt=""/></a> <!--<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              0
              <span class="visually-hidden">elementi preferiti</span>
            </span>-->
          </div>

          <div class="position-relative">
            <a class="nav-link" href="http://localhost/1petbuy/carrello-mobile/"><img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello.svg'; ?>" alt=""/></a> <!--<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              0
              <span class="visually-hidden">elementi nel carrello</span>
            </span>-->
          </div>
        </div>
      </div>
    </div>

    <nav class="navbar navbar-expand-lg" style="">

      	<div class="container-fluid">

      		<div class="navbar-collapse pt-3 collapse " id="navbarNav">

      <!--inizio menu donamico wordpress-->
		  <?php
      // mostra il menu principale con icone personalizzate
      if (has_nav_menu('primary-menu')) {
          wp_nav_menu(array(
              'theme_location' => 'primary-menu',
              'container'      => '',
              'items_wrap'     => '<ul class="navbar-nav">%3$s</ul>',
              'walker'         => new Walker_Icon_Menu()
          ));
      }
      ?>

		  	</div>

		</div>

    </nav>


    </header>


    <!--area principale del sito-->
    <!--<main class="main-area">-->



    <!--


    *items_wrap is built using sprintf()
    * This a template that is parsed with sprintf():
    * $nav_menu .= sprintf(
    * $args->items_wrap
    *, esc_attr($wrap_id)  //%1$s
    *, esc_attr($wrap_class)  //%2$s
    *, $items  //%3$s
    *)



    -->
