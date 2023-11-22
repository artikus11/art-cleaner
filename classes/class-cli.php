<?php

namespace Art\Cleaner;

use WP_CLI;
use function WP_CLI\Utils\make_progress_bar;

class CLI {

	protected Woocommerce_Cleanup $cleanup;


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
	 *
	 * @subcommand   flush-scheduled-actions
	 *
	 * @param  array $args       Arguments.
	 * @param  array $assoc_args Arguments in associative array.
	 *
	 * @noinspection PhpUnusedParameterInspection
	 */
	public function flush_scheduled_actions( array $args = [], array $assoc_args = [] ) {

		/*$assoc_args = wp_parse_args( $assoc_args, [
			'scheduled-actions' => false,
			'scheduled-logs'    => false,
			'order-notes'       => false,
		] );*/

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
		/*$notify = $this->make_progress_bar();

		$notify->tick();
		$notify->finish();*/

		//WP_CLI::success( sprintf( 'Successfully selected %d tasks.', $result ) );

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
	 *     $ wp acl flush-scheduled-logs
	 *     Success: Flush Completed.
	 *
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

	public function prune_revisions( $args, $assoc_args ) {
		global $wpdb;

		$live    = (bool) $assoc_args[ 'live' ];
		$verbose = (bool) $assoc_args[ 'verbose' ];
		$offset  = 0;
		$limit   = 500;
		$count   = 0;
		$deletes = 0;


		$count_sql      = 'SELECT COUNT(ID) FROM ' . $wpdb->posts . ' WHERE post_type = "revision"';
		$revision_count = (int) $wpdb->get_row( $count_sql, ARRAY_N )[0];
		$progress       = \WP_CLI\Utils\make_progress_bar( sprintf( 'Checking %s revisions', number_format( $revision_count ) ), $revision_count );

		do {
			$sql       = $wpdb->prepare( 'SELECT ID FROM ' . $wpdb->posts . ' WHERE post_type = "revision" LIMIT %d,%d', $offset, $limit );
			$revisions = $wpdb->get_results( $sql );

			foreach ( $revisions as $revision ) {
				$count++;
				$post_parent_id = wp_get_post_parent_id( $revision->ID );

				// Fail on either 0 or false.
				if ( false == $post_parent_id ) {
					WP_CLI::warning( sprintf( 'Revision %d does not have a parent!  Skipping!', $revision->ID ) );
					continue;
				}

				$revision_modified   = get_post_modified_time( 'U', false, $revision->ID );
				$parent_publish_time = get_post_time( 'U', false, $post_parent_id );

				if ( $parent_publish_time < current_time( 'timestamp') - ( MONTH_IN_SECONDS * 12 ) ) {
					// Post is older than 12 months, safe to delete pre-publish revisions.
					if ( $revision_modified < $parent_publish_time ) {
						if ( $live ) {
							// We're doing it live!
							WP_CLI::log( sprintf( 'Deleting revision %d for post %d. (%d%% done)', $revision->ID, $post_parent_id, ( $count / $revision_count ) * 100 ) );

							// Backup data!
							$output = [];
							$data   = get_post( $revision->ID );

							// Validate the field is set, just in case.  IDK how it couldn't be.
							foreach ( $csv_headers as $field ) {
								$output = isset( $data->$field ) ? $data->$field : '';
							}

							fputcsv( $handle, $output );

							$did_delete = wp_delete_post_revision( $revision->ID );

							// Something went wrong while deleting the revision?
							if ( false === $did_delete || is_wp_error( $did_delete ) ) {
								WP_CLI::warning( sprintf( 'Revision %d for post %d DID NOT DELETE! wp_delete_post_revision returned:', $revision->ID, $post_parent_id ) );
							}
							$deletes++;

							// Pause after lots of db modifications.
							if ( 0 === $deletes % 50 ) {
								if ( $verbose ) {
									WP_CLI::log( sprintf( 'Current Deletes: %d', $deletes ) );
								}
								sleep( 1 );
							}
						} else {
							// Not live, just output info.
							WP_CLI::log( sprintf( 'Will delete revision %d for post %d.', $revision->ID, $post_parent_id ) );
						}
					} else {
						// Revision is after the post has been published.
						if ( $verbose ) {
							WP_CLI::log( sprintf( 'Post-Publish: Will NOT delete Revision %d for post %d.', $revision->ID, $post_parent_id ) );
						}
					}
				} else {
					// Post is too new to prune.
					if ( $verbose ) {
						WP_CLI::log( sprintf( 'Too-New: Will NOT delete Revision %d for post %d.', $revision->ID, $post_parent_id ) );
					}
				}
			}

			// Pause after lots of db reads.
			if ( 0 === $count % 5000 ) {
				if ( $verbose ) {
					WP_CLI::log( sprintf( 'Current Count: %d', $count ) );
				}
				sleep( 1 );
			}

			// Free up memory.
			$this->stop_the_insanity();

			// Paginate.
			if ( count( $revisions ) ) {
				$offset += $limit;
				$progress->tick( $limit );
			} else {
				WP_CLI::warning( 'Possible MySQL Error, retrying in 10 seconds!' );
				sleep( 10 );
			}

		} while ( $count < $revision_count );

		$progress->finish();

		if ( $live ) {
			fclose( $handle );
			WP_CLI::success( sprintf( 'Deleted %d revisions', $deleted ) );
		} else {
			WP_CLI::success( sprintf( 'Processed %d revisions', $revision_count ) );
		}
	}

}


