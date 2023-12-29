<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if the uninstall process was correctly initiated
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete options from the options table
delete_option('bbw_options');

// For site options in Multisite
delete_site_option('bbw_options');

// Delete any transients and other options
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'bbw\_%'");

// Delete any custom tables or other database elements, if any
// $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}myplugin_table");

// Note: In Multisite looping through blogs to delete options can be resource-intensive.
// You may want to delete only the options for the main site, or for sites that have the plugin activated
?>
