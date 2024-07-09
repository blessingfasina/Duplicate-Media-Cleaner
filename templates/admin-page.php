<div class="wrap">
    <h1><?php _e('Duplicate Media Cleaner', 'duplicate-media-cleaner'); ?></h1>

    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'true') : ?>
        <div class="notice notice-success">
            <p><?php _e('Duplicate media files deleted successfully.', 'duplicate-media-cleaner'); ?></p>
        </div>
    <?php endif; ?>

    <form method="post">
        <?php wp_nonce_field('dmc_scan_nonce'); ?>
        <input type="hidden" name="dmc_scan" value="1">
        <button type="submit" class="button button-primary"><?php _e('Scan for Duplicates', 'duplicate-media-cleaner'); ?></button>
    </form>

    <?php if (isset($duplicates) && !empty($duplicates)) : ?>
        <h2><?php _e('Duplicate Media Files', 'duplicate-media-cleaner'); ?></h2>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <?php wp_nonce_field('dmc_delete_nonce'); ?>
            <input type="hidden" name="action" value="dmc_delete">
            <table class="widefat fixed">
                <thead>
                    <tr>
                        <th><?php _e('File Hash', 'duplicate-media-cleaner'); ?></th>
                        <th><?php _e('File', 'duplicate-media-cleaner'); ?></th>
                        <th><?php _e('Action', 'duplicate-media-cleaner'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($duplicates as $hash => $attachments) : ?>
                        <?php foreach ($attachments as $attachment) : ?>
                            <tr>
                                <td><?php echo esc_html($hash); ?></td>
                                <td><?php echo wp_get_attachment_image($attachment->ID, [64, 64]); ?></td>
                                <td>
                                    <input type="checkbox" name="attachments[]" value="<?php echo esc_attr($attachment->ID); ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="button button-primary"><?php _e('Delete Selected', 'duplicate-media-cleaner'); ?></button>
        </form>
    <?php elseif (isset($_POST['dmc_scan'])) : ?>
        <h2><?php _e('No Duplicates Found', 'duplicate-media-cleaner'); ?></h2>
    <?php endif; ?>
</div>
