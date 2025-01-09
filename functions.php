<?php
if (!defined('ABSPATH')) {
    exit;
}

function add_security_headers() {
    header("X-Frame-Options: SAMEORIGIN");
    header("X-XSS-Protection: 1; mode=block");
    header("X-Content-Type-Options: nosniff");
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline';");
}
add_action('send_headers', 'add_security_headers');

//Shortcode to use the React Routing
//It can to be used with BrowserRoute or HashRoute
function custom_auth_shortcode_react() {

    if ( !is_user_logged_in() ) {
        wp_safe_redirect( wp_login_url() );
        exit;
    } else {
        $file_path = get_template_directory() . '/app/dist/index.html';
        if ( file_exists($file_path) && is_readable($file_path) ) {
            ob_start();
            include($file_path);
            return ob_get_clean();
        } else {
            wp_die('Page not Found', 'Error', array('response' => 404));
        }
    }
}
add_shortcode('react_app', 'custom_auth_shortcode_react');

//Shortcode to use the WP Routing
//It has to be used just with HashRoute
function custom_auth_shortcode_wp() {
    if ( !is_user_logged_in() ) {
        wp_safe_redirect( wp_login_url() );
        exit;
    } else {
        $page_id = get_the_ID();
        $page = get_post($page_id);
        if ($page) {
            echo apply_filters('the_content', $page->post_content);
        }
    }
}
add_shortcode('auth_wp_routing', 'custom_auth_shortcode_wp');

//Enqueue Styles
function my_theme_enqueue_styles() {
    wp_enqueue_style(
        'my-style-handle',  // Handle for the stylesheet
        get_template_directory_uri() . '/css/sample-page.css',  // Path to the stylesheet
        array(),  // Dependencies (leave empty if none)
        '1.0.0',  // Version number (optional)
        'all'  // Media type (optional, e.g., 'all', 'screen', 'print')
    );
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
