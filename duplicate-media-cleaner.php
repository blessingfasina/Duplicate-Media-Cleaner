<?php
/**
 * Plugin Name: Duplicate Media Cleaner
 * Description: Detects and deletes duplicate media files in the WordPress media library.
 * Version: 1.0
 * Author: Blessing Fasina
 * Author URI: https://geniuscreations.com.ng
 * Text Domain: duplicate-media-cleaner
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants.
define('DMC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DMC_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files.
require_once DMC_PLUGIN_DIR . 'includes/class-dmc-scanner.php';
require_once DMC_PLUGIN_DIR . 'includes/class-dmc-deleter.php';

// Initialize the plugin.
function dmc_init() {
    if (is_admin()) {
        // Load the admin interface.
        DMC_Scanner::get_instance();
        DMC_Deleter::get_instance();
    }
}
add_action('plugins_loaded', 'dmc_init');

// Add settings link to the plugin on the plugins page
function dmc_settings_link($links) {
    $settings_link = '<a href="' . admin_url('upload.php?page=duplicate-media-cleaner') . '">' . __('Settings', 'duplicate-media-cleaner') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'dmc_settings_link');
