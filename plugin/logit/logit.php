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

    $count = wp_count_posts('project');
    $total_projects = $count ? $count->publish : 0;

    ?>
    <div class="wrap">
        <h1>LogIt Dashboard</h1>

        <h2>Total Projects</h2>
        <p><?php echo esc_html($total_projects); ?></p>
    </div>
    <?php
}


add_action('init', 'logit_register_projects');

function logit_register_projects()
{
    register_post_type('project', [
        'labels' => [
            'name' => 'Projects',
            'singular_name' => 'Project',
            'add_new_item' => 'Add New Project',
            'edit_item' => 'Edit Project',
        ],
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-clipboard',
        'supports' => ['title', 'editor'],
        'show_in_rest' => true,
    ]);
}


