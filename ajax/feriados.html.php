<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php $_SESSION['filtro']['tipo'] = 'Feriados'; ?>
<?php require_once APP_PATH . '/controllers/feriados.php'; ?>
<?php $Filtro_Form_Action = "ajax/" . basename(__FILE__); ?>

<?php require_once APP_PATH . '/includes/top-mensajes.inc.php'; ?>

<div class="row">

    <!-- col -->
    <div class="col-xs-8 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-calendar"></i>
            <?php echo _('Calendario') ?>
            <span>>
                <?php echo _('Feriados') ?>
			</span>
        </h1>
    </div>
    <!-- end col -->
    <?php if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 50) { ?>
        <div class="col-xs-4 col-sm-5 col-md-5 col-lg-8" id="sparkscontainer">
            <div id="sparks">
                <button class="btn btn-sm btn-primary" type="button" data-backdrop="static" data-toggle="modal"
                        data-target="#editar" data-type="view" data-lnk="ajax/feriado-editar.html.php">
                    <?php echo _('Agregar Feriado') ?>
                </button>
            </div>
        </div>
    <?php } ?>

</div>
<!-- end row -->


<!-- widget grid -->
<section id="widget-grid" class="">

    <div class="row">

        <?php require_once APP_PATH . '/includes/widgets/widget_filtro_personas.html.php'; ?>

    </div>

    <!-- row -->
    <div class="row">

        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false"
                 data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false"
                 data-widget-sortable="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
                    <h2><?php echo _('Feriados') ?></h2>

                </header>

                <!-- widget div -->
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
                            <tr>
                                <th data-priority="1"><?php echo _('Persona') ?></th>
                                <th data-priority="2"><?php echo _('Motivo') ?></th>
                                <th data-priority="3"><?php echo _('Inicia') ?></th>
                                <th data-priority="4"><?php echo _('Finaliza') ?></th>
                                <th data-priority="5"><?php echo _('Duración') ?></th>
                                <th data-priority="6"><?php echo _('Acciones') ?></th>

                                <!--
                                    <th data-priority="4"><?php //echo _('Activo') ?></th>
                                    -->
                            </tr>
                            </thead>

                            <tbody>


                            <?php if (!is_null($o_Listado)): ?>

                                <?php foreach ($o_Listado as $key => $item): ?>

                                    <tr>

                                        <!-- PERSONA -->
                                        <td>
                                            <?php


                                            $o_Persona = $o_Listado_Personas[$item->getPerId()];
                                            $_nombrePersona = $o_Persona->getNombreCompleto();
                                            echo $_nombrePersona;

                                            /*
                                            if ($item->getPersonaOGrupo() == 'persona') {
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
                                            */
                                            ?>

                                        </td>

                                        <!-- Motivo -->
                                        <td>
                                            <?php
                                            $_Motivo = mb_convert_case($item->getDescripcion(), MB_CASE_TITLE, "UTF-8");
                                            echo htmlentities($_Motivo, ENT_QUOTES, 'utf-8');
                                            ?>
                                        </td>

                                        <!-- INICIA -->
                                        <td>
                                            <?php

                                            $fecha = strtotime($item->getFechaInicio('Y-m-d'));
                                            echo date('Y-m-d', $fecha);

                                            ?>
                                        </td>

                                        <!-- FINALIZA -->
                                        <td>
                                            <?php

                                            $fecha = strtotime($item->getFechaFin('Y-m-d'));
                                            echo date('Y-m-d', $fecha);

                                            ?>
                                        </td>


                                        <!-- DURACIÓN -->
                                        <td>
                                            <?php

                                            $duracion = $item->getDuracion();

                                            if($duracion == 1  || $duracion == ''){
                                                echo $duracion." día";
                                            }
                                            else{
                                                echo $duracion." días";
                                            }

                                            ?>
                                        </td>

                                        <!-- ACCIONES -->
                                        <td>
                                            <?php if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 50) { ?>
                                                <button data-toggle="modal" data-backdrop="static" data-target="#editar"
                                                        data-type="view" data-lnk="ajax/feriado-editar.html.php"
                                                        data-id="<?php echo $item->getId(); ?>"
                                                        title="<?php echo _('Editar') ?>"
                                                        class="btn btn-default btn-sm fa fa-edit fa-lg"
                                                        style="line-height: .75em;"></button>
                                                <button data-type="delete" data-id="<?php echo $item->getId(); ?>"
                                                        title="<?php echo _('Eliminar') ?>"
                                                        class="btn btn-default btn-sm fa fa-trash-o fa-lg"
                                                        style="line-height: .75em;"></button>
                                            <?php } ?>
                                        </td>

                                        <!-- ACTIVA -->
                                        <!--
										 <td class="smart-form">
											<section class="col col-4">
												<label class="toggle">
													<input type="checkbox" name="enabled" rId="<?php //echo $item->getId(); ?>" class="f_act" <?php //if ($item->getEnabled() == 1) echo "checked=\"yes\""; ?> >
													<i data-swchon-text="SI" data-swchoff-text="NO" style="top:0;"></i>
												</label>
											</section>
											<?php //echo $item->getEnabled() ? _("Activado") : _("Desactivado"); ?>
										</td>
                                        -->
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


</section>
<!-- end widget grid -->


<?php
//INCLUYO los view/edit etc de los cosos
require_once APP_PATH . '/templates/edit-view_modal.html.php';
?>


<script type="text/javascript">


    pageSetUp();

    if ($('.DTTT_dropdown.dropdown-menu').length) {
        $('.DTTT_dropdown.dropdown-menu').remove();
    }

    <?php require_once APP_PATH . '/includes/data_tables_otros.js.php';?>

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
                title: "Eliminar Feriado",
                content: "Está por eliminar el feriado. Esta operación no se puede deshacer. ¿Desea continuar?",
                buttons: '[No][Si]'
            }, function (ButtonPressed) {
                if (ButtonPressed === "Si") {
                    //esto refresca la pagina
                    loadURLwData('feriados', $('#content'), {tipo: view_type, id: data_id});
                }
                else if (ButtonPressed === "No") {
                    SmartUnLoading();
                }

            });


        });


    });
</script>
<?php // require_once APP_PATH . '/includes/chat_widget.php'; ?>
