<?php

namespace Art\Cleaner;

class DisableAggressiveUpdates {

	public function init_hooks(): void {

		if ( is_admin() ) {
			/**
			 *  Отключим проверку обновлений при любом заходе в админку.
			 */
			remove_action( 'admin_init', '_maybe_update_core' );
			remove_action( 'admin_init', '_maybe_update_plugins' );
			remove_action( 'admin_init', '_maybe_update_themes' );

			/**
			 * Отключим проверку обновлений при заходе на специальную страницу в админке.
			 */
			remove_action( 'load-plugins.php', 'wp_update_plugins' );
			remove_action( 'load-themes.php', 'wp_update_themes' );

			/**
			 * Оставим принудительную проверку при заходе на страницу обновлений.
			 */
			remove_action( 'load-update-core.php', 'wp_update_plugins' );
			remove_action( 'load-update-core.php', 'wp_update_themes' );

			$this->check_browser();
		}
	}


	/**
	 * Отключим проверку необходимости обновить браузер в консоли - мы всегда юзаем топовые браузеры!
	 * эта проверка происходит раз в неделю...
	 *
	 * @see https://wp-kama.ru/function/wp_check_browser_version
	 */
	protected function check_browser(): void {

		if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$user_agent = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );

			add_filter( 'pre_site_transient_browser_' . md5( $user_agent ), '__return_empty_array' );
		}
	}
}
