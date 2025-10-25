<?php

namespace Petbuy\Search\Search;

use WP_Error;
use WP_REST_Request;

class QuickSearchMetricsController
{
    private MetricsRepository $repository;

    public function __construct()
    {
        $this->repository = new MetricsRepository();
    }

    /**
     * Endpoint REST per tracciare click e completamenti.
     *
     * @param WP_REST_Request $request
     * @return array|WP_Error
     */
    public function track(WP_REST_Request $request)
    {
        $event = sanitize_text_field($request->get_param('event'));
        $term = sanitize_text_field($request->get_param('term'));
        $type = sanitize_text_field($request->get_param('type') ?? 'pr');

        if ($term === '' || $event === '') {
            return new WP_Error('petbuy_qs_invalid', 'Termine o evento non valido', ['status' => 400]);
        }

        switch ($event) {
            case 'click':
                $this->repository->recordClick($term, $type);
                break;
            case 'complete':
                $this->repository->recordCompletion($term, $type);
                break;
            default:
                return new WP_Error('petbuy_qs_invalid_event', 'Evento non supportato', ['status' => 400]);
        }

        return [
            'status' => 'ok',
        ];
    }
}
