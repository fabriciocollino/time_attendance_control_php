<?php
require_once dirname(__FILE__) . '/../../_ruta.php';

SeguridadHelper::Pasar(90);   //a este controller no accede nadie, excepto los admins

$T_Titulo       =   _('Importar');
$T_Script       =   'importar';
$Item_Name      =   "importar";
$T_Link         =   '';
$T_Mensaje      =   '';
$o_Listado      =   null;

$o_Data         =   isset($_POST['obj'])   ? json_decode( $_POST['obj'],true) : null;
$T_Accion       =   isset($_POST['accion'])   ?  $_POST['accion']         : '';

$T_Tipo         =   isset   ($_REQUEST['tipo']   )       ?   $_REQUEST['tipo']                     : '';
$T_Id           =   isset   ($_REQUEST['id']    )       ?   (integer)$_REQUEST['id']              : 0;
$_SESSION['tipo'] = $T_Tipo;


define('IMPORT_PERSONAS',   1);
define('IMPORT_GRUPOS',   2);

$a_Import_Type = array(
    IMPORT_PERSONAS => "Personas"
    //,  IMPORT_GRUPOS => "Grupos"
);



$a_Atributos    = null;
$a_Nombres      = null;
$a_Required     = null;


$a_Datos = isset( $_POST['datos'] )     ? json_decode($_POST['datos'],true)  : null;

if (!is_null($a_Datos)) {
    $a_Nombres   = $a_Datos['nombres'];
    $a_Atributos = $a_Datos['atributos'];
    $a_Required  = $a_Datos['required'];
}



switch ($T_Tipo) {
    case 'enable_and_generate':

        $o_api_key = null;
        $o_api_key = Config_L::obtenerPorParametro('api_key');
        if (is_null($o_api_key)) die('no se encontro el parametro key');
        $o_api_key->setValor(md5(bin2hex(random_bytes(15))));
        $o_api_key->save('Off');

        SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_API_GENERAR_KEY, $a_Logs_Tipos[LOG_API_GENERAR_KEY], '', 0);

        $o_api_enabled = null;
        $o_api_enabled = Config_L::obtenerPorParametro('api_enabled');
        if (is_null($o_api_enabled)) die('no se encontro el parametro enabled');
        $o_api_enabled->setValor(1);
        $o_api_enabled->save('Off');

        SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_API_ACTIVAR, $a_Logs_Tipos[LOG_API_ACTIVAR], '', 0);


        break;
    case 'regenerate':

        $o_api_key = null;
        $o_api_key = Config_L::obtenerPorParametro('api_key');
        if (is_null($o_api_key)) die('no se encontro el parametro key');
        $o_api_key->setValor(md5(bin2hex(random_bytes(15))));
        $o_api_key->save('Off');

        SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_API_REGENERAR_KEY, $a_Logs_Tipos[LOG_API_REGENERAR_KEY], '', 0);

        break;
    case 'enable':

        $o_api_enabled = null;
        $o_api_enabled = Config_L::obtenerPorParametro('api_enabled');
        if (is_null($o_api_enabled)) die('no se encontro el parametro enabled');
        $o_api_enabled->setValor(1);
        $o_api_enabled->save('Off');

        SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_API_ACTIVAR, $a_Logs_Tipos[LOG_API_ACTIVAR], '', 0);

        break;
    case 'disable':

        $o_api_enabled = null;
        $o_api_enabled = Config_L::obtenerPorParametro('api_enabled');
        if (is_null($o_api_enabled)) die('no se encontro el parametro enabled');
        $o_api_enabled->setValor(0);
        $o_api_enabled->save('Off');

        SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_API_DESACTIVAR, $a_Logs_Tipos[LOG_API_DESACTIVAR], '', 0);

        break;
    case 'test_mode_on':

        $o_api_test_mode = null;
        $o_api_test_mode = Config_L::obtenerPorParametro('api_test_mode');
        if (is_null($o_api_test_mode)) die('no se encontro el parametro test mode');
        $o_api_test_mode->setValor(1);
        $o_api_test_mode->save('Off');

        SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_API_MODO_DE_PRUEBAS_ON, $a_Logs_Tipos[LOG_API_MODO_DE_PRUEBAS_ON], '', 0);

        break;
    case 'test_mode_off':

        $o_api_test_mode = null;
        $o_api_test_mode = Config_L::obtenerPorParametro('api_test_mode');
        if (is_null($o_api_test_mode)) die('no se encontro el parametro test mode');
        $o_api_test_mode->setValor(0);
        $o_api_test_mode->save('Off');

        SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_API_MODO_DE_PRUEBAS_OFF, $a_Logs_Tipos[LOG_API_MODO_DE_PRUEBAS_OFF], '', 0);

        break;

    case IMPORT_PERSONAS:
        if(is_null($o_Data)) break;

        foreach ($o_Data as $_itemID => $_item) {

            $o_Data[$_itemID] = str_replace('"', '', $_item);

            $o_Persona = new Persona_O();
            $o_Persona->fromArray($o_Data[$_itemID]);

            if($T_Accion == 'preview'){
                if($o_Persona->esValido()) {
                    $o_Data[$_itemID]['imp_status'] = 'Listo para Importar';
                }
                else{
                    $o_Data[$_itemID]['imp_status'] = $o_Persona->getErrores();
                }
                continue;
            }


            if($T_Accion == 'importar'){

                if ($o_Persona->save()) {
                    unset($o_Data[$_itemID]);
                    continue;
                }
                else{
                    $o_Data[$_itemID]['imp_status'] = $o_Persona->getErrores();
                }
                continue;
            }




        }

        break;

    case IMPORT_GRUPOS:
        // TODO:
        if(is_null($o_Data)) break;

        foreach ($o_Data as $_itemID => $_item) {

            $o_Grupo = new Grupo_O();
            $o_Grupo->setDetalle($_item['detalle']);
            $o_Grupo->setEnVivo($_item['envivo']);


            if($T_Accion == 'preview'){
                if($o_Grupo->esValido()) {
                    $o_Data[$_itemID]['imp_status'] = 'Listo para Importar';
                }
                else{
                    $o_Data[$_itemID]['imp_status'] = $o_Grupo->getErrores();
                }
                continue;
            }


            if($T_Accion == 'importar'){

                if ($o_Grupo->save()) {
                    unset($o_Data[$_itemID]);
                    continue;
                }
                else{
                    $o_Data[$_itemID]['imp_status'] = $o_Grupo->getErrores();
                }
                continue;
            }


        }

        break;

    default:
        defaultlabel:
        $T_Link = '';
        break;
}
