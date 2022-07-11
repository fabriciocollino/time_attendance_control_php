<?php

require_once(APP_PATH . '/libs/random/random.php');

// GENERAL VARS
$T_Titulo               =   _('Usuarios');
$T_Script               =   'usuarios';
$Item_Name              =   "usuarios";
$T_Link                 =   '';
$T_Mensaje              =   '';
$T_Titulo_Singular      =   'usuario';
$T_Titulo_Pre           =   "un";

// DATA VARS
$T_Tipo        = isset($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '';
$T_Tipo_Check  = isset($_REQUEST['tipo_check']) ? $_REQUEST['tipo_check'] : '';
$T_UsuarioTipo = isset($T_UsuarioTipo) ? $T_UsuarioTipo : '';
$T_Id          = isset($_REQUEST['id']) ? (integer)$_REQUEST['id'] : 0;


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
        if (is_null($o_Usuario)) {
            $o_Usuario = new Usuario_O();
        }
        if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 99) {
            $T_UsuarioTipo = HtmlHelper::array2htmloptions(UsuarioTipo_L::obtenerTodos(), $o_Usuario->getTusId(), true, true, '', 'Tipo de Usuario');
        } else {
            $T_UsuarioTipo = HtmlHelper::array2htmloptions(UsuarioTipo_L::obtenerTodos('codigo < ' . Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo()), $o_Usuario->getTusId(), true, true, '', 'Tipo de Usuario');
        }
        break;


    case 'view-persona-user':
            $o_Usuario = Usuario_L::obtenerPorPerId($T_Id);
            if (is_null($o_Usuario)) {
                $o_Usuario = new Usuario_O();
            }
            if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 99) {
                $T_UsuarioTipo = HtmlHelper::array2htmloptions(UsuarioTipo_L::obtenerTodos(), $o_Usuario->getTusId(), true, true, '', 'Tipo de Usuario');
            } else {
                $T_UsuarioTipo = HtmlHelper::array2htmloptions(UsuarioTipo_L::obtenerTodos('codigo < ' . Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo()), $o_Usuario->getTusId(), true, true, '', 'Tipo de Usuario');
            }
            break;
        break;


    case 'crear_usuarios_personas':
        // TODAS LAS PERSONAS OR ID ARRAY
        $a_Personas = Persona_L::obtenerTodosIdenArray();

        foreach ($a_Personas as $_item => $_pId){

            // GET PERSONA DATA
            $o_Usuario = null;
            $o_Persona = null;

            $o_Persona = Persona_L::obtenerPorId($_pId);

            // GET USER
            if($o_Persona->getEmail() != ''){
                $o_Usuario = Usuario_L::obtenerPorEmail($o_Persona->getEmail());
            }
            if (is_null($o_Usuario)) {
                if($o_Persona->getDni() != '') {
                    $o_Usuario = Usuario_L::obtenerPorDni($o_Persona->getDni());
                }
            }


            // NEW USER
            if (is_null($o_Usuario)) {

                // NEW USER
                $o_Usuario = new Usuario_O();

                // SET USER TYPE
                $o_Usuario->setTusId            (6);

                // SET USER VISIBLE
                $o_Usuario->setVisible          (1);

                // SET PASSWORD
                $o_Usuario->setClave            (       $o_Persona->getDni()         );
                $o_Usuario->setConfirmacionClave(       $o_Persona->getDni()         );

                // SET DATA
                $o_Usuario->setNombre           (       $o_Persona->getNombre()         );
                $o_Usuario->setApellido         (       $o_Persona->getApellido()       );
                $o_Usuario->setDni              (       $o_Persona->getDni()            );
                $o_Usuario->setTeCelurar        (       $o_Persona->getTeCelular()      );
                $o_Usuario->setTePersonal       (       $o_Persona->getTeFijo()         );

                $_email = $o_Persona->getEmail();
                if($_email != ""){
                    $o_Usuario->setEmail        ($_email);
                }

                // SET IMAGEN
                $o_Usuario->setImagen('');
                $o_Usuario->setImagenURL('');
            }

            // SET PERSONA TO USER
            $o_Usuario->setPersona              (       $o_Persona->getId()             );

            // SAVE NEW USER
            if ($o_Usuario->save(Registry::getInstance()->general['debug'])) {

                // SAVE LOG: NEW USER
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_USUARIO_CREAR, $a_Logs_Tipos[LOG_USUARIO_CREAR], '<b>Id:</b> ' . $o_Usuario->getId() . ' <b>Usuario:</b> ' . $o_Usuario->getEmail() . ' <b>Nombre:</b> ' . $o_Usuario->getNombreCompleto(), $o_Usuario->getId());
                $T_Mensaje = $T_Mensaje."<br>"."Usuario creado/actualizado.";


                // SET USER ACTIVO
                $o_Persona->setUserActivo(1);

                // SET PERSONA ID
                $o_Persona->setUserId($o_Usuario->getId());

                // SAVE PERSONA
                if($o_Persona->save(Registry::getInstance()->general['debug'])){
                    // SAVE LOG: UPDATED PERSONA
                    SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_PERSONA_EDITAR, $a_Logs_Tipos[LOG_PERSONA_EDITAR], '<b>Id:</b> ' . $o_Persona->getId() . ' <b>Nombre:</b> ' . $o_Persona->getNombreCompleto(), $o_Persona->getId());
                    $T_Mensaje = $T_Mensaje." Persona asociada actualizada: ".$o_Persona->getNombreCompleto();
                }

                // SAVE ERROR
                else {
                    $T_Error = "Lo sentimos, hubo un error en la operación.";//$T_Error."<br>".$o_Persona->getErrores();
                }

            }

            // SAVE ERROR
            else {
                $T_Error = "Lo sentimos, hubo un error en la operación.";//$T_Error."<br>".implode(",",$o_Usuario->getErrores());


            }

        }
        goto defaultlabel;

        break;

    case 'check':

        $o_Usuario = Usuario_L::obtenerPorId($T_Id);
        if (is_null($o_Usuario)) {
            $o_Usuario = new Usuario_O();
        }

        switch ($T_Tipo_Check) {
            case 'c_usuario':
                if ($o_Usuario->getEmail() != $_POST['email'])
                    $o_Usuario->setEmail(isset($_POST['email']) ? $_POST['email'] : '');
                $T_Error = $o_Usuario->getErrores();//"Lo sentimos, hubo un error en la operación.";//

                if (!is_null($T_Error) && array_key_exists('usuario', $T_Error))
                    echo $T_Error['usuario'];
                else
                    echo "true";

                break;
            case 'c_dni':
                $o_Usuario->setDni(isset($_POST['dni']) ? $_POST['dni'] : '');
                $T_Error = $o_Usuario->getErrores();
                if (!is_null($T_Error) && array_key_exists('dni', $T_Error))
                    echo $T_Error['dni']; else echo "true";
                break;
        }
        die();
        break;

    // AGREGAR, EDITAR
    case 'add':
    case 'edit':

        SeguridadHelper::Pasar(90);

        $o_Usuario     = Usuario_L::obtenerPorId($T_Id);
        $nuevo_usuario = 0;

        if (is_null($o_Usuario)) {
            $o_Usuario = new Usuario_O();
        }

        if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 99) {

            $_usuTipo = isset($_POST['usuario_tipo']) ? $_POST['usuario_tipo'] : 0;

            $o_Usuario->setTusId($_usuTipo);
        }

        if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() < 999){
            if($o_Usuario->getTusId()==1)
                die();
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
        //$o_Usuario->setPersona          (       isset(  $_POST['perId']       )       ? $_POST['perId']           : ''        );

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

        // USUARIO NUEVO
        if ($o_Usuario->getId() == 0) {
            $nuevo_usuario = 1;
        }

        // SAVE & SEND LOG
        if ($o_Usuario->save(Registry::getInstance()->general['debug'])) {

            // NEW USER
            if ($nuevo_usuario) {
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_USUARIO_CREAR, $a_Logs_Tipos[LOG_USUARIO_CREAR], '<b>Id:</b> ' . $o_Usuario->getId() . ' <b>Usuario:</b> ' . $o_Usuario->getEmail() . ' <b>Nombre:</b> ' . $o_Usuario->getNombreCompleto(), $o_Usuario->getId());
                $T_Mensaje = _('El usuario fue agregado correctamente.');
            }
            // UPDATED USER
            else{
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_USUARIO_EDITAR, $a_Logs_Tipos[LOG_USUARIO_EDITAR], '<b>Id:</b> ' . $o_Usuario->getId() . ' <b>Usuario:</b> ' . $o_Usuario->getEmail() . ' <b>Nombre:</b> ' . $o_Usuario->getNombreCompleto(), $o_Usuario->getId());
                $T_Mensaje = _('El usuario fue modificado correctamente.');
            }

            /*
            // // // // // // // // // // // // // // UPDATE PERSONA  // // // // // // // // // // // // // //
            // EMPLOYEE ASSOCIATED TO UPDATE
            $o_Persona = null;


            if ($o_Usuario->getPersona() != 0){
                $o_Persona = Persona_L::obtenerPorId($o_Usuario->getPersona());
            }

            if (is_null($o_Persona)) {
                if ($o_Usuario->getEmail() != '') {
                    $o_Persona = Persona_L::obtenerPorEmail($o_Usuario->getEmail());
                }
            }
            // 2 OBTENER USUARIO POR DNI
            if (is_null($o_Persona)) {
                if ($o_Usuario->getDni() != '') {
                    $o_Persona = Persona_L::obtenerPorDni($o_Usuario->getDni());
                }
            }

            if(is_null($o_Persona)){
                $o_Persona = new Persona_O();
                $o_Persona->setLegajo($o_Usuario->getDni());
                $o_Persona->setUserActivo(1);
                $o_Persona->setUserId($o_Usuario->getId());
            }

            // PERSONA SET DATA
            if(!is_null($o_Persona)){


                // SET PERSONAL DATA
                $o_Persona->setNombre               ( $o_Usuario->getNombre()       );
                $o_Persona->setApellido             ( $o_Usuario->getApellido()     );
                $o_Persona->setDni                  ( $o_Usuario->getDni()          );

                $o_Persona->setTeCelurar            ( $o_Usuario->getTeCelular()    );
                $o_Persona->setTeFijo               ( $o_Usuario->getTePersonal()   );

                // SET IMAGEN
                $o_Persona->setImagen               ( $o_Usuario->getImagen()       );
                $o_Persona->setImagenURL            ( $o_Usuario->getImagenURL()    );

                // SET EMAIL
                $o_Persona->setEmail                ( $o_Usuario->getEmail()        );

                // SAVE PERSONA
                if ($o_Persona->save(true)) {

                    //$T_Mensaje = $T_Mensaje._(' La persona asociada fue modificada también con éxito. Legajo: ').$o_Persona->getLegajo();
                    // LOG: PERSONA SAVED
                    SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_PERSONA_EDITAR, $a_Logs_Tipos[LOG_PERSONA_EDITAR], '<b>Id:</b> ' . $o_Persona->getId() . ' <b>Usuario:</b> ' . $o_Persona->getEmail() . ' <b>Nombre:</b> ' . $o_Persona->getNombreCompleto(), $o_Persona->getId());
                }
                else {
                    $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Persona->getErrores();
                }



            }

            // // // // // // // // // // // // // // END: UPDATE PERSONA // // // // // // // // // // // // // //


            */










        }
        // ERROR IN SAVE
        else {
            $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Usuario->getErrores();
        }

        // TYPE OF USERS o_SELECT (FOR: EDIT USER)
        if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 99) {
            $T_UsuarioTipo = HtmlHelper::array2htmloptions(UsuarioTipo_L::obtenerTodos(), $o_Usuario->getTusId(), true, true, '', 'Tipo de Usuario');
        }
        else {
            $T_UsuarioTipo = HtmlHelper::array2htmloptions(UsuarioTipo_L::obtenerTodos('codigo < ' . Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo()), $o_Usuario->getTusId(), true, true, '', 'Tipo de Usuario');
        }

        $T_Modificar = true;
        goto defaultlabel;

        break;



    case 'delete':
        SeguridadHelper::Pasar(50);

        $o_Usuario = Usuario_L::obtenerPorId($T_Id, true);


        if (is_null($o_Usuario)) {
            $T_Error = _('Lo sentimos, el usuario que desea eliminar no existe.');
        }

        else
            if(Registry::getInstance()->Usuario->getId()==$o_Usuario->getId()) {
                $T_Error = "Lo sentimos, no se puede eliminar a sí mismo.";
            }
            else {
                $o_Usuario->setEliminadoUsuarioId(Registry::getInstance()->Usuario->getId());

                if (!$o_Usuario->delete()) {
                    $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Usuario->getErrores();
                } else {
                    SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_USUARIO_ELIMINAR, $a_Logs_Tipos[LOG_USUARIO_ELIMINAR], '<b>Id:</b> ' . $o_Usuario->getId() . ' <b>Nombre:</b> ' . $o_Usuario->getNombreCompleto(), $o_Usuario->getId());
                    $T_Mensaje = _('El usuario fue eliminado correctamente.');
                }
        }

        $T_Eliminado = true;
        $T_Link = '_mos';
        goto defaultlabel;

        break;


    case 'undelete':
        SeguridadHelper::Pasar(50);

        $o_Usuario = Usuario_L::obtenerPorId($T_Id, true);


        if (is_null($o_Usuario)) {
            $T_Error = _('Lo sentimos, el usuario que desea eliminar no existe.');
        }

        else {
            if (!$o_Usuario->undelete()) {
                $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Usuario->getErrores();
            }
            else {
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_USUARIO_EDITAR, $a_Logs_Tipos[LOG_USUARIO_EDITAR], '<b>Id:</b> ' . $o_Usuario->getId() . ' <b>Nombre:</b> ' . $o_Usuario->getNombreCompleto(), $o_Usuario->getId());
                $T_Mensaje = _('El usuario fue recuperado correctamente.');
            }
        }


        $T_Eliminado = true;
        $T_Link = '_mos';
        goto defaultlabel;

        break;


    // HABILITAR USUARIO
    case 'enable':
        SeguridadHelper::Pasar(90);

        $o_Usuario = Usuario_L::obtenerPorId($T_Id, true);

        if (is_null($o_Usuario)) {
            $T_Error = _('Lo sentimos, el usuario que desea habilitar no existe.');
        }

        if (!is_null($o_Usuario)) {
            if (!$o_Usuario->enable(Registry::getInstance()->general['debug'])) {
                //$T_Error = 'Lo sentimos, el usuario que desea habilitar no puede ser modificado.';
                $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Usuario->getErrores();
            } else {
                //SeguridadHelper::Reporte(Registry::getInstance()->Usuario->getId(), $T_Titulo . '-' . $a_Acciones[2], 'Tabla - ' . $T_Script . ' Id - ' . $o_Usuario->getId());
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_USUARIO_DESBLOQUEAR, $a_Logs_Tipos[LOG_USUARIO_DESBLOQUEAR], '<b>Id:</b> ' . $o_Usuario->getId() . ' <b>Usuario:</b> ' . $o_Usuario->getEmail() . ' <b>Nombre:</b> ' . $o_Usuario->getNombreCompleto(), $o_Usuario->getId());
                $T_Mensaje = _('El usuario fue habilitado correctamente.');
            }
        }

        $T_Habilitando = true;
        goto defaultlabel;
        break;


    // DESHABILITAR USUARIO
    case 'disable':
        SeguridadHelper::Pasar(90);

        $o_Usuario = Usuario_L::obtenerPorId($T_Id);

        if (is_null($o_Usuario)) {
            $T_Error = _('Lo sentimos, el usuario que desea deshabilitar no existe.');
        }

        if (!is_null($o_Usuario)) {
            if(Registry::getInstance()->Usuario->getId()==$o_Usuario->getId()){
                $T_Error = "Lo sentimos, no se puede bloquear a sí mismo.";
            }else{
                if (!$o_Usuario->disable(Registry::getInstance()->general['debug'])) {
                    //$T_Error = 'Lo sentimos, el usuario que desea Bloquear no puede ser eliminado.';
                    $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Usuario->getErrores();
                } else {
                    //SeguridadHelper::Reporte(Registry::getInstance()->Usuario->getId(), $T_Titulo . '-' . $a_Acciones[3], 'Tabla - ' . $T_Script . ' Id - ' . $o_Usuario->getId());
                    SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_USUARIO_BLOQUEAR, $a_Logs_Tipos[LOG_USUARIO_BLOQUEAR], '<b>Id:</b> ' . $o_Usuario->getId() . ' <b>Usuario:</b> ' . $o_Usuario->getEmail() . ' <b>Nombre:</b> ' . $o_Usuario->getNombreCompleto(), $o_Usuario->getId());
                    $T_Mensaje = _('El usuario fue bloqueado correctamente.');
                }
            }

        }

        $T_Eliminado = true;
        goto defaultlabel;
        break;





    // ENVIAR CORREO BIEVENIDA
    case 'enviaremail':
        SeguridadHelper::Pasar(999);

        $o_Usuario = Usuario_L::obtenerPorId($T_Id);

        if (is_null($o_Usuario)) {
            $T_Error = _('Lo sentimos, el usuario que desea no existe.');
        }

        if (!is_null($o_Usuario)) {


            $token = bin2hex(random_bytes(50));
            $o_Usuario->setResetToken($token);
            $o_Usuario->save('Off');


            $mail= new Email_O();
            $Sujeto="Bienvenido a enPunto!";
            $Cuerpo="<h3>Tu cuenta enPunto ya se encuentra activa!</h3><br/>"
                . "<br/>"
                . "<b>Éstos son los datos para acceder a tu cuenta:</b>"
                . "<br/>"
                . "<br/>"
                . "<span style=\"width:20%;display:inline-block;\">URL Empresa:</span><b>".$subdominio."</b>"
                . "<br/>"
                . "<span style=\"width:20%;display:inline-block;\">Usuario:</span><b>".$o_Usuario->getEmail()."</b>"
                . "<br/>"
                . "<span style=\"width:20%;display:inline-block;\">Contraseña:</span><i>Podés generarla <a href=\"https://".$subdominio.".enpuntocontrol.com/password.php?t=".$token."\">aquí</a></i>"
                . "<br/>"
                . "<br/>"
                . "Para ingresar al sistema, sigue el siguiente enlace:"
                . "<br/>"
                . "<br/>"
                . "<br/>"
                . "<div style=\"width:100%;text-align:center;\">"
                . "<a class=\"btn\" href=\"https://".$subdominio.".enpuntocontrol.com\" style=\"margin: 0;padding: 6px 12px;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color: #333;display: inline-block;margin-bottom: 0;font-size: 14px;font-weight: normal;line-height: 1.428571429;text-align: center;white-space: nowrap;vertical-align: middle;cursor: pointer;border: 1px solid transparent;border-radius: 4px;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;-o-user-select: none;user-select: none;background-color: white;border-color: #CCC;\">"
                . "Login enPunto</a>"
                . "</div>"
                . "<br/>"
                . "<br/>"
                . "<br/>"
                . "Te recordamos que al utilizar nuestro sistema, estás aceptando nuestros "
                . "<a href=\"https://".$subdominio.".enpuntocontrol.com/terminosycondiciones.pdf\">términos y condiciones</a>"
                . " puedes acceder a ellos desde el link anterior, o desde el sistema, en la sección de ayuda."
                . "<br/><br/><br/>"
                . "Ante cualquier inconveniente, contacta a nuestro soporte técnico"
                . "";
            $mail->setSujeto($Sujeto);
            $mail->setCuerpo($Cuerpo);
            $mail->setFrom("enPunto");
            $mail->setDestinatario($o_Usuario->getEmail());
            $mail->setDestinatarioBCC("fabricio.collino@enpuntocontrol.com");
            $mail->enviar();
            $mail->setEstado(2); //enviado
            $mail->setFecha(date("Y-m-d H:i:s"),"Y-m-d H:i:s");
            $mail->save('Off');

        }

        $T_Eliminado = true;
        goto defaultlabel;
        break;





    // MOSTRAR USUARIO
    case 'show':
        SeguridadHelper::Pasar(90);
        $o_Usuario = Usuario_L::obtenerPorId($T_Id, true);

        if (is_null($o_Usuario)) {
            $T_Error = _('Lo sentimos, el usuario no existe.');
        }

        $T_Link = '_mos';
        break;






    // CREAR o_Listado DE USUARIOS
    default:
        defaultlabel:

        SeguridadHelper::Pasar(90);

        if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 999)
                $o_Listado = Usuario_L::obtenerTodosSP('', '', 'ASC');
        else {

            $o_Listado = Usuario_L::obtenerTodosSP(' usu_Tus_Id<>1 AND usu_Tus_Id<>6 ', '', 'ASC');
        }
        $T_Link = '';
        break;




}
