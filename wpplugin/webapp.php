<?php
/**
 * Plugin Name: WebApp Integration
 * Plugin URI: https://example.com/
 * Description: Provides integration hooks and roles/caps for the companion web application.
 * Version: 0.1.0
 * Author: Your Name
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: webapp
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

// Define constants
if (!defined('WEBAPP_PLUGIN_FILE')) {
    define('WEBAPP_PLUGIN_FILE', __FILE__);
}
if (!defined('WEBAPP_PLUGIN_DIR')) {
    define('WEBAPP_PLUGIN_DIR', plugin_dir_path(__FILE__));
}
if (!defined('WEBAPP_PLUGIN_URL')) {
    define('WEBAPP_PLUGIN_URL', plugin_dir_url(__FILE__));
}

// Autoload includes
require_once WEBAPP_PLUGIN_DIR . 'includes/init.php';

register_activation_hook(__FILE__, function () {
    // Ensure custom roles and caps are registered on activation
    if (function_exists('webapp_register_roles')) {
        webapp_register_roles();
    }
});

register_deactivation_hook(__FILE__, function () {
    // Optionally clean up caps/roles. For safety, we do not remove roles by default.
});


