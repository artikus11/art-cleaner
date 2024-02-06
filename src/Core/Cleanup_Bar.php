<?php

namespace Art\Cleaner\Core;

class Cleanup_Bar {
	public function init_hooks(): void {
		add_action( 'admin_bar_menu', [ $this, 'admin_bar' ], PHP_INT_MAX );
	}

	public function admin_bar( $wp_admin_bar ): void {

		$wp_admin_bar->remove_node( 'wp-logo' );
		$wp_admin_bar->remove_node( 'comments' );
		$wp_admin_bar->remove_node( 'new-content' );
		$wp_admin_bar->remove_node( 'theme-dashboard' );
		$wp_admin_bar->remove_node( 'new_draft' );
		$wp_admin_bar->remove_node( 'updates' );
		$wp_admin_bar->remove_node( 'archive' );
/*		$wp_admin_bar->remove_node( 'flatsome_panel' );
		$wp_admin_bar->remove_node( 'rank-math' );
		$wp_admin_bar->remove_node( 'bapf_debug_bar' );
		$wp_admin_bar->remove_node( 'btn-wcabe-admin-bar' );
		$wp_admin_bar->remove_node( 'wpvivid_admin_menu' );
		$wp_admin_bar->remove_node( 'wp-mail-smtp-menu' );
		$wp_admin_bar->remove_node( 'aioseo-main' );
		$wp_admin_bar->remove_node( 'wpdiscuz' );
		$wp_admin_bar->remove_node( 'duplicate-post' );
		$wp_admin_bar->remove_node( 'updraft_admin_node' );*/
	}
}