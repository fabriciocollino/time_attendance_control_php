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

    /*+**+**+**+**+**+**+**+* REPORTES ****+**+**+**+**+**+*/

    /* NOTIFICACIONES */
    $a_r_Notificaciones = Notificaciones_L::obtenerTodosReportesActivos();

    /* SIN NOTIFICACIONES: SALIR */
    if (is_null($a_r_Notificaciones)) continue;

    /* REPORTES: ENVIAR */
    foreach ($a_r_Notificaciones as $o_Notificacion) {

        /* VARIABLES */
        $disparador_fecha_hora    = strtotime($o_Notificacion->getHoraD("Y-m-d H:i:s"));
        $cron_fecha_hora          = strtotime(date("Y-m-d H:i:s"));


        /* ENVIAR NOTIFICACION */
        if ($cron_fecha_hora >= $disparador_fecha_hora) {

            // QUITAR // PARA DESBLOQUEAR
            $o_Notificacion->enviarReporteAutomaticoObjeto();
            $o_Notificacion->setHoraD(date("Y-m-d H:i:s", strtotime($o_Notificacion->getHoraD("Y-m-d H:i:s") . $disparador_tipo[$o_Notificacion->getTipoD()])), "Y-m-d H:i:s");
            $o_Notificacion->save();
        }

    }

    /*+**+**+**+**+**+**+**+* ALERTAS ****+**+**+**+**+**+*/

    /* NOTIFICACIONES
    $a_a_Notificaciones = Notificaciones_L::obtenerTodasAlertasActivas();
    */
    /* SIN NOTIFICACIONES: SALIR
    if (is_null($a_a_Notificaciones)) continue;
    */

    /* ALERTAS: ENVIAR
    foreach ($a_a_Notificaciones as $o_Notificacion) {


        //$RESULT = $o_Notificacion->enviarAlertaObjeto();
       // echo $RESULT ;
        $o_Notificacion->setDisparadorHora(date('Y-m-d H:i:s'));
        $o_Notificacion->save();
    }
    */

}

