<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php global $T_Tipo;
$T_Tipo = 'Entradas';

?>
<?php require_once APP_PATH . '/controllers/reportes_empleado.php'; ?>
<?php $Filtro_Form_Action = "ajax/" . basename(__FILE__); ?>

<?php require_once APP_PATH . '/includes/top-mensajes.inc.php'; ?>


<!-- Bread crumb is created dynamically -->
<!-- row -->
<div class="row" id="topRowEditableContainer"></div>
<div class="row" id="topRowPopoverContainer"></div>
<div class="row">

    <!-- col -->
    <div class="col-xs-6 ">
        <h1 class="page-title txt-color-blueDark">

            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-hand-o-up"></i>
                <?php echo _('Reportes') ?>
            <span>>
                <?php echo _('Entradas y Salidas') ?>
						</span>
        </h1>
    </div>


</div>
<!-- end row -->

<?php //pred($o_Listado);?>
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
                    <h2>
                        <?php echo _('Entradas y Salidas') ?>
                    </h2>
                    <div id="selTemplate" class="widget-toolbar" role="menu">

                    </div>
                </header>

                <!-- widget div-->
                <div>

                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                    </div>

                    <!-- widget content -->
                    <div class="widget-body no-padding">

                        <?php //echo "<pre>";print_r($o_Listado);echo "</pre>"; ?>
                        <table id="dt_basic" class="table table-striped table-hover dataTable no-footer"
                               aria-describedby="dt_basic_info" style="width: 100%;">
                            <thead>

                            <th data-priority="2" style="width: 60px;"><?php echo _('Foto') ?></th>
                            <?php if (Config_L::p('usar_legajo')): ?><th data-priority="3"><?php echo _('Legajo') ?></th><?php endif; ?>
                            <th data-priority="2"><?php echo _('Nombre') ?></th>
                            <th data-priority="2"><?php echo _('Horario') ?></th>
                            <th data-priority="2"><?php echo _('Entradas y Salidas') ?></th>

                            </thead>
                            <tbody class="addNoWrap">
                            <?php if (!is_null($o_Listado)): ?>

                                <?php foreach ($o_Listado as $per_Id => $item):
                                    if(isset($item['Imagen'])){
                                        ?>

                                        <tr>
                                            <td style="vertical-align:middle;">
                                                <div
                                                        class="mediumImageThumb"><?php if ($item['Imagen'] == '') {
                                                        echo '<img style="border-radius: 50%;" src="https://static.enpuntocontrol.com/app/v1/img/avatars/male-big.png" />';
                                                    } else {
                                                        echo '<img style="border-radius: 50%;" src="imagen.php?per_id=' . $per_Id . '&size=60" />';
                                                    } ?></div>
                                            </td>

                                            <!-- LEGAJO -->
                                            <?php if (Config_L::p('usar_legajo')): ?>
                                                <td data-priority="2"
                                                    style="vertical-align:middle;"><?php echo $item['Legajo'] ?></td>
                                            <?php endif; ?>

                                            <!-- NOMBRE -->
                                            <td style="vertical-align:middle;">
                                                <?php echo mb_convert_case($item['Nombre'], MB_CASE_TITLE, "UTF-8"); ?>
                                            </td>

                                            <td style="vertical-align:middle;" >
                                                <table class="tablainterna no-footer" aria-describedby="dt_basic_info">
                                                    <thead>
                                                    <tr>
                                                        <th style="width:150px;"><?php echo  $item['Hora_Trabajo_Detalle'] ; ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $Semana=''; $bgSemana='class=\'bgSemana\''; //esto es para marcar las diferentes semanas?>
                                                    <?php foreach ($item['Dias'] as $fecha => $registro): ?>
                                                        <?php ?>
                                                        <?php foreach ($registro as $key => $log): ?>
                                                            <tr>
                                                                <td>
                                                                    <?php
                                                                        if($log['Hora_Trabajo_Inicio']=="" && $log['Hora_Trabajo_Fin']==""){
                                                                            echo "Feriado/Licencias";
                                                                        }
                                                                        else{
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
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>

                                            </td>
                                            <td style="vertical-align:top;">
                                                <table class="tablainterna no-footer" aria-describedby="dt_basic_info">
                                                    <thead>
                                                    <tr>
                                                        <th style="width: 36px;">Día</th>
                                                        <th>Fecha</th>
                                                        <th>Entrada</th>
                                                        <th style="width:90px;">Equipo</th>
                                                        <th >Salida</th>
                                                        <th>Equipo</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $Semana=''; $bgSemana='class=\'bgSemana\''; //esto es para marcar las diferentes semanas?>
                                                    <?php foreach ($item['Dias'] as $fecha => $registro): ?>
                                                        <?php foreach ($registro as $key => $log):

                                                            $log['Equipo_Inicio_Corto'] = strlen($log['Equipo_Inicio']) > COL_DISPOSITIVO_WIDTH ? substr($log['Equipo_Inicio'],0,COL_DISPOSITIVO_WIDTH)."..." : $log['Equipo_Inicio'];
                                                            $log['Equipo_Fin_Corto'] = strlen($log['Equipo_Fin']) > COL_DISPOSITIVO_WIDTH ? substr($log['Equipo_Fin'],0,COL_DISPOSITIVO_WIDTH)."..." : $log['Equipo_Fin'];

                                                            $fecha_para_log = date("Y-m-d", strtotime($fecha)); //esto se pone asi porque en la API el log usa formato Y-m-d
                                                            ?>
                                                            <tr <?php if(date('W', strtotime($fecha))!=$Semana){$Semana = date('W', strtotime($fecha));if($bgSemana=='')$bgSemana = "class='bgSemana'";else $bgSemana='';} echo $bgSemana; ?>>
                                                                <td title="<?php echo "Inicio: ".$log['Hora_Trabajo_Inicio'].", Fin: ".$log['Hora_Trabajo_Fin']; ?>"><?php echo $dias[date('w', strtotime($fecha))]; ?></td>

                                                                <td>
                                                                    <?php echo date("d-m-Y", strtotime($fecha)) ?>
                                                                </td>

                                                                <td data-fecha="<?php echo $fecha_para_log ?>"
                                                                    data-pk="<?php echo $log['Hora_Inicio_Log_Id']; ?>"
                                                                    class="log_hora <?php echo ($log['Llegada_Tarde'] == 'Si') ? 'tarde_compacto' : '' ?>
                                                                    <?php echo ($log['Hora_Inicio_Log_Editado'] == 1) ? 'log_editado' : '' ?> "
                                                                    <?php echo ($log['Hora_Inicio_Log_Editado'] == 1) ? 'title="Este registro ha sido editado"' : '' ?>>

                                                                    <?php echo $log['Hora_Inicio'];

                                                                    if($log['Nocturno_Doble']=='Si' && $log['Hora_Inicio']!='')
                                                                        echo "<small> (+1)</small>";  ?>

                                                                </td>

                                                                <?php if($log['Lector_Inicio']==1){ ?>
                                                                    <td><i class="fa fp_back_small" title="<?php echo dedoAstring($log['Dedo_Inicio']) ?>" ></i><?php echo $log['Equipo_Inicio_Corto']; ?></td>
                                                                <?php } else if ($log['Lector_Inicio']==2) { ?>
                                                                    <td><i class="fa fa-tag tag_back_fa_marging"></i><?php echo $log['Equipo_Inicio_Corto']; ?></td>
                                                                <?php } else if ($log['Lector_Inicio']==3 || $log['Hora_Inicio'] !='') { ?>
                                                                    <td><i class="fa fa-desktop tag_back_fa_marging"></i> Web</td>
                                                                <?php } else { ?>
                                                                    <td></td>
                                                                <?php } ?>

                                                                <td data-fecha="<?php echo $fecha_para_log ?>" data-pk="<?php if($log['Hora_Fin']=="")echo $per_Id; else echo $log['Hora_Fin_Log_Id']; ?>" class="<?php if($log['Hora_Fin']!="")echo 'log_hora ';else echo "log_hora_empty "; echo ($log['Salida_Temprano'] == 'Si') ? 'tarde_compacto' : '' ?> <?php echo ($log['Hora_Fin_Log_Editado'] == 1) ? 'log_editado' : '' ?>" <?php echo ($log['Hora_Fin_Log_Editado'] == 1) ? 'title="Este registro ha sido editado"' : '' ?>>
                                                                    <?php
                                                                        echo $log['Hora_Fin'];
                                                                        if($log['Nocturno']=='Si' && $log['Hora_Fin']!='')
                                                                            echo "<small> (+1)</small>";
                                                                    ?>

                                                                </td>

                                                                <?php if($log['Lector_Fin']==1){ ?>
                                                                    <td><i class="fa fp_back_small" title="<?php echo dedoAstring($log['Dedo_Fin']) ?>" ></i><?php echo $log['Equipo_Fin_Corto']; ?></td>
                                                                <?php } else if ($log['Lector_Fin']==2) { ?>
                                                                    <td><i class="fa fa-tag tag_back_fa_marging"></i><?php echo  $log['Equipo_Fin_Corto']; ?></td>
                                                                <?php } else if ($log['Lector_Fin']==3 || $log['Hora_Fin'] !='') { ?>
                                                                    <td><i class="fa fa-desktop tag_back_fa_marging"></i> Web</td>
                                                                <?php } else { ?>
                                                                    <td></td>
                                                                <?php } ?>
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

