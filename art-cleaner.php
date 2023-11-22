<?php
/**
 * Plugin Name: Art Cleaner
 * Plugin URI: wpruse.ru
 * Text Domain: art-cleaner
 * Domain Path: /languages
 * Description: Plugin for WooCommerce. Quick order of products which are currently in the cart
 * Version: 1.2.2
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

use Art\Cleaner\Main;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plugin_data = get_file_data(
	__FILE__,
	[
		'ver'  => 'Version',
		'name' => 'Plugin Name',
	]
);

const ACL_PLUGIN_DIR   = __DIR__;
const ACL_PLUGIN_AFILE = __FILE__;
define( 'ACL_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
define( 'ACL_PLUGIN_FILE', plugin_basename( __FILE__ ) );

define( 'ACL_PLUGIN_VER', $plugin_data['ver'] );
define( 'ACL_PLUGIN_NAME', $plugin_data['name'] );

require ACL_PLUGIN_DIR . '/vendor/autoload.php';

new Main();
