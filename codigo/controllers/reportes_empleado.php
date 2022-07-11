<?php

$T_Titulo  = _('Control de Personal');
$Item_Name = 'reportes';

$T_Mensaje  = '';
$T_Paginado = 'No';

//para los reportes abduls
$Botones_Exportar = true;  // comment by abduls
$Reporte_Tipo     = '';
$Reporte_Nombre   = '';



if (!isset ($_SESSION['ctrl']['edicion_de_logs_habilitada'])) {
    $_SESSION['ctrl']['edicion_de_logs_habilitada'] = false;
}

// TIPO
if (!isset ($T_Tipo))    {
    $T_Tipo     =   (isset($_REQUEST['tipo']))        ?       $_REQUEST['tipo']       :   '';
}

if(!isset($T_Accion))
    $T_Accion   =   (isset($_REQUEST['accion']))      ?       $_REQUEST['accion']     :   '';

//rahull for always unlock the lock in reporte_entradas_salidas
//$T_Accion='unlockEdit';
//end----

$template = $T_Template = (isset  ($_REQUEST['template'])) ? $_REQUEST['template'] : '';

if ($template == '')
    $template = 'compacto';

$_SESSION['filtro']['csv'] = '';
$_SESSION['filtro']['pdf'] = array();



// ACTIVOS
if (isset($_REQUEST['activos'])) {
    $_SESSION['filtro']['activos'] = $_REQUEST['activos'];
} else {
    $_SESSION['filtro']['activos'] = (integer)0;
    $_REQUEST['activos']           = (integer)0;
}
// FECHA DESDE
if (!isset($_SESSION['filtro']['fechaD'])){
    $_SESSION['filtro']['fechaD'] = date('Y-m-d H:i:s', strtotime('today 00:00:00'));
}
// FECHA H
if (!isset($_SESSION['filtro']['fechaH'])){
    $_SESSION['filtro']['fechaH'] = date('Y-m-d H:i:s', strtotime('tomorrow 00:00:00'));
}
// PERSONA
if (isset($_GET['id']))     { // valor de los enlaces del encabezado de las tablas
    $_SESSION['filtro']['persona'] = $_GET['id'];
}
// PERSONA, FECHAD, FECHAH
if (isset($_GET['mod_id'])) { // valores para el modulo de llegadas tarde
    $_SESSION['filtro']['persona'] = $_GET['mod_id'];
    $_SESSION['filtro']['fechaD']  = date('Y-m-d H:i:s', strtotime('-7 day'));   //que hace esto?
    $_SESSION['filtro']['fechaH']  = date('Y-m-d H:i:s');
}
// FECHA D
if (isset($_GET['desde']))  {
    $_SESSION['filtro']['fechaD'] = $_GET['desde'];
}
// FECHA H
if (isset($_GET['hasta']))  {
    $_SESSION['filtro']['fechaH'] = $_GET['hasta'];
}



// abduls
if(isset($_SESSION['Previous_URL']) && $_SESSION['Previous_URL'] == $_SERVER['REQUEST_URI'])
    $F_Flag ='';
else
    $F_Flag ='F_Semana';
//pred($_SESSION);

$_SESSION['Previous_URL'] = $_SERVER['REQUEST_URI'];

$T_Intervalo = isset($_REQUEST['intervaloFecha']) ? (string) $_REQUEST['intervaloFecha'] : $F_Flag;


//$T_Intervalo
if (isset($T_Intervalo) && ($T_Intervalo != '')) {
    switch ($T_Intervalo) {
        case 'F_Hoy'://diario
            $_SESSION['filtro']['fechaD'] = date('Y-m-d H:i:s', strtotime('today 00:00:00'));
            $_SESSION['filtro']['fechaH'] = date('Y-m-d H:i:s', strtotime('today 23:59:59'));
            break;
        case 'F_Ayer'://diario
            $_SESSION['filtro']['fechaD'] = date('Y-m-d H:i:s', strtotime('yesterday 00:00:00'));
            $_SESSION['filtro']['fechaH'] = date('Y-m-d H:i:s', strtotime('yesterday 23:59:59'));
            break;
        case 'F_Semana'://semana
            $_SESSION['filtro']['fechaD'] = date('Y-m-d H:i:s', strtotime('monday this week 00:00:00'));
            $_SESSION['filtro']['fechaH'] = date('Y-m-d H:i:s', strtotime('sunday this week 23:59:59'));
            break;
        case 'F_Semana_Pasada'://semana
            $_SESSION['filtro']['fechaD'] = date('Y-m-d H:i:s', strtotime('monday last week 00:00:00'));
            $_SESSION['filtro']['fechaH'] = date('Y-m-d H:i:s', strtotime('sunday last week 23:59:59'));
            break;
        case 'F_Quincena'://quincena
            $primerDiadelMes = strtotime('first day of this month 00:00:00');
            $ultimoDiadelMes = strtotime('first day of next month 00:00:00');
            $mitadDelMes     = strtotime('+15 days', $primerDiadelMes);
            if (time() < $mitadDelMes) {//primera quincena
                $_SESSION['filtro']['fechaD'] = date('Y-m-d H:i:s', $primerDiadelMes);
                $_SESSION['filtro']['fechaH'] = date('Y-m-d H:i:s', $mitadDelMes);
            }
            else {
                $_SESSION['filtro']['fechaD'] = date('Y-m-d H:i:s', $mitadDelMes);
                $_SESSION['filtro']['fechaH'] = date('Y-m-d H:i:s', $ultimoDiadelMes);
            }
            break;

        case 'F_Mes'://mes
            $_SESSION['filtro']['fechaD'] = date('Y-m-d H:i:s', strtotime('first day of this month 00:00:00'));
            $_SESSION['filtro']['fechaH'] = date('Y-m-d H:i:s', strtotime('first day of next month 00:00:00'));
            break;
        case 'F_Mes_Pasado'://mes
            $_SESSION['filtro']['fechaD'] = date('Y-m-d H:i:s', strtotime('first day of last month 00:00:00'));
            $_SESSION['filtro']['fechaH'] = date('Y-m-d H:i:s', strtotime('first day of this month 00:00:00'));
            break;

        case 'F_Ano'://mes
            $_SESSION['filtro']['fechaD'] = date('Y-m-d H:i:s', strtotime("first day of january " . date('Y') . " 00:00:00 "));
            $_SESSION['filtro']['fechaH'] = date('Y-m-d H:i:s', strtotime("first day of january " . date('Y') . " 00:00:00 +1 year"));
            break;

        case 'F_Personalizado':
            $_SESSION['filtro']['fechaD'] = (!isset($_POST['fechaD'])) ? (isset($_SESSION['filtro']['fechaD'])) ? $_SESSION['filtro']['fechaD'] : date('Y-m-d H:i:s', strtotime('-1 day')) : $_POST['fechaD'];
            $_SESSION['filtro']['fechaH'] = (!isset($_POST['fechaH'])) ? (isset($_SESSION['filtro']['fechaH'])) ? $_SESSION['filtro']['fechaH'] : date('Y-m-d H:i:s') : $_POST['fechaH'];
            break;
    }
}
else {//selecciono el dropdown si la fecha ya viene
    if ($_SESSION['filtro']['fechaD'] == date('Y-m-d H:i:s', strtotime('today 00:00:00')) && $_SESSION['filtro']['fechaH'] == date('Y-m-d H:i:s', strtotime('today 23:59:59')))
        $T_Intervalo = 'F_Hoy';
    elseif ($_SESSION['filtro']['fechaD'] == date('Y-m-d H:i:s', strtotime('yesterday 00:00:00')) && $_SESSION['filtro']['fechaH'] == date('Y-m-d H:i:s', strtotime('yesterday 23:59:59')))
        $T_Intervalo = 'F_Ayer';
    elseif ($_SESSION['filtro']['fechaD'] == date('Y-m-d H:i:s', strtotime('monday this week 00:00:00')) && $_SESSION['filtro']['fechaH'] == date('Y-m-d H:i:s', strtotime('sunday this week 23:59:59')))
        $T_Intervalo = 'F_Semana';
    elseif ($_SESSION['filtro']['fechaD'] == date('Y-m-d H:i:s', strtotime('monday last week 00:00:00')) && $_SESSION['filtro']['fechaH'] == date('Y-m-d H:i:s', strtotime('sunday last week 23:59:59')))
        $T_Intervalo = 'F_Semana_Pasada';
    elseif ($_SESSION['filtro']['fechaD'] == date('Y-m-d H:i:s', strtotime('first day of this month 00:00:00')) && $_SESSION['filtro']['fechaH'] == date('Y-m-d H:i:s', strtotime('+15 days', strtotime('first day of this month 00:00:00'))))
        $T_Intervalo = 'F_Quincena';
    elseif ($_SESSION['filtro']['fechaD'] == date('Y-m-d H:i:s', strtotime('+15 days', strtotime('first day of this month 00:00:00'))) && $_SESSION['filtro']['fechaH'] == date('Y-m-d H:i:s', strtotime('first day of next month 00:00:00')))
        $T_Intervalo = 'F_Quincena';
    elseif ($_SESSION['filtro']['fechaD'] == date('Y-m-d H:i:s', strtotime('first day of this month 00:00:00')) && $_SESSION['filtro']['fechaH'] == date('Y-m-d H:i:s', strtotime('first day of next month 00:00:00')))
        $T_Intervalo = 'F_Mes';
    elseif ($_SESSION['filtro']['fechaD'] == date('Y-m-d H:i:s', strtotime('first day of last month 00:00:00')) && $_SESSION['filtro']['fechaH'] == date('Y-m-d H:i:s', strtotime('first day of this month 00:00:00')))
        $T_Intervalo = 'F_Mes_Pasado';
    elseif ($_SESSION['filtro']['fechaD'] == date('Y-m-d H:i:s', strtotime("first day of january " . date('Y') . " 00:00:00 ")) && $_SESSION['filtro']['fechaH'] == date('Y-m-d H:i:s', strtotime("first day of january " . date('Y') . " 00:00:00 +1 year")))
        $T_Intervalo = 'F_Ano';
    else    $T_Intervalo = 'F_Personalizado';
}

switch ($T_Tipo) {
    case 'Tarde':
        $T_Titulo                         = _('Llegadas Tarde');
        $_SESSION['filtro']['csv_nombre'] = _('Llegadas_Tarde');
        $Reporte_Tipo                     = 19;
        $Reporte_Nombre                   = _('Reporte_Llegadas_tarde') . ' - ' . date(Config_L::p('f_fecha_archivos'), time()) . '.pdf';
        $paginado                         = 'No';
        break;
    case 'Temprano':
        $T_Titulo                         = _('Salidas Temprano');
        $_SESSION['filtro']['csv_nombre'] = _('Salidas_Temprano');
        $Reporte_Tipo                     = 23;
        $Reporte_Nombre                   = _('Reporte_Salidas_Temprano') . ' - ' . date(Config_L::p('f_fecha_archivos'), time()) . '.pdf';
        $paginado                         = 'No';
        break;
    case 'Ausencias':
        $T_Titulo                         = _('Ausencias');
        $_SESSION['filtro']['csv_nombre'] = _('Reporte_Ausencias');
        $Reporte_Tipo                     = 22;
        $Reporte_Nombre                   = _('Reporte_Ausencias') . ' - ' . date(Config_L::p('f_fecha_archivos'), time()) . '.pdf';
        $paginado                         = 'No';
        break;
    case 'Entradas':
        $T_Titulo                         = _('Entradas y Salidas por Persona');
        $_SESSION['filtro']['csv_nombre'] = _('Entradas_Salidas_por_Persona');
        $Reporte_Tipo                     = 20;
        $Reporte_Nombre                   = _('Reporte_Entradas_Salidas') . ' - ' . date(Config_L::p('f_fecha_archivos'), time()) . '.pdf';
        $paginado                         = 'No';
        break;
    case 'Dias':
        $T_Titulo                         = _('Días y Horas Trabajadas');
        $_SESSION['filtro']['csv_nombre'] = _('Dias_Horas_Trabajadas'); // abduls / name change
        $Reporte_Tipo                     = 21;
        $Reporte_Nombre                   = _('Reporte_Dias-Horas_Trabajadas') . ' - ' . date(Config_L::p('f_fecha_archivos'), time()) . '.pdf';
        $paginado                         = 'No';
        break;
    case 'Payroll_Por_Dia':
        $T_Titulo                         = _('Payroll_Por_Dia');
        $_SESSION['filtro']['csv_nombre'] = _('Payroll_Por_Dia'); // abduls / name change
        $Reporte_Tipo                     = 26;
        $Reporte_Nombre                   = _('Reporte_Payroll_Por_Dia') . ' - ' . date(Config_L::p('f_fecha_archivos'), time()) . '.pdf';
        $paginado                         = 'No';
        break;
    case 'Payroll':
        $T_Titulo                         = _('Liquidacion de Salarios');
        $_SESSION['filtro']['csv_nombre'] = _('Payroll');
        $Reporte_Tipo                     = 25;
        $Reporte_Nombre                   = _('Reporte_Payroll') . ' - ' . date(Config_L::p('f_fecha_archivos'), time()) . '.pdf';
        $paginado                         = 'No';
        break;
    case 'Feriados':
        $T_Titulo                         = _('Reporte de Feriados');
        $_SESSION['filtro']['csv_nombre'] = _('Reporte_de_Feriados');
        $Reporte_Tipo                     = 24;
        $Reporte_Nombre                   = _('Reporte_de_Feriados') . ' - ' . date(Config_L::p('f_fecha_archivos'), time()) . '.pdf';
        $paginado                         = 'No';
        break;
    default:
        $T_Tipo = 'L_Dia';
        SeguridadHelper::Pasar(5);
        $T_Link    = '';
        $o_Listado = null;
        $paginado = 'No';
        break;
}

include_once APP_PATH . '/includes/reportes_empleado.php';
