<?php

namespace Art\Cleaner;

use Art\Cleaner\Woocommerce\Disabled;
use Art\Cleaner\Woocommerce\Tools;
use Exception;
use WP_CLI;

class Main {

	/**
	 * @var \Art\Cleaner\CLI
	 */
	protected CLI $cli;

	/**
	 * @var \Art\Cleaner\Woocommerce\Tools
	 */
	protected Tools $flushing;
	/**
	 * @var \Art\Cleaner\Updater
	 */
	protected Updater $updater;


	public function __construct() {

		$this->flushing = new Tools();
		$this->updater  = new Updater( ACL_PLUGIN_AFILE );

		$this->updater_init( $this->updater );
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

		( new DisableAggressiveUpdates() )->init_hooks();
		( new Hide() )->init_hooks();
		( new CleanupHead() )->init_hooks();

		if ( class_exists( 'Woocommerce' ) && ! $this->is_cli() ) {
			( new Disabled() )->init_hooks();

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
			 */
			WP_CLI::add_command(
				'acl',
				$this->cli,
				[ 'shortdesc' => 'Flushing logs, completed cron tasks and order notes.' ]
			);
		} catch ( Exception $ex ) {
			return;
		}
	}


	private function updater_init( $updater ): void {

		$updater->set_repository( 'art-cleaner' );
		$updater->set_username( 'artikus11' );

		$updater->init();
	}


	/**
	 * @return bool
	 */
	protected function is_cli(): bool {

		return defined( 'WP_CLI' ) && constant( 'WP_CLI' );
	}
}
