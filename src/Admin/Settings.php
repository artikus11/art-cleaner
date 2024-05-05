<?php

namespace Art\Cleaner\Admin;

use Art\Cleaner\Utils;

class Settings {

	protected Options $wposa;


	protected Utils $utils;


	protected array $widgets;


	public function __construct( $wposa, $utils ) {

		$this->wposa = $wposa;
		$this->utils = $utils;

		$this->hooks();
	}


	public function hooks() {

		add_action( 'init', [ $this, 'fields' ], 110 );
		add_action( 'widgets_init', [ $this, 'set_widgets' ], 99 );
	}


	public function fields() {

		$this->section_general();
		$this->section_head();
		$this->section_admin();

		$this->wposa->add_section(
			[
				'id'        => 'tools',
				'title'     => '',
				'title_nav' => 'Инструменты',
			]
		);
	}


	/**
	 * @return void
	 */
	protected function section_general(): void {

		$this->wposa->add_section(
			[
				'id'        => 'general',
				'title'     => '',
				'title_nav' => 'Основные',
			]
		);

		$this->wposa->add_field(
			'general',
			[
				'id'      => 'disable_aggressive_updates',
				'type'    => 'switch',
				'name'    => 'Отключение агрессивных обновлений',
				'default' => 'off',
				'desc'    => 'Проверки новых версий при генерации страницы PHP отправляет HTTP запрос, а точнее 3 запроса: ядро, темы, плагины. Если есть платные плагины, то на каждый плагин обычно еще один свой запрос. При HTTP запросе в PHP генерация страницы зависает пока каждый запрос не получит результат, а на каждый запрос уходит в среднем 0,3 - 1 секунда. Вот и получается, что страница виснет на 2-4 секунды. <a href="https://wp-kama.ru/id_8514/uskoryaem-adminku-wordpress-otklyuchaem-proverki-obnovlenij.html" target="_blank">Подробнее</a>',
			]
		);

		$this->wposa->add_field(
			'general',
			[
				'id'      => 'disable_emoji',
				'type'    => 'switch',
				'name'    => 'Отключение Emoji',
				'default' => 'off',
				'desc'    => 'C версии 4.2. в wordPress появились эти самые Emoji - смайлики, эмоции. Если не используются на сайте, то можно выключить',
			]
		);

		$this->wposa->add_field(
			'general',
			[
				'id'      => 'disable_feed',
				'type'    => 'switch',
				'name'    => 'Отключение RSS Feed',
				'default' => 'off',
				'desc'    => 'Отключает все каналы RSS/Atom/RDF на вашем сайте и ставит редирект со всех RSS-лент.',
			]
		);

		$this->wposa->add_field(
			'general',
			[
				'id'      => 'disable_embeds',
				'type'    => 'switch',
				'name'    => 'Отключение Embeds',
				'default' => 'off',
				'desc'    => 'Отключает возможность вставки ссылок с контенте с других сайтов',
			]
		);

		$this->wposa->add_field(
			'general',
			[
				'id'      => 'disable_xml',
				'type'    => 'switch',
				'name'    => 'Отключение XML RPC',
				'default' => 'off',
				'desc'    => 'Отключает XML RPC',
			]
		);

		$this->wposa->add_field(
			'general',
			[
				'id'      => 'autoremove_attachments',
				'type'    => 'switch',
				'name'    => 'Включить автоматическое удаление вложений',
				'default' => 'off',
				'desc'    => 'Включает автоматическое удаление прикрепленных вложений при удалении записи',
			]
		);
	}


	/**
	 * @return void
	 */
	protected function section_admin(): void {

		$this->wposa->add_section(
			[
				'id'        => 'admin',
				'title'     => '',
				'title_nav' => 'Очистка админки',
			]
		);

		$this->wposa->add_field(
			'admin',
			[
				'id'      => 'cleanup_dashboard',
				'type'    => 'switch',
				'name'    => 'Отключить виджеты в консоли',
				'default' => 'off',
				'desc'    => 'Удаление виджетов на странице Консоли. Совсем всех, независимо от установленных плагинов.',
			]
		);

		$this->wposa->add_field(
			'admin',
			[
				'id'      => 'cleanup_admin_bar',
				'type'    => 'switch',
				'name'    => 'Отключить элементы админбара',
				'default' => 'off',
				'desc'    => 'Отключает элементы админбара вверху админки. Внимание! Отключаются все элементы, независимо от установленных плагинов, кроме разрешенных.',
			]
		);

		$this->wposa->add_field(
			'admin',
			[
				'id'      => 'cleanup_widgets',
				'type'    => 'multicheck',
				'name'    => 'Отключить виджеты',
				'class'   => 'multicheck-inputs',
				'options' => $this->get_option_widgets(),
				'desc'    => 'Удаление всех виджетов, кроме не выбранных. Выберите виджет который требуется отключить',
			]
		);
	}


	/**
	 * @return void
	 */
	protected function section_head(): void {

		$this->wposa->add_section(
			[
				'id'        => 'head',
				'title'     => 'Очистка',
				'title_nav' => 'Очистка &lt;head&gt;',
			]
		);

		$this->wposa->add_field(
			'head',
			[
				'id'      => 'cleanup_head_generator',
				'type'    => 'switch',
				'name'    => 'Отключение версии ядра',
				'default' => 'off',
				'desc'    => 'Удаляет метатег вида <code>&lt;meta name="generator" content="WordPress 6.4.3" /&gt;</code> из секции <code>&lt;head&gt;</code>',
			]
		);

		$this->wposa->add_field(
			'head',
			[
				'id'      => 'cleanup_head_shortlink',
				'type'    => 'switch',
				'name'    => 'Отключение короткой ссылки',
				'default' => 'off',
				'desc'    => 'Удаляет короткую ссылку вида <code>&lt;link rel="shortlink" href="https://site.loc/?p=51159" /&gt;</code> из секции <code>&lt;head&gt;</code>',
			]
		);

		$this->wposa->add_field(
			'head',
			[
				'id'      => 'cleanup_head_wp_json',
				'type'    => 'switch',
				'name'    => 'Отключение ссылок на REST API',
				'default' => 'off',
				'desc'    => 'Удаляет ссылки вида <code>&lt;link rel="alternate" type="text/xml+oembed" href="https://site.loc/wp-json/oembed/..." &gt;</code> из секции <code>&lt;head&gt;</code>',
			]
		);

		$this->wposa->add_field(
			'head',
			[
				'id'      => 'cleanup_head_rsd_link',
				'type'    => 'switch',
				'name'    => 'Отключение RSD ссылки',
				'default' => 'off',
				'desc'    => 'Удаляет ссылки вида <code>&lt;link rel="EditURI" type="application/rsd+xml" title="RSD" href="https://site.loc/xmlrpc.php?rsd" /&gt;</code> из секции <code>&lt;head&gt;</code>',
			]
		);
	}


	/**
	 * Получение активных виджетов перед сохранением
	 *
	 * @return void
	 */
	public function set_widgets() {

		global $wp_widget_factory;

		$this->widgets = $wp_widget_factory->widgets;
	}


	/**
	 * @return array[]
	 */
	protected function get_option_widgets(): array {

		$widgets_check = [
			'select_all' => [
				'label'      => 'Выбрать все',
				'class'      => '',
				'attributes' => [
					'data-select-all' => 'remove',
				],
			],
		];

		foreach ( $this->widgets as $key => $widget ) {

			$is_active_widget = is_active_widget( false, false, $widget->id_base, true ) ? ' (активен)' : '';

			$widgets_check[ $key ] = [
				'label'      => $widget->name . $is_active_widget,
				'class'      => 'checkbox-' . $widget->option_name,
				'attributes' => [
					'data-selectable' => 'remove',
				],
			];
		}

		return $widgets_check;
	}


	protected function default_widgets(): array {

		$allowed_widgets = [
			'WP_Widget_Text',
			'WP_Widget_Custom_HTML',
			'WP_Widget_Block',
		];

		$default_widgets = [];

		foreach ( $this->get_option_widgets() as $key => $widget ) {
			if ( ! in_array( $key, $allowed_widgets, true ) ) {
				$default_widgets[ $key ] = $key;
			}
		}

		return $default_widgets;
	}
}