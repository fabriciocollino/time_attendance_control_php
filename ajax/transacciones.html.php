<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>

<?php require_once(APP_PATH . '/libs/payu/PayU.php'); ?>

<?php require_once(APP_PATH . '/libs/random/random.php'); ?>


<?php
PayU::$apiKey = PAYU_API_KEY; //sandbox
PayU::$apiLogin = PAYU_API_LOGIN; //sandbox
$PayuACCOUNT_ID = PAYU_ACCOUNT_ID; //sandbox
PayU::$merchantId = PAYU_MERCHANT_ID; //Ingrese aquí su Id de Comercio.
PayU::$language = PAYU_LANGUAGE; //Seleccione el idioma.
PayU::$isTest = PAYU_IS_TEST; //Dejarlo True cuando sean pruebas.
Environment::setPaymentsCustomUrl(PAYU_API_PAYMENTS_URL);
Environment::setReportsCustomUrl(PAYU_API_REPORTS_URL);
Environment::setSubscriptionsCustomUrl(PAYU_SUSCRIPTIONS_URL);
?>

<?php require_once APP_PATH . '/controllers/' . basename(__FILE__, '.html.php') . '.php'; ?>

<?php require_once APP_PATH . '/includes/top-mensajes.inc.php'; ?>


<!-- Bread crumb is created dynamically -->
<!-- row -->
<div class="row">

    <!-- col -->
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-tasks"></i>
            <?php echo _('Facturación') ?>
            <span>>
                <?php echo _('Transacciones') ?>
			</span>
        </h1>
    </div>

</div>
<!-- end row -->


<!-- widget grid -->
<section id="widget-grid" class="">

    <!-- row -->
    <div class="row">

        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false"
                 data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false"
                 data-widget-sortable="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-tasks"></i> </span>
                    <h2><?php echo _('Transacciones') ?></h2>
                </header>
                <!-- widget div-->
                <div>
                    <div class="widget-body no-padding">

                        <table id="dt_basic" class="table table-striped table-hover dataTable no-footer"
                               aria-describedby="dt_basic_info" style="width: 100%;">
                            <thead>
                            <tr>
                                <th><?php echo _('Código #') ?></th>
                                <th><?php echo _('Fecha') ?></th>
                                <th><?php echo _('Vencimiento') ?></th>
                                <th><?php echo _('Plan') ?></th>
                                <th><?php echo _('Periodo') ?></th>
                                <th><?php echo _('Medio de Pago') ?></th>
                                <th data-priority="1"><?php echo _('Monto') ?></th>
                                <th data-priority="1"><?php echo _('Estado') ?></th>
                                <th><?php echo _('Opciones') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!is_null($o_Listado)): ?>

                                <?php foreach ($o_Listado as $key => $item): /* @var $item Transaccion_O */ ?>
                                    <?php $item_plan = null; ?>
                                    <?php $item_plan = Planes_L::obtenerPorId($item->getPlan()); ?>
                                    <?php
                                    $tarjeta_guardada = '';
                                    if($item->getMetodo() == 'credito_guardada' || $item->getMetodo() == 'credito_default'){
                                        $o_Tarjeta = Tarjetas_L::obtenerPorId($item->getTarjetaID());
                                        if(!is_null($o_Tarjeta)){
                                            $tarjeta_guardada = ' <small style="font-size:10px;">('.$o_Tarjeta->getTarjeta().' '.substr($o_Tarjeta->getMaskedNumber(),12).')</small>';
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo str_pad($item->getId(), 5, '0', STR_PAD_LEFT); ?></td>
                                        <td title="<?php echo $item->getFecha('d-m-Y H:i'); ?>"><?php echo $item->getFecha('d-m-Y'); ?></td>
                                        <td><?php echo $item->getVencimiento('d-m-Y'); ?></td>
                                        <td><?php if (!is_null($item_plan)) echo $item_plan->getNombre(); ?></td>
                                        <td><?php echo $item->getPeriodo_S(); ?></td>
                                        <td><?php echo $item->getMetodo_S().$tarjeta_guardada; ?></td>
                                        <td>$<?php echo $item->getMonto(); //esto va sin formato, porque el formato decimal ya viene desde el mysql ?></td>

                                        <?php /* COLOR LABEL FOR TYPE OF TRANSACTION: REMOVED AND REPLACED BY FOLLOWING LINE
                                            <td><small><span class="label label-<?php echo $item->getEstadoLabel(); ?>" style="line-height: 23px;"><?php echo $a_Estados_Transacciones[$item->getEstado()]; ?></span></small></td>
                                        */?>
                                        <td><small><span  style="line-height: 23px;"><?php echo $a_Estados_Transacciones[$item->getEstado()]; ?></span></small></td>

                                        <td style="white-space: nowrap;">

                                            <?php /* if ($item->getEstado()==TRANSACTION_PAID): ?>
                                                // TODO: DESCARGAR COMPROBANTE DE PAGO: todas las variables data-.. no son reales
                                                <button
                                                        title="Descargar Comprobante"
                                                        data-backdrop="static" data-toggle="modal" data-target="#editar" data-type="checkout"
                                                        data-id="<?php echo $item->getId(); ?>"
                                                        class="btn btn-default btn-sm fa fa-file-pdf-o"
                                                        style="line-height: .75em;"
                                                        data-lnk="ajax/transacciones-descargar.html.php"
                                                        onclick="">Descargar
                                                </button>
                                            <?php endif;  */ ?>

                                            <?php if ($item->getEstado()==TRANSACTION_UNPAID || $item->getEstado()==TRANSACTION_REJECTED ): ?>
                                                <button title="Pagar"
                                                        data-backdrop="static" data-toggle="modal" data-target="#editar" data-type="checkout"
                                                        data-id="<?php echo $item->getId(); ?>"
                                                        class="btn btn-default btn-sm fa fa-cart-arrow-down"
                                                        style="line-height: .75em;padding-left: 8px;padding-right: 8px;"
                                                        data-lnk="ajax/transacciones-checkout.html.php"
                                                        onclick="">
                                                </button>
                                            <?php endif;  ?>
                                            <?php // if ($item->getEstado()==TRANSACTION_PENDING || $item->getEstado()==TRANSACTION_REJECTED || $item->getEstado()==TRANSACTION_APPROVED || $item->getEstado()==TRANSACTION_PAID): ?>
                                                <button title="Detalles"
                                                        data-backdrop="static" data-toggle="modal" data-target="#editar" data-type="details"
                                                        data-id="<?php echo $item->getId(); ?>"
                                                        class="btn btn-default btn-sm fa fa-info"
                                                        style="line-height: .75em;padding-left: 8px;padding-right: 8px;"
                                                        data-lnk="ajax/transacciones-detalles.html.php"
                                                        onclick="">

                                                </button>
                                            <?php // endif;  ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>


                            <?php else: ?>
                            <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
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

    <?php
    //INCLUYO el js de las datatables
    require_once APP_PATH . '/includes/data_tables.js.php';
    ?>

    //esto asigna el ID al modal cada vez que se hace click en el boton
    $(document).ready(function () {

        //en esta pagina uso este metodo para bindear el click porque los botones son "cambiados/agregados" dinamicamente por el jquery mobile, despues de renderizar el dom. por eso la funcion click() no anda
        //$(document).on('click', 'a[data-toggle=modal], button[data-toggle=modal]', function (e) {
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
    });


</script>

<?php // require_once APP_PATH . '/includes/chat_widget.php'; ?>
