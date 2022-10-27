<?php
/**
 * @package Acyt-master
 */

declare(strict_types=1);

namespace AcytMaster\Api\Widgets;

use WP_Widget;

/**
 * Generate wiget
 */
class MediaWidget extends WP_Widget
{

  // default variables wordpress uses in widget API

  public $widget_ID;

  public $widget_name;

  public $widget_options = array();

  public $control_options = array();

  function __construct() {

    $this->widget_ID = 'acyt_media_widget';
    $this->widget_name = 'Acyt Media Widget';

    // set widget options
    $this->widget_options = array(
      'classname' => $this->widget_ID,
      'description' => $this->widget_name,
      'customize_selective_refresh' => true, // refresh without reloading page
    );

    // set dimensions
    $this->control_options = array(
      'width' => 400,
      'height' => 350,
    );
  }

  public function register() {
    // call parent
    parent::__construct( $this->widget_ID, $this->widget_name, $this->widget_options, $this->control_options );

    add_action( 'widgets_init', array( $this, 'widget_init' ) );
  }

  public function widget_init() {
    register_widget( $this ); // just pass instance to register
  }

  // generate output of widget
  public function widget( $args, $instance ) {
    echo $args['before_widget']; // predeclared classes
    if ( ! empty( $instance['title'] ) ) {
      echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
    }
    if ( ! empty( $instance['image'] ) ) {
      echo '<img src="'. esc_url( $instance['image'] ) .'" alt="">';
    }
    echo $args['after_widget'];
  }

  // generate widget settings update form
  public function form( $instance ) {
    $title = !empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Custom Text', 'acyt-master' );
    $image = !empty( $instance['image'] ) ? $instance['image'] : '';

    ?>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'acyt_plugin' ); ?></label> 
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php esc_attr_e( 'Image:', 'acyt_plugin' ); ?></label> 
      <input class="widefat image-upload" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" type="text" value="<?php echo esc_url( $image ); ?>">
      <button type="button" class="button button-primary js-image-upload">Select Image</button>
    </p>
    <?php 
  }

  // updating widget info
  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = sanitize_text_field( $new_instance['title'] );
    $instance['image'] = ! empty( $new_instance['image'] ) ? $new_instance['image'] : '';

    return $instance;
  }

}