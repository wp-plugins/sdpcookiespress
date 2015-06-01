<?php

if(isset($_POST['content']) AND isset($_POST['common_url']))
{
    sdp_update_cache($_POST['content'], $_POST['common_url']);
}


if(isset($_POST['url']))
{
    sdp_get_directories($_POST['url']);
}

function sdp_update_cache($content, $url)
{
    $base = $_SERVER['DOCUMENT_ROOT'];
    $url = $base . $url;

    if (!$file = fopen($url, 'w')) {
        echo "No se puede abrir el archivo ($url)";
        exit;
    }

    // Escribir $contenido a nuestro archivo abierto.
    if (fwrite($file, $content) === FALSE) {
        echo "No se puede escribir en el archivo ($url)";
        exit;
    }

    echo "Éxito, se escribió ($content) en el archivo ($url)";

    fclose($file);
}

function sdp_get_directories($url_comun){
    $urls ="";
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($url_comun)) as $filename)
    {
        $pos = strpos($filename, ".html");
        if($pos !== false){
            $urls = $urls . $filename . "||";
        }
    }
    print_r($urls);
}