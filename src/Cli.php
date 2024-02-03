<?php

namespace Art\Cleaner;

use Art\Cleaner\Woocommerce\Tools;
use cli\progress\Bar;
use WP_CLI;
use WP_CLI\NoOp;
use WP_CLI_Command;
use function WP_CLI\Utils\make_progress_bar;

class CLI {

	protected Tools $cleanup;


	public function __construct( $cleanup ) {

		$this->cleanup = $cleanup;
	}


	/**
	 * Flushing completed cron tasks
	 *
	 * ## OPTIONS
	 *
	 * [--force]
	 * : By default flushing all.
	 * ---
	 * default: true
	 *
	 * [--select]
	 * : By default select all tasks.
	 * ---
	 * default: false
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp acl flush_scheduled_actions
	 *     Success: Flush Completed.
	 *
	 * @subcommand   flush-scheduled-actions
	 *
	 * @param  array $args       Arguments.
	 * @param  array $assoc_args Arguments in associative array.
	 *
	 * @noinspection PhpUnusedParameterInspection
	 */
	public function flush_scheduled_actions( array $args = [], array $assoc_args = [] ) {

		WP_CLI::line( 'Starting...' );

		$assoc_args = wp_parse_args( $assoc_args, [
			'force'  => true,
			'select' => false,
		] );

		if ( $assoc_args['force'] && $assoc_args['select'] ) {
			WP_CLI::success( $this->cleanup->select_scheduler_actions() );

			return;
		}

		if ( $assoc_args['force'] && ! $assoc_args['select'] ) {
			WP_CLI::success( $this->cleanup->clear_scheduler_actions() );

			return;
		}
	}


	/**
	 * Flushing completed cron tasks
	 *
	 * ## OPTIONS
	 *
	 *
	 * [--select]
	 * : By default select all tasks.
	 * ---
	 * default: false
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp acl flush-scheduled-logs
	 *     Success: Flush Completed.
	 *
	 * @subcommand   flush-scheduled-logs
	 *
	 * @param  array $args       Arguments.
	 * @param  array $assoc_args Arguments in associative array.
	 *
	 * @noinspection PhpUnusedParameterInspection
	 */
	public function flush_scheduled_logs( array $args = [], array $assoc_args = [] ) {

		$assoc_args = wp_parse_args( $assoc_args, [
			'select' => false,
		] );

		WP_CLI::line( 'Starting...' );

		if ( $assoc_args['select'] ) {

			WP_CLI::success( $this->cleanup->select_scheduler_actions_logs() );

			return;
		}

		WP_CLI::success( $this->cleanup->clear_scheduler_actions_logs() );
	}


	/**
	 * Flushing completed cron tasks
	 *
	 * ## OPTIONS
	 *
	 *
	 * [--select]
	 * : By default select all tasks.
	 * ---
	 * default: false
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp acl flush-order-notes
	 *     Success: Flush Completed.
	 *
	 * @subcommand   flush-order-notes
	 *
	 * @param  array $args       Arguments.
	 * @param  array $assoc_args Arguments in associative array.
	 *
	 * @noinspection PhpUnusedParameterInspection
	 */
	public function flush_order_notes( array $args = [], array $assoc_args = [] ) {

		$assoc_args = wp_parse_args( $assoc_args, [
			'select' => false,
		] );

		WP_CLI::line( 'Starting...' );

		if ( $assoc_args['select'] ) {

			WP_CLI::success( $this->cleanup->select_order_notes() );

			return;
		}

		WP_CLI::success( $this->cleanup->clear_order_notes() );
	}


	protected function make_progress_bar() {

		return make_progress_bar( 'Regenerate existing slugs', 1 );
	}
}
