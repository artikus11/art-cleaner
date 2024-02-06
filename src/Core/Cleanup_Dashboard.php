<?php

namespace Art\Cleaner\Core;

class Cleanup_Dashboard {
	public function init_hooks(): void {
		add_action( 'admin_init', [ $this, 'init' ], PHP_INT_MAX );

		add_action( 'wp_dashboard_setup', [ $this, 'dashboard' ], PHP_INT_MAX );
	}

	public function init(){
		remove_action( 'welcome_panel', 'wp_welcome_panel');
		remove_action( 'admin_print_scripts-index.php', 'wp_localize_community_events' );
	}


	public function dashboard() {

		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );

/*		$dash_side        = &$GLOBALS['wp_meta_boxes']['dashboard']['side']['core'];
		$dash_normal      = &$GLOBALS['wp_meta_boxes']['dashboard']['normal']['core'];
		$dash_normal_high = &$GLOBALS['wp_meta_boxes']['dashboard']['normal']['high'];

		// Быстрая публикация
		unset(
			$dash_side['dashboard_quick_press'],
			$dash_side['dashboard_recent_drafts'],
			$dash_side['dashboard_primary'],
			$dash_side['dashboard_secondary'],
			$dash_normal['dashboard_incoming_links'],
			$dash_normal['dashboard_right_now'],
			$dash_normal['dashboard_recent_comments'],
			$dash_normal['dashboard_plugins'],
			$dash_normal['dashboard_site_health'],
			$dash_normal['dashboard_activity'],
			$dash_normal['woo_vl_news_widget'],
			$dash_normal['woo_st-dashboard_right_now'],
			$dash_normal['woo_st-dashboard_sales'],
			$dash_normal['woocommerce_dashboard_status'],
			$dash_normal['woocommerce_dashboard_recent_reviews'],
			$dash_normal['owp_dashboard_news'],
			$dash_normal['so-dashboard-news'],
			$dash_normal['wp_mail_smtp_reports_widget_lite'],
			$dash_normal['yith_dashboard_products_news'],
			$dash_normal['yith_dashboard_blog_news'],
			$dash_normal['wpseo-dashboard-overview'],
			$dash_normal_high['rank_math_dashboard_widget'],
			$dash_normal_high['dashboard_rediscache'],
			$dash_normal_high['aioseo-seo-setup'],
			$dash_normal['aioseo-overview'],
			$dash_normal['aioseo-rss-feed'],
			$dash_normal['wcfm_dashboard_status']
		);*/
	}
}