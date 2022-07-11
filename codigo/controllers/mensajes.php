<?php
require_once dirname(__FILE__) . '/../../_ruta.php';


SeguridadHelper::Pasar(50);

$T_Link = '';
$T_Mensaje = '';

$T_Tipo = (isset($_REQUEST['tipo'])) ? $_REQUEST['tipo'] : '';
$T_Id = isset($_REQUEST['id']) ? (integer)$_REQUEST['id'] : 0;

$T_Tipon = (isset($_POST['tipon'])) ? $_POST['tipon'] : '';
$T_Detalle = (isset($_POST['detalle'])) ? $_POST['detalle'] : '';

$T_Destinatario_Tipo = isset($_POST['destinatario_tipo']) ? (integer)$_POST['destinatario_tipo'] : 0;
$T_Destinatario_Usuarios = intval(isset($_POST['destinatario_usuario']) ? (integer)$_POST['destinatario_usuario'] : 0);
$T_Destinatario_Grupos = intval(isset($_POST['destinatario_grupo']) ? (integer)$_POST['destinatario_grupo'] : 0);
$T_Destinatario_Personas = intval(isset($_POST['destinatario_persona']) ? (integer)$_POST['destinatario_persona'] : 0);


//DISPARADOR

$T_Disparador = (isset($_POST['disparador'])) ? $_POST['disparador'] : '';
$T_DisparadorTipo = (isset($_POST['disparador_tipo'])) ? $_POST['disparador_tipo'] : '';
$T_Hora = (isset($_POST['hora'])) ? $_POST['hora'] : '';



//CONTENIDO
$T_Tema = (isset($_POST['tema'])) ? $_POST['tema'] : '';
$T_Mensajec = (isset($_POST['mensajec'])) ? $_POST['mensajec'] : '';

$T_Titulo = _('Mensajes');
$Item_Name = "mensaje";
$T_Titulo_Singular = "mensaje";
$T_Titulo_Pre = "un";
$T_Script = 'mensaje';
$T_Mensaje = '';

$T_Tipo = (isset($_REQUEST['tipo'])) ? $_REQUEST['tipo'] : '';
$T_Id = isset($_REQUEST['id']) ? (integer)$_REQUEST['id'] : 0;
$T_Id = isset($_POST['id']) ? (integer)$_POST['id'] : $T_Id;
$T_Cmd = (isset($_REQUEST['cmd'])) ? $_REQUEST['cmd'] : '';



//TIPOS DE CONTENIDO
/*
 * Reporte tipo 1= aviso
 * tipo 10 = reporte por persona
 * tipo 11 = reporte por zona
 * tipo 12 = reporte por equipo
 * tipo 13 = reporte por alarmas
 * tipo 14 = reporte por periferico
 * tipo 15 = reporte por persona y periferico
 * 
 * 
 * */

switch ($T_Tipo) {
    case 'accion':

        if($T_Cmd=='mark_as_read') {
            $o_Mensaje = Mensaje_L::obtenerPorId($T_Id);
            if (!is_null($o_Mensaje)) {
                $o_Mensaje->setVisto(1);
                $o_Mensaje->setFechaVisto(date('Y-m-d H:i:s'), 'Y-m-d H:i:s');
                $o_Mensaje->save('Off',true);
            } else {
                die("El mensaje no existe");
            }
            die();
        }
        break;
    case 'view':

        SeguridadHelper::Pasar(50);
        $o_Mensaje = Mensaje_L::obtenerPorId($T_Id);
        if($o_Mensaje != null){
            $T_Tema = $o_Mensaje->getTitulo();
            $T_Mensajec = $o_Mensaje->getMensaje();
            $T_Hora = $o_Mensaje->getDisparadorHora();
            $T_DisparadorTipo = $o_Mensaje->getDisparadorTipo();
            $T_Destinatario_Usuarios = $o_Mensaje->getUsuId();
            $T_Destinatario_Grupos = $o_Mensaje->getGruId();
            $T_Destinatario_Personas = $o_Mensaje->getPerId();
            $T_Tipon = $o_Mensaje->getTipo();

        }else{
            $o_Mensaje = new Mensaje_O();
            $T_Tipon = 0 ;
            $T_Destinatario_Usuarios = 0;
            $T_Destinatario_Personas = 0 ;
            $T_Destinatario_Grupos = 0 ;
            $T_Tema = '';
            $T_Mensajec = '';
            $T_Disparador = '';
            $T_DisparadorTipo = 0;
            $T_Hora = date('Y-m-d H:i:s');
        }

        if ($T_Tipon == '') $i_Tipon = null;
        else $i_Tipon = $T_Tipon;

        if ($T_Disparador == '') $i_Disparador = null;
        else $i_Disparador = $T_Disparador;

        if ($T_DisparadorTipo == 0) $i_DisparadorTipo = 0;
        else $i_DisparadorTipo = $T_DisparadorTipo;

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
        $T_Tipon = HtmlHelper::array2htmloptions(array( 0 => _("Mensaje en Inicio") ,1 => _("Email")  ), $i_Tipon, false, false, '', 'Seleccione un Tipo');

        $T_Destinatario_Tipo = HtmlHelper::array2htmloptions(Persona_L::obtenerTodos(0,0,0,'per_Eliminada = 0 and per_Excluir=0'), $i_Destinatario_Tipo, true, true, 'Destinatario', 'Seleccione un Tipo');

        $T_Destinatario_Usuarios = HtmlHelper::array2htmloptions(Usuario_L::obtenerListaEmails(), $i_Destinatario_Usuarios, true, false, 'Usuarios', _('Seleccione un Usuario'));
        $T_Destinatario_Grupos = HtmlHelper::array2htmloptions(Grupo_L::obtenerTodos(), $i_Destinatario_Grupos, true, true, 'GrupoPersonas', _('Seleccione Grupo de Personas'));
        // $T_Destinatario_Personas = HtmlHelper::array2htmloptions(Persona_L::obtenerTodos(0,0,0,'per_Eliminada = 0 '), $i_Destinatario_Personas, true, true, 'Personas', _('Seleccione un Persona'));


        $T_DisparadorTipo = HtmlHelper::array2htmloptions(array(0 =>_("Now"), 1 => _("Programmed")), $i_DisparadorTipo, false, false, '', _('Seleccione un Tipo'));

        // $T_Disparador = HtmlHelper::array2htmloptions($Notificaciones_Disparadores_1, $i_Disparador, 1, false, '', _('Seleccione un Disparador'));
        // $T_Persona = HtmlHelper::array2htmloptions(Persona_L::obtenerTodos(0,0,0,'per_Eliminada = 0 '), $i_Persona, true, true, 'PersonayRol', _('Todas las Personas'));
        // $T_Grupos = HtmlHelper::array2htmloptions(Grupo_L::obtenerTodos(), $i_Grupos, true, true, '', _('Seleccione un Grupo'));

        break;
    case 'add':
    case 'edit':
        SeguridadHelper::Pasar(50);

        $o_Mensaje = Mensaje_L::obtenerPorId($T_Id);
        if(is_null($o_Mensaje)){ //esto quiere decir que es un add
            $o_Mensaje = new Mensaje_O();
            $flag = false;
        }else{
            $flag = true;
        }

        if ($T_Destinatario_Tipo == 'Usuario'){ // para Usuarios
            $o_Mensaje->setUsuId($T_Destinatario_Usuarios);
            $o_Mensaje->setGruId(0);
            $o_Mensaje->setPerId(0);
        }else if ($T_Destinatario_Tipo == 'Grupo'){// para grupos
            $o_Mensaje->setUsuId(0);
            $o_Mensaje->setGruId($T_Destinatario_Grupos);
            $o_Mensaje->setPerId(0);
        }else { // para personas
            $o_Mensaje->setUsuId(0);
            $o_Mensaje->setGruId(0);
            $o_Mensaje->setPerId($T_Destinatario_Tipo);
        }


        $o_Mensaje->setTitulo($T_Tema);
        $o_Mensaje->setMensaje($T_Mensajec);

        if($T_DisparadorTipo == 0){
            $o_Mensaje->setDisparadorHora(date('Y-m-d H:i:s'));
        }else{
            $o_Mensaje->setDisparadorHora($T_Hora);
        }
        $o_Mensaje->setDisparadorTipo($T_DisparadorTipo);
        $o_Mensaje->setTipo($T_Tipon);
        $o_Mensaje->setVisto(1);
        $o_Mensaje->setStatus(0);

        if ($o_Mensaje->getId() != null)
            $T_Modificar = true;
        else
            $T_Modificar = false;

        if (!$o_Mensaje->save(Registry::getInstance()->general['debug'] , $flag) ) {
            $T_Error = $o_Mensaje->getErrores();
        } else {
            if ($T_Modificar) {
                $T_Mensaje = _('El mensaje fue modificado correctamente.');
            } else {
                $T_Mensaje = _('La mensaje fue guardada correctament.e');
            }
        }
        $T_Modificar = true;

        goto defaultlabel;
        break;
    case 'delete':
        $o_Mensaje = Mensaje_L::obtenerPorId($T_Id);
        if (!$o_Mensaje->delete(Registry::getInstance()->general['debug'])) {
            $T_Error = $o_Mensaje->getErrores();
        } else {
            $ref_url = "";
            $T_Eliminado = true;
            $T_Link = '';
            $T_Mensaje = _('El mensaje ha sido eliminado correctamente.');
            goto defaultlabel;
        }
        $ref_url = "";
        $T_Link = '';
        break;
    default:
        defaultlabel:
        SeguridadHelper::Pasar(50);
        $o_Listado = Mensaje_L::obtenerTodos();
        $T_Link = '';
}
