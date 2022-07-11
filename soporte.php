<?php
/*
use \Firebase\JWT\JWT;
require_once(dirname(__FILE__) . '/_ruta.php');
require_once(APP_PATH . '/libs/jwt/JWT.php');


// Log your user in.
$key       = "k6ntXjCFbOgkA3YUUfx6Z04ZgP5rLvm88lI2Z6R8XDA8GLnM";
$subdomain = "enpunto";
$now       = time();

$token = array(
    "jti"   => md5($now . rand()),
    "iat"   => $now,
    "external_id" => Registry::getInstance()->Usuario->getId(),
    "organization" => Registry::getInstance()->Cliente->getEmpresa(),
    "name"  => Registry::getInstance()->Usuario->getNombreCompleto(),
    "email" => Registry::getInstance()->Usuario->getEmail(),
    "remote_photo_url" => Registry::getInstance()->Usuario->getImagenURL()
);
$jwt = JWT::encode($token, $key);
$location = "https://" . $subdomain . ".zendesk.com/access/jwt?jwt=" . $jwt;
if(isset($_GET["return_to"])) {
    $location .= "&return_to=" . urlencode($_GET["return_to"]);
}
// Redirect
header("Location: " . $location);


*/


