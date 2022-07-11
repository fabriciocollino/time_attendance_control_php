<?php


$T_Titulo = _('Transacciones');
$T_Script = 'transacciones';
$Item_Name = "transacciones";
$T_Link = ''; 
$T_Mensaje = '';

$T_Tipo = (isset($_REQUEST['tipo'])) ? $_REQUEST['tipo'] : '';
$T_Id = isset($_REQUEST['id']) ? (integer)$_REQUEST['id'] : 0;

$T_ItemID = isset($_REQUEST['ItemID']) ? (integer)$_REQUEST['ItemID'] : 0;
$T_FormaDePago = isset($_REQUEST['formadepago']) ? (string)$_REQUEST['formadepago'] : '';
$T_Tarjeta = isset($_REQUEST['radio-tarjeta']) ? (string)$_REQUEST['radio-tarjeta'] : '';
$T_Name = isset($_REQUEST['name']) ? (string)$_REQUEST['name'] : '';
$T_DNI = isset($_REQUEST['dni']) ? (string)$_REQUEST['dni'] : '';
$T_Card = isset($_REQUEST['card']) ? (string)$_REQUEST['card'] : '';
$T_CVV = isset($_REQUEST['cvv']) ? (integer)$_REQUEST['cvv'] : 0;
$T_Month = isset($_REQUEST['month']) ? (integer)$_REQUEST['month'] : 0;
$T_Year = isset($_REQUEST['year']) ? (integer)$_REQUEST['year'] : 0;

SeguridadHelper::Pasar(90);

switch ($T_Tipo) {
    case 'checkout':
    case 'details':
        SeguridadHelper::Pasar(90);

        $o_Transaccion = null;
        $o_Transaccion = Transaccion_L::obtenerPorId($T_Id);
        if (is_null($o_Transaccion)) die('transacción no encontrada');
        $o_TSuscripcion = null;
        $o_TSuscripcion = Suscripcion_L::obtenerPorId($o_Transaccion->getSuscripcion());
        if (is_null($o_TSuscripcion)) die('suscripción no encontrada');
        $o_TPlan = null;
        $o_TPlan = Planes_L::obtenerPorId($o_Transaccion->getPlan());
        if (is_null($o_TPlan)) die('plan no encontrado');

        break;

    case 'processCheckout':
        //checkout para suscripcion nueva
        SeguridadHelper::Pasar(90);


        $o_Transaccion = null;
        $o_Transaccion = Transaccion_L::obtenerPorId($T_ItemID);

        if (is_null($o_Transaccion)) die('transacción no encontrada');

        $o_TSuscripcion = null;
        $o_TSuscripcion = Suscripcion_L::obtenerPorId($o_Transaccion->getSuscripcion());
        if (is_null($o_TSuscripcion)) die('suscripción no encontrada');

        $o_TPlan = null;
        $o_TPlan = Planes_L::obtenerPorId($o_Transaccion->getPlan());
        if (is_null($o_TPlan)) die('plan no encontrado');



        $precio_siniva = $o_Transaccion->getMontoSinIVA();
        $precio_final = $o_Transaccion->getMonto();


        switch ($T_FormaDePago) {
            case 'credito':

                $reference = $o_TPlan->getNombre().'_'.date('YmdHis');
                $value = $precio_final;


                //creacion del token de la CC
                $parameters = array(
                    PayUParameters::PAYER_NAME => $T_Name,//Enter the payer's name here.
                    PayUParameters::PAYER_ID => $o_Cliente->getId(),//Enter the payer's ID here.
                    PayUParameters::PAYER_DNI => $T_DNI,//Enter the payer's contact document here.
                    PayUParameters::CREDIT_CARD_NUMBER => str_replace("-", "", $T_Card),//Enter the number of the credit card here
                    PayUParameters::CREDIT_CARD_EXPIRATION_DATE => $T_Year . "/" . $T_Month,//Enter the expiration date of the credit card here
                    PayUParameters::PAYMENT_METHOD => $T_Tarjeta//Enter the name of the credit card here
                );
                $response = PayUTokens::create($parameters);
                //printear($response);
                if($response){
                    $o_Tarjeta = null;
                    $tarjeta_existente=null;
                    //chequeo si la tarjeta existe, quizas ya tengo el token en la db
                    $tarjeta_existente = Tarjetas_L::obtenerPorToken($response->creditCardToken->creditCardTokenId);
                    if(is_null($tarjeta_existente)) {
                        $o_Tarjeta = new Tarjetas_O();
                        $o_Tarjeta->setCliente($o_Cliente->getId());
                        $o_Tarjeta->setCVC($T_CVV);
                        $o_Tarjeta->setMonth($T_Month);
                        $o_Tarjeta->setYear($T_Year);
                        $o_Tarjeta->setDefault(1);
                        $o_Tarjeta->setTarjeta($T_Tarjeta);
                        $o_Tarjeta->setNombre($T_Name);
                        $o_Tarjeta->setDNI($T_DNI);
                        $o_Tarjeta->setToken($response->creditCardToken->creditCardTokenId);
                        $o_Tarjeta->setMaskedNumber($response->creditCardToken->maskedNumber);
                        $o_Tarjeta->save();
                    }else{
                        $o_Tarjeta = $tarjeta_existente;
                    }

                    //ya guarde la tarjeta, ahora hago el cargo

                    $parameters = array(
                        PayUParameters::ACCOUNT_ID => $PayuACCOUNT_ID,
                        PayUParameters::REFERENCE_CODE => $reference,
                        PayUParameters::DESCRIPTION => "Plan ".$o_TPlan->getNombre(),
                        PayUParameters::VALUE => $value,
                        PayUParameters::CURRENCY => "ARS",
                        // -- Buyer --
                        PayUParameters::BUYER_NAME => $T_Name,
                        PayUParameters::BUYER_EMAIL => Config_L::p('empresa_email'),
                        PayUParameters::BUYER_CONTACT_PHONE => Config_L::p('empresa_telefono'),
                        //PayUParameters::BUYER_DNI => "5415668464654",
                        PayUParameters::BUYER_STREET => Config_L::p('empresa_direccion'),
                        //PayUParameters::BUYER_STREET_2 => "1366",
                        PayUParameters::BUYER_CITY => Config_L::p('empresa_localidad'),
                        PayUParameters::BUYER_STATE => Config_L::p('empresa_provincia'),
                        PayUParameters::BUYER_COUNTRY => "AR",
                        PayUParameters::BUYER_POSTAL_CODE => Config_L::p('empresa_codigo_postal'),
                        PayUParameters::BUYER_PHONE => Config_L::p('empresa_telefono'),
                        // -- Payer --
                        PayUParameters::PAYER_NAME => $T_Name,
                        PayUParameters::PAYER_EMAIL => Config_L::p('empresa_email'),
                        PayUParameters::PAYER_CONTACT_PHONE => Config_L::p('empresa_telefono'),
                        //PayUParameters::PAYER_DNI => "5415668464654",
                        PayUParameters::PAYER_STREET => Config_L::p('empresa_direccion'),
                        //PayUParameters::PAYER_STREET_2 => "452",
                        PayUParameters::PAYER_CITY => Config_L::p('empresa_localidad'),
                        PayUParameters::PAYER_STATE => Config_L::p('empresa_provincia'),
                        PayUParameters::PAYER_COUNTRY => "AR",
                        PayUParameters::PAYER_POSTAL_CODE => Config_L::p('empresa_codigo_postal'),
                        PayUParameters::PAYER_PHONE => Config_L::p('empresa_telefono'),
                        // -- Token Data --
                        PayUParameters::TOKEN_ID => $o_Tarjeta->getToken(),
                        PayUParameters::CREDIT_CARD_SECURITY_CODE => $o_Tarjeta->getCVC(),
                        PayUParameters::PAYMENT_METHOD => $o_Tarjeta->getTarjeta(),// "MASTERCARD" || "AMEX" || "ARGENCARD" || "CABAL" || "NARANJA" || "CENCOSUD" || "SHOPPING"
                        PayUParameters::INSTALLMENTS_NUMBER => "1",
                        PayUParameters::COUNTRY => PayUCountries::AR,
                        // -- Session data --
                        PayUParameters::DEVICE_SESSION_ID => session_id(),
                        PayUParameters::IP_ADDRESS => $_SERVER['REMOTE_ADDR'],
                        PayUParameters::PAYER_COOKIE=> session_id(),
                        PayUParameters::USER_AGENT=> $_SERVER['HTTP_USER_AGENT']
                    );
                    $response = null;
                    $response_mensaje='';
                    try {
                        $response = PayUPayments::doAuthorizationAndCapture($parameters);

                        $o_Transaccion->setMetodo($T_FormaDePago);



                        if(isset($response->code) && $response->code=='SUCCESS') {
                            //printear($response);
                            //si llego aca, la comunicacion fue correcta y la tarjeta es valida.
                            //falta comprobar si tiene fondos y si la transaccion fue exitosa.

                            if($response->transactionResponse->state=='APPROVED') {
                                $o_Transaccion->setFechaPago(date("Y-m-d H:i:s"));
                                $o_Transaccion->setEstado(TRANSACTION_APPROVED);
                                $o_Transaccion->setPayuResponce(json_encode($response));

                            }else if($response->transactionResponse->state=='DECLINED'){

                                $response_mensaje = $response->transactionResponse->responseMessage;
                                $o_Transaccion->setEstado(TRANSACTION_REJECTED);
                                $o_Transaccion->setPayuResponce(json_encode($response));
                                $response = false;

                            }else if($response->transactionResponse->state=='PENDING'){

                                $response_mensaje = $response->transactionResponse->responseMessage;
                                $o_Transaccion->setEstado(TRANSACTION_PENDING);
                                $o_Transaccion->setPayuResponce(json_encode($response));
                                $response = false;

                            }else{
                                $o_Transaccion->setEstado(TRANSACTION_REJECTED);
                                $o_Transaccion->setPayuResponce(json_encode($response));
                                $response = false;
                            }

                            $o_Transaccion->save();
                        }else{
                            //printear($response);
                            $response = false;
                        }
                    } catch (Exception $e) {
                        //print_r($e);
                        $response = false;
                    }

                    if ($response) {  //si llego aca es porque el pago se efectuo de forma correcta

                        $o_Suscripcion->setActiva(1);
                        $o_Suscripcion->setPlan($o_TPlan->getId());
                        $o_Suscripcion->save('Off');


                        $T_Mensaje = 'Muchas Gracias!!! El pago fue procesado con éxito. Su sucripción al plan '.$o_TPlan->getNombre().' ya se encuentra activa.';
                        SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_SUSCRIPCION_CHECKOUT_OK, $a_Logs_Tipos[LOG_SUSCRIPCION_CHECKOUT_OK], '<b>Id:</b> ' . $o_Transaccion->getId() . ' <b>Método:</b> ' . $o_Transaccion->getMetodo_S() . ' <b>Monto:</b> ' . $o_Transaccion->getMonto(), $o_Transaccion->getId());
                        $Mostrar_Detalles=1;
                        goto defaultlabel;
                    }else{
                        if($response_mensaje!='')
                            $T_Error = 'No hemos podido procesar su pago: '.$response_mensaje;
                        else
                            $T_Error = 'Ha ocurrido un error y no hemos podido procesar su pago.';

                        SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_SUSCRIPCION_CHECKOUT_ERROR, $a_Logs_Tipos[LOG_SUSCRIPCION_CHECKOUT_ERROR], ' <b>Método:</b> ' . $T_FormaDePago . ' <b>Monto:</b> ' . $precio_final);
                        $Mostrar_Planes=1;
                        goto defaultlabel;
                    }


                }

                goto defaultlabel;
                break;
        }


        break;

    case 'view':
        $o_Transaccion = Transaccion_L::obtenerPorId($T_Id);
        if (is_null($o_Transaccion)) {
            $o_Transaccion = new Transaccion_O();
        }
        break;


    default:
        defaultlabel:

        $o_Listado = Transaccion_L::obtenerTodos();

        $T_Link = '';
}
