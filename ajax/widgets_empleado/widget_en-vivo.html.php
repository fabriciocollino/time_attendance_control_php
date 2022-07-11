<?php require_once dirname(__FILE__) . '/../../_ruta.php'; ?>

<?php
SeguridadHelper::Pasar(5);
$UsarFechasWidget = true;
$T_Titulo = _('en Vivo');


$_SESSION['filtro']['fechaHw'] = date('Y-m-d H:i:s');
$_SESSION['filtro']['persona'] = '';

// FILTRO ACTIVOS
$_SESSION ['filtro']['activos'] = (integer)1;

// FILTRO INTERVALOS
$T_SelIntervalo                = (isset($_REQUEST['selIntervalo'])) ? $_REQUEST['selIntervalo'] : '';

$_SESSION['filtro']['fechaDw'] = date('Y-m-d 00:00:00');
$_SESSION['filtro']['fechaHw'] = date('Y-m-d 23:59:59');

$day = date('w');
$week_start = date('Y-m-d 00:00:00', strtotime('-' . $day . ' days'));
$week_end = date('Y-m-d 23:59:59', strtotime('+' . (6 - $day) . ' days'));


/**
 * BEGIN OF CODE
 *
 * */
$o_Logs         =   array();

$o_Persona      =   Persona_L::obtenerPorUsuarioActual();

$o_Equipos      =   Equipo_L::obtenerTodosenArray();

if(!is_null($o_Persona)){
    $o_Logs     =   Logs_Equipo_L::obtenerPorPersonaYFecha($o_Persona['per_Id'], $_SESSION['filtro']['fechaDw']);
}

?>


<table id="table_en_vivo"
       class="table table-striped table-hover dataTable no-footer"
       aria-describedby="dt_basic_info"
       style="width: 100%;">

    <tbody class="addNoWrap">

            <!-- TOTAL LOGS -->
            <?php
                $total_logs  = count($o_Logs);
            ?>


            <!-- NO LOGS -->
            <?php if ($total_logs == 0) { ?>

                <tr>
                    <td></td>
                    <td style="color:lightgrey;vertical-align:middle;">
                        <?php echo 'No hay marcaciones' ?>
                    </td>
                    <td></td>

                </tr>

            <?php } ?>

            <!-- LOGS -->
            <?php if ($total_logs > 0) {

                foreach ($o_Logs as $_key => $_log) { ?>
                    <tr>


                        <?php /*
                            <!-- LEGAJO -->
                            <td style="padding-left: 5%; color:grey;vertical-align:middle;width:20%">
                                <?php
                                $_legajo = $o_Persona->getLegajo();
                                echo mb_convert_case($_legajo, MB_CASE_TITLE, "UTF-8"); ?>
                            </td>
                        */?>

                        <!-- READER -->
                        <?php $_lectorId = $_log->getLector(); ?>
                        <?php switch ($_lectorId){
                            case 1:?>
                                <td style="vertical-align:middle;color:grey;width:10%"">
                                <i class="fa fp_back_small"></i>
                                </td>
                                <?php break;

                            case 2:?>
                                <td style="vertical-align:middle;color:grey;width:10%"">
                                <i class="fa fa-tag tag_back_fa_marging"></i>
                                </td>
                                <?php break;

                            case 3:?>
                                <td style="vertical-align:middle;color:grey;width:10%"">
                                <i class="fa fa-desktop tag_back_fa_marging"></i>
                                </td>
                                <?php break;

                            default:?>
                                <td style="vertical-align:middle;color:grey;width:10%"">
                                </td>
                                <?php break;
                        } ?>


                        <!-- IN/OUT -->
                        <td style="padding-left: 5%; color:grey;vertical-align:middle;width:20%">
                            <?php
                            $_InOut = "";
                            if ($_key % 2 == 0) {
                                $_InOut = "Entrada";
                            }
                            else{
                                $_InOut = "Salida";
                            }

                            echo $_InOut ?>
                        </td>


                        <!-- APELLIDO, NOMBRE -->
                        <td style="vertical-align:middle;color:grey;width:30%">
                            <?php
                            $_name = $o_Persona['per_Apellido'] . ', ' . $o_Persona['per_Nombre'];
                            echo mb_convert_case($_name, MB_CASE_TITLE, "UTF-8"); ?>
                        </td>

                        <!-- TIME -->
                        <td style="vertical-align:middle;color:grey;width:20%">
                            <?php
                            $_hora = date("H:i", $_log->getFechaHora());
                            echo mb_convert_case($_hora, MB_CASE_TITLE, "UTF-8"); ?>
                        </td>


                        <!-- EQUIPO -->
                        <?php
                            $_deviceId = $_log->getEqId();

                            $_detalleEquipo = "";

                            if($_deviceId != 0){
                                $o_Equipo = $o_Equipos [$_deviceId];
                                $_detalleEquipo = $o_Equipo->getDetalle();
                            }
                            else{
                                $_detalleEquipo = "Web";
                            }
                        ?>
                            <td style="vertical-align:middle;color:grey;width:20%">
                                <?php echo mb_convert_case($_detalleEquipo, MB_CASE_TITLE, "UTF-8"); ?>
                            </td>


                        <!-- EMPTY DATA -->
                        <td>
                        </td>

                    </tr> <?php
                }
            }
        ?>

    </tbody>
</table>



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
