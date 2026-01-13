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

/* --------------------------------------------------
 * ADMIN MENU
 * -------------------------------------------------- */
add_action('admin_menu', 'logit_add_admin_menu');

function logit_add_admin_menu()
{
    add_menu_page(
        'LogIt Dashboard',
        'LogIt',
        'manage_options',
        'logit-dashboard',
        'logit_render_dashboard',
        'dashicons-chart-area',
        25
    );
}

add_action('admin_enqueue_scripts', 'logit_admin_styles');

function logit_admin_styles()
{
    wp_add_inline_style(
        'wp-admin',
        '
        .logit-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .logit-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        }

        .logit-card h3 {
            margin-top: 0;
        }

        .logit-section {
            margin-top: 40px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
        }
        '
    );
}


/* --------------------------------------------------
 * DASHBOARD PAGE
 * -------------------------------------------------- */
function logit_render_dashboard()
{

    // Total projects
    $project_count = wp_count_posts('project');
    $total_projects = $project_count ? $project_count->publish : 0;

    // Active projects
    $active_projects = new WP_Query([
        'post_type' => 'project',
        'meta_key' => '_logit_status',
        'meta_value' => 'active',
        'posts_per_page' => -1,
    ]);

    // Learning logs count
    $log_count = wp_count_posts('learning_log');
    $total_logs = $log_count ? $log_count->publish : 0;

    // Recent activity
    $recent_projects = get_posts([
        'post_type' => 'project',
        'posts_per_page' => 3,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    $recent_logs = get_posts([
        'post_type' => 'learning_log',
        'posts_per_page' => 3,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    ?>

    <div class="wrap">
        <h1>LogIt Dashboard</h1>

        <!-- Metric Cards -->
        <div class="logit-cards">
            <div class="logit-card">
                <h3>Total Projects</h3>
                <p><?php echo esc_html($total_projects); ?></p>
            </div>

            <div class="logit-card">
                <h3>Active Projects</h3>
                <p><?php echo esc_html($active_projects->found_posts); ?></p>
            </div>

            <div class="logit-card">
                <h3>Learning Logs</h3>
                <p><?php echo esc_html($total_logs); ?></p>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="logit-section">
            <h2>Recent Activity</h2>
            <ul>
                <?php foreach ($recent_projects as $project): ?>
                    <li>
                        📁 Project: <?php echo esc_html($project->post_title); ?>
                        <small style="color:#666;">
                            — <?php echo human_time_diff(
                                get_the_time('U', $project),
                                current_time('timestamp')
                            ); ?> ago
                        </small>
                    </li>

                <?php endforeach; ?>

                <?php foreach ($recent_logs as $log): ?>
                    <li>
                        📘 Learning: <?php echo esc_html($log->post_title); ?>
                        <small style="color:#666;">
                            — <?php echo human_time_diff(
                                get_the_time('U', $log),
                                current_time('timestamp')
                            ); ?> ago
                        </small>
                    </li>

                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <?php
    wp_reset_postdata();
}

/* --------------------------------------------------
 * PROJECTS (CPT)
 * -------------------------------------------------- */
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

/* --------------------------------------------------
 * PROJECT STATUS META
 * -------------------------------------------------- */
add_action('add_meta_boxes', 'logit_add_project_status_box');

function logit_add_project_status_box()
{
    add_meta_box(
        'logit_project_status',
        'Project Status',
        'logit_render_project_status_box',
        'project',
        'side'
    );
}

function logit_render_project_status_box($post)
{
    $status = get_post_meta($post->ID, '_logit_status', true);
    ?>
    <select name="logit_project_status" style="width:100%">
        <option value="active" <?php selected($status, 'active'); ?>>Active</option>
        <option value="paused" <?php selected($status, 'paused'); ?>>Paused</option>
        <option value="completed" <?php selected($status, 'completed'); ?>>Completed</option>
    </select>
    <?php
}

add_action('save_post_project', 'logit_save_project_status');

function logit_save_project_status($post_id)
{
    if (!isset($_POST['logit_project_status'])) {
        return;
    }

    update_post_meta(
        $post_id,
        '_logit_status',
        sanitize_text_field($_POST['logit_project_status'])
    );
}

/* --------------------------------------------------
 * LEARNING LOGS (CPT)
 * -------------------------------------------------- */
add_action('init', 'logit_register_learning_logs');

function logit_register_learning_logs()
{
    register_post_type('learning_log', [
        'labels' => [
            'name' => 'Learning Logs',
            'singular_name' => 'Learning Log',
            'add_new_item' => 'Add Learning Log',
            'edit_item' => 'Edit Learning Log',
        ],
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-welcome-learn-more',
        'supports' => ['title', 'editor'],
        'show_in_rest' => true,
    ]);
}
