<?php
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

// From here on, WordPress is loaded and the user is authenticated.
// Your app can use WP functions (e.g., get_current_user_id()) and REST.

?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>WebApp</title>
  </head>
  <body>
    <noscript>This application requires JavaScript.</noscript>
    <div id="app-root">WebApp starter is installed. User ID: <?php echo (int) get_current_user_id(); ?></div>
  </body>
  </html>


