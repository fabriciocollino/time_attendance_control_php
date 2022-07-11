<?php


class Planes_L {

    // GET PLAN BY ID
	public static function obtenerPorId($p_Id){

		$cnn = Registry::getInstance() -> DbConnMGR;

		$p_Id = (integer)$p_Id;

		$row = $cnn -> Select_Fila("SELECT * FROM planes WHERE plan_Id = ? ORDER BY plan_Id", array($p_Id));

		$object = null;
		if (!empty($row)) {
			$object = new Planes_O();
			$object -> loadArray($row);
		}

		if ($row === false) {// devuelve el error si algo fallo con MySql
			echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
		}

		return $object;
	}

    // GET PLANES
	public static function obtenerTodos($p_Condicion = '', $p_Tablas = ''){

		$cnn = Registry::getInstance() -> DbConnMGR;

		if ($p_Condicion != '') {
			$p_Condicion = 'WHERE ' . $p_Condicion;
		}

		if ($p_Tablas != '') {
			$p_Tablas = ', ' . $p_Tablas;
		}

		$rows = $cnn -> Select_Lista("SELECT * FROM planes {$p_Tablas} {$p_Condicion} ORDER BY plan_Id");

		$object = null;
		$list = array();

		if ($rows) {
			foreach ($rows as $row) {
				$object = new Planes_O();
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

	// GET PLANES ACTIVOS
    public static function obtenerTodosActivos(){

        $cnn = Registry::getInstance() -> DbConnMGR;

        $rows = $cnn -> Select_Lista("SELECT * FROM planes WHERE plan_Activo=1 ORDER BY plan_Id");

        $list = array();

        // PLANES ACTIVOS
        if ($rows) {
            foreach ($rows as $row) {
                $object = null;
                $object = new Planes_O();
                $object -> loadArray($row);
                $list[$object ->getId()] = $object;
            }
        }
        // NO HAY PLANES ACTIVOS
        else {
            $list = null;
        }

        // ERROR SQL
        if ($rows === false) {// devuelve el error si algo fallo con MySql
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $list;
    }

    public static function obtenerTodosArray($p_Condicion = '', $p_Tablas = ''){

        $cnn = Registry::getInstance() -> DbConnMGR;

        if ($p_Condicion != '') {
            $p_Condicion = 'WHERE ' . $p_Condicion;
        }

        if ($p_Tablas != '') {
            $p_Tablas = ', ' . $p_Tablas;
        }

        $rows = $cnn -> Select_Lista("SELECT * FROM planes {$p_Tablas} {$p_Condicion} ORDER BY plan_Id");

        $object = null;
        $list = array();

        if (!is_null($rows)) {
            foreach ($rows as $row) {
                $list[$row["plan_Id"]] = $row;
            }
        } else {
            $list = $object;
        }
        if ($rows === false) {// devuelve el error si algo fallo con MySql
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $list;
    }

    // GET PLAN BY MERCADOPAGO ID
    public static function obtenerPorMercadoPagoId($p_MercadoPago_Id){

        $cnn = Registry::getInstance() -> DbConnMGR;

        $row = $cnn -> Select_Fila("SELECT * FROM planes WHERE plan_Mercadopago_Plan_Id = ? ORDER BY plan_Id", array($p_MercadoPago_Id));

        $object = null;
        if (!empty($row)) {
            $object = new Planes_O();
            $object -> loadArray($row);
        }

        if ($row === false) {
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $object;
    }


}