<?php require_once dirname(__FILE__) . '/../../_ruta.php'; ?>

<?php

$UsarFechasWidget                   = true;
$T_Titulo                           = _('en Vivo');


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

//ARRAY $o_Grupos_Filtro
$T_SelFiltro                        = (isset($_REQUEST['selFiltro'])) ? $_REQUEST['selFiltro'] : 'grupos';

////printear('$_REQUEST');
////printear($_REQUEST);

switch ($T_SelFiltro) {
    case 'grupos':

        // GRUPOS EN VIVO
        $a_o_Grupos_Vivo            = Grupo_L::obtenerTodosEnVivo();

        // PERSONAS
        $a_o_Personas               = Persona_L::obtenerDesdeFiltroArray(Filtro_L::get_filtro_persona());

        ////printear('$a_o_Equipos');
        ////printear($a_o_Grupos_Vivo);
        //printear('$a_o_Personas');
        //printear($a_o_Personas);

        // PERSONAS POR GRUPO
        $a_Grupo_Personas = array();
        foreach ($a_o_Grupos_Vivo as $grupo_vivo_ID => $o_grupo_vivo){
            $a_Grupo_Personas[$grupo_vivo_ID]['personas']   = Grupos_Personas_L::obtenerARRAYPorGrupo($grupo_vivo_ID);
            $a_Grupo_Personas[$grupo_vivo_ID]['detalle']    = $o_grupo_vivo->getDetalle();
            $a_Grupo_Personas[$grupo_vivo_ID]['presentes']  = array();
            $a_Grupo_Personas[$grupo_vivo_ID]['ausentes']   = array();
        }

        //REPORTE
        $T_Tipo         =   "Marcaciones";
        $T_Intervalo    =   array(
                'desde' => date('Y-m-d H:i:s', strtotime('today 00:00:00'))  ,
                'hasta' => date('Y-m-d H:i:s', strtotime('today 23:59:59'))
        );
        $T_Filtro       =   Filtro_L::get_filtro_persona();
        $o_Reporte      =   new Reporte_O($T_Tipo,$T_Intervalo, $T_Filtro);
        $o_Listado      =   $o_Reporte->generar_reporte();

        //printear('$o_Listado ANTES');
        //printear($o_Listado);

        foreach ($o_Listado as $personaID => $persona_item){

            foreach ($persona_item['logs'] as $log_Id => $log_Item){

                //printear('$personaID');
                //printear($personaID);
                //printear('$log_Item[Fecha_Hora_Fin]');
                //printear($log_Item['Fecha_Hora_Fin']);

                // HORARIO FIN EXISTE: NO HAY QUE ACOMODAR LOGS
                if ($log_Item['Fecha_Hora_Fin'] != ""){

                    //printear(' HORARIO FIN EXISTE: NO HAY QUE ACOMODAR LOGS');

                    continue;
                }

                /* VARIABLES */
                $horario_inicio_date            = date_create($log_Item['Fecha_Hora_Trabajo_Inicio']);
                $horario_fin_date               = date_create($log_Item['Fecha_Hora_Trabajo_Fin']);
                $fecha_hora_inicio_date         = date_create($log_Item['Fecha_Hora_Inicio']);

                //printear('$horario_inicio_date');
                //printear($horario_inicio_date);
                //printear('$horario_fin_date');
                //printear($horario_fin_date);

                //printear('$fecha_hora_inicio_date');
                //printear($fecha_hora_inicio_date);


                $diff_horario_inicio            = date_diff($horario_inicio_date, $fecha_hora_inicio_date);
                $diff_horario_fin               = date_diff($horario_fin_date, $fecha_hora_inicio_date);

                //printear('$diff_horario_inicio');
                //printear($diff_horario_inicio);
                //printear('$diff_horario_fin');
                //printear($diff_horario_fin);

                $interval_horario_inicio    = $diff_horario_inicio->format("%H:%I:%S");
                $interval_horario_fin       = $diff_horario_fin->format("%H:%I:%S");

                $number_interval_horario_inicio= round(abs(DateTimeHelper::time_to_sec($interval_horario_inicio) / 3600), 2);
                $number_interval_horario_fin= round(abs(DateTimeHelper::time_to_sec($interval_horario_fin) / 3600), 2);



                if ($number_interval_horario_fin < $number_interval_horario_inicio){
                    //printear('$diff_horario_fin < $diff_horario_inicio MENORRR SI CAMBIA');
                    $o_Listado[$personaID]['logs'][$log_Id]['Fecha_Hora_Fin']       =  $o_Listado[$personaID]['logs'][$log_Id]['Fecha_Hora_Inicio'];
                    $o_Listado[$personaID]['logs'][$log_Id]['Fecha_Hora_Inicio']    = "";

                    $o_Listado[$personaID]['logs'][$log_Id]['Fecha_Fin']       =  $o_Listado[$personaID]['logs'][$log_Id]['Fecha_Inicio'];
                    $o_Listado[$personaID]['logs'][$log_Id]['Fecha_Inicio']    = "";

                    $o_Listado[$personaID]['logs'][$log_Id]['Hora_Fin']       =  $o_Listado[$personaID]['logs'][$log_Id]['Hora_Inicio'];
                    $o_Listado[$personaID]['logs'][$log_Id]['Hora_Inicio']    = "";
                }


            }

        }
        //printear('$o_Listado DESPUES');
        //printear($o_Listado);

        foreach($a_Grupo_Personas as $gru_ID => $gru_item){

            if(empty($gru_item['personas'])){
                continue;
            }

            foreach($gru_item['personas'] as $per_ID => $per_item){

                // PERSONAS DEL GRUPO INACTIVAS
                if (!isset($a_o_Personas[$per_ID])){
                    unset($a_Grupo_Personas[$gru_ID]['personas'][$per_ID]);
                    continue;
                }

                // AUSENTES: SIN LOGS DE LA PERSONA
                if (!isset($o_Listado[$per_ID])){
                    $a_Grupo_Personas[$gru_ID]['ausentes'][$per_ID]['per_Nombre']           = $a_o_Personas[$per_ID]['per_Nombre'];
                    $a_Grupo_Personas[$gru_ID]['ausentes'][$per_ID]['per_Apellido']         = $a_o_Personas[$per_ID]['per_Apellido'];
                    $a_Grupo_Personas[$gru_ID]['ausentes'][$per_ID]['per_Legajo']           = $a_o_Personas[$per_ID]['per_Legajo'];
                    $a_Grupo_Personas[$gru_ID]['ausentes'][$per_ID]['Fecha_Hora_Inicio']    = '';
                    $a_Grupo_Personas[$gru_ID]['ausentes'][$per_ID]['Fecha_Hora_Fin']       = '';

                    continue;
                }

                // AUSENTES: PERSONAS SIN LOG DE SALIDA
                $last_log = end($o_Listado[$per_ID]['logs']);
                if($last_log['Fecha_Hora_Fin'] != ''){
                    $a_Grupo_Personas[$gru_ID]['ausentes'][$per_ID]['per_Nombre']           = $a_o_Personas[$per_ID]['per_Nombre'];
                    $a_Grupo_Personas[$gru_ID]['ausentes'][$per_ID]['per_Apellido']         = $a_o_Personas[$per_ID]['per_Apellido'];
                    $a_Grupo_Personas[$gru_ID]['ausentes'][$per_ID]['per_Legajo']           = $a_o_Personas[$per_ID]['per_Legajo'];
                    $a_Grupo_Personas[$gru_ID]['ausentes'][$per_ID]['Fecha_Hora_Inicio']    = $last_log['Fecha_Hora_Inicio'];
                    $a_Grupo_Personas[$gru_ID]['ausentes'][$per_ID]['Fecha_Hora_Fin']       = $last_log['Fecha_Hora_Fin'];

                    continue;
                }
                // PRESENTES: PERSONAS CON LOG DE SALIDA
                $a_Grupo_Personas[$gru_ID]['presentes'][$per_ID]['per_Nombre']          = $a_o_Personas[$per_ID]['per_Nombre'];
                $a_Grupo_Personas[$gru_ID]['presentes'][$per_ID]['per_Apellido']        = $a_o_Personas[$per_ID]['per_Apellido'];
                $a_Grupo_Personas[$gru_ID]['presentes'][$per_ID]['per_Legajo']          = $a_o_Personas[$per_ID]['per_Legajo'];
                $a_Grupo_Personas[$gru_ID]['presentes'][$per_ID]['Fecha_Hora_Inicio']   = $last_log['Fecha_Hora_Inicio'];
                $a_Grupo_Personas[$gru_ID]['presentes'][$per_ID]['Fecha_Hora_Fin']      = $last_log['Fecha_Hora_Fin'];
            }
        }
        //printear('$a_Grupo_Personas');
        //printear($a_Grupo_Personas);

        // BORRO VARIABLES
        unset($a_o_Personas, $o_Listado, $a_o_Grupos_Vivo);
        break;

    case 'equipos':

        // PERSONAS
        $a_o_Personas       = Persona_L::obtenerDesdeFiltroArray(Filtro_L::get_filtro_persona());

        // GRUPOS EN VIVO
        $a_o_Equipos = Equipo_L::obtenerTodosenArray();
        ////printear('$a_o_Equipos');
        ////printear($a_o_Equipos);
        ////printear('$a_o_Personas');
        ////printear($a_o_Personas);

        // PERSONAS POR GRUPO
        $a_Equipo_Personas = array();
        foreach ($a_o_Equipos as $o_Equipo_ID => $o_Equipo){

            $a_Equipo_Personas[$o_Equipo_ID]['personas']   = Persona_L::obtenerARRAYPorEquipoId($o_Equipo_ID);
            $a_Equipo_Personas[$o_Equipo_ID]['detalle']    = $o_Equipo->getDetalle();
            $a_Equipo_Personas[$o_Equipo_ID]['presentes']  = array();
            $a_Equipo_Personas[$o_Equipo_ID]['ausentes']   = array();
        }

        ////printear('$a_Equipo_Personas');
        ////printear($a_Equipo_Personas);


        //REPORTE
        $T_Tipo         =   "Marcaciones";
        $T_Intervalo    =   array(
            'desde' => date('Y-m-d H:i:s', strtotime('today 00:00:00'))  ,
            'hasta' => date('Y-m-d H:i:s', strtotime('today 23:59:59'))
        );
        $T_Filtro       =   Filtro_L::get_filtro_persona();
        $o_Reporte      =   new Reporte_O($T_Tipo,$T_Intervalo, $T_Filtro);
        $o_Listado      =   $o_Reporte->generar_reporte();


        //printear('$o_Listado ANTES');
        //printear($o_Listado);


        foreach ($o_Listado as $personaID => $persona_item){

            foreach ($persona_item['logs'] as $log_Id => $log_Item){

                //printear('$personaID');
                //printear($personaID);
                //printear('$log_Item[Fecha_Hora_Fin]');
                //printear($log_Item['Fecha_Hora_Fin']);

                // HORARIO FIN EXISTE: NO HAY QUE ACOMODAR LOGS
                if ($log_Item['Fecha_Hora_Fin'] != ""){

                    //printear(' HORARIO FIN EXISTE: NO HAY QUE ACOMODAR LOGS');

                    continue;
                }

                /* VARIABLES */
                $horario_inicio_date            = date_create($log_Item['Fecha_Hora_Trabajo_Inicio']);
                $horario_fin_date               = date_create($log_Item['Fecha_Hora_Trabajo_Fin']);
                $fecha_hora_inicio_date         = date_create($log_Item['Fecha_Hora_Inicio']);

                //printear('$horario_inicio_date');
                //printear($horario_inicio_date);
                //printear('$horario_fin_date');
                //printear($horario_fin_date);

                //printear('$fecha_hora_inicio_date');
                //printear($fecha_hora_inicio_date);


                $diff_horario_inicio            = date_diff($horario_inicio_date, $fecha_hora_inicio_date);
                $diff_horario_fin               = date_diff($horario_fin_date, $fecha_hora_inicio_date);

                //printear('$diff_horario_inicio');
                //printear($diff_horario_inicio);
                //printear('$diff_horario_fin');
                //printear($diff_horario_fin);

                $interval_horario_inicio    = $diff_horario_inicio->format("%H:%I:%S");
                $interval_horario_fin       = $diff_horario_fin->format("%H:%I:%S");

                $number_interval_horario_inicio= round(abs(DateTimeHelper::time_to_sec($interval_horario_inicio) / 3600), 2);
                $number_interval_horario_fin= round(abs(DateTimeHelper::time_to_sec($interval_horario_fin) / 3600), 2);



                if ($number_interval_horario_fin < $number_interval_horario_inicio){
                    //printear('$diff_horario_fin < $diff_horario_inicio MENORRR SI CAMBIA');
                    $o_Listado[$personaID]['logs'][$log_Id]['Fecha_Hora_Fin']       =  $o_Listado[$personaID]['logs'][$log_Id]['Fecha_Hora_Inicio'];
                    $o_Listado[$personaID]['logs'][$log_Id]['Fecha_Hora_Inicio']    = "";

                    $o_Listado[$personaID]['logs'][$log_Id]['Fecha_Fin']       =  $o_Listado[$personaID]['logs'][$log_Id]['Fecha_Inicio'];
                    $o_Listado[$personaID]['logs'][$log_Id]['Fecha_Inicio']    = "";

                    $o_Listado[$personaID]['logs'][$log_Id]['Hora_Fin']       =  $o_Listado[$personaID]['logs'][$log_Id]['Hora_Inicio'];
                    $o_Listado[$personaID]['logs'][$log_Id]['Hora_Inicio']    = "";
                }


            }

        }
        //printear('$o_Listado DESPUES');
        //printear($o_Listado);


        foreach($a_Equipo_Personas as $equipo_ID => $equipo_item){

            if(empty($equipo_item['personas'])){
                continue;
            }
            foreach($equipo_item['personas'] as $per_ID => $per_item){

                // PERSONAS DEL EQUIPO INACTIVAS
                if (!isset($a_o_Personas[$per_ID])){
                    unset($a_Equipo_Personas[$equipo_ID]['personas'][$per_ID]);
                    continue;
                }

                // AUSENTES: SIN LOGS DE LA PERSONA
                if (!isset($o_Listado[$per_ID])){
                    $a_Equipo_Personas[$equipo_ID]['ausentes'][$per_ID]['per_Nombre']           = $a_o_Personas[$per_ID]['per_Nombre'];
                    $a_Equipo_Personas[$equipo_ID]['ausentes'][$per_ID]['per_Apellido']         = $a_o_Personas[$per_ID]['per_Apellido'];
                    $a_Equipo_Personas[$equipo_ID]['ausentes'][$per_ID]['per_Legajo']           = $a_o_Personas[$per_ID]['per_Legajo'];
                    $a_Equipo_Personas[$equipo_ID]['ausentes'][$per_ID]['Fecha_Hora_Inicio']    = '';
                    $a_Equipo_Personas[$equipo_ID]['ausentes'][$per_ID]['Fecha_Hora_Fin']       = '';

                    continue;
                }

                // AUSENTES: PERSONAS SIN LOG DE SALIDA
                $last_log = end($o_Listado[$per_ID]['logs']);
                if($last_log['Fecha_Hora_Fin'] != ''){
                    $a_Equipo_Personas[$equipo_ID]['ausentes'][$per_ID]['per_Nombre']           = $a_o_Personas[$per_ID]['per_Nombre'];
                    $a_Equipo_Personas[$equipo_ID]['ausentes'][$per_ID]['per_Apellido']         = $a_o_Personas[$per_ID]['per_Apellido'];
                    $a_Equipo_Personas[$equipo_ID]['ausentes'][$per_ID]['per_Legajo']           = $a_o_Personas[$per_ID]['per_Legajo'];
                    $a_Equipo_Personas[$equipo_ID]['ausentes'][$per_ID]['Fecha_Hora_Inicio']    = $last_log['Fecha_Hora_Inicio'];
                    $a_Equipo_Personas[$equipo_ID]['ausentes'][$per_ID]['Fecha_Hora_Fin']       = $last_log['Fecha_Hora_Fin'];

                    continue;
                }
                // PRESENTES: PERSONAS CON LOG DE SALIDA
                $a_Equipo_Personas[$equipo_ID]['presentes'][$per_ID]['per_Nombre']          = $a_o_Personas[$per_ID]['per_Nombre'];
                $a_Equipo_Personas[$equipo_ID]['presentes'][$per_ID]['per_Apellido']        = $a_o_Personas[$per_ID]['per_Apellido'];
                $a_Equipo_Personas[$equipo_ID]['presentes'][$per_ID]['per_Legajo']          = $a_o_Personas[$per_ID]['per_Legajo'];
                $a_Equipo_Personas[$equipo_ID]['presentes'][$per_ID]['Fecha_Hora_Inicio']   = $last_log['Fecha_Hora_Inicio'];
                $a_Equipo_Personas[$equipo_ID]['presentes'][$per_ID]['Fecha_Hora_Fin']      = $last_log['Fecha_Hora_Fin'];


            }
        }
        ////printear($a_Equipo_Personas);

        // BORRO VARIABLES
        unset($a_o_Personas, $o_Listado, $a_o_Grupos_Vivo);

        $a_Grupo_Personas = $a_Equipo_Personas;
        unset($a_Equipo_Personas);
        break;


}



// TABLA DE PRESENTES, AUSENTES, RETIRADAS
if (!empty($a_Grupo_Personas)): ?>
    <table id="table_en_vivo"
           class="table table-striped table-hover dataTable no-footer"
           aria-describedby="dt_basic_info"
           style="width: 100%;">

        <tbody class="addNoWrap">
        <?php
        foreach ($a_Grupo_Personas as $grupoID => $grupo):?>
            <!-- CALCULO DE TOTALES -->
            <?php

            $grupoNombre = $grupo['detalle'];


            // TOTALES //
            $total_Personas  = count($grupo['personas']);
            $total_Presentes = count($grupo['presentes']);
            $total_Ausentes  = count($grupo['ausentes']);

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
                foreach ($grupo['presentes'] as $personaID => $persona) { ?>
                    <tr data-parent="<?php echo $grupoID; ?>" style="display: none;">
                        <!-- CONTINUE IF NO PERSON DATA AVAILABLE-->
                        <?php
                        //if (!array_key_exists($presenteID, $a_Personas)) continue;
                        ?>

                        <!-- LEGAJO -->
                        <td style="padding-left: 5%; color:grey;vertical-align:middle;width:25%">
                            <?php
                            $_legajo = $persona['per_Legajo'];
                            echo mb_convert_case($_legajo, MB_CASE_TITLE, "UTF-8"); ?>
                        </td>
                        <!-- APELLIDO, NOMBRE -->
                        <td style="vertical-align:middle;color:grey;width:50%">
                            <?php
                            $_name = $persona['per_Apellido'] . ', ' . $persona['per_Nombre'];
                            echo mb_convert_case($_name, MB_CASE_TITLE, "UTF-8"); ?>
                        </td>
                        <!-- EMPTY DATA -->
                        <td style="vertical-align:middle;color:grey;width:20%">
                            <?php
                            $_hora = date("H:i", strtotime($persona['Fecha_Hora_Inicio']));
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
                foreach ($grupo['ausentes'] as $personaID => $persona) { ?>
                    <tr data-parent="<?php echo $grupoID; ?>" style="display: none;">
                        <!-- CONTINUE IF NO PERSON DATA AVAILABLE-->
                        <?php
                        //if (!array_key_exists($ausenteID, $a_Personas)) continue;
                        ?>

                        <!-- LEGAJO -->
                        <td style="padding-left: 5%; color:lightgrey;vertical-align:middle;width:25%">
                            <?php
                            $_legajo = $persona['per_Legajo'];
                            echo mb_convert_case($_legajo, MB_CASE_TITLE, "UTF-8"); ?>
                        </td>
                        <!-- APELLIDO, NOMBRE -->
                        <td style="vertical-align:middle;color:lightgrey;width:50%">
                            <?php
                            $_name = $persona['per_Apellido'] . ', ' . $persona['per_Nombre'];
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