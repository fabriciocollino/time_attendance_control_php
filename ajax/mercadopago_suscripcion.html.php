<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>

<?php
$T_Suscripciones    =   array();
?>


<?php require_once(APP_PATH . '/libs/random/random.php'); ?>
<?php require_once APP_PATH . '/controllers/mercadopago_suscripcion.php'; ?>
<?php require_once APP_PATH . '/includes/top-mensajes.inc.php'; ?>


    <!-- Bread crumb is created dynamically -->
<!-- row -->
<div class="row">

    <!-- SECTION HEADER -->
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-star"></i>
            <?php echo _('Mi cuenta') ?>
            <span>>
                <?php echo _('Suscripción') ?>
			</span>
        </h1>
    </div>

    <!-- BUTTON: NEW SUBSCRIPTION -->
    <div class="col-xs-4 col-sm-5 col-md-5 col-lg-8" id="sparkscontainer">
        <div id="sparks">

            <button class="btn btn-sm btn-primary"
                    type="button"
                    data-backdrop="static"
                    data-toggle="modal"
                    data-target="#editar"
                    data-type="checkout"
                    data-lnk="ajax/mercadopago_formulario.html.php">
                <?php echo _('Nueva Suscripción') ?>
            </button>
        </div>
    </div>




</div>



<!-- SUSCRIPCIONES -->
<section id="widget-grid" class="">

    <!-- row -->
    <div class="row">

        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false"
                 data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false"
                 data-widget-sortable="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-list-alt"></i> </span>
                    <h2><?php echo _('Suscripciones') ?></h2>
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

                                <th data-priority="1"><?php echo _('Estado') ?></th>
                                <th data-priority="1"><?php echo _('Descripción') ?></th>
                                <th data-priority="1"><?php echo _('Importe') ?></th>

                                <th data-priority="3"><?php echo _('Fecha De Inicio') ?></th>
                                <th data-priority="2"><?php echo _('Fecha De Próximo Pago') ?></th>

                                <th data-priority="3"><?php echo _('Fecha De Último Cobro') ?></th>
                                <th data-priority="3"><?php echo _('Importe De Último Cobro') ?></th>

                                <th data-priority="4"><?php echo _('Medio de Pago') ?></th>
                                <th data-priority="4"><?php echo _('Email Titular Del Pago') ?></th>

                                <th data-priority="31"><?php echo _('MP Plan ID') ?></th>
                                <th data-priority="31"><?php echo _('MP Suscripcion ID') ?></th>
                                <th data-priority="31"><?php echo _('Modulos_Permisos_ID') ?></th>
                                <!--<th data-priority="31"><?php //echo _('Aplicación: Referencia Externa') ?></th>-->


                                <th data-priority="1"><?php echo _('Opciones') ?></th>


                                <!--<th data-priority="31"><?php // echo _('Cuotas ') ?></th>-->
                                <!--<th data-priority="31"><?php // echo _('Semáforo') ?></th>-->
                                <!--<th data-priority="31"><?php // echo _('Cantidad De Carga') ?></th>-->
                                <!--<th data-priority="31"><?php // echo _('Cantidad De Carga Pendiente') ?></th>-->
                                <!--<th data-priority="31"><?php // echo _('Cantidad Acusada') ?></th>-->
                                <!--<th data-priority="31"><?php // echo _('Saldo Pendiente') ?></th>-->

                                <!--<th data-priority="31"><?php // echo _('Id Titular') ?></th>-->
                                <!-- <th data-priority="31"><?php //echo _('Nombre Del Titular') ?></th>-->
                                <!-- <th data-priority="31"><?php //echo _('Apellido Del Titular') ?></th>-->

                                <!--<th data-priority="31"><?php // echo _('Aplicación: Backend Url') ?></th>-->
                                <!--<th data-priority="31"><?php // echo _('Aplicación: Recolector del Pago') ?></th>-->
                                <!--<th data-priority="31"><?php // echo _('Aplicación: Id ') ?></th>-->

                                <!--<th data-priority="2"><?php // echo _('Suscripcion: Fecha De Creacion') ?></th>-->
                                <!--<th data-priority="31"><?php // echo _('Suscripcion: Última Modificación') ?></th>-->
                                <!--<th data-priority="31"><?php // echo _('Suscripcion: Punto De Inicio') ?></th>-->
                                <!--<th data-priority="31"><?php // echo _('Suscripcion: Punto De Inicio De Sandbox') ?></th>-->
                                <!--<th data-priority="2"><?php  // echo _('Frecuencia') ?></th>-->
                                <!--<th data-priority="31"><?php // echo _('Frecuencia De Pago') ?></th>-->
                                <!--<th data-priority="31"><?php // echo _('Monto De Próximo Pago') ?></th>-->
                                <!--<th data-priority= "2"><?php // echo _('Moneda Id ') ?></th>-->
                                <!--<th data-priority="31"><?php //echo _('Fecha De Finalización') ?></th>-->


                            </thead>

                            <tbody class="addNoWrap">


                                    <?php foreach ($T_Suscripciones as $key => $_suscripcion){ ?>

                                        <tr <?php if ($_suscripcion->get_status('EN') == 'cancelled') echo "class='text-muted'"; ?>>

                                            <!-- ESTADO -->
                                            <td>
                                                <?php echo _($_suscripcion->get_status('AR'));  ?>
                                            </td>
                                            <!-- RAZÓN -->
                                            <td>
                                                <?php echo _($_suscripcion->get_reason());  ?>
                                            </td>
                                            <!-- PRECIO -->
                                            <td>
                                                <?php echo _($_suscripcion->get_precio());  ?>
                                            </td>

                                            <!-- FECHA DE INICIO RECURRENTE AUTOMÁTICA -->
                                            <td>
                                                <?php echo _($_suscripcion->get_auto_recurring_start_date());  ?>
                                            </td>
                                            <!-- FECHA DE PAGO SIGUIENTE -->
                                            <td>
                                                <?php echo _($_suscripcion->get_next_payment_date());  ?>
                                            </td>

                                            <!-- RESUMIDO LA ÚLTIMA FECHA CARGADA -->
                                            <td>
                                                <?php echo _($_suscripcion->get_summarized_last_charged_date());  ?>
                                            </td>
                                            <!-- RESUMIDO EL ÚLTIMO IMPORTE CARGADO -->
                                            <td>
                                                <?php echo _(number_format($_suscripcion->get_summarized_last_charged_amount(),2));  ?>
                                            </td>

                                            <!-- ID DEL MÉTODO DE PAGO -->
                                            <td>
                                                <?php echo _($_suscripcion->get_payment_method_id('AR'));  ?>
                                            </td>
                                            <!-- EMAIL PAGARIO -->
                                            <td>
                                                <?php echo _($_suscripcion->get_payer_email());  ?>
                                            </td>

                                            <!-- ID PREAPPROAL PLAN -->
                                            <td>
                                                <?php echo _($_suscripcion->get_preapproval_plan_id());  ?>
                                            </td>
                                            <!-- ID PREAPPROAL -->
                                            <td>
                                                <?php echo _($_suscripcion->get_preapproval_id());  ?>
                                            </td>

                                            <!-- MODULOS_PERMISOS_ID-->
                                            <td>
                                                <?php echo _($_suscripcion->get_Modulos_Permisos_Id());  ?>
                                            </td>

                                            <!-- REFERENCIA EXTERNA
                                            <td>
                                                <?php //echo _($_suscripcion->get_external_reference());  ?>
                                            </td>-->

                                            <!-- OPCIONES -->
                                            <td class="fc-button-group">

                                                <!-- REACTIVAR -->
                                                <button
                                                        title           ="Activar Suscripción"
                                                        class           ="btn btn-default btn-sm fa fa-play-circle fa-lg "
                                                        type            ="button"
                                                        style           ="<?php  if ($_suscripcion->get_status('EN') == 'authorized') echo 'display:none"'; ?>"
                                                        data-backdrop   ="static"
                                                    <?php           if (($_suscripcion->get_status('EN') ==  'cancelled')) echo 'disabled'; ?>
                                                        onclick         ="Editar_Suscripcion('editar_status_suscripcion','authorized','<?php echo $_suscripcion->get_preapproval_id();  ?>','<?php echo $_suscripcion->get_application_id();  ?>')">
                                                </button>

                                                <!-- PAUSAR -->
                                                <button
                                                        title           ="Pausar Suscripción"
                                                        class           ="btn btn-default btn-sm fa fa-pause-circle fa-lg"
                                                        type            ="button"
                                                        style           ="<?php  if ($_suscripcion->get_status('EN') == 'paused' || $_suscripcion->get_status('') == 'cancelled') echo 'display:none"'; ?>"
                                                        data-backdrop   ="static"
                                                    <?php           if (($_suscripcion->get_status('EN') ==  'cancelled')) echo 'disabled'; ?>
                                                        onclick         ="Editar_Suscripcion('editar_status_suscripcion','paused','<?php echo $_suscripcion->get_preapproval_id(); ?>','<?php echo $_suscripcion->get_application_id();  ?>')">
                                                </button>

                                                <!-- CANCELAR -->
                                                <button
                                                        title           ="Cancelar Suscripción"
                                                        class           ="btn btn-default btn-sm fa fa-close fa-lg"
                                                        type            ="button"
                                                        data-backdrop   ="static"
                                                    <?php           if ($_suscripcion->get_status('EN') == 'cancelled') echo 'disabled'; ?>
                                                        onclick         ="Editar_Suscripcion('editar_status_suscripcion','cancelled','<?php echo $_suscripcion->get_preapproval_id();  ?>','<?php echo $_suscripcion->get_application_id();  ?>')">
                                                </button>


                                                <!-- EDITAR MEDIO DE PAGO -->
                                                <button data-toggle         ="modal"
                                                        data-backdrop       ="static"
                                                        data-target         ="#editar"
                                                        data-type           ="view"
                                                        data-lnk            ="ajax/mercadopago_suscripcion-editar.html.php"
                                                        data-preapproval_id ="<?php echo $_suscripcion->get_preapproval_id();  ?>"
                                                        data-application_id ="<?php echo $_suscripcion->get_application_id();  ?>"
                                                    <?php           if ($_suscripcion->get_status('EN') == 'cancelled') echo 'disabled'; ?>
                                                        title               ="Editar Medio de Pago"
                                                        class               ="btn btn-default btn-sm fa fa-credit-card fa-lg">
                                                </button>

                                                <!-- VER LISTA DE PAGOS -->
                                                <button data-toggle         ="modal"
                                                        data-backdrop       ="static"
                                                        data-target         ="#editar"
                                                        data-type           ="getDetallePagoMercadoPago"
                                                        data-lnk            = "ajax/listado-mini.html.php"
                                                        data-preapproval_id ="<?php echo $_suscripcion->get_preapproval_id();  ?>"
                                                        title               ="Ver Pagos"
                                                        class               ="btn btn-default btn-sm fa fa-list fa-lg">
                                                </button>


                                                <!-- ACTUALIZAR
                                                <button
                                                        title           ="Actualizar Suscripción"
                                                        class           ="btn btn-default btn-sm fa fa-arrow-circle-o-down fa-lg"
                                                        type            ="button"
                                                        data-backdrop   ="static"
                                                    <?php       //    if ($_suscripcion->get_status('EN') == 'cancelled') echo 'disabled'; ?>
                                                        onclick         ="Editar_Suscripcion('loadArrayMercadoPago','','<?php // echo $_suscripcion->get_preapproval_id();  ?>','<?php //echo $_suscripcion->get_application_id();  ?>')">
                                                </button> -->



                                            </td>


                                            <!-- CUOTAS RESUMIDAS -->
                                            <!--<td>
                                                <?php // echo _($_suscripcion->get_summarized_quotas());  ?>
                                            </td>-->
                                            <!-- SEMÁFORO RESUMIDO -->
                                            <!--<td>
                                                <?php // echo _($_suscripcion->get_summarized_semaphore());  ?>
                                            </td> -->
                                            <!-- CANTIDAD CARGADA RESUMIDA -->
                                            <!--<td>
                                                <?php // echo _($_suscripcion->get_summarized_charged_quantity());  ?>
                                            </td> -->
                                            <!-- CANTIDAD DE CARGA PENDIENTE RESUMIDA -->
                                            <!--<td>
                                                <?php // echo _($_suscripcion->get_summarized_pending_charge_quantity());  ?>
                                            </td> -->
                                            <!-- CANTIDAD ACUSADA RESUMIDA -->
                                            <!--<td>
                                                <?php // echo _($_suscripcion->get_summarized_charged_amount());  ?>
                                            </td> -->
                                            <!-- CANTIDAD DE CARGA PENDIENTE RESUMIDA -->
                                            <!--<td>
                                                <?php // echo _($_suscripcion->get_summarized_pending_charge_amount());  ?>
                                            </td> -->



                                            <!-- IDENTIFICACIÓN DEL PAGADOR -->
                                            <!--<td>
                                                <?php // echo _($_suscripcion->get_payer_id());  ?>
                                            </td>-->

                                            
                                            <!-- NOMBRE DEL PAGADOR -->
                                            <!--<td>
                                                <?php // echo _($_suscripcion->get_payer_first_name());  ?>
                                            </td> -->
                                            <!-- APELLIDO DEL PAGADOR -->
                                            <!--<td>
                                                <?php // echo _($_suscripcion->get_payer_last_name());  ?>
                                            </td> -->
                                            <!-- URL ESPALDA -->
                                            <!--<td>
                                                <?php // echo _($_suscripcion->get_back_url());  ?>
                                            </td> -->
                                            <!-- ID DE COLECCIONISTA -->
                                            <!--<td>
                                                <?php // echo _($_suscripcion->get_collector_id());  ?>
                                            </td> -->
                                            <!-- ID DE APLICACIÓN -->
                                            <!--<td>
                                                <?php // echo _($_suscripcion->get_application_id());  ?>
                                            </td> -->

                                            <!-- FECHA DE CREACION -->
                                            <!-- <td>
                                                <?php //echo _($_suscripcion->get_date_created());  ?>
                                            </td> -->
                                            <!-- ÚLTIMA MODIFICACIÓN -->
                                            <!--<td>
                                                <?php // echo _($_suscripcion->get_last_modified());  ?>
                                            </td> -->
                                            <!-- PUNTO DE INICIO -->
                                            <!--<td>
                                                <?php // echo _($_suscripcion->get_init_point());  ?>
                                            </td> -->
                                            <!-- PUNTO DE INICIO DE SANDBOX -->
                                            <!--<td>
                                                <?php // echo _($_suscripcion->get_sandbox_init_point());  ?>
                                            </td> -->
                                            
                                            

                                            <!-- FRECUENCIA RECURRENTE AUTOMÁTICA -->
                                            <!--<td>
                                                <?php // echo _($_suscripcion->get_auto_recurring_frequency());  ?>
                                            </td> -->

                                            <!-- TIPO DE FRECUENCIA RECURRENTE AUTOMÁTICO -->
                                            <!-- <td>
                                                <?php //echo _($_suscripcion->get_auto_recurring_frequency_type());  ?>
                                            </td>-->
                                            <!-- CANTIDAD DE TRANSACCIÓN RECURRENTE AUTOMÁTICA -->
                                            <!-- <td>
                                                <?php //echo  _($_suscripcion->get_auto_recurring_transaction_amount());  ?>
                                            </td>-->
                                            <!-- ID DE MONEDA RECURRENTE AUTOMÁTICA -->
                                            <!-- <td>
                                                <?php //echo _($_suscripcion->get_auto_recurring_currency_id());  ?>
                                            </td>-->

                                            <!-- FECHA DE FINALIZACIÓN RECURRENTE AUTOMÁTICA -->
                                            <!--<td>
                                                <?php //echo _($_suscripcion->get_auto_recurring_end_date());  ?>
                                            </td>-->



                                        </tr>
                                    <?php } ?>



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

<?php require_once APP_PATH . '/templates/edit-view_modal.html.php'; ?>


<script type="text/javascript">

    $("th td").each(function(){
        $(this).attr("colSpan", 6);
    });




    // PAGE
    pageSetUp();

    if ($('.DTTT_dropdown.dropdown-menu').length) {
        $('.DTTT_dropdown.dropdown-menu').remove();
    }



    <?php require_once APP_PATH . '/includes/data_tables_otros.js.php'; ?>


    // NUEVA SUSCRIPCION
    $(document).ready(function () {

        $('a[data-toggle=modal], button[data-toggle=modal]').click(function (e) {
            //e.stopPropagation();

            e.preventDefault();

            var data_id = '';
            var lnk = '';
            var view_type = '';
            var preapproval_id = '';
            var application_id = '';


            if (typeof $(this).data('id') !== 'undefined') {
                data_id = $(this).data('id');
            }
            if (typeof $(this).data('lnk') !== 'undefined') {
                lnk = $(this).data('lnk');
            }
            if (typeof $(this).data('type') !== 'undefined') {
                view_type = $(this).data('type');
            }

            if (typeof $(this).data('preapproval_id') !== 'undefined') {
                preapproval_id = $(this).data('preapproval_id');
            }
            if (typeof $(this).data('application_id') !== 'undefined') {
                application_id = $(this).data('application_id');
            }

            $('.modal-content').html("<div style=\"padding:15px;height:75px;\"><h1 class=\"ajax-loading-animation\"><i class=\"fa fa-cog fa-spin\"></i> Cargando...</h1></div>");

            $.ajax({
                cache: false,
                type: 'POST',
                url: lnk,
                data: {
                    tipo: view_type,
                    preapproval_id: preapproval_id,
                    application_id: application_id,
                    id: data_id
                },
                success: function (data) {
               //     $('.modal-content').css({opacity: '0.0'}).html(data).delay(50).animate({opacity: '1.0'}, 300);

                    $('.modal-content').html(data).show('slow');
                }
            });
        });
    });

    // EDITAR SUSCRIPCION
    function Editar_Suscripcion(tipo, status, preapproval_id, application_id) {


        if (status != "cancelled"){
            $('#content').html('<h1 class="ajax-loading-animation"><i class="fa fa-cog fa-spin"></i> Cargando...</h1>');
            $.ajax({
                type: "POST",
                url: "/ajax/mercadopago_suscripcion.html.php",
                data: {
                    tipo                : tipo,
                    preapproval_id      : preapproval_id,
                    status              : status,
                    application_id      : application_id
                },
                success: function (data, status) {
                    // RELOAD CONTENT
                    $('#content').css({opacity : '0.0'}).html(data).delay(50).animate({opacity : '1.0'}, 300);
                }
            });
        }
        else{
            ExistMsg = 0;

            $.SmartMessageBox({
                title: "Cancelar suscripción",
                content: "Está por cancelar su suscripción</br>Esta operación no se puede deshacer. ¿Desea continuar?",
                buttons: '[No][Si]'
            }, function (ButtonPressed) {
                if (ButtonPressed === "Si" || ButtonPressed === "Yes") {

                    $('#content').html('<h1 class="ajax-loading-animation"><i class="fa fa-cog fa-spin"></i> Cargando...</h1>');
                    $.ajax({
                        type: "POST",
                        url: "/ajax/mercadopago_suscripcion.html.php",
                        data: {
                            tipo                : tipo,
                            preapproval_id      : preapproval_id,
                            status              : status,
                            application_id      : application_id
                        },
                        success: function (data, status) {
                            // RELOAD CONTENT
                            $('#content').css({opacity : '0.0'}).html(data).delay(50).animate({opacity : '1.0'}, 300);
                        }
                    });
                }
                else if (ButtonPressed === "No" || ButtonPressed === "Do not") {
                    SmartUnLoading();
                }
            });
        }



    }

</script>


<style>
    th {
        min-width: 150px;
    }

</style>

<!DOCTYPE html>
<html>
<head>
    <title>Template Code - Transparent Payment</title>
    <meta charset="utf-8">
    <script src="https://sdk.mercadopago.com/js/v2"></script>

</head>
<body>

</body>
</html>
