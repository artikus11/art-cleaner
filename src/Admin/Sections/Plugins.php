<?php

namespace Art\Cleaner\Admin\Sections;

use Art\Cleaner\Admin\Settings;

class Plugins extends Settings {

	protected string $section_id = 'plugins';


	/**
	 * @return void
	 */
	public function section(): void {

		$this->wposa->add_section(
			[
				'id'        => $this->section_id,
				'title'     => '',
				'title_nav' => 'Плагины',
			]
		);

		if ( ! empty( $this->is_active_plugins() ) ) {
			$this->fields();
		} else {
			$this->message();
		}
	}


	protected function message() {

		$this->wposa->add_field(
			$this->section_id,
			[
				'id'   => 'plugins_message',
				'type' => 'title',
				'name' => 'Нет активных плагинов',
				'desc' => 'На текущий момент плагин поддержвается плагины: WooCommerce, SEO Rank Math. Настройки появяться автоматически приактивации поддерживаемых плагинов.',
			]
		);
	}


	/**
	 * @return void
	 */
	protected function fields(): void {}
}
