<?php

namespace Art\Cleaner\Core;

class Cleanup_Widgets {

	public function init_hooks(): void {

		add_action( 'widgets_init', [ $this, 'remove_widgets' ], - PHP_INT_MAX );
	}


	public function remove_widgets() {

		global $wp_widget_factory;

		$widgets = $wp_widget_factory->widgets;

		$allowed_widgets = [
			'WP_Widget_Text',
			'WP_Widget_Custom_HTML',
			'WP_Widget_Block',
		];

		foreach ( $widgets as $widget_key => $widget ) {
			if ( ! in_array( $widget_key, $allowed_widgets, true ) ) {
				$wp_widget_factory->unregister( $widget_key );
			}
		}
	}
}