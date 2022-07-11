<?php


$T_Titulo =                 _('Tarjetas')   ;
$T_Script               =    'tarjetas'     ;
$Item_Name              =   "tarjetas"      ;
$T_Titulo_Singular      =   _('Tarjeta')    ;
$T_Titulo_Pre           =   _('la')         ;
$T_Link                 =   ''              ;
$T_Mensaje              =   ''              ;

$T_Tipo                 =   isset($_REQUEST['tipo'])              ?     $_REQUEST['tipo']                   : ''    ;
$T_Id                   =   isset($_REQUEST['id'])                ?     (integer)$_REQUEST['id']            : 0     ;

$T_ItemID               =   isset($_REQUEST['ItemID'])            ?     (integer)$_REQUEST['ItemID']        : 0     ;
$T_FormaDePago          =   isset($_REQUEST['formadepago'])       ?     (string)$_REQUEST['formadepago']    : ''    ;
$T_Tarjeta              =   isset($_REQUEST['radio-tarjeta'])     ?     (string)$_REQUEST['radio-tarjeta']  : ''    ;
$T_Name                 =   isset($_REQUEST['name'])              ?     (string)$_REQUEST['name']           : ''    ;
$T_DNI                  =   isset($_REQUEST['dni'])               ?     (string)$_REQUEST['dni']            : ''    ;
$T_Card                 =   isset($_REQUEST['card'])              ?     (string)$_REQUEST['card']           : ''    ;
$T_CVV                  =   isset($_REQUEST['cvv'])               ?     (integer)$_REQUEST['cvv']           : 0     ;
$T_Month                =   isset($_REQUEST['month'])             ?     (integer)$_REQUEST['month']         : 0     ;
$T_Year                 =   isset($_REQUEST['year'])              ?     (integer)$_REQUEST['year']          : 0     ;
$T_Default              =   isset($_REQUEST['default'])           ?     (integer)$_REQUEST['default']       : 0     ;

SeguridadHelper::Pasar(90);
/*
echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
*/
switch ($T_Tipo) {

    case 'editar':
        //checkout para suscripcion nueva
        SeguridadHelper::Pasar(90);

        $o_Tarjeta = Tarjetas_L::obtenerPorId($T_ItemID);

        if(is_null($o_Tarjeta)){
            $o_Tarjeta = new Tarjetas_O();
        }

        if($T_Card!='' && $T_CVV !=''){ //si viene la tarjeta, es porque tengo que re-actualizar el token

            $_expirationDate= $T_Year . "/" . $T_Month;

            //creacion del token de la CC
            $parameters = array(
                PayUParameters::PAYER_NAME => $T_Name,//Enter the payer's name here.
                PayUParameters::PAYER_ID => $o_Cliente->getId(),//Enter the payer's ID here.
                PayUParameters::PAYER_DNI => $T_DNI,//Enter the payer's contact document here.
                PayUParameters::CREDIT_CARD_NUMBER => str_replace("-", "", $T_Card),//Enter the number of the credit card here
                PayUParameters::CREDIT_CARD_EXPIRATION_DATE => $_expirationDate,//Enter the expiration date of the credit card here
                PayUParameters::PAYMENT_METHOD => $T_Tarjeta//Enter the name of the credit card here
            );

            // CREATE PAYU TOKEN
            try {
                $response = PayUTokens::create($parameters);
                //print_r($e);
            }
            catch (Exception $e) {
                //print_r($e);
                $response = false;
            }

            if($response){
                // $o_Tarjeta = null;
                $tarjeta_existente=null;
                //chequeo si la tarjeta existe, quizas ya tengo el token en la db
                $tarjeta_existente = Tarjetas_L::obtenerPorToken($response->creditCardToken->creditCardTokenId);

                if(is_null($tarjeta_existente)) {
                    $o_Tarjeta->setCliente($o_Cliente->getId());
                    $o_Tarjeta->setCVC($T_CVV);
                    $o_Tarjeta->setMonth($T_Month);
                    $o_Tarjeta->setYear($T_Year);
                    $o_Tarjeta->setDefault($T_Default);
                    $o_Tarjeta->setTarjeta($T_Tarjeta);
                    $o_Tarjeta->setNombre($T_Name);
                    $o_Tarjeta->setDNI($T_DNI);
                    $o_Tarjeta->setToken($response->creditCardToken->creditCardTokenId);
                    $o_Tarjeta->setMaskedNumber($response->creditCardToken->maskedNumber);
                    $o_Tarjeta->save();
                }
                else{
                    $o_Tarjeta = $tarjeta_existente;
                }

            }
        }
        // ONLY SAVE CARD
        else {
            $o_Tarjeta->setCliente($o_Cliente->getId());
            $o_Tarjeta->setMonth($T_Month);
            $o_Tarjeta->setYear($T_Year);
            $o_Tarjeta->setDefault($T_Default);
            $o_Tarjeta->setTarjeta($T_Tarjeta);
            $o_Tarjeta->setNombre($T_Name);
            $o_Tarjeta->setDNI($T_DNI);
            $o_Tarjeta->save();
        }

        goto defaultlabel;
        break;

    case 'view':
        $o_Tarjeta = Tarjetas_L::obtenerPorId($T_Id);

        if (is_null($o_Tarjeta)) {
            $o_Tarjeta = new Tarjetas_O();
        }

        break;

    default:
        defaultlabel:

        $o_Listado = Tarjetas_L::obtenerTodasPorCliente($o_Cliente->getId());

        $T_Link = '';
}
