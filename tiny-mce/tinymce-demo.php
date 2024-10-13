<?php
/*
Plugin Name: TinyMCE Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Demo Plugin for various TinyMCE related tasks
Version: 1.0
Author: Sajjad Hossen
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: tinymce-demo
Domain Path: /languages/
*/


function tmcd_mce_external_plugins($plugins){
	$plugins['tmcd_plugin'] = plugin_dir_url(__FILE__)."assets/js/tinymce.js";
	return $plugins;
}

function tmcd_mce_buttons($buttons){
	$buttons[] = 'tmcd_button_one';
	$buttons[] = 'tmcd_button_two';
	return $buttons;
}


function tmcd_admin_assets(){
	add_filter('mce_external_plugins','tmcd_mce_external_plugins');
	add_filter('mce_buttons','tmcd_mce_buttons');
}
add_action('admin_init','tmcd_admin_assets');
