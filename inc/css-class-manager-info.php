<?php
/*Shows the info to recommend installing the CSS Class Manger plugin for use with themes built in css-framework.css https://wordpress.org/plugins/css-class-manager/ 
Tutorial: XXXX
*/

/**
 * 1. LOGIC: Handle the "Dismiss Forever" action
 * Runs on admin_init to catch the URL parameter before the page loads.
 */
add_action( 'admin_init', 'jksn_css_framework_handle_dismissal' );

function jksn_css_framework_handle_dismissal() {
    // Check if our specific dismiss flag is in the URL
    if ( isset( $_GET['jksn_dismiss_notice'] ) && 'forever' === $_GET['jksn_dismiss_notice'] ) {
        
        // Security Check: Verify the Nonce
        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'jksn_dismiss_framework_notice' ) ) {
            return;
        }

        // Permission Check
        if ( ! current_user_can( 'install_plugins' ) ) {
            return;
        }

        // Save the option globally (using update_option instead of user_meta)
        update_option( 'jksn_css_framework_notice_dismissed', 'yes' );

        // Redirect back to the same page to clear the URL and hide the notice immediately
        wp_safe_redirect( remove_query_arg( array( 'jksn_dismiss_notice', '_wpnonce' ) ) );
        exit;
    }
}

/**
 * 2. VIEW: The Admin Notice
 */
add_action( 'admin_notices', 'jksn_css_framework_theme_reminder_notice' );

function jksn_css_framework_theme_reminder_notice() {
    
    // 1. Security/Role Check
    if ( ! current_user_can( 'install_plugins' ) ) {
        return;
    }

    // 2. Database Check: Has this been dismissed forever?
    if ( get_option( 'jksn_css_framework_notice_dismissed' ) ) {
        return;
    }

    // 3. Plugin Active Check: Stop showing if "CSS Class Manager" is already active.
    $plugin_slug = 'css-class-manager/css-class-manager.php';
    if ( in_array( $plugin_slug, (array) get_option( 'active_plugins', array() ) ) ) {
        return;
    }

    // 4. Generate the URLs
    // -- Install URL
    $search_query = 'CSS Class Manager – An advanced autocomplete additional css class control for your blocks';
    $install_url = add_query_arg(
        array(
            's'    => $search_query,
            'tab'  => 'search',
            'type' => 'term',
        ),
        admin_url( 'plugin-install.php' )
    );

    // -- Dismiss URL (with Nonce security)
    $dismiss_url = wp_nonce_url( 
        add_query_arg( 'jksn_dismiss_notice', 'forever' ), 
        'jksn_dismiss_framework_notice' 
    );

    // 5. The HTML Output
    // Note: We kept 'is-dismissible' so they can still temporarily close it with the 'X' 
    // if they aren't ready to decide yet.
    ?>
    <div class="notice notice-warning is-dismissible">
        <p style="font-size: 14px; margin-bottom: 10px;">
            <strong>kc-bt-temp:</strong> Hey, don’t forget to also install the <strong>CSS Class Manager</strong> plugin.<br>
            This plugin shows a drop down of the available CSS classes we've included in the kc-bt-temp theme for full responsive control 
            <a href="https://youtu.be/uAkUVwAd_cE" target="_blank" style="display: inline-block; text-decoration: none; font-weight: 700;"> 
                <span class="dashicons dashicons-video-alt3" style="font-size: 18px; margin-right: 3px; vertical-align: middle;"></span>Watch the Tutorial 
            </a>
        </p>
        <p style="margin-bottom: 13px; padding: 0; display: flex; align-items: center; gap: 15px;">
            <a href="<?php echo esc_url( $install_url ); ?>" class="button button-primary">
                Click here to show the CSS Class Manager plugin in the "Add Plugin"
            </a>
            
            <a href="<?php echo esc_url( $dismiss_url ); ?>" style="text-decoration: none; color: #72777c; font-size: 13px;">
                Dismiss this message forever
            </a>
        </p>
    </div>
    <?php
}


