<?php


$T_Titulo                   = _('Notificaciones');
$T_Script                   = 'notificaciones';
$Item_Name                  = "notificaciones";
$T_Link                     = '';
$T_Mensaje                  = '';
$T_Tipo                     = isset($_REQUEST['tipo'])                                  ?       $_REQUEST['tipo']                           : '';

// NOTIFICACIÓN
$T_Id                       = isset($_REQUEST['id'])                                    ?       (integer)$_REQUEST['id']                    : 0;
$T_Detalle                  = isset($_POST['detalle'])                                  ?       $_POST['detalle']                           : '';

// DESTINATARIO
$T_Destinatario_Tipo        = isset($_REQUEST['destinatario_tipo'])                     ?       $_REQUEST['destinatario_tipo']              : 0;
$T_Destinatario_Usuarios    = intval(isset($_REQUEST['destinatario_usuario'])           ?       $_REQUEST['destinatario_usuario']           : 0);
$T_Destinatario_Grupos      = intval(isset($_REQUEST['destinatario_grupo'])             ?       $_REQUEST['destinatario_grupo']             : 0);
$T_Destinatario_Personas    = intval(isset($_REQUEST['destinatario_persona'])           ?       $_REQUEST['destinatario_persona']           : 0);
$T_Email_Me                 = isset($_POST['email_me'])                                 ?       $_POST['email_me']                          : '';

// FRECUENCIA
$T_Hora                     = isset($_POST['hora'])             ?       $_POST['hora']                  : '';
$T_Repetir                  = isset($_POST['repetir'])          ?       $_POST['repetir']               : 0;

// CONTENIDO
$T_Tipoc                    = isset($_POST['tipoc'])            ?       $_POST['tipoc']                 : 0;
$T_Detallec                 = isset($_POST['detallec'])         ?       $_POST['detallec']              : '';
$T_Mensajec                 = isset($_POST['mensajec'])         ?       $_POST['mensajec']              : '';
$T_Reportec                 = isset($_POST['reportec'])         ?       $_POST['reportec']              : '';
$T_Personac                 = isset($_POST['personac'])         ?       $_POST['personac']              : 0;
$T_Intervaloc               = isset($_POST['intervaloc'])       ?       $_POST['intervaloc']            : 0;
$T_Grupoc                   = isset($_POST['rolc'])             ?       $_POST['rolc']                  : 0;
$T_DescargarTipo            = isset($_POST['DescargarTipo'])    ?       $_POST['DescargarTipo']         : 0;

// ACTIVA
$T_Activa                   = isset($_REQUEST['activa'])                                ?       (integer)$_REQUEST['activa']                : 1;

$o_Contenido                = '';

$nuevo_reporte = false;


switch ($T_Tipo) {
    case 'view':

        /* NOTIFICACIÓN */
        $o_Notificacion = Notificaciones_L::obtenerPorId($T_Id);

        /* NOTIFICACIÓN NO EXISTE */
        if (is_null($o_Notificacion)) {
            $o_Notificacion = new Notificaciones_O();
            $o_Notificacion->setTipoD(1);
            $o_Notificacion->setTipoC(2);
            $nuevo_reporte = true;
        }

        /* DATOS */
        $T_Destinatario_Usuarios        =   $o_Notificacion->getUsuId();
        $T_Destinatario_Grupos          =   $o_Notificacion->getGrupo();
        $T_Destinatario_Tipo            =   $o_Notificacion->getPerId();
        $T_Email_Me                     =   $o_Notificacion->getEmailMe();
        $T_Repetir                      =   $o_Notificacion->getTipoD() != 0  ?       $o_Notificacion->getTipoD()     :   1;
        $T_Hora                         =   $o_Notificacion->getHoraD("Y-m-d H:i:s");
        $T_Personac                     =   $o_Notificacion->getPersonaC();
        $T_Grupoc                       =   $o_Notificacion->getRolC();
        $T_Intervaloc                   =   $o_Notificacion->getIntervaloc();
        $T_Reportec                     =   $o_Notificacion->getTipoC();
        $T_Detalle                      =   $o_Notificacion->getDetalle();
        $T_Detallec                     =   $o_Notificacion->getDetalleC();
        $T_Mensajec                     =   $o_Notificacion->getTextoC();
        $T_DescargarTipo                =   $o_Notificacion->getReporteDescargarTipoC();


        /* VARIABLES */
        $i_Destinatario_Usuarios        =   $T_Destinatario_Usuarios;
        $i_Destinatario_Grupos          =   $T_Destinatario_Grupos;
        $i_Destinatario_Tipo            =   $T_Destinatario_Tipo;

        $i_Repetir                      =   $T_Repetir != ''                    ?   $T_Repetir          :   0;
        $i_Personac                     =   $T_Personac != ''                   ?   $T_Personac         :   0;
        $i_Grupoc                       =   $T_Grupoc != ''                     ?   $T_Grupoc           :   0;
        $i_Intervaloc                   =   $T_Intervaloc != ''                 ?   $T_Intervaloc       :   0;
        $i_Reportec                     =   $T_Reportec != ''                   ?   $T_Reportec         :   0;
        $i_DescargarTipo                =   $T_DescargarTipo != ''              ?   $T_DescargarTipo    :   0;

        /* HTML */
        $T_Destinatario_Usuarios        =   HtmlHelper::array2htmloptions(Usuario_L::obtenerListaEmails()                                           , $i_Destinatario_Usuarios  , true  , false , 'Usuarios'        , 'Seleccione un Usuario');
        $T_Destinatario_Grupos          =   HtmlHelper::array2htmloptions(Grupo_L::obtenerTodos()                                                   , $i_Destinatario_Grupos    , true  , true  , 'GrupoPersonas'   , 'Seleccione Grupo de Personas');
        $T_Destinatario_Tipo            =   HtmlHelper::array2htmloptions(Persona_L::obtenerTodos(0,0,0,'per_Eliminada = 0 and per_Excluir= 0 ')    , $i_Destinatario_Tipo      , true  , true  , 'Destinatario'    , 'Seleccione un Tipo');
        $T_Repetir                      =   HtmlHelper::array2htmloptions($a_Notificaciones_Contenidos_Repetir                                      , $i_Repetir                , true  , false , ''    , 'Seleccione una Repetición');
        $T_Grupoc                       =   HtmlHelper::array2htmloptions(Grupo_L::obtenerTodos()                                                   , $i_Grupoc                 , true  , true  , ''    , 'Seleccione un Grupo');
        $T_Personac                     =   HtmlHelper::array2htmloptions(Persona_L::obtenerTodos(0,0,0,'per_Eliminada = 0 and per_Excluir= 0 ')    , $i_Personac               , true  , true  , 'PersonayRol'     , 'Todas las Personas');
        $T_Intervaloc                   =   HtmlHelper::array2htmloptions($a_Notificaciones_Contenidos_Intervalos                                   , $i_Intervaloc             , true  , false , ''    , 'Período del Reporte');
        $T_Reportec                     =   HtmlHelper::array2htmloptions($Notificaciones_Contenidos_V2                                              , $i_Reportec               , true  , false , ''    , 'Seleccione un Reporte');
        $T_DescargarTipo                =   HtmlHelper::array2htmloptions($a_Notificaciones_Reporte_Descargar_Tipo                                  , $i_DescargarTipo          , true  , false , ''    , 'Seleccione un Formato');



        break;


    case 'add':
    case 'edit':

        /* NOTIFICACIÓN */
        $o_Notificacion = Notificaciones_L::obtenerPorId($T_Id);

        /* NOTIFICACIÓN NO EXISTE */
        if (is_null($o_Notificacion)) {
            $o_Notificacion = new Notificaciones_O();
        }

        /* VARIABLES*/
        $T_Destinatario_Usuarios    = $T_Destinatario_Tipo == 'Usuario' ? $T_Destinatario_Usuarios : 0;
        $T_Destinatario_Grupos      = $T_Destinatario_Tipo == 'Grupo'   ? $T_Destinatario_Grupos : 0;
        $T_Destinatario_Tipo        = $T_Destinatario_Tipo != 'Usuario' && $T_Destinatario_Tipo != 'Grupo' ? $T_Destinatario_Tipo : 0;


        /* SET DE DATOS */
        $o_Notificacion->setDetalle($T_Detalle);
        $o_Notificacion->setUsuId($T_Destinatario_Usuarios);
        $o_Notificacion->setGrupo($T_Destinatario_Grupos);
        $o_Notificacion->setPerId($T_Destinatario_Tipo);
        $o_Notificacion->setEmailMe($T_Email_Me);
        $o_Notificacion->setHoraD($T_Hora, "Y-m-d H:i:s");

        $o_Notificacion->setIntervaloC($T_Intervaloc);
        $o_Notificacion->setTipoC($T_Reportec);
        $o_Notificacion->setDetalleC($T_Detallec);
        $o_Notificacion->setReporteDescargarTipoC($T_DescargarTipo);
        $o_Notificacion->setTipoD($T_Repetir);
        $o_Notificacion->setActiva($T_Activa);
        $o_Notificacion->setTipo(1);//email
        $o_Notificacion->setTextoC($T_Mensajec);

        $o_Notificacion->setRolC_NoCHECK($T_Grupoc);
        $o_Notificacion->setPersonaC_NoCHECK($T_Personac == 'TodasLasPersonas' ? 0 : $T_Personac);


        /* GUARDAR */
        if ($o_Notificacion->save(Registry::getInstance()->general['debug'])) {
            $T_Modificar    = $o_Notificacion->getId() != null ? true : false;
            $T_Mensaje      = $T_Modificar ? _('El reporte fue modificado correctamente.') : _('El reporte fue guardado correctamente.');
        }
        /* ERRORES */
        else {
            $T_Error = $o_Notificacion->getErrores();
        }

        /* VARIBALES */
        $T_Modificar    = true;
        $T_Link         = '';

        /* LISTADO */
        $o_Listado      = Notificaciones_L::obtenerTodosReportes();

        break;



    case 'send':

        $o_Notificacion = Notificaciones_L::obtenerPorId($T_Id);
        $o_Notificacion->enviarReporteAutomaticoObjeto(true);
        $o_Notificacion->setDisparadorHora(date('Y-m-d H:i:s'));
        $o_Notificacion->save(Registry::getInstance()->general['debug']);

        $T_Mensaje = _('El reporte se envió correctamente.');

        $_SESSION['Mensaje'] = '';

        $o_Listado = Notificaciones_L::obtenerTodosReportes();
        break;


    case 'delete':

        $o_Notificacion = Notificaciones_L::obtenerPorId($T_Id);

        if (!$o_Notificacion->delete(Registry::getInstance()->general['debug'])) {
            $T_Error = $o_Notificacion->getErrores();
        }
        else {
            $T_Eliminado = true;
            $T_Mensaje = _('El reporte ha sido eliminado correctamente.');
            $o_Listado = Notificaciones_L::obtenerTodosReportes();
        }


        break;


    default:
        $o_Listado = Notificaciones_L::obtenerTodosReportes();
        break;
}
$_SESSION['filtro']['tipo_data']             =   'Reportes_Auotmaticos';
$_SESSION['filtro']['persona_data']          =   Filtro_L::get_filtro_persona();
$_SESSION['filtro']['intervalo_data']        =   '';