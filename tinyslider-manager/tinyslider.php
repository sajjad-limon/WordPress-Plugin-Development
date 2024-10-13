<?php 

/* 
Plugin Name: Tiny Slider Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Best plugin for slide images.
Version: 1.0
Author: Sajjad Hossen
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: tinyslider
Domain Path: /languages/
*/

// load textdomain
function tinys_load_textdomain() {
    load_plugin_textdomain( 'tinyslider', false, dirname(__FILE__). "/languages" );
}
add_action( 'plugins_loaded', 'tinys_load_textdomain' );


// image size
function tinys_init() {
    add_image_size( 'tiny-slider', 800, 600, true );
}
add_action( 'init', 'tinys_init' );


// load assets
function tinys_assets () {
    wp_enqueue_style( 'tinyslider-css', '//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.8.4/tiny-slider.css', null, '1.0' );
    wp_enqueue_script( 'tinyslider-js', '//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.8.4/min/tiny-slider.js', null, '1.0', true );

    wp_enqueue_script( 'tinyslider-main-js', plugin_dir_url( __FILE__ )."assets/js/main.js", array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'tinys_assets' );


// image slider shortcode
function tinys_shortcode_slider ( $arguments, $content ) {
    $defaults = array(
        'width'     => 800, 
        'height'    => 600, 
        'id'        => '',
    );

    $attributes = shortcode_atts( $defaults, $arguments );
    $content    = do_shortcode( $content );

    $slider = <<<HEREDOC

    <div id="{$attributes['id']}" style="width: {$attributes['width']}; height: {$attributes['height']}" >
        <div class="slider">
            {$content}
        </div>
    </div>

HEREDOC;

    return $slider;
}
add_shortcode( 'tslider', 'tinys_shortcode_slider' );



// image slides
function tinys_shortcode_slide( $arguments ) {
    $defaults = array(
        'caption'   => '',
        'id'        => '',
        'size'      => 'tiny-slider',
    );

    $attributes = shortcode_atts( $defaults, $arguments );

    $image_src = wp_get_attachment_image_src( $attributes['id'], $attributes['size'] );

    $slide = <<<HEREDOC
    <div class='slide'>
        <p><img src="{$image_src[0]}" alt="{$attributes['caption']}"></p>
        <p>{$attributes['caption']}</p>
    </div>

HEREDOC;

return $slide;
}
add_shortcode( 'tslide', 'tinys_shortcode_slide' );