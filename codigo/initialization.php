<?php

/**
 * INITIALIZATION FILE
 */
require dirname(__FILE__) . '/../vendor/autoload.php';

header('Content-Type: text/html; charset=utf-8');





/**
 * INCORRECT ACCESS TO APPLICATION
 */
if (basename($_SERVER['PHP_SELF']) == 'initialization.php') {
    die('Acceso incorrecto a la aplicación.');
}






/**
 * APP PATH, DATA PATH
 */
define('APP_PATH', dirname(__FILE__));
define('DATA_PATH', APP_PATH . '/data');





/**
 * MULTI DB
 */
define('MULTI-DB', true);





/**
 * LICENCIA ON/OFF
 */
$Usar_Licencia = false;


/**
 * CONNECTION LIBRARIES
 */
require_once(APP_PATH . '/libs/misc/registry.class.php');
require_once(APP_PATH . '/libs/misc/https.functions.php');
require_once(APP_PATH . '/libs/misc/misc.functions.php');
require_once(APP_PATH . '/libs/sesiones/sessions.php');



/**
 * HTTPS VS HTTP
 */
$http_s = "";
if (isHTTPS()) {
    $http_s     = "https";
} else {
    $http_s     = "http";
}




/**
 * UPLOAD CONFIG REGISTRY FOR APPLICATION
 */
$Registry = Registry::getInstance(dirname(__FILE__) . '/config/configuration.ini');



define( 'WEB_ROOT'  ,   $Registry->general['web_root']      );
define( 'GAE'       ,   $Registry->general['gae']           );



/**
 * SUBDOMAIN
 */
$array_dominio              = explode(".", $_SERVER['HTTP_HOST']);
$subdominio_inseguro        = array_shift($array_dominio);
global $subdominio;
$subdominio                 = preg_replace("/[^a-zA-Z0-9]+/", "", $subdominio_inseguro);


// PATHS DEFINITION
define(     'GS_TEMP_BUCKET'                ,       'gs://enpunto/temp/'                                );
define(     'GS_CLIENTS_BUCKET'             ,       'gs://enpunto/clients/'                             );
define(     'GS_CLIENT_BUCKET'              ,       'gs://enpunto/clients/' . $subdominio . "/"         );
define(     'GS_CLIENT_SESSIONS'            ,       GS_CLIENT_BUCKET        . 'sesiones/'               );
define(     'GS_CLIENT_IMAGES_PERSONAS'     ,       GS_CLIENT_BUCKET        . 'imagenes/personas/'      );
define(     'GS_CLIENT_IMAGES_LOGO'         ,       GS_CLIENT_BUCKET        . 'imagenes/logo/'          );
define(     'GS_CLIENT_IMAGES_USUARIOS'     ,       GS_CLIENT_BUCKET        . 'imagenes/usuarios/'      );
define(     'GS_CLIENT_TEMP_FOLDER'         ,       GS_CLIENT_BUCKET        . 'temp/'                   );
define(     'FIREBASE_URL'                  ,       'https://enpunto-1286.firebaseio.com'               );
define(     'FIREBASE_TOKEN'                ,       'vXW6SniwKlPl4Lu6dQxZuSSQsWLLJWnSuBbImNC7'          );
define(     'FIREBASE_BASE_REF'             ,       'clients/'              .$subdominio                );

define('ACCESS_TOKEN_MERCADOPAGO_SUSCRIPCIONES', 'APP_USR-1762536926307627-081006-53a401c2c4ba7bc9c1744cf8141e0881-239560895"');


/**
 * MAGIC QUOTES PROBLEM SOLUTION
 * magic_quotes_gpc en On //http://stackoverflow.com/questions/517008/how-to-turn-off-magic-quotes-on-shared-hosting
 */
if (in_array(strtolower(ini_get('magic_quotes_gpc')), array('1', 'on'))) {
//  $_POST = array_map( 'stripslashes', $_POST );
    $_GET = array_map('stripslashes', $_GET);
    $_REQUEST = array_map('stripslashes', $_REQUEST);
    $_COOKIE = array_map('stripslashes', $_COOKIE);
}



/**
 * HIDE ERRORS DEBUG
 * //if ($Registry->general['debug']) {
 */
if ($subdominio == 'dev') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL | E_STRICT);
    ini_set('log_errors', 0);
}
else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_DEPRECATED);
    ini_set('log_errors', 1);
    ini_set('error_log', $Registry->general['error_log']);
}





/**
 * CHACHE OFF

header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

*/



/**
 * CONECT DB WITH CLASSES mySQL 1
 */
require_once($Registry->general['library_path'] . '/mysql/mysql.class.php');


/**
 * CONECT DB WITH CLASSES mySQL 2
 */
$G_DbConnMGR = new mySQL(
    $Registry->database['db_name'],
    $Registry->database['db_user'],
    $Registry->database['db_pass'],
    $Registry->database['db_host'],
    $Registry->database['db_port']
);


/**
 * CONECT DB WITH CLASSES mySQL 3
 */
// CONECTAR SOCKET GAE
if (GAE) {
    if (!$G_DbConnMGR->ConectarSocket()) {
        die($G_DbConnMGR->get_Error($Registry->general['debug']));
    }
}
else {
    if (!$G_DbConnMGR->Conectar()) {
        die($G_DbConnMGR->get_Error($Registry->general['debug']));
    }
}


/**
 * CONECT DB WITH CLASSES mySQL 4
 */
$Registry->DbConnMGR = $G_DbConnMGR;
spl_autoload_register(function ($class_name) {
    $Registy = Registry::getInstance();
    foreach ($Registy->autoload['paths'] as $path) {
        $filepath = $path . '/' . strtolower($class_name) . '.class.php';

        if (is_file($filepath)) {
            require_once $filepath;
            break;
        }
    }
});


// CRON
if (isset($_SERVER['X-Appengine-Cron']) && $_SERVER['X-Appengine-Cron'] == true) {
    $Registry->DbConn = $G_DbConnMGR;
}
else if($subdominio=='login' || $subdominio=='setup'|| $subdominio=='register' ) { // REGISTER CASE TEST

    $Registry->DbConn = $G_DbConnMGR;
}
else {

    $o_Cliente = Cliente_L::obtenerPorSubdominio($subdominio);

    if (isset($o_Cliente) && $o_Cliente != null) {

        global $clienteId;
        $clienteId = $o_Cliente->getId();

        $G_DbConn = new mySQL(
            $o_Cliente->getDBname(),
            $o_Cliente->getDBuser(),
            $o_Cliente->getDBpass(),
            $o_Cliente->getDBhost(),
            $o_Cliente->getDBport()
        );


        if (GAE) {
            if (!$G_DbConn->ConectarSocket()) {
                die($G_DbConnMGR->get_Error($Registry->general['debug']));
            }
        } else {
            if (!$G_DbConn->Conectar()) {
                die($G_DbConnMGR->get_Error($Registry->general['debug']));
            }
        }

        $Registry->DbConn = $G_DbConn;


        global $a_modulos_permisos_Cliente;
        $a_modulos_permisos_Cliente = array();
        $T_Suscripciones = Suscripcion_L::obtenerTodosPorClienteId($clienteId);

        // ACTUALIZAR SUSCRIPCIONES DE CLIENTE
        foreach ($T_Suscripciones as $t_Suscripcion_Id => $t_Suscripcion) {

            // VARIABLES
            $_status = $t_Suscripcion->get_status('AR');

            // SUSCRIPCION CANCELADA: SALIR
            if ($_status == "Cancelada") continue;

            // ACTUALIZAR SUSCRIPCION MERCADOPAGO
            $t_Suscripcion->update_suscripcion_MercadoPago();
            $t_Suscripcion->save();

            // SUSCRIPCION ACTIVA
            if ($_status == 'Activa'){

                $modulos_permisos_id = $t_Suscripcion->get_Modulos_Permisos_Id();

                // OBTENER MODULOS_PERMISOS GLOBALES DE LA SUSCRIPCION
                $o_Modulos_Permisos             = Modulos_Permisos_L::obtenerPorId($modulos_permisos_id);
                $a_modulos_permisos_Cliente     = $o_Modulos_Permisos->getArray();

            }


            if (!empty($a_modulos_permisos_Cliente)){
                define   ('PERMISOS_MOD_CONFIGURACIONES_EDITAR' ,$a_modulos_permisos_Cliente['mod_configuraciones_editar']);
                define   ('PERMISOS_MOD_CONFIGURACIONES_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_configuraciones_cantidad']);
                define   ('PERMISOS_MOD_INICIO_EDITAR' ,$a_modulos_permisos_Cliente['mod_inicio_editar']);
                define   ('PERMISOS_MOD_INICIO_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_inicio_cantidad']);
                define   ('PERMISOS_MOD_PERSONA_CREAR' ,$a_modulos_permisos_Cliente['mod_persona_crear']);
                define   ('PERMISOS_MOD_PERSONA_EDITAR' ,$a_modulos_permisos_Cliente['mod_persona_editar']);
                define   ('PERMISOS_MOD_PERSONA_ELIMINAR' ,$a_modulos_permisos_Cliente['mod_persona_eliminar']);
                define   ('PERMISOS_MOD_PERSONA_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_persona_cantidad']);
                define   ('PERMISOS_MOD_PERSONA_HUELLAS_CREAR' ,$a_modulos_permisos_Cliente['mod_persona_huellas_crear']);
                define   ('PERMISOS_MOD_PERSONA_HUELLAS_EDITAR' ,$a_modulos_permisos_Cliente['mod_persona_huellas_editar']);
                define   ('PERMISOS_MOD_PERSONA_HUELLAS_ELIMINAR' ,$a_modulos_permisos_Cliente['mod_persona_huellas_eliminar']);
                define   ('PERMISOS_MOD_PERSONA_HUELLAS_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_persona_huellas_cantidad']);
                define   ('PERMISOS_MOD_PERSONA_RFID_CREAR' ,$a_modulos_permisos_Cliente['mod_persona_rfid_crear']);
                define   ('PERMISOS_MOD_PERSONA_RFID_EDITAR' ,$a_modulos_permisos_Cliente['mod_persona_rfid_editar']);
                define   ('PERMISOS_MOD_PERSONA_RFID_ELIMINAR' ,$a_modulos_permisos_Cliente['mod_persona_rfid_eliminar']);
                define   ('PERMISOS_MOD_PERSONA_RFID_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_persona_rfid_cantidad']);
                define   ('PERMISOS_MOD_GRUPO_CREAR' ,$a_modulos_permisos_Cliente['mod_grupo_crear']);
                define   ('PERMISOS_MOD_GRUPO_EDITAR' ,$a_modulos_permisos_Cliente['mod_grupo_editar']);
                define   ('PERMISOS_MOD_GRUPO_ELIMINAR' ,$a_modulos_permisos_Cliente['mod_grupo_eliminar']);
                define   ('PERMISOS_MOD_GRUPO_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_grupo_cantidad']);
                define   ('PERMISOS_MOD_HORARIO_TRABAJO_CREAR' ,$a_modulos_permisos_Cliente['mod_horario_trabajo_crear']);
                define   ('PERMISOS_MOD_HORARIO_TRABAJO_EDITAR' ,$a_modulos_permisos_Cliente['mod_horario_trabajo_editar']);
                define   ('PERMISOS_MOD_HORARIO_TRABAJO_ELIMINAR' ,$a_modulos_permisos_Cliente['mod_horario_trabajo_eliminar']);
                define   ('PERMISOS_MOD_HORARIO_TRABAJO_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_horario_trabajo_cantidad']);
                define   ('PERMISOS_MOD_HORARIO_FLEXIBLE_CREAR' ,$a_modulos_permisos_Cliente['mod_horario_flexible_crear']);
                define   ('PERMISOS_MOD_HORARIO_FLEXIBLE_EDITAR' ,$a_modulos_permisos_Cliente['mod_horario_flexible_editar']);
                define   ('PERMISOS_MOD_HORARIO_FLEXIBLE_ELIMINAR' ,$a_modulos_permisos_Cliente['mod_horario_flexible_eliminar']);
                define   ('PERMISOS_MOD_HORARIO_FLEXIBLE_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_horario_flexible_cantidad']);
                define   ('PERMISOS_MOD_HORARIO_MULTIPLE_CREAR' ,$a_modulos_permisos_Cliente['mod_horario_multiple_crear']);
                define   ('PERMISOS_MOD_HORARIO_MULTIPLE_EDITAR' ,$a_modulos_permisos_Cliente['mod_horario_multiple_editar']);
                define   ('PERMISOS_MOD_HORARIO_MULTIPLE_ELIMINAR' ,$a_modulos_permisos_Cliente['mod_horario_multiple_eliminar']);
                define   ('PERMISOS_MOD_HORARIO_MULTIPLE_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_horario_multiple_cantidad']);
                define   ('PERMISOS_MOD_HORARIO_ROTATIVO_CREAR' ,$a_modulos_permisos_Cliente['mod_horario_rotativo_crear']);
                define   ('PERMISOS_MOD_HORARIO_ROTATIVO_EDITAR' ,$a_modulos_permisos_Cliente['mod_horario_rotativo_editar']);
                define   ('PERMISOS_MOD_HORARIO_ROTATIVO_ELIMINAR' ,$a_modulos_permisos_Cliente['mod_horario_rotativo_eliminar']);
                define   ('PERMISOS_MOD_HORARIO_ROTATIVO_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_horario_rotativo_cantidad']);
                define   ('PERMISOS_MOD_LICENCIA_CREAR' ,$a_modulos_permisos_Cliente['mod_licencia_crear']);
                define   ('PERMISOS_MOD_LICENCIA_EDITAR' ,$a_modulos_permisos_Cliente['mod_licencia_editar']);
                define   ('PERMISOS_MOD_LICENCIA_ELIMINAR' ,$a_modulos_permisos_Cliente['mod_licencia_eliminar']);
                define   ('PERMISOS_MOD_LICENCIA_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_licencia_cantidad']);
                define   ('PERMISOS_MOD_SUSPENSION_CREAR' ,$a_modulos_permisos_Cliente['mod_suspension_crear']);
                define   ('PERMISOS_MOD_SUSPENSION_EDITAR' ,$a_modulos_permisos_Cliente['mod_suspension_editar']);
                define   ('PERMISOS_MOD_SUSPENSION_ELIMINAR' ,$a_modulos_permisos_Cliente['mod_suspension_eliminar']);
                define   ('PERMISOS_MOD_SUSPENSION_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_suspension_cantidad']);
                define   ('PERMISOS_MOD_FERIADO_CREAR' ,$a_modulos_permisos_Cliente['mod_feriado_crear']);
                define   ('PERMISOS_MOD_FERIADO_EDITAR' ,$a_modulos_permisos_Cliente['mod_feriado_editar']);
                define   ('PERMISOS_MOD_FERIADO_ELIMINAR' ,$a_modulos_permisos_Cliente['mod_feriado_eliminar']);
                define   ('PERMISOS_MOD_FERIADO_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_feriado_cantidad']);
                define   ('PERMISOS_MOD_ALERTA_CREAR' ,$a_modulos_permisos_Cliente['mod_alerta_crear']);
                define   ('PERMISOS_MOD_ALERTA_EDITAR' ,$a_modulos_permisos_Cliente['mod_alerta_editar']);
                define   ('PERMISOS_MOD_ALERTA_ELIMINAR' ,$a_modulos_permisos_Cliente['mod_alerta_eliminar']);
                define   ('PERMISOS_MOD_ALERTA_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_alerta_cantidad']);
                define   ('PERMISOS_MOD_REPORTE_AUTOMATICO_CREAR' ,$a_modulos_permisos_Cliente['mod_reporte_automatico_crear']);
                define   ('PERMISOS_MOD_REPORTE_AUTOMATICO_EDITAR' ,$a_modulos_permisos_Cliente['mod_reporte_automatico_editar']);
                define   ('PERMISOS_MOD_REPORTE_AUTOMATICO_ELIMINAR' ,$a_modulos_permisos_Cliente['mod_reporte_automatico_eliminar']);
                define   ('PERMISOS_MOD_REPORTE_AUTOMATICO_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_reporte_automatico_cantidad']);
                define   ('PERMISOS_MOD_REPORTE_REGISTROS_CREAR' ,$a_modulos_permisos_Cliente['mod_reporte_registros_crear']);
                define   ('PERMISOS_MOD_REPORTE_REGISTROS_DESCARGAR' ,$a_modulos_permisos_Cliente['mod_reporte_registros_descargar']);
                define   ('PERMISOS_MOD_REPORTE_REGISTROS_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_reporte_registros_cantidad']);
                define   ('PERMISOS_MOD_REPORTE_MARCACIONES_CREAR' ,$a_modulos_permisos_Cliente['mod_reporte_marcaciones_crear']);
                define   ('PERMISOS_MOD_REPORTE_MARCACIONES_DESCARGAR' ,$a_modulos_permisos_Cliente['mod_reporte_marcaciones_descargar']);
                define   ('PERMISOS_MOD_REPORTE_MARCACIONES_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_reporte_marcaciones_cantidad']);
                define   ('PERMISOS_MOD_REPORTE_ASISTENCIAS_CREAR' ,$a_modulos_permisos_Cliente['mod_reporte_asistencias_crear']);
                define   ('PERMISOS_MOD_REPORTE_ASISTENCIAS_DESCARGAR' ,$a_modulos_permisos_Cliente['mod_reporte_asistencias_descargar']);
                define   ('PERMISOS_MOD_REPORTE_ASISTENCIAS_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_reporte_asistencias_cantidad']);
                define   ('PERMISOS_MOD_REPORTE_AUSENCIAS_CREAR' ,$a_modulos_permisos_Cliente['mod_reporte_ausencias_crear']);
                define   ('PERMISOS_MOD_REPORTE_AUSENCIAS_DESCARGAR' ,$a_modulos_permisos_Cliente['mod_reporte_ausencias_descargar']);
                define   ('PERMISOS_MOD_REPORTE_AUSENCIAS_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_reporte_ausencias_cantidad']);
                define   ('PERMISOS_MOD_REPORTE_LLEGADAS_TARDE_CREAR' ,$a_modulos_permisos_Cliente['mod_reporte_llegadas_tarde_crear']);
                define   ('PERMISOS_MOD_REPORTE_LLEGADAS_TARDE_DESCARGAR' ,$a_modulos_permisos_Cliente['mod_reporte_llegadas_tarde_descargar']);
                define   ('PERMISOS_MOD_REPORTE_LLEGADAS_TARDE_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_reporte_llegadas_tarde_cantidad']);
                define   ('PERMISOS_MOD_REPORTE_SALIDAS_TEMPRANO_CREAR' ,$a_modulos_permisos_Cliente['mod_reporte_salidas_temprano_crear']);
                define   ('PERMISOS_MOD_REPORTE_SALIDAS_TEMPRANO_DESCARGAR' ,$a_modulos_permisos_Cliente['mod_reporte_salidas_temprano_descargar']);
                define   ('PERMISOS_MOD_REPORTE_SALIDAS_TEMPRANO_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_reporte_salidas_temprano_cantidad']);
                define   ('PERMISOS_MOD_REPORTE_JORNADAS_CREAR' ,$a_modulos_permisos_Cliente['mod_reporte_jornadas_crear']);
                define   ('PERMISOS_MOD_REPORTE_JORNADAS_DESCARGAR' ,$a_modulos_permisos_Cliente['mod_reporte_jornadas_descargar']);
                define   ('PERMISOS_MOD_REPORTE_JORNADAS_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_reporte_jornadas_cantidad']);
                define   ('PERMISOS_MOD_REPORTE_INTERVALOS_CREAR' ,$a_modulos_permisos_Cliente['mod_reporte_intervalos_crear']);
                define   ('PERMISOS_MOD_REPORTE_INTERVALOS_DESCARGAR' ,$a_modulos_permisos_Cliente['mod_reporte_intervalos_descargar']);
                define   ('PERMISOS_MOD_REPORTE_INTERVALOS_CANTIDAD' ,$a_modulos_permisos_Cliente['mod_reporte_intervalos_cantidad']);
                define   ('PERMISOS_MOD_CONFIGURACIONES_VER' ,$a_modulos_permisos_Cliente['mod_configuraciones_ver']);
                define   ('PERMISOS_MOD_INICIO_VER' ,$a_modulos_permisos_Cliente['mod_inicio_ver']);
                define   ('PERMISOS_MOD_PERSONA_VER' ,$a_modulos_permisos_Cliente['mod_persona_ver']);
                define   ('PERMISOS_MOD_PERSONA_HUELLAS_VER' ,$a_modulos_permisos_Cliente['mod_persona_huellas_ver']);
                define   ('PERMISOS_MOD_PERSONA_RFID_VER' ,$a_modulos_permisos_Cliente['mod_persona_rfid_ver']);
                define   ('PERMISOS_MOD_GRUPO_VER' ,$a_modulos_permisos_Cliente['mod_grupo_ver']);
                define   ('PERMISOS_MOD_HORARIO_TRABAJO_VER' ,$a_modulos_permisos_Cliente['mod_horario_trabajo_ver']);
                define   ('PERMISOS_MOD_HORARIO_FLEXIBLE_VER' ,$a_modulos_permisos_Cliente['mod_horario_flexible_ver']);
                define   ('PERMISOS_MOD_HORARIO_MULTIPLE_VER' ,$a_modulos_permisos_Cliente['mod_horario_multiple_ver']);
                define   ('PERMISOS_MOD_HORARIO_ROTATIVO_VER' ,$a_modulos_permisos_Cliente['mod_horario_rotativo_ver']);
                define   ('PERMISOS_MOD_LICENCIA_VER' ,$a_modulos_permisos_Cliente['mod_licencia_ver']);
                define   ('PERMISOS_MOD_SUSPENSION_VER' ,$a_modulos_permisos_Cliente['mod_suspension_ver']);
                define   ('PERMISOS_MOD_FERIADO_VER' ,$a_modulos_permisos_Cliente['mod_feriado_ver']);
                define   ('PERMISOS_MOD_ALERTA_VER' ,$a_modulos_permisos_Cliente['mod_alerta_ver']);
                define   ('PERMISOS_MOD_REPORTE_AUTOMATICO_VER' ,$a_modulos_permisos_Cliente['mod_reporte_automatico_ver']);
                define   ('PERMISOS_MOD_REPORTE_REGISTROS_VER' ,$a_modulos_permisos_Cliente['mod_reporte_registros_ver']);
                define   ('PERMISOS_MOD_REPORTE_MARCACIONES_VER' ,$a_modulos_permisos_Cliente['mod_reporte_marcaciones_ver']);
                define   ('PERMISOS_MOD_REPORTE_ASISTENCIAS_VER' ,$a_modulos_permisos_Cliente['mod_reporte_asistencias_ver']);
                define   ('PERMISOS_MOD_REPORTE_AUSENCIAS_VER' ,$a_modulos_permisos_Cliente['mod_reporte_ausencias_ver']);
                define   ('PERMISOS_MOD_REPORTE_LLEGADAS_TARDE_VER' ,$a_modulos_permisos_Cliente['mod_reporte_llegadas_tarde_ver']);
                define   ('PERMISOS_MOD_REPORTE_SALIDAS_TEMPRANO_VER' ,$a_modulos_permisos_Cliente['mod_reporte_salidas_temprano_ver']);
                define   ('PERMISOS_MOD_REPORTE_JORNADAS_VER' ,$a_modulos_permisos_Cliente['mod_reporte_jornadas_ver']);
                define   ('PERMISOS_MOD_REPORTE_INTERVALOS_VER' ,$a_modulos_permisos_Cliente['mod_reporte_intervalos_ver']);
            }

        }

    }
    else {
        $o_Cliente = null;
    }


//PROTECCION SQL
    /*  por ahora desactivo esto en el post
    foreach( array_keys($_POST) as $key){
            if(!is_array($_POST[$key])){
                    //echo $key;
                                    $variable=$_POST[$key];
                                    $temp_re= mysqli_real_escape_string($Registry->DbConn->getLink(),$variable);
                                    $temp_r = str_replace ( array("<",">","[","]","*","^","="), "" , $temp_re);
                    $_POST [ $key ] = $temp_r;
                    //echo "$_POST:".$key."->".$_POST [ $key ];
            }
    }
     */
    foreach (array_keys($_REQUEST) as $key) {
        if (!is_array($_REQUEST[$key])) {
            //echo $key;
            $variable = $_REQUEST[$key];
            $temp_re = mysqli_real_escape_string($Registry->DbConn->getLink(), $variable);
            $temp_r = str_replace(array("<", ">", "[", "]", "*", "^", "="), "", $temp_re);
            $_REQUEST [$key] = $temp_r;
            //echo "$_REQUEST:".$key."->".$_REQUEST [ $key ];
        }
    }
    foreach (array_keys($_GET) as $key) {
        if (!is_array($_GET[$key])) {
            //echo $key;
            $variable = $_GET[$key];
            $temp_re = mysqli_real_escape_string($Registry->DbConn->getLink(), $variable);
            $temp_r = str_replace(array("<", ">", "[", "]", "*", "^", "="), "", $temp_re);
            $_GET [$key] = $temp_r;
            //echo "$_GET:".$key."->".$_GET [ $key ];
        }
    }

}

$timezone='';

/**
 * SESSION CONFIG
 */
$sessioName = $Registry->session['name'];
session_name($sessioName);
session_set_cookie_params(0);
//session_save_path(GS_CLIENT_SESSIONS);


//este es el session handler que usa el storage y el memcache
/*
$sessionHandler = null;
$sessionHandler = new enPuntoSessionHandler(GS_CLIENT_SESSIONS,true);
session_set_save_handler(array(&$sessionHandler, "open"), array(&$sessionHandler, "close"), array(&$sessionHandler, "read"), array(&$sessionHandler, "write"), array(&$sessionHandler, "destroy"), array(&$sessionHandler, "gc"));
*/

session_start();



/**
 * COOKIES CONFIG
 */
if ($Registry->session['lifetime'] > 0 && isset($_COOKIE[$sessioName])) {
    $cookieParams = session_get_cookie_params();
    $cookieParams['httponly'] = isset($cookieParams['httponly']) ? $cookieParams['httponly'] : false;
    setcookie(session_name(), session_id(), time() + $Registry->session['lifetime'], $cookieParams['path'], $cookieParams['domain'], $cookieParams['secure'], $cookieParams['httponly']);
}



/**
 * CRON CONFIG
 */
if (isset($_SERVER['X-Appengine-Cron']) && $_SERVER['X-Appengine-Cron'] == true) {//esto aca es una atada con alambre terrible
    //vengo de un cron!!!
    function _($variable) {
        return $variable;
    }
    $language = 'es_AR.utf8';
    setlocale(LC_ALL, $language);

}
else {
    if ($Registry->general['gettext_enable']) {

        //una vez que tengo la conexion, cargo el locale e inicio gettext
        putenv("LANGUAGE=");
        $language = Config_L::p("lenguaje");
        putenv("LANG=$language");
        setlocale(LC_ALL, $language);
        $domain = 'messages';
        bindtextdomain($domain, APP_PATH . "/locale");
        textdomain($domain);

    }
    else {
        function _($variable) {
            return $variable;
        }

        $language = Config_L::p("lenguaje");
        setlocale(LC_ALL, $language);
    }
    //TIMEZONE
    $timezone = Config_L::p('timezone');
    date_default_timezone_set($timezone);  //php timezone
    Registry::getInstance()->DbConn->Query("SET time_zone = '" . $timezone . "';");   //sql timezone
    //el timezone se carga aca para uso general, pero tambien se tiene que cargar en los cron y sync.php

}




/**
 * CHECK LOGIN VALID. SEND TO LOGIN PAGE OTHERWISE 1
 */
$T_Error                    =       ''          ;
$necesitaIniciarSesion      =       false       ;
$o_Usuario                  =       null        ;

if      (!isset($_SESSION['USUARIO']))      {
    $necesitaIniciarSesion = true;
}
elseif  (isset($_SESSION['USUARIO']))       {

    $o_Usuario = Usuario_L::obtenerPorId($_SESSION['USUARIO']['id']);

    if (is_null($o_Usuario)) {
        SeguridadHelper::Bloqueardo($_SESSION['USUARIO']['id'], _('Usuario Bloqueado.'));
        $_SESSION = array();
        $necesitaIniciarSesion = true;
        $T_Error = _('Usuario temporalmente bloqueado.');
    }
    else {//login correcto
        $Registry->Usuario = $o_Usuario;
    }
}


/**
 * CHECK LOGIN VALID. SEND TO LOGIN PAGE OTHERWISE 2
 */
if (
    basename($_SERVER['PHP_SELF'])          == 'register.php'                       ||
    //basename($_SERVER['PHP_SELF'])        == 'vtex_a_facturante'                  ||
    basename($_SERVER['PHP_SELF'])          == 'sync.php'                           ||
    basename($_SERVER['PHP_SELF'])          == 'cron.php'                           ||
    basename($_SERVER['PHP_SELF'])          == 'cron_reportes_automaticos.php'      ||
    basename($_SERVER['PHP_SELF'])          == 'password.php'                       ||
    basename($_SERVER['SCRIPT_NAME'])       == 'api.php'                            ||
    //basename($_SERVER['SCRIPT_NAME'])     == 'demo.php'                           ||
    basename($_SERVER['SCRIPT_NAME'])       == 'task.php'                           ||
    basename($_SERVER['SCRIPT_NAME'])       == 'manager.php'                        ||
    basename($_SERVER['PHP_SELF'])          == 'cron_message.php'
)
{
    //pasa solo si viene de la pagina sync_o.php o cron.php

}
else {

    if ($necesitaIniciarSesion && basename($_SERVER['PHP_SELF']) != 'login.php' ) {
        SeguridadHelper::Entrar();

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            die('login');
        }
        else{
            if (isset($_REQUEST['setup'])){
                header('Location: ' . WEB_ROOT . '/login.php?setup=1');
            }
            else{
                header('Location: ' . WEB_ROOT . '/login.php');
            }
        }
        exit();
    }
}

if (!isset($_POST['register'])){
    unset($Registry, $o_Usuario);
}








/**
 * GENERAL ARRAY DEFINITIONS
 */
$a_Acciones                                     =   array(
    _('Crear'), _('Modificar'), _('Habilitar'), _('Bloquear'), _('Agregar'), _('Quitar'), _('Eliminar'), _('Borrar'), _('Reiniciar'), _('Re-Enviar'), _('Re-Sync'), _('Backup'), _('JavaScript'));
$a_Salida                                       =   array(
    1 => 'S-01', 'S-02', 'S-03', 'S-04', 'S-05', 'S-06', 'S-07', 'S-08' );
$a_Lector                                       =   array(
    1 => 'L-01', 'L-02', 'L-03', 'L-04', 'L-05', 'L-06', 'L-07', 'L-08' );
$a_Pulsador                                     =   array(
    1 => 'P-01', 'P-02', 'P-03', 'P-04', 'P-05', 'P-06', 'P-07', 'P-08' );

$a_Notificaciones_Tipos                         =   array(
    1 => _("Email"),
    _("Mensaje de Inicio"),
    _("Llamada IP")
);
$a_Notificaciones_Contenidos_Tipos              =   array(
    1 => _("Aviso"),
    _("Reporte")
);
$a_Notificaciones_Contenidos_Intervalos         =   array(
    1 => _("Dia"),
    _("Semana"),
    _("Quincena"),
    _("Mes")//,
    //_("Año")
);
$a_Notificaciones_Contenidos_Repetir            =   array(
    // _("Única Vez"),
    1 => _("Diariamente"),
    _("Semanalmente"),
    _("Quincenalmente"),
    _("Mensualmente"),
    _("Anualmente")
);
$a_Notificaciones_Reporte_Descargar_Tipo        =   array(
    1 => _("PDF"),
    _("CSV"),
    _("EXCEL")
);

$dias                                           =   array(
    "Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb");
$dias_red                                       =   array(
    _("Dom"), _("Lun"), _("Mar"), _("Mie"), _("Jue"), _("Vie"), _("Sab"));
$dias_red_1                                     =   array(
    _("Dom"), _("Lun"), _("Mar"), _("Mie"), _("Jue"), _("Vie"), _("Sab"));
$dias_red_2                                     =   array(
    1 => _("Dom"), _("Lun"), _("Mar"), _("Mie"), _("Jue"), _("Vie"), _("Sab"));
$meses                                          =   array(
    _("Enero"), _("Febrero"), _("Marzo"), _("Abril"), _("Mayo"), _("Junio"), _("Julio"), _("Agosto"), _("Septiembre"), _("Octubre"), _("Noviembre"), _("Diciembre"));
$a_meses                                        =   array(
    1 => _("Enero"), _("Febrero"), _("Marzo"), _("Abril"), _("Mayo"), _("Junio"), _("Julio"), _("Agosto"), _("Septiembre"), _("Octubre"), _("Noviembre"), _("Diciembre"));
$a_dias                                         =   array(
    1 => _('Domingo'), _('Lunes'), _('Martes'), _('Miércoles'), _('Jueves'), _('Viernes'), _('Sábado'));
$a_abr_dias                                     =   array(
    1 => "Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb");

$IntervalosFechas                               =   array(
    'F_Hoy' => _("Hoy"),
    'F_Ayer' => _("Ayer"),
    'F_Semana' => _("Esta Semana"),
    'F_Semana_Pasada' => _("La Semana Pasada"),
    'F_Quincena' => _("Esta Quincena"),
    'F_Quincena_Pasada' => _("La Quincena Pasada"),
    'F_Mes' => _("Este Mes"),
    'F_Mes_Pasado' => _("El Mes Pasado"),
    //'F_Ano' =>_("Este Año"),
    'F_Personalizado' => _("Personalizado:")
);
$SelectorMinutosHoras                           =   array(
    'F_Minutos' => _("Minutos"),
    'F_Horas' => _("Horas")
);
$IntervalosLlegadaTardeLicencias                =   array(
    'F_15' => _("15 Minutos"),
    'F_30' => _("30 Minutos"),
    'F_1' => _("1 Hora"),
    'F_2' => _("2 Horas"),
    'F_Personalizado' => _("Personalizado:")
);
$IntervalosSalidaTempranoLicencias              =   array(
    'F_15' => _("15 Minutos"),
    'F_30' => _("30 Minutos"),
    'F_1' => _("1 Hora"),
    'F_2' => _("2 Horas"),
    'F_3' => _("3 Horas"),
    'F_Personalizado' => _("Personalizado:")
);
$TiposdeLicenciasRepetitivas                    =   array(
    '0' => _("No repetir"),
    '1' => _("Todas las semanas")//,
    // '2' => _("Todos los meses"),
);


$Filtro_Mostrar ['Intervalo']                   = [
    'reporte',
    'feriado',
    'licencia',
    'suspension'
];



if (!isset($_SESSION['js'])) {
    $_SESSION['js']         = 1;
    $_SESSION['count']      = 0;
}

$rutaBackup         = APP_PATH . '/../backups'  ;
$webRutaBackup      = WEB_ROOT . '/backups'     ;


/* * *******************************************
 * NOTIFICACIONES
 * DISPARADORES
 *
 * ******************************************* */
define      (   'NOT_REINICIO_EQUIPO'          ,   180     );
define      (   'NOT_PERDIDA_DE_CONEXION'      ,   200     );

define      (   'NOT_LLEGADA_TARDE'            ,   500     );
define      (   'NOT_LLEGADA_TEMPRANA'         ,   520     );
define      (   'NOT_SALIDA_TARDE'             ,   540     );
define      (   'NOT_SALIDA_TEMPRANA'          ,   560     );
define      (   'NOT_AUSENCIA'                 ,   580     );
define      (   'NOT_TRACKING_PERSONA'         ,   600     );


$Notificaciones_Disparadores                    =   array(
    NOT_LLEGADA_TARDE => _("Llegada Tarde"),
    NOT_LLEGADA_TEMPRANA => _("Llegada Temprana"),
    NOT_SALIDA_TARDE => _("Salida Tarde"),
    NOT_AUSENCIA => _("Ausencias"),
    NOT_SALIDA_TEMPRANA => _("Salida Temprana"),
    NOT_PERDIDA_DE_CONEXION => _("Pérdida de conexión con Equipo")
);
$Notificaciones_Disparadores_1                  =   array(
    NOT_LLEGADA_TARDE => _("Llegada Tarde"),
    NOT_SALIDA_TEMPRANA => _("Salida Temprana")//,
    //NOT_AUSENCIA => _("Ausencias")
);


/*********************************************
 * NOTIFICACIONES
 * CONTENIDO
 *
 *********************************************/
define      (   'NOT_REPORTE_DE_PERSONA'                   , 10     );
define      (   'NOT_REPORTE_DE_EQUIPO'                    , 12     );
define      (   'NOT_REPORTE_DE_LLEGADA_TARDE'             , 19     );
define      (   'NOT_REPORTE_DE_ENTRADAS_SALIDAS'          , 20     );
define      (   'NOT_REPORTE_DE_DIAS_HORAS_TRABAJADAS'     , 21     );
define      (   'NOT_REPORTE_DE_AUSENCIAS'                 , 22     );
define      (   'NOT_REPORTE_DE_PAYRROLL'                  , 26     );

define      (   'REPORTE_MARCACIONES'           ,   40    );
define      (   'REPORTE_JORNADAS'              ,   41    );
define      (   'REPORTE_INTERVALO'             ,   42    );
define      (   'REPORTE_LLEGADAS_TARDE'        ,   43    );
define      (   'REPORTE_SALIDAS_TEMPRANO'      ,   44    );
define      (   'REPORTE_LISTADO_ASISTENCIAS'   ,   45    );
define      (   'REPORTE_LISTADO_AUSENCIAS'     ,   46    );





$Notificaciones_Contenidos                      =   array(
    NOT_REPORTE_DE_LLEGADA_TARDE => _("Reporte de Llegadas Tarde"),
    NOT_REPORTE_DE_ENTRADAS_SALIDAS => _("Reporte de Entradas/Salidas"),
    NOT_REPORTE_DE_DIAS_HORAS_TRABAJADAS => _("Reporte de Días/Horas Trabajadas"),
    NOT_REPORTE_DE_AUSENCIAS => _("Reporte de Ausencias"),
    NOT_REPORTE_DE_EQUIPO => _("Reporte por Equipo"),
    NOT_REPORTE_DE_PAYRROLL => _("Reporte de Liquidaciones")
);

// abduls
$Notificaciones_Contenidos_1                    =   array(
    NOT_REPORTE_DE_LLEGADA_TARDE => _("Reporte de Llegadas Tarde"),
    NOT_REPORTE_DE_ENTRADAS_SALIDAS => _("Reporte de Entradas/Salidas"),
    NOT_REPORTE_DE_DIAS_HORAS_TRABAJADAS => _("Reporte de Días/Horas Trabajadas"),
    NOT_REPORTE_DE_AUSENCIAS => _("Reporte de Ausencias"),
    NOT_REPORTE_DE_PAYRROLL => _("Reporte de Liquidaciones")
);

$Notificaciones_Contenidos_V2   =   array(
    REPORTE_MARCACIONES             => _("Marcaciones"),
    REPORTE_JORNADAS                => _("Jornadas"),
    REPORTE_INTERVALO               => _("Intervalo"),
    REPORTE_LLEGADAS_TARDE          => _("Llegadas Tarde"),
    REPORTE_SALIDAS_TEMPRANO        => _("Salidas Temprano"),
    REPORTE_LISTADO_ASISTENCIAS     => _("Asistencias"),
    REPORTE_LISTADO_AUSENCIAS       => _("Ausencias")
);



define('MOD_TK_822_F', 5);
$Equipos_Modelos                                 =  array(
    MOD_TK_822_F => _("TK-822F")

);


//echo 'FechaD: '.$_SESSION['filtro']['fechaD'].'<br>';
//echo 'FechaH: '.$_SESSION['filtro']['fechaH'];


//Definiciones DEDOS
define('LEFT_THUMB', 1);
define('LEFT_INDEX', 2);
define('LEFT_MIDDLE', 3);
define('LEFT_RING', 4);
define('LEFT_LITTLE', 5);
define('RIGHT_THUMB', 6);
define('RIGHT_INDEX', 7);
define('RIGHT_MIDDLE', 8);
define('RIGHT_RING', 9);
define('RIGHT_LITTLE', 10);



$Intervalos_De_Horarios_Rotativos_Repetitivos = array(
    1 => _("1"),
    _("2"),
    _("3"),
    _("4"),
    _("5"),
    _("6")
);



$a_Tipos_De_Horario = array(
    1 => _("Horario Normal"),
    2 => _("Horario Flexible"),
    3 => _("Horario Rotativo"),
    4 => _("Horario Múltiple")
);

//Definiciones HORARIOS
define('HORARIO_NORMAL', 1);
define('HORARIO_FLEXIBLE', 2);
define('HORARIO_ROTATIVO', 3);
define('HORARIO_MULTIPLE', 4);


//Definiciones de Licencias
define('LICENCIA_DIA_COMPLETO', 1);
define('LICENCIA_PERSONALIZADA', 2);

$a_Licencias_Tipos = array(
    LICENCIA_DIA_COMPLETO => 'Día Completo',
    LICENCIA_PERSONALIZADA => 'Personalizada'
);

//Definiciones de Suspensiones
define('SUSPENSION_DIA_COMPLETO', 1);
define('SUSPENSION_PERSONALIZADA', 2);

$a_Suspensions_Tipos = array(
    SUSPENSION_DIA_COMPLETO => 'Día Completo',
    SUSPENSION_PERSONALIZADA => 'Personalizada'
);

//Definiciones de Permisos
define('PERMISO_LLEGADA_TARDE', 1);
define('PERMISO_SALIDA_TEMPRANO', 2);
define('PERMISO_DIA_COMPLETO', 3);
define('PERMISO_PERSONALIZADA', 4);

$a_Permisos_Tipos = array(
    PERMISO_LLEGADA_TARDE => 'Llegada Tarde',
    PERMISO_SALIDA_TEMPRANO => 'Salida Temprano',
    PERMISO_DIA_COMPLETO => 'Día Completo',
    PERMISO_PERSONALIZADA => 'Personalizada'
);


define('FERIADO_DIA_COMPLETO', 1);
define('FERIADO_PERSONALIZADO', 2);

$a_Feriados_Tipos = array(
    FERIADO_DIA_COMPLETO => _("Día Completo"),
    FERIADO_PERSONALIZADO => _("Personalizado")
);


//Definiciones TIPOS DE LOG WEB
define('LOG_PERSONA_CREAR', 1);
define('LOG_PERSONA_EDITAR', 2);
define('LOG_PERSONA_ELIMINAR', 3);
define('LOG_PERSONA_BLOQUEAR', 4);
define('LOG_PERSONA_DESBLOQUEAR', 5);

define('LOG_USUARIO_CREAR', 20);
define('LOG_USUARIO_EDITAR', 21);
define('LOG_USUARIO_ELIMINAR', 22);
define('LOG_USUARIO_BLOQUEAR', 23);
define('LOG_USUARIO_DESBLOQUEAR', 24);
define('LOG_USUARIO_LOGIN_CORRECTO', 25);
define('LOG_USUARIO_LOGIN_ERROR', 26);
define('LOG_USUARIO_PASSWORD_RESET', 27);

define('LOG_HORARIO_ROTATIVO_CREAR', 40);
define('LOG_HORARIO_ROTATIVO_EDITAR', 41);
define('LOG_HORARIO_ROTATIVO_ELIMINAR', 42);

define('LOG_HORARIO_FLEXIBLE_CREAR', 50);
define('LOG_HORARIO_FLEXIBLE_EDITAR', 51);
define('LOG_HORARIO_FLEXIBLE_ELIMINAR', 52);

define('LOG_HORARIO_MULTIPLE_CREAR', 55);
define('LOG_HORARIO_MULTIPLE_EDITAR', 56);
define('LOG_HORARIO_MULTIPLE_ELIMINAR', 57);

define('LOG_HORARIO_NORMAL_CREAR', 60);
define('LOG_HORARIO_NORMAL_EDITAR', 61);
define('LOG_HORARIO_NORMAL_ELIMINAR', 62);

define('LOG_LICENCIA_CREAR', 70);
define('LOG_LICENCIA_EDITAR', 71);
define('LOG_LICENCIA_ELIMINAR', 72);


define('LOG_FERIADO_CREAR', 80);
define('LOG_FERIADO_EDITAR', 81);
define('LOG_FERIADO_ELIMINAR', 82);

define('LOG_GRUPO_CREAR', 90);
define('LOG_GRUPO_EDITAR', 91);
define('LOG_GRUPO_ELIMINAR', 92);

define('LOG_TRANSACCION_CHECKOUT_OK', 100);
define('LOG_TRANSACCION_CHECKOUT_ERROR', 101);
define('LOG_SUSCRIPCION_CHECKOUT_OK', 102);
define('LOG_SUSCRIPCION_CHECKOUT_ERROR', 103);

define('LOG_API_ACTIVAR', 120);
define('LOG_API_GENERAR_KEY', 121);
define('LOG_API_REGENERAR_KEY', 122);
define('LOG_API_DESACTIVAR', 123);
define('LOG_API_MODO_DE_PRUEBAS_ON', 124);
define('LOG_API_MODO_DE_PRUEBAS_OFF', 125);

define('LOG_HUELLA_ENROLL_START', 160);
define('LOG_HUELLA_DELETE', 161);
define('LOG_HUELLA_ENROLL_OK', 162);
define('LOG_HUELLA_ENROLL_CANCEL', 163);
define('LOG_HUELLA_CREAR', 164);
define('LOG_HUELLA_EDITAR', 165);

define('LOG_RFID_READ_START', 170);
define('LOG_RFID_DELETE', 171);
define('LOG_RFID_READ_OK', 172);
define('LOG_RFID_READ_CANCEL', 173);
define('LOG_RFID_READ_ERROR', 174);

define('LOG_EQUIPO_CREAR', 180);
define('LOG_EQUIPO_EDITAR', 181);
define('LOG_EQUIPO_ELIMINAR', 182);
define('LOG_EQUIPO_BLOQUEAR', 183);
define('LOG_EQUIPO_DESBLOQUEAR', 184);

define('LOG_LOG_CREAR', 200);
define('LOG_LOG_EDITAR', 201);
define('LOG_LOG_ELIMINAR', 202);
define('LOG_LOG_EDICION_HABILITADA', 203);
define('LOG_LOG_EDICION_DESHABILITADA', 204);

define('LOG_PERMISO_CREAR', 210);
define('LOG_PERMISO_EDITAR', 211);
define('LOG_PERMISO_ELIMINAR', 212);

define('LOG_SUSPENSION_CREAR', 220);
define('LOG_SUSPENSION_EDITAR', 221);
define('LOG_SUSPENSION_ELIMINAR', 222);

//array de tipos de log

$a_Logs_Tipos = array(
    LOG_PERSONA_CREAR => 'Persona - Creada',
    LOG_PERSONA_EDITAR => 'Persona - Editada',
    LOG_PERSONA_ELIMINAR => 'Persona - Eliminada',
    LOG_PERSONA_BLOQUEAR => 'Persona - Bloqueada',
    LOG_PERSONA_DESBLOQUEAR => 'Persona - Desbloqueada',
    LOG_USUARIO_CREAR => 'Usuario - Creado',
    LOG_USUARIO_EDITAR => 'Usuario - Editado',
    LOG_USUARIO_ELIMINAR => 'Usuario - Eliminado',
    LOG_USUARIO_BLOQUEAR => 'Usuario - Bloqueado',
    LOG_USUARIO_DESBLOQUEAR => 'Usuario - Desbloqueado',
    LOG_USUARIO_LOGIN_CORRECTO => 'Usuario - Login OK',
    LOG_USUARIO_LOGIN_ERROR => 'Usuario - Login ERROR',
    LOG_USUARIO_PASSWORD_RESET => 'Usuario - Reset Pass',
    LOG_HORARIO_ROTATIVO_CREAR => 'Horario Rotativo - Creado',
    LOG_HORARIO_ROTATIVO_EDITAR => 'Horario Rotativo - Editado',
    LOG_HORARIO_ROTATIVO_ELIMINAR => 'Horario Rotativo - Eliminado',
    LOG_HORARIO_FLEXIBLE_CREAR => 'Horario Flexible - Creado',
    LOG_HORARIO_FLEXIBLE_EDITAR => 'Horario Flexible - Editado',
    LOG_HORARIO_FLEXIBLE_ELIMINAR => 'Horario Flexible - Eliminado',
    LOG_HORARIO_MULTIPLE_CREAR => 'Horario Múltiple - Creado',
    LOG_HORARIO_MULTIPLE_EDITAR => 'Horario Múltiple - Editado',
    LOG_HORARIO_MULTIPLE_ELIMINAR => 'Horario Múltiple - Eliminado',
    LOG_HORARIO_NORMAL_CREAR => 'Horario - Creado',
    LOG_HORARIO_NORMAL_EDITAR => 'Horario - Editado',
    LOG_HORARIO_NORMAL_ELIMINAR => 'Horario - Eliminado',
    LOG_LICENCIA_CREAR => 'Licencia - Creada',
    LOG_LICENCIA_EDITAR => 'Licencia - Editada',
    LOG_LICENCIA_ELIMINAR => 'Licencia - Eliminada',
    LOG_FERIADO_CREAR => 'Feriado - Creado',
    LOG_FERIADO_EDITAR => 'Feriado - Editado',
    LOG_FERIADO_ELIMINAR => 'Feriado - Eliminado',
    LOG_GRUPO_CREAR => 'Grupo - Creado',
    LOG_GRUPO_EDITAR => 'Grupo - Editado',
    LOG_GRUPO_ELIMINAR => 'Grupo - Eliminado',
    LOG_TRANSACCION_CHECKOUT_OK => 'Transacciones - OK',
    LOG_TRANSACCION_CHECKOUT_ERROR => 'Transacciones - Error',
    LOG_SUSCRIPCION_CHECKOUT_OK => 'Suscripciones - OK',
    LOG_SUSCRIPCION_CHECKOUT_ERROR => 'Suscripciones - Error',
    LOG_API_ACTIVAR => 'API - Activada',
    LOG_API_GENERAR_KEY => 'API - Key Generada',
    LOG_API_REGENERAR_KEY => 'API - Key Re-Generada',
    LOG_API_DESACTIVAR => 'API - Desactivada',
    LOG_API_MODO_DE_PRUEBAS_ON => 'API - Modo de Pruebas Activado',
    LOG_API_MODO_DE_PRUEBAS_OFF => 'API - Modo de Pruebas Desctivado',
    LOG_HUELLA_ENROLL_START => 'Huella - Inicio de carga de huella',
    LOG_HUELLA_DELETE => 'Huella - Huella eliminada',
    LOG_HUELLA_ENROLL_OK => 'Huella - Carga finalizada correctamente',
    LOG_HUELLA_ENROLL_CANCEL => 'Huella - Carga cancelada',
    LOG_HUELLA_CREAR => 'Huella - Creada',
    LOG_HUELLA_EDITAR => 'Huella - Editada',
    LOG_RFID_READ_START => 'RFID - Inicio de carga de Tag',
    LOG_RFID_DELETE => 'RFID - Tag eliminado',
    LOG_RFID_READ_OK => 'RFID - Carga de Tag finalizada correctamente',
    LOG_RFID_READ_CANCEL => 'RFID - Carga cancelada',
    LOG_RFID_READ_ERROR => 'RFID - Error de carga',
    LOG_EQUIPO_CREAR => 'Equipo - Creado',
    LOG_EQUIPO_EDITAR => 'Equipo - Editado',
    LOG_EQUIPO_ELIMINAR => 'Equipo - Eliminado',
    LOG_EQUIPO_BLOQUEAR => 'Equipo - Bloqueado',
    LOG_EQUIPO_DESBLOQUEAR => 'Equipo - Desbloqueado',
    LOG_LOG_CREAR => 'Registro - Creado',
    LOG_LOG_EDITAR => 'Registro - Editado',
    LOG_LOG_ELIMINAR => 'Registro - Eliminado',
    LOG_LOG_EDICION_HABILITADA => 'Edición de registros habilitada',
    LOG_LOG_EDICION_DESHABILITADA => 'Edición de registros deshabilitada',
    LOG_PERMISO_CREAR => 'Permiso - Creada',
    LOG_PERMISO_EDITAR => 'Permiso - Editada',
    LOG_PERMISO_ELIMINAR => 'Permiso - Eliminada',
    LOG_SUSPENSION_CREAR => 'Suspensión - Creada',
    LOG_SUSPENSION_EDITAR => 'Suspensión - Editada',
    LOG_SUSPENSION_ELIMINAR => 'Suspensión - Eliminada'
);

/*
echo "session name: ".session_name()."<br />";
echo "session save path: ".session_save_path()."<br />";
echo "session cookie params: "; echo "<pre>";print_r(session_get_cookie_params());echo "</pre>"; echo "<br />";
*/




//logs equipos

define('LOGE_NULL', 0);
define('LOGE_INGRESO_CORRECTO', 1);
define('LOGE_PERSONA_NO_REGISTRADA', 2);
define('LOGE_PERSONA_DESACTIVADA', 3);

$a_Logs_Accion = array(
    LOGE_NULL => "NULL",
    LOGE_INGRESO_CORRECTO => "INGRESO CORRECTO",
    LOGE_PERSONA_NO_REGISTRADA => "PERSONA NO REGISTRADA",
    LOGE_PERSONA_DESACTIVADA => "PERSONA DESACTIVADA",
);


//defines para la sincronizacion

define('CMD_SYNC', "CMD_SYNC");
define('CMD_ACK', "CMD_ACK");
define('CMD_FIRST_START', "CMD_FIRST_START");
define('CMD_FIRST_START_CONFIG', "CMD_FIRST_START_CONFIG");
define('CMD_CONFIG', "CMD_CONFIG");
define('ACK', "ACK");
define('CMD_ENROLL_START', "CMD_ENROLL_START");
define('ACK_ENROLL_START', "ACK_ENROLL_START");
define('CMD_ENROLL_CANCEL', "CMD_ENROLL_CANCEL");
define('CMD_ENROLL_STATUS', "CMD_ENROLL_STATUS");
define('CMD_ENROLL_OK', "CMD_ENROLL_OK");
define('CMD_RFID_READ_START', "CMD_RFID_READ_START");
define('ACK_RFID_READ_START', "ACK_RFID_READ_START");
define('CMD_RFID_READ_CANCEL', "CMD_RFID_READ_CANCEL");
define('CMD_RFID_READ_STATUS', "CMD_RFID_READ_STATUS");
define('CMD_RFID_READ_OK', "CMD_RFID_READ_OK");
define('CMD_LOG', "CMD_LOG");
define('ACK_LOG', "ACK_LOG");
define('CMD_PING', "CMD_PING");
define('CMD_PONG', "CMD_PONG");
define('CMD_ACK_ELIMINADO', "CMD_ACK_ELIMINADO");
define('CMD_REBOOT', "CMD_REBOOT");
define('CMD_RESTART_APP', "CMD_RESTART_APP");
define('CMD_RESET_READER', "CMD_RESET_READER");
define('CMD_FORCE_PING', "CMD_FORCE_PING");
define('CMD_BLINK', "CMD_BLINK");
define('CMD_CLEAR_NETWORK_INFO', "CMD_CLEAR_NETWORK_INFO");
define('CMD_RESET_WIRELESS_NETWORK',"CMD_RESET_WIRELESS_NETWORK");
define('CMD_PURGE_DATABASE', "CMD_PURGE_DATABASE");
define('CMD_LOCK_UPDATES', "CMD_LOCK_UPDATES");
define('CMD_UNLOCK_UPDATES', "CMD_UNLOCK_UPDATES");
define('CMD_UNBLOCK', "CMD_UNBLOCK");
define('CMD_BLOCK', "CMD_BLOCK");
define('CMD_MAINTENANCE_ENABLE', "CMD_MAINTENANCE_ENABLE");
define('CMD_MAINTENANCE_DISABLE', "CMD_MAINTENANCE_DISABLE");
define('CMD_DEBUG_INFO', "CMD_DEBUG_INFO");




define('TYPE_PERSON', "TYPE_PERSON");
define('TYPE_FINGERPRINT', "TYPE_FINGERPRINT");
define('TYPE_NORMAL_HOURS', "TYPE_NORMAL_HOURS");
define('TYPE_FLEX_HOURS', "TYPE_FLEX_HOURS");
define('TYPE_ROTATIVE_HOURS', "TYPE_ROTATIVE_HOURS");
define('TYPE_COMPANIES', "TYPE_COMPANIES");
define('TYPE_GROUPS', "TYPE_GROUPS");
define('TYPE_GROUPS_PERSONS', "TYPE_GROUPS_PERSONS");
define('TYPE_HOLIDAYS', "TYPE_HOLIDAYS");
define('TYPE_LICENSES', "TYPE_LICENSES");
define('TYPE_CONFIG', "TYPE_CONFIG");






define('TRANSACTION_PENDING', 1);
define('TRANSACTION_APPROVED', 2);
define('TRANSACTION_REJECTED', 3);
define('TRANSACTION_PAID', 4);
define('TRANSACTION_UNPAID', 5);
define('TRANSACTION_REFUNDED', 6);

$a_Estados_Transacciones = array(
    TRANSACTION_PENDING => 'Pendiente',
    TRANSACTION_APPROVED => 'Aprobada',
    TRANSACTION_REJECTED => 'Rechazada',
    TRANSACTION_PAID => 'Pagada',
    TRANSACTION_UNPAID => 'Sin Pagar'
);


define("IVA", 1.21);
define("IVA_MOSTRAR", 21);


/* datos payu sandbox */
/*
define("PAYU_API_KEY",'4Vj8eK4rloUd272L48hsrarnUA');
define("PAYU_API_LOGIN",'pRRXKOl8ikMmt9u');
define("PAYU_ACCOUNT_ID",'512322');
define("PAYU_MERCHANT_ID",'508029');
define("PAYU_LANGUAGE",'es');
define("PAYU_IS_TEST",true);
define("PAYU_API_PAYMENTS_URL",'https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi');
define("PAYU_API_REPORTS_URL",'https://sandbox.api.payulatam.com/reports-api/4.0/service.cgi');
define("PAYU_SUSCRIPTIONS_URL",'https://sandbox.api.payulatam.com/payments-api/rest/v4.3/');
*/
/* datos payu reales */

/* fabri */
define("PAYU_API_KEY",'6V5rQjO56DSyr4U7KcBCzoz52A');
define("PAYU_API_LOGIN",'YedrDJ16MVKNtTm');
define("PAYU_ACCOUNT_ID",'540353');
define("PAYU_MERCHANT_ID",'538292');
define("PAYU_LANGUAGE",'es');
define("PAYU_IS_TEST",true);
define("PAYU_API_PAYMENTS_URL",'https://api.payulatam.com/payments-api/4.0/service.cgi');
define("PAYU_API_REPORTS_URL",'https://api.payulatam.com/reports-api/4.0/service.cgi');
define("PAYU_SUSCRIPTIONS_URL",'https://api.payulatam.com/payments-api/rest/v4.3/');





define('HEARTBEAT_TIME',300);
define('HEARTBEAT_OFFLINE_MARGIN',400);


//para el reporte de entradas/salidas
define('COL_DISPOSITIVO_WIDTH',12);


//si ya esta logueado y esta haciendo un setup, lo mando directamente a la pagina de setup
if(!$necesitaIniciarSesion && isset($_REQUEST['setup']))
    header('Location: ' . WEB_ROOT . '/#setup');

if(!$necesitaIniciarSesion && isset($_REQUEST['vtex_a_facturante']))
    header('Location: ' . WEB_ROOT . '/#vtex_a_facturante');

//pubsub


$psclient = new Google_Client();
$psclient->useApplicationDefaultCredentials();//usa las credenciales de app engine
$psclient->addScope([Google_Service_Pubsub::PUBSUB,Google_Service_Storage::CLOUD_PLATFORM]);

