<?php

if (!defined('ABSPATH')) {
    exit;
}

class DMC_Deleter {
    private static $instance = null;

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_post_dmc_delete', [$this, 'delete_duplicates']);
    }

    public function delete_duplicates() {
        if (!current_user_can('manage_options') || !check_admin_referer('dmc_delete_nonce')) {
            wp_die(__('You are not allowed to do this action.', 'duplicate-media-cleaner'));
        }

        if (isset($_POST['attachments']) && is_array($_POST['attachments'])) {
            foreach ($_POST['attachments'] as $attachment_id) {
                $this->delete_attachment($attachment_id);
            }
        }

        wp_redirect(admin_url('upload.php?page=duplicate-media-cleaner&deleted=true'));
        exit;
    }

    private function delete_attachment($attachment_id) {
        if (wp_delete_attachment($attachment_id, true)) {
            // Log the deletion if needed
        }
    }
}
