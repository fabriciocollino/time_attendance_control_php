<?php


class Mensaje_L {

    public static function obtenerPorId($p_Id){

        $cnn = Registry::getInstance() -> DbConn;

        $p_Id = (integer)$p_Id;

        $row = $cnn -> Select_Fila("SELECT * FROM mensajes WHERE men_Id = ? ORDER BY men_Id", array($p_Id));

        $object = null;
        if (!empty($row)) {
            $object = new Mensaje_O();
            $object -> loadArray($row);
        }

        if ($row === false) {// devuelve el error si algo fallo con MySql
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $object;
    }


    public static function obtenerTodos($p_Condicion = '', $p_Tablas = '',$p_Cantidad = ''){

        $cnn = Registry::getInstance() -> DbConn;

        if ($p_Condicion != '') {
            $p_Condicion = 'WHERE ' . $p_Condicion;
        }

        if ($p_Tablas != '') {
            $p_Tablas = ', ' . $p_Tablas;
        }

        if ($p_Cantidad != '') {
            $p_Cantidad = ' LIMIT 0,' . $p_Cantidad;
        }

        $rows = $cnn -> Select_Lista("SELECT * FROM mensajes {$p_Tablas} {$p_Condicion} ORDER BY men_Id DESC {$p_Cantidad}");

        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Mensaje_O();
                $object -> loadArray($row);
                $list[] = $object;
            }
        } else {
            $list = $object;
        }
        if ($rows === false) {// devuelve el error si algo fallo con MySql
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $list;
    }


    public static function obtenerCantidad(){

        $cnn = Registry::getInstance() -> DbConn;

        $row = $cnn -> Select_Fila("SELECT COUNT(men_Id) as Cantidad FROM mensajes ");

        if ($row === false) {// devuelve el error si algo fallo con MySql
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $row['Cantidad'];
    }


    public static function obtenerCantidadUnread(){

        $cnn = Registry::getInstance() -> DbConn;

        $row = $cnn -> Select_Fila("SELECT COUNT(men_Id) as Cantidad FROM mensajes WHERE men_Visto=0");

        if ($row === false) {// devuelve el error si algo fallo con MySql
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $row['Cantidad'];
    }

    public static function obtenerTodosUnStatus(){

        $cnn = Registry::getInstance() -> DbConn;

        $rows = $cnn -> Select_Lista("SELECT * FROM mensajes WHERE men_Visto=1 and men_Status=0");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Mensaje_O();
                $object -> loadArray($row);
                $list[] = $object;
            }
        } else {
            $list = $object;
        }
        if ($rows === false) {// devuelve el error si algo fallo con MySql
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $list;
    }

    public static function obtenerTodosHoy(){

        $cnn = Registry::getInstance() -> DbConn;

        $rows = $cnn -> Select_Lista("SELECT * FROM mensajes WHERE men_Visto=0 and men_DisparadoHora like '".date('Y-m-d')."%' ");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Mensaje_O();
                $object -> loadArray($row);
                $list[] = $object;
            }
        } else {
            $list = $object;
        }
        if ($rows === false) {// devuelve el error si algo fallo con MySql
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $list;
    }



}


