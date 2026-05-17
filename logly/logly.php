<?php
/**
 * Plugin Name: Logly Analytics
 * Plugin URI:  https://logly.uk
 * Description: Privacy-first analytics under 1 KB. No cookies, no consent banner, works with Brave and uBlock Origin.
 * Version:     1.0.0
 * Author:      Logly
 * Author URI:  https://logly.uk
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: logly
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'LOGLY_VERSION', '1.0.0' );
define( 'LOGLY_APP',     'https://app.logly.uk' );
define( 'LOGLY_TRACKER', 'https://logly.uk' );

// ── Settings ──────────────────────────────────────────────────────────────────

add_action( 'admin_init', function () {
    register_setting( 'logly_settings', 'logly_site_id', [
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
    ] );
} );

add_action( 'admin_menu', function () {
    add_menu_page( 'Logly Analytics', 'Logly', 'manage_options', 'logly', 'logly_admin_page', 'dashicons-chart-bar', 80 );
} );

function logly_admin_page() {
    $site_id = get_option( 'logly_site_id', '' );
    ?>
    <div class="wrap" style="max-width:960px">
        <h1 style="margin-bottom:0.75rem"><span style="color:#1a56db">&#9679;</span> Logly Analytics</h1>

        <?php if ( $site_id ) : ?>
        <div id="logly-settings-bar" style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:0.6rem 1rem;margin-bottom:0.75rem;display:flex;align-items:center;gap:1rem;flex-wrap:wrap">
            <span style="font-size:0.8125rem;color:#16a34a;font-weight:500">✅ Tracking active</span>
            <code style="font-size:0.8125rem;background:#f1f5f9;padding:0.15rem 0.5rem;border-radius:4px"><?php echo esc_html( $site_id ); ?></code>
            <button type="button" onclick="document.getElementById('logly-settings-form').style.display=document.getElementById('logly-settings-form').style.display==='none'?'block':'none';this.textContent=this.textContent==='Change'?'Cancel':'Change'" style="font-size:0.8rem;color:#6366f1;background:none;border:none;cursor:pointer;padding:0;text-decoration:underline">Change</button>
        </div>
        <div id="logly-settings-form" style="display:none;background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:1.25rem;margin-bottom:0.75rem">
        <?php else : ?>
        <div id="logly-settings-form" style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:1.25rem;margin-bottom:0.75rem">
        <?php endif; ?>
            <p style="color:#64748b;font-size:0.875rem;margin:0 0 0.875rem">
                Find your Site ID in <a href="<?php echo esc_url( LOGLY_APP ); ?>" target="_blank">app.logly.uk → Settings</a> — shown below the install snippet for each site.
                Enable the <strong>Public</strong> toggle on your site there to show analytics here.
            </p>
            <form method="post" action="options.php" style="display:flex;gap:0.5rem;align-items:flex-end;flex-wrap:wrap">
                <?php settings_fields( 'logly_settings' ); ?>
                <div>
                    <label for="logly_site_id" style="display:block;font-size:0.8rem;font-weight:600;color:#475569;margin-bottom:0.3rem">Site ID</label>
                    <input type="text" id="logly_site_id" name="logly_site_id"
                           value="<?php echo esc_attr( $site_id ); ?>"
                           style="width:260px" placeholder="e.g. licenflow-com" />
                </div>
                <?php submit_button( 'Save', 'primary', 'submit', false ); ?>
            </form>
        </div>

        <?php if ( $site_id ) : ?>
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:0.875rem 1rem 0">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.625rem">
                <span style="font-size:0.9375rem;font-weight:600">Analytics</span>
                <a href="<?php echo esc_url( LOGLY_APP . '/share?s=' . $site_id ); ?>" target="_blank" style="font-size:0.8125rem;color:#6366f1">Full dashboard →</a>
            </div>
            <iframe
                src="<?php echo esc_url( LOGLY_APP . '/embed?s=' . $site_id ); ?>"
                style="width:100%;height:calc(100vh - 220px);min-height:600px;border:1px solid #e2e8f0;border-radius:6px;background:#f8fafc;display:block"
                loading="lazy"
                title="Logly Analytics"
            ></iframe>
        </div>
        <?php endif; ?>
    </div>
    <?php
}

// ── Script injection ──────────────────────────────────────────────────────────

add_action( 'wp_enqueue_scripts', function () {
    $site_id = get_option( 'logly_site_id', '' );
    if ( ! $site_id ) return;
    wp_enqueue_script( 'logly-tracker', LOGLY_TRACKER . '/p.js', [], LOGLY_VERSION, true );
} );

add_filter( 'script_loader_tag', function ( $tag, $handle ) {
    if ( 'logly-tracker' !== $handle ) return $tag;
    $site_id = get_option( 'logly_site_id', '' );
    if ( ! $site_id ) return $tag;
    return str_replace( ' src=', ' data-site="' . esc_attr( $site_id ) . '" async src=', $tag );
}, 10, 2 );
