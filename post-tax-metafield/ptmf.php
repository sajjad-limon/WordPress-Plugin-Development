<?php

/* 
Plugin Name: Post Tax Metafield
Plugin URI: htpps://github.com/sajjad-limon
Description: Metabox api demo
Version: 1.0
Author: Sajjad Hossen
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: post-tax-metafield
Domain Path: /languages/
*/

function ptmf_load_textdomain() {
    load_plugin_textdomain( 'post-tax-metafield', false, dirname( __FILE__ ). "/languages" );
}
add_action( 'plugins_loaded', 'ptmf_load_textdomain' );



function ptmf_assets() {
    wp_enqueue_style( 'ptmf-admin-css', plugin_dir_url( __FILE__ )."assets/admin/css/style.css", null, time() );
    wp_enqueue_style( 'ptmf-admin-css', plugins_url( "assets/admin/css/style.css", __FILE__ ), null, time() );
}
add_action( 'admin_enqueue_scripts', 'ptmf_assets' );


if( !function_exists( 'ptmf_is_secured' ) ) {
    function ptmf_is_secured( $nonce_field, $action, $post_id ) {

        $nonce_field    = isset( $_POST[$nonce_field] )? $_POST[$nonce_field] : '';

        if( $nonce_field == '' ) {
            return false;
        }

        if( !wp_verify_nonce( $nonce_field, $action ) ) {
            return false;
        }

        if( !current_user_can( 'edit_post', $post_id ) ) {
            return false;
        }

        if( wp_is_post_autosave( $post_id ) ) {
            return false;
        }

        if( wp_is_post_revision( $post_id ) ) {
            return false;
        }

        return true;
    }
}



function ptmf_add_metabox() {
    add_meta_box(
        'ptmf_select_posts_mb',
        __( 'Select Posts', 'post-tax-metafield' ),
        'pfmt_display_metabox',
        array('page'),
    );
}
add_action( 'admin_menu', 'ptmf_add_metabox' );



function ptmf_save_metabox($post_id) {
    if( !ptmf_is_secured( 'ptmf_select_posts_nonce', 'ptmf_selct_posts', $post_id ) ) {
        return $post_id;
    }

    $selected_post = $_POST['ptmf_posts'];
    if( $selected_post > 0 ) {
        update_post_meta( $post_id, 'ptmf_selected_posts', $selected_post );
    }
    return $post_id;

}
add_action( 'save_post' , 'ptmf_save_metabox');



function pfmt_display_metabox($post) {

    $selected_post = get_post_meta( $post->ID, 'ptmf_selected_posts', true );
    wp_nonce_field('ptmf_selct_posts','ptmf_select_posts_nonce');

    $args = array(
        'post_type'     	=> 'post',
        'posts_per_page'    => -1
    );

    $dropdown_list = '';
    $_posts = new wp_query($args);
    while( $_posts->have_posts() ) {
        $selected_value = '';
        $_posts->the_post();
        if( get_the_ID() == $selected_post ) {
            $selected_value = 'selected';
        }
        $dropdown_list .= sprintf( "<option %s value='%s'> %s </option>",$selected_value,  get_the_ID(), get_the_title() );
    }
    wp_reset_query();


    $label = __( 'Select Posts', 'post-tax-metabox' );

	$metabox = <<<HEREDOC
<div class="fields">
	<div class="field_c">
		<div class="label_c">
			<label> {$label} </label>
		</div>
		<div class="input_c">
			<select name="ptmf_posts" id="ptmf_posts" >
                <option value="0"> {$label} </option>
                {$dropdown_list}
            </select>
		</div>
		<div class="float_c"></div>
	</div>
	
</div>
HEREDOC;

		echo $metabox;
}