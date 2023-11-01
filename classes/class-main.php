<?php

namespace Art\Cleaner;

class Main {

	public function __construct() {

		add_action( 'after_setup_theme', [ $this, 'init_hooks' ] );
	}


	public function init_hooks(): void {

		( new Disable_Aggressive_Updates() )->init_hooks();
		( new Hide() )->init_hooks();
		( new Cleanup() )->init_hooks();

		if ( class_exists( 'Woocommerce' ) && ! defined( 'WP_CLI' ) ) {
			( new Woocommerce_Disabled() )->init_hooks();

			( new Woocommerce_Cleanup() )->init_hooks();
		}

	}

}