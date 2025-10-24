<?php

namespace App\Advertisement;

/**
 * AdvertisementValidator - Validation Layer
 * 
 * Gestisce tutte le validazioni degli input per gli annunci.
 */
class AdvertisementValidator
{
    /**
     * Valida dati per creazione annuncio
     */
    public function validateCreate(array $params, $files): array
    {
        $errors = [];

        // Campi obbligatori
        $required = ['title', 'descr', 'category', 'birth', 'sex', 'gift', 'phone', 'region', 'province', 'cites', 'health'];
        foreach ($required as $field) {
            if (empty($params[$field])) {
                $errors[] = "Il campo '{$field}' è obbligatorio";
            }
        }

        if (!empty($errors)) {
            return ['valid' => false, 'errors' => $errors];
        }

        // Lunghezza stringhe
        if (strlen($params['title']) < 1 || strlen($params['title']) > 1000) {
            $errors[] = "Il titolo deve essere tra 1 e 1000 caratteri";
        }

        if (strlen($params['descr']) < 200 || strlen($params['descr']) > 5000) {
            $errors[] = "La descrizione deve essere tra 200 e 5000 caratteri";
        }

        if (strlen($params['health']) < 100) {
            $errors[] = "La descrizione della salute deve essere di almeno 100 caratteri";
        }

        // Valori booleani
        if (!in_array($params['gift'], ['T', 'F'])) {
            $errors[] = "Il parametro 'gift' deve essere 'T' o 'F'";
        }

        if (!in_array($params['cites'], ['T', 'F'])) {
            $errors[] = "Il parametro 'cites' deve essere 'T' o 'F'";
        }

        // Sesso
        if (!in_array($params['sex'], ['M', 'F'])) {
            $errors[] = "Il sesso deve essere 'M' o 'F'";
        }

        // Prezzo
        if (isset($params['price'])) {
            $price = floatval($params['price']);
            
            if ($price < 0) {
                $errors[] = "Il prezzo non può essere negativo";
            }

            if ($price > 0 && $params['gift'] === 'T') {
                $errors[] = "Un annuncio regalo non può avere un prezzo";
            }
        }

        // Validazione immagini
        if (empty($files) || !isset($files['name'])) {
            $errors[] = "Devi caricare almeno un'immagine";
        } else {
            foreach ($files['type'] as $type) {
                if (!in_array($type, ['image/jpg', 'image/jpeg', 'image/png', 'image/webp', 'image/avif'])) {
                    $errors[] = "Formato immagine non valido. Usa JPG, PNG, WEBP o AVIF";
                    break;
                }
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Valida dati per aggiornamento annuncio
     */
    public function validateUpdate(array $params): array
    {
        $errors = [];

        // Campi obbligatori
        if (empty($params['user_token'])) {
            $errors[] = "Token utente obbligatorio";
        }

        if (empty($params['ad_hash'])) {
            $errors[] = "Hash annuncio obbligatorio";
        }

        // Deve esserci almeno un campo da aggiornare
        $updatableFields = ['new_price', 'new_contact', 'gift', 'description', 'title', 'on_sale', 'health', 'ad_state'];
        $hasUpdate = false;
        foreach ($updatableFields as $field) {
            if (isset($params[$field]) && !empty($params[$field])) {
                $hasUpdate = true;
                break;
            }
        }

        if (!$hasUpdate) {
            $errors[] = "Devi specificare almeno un campo da aggiornare";
        }

        // Valida prezzo se presente
        if (isset($params['new_price'])) {
            $price = floatval($params['new_price']);
            
            if ($price < 0) {
                $errors[] = "Il prezzo non può essere negativo";
            }

            if ($price <= 0 && isset($params['gift']) && $params['gift'] === 'F') {
                $errors[] = "Un annuncio non regalo deve avere un prezzo";
            }
        }

        // Valida stato se presente
        if (isset($params['ad_state']) && !in_array($params['ad_state'], ['CLOSED', 'DENIED', 'APPROVED', 'IN_REVIEW'])) {
            $errors[] = "Stato annuncio non valido";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Valida dati per eliminazione annuncio
     */
    public function validateDelete(array $params): array
    {
        $errors = [];

        if (empty($params['user_token'])) {
            $errors[] = "Token utente obbligatorio";
        }

        if (empty($params['ad_hash'])) {
            $errors[] = "Hash annuncio obbligatorio";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Valida filtri per ricerca annunci
     */
    public function validateFilters(array $filters): array
    {
        $valid = [];

        if (isset($filters['min_price'])) {
            $valid['min_price'] = max(0, floatval($filters['min_price']));
        }

        if (isset($filters['max_price'])) {
            $valid['max_price'] = max(0, floatval($filters['max_price']));
        }

        if (isset($filters['category'])) {
            $valid['category'] = sanitize_text_field($filters['category']);
        }

        if (isset($filters['sub_category'])) {
            $valid['sub_category'] = sanitize_text_field($filters['sub_category']);
        }

        if (isset($filters['sex']) && in_array($filters['sex'], ['M', 'F'])) {
            $valid['sex'] = $filters['sex'];
        }

        if (isset($filters['gift']) && in_array($filters['gift'], ['T', 'F'])) {
            $valid['gift'] = $filters['gift'];
        }

        return $valid;
    }

    /**
     * Valida parametri di paginazione
     */
    public function validatePagination(array $params): array
    {
        $page = isset($params['page']) ? max(1, intval($params['page'])) : 1;
        $perPage = isset($params['per_page']) ? intval($params['per_page']) : 20;
        $perPage = max(1, min(100, $perPage)); // Max 100 per page

        return [
            'page' => $page,
            'per_page' => $perPage,
            'offset' => ($page - 1) * $perPage
        ];
    }

    /**
     * Valida ordinamento
     */
    public function validateOrdering(array $params): array
    {
        $allowedOrderBy = ['price', 'date'];
        $orderBy = isset($params['order_by']) && in_array($params['order_by'], $allowedOrderBy) 
            ? $params['order_by'] 
            : 'date';

        // Converti 'date' in 'creation_date' per il database
        if ($orderBy === 'date') {
            $orderBy = 'creation_date';
        }

        $order = isset($params['order']) && strtoupper($params['order']) === 'DESC' 
            ? 'DESC' 
            : 'ASC';

        return [
            'order_by' => $orderBy,
            'order' => $order
        ];
    }
}
