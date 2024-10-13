<?php
/*
Plugin Name: Assets Ninja Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Best plugin for slide images.
Version: 1.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: assets-ninja
Domain Path: /languages/
 */

define( 'ASN_DIR', plugin_dir_url( __FILE__ ) );
define( 'ASN_PUBLIC_DIR', plugin_dir_url( __FILE__ ) . '/assets/public/' );
define( 'ASN_ADMIN_DIR', plugin_dir_url( __FILE__ ) . '/assets/admin/' );

class AssetsNinja {

    function __construct() {
        add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'asn_load_public_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'asn_load_admin_assets' ) );
    }

    function load_text_domain() {
        load_plugin_textdomain( 'assets-ninja', false, ASN_DIR . '/languages' );
    }

    // back end or admin assets
    function asn_load_admin_assets( $screen ) {

        $_screen = get_current_screen();

        if ( 'edit.php' == $screen && 'page' == $_screen->post_type || 'book'== $_screen->post_type ) {
            wp_enqueue_script( 'asn-admin', ASN_ADMIN_DIR . 'js/admin.js', array( 'jquery' ), time(), true );
        }


        /* if('edit-tags.php' == $screen && 'language'==$_screen->taxonomy && 'book'== $_screen->post_type){
            wp_enqueue_script( 'asn-admin', ASN_ADMIN_DIR . 'js/admin.js', array( 'jquery' ), time(), true );
        } */

    }

    // front end assets
    function asn_load_public_assets() {
        wp_enqueue_script( 'asn-main', ASN_PUBLIC_DIR . 'js/main.js', array( 'jquery', 'asn-another' ), time(), true );
        wp_enqueue_script( 'asn-another', ASN_PUBLIC_DIR . 'js/another.js', array( 'jquery', 'asn-extra' ), time(), true );
        wp_enqueue_script( 'asn-extra', ASN_PUBLIC_DIR . 'js/extra.js', array( 'jquery' ), time(), true );

        // sending data to js
        $data = array(
            'name'  => 'Limon Hossain',
            'email' => 'limonhossain6778@gmail.com',
        );

        $user_data = array(
            'name'  => 'John Doe',
            'email' => 'johnhos6@gmail.com',
        );

        $translated_string = array(
            'greet' => __( 'Hello from Extra.js Here again' ),
        );

        wp_localize_script( 'asn-extra', 'sitedata', $data );
        wp_localize_script( 'asn-extra', 'userdata', $user_data );
        wp_localize_script( 'asn-extra', 'translatedstring', $translated_string );
    }

}

new AssetsNinja();