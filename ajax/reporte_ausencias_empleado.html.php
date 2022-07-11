<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php global $T_Tipo;
$T_Tipo = 'Ausencias'; ?>
<?php require_once APP_PATH . '/controllers/reportes_empleado.php'; ?>
<?php $Filtro_Form_Action = "ajax/" . basename(__FILE__); ?>

<?php require_once APP_PATH . '/includes/top-mensajes.inc.php'; 
//pred($o_Listado_Ausencias);
?>



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
                <?php echo _('Ausencias') ?>
			</span>
        </h1>
    </div>
    <!-- end col -->


</div>
<!-- end row -->


<!-- widget grid -->
<section id="widget-grid" class="">


    <div class="row">

        <?php require_once APP_PATH . '/includes/widgets/widget_filtro_intervalos_empleado.html.php'; ?>

    </div>

    <!-- row -->
    <div class="row">

        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false"
                 data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false"
                 data-widget-sortable="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-list-alt"></i> </span>
                    <h2><?php echo _('Ausencias') ?></h2>
                    <div id="selTemplate" class="widget-toolbar" role="menu">

                    </div>
                </header>

                <!-- widget div-->
                <div>

                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->

                    </div>
                    <!-- end widget edit box -->

                    <!-- widget content -->
                    <div class="widget-body no-padding">

                        <table id="dt_basic" class="table table-striped table-hover dataTable no-footer"
                               aria-describedby="dt_basic_info" style="width: 100%;">
                            <thead>
                                <th data-priority="2" style="width: 60px;"><?php echo _('Foto') ?></th>

                                <?php if(Config_L::p('usar_legajo')): ?>
                                <th data-priority="2"><?php echo _('Legajo') ?></th>
                                <?php endif; ?>
                                <th data-priority="2"><?php echo _('Nombre') ?></th>
                                <th data-priority="2"><?php echo _('Horario') ?></th>
                                <!--<th data-priority="2"><?php //echo _('Ausencias') ?></th>-->
                            </thead>
                            <tbody class="addNoWrap">
								<?php
                                if (!is_null($o_Listado_Ausencias)):
								?>
										

								<?php foreach ($o_Listado_Ausencias as $per_Id => $item): ?>

									<tr>
										<td style="vertical-align:middle;">
											<div
												class="mediumImageThumb">
                                                <?php
                                                if ($item['per_Imagen'] == '') {
													echo '<img style="border-radius: 50%;" src="https://static.enpuntocontrol.com/app/v1/img/avatars/male-big.png" />';
												}
                                                else {
													echo '<img style="border-radius: 50%;" src="imagen.php?per_id=' . $per_Id . '&size=80" />';
												} ?>
                                            </div>
										</td>

										<?php if (Config_L::p('usar_legajo')): ?>
											<td data-priority="2"
												style="vertical-align:middle;"><?php echo $item['per_Legajo'] ?></td>
										<?php endif; ?>

                                        <td style="vertical-align:middle;">
                                            <?php echo mb_convert_case($item['per_Apellido'].", ".$item['per_Nombre'], MB_CASE_TITLE, "UTF-8"); ?>
                                        </td>

										<td >
											<table class="">
												<thead>
													<tr>
														<th style="padding-left:0px !important; width:170px;"><?php echo " (" . $item['Hora_Trabajo_Detalle'] . ")"; ?></th>
														<th style="width:50px; padding-left:0px !important;">Dia</th>
														<th style="padding-left:10px !important;">Fecha</th>
														<th style="padding-left:25px !important;">Motivo</th>
													</tr>
												</thead>
												<tbody>
											
											
											<?php foreach ($item['Ausencias'] as $r_key=>$t_fecha): ?>
												<tr>
													<td style="padding-left:0px !important;">
														<?php echo $item['Hora_Trabajo_Inicio'][$r_key]."-".$item['Hora_Trabajo_Fin'][$r_key]; ?>
													</td>
													
													<td style="">
														<?php echo $dias[date('w', strtotime($t_fecha))]; ?>
													</td>
													<td style="padding-left:10px !important;">
														<?php echo date("d-m-Y", strtotime($t_fecha)) ?>
													</td>
													<td style="padding-left:25px !important;">
														<?php echo $item['Reason'][$t_fecha] ?>
													</td>
												</tr>
												
											<?php endforeach; ?>
											</table>
										</td>

									</tr>

								<?php endforeach; ?>

							<?php endif; ?>
							</tbody>
                    </table>

                    </div>
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->

        </article>
        <!-- WIDGET END -->

    </div>

        <!-- end row -->
        <div class="row" style="display:none;">
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-555" data-widget-editbutton="false"
                     data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false">
                    <header>
                        <span class="widget-icon"> <i class="fa-fw fa fa-folder-o"></i> </span>
                        <h2><?php echo _('Licencias') ?></h2>
                    </header>
                    <!-- widget div-->
                    <div>
                        <?php if (isset($personasConLicencia) && !is_null($personasConLicencia)): ?>
                            <?php foreach ($personasConLicencia as $per_Id => $item): ?>
                                <span style="padding-left: 5px; padding-bottom: 5px;">
                                <?php echo '<b>' . $item['Nombre'] . '</b> estuvo de licencia desde ' . $item['Desde'] . ' hasta ' . $item['Hasta'] . '. <br />'; ?>
                            </span>
                            <?php endforeach; ?>
                        <br />
                        <?php endif; ?>
                        <!-- end widget content -->

                    </div>
                    <!-- end widget div -->

                </div>
                <!-- end widget -->

            </article>
        </div>

</section>
<!-- end widget grid -->


<script type="text/javascript">


    pageSetUp();

    if ($('.DTTT_dropdown.dropdown-menu').length) {
        $('.DTTT_dropdown.dropdown-menu').remove();
    }





    <?php
    //INCLUYO el js de las datatables
    $NoWrap = 1;
    require_once APP_PATH . '/includes/data_tables.js.php';
    ?>


</script>


<?php // require_once APP_PATH . '/includes/chat_widget.php'; ?>

