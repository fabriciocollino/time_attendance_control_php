<?php
require_once(dirname(__FILE__) . '/_ruta.php');


$T_Id = isset($_REQUEST['id']) ? (integer)$_REQUEST['id'] : 0;
$T_Cmd = (isset($_REQUEST['cmd'])) ? $_REQUEST['cmd'] : '';
$T_Type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';


$pubsub = new Google_Service_Pubsub($psclient);


$subscription = new Google_Service_Pubsub_Subscription();

$subscripcion = 'projects/enpunto-1286/subscriptions/clients-' . $subdominio . '-B-' . session_id();

$subscription->setName($subscripcion);
$subscription->setTopic('projects/enpunto-1286/topics/clients-' . $subdominio);


try {
    $pubsub->projects_subscriptions->create(
        $subscription->getName(),
        $subscription
    );
} catch (Google_Service_Exception $e) {
    // 409 is ok.  The subscription already exists.
    if ($e->getCode() != 409) {
        //throw $e;
    }
}


//recibe mensajes hasta que llegue un cmd_ack
$salida = 0;
while ($salida == 0) {

    $request = new Google_Service_Pubsub_PullRequest();

    $request->setReturnImmediately(false);
    $request->setMaxMessages(1);

    $optParams = array();


    try {
        /*
        Se limito el timeout del curl de pubsub a 10 segundos.
        archivo curlfactory.php
        */


        $responce = $pubsub->projects_subscriptions->pull($subscripcion, $request);

        $mensajes = $responce->getReceivedMessages();
//if($mensajes)
        $ack_ids = array();
        foreach ($mensajes as $mensaje) {

            $pub_sub_mensaje = $mensaje->getMessage();

            $att = $pub_sub_mensaje->getAttributes();
            $data = base64_decode($pub_sub_mensaje->getData());

            echo $data;

            $data = json_decode($data,true);



            if (isset( $data['data']['data'])){
                $data = implode(array_map("chr", $data['data']['data']));
                $data = json_decode($data,true);
            }



            if ($T_Type != '' && isset($att['cmd']) && $att['cmd'] == $T_Cmd && $att['type'] == $T_Type) {
                    echo json_encode(array('attributes' => $att, 'data' => $data));
                    $salida = 1;

            }
            else if (isset($att['cmd']) && $att['cmd'] == $T_Cmd) {
                    echo json_encode(array('attributes' => $att, 'data' => $data));
                    $salida = 1;

            }


            $ack_ids[] = $mensaje->getAckId();

        }

        $aknowdelegeRequest = new Google_Service_Pubsub_AcknowledgeRequest();
        $aknowdelegeRequest->setAckIds($ack_ids);

        try {
            $pubsub->projects_subscriptions->acknowledge($subscripcion, $aknowdelegeRequest);
        } catch (Exception $e) {
            $salida = 1;
            //echo $e;
        }


    } catch (Exception $e) {
        $salida = 1;
        //echo $e;
    }

}

/*

{
    "hue_id":31,"per_id":10,"hue_dedo":"8","fecha_start":1619805750
}
{
    "data":
        {
            "type":"Buffer",
            "data":[123,34,105,100,34,58,51,49,125]
        },

    "attributes":
        {
            "sess_id":"84a65aadfd722700259d3f20f580a41d",
            "uuid":"7f7c5ae7b2ee052edde11786a57f5c40",
            "cmd":"ACK_ENROLL_START",
            "cli_id":"4",
            "ver":"1.0"
        }
}

*/
