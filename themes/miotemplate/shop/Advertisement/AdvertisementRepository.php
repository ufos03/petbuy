<?php

namespace App\Advertisement;

/**
 * AdvertisementRepository - Data Access Layer
 * 
 * Gestisce tutte le operazioni di database per gli annunci.
 * Completamente separato dalla logica business e dalle API REST.
 */
class AdvertisementRepository
{
    private $wpdb;
    private $tables;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        
        $this->tables = [
            'advertisements' => $wpdb->prefix . 'advertisements',
            'advertisement_images' => $wpdb->prefix . 'advertisement_images',
            'advertisement_status' => $wpdb->prefix . 'advertisement_status',
            'ads_and_prods' => $wpdb->prefix . 'ads_and_prods',
            'advertisements_view' => $wpdb->prefix . 'advertisements_view',
            'advertisement_order_by_date' => $wpdb->prefix . 'advertisement_order_by_date',
            'advertisement_order_by_price' => $wpdb->prefix . 'advertisement_order_by_price',
        ];
    }

    /**
     * Trova ID annuncio tramite hash
     */
    public function findIdByHash(string $hash): ?int
    {
        $query = $this->wpdb->prepare(
            "SELECT id FROM {$this->tables['advertisements']} WHERE advertisement_hash = %s",
            $hash
        );
        
        $result = $this->wpdb->get_var($query);
        return $result ? (int)$result : null;
    }

    /**
     * Trova annuncio per utente e hash
     */
    public function findByUserAndHash(int $userId, string $hash): ?object
    {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->tables['advertisements']} WHERE user_id = %d AND advertisement_hash = %s",
            $userId,
            $hash
        );
        
        $result = $this->wpdb->get_row($query);
        return $result ?: null;
    }

    /**
     * Crea nuovo annuncio
     */
    public function create(array $data): bool
    {
        $result = $this->wpdb->insert(
            $this->tables['advertisements'],
            [
                'ad_state' => $data['region'],
                'province' => $data['province'],
                'ad_name' => $data['title'],
                'ad_description' => $data['description'],
                'health' => $data['health_description'],
                'cites' => $data['cites'],
                'price' => $data['price'],
                'category' => $data['category'],
                'sub_category' => $data['subcategory'],
                'contact' => $data['phone_number'],
                'birth' => $data['day_of_birthday'],
                'sex' => $data['sex'],
                'animal_weight' => $data['weight'],
                'link_cover' => $data['cover_link'],
                'path_cover' => $data['cover_path'],
                'creation_date' => $data['date'],
                'advertisement_hash' => $data['hash'],
                'gift' => $data['is_gift'],
                'user_id' => $data['user_id'],
            ],
            [
                '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s',
                '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%d'
            ]
        );

        return $result !== false;
    }

    /**
     * Crea status annuncio
     */
    public function createStatus(int $adId, string $status = 'IN_REVIEW'): bool
    {
        $result = $this->wpdb->insert(
            $this->tables['advertisement_status'],
            ['ad_status' => $status, 'advertisement_id' => $adId],
            ['%s', '%d']
        );

        return $result !== false;
    }

    /**
     * Salva immagini aggiuntive
     */
    public function saveImages(int $adId, array $images): bool
    {
        foreach ($images as $image) {
            $result = $this->wpdb->insert(
                $this->tables['advertisement_images'],
                [
                    'link' => $image['link'],
                    'path' => $image['path'],
                    'advertisement_id' => $adId
                ],
                ['%s', '%s', '%d']
            );

            if ($result === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Aggiorna annuncio
     */
    public function update(int $adId, array $fields): bool
    {
        if (empty($fields)) {
            return true;
        }

        $result = $this->wpdb->update(
            $this->tables['advertisements'],
            $fields['data'],
            ['id' => $adId],
            $fields['format'],
            ['%d']
        );

        return $result !== false;
    }

    /**
     * Elimina annuncio
     */
    public function delete(int $adId): bool
    {
        $result = $this->wpdb->delete(
            $this->tables['advertisements'],
            ['id' => $adId],
            ['%d']
        );

        return $result !== false;
    }

    /**
     * Recupera immagini annuncio
     */
    public function getImages(int $adId): array
    {
        $query = $this->wpdb->prepare(
            "SELECT link, path FROM {$this->tables['advertisement_images']} WHERE advertisement_id = %d",
            $adId
        );

        $results = $this->wpdb->get_results($query);
        return $results ?: [];
    }

    /**
     * Recupera immagini con solo i path
     */
    public function getImagePaths(int $adId): array
    {
        $query = $this->wpdb->prepare(
            "SELECT path FROM {$this->tables['advertisement_images']} WHERE advertisement_id = %d",
            $adId
        );

        $results = $this->wpdb->get_results($query);
        return $results ?: [];
    }

    /**
     * Trova annuncio approvato per visualizzazione pubblica
     */
    public function findApprovedById(int $adId): ?object
    {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->tables['advertisements_view']} WHERE id = %d AND ad_status = %s",
            $adId,
            'APPROVED'
        );

        $result = $this->wpdb->get_row($query);
        return $result ?: null;
    }

    /**
     * Trova tutti gli annunci di un utente
     */
    public function findByUserId(int $userId): array
    {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->tables['advertisements_view']} WHERE user_id = %d",
            $userId
        );

        $results = $this->wpdb->get_results($query);
        return $results ?: [];
    }

    /**
     * Trova annunci con filtri e paginazione
     */
    public function findAll(array $filters, string $orderBy, string $order, int $limit, int $offset): array
    {
        $where = $this->buildWhereClause($filters);
        
        // Determina la tabella da usare in base all'ordinamento
        $table = ($orderBy === 'creation_date') 
            ? $this->tables['advertisement_order_by_date']
            : $this->tables['advertisement_order_by_price'];

        $query = $this->wpdb->prepare(
            "SELECT * FROM {$table} {$where['clause']} ORDER BY {$orderBy} {$order} LIMIT %d OFFSET %d",
            ...array_merge($where['params'], [$limit, $offset])
        );

        $results = $this->wpdb->get_results($query);
        return $results ?: [];
    }

    /**
     * Conta annunci con filtri
     */
    public function count(array $filters): int
    {
        $where = $this->buildWhereClause($filters);
        
        // Usa una delle tabelle view (non importa quale per il count)
        $table = $this->tables['advertisement_order_by_date'];

        $query = $this->wpdb->prepare(
            "SELECT COUNT(*) FROM {$table} {$where['clause']}",
            ...$where['params']
        );

        return (int) $this->wpdb->get_var($query);
    }

    /**
     * Costruisce clausola WHERE dai filtri
     */
    private function buildWhereClause(array $filters): array
    {
        $conditions = [];
        $params = [];

        if (isset($filters['min_price']) && $filters['min_price'] > 0) {
            $conditions[] = 'price >= %d';
            $params[] = $filters['min_price'];
        }

        if (isset($filters['max_price']) && $filters['max_price'] > 0) {
            $conditions[] = 'price <= %d';
            $params[] = $filters['max_price'];
        }

        if (!empty($filters['category'])) {
            $conditions[] = 'category = %s';
            $params[] = $filters['category'];
        }

        if (!empty($filters['sub_category'])) {
            $conditions[] = 'sub_category = %s';
            $params[] = $filters['sub_category'];
        }

        if (!empty($filters['sex'])) {
            $conditions[] = 'sex = %s';
            $params[] = $filters['sex'];
        }

        if (!empty($filters['gift'])) {
            $conditions[] = 'gift = %s';
            $params[] = $filters['gift'];
        }

        // Sempre filtra per stato APPROVED
        $conditions[] = 'ad_status = %s';
        $params[] = 'APPROVED';

        $clause = empty($conditions) ? '' : 'WHERE ' . implode(' AND ', $conditions);

        return ['clause' => $clause, 'params' => $params];
    }
}
