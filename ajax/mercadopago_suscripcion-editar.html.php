<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>

<?php
$preapproval_id       = isset($_POST['preapproval_id']) ?   $_POST['preapproval_id'] :   '';
$o_Suscripcion        =  Suscripcion_L::obtenerPorIdMercadoPago($preapproval_id);
if (is_null($o_Suscripcion)){
    $o_Suscripcion = new Suscripcion_O();
}
?>

<style>

    body {
        background-color: #fff;
        width: auto;
        height: auto;
        font-family: "Helvetica Neue",Helvetica,sans-serif;
        color: RGBA(0,0,0,0.8);
    }

    /*
    main {
        margin: 4px 0 0px 0;
        min-height: 90%;
        padding-bottom: 100px;
    }*/

    .hidden {
        display: none
    }



    .payment-form {
        padding-bottom: 10px;
        margin-right: 15px;
        margin-left: 15px;
        font-family: "Helvetica Neue",Helvetica,sans-serif;
    }


    .payment-form .content {
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.075);
        background-color: white;
    }

    .payment-form .block-heading {
        padding-top: 40px;
        margin-bottom: 30px;
        text-align: center;
    }

    .payment-form .block-heading p {
        text-align: center;
        max-width: 420px;
        margin: auto;
        color: RGBA(0,0,0,0.45);
    }

    .payment-form .block-heading h1,
    .payment-form .block-heading h2,
    .payment-form .block-heading h3 {
        margin-bottom: 1.2rem;
    }

    .payment-form .form-payment {
        /*border-top: 2px solid #C6E9FA;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.075);
        max-width: 600px;*/
        background-color: #ffffff;
        padding: 0;
        margin: auto;
    }

    .payment-form .title {
        font-size: 1em;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        margin-bottom: 0.8em;
        font-weight: 400;
        padding-bottom: 8px;
    }

    .payment-form .products {
        background-color: #f7fbff;
        padding: 25px;
    }

    .payment-form .products .item {
        margin-bottom: 1em;
    }

    .payment-form .products .item-name {
        font-weight: 500;
        font-size: 0.9em;
    }

    .payment-form .products .item-description {
        font-size: 0.8em;
        opacity: 0.6;
    }

    .payment-form .products .item p {
        margin-bottom: 0.2em;
    }

    .payment-form .products .price {
        float: right;
        font-weight: 500;
        font-size: 0.9em;
    }

    .payment-form .products .total {
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        margin-top: 10px;
        padding-top: 19px;
        font-weight: 500;
        line-height: 1;
    }

    .payment-form .payment-details {
        padding: 25px 25px 15px;
        height: 100%;
    }

    .payment-form .payment-details label {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #8C8C8C;
        text-transform: uppercase;
    }

    .payment-form .payment-details button {
        margin-top: 0.6em;
        padding: 12px 0;
        font-weight: 500;
        margin-bottom: 10px;
    }

    .payment-form .date-separator {
        margin-left: 10px;
        margin-right: 10px;
        margin-top: 5px;
    }

    .payment-form a, .payment-form a:not([href]) {
        margin: 0;
        padding: 0;
        font-size: 13px;
        cursor:pointer;
    }

    .payment-form a:not([href]):hover{
        cursor:pointer;
    }

    footer {
        padding: 2% 10% 6% 10%;
        margin: 0 auto;
        position: relative;
    }

    #horizontal_logo {
        width: 150px;
        margin: 0;
    }

    footer p a {
        text-decoration: none;
    }

    footer p a:hover {
        text-decoration: none;
    }

    @media (min-width: 576px) {
        .payment-form .title {
            font-size: 1.2em;
        }

        .payment-form .products {
            padding: 40px;
        }

        .payment-form .products .item-name {
            font-size: 1em;
        }

        .payment-form .products .price {
            font-size: 1em;
        }

        .payment-form .payment-details {
            padding: 40px 40px 30px;
        }

        .payment-form .payment-details button {
            margin-top: 1em;
            margin-bottom: 15px;
        }

        .footer_logo {
            margin: 0 0 0 0;
            width: 20%;
            text-align: left;
            position: absolute;
        }

        .footer_text {
            margin: 0 0 0 65%;
            width: 200px;
            text-align: left;
            position: absolute
        }

        footer p {
            padding: 1px;
            font-size: 13px;
            color: RGBA(0,0,0,0.45);
            margin-bottom: 0;
        }
    }

    @media (max-width: 576px) {
        footer {
            padding: 5% 1% 15% 1%;
            height: 55px;
        }

        footer p {
            padding: 1px;
            font-size: 11px;
            margin-bottom: 0;
        }
        .footer_text {
            margin: 0 0 0 45%;
            width: 180px;
            position: absolute
        }

        .footer_logo {
            margin: 0 0 0 0;
            position: absolute;
        }

    }

</style>

<!-- CERRAR MODAL -->
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="modalTitle">Editar Suscripción</h4>
</div>

<div class="modal-body">
    <section class="payment-form dark">
        <div class="container__payment">
            <div class="form-payment">

                <!-- RESUMEN -->
                <div class="products">
                    <h2 class="title">Resumen</h2>
                    <div class="item">
                        $ <span id="summary-price"      value="1"></span>
                        x <span id="summary-quantity"   value="1"></span>
                        <p class="item-name"><span id="product-description"></span></p>
                    </div>
                    <div class="total">Total <span class="price" id="summary-total"></span></div>
                </div>

                <!-- INFORMACIÓN CLIENTE, DATOS DE LA TARJETA, BOTÓN PAGAR -->
                <div class="payment-details">
                    <form id="form-checkout" class="smart-form">

                                                                    <!-- INFORMACIÓN CLIENTE -->
                        <h3 class="title">Información del Cliente</h3>

                        <!-- EMAIL  -->
                        <div class="row">
                            <section class="col col-sm-6">
                                <label class="input">
                                    <input type="email" name="cardholderEmail" id="form-checkout__cardholderEmail" class="form-control" />
                                </label>
                            </section>
                        </div>

                        <!-- DNI -->
                        <div class="row">
                            <section class="col col-sm-6">
                                <label class = "select">
                                    <select id="form-checkout__identificationType" name="identificationType" class="form-control"></select><i></i>
                                </label>
                            </section>

                            <section class="col col-sm-6">
                                <label class="input">
                                    <input  type="text" name="docNumber" id="form-checkout__identificationNumber" class="form-control"/>
                                </label>
                            </section>
                        </div>


                                                                    <!-- DATOS DE LA TARJETA -->
                        <h3 class="title">Datos de la Tarjeta</h3>

                        <!-- NOMBRE TITULAR -->
                        <div class="row">
                            <section class="col col-sm-12">
                                <label class="input">
                                    <input id="form-checkout__cardholderName" name="cardholderName" type="text" class="form-control"/>
                                </label>
                            </section>
                        </div>

                        <!-- NÚMERO TARJETA, MM, AA -->
                        <div class="row">

                            <!-- NÚMERO TARJETA -->
                            <section class="col col-sm-6">
                                <label class="input">
                                    <input id="form-checkout__cardNumber" name="cardNumber" type="text" class="form-control"/>
                                </label>
                            </section>

                            <!-- MM -->
                            <section class="col col-sm-2">
                                <label class="input">
                                    <input id="form-checkout__cardExpirationMonth" name="cardExpirationMonth" type="text" class="form-control"/>
                                </label>
                            </section>


                            <!-- AA -->
                            <section class="col col-sm-2">
                                <label class="input">
                                    <input id="form-checkout__cardExpirationYear" name="cardExpirationYear" type="text" class="form-control"/>
                                </label>
                            </section>

                            <!-- CVC -->
                            <section class="col col-sm-2">
                                <label class="input">
                                    <input id="form-checkout__securityCode" name="securityCode" type="text" class="form-control"/>
                                </label>
                            </section>

                        </div>

                        <!-- CVC -->
                        <div class="row">


                        </div>

                        <!-- CUOTAS -->
                        <div class="row">
                            <section class="col col-sm-12">
                                <label class="select">
                                    <select id="form-checkout__installments" name="installments" type="text" class="form-control"></select><i></i>
                                </label>
                            </section>
                        </div>

                        <!-- ISSUER -->
                        <div class="row">
                            <section class="col col-sm-6">
                                <label class="select">
                                    <select id="form-checkout__issuer" name="issuer" class="form-control"></select><i></i>
                                </label>
                            </section>
                        </div>


                                                                    <!-- BOTÓN PAGAR -->
                        <div class="row hidden">
                            <section class="col-sm-12">

                                <input type="hidden" id="amount" />
                                <input type="hidden" id="application_id"/>
                                <input type="hidden" id="preapproval_id"/>


                                <br>
                                <button id="form-checkout__submit" type="submit" class="btn btn-primary btn-block">Pagar</button>
                                <br>

                            </section>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>


<div class="modal-footer">

    <button type="button" class="btn btn-default" data-dismiss="modal">
        Salir
    </button>
    <button type="submit" class="btn btn-primary" id="submit-editar" data-dismiss="modal">
        Confirmar Pago
    </button>
</div>


<script>

    document.getElementById("form-checkout__submit").setAttribute('disabled', true);

    // SET SUSCRIPCION DATA
    document.getElementById("form-checkout__cardholderEmail").value =   '<?php echo $o_Suscripcion->get_payer_email();?>';
    document.getElementById("product-description").innerHTML        =   '<?php echo $o_Suscripcion->get_reason();?>';
    document.getElementById("summary-quantity").innerHTML           =   '<?php echo $o_Suscripcion->get_summarized_charged_quantity(); ?>';
    document.getElementById("summary-price").innerHTML              =   '<?php echo $o_Suscripcion->get_auto_recurring_transaction_amount(); ?>';
    document.getElementById("preapproval_id").value                 =   '<?php echo $o_Suscripcion->get_preapproval_id(); ?>';
    document.getElementById("summary-total").innerHTML              =   '<?php echo $o_Suscripcion->get_precio(); ?>';
    document.getElementById('amount').value                         =   '<?php echo $o_Suscripcion->get_auto_recurring_transaction_amount(); ?>';
    document.getElementById('application_id').value                 =   '<?php echo $o_Suscripcion->get_application_id(); ?>';


    const mp = new MercadoPago('APP_USR-484ea094-15a8-4a24-9151-309d131193d0', {
        locale: 'es-AR'
    })

    function loadCardForm() {
        const productCost = document.getElementById('amount').value;

        const cardForm = mp.cardForm({
            amount: productCost,
            autoMount: true,
            form: {
                id: "form-checkout",
                cardholderName: {
                    id: "form-checkout__cardholderName",
                    placeholder: "Nombre del titular",
                },
                cardholderEmail: {
                    id: "form-checkout__cardholderEmail",
                    placeholder: "E-mail",
                },
                cardNumber: {
                    id: "form-checkout__cardNumber",
                    placeholder: "Número de tarjeta",
                },
                cardExpirationMonth: {
                    id: "form-checkout__cardExpirationMonth",
                    placeholder: "MM",
                },
                cardExpirationYear: {
                    id: "form-checkout__cardExpirationYear",
                    placeholder: "AA",
                },
                securityCode: {
                    id: "form-checkout__securityCode",
                    placeholder: "CVC",
                },
                installments: {
                    id: "form-checkout__installments",
                    placeholder: "Cuotas",
                },
                identificationType: {
                    id: "form-checkout__identificationType",
                },
                identificationNumber: {
                    id: "form-checkout__identificationNumber",
                    placeholder: "Número de documento",
                },
                issuer: {
                    id: "form-checkout__issuer",
                    placeholder: "Banco Emisor",
                },
            },
            callbacks: {
                onFormMounted: error => {
                    if (error)
                        return console.warn("Form Mounted handling error: ", error);
                    console.log("Form mounted");
                },
                onSubmit: (event) => {

                    event.preventDefault();
                    const cardData = cardForm.getCardFormData();

                    // VARIABLES
                    var client_data                             = cardData;
                    client_data.tipo                            = "actualizar_tarjeta_suscripcion";
                    client_data.payer_email                     = document.getElementById('form-checkout__cardholderEmail').value;
                    client_data.preapproval_id                  = document.getElementById('preapproval_id').value;
                    client_data.application_id                  = document.getElementById('application_id').value;

                    console.log('client_data',client_data);

                    $('.modal-content').html("<div style=\"padding:15px;height:75px;\"><h1 class=\"ajax-loading-animation\"><i class=\"fa fa-cog fa-spin\"></i> Procesando Pago...</h1></div>");

                    // UPDATE CARD SUSCRIPTION
                    $.ajax({
                        type:"POST",
                        url: "ajax/mercadopago_suscripcion.html.php",
                        data:{
                            tipo                    : client_data.tipo,
                            payer_email             : client_data.payer_email,
                            preapproval_id          : client_data.preapproval_id,
                            application_id          : client_data.application_id,
                            card_token_id           : client_data.token
                        },
                        success: function (data,status) {

                            // PRINT CONSOLE
                            console.log("data",data);
                            console.log("status",status);

                            $('#editar').modal('hide');
                            $('#content').css({opacity: '0.0'}).html(data).delay(50).animate({opacity: '1.0'}, 300);


                        }
                    });



                },
                onFetching: (resource) => {
                    console.log("Fetching resource: ", resource);
                    const payButton = document.getElementById("form-checkout__submit");
                    payButton.setAttribute('disabled', true);
                    return () => {
                        payButton.removeAttribute("disabled");
                    };
                },
            },
        });
    };


    loadCardForm();


    $('#submit-editar').click(function(e){
        //e.preventDefault();
        $('#form-checkout__submit').click();
    });

    $('#editar').on('hidden.bs.modal', '.modal', function () {
       $(this).removeData('bs.modal');
    });



</script>

