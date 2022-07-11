<?php

$_SERVER['X-Appengine-Cron'] = true;

require_once(dirname(__FILE__) . '/_ruta.php');

/* CLIENTES */
$a_Clientes = Cliente_L::obtenerTodosEnabled();

/* SIN CLIENTES EXIT */
if (is_null($a_Clientes)) {
    exit();
}


/* DISPARADOR TIPO */
$disparador_tipo    = array();
$disparador_tipo[1] = ' +1 day';
$disparador_tipo[2] = ' +1 week';
$disparador_tipo[3] = ' +15 days';
$disparador_tipo[4] = ' +1 month';
$disparador_tipo[5] = ' +1 year';



/* CLIENTES */
foreach ($a_Clientes as $key_cliente => $o_Cliente) {

    /*+**+**+**+**+**+**+**+* CONFIGURACIONES ****+**+**+**+**+**+*/
    $subdominio = $o_Cliente->getSubdominio();

    /* CONEXIÓN A BASE DE DATOS */
    $G_DbConn1 = new mySQL($o_Cliente->getDBname(), $o_Cliente->getDBuser(), $o_Cliente->getDBpass(), $o_Cliente->getDBhost(), $o_Cliente->getDBport());

    /* GAE */
    if (GAE) {
        if (!$G_DbConn1->ConectarSocket()) {
            die();
        }
    }
    elseif (!$G_DbConn1->Conectar()) {
        die();
    }

    /* REGISTRO DB CONN */
    Registry::getInstance()->DbConn = $G_DbConn1;

    /* TIMEZONE */
    $timezone = Config_L::p('timezone');

    /* SIN TIMEZONE: EXIT */
    if ($timezone == '') continue;

    /* PHP TIMEZONE */
    date_default_timezone_set($timezone);

    /* SQL TIMEZONE */
    Registry::getInstance()->DbConn->Query("SET time_zone = '" . $timezone . "';");

    /*+**+**+**+**+**+**+**+* SUSCRIPCIONES ****+**+**+**+**+**+*/

    /* CLIENTES */

    $a_o_Clientes       = Cliente_L::obtenerTodosEnabled();


    /* SIN CLIENTES ACTIVOS: SALIR */
    if (is_null($a_o_Clientes)) continue;

    /* CLIENTES: UPDATE SUSCRIPCION */
    foreach ($a_o_Clientes as $o_Cliente_Key  => $o_Cliente) {

        $o_Cliente  = new Cliente_O();

        /* VARIABLES */
        $a_o_Suscripciones                      = Suscripcion_L::obtenerTodosPorClienteId($o_Cliente_Key);
        $a_o_Suscripciones_Activas              = null;
        $a_o_Suscripciones_Factura_Pendiente    = null;

        // BUSCAR LAS SUSCRIPCIONES ACTIVAS. // VER CUALES HAY QUE FACTURAR Y PONER EN UN ARRAY
        foreach ($a_o_Suscripciones as $o_Suscripcion_Key  => $o_Suscripcion) {
            $o_Suscripcion = new Suscripcion_O();

            // VARIABLES
            $status_suscripcion         = $o_Suscripcion->get_status('EN');
            $cost_suscripcion           = $o_Suscripcion->get_auto_recurring_transaction_amount();
            $factura_CAE_suscripcion    = $o_Suscripcion->get_factura_CAE();

            // SUSCRIPCIONES ACTIVAS
            if ($status_suscripcion == 'Activa') {
                $a_o_Suscripciones_Activas[] = $o_Suscripcion;
            }

            // SUSCRIPCIONES PENDIENTES DE FACTURAR
            if ($status_suscripcion == 'Activa' && $cost_suscripcion > 0 && $factura_CAE_suscripcion == "") {
                $a_o_Suscripciones_Factura_Pendiente[] = $o_Suscripcion;
            }
        }


        // HAY SUSCRIPCIONES ACTIVAS: ACTUALIZAR VENCIDAS, LISTAR PENDIENTES DE FACTURAR
        if (!is_null($a_o_Suscripciones_Activas)) {
            // ACTUALIZAR SUSCRIPCIONES ACTIVAS Y VER SI ESTAN VENCIDAS

            // SI ESTAN VENCIDAS: CAMBIAR SU ESTADO A INACTIVA
        }


        // HAY FACTURAS PENDIENTES: FACTURAR // TODO
        if (!is_null($a_o_Suscripciones_Factura_Pendiente)){
            // CREAR PETICION EN AFIP //TODO: INTEGRAR SDK AFIP

            // GUARDAR CAE

            // GUARDAR ARCHIVO EN BUCKET //TODO: CREAR BUCKET FACTURAS

            // ENVIAR MAIL CON ARCHIVO ADJUNTO
        }





    }


}

