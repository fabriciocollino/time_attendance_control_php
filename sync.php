<?php

ob_start();




function errorCuliado($errno, $errstr, $errfile, $errline){

    syslog(LOG_INFO, "Error: " . $errno . " " . $errstr . " en la linea " . $errline . " del archivo " . $errfile);
}

set_error_handler("errorCuliado");


$_SERVER['X-Appengine-Cron'] = true; //como este archivo se llama mediante un webhook, le pongo esto para que crea que es como un cron


if($_REQUEST['tokenSuperSeguro']!='hue395257d89658016985688c190b2896ca383')die('no vieja jajaj');



require_once(dirname(__FILE__) . '/_ruta.php');


use google\appengine\api\cloud_storage\CloudStorageTools;
use google\appengine\api\taskqueue\PushTask;
use google\appengine\api\taskqueue\PushQueue;

//********************************************************************************************************************//
//********************************************************************************************************************//
//************************     INICIO DEL PROCESO DE RECUPERACION DE MENSAJES     ************************************//
//********************************************************************************************************************//
//********************************************************************************************************************//

$inputJSON = file_get_contents('php://input');
$request = json_decode($inputJSON, true);

//die("ok");

$subscription = $request['subscription'];
$message = '';
$message_id = '';
$data = array();
$attributes = array();
$publish_time = '';

//$request['message'] = $request['data'];

if (!isset($request['message']['data'])) {
    http_response_code(400);
}

$message_decoded64 = base64_decode($request['message']['data']);
$message_decodedJson = json_decode($message_decoded64,true);




$message_id         = $request['message']['message_id'];
$publish_time       = $request['message']['publish_time'];
$attributes         = $message_decodedJson['attributes'];

$data = implode(array_map("chr", $message_decodedJson['data']['data']));
$data = json_decode($data,true);


//$data = json_decode($data);

//$data               = $data_decodedJson['data'];

$cli_id = null;
if (isset($attributes['cli_id']))
    $cli_id = $attributes['cli_id'];
$eq_uuid = null;
if (isset($attributes['uuid']))
    $eq_uuid = $attributes['uuid'];
$cmd = null;
if (isset($attributes['cmd']))
    $cmd = $attributes['cmd'];
$type = null;
if (isset($attributes['type']))
    $type = $attributes['type'];






syslog(LOG_INFO,"CLI_ID: ".$cli_id);
syslog(LOG_INFO,"UUID: ".$eq_uuid);
syslog(LOG_INFO,"CMD: ".$cmd);
syslog(LOG_INFO,"TYPE: ".$type);
syslog(LOG_INFO,"message_id: ".$message_id);


//outputeo el mensaje, reemplazando el data por el contenido decodificado
$request['message']['data'] = $data;
print_r($data);


//********************************************************************************************************************//
//********************************************************************************************************************//
//**************************************     CHEQUEO DE SETUP INICIAL     ********************************************//
//********************************************************************************************************************//
//********************************************************************************************************************//

if($cli_id=='0'){
    echo('setup inicial');

    $subdominio = 'manager';

    $o_Equipo = Equipo_L::obtenerPorUUID($eq_uuid);
    if (is_null($o_Equipo)) {
        echo('No se pudo encontrar el equipo');
    }else{

        if($o_Equipo->getPassword()=='') {
            $o_Equipo->setPassword(mt_rand(100000, 999999));
            $o_Equipo->save();
        }

        PubSubHelper::sendMessage(CMD_FIRST_START, json_encode(array('key'=>$o_Equipo->getPassword())), $eq_uuid);

    }



    syslog(LOG_INFO, ob_get_contents());
    ob_end_flush();
    die();
}


//********************************************************************************************************************//
//********************************************************************************************************************//
//***************************     INICIO DEL PROCESO DE LOGUEO DEL CLIENTE     ***************************************//
//********************************************************************************************************************//
//********************************************************************************************************************//

$o_Cliente = null;
$o_Cliente = Cliente_L::obtenerPorId($cli_id);
if (is_null($o_Cliente)) {
    echo $cli_id;
    echo('No se pudo loguear el cliente');
    syslog(LOG_INFO, ob_get_contents());
    ob_end_flush();
    die();
}

$subdominio = $o_Cliente->getSubdominio();

$G_DbConn1 = new mySQL(
    $o_Cliente->getDBname(),
    $o_Cliente->getDBuser(),
    $o_Cliente->getDBpass(),
    $o_Cliente->getDBhost(),
    $o_Cliente->getDBport()
);

syslog(LOG_INFO,"SUB_DOM: ".$subdominio);

if (!$G_DbConn1->ConectarSocket()) {
    echo($G_DbConnMGR->get_Error($Registry->general['debug']));

    syslog(LOG_INFO, ob_get_contents());
    ob_end_flush();
    die();
}

Registry::getInstance()->DbConn = $G_DbConn1;

//TIMEZONE
$timezone = Config_L::p('timezone');
date_default_timezone_set($timezone);  //php timezone
Registry::getInstance()->DbConn->Query("SET time_zone = '" . $timezone . "';");   //sql timezone

//********************************************************************************************************************//
//********************************************************************************************************************//
//****************************     INICIO DEL PROCESO DE SINCRONIZACION     ******************************************//
//********************************************************************************************************************//
//********************************************************************************************************************//


switch ($cmd) {
    case CMD_SYNC:
        switch ($type) {
            case TYPE_PERSON:
                break;//type person
            case TYPE_FINGERPRINT:
                break;//type fingerprint
            case TYPE_NORMAL_HOURS:
                break;//type normal hours
            case TYPE_FLEX_HOURS:
                break;//type flex hours
            case TYPE_ROTATIVE_HOURS:
                break;//type rotative hours
            case TYPE_COMPANIES:
                break;//type companies
            case TYPE_GROUPS:
                break;//type groups
            case TYPE_GROUPS_PERSONS:
                break;//type groups persons
            case TYPE_HOLIDAYS:
                break;//type holydays
            case TYPE_LICENSES:
                break;//type licenses
            case TYPE_CONFIG:
                break;//type config
        }//switch type
        break;//cmd sync
    case CMD_ENROLL_OK:

        $o_huella = null;
        $o_huella = Huella_L::obtenerPorId($data['hue_id']);
        if (!is_null($o_huella)) {


            $o_huella->setDatos($data['hue_datos']);
            $o_huella->save();

            $o_Persona = Persona_L::obtenerPorId($o_huella->getPerId());

            SeguridadHelper::Log(0, LOG_HUELLA_ENROLL_OK, $a_Logs_Tipos[LOG_HUELLA_ENROLL_OK], '<b>Persona Id:</b> ' . $o_Persona->getId() . ' <b>Nombre:</b> ' . $o_Persona->getNombreCompleto() . ' <b>Huella Id:</b>' . $o_huella->getId(). ' <b>Dedo:</b>' . $o_huella->getDedo(), $o_Persona->getId());
            SyncHelper::SyncHuella($o_huella);
        }

        break;//cmd enroll ok
    case CMD_RFID_READ_OK:

        $o_persona = null;
        $o_persona = Persona_L::obtenerPorId($data['per_id']);

        $o_persona_tag = Persona_L::obtenerPorTag($data['tag']);

        if(!is_null($o_persona_tag)){
            SeguridadHelper::Log(0, LOG_RFID_READ_ERROR, $a_Logs_Tipos[LOG_RFID_READ_ERROR], 'La tarjeta ya existe. <b>Persona Id:</b> ' . $o_persona_tag->getId() . ' <b>Nombre:</b> ' . $o_persona_tag->getNombreCompleto() . ' <b>TAG:</b>' . $o_persona_tag->getTag(), $o_persona_tag->getId());
        }else{
            if (!is_null($o_persona)) {

                $tagViejo = $o_persona->getTag();

                $o_persona->setTag($data['tag']);
                $o_persona->save();

                if($tagViejo!='') //cambio de tag
                    SeguridadHelper::Log(0, LOG_RFID_READ_OK, $a_Logs_Tipos[LOG_RFID_READ_OK], '<b>Persona Id:</b> ' . $o_persona->getId() . ' <b>Nombre:</b> ' . $o_persona->getNombreCompleto() . ' <b>TAG Anterior:</b>' . $tagViejo . ' <b>TAG Actual:</b>' . $o_persona->getTag(), $o_persona->getId());
                else   //tag nuevo
                    SeguridadHelper::Log(0, LOG_RFID_READ_OK, $a_Logs_Tipos[LOG_RFID_READ_OK], '<b>Persona Id:</b> ' . $o_persona->getId() . ' <b>Nombre:</b> ' . $o_persona->getNombreCompleto() . ' <b>TAG:</b>' . $o_persona->getTag(), $o_persona->getId());

                SyncHelper::SyncPersona($o_persona);

            }
        }



        break;//cmd rfid read ok
    case CMD_PING:



        $o_Equipo = Equipo_L::obtenerPorUUID($eq_uuid);

        if(is_null($o_Equipo)) {
            echo($eq_uuid . ' no existe');
        }
        else {

            $o_Equipo->setHeartbeat(time(), 'U');
            $o_Equipo->save('Off');

            $o_Heartbeat = new Heartbeat_O($o_Equipo->getId());
            $o_Heartbeat->setHeartbeat($o_Equipo->getHeartbeat('U'), 'U');
            $o_Heartbeat->save();


            if(!is_null($o_Equipo->getBloqueadoEl()))
                $estado='blocked';
            else if($o_Equipo->getMaintenanceMode())
                $estado='maintenance';
            else
                $estado='normal';


            PubSubHelper::sendMessage(CMD_PONG, '', $eq_uuid);//array('status'=>$estado));

            if($o_Equipo->getBloquearSync())break;  //si el equipo tiene el sync bloqueado salgo de aca;


            $sync_companies_time = 0;
            if (isset($data['companies'])) $sync_companies_time = intval($data['companies']);
            $sync_fingerprints_time = 0;
            if (isset($data['fingerprints'])) $sync_fingerprints_time = intval($data['fingerprints']);
            $sync_normal_hours_time = 0;
            if (isset($data['normal_hours'])) $sync_normal_hours_time = intval($data['normal_hours']);
            $sync_rotative_hours_time = 0;
            if (isset($data['rotative_hours'])) $sync_rotative_hours_time = intval($data['rotative_hours']);
            $sync_flex_hours_time = 0;
            if (isset($data['flex_hours'])) $sync_flex_hours_time = intval($data['flex_hours']);
            $sync_groups_time = 0;
            if (isset($data['groups'])) $sync_groups_time = intval($data['groups']);
            $sync_groups_persons_time = 0;
            if (isset($data['groups_persons'])) $sync_groups_persons_time = intval($data['groups_persons']);
            $sync_holidays_time = 0;
            if (isset($data['holidays'])) $sync_holidays_time = intval($data['holidays']);
            $sync_licenses_time = 0;
            if (isset($data['licenses'])) $sync_licenses_time = intval($data['licenses']);
            $sync_persons_time = 0;
            if (isset($data['persons'])) $sync_persons_time = intval($data['persons']);

            /*************************************************** **********************************************************/
            /********************************************     STATUS     **************************************************/
            /*************************************************** **********************************************************/

            $save = false;
            if (isset($data['status'])){
                if (isset($data['status']['wifi'])){
                    if($o_Equipo->getWifiSignal()!=$data['status']['wifi']) {
                        $o_Equipo->setWifiSignal($data['status']['wifi']);
                        $save = true;
                    }
                }
                if (isset($data['status']['network'])){
                    if($o_Equipo->getTipoRed()!=$data['status']['network']) {
                        $o_Equipo->setTipoRed($data['status']['network']);
                        $save = true;
                    }
                }
            }
            if($save)
                $o_Equipo->save();

            /*************************************************** **********************************************************/
            /*******************************************     PERSONAS     *************************************************/
            /*************************************************** **********************************************************/
            //if ($sync_persons_time != 0) {






            $cant_Personas = Persona_L::obtenerCOUNTPorFechaMod($sync_persons_time);
            if ($cant_Personas>0) {
                $task = new PushTask('/task', ['accion' => 'sync', 'tipo' => 'personas', 'fecha_mod' => $sync_persons_time, 'eq_uuid' => $eq_uuid, 'client_id' => $o_Cliente->getId()]);
                $task_name = $task->add();
                break;
            }



            /* } else {
                 //$a_Personas = Persona_L::obtenerTodos('', '', '', '', true);//persona con imagen
                 $a_Personas = Persona_L::obtenerPorFechaMod(0,10);
                 if (!is_null($a_Personas)) {
                     foreach ($a_Personas as $o_Persona) {
                         SyncHelper::SyncPersona($o_Persona, $eq_uuid);   //hacer eq uuid
                     }
                 }
             }
            */
            /*************************************************** **********************************************************/
            /*******************************************     HUELLAS      *************************************************/
            /*************************************************** **********************************************************/
            $cant_Huellas = Huella_L::obtenerCOUNTPorFechaMod($sync_fingerprints_time, '', true);
            if ($cant_Huellas>0) {
                $task = new PushTask('/task', ['accion' => 'sync', 'tipo' => 'huellas', 'fecha_mod' => $sync_fingerprints_time, 'eq_uuid' => $eq_uuid, 'client_id' => $o_Cliente->getId()]);
                $task_name = $task->add();
                break;
            }

            //if ($sync_fingerprints_time != 0) {
            /*
            $contador_de_huellas = 0;
            $a_Huellas = Huella_L::obtenerPorFechaMod($sync_fingerprints_time,30);
            if (!is_null($a_Huellas)) {
                foreach ($a_Huellas as $o_Huella)
                    SyncHelper::SyncHuella($o_Huella, $eq_uuid);
            }
            if($contador_de_huellas>0)break;
            */
            /*} else {
                $a_Huellas = Huella_L::obtenerTodos('', true);//huella con datos
                if (!is_null($a_Huellas)) {
                    foreach ($a_Huellas as $o_Huella)
                        SyncHelper::SyncHuella($o_Huella, $eq_uuid);
                }
            } */
            /*************************************************** **********************************************************/
            /*******************************************     EMPRESAS     *************************************************/
            /*************************************************** **********************************************************/

            /*************************************************** **********************************************************/
            /****************************************     HORARIO NORMAL      *********************************************/
            /*************************************************** **********************************************************/

            /*************************************************** **********************************************************/
            /***************************************     HORARIO ROTATIVO      ********************************************/
            /*************************************************** **********************************************************/

            /*************************************************** **********************************************************/
            /***************************************     HORARIO FLEXIBLE      ********************************************/
            /*************************************************** **********************************************************/

            /*************************************************** **********************************************************/
            /********************************************     GRUPOS      *************************************************/
            /*************************************************** **********************************************************/

            /*************************************************** **********************************************************/
            /****************************************     GRUPOS PERSONAS      ********************************************/
            /*************************************************** **********************************************************/

            /*************************************************** **********************************************************/
            /*******************************************     FERIADOS      ************************************************/
            /*************************************************** **********************************************************/

            /*************************************************** **********************************************************/
            /******************************************     LICENCIAS      ************************************************/
            /*************************************************** **********************************************************/


        }



        break;//cmd ping
    case CMD_PONG:

        $o_Equipo = Equipo_L::obtenerPorUUID($eq_uuid);
        if(is_null($o_Equipo)) {
            echo($eq_uuid . ' no existe');
        }else {
            $o_Equipo->setHeartbeat(time(), 'U');
            $o_Equipo->save('Off');

            $o_Heartbeat = new Heartbeat_O($o_Equipo->getId());
            $o_Heartbeat->setHeartbeat($o_Equipo->getHeartbeat('U'), 'U');
            $o_Heartbeat->save('Off');
        }
        break;//cmd pong
    case CMD_ACK_ELIMINADO:
        switch ($type) {
            case TYPE_PERSON:
                $o_Equipo = Equipo_L::obtenerPorUUID($eq_uuid);
                $o_persona = null;
                if(array_key_exists('per_id',$data)) {
                    $o_persona = Persona_L::obtenerPorId($data['per_id']);
                    if (!is_null($o_persona))
                        $o_persona->deleteEquipo($o_Equipo->getId());
                }

                break;//type person
            case TYPE_FINGERPRINT:
                break;//type fingerprint
            case TYPE_NORMAL_HOURS:
                break;//type normal hours
            case TYPE_FLEX_HOURS:
                break;//type flex hours
            case TYPE_ROTATIVE_HOURS:
                break;//type rotative hours
            case TYPE_COMPANIES:
                break;//type companies
            case TYPE_GROUPS:
                break;//type groups
            case TYPE_GROUPS_PERSONS:
                break;//type groups persons
            case TYPE_HOLIDAYS:
                break;//type holydays
            case TYPE_LICENSES:
                break;//type licenses
            case TYPE_CONFIG:
                break;//type config
        }//switch type

        break;//cmd ack eliminado

    case CMD_LOG:

        $resultado = array();
        foreach ($data as $log) {

            //chequeo que el log no sea del "futuro"
            if($log['fecha'] > strtotime('+2 days')){
                //echo "log del futuro, no se guarda...\r\n";
                $resultado[] = array('id' => $log['log_id'],'status' => 'OK');
                continue;
            }

            $o_Equipo = Equipo_L::obtenerPorUUID($eq_uuid);
            if(isset($o_Equipo)){

                //$string = "1) log ".$log['log_id'].". buscando registros de la persona ".$log['persona'].", en los ultimos ".Config_L::p('tiempo_bloqueo_lectura')." segundos.\r\n";
                //syslog(LOG_INFO,$string);

                //echo "\r\nlog ".$log['log_id'].". buscando registros de la persona ".$log['persona'].", en los ultimos ".Config_L::p('tiempo_bloqueo_lectura')." segundos.\r\n";



                $a_logCP = Logs_Equipo_L::obtenerPorPersonaYTiempo($log['persona'], Config_L::p('tiempo_bloqueo_lectura'));



                if ($a_logCP != null) {
                    //echo "logs encontrados, no se   guarda bv\r\n";
                    $resultado[] = array('id' => $log['log_id'],'status' => 'OK');

                }else{

                    //echo "guardando\r\n";
                    //echo $log['fecha']."\r\n";
                    //echo date('Y-m-d H:i:s',$log['fecha']);
                    $o_log = new Logs_Equipo_O($o_Equipo->getId());
                    $o_log->setPerId($log['persona']);
                    $o_log->setLector($log['lector']);
                    $o_log->setFechaHora(date('Y-m-d H:i:s',$log['fecha']), 'Y-m-d H:i:s');
                    $o_log->setAccion($log['accion']);


                    if(array_key_exists('dedo',$log))
                        $o_log->setDedo($log['dedo']);

                    if ($o_log->save()) {

                        // RESULTADO: LOG GUARDADO
                        $resultado[] = array('id' => $log['log_id'],'status' => 'OK');

                        // NOTIFICACIONES: ALERTAS
                        $a_a_Notificaciones = Notificaciones_L::obtenerTodasAlertasActivas();

                        // SIN NOTIFICACIONES: SALIR
                        if (is_null($a_a_Notificaciones)) continue;

                        // ALERTAS: ENVIAR
                        foreach ($a_a_Notificaciones as $o_Notificacion) {

                            if(!$o_Notificacion->alerta_log_enviar($o_log->getPerId())){
                                continue;
                            }

                            $o_Notificacion->setDisparadorHora(date('Y-m-d H:i:s'));
                            $o_Notificacion->save();

                        }

                    } else {
                        //por ahora le pongo siempre OK.
                        //TODO: implementar DUPLICATE
                        $resultado[] = array('id' => $log['log_id'],'status' => 'OK');
                        //$resultado[] = array('id' => $log['log_id'],'status' => 'ERROR');
                        //echo $log['log_id'].':ERROR<br/>';
                    }
                }



            }

        }


        PubSubHelper::sendMessage(ACK_LOG, json_encode($resultado), $eq_uuid);

        break;

}


session_destroy();


syslog(LOG_INFO, ob_get_contents());
ob_end_flush();
/*


"Array
(
    [data] => Array
        (
            [data] => Array
                (
                    [type] => Buffer
                    [data] => Array
                        (
                            [0] => 123
                            [1] => 34
                            [2] => 108
                            [3] => 111
                            [4] => 103
                            [5] => 95
                            [6] => 105
                            [7] => 100
                            [8] => 34
                            [9] => 58
                            [10] => 49
                            [11] => 52
                            [12] => 44
                            [13] => 34
                            [14] => 112
                            [15] => 101
                            [16] => 114
                            [17] => 115
                            [18] => 111
                            [19] => 110
                            [20] => 97
                            [21] => 34
                            [22] => 58
                            [23] => 49
                            [24] => 48
                            [25] => 44
                            [26] => 34
                            [27] => 108
                            [28] => 101
                            [29] => 99
                            [30] => 116
                            [31] => 111
                            [32] => 114
                            [33] => 34
                            [34] => 58
                            [35] => 49
                            [36] => 44
                            [37] => 34
                            [38] => 102
                            [39] => 101
                            [40] => 99
                            [41] => 104
                            [42] => 97
                            [43] => 34
                            [44] => 58
                            [45] => 49
                            [46] => 54
                            [47] => 49
                            [48] => 56
                            [49] => 56
                            [50] => 49
                            [51] => 49
                            [52] => 51
                            [53] => 55
                            [54] => 55
                            [55] => 44
                            [56] => 34
                            [57] => 97
                            [58] => 99
                            [59] => 99
                            [60] => 105
                            [61] => 111
                            [62] => 110
                            [63] => 34
                            [64] => 58
                            [65] => 49
                            [66] => 44
                            [67] => 34
                            [68] => 100
                            [69] => 101
                            [70] => 100
                            [71] => 111
                            [72] => 34
                            [73] => 58
                            [74] => 51
                            [75] => 125
                        )

                )

            [attributes] => Array
                (
                    [uuid] => 7f7c5ae7b2ee052edde11786a57f5c40
                    [cmd] => CMD_LOG
                    [cli_id] => 4
                    [ver] => 1.0
                )

        )

    [messageId] => 2182919750047311
    [message_id] => 2182919750047311
    [publishTime] => 2021-04-19T09:39:07.017Z
    [publish_time] => 2021-04-19T09:39:07.017Z
)
No se pudo loguear el cliente"
}
]
appEngineRelease: "1.9.71"
traceId: "c7c9741a97340f93e3b738a1cec8eed5"
first: true
}
insertId: "607d4fbb000ddd7d78ae7e2d"
httpRequest: {
status: 200
}
resource: {
type: "gae_app"
labels: {
module_id: "default"
project_id: "enpunto-1286"
version_id: "20210417t032906"
zone: "us17"
}
}
timestamp: "2021-04-19T09:39:07.263667Z"
severity: "INFO"
labels: {
clone_id: "00c61b117c19baf190fb796fc8d50bff3a756c9391025ca6f9b04c9e31ba05dd8b5cda8036b0b761bc94534c69527c0df2176036182090dadd4f82bdcf2515164cb4c7fedac59a3e"
}
logName: "projects/enpunto-1286/logs/appengine.googleapis.com%2Frequest_log"
operation: {
id: "607d4fbb00ff04058702acaa7d0001737e656e70756e746f2d313238360001323032313034313774303332393036000100"
producer: "appengine.googleapis.com/request_id"
first: true
last: true
}
trace: "projects/enpunto-1286/traces/c7c9741a97340f93e3b738a1cec8eed5"
receiveTimestamp: "2021-04-19T09:39:07.918366453Z"
}
Info
2021-04-19 06:39:07.649 ART
Error: 8 Undefined index: attributes en la linea 59 del archivo /base/data/home/apps/s~enpunto-1286/20210417t032906.434500641364416073/sync.php
Info
2021-04-19 06:39:07.649 ART
CLI_ID:
Info
2021-04-19 06:39:07.649 ART
UUID:
Info
2021-04-19 06:39:07.649 ART
CMD:
Info
2021-04-19 06:39:07.649 ART
TYPE:
Info
2021-04-19 06:39:07.652 ART
Array ( [data] => Array ( [data] => Array ( [type] => Buffer [data] => Array ( [0] => 123 [1] => 34 [2] => 108 [3] => 111 [4] => 103 [5] => 95 [6] => 105 [7] => 100 [8] => 34 [9] => 58 [10] => 49 [11] => 52 [12] => 44 [13] => 34 [14] => 112 [15] => 101 [16] => 114 [17] => 115 [18] => 111 [19] => 110 [20] => 97 [21] => 34 [22] => 58 [23] => 49 [24] => 48 [25] => 44 [26] => 34 [27] => 108 [28] => 101 [29] => 99 [30] => 116 [31] => 111 [32] => 114 [33] => 34 [34] => 58 [35] => 49 [36] => 44 [37] => 34 [38] => 102 [39] => 101 [40] => 99 [41] => 104 [42] => 97 [43] => 34 [44] => 58 [45] => 49 [46] => 54 [47] => 49 [48] => 56 [49] => 56 [50] => 49 [51] => 49 [52] => 51 [53] => 55 [54] => 55 [55] => 44 [56] => 34 [57] => 97 [58] => 99 [59] => 99 [60] => 105 [61] => 111 [62] => 110 [63] => 34 [64] => 58 [65] => 49 [66] => 44 [67] => 34 [68] => 100 [69] => 101 [70] => 100 [71] => 111 [72] => 34 [73] => 58 [74] => 51 [75] => 125 ) ) [attributes] => Array ( [uuid] => 7f7c5ae7b2ee052edde11786a57f5c40 [cmd] => CMD_LOG [cli_id] => 4 [ver] => 1.0 ) ) [messageId] => 2182919750047311 [message_id] => 2182919750047311 [publishTime] => 2021-04-19T09:39:07.017Z [publish_time] => 2021-04-19T09:39:07.017Z ) No se pudo loguear el cliente
Info
2021-04-19 06:39:07.263 ART
POST
200
3.85 KiB
419 ms
CloudPubSub-Google
/sync.php?tokenSuperSeguro=hue395257d89658016985688c190b2896ca383
Info
2021-04-19 06:39:07.265 ART
POST
200
3.85 KiB
472 ms
CloudPubSub-Google
/sync.php?tokenSuperSeguro=hue395257d89658016985688c190b2896ca383
Info
2021-04-19 06:39:28.046 ART
POST
200
3.85 KiB
105 ms
CloudPubSub-Google
/sync.php?tokenSuperSeguro=hue395257d89658016985688c190b2896ca383
Info
2021-04-19 06:39:28.087 ART
POST
200
3.81 KiB
101 ms
CloudPubSub-Google
/sync.php?tokenSuperSeguro=hue395257d89658016985688c190b2896ca383
Info
2021-04-19 06:39:28.106 ART
POST
200
3.85 KiB
131 ms
CloudPubSub-Google
/sync.php?tokenSuperSeguro=hue395257d89658016985688c190b2896ca383
Info
2021-04-19 06:39:28.115 ART
POST
200
3.85 KiB
150 ms
CloudPubSub-Google
/sync.php?tokenSuperSeguro=hue395257d89658016985688c190b2896ca383
Info
2021-04-19 06:39:28.126 ART
POST
200
3.85 KiB
207 ms
CloudPubSub-Google
/sync.php?tokenSuperSeguro=hue395257d89658016985688c190b2896ca383
Info
2021-04-19 06:39:28.140 ART
POST
200
3.77 KiB
222 ms
CloudPubSub-Google
/sync.php?tokenSuperSeguro=hue395257d89658016985688c190b2896ca383
Info
2021-04-19 06:39:28.172 ART
POST
200
3.84 KiB
259 ms
CloudPubSub-Google
/sync.php?tokenSuperSeguro=hue395257d89658016985688c190b2896ca383


 */




