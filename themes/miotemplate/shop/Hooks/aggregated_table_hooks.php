<?php
/**
 * Hooks per la sincronizzazione della tabella aggregata (wp_ads_and_prods)
 * 
 * Queste funzioni sono hook WordPress che vengono eseguiti quando:
 * - Un annuncio viene creato/aggiornato/eliminato
 * - Un prodotto WooCommerce viene salvato/eliminato
 * 
 * Mantengono sincronizzata la tabella aggregata wp_ads_and_prods
 * utilizzata dall'API Mixed per query performanti.
 * 
 * NOTA: Questi hook devono rimanere procedurali (non OOP) perché
 * WordPress li invoca direttamente tramite eventi.
 * 
 * @package PetBuy
 * @subpackage Hooks
 */

/**
 * Inserisce un item (advertisement o product) nella tabella aggregata
 * 
 * @param string|int $item_id ID dell'item (hash per ads, ID numerico per products)
 * @param string $type Tipo di item: 'advertisement' o 'product'
 * @param string $name Nome/titolo dell'item
 * @param float $price Prezzo
 * @param string $date Data di creazione (Y-m-d)
 * @param string $category Categoria principale
 * @param string $sub_category Sottocategoria
 * @return bool True se inserito con successo, false altrimenti
 */
function insert_ad_or_product($item_id, $type, $name, $price, $date, $category, $sub_category)
{
    if ($item_id <= 0 || ($type != 'advertisement' && $type != 'product') || $price < 0) {
        return false;
    }

    global $wpdb;
    $ads_and_prods_table = $wpdb->prefix . 'ads_and_prods';
    
    $query = $wpdb->prepare(
        "INSERT INTO $ads_and_prods_table (item_id, type, name, price, creation_date, category, sub_category) 
         VALUES (%s, %s, %s, %f, %s, %s, %s)", 
        $item_id, $type, $name, $price, $date, $category, $sub_category
    );
    
    $query_check = $wpdb->query($query);
    
    return $query_check !== false;
}

/**
 * Elimina un item dalla tabella aggregata
 * 
 * @param string|int $item_id ID dell'item da eliminare
 * @return bool True se eliminato con successo, false altrimenti
 */
function delete_ad_or_product($item_id)
{
    if ($item_id <= 0) {
        return false;
    }

    global $wpdb;
    $ads_and_prods_table = $wpdb->prefix . 'ads_and_prods';
    
    $query = $wpdb->prepare(
        "DELETE FROM $ads_and_prods_table WHERE item_id = %s", 
        $item_id
    );
    
    $query_check = $wpdb->query($query);
    
    return $query_check !== false;
}

/**
 * Aggiorna il prezzo (e opzionalmente il nome) di un item nella tabella aggregata
 * 
 * @param string|int $item_id ID dell'item da aggiornare
 * @param float $price Nuovo prezzo
 * @param string|null $name Nuovo nome (opzionale)
 * @return bool True se aggiornato con successo, false altrimenti
 */
function update_ad_or_product($item_id, $price, $name = null)
{
    if ($item_id <= 0 || $price < 0) {
        return false;
    }

    global $wpdb;
    $ads_and_prods_table = $wpdb->prefix . 'ads_and_prods';
    
    if ($name !== null) {
        $query = $wpdb->prepare(
            "UPDATE $ads_and_prods_table SET price = %f, name = %s WHERE item_id = %s",
            $price, $name, $item_id
        );
    } else {
        $query = $wpdb->prepare(
            "UPDATE $ads_and_prods_table SET price = %f WHERE item_id = %s",
            $price, $item_id
        );
    }
    
    $query_check = $wpdb->query($query);
    
    return $query_check !== false;
}

/**
 * Verifica se un item esiste già nella tabella aggregata
 * 
 * @param string|int $object_id ID dell'item da verificare
 * @return bool True se esiste, false altrimenti
 */
function is_ad_or_product($object_id)
{
    if ($object_id <= 0) {
        return false;
    }

    global $wpdb;
    $ads_and_prods_table = $wpdb->prefix . 'ads_and_prods';
    
    $query = $wpdb->prepare(
        "SELECT id FROM $ads_and_prods_table WHERE item_id = %s", 
        $object_id
    );
    
    $result = $wpdb->get_results($query);
    
    return !empty($result);
}

// ============================================================================
// WORDPRESS HOOKS - Product Synchronization
// ============================================================================

/**
 * Hook eseguito quando un prodotto WooCommerce viene salvato
 * Sincronizza automaticamente i dati nella tabella aggregata
 * 
 * @param int $post_id ID del post/prodotto
 * @return bool|void
 */
function custom_save_post_product($post_id) 
{
    // Evita esecuzione durante autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Se il prodotto è stato cestinato, eliminalo dalla tabella aggregata
    if (get_post_status($post_id) === 'trash') {
        return delete_ad_or_product($post_id);
    }

    // Recupera il prodotto WooCommerce
    $product = wc_get_product($post_id);
    if (!$product) {
        return;
    }

    // Estrai i dati del prodotto
    $name = $product->get_name();
    $price = $product->get_price();
    
    $date_created = $product->get_date_created();
    $day = $date_created ? $date_created->date('Y-m-d') : '';
    
    // Estrai categorie
    $terms = get_the_terms($post_id, 'product_cat');
    $category = '';
    $subcategory = '';
    
    if (!empty($terms) && !is_wp_error($terms)) {
        $parent_cats = array();
        $child_cats = array();
    
        foreach ($terms as $term) {
            if (0 == (int)$term->parent) {
                $parent_cats[] = $term->name;
            } else {
                $child_cats[] = $term->name;
            }
        }
    
        if (!empty($parent_cats)) {
            $category = reset($parent_cats);
        }
        if (!empty($child_cats)) {
            $subcategory = reset($child_cats);
        }
    }

    // Se esiste già, aggiorna; altrimenti inserisci
    if (is_ad_or_product($post_id)) {
        return update_ad_or_product($post_id, $price, $name);
    }
    
    return insert_ad_or_product($post_id, 'product', $name, $price, $day, $category, $subcategory);
}

/**
 * Hook eseguito quando un prodotto viene completamente eliminato dal database
 * Rimuove il prodotto dalla tabella aggregata
 * 
 * @param int $post_id ID del post/prodotto
 * @return void
 */
function custom_delete_action($post_id) 
{
    $product = wc_get_product($post_id);
    if (!$product) {
        return;
    }

    delete_ad_or_product($post_id);
}

// ============================================================================
// REGISTRAZIONE HOOKS WORDPRESS
// ============================================================================

// Hook per salvare/aggiornare prodotti nella tabella aggregata
add_action('save_post', 'custom_save_post_product');

// Hook per eliminare prodotti dalla tabella aggregata
// NOTA: Commentato di default, abilitare se necessario
// add_action('delete_post', 'custom_delete_action');

// ============================================================================
// HELPER FUNCTIONS (possono essere chiamate manualmente se necessario)
// ============================================================================

/**
 * Sincronizza manualmente tutti i prodotti WooCommerce nella tabella aggregata
 * Utile per inizializzazione o reset della tabella
 * 
 * @return array Array con conteggi: ['inserted' => int, 'updated' => int, 'errors' => int]
 */
function sync_all_products_to_aggregated_table()
{
    $args = [
        'status' => 'publish',
        'limit' => -1,
    ];
    
    $products = wc_get_products($args);
    $stats = ['inserted' => 0, 'updated' => 0, 'errors' => 0];
    
    foreach ($products as $product) {
        $post_id = $product->get_id();
        
        try {
            $result = custom_save_post_product($post_id);
            if ($result) {
                if (is_ad_or_product($post_id)) {
                    $stats['updated']++;
                } else {
                    $stats['inserted']++;
                }
            }
        } catch (Exception $e) {
            $stats['errors']++;
        }
    }
    
    return $stats;
}

/**
 * Pulisce la tabella aggregata da items orfani
 * Rimuove items che non hanno più un corrispondente prodotto/annuncio
 * 
 * @return int Numero di items rimossi
 */
function cleanup_orphaned_aggregated_items()
{
    global $wpdb;
    $ads_and_prods_table = $wpdb->prefix . 'ads_and_prods';
    $advertisements_table = $wpdb->prefix . 'advertisements';
    
    // Trova prodotti orfani (non esistono più in wp_posts)
    $orphaned_products = $wpdb->get_results("
        SELECT ap.item_id 
        FROM {$ads_and_prods_table} ap
        LEFT JOIN {$wpdb->posts} p ON ap.item_id = p.ID
        WHERE ap.type = 'product' AND p.ID IS NULL
    ");
    
    // Trova annunci orfani (non esistono più in wp_advertisements)
    $orphaned_ads = $wpdb->get_results("
        SELECT ap.item_id 
        FROM {$ads_and_prods_table} ap
        LEFT JOIN {$advertisements_table} a ON ap.item_id = a.advertisement_hash
        WHERE ap.type = 'advertisement' AND a.id IS NULL
    ");
    
    $removed = 0;
    
    foreach ($orphaned_products as $item) {
        if (delete_ad_or_product($item->item_id)) {
            $removed++;
        }
    }
    
    foreach ($orphaned_ads as $item) {
        if (delete_ad_or_product($item->item_id)) {
            $removed++;
        }
    }
    
    return $removed;
}
