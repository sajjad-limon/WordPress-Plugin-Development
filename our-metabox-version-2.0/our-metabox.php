<?php
/*
Plugin Name: Our Metabox Example
Plugin URI: htpps://github.com/sajjad-limon
Description: Best plugin for create metaboxes.
Version: 2.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: our-metabox
Domain Path: /languages/
 */

class OurMetabox {

    function __construct() {
        add_action( 'plugins_loaded', array( $this, 'omb_load_textdomain' ) );
        add_action( 'load-post.php', array( $this, 'omb_add_metabox' ) );
        add_action( 'save_post', array( $this, 'omb_save_metabox' ) );

        // author meta
        add_filter('user_contactmethods', array($this,'omb_author_contactmethods'));
    }

    // textdomain
    function omb_load_textdomain() {
        load_plugin_textdomain( 'our-metabox', false, plugin_dir_url( __FILE__ ) . '/languages' );
    }


    // author meta 
    function omb_author_contactmethods($methods) {
        $methods['facebook']= __('Facebook');
        $methods['twitter']= __('Twitter');
        $methods['instagram']= __('Instagram');

        return $methods;
    }



    // add metabox
    function omb_add_metabox() {
        add_meta_box(
            'omb_location',
            __( 'Location Info' ),
            array( $this, 'omb_display_metabox' ),
            'post',
            'advanced',
            'high'
        );
    }

    // check nonce, user capability etc
    private function is_secured( $nonce_field, $nonce_action, $post_id ) {
        $nonce_field = isset( $_POST[$nonce_field] ) ? $_POST[$nonce_field] : '';

        if ( $nonce_field == '' ) {
            return false;
        }

        if ( !wp_verify_nonce( $nonce_field, $nonce_action ) ) {
            return false;
        }

        if ( !current_user_can( 'edit_post' ) ) {
            return false;
        }

        if ( wp_is_post_autosave( $post_id ) ) {
            return false;
        }

        if ( wp_is_post_revision( $post_id ) ) {
            return false;
        }

    }

    // save metabox value
    function omb_save_metabox( $post_id ) {

        if ( !array( $this->is_secured( 'omb_location_nonce', 'omb_location_nonce_action', $post_id ) ) ) {
            return $post_id;
        }

        $location = isset( $_POST['omb_location'] ) ? $_POST['omb_location'] : '';
        $country = isset( $_POST['omb_country'] ) ? $_POST['omb_country'] : '';
        $is_favourite = isset( $_POST['omb_is_favourite'] ) ? $_POST['omb_is_favourite'] : 0;
        $colors = isset( $_POST['omb_clr'] ) ? $_POST['omb_clr'] : array();
        $dropdown_colors = isset( $_POST['omb_drop_color'] ) ? $_POST['omb_drop_color'] : '';

        if ( $location == '' || $country == '' ) {
            return $post_id;
        }

        update_post_meta( $post_id, 'omb_location', $location );
        update_post_meta( $post_id, 'omb_country', $country );
        update_post_meta( $post_id, 'omb_is_favourite', $is_favourite );
        update_post_meta( $post_id, 'omb_clr', $colors );
        update_post_meta( $post_id, 'omb_drop_color', $dropdown_colors );
    }

    // metabox field structure
    function omb_display_metabox( $post ) {
        $label = __( 'Location' );
        $label2 = __( 'Country' );
        $label3 = __( 'Is Favourite' );
        $label4 = __( 'Colors' );
        $label5 = __( 'Select Colors' );

        $location_value = get_post_meta( $post->ID, 'omb_location', true );
        $country_value = get_post_meta( $post->ID, 'omb_country', true );
        $is_fav_value = get_post_meta( $post->ID, 'omb_is_favourite', true );
        $saved_colors_value = get_post_meta( $post->ID, 'omb_clr', true );

        $saved_drop_colors_value = get_post_meta( $post->ID, 'omb_drop_color', true );

        // checkbox value
        $checked = $is_fav_value == 1 ? 'checked' : 0;

        //colors array
        $colors = array( 'Red', 'Green', 'Blue', 'Magenta', 'Pink' );

        wp_nonce_field( 'omb_location_nonce_action', 'omb_location_nonce' );

        $metabox_html = <<<HEREDOC
        <p>
        <label for="">{$label}:</label>
            <input type="text" name="omb_location" id="omb_location" value="{$location_value}">
            <br>
            <label for="">{$label2}:</label>
            <input type="text" name="omb_country" id="omb_country" value="{$country_value}">
            <br>
            <label for="">{$label3}:</label>
            <input type="checkbox" name="omb_is_favourite" id="omb_is_favourite" value="1" {$checked}>
            <br>
            <label for="">{$label4}: </label>
        HEREDOC;

        foreach ( $colors as $color ) {
            $checked = in_array( $color, $saved_colors_value ) ? 'checked' : '';
            $metabox_html .= <<<HEREDOC
                <label for="omb_clr_{$color}">{$color} </label>
                <input type="checkbox" name="omb_clr[]" id="omb_clr_{$color}" value="{$color}" {$checked} >
                HEREDOC;
        }

        $metabox_html .= '</p';

        // dropdown
        $dropdown_html = "<option value=''>" . __( 'Pick a color' ) . "</option>";

        foreach ( $colors as $color ) {
            $selected = '';
            if ( $color == $saved_drop_colors_value ) {
                $selected = 'selected';
            }

            $dropdown_html .= sprintf( "<option %s value='%s'> %s </option> ", $selected, $color, $color );
        }

        $metabox_html .= <<<HEREDOC
            <label for="">{$label4}: </label>
            <select name="omb_drop_color" id="omb_drop_color" >
                {$dropdown_html}
            </select>
        HEREDOC;

        echo $metabox_html;
    }

}

new OurMetabox();
