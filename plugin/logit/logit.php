<?php
/**
 * Plugin Name: LogIt
 * Description: Core plugin for the LogIt dashboard.
 * Version: 0.1.0
 * Author: Sandeep Jakka
 */

if (!defined('ABSPATH')) {
    exit;
}
add_action('admin_menu', 'logit_add_admin_menu');

function logit_add_admin_menu()
{
    add_menu_page(
        'LogIt Dashboard',   // Page title
        'LogIt',             // Menu title
        'manage_options',    // Capability
        'logit-dashboard',   // Menu slug
        'logit_render_dashboard', // Callback
        'dashicons-chart-area',   // Icon
        25                   // Position
    );
}
function logit_render_dashboard()
{
    ?>
        <div class="wrap">
            <h1>LogIt Dashboard</h1>
            <p>Welcome to LogIt. Your activity will appear here.</p>
        </div>
        <?php
}


