<?php
/* 
Plugin Name: Custom Post Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Demo plugin api
Version: 1.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: customdemo
*/


function customdemo_register_my_cpts_recipe() {

	/**
	 * Post Type: Recipe.
	 */

	$labels = [
		"name" => __( "Recipe", "customdemo" ),
		"singular_name" => __( "recipe", "customdemo" ),
		"menu_name" => __( "Recipes", "customdemo" ),
		"all_items" => __( "My Recipes", "customdemo" ),
		"add_new" => __( "Add Recipe", "customdemo" ),
		"add_new_item" => __( "New recipe", "customdemo" ),
		"featured_image" => __( "Recipe Cover", "customdemo" ),
	];

	$args = [
		"label" => __( "Recipes", "customdemo" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => "recipes",
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "recipe", "with_front" => false ],
		"query_var" => true,
		"menu_position" => 5,
		"menu_icon" => "dashicons-book-alt",
		"supports" => [ "title", "editor", "thumbnail", "excerpt" ],
		"taxonomies" => [ "category", "post_tag" ],
		"show_in_graphql" => false,
	];

	register_post_type( "recipe", $args );
}
add_action( 'init', 'customdemo_register_my_cpts_recipe' );


// define page template
function customdemo_single_recipe_template($file) {
    global $post;
    if( 'recipe' == $post->post_type ) {
        $file_path = plugin_dir_path( __FILE__ ). "/template-parts/single-recipe.php";
        $file = $file_path;
    }
    return $file;
}
add_filter( 'single_template', 'customdemo_single_recipe_template' );