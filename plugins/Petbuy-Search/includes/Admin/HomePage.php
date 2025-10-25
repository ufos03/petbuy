<?php
namespace Petbuy\Search\Admin;

use Petbuy\Search\Search\MetricsRepository;

class HomePage extends AbstractPage {

	public function get_slug(): string {
		return 'petbuy-search-home';
	}

	public function get_title(): string {
		return 'Petbuy Search – Statistiche';
	}

	public function get_menu_title(): string {
		return 'Home';
	}

	public function render(): void {
		$metrics = new MetricsRepository();
		$summary = $metrics->getSummaryStats();
		$topHits = $metrics->getTopTermsByHits(7);
		$topCtr  = $metrics->getTopTermsByCTR(7, 10);

		$totHits = $summary['total_hits'] ?? 0;
		$totClicks = $summary['total_clicks'] ?? 0;
		$totCompletions = $summary['total_completions'] ?? 0;
		$totTerms = $summary['total_terms'] ?? 0;

		$globalCtr = $totHits > 0 ? ($totClicks / $totHits) * 100 : 0;
		$completionRate = $totClicks > 0 ? ($totCompletions / $totClicks) * 100 : 0;

		$chartPayload = [
			'topHits' => [
				'labels' => array_map(
					static fn($row) => $row['term'],
					$topHits
				),
				'values' => array_map(
					static fn($row) => $row['hits'],
					$topHits
				),
			],
		];

		?>
		<div class="wrap petbuy-qsearch-dashboard">
			<h1>Quick Search – Insight</h1>
			<p class="description">Monitoraggio in tempo reale di suggerimenti, click e conversioni raccolti dal motore di ricerca.</p>

			<?php if ( $totHits === 0 ) : ?>
				<div class="notice notice-info">
					<p>Nessun dato disponibile al momento. Effettua qualche ricerca o attendi il tracciamento degli utenti per popolare le metriche.</p>
				</div>
			<?php endif; ?>

			<div class="petbuy-metric-cards">
				<div class="petbuy-card">
					<h3>Impression totali</h3>
					<p class="petbuy-metric-number"><?php echo esc_html( number_format_i18n( $totHits ) ); ?></p>
					<p class="description">Suggerimenti mostrati agli utenti</p>
				</div>
				<div class="petbuy-card">
					<h3>Click</h3>
					<p class="petbuy-metric-number"><?php echo esc_html( number_format_i18n( $totClicks ) ); ?></p>
					<p class="description">Interazioni sui suggerimenti</p>
				</div>
				<div class="petbuy-card">
					<h3>CTR globale</h3>
					<p class="petbuy-metric-number"><?php echo esc_html( number_format_i18n( $globalCtr, 2 ) ); ?>%</p>
					<p class="description">Click / Impression</p>
				</div>
				<div class="petbuy-card">
					<h3>Completamenti</h3>
					<p class="petbuy-metric-number"><?php echo esc_html( number_format_i18n( $totCompletions ) ); ?></p>
					<p class="description">Ricerche complete dopo il click</p>
				</div>
				<div class="petbuy-card">
					<h3>Completion rate</h3>
					<p class="petbuy-metric-number"><?php echo esc_html( number_format_i18n( $completionRate, 2 ) ); ?>%</p>
					<p class="description">Completamenti / Click</p>
				</div>
				<div class="petbuy-card">
					<h3>Termini monitorati</h3>
					<p class="petbuy-metric-number"><?php echo esc_html( number_format_i18n( $totTerms ) ); ?></p>
					<p class="description">Totale termini con metriche</p>
				</div>
			</div>

			<div class="petbuy-charts-grid">
				<div class="petbuy-card petbuy-chart-card">
					<h2>Top query per impression</h2>
					<?php if ( ! empty( $topHits ) ) : ?>
						<div class="petbuy-chart-wrapper">
							<canvas id="petbuy-chart-top-hits"></canvas>
						</div>
					<?php else : ?>
						<p class="description">Ancora nessuna query popolare.</p>
					<?php endif; ?>
				</div>
			</div>

			<div class="petbuy-table-grid">
				<div class="petbuy-card">
					<h2>Elenco top query</h2>
					<?php if ( ! empty( $topHits ) ) : ?>
						<table class="widefat striped">
							<thead>
								<tr>
									<th>Termine</th>
									<th style="text-align:right;">Impression</th>
									<th style="text-align:right;">Click</th>
									<th style="text-align:right;">CTR</th>
									<th style="text-align:right;">Completamenti</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $topHits as $row ) :
									$ctr = $row['hits'] > 0 ? ( $row['clicks'] / $row['hits'] ) * 100 : 0;
									?>
									<tr>
										<td><strong><?php echo esc_html( $row['term'] ); ?></strong></td>
										<td style="text-align:right;"><?php echo esc_html( number_format_i18n( $row['hits'] ) ); ?></td>
										<td style="text-align:right;"><?php echo esc_html( number_format_i18n( $row['clicks'] ) ); ?></td>
										<td style="text-align:right;"><?php echo esc_html( number_format_i18n( $ctr, 2 ) ); ?>%</td>
										<td style="text-align:right;"><?php echo esc_html( number_format_i18n( $row['completed_searches'] ) ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else : ?>
						<p class="description">Le query più cercate compariranno qui a breve.</p>
					<?php endif; ?>
				</div>
				<div class="petbuy-card">
					<h2>Miglior CTR</h2>
					<?php if ( ! empty( $topCtr ) ) : ?>
						<table class="widefat striped">
							<thead>
								<tr>
									<th>Termine</th>
									<th style="text-align:right;">CTR</th>
									<th style="text-align:right;">Impression</th>
									<th style="text-align:right;">Click</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $topCtr as $row ) :
									?>
									<tr>
										<td><strong><?php echo esc_html( $row['term'] ); ?></strong></td>
										<td style="text-align:right;"><?php echo esc_html( number_format_i18n( $row['ctr'] * 100, 2 ) ); ?>%</td>
										<td style="text-align:right;"><?php echo esc_html( number_format_i18n( $row['hits'] ) ); ?></td>
										<td style="text-align:right;"><?php echo esc_html( number_format_i18n( $row['clicks'] ) ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else : ?>
						<p class="description">Nessun click registrato ancora per calcolare il CTR.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				if (typeof Chart === 'undefined') {
					return;
				}

				const data = <?php echo wp_json_encode( $chartPayload ); ?>;

				if (Array.isArray(data.topHits.values) && data.topHits.values.length) {
					const ctxHits = document.getElementById('petbuy-chart-top-hits');
					if (ctxHits) {
						new Chart(ctxHits, {
							type: 'bar',
							data: {
								labels: data.topHits.labels,
								datasets: [{
									label: 'Impression',
									data: data.topHits.values,
									backgroundColor: '#1d72b8',
									borderRadius: 6,
									maxBarThickness: 36,
								}]
							},
							options: {
								scales: {
									y: {
										beginAtZero: true,
										grid: { color: '#eef2f7' },
										ticks: { precision: 0 }
									},
									x: {
										grid: { display: false },
										ticks: { maxRotation: 45, minRotation: 0 }
									}
								},
								plugins: {
									legend: { display: false },
									tooltip: {
										backgroundColor: '#1d72b8',
										callbacks: {
											label: function(context) {
												return 'Impression: ' + context.formattedValue;
											}
										}
									}
								},
								maintainAspectRatio: false
							}
						});
					}
				}
			});
		</script>

		<style>
			.petbuy-qsearch-dashboard .petbuy-metric-cards {
				display: grid;
				grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
				gap: 20px;
				margin-top: 20px;
				margin-bottom: 30px;
			}
			.petbuy-qsearch-dashboard .petbuy-charts-grid,
			.petbuy-qsearch-dashboard .petbuy-table-grid {
				display: grid;
				grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
				gap: 20px;
				margin-bottom: 30px;
			}
			.petbuy-qsearch-dashboard .petbuy-card {
				background: #fff;
				border: 1px solid #dcdcdc;
				border-radius: 6px;
				padding: 20px;
				box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
			}
			.petbuy-qsearch-dashboard .petbuy-metric-number {
				font-size: 28px;
				font-weight: 600;
				margin: 10px 0 0;
			}
			.petbuy-qsearch-dashboard canvas {
				max-width: 100%;
			}
			.petbuy-chart-card {
				padding-bottom: 5px;
			}
			.petbuy-chart-wrapper {
				position: relative;
				min-height: 220px;
				height: clamp(220px, 32vw, 360px);
			}
			.petbuy-chart-card canvas {
				width: 100% !important;
				height: 100% !important;
			}
			.petbuy-qsearch-dashboard table.widefat th,
			.petbuy-qsearch-dashboard table.widefat td {
				vertical-align: middle;
			}
		</style>
		<?php
	}
}
