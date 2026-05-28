# WP Filesystem SCP Addon

```This plugin is a custom extension of the FileSystem API```
#### :outbox_tray: **It adds support for file transfers over the SCP protocol**

> [!IMPORTANT]
> **This project exists as a POC and should be carefully reviewed before production usage**

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
define('FTP_USER', 'deploy');t
define('FTP_PASS', 'your-password');
define('WPFS_SCP_BASE_DIR', '/var/www/html');
```

For private key auth:

```php
define('WPFS_SCP_PRIVATE_KEY', '/home/www/.ssh/id_ed25519');
define('WPFS_SCP_PRIVATE_KEY_PASSWORD', 'optional-passphrase');
```

## Why SCP Instead of FTP?

**Compared to FTP:**

- ✔️ SCP is encrypted
- ✔️ supports SSH key authentication
- ✔️ does not require additional services
- ✔️ integrates naturally with Linux infrastructure

**Compared to SFTP:**

- ✔️ SCP is simpler and optimized for direct transfers
- ✔️ good for deployment-style workflows
- ✔️ ideal for bulk uploads and artifact delivery

## Potential
> [!TIP]
> Consider the following use cases where SCP will shine!

<details>
<summary>Managed hosts</summary><br>

**Many managed hosts disable:**

- `FS_METHOD=direct`
- FTP access
- writable webroot permissions
  
**But still allow SSH access.**<br>

✔️ This plugin enables secure file operations without requiring unsafe permissions like:

```bash
chmod -R 777 wp-content
```

</details>

<details>
<summary> Multi-Site or Multi-Server Deployments</summary><br>

**Useful for distributing:**

- ✔️ plugins
- ✔️ themes
- ✔️ MU plugins
- ✔️ configuration files
- ✔️ generated assets

From a central WordPress dashboard to multiple remote installations.<br>

**Example:**

```text
Master WordPress Site
        ↓
 Deploy Plugin/Theme
        ↓
 Multiple Remote WordPress Sites
```

</details>

<details>
<summary>CI/CD and Deployment Pipelines</summary><br>

**Can be integrated into:**

- ✔️ GitHub Actions
- ✔️ GitLab CI
- ✔️ Jenkins
- ✔️ custom deployment systems
 
**Typical workflow:**

```text
Build Assets
    ↓
Upload via SCP
    ↓
Remote Installation / Activation
```

</details>

<details>
        
<summary>Enterprise / Internal Infrastructure</summary><br>

**Useful for:**

- ✔️ government systems
- ✔️ banking infrastructure
- ✔️ private intranets
- ✔️ air-gapped environments

Where only SSH traffic is permitted.

</details>

## Limitations

> [!CAUTION]
> The WordPress Filesystem API expects full filesystem behavior:

```php
exists()
mkdir()
chmod()
delete()
dirlist()
```

- :white_check_mark: SCP itself is mainly a transfer protocol.
- :expressionless: Some advanced operations may still require SSH shell commands or additional abstractions.

## Long-Term Vision

**This project can evolve into a more complete WordPress deployment layer featuring:**

- :white_check_mark: SCP-based deployments
- :white_check_mark: SSH command execution
- :white_check_mark: WP-CLI orchestration
- :white_check_mark: rollback support
- :white_check_mark: remote backups
- :white_check_mark: atomic deployments
- :white_check_mark:multi-server synchronization

**A modern DevOps-oriented deployment solution for WordPress.**

# Warning
> [!CAUTION]
> SCP itself mainly uploads/downloads files. Methods such as `dirlist()`, `chmod()`, `mkdir()` and `delete()` are implemented with SSH shell commands over the same connection.

**This is an MVP and should be tested carefully before use on production sites**
