<?php

namespace Art\Cleaner;

use Art\Cleaner\Admin\Options;
use Art\Cleaner\Admin\Sections\Admin;
use Art\Cleaner\Admin\Sections\General;
use Art\Cleaner\Admin\Sections\Head;
use Art\Cleaner\Admin\Sections\Plugins;
use Art\Cleaner\Admin\Settings;
use Art\Cleaner\Cleanup_Core\Autoremove_Attachments;
use Art\Cleaner\Cleanup_Core\Cleanup_Bar;
use Art\Cleaner\Cleanup_Core\Cleanup_Common;
use Art\Cleaner\Cleanup_Core\Cleanup_Dashboard;
use Art\Cleaner\Cleanup_Core\Cleanup_Head;
use Art\Cleaner\Cleanup_Core\Cleanup_Widgets;
use Art\Cleaner\Cleanup_Core\Disable_Aggressive_Updates;
use Art\Cleaner\Cleanup_Core\Disable_Comments;
use Art\Cleaner\Cleanup_Core\Disable_Embeds;
use Art\Cleaner\Cleanup_Core\Disable_Emoji;
use Art\Cleaner\Cleanup_Core\Disable_Feed;
use Art\Cleaner\Cleanup_Core\Disable_Xml_Rpc;
use Art\Cleaner\Cleanup_Plugins\RankMath\Disabled;
use Art\Cleaner\Cleanup_Plugins\Woocommerce;

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
	protected Utils $utils;


	public function init() {

		add_action( 'plugins_loaded', [ $this, 'init_all' ], - PHP_INT_MAX );
	}


	public function init_all() {

		$this->init_classes();
		$this->init_condition_classes();
	}


	public function init_classes(): void {

		$this->utils = new Utils();
		$this->wposa = new Options( $this->utils );

		( new General( $this->wposa, $this->utils ) )->init_hooks();
		( new Admin( $this->wposa, $this->utils ) )->init_hooks();
		( new Head( $this->wposa, $this->utils ) )->init_hooks();
		( new Plugins\Woocommerce( $this->wposa, $this->utils ) )->init_hooks();
		( new Plugins\RankMath( $this->wposa, $this->utils ) )->init_hooks();
	}


	public function init_condition_classes(): void {

		$data = [
			'disable_aggressive_updates',
			'disable_emoji',
			'disable_feed',
			'disable_embeds',
			'disable_xml_rpc',
			'disable_comments',
			'autoremove_attachments',
			'disabled_woocommerce',
			'disabled_rank_math',
			'cleanup_head',
			'cleanup_dashboard',
			'cleanup_admin_bar',
			'cleanup_widgets',
			'cleanup_common',
		];

		foreach ( $data as $value ) {
			if ( is_callable( [ $this, 'set_' . $value ] ) ) {
				$method = 'set_' . $value;
				$this->$method();
			}
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


	/**
	 * @return void
	 */
	protected function set_disable_aggressive_updates(): void {

		if ( is_admin() && 'on' === Options::get( 'disable_aggressive_updates', 'general' ) ) {
			( new Disable_Aggressive_Updates() )->init_hooks();
		}
	}


	/**
	 * @return void
	 */
	protected function set_disable_emoji(): void {

		if ( 'on' === Options::get( 'disable_emoji', 'general' ) ) {
			( new Disable_Emoji() )->init_hooks();
		}
	}


	/**
	 * @return void
	 */
	protected function set_disable_feed(): void {

		if ( 'on' === Options::get( 'disable_feed', 'general' ) ) {
			( new Disable_Feed() )->init_hooks();
		}
	}


	/**
	 * @return void
	 */
	protected function set_disable_embeds(): void {

		if ( 'on' === Options::get( 'disable_embeds', 'general' ) ) {
			( new Disable_Embeds() )->init_hooks();
		}
	}


	/**
	 * @return void
	 */
	protected function set_disable_xml_rpc(): void {

		if ( 'on' === Options::get( 'disable_xml', 'general' ) ) {
			( new Disable_Xml_Rpc() )->init_hooks();
		}
	}


	/**
	 * @return void
	 */
	protected function set_disable_comments(): void {

		if ( 'on' === Options::get( 'disable_comments', 'general' ) ) {
			( new Disable_Comments() )->init_hooks();
		}
	}


	/**
	 * @return void
	 */
	protected function set_autoremove_attachments(): void {

		if ( is_admin() && 'on' === Options::get( 'autoremove_attachments', 'general' ) ) {
			( new Autoremove_Attachments() )->init_hooks();
		}
	}


	/**
	 * @return void
	 */
	protected function set_cleanup_head(): void {

		if ( ! is_admin() ) {
			( new Cleanup_Head() )->init_hooks();
		}
	}


	/**
	 * @return void
	 */
	protected function set_cleanup_dashboard(): void {

		if ( is_admin() && 'on' === Options::get( 'cleanup_dashboard', 'admin' ) ) {
			( new Cleanup_Dashboard() )->init_hooks();
		}
	}


	/**
	 * @return void
	 */
	protected function set_cleanup_admin_bar(): void {

		if ( 'on' === Options::get( 'cleanup_admin_bar', 'admin' ) ) {
			( new Cleanup_Bar() )->init_hooks();
		}
	}


	/**
	 * @return void
	 */
	protected function set_cleanup_widgets(): void {

		if ( is_admin() ) {
			( new Cleanup_Widgets() )->init_hooks();
		}
	}


	/**
	 * @return void
	 */
	protected function set_cleanup_common(): void {

		if ( is_admin() ) {
			( new Cleanup_Common() )->init_hooks();
		}
	}


	/**
	 * @return void
	 */
	protected function set_disabled_woocommerce(): void {

		if ( is_admin() ) {
			( new Cleanup_Plugins\Woocommerce\Disabled() )->init_hooks();
		}
	}


	/**
	 * @return void
	 */
	protected function set_disabled_rank_math(): void {

		( new Disabled() )->init_hooks();
	}
}
