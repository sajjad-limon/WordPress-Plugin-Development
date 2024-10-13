<?php
/*
Plugin Name: User Role Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Demonstration of User Role API
Version: 1.0
Author: Sajjad Hossen
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: role-demo
 */

// laod assets
add_action( 'admin_enqueue_scripts', function ( $hook ) {

    if ( 'toplevel_page_role-demo' == $hook ) {
        wp_enqueue_style( 'pure-grid-css', '//unpkg.com/purecss@1.0.1/build/grids-min.css' );
        wp_enqueue_style( 'optionapi-demo-css', plugin_dir_url( __FILE__ ) . "assets/css/style.css", null, time() );
        wp_enqueue_script( 'optionapi-demo-js', plugin_dir_url( __FILE__ ) . "assets/js/main.js", array( 'jquery' ), time(), true );
        $nonce = wp_create_nonce( 'display_result' );
        wp_localize_script(
            'optionapi-demo-js',
            'plugindata',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'nonce' => $nonce )
        );
    }

} );

//create menu page
add_action( 'admin_menu', function () {
    add_menu_page( 'User Role Demo', __( 'User Role Demo' ), 'manage_options', 'role-demo', 'role_demo_admin_page' );
} );

// display action using ajax
add_action( 'wp_ajax_display_result', function () {
    global $wpdb;
    $table_name = $wpdb->prefix . 'peoples';

    if ( wp_verify_nonce( $_POST['nonce'], 'display_result' ) ) {
        $task = $_POST['task'];

        if ( 'current-user-details' == $task ) {

            $user = wp_get_current_user();

            echo $user->user_email. '</br>';
            print_r( $user );
        }

    }

    die( 0 );
} );

// menu page structure
function role_demo_admin_page() {
    ?>
         <div class="container" style="padding-top:20px;">
            <h1>Roles Demo</h1>
            <div class="pure-g">
                <div class="pure-u-1-4" style='height:100vh;'>
                    <div class="plugin-side-options">
                        <button class="action-button" data-task='current-user-details'>Get Current User Details</button>
                        <button class="action-button" data-task='any-user-detail'>Get Any User Details</button>
                        <button class="action-button" data-task='current-role'>Detect Any User Role</button>
                        <button class="action-button" data-task='all-roles'>Get All Roles List</button>
                        <button class="action-button" data-task='current-capabilities'>Current User Capability</button>
                        <button class="action-button" data-task='check-user-cap'>Check User Capability</button>
                        <button class="action-button" data-task='create-user'>Create A New User</button>
                        <button class="action-button" data-task='set-role'>Assign Role To A New User</button>
                        <button class="action-button" data-task='login'>Login As A User</button>
                        <button class="action-button" data-task='users-by-role'>Find All Users From Role</button>
                        <button class="action-button" data-task='change-role'>Change User Role</button>
                        <button class="action-button" data-task='create-role'>Create New Role</button>
                    </div>
                </div>
                <div class="pure-u-3-4">
                    <div class="plugin-demo-content">
                        <h3 class="plugin-result-title">Result</h3>
                        <div id="plugin-demo-result" class="plugin-result"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php
}
