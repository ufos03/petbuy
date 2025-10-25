<?php
/**
 * Product Repository
 * 
 * Data Access Layer per i prodotti WooCommerce.
 * Gestisce tutte le interazioni con WooCommerce e il database.
 * 
 * @package PetBuy
 * @subpackage Product
 */

namespace App\Product;

use WC_Product;

class ProductRepository {
    
    /**
     * Recupera un prodotto tramite ID
     * 
     * @param int $productId ID del prodotto
     * @return WC_Product|false Oggetto prodotto o false se non esiste
     */
    public function findById(int $productId) {
        return wc_get_product($productId);
    }
    
    /**
     * Recupera prodotti con filtri, ordinamento e paginazione
     * 
     * @param array $filters Filtri da applicare (category, sub_category, min_price, max_price)
     * @param string $orderBy Campo per ordinamento (date, price, popularity, rating)
     * @param string $order Direzione ordinamento (ASC, DESC)
     * @param int $page Numero pagina
     * @param int $perPage Elementi per pagina
     * @return array Array di WC_Product
     */
    public function findAll(array $filters, string $orderBy, string $order, int $page, int $perPage): array {
        $args = $this->buildQueryArgs($filters, $orderBy, $order, $page, $perPage);
        return wc_get_products($args);
    }
    
    /**
     * Conta il totale dei prodotti con filtri applicati
     * 
     * @param array $filters Filtri da applicare
     * @return int Numero totale di prodotti
     */
    public function count(array $filters): int {
        $args = $this->buildQueryArgs($filters, 'date', 'DESC', 1, -1);
        $products = wc_get_products($args);
        return count($products);
    }
    
    /**
     * Recupera le categorie di un prodotto
     * 
     * @param int $productId ID del prodotto
     * @return array Array di categorie con nome e URL
     */
    public function getCategories(int $productId): array {
        $terms = wp_get_post_terms($productId, 'product_cat');
        $categories = [];
        
        foreach ($terms as $term) {
            $categories[] = [
                'name' => $term->name,
                'url'  => get_term_link($term),
            ];
        }
        
        return $categories;
    }
    
    /**
     * Recupera i dettagli dell'immagine principale
     * 
     * @param WC_Product $product Oggetto prodotto
     * @return array Dettagli immagine (url, alt, srcset, sizes)
     */
    public function getMainImage(WC_Product $product): array {
        $imageId = $product->get_image_id();
        
        if (!$imageId) {
            return [
                'url'    => 'https://petbuy-local.ns0.it:8080/wp-content/uploads/woocommerce-placeholder-300x300.png.webp',
                'alt'    => '',
                'srcset' => '',
                'sizes'  => '',
            ];
        }
        
        return [
            'url'    => wp_get_attachment_url($imageId),
            'alt'    => get_post_meta($imageId, '_wp_attachment_image_alt', true),
            'srcset' => wp_get_attachment_image_srcset($imageId, 'woocommerce_thumbnail'),
            'sizes'  => wp_get_attachment_image_sizes($imageId, 'woocommerce_thumbnail'),
        ];
    }

    /**
     * Recupera le immagini galleria del prodotto
     *
     * @param WC_Product $product Oggetto prodotto
     * @return array Lista immagini con metadati
     */
    public function getGalleryImages(WC_Product $product): array {
        $imageIds = $product->get_gallery_image_ids();
        $images = [];

        foreach ($imageIds as $imageId) {
            $images[] = [
                'url'    => wp_get_attachment_url($imageId),
                'alt'    => get_post_meta($imageId, '_wp_attachment_image_alt', true),
                'srcset' => wp_get_attachment_image_srcset($imageId, 'woocommerce_thumbnail'),
                'sizes'  => wp_get_attachment_image_sizes($imageId, 'woocommerce_thumbnail'),
            ];
        }

        return $images;
    }
    
    /**
     * Costruisce gli argomenti per la query WooCommerce
     * 
     * @param array $filters Filtri
     * @param string $orderBy Campo ordinamento
     * @param string $order Direzione ordinamento
     * @param int $page Numero pagina
     * @param int $perPage Elementi per pagina
     * @return array Argomenti per wc_get_products()
     */
    private function buildQueryArgs(array $filters, string $orderBy, string $order, int $page, int $perPage): array {
        $args = [
            'status' => 'publish',
            'order'  => strtoupper($order),
            'paged'  => $page,
            'limit'  => $perPage,
        ];
        
        // Ordinamento
        switch ($orderBy) {
            case 'date':
                $args['orderby'] = 'date';
                break;
            case 'price':
                $args['orderby']  = 'meta_value_num';
                $args['meta_key'] = '_price';
                break;
            case 'popularity':
                $args['orderby']  = 'meta_value_num';
                $args['meta_key'] = 'total_sales';
                break;
            case 'rating':
                $args['orderby']  = 'meta_value_num';
                $args['meta_key'] = '_wc_average_rating';
                break;
        }
        
        // Filtri di categoria
        $categories = [];
        if (!empty($filters['category'])) {
            $categories = array_merge($categories, (array)$filters['category']);
        }
        if (!empty($filters['sub_category'])) {
            $categories = array_merge($categories, (array)$filters['sub_category']);
        }
        if (!empty($categories)) {
            $args['category'] = $categories;
        }
        
        // Filtri di prezzo
        if (!empty($filters['min_price'])) {
            $args['min_price'] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $args['max_price'] = $filters['max_price'];
        }
        
        return $args;
    }
}
