<?php


$T_Titulo           = _('Notificaciones');
$T_Script           = 'notificaciones';
$Item_Name          = "notificaciones";
$T_Link             = '';
$T_Mensaje          = '';

$T_Tipo             = isset($_REQUEST['tipo'])      ? $_REQUEST['tipo']                 : '';
$T_Id               = isset($_REQUEST['id'])        ? (integer)$_REQUEST['id']          : 0;

$T_Tipon            = intval(isset($_POST['tipon'])     ? $_POST['tipon']       : 0);
$T_Detalle          = isset($_POST['detalle'])                  ? $_POST['detalle']     : '';
$T_Email_Me         = isset($_POST['email_me'])                 ? $_POST['email_me']    : '';

$T_Activa                   = isset($_REQUEST['activa'])                ? (integer)$_REQUEST['activa']          : 0;
$T_Destinatario_Tipo        = isset($_REQUEST['destinatario_tipo'])     ? $_REQUEST['destinatario_tipo']        : 0;
$T_Destinatario_Usuarios    = intval(isset($_REQUEST['destinatario_usuario'])   ? $_REQUEST['destinatario_usuario']         : 0);
$T_Destinatario_Grupos      = intval(isset($_REQUEST['destinatario_grupo'])     ? $_REQUEST['destinatario_grupo']           : 0);
$T_Destinatario_Personas    = intval(isset($_REQUEST['destinatario_persona'])   ? $_REQUEST['destinatario_persona']         : 0);


//DISPARADOR
$T_Tipod            = isset($_POST['tipod'])            ? $_POST['tipod']           : 0;
$T_Hora             = isset($_POST['hora'])             ? $_POST['hora']            : '';
$T_Disparador       = isset($_POST['disparador'])       ? $_POST['disparador']      : '';
$T_Equipo           = isset($_POST['equipo'])           ? $_POST['equipo']          : 0;
//$T_Alarma         = isset($_POST['alarma'])           ? $_POST['alarma']          : 0;
$T_Persona          = isset($_POST['persona'])          ? $_POST['persona']         : 0;
$T_Repetir          = isset($_POST['repetir'])          ? $_POST['repetir']         : 0;
$T_Accion           = isset($_POST['accion'])           ? $_POST['accion']          : 0;
$T_Grupos           = isset($_POST['grupos'])           ? $_POST['grupos']          : 0;

$T_Hora_Inicio_Lun  = isset($_POST['hs_inicio_lun'])    ? $_POST['hs_inicio_lun']   : '--:--';
$T_Hora_Inicio_Mar  = isset($_POST['hs_inicio_mar'])    ? $_POST['hs_inicio_mar']   : '--:--';
$T_Hora_Inicio_Mie  = isset($_POST['hs_inicio_mie'])    ? $_POST['hs_inicio_mie']   : '--:--';
$T_Hora_Inicio_Jue  = isset($_POST['hs_inicio_jue'])    ? $_POST['hs_inicio_jue']   : '--:--';
$T_Hora_Inicio_Vie  = isset($_POST['hs_inicio_vie'])    ? $_POST['hs_inicio_vie']   : '--:--';
$T_Hora_Inicio_Sab  = isset($_POST['hs_inicio_sab'])    ? $_POST['hs_inicio_sab']   : '--:--';
$T_Hora_Inicio_Dom  = isset($_POST['hs_inicio_dom'])    ? $_POST['hs_inicio_dom']   : '--:--';

//CONTENIDO
$T_Detallec         = isset($_POST['detallec'])         ? $_POST['detallec']        : '';
$T_Tipoc            = isset($_POST['tipoc'])            ? $_POST['tipoc']           : 0;
$T_Mensajec         = isset($_POST['mensajec'])         ? $_POST['mensajec']        : '';
$T_Reportec         = isset($_POST['reportec'])         ? $_POST['reportec']        : '';
$T_Equipoc          = isset($_POST['equipoc'])          ? $_POST['equipoc']         : 0;
$T_Personac         = isset($_POST['personac'])         ? $_POST['personac']        : 0;
$T_Intervaloc       = isset($_POST['intervaloc'])       ? $_POST['intervaloc']      : 0;
$T_Grupoc           = isset($_POST['rolc'])             ? $_POST['rolc']            : 0;
$T_DescargarTipo    = isset($_POST['DescargarTipo'])    ? $_POST['DescargarTipo']   : 0;

//TIPOS DE CONTENIDO
/*
 * Reporte tipo 1   = aviso
 * tipo 10          = reporte por persona
 * tipo 11          = reporte por zona
 * tipo 12          = reporte por equipo
 * tipo 13          = reporte por alarmas
 * tipo 14          = reporte por periferico
 * tipo 15          = reporte por persona y periferico
 * 
 * 
 * */

switch ($T_Tipo) {
    case 'view':

        SeguridadHelper::Pasar(50);
        $o_Notificacion = Notificaciones_L::obtenerPorId($T_Id);

        if (is_null($o_Notificacion)) {
            $o_Notificacion = new Notificaciones_O();
            if ($alertaOreporte == 'reporte') {
                $o_Notificacion->setTipoD(1);//diferido
                $o_Notificacion->setTipoC(2);//diferido
                $T_Tipod=1;//diferido
                $T_Tipoc=2;//reporte
                $T_Hora = date('Y-m-d H:i:s');
                $o_Notificacion->setHoraD(date('Y-m-d H:i:s'), "Y-m-d H:i:s");
            } else if ($alertaOreporte == 'alerta') {
                $o_Notificacion->setTipoD(0);//inmediato
                $o_Notificacion->setTipoC(1);//diferido
                $T_Tipod=0;
                $T_Tipoc=1;//aviso
                // $o_Notificacion->setTextoC('Esta es en predefinido contenido');
            }
        } else {

            $T_Tipon = $o_Notificacion->getTipo(); //tipo de notificacion (email, sms etc)  1 : Email, 2: Mensaje

            $T_Destinatario_Grupos = $o_Notificacion->getGrupo();
            $T_Destinatario_Usuarios = $o_Notificacion->getUsuId();
            $T_Destinatario_Personas = $o_Notificacion->getPerId();
            $T_Email_Me = $o_Notificacion->getEmailMe();
            $T_Disparador = $o_Notificacion->getDisparador();
            $T_Persona = $o_Notificacion->getPersonaD();
            $T_Grupos = $o_Notificacion->getRolD();
            $T_Equipo = $o_Notificacion->getEquipoD();
            //$T_Alarma = $o_Notificacion->getAlarmaD();
            $T_Accion = $o_Notificacion->getLogD();
            if ($o_Notificacion->getTipoD() == 0) {    //Disparador Inmediato
                $T_Tipod = 0;
                $T_Repetir = 1;
                // $T_Disparador = $o_Notificacion->getDisparador();
                // $T_Persona = $o_Notificacion->getPersonaD();
                // $T_Grupos = $o_Notificacion->getRolD();
                // $T_Equipo = $o_Notificacion->getEquipoD();
                // $T_Alarma = $o_Notificacion->getAlarmaD();
                // $T_Accion = $o_Notificacion->getLogD();
                // $T_Hora_Inicio_Dom = $o_Notificacion->getHorariosD(0);
                // $T_Hora_Inicio_Lun = $o_Notificacion->getHorariosD(1);
                // $T_Hora_Inicio_Mar = $o_Notificacion->getHorariosD(2);
                // $T_Hora_Inicio_Mie = $o_Notificacion->getHorariosD(3);
                // $T_Hora_Inicio_Jue = $o_Notificacion->getHorariosD(4);
                // $T_Hora_Inicio_Vie = $o_Notificacion->getHorariosD(5);
                // $T_Hora_Inicio_Sab = $o_Notificacion->getHorariosD(6);
            } else if ($o_Notificacion->getTipoD() == 1) {
                $T_Tipod = 1;
                $T_Repetir = 1;
            } else if ($o_Notificacion->getTipoD() == 2) {
                $T_Tipod = 1;
                $T_Repetir = 2;
            } else if ($o_Notificacion->getTipoD() == 3) {
                $T_Tipod = 1;
                $T_Repetir = 3;
            } else if ($o_Notificacion->getTipoD() == 4) {
                $T_Tipod = 1;
                $T_Repetir = 4;
            }else if ($o_Notificacion->getTipoD() == 5) {
                $T_Tipod = 1;
                $T_Repetir = 5;
            }

            $T_Hora = $o_Notificacion->getHoraD("Y-m-d H:i:s");

            if ($o_Notificacion->getTipoC() == 1) {//reporte o aviso
                $T_Tipoc = 1;//aviso /alert
            } else if ($o_Notificacion->getTipoC() >= 2) {
                $T_Tipoc = 2; //reporte
                $T_Intervaloc = $o_Notificacion->getIntervaloc();
                $T_Personac = $o_Notificacion->getPersonaC();
                $T_Grupoc = $o_Notificacion->getRolC();
                $T_Equipoc = $o_Notificacion->getEquipoC();
                $T_Reportec = $o_Notificacion->getTipoC();
                $T_DescargarTipo = $o_Notificacion->getReporteDescargarTipoC();
            }
        }

        if($T_DescargarTipo==null || $T_DescargarTipo=='') $i_DescargarTipo = 0;
        else $i_DescargarTipo = $T_DescargarTipo;

        if ($T_Tipon == '') $i_Tipon = null;
        // else if ($T_Tipon == 0 && $T_Tipon != '') $i_Tipon = 0;
        else $i_Tipon = $T_Tipon;

        if ($T_Tipod == '') $i_Tipod = null;
        // else if ($T_Tipod == 0 && $T_Tipod != '') $i_Tipod = 0;
        else $i_Tipod = $T_Tipod;

        if ($T_Disparador == '') $i_Disparador = null;
        else $i_Disparador = $T_Disparador;

        if ($T_Persona == '' && $T_Grupos == '') $i_Persona = 'TodasLasPersonas';
        else if ($T_Persona == 'TodasLasPersonas') $i_Persona = $T_Persona;
        else if ($T_Persona == 0 && $T_Grupo == '') $i_Persona = 'TodasLasPersonas';
        else if ($T_Persona == 'SelectRol') $i_Persona = $T_Persona;
        else $i_Persona = $T_Persona;

        if ($T_Grupos == '') $i_Grupos = null;
        // else if ($T_Grupos == 0 && $T_Grupos != '') $i_Grupos = 0;
        else $i_Grupos = $T_Grupos;

        if ($T_Equipo == '') $i_Equipo = 'TodosLosEquipos';
        // else if ($T_Equipo == 0 && $T_Equipo != '') $i_Equipo = 'TodosLosEquipos';
        else $i_Equipo = $T_Equipo;

        if ($T_Repetir == '') $i_Repetir = null;
        else if ($T_Repetir == 0) $i_Repetir = 0;
        else $i_Repetir = $T_Repetir;

        if ($T_Accion == '') $i_Accion = null;
        // else if ($T_Accion == 0 && $T_Accion != '') $i_Accion = 0;
        else $i_Accion = $T_Accion;


        if ($T_Tipoc == '') $i_Tipoc = null;
        // else if ($T_Tipoc == 0 && $T_Tipoc != '') $i_Tipoc = 0;
        else $i_Tipoc = $T_Tipoc;

        if ($T_Reportec == '') $i_Reportec = null;
        // else if ($T_Reportec == 0 && $T_Reportec != '') $i_Reportec = 0;
        else $i_Reportec = $T_Reportec;

        if ($T_Personac == '' && $T_Grupoc == '') $i_Personac = 'TodasLasPersonas';
        else if ($T_Personac == 'TodasLasPersonas') $i_Personac = $T_Personac;
        else if ($T_Personac == 0 && $T_Grupoc == '') $i_Personac = 'TodasLasPersonas';
        else if ($T_Personac == 'SelectRol') $i_Personac = $T_Personac;
        else $i_Personac = $T_Personac;


        if ($T_Grupoc == '') $i_Grupoc = null;
        // else if ($T_Grupoc == 0 && $T_Grupoc != '') $i_Grupoc = 0;
        else $i_Grupoc = $T_Grupoc;

        if ($T_Equipoc == '') $i_Equipoc = null;
        else if ($T_Equipoc == 'TodosLosEquipos') $i_Equipoc = $T_Equipoc;
        else if ($T_Equipoc == 0) $i_Equipoc = 'TodosLosEquipos';
        else $i_Equipoc = $T_Equipoc;

        if ($T_Intervaloc == '') $i_Intervaloc = null;
        // else if ($T_Intervaloc == 0 && $T_Intervaloc != '') $i_Intervaloc = 0;
        else $i_Intervaloc = $T_Intervaloc;

        // abduls
        if ($T_Destinatario_Usuarios!= 0){ // para Usuarios
            $i_Destinatario_Usuarios = $T_Destinatario_Usuarios;
            $i_Destinatario_Grupos = 0;
            // $i_Destinatario_Personas = 0;
            $i_Destinatario_Tipo = 'Usuario';
        }else if ($T_Destinatario_Grupos != 0){// para grupos
            $i_Destinatario_Usuarios = 0;
            $i_Destinatario_Grupos = $T_Destinatario_Grupos;
            // $i_Destinatario_Personas = 0;
            $i_Destinatario_Tipo = 'Grupo';
        }else if ($T_Destinatario_Personas != 0){// para personas
            $i_Destinatario_Usuarios = 0;
            $i_Destinatario_Grupos = 0;
            // $i_Destinatario_Personas = $T_Destinatario_Personas;
            $i_Destinatario_Tipo = $T_Destinatario_Personas;
        }else{// para none
            $i_Destinatario_Usuarios = 0;
            $i_Destinatario_Grupos = 0;
            // $i_Destinatario_Personas = 0;
            $i_Destinatario_Tipo = 'Usuario';
        }
        //$T_Tipon = HtmlHelper::array2htmloptions($a_Notificaciones_Tipos,$i_Tipon,true,false);
        if ($alertaOreporte == 'reporte') {
            $T_Tipon = HtmlHelper::array2htmloptions(array(1 => _("Email")), $i_Tipon, true, false, '', 'Seleccione un Tipo');
        } else if ($alertaOreporte == 'alerta') {
            $T_Tipon = HtmlHelper::array2htmloptions(array(1 => _("Email")/*,2 => _("Mensaje en Inicio")*/), $i_Tipon, false, false, '', 'Seleccione un Tipo');
        }

        $T_Destinatario_Tipo = HtmlHelper::array2htmloptions(Persona_L::obtenerTodos(0,0,0,'per_Eliminada = 0 and per_Excluir= 0 '), $i_Destinatario_Tipo, true, true, 'Destinatario', 'Seleccione un Tipo');
        // pre($T_Destinatario_Tipo);
        $T_Destinatario_Usuarios = HtmlHelper::array2htmloptions(Usuario_L::obtenerListaEmails(), $i_Destinatario_Usuarios, true, false, 'Usuarios', _('Seleccione un Usuario'));
        $T_Destinatario_Grupos = HtmlHelper::array2htmloptions(Grupo_L::obtenerTodos(), $i_Destinatario_Grupos, true, true, 'GrupoPersonas', _('Seleccione Grupo de Personas'));
        // $T_Destinatario_Personas = HtmlHelper::array2htmloptions(Persona_L::obtenerTodos(0,0,0,'per_Eliminada = 0  and per_Excluir=0 '), $i_Destinatario_Personas, true, true, 'Personas', _('Seleccione un Persona'));

        $T_Persona = HtmlHelper::array2htmloptions(Persona_L::obtenerTodos(0,0,0,'per_Eliminada = 0 and per_Excluir= 0 '), $i_Persona, true, true, 'PersonayRol', _('Todas las Personas'));

        $T_Grupos = HtmlHelper::array2htmloptions(Grupo_L::obtenerTodos(), $i_Grupos, true, true, '', _('Seleccione un Grupo'));
        $T_Equipo = HtmlHelper::array2htmloptions(Equipo_L::obtenerTodos(), $i_Equipo, true, true, 'EquipoNotificacion');

        $T_Disparador = HtmlHelper::array2htmloptions($Notificaciones_Disparadores_1, $i_Disparador, 1, false, '', _('Seleccione un Disparador'));

        $T_Tipod = HtmlHelper::array2htmloptions(array(_("Inmediato"), _("Diferido")), $i_Tipod, true, false, '', _('Seleccione un Tipo'));
        $T_Repetir = HtmlHelper::array2htmloptions($a_Notificaciones_Contenidos_Repetir, $i_Repetir, true, false,'','Seleccione una Repetición');
        $T_Accion = HtmlHelper::array2htmloptions(array(_("Acceso Correcto"), _("Sin Permisos"), _("Fuera de Horario")), $i_Accion, false, false);

        $T_Tipoc = HtmlHelper::array2htmloptions($a_Notificaciones_Contenidos_Tipos, $i_Tipoc, true, false, '', _('Seleccione un Tipo'));
        $T_Reportec = HtmlHelper::array2htmloptions($Notificaciones_Contenidos_1, $i_Reportec, true, false, '', _('Seleccione un Reporte'));
        $T_Personac = HtmlHelper::array2htmloptions(Persona_L::obtenerTodos(0,0,0,'per_Eliminada = 0 and per_Excluir= 0 '), $i_Personac, true, true, 'PersonayRol', _('Todas las Personas'));
        $T_Grupoc = HtmlHelper::array2htmloptions(Grupo_L::obtenerTodos(), $i_Grupoc, true, true, '', _('Seleccione un Grupo'));
        $T_Equipoc = HtmlHelper::array2htmloptions(Equipo_L::obtenerTodos(), $i_Equipoc, true, true, 'EquipoNotificacion');
        $T_Intervaloc = HtmlHelper::array2htmloptions($a_Notificaciones_Contenidos_Intervalos, $i_Intervaloc, true, false,'','Período del Reporte');
        $T_DescargarTipo = HtmlHelper::array2htmloptions($a_Notificaciones_Reporte_Descargar_Tipo, $i_DescargarTipo, true, false,'','Seleccione un tipo de descarga');
        break;
    case 'add':
    case 'edit':
        SeguridadHelper::Pasar(50);
        $o_Notificacion = Notificaciones_L::obtenerPorId($T_Id);
        if (is_null($o_Notificacion))//esto quiere decir que es un add
            $o_Notificacion = new Notificaciones_O();

        $o_Notificacion->setDetalle($T_Detalle);

        if($T_Tipon != 2){ // report / alert - email
            if ($T_Destinatario_Tipo == 'Usuario'){ // para Usuarios
                $o_Notificacion->setUsuId($T_Destinatario_Usuarios);
                $o_Notificacion->setGrupo(0);
                $o_Notificacion->setPerId(0);
            }else if ($T_Destinatario_Tipo == 'Grupo'){// para grupos
                $o_Notificacion->setUsuId(0);
                $o_Notificacion->setGrupo($T_Destinatario_Grupos);
                $o_Notificacion->setPerId(0);
            }else { // para personas
                $o_Notificacion->setUsuId(0);
                $o_Notificacion->setGrupo(0);
                $o_Notificacion->setPerId($T_Destinatario_Tipo);
            }
            if($T_Email_Me == ''){
                $o_Notificacion->setEmailMe('');
            }else{
                $o_Notificacion->setEmailMe($T_Email_Me);
            }
        }else{// para mensaje
            $o_Notificacion->setUsuId(0);
            $o_Notificacion->setGrupo(0);
            $o_Notificacion->setPerId(0);
            $o_Notificacion->setEmailMe('');
        }

        $o_Notificacion->setActiva(1);
        if ($T_Tipod == 0) {//inmediato alerta

            $o_Notificacion->setTipoD(0);
            // $o_Notificacion->setHoraD(0, '', true);
            $o_Notificacion->setHoraD(date('Y-m-d H:i:s'), "Y-m-d H:i:s");

            if ($T_Disparador == '') {
                $o_Notificacion->_errores['disparador'] = _("Debe seleccionar un Disparador");
            } else if ($T_Disparador == NOT_PERDIDA_DE_CONEXION) {//perdio conexion
                $o_Notificacion->setDisparador(NOT_PERDIDA_DE_CONEXION);
                if ($T_Equipo == 'TodosLosEquipos')
                    $o_Notificacion->setEquipoD_NoCHECK(0);
                else
                    $o_Notificacion->setEquipoD($T_Equipo);
                $o_Notificacion->setZonaD_NoCHECK(0);
                $o_Notificacion->setPersonaD_NoCHECK(0);
                $o_Notificacion->setDispositivoD_NoCHECK(0);
                $o_Notificacion->setLogD(0);
                $o_Notificacion->setRolD_NoCHECK(0);
            } else if ($T_Disparador == NOT_REINICIO_EQUIPO) {//reinicio equipo
                $o_Notificacion->setDisparador(NOT_REINICIO_EQUIPO);
                if ($T_Equipo == 'TodosLosEquipos')
                    $o_Notificacion->setEquipoD_NoCHECK(0);
                else
                    $o_Notificacion->setEquipoD($T_Equipo);
                $o_Notificacion->setZonaD_NoCHECK(0);
                $o_Notificacion->setPersonaD_NoCHECK(0);
                $o_Notificacion->setDispositivoD_NoCHECK(0);
                $o_Notificacion->setLogD(0);
                $o_Notificacion->setRolD_NoCHECK(0);

            } else if ($T_Disparador == NOT_LLEGADA_TARDE) {//llegada tarde
                $o_Notificacion->setDisparador(NOT_LLEGADA_TARDE);
                if ($T_Persona == 'SelectRol') {
                    $o_Notificacion->setRolD($T_Grupos);
                    $o_Notificacion->setPersonaD_NoCHECK(0);
                } else {
                    if ($T_Persona != 'TodasLasPersonas'){
                        $o_Notificacion->setPersonaD($T_Persona);
                    }else{
                        $o_Notificacion->setPersonaD_NoCHECK(0);//asigno este ID de persona que se que no va a existir
                    }
                    $o_Notificacion->setRolD_NoCHECK(0);
                }
                $o_Notificacion->setDispositivoD_NoCHECK(0);
                $o_Notificacion->setEquipoD_NoCHECK(0);
            } else if ($T_Disparador == NOT_AUSENCIA) {//ausencia
                // $array_horarios= array(
                // 0 => $T_Hora_Inicio_Dom,
                // 1 => $T_Hora_Inicio_Lun,
                // 2 => $T_Hora_Inicio_Mar,
                // 3 => $T_Hora_Inicio_Mie,
                // 4 => $T_Hora_Inicio_Jue,
                // 5 => $T_Hora_Inicio_Vie,
                // 6 => $T_Hora_Inicio_Sab
                // );
                // $o_Notificacion->setHorariosD(json_encode($array_horarios));
                $o_Notificacion->setDisparador(NOT_AUSENCIA);
                if ($T_Persona == 'SelectRol') {
                    $o_Notificacion->setRolD($T_Grupos);
                    $o_Notificacion->setPersonaD_NoCHECK(0);
                } else {
                    if ($T_Persona != 'TodasLasPersonas')
                        $o_Notificacion->setPersonaD($T_Persona);
                    else
                        $o_Notificacion->setPersonaD_NoCHECK(0);
                    $o_Notificacion->setRolD_NoCHECK(0);
                }
                $o_Notificacion->setDispositivoD_NoCHECK(0);
                $o_Notificacion->setEquipoD_NoCHECK(0);
            } else if ($T_Disparador == NOT_LLEGADA_TEMPRANA) {//llegada temprana
                $o_Notificacion->setDisparador(NOT_LLEGADA_TEMPRANA);
                if ($T_Persona == 'SelectRol') {
                    $o_Notificacion->setRolD($T_Grupos);
                    $o_Notificacion->setPersonaD_NoCHECK(0);
                } else {
                    if ($T_Persona != 'TodasLasPersonas')
                        $o_Notificacion->setPersonaD($T_Persona);
                    else
                        $o_Notificacion->setPersonaD_NoCHECK(0);
                    $o_Notificacion->setRolD_NoCHECK(0);
                }
                $o_Notificacion->setDispositivoD_NoCHECK(0);
                $o_Notificacion->setEquipoD_NoCHECK(0);
            }
            else if ($T_Disparador == NOT_SALIDA_TARDE) {//salida tarde
                $o_Notificacion->setDisparador(NOT_SALIDA_TARDE);
                if ($T_Persona == 'SelectRol') {
                    $o_Notificacion->setRolD($T_Grupos);
                    $o_Notificacion->setPersonaD_NoCHECK(0);
                } else {
                    if ($T_Persona != 'TodasLasPersonas')
                        $o_Notificacion->setPersonaD($T_Persona);
                    else
                        $o_Notificacion->setPersonaD_NoCHECK(0);
                    $o_Notificacion->setRolD_NoCHECK(0);
                }
                $o_Notificacion->setDispositivoD_NoCHECK(0);
                $o_Notificacion->setEquipoD_NoCHECK(0);
            }
            else if ($T_Disparador == NOT_SALIDA_TEMPRANA) {//salida temprana
                $o_Notificacion->setDisparador(NOT_SALIDA_TEMPRANA);
                if ($T_Persona == 'SelectRol') {
                    $o_Notificacion->setRolD($T_Grupos);
                    $o_Notificacion->setPersonaD_NoCHECK(0);
                } else {
                    if ($T_Persona != 'TodasLasPersonas')
                        $o_Notificacion->setPersonaD($T_Persona);
                    else
                        $o_Notificacion->setPersonaD_NoCHECK(0);
                    $o_Notificacion->setRolD_NoCHECK(0);
                }
                $o_Notificacion->setDispositivoD_NoCHECK(0);
                $o_Notificacion->setEquipoD_NoCHECK(0);
            }
            else if ($T_Disparador == NOT_REPORTE_DE_PAYRROLL) {//payrroll
                $o_Notificacion->setDisparador(NOT_REPORTE_DE_PAYRROLL);
                if ($T_Persona == 'SelectRol') {
                    $o_Notificacion->setRolD($T_Grupos);
                    $o_Notificacion->setPersonaD_NoCHECK(0);
                } else {
                    if ($T_Persona != 'TodasLasPersonas')
                        $o_Notificacion->setPersonaD($T_Persona);
                    else
                        $o_Notificacion->setPersonaD_NoCHECK(0);
                    $o_Notificacion->setRolD_NoCHECK(0);
                }
                $o_Notificacion->setDispositivoD_NoCHECK(0);
                $o_Notificacion->setEquipoD_NoCHECK(0);
            }
        }
        else if ($T_Tipod == 1)
        {//diferido reporte
            if ($T_Repetir == 0) $o_Notificacion->setTipoD(0);//unica vez
            else if ($T_Repetir == 1) $o_Notificacion->setTipoD(1);//diariamente
            else if ($T_Repetir == 2) $o_Notificacion->setTipoD(2);//semanalmente
            else if ($T_Repetir == 3) $o_Notificacion->setTipoD(3);//quincenalmente
            else if ($T_Repetir == 4) $o_Notificacion->setTipoD(4);//mensualmente
            else if ($T_Repetir == 5) $o_Notificacion->setTipoD(5);//anualmente

            $o_Notificacion->setHoraD($T_Hora, "Y-m-d H:i:s");
        } else {
            $o_Notificacion->_errores['tipod'] = _("Debe seleccionar un Tipo");
        }

        if ($alertaOreporte == 'alerta'){//aviso
            $o_Notificacion->setTipoC(1);
            $o_Notificacion->setDetalleC( $Notificaciones_Disparadores[$T_Disparador]);
            if($T_Tipon == 1)
                $o_Notificacion->setTextoC($T_Mensajec);
            else
                $o_Notificacion->setTextoC('');
            $o_Notificacion->setTipo($T_Tipon); // 1 : email , 2 : mensaje
        } else if ($alertaOreporte == 'reporte'){//reporte

            $o_Notificacion->setTipo(1);//email
            $o_Notificacion->setDetalleC( $T_Detallec);
            $o_Notificacion->setReporteDescargarTipoC($T_DescargarTipo);
            if ($T_Reportec == '') {
                $o_Notificacion->_errores['reportec'] = _("Debe seleccionar un tipo de Reporte");
            } else if ($T_Reportec == NOT_REPORTE_DE_PERSONA) {//reporte por persona
                $o_Notificacion->setTipoC(NOT_REPORTE_DE_PERSONA);

                if ($T_Personac == 'SelectRol') {
                    $o_Notificacion->setRolC($T_Grupoc);
                    $o_Notificacion->setPersonaC_NoCHECK(0);
                } else {
                    if ($T_Personac != 'TodasLasPersonas')
                        $o_Notificacion->setPersonaC($T_Personac);
                    else
                        $o_Notificacion->setPersonaC_NoCHECK(0);
                    $o_Notificacion->setRolC_NoCHECK(0);
                }
                $o_Notificacion->setIntervaloC($T_Intervaloc);

            } else if ($T_Reportec == NOT_REPORTE_DE_EQUIPO) {//reporte por equipo
                $o_Notificacion->setTipoC(NOT_REPORTE_DE_EQUIPO);
                $o_Notificacion->setEquipoC($T_Equipoc);
                $o_Notificacion->setIntervaloC($T_Intervaloc);

            } else if ($T_Reportec == NOT_REPORTE_DE_LLEGADA_TARDE) {//reporte de llegadas tarde
                $o_Notificacion->setTipoC(NOT_REPORTE_DE_LLEGADA_TARDE);
                if ($T_Personac == 'SelectRol') {
                    $o_Notificacion->setRolC($T_Grupoc);
                    $o_Notificacion->setPersonaC_NoCHECK(0);
                } else {
                    if ($T_Personac != 'TodasLasPersonas')
                        $o_Notificacion->setPersonaC($T_Personac);
                    else
                        $o_Notificacion->setPersonaC_NoCHECK(0);
                    $o_Notificacion->setRolC_NoCHECK(0);
                }
                $o_Notificacion->setIntervaloC($T_Intervaloc);

            } else if ($T_Reportec == NOT_REPORTE_DE_ENTRADAS_SALIDAS) {//reporte de entradas salidas
                $o_Notificacion->setTipoC(NOT_REPORTE_DE_ENTRADAS_SALIDAS);
                if ($T_Personac == 'SelectRol') {
                    $o_Notificacion->setRolC($T_Grupoc);
                    $o_Notificacion->setPersonaC_NoCHECK(0);
                } else {
                    if ($T_Personac != 'TodasLasPersonas')
                        $o_Notificacion->setPersonaC($T_Personac);
                    else
                        $o_Notificacion->setPersonaC_NoCHECK(0);
                    $o_Notificacion->setRolC_NoCHECK(0);
                }
                $o_Notificacion->setIntervaloC($T_Intervaloc);

            } else if ($T_Reportec == NOT_REPORTE_DE_DIAS_HORAS_TRABAJADAS) {//reporte de dias horas trabajadas
                $o_Notificacion->setTipoC(NOT_REPORTE_DE_DIAS_HORAS_TRABAJADAS);
                if ($T_Personac == 'SelectRol') {
                    $o_Notificacion->setRolC($T_Grupoc);
                    $o_Notificacion->setPersonaC_NoCHECK(0);
                } else {
                    if ($T_Personac != 'TodasLasPersonas')
                        $o_Notificacion->setPersonaC($T_Personac);
                    else
                        $o_Notificacion->setPersonaC_NoCHECK(0);
                    $o_Notificacion->setRolC_NoCHECK(0);
                }
                $o_Notificacion->setIntervaloC($T_Intervaloc);

            }

            else if ($T_Reportec == NOT_REPORTE_DE_AUSENCIAS) {//reporte de ausencias
                $o_Notificacion->setTipoC(NOT_REPORTE_DE_AUSENCIAS);
                if ($T_Personac == 'SelectRol') {
                    $o_Notificacion->setRolC($T_Grupoc);
                    $o_Notificacion->setPersonaC_NoCHECK(0);
                } else {
                    if ($T_Personac != 'TodasLasPersonas')
                        $o_Notificacion->setPersonaC($T_Personac);
                    else
                        $o_Notificacion->setPersonaC_NoCHECK(0);
                    $o_Notificacion->setRolC_NoCHECK(0);
                }
                $o_Notificacion->setIntervaloC($T_Intervaloc);

            }
            else if ($T_Reportec == NOT_REPORTE_DE_PAYRROLL) {//reporte de ausencias
                $o_Notificacion->setTipoC(NOT_REPORTE_DE_PAYRROLL);
                if ($T_Personac == 'SelectRol') {
                    $o_Notificacion->setRolC($T_Grupoc);
                    $o_Notificacion->setPersonaC_NoCHECK(0);
                } else {
                    if ($T_Personac != 'TodasLasPersonas')
                        $o_Notificacion->setPersonaC($T_Personac);
                    else
                        $o_Notificacion->setPersonaC_NoCHECK(0);
                    $o_Notificacion->setRolC_NoCHECK(0);
                }
                $o_Notificacion->setIntervaloC($T_Intervaloc);

            }

        } else {
            $o_Contenido->_errores['tipoc'] = _("Debe seleccionar un Tipo");
        }

        if ($o_Notificacion->getId() != null)
            $T_Modificar = true;
        else
            $T_Modificar = false;
        // pre($o_Notificacion);
        if (!$o_Notificacion->save(Registry::getInstance()->general['debug']) ) {
            $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Notificacion->getErrores();
        } else {
            if ($T_Modificar) {
                if ($alertaOreporte == 'reporte')
                    $T_Mensaje = _('El reporte fue modificado correctamente.');
                else if ($alertaOreporte == 'alerta')
                    $T_Mensaje = _('La alerta fue modificada correctamente.');
            }
            else {
                if ($alertaOreporte == 'reporte')
                    $T_Mensaje = _('El reporte fue guardado correctamente.');
                else if ($alertaOreporte == 'alerta')
                    $T_Mensaje = _('La alerta fue guardada correctamente.');
            }
        }


        $T_Modificar = true;


        goto defaultlabel;
        break;
    case 'send':

        /*
        SeguridadHelper::Pasar(50);

        $o_Notificacion = Notificaciones_L::obtenerPorId($T_Id);
        // if($o_Notificacion->getTipo()==2){
        // $o_Mensaje = Mensaje_L::obtenerPorId($o_Notificacion->getMenId());
        // if($o_Mensaje){
        // $flag = true;
        // $o_Mensaje->setDisparadorHora(date('Y-m-d H:i:s'));
        // $o_Mensaje->setVisto(1);
        // $o_Mensaje->setStatus(0);
        // $o_Mensaje->save(Registry::getInstance()->general['debug'] , $flag);
        // }
        // }else{
        //$o_Notificacion->enviar(0,0,0,0,0,0,0,$_SESSION['Ausencias_Email'],$_SESSION['Llegadas_Tarde_Email'],$_SESSION['Salidas_Temprano_Email']);
        // }

        $o_Notificacion->enviar(0,0,0,0,0,0,0,$_SESSION['Ausencias_Email'],$_SESSION['Llegadas_Tarde_Email'],$_SESSION['Salidas_Temprano_Email']);


        $o_Notificacion->setDisparadorHora(date('Y-m-d H:i:s'));

        $o_Notificacion->save(Registry::getInstance()->general['debug']);

        if ($alertaOreporte == 'reporte'){
            $T_Mensaje = _('El reporte se envió con éxito');
        }

        else if ($alertaOreporte == 'alerta')
            if(isset($_SESSION['Mensaje']) && !empty($_SESSION['Mensaje']))
                $T_Mensaje = $_SESSION['Mensaje'];
            else
                $T_Mensaje = _('La alerta se envió con éxito');

        $_SESSION['Mensaje'] = '';

        goto defaultlabel;*/
        break;
    case 'delete':
        $o_Notificacion = Notificaciones_L::obtenerPorId($T_Id);

        if (!$o_Notificacion->delete(Registry::getInstance()->general['debug'])) {
            $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Notificacion->getErrores();
        } else {
            $ref_url = "";
            $T_Eliminado = true;
            $T_Link = '';
            if ($alertaOreporte == 'reporte')
                $T_Mensaje = _('El reporte ha sido eliminado correctamente.');
            else if ($alertaOreporte == 'alerta')
                $T_Mensaje = _('La alerta ha sido eliminada correctamente.');

            goto defaultlabel;
        }
        $ref_url = "";
        $T_Link = '';
        break;
    default:
        defaultlabel:
        SeguridadHelper::Pasar(50);

        if ($alertaOreporte == 'alerta')
            $o_Listado = Notificaciones_L::obtenerTodasAlertas();
        else
            $o_Listado = Notificaciones_L::obtenerTodosReportes();
        $T_Link = '';
}
