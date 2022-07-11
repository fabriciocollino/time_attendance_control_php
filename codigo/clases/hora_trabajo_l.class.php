<?php

/**
 * Hora Trabajo (List)
 *
 */
class Hora_Trabajo_L {

    /**
     * Obtiene un Hora Trabajo por ID.
     *
     * @param integer $p_Id
     * @return Hora_Trabajo_O
     */
    public static function obtenerPorId($p_Id) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_Id = (integer) $p_Id;

        $row = $cnn->Select_Fila("SELECT * FROM hora_trabajo WHERE hor_Id = ? ORDER BY hor_Id", array($p_Id));
        $object = null;

        if (!empty($row)) {
            $object = new Hora_Trabajo_O();
            $object->loadArray($row);
        }

        if($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }

    /**
     * Permite obtener un Hora_Trabajo_O que tiene un Detalle especifico.
     *
     * Si el detalle no existe entonces la función devuelve NULL.
     *
     * @param string $p_Detalle
     * @return Hora_Trabajo_O
     */
    public static function obtenerPorDetalle($p_Detalle, $p_Id = 0) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($p_Id != 0) {
            $p_Id = ' AND hor_Id <> ' . $p_Id;
        } else {
            $p_Id = '';
        }

        $row = $cnn->Select_Fila("SELECT * FROM hora_trabajo WHERE hor_Detalle = ? {$p_Id} ORDER BY hor_Id", array($p_Detalle));
        $object = null;

        if (!empty($row)) {
            $object = new Hora_Trabajo_O();
            $object->loadArray($row);
        }

        if($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }

    /**
     * Permite obtener un Hora_Trabajo_O que tiene un Codigo especifico.
     *
     * Si la codigo no existe entonces la función devuelve NULL.
     *
     * @param string $p_Codigo
     * @return Hora_Trabajo_O
     */
    public static function obtenerPorCodigo($p_Codigo) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $row = $cnn->Select_Fila("SELECT * FROM hora_trabajo WHERE hor_Codigo = ? ORDER BY hor_Id", array($p_Codigo));
        $object = null;

        if (!empty($row)) {
            $object = new Hora_Trabajo_O();
            $object->loadArray($row);
        }

        if($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }

    /**
     * Permite obtener un listado de objetos Hora_Trabajo_O.
     *
     */
    public static function obtenerTodos($p_condicion = '') {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($p_condicion != '') {
            $p_condicion = 'WHERE ' . $p_condicion;
        }

        $rows = $cnn->Select_Lista("SELECT * FROM hora_trabajo {$p_condicion} ORDER BY hor_Id");
        $object = null;
        $list = array();
        if ($rows) {
            foreach ($rows as $row) {
                $object = new Hora_Trabajo_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        } else {
            $list = $object;
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }

    /**
     * Permite obtener un listado de objetos Hora_Trabajo_O ordenados en un array por indice.
     *
     */
    public static function obtenerTodosenArray($p_condicion = '') {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($p_condicion != '') {
            $p_condicion = 'WHERE ' . $p_condicion;
        }

        $rows = $cnn->Select_Lista("SELECT * FROM hora_trabajo {$p_condicion} ORDER BY hor_Id");
        $object = null;
        $list = array();
        if ($rows) {
            foreach ($rows as $row) {
                $object = new Hora_Trabajo_O();
                $object->loadArray($row);
                $list[$object->getId()] = $object;
            }
        } else {
            $list = $object;
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }

    public static function obtenerTodosArray() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;


        $rows = $cnn->Select_Lista("SELECT * FROM hora_trabajo ORDER BY hor_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $list[$row['hor_Id']] = $row;
            }
        } else {
            $list = $object;
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }



}
