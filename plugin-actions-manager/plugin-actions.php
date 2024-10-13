<?php
/* 
Plugin Name: Plugin Action Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Demo plugin api
Version: 1.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: plugin-actions
*/

 
add_action( 'admin_menu', function(){
    add_menu_page( 
        __( 'Action Links', 'plugin-actions' ),
        __( 'Action Links', 'plugin-actions' ),
        'manage_options',
        'action_links',
        function () {
    ?>
        <h1>Hello World!</h1>
    <?php
    } );

} );


// redirect settings page on plugin activation
add_action( 'activated_plugin', function ($plugin) {
    if( plugin_basename( __FILE__ ) == $plugin ) {
        wp_redirect( admin_url( 'admin.php?page=action_links' ) );
        die();
    }
} );


// plugin action links 
add_filter( 'plugin_action_links_'.plugin_basename( __FILE__ ) , function( $links ) {
    $new_link = sprintf( "<a style='color: #ef4110;' href='%s' > %s </a>", admin_url('admin.php?page=action_links' ), __( 'Settings', 'plugin-actions' ) );
    array_push( $links, $new_link );
    return $links;

} );


// plugin row links
add_filter( 'plugin_row_meta', function($links, $plugin){
    if( plugin_basename( __FILE__ ) == $plugin ) {
        $new_link = sprintf( "<a target='_blank' style='color: #ef4110;' href='%s'> %s </a>", esc_url('https://github.com/limonhossain' ), __( 'Fork on Github', 'plugin-actions' ) );
        array_push( $links, $new_link );
    }
    return $links;
}, 10, 2);