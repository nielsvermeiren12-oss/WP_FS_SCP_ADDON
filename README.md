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

# WordPress SCP Filesystem Transport

A custom implementation of the WordPress Filesystem API that adds support for the SCP protocol over SSH.

## Why Use SCP?

SCP is useful in environments where:

- FTP is disabled or considered insecure
- direct filesystem access is not available
- SSH access already exists
- deployments happen across multiple servers
- encrypted authenticated transfers are required

Because SCP uses SSH, it provides:

- encrypted file transfers
- SSH key authentication
- secure automation
- compatibility with hardened Linux servers
- firewall-friendly communication (usually port 22)

---

# Potential Use Cases

## Managed Hosting Environments

Many managed hosts disable:

- `FS_METHOD=direct`
- FTP access
- writable webroot permissions

But still allow SSH access.

This plugin enables secure file operations without requiring unsafe permissions like:

```bash
chmod -R 777 wp-content
```

---

## Multi-Site or Multi-Server Deployments

Useful for distributing:

- plugins
- themes
- MU plugins
- configuration files
- generated assets

From a central WordPress dashboard to multiple remote installations.

Example:

```text
Master WordPress Site
        ↓
 Deploy Plugin/Theme
        ↓
 Multiple Remote WordPress Sites
```

---

## CI/CD and Deployment Pipelines

Can be integrated into:

- GitHub Actions
- GitLab CI
- Jenkins
- custom deployment systems

Typical workflow:

```text
Build Assets
    ↓
Upload via SCP
    ↓
Remote Installation / Activation
```

---

## Enterprise / Internal Infrastructure

Useful for:

- government systems
- banking infrastructure
- private intranets
- air-gapped environments

Where only SSH traffic is permitted.

---

# Why SCP Instead of FTP?

Compared to FTP:

- SCP is encrypted
- supports SSH key authentication
- does not require additional services
- integrates naturally with Linux infrastructure

Compared to SFTP:

- SCP is simpler and optimized for direct transfers
- good for deployment-style workflows
- ideal for bulk uploads and artifact delivery

---

# Limitations

The WordPress Filesystem API expects full filesystem behavior:

```php
exists()
mkdir()
chmod()
delete()
dirlist()
```

SCP itself is mainly a transfer protocol.

Some advanced operations may still require SSH shell commands or additional abstractions.

---

# Long-Term Vision

This project can evolve into a more complete WordPress deployment layer featuring:

- SCP-based deployments
- SSH command execution
- WP-CLI orchestration
- rollback support
- remote backups
- atomic deployments
- multi-server synchronization

A modern DevOps-oriented deployment solution for WordPress.

## Notes

<p style="color:red;font-size:12px;">Make this text blue.</p>SCP itself mainly uploads/downloads files. Methods such as `dirlist()`, `chmod()`, `mkdir()` and `delete()` are implemented with SSH shell commands over the same connection.</p>

<em>This is an MVP and should be tested carefully before use on production sites.</em>
