<?php
/**
 * Class Cleanup_Dashboard
 *
 * @since   2.0.0
 * @package art-cleaner
 */

namespace Art\Cleaner\Cleanup_Core;

class Cleanup_Dashboard {

	public function init_hooks(): void {

		add_action( 'admin_init', [ $this, 'init' ], PHP_INT_MAX );

		add_action( 'wp_dashboard_setup', [ $this, 'dashboard' ], PHP_INT_MAX );
	}


	public function init() {

		remove_action( 'welcome_panel', 'wp_welcome_panel' );
		remove_action( 'admin_print_scripts-index.php', 'wp_localize_community_events' );

		// Отключение вкладки Помощь
		add_action( 'admin_head', [ $this, 'remove_wp_help_tab' ] );

		// Отключение надписей в подвале админки
		add_filter( 'admin_footer_text', '__return_empty_string' );
		add_filter( 'update_footer', '__return_empty_string', 11 );
	}


	public function dashboard() {

		global $wp_meta_boxes;

		unset( $wp_meta_boxes['dashboard'] );
	}


	public function remove_wp_help_tab() {

		$screen = get_current_screen();
		$screen->remove_help_tabs();
	}
}