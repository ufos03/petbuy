<?php

namespace App\Advertisement;

/**
 * AdvertisementService - Business Logic Layer
 * 
 * Gestisce tutta la logica business degli annunci.
 * Indipendente da WordPress REST API e dal database diretto.
 */
class AdvertisementService
{
    private $repository;
    private $validator;
    private $userManager;
    private $hooksHelper;

    public function __construct(
        AdvertisementRepository $repository,
        AdvertisementValidator $validator,
        $userManager,
        $hooksHelper = null
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->userManager = $userManager;
        $this->hooksHelper = $hooksHelper;
    }

    /**
     * Crea nuovo annuncio
     */
    public function createAdvertisement(array $params, $files): array
    {
        // Validazione
        $validation = $this->validator->validateCreate($params, $files);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => implode(', ', $validation['errors']),
                'code' => 400
            ];
        }

        // Verifica token utente
        $userId = $this->userManager->getUserIdFromToken($params['token']);
        if ($userId === null) {
            return [
                'success' => false,
                'message' => 'Token non valido',
                'code' => 401
            ];
        }

        // Gestione upload immagini
        $uploadResult = $this->handleImageUploads($files);
        if (!$uploadResult['success']) {
            return [
                'success' => false,
                'message' => $uploadResult['message'],
                'code' => 500
            ];
        }

        $images = $uploadResult['images'];

        // Genera hash univoco
        $adHash = bin2hex(random_bytes(16));

        // Prepara dati per il database
        $adData = [
            'hash' => $adHash,
            'title' => $params['title'],
            'description' => $params['descr'],
            'category' => $params['category'],
            'subcategory' => $params['sub-category'],
            'health_description' => $params['health'],
            'day_of_birthday' => $params['birth'],
            'sex' => $params['sex'],
            'weight' => $params['weight'] ?? 0,
            'cites' => $params['cites'],
            'price' => $params['price'] ?? 0,
            'phone_number' => $params['phone'],
            'region' => $params['region'],
            'province' => $params['province'],
            'is_gift' => $params['gift'],
            'date' => date('Y-m-d'),
            'user_id' => $userId,
            'cover_link' => $images[0]['link'],
            'cover_path' => $images[0]['path']
        ];

        // Inserisci nella tabella indice (hook)
        if ($this->hooksHelper) {
            $indexResult = $this->hooksHelper->insert_ad_or_product(
                $adHash,
                'advertisement',
                $adData['title'],
                $adData['price'],
                $adData['date'],
                $adData['category'],
                $adData['subcategory']
            );

            if (!$indexResult) {
                $this->cleanupImages($images);
                return [
                    'success' => false,
                    'message' => 'Errore nella creazione dell\'annuncio',
                    'code' => 500
                ];
            }
        }

        // Crea annuncio
        if (!$this->repository->create($adData)) {
            $this->cleanupImages($images);
            return [
                'success' => false,
                'message' => 'Errore nella creazione dell\'annuncio',
                'code' => 500
            ];
        }

        // Recupera ID annuncio
        $adId = $this->repository->findIdByHash($adHash);
        if ($adId === null) {
            return [
                'success' => false,
                'message' => 'Errore nel recupero dell\'annuncio creato',
                'code' => 500
            ];
        }

        // Crea status
        if (!$this->repository->createStatus($adId, 'IN_REVIEW')) {
            return [
                'success' => false,
                'message' => 'Errore nella creazione dello status',
                'code' => 500
            ];
        }

        // Salva immagini aggiuntive
        if (count($images) > 1) {
            $additionalImages = array_slice($images, 1);
            if (!$this->repository->saveImages($adId, $additionalImages)) {
                return [
                    'success' => false,
                    'message' => 'Errore nel salvataggio delle immagini',
                    'code' => 500
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'Annuncio creato con successo',
            'data' => ['hash' => $adHash],
            'code' => 201
        ];
    }

    /**
     * Aggiorna annuncio esistente
     */
    public function updateAdvertisement(array $params): array
    {
        // Validazione
        $validation = $this->validator->validateUpdate($params);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => implode(', ', $validation['errors']),
                'code' => 400
            ];
        }

        // Verifica token utente
        $userId = $this->userManager->getUserIdFromToken($params['user_token']);
        if ($userId === null) {
            return [
                'success' => false,
                'message' => 'Token non valido',
                'code' => 401
            ];
        }

        // Verifica ownership
        $ad = $this->repository->findByUserAndHash($userId, $params['ad_hash']);
        if ($ad === null) {
            return [
                'success' => false,
                'message' => 'Annuncio non trovato o non autorizzato',
                'code' => 403
            ];
        }

        // Costruisci campi da aggiornare
        $updateFields = $this->buildUpdateFields($params, $ad);

        // Aggiorna tabella indice se necessario
        if (isset($params['new_price']) && $this->hooksHelper) {
            $this->hooksHelper->update_ad_or_product($params['ad_hash'], $params['new_price']);
        }

        // Esegui update
        if (!$this->repository->update($ad->id, $updateFields)) {
            return [
                'success' => false,
                'message' => 'Errore nell\'aggiornamento',
                'code' => 500
            ];
        }

        return [
            'success' => true,
            'message' => 'Aggiornamento completato',
            'code' => 200
        ];
    }

    /**
     * Elimina annuncio
     */
    public function deleteAdvertisement(array $params): array
    {
        // Validazione
        $validation = $this->validator->validateDelete($params);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => implode(', ', $validation['errors']),
                'code' => 400
            ];
        }

        // Verifica token utente
        $userId = $this->userManager->getUserIdFromToken($params['user_token']);
        if ($userId === null) {
            return [
                'success' => false,
                'message' => 'Token non valido',
                'code' => 401
            ];
        }

        // Verifica ownership
        $ad = $this->repository->findByUserAndHash($userId, $params['ad_hash']);
        if ($ad === null) {
            return [
                'success' => false,
                'message' => 'Annuncio non trovato o non autorizzato',
                'code' => 403
            ];
        }

        // Elimina dalla tabella indice
        if ($this->hooksHelper) {
            $this->hooksHelper->delete_ad_or_product($params['ad_hash']);
        }

        // Recupera e elimina immagini
        $images = $this->repository->getImagePaths($ad->id);
        foreach ($images as $image) {
            wp_delete_file($image->path);
        }
        wp_delete_file($ad->path_cover);

        // Elimina annuncio
        if (!$this->repository->delete($ad->id)) {
            return [
                'success' => false,
                'message' => 'Errore nell\'eliminazione',
                'code' => 500
            ];
        }

        return [
            'success' => true,
            'message' => 'Annuncio eliminato con successo',
            'code' => 200
        ];
    }

    /**
     * Recupera annuncio singolo (pubblico)
     */
    public function getAdvertisement(string $adHash): array
    {
        if (empty($adHash)) {
            return [
                'success' => false,
                'message' => 'Hash annuncio obbligatorio',
                'code' => 400
            ];
        }

        $adId = $this->repository->findIdByHash($adHash);
        if ($adId === null) {
            return [
                'success' => false,
                'message' => 'Annuncio non trovato',
                'code' => 404
            ];
        }

        $ad = $this->repository->findApprovedById($adId);
        if ($ad === null) {
            return [
                'success' => false,
                'message' => 'Annuncio non disponibile',
                'code' => 403
            ];
        }

        $images = $this->repository->getImages($adId);
        $formatted = $this->formatAdvertisement($ad, $images);

        return [
            'success' => true,
            'data' => $formatted,
            'code' => 200
        ];
    }

    /**
     * Recupera tutti gli annunci con filtri
     */
    public function getAllAdvertisements(array $filters, array $ordering, array $pagination): array
    {
        // Valida e sanitizza input
        $validFilters = $this->validator->validateFilters($filters);
        
        // Conta totale
        $total = $this->repository->count($validFilters);
        $totalPages = ceil($total / $pagination['per_page']);

        // Recupera annunci
        $ads = $this->repository->findAll(
            $validFilters,
            $ordering['order_by'],
            $ordering['order'],
            $pagination['per_page'],
            $pagination['offset']
        );

        // Formatta risultati
        $formatted = [];
        foreach ($ads as $ad) {
            $images = $this->repository->getImages($ad->id);
            $formatted[] = $this->formatAdvertisement($ad, $images);
        }

        return [
            'success' => true,
            'data' => $formatted,
            'pagination' => [
                'total' => $total,
                'total_pages' => $totalPages,
                'current_page' => $pagination['page'],
                'per_page' => $pagination['per_page']
            ],
            'code' => 200
        ];
    }

    /**
     * Recupera annunci di un utente
     */
    public function getUserAdvertisements(string $token): array
    {
        if (empty($token)) {
            return [
                'success' => false,
                'message' => 'Token obbligatorio',
                'code' => 400
            ];
        }

        $userId = $this->userManager->getUserIdFromToken($token);
        if ($userId === null) {
            return [
                'success' => false,
                'message' => 'Token non valido',
                'code' => 401
            ];
        }

        $ads = $this->repository->findByUserId($userId);
        
        $formatted = [];
        foreach ($ads as $ad) {
            $images = $this->repository->getImages($ad->id);
            $formattedAd = $this->formatAdvertisement($ad, $images);
            $formattedAd['status'] = $ad->ad_status; // Includi status per annunci utente
            $formatted[] = $formattedAd;
        }

        return [
            'success' => true,
            'data' => $formatted,
            'code' => 200
        ];
    }

    /**
     * Gestisce upload di immagini
     */
    private function handleImageUploads($files): array
    {
        $uploadOverrides = ['test_form' => false];
        $images = [];

        foreach ($files['name'] as $key => $value) {
            if ($files['name'][$key]) {
                $file = [
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                ];

                $imageObject = wp_handle_upload($file, $uploadOverrides);
                if (!isset($imageObject['file'])) {
                    return [
                        'success' => false,
                        'message' => 'Errore nel caricamento dell\'immagine'
                    ];
                }

                $images[] = [
                    'link' => $imageObject['url'],
                    'path' => $imageObject['file']
                ];
            }
        }

        return [
            'success' => true,
            'images' => $images
        ];
    }

    /**
     * Pulizia immagini in caso di errore
     */
    private function cleanupImages(array $images): void
    {
        foreach ($images as $image) {
            if (isset($image['path'])) {
                wp_delete_file($image['path']);
            }
        }
    }

    /**
     * Costruisce campi da aggiornare
     */
    private function buildUpdateFields(array $params, object $ad): array
    {
        $data = [];
        $format = [];

        if (isset($params['new_contact'])) {
            $data['contact'] = $params['new_contact'];
            $format[] = '%s';
        }

        if (isset($params['gift']) && $params['gift'] === 'T') {
            $data['price'] = 0;
            $data['sale_price'] = 0;
            $data['on_sale'] = 'F';
            $data['gift'] = 'T';
            $format = array_merge($format, ['%f', '%f', '%s', '%s']);
        } elseif (isset($params['new_price'])) {
            $newPrice = floatval($params['new_price']);
            
            if (isset($params['on_sale']) && $params['on_sale'] === 'T' 
                && $newPrice < $ad->price && $ad->price != 0) {
                $data['on_sale'] = 'T';
                $data['sale_price'] = $newPrice;
                $format = array_merge($format, ['%s', '%f']);
            } else {
                $data['price'] = $newPrice;
                $data['on_sale'] = 'F';
                $data['sale_price'] = 0;
                $format = array_merge($format, ['%f', '%s', '%f']);
            }
        }

        if (isset($params['description'])) {
            $data['ad_description'] = $params['description'];
            $format[] = '%s';
        }

        if (isset($params['title'])) {
            $data['ad_name'] = $params['title'];
            $format[] = '%s';
        }

        if (isset($params['ad_state'])) {
            $data['ad_status'] = $params['ad_state'];
            $format[] = '%s';
        }

        return ['data' => $data, 'format' => $format];
    }

    /**
     * Formatta annuncio per output
     */
    private function formatAdvertisement(object $ad, array $images): array
    {
        $imageLinks = array_map(function($img) {
            return $img->link;
        }, $images);

        return [
            'title' => $ad->ad_name ?? '',
            'region' => $ad->ad_state ?? '',
            'province' => $ad->province ?? '',
            'description' => $ad->ad_description ?? '',
            'health' => $ad->health ?? '',
            'has_cites' => $ad->cites ?? 'F',
            'price' => isset($ad->price) ? floatval($ad->price) : 0,
            'sale_price' => isset($ad->sale_price) ? floatval($ad->sale_price) : 0,
            'is_gift' => $ad->gift ?? 'F',
            'on_sale' => $ad->on_sale ?? 'F',
            'category' => $ad->category ?? '',
            'sub_category' => $ad->sub_category ?? '',
            'contact' => $ad->contact ?? '',
            'birth' => $ad->birth ?? '',
            'weight' => isset($ad->animal_weight) ? floatval($ad->animal_weight) : 0,
            'sex' => $ad->sex ?? '',
            'cover' => $ad->link_cover ?? '',
            'date' => $ad->creation_date ?? '',
            'hash' => $ad->advertisement_hash ?? '',
            'images' => $imageLinks
        ];
    }
}
