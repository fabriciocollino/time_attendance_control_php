<?php


$T_Tipo             = (isset($_REQUEST['tipo']))                ?           $_REQUEST['tipo']               : $T_Tipo;

$T_Id               = isset($_REQUEST["id"])                    ?           $_REQUEST["id"]             : 0;
$T_Body             = isset($_REQUEST["not_Body"])              ?           $_REQUEST["not_Body"]           : "";

$o_Notas = null;
$o_Nota = null;

switch ($T_Tipo) {
    
    case 'add':
        $o_Nota          = new Notas_O();
        $currentUser_Id  = Registry::getInstance()->Usuario->getId();

        $o_Nota->setPersonaId($T_Id);
        $o_Nota->setBody($T_Body);
        $o_Nota->setUserWriterId($currentUser_Id);
        $o_Nota->setCreationDate(date('Y-m-d H:i:s'));

        if($o_Nota->save()){
            $T_Mensaje = _('La nota fué guardado correctamente.');
        }
        else{
            $o_Nota->getErrores();
        }

        break;

    case 'delete':
        $o_Nota = Notas_L::obtenerPorId($T_Id);

        if ($o_Nota->delete(Registry::getInstance()->general['debug'])) {
            $T_Eliminado = true;
            $o_Nota = "Nota Eliminada";
        }
        else {
            $T_Error = $o_Notificacion->getErrores();
        }

        break;

    case 'viewAllPersona':
        $o_Notas         = Notas_L::getAllbyPersonaId($T_Id);

        break;

    default:
        $o_Notas = null;

        break;
        
}
