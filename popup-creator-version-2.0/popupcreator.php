<?php

/* 
Plugin Name: PopupCreator Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Companion plugin for the demo theme
Version: 2.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: popupcreator
*/

function popupcreator_assets() {
    wp_enqueue_style( 'popcreator-modal', plugin_dir_path( __FILE__ )."assets/css/modal.css", null, time() );
    wp_enqueue_script( 'plain-modal-main', plugin_dir_url( __FILE__ )."assets/js/plain-modal-main.js", null, '1.0.34', true );
    wp_enqueue_script( 'popupcreator-main', plugin_dir_path( __FILE__ )."assets/js/popupcreator-main.js", array(
        'jquery',
        'plainmodal-js'
    ), time(), true );
}
add_action( 'wp_enqueue_scripts', 'popupcreator_assets' );



function popupcreator_register_my_cpts_popup() {

        /**
         * Post Type: Popups.
         */

        $labels = [
            "name" => __( "Popups", "philosophy" ),
            "singular_name" => __( "Popup", "philosophy" ),
            "featured_image" => __( "Popup Image", "philosophy" ),
            "set_featured_image" => __( "Set Popup Image", "philosophy" ),
            "remove_featured_image" => __( "Remove Popup Image", "philosophy" ),
        ];

        $args = [
            "label" => __( "Popups", "philosophy" ),
            "labels" => $labels,
            "description" => "",
            "public" => false,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "rest_namespace" => "wp/v2",
            "has_archive" => false,
            "show_in_menu" => true,
            "show_in_nav_menus" => false,
            "delete_with_user" => false,
            "exclude_from_search" => true,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "can_export" => false,
            "rewrite" => [ "slug" => "popup", "with_front" => true ],
            "query_var" => true,
            "menu_position" => 4,
            "menu_icon" => "dashicons-cover-image",
            "supports" => [ "title", "thumbnail" ],
            "show_in_graphql" => false,
        ];

        register_post_type( "popup", $args );
    }

add_action( 'init', 'popupcreator_register_my_cpts_popup' );


// image size
function register_popup_size() {
    add_image_size( 'popup-landscape', 600, 800, true );
    add_image_size( 'popup-square', 500, 500, true );
}
add_action( 'init', 'register_popup_size' );


// modal markup
function print_modal_markup() {
    ?>
        <div id="modal-content">
                <div><img id="close-button" width="30"
                src="<?php echo plugin_dir_url(__FILE__)."/assets/img/close.jpg" ?>" alt="<?php _e( 'Close', 'popupcreator' ); ?>" >
            </div>
            <img src="https://d30itml3t0pwpf.cloudfront.net/wp-content/uploads/sites/3/2018/06/ecommerce-flash-sale-popup-mobile-.jpg" alt="">
        </div>

    <?php
}
add_action( 'wp_footer', 'print_modal_markup' );

