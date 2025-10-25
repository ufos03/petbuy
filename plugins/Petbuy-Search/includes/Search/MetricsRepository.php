<?php

namespace Petbuy\Search\Search;

/**
 * Gestisce la registrazione delle metriche di Quick Search.
 */
class MetricsRepository
{
    private string $table;

    private static ?bool $tableExists = null;

    public function __construct()
    {
        global $wpdb;
        $this->table = $wpdb->prefix . 'petbuy_qs_metrics';

        if (self::$tableExists === null) {
            $found = $wpdb->get_var(
                $wpdb->prepare('SHOW TABLES LIKE %s', $this->table)
            );
            self::$tableExists = ($found === $this->table);
        }
    }

    /**
     * Registra le impressioni generate da una ricerca.
     *
     * @param string $term Termine richiesto dall'utente.
     * @param array  $items Lista di risultati [{term,type}].
     */
    public function recordImpressions(string $term, array $items): void
    {
        if (!self::$tableExists || empty($items)) {
            return;
        }

        $term = $this->normalizeTerm($term);
        $now = $this->currentTime();

        $pairs = [];
        foreach ($items as $item) {
            $candidate = isset($item['term']) ? (string) $item['term'] : '';
            $type = isset($item['type']) ? (string) $item['type'] : '';

            if ($candidate === '') {
                continue;
            }

            $normalized = $this->normalizeTerm($candidate);
            $type = $this->normalizeType($type);
            $key = $normalized . '|' . $type;
            $pairs[$key] = [$normalized, $type];
        }

        if (empty($pairs)) {
            return;
        }

        global $wpdb;
        foreach ($pairs as [$resultTerm, $type]) {
            // Collegamento con il termine cercato dall'utente
            $lookupTerm = $resultTerm;
            $sql = $wpdb->prepare(
                "INSERT INTO {$this->table} (term, type, hits, clicks, completed_searches, last_seen_at)
                 VALUES (%s, %s, %d, %d, %d, %s)
                 ON DUPLICATE KEY UPDATE hits = hits + VALUES(hits), last_seen_at = VALUES(last_seen_at)",
                $lookupTerm,
                $type,
                1,
                0,
                0,
                $now
            );
            $wpdb->query($sql);
        }
    }

    /**
     * Registra un click su un suggerimento.
     */
    public function recordClick(string $term, string $type): void
    {
        if (!self::$tableExists) {
            return;
        }

        $normalizedTerm = $this->normalizeTerm($term);
        $normalizedType = $this->normalizeType($type);
        $now = $this->currentTime();

        global $wpdb;
        $sql = $wpdb->prepare(
            "INSERT INTO {$this->table} (term, type, hits, clicks, completed_searches, last_seen_at, last_clicked_at)
             VALUES (%s, %s, %d, %d, %d, %s, %s)
             ON DUPLICATE KEY UPDATE clicks = clicks + VALUES(clicks), last_clicked_at = VALUES(last_clicked_at)",
            $normalizedTerm,
            $normalizedType,
            0,
            1,
            0,
            $now,
            $now
        );
        $wpdb->query($sql);
    }

    /**
     * Registra un completamento (click → ricerca completa).
     */
    public function recordCompletion(string $term, string $type): void
    {
        if (!self::$tableExists) {
            return;
        }

        $normalizedTerm = $this->normalizeTerm($term);
        $normalizedType = $this->normalizeType($type);
        $now = $this->currentTime();

        global $wpdb;
        $sql = $wpdb->prepare(
            "INSERT INTO {$this->table} (term, type, hits, clicks, completed_searches, last_seen_at)
             VALUES (%s, %s, %d, %d, %d, %s)
             ON DUPLICATE KEY UPDATE completed_searches = completed_searches + VALUES(completed_searches)",
            $normalizedTerm,
            $normalizedType,
            0,
            0,
            1,
            $now
        );
        $wpdb->query($sql);
    }

    /**
     * Restituisce le metriche memorizzate per i risultati forniti.
     *
     * @param array $items
     * @return array<string,array{hits:int,clicks:int,completed_searches:int}>
     */
    public function getMetricsForItems(array $items): array
    {
        if (!self::$tableExists || empty($items)) {
            return [];
        }

        $terms = [];
        foreach ($items as $item) {
            $term = isset($item['term']) ? (string) $item['term'] : '';
            if ($term === '') {
                continue;
            }
            $terms[] = $this->normalizeTerm($term);
        }

        if (empty($terms)) {
            return [];
        }

        $terms = array_unique($terms);

        global $wpdb;
        $placeholders = implode(',', array_fill(0, count($terms), '%s'));
        $query = $wpdb->prepare(
            "SELECT term, type, hits, clicks, completed_searches
             FROM {$this->table}
             WHERE term IN ($placeholders)",
            ...$terms
        );

        $rows = $wpdb->get_results($query, ARRAY_A);
        if (empty($rows)) {
            return [];
        }

        $metrics = [];
        foreach ($rows as $row) {
            $key = $row['term'] . '|' . $this->normalizeType($row['type']);
            $metrics[$key] = [
                'hits' => (int) $row['hits'],
                'clicks' => (int) $row['clicks'],
                'completed_searches' => (int) $row['completed_searches'],
            ];
        }

        return $metrics;
    }

    /**
     * Riepilogo aggregato delle metriche globali.
     *
     * @return array{total_hits:int,total_clicks:int,total_completions:int,total_terms:int}
     */
    public function getSummaryStats(): array
    {
        if (!self::$tableExists) {
            return [
                'total_hits' => 0,
                'total_clicks' => 0,
                'total_completions' => 0,
                'total_terms' => 0,
            ];
        }

        global $wpdb;
        $sql = "SELECT COALESCE(SUM(hits),0) AS hits,
                       COALESCE(SUM(clicks),0) AS clicks,
                       COALESCE(SUM(completed_searches),0) AS completions,
                       COUNT(*) AS terms
                FROM {$this->table}";
        $row = $wpdb->get_row($sql, ARRAY_A);

        return [
            'total_hits' => (int) ($row['hits'] ?? 0),
            'total_clicks' => (int) ($row['clicks'] ?? 0),
            'total_completions' => (int) ($row['completions'] ?? 0),
            'total_terms' => (int) ($row['terms'] ?? 0),
        ];
    }

    /**
     * Top termini ordinati per numero di impression.
     *
     * @return array<int,array{term:string,type:string,hits:int,clicks:int,completed_searches:int}>
     */
    public function getTopTermsByHits(int $limit = 10): array
    {
        if (!self::$tableExists) {
            return [];
        }

        $limit = max(1, min(50, $limit));

        global $wpdb;
        $sql = $wpdb->prepare(
            "SELECT term, type, hits, clicks, completed_searches
             FROM {$this->table}
             ORDER BY hits DESC
             LIMIT %d",
            $limit
        );

        $rows = $wpdb->get_results($sql, ARRAY_A) ?: [];

        return array_map(
            static fn($row) => [
                'term' => (string) $row['term'],
                'type' => (string) $row['type'],
                'hits' => (int) $row['hits'],
                'clicks' => (int) $row['clicks'],
                'completed_searches' => (int) $row['completed_searches'],
            ],
            $rows
        );
    }

    /**
     * Top termini ordinati per CTR (clicks / hits).
     *
     * @return array<int,array{term:string,type:string,hits:int,clicks:int,ctr:float}>
     */
    public function getTopTermsByCTR(int $limit = 10, int $minHits = 5): array
    {
        if (!self::$tableExists) {
            return [];
        }

        $limit = max(1, min(50, $limit));
        $minHits = max(1, $minHits);

        global $wpdb;
        $sql = $wpdb->prepare(
            "SELECT term, type, hits, clicks,
                    (clicks / hits) AS ctr
             FROM {$this->table}
             WHERE hits >= %d AND clicks > 0
             ORDER BY ctr DESC
             LIMIT %d",
            $minHits,
            $limit
        );

        $rows = $wpdb->get_results($sql, ARRAY_A) ?: [];

        return array_map(
            static fn($row) => [
                'term' => (string) $row['term'],
                'type' => (string) $row['type'],
                'hits' => (int) $row['hits'],
                'clicks' => (int) $row['clicks'],
                'ctr' => (float) $row['ctr'],
            ],
            $rows
        );
    }

    /**
     * Distribuzione aggregata per type (pr/ad/st).
     *
     * @return array<int,array{type:string,hits:int,clicks:int}>
     */
    public function getTypeBreakdown(): array
    {
        if (!self::$tableExists) {
            return [];
        }

        global $wpdb;
        $sql = "SELECT type,
                       COALESCE(SUM(hits),0) AS hits,
                       COALESCE(SUM(clicks),0) AS clicks
                FROM {$this->table}
                GROUP BY type";

        $rows = $wpdb->get_results($sql, ARRAY_A) ?: [];

        return array_map(
            static fn($row) => [
                'type' => (string) $row['type'],
                'hits' => (int) $row['hits'],
                'clicks' => (int) $row['clicks'],
            ],
            $rows
        );
    }

    /**
     * Normalizza un termine (case insensitive).
     */
    private function normalizeTerm(string $term): string
    {
        $trimmed = trim($term);
        return mb_strtolower($trimmed, 'UTF-8');
    }

    /**
     * Normalizza il type supportando solo quelli previsti.
     */
    private function normalizeType(string $type): string
    {
        $allowed = ['ad', 'pr', 'st'];
        $normalized = strtolower(trim($type));
        return in_array($normalized, $allowed, true) ? $normalized : 'pr';
    }

    private function currentTime(): string
    {
        return current_time('mysql');
    }
}
