<?php

namespace App\Category;

class CategoryService
{
    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getCategories(): array
    {
        $categories = $this->repository->getAllCategories();

        return [
            'status' => 'ok',
            'total_items' => count($categories),
            'content' => $categories,
        ];
    }
}

