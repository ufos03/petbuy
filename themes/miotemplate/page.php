<?php
// Questo è il file page.php del tuo tema

get_header(); // Carica l'header del sito
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        // Questo loop di WordPress controlla se ci sono post (in questo caso, pagine) da mostrare
        while ( have_posts() ) :
            the_post();

            // the_content() è la funzione fondamentale.
            // È qui che il contenuto della pagina (lo shortcode [woocommerce_cart]) viene stampato a schermo.
            the_content();
        
        endwhile; // Fine del loop
        ?>

    </main>
</div>

<?php
get_footer(); // Carica il footer del sito
?>