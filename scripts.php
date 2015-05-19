<?php
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );


if ( !(is_plugin_active('wp-fastest-cache/wpFastestCache.php')) ) {
    function sdp_cookies_script() {
        $parameters = get_option('sdp_cookies_options');
        if($parameters["apiKey"]!=""){
            echo '<script type="text/javascript" src="http://test2.smartdataprotection.eu/es/services/mcla/'.$parameters["apiKey"].'"></script>';
        }
    }
    add_action( 'wp_head', 'sdp_cookies_script', 1);
}

//admin files
function sdp_cookies_admin_style() {
    wp_enqueue_style('my-admin-theme', plugins_url('css/admin_style.css', __FILE__));

    if ( !(is_plugin_active('wp-fastest-cache/wpFastestCache.php')) ){
        wp_enqueue_script('sdp_admin', plugins_url('js/sdp_admin.js', __FILE__), array('jquery'),false,'1.2.1');
    }else{
        wp_enqueue_script('sdp_admin_cache', plugins_url('js/sdp_admin_cache.js', __FILE__), array('jquery'),false,'1.2.1');
    }
}
add_action('admin_enqueue_scripts', 'sdp_cookies_admin_style');

?>