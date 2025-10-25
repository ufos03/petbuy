<?php

namespace App\Advertisement;

use WP_REST_Request;
use WP_REST_Response;

/**
 * AdvertisementController - REST API Layer
 * 
 * Gestisce esclusivamente le chiamate REST API.
 * Completamente separato dalla logica business.
 */
class AdvertisementController
{
    private $service;
    private $validator;

    public function __construct(AdvertisementService $service, AdvertisementValidator $validator)
    {
        $this->service = $service;
        $this->validator = $validator;
    }

    /**
     * POST /advertisement/create
     * Crea nuovo annuncio
     */
    public function create(WP_REST_Request $request): WP_REST_Response
    {
        $params = $request->get_body_params();
        $files = $_FILES['photo'] ?? [];

        $result = $this->service->createAdvertisement($params, $files);

        return new WP_REST_Response([
            'status' => $result['success'] ? 'success' : 'error',
            'message' => $result['message'],
            'data' => $result['data'] ?? null
        ], $result['code']);
    }

    /**
     * PUT /advertisement/update
     * Aggiorna annuncio esistente
     */
    public function update(WP_REST_Request $request): WP_REST_Response
    {
        $params = $request->get_json_params();

        $result = $this->service->updateAdvertisement($params);

        return new WP_REST_Response([
            'status' => $result['success'] ? 'success' : 'error',
            'message' => $result['message']
        ], $result['code']);
    }

    /**
     * DELETE /advertisement/delete
     * Elimina annuncio
     */
    public function delete(WP_REST_Request $request): WP_REST_Response
    {
        $params = $request->get_json_params();

        $result = $this->service->deleteAdvertisement($params);

        return new WP_REST_Response([
            'status' => $result['success'] ? 'success' : 'error',
            'message' => $result['message']
        ], $result['code']);
    }

    /**
     * GET /advertisements
     * Recupera tutti gli annunci con filtri
     */
    public function getAll(WP_REST_Request $request): WP_REST_Response
    {
        $params = $request->get_params();

        // Estrai e valida parametri
        $filters = [
            'category' => $params['category'] ?? null,
            'sub_category' => $params['sub_category'] ?? null,
            'min_price' => $params['min_price'] ?? null,
            'max_price' => $params['max_price'] ?? null,
            'sex' => $params['sex'] ?? null,
            'gift' => $params['gift'] ?? null,
        ];

        $ordering = $this->validator->validateOrdering($params);
        $pagination = $this->validator->validatePagination($params);

        $result = $this->service->getAllAdvertisements($filters, $ordering, $pagination);

        return new WP_REST_Response($result['data'], $result['code']);
    }

    /**
     * GET /advertisement/{ad_hash}
     * Recupera singolo annuncio
     */
    public function getSingle(WP_REST_Request $request): WP_REST_Response
    {
        $adHash = $request->get_param('ad_hash');

        $result = $this->service->getAdvertisement($adHash);

        if (!$result['success']) {
            return new WP_REST_Response([
                'status' => 'error',
                'message' => $result['message']
            ], $result['code']);
        }

        return new WP_REST_Response([
            'status' => 'ok',
            'content' => $result['data']
        ], $result['code']);
    }

    /**
     * GET /user/advertisements
     * Recupera annunci dell'utente
     */
    public function getUserAds(WP_REST_Request $request): WP_REST_Response
    {
        $token = $request->get_param('token');

        $result = $this->service->getUserAdvertisements($token);

        if (!$result['success']) {
            return new WP_REST_Response([
                'status' => 'error',
                'message' => $result['message']
            ], $result['code']);
        }

        return new WP_REST_Response($result['data'], $result['code']);
    }
}
