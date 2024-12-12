<?php
/**
 * В этом файле отключается весь ненужный функционал, создаваемый ядром, плагинами и темами.
 *
 * Art\Kadence_Child\Hide class
 *
 * @package kadence-child
 */

namespace Art\Cleaner;

use WP_Admin_Bar;

class Hide {

	public function init_hooks() {

		add_filter( 'wp_count_comments', [ $this, 'count_comments_empty' ] );
		add_filter( 'intermediate_image_sizes', [ $this, 'delete_intermediate_image_sizes' ] );
	}


	/**
	 * Отключает создание миниатюр файлов для указанных размеров.
	 *
	 * @param  array $sizes
	 *
	 * @return array
	 */
	public function delete_intermediate_image_sizes( $sizes ) {
		return array_diff( $sizes, [
			'medium_large',
			'1536x1536',
			'2048x2048',
		] );
	}


	/**
	 * @return object
	 */
	public function count_comments_empty() {

		return (object) [
			'approved'            => 0,
			'awaiting_moderation' => 0,
			'moderated'           => 0,
			'spam'                => 0,
			'trash'               => 0,
			'post-trashed'        => 0,
			'total_comments'      => 0,
			'all'                 => 0,
		];
	}
}
