<?php
/*
Plugin Name: Option API Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Demonstration of Option API
Version: 1.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: optionapi-demo
 */

// laod assets
add_action( 'admin_enqueue_scripts', function ( $hook ) {

    if ( 'toplevel_page_optionapi-demo' == $hook ) {
        wp_enqueue_style( 'pure-grid-css', '//unpkg.com/purecss@1.0.1/build/grids-min.css' );
        wp_enqueue_style( 'optionapi-demo-css', plugin_dir_url( __FILE__ ) . "assets/css/style.css", null, time() );
        wp_enqueue_script( 'optionapi-demo-js', plugin_dir_url( __FILE__ ) . "assets/js/main.js", array( 'jquery' ), time(), true );
        $nonce = wp_create_nonce( 'display_result' );
        wp_localize_script(
            'optionapi-demo-js',
            'plugindata',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'nonce' => $nonce )
        );
    }

} );

//create menu page
add_action( 'admin_menu', function () {
    add_menu_page( 'Options Api Demo', __( 'Options Demo' ), 'manage_options', 'optionapi-demo', 'optionapi_demo_admin_page' );
} );


// display action using ajax
add_action( 'wp_ajax_display_result', function () {
    global $wpdb;
    $table_name = $wpdb->prefix . 'peoples';

    if ( wp_verify_nonce( $_POST['nonce'], 'display_result' ) ) {
        $task = $_POST['task'];

        if ( 'add-option' == $task ) {      // insert a data
            $option_name = 'od-country';
            $value = 'Bangladesh';

            echo "Result : " . add_option( $option_name, $value ) . "</br>";

        } elseif ( 'add-array-option' == $task ) {      // insert an array
            $option_name = 'od-array-country';
            $value = array( 'country' => 'Bangladesh', 'Nepal', 'Pakistan', 'India', 'Srilanka', 'capital' => 'Dhaka', 'Kathmundu', 'Islamabad', 'New Delhi', 'Colombo' );

            echo "Result : " . add_option( $option_name, $value ) . "</br>";

            $option_name = 'od-array-country-json';
            $value = json_encode( array( 'Bangladesh', 'Nepal', 'Pakistan', 'India', 'Srilanka' ) );

            echo "Result : " . add_option( $option_name, $value ) . "</br>";

        } elseif ( 'get-option' == $task ) {    // display a option_value
            $option_name = 'od-country';
            $result = get_option( $option_name );

            echo "Result : " . $result . "</br>";

        } elseif ( 'get-array-option' == $task ) {      // display an array option_value
            $option_name = 'od-array-country';
            $result = get_option( $option_name );

            print_r( $result ) . '</br>';

            $option_name = 'od-array-country-json';
            $result = get_option( $option_name );

            print_r( $result );

        } elseif ( 'option-filter-hook' == $task ) {    // aplying filter on option

            $option_name = 'od-array-country-json';
            $result = get_option( $option_name );

            print_r( $result );

        } elseif ( 'update-option' == $task ) {     // update option value

            $option_name = 'od-capital';
            $value = 'Islamabad';
            $result = update_option( $option_name, $value );

            echo ( 'Result : ' . $result . '</br>' );

        } elseif ( 'update-array-option' == $task ) {       // update array option value

            $option_name = 'od-capital-array';
            $value = array( 'country' => 'Pakistan', 'capital' => 'Islamabad' );
            $new_value = array( 'capital' => 'Rome' );
            $result = update_option( $option_name, $value );
            $result = update_option( $option_name, $new_value );

            echo ( 'Result : ' . $result . '</br>' );
            echo ( 'Result : ' . $result . '</br>' );

        } elseif ( 'delete-option' == $task ) {     // delete option value

            $option_name = 'od-capital-array';
            $result = delete_option( $option_name );

            echo ( 'Result : ' . $result . '</br>' );

        } elseif ( 'export-option' == $task ) {     // export option value

            $option_normal = array('od-country', 'od-capital'); // normal string
            $option_array = array('od-array-country', 'od-capital-array'); // normal array
            $option_json = array('od-array-country-json'); // json array

            $exported_data = array();

            foreach ( $option_normal as $option_name ) {
                $value = get_option( $option_name );
                $exported_data[$option_name] = $value;
            }

            foreach ( $option_array as $option_name ) {
                $value = get_option( $option_name );
                $exported_data[$option_name] = $value;
            }

            foreach ( $option_json as $option_name ) {
                $value = get_option( $option_name );
                $exported_data[$option_name] = $value;
            }

            //print_r($exported_data);

            echo json_encode( $exported_data );

        } elseif ( 'import-option' == $task ) {     // import option value

            $exported_data = '{"od-country":"Bangladesh","od-capital":"Islamabad","od-array-country":{"country":"Bangladesh","0":"Nepal","1":"Pakistan","2":"India","3":"Srilanka","capital":"Dhaka","4":"Kathmundu","5":"Islamabad","6":"New Delhi","7":"Colombo"},"od-capital-array":{"capital":"Rome"},"od-array-country-json":["Bangladesh","Nepal","Pakistan","India","Srilanka"]}';
            $array_data = json_decode( $exported_data );

            print_r( $array_data );

            foreach ( $array_data as $option_name => $value ) {
                update_option( $option_name, $value );
            }

        }

    }

    die( 0 );
} );

// filter hook for option table {json-decode}
add_filter( 'option_od-array-country-json', function ( $value ) {
    return json_decode( $value, true );
} );

// menu page structure
function optionapi_demo_admin_page() {
    ?>
         <div class="container" style="padding-top:20px;">
            <h1>Options Demo</h1>
            <div class="pure-g">
                <div class="pure-u-1-4" style='height:100vh;'>
                    <div class="plugin-side-options">
                        <button class="action-button" data-task='add-option'>Add New Option</button>
                        <button class="action-button" data-task='add-array-option'>Add Array Option</button>
                        <button class="action-button" data-task='get-option'>Display Saved Option</button>
                        <button class="action-button" data-task='get-array-option'>Display Option Array</button>
                        <button class="action-button" data-task='option-filter-hook'>Option Filter Hook</button>
                        <button class="action-button" data-task='update-option'>Update Option</button>
                        <button class="action-button" data-task='update-array-option'>Update Array Option</button>
                        <button class="action-button" data-task='delete-option'>Delete Option</button>
                        <button class="action-button" data-task='export-option'>Export Options</button>
                        <button class="action-button" data-task='import-option'>Import Options</button>
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
