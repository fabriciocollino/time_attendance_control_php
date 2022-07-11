<?php




/* VARIABLES GENERALES */
$T_Titulo       = _('Suspensiones');
$Item_Name      = "suspension";
$T_Script       = 'suspension';
$T_Titulo_Singular = _('Suspension');
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
            $o_Suspension = Suspensions_L::obtenerPorId($T_Id);

            // CREATE LICENCE OBJECT
            if (is_null($o_Suspension)) {
                $o_Suspension = new Suspensions_O();
            }

            $o_Suspension->setMotivo($T_Motivo);
            $o_Suspension->setPerId($p_Item);
            $o_Suspension->setTipo($T_selTipo);
            $o_Suspension->setEnabled($T_Enabled);


            switch ($T_selTipo) {

                case SUSPENSION_DIA_COMPLETO:
                    $o_Suspension->setFechaInicio($T_DiaCompleto, 'Y-m-d H:i:s');
                    $o_Suspension->setFechaFin(date('Y-m-d', strtotime($T_DiaCompleto)) . " " . "23:59:59", 'Y-m-d H:i:s');
                    $o_Suspension->setDuracion(1);
                    break;

                case SUSPENSION_PERSONALIZADA:
                    $o_Suspension->setFechaInicio($T_LFechaD, 'Y-m-d H:i:s');
                    $o_Suspension->setFechaFin(date('Y-m-d', strtotime($T_LFechaH)) . " " . "23:59:59", 'Y-m-d H:i:s');
                    $diff_time      = strtotime($o_Suspension->getFechaFin()) - strtotime($o_Suspension->getFechaInicio());
                    $diff_days      = floor($diff_time/(60*60*24)) + 1;
                    $o_Suspension->setDuracion($diff_days);
                    break;
            }


            $_Flag_nuevaSuspension = $o_Suspension->getId() == 0 ? true : false;

            // SAVE
            if ($o_Suspension->save(Registry::getInstance()->general['debug'])) {
            }
            else {
                $T_Error = $o_Suspension->getErrores();
            }
        }

        if($T_Error == ''){
            $T_Mensaje = $_Flag_nuevaSuspension == true ? _('Suspensión creada correctamente.') : _('Suspensión editada correctamente.');
        }

        $T_Modificar = true;
        goto defaultLabel;
        break;

    case 'delete' :
        $o_Suspension = Suspensions_L::obtenerPorId($T_Id);

        if (is_null($o_Suspension)) {
            $T_Error = _('Lo sentimos, la suspensión que desea eliminar no existe.');
        }
        else {
            $personagrupo = '';

            if ($o_Suspension->getPersonaOGrupo() == 'persona') {
                $personagrupo = Persona_L::obtenerPorId($o_Suspension->getPerId(), false, false)->getNombreCompleto();
            }
            else if ($o_Suspension->getPersonaOGrupo() == 'grupo') {
                $personagrupo = 'Grupo: ' . Grupo_L::obtenerPorId($o_Suspension->getGrupoId())->getDetalle();
            }

            if (!$o_Suspension->delete(Registry::getInstance()->general['debug'])) {
                $T_Error = $o_Suspension->getErrores();
            }
            else {
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_SUSPENSION_ELIMINAR, $a_Logs_Tipos[LOG_SUSPENSION_ELIMINAR], '<b>Id:</b> ' . $o_Suspension->getId() . ' <b>Motivo:</b> ' . $o_Suspension->getMotivo() . ' <b>Personas:</b> ' . $personagrupo, $o_Suspension->getId());
                $T_Mensaje = _('Suspensión eliminada correctamente.');
            }
        }

        goto defaultLabel;

        break;

    case 'view':
        $o_Suspension = Suspensions_L::obtenerPorId($T_Id);

        if (is_null($o_Suspension)) {
            $o_Suspension = new Suspensions_O();
        }

        break;

    default:

        defaultLabel:


        $T_Filtro_Array         = Filtro_L::get_filtro_persona($_POST,$_SESSION);
        $T_Filtro_Intervalo     = $T_Intervalo;
        $T_Filtro_Suspension    = $T_Id;

        $o_Listado              = Suspensions_L::obtenerDesdeFiltro($T_Filtro_Array, $T_Filtro_Intervalo, $T_Filtro_Suspension);
        $o_Listado_Personas     = Persona_L::obtenerDesdeFiltro($T_Filtro_Array);


        $_SESSION['filtro']['id']               =   $T_Id;
        $_SESSION['filtro']['tipo']             =   'Suspensiones';;
        $_SESSION['filtro']['intervalo']        =   $T_Filtro_Intervalo;

        $_SESSION['filtro']['tipo_data']                =   'Suspensiones';
        $_SESSION['filtro']['persona_data']             =   $T_Filtro_Array;
        $_SESSION['filtro']['intervalo_data']           =   $T_Filtro_Intervalo;


}

