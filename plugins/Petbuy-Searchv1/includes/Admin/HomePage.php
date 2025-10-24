<?php
namespace Petbuy\Search\Admin;

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
		?>
		<div class="wrap">
			<h1>Statistiche (placeholder)</h1>
			<p>Qui puoi inserire grafici e KPI collegandoti a Google Analytics, WooCommerce ecc.</p>
		</div>
		<?php
	}
}
