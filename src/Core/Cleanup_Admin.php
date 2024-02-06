<?php

namespace Art\Cleaner\Core;

class Cleanup_Admin {

	public function init_hooks(): void {

		add_action( 'admin_head', [ $this, 'remove_wp_help_tab' ] );

		$this->disable_footer_text();
	}


	public function disable_footer_text() {

		add_filter( 'admin_footer_text', '__return_empty_string' );
		add_filter( 'update_footer', '__return_empty_string', 11 );
	}

	public function remove_wp_help_tab() {

		$screen = get_current_screen();
		$screen->remove_help_tabs();
	}
}