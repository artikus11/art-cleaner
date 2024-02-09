<?php

namespace Art\Cleaner\Core;

class Cleanup_Dashboard {

	public function init_hooks(): void {

		add_action( 'admin_init', [ $this, 'init' ], PHP_INT_MAX );

		add_action( 'wp_dashboard_setup', [ $this, 'dashboard' ], PHP_INT_MAX );
	}


	public function init() {

		remove_action( 'welcome_panel', 'wp_welcome_panel' );
		remove_action( 'admin_print_scripts-index.php', 'wp_localize_community_events' );
	}


	public function dashboard() {

		global $wp_meta_boxes;

		unset( $wp_meta_boxes['dashboard'] );
	}
}