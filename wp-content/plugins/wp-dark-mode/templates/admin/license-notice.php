<div class="license-activation-notice">
    <div class="wp-dark-mode-notice-icon">
        <img src="<?php echo wp_dark_mode()->plugin_url( 'assets/images/wp-dark-mode-icon.png' ); ?>" alt="WP Dark Mode Icon">
    </div>

    <div class="wp-dark-mode-notice-text">
        <p><strong><?php _e( 'Activate License', 'wp-dark-mode' ); ?> - <?php echo $plugin_name; ?> - <?php _e( 'Version',
					'wp-dark-mode' ); ?> <?php echo $version; ?></strong></p>
        <p><?php _e( 'Activate the license for ', 'wp-dark-mode' ); ?><?php echo $plugin_name; ?><?php _e( ' to function properly.',
				'wp-dark-mode' ); ?></p>
    </div>

    <div class="wp-dark-mode-notice-actions">
        <a href="<?php echo admin_url( 'options-general.php?page=wp-dark-mode-settings#wp_dark_mode_license' ); ?>" class="button button-primary activate-license"><?php _e( 'Activate License',
				'wp-dark-mode' ); ?></a>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
    </div>
</div>