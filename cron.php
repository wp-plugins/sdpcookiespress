<?php
include('writer.php');


//Add new cron
function cron_add_second( $schedules ) {
    // Adds once second to the existing schedules.
    $schedules['second'] = array(
        'interval' => 1,
        'display' => __( 'Once second' )
    );
    return $schedules;
}
//
add_filter( 'cron_schedules', 'cron_add_second' );


//register_activation_hook(__FILE__, 'my_activation');

//Add
if ( (is_plugin_active('wp-fastest-cache/wpFastestCache.php')) ) {
    if (!wp_next_scheduled('check_script')) {
        wp_schedule_event(time(), 'second', 'check_script_hook');
    }
}

function checkScript()
{
//    $base = $_SERVER['DOCUMENT_ROOT'];
    $base = 'file://'. ABSPATH . 'wp-content/cache/all/';




    $parameters = get_option('sdp_cookies_options');
    $urls ="";
    if (!$parameters['apiKey']=="") {

        //get all the directories
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base)) as $filename)
        {
            $pos = strpos($filename, ".html");
            if($pos !== false){
                $urls = $urls . $filename . "||";
            }
        }
        $urls_array = explode("||", $urls);

        //For each path open the file and extract content
        foreach($urls_array as $aux){
            if(strlen($aux) > 9){
//                $file = fopen($aux, 'r');
//                $content = fread($file, filesize($aux));
//                echo($content);

//                fclose($file);
                $content = file_get_contents($aux);

                //check if the html has the script
                $pos = strpos($content, "es/services/mcla/");
                if($pos === false){

                    $ch = curl_init();

                    $data = array(
                        'token' => $parameters['apiKey'],
                        'content' => $content
                    );



                    $postString = http_build_query($data, '', '&');


                    // Setting options
                    curl_setopt($ch, CURLOPT_URL,"http://smartdataprotection.eu/es/services/block_cookies_wordpress");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $sdp_response = curl_exec ($ch);

                    // cerramos la sesión cURL
                    curl_close ($ch);

                    // hacemos lo que queramos con los datos recibidos
                    // por ejemplo, los mostramos

//                    echo($sdp_response);

                    //Write into cache
                    $file = fopen($aux, 'w');
                    fwrite($file, $sdp_response);
                    fclose($file);


                }else
                {
//                    echo("No hay que ejecutar el script");
                }
            }
        }
    } else {

    }

}
add_action('check_script_hook', 'checkScript');

