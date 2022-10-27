<?php
/**
 * @package Acyt-master
 */

declare(strict_types=1);

namespace AcytMaster\Api;

/**
 * Modularize Wordpress Settings API (because its bloated mess) , use it as some interface
 * https://developer.wordpress.org/plugins/settings/settings-api/
 */

final class SettingsApi
{
  public $admin_pages = array();

  public $admin_subpages = array();

  public $settings = array();
 
	public $sections = array();

	public $fields = array();

  public function register() {
    if( !empty($this->admin_pages || $this->admin_subpages) ) { // add page or subpage
      add_action( 'admin_menu', array( $this, 'addAdminMenu' ) );
    }

    if ( !empty($this->settings) ) {
      add_action( 'admin_init', array( $this, 'registerCustomFields' ) );
    }
  }

  public function addPages( array $admin_pages ) { // type hinting https://stackoverflow.com/questions/38308985/why-is-type-hinting-necessary-in-php
    $this->admin_pages = $admin_pages;
    return $this; // return instance of this class - used for method chaining
  }
  
  // adds subpage to first page
  public function withSubPage( string $title = null ) 
  {
    if ( empty($this->admin_pages) ) {
      return $this;
    }

    $admin_page = $this->admin_pages[0];

    $subpage = array(
      array(
        'parent_slug' => $admin_page['menu_slug'], 
        'page_title' => $admin_page['page_title'], 
        'menu_title' => ($title) ? $title : $admin_page['menu_title'], 
        'capability' => $admin_page['capability'], 
        'menu_slug' => $admin_page['menu_slug'], 
        'callback' => $admin_page['callback']
      )
    );

    $this->admin_subpages = $subpage;

    return $this;
  }

  // creates subpages from input array
  public function addSubPages( array $pages )
  {
    $this->admin_subpages = array_merge( $this->admin_subpages, $pages );

    return $this;
  }

  public function addAdminMenu() {
    // pages
    foreach ( $this->admin_pages as $page ) { // loop through pages and add to menu
      add_menu_page( $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position'] );
    }
  
    //subpages
    foreach ( $this->admin_subpages as $page ) {
      add_submenu_page( $page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'] );
    }
  }

  public function setSettings( array $settings ) {
    $this->settings = $settings;

    return $this;
  }

  public function setSections( array $sections ) {
    $this->sections = $sections;

    return $this;
  }

  public function setFields( array $fields ) {
    $this->fields = $fields;

    return $this;
  }

  public function registerCustomFields() {
    // register setting groups
    foreach ( $this->settings as $setting ) {
      register_setting( $setting["option_group"], $setting["option_name"], ( isset( $setting["callback"] ) ? $setting["callback"] : '' ) );
    }

    // add settings section
    foreach ( $this->sections as $section ) {
      add_settings_section( $section["id"], $section["title"], ( isset( $section["callback"] ) ? $section["callback"] : '' ), $section["page"] );
    }

    // add settings field
    foreach ( $this->fields as $field ) {
      add_settings_field( $field["id"], $field["title"], ( isset( $field["callback"] ) ? $field["callback"] : '' ), $field["page"], $field["section"], ( isset( $field["args"] ) ? $field["args"] : '' ) );
    }
  }

}