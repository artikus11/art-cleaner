<?php

namespace Art\Cleaner;

use Automattic\WooCommerce\Internal\Utilities\Users;

class Woocommerce_Cleanup {

	public function init_hooks(): void {

		if ( ! Users::is_site_administrator() ) {
			return;
		}

		add_filter( 'woocommerce_debug_tools', [ $this, 'add_tools' ] );
	}


	public function add_tools( array $tools ): array {

		$tools['clear_scheduler_actions'] = [
			'name'     => 'Очистить все выполненые крон задачи (статусы: canceled, complete, failed)',
			'desc'     => 'Удаляет все выполненные, неудавшиеся и отменныне крон задачи',
			'button'   => 'Очистить',
			'callback' => [ $this, 'clear_scheduler_actions' ],
		];

		$tools['clear_scheduler_actions_logs'] = [
			'name'     => 'Очистить все логи крон задач',
			'desc'     => 'Очищает таблицу журнала запланированных задач',
			'button'   => 'Очистить',
			'callback' => [ $this, 'clear_scheduler_actions_logs' ],
		];

		return $tools;
	}


	public function clear_scheduler_actions() {

		global $wpdb;
		$wpdb->query(
			"
			DELETE 
			FROM {$wpdb->prefix}actionscheduler_actions
			 WHERE `status` 
			 IN ( 'canceled', 'failed', 'complete' )
		 "
		);
	}


	public function clear_scheduler_actions_logs() {

		global $wpdb;
		$wpdb->query( "TRUNCATE `{$wpdb->prefix}actionscheduler_logs`" );
	}

}