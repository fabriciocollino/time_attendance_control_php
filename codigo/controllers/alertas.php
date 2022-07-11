<?php

/* VARIABLES SECCIÓN */
$T_Titulo                   = _('Notificaciones');
$T_Script                   = 'notificaciones';
$Item_Name                  = "notificaciones";
$T_Link                     = '';
$T_Mensaje                  = '';

/* NOTIFICACION TIPO*/
$T_Tipo                     = isset($_REQUEST['tipo'])                          ?       $_REQUEST['tipo']                       : '';

/* VARIABLES NOTIFICACION */
$T_Id                       = isset($_REQUEST['id'])                            ?       (integer)$_REQUEST['id']                : 0;
$T_Detalle                  = isset($_REQUEST['detalle'])                       ?       $_REQUEST['detalle']                    : '';

/* VARIABLES DESTINATARIO */
$T_Destinatario_Usuario     = isset($_REQUEST['destinatario_usuario'])          ?       $_REQUEST['destinatario_usuario']       : '';
$T_Email                    = isset($_REQUEST['destinatario_email'])            ?       $_REQUEST['destinatario_email']         : '';

//$T_Destinatario_Grupo       = isset($_REQUEST['destinatario_grupo'])            ?       $_REQUEST['destinatario_grupo']         : '';
//$T_Destinatario_Persona     = isset($_REQUEST['destinatario_persona'])          ?       $_REQUEST['destinatario_persona']       : '';


/* VARIABLES DISPARADOR */
$T_Disparador_Evento        = isset($_REQUEST['disparador_evento'])             ?       $_REQUEST['disparador_evento']          : '';
$T_Disparador_Persona       = isset($_REQUEST['disparador_persona'])            ?       $_REQUEST['disparador_persona']         : '';
$T_Disparador_Grupo         = isset($_REQUEST['disparador_grupo'])              ?       $_REQUEST['disparador_grupo']           : '';

/* VARIABLES CONTENIDO */
$T_Mensaje_Detalle          = isset($_REQUEST['mensaje_detalle'])               ?       $_REQUEST['mensaje_detalle']            : '';
$T_Mensaje_Cuerpo           = isset($_POST['mensaje_cuerpo'])                   ?       $_POST['mensaje_cuerpo']             : '';

// ACTIVA
$T_Activa                   = isset($_REQUEST['activa'])                                ?       (integer)$_REQUEST['activa']                : 1;

switch ($T_Tipo) {
    case 'view':

        /* NOTIFICACION */
        $o_Notificacion = Notificaciones_L::obtenerPorId($T_Id);


        /* NOTIFICACIÓN NO EXISTE */
        if (is_null($o_Notificacion)) {
            $o_Notificacion = new Notificaciones_O();
        }

        /* VARIABLES NOTIFICACION */
        $T_Id                           = $o_Notificacion->getId();
        $T_Detalle                      = $o_Notificacion->getDetalle();


        /* VARIABLES DESTINATARIO */
        $i_Destinatario_Usuario         = $o_Notificacion->getUsuId();

        /*
        $i_Destinatario_Persona         = $o_Notificacion->getPerId();
        $i_Destinatario_Grupo           = $o_Notificacion->getGrupo();

        if($i_Destinatario_Usuario != 0 || $T_Id == 0){
            $i_Destinatario_Persona = "Usuario";
        }

        if($i_Destinatario_Grupo != 0){
            $i_Destinatario_Persona = "Grupo";
        }
*/

        $T_Destinatario_Usuario         = HtmlHelper::array2htmloptions(Usuario_L::obtenerListaEmails()                                             ,   $i_Destinatario_Usuario     , true  , false , 'Usuarios'        , 'Seleccione un Usuario');
        $T_Email                        = $o_Notificacion->getEmailMe();
        //$T_Destinatario_Persona       = HtmlHelper::array2htmloptions(Persona_L::obtenerTodos(0,0,0,'per_Eliminada = 0 and per_Excluir= 0 ')      ,   $i_Destinatario_Persona     , true  , true  , 'Alerta'    , 'Seleccione un Tipo');
        //$T_Destinatario_Grupo         = HtmlHelper::array2htmloptions(Grupo_L::obtenerTodos()                                                     ,   $i_Destinatario_Grupo       , true  , true  , 'GrupoPersonas'   , 'Seleccione Grupo de Personas');


        /* VARIABLES DISPARADOR */
        $i_Disparador_Evento            = $o_Notificacion->getDisparador();
        $i_Disparador_Persona           = $o_Notificacion->getPersonaD();
        $i_Disparador_Grupo             = $o_Notificacion->getRolD();


        if($i_Disparador_Grupo != 0){
            $i_Disparador_Persona = "SelectRol";
        }
        else if( $i_Disparador_Persona == 0 || $T_Id == 0){
            $i_Disparador_Persona = "TodasLasPersonas";
        }



        $T_Disparador_Evento            = HtmlHelper::array2htmloptions($Notificaciones_Disparadores_1                                          , $i_Disparador_Evento          , 1     , false , ''                , 'Seleccione un Disparador');
        $T_Disparador_Persona           = HtmlHelper::array2htmloptions(Persona_L::obtenerDesdeFiltro(Filtro_L::get_filtro_persona() )          , $i_Disparador_Persona         , true  , true  , 'PersonayRol'     , 'Todas las Personas');
        $T_Disparador_Grupo             =   HtmlHelper::array2htmloptions(Grupo_L::obtenerTodos()                                                   , $i_Disparador_Grupo                 , true  , true  , ''    , 'Seleccione un Grupo');


        /* VARIABLES CONTENIDO */
        $T_Mensaje_Detalle              = $o_Notificacion->getDetalleC();
        $T_Mensaje_Cuerpo               = $o_Notificacion->getTextoC();



        break;

    case 'add':
    case 'edit':

        /* NOTIFICACIÓN */
        $o_Notificacion = Notificaciones_L::obtenerPorId($T_Id);


        /* NOTIFICACIÓN NO EXISTE */
        if (is_null($o_Notificacion)) {
            $o_Notificacion = new Notificaciones_O();
        }

        /* NOTIFICACION TIPO */
        $o_Notificacion->setTipo(1);//email
        $o_Notificacion->setTipoD(0);//destinatario
        $o_Notificacion->setTipoC(1);//contenido

        /* NOTIFICACION DETALLE */
        $o_Notificacion->setDetalle($T_Detalle);

        /* NOTIFICACION DESTINATARIO */
        $o_Notificacion->setUsuId($T_Destinatario_Usuario);
        $o_Notificacion->setEmailMe($T_Email);
        //$o_Notificacion->setGrupo($T_Destinatario_Grupo);
        //$o_Notificacion->setPerId($T_Destinatario_Persona);


        /* NOTIFICACION DISPARADOR */
        $o_Notificacion->setDisparador($T_Disparador_Evento);
        $o_Notificacion->setPersonaD_NoCHECK($T_Disparador_Persona);
        $o_Notificacion->setRolD_NoCHECK($T_Disparador_Grupo);

        /* NOTIFICACION CONTENIDO */
        //$o_Notificacion->setDetalleC($T_Mensaje_Detalle);
        //$o_Notificacion->setTextoC($T_Mensaje_Cuerpo);
        $o_Notificacion->setDetalleC($Notificaciones_Disparadores_1[$T_Disparador_Evento]);
        $o_Notificacion->setTextoC($T_Mensaje_Cuerpo);

        /* NOTIFICACION ACTIVA */
        $o_Notificacion->setActiva($T_Activa);

        /* GUARDAR */
        if($o_Notificacion->save(Registry::getInstance()->general['debug'])) {
            $T_Mensaje = $T_Tipo == 'edd' ? _('La alerta fue guardada correctamente.') : _('La alerta fue modificada correctamente.');
        }
        else {
            $T_Error = "Lo sentimos. Hubo un error en la operación." ;//$o_Notificacion->getErrores();
        }

        /* LISTADO NOTIFICACIONES */
        $o_Listado = Notificaciones_L::obtenerTodasAlertas();

        break;



    case 'send':

        $o_Notificacion = Notificaciones_L::obtenerPorId($T_Id);


        $condition_persona = [
            'persona'   => $o_Notificacion->getPersonaD(),
            'rolf'      => $o_Notificacion->getRolD()
        ];

        $T_Filtro_Array                 = Filtro_L::get_filtro_persona($condition_persona);
        $array_personas_a_controlar     = Persona_L::obtenerDesdeFiltroArray($T_Filtro_Array);
        $keys_array                     = array_keys($array_personas_a_controlar);
        $firstKey                       = $keys_array[0];

        $alerta_enviada = $o_Notificacion->alerta_log_enviar($firstKey, false);
        if($alerta_enviada) {

            if($alerta_enviada === 'Enviado anteriormente'){
                $T_Mensaje = _('La alerta ya había sido enviada.');
            }
            else{
                $T_Mensaje = _('La alerta se envió correctamente.');
            }
            $o_Notificacion->setDisparadorHora(date('Y-m-d H:i:s'));
            $o_Notificacion->save();



        }
        else {
            $T_Error = _('Hubo un error al procesar la operación.');
        }

        $o_Listado = Notificaciones_L::obtenerTodasAlertas();

        break;


    case 'delete':
        $o_Notificacion = Notificaciones_L::obtenerPorId($T_Id);

        if (!$o_Notificacion->delete(Registry::getInstance()->general['debug'])) {
            $T_Error = $o_Notificacion->getErrores();
            break;
        }

        $T_Eliminado = true;
        $T_Mensaje = _('La alerta ha sido eliminada correctamente.');
        $o_Listado = Notificaciones_L::obtenerTodasAlertas();

        break;


    default:
        $o_Listado = Notificaciones_L::obtenerTodasAlertas();
}

$_SESSION['filtro']['tipo_data']             =   'Alertas';
$_SESSION['filtro']['persona_data']          =   Filtro_L::get_filtro_persona();
$_SESSION['filtro']['intervalo_data']        =   '';