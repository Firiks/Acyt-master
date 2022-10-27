<?php
/**
 * @package Acyt-master
 */

declare(strict_types=1);

namespace AcytMaster\Base;

/**
 * Show setting link on plugins page
 */
final class SettingsLinks
{
  public function register() {
    add_filter( 'plugin_action_links_' . ACYT_PLUGIN_BASENAME, array( $this, 'plugins_link' ) ); // action link on plugins page
  }
  
  // add custom settings link on plugins page
  function plugins_link( $links ) {
    $settings_link = '<a href="options-general.php?page=acyt_plugin">Settings</a>';
    array_push( $links, $settings_link );
    return $links;
  }
}