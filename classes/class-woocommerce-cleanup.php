<?php

namespace Art\Cleaner;

use Automattic\WooCommerce\Internal\Utilities\Users;

class Woocommerce_Cleanup {

	public function init_hooks(): void {

		if ( ! Users::is_site_administrator() ) {
			return;
		}

		add_filter( 'woocommerce_debug_tools', [ $this, 'add_tools' ] );

		add_filter( 'action_scheduler_retention_period', [ $this, 'retention_period' ], 100 );
		add_filter( 'action_scheduler_default_cleaner_statuses', [ $this, 'default_cleaner_statuses' ], 100 );
		add_filter( 'action_scheduler_cleanup_batch_size', [ $this, 'cleanup_batch_size' ], 100 );
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


	public function clear_scheduler_actions(): string {

		global $wpdb;
		$result = absint(
			$wpdb->query(
				"
			DELETE 
			FROM {$wpdb->prefix}actionscheduler_actions
			 WHERE `status` 
			 IN ( 'canceled', 'failed', 'complete' )
		 "
			)
		);

		wp_cache_flush();

		return sprintf( 'Успешно удалено %d задач.', absint( $result ) );
	}


	public function clear_scheduler_actions(): string {

		global $wpdb;

		$result = absint(
			$wpdb->query(
				"DELETE 
							FROM {$wpdb->prefix}actionscheduler_actions
							WHERE `status` 
						        IN ( 'canceled', 'failed', 'complete' )"
			)
		);

		wp_cache_flush();

		return sprintf( 'Успешно удалено %d задач.', absint( $result ) );
	}


	public function clear_scheduler_actions_logs(): string {

		global $wpdb;

		$wpdb->query( "TRUNCATE `{$wpdb->prefix}actionscheduler_logs`" );

		return 'Журнал задач успешно очищен.';
	}


	public function retention_period( $period ) {

		return 7 * DAY_IN_SECONDS;
	}


	public function default_cleaner_statuses( $statuses ) {

		$statuses[] = 'failed';

		return $statuses;
	}


	public function cleanup_batch_size( $batch_size ): int {

		return 100;
	}

}