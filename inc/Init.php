<?php
/**
 * @package Acyt-master
 */

declare(strict_types=1);

namespace AcytMaster;

/**
 * Custom loader for classes
 */
final class Init
{

  /**
   * Cloning is forbidden.
   * @access public
   * @since 1.0.0
   */
  public function __clone() {
    _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
  }

  /**
   * Unserializing instances of this class is forbidden.
   * @access public
   * @since 1.0.0
   */
  public function __wakeup() {
    _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
  }

  /**
   * Store all the classes inside an array
   * @return array Full list of classes
   */
  public static function get_services() {
    return array(
      Pages\Dashboard::class, // must be first - generation of setting menu
      Base\Enqueue::class,
      Base\SettingsLinks::class,
      Controllers\CustomPostTypeController::class,
      Controllers\CustomTaxonomyController::class,
      Controllers\WidgetController::class,
      // Controllers\GalleryController::class,
      Controllers\TestimonialController::class,
      // Controllers\TemplateController::class,
      // Controllers\AuthController::class,
      // Controllers\MembershipController::class,
      // Controllers\ChatController::class,
    );
  }

  /**
   * Initialize the class
   * @param  class $class    class from the services array
   * @return class instance  new instance of the class
   */
  private static function instantiate( $class ) {
    $service = new $class();

    return $service;
  }

  /**
   * Loop through the classes, initialize them, 
   * and call the register() method if it exists
   * @return
   */
  public static function register_services() {
    foreach ( self::get_services() as $class ) {
      $service = self::instantiate( $class );
      if ( method_exists( $service, 'register' ) ) {
        $service->register();
      }
    }
  }

}