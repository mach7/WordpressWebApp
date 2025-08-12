<?php
// Boot WordPress for session, user, DB, and more
$wp_path = dirname(dirname(__DIR__)) . '/wp-load.php';
if (!file_exists($wp_path)) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['error' => 'Cannot find wp-load.php. Make sure this file is in the right directory.']);
    exit;
}
require_once $wp_path;

header('Content-Type: application/json');

// Utility functions
function webapp_json($data, int $status = 200): void {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

function webapp_require_auth(): void {
    if (!is_user_logged_in()) {
        webapp_json(['error' => 'Unauthorized'], 401);
    }
}

// Determine route path relative to this directory (e.g., /webapp/api)
$request_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$script_dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
$route_path = '/' . ltrim(substr($request_path, strlen($script_dir)), '/');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Basic router
if ($method === 'GET' && ($route_path === '/' || $route_path === '')) {
    webapp_json(['name' => 'WebApp API', 'endpoints' => ['/health', '/me']]);
}

if ($method === 'GET' && $route_path === '/health') {
    webapp_json(['status' => 'ok']);
}

if ($method === 'GET' && $route_path === '/me') {
    webapp_require_auth();
    $user_id = get_current_user_id();
    $user = $user_id ? get_user_by('ID', $user_id) : null;
    webapp_json([
        'id'       => $user_id,
        'username' => $user ? $user->user_login : null,
        'email'    => $user ? $user->user_email : null,
    ]);
}

webapp_json(['error' => 'Not Found'], 404);


