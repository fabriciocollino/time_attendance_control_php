<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php global $T_Tipo;
$T_Tipo = 'Dias'; ?>
<?php require_once APP_PATH . '/controllers/reportes_empleado.php'; ?>
<?php $Filtro_Form_Action = "ajax/" . basename(__FILE__); ?>

<?php ?>

<?php require_once APP_PATH . '/includes/top-mensajes.inc.php'; ?>


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
                <?php echo _('Días/Horas Trabajadas') ?>
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
                    <!-- <h2><?php //echo _('Días/Horas Trabajadas') ?> </h2>      // QUITAMOS DEL TITULO, VOLVER SI NO ANDA REPORTE -->
                    <h2><?php echo _('Horas Trabajadas') ?></h2>
                    <div id="selTemplate" class="widget-toolbar" role="menu"></div>
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

                                <?php if (Config_L::p('usar_legajo')): ?>
                                    <th data-priority="2"><?php echo _('Legajo') ?></th>
                                <?php endif; ?>
                                <th data-priority="2"><?php echo _('Nombre') ?></th>
                                <th data-priority="2"><?php echo _('Horario') ?></th>
                                <th data-priority="2"><?php echo _('Horas Trabajadas') ?></th>

                            </thead>
                            <tbody>
                            <?php if (!is_null($o_Listado)):



                                ?>

                                <?php  //echo "<pre>";print_r($o_Listado);echo "</pre>"; ?>

                                <?php foreach ($o_Listado as $item): 
										if(isset($a_Personas[$item['per_Id']])){
								?>

                                    <?php $o_persona = $a_Personas[$item['per_Id']];
                                    if (is_null($o_persona)) continue; ?>
                                    <tr>
                                        <td style="vertical-align:middle;">
                                            <div
                                                class="mediumImageThumb"><?php if ($o_persona['per_Imagen'] == '') {
                                                    echo '<img style="border-radius: 50%;" src="https://static.enpuntocontrol.com/app/v1/img/avatars/male-big.png" />';
                                                } else {
                                                    echo '<img style="border-radius: 50%;" src="imagen.php?per_id=' . $o_persona['per_Id'] . '&size=60" />';
                                                } ?></div>
                                        </td>

                                        <?php if (Config_L::p('usar_legajo')): ?>
                                            <td data-priority="2"
                                                style="vertical-align:middle;white-space: nowrap;"><?php echo $o_persona->getLegajo() ?></td>
                                        <?php endif; ?>
                                        <td style="vertical-align:middle;">
                                            <?php echo mb_convert_case($item['per_Apellido'].", ".$item['per_Nombre'], MB_CASE_TITLE, "UTF-8"); ?>
                                        </td>
										 <td style="vertical-align:middle;" >
											 <table class="tablainterna no-footer"
                                                   aria-describedby="dt_basic_info">
                                                <thead>
													<tr>
														<th style="width:150px;"><?php echo  $item['Hora_Trabajo_Detalle'] ; ?></th>
													</tr>
												</thead>
												<tbody>
													 <?php foreach ($item['Dias'] as $fecha => $registro): ?>
                                                    <?php ?>
                                                        <?php foreach ($registro as $key => $log): ?>
														 <tr>
															<td ><?php 
																if($log['Hora_Trabajo_Inicio']=="" && $log['Hora_Trabajo_Fin']==""){
																	echo "Sin Horario";
																}else{
																		if(strlen($log['Hora_Trabajo_Inicio'])==5){
																			echo $log['Hora_Trabajo_Inicio'].":00-";
																		}else{
																			echo $log['Hora_Trabajo_Inicio']."-";
																		}
																		if(strlen($log['Hora_Trabajo_Fin'])==5){
																			echo $log['Hora_Trabajo_Fin'].":00";
																		}else{
																			echo $log['Hora_Trabajo_Fin']."";
																		}
																}
																 ?></td>
														 </tr>
														 <?php endforeach; ?>
                                                    <?php endforeach; ?>
												</tbody>
											</table>
                                        </td>

                                        <td style="vertical-align:top;">
                                            <table class="tablainterna no-footer"
                                                   aria-describedby="dt_basic_info">
                                                <thead>
                                                <tr>
													<!-- <th><?php //echo $item['Hora_Trabajo_Detalle']; ?></th>-->
                                                    <th style="width: 50px;">Día</th>
                                                    <th>Fecha</th>
                                                    <th>Ingreso</th>
                                                    <th>Salida</th>
                                                    <th>Total Intervalo</th>
                                                    <!--<th>Horas Extra</th>
                                                    <th>Total Diario</th>-->
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $Semana=''; $bgSemana='bgSemana'; //esto es para marcar las diferentes semanas?>
                                                    <?php foreach ($item['Dias'] as $fecha => $registro): ?>
                                                    <?php ?>
                                                        <?php foreach ($registro as $key => $log):

                                                            $log['Equipo_Inicio_Corto'] = strlen($log['Equipo_Inicio']) > COL_DISPOSITIVO_WIDTH ? substr($log['Equipo_Inicio'],0,COL_DISPOSITIVO_WIDTH)."..." : $log['Equipo_Inicio'];
                                                            $log['Equipo_Fin_Corto'] = strlen($log['Equipo_Fin']) > COL_DISPOSITIVO_WIDTH ? substr($log['Equipo_Fin'],0,COL_DISPOSITIVO_WIDTH)."..." : $log['Equipo_Fin'];

                                                            ?>
                                                            <tr <?php if(date('W', strtotime($fecha))!=$Semana){$Semana = date('W', strtotime($fecha));if($bgSemana=='')$bgSemana = "bgSemana";else $bgSemana='';}?> class="nowrp <?php echo $bgSemana; ?>">
                                                               <!-- <td style="width:180px;"><?php 
																	// if(strlen($log['Hora_Trabajo_Inicio'])==5){
																		// echo $log['Hora_Trabajo_Inicio'].":00-";
																	// }else{
																		// echo $log['Hora_Trabajo_Inicio']."-";
																	// }
																	// if(strlen($log['Hora_Trabajo_Fin'])==5){
																		// echo $log['Hora_Trabajo_Fin'].":00";
																	// }else{
																		// echo $log['Hora_Trabajo_Fin']."";
																	// }
																 ?></td>-->
																<td title="<?php echo "Inicio: ".$log['Hora_Trabajo_Inicio'].", Fin: ".$log['Hora_Trabajo_Fin']; ?>"><?php echo $dias[date('w', strtotime($fecha))]; ?></td>
                                                                <td><?php echo date("d-m-Y", strtotime($fecha)) ?></td>
                                                                <?php if($log['Lector_Inicio']==1){
                                                                    $resumen_equipo = "<i class=\"fa fp_back_small\" title=\"".$log['Equipo_Inicio_Corto']." - ".dedoAstring($log['Dedo_Inicio'])."\"></i>";
                                                                } else if ($log['Lector_Inicio']==2) {
                                                                    $resumen_equipo = "<i class=\"fa tag_back_fa_marging\" title=\"".$log['Equipo_Inicio_Corto']."\"></i>";
                                                                } ?>
                                                                <td <?php echo ($log['Llegada_Tarde'] == 'Si') ? 'class="tarde_compacto"' : '' ?>><?php //echo $resumen_equipo; ?><?php echo $log['Hora_Inicio']; if($log['Nocturno_Doble']=='Si' && $log['Hora_Inicio']!='')echo "<small> (+1)</small>";?></td>

                                                                <?php if($log['Lector_Fin']==1){
                                                                    $resumen_equipo = "<i class=\"fa fp_back_small\" title=\"".$log['Equipo_Fin_Corto']." - ".dedoAstring($log['Dedo_Fin'])."\"></i>";
                                                                } else if ($log['Lector_Fin']==2) {
                                                                    $resumen_equipo = "<i class=\"fa tag_back_fa_marging\" title=\"".$log['Equipo_Fin_Corto']."\"></i>";
                                                                } ?>
                                                                <td <?php echo ($log['Salida_Temprano'] == 'Si') ? 'class="tarde_compacto"' : '' ?>><?php //echo $resumen_equipo; ?><?php echo $log['Hora_Fin']; if($log['Nocturno']=='Si' && $log['Hora_Fin']!='')echo "<small> (+1)</small>"; ?></td>
                                                               <td><?php echo ($log['Total_Intervalo']) ?></td>
                                                               <!-- <td title="Previas: <?php //echo $log['Hora_Extra_Antes'];?>, Después: <?php //echo $log['Hora_Extra_Despues'];?>"><?php //if(isset($log['extratime'])){echo ($log['extratime']);}//echo ($log['Hora_Extra_Totales']) ?></td>
                                                                <td><?php //if(isset($log['totalTime'])){echo ($log['totalTime']);}//if($key==$cantRegistrosPorDia-1)echo $item['Horas_Totales_Por_Dia'][$fecha]; //solo muestro el total en el ultimo registro diario?></td>-->
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
										<?php } ?>
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


</section>
<!-- end widget grid -->


<script type="text/javascript">


    pageSetUp();

    if ($('.DTTT_dropdown.dropdown-menu').length) {
        $('.DTTT_dropdown.dropdown-menu').remove();
    }





    <?php
    //INCLUYO el js de las datatables
    require_once APP_PATH . '/includes/data_tables.js.php';
    ?>


    function selTemplateReportes(item) {

        var widget = "#wid-id-1";
        var btn = "#btn_SelTemplate";

        if ($(item).data("template") === 'expandido') {

            $(widget).find('a[data-template="compacto"]').parent().removeClass("active");
            $(widget).find('a[data-template="compacto"]').children().removeClass("fa-check");
            $(widget).find('a[data-template="expandido"]').parent().addClass("active");
            $(widget).find('a[data-template="expandido"]').children().addClass("fa-check");
            $(btn + ' .btn_text').text("Expandido");

            if ('<?php echo $template;?>' == 'compacto')
                loadURLwData('<?php echo 'reporte_dias_horas_trabajadas' ?>', $('#content'), {
                    tipo: 'Dias',
                    template: 'expandido'
                });

        } else if ($(item).data("template") === 'compacto') {

            $(widget).find('a[data-template="expandido"]').parent().removeClass("active");
            $(widget).find('a[data-template="expandido"]').children().removeClass("fa-check");
            $(widget).find('a[data-template="compacto"]').parent().addClass("active");
            $(widget).find('a[data-template="compacto"]').children().addClass("fa-check");
            $(btn + ' .btn_text').text("Compacto");

            if ('<?php echo $template;?>' == 'expandido')
                loadURLwData('<?php echo 'reporte_dias_horas_trabajadas' ?>', $('#content'), {
                    tipo: 'Dias',
                    template: 'compacto'
                });
        }

    }
    var widget = "#wid-id-1";
    if ('<?php echo $template;?>' == 'compacto')$(widget).find('a[data-template="compacto"]').click();
    else if ('<?php echo $template;?>' == 'expandido')$(widget).find('a[data-template="expandido"]').click();


</script>

<?php // require_once APP_PATH . '/includes/chat_widget.php'; ?>
