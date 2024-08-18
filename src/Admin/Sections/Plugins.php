<?php

namespace Art\Cleaner\Admin\Sections;

use Art\Cleaner\Admin\Settings;

class Plugins extends Settings {

	protected string $section_id = 'plugins';


	/**
	 * @return void
	 */
	public function section(): void {

		if ( ! empty( $this->is_active_plugins() ) ) {
			$this->wposa->add_section(
				[
					'id'        => $this->section_id,
					'title'     => '',
					'title_nav' => 'Плагины',
				]
			);
		}

		$this->fields();
	}


	/**
	 * @return void
	 */
	protected function fields(): void {}
}
