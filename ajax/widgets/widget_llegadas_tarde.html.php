<?php require_once dirname(__FILE__) . '/../../_ruta.php'; ?>

<?php


$T_Tipo                             = 'Llegadas_Tarde';
$T_Intervalo                        = (isset($_REQUEST['selIntervalo'])) ? $_REQUEST['selIntervalo'] : ' ';
$cantidad                           = 0;

// HORARIOS NOCTURNOS

if ($T_Intervalo = 'F_Hoy'){
    $T_Intervalo    =   array(
        'desde' => date('Y-m-d H:i:s', strtotime('today 00:00:00'))  ,
        'hasta' => date('Y-m-d H:i:s', strtotime('today 23:59:59'))
    );
}


/* ARRAY FILTRO */
$T_Filtro_Array         =   Filtro_L::get_filtro_persona();

/* NUEVO REPORTE */
$o_Reporte              = new Reporte_O($T_Tipo,$T_Intervalo, $T_Filtro_Array);//, $T_Intervalo, $T_Persona, $T_Grupo);
$a_Llegadas_Tarde       = $o_Reporte->generar_reporte();

unset($o_Reporte);


foreach ($a_Llegadas_Tarde as $perID => $item){
    $cantidad += $item['Total'];
}



if ($cantidad > 0) {?>

    <div>
        <table id="dt_basic"
               class="table table-striped table-hover dataTable no-footer"
               aria-describedby="dt_basic_info"
               style="width: 100%;">

            <thead>
            <th><?php echo _('Legajo');   ?></th>
            <th><?php echo _('Nombre');   ?></th>
            <th><?php echo _('Hora');     ?></th>
            <th><?php echo _('Horario');  ?></th>

            </thead>

            <tbody class="addNoWrap">

            <?php foreach ($a_Llegadas_Tarde as $per_Id => $item){?>

                <?php
                if($item['Total'] == 0){
                    continue;
                }
                ?>

                <tr>
                    <!-- LEGAJO -->
                    <td>
                        <?php echo $item['per_Legajo']; ?>
                    </td>

                    <!-- NOMBRE -->
                    <td>
                        <?php echo mb_convert_case($item['per_Apellido'].", ".$item['per_Nombre'], MB_CASE_TITLE, "UTF-8"); ?>
                    </td>

                    <!-- HORA -->
                    <td>
                        <?php foreach ($item['Llegadas_Tarde'] as $key => $log){ ?>

                            <div>
                                        <span class="">
                                            <?php
                                            $hora       = date('H:i', strtotime($log['Hora_Inicio']) );
                                            $dia        = $dias[date('w', strtotime($log['Fecha_Inicio']))];


                                            echo $hora.' '.$dia;
                                            ?>
                                        </span><br>
                            </div>
                        <?php } ?>
                    </td>

                    <!-- HORARIO -->
                    <td>
                        <?php echo mb_convert_case($item['Hora_Trabajo_Detalle'] . '<br>', MB_CASE_TITLE, "UTF-8"); ?>
                    </td>
                </tr>

            <?php } ?>


            </tbody>
        </table>
    </div>

    <?php
}
else {
    echo _('No hay llegadas tarde');
}
?>

<style>
    td{
        vertical-align:middle;
    }
    th{
        vertical-align:middle;
        width:25%;
    }
</style>

<!-- WIDGET TOTAL ICON -->
<script type="text/javascript">

    $("#div_unread_count_llegadas_tarde").text("<?php echo $cantidad; ?>");

    if (<?php echo $cantidad; ?> > 0)
    $("#div_unread_count_llegadas_tarde").show();
    else
    $("#div_unread_count_llegadas_tarde").hide();

</script>


