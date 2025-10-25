<?php
/**
 * Category Module Bootstrap
 */

require_once __DIR__ . '/CategoryRepository.php';
require_once __DIR__ . '/CategoryService.php';
require_once __DIR__ . '/CategoryController.php';

use App\Category\CategoryRepository;
use App\Category\CategoryService;
use App\Category\CategoryController;

class CategoryContainer
{
    private static ?CategoryContainer $instance = null;
    private ?CategoryRepository $repository = null;
    private ?CategoryService $service = null;
    private ?CategoryController $controller = null;

    public static function getInstance(): CategoryContainer
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function getRepository(): CategoryRepository
    {
        $instance = self::getInstance();
        if ($instance->repository === null) {
            $instance->repository = new CategoryRepository();
        }

        return $instance->repository;
    }

    public static function getService(): CategoryService
    {
        $instance = self::getInstance();
        if ($instance->service === null) {
            $instance->service = new CategoryService(self::getRepository());
        }

        return $instance->service;
    }

    public static function getController(): CategoryController
    {
        $instance = self::getInstance();
        if ($instance->controller === null) {
            $instance->controller = new CategoryController(self::getService());
        }

        return $instance->controller;
    }

    private function __construct() {}
    private function __clone() {}
}

function register_category_rest_routes(): void
{
    $controller = CategoryContainer::getController();

    register_rest_route('api/v1', '/categories/tree', [
        'methods'             => 'GET',
        'callback'            => [$controller, 'getCategories'],
        'permission_callback' => '__return_true',
    ]);
}

add_action('rest_api_init', 'register_category_rest_routes');

