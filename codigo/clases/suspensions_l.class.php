<?php
//suspensions de personas
//
class Suspensions_L {

    public static function obtenerPorId($pId) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $pId = (integer) $pId;

        $row = $cnn->Select_Fila("SELECT * FROM suspensions WHERE sus_Id = ? ORDER BY sus_Id DESC", array($pId));
        $object = null;

        if (!empty($row)) {
            $object = new Suspensions_O();
            $object->loadArray($row);
        }

        if($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }
    public static function obtenerTodasActivas() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT * FROM suspensions WHERE sus_Enabled = 1 ORDER BY sus_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Suspensions_O();
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
    public static function obtenerPorPersona($p_Persona) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_Persona = (integer) $p_Persona;

        $rows = $cnn->Select_Lista("SELECT * FROM suspensions WHERE sus_Per_Id = ".$p_Persona." ORDER BY sus_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Suspensions_O();
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

        $rows = $cnn->Select_Lista("SELECT * FROM suspensions WHERE sus_Gru_Id = ".$p_Grupo." ORDER BY sus_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Suspensions_O();
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


    public static function obtenerPorIntervalo($p_Fecha_Inicio,$p_Fecha_Fin) {

        //printear($p_Fecha_Inicio);
        //printear($p_Fecha_Fin);
        /* VARIBALES FECHAS */
        $a_items            = array();
        $a_suspensiones     = array();

        /* VARIBALE SQL */
        $cnn = Registry::getInstance()->DbConn;

        /* CONSULTA SQL */
        $rows = $cnn->Select_Lista("SELECT * FROM suspensions WHERE sus_Fecha_Inicio >= '".$p_Fecha_Inicio."' AND sus_Fecha_Fin <= '".$p_Fecha_Fin."'  ORDER BY sus_Id");

        /* ERROR SQL */
        if($rows === false) {
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
            return $a_suspensiones;
        }

        //printear('rows');
        //printear($rows);

        if(is_array($rows)){
            foreach ($rows as $suspensionID => $suspension){

                $perID                  = $suspension['sus_Per_Id'];
                $a_items[$perID][]      = $suspension;
            }

            //printear('a_items');
            //printear($a_items);

            foreach ($a_items as $perID => $items_persona){
                $a_suspensiones[$perID] = self::ordenarPorDia($items_persona);
            }

        }
        return $a_suspensiones;

    }

    public static function ordenarPorDia($a_items){

        $_array_por_fecha =  array();

        foreach ($a_items as $itemID => $item){

            $Fecha_Desde                = $item['sus_Fecha_Inicio'];
            $Fecha_Hasta                = $item['sus_Fecha_Fin'];

            $dia_inicio_filtro          = date('Y-m-d', strtotime($Fecha_Desde));
            $dias_cantidad_filtro       = DateTimeHelper::diff_Fecha_En_Dias($Fecha_Desde,$Fecha_Hasta);

            /* DÍAS FILTRO */
            for ($i = 0; $i <= $dias_cantidad_filtro; $i++) {

                /* VARIABLES DIAS FILTRO */
                $dia_filtro                 = date('Y-m-d', strtotime($dia_inicio_filtro . ' +' . $i . ' day'));

                /* DIAS FILTRO */
                $_array_por_fecha[$dia_filtro][]          = $item;
            }


        }
        return $_array_por_fecha;
    }

    public static function obtenerPorDia($p_Fecha,$p_perID) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_Fecha = (string) $p_Fecha . ' 00:00:00';

        $rows = $cnn->Select_Lista("SELECT * FROM suspensions WHERE sus_Per_Id ='".$p_perID."' AND sus_Fecha_Inicio <= '".$p_Fecha."' AND sus_Fecha_Fin >= '".$p_Fecha."'  ORDER BY sus_Id");

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $rows;
    }


    public static function obtenerPorDiaCompletoyPersona($p_Fecha,$p_persona_id) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_Fecha = (string) $p_Fecha . ' 00:00:00';

        $rows = $cnn->Select_Lista("SELECT * FROM suspensions WHERE (sus_Tipo=".LICENCIA_DIA_COMPLETO." OR sus_Tipo=".LICENCIA_PERSONALIZADA.") AND sus_Fecha_Inicio <= '".$p_Fecha."' AND sus_Fecha_Fin >= '".$p_Fecha."'  ORDER BY sus_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Suspensions_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        }else{
            $list = $object;
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        if($list!=array()){
            foreach($list as $index => $licencia){
                switch ($licencia->getPersonaOGrupo()){
                    case 'grupo'://si es un grupo, me fijo si la persona pertenece al grupo
                        if(is_null(Grupos_Personas_L::obtenerPorPerIdyGrupo($p_persona_id,$licencia->getGrupoId()))) {
                            unset($list[$index]);
                        }
                        break;
                    case 'persona':
                        if($licencia->getPerId()!=$p_persona_id) {
                            unset($list[$index]);
                        }
                        break;
                }
            }
        }

        return $list;
    }
    public static function obtenerPorLlegadaTardeyPersona($p_Fecha_Inicio,$p_persona_id,$p_Fecha) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_Fecha = (string) $p_Fecha;
        $p_Fecha_Inicio = (string) $p_Fecha_Inicio;
        $p_Fecha_Inicio = date("Y-m-d H:i:s",strtotime($p_Fecha_Inicio));
        $fecha_sql= new DateTime($p_Fecha_Inicio);
        $fecha_sql = $fecha_sql->format('Y-m-d 00:00:00');

        $rows = $cnn->Select_Lista("SELECT * FROM suspensions WHERE (sus_Tipo=".LICENCIA_LLEGADA_TARDE." OR sus_Tipo=".LICENCIA_PERSONALIZADA." OR sus_Tipo=".LICENCIA_DIA_COMPLETO.") AND sus_Fecha_Inicio <= '".$fecha_sql."' AND sus_Fecha_Fin >= '".$fecha_sql."'  ORDER BY sus_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Suspensions_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        }else{
            $list = $object;
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }


        if($list!=array()){
            foreach($list as $index => $licencia){
                switch ($licencia->getPersonaOGrupo()){
                    case 'grupo'://si es un grupo, me fijo si la persona pertenece al grupo
                        if(is_null(Grupos_Personas_L::obtenerPorPerIdyGrupo($p_persona_id,$licencia->getGrupoId()))) {
                            unset($list[$index]);
                        }
                        break;
                    case 'persona':
                        if($licencia->getPerId()!=$p_persona_id) {
                            unset($list[$index]);
                        }
                        break;
                }
            }
            foreach($list as $index => $licencia){
                if($licencia->getTipo()==LICENCIA_LLEGADA_TARDE) {//solo me fijo para las llegadas tarde, si es personalizada o dia completo, y esta en el rango de tiempo, esta OK
                    if ($licencia->checkDuracionValidaLlegadaTarde($p_Fecha_Inicio, $p_Fecha) == false)
                        unset($list[$index]);
                }
            }
        }

        return $list;
    }
    public static function obtenerPorSalidaTempranoyPersona($p_Fecha_Fin,$p_persona_id,$p_Fecha) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_Fecha = (string) $p_Fecha;
        $p_Fecha_Fin = (string) $p_Fecha_Fin;

        $fecha_sql= new DateTime($p_Fecha_Fin);
        $fecha_sql = $fecha_sql->format('Y-m-d 00:00:00');

        $rows = $cnn->Select_Lista("SELECT * FROM suspensions WHERE (sus_Tipo=".LICENCIA_SALIDA_TEMPRANO." OR sus_Tipo=".LICENCIA_PERSONALIZADA." OR sus_Tipo=".LICENCIA_DIA_COMPLETO.") AND sus_Fecha_Inicio <= '".$fecha_sql."' AND sus_Fecha_Fin >= '".$fecha_sql."'  ORDER BY sus_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Suspensions_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        }else{
            $list = $object;
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }


        if($list!=array()){
            foreach($list as $index => $licencia){
                switch ($licencia->getPersonaOGrupo()){
                    case 'grupo'://si es un grupo, me fijo si la persona pertenece al grupo
                        if(is_null(Grupos_Personas_L::obtenerPorPerIdyGrupo($p_persona_id,$licencia->getGrupoId()))) {
                            unset($list[$index]);
                        }
                        break;
                    case 'persona':
                        if($licencia->getPerId()!=$p_persona_id) {
                            unset($list[$index]);
                        }
                        break;
                }
            }
            foreach($list as $index => $licencia){
                if($licencia->getTipo()==LICENCIA_SALIDA_TEMPRANO) {//solo me fijo para las salidas temprano, si es personalizada o dia completo, y esta en el rango de tiempo, esta OK
                    if ($licencia->checkDuracionValidaSalidaTemprano($p_Fecha_Fin, $p_Fecha) == false)
                        unset($list[$index]);
                }
            }
        }

        return $list;
    }
    public static function obtenerTodos($p_Condicion='') {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($p_Condicion != '') {
            $p_Condicion = 'WHERE ' . $p_Condicion;
        }

        $rows = $cnn->Select_Lista("SELECT * FROM suspensions {$p_Condicion} ORDER BY sus_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Suspensions_O();
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
    public static function obtenerTodosSinPasadas($p_Condicion='') {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($p_Condicion != '') {
            $p_Condicion = 'WHERE ' . $p_Condicion;
        }

        $rows = $cnn->Select_Lista("SELECT * FROM suspensions {$p_Condicion} ORDER BY sus_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Suspensions_O();
                $object->loadArray($row);
                if(!$object->checkPasada())
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

    public static function obtenerTodasRepetitivas() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT * FROM suspensions WHERE sus_Repetitiva <> 0 ORDER BY sus_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Suspensions_O();
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

    public static function obtenerTodosNewArray($p_Condicion='') {

        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;
        if(isset($_REQUEST['fechaD']) && isset($_REQUEST['fechaH'])){
            $p_Condicion.=" AND sus_Fecha_Inicio >= '".$_REQUEST['fechaD']."' and sus_Fecha_Fin <= '".$_SESSION['filtro']['fechaH']."'";

        }else if(isset($_SESSION['filtro']['fechaD']) && isset($_SESSION['filtro']['fechaH'])){
            $p_Condicion.=" AND sus_Fecha_Inicio >= '".$_SESSION['filtro']['fechaD']."' and sus_Fecha_Fin <= '".$_SESSION['filtro']['fechaH']."'";

        }
        $person = $_SESSION['filtro']['persona'];
        $rolf="0";
        if(isset($_SESSION['filtro']['rolF'])){
            $rolf=$_SESSION['filtro']['rolF'];
        }else if(isset($_POST['rolF'])){
            $rolf=$_POST['rolF'];
        }
        switch($person){
            case "TodasLasPersonas":
                $p_Condicion .="";
                break;
            case "SelectRol":
                $p_Condicion .=" AND sus_Per_Id=0 AND sus_Gru_Id='".$rolf."'";
                break;
            default:
                $p_Condicion .=" AND sus_Per_Id='".$person."' AND sus_Gru_Id=0";

        }
        $p_Condicion = trim($p_Condicion," AND");
        if ($p_Condicion != '') {
            $p_Condicion = 'WHERE ' . $p_Condicion;
        }

        $rows = $cnn->Select_Lista("SELECT * FROM suspensions {$p_Condicion} ORDER BY sus_Fecha_Inicio DESC");
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $perID = $row['sus_Per_Id'];
                $list[$perID][] = $row;

            }
        }else{
            $list = null;
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }






    public static function obtenerTodosEntreFechasArray($p_Fecha_Desde, $p_Fecha_Hasta, $p_Person = 'TodasLasPersonas', $p_Grupo){

        $cnn = Registry::getInstance()->DbConn;

        $p_Condicion = "WHERE ";
        $p_Condicion .= "((sus_Fecha_Inicio <= '".$p_Fecha_Desde."' AND sus_Fecha_Fin >= '".$p_Fecha_Hasta."')";
        $p_Condicion .= " OR (sus_Fecha_Inicio > '".$p_Fecha_Desde."' AND sus_Fecha_Fin < '".$p_Fecha_Desde."')";
        $p_Condicion .= " OR (sus_Fecha_Inicio > '".$p_Fecha_Desde."' AND sus_Fecha_Inicio < '".$p_Fecha_Hasta."')";
        $p_Condicion .= " OR (sus_Fecha_Fin > '".$p_Fecha_Desde."' AND sus_Fecha_Fin < '".$p_Fecha_Hasta."'))";

        switch($p_Person){
            case "TodasLasPersonas":
                break;
            case 'SelectRol':
                $a_Ids_personas_a_controlar         = Grupos_Personas_L::obtenerARRAYPorGrupo($p_Grupo);
                $p_Condicion .= " AND (sus_Per_Id IN (" . implode(',', array_map('intval', $a_Ids_personas_a_controlar)) . ")) ";
                break;
            default:
                $p_Condicion .=" AND (sus_Per_Id='".$p_Person."')";
        }

        $rows = $cnn->Select_Lista("SELECT * FROM suspensions {$p_Condicion} ORDER BY sus_Fecha_Inicio ASC");

        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $perID = $row['sus_Per_Id'];
                $list[$perID][] = $row;
            }
        }
        else{
            $list = null;
        }


        return $list;
    }

    public static function generar_string_condiciones ($p_Filtro_Personas, $p_Filtro_Intervalo, $p_Filtro_Feriado){

        // VARIBALES : ID PERSONAS
        $string_condiciones_personas    = '';
        $array_ids_personas             = Persona_L::obtenerIdsDesdeFiltro($p_Filtro_Personas);
        // CONDICION: IDs PERSONAS
        if (!empty($array_ids_personas)){
            $string_condiciones_personas .= "sus_Per_Id IN (" . implode(',', array_map('intval', $array_ids_personas)) . ") ";
        }
        else {
            return null;
        }


        // VARIABLES: FECHA DESDE, FECHAS HASTA
        $string_condiciones_intervalo   = '';
        $fechas_desde_hasta             = Reporte_L::get_filtro_intervalo($p_Filtro_Intervalo);

        $p_Fecha_Desde                  = $fechas_desde_hasta["fechaD"];
        $p_Fecha_Hasta                  = $fechas_desde_hasta["fechaH"];

        // CONDICION: FECHA DESDE Y FECHA HASTA
        if (!empty($p_Fecha_Desde) && !empty($p_Fecha_Hasta)){
            $string_condiciones_intervalo       .= "((sus_Fecha_Inicio <= '".$p_Fecha_Desde."' AND sus_Fecha_Fin >= '".$p_Fecha_Hasta."')";
            $string_condiciones_intervalo       .= " OR (sus_Fecha_Inicio > '".$p_Fecha_Desde."' AND sus_Fecha_Fin < '".$p_Fecha_Desde."')";
            $string_condiciones_intervalo       .= " OR (sus_Fecha_Inicio > '".$p_Fecha_Desde."' AND sus_Fecha_Inicio < '".$p_Fecha_Hasta."')";
            $string_condiciones_intervalo       .= " OR (sus_Fecha_Fin > '".$p_Fecha_Desde."' AND sus_Fecha_Fin < '".$p_Fecha_Hasta."'))";
        }
        // CONDICION: FECHA DESDE
        else if (!empty($p_Fecha_Desde)) {
            $string_condiciones_intervalo       .= "sus_Fecha_Inicio > '".$p_Fecha_Desde."'";
        }
        // CONDICION: FECHA HASTA
        else if (!empty($p_Fecha_Hasta)) {
            $string_condiciones_intervalo      .= "sus_Fecha_Inicio < '".$p_Fecha_Hasta."'";
        }
        // CONDICION: SIN FECHA ()
        else{
            return "";
        }





        // VARIBALES : IDs FERIADOS
        $string_condiciones_feriado     = '';
        // CONDICION: IDs FERIADOS
        if (!empty($p_Filtro_Feriado)){
            $string_condiciones_feriado = "sus_Id IN (" . $p_Filtro_Feriado . ")";
        }




        // CONDICION
        $string_condiciones = $string_condiciones_personas . " AND " . $string_condiciones_intervalo  . " AND " . $string_condiciones_feriado;
        $string_condiciones = ltrim($string_condiciones, " AND ");
        $string_condiciones = rtrim($string_condiciones, " AND ");


        $string_condiciones = "WHERE " . $string_condiciones;

        return $string_condiciones;


    }



    public static function obtenerDesdeFiltro($p_Filtro_Personas='', $p_Filtro_Intervalo='', $p_Filtro_Suspension=''){

        // VARIABLES
        $list = array();

        // CONDICION
        $p_Condicion = Suspensions_L::generar_string_condiciones($p_Filtro_Personas,$p_Filtro_Intervalo, $p_Filtro_Suspension);

        if(is_null($p_Condicion)){
            return $list;
        }
        // CONEXIÓN
        $cnn = Registry::getInstance()->DbConn;

        // CONSULTA SQL
        $rows = $cnn->Select_Lista("SELECT * FROM suspensions {$p_Condicion} ORDER BY sus_Fecha_Inicio ASC");

        // RESULTADO
        if ($rows) {
            foreach ($rows as $row) {
                $object = new Suspensions_O();
                $object->loadArray($row);
                $list[$row['sus_Id']] = $object;
            }
        }


        // RETORNO
        return $list;


    }


    public static function obtenerTodosEntreFechas($p_Fecha_Desde, $p_Fecha_Hasta, $p_Person = 'TodasLasPersonas', $p_Grupo){

        $cnn = Registry::getInstance()->DbConn;

        $p_Condicion = "WHERE ";
        $p_Condicion .= "((sus_Fecha_Inicio <= '".$p_Fecha_Desde."' AND sus_Fecha_Fin >= '".$p_Fecha_Hasta."')";
        $p_Condicion .= " OR (sus_Fecha_Inicio > '".$p_Fecha_Desde."' AND sus_Fecha_Fin < '".$p_Fecha_Desde."')";
        $p_Condicion .= " OR (sus_Fecha_Inicio > '".$p_Fecha_Desde."' AND sus_Fecha_Inicio < '".$p_Fecha_Hasta."')";
        $p_Condicion .= " OR (sus_Fecha_Fin > '".$p_Fecha_Desde."' AND sus_Fecha_Fin < '".$p_Fecha_Hasta."'))";

        switch($p_Person){
            case "TodasLasPersonas":
                break;
            case 'SelectRol':
                $a_Ids_personas_a_controlar         = Grupos_Personas_L::obtenerARRAYPorGrupo($p_Grupo);
                $p_Condicion .= " AND (sus_Per_Id IN (" . implode(',', array_map('intval', $a_Ids_personas_a_controlar)) . ")) ";
                break;
            default:
                $p_Condicion .=" AND (sus_Per_Id='".$p_Person."')";
        }


        $rows = $cnn->Select_Lista("SELECT * FROM suspensions {$p_Condicion} ORDER BY sus_Fecha_Inicio ASC");

        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Suspensions_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        }
        else{
            $list = null;
        }

        return $list;
    }


    public static function obtenerTodosNew($p_Condicion='') {

        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;
        if(isset($_REQUEST['fechaD']) && isset($_REQUEST['fechaH'])){
            $p_Condicion.=" AND sus_Fecha_Inicio >= '".$_REQUEST['fechaD']."' and sus_Fecha_Fin <= '".$_REQUEST['filtro']['fechaH']."'";

        }else if(isset($_SESSION['filtro']['fechaD']) && isset($_SESSION['filtro']['fechaH'])){
            $p_Condicion.=" AND sus_Fecha_Inicio >= '".$_SESSION['filtro']['fechaD']."' and sus_Fecha_Fin <= '".$_SESSION['filtro']['fechaH']."'";

        }
        $person = $_SESSION['filtro']['persona'];
        $rolf="0";
        if(isset($_SESSION['filtro']['rolF'])){
            $rolf=$_SESSION['filtro']['rolF'];
        }else if(isset($_POST['rolF'])){
            $rolf=$_POST['rolF'];
        }
        switch($person){
            case "TodasLasPersonas":
                $p_Condicion .="";
                break;
            case "SelectRol":
                $p_Condicion .=" AND sus_Per_Id=0 AND sus_Gru_Id='".$rolf."'";
                break;
            default:
                $p_Condicion .=" AND sus_Per_Id='".$person."' AND sus_Gru_Id=0";

        }
        $p_Condicion = trim($p_Condicion," AND");
        if ($p_Condicion != '') {
            $p_Condicion = 'WHERE ' . $p_Condicion;
        }

        $rows = $cnn->Select_Lista("SELECT * FROM suspensions {$p_Condicion} ORDER BY sus_Fecha_Inicio DESC");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Suspensions_O();
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
    public static function obtenerTodosSinPasadasNew($p_Condicion='') {
        /* @var $cnn mySQL */

        $cnn = Registry::getInstance()->DbConn;
        if(isset($_REQUEST['fechaD']) && isset($_REQUEST['fechaH'])){
            $p_Condicion.=" AND sus_Fecha_Inicio >= '".$_REQUEST['fechaD']."' and sus_Fecha_Fin <= '".$_SESSION['filtro']['fechaH']."'";

        }else if(isset($_SESSION['filtro']['fechaD']) && isset($_SESSION['filtro']['fechaH'])){
            $p_Condicion.=" AND sus_Fecha_Inicio >= '".$_SESSION['filtro']['fechaD']."' and sus_Fecha_Fin <= '".$_SESSION['filtro']['fechaH']."'";

        }
        $person = $_SESSION['filtro']['persona'];
        switch($person){
            case "TodasLasPersonas":
                $p_Condicion .="";
                break;
            case "SelectRol":
                $p_Condicion .=" AND sus_Per_Id=0 AND sus_Gru_Id='".$_SESSION['filtro']['rolF']."'";
                break;
            default:
                $p_Condicion .=" AND sus_Per_Id='".$person."' AND sus_Gru_Id=0";

        }
        $p_Condicion = trim($p_Condicion," AND");

        if ($p_Condicion != '') {
            $p_Condicion = 'WHERE ' . $p_Condicion;
        }
        //echo "SELECT * FROM suspensions {$p_Condicion} ORDER BY sus_Id"; die;
        $rows = $cnn->Select_Lista("SELECT * FROM suspensions {$p_Condicion} ORDER BY sus_Fecha_Inicio DESC");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Suspensions_O();
                $object->loadArray($row);
                if(!$object->checkPasada())
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


    public static function obtenerTodosArray(){

        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT * FROM suspensions ORDER BY sus_Id ASC");

        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $list[$row['sus_Id']] = $row;
            }
        }
        else{
            $list = null;
        }


        return $list;
    }

}
