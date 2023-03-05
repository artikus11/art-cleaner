<?php

namespace Art\Cleaner;

class Woocommerce_Disabled {

	public function init_hooks(): void {

		// Отключение раздела Маркетинг
		add_filter( 'woocommerce_marketing_menu_items', '__return_empty_array' );
		add_filter( 'woocommerce_admin_features', [ $this, 'disable_features' ] );

		// Отключение раздела ноисов
		add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );

		// Отключение раздела Аналитика
		add_filter( 'woocommerce_admin_disabled', '__return_true' );

		// Отключение стилй панели Вукомерса
		add_action( 'admin_enqueue_scripts', [ $this, 'disable_wc_admin_app' ], 19 );

		// Скрытие верхнего бара на страницах Вукомерса
		add_action( 'admin_head', [ $this, 'hide_header' ] );

		// Отклбчение виджетов Вукомерса в Консоли
		add_action( 'wp_dashboard_setup', [ $this, 'disable_woocommerce_status' ], PHP_INT_MAX );
		add_action( 'admin_menu', [ $this, 'disable_woocommerce_status' ], PHP_INT_MAX );
		add_action( 'wp_user_dashboard_setup', [ $this, 'disable_woocommerce_status' ], PHP_INT_MAX );
		// Отклбчение раздела Дополнения
		add_action( 'admin_menu', [ $this, 'remove_admin_addon_submenu' ], 999 );

		// Отключение сбора статистики и отслеживания
		add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false', 999 );
		add_filter( 'woocommerce_apply_user_tracking', '__return_false', 999 );
		add_filter( 'woocommerce_apply_tracking', '__return_false', 999 );

	}


	public function disable_features( $features ) {

		$marketing = array_search( 'marketing', $features, true );
		unset( $features[ $marketing ] );

		return $features;
	}


	public function disable_wc_admin_app(): void {

		wp_dequeue_style( 'wc-admin-app' );
		wp_deregister_style( 'wc-admin-app' );
		?>
		<style>.woocommerce-layout__header {
				display: none;
			}</style>
		<?php
	}


	public function hide_header(): void {

		?>
		<style>
			.woocommerce-layout__header {
				display: none;
			}
		</style>
		<?php
	}


	public function disable_woocommerce_status(): void {

		remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal' );
		remove_meta_box( 'woocommerce_network_orders', 'dashboard', 'normal' );
		remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal' );
		remove_meta_box( 'wc_admin_dashboard_setup', 'dashboard', 'normal' );
	}


	public function remove_admin_addon_submenu(): void {

		remove_submenu_page( 'woocommerce', 'wc-admin' );
		remove_submenu_page( 'woocommerce', 'wc-addons' );
		remove_submenu_page( 'woocommerce', 'wc-addons&section=helper' );
	}

}