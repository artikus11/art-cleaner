<?php

namespace Art\Cleaner\Admin;

use Art\Cleaner\Utils;

class Settings {

	protected Options $wposa;


	protected Utils $utils;


	public function __construct( $wposa, $utils ) {

		$this->wposa = $wposa;
		$this->utils = $utils;

		$this->hooks();
	}


	public function hooks() {

		add_action( 'init', [ $this, 'fields' ], 110 );
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
				'type'    => 'select',
				'name'    => 'Отключение агрессивных обновлений',
				'options' => [
					'yes' => 'Да',
					'no'  => 'Нет',
				],
				'default' => 'no',
				'desc'    => 'Проверки новых версий при генерации страницы PHP отправляет HTTP запрос, а точнее 3 запроса: ядро, темы, плагины. Если есть платные плагины, то на каждый плагин обычно еще один свой запрос. При HTTP запросе в PHP генерация страницы зависает пока каждый запрос не получит результат, а на каждый запрос уходит в среднем 0,3 - 1 секунда. Вот и получается, что страница виснет на 2-4 секунды. <a href="https://wp-kama.ru/id_8514/uskoryaem-adminku-wordpress-otklyuchaem-proverki-obnovlenij.html" target="_blank">Попробнее</a>',
			]
		);

		$this->wposa->add_field(
			'general',
			[
				'id'      => 'disable_emoji',
				'type'    => 'select',
				'name'    => 'Отключение Emoji',
				'options' => [
					'yes' => 'Да',
					'no'  => 'Нет',
				],
				'default' => 'no',
				'desc'    => 'C версии 4.2. в wordPress появились эти самые Emoji - смайлики, эмоции. Если не используются на сайте, то можно выключить',
			]
		);

		$this->wposa->add_field(
			'general',
			[
				'id'      => 'disable_feed',
				'type'    => 'select',
				'name'    => 'Отключение RSS Feed',
				'options' => [
					'yes' => 'Да',
					'no'  => 'Нет',
				],
				'default' => 'no',
				'desc'    => 'Отключает все каналы RSS/Atom/RDF на вашем сайте и ставит редирект со всех RSS-лент.',
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
				'type'    => 'select',
				'name'    => 'Отключить виджеты в консоли',
				'options' => [
					'yes' => 'Да',
					'no'  => 'Нет',
				],
				'default' => 'no',
				'desc'    => 'Удаление виджетов на странице Консоли',
			]
		);


		$this->wposa->add_field(
			'admin',
			[
				'id'      => 'cleanup_admin_bar',
				'type'    => 'select',
				'name'    => 'Отключить элементы админбара',
				'options' => [
					'yes' => 'Да',
					'no'  => 'Нет',
				],
				'default' => 'no',
				'desc'    => 'Отключает элементы админбара вверху админки (слева-направо в админке: лого, индикатор обновлений, индикатор комментариев, добавление контента).',
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
				'type'    => 'select',
				'name'    => 'Отключение версии ядра',
				'options' => [
					'yes' => 'Да',
					'no'  => 'Нет',
				],
				'default' => 'no',
				'desc'    => 'Удаляет метатег вида <code>&lt;meta name="generator" content="WordPress 6.4.3" /&gt;</code> из секции <code>&lt;head&gt;</code>',
			]
		);

		$this->wposa->add_field(
			'head',
			[
				'id'      => 'cleanup_head_shortlink',
				'type'    => 'select',
				'name'    => 'Отключение короткой ссылки',
				'options' => [
					'yes' => 'Да',
					'no'  => 'Нет',
				],
				'default' => 'no',
				'desc'    => 'Удаляет короткую ссылку вида <code>&lt;link rel="shortlink" href="https://site.loc/?p=51159" /&gt;</code> из секции <code>&lt;head&gt;</code>',
			]
		);

		$this->wposa->add_field(
			'head',
			[
				'id'      => 'cleanup_head_wp_json',
				'type'    => 'select',
				'name'    => 'Отключение ссылок на REST API',
				'options' => [
					'yes' => 'Да',
					'no'  => 'Нет',
				],
				'default' => 'no',
				'desc'    => 'Удаляет ссылки вида <code>&lt;link rel="alternate" type="text/xml+oembed" href="https://site.loc/wp-json/oembed/..." &gt;</code> из секции <code>&lt;head&gt;</code>',
			]
		);

		$this->wposa->add_field(
			'head',
			[
				'id'      => 'cleanup_head_rsd_link',
				'type'    => 'select',
				'name'    => 'Отключение RSD ссылки',
				'options' => [
					'yes' => 'Да',
					'no'  => 'Нет',
				],
				'default' => 'no',
				'desc'    => 'Удаляет ссылки вида <code>&lt;link rel="EditURI" type="application/rsd+xml" title="RSD" href="https://site.loc/xmlrpc.php?rsd" /&gt;</code> из секции <code>&lt;head&gt;</code>',
			]
		);
	}
}