<?php


SeguridadHelper::Pasar(20);

$T_Titulo               = _('Grupos');
$T_Script               = 'grupos';
$T_Titulo_Singular      = _('Grupo');
$T_Titulo_Pre           = _('el');
$Item_Name              = 'grupos';
$T_Link                 = '';
$T_Error                = '';
$T_Mensaje              = '';
$T_Tipo                 = (isset($_REQUEST['tipo'])) ? $_REQUEST['tipo'] : '';
$T_Id                   = isset($_REQUEST['id']) ? (integer)$_REQUEST['id'] : 0;
$T_EnVivo               = (isset($_REQUEST['envivo'])) ? $_REQUEST['envivo'] : '';

switch ($T_Tipo) {
    case 'add':
    case 'edit':
        SeguridadHelper::Pasar(50);

        $o_Grupo = Grupo_L::obtenerPorId($T_Id);
        if (is_null($o_Grupo)) {
            $o_Grupo = new Grupo_O();
        }


        $o_Grupo->setDetalle(isset($_REQUEST['detalle']) ? $_REQUEST['detalle'] : '');
        $o_Grupo->setEnVivo($T_EnVivo == "on"   ?    1   :   0);


        $grupo_nuevo = 0;
        if ($o_Grupo->getId() == 0)
            $grupo_nuevo = 1;//esta variable me permite saber si fue un insert o un edit

        if (!$o_Grupo->save(Registry::getInstance()->general['debug'])) {
            $T_Error = $o_Grupo->getErrores();
        }
        else {
            SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_GRUPO_CREAR, $a_Logs_Tipos[$grupo_nuevo == 1 ? LOG_GRUPO_CREAR: LOG_GRUPO_EDITAR], '<b>Id:</b> ' . $o_Grupo->getId() . ' <b>Grupo:</b> ' . $o_Grupo->getDetalle(), $o_Grupo->getId());
            $T_Mensaje = _('El grupo fue modificado correctamente.');
        }


        $T_Modificar = true;
        $o_Listado = Grupo_L::obtenerTodos();
        $T_Link = '';
        break;

    case 'personas':
        $o_Grupo = Grupo_L::obtenerPorId($T_Id);

        $a_Personas = HtmlHelper::array2htmloptions(Persona_L::obtenerTodos(), null, false, true, 'Persona');

        $a_PersonasGrupo = HtmlHelper::array2htmloptions(Grupos_Personas_L::obtenerListaPorGrupo($T_Id), null, false, true, 'Not_Persona');

        $T_Add = true;
        break;
    case 'insert':
        SeguridadHelper::Pasar(50);
        $o_Grupo = Grupo_L::obtenerPorId($T_Id);

        $o_GPersona = new Grupos_Personas_O();

        $o_personaCheck = Grupos_Personas_L::obtenerPorPerIDyGrupo(isset($_REQUEST['persona']) ? (integer)$_REQUEST['persona'] : 0, $T_Id);
        if ($o_personaCheck != null) die("error_persona_ya_existe");

        $o_GPersona->setPersona(isset($_REQUEST['persona']) ? (integer)$_REQUEST['persona'] : 0);
        $o_GPersona->setGrupo($T_Id);

        $o_GPersona->save('Off');

        //$a_PersonasGrupo = HtmlHelper::array2htmloptions(Grupos_Personas_L::obtenerListaPorGrupo($T_Id),null,false,true,'Not_Persona');
        //echo $a_PersonasGrupo;

        //$T_Add = true;
        //$T_Link = '_add';
        die();
        break;
    case 'remove':
        SeguridadHelper::Pasar(50);

        if (isset($_REQUEST['GpersonaID'])) {
            $o_GPersona = Grupos_Personas_L::obtenerPorPerIdyGrupo(isset($_REQUEST['GpersonaID']) ? (integer)$_REQUEST['GpersonaID'] : 0, $T_Id);
            $o_GPersona->delete('Off');
        }
        //$a_PersonasGrupo = HtmlHelper::array2htmloptions(Grupos_Personas_L::obtenerListaPorGrupo($T_Id),null,false,true,'Not_Persona');
        //echo $a_PersonasGrupo;

        //$T_Add = true;
        die();
        break;
    case 'delete' :
        SeguridadHelper::Pasar(50);

        // GRUPO OBJECT
        $o_Grupo = Grupo_L::obtenerPorId($T_Id);

        // GRUPO NO EXISTE
        if (is_null($o_Grupo)) {
            $T_Error = _('Lo sentimos, el grupo que desea eliminar no existe.');
            goto defaultLabel;
            break;
        }

        // PERSONA_GRUPO ARRAY RELATIONS OBJECTS
        $a_PersonasGrupo = Grupos_Personas_L::obtenerListaPorGrupo($T_Id);

        $T_Eliminado = false;
        // PERSONA_GRUPO DELETE RELATIONS
        if (!is_null($a_PersonasGrupo)) {
            $T_Eliminado = true;
            foreach ($a_PersonasGrupo as $PG_Key => $PG_Item){
                $_PGID=$PG_Item->getId();

                if($PG_Item->delete(Registry::getInstance()->general['debug'])){
                    continue;
                }
                else{
                    $T_Eliminado = false;
                    $T_Error = $T_Error."<br>".$PG_Item->getErrores() ;
                }
            }
        }
        // GRUPO CANNOT BE DELETED, TRY AGAIN
        if(!$T_Eliminado){
            $T_Error     = _('El grupo no se pudo eliminar por un error en la conexión. Intente Nuevamente.');
            goto defaultLabel;
            break;
        }

        // DELETE SUCCESS
        if ($o_Grupo->delete(Registry::getInstance()->general['debug'])) {
            SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_GRUPO_ELIMINAR, $a_Logs_Tipos[LOG_GRUPO_ELIMINAR], '<b>Id:</b> ' . $o_Grupo->getId() . ' <b>Grupo:</b> ' . $o_Grupo->getDetalle(), $o_Grupo->getId());
            $T_Mensaje = ('El grupo fue eliminado correctamente.');
        }
        // DELETE ERROR
        else {
            $T_Error     = _('El grupo no se pudo eliminar. ')."<br>".$o_Grupo->getErrores();
        }

        goto defaultLabel;
        break;

    case 'view':
        SeguridadHelper::Pasar(20);
        $o_Grupo = Grupo_L::obtenerPorId($T_Id);

        if (is_null($o_Grupo)) {
            $o_Grupo = new Grupo_O();
        } else {
        }
        break;

    default:
        defaultLabel:
        $o_Listado = Grupo_L::obtenerTodos();
        $T_Link = '';
        break;
}


$_SESSION['filtro']['tipo_data']             =   'Grupos';
$_SESSION['filtro']['persona_data']          =   Filtro_L::get_filtro_persona();
$_SESSION['filtro']['intervalo_data']        =   '';