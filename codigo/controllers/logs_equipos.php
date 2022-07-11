<?php

SeguridadHelper::Pasar(90);

$T_Script   = 'logs_equipos';
$T_Link     = '';
$o_Listado  = null;

$T_Id       = isset($_REQUEST['id'])        ? (integer) $_REQUEST['id']         : 0;
$T_Accion   = isset($_REQUEST['accion'])    ? (string) $_REQUEST['accion']      : '';


$T_Tipo     = isset($_REQUEST['tipo'])      ? $_REQUEST['tipo']                 : '';
$T_Id       = isset($_REQUEST['id'])        ? (integer)$_REQUEST['id']          : 0;

$T_Persona  = isset($_REQUEST['persona'])   ? (integer)$_REQUEST['persona']     : 0;
$T_Grupo    = isset($_REQUEST['grupo'])     ? (integer)$_REQUEST['grupo']       : 0;
$T_Fecha    = isset($_REQUEST['fecha'])     ? $_REQUEST['fecha']                : '';
$T_Hora     = isset($_REQUEST['hora'])      ? $_REQUEST['hora']                 : '';

$T_Mensaje              = "";
$T_Error                = "";

switch ($T_Tipo) {
    case 'delete' :  //este es para la vista de logs equipos
        SeguridadHelper::Pasar(90);
        $o_Log = Logs_Equipo_L::obtenerPorId($T_Id);

        if (is_null($o_Log)) {
            $T_Error = _('Lo sentimos, el log que desea eliminar no existe.');
        }
        else {
            if (!$o_Log->delete(Registry::getInstance()->general['debug'])) {
                $T_Error = $o_Log->getErrores();
            }
            else {
                $T_Mensaje = _('El registro fue eliminado correctamente.');
            }

        }
        break;

    case 'view' :  //este es para la vista de agregar/editar log, desde entradas/salidas
        SeguridadHelper::Pasar(90);

        if($T_Id!=0){
            $o_Log = Logs_Equipo_L::obtenerPorId($T_Id);
        }
        else{
            $o_Log = new Logs_Equipo_O();
        }

        break;

    case 'add':
    case 'edit':

        // SEGURIDAD
        SeguridadHelper::Pasar(90);

        // GET LOG
        $o_Log = Logs_Equipo_L::obtenerPorId($T_Id);

        $editado = 1;

        // NEW LOG
        if (is_null($o_Log) || $T_Id == 0) {
            $o_Log = new Logs_Equipo_O();
            $editado = 0;
        }


        if(count(explode(':',$T_Hora))==2){
            $T_Hora = $T_Hora . ':00';   //le agrego los segundos
        }

        $fechaAnterior = $o_Log->getFechaHora('d-m-Y H:i:s');

        $o_Log->setFechaHora($T_Fecha.' '.$T_Hora, 'Y-m-d H:i:s');
        $o_Log->setEditado($editado);

        $o_Log->setLector(3);
        $o_Log->setEditadoPor(Registry::getInstance()->Usuario->getId());
        $o_Log->setAccion(1);


        if($editado){

            if($o_Log->save()) {
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_LOG_EDITAR, $a_Logs_Tipos[LOG_LOG_EDITAR],'<b>Id:</b> ' . $o_Log->getId(). ' <b>Persona: </b> ' .$o_Log->getPerId() . '<b><span class="labelFechaAnterior">Fecha Anterior:</span></b> ' . $fechaAnterior . ' <b><span class="labelFechaNueva">Fecha Nueva:</b> ' . $T_Fecha.' '.$T_Hora, $o_Log->getId());
                $T_Mensaje = _('El registro fue editado correctamente.');
            }
            else{
                $T_Error = $o_Log->getErrores();
            }

        }
        else{
            if($T_Persona == 'SelectRol'){
                $Grupos_Personas = Grupos_Personas_L::obtenerARRAYPorGrupo($T_Grupo);

                if(!is_null($Grupos_Personas)){

                    foreach($Grupos_Personas as $perId){
                        $o_Log->setPerId($perId);
                        $o_Log->save();
                        $o_Log->setId(0);
                    }

                    $T_Mensaje = _('Los registros fueron creados correctamente.');
                }
            }
            else{
                $o_Log->setPerId($T_Persona);

                if($o_Log->save()) {
                    $T_Mensaje = _('El registro fue creado correctamente.');
                }
                else{
                    $T_Error = $o_Log->getErrores();
                }


            }
        }

        break;



    default:

        $T_Intervalo = isset($_REQUEST['intervaloFecha']) ? (string) $_REQUEST['intervaloFecha'] : '';
        if(isset($T_Intervalo) && $T_Intervalo!=''){
            switch($T_Intervalo){
                case 'F_Hoy'://diario
                    $_SESSION['filtro']['fechaD']=date('Y-m-d H:i:s',strtotime('today 00:00'));
                    $_SESSION['filtro']['fechaH']=date('Y-m-d H:i:s',strtotime('tomorrow 00:00'));
                    break;
                case 'F_Ayer'://diario
                    $_SESSION['filtro']['fechaD']=date('Y-m-d H:i:s',strtotime('yesterday 00:00'));
                    $_SESSION['filtro']['fechaH']=date('Y-m-d H:i:s',strtotime('today 00:00'));
                    break;
                case 'F_Semana'://semana
                    $_SESSION['filtro']['fechaD']=date('Y-m-d H:i:s',strtotime('this week 00:00'));
                    $_SESSION['filtro']['fechaH']=date('Y-m-d H:i:s',strtotime('next week 00:00'));
                    break;
                case 'F_Semana_Pasada'://semana
                    $_SESSION['filtro']['fechaD']=date('Y-m-d H:i:s',strtotime('last week 00:00'));
                    $_SESSION['filtro']['fechaH']=date('Y-m-d H:i:s',strtotime('this week 00:00'));
                    break;
                case 'F_Quincena'://quincena
                    $primerDiadelMes=strtotime('first day of this month 00:00');
                    $ultimoDiadelMes=strtotime('first day of next month 00:00');
                    $mitadDelMes=strtotime('+15 days',$primerDiadelMes);
                    if(time()<$mitadDelMes){//primera quincena
                        $_SESSION['filtro']['fechaD']= date('Y-m-d H:i:s',$primerDiadelMes);
                        $_SESSION['filtro']['fechaH']= date('Y-m-d H:i:s',$mitadDelMes);
                    }
                    else {
                        $_SESSION['filtro']['fechaD']= date('Y-m-d H:i:s',$mitadDelMes);
                        $_SESSION['filtro']['fechaH']= date('Y-m-d H:i:s',$ultimoDiadelMes);
                    }
                    break;
                case 'F_Mes'://mes
                    $_SESSION['filtro']['fechaD']=date('Y-m-d H:i:s',strtotime('first day of this month 00:00'));
                    $_SESSION['filtro']['fechaH']=date('Y-m-d H:i:s',strtotime('first day of next month 00:00'));
                    break;
                case 'F_Mes_Pasado'://mes
                    $_SESSION['filtro']['fechaD']=date('Y-m-d H:i:s',strtotime('first day of last month 00:00'));
                    $_SESSION['filtro']['fechaH']=date('Y-m-d H:i:s',strtotime('first day of this month 00:00'));
                    break;
                case 'F_Ano'://mes
                    $_SESSION['filtro']['fechaD']=date('Y-m-d H:i:s',strtotime("first day of january " . date('Y') . " 00:00 "));
                    $_SESSION['filtro']['fechaH']=date('Y-m-d H:i:s',strtotime("first day of january " . date('Y') . " 00:00 +1 year"));
                    break;
                case 'F_Personalizado':
                    $_SESSION['filtro']['fechaD'] = (!isset($_POST['fechaD'])) ? (isset($_SESSION['filtro']['fechaD'])) ? $_SESSION['filtro']['fechaD'] : date('Y-m-d H:i:s', strtotime('-1 day'))  : $_POST['fechaD'];
                    $_SESSION['filtro']['fechaH'] = (!isset($_POST['fechaH'])) ? (isset($_SESSION['filtro']['fechaH'])) ? $_SESSION['filtro']['fechaH'] : date('Y-m-d H:i:s')  : $_POST['fechaH'];
                    break;
            }
        }else{//selecciono el dropdown si la fecha ya viene
            if($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime('today 00:00')) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime('tomorrow 00:00')))
                $T_Intervalo='F_Hoy';
            elseif($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime('yesterday 00:00')) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime('today 00:00')))
                $T_Intervalo='F_Ayer';
            elseif($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime('this week 00:00')) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime('next week 00:00')))
                $T_Intervalo='F_Semana';
            elseif($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime('last week 00:00')) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime('this week 00:00')))
                $T_Intervalo='F_Semana_Pasada';
            elseif($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime('first day of this month 00:00')) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime('+15 days',strtotime('first day of this month 00:00'))))
                $T_Intervalo='F_Quincena';
            elseif($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime('+15 days',strtotime('first day of this month 00:00'))) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime('first day of next month 00:00')))
                $T_Intervalo='F_Quincena';
            elseif($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime('first day of this month 00:00')) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime('first day of next month 00:00')))
                $T_Intervalo='F_Mes';
            elseif($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime('first day of last month 00:00')) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime('first day of this month 00:00')))
                $T_Intervalo='F_Mes_Pasado';
            elseif($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime("first day of january " . date('Y') . " 00:00 ")) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime("first day of january " . date('Y') . " 00:00 +1 year")))
                $T_Intervalo='F_Ano';
            else	$T_Intervalo='F_Personalizado';
        }


        if(!isset($_POST['equipoid'])){
            if(isset($_SESSION['filtro']['equipoid'])){
                $_SESSION['filtro']['equipoid'] = $_SESSION['filtro']['equipoid'];
            }else{
                $_SESSION['filtro']['equipoid'] =  0;
            }
        }  else {
            $_SESSION['filtro']['equipoid'] = $_POST['equipoid'];
        }


        if ((DateTimeHelper::getTimestampFromFormat($_SESSION['filtro']['fechaD'], 'Y-m-d H:i:s') !== false || $_SESSION['filtro']['fechaD'] == '') && DateTimeHelper::getTimestampFromFormat($_SESSION['filtro']['fechaH'], 'Y-m-d H:i:s') !== false) {

            if($_SESSION['filtro']['equipoid']!=0)
                $condicion = " leq_Eq_Id = '{$_SESSION['filtro']['equipoid']}' AND leq_Fecha_Hora >= '{$_SESSION['filtro']['fechaD']}' AND leq_Fecha_Hora <= '{$_SESSION['filtro']['fechaH']}' ";
            else
                $condicion = " leq_Fecha_Hora >= '{$_SESSION['filtro']['fechaD']}' AND leq_Fecha_Hora <= '{$_SESSION['filtro']['fechaH']}' ";


            $o_Listado = Logs_Equipo_L::obtenerTodosOSP($condicion,'DESC');

            //echo "<pre>";print_r($o_Listado);echo "</pre>";


        } else {
            $T_Error['error'] = _('Alguna de las fechas no es valida');
        }
        break;

}




$_SESSION['T_Mensaje']          = $T_Mensaje;
$_SESSION['T_Error']            = $T_Error;
