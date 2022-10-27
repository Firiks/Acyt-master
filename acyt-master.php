<?php

declare(strict_types=1);

/**
 * Acyt-master
 *
 * @package           Acyt-master
 * @author            Miki
 * @copyright         2022 Miki
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Acyt-master
 * Plugin URI:        https://github.com/Firiks?tab=repositories
 * Tags:              comments, spam
 * Donate link:       http://example.com/
 * Version:           1.0.0
 * Tested up to:      6.0
 * Requires PHP:      7.4
 * Author:            Miki
 * Author URI:        https://example.com
 * Text Domain:       acyt
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

// Check if wp is loaded
if ( !defined( 'ABSPATH' ) || !function_exists( 'add_action' ) ) {
  die('No direct access allowed !');
}

define( 'ACYT_VERSION', '1.0.0' );

define( 'ACYT_PLUGIN_FILE', __FILE__ );

define( 'ACYT_PLUGIN_BASENAME', plugin_basename( ACYT_PLUGIN_FILE ) ); // acyt-master/acyt-master.php

define( 'ACYT_PLUGIN_NAME', trim( dirname( ACYT_PLUGIN_BASENAME ), '/' ) );

define( 'ACYT_PLUGIN_URL', plugin_dir_url( ACYT_PLUGIN_FILE ) );

define( 'ACYT_PLUGIN_DIR', plugin_dir_path( ACYT_PLUGIN_FILE ) );

add_action( 'plugins_loaded', 'acyt_load_plugin_textdomain' );

/**
 * Psr-4 autoloading
 */
if ( file_exists( ACYT_PLUGIN_DIR . '/vendor/autoload.php' ) ) {
  require_once ACYT_PLUGIN_DIR . '/vendor/autoload.php';
}

/**
 * The code that runs during plugin activation
 */
function activate_acyt_plugin() {
  AcytMaster\Base\Activate::activate();
}

/**
 * The code that runs during plugin deactivation
 */
function deactivate_acyt_plugin() {
  AcytMaster\Base\Deactivate::deactivate();
}

/**
 * Load text domain
 *
 */
function acyt_load_plugin_textdomain() {
  load_plugin_textdomain( 'acyt-master', false, ACYT_PLUGIN_NAME . 'lang/' );
}

// for activation/deactivation must trigger by procedurall function outside class, otherwise they wont trigger properly
register_activation_hook( ACYT_PLUGIN_FILE, 'activate_acyt_plugin' );
register_deactivation_hook( ACYT_PLUGIN_FILE, 'deactivate_acyt_plugin' );

/**
 * Initialize all the core classes of the plugin
 */
if ( class_exists('AcytMaster\\Init') ) {
  // AcytMaster\Init::class // call constructor
  AcytMaster\Init::register_services(); // or method
}
