=== WP Filesystem SCP ===
Contributors: nielsvermeiren
Tags: filesystem, scp, ssh, deployment
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 8.0
Stable tag: 0.1.0
License: GPL-2.0-or-later

Experimental SCP transport for the WordPress Filesystem API using phpseclib.

== Installation ==
1. Upload the plugin directory to wp-content/plugins/wp-filesystem-scp.
2. Run composer install inside the plugin directory.
3. Activate the plugin.
4. Configure Settings > SCP Filesystem or use wp-config.php constants.

== Recommended wp-config.php constants ==
define('FS_METHOD', 'scp');
define('FTP_HOST', 'example.com:22');
define('FTP_USER', 'deploy');
define('FTP_PASS', 'your-password');
define('WPFS_SCP_BASE_DIR', '/var/www/html');

Optional private key auth:
define('WPFS_SCP_PRIVATE_KEY', '/home/www/.ssh/id_ed25519');
define('WPFS_SCP_PRIVATE_KEY_PASSWORD', 'optional-passphrase');

== Notes ==
This is an MVP. SCP does not expose every filesystem operation natively, so directory listings and metadata methods use SSH shell commands where needed.
