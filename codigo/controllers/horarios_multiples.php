<?php

/*
 * 
 * 
 * 
  [
  {
  "sec_id", 
  "orden": "1",
  "horario_id": 5,
  "duracion": 3
  }
  ]
 */

require_once dirname(__FILE__) . '/../../_ruta.php';

//if ($o_Plan->getId() < PLAN_PLUS)die('nop'); //plan free no pasa;

$T_Titulo = _('Horarios Multiples');
$T_Titulo_Singular = _('Horario');
$T_Titulo_Pre = _('el');
$T_Script = 'horarios_multiples';
$Item_Name = "horarios_multiple";
$T_Link = '';
$T_Mensaje = '';



$T_Tipo = (isset($_REQUEST['tipo'])) ? $_REQUEST['tipo'] : '';
$T_Id   = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;


$T_Horarios = isset($_POST['horarios']) ? (string) $_POST['horarios'] : '';
$T_Detalle = isset($_REQUEST['detalle']) ? (string) $_REQUEST['detalle'] : '';

$T_Filtro = (isset($_POST['filtro'])) ? $_POST['filtro'] : 0;

$T_personaID = (isset($_REQUEST['personaID'])) ? $_REQUEST['personaID'] : '';

$T_Filtro_Horario_Tipo = (isset($_REQUEST['f_horario_tipo'])) ? $_REQUEST['f_horario_tipo'] : 0;
$T_Filtro_Horario_Id   = (isset($_REQUEST['f_horario_id'])) ? $_REQUEST['f_horario_id'] : '';

switch ($T_Tipo) {
    case 'add':
    case 'edit':
        SeguridadHelper::Pasar(50);

        $o_Horario_Multiple = Horario_Multiple_L::obtenerPorId($T_Id);
        if (is_null($o_Horario_Multiple)) {
            $o_Horario_Multiple = new Horario_Multiple_O();
        }

        $o_Horario_Multiple->setDetalle($T_Detalle);
        $T_Horarios = str_replace('\"', '"', $T_Horarios);
        $o_Horario_Multiple->setHorarios($T_Horarios);

        $nuevo_horario = 0;
        if ($o_Horario_Multiple->getId() == 0) $nuevo_horario = 1;//esta variable me permite saber si fue un insert o un edit

        if (!$o_Horario_Multiple->save(Registry::getInstance()->general['debug'])) {
            $T_Error = $o_Horario_Multiple->getErrores();
        } else {
            if($nuevo_horario)
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_HORARIO_MULTIPLE_CREAR, $a_Logs_Tipos[LOG_HORARIO_MULTIPLE_CREAR], '<b>Id:</b> ' . $o_Horario_Multiple->getId() . ' <b>Horario:</b> ' . $o_Horario_Multiple->getDetalle() , $o_Horario_Multiple->getId());
            else
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_HORARIO_MULTIPLE_EDITAR, $a_Logs_Tipos[LOG_HORARIO_MULTIPLE_EDITAR], '<b>Id:</b> ' . $o_Horario_Multiple->getId() . ' <b>Horario:</b> ' . $o_Horario_Multiple->getDetalle() , $o_Horario_Multiple->getId());

            $T_Mensaje = _('Horario m??ltiple guardado correctamente.');
        }

        goto defaultLabel;
        break;

    case 'view':
        $o_Horario_Multiple = Horario_Multiple_L::obtenerPorId($T_Id);

        if (is_null($o_Horario_Multiple)) {
            $o_Horario_Multiple = new Horario_Multiple_O();
        } else {

        }
        break;

    case 'delete':

        $o_Horario_Multiple = Horario_Multiple_L::obtenerPorId($T_Id);

        if (is_null($o_Horario_Multiple)) {
            $T_Error = _('Lo sentimos, el horario m??ltiple que desea eliminar no existe.');
        } else {
            $cantidad_personas = Persona_L::obtenerPorHorariodeTrabajoCOUNT($T_Id,HORARIO_MULTIPLE);
            if ($cantidad_personas == 0) {
                if (!$o_Horario_Multiple->delete(Registry::getInstance()->general['debug'])) {
                    $T_Error = $o_Horario_Multiple->getErrores();
                } else {
                    SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_HORARIO_MULTIPLE_ELIMINAR, $a_Logs_Tipos[LOG_HORARIO_MULTIPLE_ELIMINAR], '<b>Id:</b> ' . $o_Horario_Multiple->getId() . ' <b>Horario:</b> ' . $o_Horario_Multiple->getDetalle() , $o_Horario_Multiple->getId());
                    $T_Mensaje = _('El horario m??ltiple fue eliminado correctamente.');
                }
            } else {
                $T_Eliminado = false;
                $T_Error = _('El horario no se puede eliminar, porque est?? asignado a una o m??s personas.');
                goto defaultLabel;
            }
        }

        goto defaultLabel;
        break;
    case 'insert':

        if ($T_personaID == '') break;

        $o_Persona = Persona_L::obtenerPorId($T_personaID);
        $o_Persona->setHorTipo($T_Filtro_Horario_Tipo);
        $o_Persona->setHorId($T_Id);


        if (!$o_Persona->save(true)) {
            $T_Error = $o_Persona->getErrores();
        }
        else {
            /* AGREGAR ESTA SINCRONIZACI??N CUANDO LOS HORARIOS SEAN NECESARIOS EN EQUIPOS
            //SeguridadHelper::Reporte(Registry::getInstance()->Usuario->getId(), $T_Titulo . '-' . $a_Acciones[0], _('Tabla') . ' - ' . $T_Script . ' Id - ' . $o_Persona->getId(),LOG_PERSONAS_EDITAR,$o_Persona->getId());
            SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_PERSONA_EDITAR, $a_Logs_Tipos[LOG_PERSONA_EDITAR], '<b>Id:</b> ' . $o_Persona->getId() . ' <b>Nombre:</b> ' . $o_Persona->getNombreCompleto(), $o_Persona->getId());
            $se_sincroniza=0;
            $a_o_Equipo = Equipo_L::obtenerTodos();
            $array_equipos = explode(':', $o_Persona->getEquipos());
            if ($a_o_Equipo != null) {
                foreach ($a_o_Equipo as $o_Equipo) {
                    // @var $o_Equipo Equipo_O
                    if ($o_Equipo->isOffline()) continue;
                    if (!in_array($o_Equipo->getId(), $array_equipos)) continue;
                    $se_sincroniza=1;
                }
            }

            if($se_sincroniza){
                $T_Mensaje = 'La persona fue guardada con ??xito. Sincronizando datos...';
                $T_sync_checker = "syncChecker(" . $o_Persona->getId() . ",\"" . $o_Persona->getApellido() . ', ' . $o_Persona->getNombre() . "\",".count(explode(':',$o_Persona->getEquipos())).");";
                $T_sync_js_start = "disableRow(\"" . $o_Persona->getApellido() . ', ' . $o_Persona->getNombre() . "\");";
            }else{
                $T_Mensaje = 'La persona fue guardada con ??xito.';
            }

            // Sync
            SyncHelper::SyncPersona($o_Persona);
            // Fin Sync
            */
        }
        die();
        break;

    case 'remove':

        if ($T_personaID == '') break;


        $o_Persona = Persona_L::obtenerPorId($T_personaID);
        $o_Persona->setHorTipo(0);
        $o_Persona->setHorId(0);

        if (!$o_Persona->save()) {
            $T_Error = $o_Persona->getErrores();
        }
        else {
            /* AGREGAR ESTA SINCRONIZACI??N CUANDO LOS HORARIOS SEAN NECESARIOS EN EQUIPOS
            */
        }
        die();
        break;

    default:
        defaultLabel:
        SeguridadHelper::Pasar(20);

        $o_Listado = Horario_Multiple_L::obtenerTodos();
        break;

}

$_SESSION['filtro']['tipo_data']             =   'Horarios_Multiples';
$_SESSION['filtro']['persona_data']          =   Filtro_L::get_filtro_persona();
$_SESSION['filtro']['intervalo_data']        =   '';
