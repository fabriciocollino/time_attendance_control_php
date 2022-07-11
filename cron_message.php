<?php

$_SERVER['X-Appengine-Cron'] = true;

require_once(dirname(__FILE__) . '/_ruta.php');

$errores = array();

$ErroresTipo = isset($_REQUEST['errorestipo']) ? (string)$_REQUEST['errorestipo'] : '';


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

        if(GAE) {
            if (!$G_DbConn1->ConectarSocket()) {
                die();//$G_DbConnMGR->get_Error($Registry->general['debug']));
            }
        }
        else{
            if (!$G_DbConn1->Conectar()) {
                die();//($G_DbConnMGR->get_Error($Registry->general['debug']));
            }
        }

        Registry::getInstance()->DbConn = $G_DbConn1;

        //TIMEZONE
        $timezone = Config_L::p('timezone');
        if($timezone == '') continue;
        date_default_timezone_set($timezone);  //php timezone
        Registry::getInstance()->DbConn->Query("SET time_zone = '" . $timezone . "';");   //sql timezone


        $a_Messages = Message_L::getAllScheduledCron();

        if($a_Messages){
            foreach ($a_Messages as $o_Message) {
                if(strtotime(date('Y-m-d H:i:s')) > strtotime($o_Message->getScheduledDate())){

                    $o_Message->setIsScheduled(0);
                    //$o_Message->setScheduledDate("00-00-00 00:00:00");
                    $o_Message->setSentDateTime(date('Y-m-d H:i:s'));
                    $o_Message->setStateSent(1);
                    $o_Message->save();
                }
            }
        }





        $Cron = Cron_L::obtenerPorNombre('cron_message.php ');
        if($Cron){
            $Cron->setTimestamp(time());
            $Cron->Save('Off');
        }


    }
}

// session_destroy();

//muestro los errores

if ($ErroresTipo == '') {
    foreach ($errores as $error) {
        echo $error[1];
    }
}
