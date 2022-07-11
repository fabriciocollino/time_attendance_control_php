<?php require_once dirname(__FILE__) . '/../../_ruta.php'; ?>

<?php

$UsarFechasWidget = true;
SeguridadHelper::Pasar(5);

$T_Titulo               = _('Salidas Temprano');
$Fecha_Boton            = Config_L::p('reset_salidas_temprano');
$Fecha_Lunes            = strtotime('Tuesday this week');
$T_SelIntervalo         = (isset($_REQUEST['selIntervalo'])) ? $_REQUEST['selIntervalo'] : '';


if (Config_L::p('resetear_salidas_temprano_los_martes')) {
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

$_SESSION['filtro']['fechaHw']      = date('Y-m-d H:i:s');
$_SESSION['filtro']['persona']      = '';

$cantidad = 0;

$day = date('w');

switch ($T_SelIntervalo) {
    case '':
    case 'hoy':
        $_SESSION['filtro']['fechaDw'] = date('Y-m-d 00:00:00');
        $_SESSION['filtro']['fechaHw'] = date('Y-m-d 23:59:59');
        break;
    case 'esta_semana':
        $_SESSION['filtro']['fechaDw'] = date('Y-m-d 00:00:00', strtotime('-' . $day . ' days'));
        $_SESSION['filtro']['fechaHw'] = date('Y-m-d 23:59:59', strtotime('+' . (6 - $day) . ' days'));

        break;
}

$T_Tipo = 'mod_Salida_Temprano';
$a_mod = null;
$cantidad_salida = 0;

include_once APP_PATH . '/includes/reportes_empleado.php';

// LISTA SALIDAS TEMPRANO
if ($o_Listado) {
	$cantidad_salida = count($o_Listado);

    echo '<table id="dt_basic" class="table table-striped table-hover dataTable no-footer" aria-describedby="dt_basic_info" style="width: 100%;">';

    echo '<thead>';
    echo '<tr>';
    echo '<th class="">';
    echo _('Nombre');
    echo '</th>';
    echo '<th class="">';
    echo _('Hora');
    echo '</th>';
    echo '<th class="">';
    echo _('Horario');
    echo '</th>';
    echo '</tr>';
    echo '</thead>';

    $key = 1;
    foreach ($o_Listado as $reng) {
        echo '<tr>';

        echo '<td  style="vertical-align:middle;width:40%;">';
        echo mb_convert_case($reng['per_Apellido'] . ", " . $reng['per_Nombre'], MB_CASE_TITLE, "UTF-8");
        echo '</td>';

        echo '<td style="vertical-align:middle;width:30%;">';
        foreach ($reng['Dias'] as $dia => $valor) {
            echo date('H:i:s ', strtotime($valor[0]['Fecha_Hora_Fin'])) . $dias_red[date('w', strtotime($valor[0]['Fecha_Hora_Fin']))] . '<br>';
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
}

// NO HAY SALIDAS TEMPRANO
else {
    echo _('No hay salidas temprano');
}

?>

<script type="text/javascript">
	$("#div_unread_count_salidas_temprano").text("<?php echo $cantidad_salida; ?>");
    if (<?php echo $cantidad_salida; ?> > 0)
		$("#div_unread_count_salidas_temprano").show();
    else
		$("#div_unread_count_salidas_temprano").hide();


</script>


