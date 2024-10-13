<?php
/*
Plugin Name: Transient Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Demonstration of Transient API
Version: 1.0
Author: Sajjad Hossen
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: tran-demo
 */

// laod assets
add_action( 'admin_enqueue_scripts', function ( $hook ) {

    if ( 'toplevel_page_transient-demo' == $hook ) {
        wp_enqueue_style( 'pure-grid-css', '//unpkg.com/purecss@1.0.1/build/grids-min.css' );
        wp_enqueue_style( 'transient-demo-css', plugin_dir_url( __FILE__ ) . "assets/css/style.css", null, time() );
        wp_enqueue_script( 'transient-demo-js', plugin_dir_url( __FILE__ ) . "assets/js/main.js", array( 'jquery' ), time(), true );
        $nonce = wp_create_nonce( 'transient_display_result' );
        wp_localize_script(
            'transient-demo-js',
            'plugindata',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'nonce' => $nonce )
        );
    }

} );

//create menu page
add_action( 'admin_menu', function () {
    add_menu_page( 'Transient Demo', 'Transient Demo', 'manage_options', 'transient-demo', 'transientdemo_admin_page' );
} );

// display action using ajax
add_action( 'wp_ajax_transient_display_result', function () {
    global $transient;
    $table_name = $transient->prefix . 'peoples';

    if ( wp_verify_nonce( $_POST['nonce'], 'transient_display_result' ) ) {
        $task = $_POST['task'];

        if ( 'add-transient' == $task ) { // create transient
            $transient = 'tr-country';
            $value = 'Bangladesh';

            echo 'Result : ' . set_transient( $transient, $value ) . '</br>';

        } elseif ( 'set-expiry' == $task ) { // set transient expiry
            $transient = 'tr-capital';
            $value = 'Dhaka';
            $expiry = 1 * 60;   // 1 min

            echo 'Result : ' . set_transient( $transient, $value, $expiry ) . '</br>';

        } elseif ( 'get-transient' == $task ) { // get transient value
            $transient1 = 'tr-country';
            echo 'Result : ' . get_transient( $transient1 ) . '</br>';

            $transient2 = 'tr-capital';
            echo 'Result : ' . get_transient( $transient2 ) . '</br>';

        } elseif ( 'importance' == $task ) { // importance of ===
            $transient1 = 'tr-country';

            if ( $transient1 == false ) {
                echo 'Result not found' . '</br>';
            } else {
                echo 'Result1 : ' . get_transient( $transient1 ) . '</br>';
            }

            $transient2 = 'tr-temparature-Dhaka';
            $value = 0;
            set_transient( $transient2, $value );

            if ( $transient2 === false ) {
                echo 'Result not found' . '</br>';
            } else {

                echo 'Result2 : ' . get_transient( $transient2 ) . ' Degree </br>';
            }

        } elseif ( 'add-complex-transient' == $task ) { // add complex transient value
            global $wpdb;

            $result = $wpdb->get_results( 'SELECT post_title FROM wp_posts ORDER BY id ASC LIMIT 10', ARRAY_A );
            //print_r($result);

            $transient = 'tr-posts_title';
            set_transient( $transient, $result );

            print_r( get_transient( $transient ) );

        } elseif ( 'transient-filter-hook' == $task ) { // add filter on transient
            $transient1 = 'tr-country';
            $result = get_transient( $transient1 );

            echo 'Result : ' . $result . '</br>';

        } elseif ( 'delete-transient' == $task ) { // delete transient value
            $transient1 = 'tr-country';
            $result1 = get_transient( $transient1 );
            echo 'Before Delete : ' . $result1 . '</br>';

            $result2 = delete_transient( $transient1 );

            echo 'After Delete : ' . $result2 . '</br>';


            $transient1 = 'tr-capital';
            $result1 = get_transient( $transient1 );
            echo 'Before Delete : ' . $result1 . '</br>';

            $result2 = delete_transient( $transient1 );

            echo 'After Delete : ' . $result2 . '</br>';

        }

    }

    die( 0 );
} );

// filter hook for transient tr-country
add_filter( 'pre_transient_tr-country', function ( $result ) {
    //return false;       // false will return old transient value
    return 'Banglaseh my love';
} );

// menu page structure
function transientdemo_admin_page() {
    ?>
        <div class="container" style="padding-top:20px;">
            <h1>Transient Demo</h1>
            <div class="pure-g">
                <div class="pure-u-1-4" style='height:100vh;'>
                    <div class="plugin-side-options">
                        <button class="action-button" data-task='add-transient'>Add New transient</button>
                        <button class="action-button" data-task='set-expiry'>Set Expiry</button>
                        <button class="action-button" data-task='get-transient'>Display Transient</button>
                        <button class="action-button" data-task='importance'>Importance of ===</button>
                        <button class="action-button" data-task='add-complex-transient'>Add Complex Transient</button>
                        <button class="action-button" data-task='transient-filter-hook'>Transient Filter Hook</button>
                        <button class="action-button" data-task='delete-transient'>Delete Transient</button>
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
