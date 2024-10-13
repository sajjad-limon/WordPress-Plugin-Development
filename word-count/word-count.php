<?php

/* 
Plugin Name: Word Count Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Best plugin for your webpages word count.
Version: 1.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: word-count
Domain Path: /languages/
*/

/* function wordcount_activation_hook () {}
register_activation_hook( __FILE__, 'wordcount_activation_hook' );

function wordcount_deactivation_hook () {}
register_deactivation_hook( __FILE__, 'wordcount_deactivation_hook' ); */

function wordcount_load_textdomain() {
    load_plugin_textdomain( 'word-count', false, dirname(__FILE__). '/languages' );
}
add_action( 'plugins_loaded', 'wordcount_load_textdomain' );


function wordcount_word_count( $content ) {

    $stripped_content   = strip_tags( $content );
    $wordcount          = str_word_count( $stripped_content );
    $label              = __( 'Total numbers of words', 'word-count' );
    $label              = apply_filters( 'wordcount_heading', $label );
    $tags               = apply_filters( 'wordcount_tags', 'h3' );

    $content           .= sprintf( '<%s>%s: %s</%s>', $tags, $label, $wordcount, $tags );
    return $content;
}
add_filter( 'the_content', 'wordcount_word_count' );


function wordcount_reading_word( $content ){
    $stripped_content   = strip_tags( $content );
    $wordcount          = str_word_count( $stripped_content );
    $reading_minutes    = floor( $wordcount / 200 );
    $reading_seconds    = floor( $wordcount % 200 / ( 200 / 60 ) );
    $is_visible         = apply_filters( 'wordcount_display_readingtime', 0 );
    
    if( $is_visible ) {
        $label              = __( 'Total times of reading', 'word-count' );
        $label              = apply_filters( 'wordcount_reading_heading', $label );
        $tags               = apply_filters( 'wordcount_reading_tags', 'h4' );

        $content           .= sprintf( '<%s>%s: %s minutes %s seconds </%s>', $tags, $label, $reading_minutes, $reading_seconds, $tags );
    }
    return $content;
}
add_filter( 'the_content', 'wordcount_reading_word' );