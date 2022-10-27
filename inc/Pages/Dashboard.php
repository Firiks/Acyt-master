<?php
/**
 * @package Acyt-master
 */

namespace AcytMaster\Pages;

use \AcytMaster\Base\BaseClass;
use \AcytMaster\Api\SettingsApi; // prefix with \ when inside folder structure
use \AcytMaster\Api\Callbacks\AdminCallbacks; // for pages & subpages
use \AcytMaster\Api\Callbacks\ManagerCallbacks; // fields, sections & sanitize

final class Dashboard extends BaseClass
{
  public $settings;

  public $callbacks;

  public $callbacks_mngr;

  public $pages = array(); // pages to be created
  
  // public $subpages = array();

  public function register() {

    $this->settings = new SettingsApi(); // our settings api instance will take care for crating pages

    $this->callbacks = new AdminCallbacks();

    $this->callbacks_mngr = new ManagerCallbacks();

    // pages & subpages
    $this->setPages();

    // $this->setSubpages();

    // settings groups & sections & fields
    $this->setSettings();
    
    $this->setSections();
    
    $this->setFields();

    $this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->register(); // method chaining , addPages returns $this , no need to call $this->settings->register()
  }

  public function setPages() {
    $this->pages = array( // what page needs to be create
      array(
        'page_title' => 'Acyt Plugin',
        'menu_title' => 'Acyt Plugin',
        'capability' => 'manage_options',
        'menu_slug' => 'acyt_plugin',
        'callback' => array( $this->callbacks, 'adminDashboard' ), 
        'icon_url' => 'dashicons-money-alt',
        'position' => 110
      ),
    );
  }

  // moved to separate classes and enable based on option
  // public function setSubpages() {
  //   $this->subpages = array(
  //     array(
  //       'parent_slug' => 'acyt_plugin',
  //       'page_title' => 'Custom Post Types',
  //       'menu_title' => 'CPT',
  //       'capability' => 'manage_options',
  //       'menu_slug' => 'acyt_cpt',
  //       'callback' => array( $this->callbacks, 'adminCpt' )
  //     ),
  //     array(
  //       'parent_slug' => 'acyt_plugin',
  //       'page_title' => 'Custom Taxonomies',
  //       'menu_title' => 'Taxonomies',
  //       'capability' => 'manage_options',
  //       'menu_slug' => 'acyt_taxonomies',
  //       'callback' => array( $this->callbacks, 'adminTaxonomy' )
  //     ),
  //     array(
  //       'parent_slug' => 'acyt_plugin',
  //       'page_title' => 'Custom Widgets',
  //       'menu_title' => 'Widgets',
  //       'capability' => 'manage_options',
  //       'menu_slug' => 'acyt_widgets',
  //       'callback' => array( $this->callbacks, 'adminWidget' )
  //     )
  //   );
  // }

  public function setSettings() {
    $args = array(
      array(
        'option_group' => 'acyt_plugin_settings',
        'option_name' => 'acyt_plugin', // all settings will be in this option and serialized (wp_options table)
        'callback' => array( $this->callbacks_mngr, 'checkboxSanitize' )
      )
    );

    $this->settings->setSettings( $args );
  }

  public function setSections() {
    $args = array(
      array(
        'id' => 'acyt_admin_index',
        'title' => 'Settings Manager',
        'callback' => array( $this->callbacks_mngr, 'adminSectionManager' ),
        'page' => 'acyt_plugin'
      )
    );

    $this->settings->setSections( $args );
  }

  public function setFields() {
    $args = array();

    foreach ( $this->managers as $key => $value ) {
      $args[] = array(
        'id' => $key,
        'title' => $value,
        'callback' => array( $this->callbacks_mngr, 'checkboxField' ),
        'page' => 'acyt_plugin',
        'section' => 'acyt_admin_index',
        'args' => array( // optional arguments, names doesnt matter
          'option_name' => 'acyt_plugin', // master option
          'label_for' => $key, // label
          'class' => 'ui-toggle' // class
        )
      );
    }

    $this->settings->setFields( $args );
  }

}