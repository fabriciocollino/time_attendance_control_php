<?php


class Notas_L {

	public static function obtenerPorId($p_Id){

		$cnn = Registry::getInstance() -> DbConn;

		$p_Id = (integer)$p_Id;

		$row = $cnn -> Select_Fila("SELECT * FROM notas WHERE not_Id = ? ORDER BY not_Id", array($p_Id));

		$object = null;
		if (!empty($row)) {
			$object = new Notas_O();
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

		$rows = $cnn -> Select_Lista("SELECT * FROM notas {$p_Tablas} {$p_Condicion} ORDER BY not_Id DESC {$p_Cantidad}");

		$object = null;
		$list = array();

		if ($rows) {
			foreach ($rows as $row) {
				$object = new Notas_O();
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

    public static function getAllbyPersonaId($p_PerId, $p_Tablas = '',$p_Cantidad = ''){

        $cnn = Registry::getInstance() -> DbConn;

        $p_Condicion = 'WHERE not_Persona_Id=' . $p_PerId;

        if ($p_Tablas != '') {
            $p_Tablas = ', ' . $p_Tablas;
        }

        if ($p_Cantidad != '') {
            $p_Cantidad = ' LIMIT 0,' . $p_Cantidad;
        }

        $rows = $cnn -> Select_Lista("SELECT * FROM notas {$p_Tablas} {$p_Condicion} ORDER BY not_Id DESC {$p_Cantidad}");

        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Notas_O();
                $object -> loadArray($row);
                $list[] = $object;
            }
        }

        if ($rows === false) {// devuelve el error si algo fallo con MySql
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $list;
    }

	public static function obtenerCantidad(){

		$cnn = Registry::getInstance() -> DbConn;

		$row = $cnn -> Select_Fila("SELECT COUNT(not_Id) as Cantidad FROM notas ");

		if ($row === false) {// devuelve el error si algo fallo con MySql
			echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
		}

		return $row['Cantidad'];
	}


}


