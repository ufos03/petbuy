<?php
namespace Petbuy\Search\Admin;

abstract class AbstractPage {

	abstract public function get_slug(): string;
	abstract public function get_title(): string;
	abstract public function get_menu_title(): string;
	abstract public function render(): void;

	public function register(): void {
		add_submenu_page(
			'petbuy-search-home',
			$this->get_title(),
			$this->get_menu_title(),
			'manage_options',
			$this->get_slug(),
			[ $this, 'render' ]
		);
	}
}
