<?php require_once dirname(__FILE__) . '/../_ruta.php';
$T_Tipo             = 'viewAllSent'; ?>
<?php require_once APP_PATH . '/controllers/message.php';    ?>
<?php require_once APP_PATH . '/includes/top-mensajes.inc.php';         ?>



<div class="row">


    <div class="col-xs-8 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <i class="fa-fw fa fa-bullhorn"></i>
                <?php echo _('Mensajes') ?>
            <span>>
                <?php echo _('Enviados') ?>
			</span>
        </h1>
    </div>

    <div class="col-xs-4 col-sm-5 col-md-5 col-lg-8" id="sparkscontainer">
        <div id="sparks">
            <button class="btn btn-sm btn-primary" type="button" data-backdrop="static" data-toggle="modal"
                    data-target="#editar" data-type="new" data-lnk="ajax/message-editar.html.php">
                <?php echo _('Agregar Mensage') ?>
            </button>

        </div>
    </div>


</div>

<section id="widget-grid" class="">

    <!-- row -->
    <div class="row">

        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false"
                 data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false"
                 data-widget-sortable="false">

                <header>
                    <span class="widget-icon">
                        <i class="fa fa-bullhorn"></i>
                    </span>
                    <h2>
                        <?php echo _('Mensajes Enviados') ?>
                    </h2>
                </header>


                <div>
                    <div class="jarviswidget-editbox">
                    </div>


                    <div class="widget-body no-padding">


                        <!-- TABLE -->
                        <table id="dt_basic" class="table table-striped table-hover dataTable no-footer"
                               aria-describedby="dt_basic_info" style="width: 100%;">

                            <!-- HEADERS -->
                            <thead>
                                <tr>
                                    <th style="width: 60px;"><?php echo _('Foto') ?></th>
                                    <th><?php echo _('Destinatario') ?></th>
                                    <th><?php echo _('Fecha') ?></th>
                                    <th><?php echo _('Asunto') ?></th>
                                    <th><?php echo _('Cuerpo') ?></th>
                                    <th><?php echo _('Visto') ?></th>
                                    <th><?php echo _('Cantidad') ?></th>
                                    <th style="width: 100px;"><?php echo _('Acciones') ?></th>
                                </tr>
                            </thead>

                            <!-- BODY -->
                            <tbody>
                                <?php if (!is_null($o_Messages)){ ?>

                                    <?php foreach ($o_Messages as $key => $item){

                                        $o_rPersona =  Usuario_L::obtenerPorId($item->getReceiverId());

                                        ?>


                                        <tr>
                                            <!-- FOTO -->
                                            <td style="vertical-align:middle;">
                                                <div class="mediumImageThumb">
                                                    <?php
                                                        if ($o_rPersona->getImagen() == '') {
                                                            echo '<img style="border-radius: 50%;" src="https://static.enpuntocontrol.com/app/v1/img/avatars/male-big.png" />';
                                                        }
                                                        else {
                                                            echo '<img style="border-radius: 50%;" src="' .$o_rPersona->getImagenURL() . '" />';
                                                        }
                                                    ?>
                                                </div>
                                            </td>

                                            <!-- RECEIVER -->
                                            <td>
                                                <?php
                                                $o_rPersona =  Usuario_L::obtenerPorId($item->getReceiverId());
                                                echo $o_rPersona -> getNombreCompleto();
                                                ?>
                                            </td>

                                            <!-- FECHA -->
                                            <td>
                                                <?php echo $item->getCreationDateTime(); ?>
                                            </td>

                                            <!-- SUJETO -->
                                            <td>
                                                <?php echo $item->getSubject();  ?>
                                            </td>

                                            <!-- CUERPO -->
                                            <td>
                                                <?php echo nl2br(stripcslashes($item->getBody())); ?>
                                            </td>

                                            <!-- VISTO -->
                                            <td>
                                                <?php

                                                if($item->getSeenDateTime() != '0000-00-00 00:00:00'){
                                                    echo $item->getSeenDateTime();
                                                }
                                                else{
                                                    echo 'No';
                                                }
                                                ?>                                            </td>

                                            <!-- CANTIDAD -->
                                            <td style="horiz-align: center">
                                                <?php echo Message_L::getAllChainedQuantity($item->getChainedId());  ?>
                                            </td>

                                            <!-- ACCIONES -->
                                            <td>


                                                <!-- VIEW MODAL -->
                                                <button data-toggle         ="modal"
                                                        data-backdrop       ="static"
                                                        type                ="button"
                                                        data-target         ="#editar"
                                                        data-type           ="view"
                                                        data-lnk            ="ajax/message-editar.html.php"
                                                        data-id             ="<?php echo $item->getId(); ?>"
                                                        data-chainedid      ="<?php echo $item->getChainedId(); ?>"
                                                        title               ="<?php echo _('Abrir Mensaje') ?>"
                                                        class               ="btn btn-default btn-sm fa fa-edit fa-lg"
                                                        style               ="line-height: .75em;">
                                                </button>

                                                <!-- DELETE MESSAGE -->
                                                <button data-type           ="delete"
                                                        data-id             ="<?php echo $item->getId(); ?>"
                                                        data-lnk            ="ajax/message_outbox.html.php"
                                                        title               ="<?php echo _('Eliminar Mensaje') ?>"
                                                        class               ="btn btn-default btn-sm fa fa-trash-o fa-lg"
                                                        style               ="line-height: .75em;">
                                                </button>
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



<?php require_once APP_PATH . '/templates/edit-view_modal.html.php'; ?>


<script type="text/javascript">


    pageSetUp();

    if ($('.DTTT_dropdown.dropdown-menu').length) {
        $('.DTTT_dropdown.dropdown-menu').remove();
    }
    <?php require_once APP_PATH . '/includes/data_tables.js.php';?>

    loadScript("https://static.enpuntocontrol.com/app/v1/js/plugin/clockpicker/clockpicker.min.js");


    $(document).ready(function () {
        $('a[data-toggle=modal], button[data-toggle=modal]').click(function () {
            var data_id = '';
            var lnk = '';
            var view_type = '';
            var chained_id = '';

            if (typeof $(this).data('chainedid') !== 'undefined') {
                chained_id = $(this).data('chainedid');
            }
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
                data: {tipo: view_type, men_Id: data_id, men_Chained_Id:chained_id},
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
                title: "Eliminar Mensaje",
                content: "Está por eliminar el Mensaje</br>Esta operación no se puede deshacer. ¿Desea continuar?",
                buttons: '[No][Si]'
            }, function (ButtonPressed) {
                if (ButtonPressed === "Si" || ButtonPressed === "Yes") {

                    $.ajax({
                        cache: false,
                        type: 'POST',
                        url: lnk,
                        data: {tipo: view_type, men_Id: data_id},
                        success: function (data) {
                            location.reload();
                        }
                    });
                }
                else if (ButtonPressed === "No" || ButtonPressed === "Do not") {
                    SmartUnLoading();
                }

            });
        });


    });


</script>
