<?php
/**
 * В этом файле отключается весь ненужный функционал, создаваемый ядром, плагинами и темами.
 *
 * Art\Kadence_Child\Hide class
 *
 * @package kadence-child
 */

namespace Art\Cleaner;

class Hide {

	public function init_hooks() {

		// Убирает notice для переключения обратно на Админа.
		// Это можно сделать и в выпадающем меню профиля админбара.
		if ( isset( $GLOBALS['user_switching'] ) ) {
			remove_action( 'all_admin_notices', [ $GLOBALS['user_switching'], 'action_admin_notices' ], 1 );
		}

		remove_action( 'welcome_panel', 'wp_welcome_panel' );
		remove_action( 'admin_print_scripts-index.php', 'wp_localize_community_events' );
		add_action( 'admin_head', [ $this, 'remove_wp_help_tab' ] );

		add_filter( 'admin_footer_text', '__return_empty_string' );
		add_filter( 'update_footer', '__return_empty_string', 11 );
		add_filter( 'pre_site_transient_php_check_' . md5( PHP_VERSION ), '__return_empty_array' );

		add_action('admin_bar_menu', [ $this, 'admin_bar' ], 9999);

		if ( ! current_user_can( 'edit_posts' ) ) {
			add_action( 'init', [ $this, 'remove_front_admin_bar' ] );
		}

		add_action( 'wp_dashboard_setup', [ $this, 'dashboard' ], 100 );
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );

		add_filter( 'wp_count_comments', [ $this, 'count_comments_empty' ] );
		add_filter( 'intermediate_image_sizes', [ $this, 'delete_intermediate_image_sizes' ] );

		// Remove unwanted SVG filter injection WP
		remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
		remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
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
	 * @return void
	 */
	public function admin_bar($wp_admin_bar) {

		//$wp_admin_bar->remove_node('edit');
		//$wp_admin_bar->remove_node('customize');
		$wp_admin_bar->remove_node('wp-logo');
		$wp_admin_bar->remove_node('comments');
		$wp_admin_bar->remove_node('new-content');
		$wp_admin_bar->remove_node('litespeed-menu');
		$wp_admin_bar->remove_node('theme-dashboard');
		$wp_admin_bar->remove_node('new_draft');
		$wp_admin_bar->remove_node('updates');
		$wp_admin_bar->remove_node('flatsome_panel');
		$wp_admin_bar->remove_node('rank-math');
		$wp_admin_bar->remove_node('bapf_debug_bar');
		$wp_admin_bar->remove_node('btn-wcabe-admin-bar');
		$wp_admin_bar->remove_node('wpvivid_admin_menu');
		$wp_admin_bar->remove_node('wp-mail-smtp-menu');
		$wp_admin_bar->remove_node('aioseo-main');
		$wp_admin_bar->remove_node('wpdiscuz');
		$wp_admin_bar->remove_node('duplicate-post');
	}


	/**
	 * Удаляет виджеты из Консоли WordPress.
	 *
	 * @return void
	 */
	public function dashboard() {

		$dash_side        = &$GLOBALS['wp_meta_boxes']['dashboard']['side']['core'];
		$dash_normal      = &$GLOBALS['wp_meta_boxes']['dashboard']['normal']['core'];
		$dash_normal_high = &$GLOBALS['wp_meta_boxes']['dashboard']['normal']['high'];

		// Быстрая публикация
		unset( $dash_side['dashboard_quick_press'] );
		// Последние черновики
		unset( $dash_side['dashboard_recent_drafts'] );
		// Новости и мероприятия WordPress
		unset( $dash_side['dashboard_primary'] );
		// Другие Новости WordPress
		unset( $dash_side['dashboard_secondary'] );
		// Входящие ссылки
		unset( $dash_normal['dashboard_incoming_links'] );
		// Прямо сейчас
		unset( $dash_normal['dashboard_right_now'] );
		// Последние комментарии
		unset( $dash_normal['dashboard_recent_comments'] );
		unset( $dash_normal['dashboard_plugins'] );
		// Виджет здоровья
		unset( $dash_normal['dashboard_site_health'] );
		// Активность
		unset( $dash_normal['dashboard_activity'] );

		unset( $dash_normal['woo_vl_news_widget'] );
		unset( $dash_normal['woo_st-dashboard_right_now'] );
		unset( $dash_normal['woo_st-dashboard_sales'] );

		// Виджет OceanWP
		unset( $dash_normal['owp_dashboard_news'] );
		// Виджет WooCommerce
		unset( $dash_normal['woocommerce_dashboard_status'] );
		// Виджет WooCommerce
		unset( $dash_normal['woocommerce_dashboard_recent_reviews'] );
		// Виджет Page Builder от SiteOrigin
		unset( $dash_normal['so-dashboard-news'] );
		// Виджет Rank Math SEO
		unset( $dash_normal_high['rank_math_dashboard_widget'] );
		// Виджет wp_mail_smtp
		unset( $dash_normal['wp_mail_smtp_reports_widget_lite'] );
		unset( $dash_normal['yith_dashboard_products_news'] );
		unset( $dash_normal['yith_dashboard_blog_news'] );
		unset( $dash_normal['wcfm_dashboard_status'] );

	}


	/**
	 * Изменяет набор пунктов меню в админке.
	 *
	 * @return void
	 */
	public function admin_menu() {

		//remove_menu_page( 'tools.php' );
		//remove_menu_page( 'edit-comments.php' );
		//remove_menu_page( 'upload.php' );
		//remove_menu_page( 'index.php' );
	}


	/**
	 * Удаляет табы-помощники.
	 *
	 * @return void
	 */
	public function remove_wp_help_tab() {

		$screen = get_current_screen();
		$screen->remove_help_tabs();
	}

}
