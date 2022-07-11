<?php

$T_Mensaje          = '';
$Botones_Exportar   = true;
$Item_Name          = "reporte";


/* VARIABLES */
$T_Tipo                                 =   isset ($_SESSION['filtro']['tipo'] )        ?       $_SESSION['filtro']['tipo']                     :   '';
$T_Intervalo                            =   !isset($_REQUEST['intervaloFecha'])         ?       (isset($_SESSION['filtro']['intervalo']))       ?   $_SESSION['filtro']['intervalo']            :    'F_Hoy'       :     $_REQUEST['intervaloFecha'];
$T_Guardar                              =   "Descargar";
$T_Persona                              =   !isset($_POST['persona'])                   ? isset($_SESSION['filtro']['persona'])             ?   $_SESSION['filtro']['persona']      :     0       :     $_POST['persona'];
$T_Grupo                                =   !isset($_POST['rolF'])                      ? isset($_SESSION['filtro']['rolF'])             ?   $_SESSION['filtro']['rolF']            :     0       :     $_POST['rolF'];



$T_Filtro_Activos                       =   isset($_REQUEST['activos'])                 ?  $_REQUEST['activos']                 :       0   ;
$T_Filtro_Activos                       =   $T_Filtro_Activos == ('on' || 1)            ?   1                                   :       0   ;


/* VARIABLES PROXIMA CARGA */
$_SESSION['filtro']['tipo']             =   $T_Tipo;
$_SESSION['filtro']['intervalo']        =   $T_Intervalo;
$_SESSION['filtro']['rolF']             =   $T_Grupo;
$_SESSION['filtro']['bloqueados']       =   !isset($_POST['bloqueados'])                ?       (isset($_SESSION['filtro']['bloqueados']))      ?   $_SESSION['filtro']['bloqueados']               :     ''      :     $_POST['bloqueados'];



/* NUEVO REPORTE */
$T_Filtro_Array         =   Filtro_L::get_filtro_persona($_POST,$_SESSION);
$o_Reporte              =   new Reporte_O($T_Tipo,$T_Intervalo, $T_Filtro_Array);//, $T_Intervalo, $T_Persona, $T_Grupo);
$o_Listado              =   $o_Reporte->generar_reporte();


unset($o_Reporte);

$_SESSION['filtro']['tipo_data']             =   $T_Tipo;
$_SESSION['filtro']['persona_data']          =   $T_Filtro_Array;
$_SESSION['filtro']['intervalo_data']        =   $T_Intervalo;

//printear($o_Listado);