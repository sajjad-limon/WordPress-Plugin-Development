<?php

/*
Plugin Name: Our Metabox Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Metabox api demo
Version: 1.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: our-metabox
Domain Path: /languages/
 */

class OurMetabox {

    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'omb_load_textdomain' ) );

        add_action( 'admin_menu', array( $this, 'omb_add_metabox' ) );
        add_action( 'save_post', array( $this, 'omb_save_location_metabox' ) );
        add_action( 'save_post', array( $this, 'omb_save_image' ) );
        add_action( 'save_post', array( $this, 'omb_save_gallery' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'omb_admin_assets' ) );

        add_filter( 'user_contactmethods', array( $this, 'omb_user_contactmethods' ) );
    }

    // atuhor meta info
    function omb_user_contactmethods( $methods ) {
        $methods['facebook'] = __( 'Facebook', 'our-metabox' );
        $methods['twitter'] = __( 'Twitter', 'our-metabox' );
        $methods['intagram'] = __( 'Instagram', 'our-metabox' );
        $methods['youtube'] = __( 'Youtube', 'our-metabox' );

        return $methods;
    }

    // load textdomain
    function omb_load_textdomain() {
        load_plugin_textdomain( 'our-metabox', false, dirname( __FILE__ ) . "/languages" );
    }

    // load assets
    function omb_admin_assets() {
        wp_enqueue_style( 'admin-style-css', plugin_dir_url( __FILE__ ) . "assets/admin/css/style.css", null, time() );
        wp_enqueue_style( 'jquery-ui', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css', null, time() );
        wp_enqueue_script( 'admin-jquery-js', plugin_dir_url( __FILE__ ) . "assets/admin/js/main.js", array( 'jquery', 'jquery-ui-datepicker' ), time(), true );
    }

    // check metavalue & nonce conditions
    private function is_secured( $nonce_field, $action, $post_id ) {

        $nonce_field = isset( $_POST[$nonce_field] ) ? $_POST[$nonce_field] : '';

        if ( $nonce_field == '' ) {
            return false;
        }

        if ( !wp_verify_nonce( $nonce_field, $action ) ) {
            return false;
        }

        if ( !current_user_can( 'edit_post', $post_id ) ) {
            return false;
        }

        if ( wp_is_post_autosave( $post_id ) ) {
            return false;
        }

        if ( wp_is_post_revision( $post_id ) ) {
            return false;
        }

        return true;
    }

    // add metabox
    function omb_add_metabox() {
        add_meta_box(
            'omb_post_location',
            __( 'Location Info', 'our-metabox' ),
            array( $this, 'omb_display_metabox_location' ),
            'post',
            'normal'
        );

        add_meta_box(
            'omb_book_info',
            __( 'Book Info', 'our-metabox' ),
            array( $this, 'omb_book_info' ),
            'book',
            'normal'
        );

        add_meta_box(
            'omb_image_info',
            __( 'Image Info', 'our-metabox' ),
            array( $this, 'omb_image_info' ),
            'post',
            'normal'
        );
        add_meta_box(
            'omb_gallery_info',
            __( 'Gallery Info', 'our-metabox' ),
            array( $this, 'omb_gallery_info' ),
            'post',
            'normal'
        );
    }

    // save image metabox value
    function omb_save_image( $post_id ) {

        if ( !$this->is_secured( 'omb_image_nonce', 'omb_image', $post_id ) ) {
            return $post_id;
        }

        $image_id = isset( $_POST['omb_image_id'] ) ? $_POST['omb_image_id'] : '';
        $image_url = isset( $_POST['omb_image_url'] ) ? $_POST['omb_image_url'] : '';

        update_post_meta( $post_id, 'omb_image_id', $image_id );
        update_post_meta( $post_id, 'omb_image_url', $image_url );

    }

    // save gallery images metabox value
    function omb_save_gallery( $post_id ) {

        if ( !$this->is_secured( 'omb_gallery_nonce', 'omb_gallery', $post_id ) ) {
            return $post_id;
        }

        $images_id = isset( $_POST['omb_images_id'] ) ? $_POST['omb_images_id'] : '';
        $images_url = isset( $_POST['omb_images_url'] ) ? $_POST['omb_images_url'] : '';

        update_post_meta( $post_id, 'omb_images_id', $images_id );
        update_post_meta( $post_id, 'omb_images_url', $images_url );
    }

    // save location metabox value
    function omb_save_location_metabox( $post_id ) {

        if ( !$this->is_secured( 'omb_location_field', 'omb_location', $post_id ) ) {
            return $post_id;
        }

        if ( !$this->is_secured( 'omb_country_field', 'omb_country', $post_id ) ) {
            return $post_id;
        }

        $location_value = isset( $_POST['omb_location'] ) ? $_POST['omb_location'] : '';
        $country_value = isset( $_POST['omb_country'] ) ? $_POST['omb_country'] : '';
        $is_favourite = isset( $_POST['omb_is_favourite'] ) ? $_POST['omb_is_favourite'] : 0;
        $colors = isset( $_POST['omb_color'] ) ? $_POST['omb_color'] : '';
        $radio_colors = isset( $_POST['omb_radio_color'] ) ? $_POST['omb_radio_color'] : '';
        $dropdown_colors = isset( $_POST['omb_dropdown'] ) ? $_POST['omb_dropdown'] : '';

        /* if( $location_value == '' || $country_value == '' ) {
        return $post_id;
        } */

        $location_value = sanitize_text_field( $location_value );
        $country_value = sanitize_text_field( $country_value );

        update_post_meta( $post_id, 'omb_location', $location_value );
        update_post_meta( $post_id, 'omb_country', $country_value );
        update_post_meta( $post_id, 'omb_is_favourite', $is_favourite );
        update_post_meta( $post_id, 'omb_color', $colors );
        update_post_meta( $post_id, 'omb_radio_color', $radio_colors );
        update_post_meta( $post_id, 'omb_dropdown', $dropdown_colors );
    }

    // upload image metabox structure
    function omb_image_info( $post ) {
        $image_id = esc_attr( get_post_meta( $post->ID, 'omb_image_id', true ) );
        $image_url = esc_attr( get_post_meta( $post->ID, 'omb_image_url', true ) );

        wp_nonce_field( 'omb_image', 'omb_image_nonce' );

        $metabox = <<<HEREDOC
<div class="fields">
	<div class="field_c">
		<div class="label_c">
			<label> Image</label>
		</div>
		<div class="input_c">
			<button class="button" id="upload_image"> Upload Image </button>
            <input type="hidden" name="omb_image_id" id="omb_image_id" value="{$image_id}" />
            <input type="hidden" name="omb_image_url" id="omb_image_url" value="{$image_url}" />
            <div id="image-container"> </div>
		</div>
		<div class="float_c"></div>
	</div>

</div>
HEREDOC;

        echo $metabox;
    }

    // upload gallery images metabox structure
    function omb_gallery_info( $post ) {
        $images_id = esc_attr( get_post_meta( $post->ID, 'omb_images_id', true ) );
        $images_url = esc_attr( get_post_meta( $post->ID, 'omb_images_url', true ) );

        $label = __( 'Gallery', 'our-metabox' );
        $button_label = __( 'Upload Images', 'our-metabox' );

        wp_nonce_field( 'omb_gallery', 'omb_gallery_nonce' );

        $metabox = <<<HEREDOC
<div class="fields">
	<div class="field_c">
		<div class="label_c">
			<label> {$label} </label>
		</div>
		<div class="input_c">
			<button class="button" id="upload_images"> {$button_label} </button>
            <input type="hidden" name="omb_images_id" id="omb_images_id" value="{$images_id}" />
            <input type="hidden" name="omb_images_url" id="omb_images_url" value="{$images_url}" />
            <div id="images-container"> </div>
		</div>
		<div class="float_c"></div>
	</div>

</div>
HEREDOC;

        echo $metabox;
    }

    // book info metabox structure
    function omb_book_info() {
        wp_nonce_field( 'omb_book', 'omb_book_nonce' );

        $metabox = <<<HEREDOC
<div class="fields">
	<div class="field_c">
		<div class="label_c">
			<label for="book_author">Book Author</label>
		</div>
		<div class="input_c">
			<input type="text" class="widefat" id="book_author">
		</div>
		<div class="float_c"></div>
	</div>

	<div class="field_c">
		<div class="label_c">
			<label for="book_isbn">Book ISBN</label>
		</div>
		<div class="input_c">
			<input type="text" id="book_isbn">
		</div>
		<div class="float_c"></div>
	</div>

	<div class="field_c">
		<div class="label_c">
			<label for="book_year">Publish Year</label>
		</div>
		<div class="input_c">
			<input type="text" class="omb_dp" id="book_year">
		</div>
		<div class="float_c"></div>
	</div>

</div>
HEREDOC;

        echo $metabox;
    }

    // location metabox field structure
    function omb_display_metabox_location( $post ) {
        $location_value = get_post_meta( $post->ID, 'omb_location', true );
        $country_value = get_post_meta( $post->ID, 'omb_country', true );
        $is_favourite = get_post_meta( $post->ID, 'omb_is_favourite', true );

        $saved_colors = get_post_meta( $post->ID, 'omb_color', true );
        $saved_radio_colors = get_post_meta( $post->ID, 'omb_radio_color', true );

        $saved_drop_colors = get_post_meta( $post->ID, 'omb_dropdown', true );

        $checked = $is_favourite == 1 ? 'checked' : '';

        $label1 = __( 'Location', 'our-metabox' );
        $label2 = __( 'Country', 'our-metabox' );
        $label3 = __( 'Is Favourite?', 'our-metabox' );
        $label4 = __( 'Colors', 'our-metabox' );
        $label5 = __( 'Select Colors', 'our-metabox' );

        $colors = array( 'red', 'green', 'blue', 'yellow', 'purple', 'orange' );

        wp_nonce_field( 'omb_location', 'omb_location_field' );
        wp_nonce_field( 'omb_country', 'omb_country_field' );

        $metabox = <<<HEREDOC
            <p>
                <label for="omb_location"> {$label1} : </label>
                <input type="text" name="omb_location" id="omb_location" value="{$location_value}" /> <br>
                <label for="omb_country"> {$label2} : </label>
                <input type="text" name="omb_country" id="omb_country" value="{$country_value}" />
            </p>
            <p>
                <label for="omb_is_favourite"> {$label3} </label>
                <input type="checkbox" name="omb_is_favourite" id="omb_is_favourite" value="1" {$checked} />
            </p>
            <p>
                <label> {$label4} : </label>

    HEREDOC;
        // color checkbox
        $saved_colors = is_array( $saved_colors ) ? $saved_colors : [];

        foreach ( $colors as $color ) {
            $_color = ucwords( $color );
            $checked = in_array( $color, $saved_colors ) ? 'checked' : '';
            $metabox .= <<<HEREDOC
                    <label for="omb_color_{$color}" > {$_color} </label>
                    <input type="checkbox" name="omb_color[]" id="omb_color_{$color}"
                    value="{$color}" {$checked} />
    HEREDOC;
        }

        $metabox .= "</p>";

        // radio buttons
        $metabox .= <<<HEREDOC
            <p>
            <label> {$label4} : </label>
    HEREDOC;

        foreach ( $colors as $color ) {
            $_color = ucwords( $color );
            $checked = ( $color == $saved_radio_colors ) ? "checked" : '';
            $metabox .= <<<HEREDOC
                <label for="omb_radio_color_{$color}" > {$_color} </label>
                <input type="radio" name="omb_radio_color" id="omb_radio_color_{$color}"
                value="{$color}" {$checked} />
    HEREDOC;
        }
        $metabox .= "</p>";

        
        // select colors dropdown
        $metabox_dropdown = "<option value=''> " . __( 'Pick a color' ) . "</option>";

        foreach ( $colors as $color ) {
            $checked = ( $color == $saved_drop_colors ) ? 'selected' : '';
            $metabox_dropdown .= sprintf( '<option %s value="%s">%s</option>', $checked, $color, ucwords( $color ) );
        }

        $metabox .= <<<HEREDOC
            <p>
            <label> {$label5} : </label>
            <select name="omb_dropdown" id="omb_dropdown">
            {$metabox_dropdown}
            </select>
    HEREDOC;

        $metabox .= "</p>";
        echo $metabox;
    }

}

new OurMetabox;