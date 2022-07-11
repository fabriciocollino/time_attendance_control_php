<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/tarjetas.php'; ?>


<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="modalTitle">Tarjetas</h4>
</div>
<div class="modal-body" style="padding-top: 0;">


    <form class="smart-form" novalidate="novalidate" data-async method="post" id="editar-form"
          action="<?php echo 'ajax/tarjetas.html.php' ?>?tipo=editar">
        <input type="hidden" id="ItemID" name="ItemID" value="<?php echo $o_Tarjeta->getId(); ?>">

        <fieldset id="pagoConTarjeta">

            <div class="row">
                <section class="col col-12">
                    <div class="inline-group">
                        <label class="radio">
                            <input type="radio" name="radio-tarjeta" value="VISA" <?php if($o_Tarjeta->getTarjeta()=='VISA')echo 'checked=""'; ?>><i></i>Visa
                        </label>
                        <label class="radio">
                            <input type="radio" name="radio-tarjeta" value="MASTERCARD" <?php if($o_Tarjeta->getTarjeta()=='MASTERCARD')echo 'checked=""'; ?>><i></i>MasterCard
                        </label>
                        <label class="radio">
                            <input type="radio" name="radio-tarjeta" value="AMEX" <?php if($o_Tarjeta->getTarjeta()=='AMEX')echo 'checked=""'; ?>><i></i>American Express
                        </label>
                        <label class="radio">
                            <input type="radio" name="radio-tarjeta" value="NARANJA" <?php if($o_Tarjeta->getTarjeta()=='NARANJA')echo 'checked=""'; ?>><i></i>Naranja
                        </label>
                    </div>
                </section>
            </div>
            <div class="row">
                <section class="col col-5">
                    <label class="input">
                        <input type="text" name="name" placeholder="Nombre en la Tarjeta" autocomplete="cc-name" value="<?php echo $o_Tarjeta->getNombre(); ?>">
                    </label>
                </section>
                <section class="col col-3">
                    <label class="input">
                        <input type="text" name="dni" placeholder="DNI" value="<?php echo $o_Tarjeta->getDNI(); ?>">
                    </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-6">
                    <label class="input">
                        <input type="text" name="card" id="card" placeholder="Número de Tarjeta" data-mask="9999-9999-9999-9999" class="invalid" autocomplete="cc-number" value="">
                        <small style="font-size: 9px;">(dejar en blanco para conservar)</small>
                    </label>
                </section>
                <section class="col col-2">
                    <label class="input">
                        <input type="text" name="cvv" id="cvv" placeholder="CSV" data-mask="999" class="invalid" autocomplete="cc-csc" value="<?php if(strlen($o_Tarjeta->getCVC())==3) echo ''; ?>">
                        <!-- <small style="font-size: 4px;">(dejar en blanco para conservar)</small> -->
                    </label>
                </section>
            </div>

            <div class="row">
                <label class="label col col-3">Vencimiento:</label>
                <section class="col col-3">
                    <label class="select">
                        <select name="month" autocomplete="cc-exp-month">
                            <option value="00" <?php if($o_Tarjeta->getMonth()==0)echo 'selected=""'; ?> disabled="">Mes</option>
                            <option value="01" <?php if($o_Tarjeta->getMonth()==1)echo 'selected=""'; ?>>Enero</option>
                            <option value="02" <?php if($o_Tarjeta->getMonth()==2)echo 'selected=""'; ?>>Febrero</option>
                            <option value="03" <?php if($o_Tarjeta->getMonth()==3)echo 'selected=""'; ?>>Marzo</option>
                            <option value="04" <?php if($o_Tarjeta->getMonth()==4)echo 'selected=""'; ?>>Abril</option>
                            <option value="05" <?php if($o_Tarjeta->getMonth()==5)echo 'selected=""'; ?>>Mayo</option>
                            <option value="06" <?php if($o_Tarjeta->getMonth()==6)echo 'selected=""'; ?>>Junio</option>
                            <option value="07" <?php if($o_Tarjeta->getMonth()==7)echo 'selected=""'; ?>>Julio</option>
                            <option value="08" <?php if($o_Tarjeta->getMonth()==8)echo 'selected=""'; ?>>Agosto</option>
                            <option value="09" <?php if($o_Tarjeta->getMonth()==9)echo 'selected=""'; ?>>Septiembre</option>
                            <option value="10" <?php if($o_Tarjeta->getMonth()==10)echo 'selected=""'; ?>>Octubre</option>
                            <option value="11" <?php if($o_Tarjeta->getMonth()==11)echo 'selected=""'; ?>>Noviembre</option>
                            <option value="12" <?php if($o_Tarjeta->getMonth()==12)echo 'selected=""'; ?>>Diciembre</option>
                        </select> <i></i> </label>
                </section>
                <section class="col col-2">
                    <label class="input">
                        <input type="text" name="year" placeholder="Año" data-mask="2099" autocomplete="cc-exp-year" >
                    </label>
                </section>
            </div>

            <div class="row">
                <label class="label col col-3">Tarjeta Default:</label>
                <section class="col col-2">
                    <label class="select">
                        <select name="default" autocomplete="">
                            <option value="0" <?php if($o_Tarjeta->getDefault()==0)echo 'selected=""'; ?>>No</option>
                            <option value="1" <?php if($o_Tarjeta->getDefault()==1)echo 'selected=""'; ?>>Si</option>
                        </select> <i></i> </label>
                </section>
            </div>

        </fieldset>

    </form>


</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
        Salir
    </button>
    <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-editar">Aceptar</button>
</div>


<script type="text/javascript">


    pageSetUp();

    function isCCPresent() {
        return $('#card').val().length > 0;
    }

    function isCVVPresent() {
        return $('#cvv').val().length > 0;
    }

    $(function () {
        // Validation


        $("#editar-form").validate({
            rules: {
                name: {
                    required: true
                },
                card: {
                    required: isCCPresent(),
                    creditcard: isCCPresent()
                },
                cvv: {
                    required: isCVVPresent(),
                    digits: isCVVPresent()
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
                $('#editar').modal('hide');
                function showProcesando() {
                    $('#content').css({opacity: '0.0'}).html("<div style=\"padding:15px;height:75px;\"><h1 class=\"ajax-loading-animation\"><i class=\"fa fa-cog fa-spin\"></i> Procesando transacción...</h1></div>").delay(50).animate({opacity: '1.0'}, 300);
                }
                setTimeout(showProcesando, 300);
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

