<?php
namespace Petbuy\Search;

/**
 * Autoloader PSR-4 semplificato con supporto
 * sia a `Namespace\Foo\Bar => includes/Foo/Bar.php`
 * sia a `class-bar.php` (WordPress style, lowercase).
 */
spl_autoload_register(
	function ( $class ) {
		$prefix = __NAMESPACE__ . '\\';

		// ignora classi fuori dal nostro namespace
		if ( strncmp( $prefix, $class, strlen( $prefix ) ) !== 0 ) {
			return;
		}

		// estrae la parte senza namespace radice
		$relative = substr( $class, strlen( $prefix ) );

		// Foo\Bar => Foo/Bar
		$path = str_replace( '\\', DIRECTORY_SEPARATOR, $relative );

		$base_dir = PETBUY_SEARCH_PLUGIN_DIR . 'includes' . DIRECTORY_SEPARATOR;

		$candidates = [
			$base_dir . $path . '.php',                                   // PSR-4 (Foo/Bar.php)
			$base_dir . 'class-' . strtolower( basename( $path ) ) . '.php', // class-bar.php
		];

		foreach ( $candidates as $file ) {
			if ( file_exists( $file ) ) {
				require_once $file;
				return;
			}
		}
	}
);
