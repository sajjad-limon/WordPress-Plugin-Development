<?php
/*
Plugin Name: Posts to QR Code Example
Plugin URI: htpps://github.com/sajjad-limon
Description: Display QR code under every posts.
Version: 2.0
Author: Sajjad Hossen
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: posts-to-qrcode
Domain Path: /languages/
 */

// load textdomain
function qrcode_load_textdomain() {
    load_plugin_textdomain( 'posts-to-qrcode', false, plugin_basename( __FILE__ ) . '/languages' );
}

add_action( 'plugins_loaded', 'qrcode_load_textdomain' );

// display qr_code
function pqrc_display_qrcode( $content ) {

    $current_post_id = get_the_ID();
    $current_post_title = get_the_title( $current_post_id );
    $current_post_url = get_the_permalink( $current_post_id );
    $current_post_type = get_post_type( $current_post_id );

    // dimension hook
    $height = get_option( 'pqrc_height' );
    $width = get_option( 'pqrc_width' );
    $height = $height ?? '150';
    $width = $width ?? '150';
    $dimension = apply_filters( 'pqrc_qrcode_dimension', '{$width}x{$height}' );

    $img_url = sprintf( 'https://api.qrserver.com/v1/create-qr-code/?size=%s&ecc=L&qzone=1&data=%s', $dimension, $current_post_url );

    // exclude post types
    $excluded_post_types = apply_filters( 'pqrc_excluded_post_types', array() );

// check post types
    if ( in_array( $current_post_type, $excluded_post_types ) ) {
        return $content;
    }

    // image_attributes
    $image_attributes = apply_filters( 'pqrc_img_attributes', null );

    // custom action & filter hook
    do_action( 'pqrc_qrcode' );

    $pqrc_before_text = apply_filters( 'pqrc_before_qrcode', 'QR Code for this post:' );

    $content .= sprintf( " %s <img %s src='%s' alt='%s' />", $pqrc_before_text, $image_attributes, $img_url, $current_post_title );

    return $content;
}

add_filter( 'the_content', 'pqrc_display_qrcode' );

// add settings field for qrcode
function pqrc_settings() {
    add_settings_section( 'pqrc_section', __( 'Posts To QR Code' ), 'pqrc_display_section', 'general' );

    add_settings_field( 'pqrc_height', __( 'QR Code Height', 'posts-to-qrcode' ), 'pqrc_display_field', 'general', 'pqrc_section', ['pqrc_height'] );
    add_settings_field( 'pqrc_width', __( 'QR Code Width', 'posts-to-qrcode' ), 'pqrc_display_field', 'general', 'pqrc_section', ['pqrc_width'] );

    register_setting( 'general', 'pqrc_height', array( 'sanitize_callback' => 'esc_attr' ) );
    register_setting( 'general', 'pqrc_width', array( 'sanitize_callback' => 'esc_attr' ) );
}
    // section
function pqrc_display_section() {
    echo '<p>' . __( 'Settings for Posts to QR Plugin' ) . ' </p>';
}
    // setting field structure
function pqrc_display_field($args){
    $option_value = get_option($args[0]);
    printf( "<input type='text' name='%s' id='%s' value='%s' />", $args[0], $args[0], $option_value );
}

    //not used
function pqrc_display_height() {
    $height = get_option( 'pqrc_height' );
    printf( "<input type='text' name='%s' id='%s' value='%s' />", 'pqrc_height', 'pqrc_height', $height );
}
    //not used
function pqrc_display_width() {
    $width = get_option( 'pqrc_width' );
    printf( "<input type='text' name='%s' id='%s' value='%s' />", 'pqrc_width', 'pqrc_width', $width );
}

add_action( 'admin_init', 'pqrc_settings' );
