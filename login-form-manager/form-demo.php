<?php

/*
Plugin Name: Login Form Demo
Plugin URI: htpps://github.com/sajjad-limon
Description: Demo plugin api
Version: 1.0
Author: Sajjad Limon
Author URI: htpps://github.com/sajjad-limon
License: GPLv2 or later
Text Domain: lfd
 */

function lfd_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
        background-image: url(<?php echo plugin_dir_url( __FILE__ ) .'assets/images/intel.png'; ?>);
		height: 100px;
		width:320px;
		background-size: 320px 65px;
		background-repeat: no-repeat;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'lfd_login_logo' );


// change login page's text
// use login_head for only load on login page 
add_action('login_head', function(){
    add_filter('gettext',function($translated_text, $text_to_translate, $text_domain){
        if('Username or Email Address'== $text_to_translate){
            $translated_text = __('Your Login Name', 'lfd');
        } elseif('Password'== $text_to_translate){
            $translated_text = __('Your Login Key', 'lfd');
        }

        return $translated_text;
    },10,3);
});


// change logo url
function lfd_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'lfd_login_logo_url' );


// change logo title
function lfd_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter( 'login_headertext', 'lfd_login_logo_url_title' );



// load assets for changing form look 
function my_login_stylesheet() {
    wp_enqueue_style( 'custom-login', plugin_dir_url( __FILE__ ) . 'assets/css/style-login.css',null,time() );
    wp_enqueue_script( 'custom-login', plugin_dir_url( __FILE__ ) . 'assets/js/style-login.js', ['jquery'],time(), true );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );