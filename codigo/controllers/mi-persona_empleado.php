<?php

require_once dirname(__FILE__) . '/../../_ruta.php';

use google\appengine\api\cloud_storage\CloudStorageTools;


$T_Titulo = _('Personas');
$Item_Name = "persona";
$T_Titulo_Singular = "persona";
$T_Titulo_Pre = "una";
$T_Script = 'persona';
$T_Mensaje = '';


if (isset($_REQUEST['equipo'])) $_SESSION["EQUIPO"] = $_REQUEST['equipo'];


$T_Tipo             = (isset($_REQUEST['tipo'])) ? $_REQUEST['tipo'] : '';

$T_IMGBorrar        = (isset($_POST['inputBorrarImagen']))      ? $_POST['inputBorrarImagen']   : '';
$T_IMGExtension     = (isset($_POST['inputImageExtension']))    ? $_POST['inputImageExtension'] : '';
$T_IMG              = (isset($_POST['inputIMGsrc']))            ? $_POST['inputIMGsrc']         : '';
$T_IMGx             = (isset($_POST['inputIMGx']))              ? $_POST['inputIMGx']           : '';
$T_IMGy             = (isset($_POST['inputIMGy']))              ? $_POST['inputIMGy']           : '';
$T_IMGw             = (isset($_POST['inputIMGw']))              ? $_POST['inputIMGw']           : '';
$T_IMGh             = (isset($_POST['inputIMGh']))              ? $_POST['inputIMGh']           : '';


switch ($T_Tipo) {

    case 'edit':

        //SeguridadHelper::Pasar(50);

        $o_Persona      =   Persona_L::obtenerPorUsuarioActual();

        $o_Persona->setTeCelurar(   isset($_POST['telefono']) ? $_POST['telefono'] : '');
        $o_Persona->setTeFijo(      isset($_POST['te_personal']) ? $_POST['te_personal'] : '');
        $o_Persona->setEmail(       isset($_POST['email']) ? $_POST['email'] : '');



        if ($T_IMGBorrar != '') {
            $o_Persona->setImagen('');
        }

        if ($T_IMG != '') {//viene una imagen nueva


            $src = GS_CLIENT_TEMP_FOLDER . $T_IMG;
			if ($T_IMGExtension == 'image/png')
                $img_r = imagecreatefrompng($src);
            else if ($T_IMGExtension == 'image/gif')
                $img_r = imagecreatefromgif($src);
            else if ($T_IMGExtension == 'image/jpg')
                $img_r = imagecreatefromjpeg($src);
            else if ($T_IMGExtension == 'image/jpeg')
                $img_r = imagecreatefromjpeg($src);
            else
                $o_Persona->setErrores('imagen', 'formato no soportado');

            $dst_w = 250;
            $dst_h = 250;
            $dst_r = ImageCreateTrueColor($dst_w, $dst_h);

            imagecopyresampled($dst_r, $img_r, 0, 0, $T_IMGx, $T_IMGy, $dst_w, $dst_h, $T_IMGw, $T_IMGh);

            $filename = md5(time() . uniqid()) . ".jpg";
            $path = GS_CLIENT_IMAGES_PERSONAS . $o_Persona->getId() . "/";

            if (!file_exists($path))
                mkdir($path, 0777, true);

            imagejpeg($dst_r, $path . $filename, 100);

            $o_Persona->setImagen($path . $filename);
            $o_Persona->setImagenURL('');

        }

        // SAVE PERSONA
        if ($o_Persona->save()) {

            // SAVE LOG
            SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_PERSONA_EDITAR, $a_Logs_Tipos[LOG_PERSONA_EDITAR], '<b>Id:</b> ' . $o_Persona->getId() . ' <b>Nombre:</b> ' . $o_Persona->getNombreCompleto(), $o_Persona->getId());

            $se_sincroniza = 0;

            // EQUIPOS
            $a_o_Equipo    = Equipo_L::obtenerTodos();

            // EQUIPOS ASIGNADOS A PERSONA
            $array_equipos = explode(':', $o_Persona->getEquipos());

            // IF EQUIPOS
            if ($a_o_Equipo != null) {


                foreach ($a_o_Equipo as $o_Equipo) {

                    // EQUIPO OFFLINE
                    if ($o_Equipo->isOffline()) {
                        continue;
                    }
                    // EQUIPOS_PERSONA IS NOT IN EQUIPOS
                    if (!in_array($o_Equipo->getId(), $array_equipos)) {
                        continue;
                    }

                    $se_sincroniza = 1;

                }
            }

            if (1){//$se_sincroniza) {
                $T_Mensaje       = 'La persona fue guardada con éxito. Sincronizando datos...';
                $T_sync_checker  = "syncChecker(" . $o_Persona->getId() . ",\"" . $o_Persona->getApellido() . ', ' . $o_Persona->getNombre() . "\"," . count(explode(':', $o_Persona->getEquipos())) . ");";
                $T_sync_js_start = "disableRow(\"" . $o_Persona->getApellido() . ', ' . $o_Persona->getNombre() . "\");";
            }
            else {
                $T_Mensaje = 'La persona fue guardada correctamente.';
            }

            // Sync
            SyncHelper::SyncPersona($o_Persona);
            // Fin Sync
        }

        // SAVE ERROR
        else {
            $T_Error = $o_Persona->getErrores();
        }



        break;



    default:

        $o_Persona      =   Persona_L::obtenerPorUsuarioActual();

        break;

}