<?php
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
//include_once( ABSPATH . 'wp-admin/includes/option.php' );
//if(isset($_POST['go']))
//{
//    updateDateend($_POST['apiKey']);
//}
if ( !(is_plugin_active('wp-fastest-cache/wpFastestCache.php')) ) {
    function sdp_cookies_script() {
        $parameters = get_option('sdp_cookies_options');
        if($parameters["apiKey"]!=""){
            echo '<script type="text/javascript" src="http://smartdataprotection.eu/es/services/mcla/'.$parameters["apiKey"].'"></script>';
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


//function updateDateend ($api){
////    echo($api);
//
//    $ch = curl_init();
//
//    $data = array(
//        'apiKey' => $api
//    );
//
//    $data_string = json_encode($data);
//
//    // Setting options
//    curl_setopt($ch, CURLOPT_URL,"http://localhost/smartdataprotection/web/app_dev.php/es/services/getDateend");
//    curl_setopt($ch, CURLOPT_POST, 1);
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//    $sdp_response = curl_exec ($ch);
//
//    // cerramos la sesión cURL
//    curl_close ($ch);
//
////    echo $sdp_response;
//    $obj = json_decode($sdp_response);
//    $dateend = $obj->{'dateend'};
//
//    //receive the dateend and update the wordpress variable
//    $options = $parameters = get_option('sdp_cookies_options');
//    $parameters['dateEnd'] = $dateend;
//    if ( $options != $parameters ) {
//        $options = $parameters;
//        update_option('sdp_cookies_options', $options);
//    }
//}

?>