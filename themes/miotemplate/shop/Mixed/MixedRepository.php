<?php
/**
 * Mixed Repository
 * 
 * Data Access Layer per la tabella aggregata (advertisements + products).
 * Gestisce query sulla tabella wp_ads_and_prods.
 * 
 * @package PetBuy
 * @subpackage Mixed
 */

namespace App\Mixed;

class MixedRepository {
    
    private string $table;
    private \wpdb $wpdb;
    
    /**
     * Constructor
     */
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix . 'ads_and_prods';
    }
    
    /**
     * Recupera items dalla tabella aggregata con filtri, ordinamento e paginazione
     * 
     * @param array $filters Filtri da applicare
     * @param string $orderBy Campo per ordinamento
     * @param string $order Direzione ordinamento (ASC, DESC)
     * @param int $offset Offset per paginazione
     * @param int $limit Limite elementi
     * @return array Array di oggetti dalla tabella aggregata
     */
    public function findAll(array $filters, string $orderBy, string $order, int $offset, int $limit): array {
        $whereClause = $this->buildWhereClause($filters);
        $params = $this->buildParams($filters);
        
        $sql = "SELECT * FROM {$this->table} {$whereClause['clause']} 
                ORDER BY {$orderBy} {$order} 
                LIMIT %d OFFSET %d";
        
        $paramsWithLimit = array_merge($whereClause['params'], [$limit, $offset]);
        $preparedSql = $this->wpdb->prepare($sql, $paramsWithLimit);
        
        return $this->wpdb->get_results($preparedSql);
    }
    
    /**
     * Conta il totale degli items con i filtri applicati
     * 
     * @param array $filters Filtri da applicare
     * @return int Numero totale di items
     */
    public function count(array $filters): int {
        $whereClause = $this->buildWhereClause($filters);
        
        $sql = "SELECT COUNT(*) FROM {$this->table} {$whereClause['clause']}";
        $preparedSql = $this->wpdb->prepare($sql, $whereClause['params']);
        
        return (int)$this->wpdb->get_var($preparedSql);
    }
    
    /**
     * Cerca items con LIKE sul nome
     * 
     * @param string $searchTerm Termine di ricerca
     * @param array $filters Filtri aggiuntivi
     * @param string $orderBy Campo per ordinamento
     * @param string $order Direzione ordinamento
     * @param int $offset Offset per paginazione
     * @param int $limit Limite elementi
     * @return array Array di oggetti dalla tabella aggregata
     */
    public function search(string $searchTerm, array $filters, string $orderBy, string $order, int $offset, int $limit): array {
        // Costruisce WHERE con LIKE
        $whereConditions = ["name LIKE %s"];
        $params = ['%' . $this->wpdb->esc_like($searchTerm) . '%'];
        
        // Aggiunge filtri aggiuntivi
        if (!empty($filters['category'])) {
            $whereConditions[] = 'category = %s';
            $params[] = $filters['category'];
        }
        if (!empty($filters['sub_category'])) {
            $whereConditions[] = 'sub_category = %s';
            $params[] = $filters['sub_category'];
        }
        if (isset($filters['min_price'])) {
            $whereConditions[] = 'price >= %f';
            $params[] = $filters['min_price'];
        }
        if (isset($filters['max_price'])) {
            $whereConditions[] = 'price <= %f';
            $params[] = $filters['max_price'];
        }
        
        $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
        
        $sql = "SELECT * FROM {$this->table} {$whereClause} 
                ORDER BY {$orderBy} {$order} 
                LIMIT %d OFFSET %d";
        
        $paramsWithLimit = array_merge($params, [$limit, $offset]);
        $preparedSql = $this->wpdb->prepare($sql, $paramsWithLimit);
        
        return $this->wpdb->get_results($preparedSql);
    }
    
    /**
     * Conta il totale degli items con ricerca LIKE
     * 
     * @param string $searchTerm Termine di ricerca
     * @param array $filters Filtri aggiuntivi
     * @return int Numero totale di items
     */
    public function countSearch(string $searchTerm, array $filters): int {
        $whereConditions = ["name LIKE %s"];
        $params = ['%' . $this->wpdb->esc_like($searchTerm) . '%'];
        
        // Aggiunge filtri aggiuntivi
        if (!empty($filters['category'])) {
            $whereConditions[] = 'category = %s';
            $params[] = $filters['category'];
        }
        if (!empty($filters['sub_category'])) {
            $whereConditions[] = 'sub_category = %s';
            $params[] = $filters['sub_category'];
        }
        if (isset($filters['min_price'])) {
            $whereConditions[] = 'price >= %f';
            $params[] = $filters['min_price'];
        }
        if (isset($filters['max_price'])) {
            $whereConditions[] = 'price <= %f';
            $params[] = $filters['max_price'];
        }
        
        $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
        
        $sql = "SELECT COUNT(*) FROM {$this->table} {$whereClause}";
        $preparedSql = $this->wpdb->prepare($sql, $params);
        
        return (int)$this->wpdb->get_var($preparedSql);
    }
    
    /**
     * Costruisce la clausola WHERE con i filtri
     * 
     * @param array $filters Filtri
     * @return array ['clause' => string, 'params' => array]
     */
    private function buildWhereClause(array $filters): array {
        $conditions = [];
        $params = [];
        
        if (isset($filters['min_price'])) {
            $conditions[] = 'price >= %f';
            $params[] = $filters['min_price'];
        }
        
        if (isset($filters['max_price'])) {
            $conditions[] = 'price <= %f';
            $params[] = $filters['max_price'];
        }
        
        if (isset($filters['category'])) {
            $conditions[] = 'category = %s';
            $params[] = $filters['category'];
        }
        
        if (isset($filters['sub_category'])) {
            $conditions[] = 'sub_category = %s';
            $params[] = $filters['sub_category'];
        }
        
        if (isset($filters['creation_date'])) {
            $conditions[] = 'creation_date = %s';
            $params[] = $filters['creation_date'];
        }
        
        $clause = empty($conditions) ? '' : 'WHERE ' . implode(' AND ', $conditions);
        
        return [
            'clause' => $clause,
            'params' => $params
        ];
    }
    
    /**
     * Estrae i parametri dai filtri per prepared statements
     * 
     * @param array $filters Filtri
     * @return array Parametri per wpdb->prepare()
     */
    private function buildParams(array $filters): array {
        $params = [];
        
        if (isset($filters['min_price'])) {
            $params[] = $filters['min_price'];
        }
        if (isset($filters['max_price'])) {
            $params[] = $filters['max_price'];
        }
        if (isset($filters['category'])) {
            $params[] = $filters['category'];
        }
        if (isset($filters['sub_category'])) {
            $params[] = $filters['sub_category'];
        }
        if (isset($filters['creation_date'])) {
            $params[] = $filters['creation_date'];
        }
        
        return $params;
    }
}
