<?php
require_once dirname(__FILE__) . '/../../_ruta.php';
SeguridadHelper::Pasar(10);
require dirname(__FILE__) . '/../../vendor/autoload.php';


$T_Tipo                 = (isset($_REQUEST['tipo']))    ? $_REQUEST['tipo']         :   ''  ;
$T_Id                   = (isset($_REQUEST['id']))      ? (integer) $_REQUEST['id'] :   0   ;
$T_Cmd                  = (isset($_REQUEST['cmd']))     ? $_REQUEST['cmd']          :   ''  ;
$T_Data                 = (isset($_REQUEST['data']))    ? $_REQUEST['data']         :   ''  ;

$Item_Name              = "equipo";
$T_Titulo               = "Equipos";
$T_Script               = "Equipos";
$T_Titulo_Singular      = _('Equipo');
$T_Titulo_Pre           = _('el');

$T_personaID            = (isset($_REQUEST['personaID'])) ? $_REQUEST['personaID'] : '';
$a_Personas_Equipo      = array();

switch ($T_Tipo) {
    case 'add':

        // NEW DEVICE
        $o_Equipo = new Equipo_O();

        // SET DETALLE
        $o_Equipo->setDetalle   (   isset($_POST['detalle'])   ?   $_POST['detalle']   : ''    );

        // SET UUID
        $o_Equipo->setUUID      (   isset($_POST['uuid'])      ?   $_POST['uuid']      : ''    );

        $o_Equipo->setTipoRed("wlan");

        $save_equipo= $o_Equipo->save(true);
       // printear($save_equipo);

        // SAVE SUCCESS
        if ($save_equipo) {

            // SAVE LOG
            SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_EQUIPO_CREAR, $a_Logs_Tipos[LOG_EQUIPO_CREAR], '<b>Id:</b> ' . $o_Equipo->getId() . ' <b>Detalle:</b> ' . $o_Equipo->getDetalle(), $o_Equipo->getId());



            // DISPLAY NAME
            $_accountDisplayName = "devices-".$o_Equipo->getUUID();
            $_accountEmail = substr($_accountDisplayName,0,30);

            // CREATE: SERVICE ACCOUNT
            $o_new_IAM_Account = Create_IAM_ServiceAccount($_accountEmail);

            // UPDATE ACCOUNT DISPLAY NAME
            $_accountID = $o_new_IAM_Account['email'];
            Service_Account_Update($_accountID,$_accountDisplayName);


            // CREATE SERVICE ACCOUNT
            $_accountKey        = Service_Account_Create_Key($_accountID);

            printear($_accountKey["privateKeyData"]);


            /*

            printear("PASO 5: EXAMPLE OF ACCOUNT SERVICE KEY FROM devices-104d4bb3484fa539bdf3db:");
            $response7 = Service_Account_Get_Key("devices-104d4bb3484fa539bdf3db@enpunto-1286.iam.gserviceaccount.com", "d1bdb322589bb9c62d9bd22e7046595ff3378e8e");
            printear($response7);

            printear("GETTING BACK SERVICE ACCOUNT TO SEE OVERWRITTEN DATA");
            $response2= Get_Iam_Service_Account($response['email']);
            printear($response2);

            // SET POLICY
            printear("PASO GET IAM CREATED SERVICE");
            $response2= Get_Iam_Service_Account($response['email']);
            printear($response2);


            printear("PASO GET POLICY FROM IAM SERVICE CREATED");
            $response3= Get_Iam_Policy($response['email']);
            printear($response3);

            printear("edit iam policy for resource");
            $response4= Curl_Post_SetIamPolicy($response['email']);
            printear($response4);

            */


            // MESSAGE
            $T_Mensaje = _('El equipo fue agregado correctamente.');

            // SYNC PERSONAS
            //SyncHelper::SyncTodasLasPersonas();//si se agrega un nuevo equipo re-sincronizo todas las personas.
        }

        // SAVE ERROR
        else {
            printear('ERRORES');
            $T_Error = $o_Equipo->getErrores();
        }

        goto defaultlabel;
        break;


    case 'edit':

        // GET DEVICE
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);

        // SET DETALLE
        $o_Equipo->setDetalle(isset($_POST['detalle']) ? $_POST['detalle'] : '');

        // SET DETALLE
        $o_Equipo->setUUID      (   isset($_POST['uuid'])      ?   $_POST['uuid']      : ''    );

        // SAVE SUCCESS
        if ($o_Equipo->save(Registry::getInstance()->general['debug'])) {

            // SAVE LOG
            SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_EQUIPO_EDITAR, $a_Logs_Tipos[LOG_EQUIPO_EDITAR], '<b>Id:</b> ' . $o_Equipo->getId() . ' <b>Detalle:</b> ' . $o_Equipo->getDetalle(), $o_Equipo->getId());

            // MESSAGE
            $T_Mensaje = _('El equipo fue modificado correctamente.');
        }

        // SAVE ERROR
        else {
            $T_Error = $o_Equipo->getErrores();
        }

        goto defaultlabel;
        break;


    case 'enable':

        $T_Script = "Habilitar";

        $o_Equipo = Equipo_L::obtenerPorId($T_Id);

        if (is_null($o_Equipo)) {
            $T_Error = _('Lo sentimos, el equipo que desea habilitar no existe.');
        }


        if (!$o_Equipo->desBloqueado(Registry::getInstance()->general['debug'])) {
            //$T_Error = 'Lo sentimos, el equipo que desea habilitar no puede ser modificado.';
            $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Equipo->getErrores();
        } else {
            SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_EQUIPO_BLOQUEAR, $a_Logs_Tipos[LOG_EQUIPO_BLOQUEAR], '<b>Id:</b> ' . $o_Equipo->getId() . ' <b>Detalle:</b> ' . $o_Equipo->getDetalle(), $o_Equipo->getId());
            //SeguridadHelper::Reporte(Registry::getInstance()->Usuario->getId(), $T_Titulo . '-' . $a_Acciones[2], 'Tabla - ' . $T_Script . ' Id - ' . $o_Equipo->getId());
            PubSubHelper::sendMessage(CMD_UNBLOCK,'',$o_Equipo->getUUID());
            $T_Mensaje = _('El equipo fue habilitado correctamente.');
        }


        $T_Habilitando = true;
        goto defaultlabel;
        break;

    case 'disable':
        $T_Script = "Bloquear";

        $o_Equipo = Equipo_L::obtenerPorId($T_Id);

        if (is_null($o_Equipo)) {
            $T_Error = _('Lo sentimos, el equipo que desea bloquear no existe.');
        }

        if (!$o_Equipo->bloqueado(Registry::getInstance()->general['debug'])) {
            //$T_Error = 'Lo sentimos, el equipo  que desea eliminar no puede ser eliminado.';
            $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Equipo->getErrores();
        } else {
            //SeguridadHelper::Reporte(Registry::getInstance()->Usuario->getId(), $T_Titulo . '-' . $a_Acciones[3], 'Tabla - ' . $T_Script . ' Id - ' . $o_Equipo->getId());
            SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_EQUIPO_DESBLOQUEAR, $a_Logs_Tipos[LOG_EQUIPO_DESBLOQUEAR], '<b>Id:</b> ' . $o_Equipo->getId() . ' <b>Detalle:</b> ' . $o_Equipo->getDetalle(), $o_Equipo->getId());
            PubSubHelper::sendMessage(CMD_BLOCK,'',$o_Equipo->getUUID());
            $T_Mensaje = _('El Equipo fue bloquado correctamente.');
        }


        $T_Bloqueado = true;
        goto defaultlabel;
        break;




    case  'synclogs':

        $resultado = array();
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);

        foreach ($T_Data as $log) {

            //chequeo que el log no sea del "futuro"
            if($log['fecha'] > strtotime('+2 days')){
                echo "log del futuro, no se guarda...\r\n";
                $resultado[] = array('id' => $log['log_id'],'status' => 'OK');
                continue;
            }


            if(isset($o_Equipo)){

                echo "\r\nlog ".$log['log_id'].". buscando registros de la persona ".$log['persona'].", en los ultimos ".Config_L::p('tiempo_bloqueo_lectura')." segundos.\r\n";
                $a_logCP = Logs_Equipo_L::obtenerPorPersonaYTiempo($log['persona'], Config_L::p('tiempo_bloqueo_lectura'));
                if ($a_logCP != null) {
                    echo "logs encontrados, no se guarda\r\n";
                    //ya hay logs de esta persona dentro del margen de lectura
                    //devuelvo ok, pero no grabo un choto
                    $resultado[] = array('id' => $log['log_id'],'status' => 'OK');
                    //echo $log['log_id'].':OK_ns<br/>';
                }else{
                    echo "guardando\r\n";
                    echo $log['fecha']."\r\n";
                    echo date('Y-m-d H:i:s',$log['fecha']);
                    $o_log = new Logs_Equipo_O($o_Equipo->getId());
                    $o_log->setPerId($log['persona']);
                    $o_log->setLector($log['lector']);
                    $o_log->setFechaHora(date('Y-m-d H:i:s',$log['fecha']), 'Y-m-d H:i:s');
                    $o_log->setAccion($log['accion']);
                    if(array_key_exists('dedo',$log))
                        $o_log->setDedo($log['dedo']);

                    if ($o_log->save()) {
                        $resultado[] = array('id' => $log['log_id'],'status' => 'OK');
                        //echo $log['log_id'].':OK<br/>';
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

        PubSubHelper::sendMessage(ACK_LOG, json_encode($resultado), $o_Equipo->getUUID());

        break;//cmd log

    case 'testsync':
        SeguridadHelper::Pasar(999);
        $T_Script = "Test Sync";

        $o_Equipo = Equipo_L::obtenerPorId($T_Id);

        if (is_null($o_Equipo)) {
            $T_Error = _('Lo sentimos, el equipo no existe.');
        }

        PubSubHelper::sendMessage('equipo', 'sync',$o_Equipo->getUUID());

        goto defaultlabel;
        break;

    case 'sendsyncV2':
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);
        if (is_null($o_Equipo)) {
            die('Lo sentimos, el equipo no existe.');
        }

        $o_Equipo->setHeartbeat(time(), 'U');
        $o_Equipo->save('Off');

        $o_Heartbeat = new Heartbeat_O($o_Equipo->getId());
        $o_Heartbeat->setHeartbeat($o_Equipo->getHeartbeat('U'), 'U');
        $o_Heartbeat->save('Off');

        PubSubHelper::sendMessage(CMD_SYNC,json_encode(array('id' => '33','status' => 'OK')),$o_Equipo->getUUID());


        goto defaultlabel;
        break;

    case 'SyncTodasLasPersonas':

        $o_Equipo = Equipo_L::obtenerPorId($T_Id);
        $_equuid = $o_Equipo->getUUID();


        SyncHelper::SyncTodasLasPersonas($_equuid);//si se agrega un nuevo equipo re-sincronizo todas las personas.
        goto defaultlabel;
        break;

    case 'sendsync':
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);
        if (is_null($o_Equipo)) {
            die('Lo sentimos, el equipo no existe.');
        }

            $o_Equipo->setHeartbeat(0,1,1);
            $o_Equipo->save('Off');

            PubSubHelper::sendMessage(CMD_PING,'',$o_Equipo->getUUID());

            goto defaultlabel;
            break;


        /*

*/
    case 'sync_logs':
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);
        if (is_null($o_Equipo)) {
            die('Lo sentimos, el equipo no existe.');
        }
        PubSubHelper::sendMessage(CMD_LOGS,'',$o_Equipo->getUUID());
        goto defaultlabel;
        break;

    case 'reboot':
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);
        if (is_null($o_Equipo)) {
            die('Lo sentimos, el equipo no existe.');
        }
        PubSubHelper::sendMessage(CMD_REBOOT,'',$o_Equipo->getUUID());
        goto defaultlabel;
        break;
    case 'restart_app':
        SeguridadHelper::Pasar(999);
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);
        if (is_null($o_Equipo)) {
            die('Lo sentimos, el equipo no existe.');
        }
        PubSubHelper::sendMessage(CMD_RESTART_APP,'',$o_Equipo->getUUID());
        goto defaultlabel;
        break;
    case 'reset_reader':
        SeguridadHelper::Pasar(999);
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);
        if (is_null($o_Equipo)) {
            die('Lo sentimos, el equipo no existe.');
        }
        PubSubHelper::sendMessage(CMD_RESET_READER,json_encode(array('time'=>2000)),$o_Equipo->getUUID());
        goto defaultlabel;
        break;
    case 'force_ping':
        SeguridadHelper::Pasar(999);
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);
        if (is_null($o_Equipo)) {
            die('Lo sentimos, el equipo no existe.');
        }
        PubSubHelper::sendMessage(CMD_FORCE_PING,'',$o_Equipo->getUUID());
        goto defaultlabel;
        break;
    case 'blink':
        //rahul : the just next line is commented because clien want to give permission to all to use this
        //SeguridadHelper::Pasar(999);
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);
        if (is_null($o_Equipo)) {
            die('Lo sentimos, el equipo no existe.');
        }
        PubSubHelper::sendMessage(CMD_BLINK,'',$o_Equipo->getUUID());
        goto defaultlabel;
        break;
    case 'clear_network_info':
        //rahul : the just next line is commented because clien want to give permission to all to use this 
        //SeguridadHelper::Pasar(999);
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);
        if (is_null($o_Equipo)) {
            die('Lo sentimos, el equipo no existe.');
        }
        PubSubHelper::sendMessage(CMD_CLEAR_NETWORK_INFO,'',$o_Equipo->getUUID());
        goto defaultlabel;
        break;
    case 'purge_database':
        SeguridadHelper::Pasar(999);
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);
        if (is_null($o_Equipo)) {
            die('Lo sentimos, el equipo no existe.');
        }
        PubSubHelper::sendMessage(CMD_PURGE_DATABASE,'',$o_Equipo->getUUID());
        goto defaultlabel;
        break;
    case 'block_sync':
        SeguridadHelper::Pasar(999);
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);
        if (is_null($o_Equipo)) {
            die('Lo sentimos, el equipo no existe.');
        }
        if($o_Equipo->getBloquearSync())
            $o_Equipo->setBloquearSync(0);
        else
            $o_Equipo->setBloquearSync(1);
        $o_Equipo->save();

        goto defaultlabel;
        break;
    case 'block_updates':
        SeguridadHelper::Pasar(999);
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);
        if (is_null($o_Equipo)) {
            die('Lo sentimos, el equipo no existe.');
        }
        if($o_Equipo->getLockUpdates()) {
            $o_Equipo->setLockUpdates(0);
            PubSubHelper::sendMessage(CMD_UNLOCK_UPDATES,'',$o_Equipo->getUUID());
        }
        else {
            $o_Equipo->setLockUpdates(1);
            PubSubHelper::sendMessage(CMD_LOCK_UPDATES,'',$o_Equipo->getUUID());
        }
        $o_Equipo->save();

        goto defaultlabel;
        break;
    case 'maintenance_mode':
        SeguridadHelper::Pasar(999);
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);
        if (is_null($o_Equipo)) {
            die('Lo sentimos, el equipo no existe.');
        }
        if($o_Equipo->getMaintenanceMode()) {
            $o_Equipo->setMaintenanceMode(0);
            PubSubHelper::sendMessage(CMD_MAINTENANCE_DISABLE,'',$o_Equipo->getUUID());
        }
        else {
            $o_Equipo->setMaintenanceMode(1);
            PubSubHelper::sendMessage(CMD_MAINTENANCE_ENABLE,'',$o_Equipo->getUUID());
        }
        $o_Equipo->save();

        goto defaultlabel;
        break;
    case 'reset_wireless':
        SeguridadHelper::Pasar(999);
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);
        if (is_null($o_Equipo)) {
            die('Lo sentimos, el equipo no existe.');
        }
        PubSubHelper::sendMessage(CMD_RESET_WIRELESS_NETWORK,'',$o_Equipo->getUUID());

        goto defaultlabel;
        break;
    case 'debug_info':
        SeguridadHelper::Pasar(999);
        $o_Equipo = Equipo_L::obtenerPorId($T_Id);
        if (is_null($o_Equipo)) {
            die('Lo sentimos, el equipo no existe.');
        }
        PubSubHelper::sendMessage(CMD_DEBUG_INFO,'',$o_Equipo->getUUID());

        goto defaultlabel;
        break;
    case 'delete':
        SeguridadHelper::Pasar(999);
        $T_Script = "Eliminar";

        $o_Equipo = Equipo_L::obtenerPorId($T_Id);

        if (is_null($o_Equipo)) {
            $T_Error = _('Lo sentimos, el equipo que desea eliminar no existe.');
            goto defaultlabel;
            break;
        }

        // DELETED DEVICE
        if ($o_Equipo->delete(Registry::getInstance()->general['debug'])) {
            SeguridadHelper::Reporte(Registry::getInstance()->Usuario->getId(), $T_Titulo . '-' . $a_Acciones[6], 'Tabla - ' . $T_Script . ' - Id : ' . $o_Equipo->getId() . ' - Detalle : ' . $o_Equipo->getDetalle() . 'y todos los datos relacionados (en las tablas zona, Logs_usos, logs_alama, logs_equipo y sync) fueron eliminado');
            $T_Mensaje             = _('El equipo fue eliminado correctamente.');
            $_SESSION['confirmar'] = array();
        }

        // ERROR DELETING
        else {
            $T_Error = 'Lo sentimos, el equipo que desea eliminar no puede ser eliminado.';
            $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Equipo->getErrores();
        }

        $T_Eliminado = true;
        goto defaultlabel;
        break;


    case 'view':

        $o_Equipo = Equipo_L::obtenerPorId($T_Id, true);

        // EQUIPO NO EXISTE
        if (is_null($o_Equipo)) {
            $T_Error = _('Lo sentimos, el equipo no existe.');
            $o_Equipo= new Equipo_O();
            break;
        }

        //

        // ID EQUIPO
        $equipoID = $o_Equipo->getId();

        // DATOS PARA CONSULTA SQL
        $p_condicion = "(per_equipos REGEXP '^{$equipoID}$' OR per_equipos REGEXP '^{$equipoID}:' OR  per_equipos REGEXP ':{$equipoID}$' OR  per_equipos REGEXP ':{$equipoID}:')";

        // MYSQL GET: IDs PERSONAS IN EQUIPO
        $cnn                    = Registry::getInstance()->DbConn;
        $_SESSION['filtro']['activos'] = true;
        $a_Personas             = $cnn->Select_Lista_IDs('personas', $p_condicion, 'per_Id');



        // SET ARRAY PERSONAS
        if(!is_null($a_Personas)){
            $a_Personas_Equipo = $a_Personas;
        }

        break;


    case 'insert':

        if ($T_personaID == '') {
            $T_Error = _('Lo sentimos, la persona no existe');
            break;
        }
        // OBJECT PERSONA
        $o_Persona = Persona_L::obtenerPorId($T_personaID);


        // ARRAY EQUIPOS IN PERSONA
        $array_equipos = explode(':', $o_Persona->getEquipos());

        // ADD NEW EQUIPO TO ARRAY
        $array_equipos[] = $T_Id;

        // STRING EQUIPOS (FOR MYSQL SAVING)
        $string_equipos = '';

        foreach ($array_equipos as $key => $check_equipo) {
            if($check_equipo == ""){
                continue;
            }
            $string_equipos .= $check_equipo.":";
        }


        // DELETE ":" from the end of the string
        $string_equipos = rtrim($string_equipos, ':');

        // SET EQUIPOS IN PERSONA
        $o_Persona->setEquipos($string_equipos);


        // SAVE PERSONA
        if ($o_Persona->save(true)) {


            // Sync
            SyncHelper::SyncPersona($o_Persona);
            // Fin Sync

        }
        else {
            $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Persona->getErrores();
        }

        die();

        break;

    case 'remove':

        if ($T_personaID  == '') {
            $T_Error = _('Lo sentimos, la persona no existe');
            break;
        }

        // OBJECT  PERSONA
        $o_Persona = Persona_L::obtenerPorId($T_personaID);

        // ARRAY EQUIPOS IN PERSONA
        $temp_string= ":".$o_Persona->getEquipos();
        $array_equipos = explode(':', $temp_string);


        // SEARCH FOR DEVICE KEY ID IN ARRAY EQUIPOS
        $a_Key = array_search($T_Id, $array_equipos);

        // UNSET (BY KEY ID) DEVICE VALUE FROM ARRAY
        if($a_Key){
            unset($array_equipos[$a_Key]);
        }

        // CREATE STRING EQUIPOS (FOR MYSQL SAVING)
        $string_equipos = '';

        foreach ($array_equipos as $key => $check_equipo) {
            if($check_equipo == ""){
                continue;
            }
            $string_equipos .= $check_equipo.":";
        }

        // DELETE ":" from the end of the string
        $string_equipos = rtrim($string_equipos, ':');

        // SET EQUIPOS IN PERSONA
        $o_Persona->setEquipos($string_equipos);

        // SAVE PERSONA
        if ($o_Persona->save(true)) {
            // Sync
            SyncHelper::SyncPersona($o_Persona);
            // Fin Sync
        }
        else {
            $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Persona->getErrores();

        }
        die();

        break;

    default:
        defaultlabel:
        $o_Listado = Equipo_L::obtenerTodos();

        // PERSONAS POR EQUIPO
        if (!empty($o_Listado)) {

            foreach ($o_Listado as $equipo) {

                $equipoID = $equipo->getId();

                // DATOS PARA CONSULTA SQL
                $p_tabla     = 'personas';
                $p_condicion = "(per_equipos REGEXP '^{$equipoID}$' OR per_equipos REGEXP '^{$equipoID}:' OR  per_equipos REGEXP ':{$equipoID}$' OR  per_equipos REGEXP ':{$equipoID}:')";
                $p_key       = 'per_Id';

                // CONSULTA SQL
                $cnn                            = Registry::getInstance()->DbConn;
                $_SESSION['filtro']['activos']  = true;
                $personas                       = $cnn->Select_Lista_IDs($p_tabla, $p_condicion, $p_key);
                $array_personas[$equipoID]      = array();

                // PERSONAS POR EQUIPO
                if (!is_null($personas)) {
                    $array_personas[$equipoID] = $personas;
                }

            }

        }

        $T_Link = '';
}


/**********************************************************************
 * NEW CLIENT IAM SERVICE ACCOUNT
 **********************************************************************
 * @param null $Account_ID
 * @return bool|mixed
 */
function Create_IAM_ServiceAccount($Account_ID=null){

    if(is_null($Account_ID)){
        return false;
    }

    // CREDENTIALS
    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google_Service_Iam::CLOUD_PLATFORM);

    // NEW SERVICE
    $service = new Google_Service_iam($client);

    // Required. The resource name of the project associated with the service
    // accounts, such as `projects/my-project-123`.
    $name = 'projects/enpunto-1286';


    $requestBody = new Google_Service_Iam_CreateServiceAccountRequest();
    // SET 22 CHARACTERS ID NAME
    $requestBody->setAccountId($Account_ID);

    // REQUEST
    $response = $service->projects_serviceAccounts->create($name, $requestBody);

    // RETURN PARSABLE OBJECT
    return $response;

}

function Set_Iam_Policy($_email = null, $_etag=''){

    if(is_null($_email)){
        return false;
    }

    // CREDENTIALS
    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google_Service_Iam::CLOUD_PLATFORM);
    $service = new Google_Service_iam($client);
    // SERVICE ACCOUNT
    $resource = "projects/enpunto-1286/serviceAccounts/".$_email;

    // REQUEST BODY
    $requestBody = new Google_Service_Iam_SetIamPolicyRequest();

    // TEST
    //$requestBody = new Google_Service_Iam_Resource_ProjectsServiceAccounts();

    // SET POLICY: STEP 1 - CREATE
    $o_Policy = new Google_Service_Iam_Policy();

    // SET POLICY: STEP 2 - CREATE BINDING
    $_binding = new Google_Service_Iam_Binding();
    $_binding ->setMembers(["serviceAccount:".$_email]);
    $_binding->setRole("roles/pubsub.editor"); //pubsub.publisher //pubsub.subscriptions.create

    // SET POLICY: STEP 3 - SET BINDING
    $o_Policy->setBindings($_binding);
    $o_Policy->setVersion(3);
    //$o_Policy->setEtag("MDEwMjE5Mjb=");
    //$o_Policy->setRules(array());

    $requestBody->setPolicy($o_Policy);



    // REQUEST
    $response = $service->projects_serviceAccounts->setIamPolicy($resource, $requestBody);


    // RETURN PARSABLE OBJECT
    return $response;

}

function Get_Iam_Service_Account ($_email = null){

    if(is_null($_email)){
        return false;
    }
    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope('https://www.googleapis.com/auth/cloud-platform');

    $service = new Google_Service_iam($client);

    $name = 'projects/enpunto-1286/serviceAccounts/'.$_email;  // TODO: Update placeholder value.

    $response = $service->projects_serviceAccounts->get($name);

    return $response;


}

function Get_Iam_Policy($_email = null)
{

    $client = new Google_Client();
    $client->setApplicationName('Google-iamSample/0.1');
    $client->useApplicationDefaultCredentials();
    $client->addScope('https://www.googleapis.com/auth/cloud-platform');

    $service = new Google_Service_iam($client);


    $resource = "projects/enpunto-1286/serviceAccounts/".$_email;

    $response = $service->projects_serviceAccounts->getIamPolicy($resource);

    return $response;

}

function Edit_Iam_Policy($_email = null){

    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope('https://www.googleapis.com/auth/cloud-platform');

    $service = new Google_Service_CloudResourceManager($client);

    // REQUIRED: The resource for which the policy is being specified.
    // See the operation documentation for the appropriate value for this field.
    //$resource = "projects/enpunto-1286/roles/PubSubEditorCustom";
    //$resource = "projects/enpunto-1286/roles/PubSubEditorCustom";
    $resource = "projects/enpunto-1286";

    // TODO: Assign values to desired properties of `requestBody`:
    $requestBody = new Google_Service_CloudResourceManager_SetIamPolicyRequest();

    // SET POLICY: STEP 1 - CREATE
    //$o_Policy = new Google_Service_Iam_Policy(); // Google_Service_CloudResourceManager_Policy
    $o_Policy = new Google_Service_CloudResourceManager_Policy;

    // SET POLICY: STEP 2 - CREATE BINDING
    $_binding = new Google_Service_CloudResourceManager_Binding();
    $_binding ->setMembers(["serviceAccount:".$_email]);
    $_binding->setRole("roles/pubsub.editor"); //pubsub.publisher //pubsub.subscriptions.create

    // SET POLICY: STEP 3 - SET BINDING
    $o_Policy->setBindings($_binding);
    $o_Policy->setVersion(3);
    //$o_Policy->setEtag("MDEwMjE5Mjb=");
    //$o_Policy->setRules(array());


    $requestBody->setPolicy($o_Policy);

    $response = $service->projects->setIamPolicy($resource, $requestBody);

    return $response;


}




function Curl_Post_SetIamPolicy($email){


//     curl -X POST --header 'Content-Type: application/json' --header 'Accept: application/json' -d '{"email":"fabricio.collino@enpuntocontrol.com","password":"col377YO"}' 'https://dev.enpuntocontrol.com/api/v2/login';


    //$xCurl1= "curl -X POST --header 'Content-Type: application/json' --header 'Accept: application/json' -d '{\"email\":\"{$email}\"}',\"password\":\"{$password}\" }' 'https://'{$subdominio}'.enpuntocontrol.com/api/v2/login'";

    $service_url = ' https://cloudresourcemanager.googleapis.com/v1/projects/enpunto-1286:setIamPolicy';

    $curl = curl_init($service_url);

    $curl_post_data = array(
        "policy"=> array(
            "version"=> 3,
            //"etag"=> "BwUqMvZQGfo=",
            "bindings"=>
                [
                    array(
                        "role"=> "roles/pubsub.editor",
                        "members"=> [ "serviceAccount:{$email}"]
                    )
                ]
        )
    );


    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,  array('Content-Type:application/json'));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);


    $curl_response = curl_exec($curl);


    $decoded = json_decode($curl_response);

    curl_close($curl);

    return $decoded;

}

function Service_Account_Create_Key($_account = null){

    if(is_null($_account)){
        return false;
    }

    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope('https://www.googleapis.com/auth/cloud-platform');

    $service = new Google_Service_iam($client);

    $name = 'projects/enpunto-1286/serviceAccounts/'.$_account;

    // TODO: Assign values to desired properties of `requestBody`:
    $requestBody = new Google_Service_Iam_CreateServiceAccountKeyRequest();
    //$requestBody->setPrivateKeyType();

    $response = $service->projects_serviceAccounts_keys->create($name, $requestBody);


    return $response;


}

function Service_Account_Get_Key($_account = null, $_keyID = null){

    if(is_null($_account)){
        return false;
    }

    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope('https://www.googleapis.com/auth/cloud-platform');

    $service = new Google_Service_iam($client);

    $name = "projects/enpunto-1286/serviceAccounts/".$_account."/keys/".$_keyID;  // TODO: Update placeholder value.

    $response = $service->projects_serviceAccounts_keys->get($name);

    return $response;

}

function Service_Account_Update($_account = null, $_displayName = ""){

    if(is_null($_account)){
        return false;
    }

    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope('https://www.googleapis.com/auth/cloud-platform');

    $service = new Google_Service_iam($client);

    $name = 'projects/enpunto-1286/serviceAccounts/'.$_account;

    $requestBody = new Google_Service_iam_ServiceAccount();
    $requestBody->setDisplayName($_displayName);


    $response = $service->projects_serviceAccounts->update($name, $requestBody);

    return $response;

}
