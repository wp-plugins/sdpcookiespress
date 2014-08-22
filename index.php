<?php
/*
Plugin Name: SDP Cookies Press
Plugin URI: http://smartdataprotection.eu/store/cookies/wordpress
Description: La forma más sencilla , rápida y económica de cumplir la "ley de cookies".
Version: 1.2
Author: Smart Data Protection
Author URI: http://smartdataprotection.eu
License: GPL2
*/

//On active plugin
function get_sdp_cookie_defaults () {
	$options = $parameters = get_option('sdp_cookies_options');
	$parameters = array(
		'apiKey' => '',
		'license' => '',
		'notice' => 'Al utilizar nuestro sitio web, consiente nuestro uso de cookies de acuerdo con nuestra política de cookies.',
        'consentmodel' => 'CONSENT_1',
        'style' => 'STYLE_1',
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

//	this function adds the settings page to the Appearance tab
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

    }

	if ( $options != $parameters ) {
		$options = $parameters;
		update_option('sdp_cookies_options', $options);
	}
    ?>

    <header>
        <img class='img_logo' src='<?php echo plugins_url('/img/logo_sdp_complet_hor_300.png', __FILE__); ?>' />
        <h3>La solución más elegante para el cumplimiento de la <strong>ley de Cookies</strong>, de una forma rápida, sencilla y económica.</h3>
    </header>
    <div class="sdp_cookies_plugin_intro">
        <img class='img_cookies_friend' src='<?php echo plugins_url('/img/tu_amigo_cookies.png', __FILE__); ?>' />
        <div class="sdp_cookies_introtext">
            <p>Este plugin permite configurar automáticamente el sitio web con las especificaciones indicadas en la plataforma Smart Data Protection. Si necesitas más información puedes vistar nuestra página web <a href="http://smartdataprotection.eu/store/cookies/" target="_blank">http://smartdataprotection.eu/cookies</a></p>
        </div>
    </div>
    <form method="post" id="sdpCookiesForm">
        <hr>
        <h3>Licencia</h3>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"></th>
                <td>
                    <p>Introduce la APIKey que se te ha facilitado la plataforma Smart Data Protection</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">APIKey <span class="req">*</span> </th>
                <td>
                    <input type="text" name="apiKey" value="<?php echo $options['apiKey'] ?>" size="255" required="required" />
                </td>
            </tr>
            <?php if($options['apiKey']=="") { ?>
                <tr class="getLicense">
                    <th scope="row"></th>
                    <td>
                        Obtén tu licencia GRATUITA registrándote en <a href="http://goo.gl/HqqGJR" title="sdpcookies">http://smartdataprotection.eu/store/cookies</a>
                    </td>
                </tr>
            <?php } ?>
            <tr valign="top" class="ipTable" style="display: none;">
                <th scope="row">Detalles del sitio web</th>
                <td>
                    <div id="ipTable"></div>
                </td>
            </tr>
            <tr valign="top" class="cookiesTable" style="display: none;">
                <th scope="row">Cookies del sitio</th>
                <td>
                    <div id="cookiesTable"></div>
                </td>
            </tr>
        </table>
        <hr>
        <h3>Inserta el link de aviso legal</h3>
        <div class="sdp_warning">
            <h4>Atención</h4>
            <p>Debe estar en todas las páginas. Si usas un tema comprado, insertalo en la sección de footer. Sino, edita el fichero footer.php (Apariencia -> Editor -> footer.php)</p>
        </div>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><pre><code>&lt;a href="#" id="sdpCookiesAdviceLink" class="cookiesinfo">Aviso de cookies&lt;/a></code></pre></th>
            </tr>
        </table>
        <hr>
<!--        <p>Es tú responsabilidad revisar todos los texto y funcionalidad del plugin. Se trata de un sistema automatizado y por tanto dbes revisar que se adapta a tus necesidades y, en caso contrario, realizar las modificaciones oportunas.</p>-->
<!--        <hr>-->
        <input type="hidden" name="custom_submit" value="true" />
        <p class="submit"><input class="enviar_form" type="submit" value="Guardar" /></p>
	</form>

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

require('view.php');

?>