<?php
/**
 * @package Acyt-master
 */

declare(strict_types=1);

namespace AcytMaster\Api\Callbacks;

final class TaxonomyCallbacks
{
  public function taxSectionManager() {
    echo 'Create as many Custom Taxonomies as you want.';
  }

  public function taxSanitize( $input ) {
    $output = get_option('acyt_plugin_tax', array());

    // remove cpt
    if ( isset($_POST["remove"]) ) {
      // application/x-www-form-urlencoded or multipart/form-data as the HTTP Content-Type in the request - then its possible to use $_POST
      unset($output[ $_POST["remove"] ]);
    } else { // add/update taxonomy
      $output[ $input['taxonomy'] ] = $input;
    }

    return $output;
  }

  public function textField( $args ) {
    $name = $args['label_for'];
    $option_name = $args['option_name'];
    $value = '';

    if ( isset($_POST["edit_taxonomy"]) ) {
      $input = get_option( $option_name );
      $value = $input[$_POST["edit_taxonomy"]][$name];
    }

    echo '<input type="text" class="regular-text" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="' . $value . '" placeholder="' . $args['placeholder'] . '" required>';
  }

  public function checkboxField( $args ) {
    $name = $args['label_for'];
    $classes = $args['class'];
    $option_name = $args['option_name'];
    $checked = false;

    if ( isset($_POST["edit_taxonomy"]) ) {
      $checkbox = get_option( $option_name );
      $checked = isset($checkbox[$_POST["edit_taxonomy"]][$name]) ?: false;
    }

    echo '<div class="' . $classes . '"><input type="checkbox" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="1" class="" ' . ( $checked ? 'checked' : '') . '><label for="' . $name . '"><div></div></label></div>';
  }

  public function checkboxPostTypesField( $args ) {
    $output = '';
    $name = $args['label_for'];
    $classes = $args['class'];
    $option_name = $args['option_name'];
    $checked = false;

    if ( isset($_POST["edit_taxonomy"]) ) {
      $checkbox = get_option( $option_name );
    }

    // get all post types in array
    $post_types = get_post_types( array( 'show_ui' => true ) ); // return post types that user can access from wp_admin

    foreach ($post_types as $post) {

      // check if taxonomy is associated with post type
      if ( isset($_POST["edit_taxonomy"]) ) {
        $checked = isset($checkbox[$_POST["edit_taxonomy"]][$name][$post]) ?: false;
      }

      $output .= '<div class="' . $classes . ' mb-10"><input type="checkbox" id="' . $post . '" name="' . $option_name . '[' . $name . '][' . $post . ']" value="1" class="" ' . ( $checked ? 'checked' : '') . '><label for="' . $post . '"><div></div></label> <strong>' . $post . '</strong></div>';
    }

    // echo '<div class="' . $classes . '"><input type="checkbox" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="1" class="" ' . ( $checked ? 'checked' : '') . '><label for="' . $name . '"><div></div></label></div>';

    echo $output;
  }
}