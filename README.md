# WordPress WebApp Starter Template

A template repository for projects where a standalone web application lives alongside a WordPress install and integrates with WordPress authentication.

## Repository Layout
- `webapp/`
- `wpplugin/`
- `.github/workflows/deploy.yml`
- `README.md`

## Deployed Layout (on server)
- `/webapp`
- `/wp-admin`
- `/wp-content/plugins/webapp`
- `/wp-includes`
- ... other WordPress files

## Web App Bootstrapping
Place this at the very top of `webapp/index.php` (and other PHP entry files if needed):

```php
// Boot WordPress for session, user, DB, and more
$wp_path = dirname(__DIR__) . '/wp-load.php';
if (!file_exists($wp_path)) {
    die('Error: Cannot find wp-load.php. Make sure this file is in the right directory.');
}
require_once $wp_path;

// Redirect to login if not authenticated
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url('/webapp/'));
    exit;
}
```

## WordPress Plugin Scaffold
Plugin slug: `webapp`. On deploy, files end up in `/wp-content/plugins/webapp`.

Structure:
- `wpplugin/webapp.php` (main plugin header + activation/deactivation hooks)
- `wpplugin/includes/` (init files, roles/caps, etc.)
- `wpplugin/readme.txt`

Note: API endpoints are implemented inside the web app (see below), not in the plugin.

## WebApp API (inside `webapp/api`)
This template serves API endpoints from the `webapp/api` directory.

- Entry point: `webapp/api/index.php` (front controller)
- Example endpoints:
  - `GET /webapp/api/health` → `{ "status": "ok" }`
  - `GET /webapp/api/me` → returns the current authenticated user (401 if not logged in)
- An `.htaccess` is included for Apache to route all requests under `webapp/api/` to `index.php`. For Nginx, add an equivalent location block.

## GitHub Actions Deployment (SSH + rsync)
This repository includes `.github/workflows/deploy.yml` to deploy only the app parts. It:
- Triggers on push to `main` and on `workflow_dispatch`
- Uses SSH key auth with repository secrets
- Deploys only:
  - `webapp/` → `$TARGET_WEB_ROOT/webapp`
  - `wpplugin/` → `$TARGET_WEB_ROOT/wp-content/plugins/webapp`
- Does not touch uploads or core WP files
- Supports a DRY RUN flag for safety

### Repository secrets to add
Set in GitHub → Settings → Secrets and variables → Actions:
- `DEPLOY_HOST` (e.g. example.com)
- `DEPLOY_USER` (e.g. deploy)
- `DEPLOY_PORT` (e.g. 22)
- `DEPLOY_PATH` (e.g. /var/www/example.com)
- `DEPLOY_SSH_KEY` (private key contents; no passphrase recommended for CI)

## Local Development
- Expectation: This template assumes your WordPress root is the repository root on the server. Locally, you can point a local WP install so that `/webapp` sits adjacent to `wp-load.php`.
- The app can be built with any stack you like. Ensure entry points eventually route through a PHP file that boots WordPress if you need authentication.

## Deployment
1. Set the repository secrets listed above.
2. Push to `main` or run the workflow manually with `dry_run: true` first.
3. First deploy: the plugin files will be copied automatically to `wp-content/plugins/webapp`. Activate via WP Admin → Plugins or via WP-CLI:

```bash
wp plugin activate webapp
```

What gets deployed: only `webapp/` and `wpplugin/`.

