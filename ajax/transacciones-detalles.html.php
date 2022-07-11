<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/transacciones.php'; ?>


<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="modalTitle">Transacción</h4>
</div>
<div class="modal-body" style="padding-top: 0;">


    <div class="row padding-10">
        <br>
        <div class="col-sm-7">

            <h4 class="semi-bold"><?php echo htmlentities(Config_L::p('empresa_nombre'), ENT_COMPAT, 'utf-8'); ?></h4>

            <address>
                <abbr title="Empresa">Empresa:</abbr>
                    <strong><?php echo htmlentities(Config_L::p('empresa_direccion'), ENT_COMPAT, 'utf-8'); ?></strong>
                <br>
                    <abbr title="Dirección">Dir:</abbr>
                    <?php echo htmlentities(Config_L::p('empresa_localidad')        , ENT_COMPAT, 'utf-8'); ?>
                    <?php echo htmlentities(Config_L::p('empresa_provincia')        , ENT_COMPAT, 'utf-8'); ?>
                    <?php echo htmlentities(Config_L::p('empresa_codigo_postal')    , ENT_COMPAT, 'utf-8'); ?>
                <br>
                    <abbr title="Teléfono">Tel:</abbr>
                    <?php echo htmlentities(Config_L::p('empresa_telefono'), ENT_COMPAT, 'utf-8'); ?>
                <br>
                    <abbr title="Email">Email:</abbr> <?php echo htmlentities(Config_L::p('empresa_email'), ENT_COMPAT, 'utf-8'); ?>
            </address>

        </div>
        <div class="col-sm-5">
            <div>
                <div>
                    <strong>CÓDIGO #:</strong><span class="pull-right"><?php echo str_pad($o_Transaccion->getId(), 5, '0', STR_PAD_LEFT); ?></span>
                </div>
            </div>
            <div>
                <div>
                    <strong>FECHA:</strong><span class="pull-right"> <i class="fa fa-calendar"></i> <?php echo $o_Transaccion->getFecha('d-m-Y'); ?></span>
                </div>
            </div>
            <div>
                <div>
                    <strong>VTO:</strong><span class="pull-right"> <i class="fa fa-calendar"></i> <?php echo $o_Transaccion->getVencimiento('d-m-Y'); ?></span>
                </div>
            </div>
            <br>

            <div class="well well-sm no-border">
                <div class="fa-lg">Total:<span class="pull-right">$<?php echo $o_Transaccion->getMonto(); ?> ARS</span></div>
            </div>
        </div>
    </div>

    <form class="smart-form" novalidate="novalidate" data-async method="post" id="editar-form"
          action="<?php echo 'ajax/transacciones.html.php' ?>?tipo=reTryCheckout">
        <input type="hidden" id="ItemID" name="ItemID" value="<?php echo $o_Transaccion->getId(); ?>">

        <fieldset>
            <div class="row padding-10">
                <table class="table">
                    <thead>
                    <tr>
                        <th>ITEM</th>
                        <th>DESCRIPCIÓN</th>
                        <th style="min-width: 70px;">PRECIO</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><strong><?php echo htmlentities($o_TPlan->getNombre(), ENT_COMPAT, 'utf-8'); ?></strong></td>
                        <td><?php echo htmlentities($o_TPlan->getDescripcion(), ENT_COMPAT, 'utf-8'); ?></a></td>
                        <td id="tdPrecioPlan" class="text-right">$<?php echo $o_Transaccion->getMonto(); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-right">Total</td>
                        <td id="tdTotal" class="text-right"><strong>$<?php echo $o_Transaccion->getMonto(); ?></strong></td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </fieldset>
    </form>

    <div class="row show-grid">
        <div class="col-xs-6 col-sm-3 col-md-3">
            <h5 class="grid-Hleft" style="margin: 5px;">Estado:</h5>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <h5 style="margin:0;">
                <small><span class="label label-<?php echo $o_Transaccion->getEstadoLabel(); ?>"
                             style="line-height: 23px;margin-left: 5px;"><?php echo $a_Estados_Transacciones[$o_Transaccion->getEstado()]; ?></span></small>
            </h5>
        </div>
    </div>
    <?php if ($o_Transaccion->getEstado() == TRANSACTION_PAID): ?>
        <div class="row show-grid">
            <div class="col-xs-6 col-sm-3 col-md-3">
                <h5 class="grid-Hleft">Fecha de Pago:</h5>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <h5 class="grid-Hright"><?php echo $o_Transaccion->getFechaPago('d-m-Y'); ?></h5>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-6 col-sm-3 col-md-3">
                <h5 class="grid-Hleft">Medio:</h5>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <?php
                $tarjeta_guardada = '';
                if($o_Transaccion->getMetodo() == 'credito_guardada' || $o_Transaccion->getMetodo() == 'credito_default'){
                    $o_Tarjeta = Tarjetas_L::obtenerPorId($o_Transaccion->getTarjetaID());
                    if(!is_null($o_Tarjeta)){
                        $tarjeta_guardada = ' <small style="font-size:10px;">('.$o_Tarjeta->getTarjeta().' '.$o_Tarjeta->getMaskedNumber().')</small>';
                    }
                }
                ?>
                <h5 class="grid-Hright"><?php echo $o_Transaccion->getMetodo_S().$tarjeta_guardada; ?></h5>
            </div>
        </div>
    <?php endif; ?>
    <br>
    <?php if ($o_Transaccion->getEstado() == TRANSACTION_REJECTED): ?>
        <div class="alert alert-info fade in">
            <i class="fa-fw fa fa-info"></i>
            <strong>Info!</strong> La transacción ha sido rechazada.<?php if($o_Transaccion->getPayuResponce()!='')echo "</br>Mensaje: ".json_decode($o_Transaccion->getPayuResponce())->transactionResponse->responseMessage; ?> </br>Por favor, intente nuevamente.
        </div>
    <?php endif; ?>

    <?php if ($o_Transaccion->getEstado() == TRANSACTION_APPROVED): ?>
        <div class="alert alert-info fade in">
            <i class="fa-fw fa fa-info"></i>
            <strong>Info!</strong> La transacción ha sido aprobada por el ente emisor de la tarjeta, pero necesita una revisión manual. Si no se soluciona en 24hs contacte con el
            staff de soporte técnico.
        </div>
    <?php endif; ?>

    <?php if ($o_Transaccion->getEstado() == TRANSACTION_PENDING): ?>
        <div class="alert alert-info fade in">
            <i class="fa-fw fa fa-info"></i>
            <strong>Info!</strong> La transacción está a la espera de la aprobación por parte del ente emisor de la tarjeta. Si no se soluciona en 24hs contacte con el staff de
            soporte técnico.
        </div>
    <?php endif; ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
        Salir
    </button>
    <?php if ($o_Transaccion->getEstado() == TRANSACTION_PENDING || $o_Transaccion->getEstado() == TRANSACTION_REJECTED): ?>
        <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-editar">Intentar de Nuevo</button> <?php endif; ?>
</div>


<script type="text/javascript">


    pageSetUp();

    $(function () {
        // Validation

        $('#pagoConTarjeta').hide();
        $('#pagoConEfectivo').hide();
        $('#pagoConTransferencia').hide();


        $('#formadepago').change(function () {

            if ($(this).find('option:selected').attr('value') === 'efectivo') {
                $('#pagoConTarjeta').hide();
                $('#pagoConEfectivo').show();
                $('#pagoConTransferencia').hide();
                $("#imgMediosDePago").attr("src", "https://static.enpuntocontrol.com/app/v1/img/medios-de-pago-efectivo-horizontal.png");

            } else if ($(this).find('option:selected').attr('value') === 'credito') {
                $('#pagoConTarjeta').show();
                $('#pagoConEfectivo').hide();
                $('#pagoConTransferencia').hide();
                $("#imgMediosDePago").attr("src", "https://static.enpuntocontrol.com/app/v1/img/medios-de-pago-credito-horizontal.png");

            } else if ($(this).find('option:selected').attr('value') === 'transferencia') {
                $('#pagoConTarjeta').hide();
                $('#pagoConEfectivo').hide();
                $('#pagoConTransferencia').show();
                $("#imgMediosDePago").attr("src", "");

            }
        });


        $("#editar-form").validate({
            rules: {
                name: {
                    required: true
                },
                card: {
                    required: true,
                    creditcard: true
                },
                cvv: {
                    required: true,
                    digits: true
                },
                month: {
                    required: true
                },
                year: {
                    required: true,
                    digits: true
                }
            },
            // Messages for form validation
            messages: {
                name: {
                    required: 'Porfavor, ingrese el nombre como aparece en la tarjeta'
                },
                card: {
                    required: 'Porfavor, ingrese los numeros de la tarjeta',
                    creditcard: 'Porfavor, ingrese un numero de tarjeta válido'
                },
                cvv: {
                    required: 'Ingrese el codigo CVV',
                    digits: 'Solo numeros'
                },
                month: {
                    required: 'Seleccione un Mes'
                },
                year: {
                    required: 'Ingrese el año',
                    digits: 'Solo numeros',
                    mask: '4 digitos, por ej: 2016'
                }
            },
            // Do not change code below
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            }
        });
    });


    $(document).ready(function () {
        $('#submit-editar').click(function () {
            var $form = $('#editar-form');
            if (!$('#editar-form').valid()) {
                return false;
            } else {
                //check
                $.ajax({
                    type: $form.attr('method'),
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    success: function (data, status) {
                        $('#editar').modal('hide');
                        function refreshpage() {
                            $('#content').css({opacity: '0.0'}).html(data).delay(50).animate({opacity: '1.0'}, 300);
                            $('body').removeData('bs.modal');
                        }

                        setTimeout(refreshpage, 200);
                    }
                });
            }

        });

        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });


    });


</script>

