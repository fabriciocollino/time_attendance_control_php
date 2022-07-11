<?php

class Planning_L
{

    public static function obtenerPorId($pId) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $pId = (integer)$pId;

        $row = $cnn->Select_Fila("SELECT plan_Id, CAST(plan_Event as CHAR) as plan_Event FROM planning WHERE plan_Id = ? ORDER BY plan_Id", array($pId),true);
        $object = null;

        if (!empty($row)) {
            $object = new Planning_O();
            $object->loadArray($row);
        }

        if ($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }

    public static function obtenerPorJSON($pJson) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $pJson = (string) $pJson;

        $row = $cnn->Select_Fila("SELECT plan_Id, CAST(plan_Event as CHAR) as plan_Event FROM planning WHERE ". $pJson ." ORDER BY plan_Id", '',true);
        $object = null;

        if (!empty($row)) {
            $object = new Planning_O();
            $object->loadArray($row);
            //$object->setEvent(json_decode($object->getEvent(),true));
        }

        if ($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }


    public static function obtenerTodos() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT plan_Id, CAST(plan_Event as CHAR) as plan_Event FROM planning ORDER BY plan_Id",'',true);
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Planning_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        } else {
            $list = null;
        }

        if ($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }

    public static function obtenerJSONFeedPorFechas($p_Start, $p_End) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT plan_Id, CAST(plan_Event as CHAR) as plan_Event FROM planning WHERE STR_TO_DATE(TRIM(BOTH '\"' FROM CAST(`plan_Event` -> '$.start' as CHAR)),'%Y-%m-%dT%H:%i:%s.000Z') >= \"".$p_Start." 00:00:00\" AND (STR_TO_DATE(TRIM(BOTH '\"' FROM CAST(`plan_Event` -> '$.end' as CHAR)),'%Y-%m-%dT%H:%i:%s.000Z') <= \"".$p_End." 00:00:00\" OR STR_TO_DATE(TRIM(BOTH '\"' FROM CAST(`plan_Event` -> '$.end' as CHAR)),'%Y-%m-%dT%H:%i:%s.000Z') IS NULL) ORDER BY plan_Id",'',true);

        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Planning_O();
                $object->loadArray($row);
                $list[] = json_decode($object->getEvent(),true);
            }
        } else {
            $list = null;
        }

        if ($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return json_encode($list);
    }


}
