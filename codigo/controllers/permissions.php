<?php


SeguridadHelper::Pasar(20);

if (isset($_REQUEST['activos'])) {
    $_SESSION['filtro']['activos'] = $_REQUEST['activos'];
}
else {
    $_SESSION['filtro']['activos'] = (integer)0;
    $_REQUEST['activos']           = (integer)0;
}
$_SESSION['filtro']['rolF'] = (integer)(!isset($_POST['rolF'])) ? (isset($_SESSION['filtro']['rolF'])) ? $_SESSION['filtro']['rolF'] : 0 : $_POST['rolF'];


$T_Titulo = _('Permissions');
$Item_Name = "permissions";
$T_Script = 'permissions';
$T_Mensaje = '';
$T_Titulo_Singular = _('Permiso');
$T_Titulo_Pre = _('la');

$T_Tipo = (isset($_REQUEST['tipo'])) ? $_REQUEST['tipo'] : '';
$T_Id = isset($_REQUEST['id']) ? (integer)$_REQUEST['id'] : 0;
$T_FechaD = (isset($_REQUEST['LfechaD'])) ? $_REQUEST['LfechaD']." 00:00:00" : '';
$T_FechaH = (isset($_REQUEST['LfechaH'])) ? $_REQUEST['LfechaH'] : '';
$T_Persona = (isset($_REQUEST['persona'])) ? (integer)$_REQUEST['persona'] : 0;
$T_Grupo = (isset($_REQUEST['grupo'])) ? (integer)$_REQUEST['grupo'] : 0;
$T_Repetitiva = (isset($_REQUEST['repetitiva'])) ? (integer)$_REQUEST['repetitiva'] : 0;
$T_Motivo = (isset($_REQUEST['motivo'])) ? $_REQUEST['motivo'] : '';
$T_Enabled = (isset($_REQUEST['enabled'])) ? $_REQUEST['enabled'] : '';
$T_Intervalo = isset($_REQUEST['intervaloFecha']) ? (string)$_REQUEST['intervaloFecha'] : '';
$T_selTipo = isset($_REQUEST['selTipo']) ? (string)$_REQUEST['selTipo'] : '';
$T_IntervaloLlegadaTarde = isset($_REQUEST['intervaloLlegadaTarde']) ? (string)$_REQUEST['intervaloLlegadaTarde'] : '';
$T_IntervaloSalidaTemprano = isset($_REQUEST['intervaloSalidaTemprano']) ? (string)$_REQUEST['intervaloSalidaTemprano'] : '';
$T_DuracionLlegadaTarde = isset($_REQUEST['duracionLlegadaTarde']) ? (integer)$_REQUEST['duracionLlegadaTarde'] : 0;
$T_DuracionSalidaTemprano = isset($_REQUEST['duracionSalidaTemprano']) ? (integer)$_REQUEST['duracionSalidaTemprano'] : 0;
$T_IntervaloDuracionLlegadaTarde = isset($_REQUEST['intervaloDuracionLlegadaTarde']) ? (string)$_REQUEST['intervaloDuracionLlegadaTarde'] : '';
$T_IntervaloDuracionSalidaTemprano = isset($_REQUEST['intervaloDuracionSalidaTemprano']) ? (string)$_REQUEST['intervaloDuracionSalidaTemprano'] : '';
$T_FechaLlegadaTarde = isset($_REQUEST['fechaLlegadaTarde']) ? (string)$_REQUEST['fechaLlegadaTarde'] : '';
$T_FechaSalidaTemprano = isset($_REQUEST['fechaSalidaTemprano']) ? (string)$_REQUEST['fechaSalidaTemprano'] : '';
$T_DiaCompleto = isset($_REQUEST['diaCompleto']) ? (string)$_REQUEST['diaCompleto'] : '';




switch ($T_Tipo) {

    case 'add':
    case 'edit':

        SeguridadHelper::Pasar(50);

        // CASE: TODAS LAS PERSONAS (ACTIVAS)
        if ($_REQUEST['persona'] == 'TodasLasPersonas') {
            $_todas_las_Personas = Persona_L::obtenerTodosIdenArray();
            if(!is_null($_todas_las_Personas)){
                $_List_Personas = $_todas_las_Personas;
            }
        }

        // CASE: GRUPO DE PERSONAS
        else if ($_REQUEST['persona'] == 'SelectRol') {
            $_personas_en_Grupo = Grupos_Personas_L::obtenerARRAYPorGrupo($T_Grupo);

            if(!is_null($_personas_en_Grupo)){
                $_List_Personas = $_personas_en_Grupo;
            }
        }

        // CASE PERSONA
        else if ($T_Persona >= 0 ) {
            $_List_Personas[] = $T_Persona;
        }

        foreach ($_List_Personas as $list_key => $p_Item) {
            // GET LICENCE OBJECT

            $o_Permiso = Permisos_L::obtenerPorId($T_Id);

            // CREATE LICENCE OBJECT
            if (is_null($o_Permiso)) {
                $o_Permiso = new Permisos_O();
            }

            // SET MOTIVO
            $o_Permiso->setMotivo($T_Motivo);
            // SET PERSONA
            $o_Permiso->setPerId($p_Item);
            // SET GRUPO
            $o_Permiso->setGrupoId(0);
            // SET TYPE
            $o_Permiso->setTipo($T_selTipo);

            // SET: FECHA INICIO, FECHA FIN, REPETITIVA
            switch ($T_selTipo) {
                case PERMISO_LLEGADA_TARDE:
                    switch ($T_IntervaloLlegadaTarde) {
                        case 'F_15':
                            $o_Permiso->setDuracion('15,m');
                            break;
                        case 'F_30':
                            $o_Permiso->setDuracion('30,m');
                            break;
                        case 'F_1':
                            $o_Permiso->setDuracion('1,h');
                            break;
                        case 'F_2':
                            $o_Permiso->setDuracion('2,h');
                            break;
                        case 'F_Personalizado':
                            $horaminuto = '';
                            if ($T_IntervaloDuracionLlegadaTarde == 'F_Minutos') $horaminuto = 'm';
                            else if ($T_IntervaloDuracionLlegadaTarde == 'F_Horas') $horaminuto = 'h';
                            $o_Permiso->setDuracion($T_DuracionLlegadaTarde . ',' . $horaminuto);
                            break;
                    }
                    $o_Permiso->setFechaInicio($T_FechaLlegadaTarde, 'Y-m-d H:i:s');
                    $o_Permiso->setFechaFin($T_FechaLlegadaTarde, 'Y-m-d H:i:s');
                    break;
                case PERMISO_SALIDA_TEMPRANO:
                    switch ($T_IntervaloSalidaTemprano) {
                        case 'F_15':
                            $o_Permiso->setDuracion('15,m');
                            break;
                        case 'F_30':
                            $o_Permiso->setDuracion('30,m');
                            break;
                        case 'F_1':
                            $o_Permiso->setDuracion('1,h');
                            break;
                        case 'F_2':
                            $o_Permiso->setDuracion('2,h');
                            break;
                        case 'F_Personalizado':
                            $horaminuto = '';
                            if ($T_IntervaloDuracionSalidaTemprano == 'F_Minutos') $horaminuto = 'm';
                            else if ($T_IntervaloDuracionSalidaTemprano == 'F_Horas') $horaminuto = 'h';
                            $o_Permiso->setDuracion($T_DuracionSalidaTemprano . ',' . $horaminuto);
                            break;
                    }
                    $o_Permiso->setFechaInicio($T_FechaSalidaTemprano, 'Y-m-d H:i:s');
                    $o_Permiso->setFechaFin($T_FechaSalidaTemprano, 'Y-m-d H:i:s');
                    break;
                case PERMISO_DIA_COMPLETO:
                    $o_Permiso->setFechaInicio($T_DiaCompleto, 'Y-m-d H:i:s');
                    $itr_date = date('Y-m-d', strtotime($T_DiaCompleto)) . " " . "23:59:59";
                    $o_Permiso->setFechaFin(date('Y-m-d H:i:s', strtotime($itr_date)), 'Y-m-d H:i:s');
                    $o_Permiso->setRepetitiva($T_Repetitiva);
                    break;
                case PERMISO_PERSONALIZADA:
                    //MANEJO DE LOS INTERVALOS DE FECHAS
                    if (isset($T_Intervalo) && $T_Intervalo != '') {
                        switch ($T_Intervalo) {
                            case 'F_Hoy'://diario
                                $T_FechaD = date('Y-m-d H:i:s', strtotime('today 00:00'));
                                $T_FechaH = date('Y-m-d H:i:s', strtotime('tomorrow 23:59:59'));
                                break;
                            case 'F_Manana'://mañana
                                $T_FechaD = date('Y-m-d H:i:s', strtotime('tomorrow 00:00'));
                                $T_FechaH = date('Y-m-d H:i:s', strtotime('+2 day 23:59:59'));
                                break;
                            case 'F_Personalizado':
                                //$_SESSION['filtro']['fechaD'] = (!isset($_POST['fechaD'])) ? (isset($_SESSION['filtro']['fechaD'])) ? $_SESSION['filtro']['fechaD'] : date('Y-m-d H:i:s', strtotime('-1 day'))  : $_POST['fechaD'];
                                //$_SESSION['filtro']['fechaH'] = (!isset($_POST['fechaH'])) ? (isset($_SESSION['filtro']['fechaH'])) ? $_SESSION['filtro']['fechaH'] : date('Y-m-d H:i:s')  : $_POST['fechaH'];
                                break;
                        }
                    }
                    else {//selecciono el dropdown si la fecha ya viene
                        if ($T_FechaD == date('Y-m-d H:i:s', strtotime('today 00:00')) && $T_FechaH == date('Y-m-d H:i:s', strtotime('tomorrow 23:59:59')))
                            $T_Intervalo = 'F_Hoy';
                        elseif ($T_FechaD == date('Y-m-d H:i:s', strtotime('today 00:00')) && $T_FechaH == date('Y-m-d H:i:s', strtotime('tomorrow 23:59:59')))
                            $T_Intervalo = 'F_Manana';
                        else
                            $T_Intervalo = 'F_Personalizado';
                    }


                    $o_Permiso->setFechaInicio($T_FechaD, 'Y-m-d H:i:s');
                    $o_Permiso->setFechaFin($T_FechaH, 'Y-m-d H:i:s');
                    break;
            }

            // SET ENABLE
            if ($T_Enabled == "on") {
                $o_Permiso->setEnabled(1);
            }
            else {
                $o_Permiso->setEnabled(0);
            }

            // FLAG NEW LICENCE
            if ($o_Permiso->getId() == 0) {
                $_Flag_nuevoPermiso = true;
            }
            else {
                $_Flag_nuevoPermiso = false;
            }

            // SAVE SUCCESS: SAVE LOG, SHOW MESSAGE SUCCESS
            if ($o_Permiso->save(Registry::getInstance()->general['debug'])) {


                // LOG DETALLE: PERSONA
                $Log_Detalle = '';

                $Log_Detalle = Persona_L::obtenerPorId($o_Permiso->getPerId(), true)->getNombreCompleto();
                $Log_Detalle = mb_convert_case($Log_Detalle, MB_CASE_TITLE, "UTF-8");

                // CREATED LICENCE: SAVE LOG, SHOW MESSAGE
                if ($_Flag_nuevoPermiso) {
                    SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_PERMISO_CREAR, $a_Logs_Tipos[LOG_PERMISO_CREAR], '<b>Id:</b> ' . $o_Permiso->getId() . ' <b>Motivo:</b> ' . $o_Permiso->getMotivo() . ' <b>Persona:</b> ' . $Log_Detalle, $o_Permiso->getId());
                    $T_Mensaje = $T_Mensaje.'<br>'._('El permiso de <b>'.$Log_Detalle.'</b> fue creado correctamente.');
                }
                // UPDATED LICENCE: SAVE LOG, SHOW MESSAGE
                else {
                    SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_PERMISO_EDITAR, $a_Logs_Tipos[LOG_PERMISO_EDITAR], '<b>Id:</b> ' . $o_Permiso->getId() . ' <b>Motivo:</b> ' . $o_Permiso->getMotivo() . ' <b>Persona:</b> ' . $Log_Detalle, $o_Permiso->getId());
                    $T_Mensaje = $T_Mensaje.'<br>'._('El permiso de <b>'.$Log_Detalle.'</b> fue modificado correctamente.');
                }
            }

            // SAVE SUCCESS: SHOW MESSAGE ERROR
            else {
                $T_Error = $o_Permiso->getErrores();
            }

            unset($o_Permiso);
        }


        $T_Modificar = true;
        goto defaultLabel;
        break;


    case 'delete' :

        SeguridadHelper::Pasar(50);
        $o_Permiso = Permisos_L::obtenerPorId($T_Id);

        if (is_null($o_Permiso)) {
            $T_Error = _('Lo sentimos, el Permiso que desea eliminar no existe.');
        } else {
            $personagrupo = '';
            if ($o_Permiso->getPersonaOGrupo() == 'persona') {
                $personagrupo = Persona_L::obtenerPorId($o_Permiso->getPerId())->getNombreCompleto();
            } else if ($o_Permiso->getPersonaOGrupo() == 'grupo') {
                $personagrupo = 'Grupo: ' . Grupo_L::obtenerPorId($o_Permiso->getGrupoId())->getDetalle();
            }
            if (!$o_Permiso->delete(Registry::getInstance()->general['debug'])) {
                $T_Error = $o_Permiso->getErrores();
            } else {
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_PERMISO_ELIMINAR, $a_Logs_Tipos[LOG_PERMISO_ELIMINAR], '<b>Id:</b> ' . $o_Permiso->getId() . ' <b>Motivo:</b> ' . $o_Permiso->getMotivo() . ' <b>Personas:</b> ' . $personagrupo, $o_Permiso->getId());
                $T_Mensaje = _('El Permiso fue eliminado correctamente.');
            }

        }

        goto defaultLabel;
        break;
    //Rahul
    case 'enabled':

        $id = $_GET['id'];
        $status = $_POST['status'];

        SeguridadHelper::Pasar(50);
        $o_Permiso = Permisos_L::obtenerPorId($id);
        $o_Permiso->setTipo($id);
        $o_Permiso->saveEnabled($status);
        $T_Modificar = true;
        goto defaultLabel;

        break;
    //Rahul
    case 'view':

        SeguridadHelper::Pasar(20);
        $o_Permiso = Permisos_L::obtenerPorId($T_Id);


        if (is_null($o_Permiso)) {
            $o_Permiso = new Permisos_O();
            $o_Permiso->setEnabled(1);
        } else {


        }
        break;
    default:

        defaultLabel:
        SeguridadHelper::Pasar(20);
        Feriado_L::fetchInterval();
        /*
        if($T_Pasados!='off'){
            $_SESSION['filtro']['pasados']=$T_Pasados;

            $o_Listado = Permisos_L::obtenerTodosSinPasadasNew();
        }else if(isset($_SESSION['filtro']['fechaD']) && isset($_SESSION['filtro']['fechaH'])){

            $o_Listado = Permisos_L::obtenerTodosNew();
        }else if(isset($_REQUEST['fechaD']) && isset($_REQUEST['fechaH'])){
            $o_Listado = Permisos_L::obtenerTodosNew();
        }else{
            $_SESSION['filtro']['fechaH']=date('Y-m-01')." 00:00:00";
            $_SESSION['filtro']['fechaD']=date('Y-m-t')." 23:59:59";
            $_SESSION['filtro']['pasados']="off";
            $o_Listado = Permisos_L::obtenerTodosNew();

        }
        */
        $o_Listado = Permisos_L::obtenerTodosNew();

        $T_Link = '';
}

