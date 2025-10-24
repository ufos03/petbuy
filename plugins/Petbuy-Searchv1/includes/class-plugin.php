<?php
namespace Petbuy\Search;

use Petbuy\Search\Admin\{HomePage, WordformsPage, QuickSearchPage, SyncAdminPage};

class Plugin {

    /** @var array<Admin\AbstractPage> */
    private array $pages = [];
    /**
     * Metodo di attivazione del plugin.
     */
    public static function activate(): void {
        // Qui puoi aggiungere codice da eseguire all'attivazione del plugin, se necessario.
    }

    public static function init(): void {
        new self();
    }

    private function __construct() {
        require_once __DIR__ . '/constants.php';
        add_action( 'admin_menu', [ $this, 'add_admin_menus' ] );
        $this->register_pages();

        // Registra hook per esecuzione asincrona della full search
        add_action( 'petbuy_async_fullsearch', [ Search\QuickSearchService::class, 'executeAsyncFullSearch' ] );

        // Registra endpoint REST API (solo preview Manticore)
        add_action(
            'rest_api_init',
            function () {
                $service = new Search\QuickSearchService();
                register_rest_route(
                    'api/v1',
                    '/qsearch',
                    [
                        'methods'  => \WP_REST_Server::READABLE,
                        'callback' => [ $service, 'rest_search' ],
                        'args'     => [
                            's' => 
                            [
                                'required' => true,
                                'type'     => 'string',
                                'description' => 'Termine di ricerca (min 3 caratteri)',
                            ],
                        ],
                    ]
                );
            }
        );

        register_activation_hook( PETBUY_SEARCH_PLUGIN_FILE, [ $this, 'activate' ] );
    }

    private function register_pages(): void {
        $default_pages = [
            new HomePage(),
            new WordformsPage(),
            new QuickSearchPage(),
            new SyncAdminPage(),
        ];
        $this->pages = apply_filters( 'petbuy_search_admin_pages', $default_pages );
    }

    public function add_admin_menus(): void {
        // voce principale
        add_menu_page(
            'Petbuy Search',
            'Petbuy Search',
            'manage_options',
            'petbuy-search-home',
            '',
            'dashicons-search',
            90
        );

        // sottomenù
        foreach ( $this->pages as $page ) {
            $page->register();
        }
    }
}
