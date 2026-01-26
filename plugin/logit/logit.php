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

/* --------------------------------------------------
 * TASKS (CPT)
 * -------------------------------------------------- */
add_action('init', 'logit_register_tasks');

function logit_register_tasks()
{
    register_post_type('task', [
        'labels' => [
            'name' => 'Tasks',
            'singular_name' => 'Task',
            'add_new_item' => 'Add New Task',
            'edit_item' => 'Edit Task',
        ],
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-list-view',
        'supports' => ['title', 'editor'],
        'capability_type' => 'post',
        'show_in_rest' => true,
    ]);
}

/* --------------------------------------------------
 * TASK META BOX
 * -------------------------------------------------- */
add_action('add_meta_boxes', 'logit_add_task_meta_box');

function logit_add_task_meta_box()
{
    add_meta_box(
        'logit_task_details',
        'Task Details',
        'logit_render_task_meta_box',
        'task',
        'normal',
        'high'
    );
}

function logit_render_task_meta_box($post)
{
    wp_nonce_field('logit_task_meta_nonce_action', 'logit_task_meta_nonce');

    $priority = get_post_meta($post->ID, '_logit_task_priority', true);
    $status = get_post_meta($post->ID, '_logit_task_status', true);
    $due_date = get_post_meta($post->ID, '_logit_task_due_date', true);
    $linked_project = get_post_meta($post->ID, '_logit_task_linked_project', true);

    $projects = get_posts([
        'post_type' => 'project',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ]);
    ?>

    <table class="form-table">
        <tr>
            <th><label for="logit_task_priority">Priority</label></th>
            <td>
                <select name="logit_task_priority" id="logit_task_priority" style="width:100%;">
                    <option value="">— Select Priority —</option>
                    <option value="critical" <?php selected($priority, 'critical'); ?>>Critical</option>
                    <option value="high" <?php selected($priority, 'high'); ?>>High</option>
                    <option value="medium" <?php selected($priority, 'medium'); ?>>Medium</option>
                    <option value="low" <?php selected($priority, 'low'); ?>>Low</option>
                    <option value="backlog" <?php selected($priority, 'backlog'); ?>>Backlog</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="logit_task_status">Status</label></th>
            <td>
                <select name="logit_task_status" id="logit_task_status" style="width:100%;">
                    <option value="">— Select Status —</option>
                    <option value="active" <?php selected($status, 'active'); ?>>Active</option>
                    <option value="completed" <?php selected($status, 'completed'); ?>>Completed</option>
                    <option value="archived" <?php selected($status, 'archived'); ?>>Archived</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="logit_task_due_date">Due Date</label></th>
            <td>
                <input type="date" name="logit_task_due_date" id="logit_task_due_date"
                    value="<?php echo esc_attr($due_date); ?>" style="width:100%;">
            </td>
        </tr>
        <tr>
            <th><label for="logit_task_linked_project">Linked Project</label></th>
            <td>
                <select name="logit_task_linked_project" id="logit_task_linked_project" style="width:100%;">
                    <option value="">— Select Project —</option>
                    <?php foreach ($projects as $project): ?>
                        <option value="<?php echo esc_attr($project->ID); ?>" <?php selected($linked_project, $project->ID); ?>>
                            <?php echo esc_html($project->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>

    <?php
}
/* --------------------------------------------------
 * SAVE TASK META
 * -------------------------------------------------- */
add_action('save_post_task', 'logit_save_task_meta');

function logit_save_task_meta($post_id)
{
    if (!isset($_POST['logit_task_meta_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['logit_task_meta_nonce'], 'logit_task_meta_nonce_action')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['logit_task_priority'])) {
        update_post_meta(
            $post_id,
            '_logit_task_priority',
            sanitize_text_field($_POST['logit_task_priority'])
        );
    }

    if (isset($_POST['logit_task_status'])) {
        update_post_meta(
            $post_id,
            '_logit_task_status',
            sanitize_text_field($_POST['logit_task_status'])
        );
    }

    if (isset($_POST['logit_task_due_date'])) {
        update_post_meta(
            $post_id,
            '_logit_task_due_date',
            sanitize_text_field($_POST['logit_task_due_date'])
        );
    }

    if (isset($_POST['logit_task_linked_project'])) {
        update_post_meta(
            $post_id,
            '_logit_task_project_id',
            absint($_POST['logit_task_linked_project'])
        );
    }
}
/* --------------------------------------------------
 * TASK ADMIN COLUMNS
 * -------------------------------------------------- */
add_filter('manage_task_posts_columns', 'logit_task_columns');

function logit_task_columns($columns)
{
    $new_columns = [];
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['priority'] = 'Priority';
            $new_columns['task_status'] = 'Status';
            $new_columns['due_date'] = 'Due Date';
            $new_columns['project'] = 'Project';
        }
    }
    return $new_columns;
}

add_action('manage_task_posts_custom_column', 'logit_task_column_content', 10, 2);

function logit_task_column_content($column, $post_id)
{
    switch ($column) {
        case 'priority':
            $priority = get_post_meta($post_id, '_logit_task_priority', true);
            $priority_labels = [
                'critical' => 'Critical',
                'high' => 'High',
                'medium' => 'Medium',
                'low' => 'Low',
                'backlog' => 'Backlog',
            ];
            echo esc_html($priority_labels[$priority] ?? '—');
            break;

        case 'task_status':
            $status = get_post_meta($post_id, '_logit_task_status', true);
            $status_labels = [
                'active' => 'Active',
                'completed' => 'Completed',
                'archived' => 'Archived',
            ];
            echo esc_html($status_labels[$status] ?? '—');
            break;

        case 'due_date':
            $due_date = get_post_meta($post_id, '_logit_task_due_date', true);
            if ($due_date) {
                echo esc_html(date_i18n(get_option('date_format'), strtotime($due_date)));
            } else {
                echo '—';
            }
            break;

        case 'project':
            $project_id = get_post_meta($post_id, '_logit_task_project_id', true);
            if ($project_id) {
                $project = get_post($project_id);
                if ($project && $project->post_status === 'publish') {
                    echo esc_html($project->post_title);
                } else {
                    echo '—';
                }
            } else {
                echo '—';
            }
            break;
    }
}
/* --------------------------------------------------
 * ACTIVITY (CPT)
 * -------------------------------------------------- */
add_action('init', 'logit_register_activity');

function logit_register_activity()
{
    register_post_type('activity', [
        'labels' => [
            'name' => 'Activity',
            'singular_name' => 'Activity',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'supports' => ['title'],
        'capability_type' => 'post',
    ]);
}
/* --------------------------------------------------
 * LOG ACTIVITY ON TASK CREATION
 * -------------------------------------------------- */
add_action('transition_post_status', 'logit_log_task_created', 10, 3);

function logit_log_task_created($new_status, $old_status, $post)
{

    // Only for tasks
    if ($post->post_type !== 'task') {
        return;
    }

    // Only when first published
    if ($old_status === 'publish' || $new_status !== 'publish') {
        return;
    }

    // Prevent duplicate activity
    $existing = get_posts([
        'post_type' => 'activity',
        'meta_key' => '_logit_related_task_id',
        'meta_value' => $post->ID,
        'posts_per_page' => 1,
    ]);

    if (!empty($existing)) {
        return;
    }

    // Create activity
    wp_insert_post([
        'post_type' => 'activity',
        'post_status' => 'publish',
        'post_title' => 'Task created: ' . $post->post_title,
        'meta_input' => [
            '_logit_related_task_id' => $post->ID,
            '_logit_activity_type' => 'task_created',
        ],
    ]);
}
add_action('updated_post_meta', 'logit_log_task_completed', 10, 4);

function logit_log_task_completed($meta_id, $post_id, $meta_key, $meta_value)
{

    // Only watch task status changes
    if ($meta_key !== '_logit_task_status') {
        return;
    }

    // Only for tasks
    if (get_post_type($post_id) !== 'task') {
        return;
    }

    // Only when status becomes completed
    if ($meta_value !== 'completed') {
        return;
    }

    // Prevent duplicate "completed" activity
    $existing = get_posts([
        'post_type' => 'activity',
        'meta_query' => [
            [
                'key' => '_logit_related_task_id',
                'value' => $post_id,
            ],
            [
                'key' => '_logit_activity_type',
                'value' => 'task_completed',
            ],
        ],
        'posts_per_page' => 1,
    ]);

    if (!empty($existing)) {
        return;
    }

    // Create activity
    wp_insert_post([
        'post_type' => 'activity',
        'post_status' => 'publish',
        'post_title' => 'Task completed: ' . get_the_title($post_id),
        'meta_input' => [
            '_logit_related_task_id' => $post_id,
            '_logit_activity_type' => 'task_completed',
        ],
    ]);
}


