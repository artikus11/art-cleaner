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
	 * Удаляет админ-бар у всех пользователей, кроме администраторов сайта.
	 *
	 * @return void
	 */
	public function remove_front_admin_bar() {

		if ( ! current_user_can( 'manage_options' ) ) {
			show_admin_bar( false );
		}
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
			'2048x2048'
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


	/**
	 * Изменяет базовый набор элементов (ссылок) в тулбаре.
	 *
	 * @param WP_Admin_Bar $wp_admin_bar The WP_Admin_Bar instance, passed by reference.
	 *
	 * @return void
	 */
	public function admin_bar( $wp_admin_bar ): void {

		$wp_admin_bar->remove_node( 'wp-logo' );
		$wp_admin_bar->remove_node( 'comments' );
		$wp_admin_bar->remove_node( 'new-content' );
		$wp_admin_bar->remove_node( 'litespeed-menu' );
		$wp_admin_bar->remove_node( 'theme-dashboard' );
		$wp_admin_bar->remove_node( 'new_draft' );
		$wp_admin_bar->remove_node( 'updates' );
		$wp_admin_bar->remove_node( 'flatsome_panel' );
		$wp_admin_bar->remove_node( 'rank-math' );
		$wp_admin_bar->remove_node( 'bapf_debug_bar' );
		$wp_admin_bar->remove_node( 'btn-wcabe-admin-bar' );
		$wp_admin_bar->remove_node( 'wpvivid_admin_menu' );
		$wp_admin_bar->remove_node( 'wp-mail-smtp-menu' );
		$wp_admin_bar->remove_node( 'aioseo-main' );
		$wp_admin_bar->remove_node( 'wpdiscuz' );
		$wp_admin_bar->remove_node( 'duplicate-post' );
		$wp_admin_bar->remove_node( 'updraft_admin_node' );
	}


	/**
	 * Изменяет базовый набор элементов (ссылок) в тулбаре.
	 *
	 * @return void
	 */
	public function before_admin_bar(): void {

		$wp_admin_bar = $GLOBALS['wp_admin_bar'];

		$wp_admin_bar->remove_node( 'updraft_admin_node' );
		$wp_admin_bar->remove_node( 'wp-optimize-node' );
	}




}
