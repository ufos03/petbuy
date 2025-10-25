<?php
namespace Petbuy\Search\Admin;

use Petbuy\Search\Cache\SmartCache;
use Petbuy\Search\Search\QuickSearchService;

class QuickSearchPage extends AbstractPage {

	public function get_slug(): string {
		return 'petbuy-search-quick';
	}

	public function get_title(): string {
		return 'Quick Search';
	}

	public function get_menu_title(): string {
		return 'Quick Search';
	}

	public function render(): void {
		$options = get_option( PETBUY_QS_SETTINGS );
		if ( ! is_array( $options ) ) {
			$options = [];
		}

		$defaults = [
			'ttl_results'          => HOUR_IN_SECONDS,
			'ttl_popular'          => DAY_IN_SECONDS,
			'popularity_threshold' => 20,
			'exp_limit'            => 10,
			'limit_results'        => 10,
			'fuzziness'            => 1,
			'manticore_host'       => '127.0.0.1',
			'manticore_port'       => 9308,
		];
		$options = wp_parse_args( $options, $defaults );

		$cache   = new SmartCache( $options['cache_group'] ?? 'QSEARCH' );
		$notice  = $error = '';

		// salvataggio impostazioni
		if ( isset( $_POST['save_qs'] ) && check_admin_referer( 'qs_nonce' ) ) {
			$options['ttl_results']         = (int) $_POST['ttl_results'];
			$options['ttl_popular']         = (int) $_POST['ttl_popular'];
			$options['popularity_threshold'] = (int) $_POST['popularity_threshold'];
			$options['exp_limit']           = (int) $_POST['exp_limit'];
			$options['limit_results']       = (int) $_POST['limit_results'];
			$fuzziness                      = isset( $_POST['fuzziness'] ) ? (int) $_POST['fuzziness'] : $options['fuzziness'];
			$options['fuzziness']           = max( 0, min( 3, $fuzziness ) );
			$host                           = isset( $_POST['manticore_host'] ) ? sanitize_text_field( wp_unslash( $_POST['manticore_host'] ) ) : $options['manticore_host'];
			$options['manticore_host']      = $host !== '' ? $host : '127.0.0.1';
			$port                           = isset( $_POST['manticore_port'] ) ? (int) $_POST['manticore_port'] : (int) $options['manticore_port'];
			$options['manticore_port']      = $port > 0 ? $port : 9308;
			update_option( PETBUY_QS_SETTINGS, $options );
			$notice = 'Impostazioni salvate.';
		}

		// svuota cache
		if ( isset( $_POST['flush_qs'] ) && check_admin_referer( 'qs_nonce' ) ) {
			$cache->flush();
			$notice = 'Cache svuotata.';
		}

		$stats = $cache->list_stats();

		?>
		<div class="wrap">
			<h1>Quick Search</h1>

			<?php if ( $notice ) : ?>
				<div class="notice notice-success is-dismissible"><p><?php echo esc_html( $notice ); ?></p></div>
			<?php endif; ?>

			<form method="post">
				<?php wp_nonce_field( 'qs_nonce' ); ?>
				<div class="petbuy-settings-group">
					<h2>Cache &amp; TTL</h2>
					<table class="form-table">
						<tr>
							<th scope="row">TTL risultati</th>
							<td>
								<input type="number" name="ttl_results" value="<?php echo esc_attr( $options['ttl_results'] ); ?>" min="60" step="60">
								<p class="description">Tempo in secondi per cui conservare in cache i suggerimenti standard (default 3600s).</p>
							</td>
						</tr>
						<tr>
							<th scope="row">TTL termini popolari</th>
							<td>
								<input type="number" name="ttl_popular" value="<?php echo esc_attr( $options['ttl_popular'] ); ?>" min="300" step="60">
								<p class="description">Durata cache per le query molto richieste. Più alta = meno chiamate a Manticore (default 86400s).</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Soglia popolarità</th>
							<td>
								<input type="number" name="popularity_threshold" value="<?php echo esc_attr( $options['popularity_threshold'] ); ?>" min="1">
								<p class="description">Numero minimo di richieste per considerare una query “popolare” e applicare il TTL esteso.</p>
							</td>
						</tr>
					</table>
				</div>

				<div class="petbuy-settings-group">
					<h2>Motore di ricerca</h2>
					<table class="form-table">
						<tr>
							<th scope="row">Limite risultati</th>
							<td>
								<input type="number" name="limit_results" value="<?php echo esc_attr($options['limit_results']); ?>" min="1" max="100">
								<p class="description">Numero massimo di suggerimenti mostrati in anteprima (consigliato 10-20).</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Livello di fuzziness</th>
							<td>
								<input type="number" name="fuzziness" value="<?php echo esc_attr($options['fuzziness']); ?>" min="0" max="3" step="1">
								<p class="description">0 = match esatto, 1-3 amplia la tolleranza ai typo. Valori più alti aumentano i risultati ma riducono la precisione.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">EXP limit</th>
							<td>
								<input type="number" name="exp_limit" value="<?php echo esc_attr( $options['exp_limit'] ); ?>" min="1" max="50">
								<p class="description">Massimo numero di espansioni generato da Manticore per le ricerche fuzzy/autocomplete.</p>
							</td>
						</tr>
					</table>
				</div>

				<div class="petbuy-settings-group">
					<h2>Connessione Manticore</h2>
					<table class="form-table">
						<tr>
							<th scope="row">Host Manticore</th>
							<td>
								<input type="text" name="manticore_host" value="<?php echo esc_attr( $options['manticore_host'] ); ?>" placeholder="127.0.0.1">
								<p class="description">Hostname o IP raggiungibile del server Manticore Search. Lascia 127.0.0.1 per il server locale.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Porta Manticore</th>
							<td>
								<input type="number" name="manticore_port" value="<?php echo esc_attr( $options['manticore_port'] ); ?>" min="1" max="65535">
								<p class="description">Porta SQL/HTTP di Manticore (default 9308). Aggiorna se il server usa un binding personalizzato.</p>
							</td>
						</tr>
					</table>
				</div>
				<div class="petbuy-settings-actions">
					<?php submit_button( 'Salva impostazioni', 'primary', 'save_qs' ); ?>
					<?php submit_button( 'Svuota cache', 'delete', 'flush_qs', false ); ?>
				</div>
			</form>

			<hr>

			<h2>Cache Quick Search (<?= count( $stats ); ?> termini)</h2>

			<?php if ( $stats ) : ?>
				<table class="widefat striped">
					<thead><tr><th>Termine</th><th width="100">Ricerche</th></tr></thead>
					<tbody>
						<?php foreach ( $stats as $row ) : ?>
							<tr>
								<td><code><?= esc_html( $row['term'] ); ?></code></td>
								<td style="text-align:center;"><?= esc_html( $row['hits'] ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else : ?>
				<p><em>Nessun elemento in cache.</em></p>
			<?php endif; ?>

		</div>

		<style>
			.petbuy-settings-group {
				border: 1px solid #dcdcdc;
				border-radius: 6px;
				padding: 15px 20px 5px;
				margin-bottom: 25px;
				background: #fff;
			}
			.petbuy-settings-group h2 {
				margin-top: 0;
				font-size: 18px;
			}
			.petbuy-settings-group .form-table th {
				width: 220px;
			}
			.petbuy-settings-group .form-table td .description {
				margin-top: 6px;
				color: #555d66;
			}
			.petbuy-settings-actions {
				display: flex;
				gap: 10px;
				align-items: center;
				margin-top: 20px;
			}
			@media (max-width: 782px) {
				.petbuy-settings-group .form-table th {
					width: auto;
				}
				.petbuy-settings-actions {
					flex-direction: column;
					align-items: flex-start;
				}
			}
		</style>
		<?php
	}
}
