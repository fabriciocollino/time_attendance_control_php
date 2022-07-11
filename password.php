<?php


require_once(dirname(__FILE__) . '/_ruta.php');

require_once(APP_PATH . '/libs/random/random.php');

//fuerzo que el login sea por SSL
if(Config_L::p('force_ssl'))
		if(!isHTTPS())
				forceSSL();

if (isset($_POST['btnLogout'])) {
	header('Location: ' . WEB_ROOT . '/logout.php');
	exit();
}


$T_Email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
$T_Token = isset($_REQUEST['t']) ? $_REQUEST['t'] : '';
$T_Clave = isset($_REQUEST['clave']) ? $_REQUEST['clave'] : '';
$T_ReClave = isset($_REQUEST['re_clave']) ? $_REQUEST['re_clave'] : '';
$mensaje='';

//echo "<pre>";print_r($_REQUEST);echo "</pre>";

if (isset($_SESSION['USUARIO'])) {
    header('Location: ' . WEB_ROOT. '/');
    exit();
}

//echo "<pre>";print_r($o_Cliente);echo "</pre>";


if (isset($_POST['btnReset'])) {
    
    if(SeguridadHelper::CheckResetEmailAttempts($_SERVER['REMOTE_ADDR'])){
    
        if($T_Email!=''){
            $o_Usuario = Usuario_L::obtenerPorEmail($T_Email);
            
            if (is_null($o_Usuario)) {
                    //el email no existe, guardo un log
                    $T_Error="El email no existe.";
                    SeguridadHelper::Reset(0,$T_Email);
            } else {
                    //genero los tokens, para enviar por email

                $array_dominio          =   explode         (   "."     ,   $_SERVER['HTTP_HOST']   );
                $subdominio_inseguro    =   array_shift     (   $array_dominio                      );
                $subdominio             =   preg_replace    (   "/[^a-zA-Z0-9]+/", "", $subdominio_inseguro);





                SeguridadHelper::Reset($o_Usuario->getId(),$T_Email);
                    $token = bin2hex(random_bytes(50));
                    $o_Usuario->setResetToken($token);                    
                    $o_Usuario->save('Off');     
                    $mail= new Email_O();     
                    $Sujeto="Recuperación de Contraseña";
                    $Cuerpo="Recibimos tu petición para resetear la contraseña.<br/>"
                            . "<br/>"
                            . "<div style=\"width:100%;text-align:center;\">"
                            . "<a class=\"btn\" href=\"https://".$subdominio.".enpuntocontrol.com/password.php?t=".$token."\" style=\"margin: 0;padding: 6px 12px;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color: #333;display: inline-block;margin-bottom: 0;font-size: 14px;font-weight: normal;line-height: 1.428571429;text-align: center;white-space: nowrap;vertical-align: middle;cursor: pointer;border: 1px solid transparent;border-radius: 4px;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;-o-user-select: none;user-select: none;background-color: white;border-color: #CCC;\">"
                            . "Haz click aquí para generar tu contraseña</a>"
                            . "</div>"
                            . "<br/><br/><br/>"
                            . "Ante cualquier inconveniente, contacta a nuestro soporte técnico"
                            . "";
                    $mail->setSujeto($Sujeto);
                    $mail->setCuerpo($Cuerpo);
                    $mail->setFrom("Contraseña enPunto");
                    $mail->setDestinatario($o_Usuario->getEmail());
                    $mail->enviar();
                    $mail->setEstado(2); //enviado
                    $mail->setFecha(date("Y-m-d H:i:s"),"Y-m-d H:i:s");
                    $mail->save('Off');
                    
                    $mensaje="Se ha enviado un email, por favor sigue las instrucciones para resetear la contraseña.";
	
            }
        }else{
            
            if($T_Token!=''){
                //vengo con el token, reseteo la password si esta todo bien
                if(SeguridadHelper::CheckResetTokenAttempts($_SERVER['REMOTE_ADDR'])){
                    $o_usuario=Usuario_L::obtenerPorToken($T_Token);

                    if(is_null($o_usuario)){
                        //el usuario no existe
                        $T_Token='';
                        $T_Error="El token no es válido";
                        SeguridadHelper::Reset(0,'');
                    }else{
                        if($T_Clave!='' && $T_ReClave!=''){
                            SeguridadHelper::Reset($o_usuario->getId());
                            //reseteo la password
                            $o_usuario->setClave($T_Clave);
                            $o_usuario->setConfirmacionClave($T_ReClave);
                            $o_usuario->clearResetToken();
                            $o_usuario->save('Off');

                            $array_dominio          =   explode         (   "."     ,   $_SERVER['HTTP_HOST']   );
                            $subdominio_inseguro    =   array_shift     (   $array_dominio                      );
                            $subdominio             =   preg_replace    (   "/[^a-zA-Z0-9]+/", "", $subdominio_inseguro);



                            
                            $mensaje="Su contraseña se ha reseteado correctamente.<br/> Vuelva a ingresar <a href=\"https://".$subdominio.".enpuntocontrol.com\">aquí</a>.";
                        }
                    }
                }else{
                    $T_Token='';
                    $T_Error="Bloqueado por varios intentos fallidos. Intente nuevamente más tarde.";
                }
            }
        }
    }else{
        $T_Error="Bloqueado por varios intentos fallidos. Intente nuevamente más tarde.";
    }
}
else{
    if($T_Token!=''){
        //vengo con el token, reseteo la password si esta todo bien
        if(SeguridadHelper::CheckResetTokenAttempts($_SERVER['REMOTE_ADDR'])){
            $o_usuario=Usuario_L::obtenerPorToken($T_Token);

    //echo $o_usuario->getNombre();
            if(is_null($o_usuario)){
                //el usuario no existe
                
                SeguridadHelper::Reset(0,'');
                $T_Token='';
                $T_Error='El Token no es válido';
            }else{
                //reseteo la password
                //tengo el usuario
            }
        }else{
            $T_Token='';
            $T_Error="Bloqueado por varios intentos fallidos. Intente nuevamente más tarde.";
            $mensaje='';
        }
    }
    
    
    
}
//if(isHTTPS())echo "Conexión segura";
//echo $_SERVER["HTTP_X_FORWARDED_FOR"];

require_once APP_PATH . '/templates/password.html.php';