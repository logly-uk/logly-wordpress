<?php
/**
 * Plugin Name: Loglyuk Privacy Analytics
 * Plugin URI:  https://logly.uk
 * Description: Privacy-first analytics under 1 KB. No cookies, no consent banner, works with Brave and uBlock Origin. Connects your WordPress site to Loglyuk (logly.uk).
 * Version:     1.0.0
 * Author:      Loglyuk
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: loglyuk-privacy-analytics
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'LOGLYUK_VERSION', '1.0.0' );
define( 'LOGLYUK_APP',     'https://app.logly.uk' );
define( 'LOGLYUK_TRACKER', 'https://logly.uk' );

// ── Settings ──────────────────────────────────────────────────────────────────

add_action( 'admin_init', function () {
    register_setting( 'loglyuk_settings', 'loglyuk_site_id', [
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
    ] );
} );

add_action( 'admin_menu', function () {
    add_menu_page(
        esc_html__( 'Loglyuk Privacy Analytics', 'loglyuk-privacy-analytics' ),
        esc_html__( 'Loglyuk', 'loglyuk-privacy-analytics' ),
        'manage_options',
        'loglyuk-privacy-analytics',
        'loglyuk_admin_page',
        'dashicons-chart-bar',
        80
    );
} );

add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( 'toplevel_page_loglyuk-privacy-analytics' !== $hook ) return;
    wp_enqueue_script( 'loglyuk-admin', plugins_url( 'admin.js', __FILE__ ), [], LOGLYUK_VERSION, true );
} );

function loglyuk_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have permission to access this page.', 'loglyuk-privacy-analytics' ) );
    }
    $site_id = get_option( 'loglyuk_site_id', '' );
    ?>
    <div class="wrap" style="max-width:960px">
        <h1 style="margin-bottom:0.75rem"><span style="color:#1a56db">&#9679;</span> <?php esc_html_e( 'Loglyuk Privacy Analytics', 'loglyuk-privacy-analytics' ); ?></h1>

        <?php if ( $site_id ) : ?>
        <div id="loglyuk-settings-bar" style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:0.6rem 1rem;margin-bottom:0.75rem;display:flex;align-items:center;gap:1rem;flex-wrap:wrap">
            <span style="font-size:0.8125rem;color:#16a34a;font-weight:500"><?php esc_html_e( '✅ Tracking active', 'loglyuk-privacy-analytics' ); ?></span>
            <code style="font-size:0.8125rem;background:#f1f5f9;padding:0.15rem 0.5rem;border-radius:4px"><?php echo esc_html( $site_id ); ?></code>
            <button type="button"
                    data-loglyuk-toggle
                    data-label-show="<?php echo esc_attr__( 'Change', 'loglyuk-privacy-analytics' ); ?>"
                    data-label-hide="<?php echo esc_attr__( 'Cancel', 'loglyuk-privacy-analytics' ); ?>"
                    style="font-size:0.8rem;color:#6366f1;background:none;border:none;cursor:pointer;padding:0;text-decoration:underline"><?php esc_html_e( 'Change', 'loglyuk-privacy-analytics' ); ?></button>
        </div>
        <div id="loglyuk-settings-form" style="display:none;background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:1.25rem;margin-bottom:0.75rem">
        <?php else : ?>
        <div id="loglyuk-settings-form" style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:1.25rem;margin-bottom:0.75rem">
        <?php endif; ?>
            <p style="color:#64748b;font-size:0.875rem;margin:0 0 0.875rem">
                <?php
                printf(
                    /* translators: %s: link to app.logly.uk Settings */
                    esc_html__( 'Find your Site ID in %s — shown below the install snippet for each site. Enable the Public toggle on your site there to show analytics here.', 'loglyuk-privacy-analytics' ),
                    '<a href="' . esc_url( LOGLYUK_APP . '/settings' ) . '" target="_blank" rel="noopener">app.logly.uk → Settings</a>'
                );
                ?>
            </p>
            <form method="post" action="options.php" style="display:flex;gap:0.5rem;align-items:flex-end;flex-wrap:wrap">
                <?php settings_fields( 'loglyuk_settings' ); ?>
                <div>
                    <label for="loglyuk_site_id" style="display:block;font-size:0.8rem;font-weight:600;color:#475569;margin-bottom:0.3rem"><?php esc_html_e( 'Site ID', 'loglyuk-privacy-analytics' ); ?></label>
                    <input type="text" id="loglyuk_site_id" name="loglyuk_site_id"
                           value="<?php echo esc_attr( $site_id ); ?>"
                           style="width:260px" placeholder="<?php echo esc_attr__( 'e.g. licenflow-com', 'loglyuk-privacy-analytics' ); ?>" />
                </div>
                <?php submit_button( __( 'Save', 'loglyuk-privacy-analytics' ), 'primary', 'submit', false ); ?>
            </form>
        </div>

        <?php if ( $site_id ) : ?>
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:0.875rem 1rem 0">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.625rem">
                <span style="font-size:0.9375rem;font-weight:600"><?php esc_html_e( 'Analytics', 'loglyuk-privacy-analytics' ); ?></span>
                <a href="<?php echo esc_url( LOGLYUK_APP . '/dashboard' ); ?>" target="_blank" rel="noopener" style="font-size:0.8125rem;color:#6366f1"><?php esc_html_e( 'Full dashboard →', 'loglyuk-privacy-analytics' ); ?></a>
            </div>
            <iframe
                src="<?php echo esc_url( LOGLYUK_APP . '/embed?s=' . $site_id ); ?>"
                style="width:100%;height:calc(100vh - 220px);min-height:600px;border:1px solid #e2e8f0;border-radius:6px;background:#f8fafc;display:block"
                loading="lazy"
                title="<?php echo esc_attr__( 'Loglyuk Privacy Analytics', 'loglyuk-privacy-analytics' ); ?>"
            ></iframe>
        </div>
        <?php endif; ?>
    </div>
    <?php
}

// ── Script injection ──────────────────────────────────────────────────────────

add_action( 'wp_enqueue_scripts', function () {
    $site_id = get_option( 'loglyuk_site_id', '' );
    if ( ! $site_id ) return;
    wp_enqueue_script( 'loglyuk-tracker', LOGLYUK_TRACKER . '/p.js', [], LOGLYUK_VERSION, true );
} );

add_filter( 'script_loader_tag', function ( $tag, $handle ) {
    if ( 'loglyuk-tracker' !== $handle ) return $tag;
    $site_id = get_option( 'loglyuk_site_id', '' );
    if ( ! $site_id ) return $tag;
    return str_replace( ' src=', ' data-site="' . esc_attr( $site_id ) . '" async src=', $tag );
}, 10, 2 );
