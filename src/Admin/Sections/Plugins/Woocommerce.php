<?php

namespace Art\Cleaner\Admin\Sections\Plugins;

use Art\Cleaner\Admin\Sections\Plugins;

class Woocommerce extends Plugins {

	protected function fields(): void {

		$this->wposa->add_field(
			$this->section_id,
			[
				'id'   => 'woocommerce_heading',
				'type' => 'title',
				'name' => 'WooCommerce',
			]
		);

		$this->wposa->add_field(
			$this->section_id,
			[
				'id'      => 'woocommerce_disable_feature',
				'type'    => 'switch',
				'name'    => 'Отключить WooCommerce Admin',
				'default' => 'off',
				'desc'    => 'Будут отлючены все новые функции и разделы: Маркетинг, Аналитика, Бординг и тд относящиеся к WooCommerce Admin',
			]
		);

		$this->wposa->add_field(
			$this->section_id,
			[
				'id'      => 'woocommerce_disable_feature_analytics',
				'type'    => 'switch',
				'name'    => 'Отключить Аналитику',
				'default' => 'off',
				'desc'    => 'Будет отключен только раздел Аналитики',
			]
		);

		$this->wposa->add_field(
			$this->section_id,
			[
				'id'      => 'woocommerce_disable_admin_menu',
				'type'    => 'switch',
				'name'    => 'Отключить страницы подменю',
				'default' => 'off',
				'desc'    => 'Отключает страницы подменю Расширения, Отчеты и др',
			]
		);
	}
}
