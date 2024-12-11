<?php

namespace Art\Cleaner\Admin\Sections\Plugins;

use Art\Cleaner\Admin\Sections\Plugins;

class RankMath extends Plugins {

	public function __construct( $wposa, $utils ) { parent::__construct( $wposa, $utils ); }


	protected function fields(): void {

		if ( ! is_plugin_active( 'seo-by-rank-math/rank-math.php' ) ) {
			return;
		}

		$this->wposa->add_field(
			$this->section_id,
			[
				'id'   => 'rank_math_heading',
				'type' => 'title',
				'name' => 'RankMath',
			]
		);

	/*	$this->wposa->add_field(
			$this->section_id,
			[
				'id'      => 'rank_math_disable_feature',
				'type'    => 'switch',
				'name'    => 'Отключить WooCommerce Admin',
				'default' => 'off',
				'desc'    => 'Будут отлючены все новые функции и разделы: Маркетинг, Аналитика, Бординг и тд относящиеся к WooCommerce Admin',
			]
		);*/
	}
}