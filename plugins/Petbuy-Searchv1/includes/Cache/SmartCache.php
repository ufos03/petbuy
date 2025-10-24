<?php
namespace Petbuy\Search\Cache;

/**
 * SmartCache semplice: term → meta.
 * meta = [
 *   'term'  => string,          // termine originale
 *   'data'  => mixed,           // risultato (array di suggerimenti)
 *   'hits'  => int,             // quante volte è stato richiesto
 * ]
 */
class SmartCache {

	private string $group;
	private string $index_key;

	public function __construct( string $group = 'QSEARCH' ) {
		$this->group     = $group;
		$this->index_key = $group . '_index';
	}

	/** Salva o aggiorna una voce */
	public function set( string $key, string $term, mixed $payload, int $ttl = 600 ): void {

		$meta = wp_cache_get( $key, $this->group );

		if ( false === $meta ) {
			/* primo inserimento */
			$meta = [
				'term' => $term,
				'data' => $payload,
				'hits' => 1,
			];

			/* aggiorna indice key → term */
			$index          = wp_cache_get( $this->index_key, $this->group ) ?: [];
			$index[ $key ]  = $term;
			wp_cache_set( $this->index_key, $index, $this->group );
		} else {
			$meta['data'] = $payload;  // rinnova risultato
			$meta['hits']++;           // conta nuovo hit
		}

		wp_cache_set( $key, $meta, $this->group, $ttl );
		error_log( '[SmartCache] salvato term='.$term.' hits='.($meta['hits']??1) );
	}

	/** Recupera e incrementa il contatore hit se presente */
	public function get( string $key ): mixed {

		$meta = wp_cache_get( $key, $this->group );
		if ( false === $meta ) {
			return false;                         // cache miss
		}

		$meta['hits']++;
		wp_cache_set( $key, $meta, $this->group ); // riscrive senza cambiare TTL

		return $meta['data'];
	}

	/** Statistiche: [[term, hits]] ordinate per hit desc */
	public function list_stats(): array {

		$index = wp_cache_get( $this->index_key, $this->group ) ?: [];
		$stats = [];

		foreach ( $index as $key => $term ) {
			$meta = wp_cache_get( $key, $this->group );
			if ( false === $meta ) { continue; }          // voce scaduta
			$stats[] = [
				'term' => $term,
				'hits' => $meta['hits'] ?? 0,
			];
		}

		usort( $stats, fn ( $a, $b ) => $b['hits'] <=> $a['hits'] );
		return $stats;
	}

	public function delete( string $key ): void {
		wp_cache_delete( $key, $this->group );
	}

	public function flush(): void {
		wp_cache_delete( $this->index_key, $this->group );
	}
}