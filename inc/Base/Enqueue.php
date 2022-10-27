<?php
/**
 * @package Acyt-master
 */

declare(strict_types=1);

namespace AcytMaster\Base;

/**
 * Enqueuing scripts
 */
final class Enqueue extends BaseClass
{
  public function register() {
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );
    // add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend' ) );
  }
  
  public function enqueue_admin() {
    // media/ widget
    if( $this->activated('media_widget') ) {
      wp_enqueue_script('media-upload');
      wp_enqueue_media();
    }

    // enqueue all our scripts
    wp_enqueue_style( 'acyt_admin_style', ACYT_PLUGIN_URL . 'assets/css/admin.min.css', array(), ACYT_VERSION );
    wp_enqueue_script( 'acyt_admin_script', ACYT_PLUGIN_URL . 'assets/js/admin.min.js', array(), ACYT_VERSION );
    wp_localize_script( 'acyt_admin_script', 'acyt_admin_obj', array( // localize ajax / home url, acyt_obj.ajax_url, acyt_obj.home_url
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'home_url' => home_url( '/' ),
      // 'translations' => $translations_admin
    ));
  }

  public function enqueue_frontend() {
    wp_enqueue_style( 'acyt_front_style', ACYT_PLUGIN_URL . 'assets/css/front.min.css', array(), ACYT_VERSION );
    wp_enqueue_script( 'acyt_front_script', ACYT_PLUGIN_URL . 'assets/js/front.min.js', array(), ACYT_VERSION );
    wp_localize_script( 'acyt_admin_script', 'acyt_admin_obj', array( 
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'home_url' => home_url( '/' ),
      // 'translations' => $translations_front
    ));
  }
}