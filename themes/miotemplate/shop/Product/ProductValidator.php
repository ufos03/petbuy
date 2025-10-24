<?php
/**
 * Product Validator
 * 
 * Validation Layer per i prodotti.
 * Valida input, filtri, parametri di ordinamento e paginazione.
 * 
 * @package PetBuy
 * @subpackage Product
 */

namespace App\Product;

class ProductValidator {
    
    private const ALLOWED_ORDER_BY = ['price', 'date', 'popularity', 'rating'];
    private const ALLOWED_ORDER = ['asc', 'desc'];
    private const MAX_PER_PAGE = 100;
    private const MIN_PER_PAGE = 1;
    
    /**
     * Valida i filtri per la ricerca prodotti
     * 
     * @param array $filters Filtri da validare
     * @return array ['valid' => bool, 'errors' => array, 'sanitized' => array]
     */
    public function validateFilters(array $filters): array {
        $errors = [];
        $sanitized = [];
        
        // Validazione min_price
        if (isset($filters['min_price'])) {
            $minPrice = floatval($filters['min_price']);
            if ($minPrice < 0) {
                $errors[] = 'min_price deve essere >= 0';
            } else {
                $sanitized['min_price'] = $minPrice;
            }
        }
        
        // Validazione max_price
        if (isset($filters['max_price'])) {
            $maxPrice = floatval($filters['max_price']);
            if ($maxPrice < 0) {
                $errors[] = 'max_price deve essere >= 0';
            } else {
                $sanitized['max_price'] = $maxPrice;
            }
        }
        
        // Validazione range prezzi
        if (isset($sanitized['min_price']) && isset($sanitized['max_price'])) {
            if ($sanitized['min_price'] > $sanitized['max_price']) {
                $errors[] = 'min_price non può essere maggiore di max_price';
            }
        }
        
        // Validazione category
        if (isset($filters['category'])) {
            $sanitized['category'] = $this->sanitizeCategories($filters['category']);
        }
        
        // Validazione sub_category
        if (isset($filters['sub_category'])) {
            $sanitized['sub_category'] = $this->sanitizeCategories($filters['sub_category']);
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'sanitized' => $sanitized
        ];
    }
    
    /**
     * Valida i parametri di ordinamento
     * 
     * @param string $orderBy Campo ordinamento
     * @param string $order Direzione ordinamento
     * @param string $defaultOrderBy Valore di default per orderBy
     * @param string $defaultOrder Valore di default per order
     * @return array ['order_by' => string, 'order' => string]
     */
    public function validateOrdering(
        string $orderBy, 
        string $order, 
        string $defaultOrderBy = 'date', 
        string $defaultOrder = 'DESC'
    ): array {
        $orderBy = strtolower(trim($orderBy));
        $order = strtolower(trim($order));
        
        if (!in_array($orderBy, self::ALLOWED_ORDER_BY)) {
            $orderBy = $defaultOrderBy;
        }
        
        if (!in_array($order, self::ALLOWED_ORDER)) {
            $order = strtolower($defaultOrder);
        }
        
        return [
            'order_by' => $orderBy,
            'order' => $order
        ];
    }
    
    /**
     * Valida i parametri di paginazione
     * 
     * @param int $page Numero pagina
     * @param int $perPage Elementi per pagina
     * @param int $defaultPerPage Valore di default per perPage
     * @return array ['page' => int, 'per_page' => int]
     */
    public function validatePagination(int $page, int $perPage, int $defaultPerPage = 6): array {
        $page = max(1, $page);
        $perPage = max(self::MIN_PER_PAGE, min($perPage, self::MAX_PER_PAGE));
        
        if ($perPage <= 0) {
            $perPage = $defaultPerPage;
        }
        
        return [
            'page' => $page,
            'per_page' => $perPage
        ];
    }
    
    /**
     * Valida un ID prodotto
     * 
     * @param mixed $productId ID prodotto da validare
     * @return array ['valid' => bool, 'errors' => array, 'id' => int|null]
     */
    public function validateProductId($productId): array {
        $errors = [];
        $id = null;
        
        if (!isset($productId)) {
            $errors[] = 'Product ID è richiesto';
        } elseif (!is_numeric($productId)) {
            $errors[] = 'Product ID deve essere un numero';
        } else {
            $id = intval($productId);
            if ($id <= 0) {
                $errors[] = 'Product ID deve essere maggiore di 0';
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'id' => $id
        ];
    }
    
    /**
     * Sanitizza le categorie (singola o array)
     * 
     * @param mixed $categories Categoria o array di categorie
     * @return array Array di categorie sanitizzate
     */
    private function sanitizeCategories($categories): array {
        if (!is_array($categories)) {
            $categories = [$categories];
        }
        
        return array_map('sanitize_text_field', $categories);
    }
}
