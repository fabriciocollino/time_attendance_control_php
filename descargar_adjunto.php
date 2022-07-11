<?php
require_once(dirname(__FILE__) . '/_ruta.php');

/* VARIABLES */
$T_Filtro_Tipo              = isset($_SESSION['filtro']['tipo_data'])           ?   $_SESSION['filtro']['tipo_data']        : "";
$T_Filtro_Intervalo         = isset($_SESSION['filtro']['intervalo_data'])      ?   $_SESSION['filtro']['intervalo_data']   : "";
$T_Filtro_Persona           = isset($_SESSION['filtro']['persona_data'])        ?   $_SESSION['filtro']['persona_data']     : "";
$T_Archivo_Tipo             = isset($_REQUEST['archivo_tipo'])                  ?   $_REQUEST['archivo_tipo']               : 'csv';



/* NUEVO REPORTE */
$o_Reporte                  = new Reporte_O($T_Filtro_Tipo,$T_Filtro_Intervalo, $T_Filtro_Persona);//, $T_Intervalo, $T_Persona, $T_Grupo);
$a_data                     = $o_Reporte->generar_reporte();
$csv_excel_data             = $o_Reporte->generar_csv_excel_data($a_data);

$url_adjunto                = $o_Reporte->generar_adjunto($a_data, $csv_excel_data, "Descargar",$T_Archivo_Tipo);

unset($o_Reporte);
unset($a_data);
unset($csv_excel_data);


