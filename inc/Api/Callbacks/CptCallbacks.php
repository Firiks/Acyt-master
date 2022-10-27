<?php
/**
 * @package Acyt-master
 */

declare(strict_types=1);

namespace AcytMaster\Api\Callbacks;

/**
 * Settings api callbacks for Cpt
 */
final class CptCallbacks
{
  public function cptSectionManager() {
    echo 'Create as many Custom Post Types as you want.';
  }

  public function cptSanitize( array $input ) {
    $output = get_option('acyt_plugin_cpt', array());

    // remove cpt
    if ( isset($_POST["remove"]) ) {
      // application/x-www-form-urlencoded or multipart/form-data as the HTTP Content-Type in the request - then its possible to use $_POST
      unset($output[ $_POST["remove"] ]);
    } else { // add/update cpt
      $output[ $input['post_type'] ] = $input;
    }

    return $output;
  }

  public function textField( $args ) {
    $name = $args['label_for'];
    $option_name = $args['option_name'];
    $value = ''; // default empty string

    $disabled = false;

    // We are editing cpt - show values
    if ( isset($_POST["edit_post"]) ) {
      $input = get_option( $option_name );
      $value = $input[$_POST["edit_post"]][$name];

      if( strcmp( $name, 'post_type' ) == 0 ) {
        $disabled = true;
      }

    }

    echo '<input type="text" class="regular-text" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="' . $value . '" placeholder="' . $args['placeholder'] . '" required '. ( $disabled ? 'disabled' : '') .' >';
  }

  public function checkboxField( $args ) {
    $name = $args['label_for'];
    $classes = $args['class'];
    $option_name = $args['option_name'];
    $checked = false;

    if ( isset($_POST["edit_post"]) ) {
      $checkbox = get_option( $option_name );
      $checked = isset($checkbox[$_POST["edit_post"]][$name]) ?: false; // use value or false
    }

    echo '<div class="' . $classes . '"><input type="checkbox" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="1" class="" ' . ( $checked ? 'checked' : '') . '><label for="' . $name . '"><div></div></label></div>';
  }
}