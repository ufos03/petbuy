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
		var_dump($options);
		$cache   = new SmartCache( $options['cache_group'] ?? 'QSEARCH' );
		$notice  = $error = '';

		// salvataggio impostazioni
		if ( isset( $_POST['save_qs'] ) && check_admin_referer( 'qs_nonce' ) ) {
			$options['ttl_results']         = (int) $_POST['ttl_results'];
			$options['ttl_popular']         = (int) $_POST['ttl_popular'];
			$options['popularity_threshold'] = (int) $_POST['popularity_threshold'];
			$options['exp_limit']           = (int) $_POST['exp_limit'];
			$options['limit_results']       = (int) $_POST['limit_results'];
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
				<table class="form-table">
					<tr>
						<th scope="row" colspan="2"><h3>Impostazioni Cache</h3></th>
					</tr>
					<tr>
						<th>TTL risultati</th>
						<td><input type="number" name="ttl_results" value="<?php echo esc_attr( $options['ttl_results'] ); ?>"> sec</td>
					</tr>
					<tr>
						<th>TTL termini popolari</th>
						<td><input type="number" name="ttl_popular" value="<?php echo esc_attr( $options['ttl_popular'] ); ?>"> sec</td>
					</tr>
					<tr>
						<th>Soglia popolarità</th>
						<td><input type="number" name="popularity_threshold" value="<?php echo esc_attr( $options['popularity_threshold'] ); ?>"></td>
					</tr>
				</table>

				<h2>Ricerca</h2>
				<table class="form-table">
					<tr>
						<th scope="row">Limite risultati</th>
						<td>
							<input type="number" name="limit_results" value="<?php echo esc_attr($options['limit_results']); ?>" min="1" max="100">
							<p class="description">Numero massimo di risultati da mostrare</p>
						</td>
					</tr>
					<tr>
						<th scope="row">Livello di fuzziness</th>
						<td>
							<input type="number" name="fuzziness" value="<?php echo esc_attr($options['fuzziness']); ?>" min="0" max="3" step="1">
							<p class="description">Livello di tolleranza agli errori di digitazione (0-3, 0 = esatto, 2 = consigliato)</p>
						</td>
					</tr>
					<tr>
						<th>EXP limit</th>
						<td><input type="number" name="exp_limit" value="<?php echo esc_attr( $options['exp_limit'] ); ?>"></td>
					</tr>

					<tr>
						<th scope="row" colspan="2"><h3>Pesi dei campi</h3></th>
					</tr>
					<tr>
						<th scope="row">Peso campo "name"</th>
						<td>
							<input type="number" name="weight_name" value="<?php echo esc_attr($options['weight_name']); ?>" min="1" max="1000">
							<p class="description">Importanza del campo nome (valore più alto = più importante)</p>
						</td>
					</tr>
					<tr>
						<th scope="row">Peso campo "category"</th>
						<td>
							<input type="number" name="weight_category" value="<?php echo esc_attr($options['weight_category']); ?>" min="1" max="1000">
							<p class="description">Importanza del campo categoria</p>
						</td>
					</tr>
					<tr>
						<th scope="row">Peso campo "sub_category"</th>
						<td>
							<input type="number" name="weight_subcategory" value="<?php echo esc_attr($options['weight_subcategory']); ?>" min="1" max="1000">
							<p class="description">Importanza del campo sottocategoria</p>
						</td>
					</tr>
				</table>
				<?php submit_button( 'Salva impostazioni', 'primary', 'save_qs' ); ?>
				<?php submit_button( 'Svuota cache', 'delete', 'flush_qs', false ); ?>
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
		<?php
	}
}
