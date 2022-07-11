<?php


/* VARIABLES GENERALES */
$T_Titulo       = _('Licencias');
$Item_Name      = "licencia";
$T_Script       = 'licencia';
$T_Titulo_Singular = _('Licencia');
$T_Mensaje      = '';
$T_Error        = '';
$T_Link         = '';

/* VARIABLES */
$T_Id           = isset($_REQUEST['id'])                        ? $_REQUEST['id']               : 0;
$T_Tipo         = isset($_REQUEST['tipo'])                      ? $_REQUEST['tipo']             : '';
$T_selTipo      = isset($_REQUEST['selTipo_Editar'])            ? $_REQUEST['selTipo_Editar']          : '';

$T_Motivo       = isset($_REQUEST['motivo_Editar'])             ? $_REQUEST['motivo_Editar']           : '';
$T_Persona      = isset($_REQUEST['selPersona_Editar'])         ? $_REQUEST['selPersona_Editar']         : 0;
$T_Grupo        = isset($_REQUEST['selGrupo_Editar'])           ? $_REQUEST['selGrupo_Editar']             : '';

$T_DiaCompleto  = isset($_REQUEST['diaCompleto_Editar'])        ? $_REQUEST['diaCompleto_Editar']      : '';
$T_LFechaD      = isset($_REQUEST['fechaD_Editar'])             ? $_REQUEST['fechaD_Editar']          : '';
$T_LFechaH      = isset($_REQUEST['fechaH_Editar'])             ? $_REQUEST['fechaH_Editar']          : '';
$T_Enabled      = isset($_REQUEST['enabled_Editar'])            ? $_REQUEST['enabled_Editar']          : 1;

$T_Intervalo    =   !isset($_REQUEST['intervaloFecha'])         ?       isset($_SESSION['filtro']['intervalo'])       ?   $_SESSION['filtro']['intervalo']            :     'F_Hoy'                 :     $_REQUEST['intervaloFecha'];


/*/
$_SESSION['filtro']['intervalo']        =   $T_Intervalo;
$_SESSION['filtro']['fechaD']           =   $fecha_desde;
$_SESSION['filtro']['fechaH']           =   $fecha_hasta;
$_SESSION['filtro']['persona']          =   $persona;
$_SESSION['filtro']['rolF']             =   $grupo;
*/

switch ($T_Tipo) {

    case 'add':
    case 'edit':

        // CASE: TODAS LAS PERSONAS (ACTIVAS)
        switch($T_Persona){

            case "TodasLasPersonas":
                $_todas_las_Personas = Persona_L::obtenerTodosIdenArray();
                if(!is_null($_todas_las_Personas)){
                    $_List_Personas = $_todas_las_Personas;
                }

                break;
            case "SelectRol":
                $_personas_en_Grupo = Grupos_Personas_L::obtenerARRAYPorGrupo($T_Grupo);

                if(!is_null($_personas_en_Grupo)){
                    $_List_Personas = $_personas_en_Grupo;
                }
                break;
            default:
                $_List_Personas[] = $T_Persona;
        }

        foreach ($_List_Personas as $list_key => $p_Item) {
            // GET LICENCE OBJECT

            $o_Licencia = Licencias_L::obtenerPorId($T_Id);

            // CREATE LICENCE OBJECT
            if (is_null($o_Licencia)) {
                $o_Licencia = new Licencias_O();
            }

            $o_Licencia->setMotivo($T_Motivo);
            $o_Licencia->setPerId($p_Item);
            $o_Licencia->setTipo($T_selTipo);

            switch ($T_selTipo) {
                case LICENCIA_DIA_COMPLETO:
                    $o_Licencia->setFechaInicio($T_DiaCompleto, 'Y-m-d H:i:s');
                    $o_Licencia->setFechaFin(date('Y-m-d', strtotime($T_DiaCompleto)) . " " . "23:59:59", 'Y-m-d H:i:s');
                    $o_Licencia->setDuracion(1);
                    break;

                case LICENCIA_PERSONALIZADA:
                    $o_Licencia->setFechaInicio($T_LFechaD, 'Y-m-d H:i:s');
                    $o_Licencia->setFechaFin(date('Y-m-d', strtotime($T_LFechaH)) . " " . "23:59:59", 'Y-m-d H:i:s');

                    $diff_time      = strtotime($o_Licencia->getFechaFin()) - strtotime($o_Licencia->getFechaInicio());
                    $diff_days      = floor($diff_time/(60*60*24)) + 1;
                    $o_Licencia->setDuracion($diff_days);
                    break;
            }

            $_Flag_nuevaLicencia = $o_Licencia->getId() == 0 ? true : false;

            // SAVE
            if ($o_Licencia->save(Registry::getInstance()->general['debug'])) {
            }
            else {
                $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Licencia->getErrores();
            }
        }

        if($T_Error == ''){
            $T_Mensaje = $_Flag_nuevaLicencia == true ? _('Licencia creada correctamente.') : _('Licencia editada correctamente.');
        }

        $T_Modificar = true;
        goto defaultLabel;
        break;

    case 'delete' :
        SeguridadHelper::Pasar(50);
        $o_Licencia = Licencias_L::obtenerPorId($T_Id);

        if (is_null($o_Licencia)) {
            $T_Error = _('Lo sentimos, la licencia que desea eliminar no existe.');
        } else {
            $personagrupo = '';
            if ($o_Licencia->getPersonaOGrupo() == 'persona') {
                $personagrupo = Persona_L::obtenerPorId($o_Licencia->getPerId())->getNombreCompleto();
            } else if ($o_Licencia->getPersonaOGrupo() == 'grupo') {
                $personagrupo = 'Grupo: ' . Grupo_L::obtenerPorId($o_Licencia->getGrupoId())->getDetalle();
            }
            if (!$o_Licencia->delete(Registry::getInstance()->general['debug'])) {
                $T_Error = $o_Licencia->getErrores();
            } else {
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_LICENCIA_ELIMINAR, $a_Logs_Tipos[LOG_LICENCIA_ELIMINAR], '<b>Id:</b> ' . $o_Licencia->getId() . ' <b>Motivo:</b> ' . $o_Licencia->getMotivo() . ' <b>Personas:</b> ' . $personagrupo, $o_Licencia->getId());
                $T_Mensaje = _('La Licencia fue eliminada correctamente.');
            }

        }

        goto defaultLabel;
        break;


    case 'view':
        $o_Licencia = Licencias_L::obtenerPorId($T_Id);

        if (is_null($o_Licencia)) {
            $o_Licencia = new Licencias_O();
        }

        break;

    default:

        defaultLabel:

        $T_Filtro_Array         = Filtro_L::get_filtro_persona($_POST,$_SESSION);
        $T_Filtro_Intervalo     = $T_Intervalo;
        $T_Filtro_Licencia       = $T_Id;

        $o_Listado              = Licencias_L::obtenerDesdeFiltro($T_Filtro_Array, $T_Filtro_Intervalo, $T_Filtro_Licencia);
        $o_Listado_Personas     = Persona_L::obtenerDesdeFiltro($T_Filtro_Array);


        $_SESSION['filtro']['id_data']               =   $T_Id;
        $_SESSION['filtro']['tipo_data']             =   'Licencias';
        $_SESSION['filtro']['persona_data']          =   $T_Filtro_Array;
        $_SESSION['filtro']['intervalo_data']        =   $T_Filtro_Intervalo;


        $_SESSION['filtro']['id']               =   $T_Id;
        $_SESSION['filtro']['tipo']             =   'Suspensiones';;
        $_SESSION['filtro']['intervalo']        =   $T_Filtro_Intervalo;

        $_SESSION['filtro']['tipo_data']                =   'Suspensiones';
        $_SESSION['filtro']['persona_data']             =   $T_Filtro_Array;
        $_SESSION['filtro']['intervalo_data']           =   $T_Filtro_Intervalo;



}

