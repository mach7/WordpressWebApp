<?php
defined('ABSPATH') || exit;

require_once __DIR__ . '/roles.php';

add_action('init', function () {
    // Placeholder for initialization that must run early
});

/**
 * Register plugin roles and capabilities
 */
function webapp_register_roles(): void
{
    // Example role for app users with limited capabilities
    add_role(
        'webapp_user',
        __('WebApp User', 'webapp'),
        [
            'read' => true,
        ]
    );
}


