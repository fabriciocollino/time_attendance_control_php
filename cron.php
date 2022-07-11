<?php

$_SERVER['X-Appengine-Cron']=true;

require_once(dirname(__FILE__) . '/_ruta.php');


$errores = array();

$ErroresTipo    = isset($_REQUEST['errorestipo'])   ? (string)$_REQUEST['errorestipo'] : '';
$CronTipo       = isset($_REQUEST['tipo'])          ? (string)$_REQUEST['tipo'] : '';
$DebugCron      = isset($_REQUEST['debug'])         ? 1 : 0;


$additionalHeaders = "";
$payloadName = "";

//if(date('i')>=54&&date('i')<55){echo "si";die();}else {echo "no".date('i');die();}

if ($DebugCron) echo "</br>Iniciando CRON JOB";
if ($DebugCron) echo "</br>Último cron: " . Cron_L::obtenerPorNombre('cron.php')->getFechaHora();


$a_Clientes = Cliente_L::obtenerTodosEnabled();
if (!is_null($a_Clientes)) {


    foreach ($a_Clientes as $o_Cliente) {
        /* @var $o_Cliente Cliente_O */

        $subdominio = $o_Cliente->getSubdominio();
        $o_Suscripcion = Suscripcion_L::obtenerPorId($o_Cliente->getSuscripcion());
        $o_Plan = Planes_L::obtenerPorId($o_Suscripcion->getPlan());
        $G_DbConn1 = new mySQL(
            $o_Cliente->getDBname(),
            $o_Cliente->getDBuser(),
            $o_Cliente->getDBpass(),
            $o_Cliente->getDBhost(),
            $o_Cliente->getDBport()
        );


        if (!$G_DbConn1->ConectarSocket()) {
            die();//($G_DbConnMGR->get_Error($Registry->general['debug']));
        }

        Registry::getInstance()->DbConn = $G_DbConn1;

        //TIMEZONE
        $timezone = Config_L::p('timezone');
        if($timezone == '') continue;
        date_default_timezone_set($timezone);  //php timezone
        Registry::getInstance()->DbConn->Query("SET time_zone = '" . $timezone . "';");   //sql timezone




        $a_o_Equipo = Equipo_L::obtenerTodos();







        //Proceso de envio de emails (IF=0 PARA EVITAR ENVIO DE MAILS ACUMULADOS)
        if ($CronTipo == '' || $CronTipo == 'email') {
            $a_Email_Listado = Email_L::obtenerTodosAEnviar();

            if (0){//count($a_Email_Listado)) {
                foreach ($a_Email_Listado as $o_Email) {

                    $o_Email->enviar();
                    $o_Email->setEstado(2); //enviado
                    $o_Email->setFecha($o_Email->getFecha("Y-m-d H:i:s"), "Y-m-d H:i:s");
                    $o_Email->save('Off');

                }
            }
        }


//TODO: mover esto a otro cron que sea por hora
//Tareas por Hora
        if ($CronTipo == 'hora') {

            //TODO  Limpiar tokens de usuario

            $a_usuarios = Usuario_L::obtenerTodosSPconTokens('usu_ResetTokenDate <= DATE_SUB(NOW(),INTERVAL 1 HOUR)');
            if (!is_null($a_usuarios)) {
                foreach ($a_usuarios as $o_Usuario) {
                    if ($o_Usuario->getResetTokenDate()) {
                        $o_Usuario->clearResetToken();
                        $o_Usuario->save('Off');
                    }
                }
            }
        }


        if ($DebugCron) echo "</br></br> ERRORES:</br>";


//muestro los errores

        if ($ErroresTipo == 'logs') {
            foreach ($errores as $error) {
                if ($error[0] == 'LOGS') echo $error[1];
            }
        } else if ($ErroresTipo == 'sync') {
            foreach ($errores as $error) {
                if ($error[0] == 'SYNC_STATUS') echo $error[1];
            }
        } else if ($ErroresTipo == '') {

            foreach ($errores as $error) {
                //echo $error[0]." - ".$error[1];
                echo $error[1];
            }
        }


        /* Limpio SESIONES viejas. */
//system("find " . realpath(dirname(__FILE__)) . "/codigo/data/sesiones -type f -name 'sess_*' -mmin +24 -delete");


        /* Limpio logs heartbeat viejos */
        $cnn = Registry::getInstance()->DbConn;
        $cnn->Query("DELETE FROM `logs_heartbeat` WHERE loh_Heartbeat < (NOW() - INTERVAL 1 HOUR)");


        $Cron = Cron_L::obtenerPorNombre('cron.php');
        if($Cron){
            $Cron->setTimestamp(time());
            $Cron->Save('Off');
        }


//flock($fp, LOCK_UN); // release the lock
//cronHelper::unlock("cron.php");
//}
//else{
//	die(_("El recurso está en uso"));
//}

//fclose($fp);


        /*
         * Si el llamado vino desde el sistema, no destruyo la sesion, pero si vino desde el cron comun si
         */


    }
}


if ($CronTipo != 'logs')
    session_destroy();

