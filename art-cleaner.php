<?php
/**
 * Plugin Name: Art Cleaner
 * Plugin URI: wpruse.ru
 * Text Domain: art-cleaner
 * Domain Path: /languages
 * Description: Cleans WP code from unnecessary garbage and more!
 * Version: 2.0.0
 * Author: Artem Abramovich
 * Author URI: https://wpruse.ru/
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * WC requires at least: 5.2.0
 * WC tested up to: 6.1
 *
 * RequiresWP: 5.5
 * RequiresPHP: 7.4
 *
 * Copyright Artem Abramovich
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const ACL_PLUGIN_DIR   = __DIR__;
const ACL_PLUGIN_AFILE = __FILE__;
const ACL_PLUGIN_VER   = '2.0.0';
const ACL_PLUGIN_NAME  = 'Art Cleaner';
const ACL_PLUGIN_SLUG  = 'art-cleaner';
const ACL_PLUGIN_PREFIX  = 'acl';

define( 'ACL_PLUGIN_URI', untrailingslashit( plugin_dir_url( ACL_PLUGIN_AFILE ) ) );
define( 'ACL_PLUGIN_FILE', plugin_basename( __FILE__ ) );

require ACL_PLUGIN_DIR . '/vendor/autoload.php';

( new Art\Cleaner\Main() )->init();
