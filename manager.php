<?php

$_SERVER['X-Appengine-Cron']=true;

require_once(dirname(__FILE__) . '/_ruta.php');


//if($subdominio!='dev') die();

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
            die($G_DbConnMGR->get_Error($Registry->general['debug']));
        }

        Registry::getInstance()->DbConn = $G_DbConn1;

        //TIMEZONE
        $timezone = Config_L::p('timezone');
        date_default_timezone_set($timezone);  //php timezone
        Registry::getInstance()->DbConn->Query("SET time_zone = '" . $timezone . "';");   //sql timezone

        //echo "Cliente: ".$o_Cliente->getSubdominio();
        //echo "</br>";

        $equipos = Equipo_L::obtenerTodos();
        $last_heartbeat = 0;
        if(!is_null($equipos)){
            foreach ($equipos as $equipo) { /* @var $equipo Equipo_O */
                if($equipo->getHeartbeat()>$last_heartbeat)
                    $last_heartbeat = $equipo->getHeartbeat();
            }
        }


        /*  listado de clientes
        echo $o_Cliente->getSubdominio().",".Persona_L::obtenerCantidad().",".Equipo_L::obtenerCantidad().",".date(Config_L::p("f_fecha_corta"),$last_heartbeat);
        echo "</br>";
        */





    }
}



