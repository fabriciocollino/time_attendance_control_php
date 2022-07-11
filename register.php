<?php
require 'vendor/autoload.php';
use Google\Cloud\PubSub\PubSubClient;
use \Firebase\JWT\JWT;

require_once(dirname(__FILE__) . '/_ruta.php');
require_once(APP_PATH . '/libs/jwt/JWT.php');
require_once(APP_PATH . '/libs/random/random.php');


//fuerzo que el login sea por SSL
if(Config_L::p('force_ssl'))
	if(!isHTTPS())
		forceSSL();

if (isset($_POST['btnLogout'])) {
	header('Location: ' . WEB_ROOT . '/logout.php');
	exit();
}

// STEP 1
$T_Nombre           =   isset($_POST['nombre'])     ? $_POST['nombre'] : '';
$T_Apellido         =   isset($_POST['apellido'])   ? $_POST['apellido'] : '';
$T_Email            =   isset($_POST['email'])      ? $_POST['email'] : '';
$T_Empresa          =   isset($_POST['empresa'])    ? $_POST['empresa'] : '';
$T_Token            =   isset($_REQUEST['t'])       ? $_REQUEST['t'] : '';


// STEP 2
$T_Subdominio       =   isset($_POST['subdominio'])         ? $_POST['subdominio'] : '';
$T_Clave            =   isset($_POST['password'])           ? $_POST['password'] : '';


$T_Step             =   isset($_REQUEST['step'])    ? $_REQUEST['step'] : 'default';

$_POST['t']         =   $T_Token ;
$_POST['subdominio']       =   $T_Subdominio;
$_POST['step']      =   $T_Step;

$T_Register         =   isset   (  $_REQUEST['register']   )           ? $_REQUEST['register'] : 1;


$_POST['register'] = 1;

$T_Mensaje  = '';
$T_Titulo   = '';
$T_Nota = '';
$T_Err      = '';
$Item_Name  = "register";
/*
//echo '<pre>';
print_r($_POST);
//echo '</pre>';
*/

$T_url              =   isset   (  $_REQUEST['url'] )               ?      $_REQUEST['url']         :       '';
$_POST['url']       =   $T_url;

switch ($T_Step) {

    case 'verificar_email':
        $o_Cliente = null;
        $_POST['step'] = 'default';

        // CUENTA EXISTENTE
        $o_Cliente = Cliente_L::obtenerPorEmail($T_Email);
        if (!is_null($o_Cliente)) {
            $T_Err = "La dirección de correo ya posee una cuenta.";
            break;
        }

        // NUEVO CLIENTE
        $o_Cliente = new Cliente_O();

        $o_Cliente->setEmail($T_Email);
        $o_Cliente->setNombre($T_Nombre);
        $o_Cliente->setApellido($T_Apellido);
        $o_Cliente->setEmpresa($T_Empresa);

        // TOKEN
        $_token = bin2hex(random_bytes(50));
        $o_Cliente->setcreateToken($_token);


        // SAVE CLIENTE
        if (!$o_Cliente->save()) {
            $T_Err = implode("<br>", $o_Cliente->getErrores());
            break;
        }

        // CREO EMAIL
        $mail          = new Email_O();
        $contenidoMail = "Recibimos tu petición para crear una nueva cuenta<br/>" . "<br/>" . "<div style=\"width:100%;text-align:center;\">" . "<a class=\"btn\" href=\"https://register.enpuntocontrol.com/register.php?step=verificar_subdominio&t=" . $_token . "\" style=\"margin: 0;padding: 6px 12px;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color: #333;display: inline-block;margin-bottom: 0;font-size: 14px;font-weight: normal;line-height: 1.428571429;text-align: center;white-space: nowrap;vertical-align: middle;cursor: pointer;border: 1px solid transparent;border-radius: 4px;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;-o-user-select: none;user-select: none;background-color: white;border-color: #CCC;\">" . "Haz click aquí para confirmar tu correo</a>" . "</div>" . "<br/><br/><br/>";
        $mail->setCuerpo($contenidoMail);
        $mail->setDestinatario($T_Email);
        $mail->setFrom("enPunto");
        $mail->setSujeto("Confirma tu dirección de correo");

        // ENVÍO EMAIL
        $mail->enviar();
        $mail->setEstado(2);
        $mail->setFecha(date("Y-m-d H:i:s"), "Y-m-d H:i:s");
        $mail->save();

        ////////////////// MENSAJE //////////////////
        $_POST['step'] = "mensaje";

        $T_Titulo       =   "Verifica tu correo";
        $T_Mensaje      =   $T_Email;
        $T_Nota         =   "Haz click en el link que te hemos enviado para completar tu registro.";


        break;

    case 'verificar_subdominio':
        $o_Cliente = null;

        if ($T_Token == '') {
            $T_Err = "Token no válido.";
            break;
        }

        // TOKEN VALIDATION
        $o_Cliente = Cliente_L::obtenerPorToken($T_Token);


        if (is_null($o_Cliente)) {
            $T_Err = "El token no es válido";
            break;
        }

        //  SU CUENTA YA ESTÁ HABILITADA
        if($o_Cliente->getEnabled()){
            $T_Email = $o_Cliente->getEmail();
            $T_Nombre = $o_Cliente->getNombre();
            $T_Apellido = $o_Cliente->getApellido();
            $T_Empresa = $o_Cliente->getEmpresa();
            $T_Subdominio = $o_Cliente->getSubdominio();
            $T_url = "https://".$T_Subdominio.".enpuntocontrol.com/login?username=".$T_Email;

            ////////////////// MENSAJE //////////////////
            $_POST['step'] = "mensaje";
            $T_Titulo   = "Ya posees una cuenta";
            $T_Mensaje      = $T_Email ;
            $T_Nota  = "<a href=\"".$T_url."\">Haz click aquí para iniciar sesión</a>.";
            break;
        }
        else{
            $T_Email = $o_Cliente->getEmail();
        }

        break;

    case 'crear_cuenta':
        $o_Cliente = null;
        $_POST['step'] = 'verificar_subdominio';

        // EMPTY TOKEN
        if ($T_Token == '') {
            $T_Err = "Token no válido.";
            break;
        }

        // EMPTY SUBDOMAIN
        if ($T_Subdominio == '') {
            $T_Err = "Subdominio no válido.";
            break;
        }
        // INVALID URL
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$T_Subdominio.".enpuntocontrol.com")) {
            $T_Err = "Subdominio no válido.";
        }

        // SUBDOMINIO DUPLICADO
        $o_Cliente = Cliente_L::obtenerPorSubdominio($T_Subdominio);

        if (!is_null($o_Cliente)) {
            $T_Err = "Subdominio ya utilizado. Por favor, elija otro.";
            break;
        }


        // TOKEN VALIDATION
        $o_Cliente = Cliente_L::obtenerPorToken($T_Token);
        if (is_null($o_Cliente)) {
            $T_Error = "El token no es válido";
            break;
        }

        $p_dataBase         = "tasm_" . $o_Cliente->getId();
        $p_dataBasepassword = substr($T_Token, 0, 18);
        $p_dataBase_usr     = "tasm_" . $o_Cliente->getId() . "_usr";
        $p_dataBaseHost     = "/cloudsql/enpunto-1286:us-central1:enpunto-sql";
        $p_dataPort         = 3306;

        // CLIENT
        $o_Cliente->setDBhost($p_dataBaseHost);
        $o_Cliente->setDBname($p_dataBase);
        $o_Cliente->setDBport($p_dataPort);
        $o_Cliente->setDBuser($p_dataBase_usr);
        $o_Cliente->setDBpass($p_dataBasepassword);
        $o_Cliente->setSubdominio($T_Subdominio);
        $o_Cliente->setEnabled(1);
        $o_Cliente->setSuscripcion(1);


        // DNS RECORD
        if (!Create_DnsRecord($T_Subdominio)) {
            printear(" 0 error al crear dns record");

            $T_Err = implode("<br>", $o_Cliente->getErrores());
            break;
        }
        sleep(1);


        $RESULT1 = Create_CustomDomain($T_Subdominio);
        // CUSTOM DOMAIN
        if (!$RESULT1) {
            printear(" 0 error al crear dns record ");
            printear($RESULT1);

            $T_Err = implode("<br>", $o_Cliente->getErrores());

        }


        sleep(1);


        // BASE DE DATOS
        if (!$o_Cliente->Create_clientDBCopy($p_dataBase, $p_dataBasepassword, $p_dataBase_usr)) {
            printear(" 1 error al crear base de datos");
            $T_Err = implode("<br>", $o_Cliente->getErrores());
            break;
        }
        sleep(1);
        // SAVE CLIENT
        if (!$o_Cliente->save()) {
            printear(" 2 error al guardar cliente");

            $T_Err = implode("<br>", $o_Cliente->getErrores());
            break;
        }
        sleep(1);
        // TOPIC
        if (!Create_clientTopic($T_Subdominio)) {
            printear(" 1 error al crear topic");

            $T_Err = implode("<br>", $o_Cliente->getErrores());
            break;
        }
        sleep(1);

        // CREAR USUARIO EN LA CUENTA
        if (!$o_Cliente->Create_clientUser($T_Clave)) {
            printear(" 1 error al crear clave");

            $T_Err = implode("<br>", $o_Cliente->getErrores());
            break;
        }
        sleep(1);

        // CLEAR TOKEN
        /*$o_Cliente->clearcreateToken();
        if (!$o_Cliente->save()) {
            $T_Err = implode("<br>", $o_Cliente->getErrores());
            break;
        }*/

        ////////////////// MENSAJE //////////////////
        $T_Email = $o_Cliente->getEmail();
        $_POST['step'] = "creando_cuenta";
        //$T_Titulo      = "Creando cuenta";
        //$T_Mensaje="La nueva cuenta ha sido creada correctamente.<br/> <br>Ingresa cuando quieras desde <a href=\"https://".$T_Subdominio.".enpuntocontrol.com/login?username=".$T_Email."\">https://".$T_Subdominio.".enpuntocontrol.com</a>";

        $T_url = "https://".$T_Subdominio.".enpuntocontrol.com/login?username=".$T_Email;

        break;

}




/**********************************************************************
 * NEW TOPIC
 ***********************************************************************/
function Create_clientTopic($p_subdominio=null){

    if(is_null($p_subdominio)){
        return;
    }
    //pubsub

    $topicName='projects/enpunto-1286/topics/clients-'.$p_subdominio.'';
    $pubsub = new PubSubClient([
                                   'projectId' => 'enpunto-1286',
                               ]);

    $o_topic = $pubsub->createTopic($topicName);

    if(is_null($o_topic)){
        return false;
    }
    //printf('Topic created: %s' . PHP_EOL, $o_topic->name());

    return true;

}
/**********************************************************************
 * NEW CLIENT DNS CNAME RECORD
 ***********************************************************************/
function Create_DnsRecord($p_RecordName=null){

    if(is_null($p_RecordName)){
        return false;
    }

    // CREDENTIALS
    $p_psclient = new Google_Client();
    $p_psclient->useApplicationDefaultCredentials();//usa las credenciales de app engine
    $p_psclient->addScope(Google_Service_Dns::NDEV_CLOUDDNS_READWRITE);

    // DNS SERVICE
    $dns = new Google_Service_Dns($p_psclient);

    $_recordChange= new Google_Service_Dns_Change();

    $_newRecord= new Google_Service_Dns_ResourceRecordSet();
    $_newRecord->kind = "dns#resourceRecordSet";
    $_newRecord->name = $p_RecordName.".enpuntocontrol.com.";
    $_newRecord->rrdatas = ["ghs.googlehosted.com."];
    $_newRecord->ttl = 3600;
    $_newRecord->type = "CNAME";

    $_recordChange->setAdditions(array($_newRecord));

    return $dns->changes->create('enpunto-1286', 'enpuntocontrol', $_recordChange);
}
/**********************************************************************
 * DELETE CLIENT DNS CNAME RECORD // TODO: IMPLEMENTAR ELIMINACIÓN DE CUENTAS
 ***********************************************************************/
function Delete_DnsRecord($p_RecordName=null){

    if(is_null($p_RecordName)){
        return false;
    }

    // CREDENTIALS
    $p_psclient = new Google_Client();
    $p_psclient->useApplicationDefaultCredentials();//usa las credenciales de app engine
    $p_psclient->addScope(Google_Service_Dns::NDEV_CLOUDDNS_READWRITE);

    // DNS SERVICE
    $dns = new Google_Service_Dns($p_psclient);

    $_recordChange= new Google_Service_Dns_Change();

    $_oldRecord= new Google_Service_Dns_ResourceRecordSet();
    $_oldRecord->kind = "dns#resourceRecordSet";
    $_oldRecord->name = $p_RecordName.".enpuntocontrol.com.";
    $_oldRecord->rrdatas = ["ghs.googlehosted.com."];
    $_oldRecord->ttl = 3600;
    $_oldRecord->type = "CNAME";

    $_recordChange->setDeletions(array($_oldRecord));

    return $dns->changes->create('enpunto-1286', 'enpuntocontrol', $_recordChange);
}
function isValidUrl($url) {
    /*
    // first do some quick sanity checks:
    if (!$url || !is_string($url)) {
        return false;
    }
    // quick check url is roughly a valid http request: ( http://blah/... )
    if (!preg_match('/^http(s)?:\/\/[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $url)) {
        return false;
    }*/


    if (getHttpResponseCode_using_curl($url) == 200) {
        return true;
    }
    return false;
}

/**********************************************************************
 * NEW CUSTOM DOMAIN
 ***********************************************************************/
function Create_CustomDomain($_subdomain=null){

    if(is_null($_subdomain)){
        return false;
    }

    // CREDENTIALS
    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google_Service_Iam::CLOUD_PLATFORM);

    $domainMappingsClient = new \Google\Cloud\AppEngine\V1\DomainMappingsClient();

    // NEW SERVICE
    $o_Mapping = new \Google\Cloud\AppEngine\V1\DomainMapping();
    $o_Mapping->setId($_subdomain. ".enpuntocontrol.com");


    $params = array([
        'parent'                =>      'projects/enpunto-1286' ,
        'domainMapping'         =>      $o_Mapping ,
        'overrideStrategy'      =>      '1' // STRICT (NO OVERRIDE)
    ]);

    try {

        $operationResponse = $domainMappingsClient->createDomainMapping($params);
        $operationResponse->pollUntilComplete();

        if ($operationResponse->operationSucceeded()) {
                 $result = $operationResponse->getResult();


            // doSomethingWith($result)
            }
        else {
             $result = $operationResponse->getError();
                // handleError($error)
        }
        return $result;


    }
    finally {
            $domainMappingsClient->close();
    }


}


function getHttpResponseCode_using_curl($p_url)
{

    if (!$p_url || !is_string($p_url)) {
        return false;
    }

    $ch = curl_init($p_url);

    if ($ch === false) {
        return false;
    }

    curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
    curl_setopt($ch, CURLOPT_NOBODY, true);    // dont need body
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    // catch output (do NOT print!)
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_exec($ch);


    if (curl_errno($ch)) {
        curl_close($ch);
        return false;
    }

    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return $code;
}
require_once APP_PATH . '/templates/register.html.php';











