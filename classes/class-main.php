<?php

namespace Art\Cleaner;

class Main {

	public function __construct() {

		$this->updater_init();

		add_action( 'after_setup_theme', [ $this, 'init_hooks' ] );
	}


	public function init_hooks(): void {

		add_action( 'before_woocommerce_init', static function () {

			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
					'custom_order_tables',
					ACL_PLUGIN_FILE,
					true
				);
			}
		} );

		( new Disable_Aggressive_Updates() )->init_hooks();
		( new Hide() )->init_hooks();
		( new Cleanup() )->init_hooks();

		if ( class_exists( 'Woocommerce' ) && ! defined( 'WP_CLI' ) ) {
			( new Woocommerce_Disabled() )->init_hooks();

			( new Woocommerce_Cleanup() )->init_hooks();
		}

	}


	private function updater_init(): void {

		$updater = new Updater( ACL_PLUGIN_AFILE );
		$updater->set_repository( 'art-cleaner' );
		$updater->set_username( 'artikus11' );
		$updater->set_authorize( 'Z2hwX3BaWlVBSW43NU9wczl1Tk5MVkdJVUFnYUVlblNEUzBqQWh0UQ==' );
		$updater->init();
	}

}