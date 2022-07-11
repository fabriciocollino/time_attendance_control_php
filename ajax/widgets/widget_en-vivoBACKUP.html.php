<?php require_once dirname(__FILE__) . '/../../_ruta.php'; ?>

<?php

$UsarFechasWidget                   = true;
$T_Titulo                           = _('en Vivo');


$_SESSION['filtro']['fechaHw']      = date('Y-m-d H:i:s');
$_SESSION['filtro']['persona']      = '';

// FILTRO ACTIVOS
$_SESSION ['filtro']['activos']     = (integer)1;

// FILTRO INTERVALOS
$T_SelIntervalo                     = (isset($_REQUEST['selIntervalo'])) ? $_REQUEST['selIntervalo'] : '';
$_SESSION['filtro']['fechaDw']      = date('Y-m-d 00:00:00');
$_SESSION['filtro']['fechaHw']      = date('Y-m-d 23:59:59');

$day                                = date('w');
$week_start                         = date('Y-m-d 00:00:00', strtotime('-' . $day . ' days'));
$week_end                           = date('Y-m-d 23:59:59', strtotime('+' . (6 - $day) . ' days'));


/**
 * BEGIN OF CODE
 *
 * */
$o_Logs                             = null;
$o_Grupos_Filtro                    = null;
$array_personas                     = array();
$a_Personas                         = Registry::getInstance()->DbConn->Select_Lista_assocID('personas', 'per_Eliminada=0', 'per_Id,per_Legajo,per_Nombre,per_Apellido', 'per_Id');

//ARRAY $o_Grupos_Filtro
$T_SelFiltro                        = (isset($_REQUEST['selFiltro'])) ? $_REQUEST['selFiltro'] : '';

switch ($T_SelFiltro) {
    case '':
    case 'grupos':

        $o_Grupos_enVivo = Grupo_L::obtenerTodosEnVivo();
        if (!empty($o_Grupos_enVivo)) {

            $listado_personas = '';

            foreach ($o_Grupos_enVivo as $grupo) {

                $grupoID                  = $grupo->getId();
                $personas                 = Grupos_Personas_L::obtenerARRAYPorGrupo($grupoID);
                $array_personas[$grupoID] = array();

                // PERSONAS POR GRUPO
                if (!is_null($personas[0])) {
                    $array_personas[$grupoID] = $personas;
                    $listado_personas         .= implode(',', array_map('intval', $personas)) . ',';    //array_map devuelve un array con los valores enteros de los ID de las personas en los grupos y implode lo transforma en string separado con coma
                }
            }

            // LOGS
            if ($listado_personas != '') {
                $listado_personas = rtrim($listado_personas, ',');
                $o_Logs           = Logs_Equipo_L::obtenerCantidadesEnArray($listado_personas, $_SESSION['filtro']['fechaDw'], $_SESSION['filtro']['fechaHw']);
            }
        }
        $o_Grupos_Filtro = $o_Grupos_enVivo;
        unset($o_Grupos_enVivo);

        break;
    case 'equipos':

        $o_Equipos = Equipo_L::obtenerTodosenArray();

        if (!empty($o_Equipos)) {

            $listado_personas = '';

            foreach ($o_Equipos as $equipo) {

                $equipoID = $equipo->getId();

                // DATOS PARA CONSULTA SQL
                $p_tabla     = 'personas';
                $p_condicion = "(per_equipos REGEXP '^{$equipoID}$' OR per_equipos REGEXP '^{$equipoID}:' OR  per_equipos REGEXP ':{$equipoID}$' OR  per_equipos REGEXP ':{$equipoID}:')";
                $p_key       = 'per_Id';

                // CONSULTA SQL
                $cnn                       = Registry::getInstance()->DbConn;
                $personas                  = $cnn->Select_Lista_IDs($p_tabla, $p_condicion, $p_key);
                $array_personas[$equipoID] = array();

                // PERSONAS POR EQUIPO
                if (!is_null($personas)) {
                    $array_personas[$equipoID] = $personas;
                    $listado_personas          .= implode(',', array_map('intval', $personas)) . ',';    //array_map devuelve un array con los valores enteros de los ID de las personas en los grupos y implode lo transforma en string separado con coma
                }

            }
            // LOGS
            if ($listado_personas != '') {
                $listado_personas = rtrim($listado_personas, ',');
                $o_Logs           = Logs_Equipo_L::obtenerCantidadesEnArray($listado_personas, $_SESSION['filtro']['fechaDw'], $_SESSION['filtro']['fechaHw']);
            }
        }
        $o_Grupos_Filtro = $o_Equipos;
        unset($o_Equipos);
        break;
}


if (!is_null($o_Grupos_Filtro)) {
//$total_en_vivo       = 0;
    foreach ($o_Grupos_Filtro as $grupo => $item) {

        $grupoID = $item->getId();

        $array_Presentes[$grupoID] = array();
        $array_Ausentes[$grupoID]  = array();
        //$array_Retiradas      = array();

        if (is_null($o_Logs)) {
            $array_Ausentes[$grupoID] = $array_personas[$grupoID];
            continue;
        }

        foreach ($o_Logs as $log => $_log) {

            $perlogID              = $_log['leq_Per_Id'];
            $array_logs[$perlogID] = $_log;

            // PERSONA NO ESTA EN EL GRUPO //
            if (!in_array($perlogID, $array_personas[$grupoID])) continue;


            // RETIRADA - LOG DE SALIDA //
            if ($_log['cantidades'] % 2 == 0) {
                //$array_Retiradas[] = $perlogID;
                continue;
            }

            switch ($T_SelFiltro) {

                case 'grupos':
                    $array_Presentes[$grupoID][] = $perlogID;
                    break;
                case 'equipos':
                    if ($_log['leq_Eq_Id'] == $grupoID) {
                        $array_Presentes[$grupoID][] = $perlogID;
                        unset($o_Logs[$log]);
                    }
                    break;

            }

        }
        // AUSENTES //
        $array_Ausentes[$grupoID] = array_diff($array_personas[$grupoID], $array_Presentes[$grupoID]);//,$array_Retiradas);
    }
}


// TABLA DE PRESENTES, AUSENTES, RETIRADAS
if (!is_null($o_Grupos_Filtro)): ?>
    <table id="table_en_vivo"
           class="table table-striped table-hover dataTable no-footer"
           aria-describedby="dt_basic_info"
           style="width: 100%;">

        <tbody class="addNoWrap">
        <?php
        foreach ($o_Grupos_Filtro as $grupo => $item):?>
            <!-- CALCULO DE TOTALES -->
            <?php
            $grupoID     = $item->getId();
            $grupoNombre = $item->getDetalle();


            // TOTALES //
            $total_Personas  = count($array_personas[$grupoID]);
            $total_Presentes = count($array_Presentes[$grupoID]);
            $total_Ausentes  = $total_Personas - $total_Presentes;
            //$total_Retiradas = count($array_Retiradas);


            ?>


            <!-- GRUPO NOMBRE, TOTALES, STATUS ICONS -->
            <tr data-id=" <?php echo $grupoID; ?>"
                data-parent="">

                <!-- GRUPO NOMBRE -->
                <td class=""
                    style="vertical-align:middle;width:25%">
                    <?php echo $grupoNombre; ?>
                </td>

                <td style="vertical-align:middle;width:50%">
                </td>
                <!-- TOTALES -->
                <td class="dashboard-icon-count-column"
                    style="vertical-align:middle;width:20%">
                    <?php
                    if ($total_Presentes > 0) {
                        echo "<span title=\"Personas presentes\" class=\"badge bg-color-greenLight\">" . $total_Presentes, '/' . $total_Personas . "</span>";
                    }
                    else {
                        echo "<span title=\"Personas presentes\" class=\"badge bg-color-red\">" . $total_Personas . "</span>";
                    } ?>
                </td>

                <!-- STATUS ICONS -->
                <td class="dashboard-status-icons dashboard-icon-column;"
                    style="vertical-align:middle;width:5%">
                </td>

            </tr>


            <!-- GRUPO SIN PERSONAS -->
            <?php if ($total_Personas == 0) { ?>

                <tr data-parent="<?php echo $grupoID; ?>" style="display: none;">

                    <td></td>
                    <td style="color:lightgrey;vertical-align:middle;width:50%">
                        <?php echo 'No hay personas' ?>
                    <td></td>
                    <td></td>

                </tr>
                <?php continue;
            }


            // PRESENTES -->
            if ($total_Presentes > 0) {
                foreach ($array_Presentes[$grupoID] as $presenteID) { ?>
                    <tr data-parent="<?php echo $grupoID; ?>" style="display: none;">
                        <!-- CONTINUE IF NO PERSON DATA AVAILABLE-->
                        <?php
                        //if (!array_key_exists($presenteID, $a_Personas)) continue;
                        ?>

                        <!-- LEGAJO -->
                        <td style="padding-left: 5%; color:grey;vertical-align:middle;width:25%">
                            <?php
                            $_legajo = $a_Personas[$presenteID]['per_Legajo'];
                            echo mb_convert_case($_legajo, MB_CASE_TITLE, "UTF-8"); ?>
                        </td>
                        <!-- APELLIDO, NOMBRE -->
                        <td style="vertical-align:middle;color:grey;width:50%">
                            <?php
                            $_name = $a_Personas[$presenteID]['per_Apellido'] . ', ' . $a_Personas[$presenteID]['per_Nombre'];
                            echo mb_convert_case($_name, MB_CASE_TITLE, "UTF-8"); ?>
                        </td>
                        <!-- EMPTY DATA -->
                        <td style="vertical-align:middle;color:grey;width:20%">
                            <?php
                            $_hora = date("H:i", strtotime($array_logs[$presenteID]['leq_Fecha_Hora']));
                            echo mb_convert_case($_hora, MB_CASE_TITLE, "UTF-8"); ?>
                        </td>
                        <td style="vertical-align:middle;width:5%">
                        </td>

                    </tr> <?php
                }
            }
            ?>


            <!-- AUSENTES -->
            <?php if ($total_Ausentes > 0) {
                foreach ($array_Ausentes[$grupoID] as $ausenteID) {
                    ?>
                    <tr data-parent="<?php echo $grupoID; ?>" style="display: none;">
                        <!-- CONTINUE IF NO PERSON DATA AVAILABLE-->
                        <?php
                        //if (!array_key_exists($ausenteID, $a_Personas)) continue;
                        ?>

                        <!-- LEGAJO -->
                        <td style="padding-left: 5%; color:lightgrey;vertical-align:middle;width:25%">
                            <?php
                            $_legajo = $a_Personas[$ausenteID]['per_Legajo'];
                            echo mb_convert_case($_legajo, MB_CASE_TITLE, "UTF-8"); ?>
                        </td>
                        <!-- APELLIDO, NOMBRE -->
                        <td style="vertical-align:middle;color:lightgrey;width:50%">
                            <?php
                            $_name = $a_Personas[$ausenteID]['per_Apellido'] . ', ' . $a_Personas[$ausenteID]['per_Nombre'];
                            echo mb_convert_case($_name, MB_CASE_TITLE, "UTF-8"); ?>
                        </td>
                        <td style="vertical-align:middle;width:20%">
                        </td>
                        <td style="vertical-align:middle;width:5%">
                        </td>
                    </tr> <?php
                }
            } ?>


        <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    No hay ningún grupo para mostrar en vivo
<?php endif; ?>


<script>
    $(function () {
        $('[rel=popover-hover],[data-rel="popover-hover"]').popover({"trigger": "hover"});
    });

    function iniciar_collaptable() {
        $('#table_en_vivo').aCollapTable({
            startCollapsed: true,
            addColumn: false,
            plusButton: '<i class="fa fa-plus-square-o"></i> ',
            minusButton: '<i class="fa fa-minus-square-o"></i> '
        });
    }

    setTimeout(iniciar_collaptable, 1200);


</script>

<!-- WIDGET TOTAL ICON -->
<script type="text/javascript">

    //$("#div_unread_count_en_vivo").text("<?php //echo $total_en_vivo; ?>");
    //if (<?php //echo $total_en_vivo; ?> >0) $("#div_unread_count_en_vivo").show();
    //else $("#div_unread_count_en_vivo").hide();

</script>