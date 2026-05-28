<?php
/**
 * Plugin Name: WP Filesystem SCP
 * Description: Adds an experimental SCP transport for the WordPress Filesystem API using phpseclib.
 * Version: 0.1.0
 * Author: Niels Vermeiren
 * License: GPL-2.0-or-later
 * Requires PHP: 8.0
 */

if (!defined('ABSPATH')) {
    exit;
}

define('WPFS_SCP_PLUGIN_FILE', __FILE__);
define('WPFS_SCP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPFS_SCP_PLUGIN_VERSION', '0.1.0');

/**
 * Load Composer dependencies if present.
 */
$autoload = WPFS_SCP_PLUGIN_DIR . 'vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

/**
 * Register the custom filesystem method file.
 */
add_filter('filesystem_method_file', function ($path, $method) {
    if ($method === 'scp') {
        return WPFS_SCP_PLUGIN_DIR . 'includes/class-wp-filesystem-scp.php';
    }

    return $path;
}, 10, 2);

/**
 * Optionally force SCP when requested.
 *
 * Add this to wp-config.php to force this transport:
 * define('FS_METHOD', 'scp');
 *
 * Or enable the plugin setting under Settings > SCP Filesystem.
 */
add_filter('filesystem_method', function ($method, $args = [], $context = '', $allow_relaxed_file_ownership = false) {
    if (defined('FS_METHOD') && FS_METHOD === 'scp') {
        return 'scp';
    }

    if (get_option('wpfs_scp_force_method') === '1') {
        return 'scp';
    }

    return $method;
}, 10, 4);

/**
 * Add plugin settings page.
 */
add_action('admin_menu', function () {
    add_options_page(
        'SCP Filesystem',
        'SCP Filesystem',
        'manage_options',
        'wpfs-scp',
        'wpfs_scp_render_settings_page'
    );
});

add_action('admin_init', function () {
    register_setting('wpfs_scp_settings', 'wpfs_scp_force_method', [
        'type' => 'string',
        'sanitize_callback' => static fn($value) => $value === '1' ? '1' : '0',
        'default' => '0',
    ]);

    register_setting('wpfs_scp_settings', 'wpfs_scp_hostname', [
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '',
    ]);

    register_setting('wpfs_scp_settings', 'wpfs_scp_port', [
        'type' => 'integer',
        'sanitize_callback' => static fn($value) => max(1, min(65535, (int) $value)),
        'default' => 22,
    ]);

    register_setting('wpfs_scp_settings', 'wpfs_scp_username', [
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '',
    ]);

    register_setting('wpfs_scp_settings', 'wpfs_scp_base_dir', [
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '',
    ]);
});

function wpfs_scp_render_settings_page(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>SCP Filesystem</h1>
        <p>
            This plugin registers an experimental <code>scp</code> transport for the WordPress Filesystem API.
            For production use, prefer constants in <code>wp-config.php</code> for sensitive values.
        </p>

        <form method="post" action="options.php">
            <?php settings_fields('wpfs_scp_settings'); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">Force SCP filesystem method</th>
                    <td>
                        <label>
                            <input type="checkbox" name="wpfs_scp_force_method" value="1" <?php checked(get_option('wpfs_scp_force_method'), '1'); ?>>
                            Use <code>scp</code> as the filesystem method.
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpfs_scp_hostname">Hostname</label></th>
                    <td><input class="regular-text" id="wpfs_scp_hostname" name="wpfs_scp_hostname" value="<?php echo esc_attr(get_option('wpfs_scp_hostname', '')); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpfs_scp_port">Port</label></th>
                    <td><input class="small-text" type="number" min="1" max="65535" id="wpfs_scp_port" name="wpfs_scp_port" value="<?php echo esc_attr(get_option('wpfs_scp_port', 22)); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpfs_scp_username">Username</label></th>
                    <td><input class="regular-text" id="wpfs_scp_username" name="wpfs_scp_username" value="<?php echo esc_attr(get_option('wpfs_scp_username', '')); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpfs_scp_base_dir">Remote WordPress base dir</label></th>
                    <td>
                        <input class="regular-text" id="wpfs_scp_base_dir" name="wpfs_scp_base_dir" placeholder="/var/www/html" value="<?php echo esc_attr(get_option('wpfs_scp_base_dir', '')); ?>">
                        <p class="description">Maps local WordPress paths to this remote directory.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>

        <h2>Recommended wp-config.php constants</h2>
        <pre><code>define('FS_METHOD', 'scp');
define('FTP_HOST', 'example.com:22');
define('FTP_USER', 'deploy');
define('FTP_PASS', 'your-password');
define('WPFS_SCP_BASE_DIR', '/var/www/html');

// Optional private key auth:
define('WPFS_SCP_PRIVATE_KEY', '/home/www/.ssh/id_ed25519');
define('WPFS_SCP_PRIVATE_KEY_PASSWORD', 'optional-passphrase');</code></pre>
    </div>
    <?php
}
