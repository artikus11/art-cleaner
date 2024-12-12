<?php

namespace Art\Cleaner\Cleanup_Core;

use Art\Cleaner\Admin\Options;

class Cleanup_Common {

	public function init_hooks(): void {

		if ( 'on' === Options::get( 'cleanup_count_comments', 'admin' ) ) {
			$this->disable_count_comments();
		}
	}


	public function disable_count_comments(): void {

		// Отменим запрос по подсчету комментариев
		add_filter( 'wp_count_comments', [ $this, 'count_comments_empty' ] );
	}


	public function count_comments_empty(): object {

		return (object) [
			'approved'            => 0,
			'awaiting_moderation' => 0,
			'moderated'           => 0,
			'spam'                => 0,
			'trash'               => 0,
			'post-trashed'        => 0,
			'total_comments'      => 0,
			'all'                 => 0,
		];
	}
}
