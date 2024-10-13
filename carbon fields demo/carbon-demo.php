<?php
/*
Plugin Name: Carbon Fields Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Demo plugin api
Version: 1.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: carbonfd
 */

use Carbon_Fields\Block;
use Carbon_Fields\Field;

/* function carbonfd_load() {
require_once( 'carbon-fields/vendor/autoload.php' );
\Carbon_Fields\Carbon_Fields::boot();
}
add_action( 'plugins_loaded', 'carbonfd_load' ); */

add_action( 'enqueue_block_assets', function ( $hook ) {
    wp_enqueue_style(
        'carbonfd-style-css',
        plugins_url( 'assets/css/carbon.css', __FILE__ )
    );
} );

function carbonfd_theme_options() {

    Block::make( __( 'Text And Image' ) )
    //->set_editor_style( 'carbonfd-style-css' )
        ->set_description( __( 'This is the carbon description.' ) )
        ->set_category( 'carbon', __( 'Carbon Field' ), 'superhero' )
        ->set_icon( 'superhero' )
        ->set_preview_mode( true )

        ->set_keywords( array( __( 'carbon' ), __( 'image' ), __( 'text and image' ) ) )
        ->add_fields( array(
            Field::make( 'text', 'heading', __( 'Block Heading' ) ),
            Field::make( 'image', 'image', __( 'Block image' ) ),
            Field::make( 'text', 'content', __( 'Block Content' ) ),
        ) )
        ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
            ?>
            <div class="block">

                <div class="block__heading">
                    <h1><?php echo esc_html( $fields['heading'] ); ?></h1>
                </div>

                <div class="block__image">
                    <?php echo wp_get_attachment_image( $fields['image'], 'medium' ); ?>
                </div>

                <div class="block__content">
                    <?php echo apply_filters( 'the_content', $fields['content'] ); ?>
                </div>
            </div>
        <?php

        } );

}
add_action( 'carbon_fields_register_fields', 'carbonfd_theme_options' );