<?php

/*
Plugin Name: Options Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Metabox api demo
Version: 1.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: optionsdemo
Domain Path: /languages/
 */

class OptionsDemo_Settings_Page {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'optionsdemo_create_settings' ) );
        add_action( 'admin_init', array( $this, 'optionsdemo_setup_sections' ) );
        add_action( 'admin_init', array( $this, 'optionsdemo_setup_fields' ) );
        add_action( 'plugins_loaded', array( $this, 'optionsdemo_load_text_domain' ) );

        //action links
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'optionsdemo_action_links' ) );
        add_filter( 'plugin_row_meta', array( $this, 'optionsdemo_row_links' ), 10, 2 );
    }

    // load textdomain
    function optionsdemo_load_text_domain() {
        load_plugin_textdomain( 'opotionsdemo', false, dirname( __FILE__ ) . "/languages" );
    }

    public function optionsdemo_create_settings() {
        $page_title = __( 'Options Demo', 'optionsdemo' );
        $menu_title = __( 'Options Demo', 'optionsdemo' );
        $capability = 'manage_options';
        $slug = 'optionsdemo';
        $callback = array( $this, 'optionsdemo_settings_content' );
        add_options_page( $page_title, $menu_title, $capability, $slug, $callback );
    }

    public function optionsdemo_settings_content() {?>
        <div class="wrap">
            <h1>Options Demo</h1>
            <form method="POST" action="options.php">
				<?php
settings_fields( 'optionsdemo' );
        do_settings_sections( 'optionsdemo' );
        submit_button();
        ?>
            </form>
        </div> <?php
}

    public function optionsdemo_setup_sections() {
        add_settings_section( 'optionsdemo_section', __( 'Demonstration of plugin settings page', 'optionsdemo' ), array(), 'optionsdemo' );
    }

    public function optionsdemo_setup_fields() {
        $fields = array(
            array(
                'label'       => __( 'Latitude', 'optionsdemo' ),
                'id'          => 'optionsdemo_latitude',
                'type'        => 'text',
                'section'     => 'optionsdemo_section',
                'desc'        => 'Enter maps latitude',
                'placeholder' => __( 'Latitude', 'optionsdemo' ),
            ),
            array(
                'label'       => __( 'Longitude', 'optionsdemo' ),
                'id'          => 'optionsdemo_longitude',
                'type'        => 'text',
                'section'     => 'optionsdemo_section',
                'desc'        => 'Enter maps longitude',
                'placeholder' => __( 'Longitude', 'optionsdemo' ),
            ),
            array(
                'label'   => __( 'Zoom Level', 'optionsdemo' ),
                'id'      => 'optionsdemo_zoomlevel',
                'type'    => 'text',
                'section' => 'optionsdemo_section',
            ),
            array(
                'label'   => __( 'API Key', 'optionsdemo' ),
                'id'      => 'optionsdemo_apikey',
                'type'    => 'text',
                'section' => 'optionsdemo_section',
            ),
            array(
                'label'   => __( 'External CSS', 'optionsdemo' ),
                'id'      => 'optionsdemo_externalcss',
                'type'    => 'textarea',
                'section' => 'optionsdemo_section',
            ),
            array(
                'label'   => __( 'Expiry Date', 'optionsdemo' ),
                'id'      => 'optionsdemo_expirydate',
                'type'    => 'date',
                'section' => 'optionsdemo_section',
            ),
        );

        foreach ( $fields as $field ) {
            add_settings_field( $field['id'], $field['label'], array(
                $this,
                'optionsdemo_field_callback',
            ), 'optionsdemo', $field['section'], $field );
            register_setting( 'optionsdemo', $field['id'] );
        }

    }

    public function optionsdemo_field_callback( $field ) {
        $value = get_option( $field['id'] );

        switch ( $field['type'] ) {
        case 'textarea':
            printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>',
                $field['id'],
                isset( $field['placeholder'] ) ? $field['placeholder'] : '',
                $value
            );
            break;
        default:
            printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
                $field['id'],
                $field['type'],
                isset( $field['placeholder'] ) ? $field['placeholder'] : '',
                $value
            );
        }

        if ( isset( $field['desc'] ) ) {

            if ( $desc = $field['desc'] ) {
                printf( '<p class="description">%s </p>', $desc );
            }

        }

    }

    //action links
    function optionsdemo_action_links( $links ) {
        $new_link = sprintf( "<a style='color:red' href='%s'>%s</a>", 'options-general.php?page=optionsdemo', __( 'Settings' ) );
        $links[] = $new_link;

        return $links;
    }

    function optionsdemo_row_links( $links, $plugin ) {

        if ( plugin_basename( __FILE__ ) == $plugin ) {
            $link = sprintf( "<a style='color:crimson' target='_blank' href='%s'> %s</a>", 'https://github.com/simondevyoutube', _( 'Fork on Github' ) );
            array_push( $links, $link );
        }
        return $links;
    }

}

new Optionsdemo_Settings_Page();
