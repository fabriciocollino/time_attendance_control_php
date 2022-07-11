<?php error_reporting(E_ALL); ?>
<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/' . basename(__FILE__, '.html.php') . '.php';?>
<?php $Filtro_Form_Action = "ajax/" . basename(__FILE__); ?>

<?php require_once APP_PATH . '/includes/top-mensajes.inc.php';

?>


<!-- Bread crumb is created dynamically -->
<!-- row -->
<div class="row">

    <!-- col -->
    <div class="col-xs-8 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-folder-o"></i>
            <?php echo _('Control de Personal') ?>
            <span>>
                <?php echo _('Permisos') ?>
			</span>
        </h1>
    </div>
    <!-- end col -->
    <?php if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 50) { ?>
        <div class="col-xs-4 col-sm-5 col-md-5 col-lg-8" id="sparkscontainer">
            <div id="sparks">
                <button class="btn btn-sm btn-primary" type="button" data-backdrop="static" data-toggle="modal"
                        data-target="#editar" data-type="view" data-lnk="ajax/<?= $Item_Name ?>-editar.html.php">
                    <?php echo _('Agregar Permiso') ?>
                </button>
            </div>
        </div>
    <?php } ?>

</div>
<!-- end row -->


<!-- widget grid -->
<section id="widget-grid" class="">

    <!-- FILTER: INCLUDE -->
    <div class="row">
        <?php require_once APP_PATH . '/includes/widgets/widget_filtro_feriados.html.php'; //rahul ?>
    </div>

    <!-- TABLE: ROWS -->
    <div class="row">

        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false"
                 data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false"
                 data-widget-sortable="false" style="width: 100%;">

                <header>
                    <span class="widget-icon"> <i class="fa fa-folder-o"></i> </span>
                    <h2><?php echo 'Listado de Permisos'; ?></h2>

                </header>


                <div>
                    <div class="jarviswidget-editbox">
                    </div>

                    <div class="widget-body no-padding">

                        <table id="dt_basic" class="table table-striped table-hover dataTable no-footer"
                               aria-describedby="dt_basic_info" style="width: 100%;">

                            <thead>
                            <tr>

                                <th><?php echo _('Motivo') ?></th>
                                <th data-priority="1"><?php echo _('Inicia') ?></th>
                                <th data-priority="2"><?php echo _('Finaliza') ?></th>
                                <th><?php echo _('Duración') ?></th>
                                <th data-priority="3"><?php echo _('Personas') ?></th>
                                <th data-priority="4"><?php echo _('Acciones') ?></th>

                            </tr>
                            </thead>


                            <tbody>
                            <?php if (!is_null($o_Listado)): ?>

                                <?php foreach ($o_Listado as $key => $item): ?>

                                    <!-- (UNUSED) : PASADAS FILTER -->
                                    <?php
                                    $boton_disabled = '';
                                    $row_disabled = '';
                                    if ($item->checkPasada()) {
                                        $row_disabled = " text-muted ";
                                        $boton_disabled = " disabled ";
                                    }
                                    ?>

                                    <tr class="<?php echo $row_disabled; ?>">

                                        <!-- MOTIVO -->
                                        <td>
                                            <?php
                                            $_Motivo = mb_convert_case($item->getMotivo(), MB_CASE_TITLE, "UTF-8");
                                            echo htmlentities($_Motivo, ENT_QUOTES, 'utf-8');
                                            ?>
                                        </td>

                                        <!-- INICIO-->
                                        <td>
                                            <?php $fecha = strtotime($item->getFechaInicio(Config_L::p('f_fecha_corta')));
                                            echo date('Y-m-d', $fecha);
                                            ?>
                                        </td>

                                        <!-- FINALIZA -->
                                        <td>
                                            <?php $fecha = strtotime($item->getFechaFin(Config_L::p('f_fecha_corta')));
                                            echo date('Y-m-d', $fecha);
                                            ?>
                                        </td>

                                        <!-- DURACIÓN-->
                                        <td>
                                            <?php
                                            if(     $item->getTipo() ==  3 || $item->getTipo() == 4    ){

                                                $Diff = strtotime($item->getFechaFin(Config_L::p('f_fecha_corta')))-strtotime($item->getFechaInicio(Config_L::p('f_fecha_corta')));

                                                $day=$Diff / (60 * 60 * 24);

                                                if($day < 1){
                                                    echo floor($day+1)." Día";
                                                }
                                                else if($day > 0){
                                                    echo floor($day+1)." Días";
                                                }
                                            }
                                            else{
                                                $r_arr = explode(",", $item->getDuracion());

                                                if($r_arr[1]=='h'){
                                                    if($r_arr[0] > 1 ){
                                                        echo $r_arr[0]." Horas";
                                                    }
                                                    else{
                                                        echo $r_arr[0]." Hora";
                                                    }
                                                }
                                                else if($r_arr[1]=='m'){
                                                    echo $r_arr[0]." Minutos";
                                                }
                                            }

                                            ?>
                                        </td>

                                        <!-- PERSONAS -->
                                        <td>
                                            <?php if ($item->getPersonaOGrupo() == 'persona') {

                                                $o_Persona = Persona_L::obtenerPorId($item->getPerId());
                                                if(!is_null($o_Persona)){
                                                    $_nombrePersona = mb_convert_case($o_Persona->getNombreCompleto(), MB_CASE_TITLE, "UTF-8");
                                                }
                                                else{
                                                    $_nombrePersona = '';
                                                }
                                                echo htmlentities($_nombrePersona, ENT_QUOTES, 'utf-8');
                                            }

                                            else if ($item->getPersonaOGrupo() == 'grupo') {

                                                $o_Grupo=Grupo_L::obtenerPorId($item->getGrupoId());
                                                if(!is_null($o_Grupo)){
                                                    $_g_Detalle = mb_convert_case($o_Grupo->getDetalle(), MB_CASE_TITLE, "UTF-8");
                                                    echo    '<b>Grupo:</b> ' . htmlentities($_g_Detalle, ENT_QUOTES, 'utf-8');
                                                }
                                                else{
                                                    echo    '<b>Grupo:</b> ';
                                                }
                                            }
                                            ?>
                                        </td>

                                        <!-- (UNUSED)-->
                                        <!--<td>
                                            <?php
                                        // switch ($item->getTipo()) {
                                        // case LICENCIA_LLEGADA_TARDE:
                                        // $fecha = strtotime($item->getFechaInicio(Config_L::p('f_fecha_corta')));
                                        // $dayofweek = date('w', $fecha);
                                        // $dayofmonth = date('j', $fecha);
                                        // $month = date('n', $fecha);
                                        // $year = date('Y', $fecha);

                                        // echo $dias[$dayofweek] . ', ' . $dayofmonth . ' de ' . $a_meses[$month] . ' del ' . $year;
                                        // echo '</br>';
                                        // echo $item->getDuracionNumber() . ' ' . $item->getDuracionMHString();
                                        // break;
                                        // case LICENCIA_SALIDA_TEMPRANO:
                                        // $fecha = strtotime($item->getFechaInicio(Config_L::p('f_fecha_corta')));
                                        // $dayofweek = date('w', $fecha);
                                        // $dayofmonth = date('j', $fecha);
                                        // $month = date('n', $fecha);
                                        // $year = date('Y', $fecha);

                                        // echo $dias[$dayofweek] . ', ' . $dayofmonth . ' de ' . $a_meses[$month] . ' del ' . $year;
                                        // echo '</br>';
                                        // echo $item->getDuracionNumber() . ' ' . $item->getDuracionMHString();
                                        // break;
                                        // case LICENCIA_DIA_COMPLETO:
                                        // $fecha = strtotime($item->getFechaInicio(Config_L::p('f_fecha_corta')));
                                        // $dayofweek = date('w', $fecha);
                                        // $dayofmonth = date('j', $fecha);
                                        // $month = date('n', $fecha);
                                        // $year = date('Y', $fecha);

                                        // echo $dias[$dayofweek] . ', ' . $dayofmonth . ' de ' . $a_meses[$month] . ' del ' . $year;
                                        // break;
                                        // case LICENCIA_PERSONALIZADA:
                                        // echo $item->getFechaInicio(Config_L::p('f_fecha_corta')); ?> </br> <?php // echo $item->getFechaFin(Config_L::p('f_fecha_corta'));
                                        // break;
                                        // }

                                        ?>
                                        </td>-->
                                        <!-- <td>
                                       <?php //echo htmlentities($item->getMotivo(), ENT_QUOTES, 'utf-8'); ?>
                                       </td>-->
                                        <!-- <td class="smart-form">
											<section class="col col-4">
												<label class="toggle">
													<input type="checkbox" rId="<?php //echo $item->getId();?>" class="act" name="enabled" <?php //if ($item->getEnabled() == 1) echo "checked=\"yes\""; ?>>
													<i data-swchon-text="SI" data-swchoff-text="NO" style="top:0px;"></i>
												</label>
											</section>
										</td>-->

                                        <!-- ACCIONES -->
                                        <td style="white-space: nowrap;">
                                            <?php if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 50) { ?>
                                                <button data-toggle="modal" data-backdrop="static" data-target="#editar"
                                                        data-type="view" data-lnk="ajax/permissions-editar.html.php"
                                                        data-id="<?php echo $item->getId(); ?>"
                                                        title="<?php echo _('Editar') ?>"
                                                        class="btn btn-default btn-sm fa fa-edit fa-lg <?php echo $boton_disabled; ?>"
                                                        style="line-height: .75em;"></button>
                                                <button data-type="delete" data-id="<?php echo $item->getId(); ?>"
                                                        title="<?php echo _('Eliminar') ?>"
                                                        class="btn btn-default btn-sm fa fa-trash-o fa-lg <?php echo $boton_disabled; ?>"
                                                        style="line-height: .75em;"></button>
                                            <?php } ?>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>


                            <?php else: ?>
                            <?php endif; ?>

                            </tbody>
                        </table>


                    </div>
                </div>
            </div>

        </article>

    </div>


</section>

<?php
//INCLUYO los view/edit etc de los cosos
require_once APP_PATH . '/templates/edit-view_modal.html.php';
?>


<script type="text/javascript">


    pageSetUp();

    if ($('.DTTT_dropdown.dropdown-menu').length) {
        $('.DTTT_dropdown.dropdown-menu').remove();
    }

    <?php
    //INCLUYO el js de las datatables
    require_once APP_PATH . '/includes/data_tables.js.php';
    ?>

    //esto asigna el ID al modal cada vez que se hace click en el boton
    $(document).ready(function () {

        $('a[data-toggle=modal], button[data-toggle=modal]').click(function () {

            var data_id = '';
            var lnk = '';
            var view_type = '';
            if (typeof $(this).data('id') !== 'undefined') {
                data_id = $(this).data('id');
            }
            if (typeof $(this).data('lnk') !== 'undefined') {
                lnk = $(this).data('lnk');
            }
            if (typeof $(this).data('type') !== 'undefined') {
                view_type = $(this).data('type');
            }

            $('.modal-content').html("<div style=\"padding:15px;height:75px;\"><h1 class=\"ajax-loading-animation\"><i class=\"fa fa-cog fa-spin\"></i> Cargando...</h1></div>");

            $.ajax({
                cache: false,
                type: 'POST',
                url: lnk,
                data: {tipo: view_type, id: data_id},
                success: function (data) {
                    $('.modal-content').show().html(data);

                }
            });
        });


        $('a[data-type=delete], button[data-type=delete]').click(function () {
            var data_id = '';
            var lnk = '';
            var view_type = '';
            if (typeof $(this).data('id') !== 'undefined') {
                data_id = $(this).data('id');
            }
            if (typeof $(this).data('lnk') !== 'undefined') {
                lnk = $(this).data('lnk');
            }
            if (typeof $(this).data('type') !== 'undefined') {
                view_type = $(this).data('type');
            }
            ExistMsg = 0;//por un error en el plugin smartmessagebox  http://myorange.ca/supportforum/question/smartmessagebox-not-working-after-page-reload-smartmsgboxcount-not-reset

            $.SmartMessageBox({
                title: "Eliminar <?php echo $T_Titulo_Singular; ?>",
                content: "Está por eliminar <?php echo $T_Titulo_Pre; ?> <?php echo $T_Titulo_Singular; ?>. Esta operación no se puede deshacer. ¿Desea continuar?",
                buttons: '[No][Si]'
            }, function (ButtonPressed) {
                if (ButtonPressed === "Si") {
                    //esto refresca la pagina
                    loadURLwData('<?php echo $Item_Name ?>', $('#content'), {tipo: view_type, id: data_id});
                }
                else if (ButtonPressed === "No") {
                    SmartUnLoading();
                }

            });


        });


    });
    jQuery(document).on('change', '.act', function(){
        id=jQuery(this).attr('rId');
        status=0;
        if($(this).prop("checked") == true){
            status=1;
        }
        url ='ajax/permissions.html.php?tipo=enabled&id='+id;

        $.ajax({
            cache: false,
            type: 'post',
            url: url,
            data: {status:status},
            success: function (data) {


            }
        });
    });

</script>
<?php // require_once APP_PATH . '/includes/chat_widget.php'; ?>



