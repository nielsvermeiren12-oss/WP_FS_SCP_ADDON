# WP Filesystem SCP

Experimental SCP transport for the WordPress Filesystem API.

## Install

```bash
cd wp-content/plugins/wp-filesystem-scp
composer install --no-dev
```

Activate the plugin in WordPress.

## wp-config.php example

```php
define('FS_METHOD', 'scp');
define('FTP_HOST', 'example.com:22');
define('FTP_USER', 'deploy');
define('FTP_PASS', 'your-password');
define('WPFS_SCP_BASE_DIR', '/var/www/html');
```

For private key auth:

```php
define('WPFS_SCP_PRIVATE_KEY', '/home/www/.ssh/id_ed25519');
define('WPFS_SCP_PRIVATE_KEY_PASSWORD', 'optional-passphrase');
```

## Notes

SCP itself mainly uploads/downloads files. Methods such as `dirlist()`, `chmod()`, `mkdir()` and `delete()` are implemented with SSH shell commands over the same connection.

This is an MVP and should be tested carefully before use on production sites.
