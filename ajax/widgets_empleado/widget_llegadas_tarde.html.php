<?php require_once dirname(__FILE__) . '/../../_ruta.php'; ?>

<?php
SeguridadHelper::Pasar(5);
$UsarFechasWidget = true;
$T_Titulo = _('Llegada Tarde por Semana');

$Fecha_Boton = Config_L::p('reset_llegadas_tarde');
$Fecha_Lunes = strtotime('Tuesday this week');

$T_SelIntervalo = (isset($_REQUEST['selIntervalo'])) ? $_REQUEST['selIntervalo'] : '';


if (Config_L::p('resetear_llegadas_tarde_los_martes')) {
    if ($Fecha_Lunes > strtotime($Fecha_Boton) && $Fecha_Lunes < strtotime(date('Y-m-d H:i:s'))) {
        $_SESSION['filtro']['fechaDw'] = date('Y-m-d H:i:s', $Fecha_Lunes);
    } else {
        $Fecha_Lunes = strtotime('Tuesday last week');
        if ($Fecha_Lunes > strtotime($Fecha_Boton))
            $_SESSION['filtro']['fechaDw'] = date('Y-m-d H:i:s', $Fecha_Lunes);
        else
            $_SESSION['filtro']['fechaDw'] = date('Y-m-d H:i:s', strtotime($Fecha_Boton));
    }
} else {
    $_SESSION['filtro']['fechaDw'] = date('Y-m-d H:i:s', strtotime($Fecha_Boton));
}

//$_SESSION['filtro']['fechaD'] =date('Y-m-d H:i:s',time()-60*60*24*5);

$_SESSION['filtro']['fechaHw'] = date('Y-m-d H:i:s');

$_SESSION['filtro']['persona'] = '';

$cantidad = 0;


$day = date('w');
$week_start = date('Y-m-d 00:00:00', strtotime('-' . $day . ' days'));
$week_end = date('Y-m-d 23:59:59', strtotime('+' . (6 - $day) . ' days'));

switch ($T_SelIntervalo) {
    case '':
    case 'hoy':
        $_SESSION['filtro']['fechaDw'] = date('Y-m-d 00:00:00');
        $_SESSION['filtro']['fechaHw'] = date('Y-m-d 23:59:59');
        break;
    case 'esta_semana':
        $_SESSION['filtro']['fechaDw'] = $week_start;
        $_SESSION['filtro']['fechaHw'] = $week_end;
        break;
}

$T_Tipo = 'mod_LLegada_Tarde';
$a_mod = null;
$cantidad_llegadas_tarde = 0;

include_once APP_PATH . '/includes/reportes_empleado.php';

if ($o_Listado) {
	$cantidad_llegadas_tarde = count($o_Listado);
	
    echo '<table id="dt_basic" class="table table-striped table-hover dataTable no-footer" aria-describedby="dt_basic_info" style="width: 100%;">';
    echo '<thead>';
    echo '<tr>';
		echo '<th  style="vertical-align:middle;width:40%;">';
		echo _('Nombre');
		echo '</th>';
        echo '<th  style="vertical-align:middle;width:30%;">';
        echo _('Hora');
        echo '</th>';
		echo '<th  style="vertical-align:middle;width:30%;">';
		echo _('Horario');
		echo '</th>';
    echo '</tr>';
    echo '</thead>';

    $key = 1;
    foreach ($o_Listado as $reng) {
        echo '<tr>';
            echo '<td style="vertical-align:middle;width:40%;">';
                echo mb_convert_case($reng['per_Apellido'] . ", " . $reng['per_Nombre'], MB_CASE_TITLE, "UTF-8");
            echo '</td>';
        
            echo '<td style="vertical-align:middle;width:30%;">';
                foreach($reng['Dias'] as $dia => $valor){
                    echo date('H:i:s ' , strtotime($valor[0]['Fecha_Hora_Inicio'])).$dias_red[date('w',strtotime($valor[0]['Fecha_Hora_Inicio']))].'<br>';
                }
            echo '</td>';

            echo '<td style="vertical-align:middle;width:30%;">';
                $str = mb_convert_case($reng['Hora_Trabajo_Detalle'] . '<br>', MB_CASE_TITLE, "UTF-8");
                echo $str;
            echo '</td>';
        echo '</tr>';
        $cantidad++;
        $key += 1;
    }
    echo '</table>';
} else {
    echo _('No hay llegadas tarde');
}

?>

<script type="text/javascript">
	$("#div_unread_count_llegadas_tarde").text("<?php echo $cantidad_llegadas_tarde; ?>");
    if (<?php echo $cantidad_llegadas_tarde; ?> > 0)
		$("#div_unread_count_llegadas_tarde").show();
    else
		$("#div_unread_count_llegadas_tarde").hide();
</script>


