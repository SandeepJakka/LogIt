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
add_action('admin_notices', function () {
    echo '<div class="notice notice-success"><p>LogIt plugin is active.</p></div>';
});

