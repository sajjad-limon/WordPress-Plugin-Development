<?php

/* 
Plugin Name: Column Fields Manage
Plugin URI: htpps://github.com/sajjad-limon
Description: Metabox api demo
Version: 1.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: column-field
Domain Path: /languages/
*/

function columnfd_load_text_domain() {
    load_plugin_textdomain( 'column-field', false, dirname( __FILE__ )."/languages" );
}
add_action( 'plugins_loaded', 'columnfd_load_text_domain' );


function columnfd_manage_posts_columns($columns) {
    unset($columns['date']);
    unset($columns['categories']);

    $columns['id']= "Post ID";
    $columns['thumbnail']= "Thumbnail";
    $columns['wordcount']= "Wordcount";

    return $columns;
}
add_filter( 'manage_posts_columns', 'columnfd_manage_posts_columns' );
add_filter( 'manage_pages_columns', 'columnfd_manage_posts_columns' );



function columnfd_custom_column($column, $post_id) {
    if( 'id' == $column ) {
        echo $post_id;
    } elseif( 'thumbnail' == $column ) {
        $thumbnail = get_the_post_thumbnail($post_id, array( 100,100 ) );
        echo $thumbnail;
    } elseif( 'wordcount' == $column ) {
        $_post = get_post($post_id);
        $content = $_post->post_content;
        $wordcount = str_word_count( strip_tags($content) );
        echo $wordcount;
    }
}
add_action( 'manage_posts_custom_column', 'columnfd_custom_column', 10, 2 );
add_action( 'manage_pages_custom_column', 'columnfd_custom_column', 10, 2 );



//filtering posts
function columnfd_filter() {
    if( isset($_GET['post_type']) && $_GET['post_type'] != 'post' ) {
        return;
    }
    $filter_value = isset( $_GET['column_filter']) ? $_GET['column_filter'] : '';
    $values = array(
        '0' => __( 'Select Posts', 'column-field' ),
        '1' => __( 'Clubs', 'column-field' ),
        '2' => __( 'Brands', 'column-field' ),
    )

    ?>

    <select name="column_filter" id="">
        <?php
        foreach( $values as $key => $value ) {
            printf( '<option %s value="%s"> %s </option> ', $key == $filter_value ? 'selected' : '', $key, $value );
        }
        ?>
    </select>
    <?php
}
add_action( 'restrict_manage_posts', 'columnfd_filter' );


function columnfd_filter_value( $wpquery ) {
    if( !is_admin() ) {
        return;
    }

    $filter_value = isset($_GET['column_filter']) ? $_GET['column_filter'] : '';
    echo($filter_value);

    if( '1' == $filter_value ) {
        $wpquery->set( 'post__in', array( 29, 31, 33 ) );
    } elseif( '2' == $filter_value ) {
        $wpquery->set( 'post__in', array( 21, 23, 25 ) );
    }

}
add_filter( 'pre_get_posts', 'columnfd_filter_value' );


//filtering thumbnail posts
function columnfd_thumbnail_filter() {
    if( isset($_GET['post_type']) && $_GET['post_type'] != 'post' ) {
        return;
    }
    $filter_value = isset( $_GET['column_thumbnail']) ? $_GET['column_thumbnail'] : '';
    $values = array(
        '0' => __( 'Thumbnail Status', 'column-field' ),
        '1' => __( 'Has Thumbnail', 'column-field' ),
        '2' => __( 'No Thumbnail', 'column-field' ),
    )
    ?>

    <select name="column_thumbnail" >
        <?php
        foreach( $values as $key => $value ) {
            printf( '<option %s value="%s"> %s </option> ', $key == $filter_value ? 'selected' : '', $key, $value );
        }
        ?>
    </select>
    <?php
}
add_action( 'restrict_manage_posts', 'columnfd_thumbnail_filter' );

function columnfd_thumbnail_value( $wpquery ) {
    if( !is_admin() ) {
        return;
    }

    $filter_value = isset($_GET['column_thumbnail']) ? $_GET['column_thumbnail'] : '';
    //$wpquery->set( 'posts_per_page', 2 );

    if( '1' == $filter_value ) {
        $wpquery->set( 'meta_query', array(
            array(
                'key'   => '_thumbnail_id',
                'compare' => 'EXISTS'
            )
        ) );
    } elseif( '2' == $filter_value ) {
        $wpquery->set( 'meta_query', array(
            array(
                'key'   => '_thumbnail_id',
                'compare' => 'NOT EXISTS'
            )
        ) );
    }

}
add_filter( 'pre_get_posts', 'columnfd_thumbnail_value' );


//filtering posts wordcount
function columnfd_wordcount_filter() {
    if( isset($_GET['post_type']) && $_GET['post_type'] != 'post' ) {
        return;
    }
    $filter_value = isset( $_GET['column_wordcount']) ? $_GET['column_wordcount'] : '';
    $values = array(
        '0' => __( 'Word Count', 'column-field' ),
        '1' => __( 'Above 400', 'column-field' ),
        '2' => __( '200 to 400', 'column-field' ),
        '3' => __( 'Below 200', 'column-field' ),
    )
    ?>

    <select name="column_wordcount" >
        <?php
        foreach( $values as $key => $value ) {
            printf( '<option %s value="%s"> %s </option> ', $key == $filter_value ? 'selected' : '', $key, $value );
        }
        ?>
    </select>
    <?php
}
add_action( 'restrict_manage_posts', 'columnfd_wordcount_filter' );

function columnfd_wordcount_value( $wpquery ) {
    if( !is_admin() ) {
        return;
    }

    $filter_value = isset($_GET['column_wordcount']) ? $_GET['column_wordcount'] : '';
    //$wpquery->set( 'posts_per_page', 2 );

    if( '1' == $filter_value ) {
        $wpquery->set( 'meta_query', array(
            array(
                'key'       => 'wordn',
                'value'     => 400,
                'compare'   => '>=',
                'type'      => 'NUMBER'
            )
        ) );
    } elseif( '2' == $filter_value ) {
        $wpquery->set( 'meta_query', array(
            array(
                'key'       => 'wordn',
                'value'     => array( 200, 400 ),
                'compare'   => 'BETWEEN',
                'type'      => 'NUMERIC'
            )
        ) );
    } elseif( '3' == $filter_value ) {
        $wpquery->set( 'meta_query', array(
            array(
                'key'       => 'wordn',
                'value'     => 200,
                'compare'   => '<=',
                'type'      => 'NUMERIC'
            )
        ) );
    } 

}
add_filter( 'pre_get_posts', 'columnfd_wordcount_value' );