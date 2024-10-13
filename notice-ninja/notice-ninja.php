<?php
/* 
Plugin Name: Notice Ninja Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Demo plugin api
Version: 1.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: notice-ninja
*/


function nn_notice() {      // global $pagenow tells current page
    global $pagenow;

    if(!( isset( $_COOKIE['nn-close'] ) && $_COOKIE['nn-close'] == 1 ) ) {
        if( in_array( $pagenow, ['index.php', 'plugins.php'] ) ) {

            $remote_data = wp_remote_get( '' );
            $remote_body = wp_remote_retrieve_body( $remote_data );
            if ( $remote_body != '' ) {
                ?>
                <div id="noticeninja" class="notice notice-success is-dismissible">
                    <p>Hey, this is some information for you. <?php echo $pagenow; ?></p>
                    <p> <?php echo $remote_body; ?> </p>
                </div>
                <?php
            } else {
                ?>
                <div id="noticeninja" class="notice notice-success is-dismissible">
                    </div>
                    <p>Hey, this is some information for you.</p>
                <?php
            }
        }
    }
}
add_action( 'admin_notices', 'nn_notice' );



add_action( 'admin_enqueue_scripts', function () {
    wp_enqueue_script( 'noticeninja-js', plugin_dir_url( __FILE__ ). 'assets/js/script.js', array( 'jquery' ), time(), true );
} );