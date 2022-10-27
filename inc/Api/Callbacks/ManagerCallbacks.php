<?php
/**
 * @package Acyt-master
 */

declare(strict_types=1);

namespace AcytMaster\Api\Callbacks;

use AcytMaster\Base\BaseClass;

/**
 * Sanitize checkboxes
 */
final class ManagerCallbacks extends BaseClass
{
  // settings callback for sanitize fields
  public function checkboxSanitize( $input ) {
    // return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    $output = array();

    foreach ( $this->managers as $key => $value ) {
      $output[$key] = isset( $input[$key] ) ? true : false;
    }

    return $output;
  }

  // section callback
  public function adminSectionManager() {
    echo 'Manage the Sections and Features of this Plugin by activating the checkboxes from the following list.';
  }

  // field callback
  public function checkboxField( $args ) {
    $name = $args['label_for'];
    $classes = $args['class'];
    $option_name =  $args['option_name'];
    $checkbox = get_option( $option_name ); // this function unsierialize automaticaly
    // when multiple options inside one option , name is option_name[item] -> acyt_plugin[cpt_manager]
    echo '<div class="' . $classes . '"><input type="checkbox" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="1" class="" ' . ( isset($checkbox[$name]) && $checkbox[$name] ? 'checked' : '' ) . '><label for="' . $name . '"><div></div></label></div>';
  }
}