<?php

require_once(APP_PATH . '/libs/random/random.php');

// GENERAL VARS
$T_Titulo               =   _('Mi Usuario');
$T_Script               =   'mi-usuario';
$Item_Name              =   "mi-usuario";
$T_Link                 =   '';
$T_Mensaje              =   '';
$T_Titulo_Singular      =   'usuario';
$T_Titulo_Pre           =   "un";

// DATA VARS
$T_Tipo        = isset($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '';
$T_Tipo_Check  = isset($_REQUEST['tipo_check']) ? $_REQUEST['tipo_check'] : '';
$T_UsuarioTipo = isset($T_UsuarioTipo) ? $T_UsuarioTipo : '';
$T_Id          = isset($_REQUEST['id']) ? (integer)$_REQUEST['id'] : 0;
$T_UsuarioTipo = isset($T_UsuarioTipo) ? $T_UsuarioTipo : '';

// IMAGE VARS
$T_IMGBorrar        = isset($_POST['inputBorrarImagen'])        ? $_POST['inputBorrarImagen']           : '';
$T_IMGExtension     = isset($_POST['inputImageExtension'])      ? $_POST['inputImageExtension']         : '';
$T_IMG              = (isset($_POST['inputIMGsrc']))            ? $_POST['inputIMGsrc']                 : '';
$T_IMGx             = (isset($_POST['inputIMGx']))              ? $_POST['inputIMGx']                   : '';
$T_IMGy             = (isset($_POST['inputIMGy']))              ? $_POST['inputIMGy']                   : '';
$T_IMGw             = (isset($_POST['inputIMGw']))              ? $_POST['inputIMGw']                   : '';
$T_IMGh             = (isset($_POST['inputIMGh']))              ? $_POST['inputIMGh']                   : '';

//$_usuarioID = $T_Id!=0            ? Usuario_L::obtenerPorId($T_Id)->getTusId():0;

switch ($T_Tipo) {

    // VER
    case 'view':
        $o_Usuario = Usuario_L::obtenerPorId($T_Id);

        break;

    // AGREGAR, EDITAR
    case 'edit':

        SeguridadHelper::Pasar(5);

        $o_Usuario     = Usuario_L::obtenerPorId($T_Id);

        if (is_null($o_Usuario)) {
            $T_Error = _('El Usuario no existe');
            break;
        }

        if ($T_Tipo == 'edit') {
            $o_Usuario->setClave('');//para que no se cambie la clave
            $o_Usuario->setConfirmacionClave(''); //probar si esto anda para rellenar la clave copiada
        }

        if(isset($_POST['clave']) && $_POST['clave']!=''){
            if(isset($_POST['re_clave']) && $_POST['re_clave']!=''){
                if($_POST['clave']==$_POST['re_clave']){
                    $o_Usuario->setClave(isset($_POST['clave']) ? $_POST['clave'] : '');
                    $o_Usuario->setConfirmacionClave(isset($_POST['re_clave']) ? $_POST['re_clave'] : '');
                }
            }
        }

        // DATOS PERSONALES
        $o_Usuario->setNombre           (       isset(  $_POST['nombre']      )       ? $_POST['nombre']          : ''        );
        $o_Usuario->setApellido         (       isset(  $_POST['apellido']    )       ? $_POST['apellido']        : ''        );
        $o_Usuario->setDni              (       isset(  $_POST['dni']         )       ? $_POST['dni']             : ''        );
        $o_Usuario->setTeCelurar        (       isset(  $_POST['te_celular']  )       ? $_POST['te_celular']      : ''        );
        $o_Usuario->setTePersonal       (       isset(  $_POST['te_personal'] )       ? $_POST['te_personal']     : ''        );
        $o_Usuario->setEmail            (       isset(  $_POST['email']       )       ? $_POST['email']           : ''        );

        // IMAGEN
        if ($T_IMGBorrar != '') {
            $o_Usuario->setImagen('');
        }
        // IMAGEN
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
                $o_Usuario->setErrores('imagen','formato no soportado');

            $dst_w = 250;
            $dst_h = 250;
            $dst_r = ImageCreateTrueColor($dst_w, $dst_h);

            imagecopyresampled($dst_r, $img_r, 0, 0, $T_IMGx, $T_IMGy, $dst_w, $dst_h, $T_IMGw, $T_IMGh);

            $filename = md5(time().uniqid()).".jpg";
            $path=GS_CLIENT_IMAGES_USUARIOS.$o_Usuario->getId()."/";

            if (!file_exists($path))
                mkdir($path, 0777, true);

            imagejpeg($dst_r, $path.$filename, 100);

            $o_Usuario->setImagen($path.$filename);
            $o_Usuario->setImagenURL('');
        }


        // SAVE & SEND LOG
        if ($o_Usuario->save(Registry::getInstance()->general['debug'])) {

            SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_USUARIO_EDITAR, $a_Logs_Tipos[LOG_USUARIO_EDITAR], '<b>Id:</b> ' . $o_Usuario->getId() . ' <b>Usuario:</b> ' . $o_Usuario->getEmail() . ' <b>Nombre:</b> ' . $o_Usuario->getNombreCompleto(), $o_Usuario->getId());
            $T_Mensaje = _('El usuario fue modificado correctamente.');
        }
        // ERROR IN SAVE
        else {
            $T_Error = $o_Usuario->getErrores();
        }

        // TYPE OF USERS o_SELECT (FOR: EDIT USER)

        $T_UsuarioTipo = HtmlHelper::array2htmloptions(UsuarioTipo_L::obtenerTodos(), $o_Usuario->getTusId(), true, true, '', 'Tipo de Usuario');


        $T_Modificar = true;
        goto defaultlabel;

        break;



    // CREAR o_Listado DE USUARIOS
    default:
        defaultlabel:
        $_usuId = Registry::getInstance()->Usuario->getId();
        $o_Usuario     = Usuario_L::obtenerPorId($_usuId);

}
