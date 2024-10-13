<?php

/*
Plugin Name: Database Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Demo plugin api
Version: 1.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: dbdemo
 */

define( "DB_DEMO_VERSION", '1.1' );

// create table
function dbdemo_init() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'persons';
    $query = "CREATE TABLE {$table_name} (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(255),
        email VARCHAR(255),
        PRIMARY KEY (id)
        );";

    require_once ABSPATH . "wp-admin/includes/upgrade.php";
    dbDelta( $query, true );

    add_option( 'db-demo-version', DB_DEMO_VERSION );

    if ( get_option( 'db-demo-version' ) != DB_DEMO_VERSION ) {
        $query = "CREATE TABLE {$table_name} (
                id INT NOT NULL AUTO_INCREMENT,
                name VARCHAR(255),
                email VARCHAR(255),
                age INT,
                PRIMARY KEY (id)
                );";

        dbDelta( $query, true );
        update_option( 'db-demo-version', DB_DEMO_VERSION );
    }

}

register_activation_hook( __FILE__, 'dbdemo_init' );

// column drop or delete a column
function dbdemo_drop_column() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'persons';

    if ( get_option( 'db-demo-version' ) != DB_DEMO_VERSION ) {

        $query = "ALTER TABLE {$table_name} DROP COLUMN age ";
        $wpdb->query( $query );
    }

    update_option( 'db-demo-version', DB_DEMO_VERSION );

}

add_action( 'plugins_loaded', 'dbdemo_drop_column' );

// main page menu thats why toplevel_page_[page-name]
add_action( 'admin_enqueue_scripts', function ( $hook ) {

    if ( 'toplevel_page_dbdemo' == $hook ) {
        wp_enqueue_style( 'dbdemo-style', plugin_dir_url( __FILE__ ) . 'assets/css/form.css' );
    }

} );

// add data into column
function dbdemo_add_data() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'persons';
    $wpdb->insert( $table_name, array(
        'name'  => 'John Doe',
        'email' => 'johnw32@yahoo.com',
    ) );
    $wpdb->insert( $table_name, array(
        'name'  => 'John Carter',
        'email' => 'carterjohn32@yahoo.com',
    ) );
}

register_activation_hook( __FILE__, "dbdemo_add_data" );

// flushing column data (delete data when plugin deactivated)
function dbdemo_flush_data() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'persons';
    $query = "TRUNCATE TABLE {$table_name}";
    $wpdb->query( $query );
}

register_deactivation_hook( __FILE__, 'dbdemo_flush_data' );

// display column data on admin page
add_action( 'admin_menu', function () {

    add_menu_page( 'DB Demo', 'DB Demo', 'manage_options', 'dbdemo', 'dbdemo_menu_page' );
} );

// menu page structure
function dbdemo_menu_page() {
    global $wpdb;
    echo "<h2>DB Demo </h2>";

    $id = $_GET['pid'] ?? 0;
    $id = sanitize_key( $id );

    if ( $id ) {
        $result = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}persons WHERE id = '{$id}' " );

        if ( $result ) {
            /* echo "Name: {$result->name} </br>";
        echo "Email: {$result->email} </br>"; */
        }
    }

    ?>
    <div class="form_box">
        <div class="form_box_header">
            <?php _e( 'Data Form', 'dbdemo' );?>
        </div>
        <div class="form_box_content">
            <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
                <?php
                    wp_nonce_field( 'dbdemo_nonce', 'nonce' );
    ?>
                <input type="hidden" name="action" value="dbdemo_add_record">
                <label>
                    <strong>Name: </strong>
                </label> <br>
                <input type="text" name="name" value="<?php
            if ( $id ) {
                echo $result->name;
            }
    ?>"> <br>
                <label>
                    <strong>Email: </strong>
                </label> <br>
                <input type="email" name="email" value="<?php
            if ( $id ) {
                echo $result->email;
            }
    ?>"> <br>

                <?php

    if ( $id ) {
        echo '<input type="hidden" name="id" value="' . $id . '" ';
        submit_button( 'Update Record' );
    } else {
        submit_button( 'Add Record' );
    }
    ?>
            </form>
        </div>
    </div>

<?php
    /* if( isset( $_POST['submit'] ) ) {

    $nonce  = sanitize_text_field($_POST['nonce']);
    $name   = sanitize_text_field($_POST['name']);
    $email  = sanitize_text_field($_POST['email']);
    $id     = sanitize_text_field($_POST['id']);

    if( wp_verify_nonce( $nonce, 'dbdemo_nonce' ) ) {

    // update
    if( $id ) {
    $wpdb->update( "{$wpdb->prefix}persons", ['name'=> $name, 'email'=> $email], ['id'=>$id] );
    $notice = <<<HEREDOC
    <div class="notice notice-success is-dismissible">
    <p> Record Updated Successfully! </p>
    </div>
    HEREDOC;
    echo $notice;
    wp_redirect( admin_url( 'admin.php?page=dbdemo&pid='.$id ) );

    }  //create
    else {
    $wpdb->insert( "{$wpdb->prefix}persons", ['name'=> $name, 'email'=> $email] );
    $notice = <<<HEREDOC
    <div class="notice notice-success is-dismissible">
    <p> Record Created Successfully! </p>
    </div>
    HEREDOC;
    echo $notice;
    }
    }
    else {
    $notice = <<<HEREDOC
    <div class="notice notice-error is-dismissible">
    <p> You Are Not Allowed To Create a Record! </p>
    </div>
    HEREDOC;
    echo $notice;
    }
    } */

}

//add record via admin-post.php page
add_action( 'admin_post_dbdemo_add_record', function () {
    global $wpdb;

    $nonce  = sanitize_text_field( $_POST['nonce'] );
    $name   = sanitize_text_field( $_POST['name'] );
    $email  = sanitize_text_field( $_POST['email'] );
    $id     = sanitize_text_field( $_POST['id'] );

    if ( wp_verify_nonce( $nonce, 'dbdemo_nonce' ) ) {

        if ( !empty( $name ) && !empty( $email ) ) {

            if ( $id ) {
                $wpdb->update( "{$wpdb->prefix}persons", array('name' => $name, 'email' => $email), array('id' => $id) );
                wp_redirect( admin_url( 'admin.php?page=dbdemo&pid=' . $id ) );
            } else {
                $wpdb->insert( "{$wpdb->prefix}persons", array('name' => $name, 'email' => $email) );
                $new_id = $wpdb->insert_id;
                wp_redirect( admin_url( 'admin.php?page=dbdemo&pid=' . $new_id ) );
                //wp_redirect( admin_url( 'admin.php?page=dbdemo' ) );
            }

        } else {

            echo "Please Insert all the fields.";
            wp_redirect( admin_url( 'admin.php?page=dbdemo') );
        }

    }

} );
