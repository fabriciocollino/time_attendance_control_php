<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php global $T_Tipo;
$T_Tipo = 'Intervalo';
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
                <?php echo _('Intervalo') ?>
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
                        <?php echo _('Marcaciones') ?>
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

                            <th data-priority="4"><?php echo _('Asistencias') ?></th>
                            <th data-priority="5"><?php echo _('Ausencias') ?></th>
                            <th data-priority="6"><?php echo _('Licencias') ?></th>
                            <th data-priority="7"><?php echo _('Suspensiones') ?></th>

                            <th data-priority="8"><?php echo _('Horas Horario') ?></th>
                            <th data-priority="9"><?php echo _('Horas Regulares') ?></th>
                            <th data-priority="10"><?php echo _('Horas Extra') ?></th>
                            <th data-priority="11"><?php echo _('Horas Feriado') ?></th>

                            <th data-priority="12"><?php echo _('Llegadas Tarde') ?></th>
                            <th data-priority="13"><?php echo _('Salidas Temprano') ?></th>

                            </thead>

                            <tbody class="addNoWrap">
                            <?php if (!is_null($o_Listado)){ ?>
                                <?php foreach ($o_Listado as $per_Id => $item){?>
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



                                        <!-- ASISTENCIAS -->
                                        <td>
                                            <?php echo $item['Asistencias']; ?>
                                        </td>

                                        <!-- AUSENCIAS -->
                                        <td>
                                            <?php echo $item['Ausencias']; ?>
                                        </td>


                                        <!-- LICENCIAS -->
                                        <td>
                                            <?php echo $item['Licencias']; ?>
                                        </td>

                                        <!-- SUSPENSIONES -->
                                        <td>
                                            <?php echo $item['Suspensiones']; ?>
                                        </td>




                                        <!-- HORAS HORARIO -->
                                        <td>
                                            <?php echo $item['Horas_Horario']; ?>
                                        </td>

                                        <!-- HORAS REGULARES -->
                                        <td>
                                            <?php echo $item['Horas_Regulares']; ?>
                                        </td>

                                        <!-- HORAS EXTRA -->
                                        <td>
                                            <?php echo $item['Horas_Extra']; ?>
                                        </td>

                                        <!-- HORAS FERIADO -->
                                        <td>
                                            <?php echo $item['Horas_Feriado']; ?>
                                        </td>

                                        <!-- LLEGADAS TARDE -->
                                        <td>
                                            <?php echo $item['Llegadas_Tarde']; ?>
                                        </td>

                                        <!-- SALIDAS TEMPRANO -->
                                        <td>
                                            <?php echo $item['Salidas_Temprano']; ?>
                                        </td>



                                    </tr>
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



