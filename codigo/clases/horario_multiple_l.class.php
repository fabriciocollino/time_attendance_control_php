<?php

/**
 * Hora Trabajo (List)
 *
 */
class Horario_Multiple_L
{

    /**
     * Obtiene un Horario Multiple por ID.
     *
     * @param integer $p_Id
     * @return Horario_Multiple_O
     */
    public static function obtenerPorId($p_Id) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_Id = (integer)$p_Id;

        $row = $cnn->Select_Fila("SELECT * FROM horarios_multiples WHERE hmu_Id = ? ORDER BY hmu_Id", array($p_Id));
        $object = null;

        if (!empty($row)) {
            $object = new Horario_Multiple_O();
            $object->loadArray($row);
        }

        if ($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }

    /**
     * Permite obtener un Horario_Rotativo_O que tiene un Detalle especifico.
     *
     * Si el detalle no existe entonces la función devuelve NULL.
     *
     * @param string $p_Detalle
     * @return Horario_Multiple_O
     */
    public static function obtenerPorDetalle($p_Detalle, $p_Id = 0) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($p_Id != 0) {
            $p_Id = ' AND hmu_Id <> ' . $p_Id;
        } else {
            $p_Id = '';
        }

        $row = $cnn->Select_Fila("SELECT * FROM horarios_multiples WHERE hmu_Detalle = ? {$p_Id} ORDER BY hmu_Id", array($p_Detalle));
        $object = null;

        if (!empty($row)) {
            $object = new Horario_Multiple_O();
            $object->loadArray($row);
        }

        if ($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }


    /**
     * Permite obtener un listado de objetos Horario_Multiple_O.
     *
     */
    public static function obtenerTodos($p_condicion = '') {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($p_condicion != '') {
            $p_condicion = 'WHERE ' . $p_condicion;
        }

        $rows = $cnn->Select_Lista("SELECT * FROM horarios_multiples {$p_condicion} ORDER BY hmu_Id");
        $object = null;
        $list = array();
        if ($rows) {
            foreach ($rows as $row) {
                $object = new Horario_Multiple_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        } else {
            $list = $object;
        }

        if ($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }

    /**
     * Permite obtener un listado de objetos Horario_Multiple_O.
     *
     */
    public static function obtenerTodosenArray($p_condicion = '') {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($p_condicion != '') {
            $p_condicion = 'WHERE ' . $p_condicion;
        }

        $rows = $cnn->Select_Lista("SELECT * FROM horarios_multiples {$p_condicion} ORDER BY hmu_Id");
        $object = null;
        $list = array();
        if ($rows) {
            foreach ($rows as $row) {
                $object = new Horario_Multiple_O();
                $object->loadArray($row);
                $list[$object->getId()] = $object;
            }
        } else {
            $list = $object;
        }

        if ($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }

    /**
     * devuelve un array con las IDs de los horarios dentro del horario multiple
     *
     */
    public static function obtenerTodosLosHorariosDelHorario($p_id) {
        /* @var $cnn mySQL */
        $p_id = (integer)$p_id;

        $o_horario = self::obtenerPorId($p_id);

        $horarios = $o_horario->getHorarios();

        $eventArray = array();
        if (!is_null($horarios) && $horarios != '') {
            $horarios = json_decode($horarios, true);

            foreach ($horarios as $event) {
                if ($event['id'] == null) continue;
                $eventArray[] = $event['id'];
            }
        }

        return $eventArray;
    }


    /**
     * devuelve un integer con la cantidad de veces que el horario esta en el horario
     *
     */
    public static function obtenerCantidadHorarioNormal($p_id) {
        /* @var $cnn mySQL */
        $p_id = (integer)$p_id;

        $cantidad=0;

        $a_o_horarios = self::obtenerTodos();

        if(count($a_o_horarios) == 0) return $cantidad;

        foreach($a_o_horarios as $o_horario){


            $horarios = $o_horario->getHorarios();
            $eventArray = array();
            if (!is_null($horarios) && $horarios != '') {
                $horarios = json_decode($horarios, true);

                foreach ($horarios as $event) {
                    if ($event['horario_id'] == null) continue;
                    $eventArray[] = $event['horario_id'];
                }
                if(in_array($p_id,$eventArray))$cantidad++;
            }

        }

        return $cantidad;
    }

    public static function obtenerTodosArray() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT * FROM horarios_multiples ORDER BY hmu_Id");
        $object = null;
        $list = array();
        if ($rows) {
            foreach ($rows as $row) {
                $list[$row['hmu_Id']] = $row;
            }
        } else {
            $list = $object;
        }

        if ($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }

}
