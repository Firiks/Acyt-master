<?php
/**
 * @package Acyt-master
 */

declare(strict_types=1);

namespace AcytMaster\Controllers;

use AcytMaster\Api\SettingsApi;
use AcytMaster\Base\BaseClass;
use AcytMaster\Api\Callbacks\AdminCallbacks;

/**
* Manage testimonials
*/
class TestimonialController extends BaseClass
{

  public $settings;

  public $callbacks;

  public function register() {
    if ( ! $this->activated( 'testimonial_manager' ) ) return;

    $this->settings = new SettingsApi();

    $this->callbacks = new AdminCallbacks();

    add_action( 'init', array( $this, 'testimonial_cpt' ) ); // create cpt first
    add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) ); // then add custom metabox
    add_action( 'save_post', array( $this, 'save_meta_box' ), 10, 3 ); // saving custom meta on save_post action
    // show data at wp-admin/edit.php?post_type=testimonial
    add_action( 'manage_testimonial_posts_columns', array( $this, 'set_custom_columns' ) ); // manage_{cpt}_posts - filters the columns displayed in the Posts list table for a specific post type.
    add_action( 'manage_testimonial_posts_custom_column', array( $this, 'set_custom_columns_data' ), 10, 2 ); // add data to collumns
    add_filter( 'manage_edit-testimonial_sortable_columns', array( $this, 'set_custom_columns_sortable' ) ); // enable sorting

    // create shortcode page
    $this->setShortcodePage();

    add_shortcode( 'testimonial-form', array( $this, 'testimonial_form_shortcode' ) ); // add custom shortcode
  }

  public function testimonial_cpt() {
    $labels = array(
      'name' => 'Testimonials',
      'singular_name' => 'Testimonial'
    );

    $args = array(
      'labels' => $labels,
      'public' => true,
      'has_archive' => false,
      'menu_icon' => 'dashicons-testimonial',
      'exclude_from_search' => true,
      'publicly_queryable' => false,
      'supports' => array( 'title', 'editor' )
    );

    register_post_type( 'testimonial', $args );
  }

  public function add_meta_boxes() {
    add_meta_box(
      'testimonial_author', // id
      'Testimonial Options', // title
      array( $this, 'render_features_box' ), // callback to render meta box
      'testimonial', // screen
      'side', // normal, side, advanced
      'default'
    );
  }

  public function render_features_box($post) {
    wp_nonce_field( 'acyt_testimonial', 'acyt_testimonial_nonce'); // generate nonce field

    $post_meta = get_post_meta( $post->ID, '_acyt_testimonial_key', true );
    $name = isset($post_meta['name']) ? $post_meta['name'] : '';
    $email = isset($post_meta['email']) ? $post_meta['email'] : '';
    $approved = isset($post_meta['approved']) ? $post_meta['approved'] : false;
    $featured = isset($post_meta['featured']) ? $post_meta['featured'] : false;

    ?>
    <p>
      <label class="meta-label" for="acyt_testimonial_author">Author Name</label>
      <input type="text" id="acyt_testimonial_author" name="acyt_testimonial_author" class="widefat" value="<?php echo esc_attr( $name ); ?>">
    </p>
    <p>
      <label class="meta-label" for="acyt_testimonial_email">Author Email</label>
      <input type="email" id="acyt_testimonial_email" name="acyt_testimonial_email" class="widefat" value="<?php echo esc_attr( $email ); ?>">
    </p>
    <div class="meta-container">
      <label class="meta-label w-50 text-left" for="acyt_testimonial_approved">Approved</label>
      <div class="text-right w-50 inline">
        <div class="ui-toggle inline"><input type="checkbox" id="acyt_testimonial_approved" name="acyt_testimonial_approved" value="1" <?php echo $approved ? 'checked' : ''; ?>>
          <label for="acyt_testimonial_approved"><div></div></label>
        </div>
      </div>
    </div>
    <div class="meta-container">
      <label class="meta-label w-50 text-left" for="acyt_testimonial_featured">Featured</label>
      <div class="text-right w-50 inline">
        <div class="ui-toggle inline"><input type="checkbox" id="acyt_testimonial_featured" name="acyt_testimonial_featured" value="1" <?php echo $featured ? 'checked' : ''; ?>>
          <label for="acyt_testimonial_featured"><div></div></label>
        </div>
      </div>
    </div>
    <?php
  }

  public function save_meta_box($post_id, $post, $update ) {
    // check nonce
    if ( !isset($_POST['acyt_testimonial_nonce']) || !wp_verify_nonce( $_POST['acyt_testimonial_nonce'], 'acyt_testimonial' ) ) {
      return $post_id;
    }

    // ignore autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
      return $post_id;
    }

    // check user privillege
    if ( !current_user_can( 'edit_post', $post_id ) ) {
      return $post_id;
    }

    // only for testimonial post type
    if ( 'testimonial' !== $post->post_type ) {
      return $post_id;
    }

    if ( !function_exists( 'sanitize_email' ) ) { 
      require_once ABSPATH . WPINC . '/formatting.php';
    }

    $data = array(
      'name' => sanitize_text_field( $_POST['acyt_testimonial_author'] ),
      'email' => sanitize_email( $_POST['acyt_testimonial_email'] ),
      'approved' => isset($_POST['acyt_testimonial_approved']) ? 1 : 0,
      'featured' => isset($_POST['acyt_testimonial_featured']) ? 1 : 0,
    );

    update_post_meta( $post_id, '_acyt_testimonial_key', $data ); // set/update meta
  }

  public function set_custom_columns($columns) {
    // store data
    $title = $columns['title'];
    $date = $columns['date'];
    unset( $columns['title'], $columns['date'] ); // remove before reorder

    // reorder
    $columns['name'] = 'Author Name';
    $columns['title'] = $title;
    $columns['approved'] = 'Approved';
    $columns['featured'] = 'Featured';
    $columns['date'] = $date;

    return $columns;
  }

  public function set_custom_columns_data($column, $post_id) {
    $data = get_post_meta( $post_id, '_acyt_testimonial_key', true );
    $name = isset($data['name']) ? $data['name'] : '';
    $email = isset($data['email']) ? $data['email'] : '';
    $approved = isset($data['approved']) && $data['approved'] === 1 ? '<strong>YES</strong>' : 'NO';
    $featured = isset($data['featured']) && $data['featured'] === 1 ? '<strong>YES</strong>' : 'NO';

    switch($column) {
      case 'name':
        echo '<strong>' . $name . '</strong><br/><a href="mailto:' . $email . '">' . $email . '</a>';
        break;

      case 'approved':
        echo $approved;
        break;

      case 'featured':
        echo $featured;
        break;
    }
  }

  public function set_custom_columns_sortable($columns) {
    // enable sorting by name, approved, featured
    $columns['name'] = 'name';
    $columns['approved'] = 'approved';
    $columns['featured'] = 'featured';

    return $columns;
  }

  public function setShortcodePage() {
    $subpage = array(
      array(
        'parent_slug' => 'edit.php?post_type=testimonial',
        'page_title' => 'Shortcodes',
        'menu_title' => 'Shortcodes',
        'capability' => 'manage_options',
        'menu_slug' => 'acyt_testimonial_shortcode',
        'callback' => array( $this->callbacks, 'adminTestimonial' )
      )
    );

    $this->settings->addSubPages( $subpage )->register();
  }

  public function testimonial_form_shortcode() {
    // prevent output, shortcode function must use return https://developer.wordpress.org/reference/functions/add_shortcode/
    ob_start();

    require_once( ACYT_PLUGIN_DIR . "/templates/shortcodes/testimonial-form.php" );
    echo '<link rel="stylesheet" href="'. ACYT_PLUGIN_URL .'assets/css/form.min.css" type="text/css" media="all"></link> ';
    echo '<script src="'. ACYT_PLUGIN_URL .'assets/js/form.min.js"></script>';

    $html = ob_get_clean();

    return $html;
  }
}