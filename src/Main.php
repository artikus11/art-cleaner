<?php

namespace Art\Cleaner;

use Art\Cleaner\Admin\Options;
use Art\Cleaner\Admin\Settings;
use Art\Cleaner\Core\Cleanup_Admin;
use Art\Cleaner\Core\Cleanup_Bar;
use Art\Cleaner\Core\Cleanup_Dashboard;
use Art\Cleaner\Core\Cleanup_Head;
use Art\Cleaner\Core\Cleanup_Widgets;
use Art\Cleaner\Core\Disable_Aggressive_Updates;
use Art\Cleaner\Core\Disable_Embeds;
use Art\Cleaner\Core\Disable_Emoji;
use Art\Cleaner\Core\Disable_Feed;
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
	protected Tools $tools;


	/**
	 * @var \Art\Cleaner\Updater
	 */
	protected Updater $updater;


	/**
	 * @var \Art\Cleaner\Admin\Options
	 */
	protected Options $wposa;


	/**
	 * @var \Art\Cleaner\Admin\Settings
	 */
	protected Settings $settings;


	/**
	 * @var \Art\Cleaner\Utils
	 */
	protected $utils;


	public function init() {

		add_action( 'plugins_loaded', [ $this, 'init_all' ], - PHP_INT_MAX );
	}


	public function init_all() {

		//$this->init_cli();
		$this->init_classes();
		$this->init_condition_classes();
	}


	public function init_classes() {

		$this->utils    = new Utils();
		$this->wposa    = new Options( $this->utils );
		$this->settings = new Settings( $this->wposa, $this->utils );
	}


	public function init_condition_classes() {

		if ( is_admin() && 'yes' === Options::get( 'disable_aggressive_updates', 'general' ) ) {
			( new Disable_Aggressive_Updates() )->init_hooks();
		}

		if ( 'yes' === Options::get( 'disable_emoji', 'general' ) ) {
			( new Disable_Emoji() )->init_hooks();
		}

		if ( 'yes' === Options::get( 'disable_feed', 'general' ) ) {
			( new Disable_Feed() )->init_hooks();
		}

		if ( 'yes' === Options::get( 'disable_embeds', 'general' ) ) {
			( new Disable_Embeds() )->init_hooks();
		}

		if ( ! is_admin() ) {
			( new Cleanup_Head() )->init_hooks();
		}

		( new Cleanup_Admin() )->init_hooks();

		if ( is_admin() && 'yes' === Options::get( 'cleanup_dashboard', 'admin' ) ) {
			( new Cleanup_Dashboard() )->init_hooks();
		}

		if ( 'yes' === Options::get( 'cleanup_admin_bar', 'admin' ) ) {
			( new Cleanup_Bar() )->init_hooks();
		}

		if ( 'yes' === Options::get( 'cleanup_widgets', 'admin' ) ) {
			( new Cleanup_Widgets() )->init_hooks();
		}
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
		/*if ( class_exists( 'Woocommerce' ) && ! $this->is_cli() ) {

		}*/
	}


	protected function init_cli() {

		if ( ! $this->is_cli() ) {
			return;
		}

		$this->cli = new CLI( $this->tools );

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
