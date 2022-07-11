<?php

/**
 * Cliente (List)
 * 
 */
class Cliente_L {

	/**
	 * Permite obtener un Cliente_O utilizando el $cliente y $clave.
	 * 
	 * Si el cliente no existe, o si la clave proporcionada no es valida,
	 * entonces la función devuelve NULL.
	 *
	 * @param string $p_Cliente
	 * @param string $p_Clave
	 * @return Cliente_O
	 */
	public static function obtenerPorClientID($p_Cid) {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConnMGR;

		$row = $cnn->Select_Fila("SELECT * FROM clientes WHERE cli_Id = ? ORDER BY cli_Id", array($p_Cid));

		$object = null;

		if (!empty($row)) {

				$object = new Cliente_O();
				$object->loadArray($row);
			
		}
		
		if($row === false) { // devuelve el error si algo fallo con MySql
			echo $cnn->get_Error(Registry::getInstance()->general['debug']);
		}

		return $object;
	}

	/**
	 * Obtiene un Cliente por ID.
	 * 
	 * @param integer $p_Id
	 * @return Cliente_O
	 */
	public static function obtenerPorId($p_Id) {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConnMGR;

		$p_Id = (integer) $p_Id;


		$row = $cnn->Select_Fila("SELECT * FROM clientes WHERE cli_Id = ? ORDER BY cli_Id", array($p_Id));
		
		$object = null;
		if (!empty($row)) {
			$object = new Cliente_O();
			$object->loadArray($row);
		} 
		
		if($row === false) { // devuelve el error si algo fallo con MySql
			echo $cnn->get_Error(Registry::getInstance()->general['debug']);
		}

		return $object;
	}

	/**
	 * Permite obtener un Cliente_O utilizando el $cliente.
	 *
	 * Si el cliente no existe, o si la clave proporcionada no es valida,
	 * entonces la función devuelve NULL.
	 *
	 * @param string $p_Cliente
	 * @return Cliente_O
	 */
	public static function obtenerPorNombreCliente($p_Cliente, $p_Id = 0) {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConnMGR;

		if ($p_Id != 0) {
			$p_Id = ' AND cli_Id <> ' . $p_Id;
		} else {
			$p_Id = '';
		}

		$row = $cnn->Select_Fila("SELECT * FROM clientes WHERE cli_Cliente = ?{$p_Id} ORDER BY cli_Id", array($p_Cliente));
		$object = null;

		if (!empty($row)) {
			$object = new Cliente_O();
			$object->loadArray($row);
		}

		if($row === false) { // devuelve el error si algo fallo con MySql
			echo $cnn->get_Error(Registry::getInstance()->general['debug']);
		}
		
		return $object;
	}


    public static function obtenerPorEmail($p_Email) {

        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;


        $row = $cnn->Select_Fila("SELECT * FROM clientes WHERE cli_Email = '{$p_Email}' ORDER BY cli_Id");
        $object = null;

        if (!empty($row)) {
            $object = new Cliente_O();
            $object->loadArray($row);
        }

        if($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;

    }


    public static function obtenerPorSubdominio($p_Subdominio) {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConnMGR;

		$row = $cnn->Select_Fila("SELECT * FROM clientes WHERE cli_Subdominio = '{$p_Subdominio}' ORDER BY cli_Id");
		$object = null;

		if (!empty($row)) {
			$object = new Cliente_O();
			$object->loadArray($row);
		}

		if($row === false) { // devuelve el error si algo fallo con MySql
			echo $cnn->get_Error(Registry::getInstance()->general['debug']);
		}
		
		return $object;
	}


	public static function obtenerTodosEnabled() {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConn;

		$rows = $cnn->Select_Lista("SELECT * FROM clientes WHERE cli_Enabled=1 ORDER BY cli_Id");
		$object = null;
		$list = array();

		if ($rows) {
			foreach ($rows as $row) {
				$object = new Cliente_O();
				$object->loadArray($row);
				$list[$object->getId()] = $object;
			}
		}else{
			$list = null;
		}

		if($rows === false) { // devuelve el error si algo fallo con MySql
			echo $cnn->get_Error(Registry::getInstance()->general['debug']);
		}

		return $list;
	}

    public static function obtenerPorToken($p_Token= '')
    {
        if($p_Token == ''){
            return null;
        }
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConnMGR;


        $row = $cnn->Select_Fila("SELECT * FROM clientes WHERE cli_Create_Token = '{$p_Token}' ORDER BY cli_Id");
        $object = null;

        if (!empty($row)) {
            $object = new Cliente_O();
            $object->loadArray($row);
        }

        if($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }


}
