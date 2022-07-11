<?php

/**
 * persona (List)
 *
 */
class Persona_L {

    /**
     * Obtiene un persona por ID.
     *
     * @param integer $p_Id
     * @param boolean $p_IncluirEliminado TRUE por defecto
     * @return Persona_O
     */
    public static function obtenerPorId($p_Id, $p_IncluirEliminado = true) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_Id = (integer) $p_Id;

        $condiciones = array();

        if (!$p_IncluirEliminado) {
            $condiciones[] = "per_Eliminada=0";
        }

        $addWhere = '';
        if (count($condiciones) > 0) {
            $addWhere = ' AND ' . implode(' AND ', $condiciones);
        }

        $row = $cnn->Select_Fila("SELECT * FROM personas WHERE per_Id = ? {$addWhere} ORDER BY per_Id", array($p_Id));
        $object = null;

        if (!empty($row)) {
            $object = new Persona_O();
            $object->loadArray($row);
        }

        if ($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }

    public static function obtenerPorIdArray($p_Id, $p_IncluirEliminado = true) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_Id = (integer) $p_Id;

        $condiciones = array();

        if (!$p_IncluirEliminado) {
            $condiciones[] = "per_Eliminada=0";
        }

        $addWhere = '';
        if (count($condiciones) > 0) {
            $addWhere = ' AND ' . implode(' AND ', $condiciones);
        }

        $row = $cnn->Select_Fila("SELECT * FROM personas WHERE per_Id = ? {$addWhere}", array($p_Id));
        $object = null;

        if ($row) {
            $object = $row;
        }

        if ($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }

    public static function obtenerTodosArrayWhere($p_condicion) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($_SESSION['filtro']['activos'])
            $rows = $cnn->Select_Lista("SELECT * FROM personas WHERE {$p_condicion} and per_Eliminada=0 ORDER BY per_Legajo ASC ");
        else
            $rows = $cnn->Select_Lista("SELECT * FROM personas WHERE {$p_condicion} and per_Eliminada=0 and per_Excluir=0 ORDER BY per_Legajo ASC ");


        $list = null;

        if ($rows) {
            $list = array();

            foreach ($rows as $row) {
                $list[$row['per_Id']] = $row;
            }
        }

        if ($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }
        return $list;
    }

    public static function obtenerTodosWhere($p_condicion) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT * FROM personas WHERE {$p_condicion} ORDER BY per_Id ASC ");

        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Persona_O();
                $object->loadArray($row);
                $list[$object->getId()] = $object;
            }
        }

        if ($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }
        return $list;
    }


    public static function obtenerTodosArray() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if (isset($_SESSION['filtro']['activos']) && $_SESSION['filtro']['activos'])
            $rows = $cnn->Select_Lista("SELECT * FROM personas WHERE per_Eliminada=0 ORDER BY per_Legajo ASC ");
        else
            $rows = $cnn->Select_Lista("SELECT * FROM personas WHERE per_Eliminada=0 and per_Excluir=0 ORDER BY per_Legajo ASC ");

        $list = null;

        if ($rows) {
            $list = array();

            foreach ($rows as $row) {
                $list[$row['per_Id']] = $row;
            }
        }

        if ($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }
        return $list;
    }



    public static function obtenerPorNombrePersona($p_Persona, $p_Id = 0) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($p_Id != 0) {
            $p_Id = ' AND per_Id <> ' . $p_Id;
        } else {
            $p_Id = '';
        }
        // abduls
        $row = $cnn->Select_Fila("SELECT * FROM personas WHERE per_Eliminada = 0 and per_Persona = ?{$p_Id} ORDER BY per_Id", array($p_Persona));
        $object = null;

        if (!empty($row)) {
            $object = new Persona_O();
            $object->loadArray($row);
        }

        if ($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }

    public static function obtenerPorLegajo($p_Legajo, $p_Id = 0, $incluirEliminado = true) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($p_Id != 0) {
            $p_Id = ' AND per_Id <> ' . $p_Id;
        }
        else {
            $p_Id = '';
        }

        if (!$incluirEliminado) {
            $p_Id .= ' AND per_Eliminada=0 ';
        }

        $row = $cnn->Select_Fila("SELECT * FROM personas WHERE per_Legajo = ?{$p_Id} ORDER BY per_Id", array($p_Legajo));
        $object = null;

        if (!empty($row)) {
            $object = new Persona_O();
            $object->loadArray($row);
        }

        if ($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }

    public static function selectLastPerson(){

        $cnn = Registry::getInstance()->DbConn;
        pred($cnn);
        $row = $cnn->Select_Fila("SELECT * FROM personas ORDER BY per_Id DESC");

    }

    public static function obtenerPorDni($p_Dni, $p_Id = 0, $incluirEliminado = true) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($p_Id != 0) {
            $p_Id = ' AND per_Id <> ' . $p_Id;
        } else {
            $p_Id = '';
        }
        // abduls
        if (!$incluirEliminado) {
            $p_Id .= ' AND per_Eliminada=0 ';
        }

        $row = $cnn->Select_Fila("SELECT * FROM personas WHERE per_Dni = ?{$p_Id} ORDER BY per_Id", array($p_Dni));
        $object = null;

        if (!empty($row)) {
            $object = new Persona_O();
            $object->loadArray($row);
        }

        if ($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }

    public static function obtenerPorEmail($p_Email)
    {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;


        $row = $cnn->Select_Fila("SELECT * FROM personas WHERE per_E_Mail = '{$p_Email}' ORDER BY per_Id");
        $object = null;

        if (!empty($row)) {
            $object = new Persona_O();
            $object->loadArray($row);
        }

        if($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }


    public static function obtenerPorTag($p_Tag, $p_Id = 0, $incluirEliminado = true) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($p_Id != 0) {
            $p_Id = ' AND per_Id <> ' . $p_Id;
        } else {
            $p_Id = '';
        }

        if (!$incluirEliminado) {
            $p_Id .= ' AND per_Eliminada=0 ';
        }

        $row = $cnn->Select_Fila("SELECT * FROM personas WHERE per_Tag = ?{$p_Id} ORDER BY per_Id", array($p_Tag));
        $object = null;

        if (!empty($row)) {
            $object = new Persona_O();
            $object->loadArray($row);
        }

        if ($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }

    /*
     * Permite obtener un listado de objetos Persona_O.
     *
     */
    public static function obtenerTodos($p_Pagina_Actual = 0, $p_Cant_Listar = 0, $p_Total_Registros = 0, $p_condicion = '', $imagen = false, $tabla = '') {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;


        if ($p_Cant_Listar != 0) {
            //Para el paginado
            if ($p_Total_Registros <= 0) {//sino hay registros que no tiere error
                $p_Pagina_Actual = 1;
            } else {
                if ($p_Pagina_Actual <= 0) {//Controla que no sea menor de 0 ya que no se puede paginar pode valores negativos ej:-1
                    $p_Pagina_Actual = 1;
                } elseif ($p_Pagina_Actual >= ceil($p_Total_Registros / $p_Cant_Listar)) {//Controla que no sean valores que superen los que tenemos ej:9999
                    //ceil — Redondear fracciones hacia arriba
                    $p_Pagina_Actual = ceil($p_Total_Registros / $p_Cant_Listar);
                }
            }
            /* Fin paginado */
            $limite = "LIMIT " . ($p_Pagina_Actual - 1) * $p_Cant_Listar . " , {$p_Cant_Listar}";
        } else {
            $limite = '';
        }


        if ($p_condicion != '') {
            $p_condicion = 'WHERE ' . $p_condicion;
        }

        if (Config_L::p('ordenar_personas_alfabeticamente')) $orden = ' per_Apellido ASC ';
        else $orden = ' per_Legajo ASC ';

        $rows = $cnn->Select_Lista("SELECT * FROM personas{$tabla} {$p_condicion} ORDER BY {$orden} {$limite}");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Persona_O();
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



    /*
     * Permite obtener un array con personas por id como indice.
     *
     */
    public static function obtenerTodosenArray() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;


        if (Config_L::p('ordenar_personas_alfabeticamente')) $orden = ' per_Apellido ASC ';
        else $orden = ' per_Id DESC ';

        //SALVEDAD POR SI NO SE DECLARO EN ALGUN MODULO QUE USE ESTA FUNCION
        if ($_SESSION['filtro']['activos'])
            $rows = $cnn->Select_Lista("SELECT * FROM personas WHERE per_Eliminada=0 ORDER BY {$orden} ");
        else
            $rows = $cnn->Select_Lista("SELECT * FROM personas WHERE per_Eliminada=0 and per_Excluir=0 ORDER BY {$orden} ");


        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Persona_O();

                $object->loadArray($row);
                $list[$object->getId()] = $object;
            }
        } else {
            $list[] = $object;
        }

        if ($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }
        return $list;
    }


    /**
     * @return array|null
     */
    public static function obtenerPorUsuarioActual() {

        $_usuarioActual_Id  = Registry::getInstance()->Usuario->getId();

        $cnn = Registry::getInstance()->DbConn;

        $row = $cnn->Select_Fila("SELECT * FROM personas WHERE per_User_Id = {$_usuarioActual_Id}");

        $array = null;

        if (!empty($row)) {
            $array = $row;
        }

        if ($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $row;
    }


    public static function obtenerIdsDesdeFiltro($p_Filtro) {


        $list           = array();
        $p_Condicion    = Persona_L::generar_string_condiciones ($p_Filtro);

        $cnn            = Registry::getInstance()->DbConn;
        $rows           = $cnn->Select_Lista("SELECT * FROM personas {$p_Condicion} ORDER BY per_Id ASC");


        if ($rows) {
            foreach ($rows as $row) {
                $list[$row["per_Id"]] = $row["per_Id"];
            }
        }

        return $list;
    }


    /*
         * Permite obtener un array con los id de todas las personas Activas y NO Eliminadas
         *
         */
    public static function obtenerTodosIdenArray() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;


        if (Config_L::p('ordenar_personas_alfabeticamente')) $orden = ' per_Apellido ASC ';
        else $orden = ' per_Id DESC ';

        //SALVEDAD POR SI NO SE DECLARO EN ALFUN MODULO QUE USE ESTA FUNCION
        $rows = $cnn->Select_Lista("SELECT per_Id FROM personas WHERE per_Eliminada=0 and per_Excluir=0 ORDER BY {$orden} ");

        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $list[] = $row["per_Id"];
            }
        }
        else {
            $list = null;
        }

        if ($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }
        return $list;
    }


    /*
         * Permite obtener un array con los id de todas las personas Activas, sin usuario Activo
         *
         */
    public static function obtenerTodosIdenArraySinUsuario() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;


        if (Config_L::p('ordenar_personas_alfabeticamente')) $orden = ' per_Apellido ASC ';
        else $orden = ' per_Id DESC ';

        //SALVEDAD POR SI NO SE DECLARO EN ALFUN MODULO QUE USE ESTA FUNCION
        $rows = $cnn->Select_Lista("SELECT per_Id FROM personas WHERE per_User_Id=0 ORDER BY {$orden} ");

        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $list[] = $row["per_Id"];
            }
        }
        else {
            $list = null;
        }

        if ($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }
        return $list;
    }

    public static function obtenerPorPermiso($p_Id_Permiso) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT * FROM personas WHERE per_Prm_Id = ? and per_Eliminada = 0 ORDER BY per_Id", array($p_Id_Permiso));
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Persona_O();
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

    /*
     * @param $p_Id_Horario_Trabajo
     * @param $p_Horario_Trabajo_Tipo
     * @param bool $imagen
     * @return array|null

     */
    public static function obtenerPorHorariodeTrabajo($p_Id_Horario_Trabajo, $p_Horario_Trabajo_Tipo, $imagen = false) {/* @var $cnn mySQL */
        $p_Id_Horario_Trabajo = (integer) $p_Id_Horario_Trabajo;
        $p_Horario_Trabajo_Tipo = (integer) $p_Horario_Trabajo_Tipo;
        return Persona_L::obtenerTodos(0,0,0,'per_Eliminada = 0  and per_Hor_Id='.$p_Id_Horario_Trabajo.' AND per_Hor_Tipo='.$p_Horario_Trabajo_Tipo,$imagen);
    }


    // THIS DOES NOT WORK
    public static function obtenerTodosPorEquipo($p_Id_Equipo, $imagen = false)
    {
        /* @var $cnn mySQL */
        $p_Id_Equipo = (integer)$p_Id_Equipo;
        return Persona_L::obtenerTodos(0, 0, 0, 'per_Eliminada = 0 and per_eqID=' . $p_Id_Equipo . '', $imagen);
    }

    /*
     * @param $p_Id_Horario_Trabajo
     * @param $p_Horario_Trabajo_Tipo
     * @return null
     */
    public static function obtenerPorHorariodeTrabajoCOUNT($p_Id_Horario_Trabajo, $p_Horario_Trabajo_Tipo) {
        $p_Id_Horario_Trabajo = (integer) $p_Id_Horario_Trabajo;
        $p_Horario_Trabajo_Tipo = (integer) $p_Horario_Trabajo_Tipo;

        $cnn = Registry::getInstance()->DbConn;

        $row = $cnn->Select_Fila("SELECT COUNT(per_Id) FROM personas WHERE per_Eliminada = 0 and per_Hor_Id = ".$p_Id_Horario_Trabajo." AND per_Hor_Tipo = ".$p_Horario_Trabajo_Tipo." ORDER BY per_Id", array());
        $object = null;

        if (!empty($row)) {
            $object=$row['COUNT(per_Id)'];
        }

        if ($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }


    public static function obtenerPorGrupo($p_Id_Grupo) {
        /* @var $cnn mySQL */
        $a_personas = array();
        $a_personas = Grupos_Personas_L::obtenerPersonasPorGrupo($p_Id_Grupo);

        return $a_personas;

    }

    public static function obtenerCantidadPorGrupo($p_Id_Grupo) {


        return Grupos_Personas_L::obtenerCantidadPersonasPorGrupo($p_Id_Grupo);;

    }
    /*
     *
     * Devuelve un entero con la cantidad de personas de una empresa
     *
     * @param integer $p_Id_Empresa
     * @return integer cantidad
     */
    public static function obtenerCantidadPorEmpresa($p_Id_Empresa) {

        //TODO hacer esto

        //return Grupos_Personas_L::obtenerCantidadPersonasPorGrupo($p_Id_Grupo);;

    }
    /*
     *
     * Devuelve un array con las objetos de las personas que tiene un tipo de permiso dado
     *
     * @param integer $p_Id_Grupo
     * @return array Persona_O
     */
    public static function obtenerPorIdyRol($p_Id,$p_Id_Grupo,$imagen=true) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $row = $cnn->Select_Fila("SELECT * FROM personas WHERE per_Eliminada = 0  and per_Grupo_Id = ".$p_Id_Grupo." AND per_Id = ".$p_Id." ORDER BY per_Id");
        $object = null;

        if (!empty($row)) {
            $object = new Persona_O();
            $object->loadArray($row);
        }

        if ($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }
    /*
     *
     * Devuelve una lista con las personas que tienen una fecha de modificacion mayor a la especificada
     *
     * @param integer $p_FechaMod  (timestamp)
     * @param string $p_Limit
     * @return array Persona_O
     */
    public static function obtenerPorFechaMod($p_FechaMod, $p_Limit='') {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $limit='';
        if($p_Limit!=''){
            $p_Limit = (string)$p_Limit;
            $limit = " LIMIT ". $p_Limit." ";
        }


        $rows = $cnn->Select_Lista("SELECT * FROM personas WHERE per_Eliminada = 0 and per_Fecha_Mod > FROM_UNIXTIME('".$p_FechaMod."') ORDER BY per_Fecha_Mod ASC ".$limit);
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Persona_O();
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
    /*
     *
     * Devuelve un integer con la cantidad de personas que tienen una fecha de modificacion mayor a la especificada
     *
     * @param integer $p_FechaMod  (timestamp)
     * @param string $p_Limit
     * @return array Persona_O
     */
    public static function obtenerCOUNTPorFechaMod($p_FechaMod, $p_Limit='') {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $limit='';
        if($p_Limit!=''){
            $p_Limit = (string)$p_Limit;
            $limit = " LIMIT ". $p_Limit." ";
        }


        $row = $cnn->Select_Fila("SELECT COUNT(per_Id) as Cantidad FROM personas WHERE per_Eliminada = 0 and per_Fecha_Mod > FROM_UNIXTIME('".$p_FechaMod."') ORDER BY per_Fecha_Mod ASC ".$limit);

        if ($row === false) {// devuelve el error si algo fallo con MySql
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return 	$row['Cantidad'];
    }

    public static function obtenerCantidad() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance() -> DbConn;

        $row = $cnn -> Select_Fila("SELECT COUNT(per_Id) as Cantidad FROM personas WHERE per_Eliminada=0");

        if ($row === false) {// devuelve el error si algo fallo con MySql
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return 	$row['Cantidad'];
    }

    public static function obtenerCantidadBloqueadas() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance() -> DbConn;

        $row = $cnn -> Select_Fila("SELECT COUNT(per_Id) as Cantidad FROM personas WHERE per_Enable=0 and per_Eliminada = 0 ");

        if ($row === false) {// devuelve el error si algo fallo con MySql
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return 	$row['Cantidad'];
    }

    public static function obtenerUltimoRegistro() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        return $cnn->Devolver_Ultimo_Id('personas', 'per_Id');
    }

    public static function obtenerListaEmails() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_condicion = 'WHERE per_Eliminada=0 ';

        $rows = $cnn->Select_Lista("SELECT * FROM personas {$p_condicion} ");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $list[] = $row['per_E_Mail'];
            }
        } else {
            $list = $object;
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }



    public static function generar_string_condiciones ($p_Filtro){

            /* FILTRO VACÍO */
            if (empty($p_Filtro)) return "";

            /* VARIABLES */
            $a_Condiciones  = array();
            $p_Condicion    = "";

            /* CONDICIONES FILTRO */
            foreach ($p_Filtro as $key_filtro => $value_filtro){

               // if ($key_filtro == 'Activo') $value_filtro = $value_filtro == 0 ? 1 : 0;
                if ($key_filtro == 'Equipo') $value_filtro = $value_filtro == -99 ? 0 : $value_filtro;

                /* FILTRO VACÍO */
                if ($value_filtro == "") continue;

                /* STRING CONDICION */
                $p_string           =   "";

                /* FILTRO */
                switch ($key_filtro) {

                    case 'Persona':

                        switch($value_filtro){
                            case "TodasLasPersonas":
                                $p_string   = "per_Id > 0";
                                break;
                            case 'SelectRol':
                                break;
                            default:
                                if(!is_array($value_filtro)){
                                    $p_string   = "per_Id = ".$value_filtro;
                                }


                                break;
                        }
                        break;

                    case 'Grupo':
                        $a_Ids_personas_a_controlar         = Grupos_Personas_L::obtenerARRAYPorGrupo($value_filtro);
                        $p_string = "per_Id IN (" . implode(',', array_map('intval', $a_Ids_personas_a_controlar)) . ")";

                        break;

                    case 'Estado':

                        switch($value_filtro){
                            case "Activo":
                                $p_string   = 'per_Excluir = 0';
                                break;
                            case 'Inactivo':
                                $p_string   = 'per_Excluir = 1';
                                break;
                            case 'Todos':
                            default:
                                break;
                        }
                        break;


                        break;

                    case 'Edad Desde':

                        $fecha_hoy_time                 = strtotime(date("Y-m-d H:i:s"));
                        $string_years_desde             = '-'.($value_filtro+1). " years" ;
                        $fecha_nacimiento_desde_time    = strtotime($string_years_desde,$fecha_hoy_time);
                        $fecha_nacimiento_desde         = date("Y-m-d H:i:s", $fecha_nacimiento_desde_time);

                        $p_string                       = "per_Fecha_Nacimiento <=" ."'" .$fecha_nacimiento_desde."'" ;

                        break;


                    case 'Edad Hasta':

                        $fecha_hoy_time                 = strtotime(date("Y-m-d H:i:s"));
                        $string_years_hasta             = '-'.($value_filtro+1). " years" ;
                        $fecha_nacimiento_hasta_time    = strtotime($string_years_hasta,$fecha_hoy_time);
                        $fecha_nacimiento_hasta         = date("Y-m-d H:i:s", $fecha_nacimiento_hasta_time);

                        $p_string                       = "per_Fecha_Nacimiento >=" ."'" .$fecha_nacimiento_hasta."'" ;

                        break;

                    case 'Genero':
                        $p_string = "per_Genero=" ."'" .$value_filtro."'" ;
                        break;

                    case 'Legajo':
                        $p_string = "per_Legajo=" ."'" .$value_filtro."'" ;
                        break;

                    case 'DNI':
                        $p_string = "per_Dni=" ."'" .$value_filtro."'" ;
                        break;

                    case 'Estado Civil':
                        $p_string = "per_Estado_Civil=" ."'" .$value_filtro."'" ;
                        break;

                    case 'Tipo de Horario':
                        $p_string = "per_Hor_Tipo=" . $value_filtro;

                        break;

                    case 'Horario Normal':
                    case 'Horario Flexible':
                    case 'Horario Multiple':
                    case 'Horario Rotativo':
                        $p_string = "per_Hor_Id=" . $value_filtro;
                        break;

                    case 'Equipo':
                        $p_string = "(per_equipos REGEXP '^{$value_filtro}$' OR per_equipos REGEXP '^{$value_filtro}:' OR  per_equipos REGEXP ':{$value_filtro}$' OR  per_equipos REGEXP ':{$value_filtro}:')";
                        break;

                    case 'Inicio de Actividad':
                        $p_string = "per_fechaD=" . $value_filtro;
                        break;

                    case 'Fin de Actividad':
                        $p_string = "per_fechaH=" . $value_filtro;
                        break;

                    case 'Nro. Contribuyente':
                        $p_string = "per_Nro_Contribuyente=" ."'" .$value_filtro."'" ;
                        break;

                    case 'Talle Camisa':
                        $p_string = "per_Talle_Camisa=" ."'" .$value_filtro."'" ;
                        break;

                    case 'Email':
                        $p_string = "per_E_Mail=" ."'" .$value_filtro."'" ;
                        break;


                }

                if ($p_string == "") continue;

                $a_Condiciones [] = $p_string;


            }

            $a_Condiciones []   = 'per_Eliminada = 0';



            /* STRING CONSULTA */
            foreach ($a_Condiciones as $key_condicion => $value_condicion){
                $p_Condicion .= " AND " . $value_condicion;
            }

            /* ELIMINAR PRIMER 'AND' */
            $p_Condicion = substr($p_Condicion, 5);

            /* AGREGAR 'WHERE' */
            $p_Condicion = "WHERE " . $p_Condicion;

            /* RETURN */
            return $p_Condicion;

        }


    public static function obtenerARRAYPorEquipoId($equipoID) {

        /* @var $cnn mySQL */
        $cnn            = Registry::getInstance()->DbConn;
        $equipoID       = (integer)$equipoID;
        $p_condicion    = "(per_equipos REGEXP '^{$equipoID}$' OR per_equipos REGEXP '^{$equipoID}:' OR  per_equipos REGEXP ':{$equipoID}$' OR  per_equipos REGEXP ':{$equipoID}:')";
        $rows           = $cnn->Select_Lista_IDs('personas', $p_condicion, 'per_Id');

        $a_salida = array();

        if ($rows) {
            foreach ($rows as $perID){
                $a_salida[$perID] = $perID;
            }
        }
        if ($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $a_salida;
    }


    public static function obtenerDesdeFiltro($p_Filtro = array(),$p_Orden_Lista = "per_Id ASC"){

            $list           = array();
            $p_Condicion    = Persona_L::generar_string_condiciones ($p_Filtro);

            $cnn            = Registry::getInstance()->DbConn;
            $rows           = $cnn->Select_Lista("SELECT * FROM personas {$p_Condicion} ORDER BY {$p_Orden_Lista}");

            if ($rows) {
                foreach ($rows as $row) {
                    $object = new Persona_O();
                    $object->loadArray($row);
                    $list[$object->getId()] = $object;
                }
            }

            return $list;
        }
    public static function obtenerDesdeFiltroArray($p_Filtro = array()){

        //printear('$p_Filtro');
        //printear($p_Filtro);

        $list           = array();
        $p_Condicion    = Persona_L::generar_string_condiciones ($p_Filtro);

        //printear('$p_Condicion');
        //printear($p_Condicion);

        $cnn            = Registry::getInstance()->DbConn;
        $rows           = $cnn->Select_Lista("SELECT * FROM personas {$p_Condicion} ORDER BY per_Id ASC");

        if ($rows) {
            foreach ($rows as $row) {
                $list[$row['per_Id']] = $row;
            }
        }

        return $list;
    }
    public static function obtenerTodosFiltro($p_Person = 'TodasLasPersonas', $p_Grupo = 0, $p_Activos = '',$T_Intervalo){

        $fecha_desde = "";
        $fecha_hasta = "";

        if($p_Person == 0)
            $p_Person = "TodasLasPersonas";

        if($p_Grupo != 0)
            $p_Person  = 'SelectRol';



        switch ($T_Intervalo) {
            case 'F_Hoy':
                $fecha_desde = date('Y-m-d H:i:s', strtotime('today 00:00:00'));
                $fecha_desde = date('Y-m-d H:i:s', strtotime('today 23:59:59'));
                break;

            case 'F_Ayer':
                $fecha_desde = date('Y-m-d H:i:s', strtotime('yesterday 00:00:00'));
                $fecha_hasta = date('Y-m-d H:i:s', strtotime('yesterday 23:59:59'));
                break;

            case 'F_Semana':
                $fecha_desde = date('Y-m-d H:i:s', strtotime('monday this week 00:00:00'));
                $fecha_hasta = date('Y-m-d H:i:s', strtotime('sunday this week 23:59:59'));
                break;

            case 'F_Semana_Pasada':
                $fecha_desde = date('Y-m-d H:i:s', strtotime('monday last week 00:00:00'));
                $fecha_hasta = date('Y-m-d H:i:s', strtotime('sunday last week 23:59:59'));
                break;

            case 'F_Quincena':
                $primerDiaPrimerQuincena = strtotime('first day of this month 00:00:00');
                $ultimoDiaPrimerQuincena = strtotime('+14 days 23:59:59',$primerDiaPrimerQuincena);

                $primerDiaSegundaQuincena = strtotime('+15 days', $primerDiaPrimerQuincena);
                $ultimoDiaSegundaQuincena = strtotime('last day of this month 23:59:59');

                // PRIMERA QUINCENA
                if (time() < $ultimoDiaPrimerQuincena) {
                    $fecha_desde = date('Y-m-d H:i:s', $primerDiaPrimerQuincena);
                    $fecha_hasta = date('Y-m-d H:i:s', $ultimoDiaPrimerQuincena);
                }
                // SEGUNDA QUINCENA
                else {
                    $fecha_desde= date('Y-m-d H:i:s', $primerDiaSegundaQuincena);
                    $fecha_hasta = date('Y-m-d H:i:s', $ultimoDiaSegundaQuincena);
                }
                break;

            case 'F_Quincena_Pasada':

                // PRIMER QUINCENA DE ESTE MES
                if(date('d' > 15) ){
                    $primerDiaQuincena = strtotime('first day of this month 00:00:00');
                    $ultimoDiaQuincena = strtotime('+14 days 23:59:59',$primerDiaQuincena);
                }
                // SEGUNDA QUINCENA DEL MES PASADO
                else{
                    $primerDiaMes = strtotime('first day of last month 00:00:00');
                    $primerDiaQuincena = strtotime('+15 days', $primerDiaMes);
                    $ultimoDiaQuincena = strtotime('last day of last month 23:59:59');
                }

                $fecha_desde = date('Y-m-d H:i:s', $primerDiaQuincena);
                $fecha_hasta = date('Y-m-d H:i:s', $ultimoDiaQuincena);


                break;

            case 'F_Mes'://mes
                $fecha_desde = date('Y-m-d H:i:s', strtotime('first day of this month 00:00:00'));
                $fecha_hasta = date('Y-m-d H:i:s', strtotime('last day of this month 23:59:59'));
                break;

            case 'F_Mes_Pasado'://mes
                $fecha_desde = date('Y-m-d H:i:s', strtotime('first day of last month 00:00:00'));
                $fecha_hasta = date('Y-m-d H:i:s', strtotime('last day of last month 23:59:59'));
                break;

            case 'F_Ano'://mes
                $fecha_desde = date('Y-m-d H:i:s', strtotime("first day of january " . date('Y') . " 00:00:00 "));
                $fecha_hasta = date('Y-m-d H:i:s', strtotime("last day of december " . date('Y') . " 23:59:59"));
                break;

            case 'F_Personalizado':
                $fecha_desde =  isset($_REQUEST['fechaD'])     ? $_REQUEST['fechaD'] :    $_SESSION['filtro']['fechaD'];
                $fecha_hasta =  isset($_REQUEST['fechaH'])     ? $_REQUEST['fechaH'] :    $_SESSION['filtro']['fechaH'];
                break;
        }



        $cnn = Registry::getInstance()->DbConn;

        $p_Condicion = "WHERE ";
        
        switch($p_Person){
            case "TodasLasPersonas":
                ////printear("PERSONA CLASS TODAS LAS PERSONAS");
                $p_Condicion .="per_Id > 0";
                break;
            case 'SelectRol':
                ////printear("PERSONA CLASS GRUPO");
                $a_Ids_personas_a_controlar         = Grupos_Personas_L::obtenerARRAYPorGrupo($p_Grupo);
                $p_Condicion .= "per_Id IN (" . implode(',', array_map('intval', $a_Ids_personas_a_controlar)) . ") ";
                break;
            default:
                ////printear("PERSONA CLASS PERSONA ID");
                $p_Condicion .="per_Id = ".$p_Person;
        }


        if ($fecha_desde != '' || $fecha_hasta != ''){
            ////printear("CONDICION FECHAS");
            $p_Condicion .= " AND ((per_fechaD <= '".$fecha_desde."' AND per_fechaH >= '".$fecha_hasta."')";
            $p_Condicion .= " OR (per_fechaD > '".$fecha_desde."' AND per_fechaH < '".$fecha_desde."')";
            $p_Condicion .= " OR (per_fechaD > '".$fecha_desde."' AND per_fechaD < '".$fecha_hasta."')";
            $p_Condicion .= " OR (per_fechaH > '".$fecha_desde."' AND per_fechaH < '".$fecha_hasta."'))";
        }


        if ($p_Activos == 0 || $p_Activos == ''){
            ////printear('ACTIVOS CONDITION');
            $p_Condicion   .= ' AND (per_Eliminada = 0  AND per_Excluir = 0)';
        }
        else{
            ////printear('INACTIVOS THIS TIME');
            $p_Condicion   .= ' AND (per_Eliminada = 0)';
        }


        ////printear('p_Condicion');
        ////printear($p_Condicion);

        $rows = $cnn->Select_Lista("SELECT * FROM personas {$p_Condicion} ORDER BY per_Id ASC");

        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Persona_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        }
        else{
            $list = null;
        }

        //////printear($list);

        return $list;
    }

    
}
