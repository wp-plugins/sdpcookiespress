<?php
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
//include_once( ABSPATH . 'wp-admin/includes/option.php' );
//if(isset($_POST['go']))
//{
//    updateDateend($_POST['apiKey']);
//}
    function sdp_cookies_script() {
        $parameters = get_option('sdp_cookies_options');
        if($parameters["apiKey"]!=""){
            echo "<script type='text/javascript' src = '//smartdataprotection.eu/es/services/mcla/".$parameters["apiKey"]."'></script>";
//            echo "<script type='text/javascript'>(function() {var s = document.createElement('script');s.type = 'text/javascript';s.async = true;s.src = 'http://smartdataprotection.eu/es/services/mcla/".$parameters["apiKey"]."';var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);})();</script>";

//            echo "<script type='text/javascript' async=true src='//smartdataprotection.eu/es/services/mcla/".$parameters["apiKey"]."'></script>";

//            echo "
//                <script type='text/javascript'>
//
//                (function() {
//                    var s = document.createElement('script');
//                    s.type = 'text/javascript';
//                    s.async = false;
//                    s.src = '//smartdataprotection.eu/es/services/mcla/".$parameters['apiKey']."';
//                    var x = document.getElementsByTagName('script')[0];
//                    x.parentNode.insertBefore(s, x);
//                })();
//
//                </script>
//            ";
        }
    }
    add_action( 'wp_head', 'sdp_cookies_script', 1);

//admin files
function sdp_cookies_admin_style() {
    wp_enqueue_style('sdp-admin-theme', plugins_url('css/admin_style.css', __FILE__));
    $parameters = get_option('sdp_cookies_options');
    wp_enqueue_script('sdp_admin', plugins_url('js/sdp_admin.js', __FILE__), array('jquery'),false,'1.2.1');
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
//    curl_setopt($ch, CURLOPT_URL,"https://smartdataprotection.eu/es/services/getDateend");
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