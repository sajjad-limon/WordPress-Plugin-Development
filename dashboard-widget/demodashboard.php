<?php
/*
Plugin Name: DashboardWidget Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Demo plugin api
Version: 1.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: dashboardwidget
 */
function dbw_load_text_domain() {
    load_plugin_textdomain( 'dashboardwidget', false, plugin_dir_path( __FILE__ ) . "languages/" );
}
add_action( 'plugins_loaded', 'dbw_load_text_domain' );

//add dashboard widget
function dbw_dashboard_widget() {

//only admin or editor manage settings
    if ( current_user_can( 'edit_dashboard' ) ) {
        wp_add_dashboard_widget(
            'demodashboard',
            __( 'Dashboard Widget', 'dashboardwidget' ),
            'ddw_dashboard_display',
            'dbw_dashboard_control',
        );
    } else {
        wp_add_dashboard_widget(
            'demodashboard',
            __( 'Dashboard Widget', 'dashboardwidget' ),
            'ddw_dashboard_display',
        );
    }

}
add_action( 'wp_dashboard_setup', 'dbw_dashboard_widget' );

// widget output
function ddw_dashboard_display() {
    //echo wp_dashboard_browser_nag();

    $number_of_posts = get_option( 'dashboard_nop' );
    $feeds = array(
        array(
            'url'          => 'https://wptavern.com/feed',
            'items'        => $number_of_posts,
            'show_summary' => 0,
            'show_author'  => 1,
            'show_date'    => 1,
        ),
    );
    wp_dashboard_primary_output( 'demodashboard', $feeds );
}

// manage settings
function dbw_dashboard_control() {
    $number_of_posts = get_option( 'dashboard_nop', 5 );

    if ( isset( $_POST['dbw_nop'] ) && $_POST['dbw_nop'] > 0 ) {
        $number_of_posts = sanitize_text_field( $_POST['dbw_nop'] );
        
        update_option( 'dashboard_nop', $number_of_posts );
    }

    ?>
    <p>
    <label for=""> Number of posts: </label> <br>
    <input type="text" name="dbw_nop" id="dbw_nop" value="<?php echo $number_of_posts; ?>">
    </p>
    <?php
}
