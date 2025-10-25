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
        global $wpdb;
        $table = $wpdb->prefix . 'petbuy_qs_metrics';
        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            term varchar(191) NOT NULL,
            type varchar(10) NOT NULL DEFAULT 'pr',
            hits bigint(20) unsigned NOT NULL DEFAULT 0,
            clicks bigint(20) unsigned NOT NULL DEFAULT 0,
            completed_searches bigint(20) unsigned NOT NULL DEFAULT 0,
            last_seen_at datetime DEFAULT NULL,
            last_clicked_at datetime DEFAULT NULL,
            PRIMARY KEY  (id),
            UNIQUE KEY term_type (term, type)
        ) {$charset};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        \dbDelta( $sql );
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
                $metricsController = new Search\QuickSearchMetricsController();
                register_rest_route(
                    'api/v1',
                    '/qsearch',
                    [
                        'methods'  => \WP_REST_Server::READABLE,
                        'callback' => [ $service, 'rest_search' ],
                        'permission_callback' => '__return_true',
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
                register_rest_route(
                    'api/v1',
                    '/qsearch/track',
                    [
                        'methods'  => \WP_REST_Server::CREATABLE,
                        'callback' => [ $metricsController, 'track' ],
                        'permission_callback' => '__return_true',
                        'args' => [
                            'event' => [
                                'required' => true,
                                'type' => 'string',
                                'description' => 'Evento da registrare (click|complete)',
                            ],
                            'term' => [
                                'required' => true,
                                'type' => 'string',
                                'description' => 'Termine del suggerimento interagito',
                            ],
                            'type' => [
                                'required' => false,
                                'type' => 'string',
                                'description' => 'Tipo risultato (pr|ad|st)',
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
