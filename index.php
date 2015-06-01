<?php
    //if(isset($_POST['go']))
    //{
    //    updateDateend($_POST['apiKey']);
    //}


/*
Plugin Name: SDP Cookies Press
Plugin URI: http://smartdataprotection.eu/store/cookies/wordpress
Description: La forma más sencilla , rápida y económica de cumplir la "ley de cookies".
Version: 2.0
Author: Smart Data Protection
Author URI: http://smartdataprotection.eu
License: GPL2
*/
//On active plugin
function get_sdp_cookie_defaults () {

    $options = $parameters = get_option('sdp_cookies_options');
    $parameters = array(
        'apiKey' => '',
        'dateEnd' => '',
        'mail' => '',
        'license' => '',
        'notice' => 'Al utilizar nuestro sitio web, consiente nuestro uso de cookies de acuerdo con nuestra política de cookies.',
        'consentmodel' => '',
        'style' => '',
        'automatic_page' => '1',
        'noticeUrl' => '',
        'gacode' => '',
    );

    update_option('sdp_cookies_options', $parameters);
}
register_activation_hook( __FILE__, 'get_sdp_cookie_defaults' );

//show notice when active
add_action('admin_notices', 'sdp_cookies_admin_notices');
function sdp_cookies_admin_notices() {
//    if (!get_option('sdp_cookies_notice_shown')){
    $parameters = get_option('sdp_cookies_options');
    if ($parameters['apiKey']==""){
        echo "
            <div class='updated sdp_cookies_notice'>
                <img class='img_logo_cookiespro' src='".plugins_url('/img/logo_sdp_complet_hor_300.png', __FILE__)."' />
                <a href='".get_admin_url(null, 'admin.php?page=sdpcookiespress/index.php')."' class='sdp_link' >Configura SDPCookiesPress</a>
                <p>Estás muy cerca de cumplir con la <em>Ley de Cookies</em>, solo debes activar tu cuenta.</p>
            </div>
        ";
    }
}

//  this function adds the settings page to the Appearance tab
function sdp_cookies_add_page() {
    add_menu_page('SDP Cookies', 'SDP Cookies', 'administrator', __FILE__, 'sdp_cookies',plugins_url('/img/cookies_icon.png', __FILE__));
}
add_action('admin_menu', 'sdp_cookies_add_page');

function sdp_cookies (){
    $options = $parameters = get_option('sdp_cookies_options');

    if (!empty($_POST["custom_submit"]) ) {

        $parameters['apiKey'] = strip_tags(stripslashes($_POST["apiKey"]));
        $parameters['style'] = strip_tags(stripslashes($_POST["style"]));
        $parameters['consentmodel'] = strip_tags(stripslashes($_POST["consentmodel"]));
        $parameters['dateEnd'] = strip_tags(stripslashes($_POST["dateEnd"]));

    }

    if ( $options != $parameters ) {
        $options = $parameters;
        update_option('sdp_cookies_options', $options);
    }
    ?>

    <header>
        <img class='img_logo' src='<?php echo plugins_url('/img/logo_sdp_complet_hor_300.png', __FILE__); ?>' />
        <h3>La solución profesional para cumplir la  <strong>"ley de Cookies"</strong>.</h3>
    </header>

    <div class="first_choice" style="display: none;">
        <p><button class="register">¡Regístrate ahora!</button></p>
        <p><button class="api">¿Ya tienes tu apiKey? Introdúcela!</button></p>
    </div>

    <div class="loading" style="display: none">
        <p>Procesando datos...</p>
    </div>

    <br>

    <div class="register_new" style="display: none">
        <form method="post" id="sdpCookiesForm">
            <table class="register_table" style="display: none;">
                <tr><td><h3>Registro en la aplicación</h3></td></tr>
                <tr valign="top">
                    <td><b>Introduce tu e-mail: <span class="req">*</span></b></td>
                </tr>

                <tr>
                    <td>
                        <input type="text" name="mail" value="" size="255" required="required" />
                        <br>
                        <br>
                    </td>
                </tr>

                <tr>
                    <td><b>Selecciona el estilo del aviso: <span class="req">*</span> </b></td>
                </tr>
                <tr>
                    <td>
                        <select name="style">
                            <option value="STYLE_1">Estilo 1</option>
                            <option value="STYLE_2">Estilo 2 </option>
                        </select>

                        <a href="#" class="showStyles">Ver estilos</a>
                        <a href="#" class="hideStyles" style="display: none;">Ocultar estilos</a>

                        <div id="showStyles" style="display: none;">
                            <figure class="cookies_style">
                                <img alt="cookies xandrusoft" src="<?php echo plugins_url('/img/examples/cookie_notice_1.png', __FILE__); ?>">
                                <figcaption>Estilo 1</figcaption>
                            </figure>
                            <figure class="cookies_style">
                                <img alt="cookies xandrusoft" src="<?php echo plugins_url('/img/examples/cookie_notice_2.png', __FILE__); ?>">
                                <figcaption>Estilo 2</figcaption>
                            </figure>
                        </div>
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <td><b>Tipo de consentimiento: (Ambos estan contemplados por la ley)<span class="req">*</span> </b></td>
                </tr>
                <tr>
                    <td>
                        <select name="consentmodel">
                            <option value="CONSENT_1">Cuando el usuario navege por el sitio</option>
                            <option value="CONSENT_2">Cuando el usuario pulse ACEPTAR </option>
                        </select>
                    </td>
                </tr>


            </table>

            <table class="reg_api" style="display: none;">
                <tr><td><h3>Licencia</h3></td></tr>
                <tr>
                    <td>Introduce aquí tu APIKey que se te ha facilitado la plataforma Smart Data Protection</td>
                </tr>
                <tr valign="top">
                    <td scope="row"><b>APIKey </b> <span class="req">*</span> </td>
                </tr>
                <tr>
                    <td>
                        <input type="text" name="apiKey" value="<?php echo $options['apiKey'] ?>" size="255"/>
                        <input type="hidden" name="dateEnd" value="<?php echo $options['dateEnd'] ?>" size="255"/>
                    </td>
                </tr>
            </table>

            <tr>
                <td>
                    <input type="hidden" name="custom_submit" value="true" />
                    <p class="submit"><input class="enviar_form" type="submit" value="Guardar"/></p>

                </td>
            </tr>

        </form>

    </div>
    <div class="api_table" style="display: none;">
        <table>
            <tr>
                <td>
                    <h2>¡Enhorabuena! Ya estás cumpliendo con la ley de cookies!</h2>
                    <p>Ya tienes instalado el banner de cookies y los textos del aviso legal. <strong>Te hemos dado ¡un mes de prueba gratis!</strong></p>
                    <p> Recuerda que debes insertar el link al aviso de cookies (<code>&lt;a href="#" id="sdpCookiesAdviceLink" class="cookiesinfo">Aviso de cookies&lt;/a></code>) en todas las páginas. Un buen sitio puede ser el footer o pie de página. </p>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                    if ( !(is_plugin_active('wp-fastest-cache/wpFastestCache.php')) ){
                        echo '<p> <strong>Si quieres cumplir al 100% la ley española se deben bloquear las cookies. Para ello nuestro plugin necesita instales el plugin WP Fastest Cache <a href="https://wordpress.org/plugins/wp-fastest-cache/">Plugin Fastest Cache</a></strong></p>';
                    }else{
                        echo '<p><strong>¡Además te puedes sentir orgulloso al cumplir al 100% con la ley!</strong></p>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><a href="http://smartdataprotection.eu/es/legal/cpc/cookies/"> Condiciones de contratación de SDP COOKIES</a></td>
            </tr>


        </table>
    </div>
    <br>
    <div class="api_table dateend" style="display: none">
        <form action="<?php updateDateend($options['apiKey']); ?>" method="post">
            <h4>Tú licencia termina el: <?php echo $options['dateEnd'] ?></h4>
            <input type="hidden" name="apiKey" value="<?php echo $options['apiKey'] ?>" size="255"/>
<!--            <input type="submit" name="go" class="update" value="Actualizar" />-->
        </form>
        <br>
        <a id="pricingLink" class="sdp_link">Descubre por qué utilizar SDP y no un plugin cualquier otro gratuito</a>
        <!-- comparing table -->
        <div id="pricingTable" class="tsc_pricingtable03 tsc_pt3_style1" style="display: none;">
            <div class="caption_column">
                <ul>
                    <li class="header_row_1 align_center radius5_topleft"></li>
                    <li class="header_row_2">
                        <h2 class="caption">Requisitos legales</h2>
                    </li>
                    <li class="row_style_4"><span>Mostrar banner aviso</span></li>
                    <li class="row_style_2"><span>Crear textos legales</span></li>
                    <li class="row_style_4"><span>Detallar las cookies utilizadas</span></li>
                    <li class="row_style_2"><span class="subtitle">-- Funcionalidades avanzadas --</span></li>
                    <li class="row_style_4"><span>Detectar las cookies autom.</span></li>
                    <li class="row_style_2"><span>Textos legales multidioma (es, en, fr, de, it)</span></li>
                    <li class="row_style_4"><span>Actualizar ante cambios leyes</span></li>
                    <li class="row_style_2"><span>Escanéo autom. de la web</span></li>
                    <!--                <li class="footer_row"></li>-->
                </ul>
            </div>
            <div class="column_1">
                <ul>
                    <li class="header_row_1 align_center">
                        <h2 class="col1">Otros</h2>
                    </li>
                    <li class="header_row_2 align_center">
                        <h1 class="col1">Gratis</h1>
                        <h3 class="col1">¿Seguro?</h3>
                    </li>
                    <li class="row_style_3 align_center"><span class="pricing_yes"></span></li>
                    <li class="row_style_1 align_center">Algunos</li>
                    <li class="row_style_3 align_center"><span class="pricing_no"></span></li>
                    <li class="row_style_1 align_center"></li>
                    <li class="row_style_3 align_center"><span class="pricing_no"></span></li>
                    <li class="row_style_1 align_center"><span class="pricing_no"></span></li>
                    <li class="row_style_3 align_center"><span class="pricing_no"></span></li>
                    <li class="row_style_1 align_center"><span class="pricing_no"></span></li>
                    <!--                <li class="footer_row"></li>-->
                </ul>
            </div>
            <div class="column_3">
                <ul>
                    <li class="header_row_1 align_center">
                        <h2 class="col3">SDP Cookies</h2>
                    </li>
                    <li class="header_row_2 align_center">
                        <h1 class="col3"><span>29</span>€</h1>
                        <h3 class="col3">por año</h3>
                    </li>
                    <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
                    <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
                    <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
                    <li class="row_style_2 align_center"></li>
                    <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
                    <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
                    <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
                    <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
                    <!--                <li class="footer_row"><a href="" class="tsc_buttons2 grey">sign up!</a></li>-->
                </ul>
            </div>
        </div>
        <!-- comparing table -->
    </div>

<?php
}



require('scripts.php');




function create_new_page() {

    //
//    if(is_user_admin()){
    $titulo = 'Aviso legal Cookies';
    $slug = 'aviso-de-cookies';
    $the_page = get_page_by_title( $titulo );

    if ( ! $the_page ) {

        // Se crea el objeto del post
        $_p = array();
        $_p['post_title'] = $titulo;
        $_p['post_content'] = file_get_contents( plugins_url( 'notice.html', __FILE__ ) );
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';

        // Se guarda en la base de datos
        $page_id = wp_insert_post( $_p );

    }
    else {
        // significa que el plugin ya estaba activado antes

        $page_id = $the_page->ID;

        //Por si no esta publicada, la publicamos
        $the_page->post_status = 'publish';
        $page_id = wp_update_post( $the_page );

    }
    delete_option( 'sdp_cookies_page_id' );
    add_option( 'sdp_cookies_page_id', $page_id );
//    }
}

function updateDateend ($api){
//    echo($api);

    $ch = curl_init();

    $data = array(
        'apiKey' => $api
    );

    $data_string = json_encode($data);

    // Setting options

    curl_setopt($ch, CURLOPT_URL,"https://smartdataprotection.eu/es/services/getDateend");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $sdp_response = curl_exec ($ch);

    // cerramos la sesión cURL
    curl_close ($ch);

//    echo $sdp_response;
    $obj = json_decode($sdp_response);
    $dateend = $obj->{'dateend'};

    //receive the dateend and update the wordpress variable
    $options = $parameters = get_option('sdp_cookies_options');
    $parameters['dateEnd'] = $dateend;
    if ( $options != $parameters ) {
        $options = $parameters;
        update_option('sdp_cookies_options', $options);
    }
}


require('view.php');

////----- CRON WORDPRESS
require('cron.php');

?>