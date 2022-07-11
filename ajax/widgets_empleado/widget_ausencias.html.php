<?php require_once dirname(__FILE__) . '/../../_ruta.php'; ?>

<?php
SeguridadHelper::Pasar(5);
$UsarFechasWidget = true;
$T_Titulo = _('Ausencias');

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

$_SESSION['filtro']['fechaHw'] = date('Y-m-d H:i:s');
$_SESSION['filtro']['persona'] = '';
$_SESSION['filtro']['rolF'] = '';

$cantidad = 0;

$day = date('w');
$week_start = date('Y-m-d 00:00:00', strtotime('-' . $day . ' days'));
$week_end = date('Y-m-d 23:59:59', strtotime('+' . (6 - $day) . ' days'));

/**
 * BEGIN OF CODE
 *
 * */
switch ($T_SelIntervalo) {
    case '':
    case 'hoy':
        $_SESSION['filtro']['fechaDw'] = date('Y-m-d 00:00:00');
        $_SESSION['filtro']['fechaHw'] = date('Y-m-d 23:59:59');
        $_SESSION['filtro']['fechaD'] = date('Y-m-d 00:00:00');
        $_SESSION['filtro']['fechaH'] = date('Y-m-d 23:59:59');
        break;
    case 'esta_semana':
        $_SESSION['filtro']['fechaDw'] = $week_start;
        $_SESSION['filtro']['fechaHw'] = $week_end;
        $_SESSION['filtro']['fechaD'] = $week_start;
        $_SESSION['filtro']['fechaH'] = $week_end;
        break;
}

$a_aus               = null;
$T_Tipo              = 'mod_Ausencias';
$cantidad_ausencias  = 0;
$o_Listado_Ausencias = array();

include_once APP_PATH . '/includes/reportes_empleado.php';


if ($o_Listado_Ausencias) {
    $cantidad_ausencias = count($o_Listado_Ausencias); ?>

    <div style="">
        <table id="dt_basic"
               class="table table-striped table-hover dataTable no-footer"
               aria-describedby="dt_basic_info"
               style="width: 100%;">

            <thead>
				<th data-priority="2"><?php echo _('Nombre') ?></th>
				<th data-priority="2"><?php echo _('Hora') ?></th>
				<th data-priority="2"><?php echo _('Horario') ?></th>
				<?php /*<th data-priority="2"><?php echo _('Detalle') ?></th> */ ?>
			</thead>

			<tbody class="addNoWrap">
				<?php if ($o_Listado_Ausencias): ?>

                    <?php foreach ($o_Listado_Ausencias as $per_Id => $item): ?>
                        <tr>
                            <!-- NOMBRE -->
                            <td style="vertical-align:middle;width:40%;">
                                <?php             echo mb_convert_case($item['per_Apellido'] . ", " . $item['per_Nombre'], MB_CASE_TITLE, "UTF-8"); ?>
                            </td>
                            <!-- HORA -->
                            <td style="vertical-align:middle;width:30%;">
                                <?php foreach ($item['Hora_Trabajo_Inicio'] as $key => $valor): ?>
                                    <div>
                                        <span class="">
                                            <?php echo date('H:i:s ', strtotime($valor) + $margen_ausencia) . $dias_red[date('w', strtotime($item['Ausencias'][$key]))]; ?>
                                        </span><br>
                                    </div>
                                <?php endforeach; ?>
                            </td>
                            <!-- HORARIO -->
                            <td style="vertical-align:middle;width:30%;">
                                <?php
                                $str = mb_convert_case($item['Hora_Trabajo_Detalle'] . '<br>', MB_CASE_TITLE, "UTF-8");
                                echo $str;
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

			</tbody>
		</table>
	</div>

<?php
}
else {
    echo _('No hay ausencias');
}

echo '<div id="CantAusencias" style="display:none;">' . $cantidad . '</div>';
?>

<!-- WIDGET TOTAL ICON -->
<script type="text/javascript">

    $("#div_unread_count_ausencias").text("<?php echo $cantidad_ausencias; ?>");

    if (<?php echo $cantidad_ausencias; ?> > 0)
        $("#div_unread_count_ausencias").show();
    else
        $("#div_unread_count_ausencias").hide();
		
</script>


