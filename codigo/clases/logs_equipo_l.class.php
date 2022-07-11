<?php

class Logs_Equipo_L {

    public static function obtenerPorId($pId) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $pId = (integer) $pId;

        $row = $cnn->Select_Fila("SELECT * FROM logs_equipo WHERE leq_Id = ? ORDER BY leq_Id", array($pId));
        $object = null;


        if (!empty($row)) {
                $object = new Logs_equipo_O();
                $object->loadArray($row);
        }

        if ($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }

	public static function obtenerPorEquipoId($pEqId) {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConn;

		$pEqId = (integer) $pEqId;

		$rows = $cnn->Select_Lista("SELECT * FROM logs_equipo WHERE leq_Eq_Id = ? ORDER BY leq_Id", array($pEqId));
		$object = null;
		$list = array();

		if ($rows) {
			foreach ($rows as $row) {
				$object = new Logs_equipo_O();
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

	public static function obtenerPorPersona($p_Per) {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConn;

		$p_Per = (integer) $p_Per;

		$rows = $cnn->Select_Lista("SELECT * FROM logs_equipo WHERE leq_Per_Id = ? ORDER BY leq_Id", array($p_Per));
		$object = null;
		$list = array();

		if ($rows) {
			foreach ($rows as $row) {
				$object = new Logs_equipo_O();
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

	public static function getCountxPersona($p_Per,$p_Condicion='') {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConn;

		$p_Per = (integer) $p_Per;

		if ($p_Condicion != '') {
			$p_Condicion = 'AND ' . $p_Condicion;
		}

		$row = $cnn->Select_Fila("SELECT COUNT('leq_id') as cuenta FROM logs_equipo WHERE leq_Per_Id = ? {$p_Condicion} ORDER BY leq_Id", array($p_Per));
		$object = null;
		$count=0;

		if (!empty($row)) {
			$count=$row['cuenta'];
		}

		if ($row === false) { // devuelve el error si algo fallo con MySql
			echo $cnn->get_Error(Registry::getInstance()->general['debug']);
		}

		return $count;
	}

	public static function getCount($p_Condicion='') {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConn;

		if ($p_Condicion != '') {
			$p_Condicion = 'WHERE ' . $p_Condicion;
		}

		$row = $cnn->Select_Fila("SELECT COUNT('leq_id') AS cuenta FROM logs_equipo {$p_Condicion} ORDER BY leq_Id");
		$object = null;
		$count=0;

		if (!empty($row)) {
			$count=$row['cuenta'];
		}

		if ($row === false) { // devuelve el error si algo fallo con MySql
			echo $cnn->get_Error(Registry::getInstance()->general['debug']);
		}

		return $count;
	}

	public static function obtenerEstadosEquipo($p_Fecha) {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConn;
		//echo "SELECT * FROM logs_equipo WHERE leq_Accion = 52 AND '" . $p_Fecha . "' <= leq_Fecha_Hora OR leq_Accion = 53 AND '" . $p_Fecha . "' <= leq_Fecha_Hora OR leq_Accion = 54 AND '" . $p_Fecha . "' <= leq_Fecha_Hora ORDER BY leq_Id DESC";
		$rows = $cnn->Select_Lista("SELECT * FROM logs_equipo WHERE leq_Accion = 52 AND '" . $p_Fecha . "' <= leq_Fecha_Hora OR leq_Accion = 53 AND '" . $p_Fecha . "' <= leq_Fecha_Hora OR leq_Accion = 54 AND '" . $p_Fecha . "' <= leq_Fecha_Hora ORDER BY leq_Id DESC");
		$object = null;
		$list = array();

		if ($rows) {
			foreach ($rows as $row) {
				$object = new Logs_equipo_O();
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
	 * Devuelve los logs de una persona, desde el intervalo de tiempo enviado (en segundos) hasta la actualidad
	 * 
	 * 
	 * @param int $p_Persona	persona
	 * @param int $p_Tiempo		Tiempo en segundos
	 * @return array		Listado de objetos
	 */
	public static function obtenerPorPersonaYTiempo($p_Persona, $p_Tiempo = 0) {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConn;


		$rows = $cnn->Select_Lista("SELECT * FROM logs_equipo  WHERE leq_Per_Id = " . $p_Persona . " AND DATE_SUB(NOW(),INTERVAL " . $p_Tiempo . " SECOND) <= leq_Fecha_Hora ORDER BY leq_Id DESC");
		$object = null;
		$list = array();

		if ($rows) {
			foreach ($rows as $row) {
				$object = new Logs_Equipo_O();
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
	 * Devuelve los logs de una persona, desde la fecha enviada hasta la actualidad
	 * 
	 * 
	 * @param int $p_Persona	persona
	 * @param int $p_Tiempo		Tiempo en segundos
	 * @return Logs_Equipo_O		Listado de objetos
	 */
	public static function obtenerPorPersonaYFecha($p_Persona, $p_Tiempo) {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConn;


		$rows = $cnn->Select_Lista("SELECT * FROM logs_equipo  WHERE leq_Per_Id = " . $p_Persona . " AND '" . $p_Tiempo . "'  <= leq_Fecha_Hora ORDER BY leq_Id DESC");
		$object = null;
		$list = array();

		if ($rows) {
			foreach ($rows as $row) {
				$object = new Logs_Equipo_O();
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
     * Devuelve un log buscando por persona y la fecha/hora del log
     *
     *
     * @param int $p_Persona	persona
     * @param int $p_FechaHora  fecha hora
     * @return Logs_Equipo_O		objeto log
     */
    public static function obtenerPorPersonayFechaHora($p_Persona,$p_FechaHora) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_Persona = (integer) $p_Persona;
        $p_FechaHora = (string) $p_FechaHora;

        $row = $cnn->Select_Fila("SELECT * FROM logs_equipo WHERE leq_Per_Id = ? AND leq_Fecha_Hora = '".$p_FechaHora."' ORDER BY leq_Id", array($p_Persona));
        $object = null;


        if (!empty($row)) {
            $object = new Logs_equipo_O();
            $object->loadArray($row);
        }

        if ($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }

	/**
	 * Mostrar todo paginado
	 * 
	 * 
	 * @param int $p_Total_Registros	Total de registros de la tabla
	 * @param int $p_Pagina_Actual		Pagina en donde estoy
	 * @param int $p_Cant_Listar		Cantidad de registros a listar
	 * @return Logs_Equipo_O		Listado de objetos
	 */
	public static function obtenerTodos($p_Pagina_Actual, $p_Cant_Listar, $p_Total_Registros, $p_Condicion = '', $p_Tablas = '') {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConn;

		//Para el paginado		
		if ($p_Pagina_Actual <= 1) {//Controla que no sea menor de 0 ya que no se puede paginar pode valores negativos ej:-1
			$p_Pagina_Actual = 1;
		} elseif ($p_Pagina_Actual >= ceil($p_Total_Registros / $p_Cant_Listar)) {//Controla que no sean valores que superen los que tenemos ej:9999
			//ceil — Redondear fracciones hacia arriba
			$p_Pagina_Actual = ceil($p_Total_Registros / $p_Cant_Listar);
		}
		/* Fin paginado */

		if ($p_Condicion != '') {
			$p_Condicion = 'WHERE ' . $p_Condicion;
		}
		//echo "SELECT * FROM logs_equipo {$p_Tablas} {$p_Condicion} ORDER BY leq_Fecha_Hora DESC LIMIT " . ($p_Pagina_Actual - 1) * $p_Cant_Listar . " , {$p_Cant_Listar}";
		$rows = $cnn->Select_Lista("SELECT * FROM logs_equipo {$p_Tablas} {$p_Condicion} ORDER BY leq_Fecha_Hora DESC LIMIT " . ($p_Pagina_Actual - 1) * $p_Cant_Listar . " , {$p_Cant_Listar}");
		$object = null;
		$list = array();

		if ($rows) {
			foreach ($rows as $row) {
				$object = new Logs_Equipo_O();
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
	 * Mostrar todo paginado
	 * 
	 * 
	 * @param int $p_Total_Registros	Total de registros de la tabla
	 * @param int $p_Pagina_Actual		Pagina en donde estoy
	 * @param int $p_Cant_Listar		Cantidad de registros a listar
	 * @return array			Listado de registros en un array
	 */
	public static function obtenerTodosEnArray($p_Pagina_Actual, $p_Cant_Listar, $p_Total_Registros, $p_Condicion = 'ORDER BY leq_Id ASC', $p_Tablas = '', $p_Campo = '*') {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConn;

		//Para el paginado
		if ($p_Pagina_Actual != '') {
			if ($p_Pagina_Actual <= 1) {//Controla que no sea menor de 0 ya que no se puede paginar pode valores negativos ej:-1
				$p_Pagina_Actual = 1;
			} elseif ($p_Pagina_Actual >= ceil($p_Total_Registros / $p_Cant_Listar)) {//Controla que no sean valores que superen los que tenemos ej:9999
				//ceil — Redondear fracciones hacia arriba
				$p_Pagina_Actual = ceil($p_Total_Registros / $p_Cant_Listar);
			}
		}
		/* Fin paginado */

		if ($p_Condicion != '') {
			$p_Condicion = 'WHERE ' . $p_Condicion;
		}
		//echo "SELECT {$p_Campo} FROM logs_equipo {$p_Tablas} {$p_Condicion} LIMIT " . ($p_Pagina_Actual - 1) * $p_Cant_Listar . " , {$p_Cant_Listar}";
		if ($p_Pagina_Actual != '') {
			$rows = $cnn->Select_Lista("SELECT {$p_Campo} FROM logs_equipo {$p_Tablas} {$p_Condicion} LIMIT " . ($p_Pagina_Actual - 1) * $p_Cant_Listar . " , {$p_Cant_Listar}");
		} else {
			$rows = $cnn->Select_Lista("SELECT {$p_Campo} FROM logs_equipo {$p_Tablas} {$p_Condicion} ");
		}

		//echo "SELECT {$p_Campo} FROM logs_equipo {$p_Tablas} {$p_Condicion} ";

		if ($rows === false) { // devuelve el error si algo fallo con MySql
			echo $cnn->get_Error(Registry::getInstance()->general['debug']);
		}

		return $rows;
	}

    public static function obtenerCantidadesEnArray($listado_personas, $fechaD, $fechaH)
    {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT leq_Per_Id, COUNT(leq_Per_Id) as cantidades, per_Nombre, per_Apellido,per_Legajo, MAX(logs_equipo.leq_Fecha_Hora) leq_Fecha_Hora, MAX(leq_Eq_Id) leq_Eq_Id 
                                    FROM logs_equipo, personas 
                                    WHERE leq_Per_Id IN ({$listado_personas}) 
                                    AND logs_equipo.leq_Per_Id = personas.per_Id
                                    AND logs_equipo.leq_Fecha_Hora >= '{$fechaD}' 
                                    AND logs_equipo.leq_Fecha_Hora <= '{$fechaH}' 
                                    AND logs_equipo.leq_Accion = 1
                                    GROUP BY logs_equipo.leq_Per_Id 
                                    ORDER BY logs_equipo.leq_Per_Id, logs_equipo.leq_Fecha_Hora ASC");

        if ($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $rows;
    }

	/**
	 * Agregue esta para mantener compatibilidad, porque ya tengo codigo que utiliza estos parametros. (order)
	 */
	public static function obtenerTodosO($p_Pagina_Actual, $p_Cant_Listar, $p_Total_Registros, $p_condicion = '', $p_order='DESC', $p_orderBy='leq_id') {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConn;

		//Para el paginado		
		if ($p_Pagina_Actual <= 1) {//Controla que no sea menor de 0 ya que no se puede paginar pode valores negativos ej:-1
			$p_Pagina_Actual = 1;
		} elseif ($p_Pagina_Actual >= ceil($p_Total_Registros / $p_Cant_Listar)) {//Controla que no sean valores que superen los que tenemos ej:9999
			//ceil — Redondear fracciones hacia arriba
			$p_Pagina_Actual = ceil($p_Total_Registros / $p_Cant_Listar);
		}
		/* Fin paginado */

		if ($p_condicion != '') {
			$p_condicion = 'WHERE ' . $p_condicion;
		}
		//echo "SELECT * FROM logs_equipo {$p_condicion} ORDER BY " . $p_orderBy . " " . $p_order . " LIMIT " . ($p_Pagina_Actual - 1) * $p_Cant_Listar . " , {$p_Cant_Listar}";
		$rows = $cnn->Select_Lista("SELECT * FROM logs_equipo {$p_condicion} ORDER BY " . $p_orderBy . " " . $p_order . " LIMIT " . ($p_Pagina_Actual - 1) * $p_Cant_Listar . " , {$p_Cant_Listar}");
		$object = null;
		$list = array();

		if ($rows) {
			foreach ($rows as $row) {
				$object = new Logs_Equipo_O();
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
	 * Agregue esta para mantener compatibilidad, porque ya tengo codigo que utiliza estos parametros. (order)
	 */
	public static function obtenerTodosOSP($p_condicion = '', $p_order='DESC', $p_orderBy='leq_id') {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConn;



		if ($p_condicion != '') {
			$p_condicion = 'WHERE ' . $p_condicion;
		}
		//echo "SELECT * FROM logs_equipo {$p_condicion} ORDER BY " . $p_orderBy . " " . $p_order . " LIMIT " . ($p_Pagina_Actual - 1) * $p_Cant_Listar . " , {$p_Cant_Listar}";
		$rows = $cnn->Select_Lista("SELECT * FROM logs_equipo {$p_condicion} ORDER BY " . $p_orderBy . " " . $p_order);
		$object = null;
		$list = array();

		if ($rows) {
			foreach ($rows as $row) {
				$object = new Logs_Equipo_O();
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
	 * Esta es para el reporte por grupo
	 */
	public static function obtenerTodosxRol($p_Pagina_Actual, $p_Cant_Listar, $p_Total_Registros, $p_condicion = '', $p_order='DESC', $p_orderBy='lga_id', $p_rol) {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConn;

		//Para el paginado		
		if ($p_Pagina_Actual <= 1) {//Controla que no sea menor de 0 ya que no se puede paginar pode valores negativos ej:-1
			$p_Pagina_Actual = 1;
		} elseif ($p_Pagina_Actual >= ceil($p_Total_Registros / $p_Cant_Listar)) {//Controla que no sean valores que superen los que tenemos ej:9999
			//ceil — Redondear fracciones hacia arriba
			$p_Pagina_Actual = ceil($p_Total_Registros / $p_Cant_Listar);
		}
		/* Fin paginado */

		if ($p_condicion != '') {
			$p_condicion = 'WHERE ' . $p_condicion;
		}
		//echo "SELECT * FROM logs_equipo {$p_condicion} ORDER BY " . $p_orderBy . " " . $p_order . " LIMIT " . ($p_Pagina_Actual - 1) * $p_Cant_Listar . " , {$p_Cant_Listar}";
		$rows = $cnn->Select_Lista("SELECT * FROM logs_equipo {$p_condicion} ORDER BY " . $p_orderBy . " " . $p_order . " LIMIT " . ($p_Pagina_Actual - 1) * $p_Cant_Listar . " , {$p_Cant_Listar}");
		$object = null;
		$list = array();
		if ($rows) {
			foreach ($rows as $row) {
				$object = new Logs_Equipo_O();
				$object->loadArray($row);
				//si no pertenece al grupo, no lo agrego a la lista
				if (Grupos_Personas_L::checkIfPersonaGrupo($object->getPerId(),$p_rol)) {
					continue;
				} else {
					$list[] = $object;
				}
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
	 * Se que está mal hacer esto, pero es lo que se ocurrio para contar los registros x grupo.
	 */
	public static function getCountxRol($p_condicion, $p_rol) {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConn;
		if ($p_condicion != '') {
			$p_condicion = 'WHERE ' . $p_condicion;
		}
		//echo "SELECT * FROM logs_equipo {$p_condicion}";
		$rows = $cnn->Select_Lista("SELECT * FROM logs_equipo {$p_condicion}");
		$object = null;
		$list = array();
		$contador = 0;
		if ($rows) {
			foreach ($rows as $row) {
				$object = new Logs_Equipo_O();
				$object->loadArray($row);
				//si no pertenece al grupo, no lo agrego a la lista
				if (Grupos_Personas_L::checkIfPersonaGrupo($object->getPerId(),$p_rol)) {
					//continue;
				} else {
					$contador += 1;
				}
			}
		} else {
			$list = $object;
		}

		if ($rows === false) { // devuelve el error si algo fallo con MySql
			echo $cnn->get_Error(Registry::getInstance()->general['debug']);
		}


		return $contador;
	}
	/**
	 * Se que está mal hacer esto, pero es lo que se ocurrio para contar los registros x grupo.
	 * esta es la misma que la de arriba pero refactorizada a grupo
	 */
	public static function getCountxGrupo($p_condicion, $p_grupo) {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConn;
		if ($p_condicion != '') {
			$p_condicion = 'WHERE ' . $p_condicion;
		}
		//echo "SELECT * FROM logs_equipo {$p_condicion}";
		$rows = $cnn->Select_Lista("SELECT * FROM logs_equipo {$p_condicion}");
		$object = null;
		$list = array();
		$contador = 0;
		if ($rows) {
			foreach ($rows as $row) {
				$object = new Logs_Equipo_O();
				$object->loadArray($row);
				//si no pertenece al grupo, no lo agrego a la lista
				if (Grupos_Personas_L::checkIfPersonaGrupo($object->getPerId(),$p_grupo)) {
					//continue;
				} else {
					$contador += 1;
				}
			}
		} else {
			$list = $object;
		}

		if ($rows === false) { // devuelve el error si algo fallo con MySql
			echo $cnn->get_Error(Registry::getInstance()->general['debug']);
		}


		return $contador;
	}





    public static function obtenerDesdeFiltroArray($p_Persona,$p_Grupo,$p_Fecha_Desde,$p_Fecha_Hasta,$p_Equipo){

        // VARIABLES
        $p_Condicion    = '';
        $list = array();

        // PERSONAS
        switch ($p_Persona){
            case 'TodasLasPersonas':
                break;
            case 'SelectRol':
                $a_Ids_personas_a_controlar         = Grupos_Personas_L::obtenerARRAYPorGrupo($p_Grupo);
                $p_Condicion .= ' AND leq_Per_Id IN (' . implode(',', array_map('intval', $a_Ids_personas_a_controlar)) . ') ';
                break;
            default:
                $p_Condicion .= ' AND leq_Per_Id = ' . $p_Persona;
                break;
        }

        // FECHAS
        $p_Condicion .= " AND leq_Fecha_Hora >= '{$p_Fecha_Desde}' AND leq_Fecha_Hora <= '{$p_Fecha_Hasta}' " . ' AND leq_Accion = 1 ';

        // EQUIPO
        if($p_Equipo != ""){
            $p_Equipo = $p_Equipo == -99 ? 0 :$p_Equipo;
            $p_Condicion .= " AND leq_Eq_Id = " . $p_Equipo;
        }

        // ORDEN
        $p_Condicion .= ' ORDER BY leq_Per_Id, leq_Fecha_Hora ASC';

        // TRIM
        $p_Condicion  = ltrim($p_Condicion, ' AND ');

        $p_Condicion    = 'WHERE '. $p_Condicion;

        //printear('$p_Condicion-LOGS');
        //printear($p_Condicion);

        // CONEXIÓN
        $cnn            = Registry::getInstance()->DbConn;


        // CONSULTA SQL
        $rows = $cnn->Select_Lista("SELECT * FROM logs_equipo {$p_Condicion}");

        // RESULTADO
        if ($rows) {
            $list = $rows;
        }
        else {
            //echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        // RETURN
        return $list;


    }

}
