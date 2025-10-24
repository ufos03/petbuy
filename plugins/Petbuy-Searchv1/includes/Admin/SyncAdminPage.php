<?php
namespace Petbuy\Search\Admin;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Pagina unica "Sync" con TAB:
 * - Generale: impostazioni + azioni (test-insert / run)
 * - Status: GET /status con tabelle e log_tail
 * - Logs: GET /logs con scelta righe
 */
class SyncAdminPage extends AbstractPage {

	private const OPT_BASE  = 'petbuy_sync_base_url';
	private const OPT_TOKEN = 'petbuy_sync_token';

	public function get_slug(): string       { return 'petbuy-search-sync'; }
	public function get_title(): string      { return 'Sync'; }
	public function get_menu_title(): string { return 'Sync'; }

	protected function get_base_url(): string {
		$val = trim( (string) get_option( self::OPT_BASE, '' ) );
		return rtrim( $val, '/' );
	}
	protected function get_token(): string {
		return (string) get_option( self::OPT_TOKEN, '' );
	}
	protected function headers(): array {
		$token = $this->get_token();
		$headers = [ 'Accept' => 'application/json' ];
		if ( $token !== '' ) {
			$headers['X-SYNC-TOKEN'] = $token;
			$headers['Authorization'] = 'Bearer ' . $token;
		}
		return $headers;
	}
	protected function request( string $method, string $path, array $args = [] ): array {
		$base = $this->get_base_url();
		if ( empty( $base ) ) {
			return [ 'error' => 'Configura prima l\'indirizzo del server di sync.' ];
		}
		$url  = $base . $path;
		$args = wp_parse_args( $args, [
			'method'  => $method,
			'headers' => $this->headers(),
			'timeout' => 20,
		] );
		$res = wp_remote_request( $url, $args );
		if ( is_wp_error( $res ) ) { return [ 'error' => $res->get_error_message() ]; }
		$code   = wp_remote_retrieve_response_code( $res );
		$body   = wp_remote_retrieve_body( $res );
		$parsed = null;
		if ( is_string( $body ) && $body !== '' ) {
			$decoded = json_decode( $body, true );
			$parsed  = ( json_last_error() === JSON_ERROR_NONE ) ? $decoded : $body;
		}
		return [ 'status' => $code, 'body' => $parsed, 'raw' => $body, 'url' => $url ];
	}

	private function save_settings(): string {
		if ( ! current_user_can( 'manage_options' ) ) { return 'Permesso negato.'; }
		check_admin_referer( 'petbuy_sync_settings' );
		$base  = isset($_POST['base_url']) ? trim( (string) wp_unslash( $_POST['base_url'] ) ) : '';
		$token = isset($_POST['token'])    ? (string) wp_unslash( $_POST['token'] )    : '';
		$base  = rtrim( $base, '/' );
		if ( $base !== '' && ! preg_match( '#^https?://#i', $base ) ) { $base = 'http://' . $base; }
		update_option( self::OPT_BASE,  $base );
		update_option( self::OPT_TOKEN, $token );
		return 'Impostazioni salvate.';
	}

	public function render(): void {
		// Tab attiva: generale|status|logs
		$tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'generale';
		if ( ! in_array( $tab, ['generale','status','logs'], true ) ) { $tab = 'generale'; }

		$notice=''; $error='';
		$result_general = null; $result_status = null; $result_logs = null;

		// Azioni per tab
		if ( $tab === 'generale' ) {
			if ( isset($_POST['petbuy_sync_save']) ) {
				$notice = $this->save_settings();
			} elseif ( isset($_POST['petbuy_sync_test_insert']) ) {
				check_admin_referer( 'petbuy_sync_actions' );
				$result_general = $this->request( 'POST', '/test-insert' );
			} elseif ( isset($_POST['petbuy_sync_run']) ) {
				check_admin_referer( 'petbuy_sync_actions' );
				$payload = [
					'full_ads'    => ! empty($_POST['full_ads']),
					'full_stores' => ! empty($_POST['full_stores']),
				];
				$result_general = $this->request( 'POST', '/run', [
					'body'    => wp_json_encode( $payload ),
					'headers' => array_merge( $this->headers(), [ 'Content-Type' => 'application/json' ] ),
				] );
			}
		} elseif ( $tab === 'status' ) {
			if ( isset($_POST['petbuy_sync_status']) ) {
				check_admin_referer( 'petbuy_sync_status' );
				$result_status = $this->request( 'GET', '/status' );
			}
		} elseif ( $tab === 'logs' ) {
			if ( isset($_POST['petbuy_sync_logs']) ) {
				check_admin_referer( 'petbuy_sync_logs' );
				$lines = isset($_POST['lines']) ? intval($_POST['lines']) : 200;
				$lines = max(10, min(2000, $lines));
				$result_logs = $this->request( 'GET', '/logs?lines=' . $lines );
			}
		}

		$base  = esc_attr( $this->get_base_url() );
		$token = esc_attr( $this->get_token() );

		$tabs = [
			'generale' => 'Generale',
			'status'   => 'Status',
			'logs'     => 'Logs',
		];
		$base_url = menu_page_url( $this->get_slug(), false );
		?>
		<div class="wrap">
			<h1>Sync</h1>

			<h2 class="nav-tab-wrapper">
				<?php foreach ( $tabs as $id => $label ) :
					$url = esc_url( add_query_arg( 'tab', $id, $base_url ) );
					$active = $tab === $id ? ' nav-tab-active' : '';
				?>
					<a href="<?php echo $url; ?>" class="nav-tab<?php echo $active; ?>"><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>
			</h2>

			<?php if ( $notice ) : ?>
				<div class="notice notice-success is-dismissible"><p><?php echo esc_html( $notice ); ?></p></div>
			<?php endif; ?>
			<?php if ( $error ) : ?>
				<div class="notice notice-error is-dismissible"><p><?php echo esc_html( $error ); ?></p></div>
			<?php endif; ?>

			<?php if ( $tab === 'generale' ) : ?>

				<h2 class="title">Impostazioni connessione</h2>
				<form method="post">
					<?php wp_nonce_field( 'petbuy_sync_settings' ); ?>
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><label for="base_url">Indirizzo server</label></th>
								<td>
									<input type="text" class="regular-text code" id="base_url" name="base_url" placeholder="http://127.0.0.1:27000" value="<?php echo $base; ?>" />
									<p class="description">URL base del demone di sync (es. http://host:27000).</p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="token">Token</label></th>
								<td>
									<input type="password" class="regular-text" id="token" name="token" placeholder="SYNC_REST_TOKEN" value="<?php echo $token; ?>" />
									<p class="description">Usato come <code>X-SYNC-TOKEN</code> e <code>Authorization: Bearer</code>.</p>
								</td>
							</tr>
						</tbody>
					</table>
					<p class="submit"><button type="submit" class="button button-primary" name="petbuy_sync_save" value="1">Salva impostazioni</button></p>
				</form>

				<h2 class="title">Azioni</h2>
				<form method="post">
					<?php wp_nonce_field( 'petbuy_sync_actions' ); ?>
					<table class="widefat striped" style="max-width:1200px">
						<thead><tr><th>Azione</th><th>Descrizione</th><th>Opzioni</th><th style="width:160px">Esegui</th></tr></thead>
						<tbody>
							<tr>
								<td><code>POST /test-insert</code></td>
								<td>Effettua una insert di test su <code>INDEX_ADS</code>.</td>
								<td>—</td>
								<td><button class="button" name="petbuy_sync_test_insert" value="1">Esegui</button></td>
							</tr>
							<tr>
								<td><code>POST /run</code></td>
								<td>Avvia <em>run_sync</em> in background; opzionale reindex completo.</td>
								<td>
									<label><input type="checkbox" name="full_ads" /> full_ads</label><br/>
									<label><input type="checkbox" name="full_stores" /> full_stores</label>
								</td>
								<td><button class="button button-primary" name="petbuy_sync_run" value="1">Avvia</button></td>
							</tr>
						</tbody>
					</table>
				</form>

				<?php if ( $result_general ) : ?>
					<h2 class="title">Risultato</h2>
					<table class="widefat striped" style="max-width:1200px">
						<tbody>
							<tr><th>URL</th><td><?php echo esc_html( $result_general['url'] ?? '' ); ?></td></tr>
							<tr><th>Status</th><td><?php echo esc_html( strval( $result_general['status'] ?? '' ) ); ?></td></tr>
						</tbody>
					</table>
					<h3>Body</h3>
					<pre style="background:#111;color:#eee;padding:12px;white-space:pre-wrap;"><?php
						echo esc_html( is_string($result_general['body']) ? $result_general['body'] : wp_json_encode( $result_general['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
					?></pre>
				<?php endif; ?>

			<?php elseif ( $tab === 'status' ) : ?>

				<form method="post" style="margin-bottom:1em;">
					<?php wp_nonce_field( 'petbuy_sync_status' ); ?>
					<button class="button button-primary" name="petbuy_sync_status" value="1">Aggiorna stato (GET /status)</button>
					<span class="description" style="margin-left:8px;">Server: <?php echo $base ? $base : '<em>non configurato</em>'; ?></span>
				</form>

				<?php if ( $result_status ) :
					$body = $result_status['body'] ?? null; ?>
					<table class="widefat striped" style="max-width:1200px">
						<tbody>
							<tr><th>URL</th><td><?php echo esc_html( $result_status['url'] ?? '' ); ?></td></tr>
							<tr><th>Status</th><td><?php echo esc_html( strval( $result_status['status'] ?? '' ) ); ?></td></tr>
						</tbody>
					</table>

					<?php
					if ( is_array($body) ) {
						if ( isset($body['ads_state']) && is_array($body['ads_state']) ) {
							self::render_assoc_table('ADS state', $body['ads_state']);
						}
						if ( isset($body['stores_state']) && is_array($body['stores_state']) ) {
							self::render_assoc_table('STORES state', $body['stores_state']);
						}
						if ( isset($body['log_tail']) && is_array($body['log_tail']) ) {
							echo '<h2>log_tail</h2><pre style="background:#111;color:#eee;padding:12px;white-space:pre-wrap;">';
							echo esc_html( implode("\n", $body['log_tail']) );
							echo '</pre>';
						}
					}
					?>

					<h2>JSON completo</h2>
					<pre style="background:#111;color:#eee;padding:12px;white-space:pre-wrap;"><?php
						echo esc_html( is_string($body) ? $body : wp_json_encode( $body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
					?></pre>

				<?php else: ?>
					<p>Premi <strong>Aggiorna stato</strong> per interrogare il demone.</p>
				<?php endif; ?>

			<?php elseif ( $tab === 'logs' ) : ?>

				<?php $lines = isset($_POST['lines']) ? intval($_POST['lines']) : 200; ?>
				<form method="post" style="margin-bottom:1em;">
					<?php wp_nonce_field( 'petbuy_sync_logs' ); ?>
					<label>Righe:
						<input type="number" min="10" max="2000" step="10" name="lines" value="<?php echo intval($lines); ?>" />
					</label>
					<button class="button button-primary" name="petbuy_sync_logs" value="1">Carica (GET /logs)</button>
					<span class="description" style="margin-left:8px;">Server: <?php echo $base ? $base : '<em>non configurato</em>'; ?></span>
				</form>

				<?php if ( $result_logs ) : ?>
					<table class="widefat striped" style="max-width:1200px">
						<tbody>
							<tr><th>URL</th><td><?php echo esc_html( $result_logs['url'] ?? '' ); ?></td></tr>
							<tr><th>Status</th><td><?php echo esc_html( strval( $result_logs['status'] ?? '' ) ); ?></td></tr>
						</tbody>
					</table>
					<h2>Log</h2>
					<pre style="background:#111;color:#eee;padding:12px;white-space:pre-wrap;"><?php
						$body = $result_logs['body'] ?? null;
						if ( is_array($body) ) echo esc_html( implode("\n", $body) );
						else echo esc_html( is_string($body) ? $body : (string) ($result_logs['raw'] ?? '') );
					?></pre>
				<?php else: ?>
					<p>Imposta il numero di righe e premi <strong>Carica</strong> per visualizzare la coda log.</p>
				<?php endif; ?>

			<?php endif; ?>
		</div>
	<?php }

	private static function render_assoc_table( string $title, array $assoc ): void {
		echo '<h2>'.esc_html($title).'</h2>';
		echo '<table class="widefat striped" style="max-width:800px"><tbody>';
		foreach ( $assoc as $k => $v ) {
			echo '<tr><th style="width:240px">'.esc_html((string)$k).'</th><td>';
			if ( is_array($v) ) {
				echo '<code>'.esc_html( wp_json_encode($v, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) ).'</code>';
			} else {
				echo esc_html( (string) $v );
			}
			echo '</td></tr>';
		}
		echo '</tbody></table>';
	}
}