<?php
/**
 * @package Acyt-master
 */

declare(strict_types=1);

namespace AcytMaster\Base;
/**
 * Class with activate callback
 */
final class Activate
{
  public static function activate() {
    flush_rewrite_rules();

    $default = array(); // default data

    if ( empty( get_option( 'acyt_plugin', array() ) ) ) {
      update_option( 'acyt_plugin', $default );
    }

    if ( empty( get_option( 'acyt_plugin_cpt', array() ) ) ) {
      update_option( 'acyt_plugin_cpt', $default );
    }

    if ( ! get_option( 'acyt_plugin_tax' ) ) {
      update_option( 'acyt_plugin_tax', $default );
    }

    do_action('acyt_master_activated');
  }
}