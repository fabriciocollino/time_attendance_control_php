<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>


<style>

    body {
        background-color: #fff;
        width: auto;
        height: auto;
        font-family: "Helvetica Neue",Helvetica,sans-serif;
        color: RGBA(0,0,0,0.8);
    }

    main {
        margin: 4px 0 0px 0;
        min-height: 90%;
        padding-bottom: 100px;
    }

    .hidden {
        display: none
    }

    /* Shopping Cart Section - Start */
    .shopping-cart {
        padding-bottom: 10px;
        overflow:hidden;
        transition: max-height 5s ease-in-out;
    }

    .shopping-cart.hide {
        max-height: 0;
        pointer-events: none;
    }

    .shopping-cart .content {
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.075);
        background-color: white;
    }

    .shopping-cart .block-heading {
        padding-top: 40px;
        margin-bottom: 30px;
        text-align: center;
    }

    .shopping-cart .block-heading p {
        text-align: center;
        max-width: 600px;
        margin: auto;
    }

    .shopping-cart .block-heading h1,
    .shopping-cart .block-heading h2,
    .shopping-cart .block-heading h3 {
        margin-bottom: 1.2rem;

    }

    .shopping-cart .items {
        margin: auto;
    }

    .shopping-cart .items .product {
        margin-bottom: 0px;
        padding-top: 20px;
        padding-bottom: 20px;
    }

    .shopping-cart .items .product .info {
        padding-top: 0px;
        text-align: left;
    }

    .shopping-cart .items .product .info .product-details .product-detail {
        padding-top: 40px;
        padding-left: 40px;
    }

    .shopping-cart .items .product .info .product-details h5 {
        font-size: 19px;
    }

    .shopping-cart .items .product .info .product-details .product-info {
        font-size: 15px;
        margin-top: 15px;
    }

    .shopping-cart .items .product .info .product-details label {
        width: 50px;
        font-size: 19px;
    }

    .shopping-cart .items .product .info .product-details input {
        width: 80px;
    }

    .shopping-cart .items .product .info .price {
        margin-top: 15px;
        font-weight: bold;
        font-size: 22px;
    }

    .shopping-cart .summary {
        border-top: 2px solid #C6E9FA;
        background-color: #f7fbff;
        height: 100%;
        padding: 30px;
    }

    .shopping-cart .summary h3 {
        text-align: center;
        font-size: 1.3em;
        font-weight: 400;
        padding-top: 20px;
        padding-bottom: 20px;
    }

    .shopping-cart .summary .summary-item:not(:last-of-type) {
        padding-bottom: 10px;
        padding-top: 10px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .shopping-cart .summary .text {
        font-size: 1em;
        font-weight: 400;
    }

    .shopping-cart .summary .price {
        font-size: 1em;
        float: right;
    }

    .shopping-cart .summary button {
        margin-top: 20px;
    }

    @media (min-width: 768px) {

        .shopping-cart .items .product .info .product-details .product-detail {
            padding-top: 40px;
            padding-left: 40px;
        }

        .shopping-cart .items .product .info .price {
            font-weight: 500;
            font-size: 22px;
            top: 17px;
        }

        .shopping-cart .items .product .info .quantity {
            text-align: center;
        }

        .shopping-cart .items .product .info .quantity .quantity-input {
            padding: 4px 10px;
            text-align: center;
        }
    }

    /* Card Payment Section - Start */
    .container__payment {
        display: none;
    }

    .payment-form {
        padding-bottom: 10px;
        margin-right: 15px;
        margin-left: 15px;
        font-family: "Helvetica Neue",Helvetica,sans-serif;
    }

    .payment-form.dark {
        background-color: #f6f6f6;
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
        border-top: 2px solid #C6E9FA;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.075);
        background-color: #ffffff;
        padding: 0;
        max-width: 600px;
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

    /* Payment Result Section - Start */
    .container__result {
        display: none;
    }

    .modal-dialog {
        width: 100% !important;
    }
</style>

<!-- CERRAR MODAL -->
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="modalTitle">Nueva Suscripción</h4>
</div>

<!-- PLANES -->
<div class="modal-body" style="padding-top: 0;">

    <!-- Shopping Cart TEST -->
    <section class="shopping-cart dark">
        <div class="container container__cart">
            <div class="block-heading">
                <h2>Nueva Suscripción</h2>
                <p>Seleccione el plan al cual desea suscribirse</p>
            </div>
            <div class="content">
                <div class="row justify-content-md-center text-center">
                    <div class="col-md-12 col-lg-12">


                        <?php

                        $o_Listado = Planes_L::obtenerTodosArray();

                        foreach ($o_Listado as $_itemKey => $_item)  {

                            $o_Plan = new Planes_O();
                            $o_Plan->loadArray($_item);

                            ?>


                            <div class="items">
                                <div class="product">
                                    <div class="info">
                                        <div class="product-details">
                                            <div class="row justify-content-md-center text-center">


                                                <!-- IMAGEN PLAN -->

                                                <div class="col-md-4 product-detail">
                                                    <img class="img-fluid mx-auto d-block image" src="suscripcion-mensual.png">
                                                </div>


                                                <!-- DETALLE PLAN -->
                                                <div class="col-md-4 product-detail">

                                                    <!-- NOMBRE -->
                                                    <h5><?php echo $o_Plan->getNombre(); ?></h5>

                                                    <!-- DETALLE -->
                                                    <div class="product-info">
                                                        <p>
                                                            <!-- DESCRIPCIÓN -->
                                                            <b>Descripción: </b>
                                                            <span>
                                                                <?php echo $o_Plan->getDescripcion(); ?>
                                                                <br>
                                                            </span>

                                                            <!-- CARACTERISTICAS -->
                                                            <b>Características: </b>
                                                            <span>
                                                                <?php echo $o_Plan->getCaracteristicas(); ?>
                                                                <br>
                                                            </span>


                                                            <!-- FRECUENCIA COBRO -->
                                                            <b>Tipo de Suscripcion: </b>
                                                            <span>
                                                                <?php echo $o_Plan->getFrecuencia('AR'); ?>
                                                                <br>
                                                             </span>

                                                            <!-- PRECIO -->
                                                            <b>Precio:</b> $
                                                            <span>
                                                                <?php echo $o_Plan->getMonto() . " " . $o_Plan->getTipoMoneda(); ?>
                                                                <br>
                                                            </span>

                                                            <!-- PERIODO DE PRUEBA -->
                                                            <b>Periodo de Prueba:</b>
                                                            <span>
                                                                <?php echo $o_Plan->getPeriodoPrueba(); ?>
                                                                <br>
                                                            </span>
                                                        </p>
                                                    </div>


                                                </div>

                                                <div class="col-md-3 product-detail">
                                                    <br><br><br>
                                                    <input type     ="radio"
                                                           name     ="checkout-plan-radio"
                                                           onchange ="updatePlanSelected('<?php echo $o_Plan->getNombre(); ?>', 1 ,
                                                                   '<?php echo $o_Plan->getMonto(); ?>',
                                                                   '<?php echo $o_Plan->getMercadopagoPlanId(); ?>',
                                                                   '<?php echo $o_Plan->getId(); ?>',
                                                                   '<?php echo $o_Plan->get_Modulos_Permisos_Id(); ?>'
                                                                   )">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php   } ?>

                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<!-- PAGO -->
<section class="payment-form dark">
    <div class="container__payment">
        <div class="block-heading">
            <h2>Mercado Pago</h2>
            <p>Complete los datos del pago</p>
        </div>
        <div class="form-payment">
            <div class="products">
                <h2 class="title">Resumen</h2>
                <div class="item">
                    <input type="hidden" id="checkout_preapproval_plan_id" />
                    <input type="hidden" id="checkout_plan_id" />
                    <input type="hidden" id="checkout_modulos_permisos_id" />



                    $ <span id="summary-price" value="1"></span>
                    x <span  id="summary-quantity" value="1"></span>
                    <p class="item-name"><span id="product-description"></span></p>
                </div>
                <div class="total">Total<span class="price" id="summary-total"></span></div>
            </div>
            <div class="payment-details">
                <form id="form-checkout" class="smart-form">
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

                    <!-- AMOUNT, DESCRIPTION, PAGAR, VOLVER -->
                    <div class="row">
                        <section class="col-sm-12">
                            <input type="hidden" id="amount" />
                            <input type="hidden" id="description" />
                            <br>
                            <button id="form-checkout__submit" type="submit" class="btn btn-primary btn-block">Pagar</button>
                            <br>
                            <a id="go-back">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 10 10" class="chevron-left">
                                    <path fill="#009EE3" fill-rule="nonzero"id="chevron_left" d="M7.05 1.4L6.2.552 1.756 4.997l4.449 4.448.849-.848-3.6-3.6z"></path>
                                </svg>
                                Volver a la selección de planes
                            </a>
                        </section>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<!-- RESULTADO TRANSACCION -->
<section class="shopping-cart dark">
    <div class="container__result">
        <div class="block-heading">
            <h2>Resultado de la Transacción</h2>
            <!--<p>This is an example of a Mercado Pago integration</p>        -->
        </div>
        <div class="content">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="items product info product-details">
                        <div class="row justify-content-md-center text-center">
                            <div class="col-md-1 product-detail"></div>
                            <div class="col-md-4 product-detail">
                                <div class="product-info">
                                    <br>
                                    <p><b>Estado: </b><span id="payment-status"></span></p>
                                    <p><b>Detalle: </b><span id="payment-detail"></span></p>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="modal-footer">

    <button type="button" class="btn btn-default" data-dismiss="modal">
        Salir
    </button>
    <button type="submit" class="btn btn-primary" id="checkout-btn-form">
        Continuar
    </button>
</div>


<script>

    document.getElementById("checkout-btn-form").setAttribute('disabled', true);

    // PLAN SELECCIONADO
    function updatePlanSelected(checkout_description,checkout_quantity,checkout_price,checkout_preapproval_plan_id,checkout_plan_id,checkout_modulos_permisos_id) {

        // TODO AGREGAR PLAN ID
        console.log("checkout_description",checkout_description);
        console.log("checkout_quantity",checkout_quantity);
        console.log("checkout_price",checkout_price);
        console.log("checkout_preapproval_plan_id",checkout_preapproval_plan_id);
        console.log("checkout_plan_id",checkout_plan_id);
        console.log("checkout_modulos_permisos_id",checkout_modulos_permisos_id);

        document.getElementById("product-description").innerHTML            =   checkout_description;
        document.getElementById("summary-quantity").innerHTML               =   checkout_quantity;
        document.getElementById("summary-price").innerHTML                  =   checkout_price;
        document.getElementById("checkout_preapproval_plan_id").value       =   checkout_preapproval_plan_id;
        document.getElementById("checkout_plan_id").value                   =   checkout_plan_id;
        document.getElementById("checkout_modulos_permisos_id").value       =   checkout_modulos_permisos_id;


        let amount                                                          = parseInt(checkout_price) * parseInt(checkout_quantity);
        document.getElementById("summary-total").innerHTML                  =   "$ " + amount;
        document.getElementById('amount').value                             = amount;
        document.getElementById("checkout-btn-form").removeAttribute("disabled");

    }

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
                    var client_data                     = cardData;
                    console.log('client_data: ', client_data)


                    // CREATE NEW SUSCRIPTION
                    client_data.tipo = "crear_suscripcion_default";
                    //client_data.tipo = "crear_suscripcion_custom";

                    client_data.cardholderName          = document.getElementById('form-checkout__cardholderName').value;
                    client_data.payer_email             = document.getElementById('form-checkout__cardholderEmail').value;
                    client_data.preapproval_plan_id     = document.getElementById('checkout_preapproval_plan_id').value;
                    client_data.plan_id                 = document.getElementById('checkout_plan_id').value;
                    client_data.modulos_permisos_id     = document.getElementById('checkout_modulos_permisos_id').value;

                    $.ajax({
                        type:"POST",
                        url: "ajax/mercadopago_suscripcion.html.php",
                        data:{
                            tipo                    : client_data.tipo,
                            card_token_id           : client_data.token,
                            payer_email             : client_data.payer_email,
                            preapproval_plan_id     : client_data.preapproval_plan_id,
                            plan_id                 : client_data.plan_id,
                            modulos_permisos_id     : client_data.modulos_permisos_id
                        },

                        success: function (data,status) {

                            // PRINT CONSOLE
                            console.log("data",data);
                            console.log("status",status);

                            var mensaje = JSON.parse(data);
                            console.log("mensaje",mensaje);

                            document.getElementById("payment-status").innerText = mensaje.status;
                            document.getElementById("payment-detail").innerText = mensaje.message;
                            $('.container__payment').fadeOut(500);
                            setTimeout(() => { $('.container__result').show(500).fadeIn(); }, 500);
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

    //Handle transitions
    document.getElementById('checkout-btn-form').addEventListener('click', function(){

        const continuarButton = document.getElementById("checkout-btn-form");
        continuarButton.setAttribute('disabled', true);

        $('.container__cart').fadeOut(500);
        setTimeout(() => {
            loadCardForm();
            $('.container__payment').show(500).fadeIn();
        }, 500);
    });
    document.getElementById('go-back').addEventListener('click', function(){
        $('.container__payment').fadeOut(500);
        setTimeout(() => { $('.container__cart').show(500).fadeIn(); }, 500);
    });

    $('#editar').on('hidden.bs.modal', function () {
        location.reload();
    });


</script>

