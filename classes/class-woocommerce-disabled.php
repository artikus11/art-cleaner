<?php

namespace Art\Cleaner;

class Woocommerce_Disabled {

	public function init_hooks(): void {

		// Отключение раздела ноисов
		add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );

		// Отключение раздела Аналитика
		add_filter( 'woocommerce_admin_disabled', '__return_true' );

		// Отключение стилй панели Вукомерса
		add_action( 'admin_enqueue_scripts', [ $this, 'disable_wc_admin_app' ], 19 );

		add_filter( 'woocommerce_admin_get_feature_config', [ $this, 'feature_config' ], PHP_INT_MAX, 1 );

		// Отклбчение виджетов Вукомерса в Консоли
		add_action( 'wp_dashboard_setup', [ $this, 'disable_woocommerce_status' ], PHP_INT_MAX );
		add_action( 'admin_menu', [ $this, 'disable_woocommerce_status' ], PHP_INT_MAX );
		add_action( 'wp_user_dashboard_setup', [ $this, 'disable_woocommerce_status' ], PHP_INT_MAX );

		// Отключение раздела Дополнения
		add_action( 'admin_menu', [ $this, 'remove_admin_addon_submenu' ], 999 );

		// Отключение сбора статистики и отслеживания
		add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false', 999 );
		add_filter( 'woocommerce_apply_user_tracking', '__return_false', 999 );
		add_filter( 'woocommerce_apply_tracking', '__return_false', 999 );
	}

	/**
	 * Отключение разделов Аналитика, Маркетинг и страницы Обзор
	 *
	 * @param  array $feature_config Array of feature slugs.
	 *
	 * @return array
	 */
	public function feature_config( array $feature_config ): array {

		$feature_config['activity-panels']                   = false;
		$feature_config['analytics']                         = false;
		$feature_config['product-block-editor']              = false;
		$feature_config['minified-js']                       = true;
		$feature_config['navigation']                        = false;
		$feature_config['homescreen']                        = false;
		$feature_config['marketing']                         = false;
		$feature_config['onboarding']                        = false;
		$feature_config['onboarding-tasks']                  = false;
		$feature_config['shipping-setting-tour']             = false;
		$feature_config['shipping-smart-defaults']           = false;
		$feature_config['subscriptions']                     = false;
		$feature_config['store-alerts']                      = false;
		$feature_config['transient-notices']                 = false;
		$feature_config['customize-store']                   = false;
		$feature_config['customer-effort-score-tracks']      = false;
		$feature_config['new-product-management-experience'] = false;

		return $feature_config;
	}

	public function disable_features( $features ) {

		$marketing = array_search( 'marketing', $features, true );
		unset( $features[ $marketing ] );

		return $features;
	}

	public function disable_wc_admin_app(): void {

		wp_dequeue_style( 'wc-admin-app' );
		wp_deregister_style( 'wc-admin-app' );
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
		remove_submenu_page( 'woocommerce', 'wc-reports' );
		remove_submenu_page( 'woocommerce', 'wc-addons&section=helper' );
		remove_submenu_page( 'woocommerce', 'wc-admin&path=/extensions' );
	}
}
