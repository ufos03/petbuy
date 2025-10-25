<?php

namespace App\Category;

use WP_REST_Request;
use WP_REST_Response;

class CategoryController
{
    private CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function getCategories(WP_REST_Request $request): WP_REST_Response
    {
        $data = $this->service->getCategories();
        return new WP_REST_Response($data, 200);
    }
}

