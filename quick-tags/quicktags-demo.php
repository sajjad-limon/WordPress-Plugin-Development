<?php

/*
Plugin Name: Quick Tags Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Metabox api demo
Version: 1.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: quick-tags
Domain Path: /languages/
 */

function quicktd_load_assets() {
    wp_enqueue_script( 'qt-main', plugin_dir_url( __FILE__ ) . '/assets/js/qt.js', array( 'quicktags' ), time(), true );
}
add_action( 'load-post.php', 'quicktd_load_assets' );