<?php

function sdp_cookies_script() {
    $parameters = get_option('sdp_cookies_options');
    if($parameters["apiKey"]!=""){
        echo '<script type="text/javascript" src="http://services.smartdataprotection.eu/es/services/mcla/'.$parameters["apiKey"].'"></script>';
    }
}
add_action( 'wp_head', 'sdp_cookies_script', 1);

//admin files
function sdp_cookies_admin_style() {
    wp_enqueue_style('my-admin-theme', plugins_url('css/admin_style.css', __FILE__));
    wp_enqueue_script('sdp_admin', plugins_url('js/sdp_admin.js', __FILE__), array('jquery'),false,'1.2.1');
}
add_action('admin_enqueue_scripts', 'sdp_cookies_admin_style');

?>