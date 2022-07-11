<?php


require_once dirname(__FILE__) . '/../../_ruta.php';


$T_Titulo = _('Horarios Rotativos');
$T_Titulo_Singular = _('Horario');
$T_Titulo_Pre = _('el');
$T_Script = 'horarios_rotativos';
$Item_Name = "horarios_rotativo";
$T_Link = '';
$T_Mensaje = '';



$T_Tipo = (isset($_REQUEST['tipo'])) ? $_REQUEST['tipo'] : '';
$T_Id   = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;


$T_Horarios = isset($_POST['horarios']) ? (string) $_POST['horarios'] : '';
$T_Detalle = isset($_REQUEST['detalle']) ? (string) $_REQUEST['detalle'] : '';
$T_FechaInicio = isset($_REQUEST['fecha_inicio']) ? (string) $_REQUEST['fecha_inicio'] : '';

$T_Filtro = (isset($_POST['filtro'])) ? $_POST['filtro'] : 0;

$T_personaID = (isset($_REQUEST['personaID'])) ? $_REQUEST['personaID'] : '';

$T_Filtro_Horario_Tipo = (isset($_REQUEST['f_horario_tipo'])) ? $_REQUEST['f_horario_tipo'] : 0;
$T_Filtro_Horario_Id   = (isset($_REQUEST['f_horario_id'])) ? $_REQUEST['f_horario_id'] : '';

switch ($T_Tipo) {
    case 'add':
    case 'edit':
        SeguridadHelper::Pasar(50);

        $o_Horario_Rotativo = Horario_Rotativo_L::obtenerPorId($T_Id);
        if (is_null($o_Horario_Rotativo)) {
            $o_Horario_Rotativo = new Horario_Rotativo_O();
        }

        $o_Horario_Rotativo->setDetalle($T_Detalle);
        $T_Horarios = str_replace('\"', '"', $T_Horarios);
        $o_Horario_Rotativo->setHorarios($T_Horarios);
        $o_Horario_Rotativo->setFechaInicio($T_FechaInicio,'Y-m-d H:i:s');

        $nuevo_horario = 0;
        if ($o_Horario_Rotativo->getId() == 0) $nuevo_horario = 1;//esta variable me permite saber si fue un insert o un edit

        if (!$o_Horario_Rotativo->save(Registry::getInstance()->general['debug'])) {
            $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Horario_Rotativo->getErrores();
        } else {
            if($nuevo_horario)
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_HORARIO_ROTATIVO_ELIMINAR, $a_Logs_Tipos[LOG_HORARIO_ROTATIVO_CREAR], '<b>Id:</b> ' . $o_Horario_Rotativo->getId() . ' <b>Horario:</b> ' . $o_Horario_Rotativo->getDetalle() , $o_Horario_Rotativo->getId());
            else
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_HORARIO_ROTATIVO_ELIMINAR, $a_Logs_Tipos[LOG_HORARIO_ROTATIVO_EDITAR], '<b>Id:</b> ' . $o_Horario_Rotativo->getId() . ' <b>Horario:</b> ' . $o_Horario_Rotativo->getDetalle() , $o_Horario_Rotativo->getId());
            $T_Mensaje = _('Horario rotativo guardado correctamente.');
        }

        goto defaultLabel;
        break;

    case 'view':
        $o_Horario_Rotativo = Horario_Rotativo_L::obtenerPorId($T_Id);

        if (is_null($o_Horario_Rotativo)) {
            $o_Horario_Rotativo = new Horario_Rotativo_O();
        } else {

        }
        break;

    case 'delete':

        $o_Horario_Rotativo = Horario_Rotativo_L::obtenerPorId($T_Id);

        if (is_null($o_Horario_Rotativo)) {
            $T_Error = _('Lo sentimos, el horario rotativo que desea eliminar no existe.');
        } else {
            $cantidad_personas = Persona_L::obtenerPorHorariodeTrabajoCOUNT($T_Id,HORARIO_ROTATIVO);
            if ($cantidad_personas == 0) {
                if (!$o_Horario_Rotativo->delete(Registry::getInstance()->general['debug'])) {
                    $T_Error = $o_Hora_Trabajo->getErrores();
                } else {
                    SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_HORARIO_ROTATIVO_ELIMINAR, $a_Logs_Tipos[LOG_HORARIO_ROTATIVO_ELIMINAR], '<b>Id:</b> ' . $T_Id . ' <b>Horario:</b> ' . $o_Horario_Rotativo->getDetalle() , $T_Id);
                    $T_Mensaje = _('El horario rotativo fue eliminado correctamente.');
                }
            } else {
                $T_Eliminado = false;
                $T_Error = _('El horario no se puede eliminar, porque está asignado a una o más personas.');
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
            /* AGREGAR ESTA SINCRONIZACIÓN CUANDO LOS HORARIOS SEAN NECESARIOS EN EQUIPOS
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
                $T_Mensaje = 'La persona fue guardada con éxito. Sincronizando datos...';
                $T_sync_checker = "syncChecker(" . $o_Persona->getId() . ",\"" . $o_Persona->getApellido() . ', ' . $o_Persona->getNombre() . "\",".count(explode(':',$o_Persona->getEquipos())).");";
                $T_sync_js_start = "disableRow(\"" . $o_Persona->getApellido() . ', ' . $o_Persona->getNombre() . "\");";
            }else{
                $T_Mensaje = 'La persona fue guardada con éxito.';
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

        //$datos['per_Hor_Tipo'] = 0;
        //$datos['per_Hor_Id']   = 0;

        //$resultado = Registry::getInstance()->DbConn->Update('personas', $datos, 'per_Id =' . $T_personaID);

        if (!$o_Persona->save(true)) {
            $T_Error = $o_Persona->getErrores();
        }
        else {
            /* AGREGAR ESTA SINCRONIZACIÓN CUANDO LOS HORARIOS SEAN NECESARIOS EN EQUIPOS
            */
        }
        die();
        break;

    default:
        defaultLabel:
        SeguridadHelper::Pasar(20);

        $o_Listado = Horario_Rotativo_L::obtenerTodos();
        break;
}
$_SESSION['filtro']['tipo_data']             =   'Horarios_Rotativos';
$_SESSION['filtro']['persona_data']          =   Filtro_L::get_filtro_persona();
$_SESSION['filtro']['intervalo_data']        =   '';