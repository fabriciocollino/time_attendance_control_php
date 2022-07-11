<?php

class Notificaciones_L {

    public static function obtenerPorId($pId) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $pId = (integer) $pId;

        $row = $cnn->Select_Fila("SELECT * FROM notificaciones WHERE not_Id = ? ORDER BY not_Id", array($pId));
        $object = null;

        if (!empty($row)) {
            $object = new Notificaciones_O();
            $object->loadArray($row);
        }

        if($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }

    public static function obtenerPorDetalle($p_Detalle) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $row = $cnn->Select_Fila("SELECT * FROM notificaciones WHERE not_Detalle = ? ORDER BY not_Id", array($p_Detalle));
        $object = null;

        if (!empty($row)) {
            $object = new Notificaciones_O();
            $object->loadArray($row);
        }

        if($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }
    public static function obtenerTodasAlertasActivas() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT * FROM notificaciones WHERE not_Activa = 1 AND ndi_Tipo=0 OR ndi_Tipo=100 ORDER BY not_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Notificaciones_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        }else{
            $list = $object;
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }


    public static function obtenerTodosActivos() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT * FROM notificaciones WHERE not_Activa = 1 ORDER BY not_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Notificaciones_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        }else{
            $list = $object;
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }


    public static function obtenerPorGrupo($p_Grupo) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_Grupo = (integer) $p_Grupo;

        $rows = $cnn->Select_Lista("SELECT * FROM notificaciones WHERE not_Grupo = ".$p_Grupo." ORDER BY not_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Notificaciones_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        }else{
            $list = $object;
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }


    public static function obtenerTodos() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT * FROM notificaciones ORDER BY not_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Notificaciones_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        }else{
            $list = $object;
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }

    public static function obtenerTodasAlertas() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT * FROM notificaciones WHERE ndi_Tipo=0 OR ndi_Tipo=100 ORDER BY not_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Notificaciones_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        }else{
            $list = $object;
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }



    public static function obtenerTodosReportes() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT * FROM notificaciones WHERE ndi_Tipo<>0 AND ndi_Tipo<>100 ORDER BY not_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Notificaciones_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        }else{
            $list = $object;
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }

    public static function obtenerTodosReportesActivos() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT * FROM notificaciones WHERE not_Activa = 1 AND ndi_Tipo<>0 AND ndi_Tipo<>100 ORDER BY not_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Notificaciones_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        }else{
            $list = $object;
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }

    public static function obtenerTodosNotMensaje($p_condicion = '') { // abduls
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($p_condicion != '') {
            $p_condicion = 'WHERE ' . $p_condicion;
        }

        return $rows = $cnn->Select_Lista("SELECT * FROM logs_alert_mensaje {$p_condicion}  ");
    }



    public static function obtenerTodosAlertasArray() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT * FROM notificaciones WHERE ndi_Tipo=0 OR ndi_Tipo=100 ORDER BY not_Id");
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $list[$row['not_Id']] = $row;
            }
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }

    public static function obtenerTodosReportesArray() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT * FROM notificaciones WHERE not_Activa = 1 AND ndi_Tipo<>0 AND ndi_Tipo<>100 ORDER BY not_Id");
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $list[$row['not_Id']] = $row;
            }
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }

}
