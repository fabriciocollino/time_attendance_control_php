<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php global $T_Tipo;
$T_Tipo = 'Listado_Ausencias';
$_SESSION['filtro']['tipo'] = $T_Tipo;
?>
<?php require_once APP_PATH . '/controllers/reportes.php'; ?>
<?php $Filtro_Form_Action = "ajax/" . basename(__FILE__); ?>

<?php require_once APP_PATH . '/includes/top-mensajes.inc.php'; ?>




<!-- Bread crumb is created dynamically -->
<!-- row -->

<!-- Bread crumb is created dynamically -->
<!-- row -->
<div class="row">

    <!-- col -->
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-list-alt"></i>
            <?php echo _('Reportes') ?>
            <span>>
                <?php echo _('Listado Ausencias') ?>
						</span>
        </h1>
    </div>
    <!-- end col -->


</div>

<section id="widget-grid" class="">


    <div class="row">
        <?php require_once APP_PATH . '/includes/widgets/widget_filtro_personas.html.php'; ?>
    </div>

    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false"
                 data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-sortable="false">

                <header>
                    <span class="widget-icon">
                        <i class="fa fa-list-alt"></i>
                    </span>

                    <h2>
                        <?php echo _('Listado Ausencias') ?>
                    </h2>

                    <div id="selTemplate" class="widget-toolbar" role="menu">
                    </div>
                </header>


                <div>
                    <div class="jarviswidget-editbox">
                    </div>

                    <div class="widget-body no-padding">

                        <table id="dt_basic"
                               class="table table-striped table-hover dataTable no-footer"
                               aria-describedby="dt_basic_info"
                               style="width: 100%;">

                            <thead>
                            <th data-priority="1"><?php echo _('Foto') ?></th>
                            <th data-priority="2"><?php echo _('Legajo') ?></th>
                            <th data-priority="3"><?php echo _('Nombre') ?></th>

                            <th data-priority="4"><?php echo _('Total') ?></th>
                            <th data-priority="5"><?php echo _('Justificadas') ?></th>
                            <th data-priority="6"><?php echo _('Acumula') ?></th>

                            <th data-priority="7"><?php echo _('Fecha') ?></th>
                            <th data-priority="8"><?php echo _('Horario') ?></th>

                            <th data-priority="9"><?php echo _('Feriado') ?></th>
                            <th data-priority="10"><?php echo _('Licencia') ?></th>
                            <th data-priority="11"><?php echo _('Suspension') ?></th>

                            </thead>

                            <tbody class="addNoWrap">
                            <?php if (!is_null($o_Listado)){ ?>
                            <?php foreach ($o_Listado as $per_Id => $item){

                            if($item['Total'] == 0){
                                continue;
                            }
                            ?>

                            <tr>

                                <!-- IMAGEN -->
                                <td>
                                    <div class="mediumImageThumb">
                                        <?php
                                        if ($item['per_Imagen'] == '') {
                                            echo '<img style="border-radius: 50%;" src="https://static.enpuntocontrol.com/app/v1/img/avatars/male-big.png" />';
                                        } else {
                                            echo '<img style="border-radius: 50%;" src="imagen.php?per_id=' . $per_Id . '&size=60" />';
                                        }
                                        ?>
                                    </div>
                                </td>

                                <!-- LEGAJO -->
                                <td>
                                    <?php echo $item['per_Legajo'] ?>
                                </td>

                                <!-- NOMBRE -->
                                <td>
                                    <?php echo mb_convert_case($item['per_Apellido'].", ".$item['per_Nombre'], MB_CASE_TITLE, "UTF-8"); ?>
                                </td>

                                <!-- TOTAL -->
                                <td>
                                    <?php echo $item['Total']; ?>
                                </td>

                                <!-- TOTAL JUSTIFICADAS -->
                                <td>
                                    <?php echo $item['Total_Justificadas']; ?>
                                </td>

                                <!-- TOTAL NO JUSTIFICADAS -->
                                <td>
                                    <?php echo $item['Total_No_Justificadas']; ?>
                                </td>



                                <!-- FECHA -->
                                <td>
                                    <table class="tablainterna no-footer" aria-describedby="dt_basic_info">

                                        <tbody>

                                        <?php
                                        foreach ($item['Ausencias'] as $key_log => $log){ ?>

                                            <tr class="nowrp">

                                                <td>
                                                    <?php echo date("d-m-Y", strtotime($log['Fecha_Trabajo_Inicio'])); ?>
                                                </td>

                                            </tr>

                                        <?php } ?>

                                        </tbody>
                                    </table>
                                </td>


                                <!-- HORARIO -->
                                <td>
                                    <table class="tablainterna no-footer" aria-describedby="dt_basic_info">

                                        <tbody>
                                        <?php
                                        foreach ($item['Ausencias'] as $key_log => $log){ ?>

                                            <tr class="nowrp">
                                                <td>
                                                    <?php echo $log['Hora_Trabajo_Inicio']." - ".$log['Hora_Trabajo_Fin']; ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </td>


                                <!-- FERIADO -->
                                <td>
                                    <table class="tablainterna no-footer" aria-describedby="dt_basic_info">

                                        <tbody>

                                        <?php
                                        foreach ($item['Ausencias'] as $key_log => $log){ ?>

                                            <tr class="nowrp">

                                                <td>
                                                    <?php echo $log['Feriado']; ?>
                                                </td>
                                            </tr>

                                        <?php } ?>

                                        </tbody>
                                    </table>
                                </td>


                                <!-- LICENCIA -->
                                <td>
                                    <table class="tablainterna no-footer" aria-describedby="dt_basic_info">

                                        <tbody>

                                        <?php
                                        foreach ($item['Ausencias'] as $key_log => $log){ ?>

                                            <tr class="nowrp">

                                                <td>
                                                    <?php echo $log['Licencia']; ?>
                                                </td>

                                            </tr>

                                        <?php } ?>

                                        </tbody>
                                    </table>
                                </td>


                                <!-- SUSPENSION -->
                                <td>
                                    <table class="tablainterna no-footer" aria-describedby="dt_basic_info">

                                        <tbody>

                                        <?php
                                        foreach ($item['Ausencias'] as $key_log => $log){ ?>

                                            <tr class="nowrp">

                                                <td>
                                                    <?php echo $log['Suspension']; ?>
                                                </td>


                                            </tr>

                                        <?php } ?>

                                        </tbody>
                                    </table>
                                </td>



                                <?php } ?>
                                <?php } ?>
                            </tbody>

                        </table>

                    </div>
                </div>



            </div>
        </article>
    </div>
</section>


<script type="text/javascript">


    pageSetUp();

    if ($('.DTTT_dropdown.dropdown-menu').length) {
        $('.DTTT_dropdown.dropdown-menu').remove();
    }

    <?php
    //INCLUYO el js de las datatables
    $NoWrap = 1;
    require_once APP_PATH . '/includes/data_tables_reportes.js.php';
    ?>

</script>



