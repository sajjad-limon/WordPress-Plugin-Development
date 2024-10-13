<?php
/*
Plugin Name: WPDB Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Demo plugin api
Version: 1.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: wpdb-demo
 */

// create table
function wpdb_demo_init() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'peoples';
    $query = "CREATE TABLE {$table_name} (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(255),
        email VARCHAR(255),
        age INT,
        PRIMARY KEY(id)
    );";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $query );
}

register_activation_hook( __FILE__, 'wpdb_demo_init' );

// load assets
add_action( 'admin_enqueue_scripts', function ( $hook ) {

    if ( 'toplevel_page_wpdb-demo' == $hook ) {
        wp_enqueue_style( 'pure-grid-css', '//unpkg.com/purecss@1.0.1/build/grids-min.css' );
        wp_enqueue_style( 'wpdb-demo-css', plugin_dir_url( __FILE__ ) . "assets/css/style.css", null, time() );
        wp_enqueue_script( 'wpdb-demo-js', plugin_dir_url( __FILE__ ) . "assets/js/main.js", array( 'jquery' ), time(), true );
        $nonce = wp_create_nonce( 'display_result' );
        wp_localize_script(
            'wpdb-demo-js',
            'plugindata',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'nonce' => $nonce )
        );
    }

} );

// create menu page
add_action( 'admin_menu', function () {
    add_menu_page( 'WPDB Demo', __( 'WPDB Demo' ), 'manage_options', 'wpdb-demo', 'wpdb_demo_admin_page' );
} );

// display action using ajax
add_action( 'wp_ajax_display_result', function () {
    global $wpdb;
    $table_name = $wpdb->prefix . 'peoples';

    if ( wp_verify_nonce( $_POST['nonce'], 'display_result' ) ) {
        $task = $_POST['task'];

        if ( 'add-new-record' == $task ) {
            $peoples = array(
                'name'  => 'Shaheen Afridi',
                'email' => 'afridi10@gu.edu',
                'age'   => 23,
            );
            $wpdb->insert( $table_name, $peoples );
            echo "New people added. </br>ID: {$wpdb->insert_id}";       //insert_id pulls new data id

        } elseif ( 'replace-or-insert' == $task ) { //if id match, replaced. otherwise insert new data
            $peoples = array(
                'id'    => 4,
                'name'  => 'Fakhar Zaman',
                'email' => 'fakhar23@gu.edu',
                'age'   => 23,
            );
            $wpdb->replace( $table_name, $peoples );
            echo "People data replaced. </br>ID: {$wpdb->insert_id}";

        } elseif ( 'update-data' == $task ) {
            $people = array(
                'age' => 64,
            );
            $result = $wpdb->update( $table_name, $people, array('id' => 4) );
            echo "People Record Updated. </br>Result: {$result}";

        } elseif ( 'load-single-row' == $task ) {
            $data = $wpdb->get_row( "SELECT * FROM {$table_name} WHERE id=4", OBJECT ); // Object
            print_r( $data );
            $data = $wpdb->get_row( "SELECT * FROM {$table_name} WHERE id=4", ARRAY_A ); // Associate array
            print_r( $data );
            $data = $wpdb->get_row( "SELECT * FROM {$table_name} WHERE id=4", ARRAY_N ); //Numeric
            print_r( $data );

        } elseif ( 'load-multiple-row' == $task ) {

            /* $data = $wpdb->get_results("SELECT * FROM {$table_name} WHERE id>2",ARRAY_A);    // Associate array
            print_r($data); */
            $data = $wpdb->get_results( "SELECT name,email,age FROM {$table_name}", OBJECT_K ); // Object_K will return first column as Key
            print_r( $data );

        } elseif ( 'add-multiple' == $task ) {

            $peoples = array(
                array(
                    'name'  => 'Mustafizur Hossain',
                    'email' => 'msdh23@gu.edu',
                    'age'   => 24,
                ),
                array(
                    'name'  => 'Mooen Ali',
                    'email' => 'rubel34@gu.edu',
                    'age'   => 28,
                ),
            );

            foreach ( $peoples as $people ) { // no query that add multiple. that's why use foreach loop
                $wpdb->insert( $table_name, $people );
            }

            $data = $wpdb->get_results( "SELECT name,email,age FROM {$table_name} WHERE id>5", OBJECT_K ); // Object_K will return first column as Key
            print_r( $data );

        } elseif ( 'prepared-statement' == $task ) { // prepare statement will store the query, we only need to use the value
            $id = 5;
            $name = 'Rubel Hossain';

            $prepare_statement = $wpdb->prepare( "SELECT * FROM {$table_name} WHERE id>%d and name=%s ", $id, $name );
            $data = $wpdb->get_results( $prepare_statement, ARRAY_A );
            print_r( $data );

        } elseif ( 'single-column' == $task ) { // get_col() display only one column
            $query = "SELECT name FROM {$table_name}";
            $data = $wpdb->get_col( $query );
            print_r( $data );

        } elseif ( 'single-var' == $task ) { //problem with understanding logic

            $query = "SELECT COUNT(*) FROM {$table_name}";
            $data = $wpdb->get_var( $query );
            echo "Total Users: {$data} </br>";

            $query = "SELECT name, email FROM {$table_name}";
            $data = $wpdb->get_var( $query, 0, 1 );
            echo "Name of first user : {$data}</br>";

            $query = "SELECT name, email FROM {$table_name}";
            $data = $wpdb->get_var( $query, 0, 1 );
            echo "Email of first user : {$data} </br>";

        } elseif ( 'delete-data' == $task ) { // delete record

            $data = $wpdb->delete( $table_name, array('id' => 9) );
            echo "Record Delete : {$data}";
        }

    }

    die( 0 );
} );

// menu page structure
function wpdb_demo_admin_page() {
    ?>
        <div class="container" style="padding-top:20px;">
            <h1>WPDB Demo</h1>
            <div class="pure-g">
                <div class="pure-u-1-4" style='height:100vh;'>
                    <div class="plugin-side-options">
                        <button class="action-button" data-task='add-new-record'>Add New Data</button>
                        <button class="action-button" data-task='replace-or-insert'>Replace or Insert</button>
                        <button class="action-button" data-task='update-data'>Update Data</button>
                        <button class="action-button" data-task='load-single-row'>Load Single Row</button>
                        <button class="action-button" data-task='load-multiple-row'>Load Multiple Row</button>
                        <button class="action-button" data-task='add-multiple'>Add Multiple Row</button>
                        <button class="action-button" data-task='prepared-statement'>Prepared Statement</button>
                        <button class="action-button" data-task='single-column'>Display Single Column</button>
                        <button class="action-button" data-task='single-var'>Display Variable</button>
                        <button class="action-button" data-task='delete-data'>Delete Data</button>
                    </div>
                </div>
                <div class="pure-u-3-4">
                    <div class="plugin-demo-content">
                        <h3 class="plugin-result-title">Result</h3>
                        <div id="plugin-demo-result" class="plugin-result"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php
}
