<?php

namespace Art\Cleaner\Cleanup_Plugins\Woocommerce;

use Art\Cleaner\Admin\Options;
use Automattic\WooCommerce\Internal\Admin\Analytics;

class Disabled {

	public function init_hooks(): void {

		if ( 'on' === Options::get( 'woocommerce_disable_feature', 'plugins', 'off' ) ) {
			$this->disable_feature();
		}

		if ( 'on' === Options::get( 'woocommerce_disable_admin_menu', 'plugins', 'off' ) ) {
			add_action( 'admin_menu', [ $this, 'remove_admin_addon_submenu' ], 999 );
		}

		add_action( 'admin_menu', [ $this, 'remove_admin_addon_submenu_conditionals' ], 999 );
	}


	/**
	 * @return void
	 */
	protected function disable_feature(): void {

		add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );

		add_filter( 'woocommerce_admin_disabled', '__return_true' );

		add_action( 'admin_enqueue_scripts', [ $this, 'only_wc_admin_app' ], 19 );
		add_filter( 'woocommerce_admin_get_feature_config', [ $this, 'feature_config' ], 1000, 1 );
	}


	/**
	 * Отключение разделов Аналитика, Маркетинг и страницы Обзор
	 *
	 * @param  array $feature_config Array of feature slugs.
	 *
	 * @return array
	 */
	public function feature_config( array $feature_config ): array {

		return [
			'activity-panels'                      => false, // требуется для Site Visibility
			'analytics'                            => false,
			'product-block-editor'                 => false,
			'experimental-blocks'                  => false,
			'coupons'                              => false,
			'core-profiler'                        => false,
			'customize-store'                      => false,
			'customer-effort-score-tracks'         => false,
			'import-products-task'                 => false,
			'experimental-fashion-sample-products' => false,
			'shipping-smart-defaults'              => false,
			'shipping-setting-tour'                => false,
			'homescreen'                           => false,
			'marketing'                            => false, // требуется для Site Visibility
			'minified-js'                          => false,
			'mobile-app-banner'                    => false,
			'navigation'                           => false,
			'onboarding'                           => false,
			'onboarding-tasks'                     => false,
			'pattern-toolkit-full-composability'   => false,
			'product-pre-publish-modal'            => false,
			'product-custom-fields'                => false,
			'remote-inbox-notifications'           => false,
			'remote-free-extensions'               => false,
			'payment-gateway-suggestions'          => false,
			'printful'                             => false,
			'settings'                             => false,
			'shipping-label-banner'                => false,
			'subscriptions'                        => false,
			'store-alerts'                         => false,
			'transient-notices'                    => false,
			'woo-mobile-welcome'                   => false,
			'wc-pay-promotion'                     => false,
			'wc-pay-welcome-page'                  => false,
			'async-product-editor-category-field'  => false,
			'launch-your-store'                    => false, // требуется для Site Visibility
			'product-editor-template-system'       => false,
		];
	}


	public function only_wc_admin_app(): void {

		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		$screen_current = [
			'woocommerce_page_wc-settings',
			'woocommerce_page_wc-admin',
		];

		if ( in_array( $screen_id, $screen_current, true ) ) {
			wp_enqueue_style( 'wc-components' );
			wp_enqueue_style( 'wc-admin-app' );
			wp_add_inline_style( 'wc-admin-app', '#wpbody { margin-top: 0;}' );
		}
	}


	public function remove_admin_addon_submenu(): void {

		remove_submenu_page( 'woocommerce', 'wc-admin' );
		remove_submenu_page( 'woocommerce', 'wc-addons' );
		remove_submenu_page( 'woocommerce', 'wc-reports' );
		remove_submenu_page( 'woocommerce', 'wc-addons&section=helper' );
		remove_submenu_page( 'woocommerce', 'wc-admin&path=/extensions' );
	}


	public function remove_admin_addon_submenu_conditionals(): void {

		if ( 'no' === get_option( 'woocommerce_show_marketplace_suggestions' ) ) {
			remove_submenu_page( 'woocommerce', 'wc-admin&path=/extensions' );
		}

		if ( 'no' === get_option( 'woocommerce_enable_reviews' ) ) {
			remove_submenu_page( 'edit.php?post_type=product', 'product-reviews' );
		}
	}
}
