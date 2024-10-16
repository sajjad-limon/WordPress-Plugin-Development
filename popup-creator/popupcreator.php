<?php

/*
Plugin Name: Popup Creator
Plugin URI: http://learnwith.hasinhayder.com
Description: Creates Beautiful Popups
Version: 1.0
Author: LWHH
Author URI: https://hasin.me
License: GPLv2 or later
Text Domain: popupcreator
Domain Path: /languages/
Plugin Type: Piklist
*/

class PopupCreator {
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( $this, 'register_cpt_popup' ) );
		add_action( 'init', array( $this, 'register_popup_size' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
		add_action( 'wp_footer', array( $this, 'print_modal_markup' ) );
	}

	function load_assets() {
		wp_enqueue_style( 'popupcreator-css', plugin_dir_url( __FILE__ ) . "assets/css/modal.css", null, time() );
		wp_enqueue_script( 'plainmodal-js', plugin_dir_url( __FILE__ ) . "assets/js/plain-modal.min.js", null, "1.0.27", true );
		wp_enqueue_script( 'popupcreator-main', plugin_dir_url( __FILE__ ) . "assets/js/popupcreator-main.js", array(
			'jquery',
			'plainmodal-js'
		), time(), true );
	}

	function register_popup_size() {
		add_image_size( 'popup-landscape', '800', '600', true );
		add_image_size( 'popup-square', '500', '500', true );
	}

	function load_textdomain() {
		load_plugin_textdomain( 'popupcreator', false, plugin_dir_path( __FILE__ ) . "languages" );
	}

	function register_cpt_popup() {

		$labels = array(
			"name"               => __( "Popups", "popupcreator" ),
			"singular_name"      => __( "Popup", "popupcreator" ),
			"featured_image"     => __( 'Popup Image', 'popupcreator' ),
			"set_featured_image" => __( 'Set Popup Image', 'popupcreator' )
		);

		$args = array(
			"label"               => __( "Popups", "popupcreator" ),
			"labels"              => $labels,
			"description"         => "",
			"public"              => false,
			"publicly_queryable"  => true,
			"show_ui"             => true,
			"delete_with_user"    => false,
			"show_in_rest"        => true,
			"has_archive"         => false,
			"show_in_menu"        => true,
			"show_in_nav_menus"   => false,
			"exclude_from_search" => true,
			"capability_type"     => "post",
			"map_meta_cap"        => true,
			"hierarchical"        => false,
			"rewrite"             => array( "slug" => "popup", "with_front" => true ),
			"query_var"           => true,
			"supports"            => array( "title", "thumbnail" ),
		);

		register_post_type( "popup", $args );
	}

	function print_modal_markup() {
		?>
		<div class="modal-content">
                <div><img class="close-button" width="30"
                src="<?php echo plugin_dir_url(__FILE__)."/assets/img/close.jpg" ?>" alt="<?php _e( 'Close', 'popupcreator' ); ?>" >
            </div>
            <img src="https://d30itml3t0pwpf.cloudfront.net/wp-content/uploads/sites/3/2018/06/ecommerce-flash-sale-popup-mobile-.jpg" alt="">
        </div>
	<?php
	}


}

new PopupCreator();