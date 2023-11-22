<?php

namespace Art\Cleaner;

use Exception;
use WP_CLI;

class Main {

	/**
	 * @var \Art\Cleaner\CLI
	 */
	protected CLI $cli;

	/**
	 * @var \Art\Cleaner\Woocommerce_Cleanup
	 */
	protected Woocommerce_Cleanup $flushing;


	public function __construct() {

		$this->flushing = new Woocommerce_Cleanup();

		$this->updater_init();
		$this->init_cli();


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

			$this->flushing->init_hooks();

		}

	}


	protected function init_cli() {

		if ( ! $this->is_cli() ) {
			return;
		}

		$this->cli = new CLI( $this->flushing );

		try {
			/**
			 * Method WP_CLI::add_command() accepts class as callable.
			 *
			 * @noinspection PhpParamsInspection
			 */
			WP_CLI::add_command(
				'acl',
				$this->cli,
				[ 'shortdesc' => 'Flushing logs, completed cron tasks and order notes.', ]
			);
		} catch ( Exception $ex ) {
			return;
		}

	}


	private function updater_init(): void {

		$updater = new Updater( ACL_PLUGIN_AFILE );
		$updater->set_repository( 'art-cleaner' );
		$updater->set_username( 'artikus11' );
		//$updater->set_authorize( 'Z2hwX3BaWlVBSW43NU9wczl1Tk5MVkdJVUFnYUVlblNEUzBqQWh0UQ==' );
		$updater->init();
	}


	/**
	 * @return bool
	 */
	protected function is_cli(): bool {

		return defined( 'WP_CLI' ) && constant( 'WP_CLI' );
	}

}