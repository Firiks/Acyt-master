<?php

/**
 * Trigger on plugin uninstall
 * 
 * @package Acyt-master
 */

declare(strict_types=1);

if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  die();
}

// Clear DB data
global $wpdb;

$custom_post_types = get_option('acyt_plugin_cpt', array());

$custom_post_types = wp_list_pluck( $custom_post_types, 'post_type' );

$where = array();

// build WHERE part to delete all custom post types
foreach( $custom_post_types AS $post_type ) {
  $where[] = "post_type = '$post_type'";
}

$where = implode(' OR ', $where);

// Delete custom posts
$wpdb->query( "DELETE FROM {$wpdb->prefix}posts WHERE post_type = 'book'" );

// Delete postmeta for nonexistent ids
$wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE post_id NOT IN (SELECT id FROM {$wpdb->prefix}posts)" );

// Delete term relationship (taxonomies)
$wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE object_id NOT IN (SELECT id FROM {$wpdb->prefix}posts)" );

// Delete options
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'acyt_plugin_%'" );

do_action('acyt_master_uninstalled');