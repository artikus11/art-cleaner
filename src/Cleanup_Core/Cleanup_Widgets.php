<?php
/**
 * Class Cleanup_Widgets
 *
 * @since   2.0.0
 * @package art-cleaner
 */

namespace Art\Cleaner\Cleanup_Core;

use Art\Cleaner\Admin\Options;

class Cleanup_Widgets {

	public function init_hooks(): void {

		add_action( 'widgets_init', [ $this, 'remove_widgets' ], - PHP_INT_MAX );
	}


	public function remove_widgets() {

		$widgets = Options::get( 'cleanup_widgets', 'admin' );

		if ( $widgets ) {
			foreach ( $widgets as $widget_key => $widget ) {
				unregister_widget( $widget_key );
			}
		}
	}
}