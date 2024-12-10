<?php

namespace Art\Cleaner\Admin\Sections\Plugins;

use Art\Cleaner\Admin\Sections\Plugins;

class Woocommerce extends Plugins {

	protected function fields(): void {

		if ( empty( $this->is_active_plugins() ) ) {
			return;
		}

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
				'id'      => 'woocommerce_disable_admin_menu',
				'type'    => 'switch',
				'name'    => 'Отключить страницы подменю',
				'default' => 'off',
				'desc'    => 'Отключает страницы подменю Расширения, Отчеты и др',
			]
		);
	}
}
