<?php

if (!defined('ABSPATH')) {
    exit;
}

class DMC_Scanner {
    private static $instance = null;

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
    }

    public function add_admin_menu() {
        add_media_page(
            __('Duplicate Media Cleaner', 'duplicate-media-cleaner'),
            __('Duplicate Media Cleaner', 'duplicate-media-cleaner'),
            'manage_options',
            'duplicate-media-cleaner',
            [$this, 'scanner_page']
        );
    }

    public function scanner_page() {
        if (isset($_POST['dmc_scan'])) {
            check_admin_referer('dmc_scan_nonce');

            $duplicates = $this->scan_for_duplicates();
            include DMC_PLUGIN_DIR . 'templates/admin-page.php';
        } else {
            include DMC_PLUGIN_DIR . 'templates/admin-page.php';
        }
    }

    private function scan_for_duplicates() {
        global $wpdb;

        // Get all attachments
        $query = "
            SELECT ID, post_title, guid
            FROM $wpdb->posts
            WHERE post_type = 'attachment'
        ";
        $attachments = $wpdb->get_results($query);

        $hashes = [];
        $duplicates = [];

        // Calculate hash for each attachment based on name and size
        foreach ($attachments as $attachment) {
            $file_path = get_attached_file($attachment->ID);
            if (file_exists($file_path)) {
                $file_size = filesize($file_path);
                $file_name = basename($file_path);
                $hash = md5($file_name . $file_size);

                if (isset($hashes[$hash])) {
                    $hashes[$hash][] = $attachment;
                } else {
                    $hashes[$hash] = [$attachment];
                }
            }
        }

        // Only return duplicates
        foreach ($hashes as $hash => $attachments) {
            if (count($attachments) > 1) {
                $duplicates[$hash] = $attachments;
            }
        }

        return $duplicates;
    }
}
