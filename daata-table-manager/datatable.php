<?php

/* 
Plugin Name: Data Table Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Demo plugin api
Version: 1.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: datatable
*/
require_once( 'class.persons-table.php' ) ;


function datatable_load_text_domain() {
    load_plugin_textdomain( 'datatable', false, plugin_dir_path( __FILE__ ). "languages" );
}
add_action( 'plugins_loaded', 'datatable_load_text_domain' );


// create table page
function datatable_admin_page() {
    add_menu_page(
        __( 'Table Data', 'datatable' ),
        __( 'Table Data', 'datatable' ),
        'manage_options',
        'datatable',
        'datatable_display_table'
    );
}


// search box
function datatable_serach_box($item) {
    $name = strtolower( $item['name'] );
    $age = $item['age'] ;
    $search_name = sanitize_text_field( $_REQUEST['s'] ) ;

    if( strpos( $name, $search_name ) !== false ) {
        return true;
    }
    if( strpos( $age, $search_name ) !== false ) {
        return true;
    }
    
    return false;
}



// display table
function datatable_display_table() {
    
    include_once( 'dataset.php' );

    // search box
    if( isset($_REQUEST['s']) ) {
        $data = array_filter( $data, 'datatable_serach_box' );
    }
    
    $table = new Persons_Table();
    $table->set_data($data);
    $table->prepare_items();

    ?>
        <div class="wrap">
            <h2> <?php echo esc_html__( 'Persons Table', 'datatable' ); ?> </h2>
            <form method="GET">
                <?php
                    $table->search_box( 'Seach', 'serach_box' );
                    $table->display();
                ?>
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">     <!-- request' for stick on the same page -->
            </form>
        </div>
    <?php

}

add_action( 'admin_menu', 'datatable_admin_page' );
