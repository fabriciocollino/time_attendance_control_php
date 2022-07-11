<?php

require_once dirname(__FILE__) . '/../../_ruta.php';


// VARIABLES 
global $subdominio;
global $clienteId;
$T_Suscripciones            = array();
$T_Mensaje                  = '';
$o_Suscripcion              = null;
$o_Listado                  = array();

// VARIABLES MERCADOPAGO
$T_Tipo                     = isset($_POST['tipo'])                 ?   $_POST['tipo']                  :   "";
$modulos_permisos_id        = isset($_POST['modulos_permisos_id'])  ?   $_POST['modulos_permisos_id']         :   "";
$card_token_id              = isset($_POST['card_token_id'])        ?   $_POST['card_token_id']         :   "";
$plan_id                    = isset($_POST['plan_id'])              ?   $_POST['plan_id']               :   "";
$preapproval_plan_id        = isset($_POST['preapproval_plan_id'])  ?   $_POST['preapproval_plan_id']   :   "";
$preapproval_id             = isset($_POST['preapproval_id'])       ?   $_POST['preapproval_id']        :   "";
$application_id             = isset($_POST['application_id'])       ?   $_POST['application_id']        :   "";
$payer_email_id             = isset($_POST['payer_email'])          ?   $_POST['payer_email']           :   "julio.ganam@gmail.com";
$currency_id                = isset($_POST['currency_id'])          ?   $_POST['currency_id']           :   "ARS";
$status                     = isset($_POST['status'])               ?   $_POST['status']                :   "";
$back_url                   = isset($_POST['back_url'])             ?   $_POST['back_url']                  :   "https://www.mercadolibre.com.ar/";
$collector_id               = isset($_POST['collector_id'])         ?   $_POST['collector_id']              :   "";
$external_reference         = isset($_POST['external_reference'])   ?   $_POST['external_reference']        :   "licencia-enpunto";
$reason                     = isset($_POST['reason'])               ?   $_POST['reason']                    :   "";



switch ($T_Tipo) {

    case "crear_suscripcion_default":

        // NUEVA SUSCRIPCION
        $o_Suscripcion = New Suscripcion_O();

        // SET DE VARIABLES
        $o_Suscripcion->set_preapproval_plan_id($preapproval_plan_id);
        $o_Suscripcion->set_card_token_id($card_token_id);
        $o_Suscripcion->set_payer_email($payer_email_id);
        $o_Suscripcion->setPlan($plan_id);
        $o_Suscripcion->setCliente($clienteId);

        // NUEVA SUSCRIPCION MERCADOPAGO
        $o_Suscripcion->crear_suscripcion_Mercadopago();

        // OBTENER MODULOS_PERMISOS DEL PLAN
        $o_Modulos_Permisos             = Modulos_Permisos_L::obtenerPorId($modulos_permisos_id);
        $a_modulos_permisos             = $o_Modulos_Permisos->getArray();

        // CREAR NUEVO MODULOS_PERMISOS
        $o_Suscripcion_Modulos_Permisos = new Modulos_Permisos_O();
        $o_Suscripcion_Modulos_Permisos->loadArray($a_modulos_permisos);

        // GUARDAR MODULOS_PÉRMISOS
        if ($o_Suscripcion_Modulos_Permisos->save()){
            $o_Suscripcion->set_Modulos_Permisos_Id($o_Suscripcion_Modulos_Permisos->get_mod_id());

            // ACTIALIZAR MODULOS_PERMISOS DE CLIENTE
            global $a_modulos_permisos_Cliente;
            $a_modulos_permisos_Cliente = $a_modulos_permisos;

        }

        // GUARDAR SUSCRIPCION
        $o_Suscripcion->save();

        // RESULTADO SUSCRIPCION MERCADOPAGO
        $suscripcion_status     = $o_Suscripcion->get_status('EN');

        // RESULTADO TRANSACCION
        switch ($suscripcion_status){

            case 'authorized':
                $response['status']                 = "Pago aprobado";
                $response['message']                = "Transacción autorizada.";
                break;

            case 'in_process':
                $response['status']     = "Pago En Proceso";
                $response['message']    = "Transacción en proceso. La acreditación del pago puede tardar hasta 2 días hábiles.";

            case 'rejected':
                $response['status']     = "Pago Rechazado";
                $response['message']    = "Transacción rechazada. Comuníquese con su operador bancario para conocer más detalles.";
                break;

            default:

                break;

        }

        $response['modulos_permisos_id']    = $o_Suscripcion->get_Modulos_Permisos_Id();


        // EXIT
        echo json_encode($response);
        exit();

        break;

    case "editar_status_suscripcion":

        $access_token_suscripciones = "APP_USR-1659178055922851-080106-e68d9d6057977d7357a4517760162c5f-239560895";

        // ACCESS TOKEN MERCADOPAGO
        MercadoPago\SDK::setAccessToken($access_token_suscripciones);

        // VARIABLES
        $curl_post_data     = array(
            "status"            => $status,
            "application_id"    => $application_id
        );
        $service_url                        = 'https://api.mercadopago.com/preapproval/' . $preapproval_id;

        // CURL INIT
        $ch = curl_init($service_url);

        // CURL OPTIONS
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization: Bearer ' . $access_token_suscripciones));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // CURL EXEC
        $curl_response = curl_exec($ch);
        $response = json_decode($curl_response, true);

        // CURL CLOSE
        curl_close($ch);

        // STATUS 400: ERROR API
        if($response['status'] == 400){
            $T_Error        = _('Lo sentimos, hubo un error en la operación. (Error 400)');
            break;
        }

        // GET SUSCRIPTION
        $o_Suscripcion  = Suscripcion_L::obtenerPorIdMercadoPago($preapproval_id);

        // UPDATE SUSCRIPTION
        $o_Suscripcion->loadArrayMercadoPago($response);

        // SAVE SUSCRIPTION
        if ($o_Suscripcion->save())
            $T_Mensaje      = _('Suscripción actualizada correctamente.');
        else
            $T_Error        = _('Lo sentimos, hubo un error en la operación.');


        break;

    case "loadArrayMercadoPago":

        // GET SUSCRIPTION OBJECT
        $o_Suscripcion              = Suscripcion_L::obtenerPorIdMercadoPago($preapproval_id);
        $a_suscripcion_mercadopago  = $o_Suscripcion->update_suscripcion_MercadoPago();

        // RESULTADO SUSCRIPCION MERCADOPAGO
        $suscripcion_status         = $o_Suscripcion->get_status('EN');

        // STATUS 400: ERROR API
        if($suscripcion_status == 400){
            $T_Error        = _('Lo sentimos, hubo un error en la operación. (Error 400)');
            break;
        }

        // SAVE SUSCRIPTION
        if ($o_Suscripcion->save())
            $T_Mensaje      = _('Suscripción actualizada correctamente.');
        else
            $T_Error        = _('Lo sentimos, hubo un error en la operación.');


        break;

    case "actualizar_tarjeta_suscripcion":

        $access_token_api           = "APP_USR-1762536926307627-081006-53a401c2c4ba7bc9c1744cf8141e0881-239560895";

        /*
            curl -X POST \
            'https://api.mercadopago.com/v1/customers/{customer_id}/cards' \
            -H 'Authorization: Bearer YOUR_ACCESS_TOKEN' \
            -H 'Content-Type: application/json' \
            -d '{
          "token": "9b2d63e00d66a8c721607214ceda233a"
        }

        */
        // TOKEN PARA SUSCRIPCIONES (MERCADO PAGO)
        MercadoPago\SDK::setAccessToken($access_token_api);

        // API URL
        $service_url            = 'https://api.mercadopago.com/preapproval/' . $preapproval_id;

        // CURL DATA
        $curl_post_data         = array(
            "card_token_id"         => $card_token_id,
            "application_id"        => $payer_email_id
        );

        // CURL INIT
        $ch                     = curl_init($service_url);

        // CURL OPTIONS
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization: Bearer ' . $access_token_api));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // CURL EXEC
        $curl_response = curl_exec($ch);
        $response = json_decode($curl_response, true);

        // CURL CLOSE
        curl_close($ch);

        // RESULTADO
        $suscripcion_status     = isset($response['status']) ? $response['status'] : '';
        $suscripcion_message    = isset($response['message']) ? $response['message'] : '';

        switch ($suscripcion_status){

            case 'authorized':

                // GET SUSCRIPTION
                $o_Suscripcion  = Suscripcion_L::obtenerPorIdMercadoPago($preapproval_id);

                // UPDATE SUSCRIPTION
                $o_Suscripcion->loadArrayMercadoPago($response);

                // SAVE SUSCRIPTION
                $o_Suscripcion->save();

                $T_Mensaje     = "Pago aprobado";

                break;

            case 'rejected':
                $T_Error    = "Pago Rechazado";
                break;

            default:
                $T_Error    = "Transacción rechazada. Comuníquese con su operador bancario para conocer más detalles.";

                break;

        }


        break;

    case "getDetallePagoMercadoPago":
        // GET SUSCRIPTION OBJECT
        $o_Suscripcion              = Suscripcion_L::obtenerPorIdMercadoPago($preapproval_id);

        // printear con payer id
        $o_Listado = $o_Suscripcion->getDetallePagoMercadoPagoSearch();

        break;

    default:
        // GET CLIENT SUSCRIPTIONS
        $T_Suscripciones = Suscripcion_L::obtenerTodosPorClienteId($clienteId);
        break;

}



