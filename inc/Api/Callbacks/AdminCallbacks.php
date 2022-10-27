<?php
/**
 * @package Acyt-master
 */

declare(strict_types=1);

namespace AcytMaster\Api\Callbacks;
/**
 * Pages & subpages templates callbacks
 */
final class AdminCallbacks
{

  public function adminDashboard() {
    return require_once( ACYT_PLUGIN_DIR . "/templates/pages/dashboard.php" ); // dashboard page , call settings_fields, do_settings_section, submit_button
  }

  public function adminCpt() {
    return require_once( ACYT_PLUGIN_DIR . "/templates/pages/cpt.php" );
  }

  public function adminTaxonomy() {
    return require_once( ACYT_PLUGIN_DIR . "/templates/pages/taxonomy.php" );
  }

  public function adminWidget() {
    return require_once( ACYT_PLUGIN_DIR . "/templates/pages/widget.php" );
  }

  public function adminGallery() {
    echo "<h1>Gallery Manager</h1>";
  }

  public function adminTestimonial() {
    return require_once( ACYT_PLUGIN_DIR . "/templates/pages/testimonial.php" );
  }

  public function adminTemplates() {
    echo "<h1>Templates Manager</h1>";
  }

  public function adminAuth() {
    echo "<h1>Templates Manager</h1>";
  }

  public function adminMembership() {
    echo "<h1>Membership Manager</h1>";
  }

  public function adminChat() {
    echo "<h1>Chat Manager</h1>";
  }

}