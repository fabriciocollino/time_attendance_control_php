<?php
//licencias de personas
//
class Feriado_L {

    public static function obtenerPorId($pId) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $pId = (integer) $pId;

        $row = $cnn->Select_Fila("SELECT * FROM feriados WHERE fer_Id = ? ORDER BY fer_Id", array($pId));
        $object = null;

        if (!empty($row)) {
            $object = new Feriado_O();
            $object->loadArray($row);
        }

        if($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $object;
    }
    public static function obtenerPorId2() {
        /* @var $cnn mySQL */

        $cnn = Registry::getInstance()->DbConn;



        $rows = $cnn->Select_Lista("SELECT * FROM feriados ORDER BY fer_Id");
        $object = null;
        $list = array();
        if ($rows) {
            foreach ($rows as $row) {
                $object = new Feriado_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        }else{
            $list = $object;
        }

        if($row === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $list;
    }

    public static function obtenerTodasActivas() {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT * FROM feriados WHERE fer_Enabled = 1 ORDER BY fer_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Feriado_O();
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

        $rows = $cnn->Select_Lista("SELECT * FROM feriados WHERE fer_Per_Id = ".$p_Persona." ORDER BY fer_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Feriado_O();
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
        $a_feriados         = array();

        /* VARIBALE SQL */
        $cnn = Registry::getInstance()->DbConn;

        /* CONSULTA SQL */
        $rows = $cnn->Select_Lista("SELECT * FROM feriados WHERE fer_Fecha_Inicio >= '".$p_Fecha_Inicio."' AND fer_Fecha_Fin <= '".$p_Fecha_Fin."'  ORDER BY fer_Id");

        /* ERROR SQL */
        if($rows === false) {
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
            return $a_feriados;
        }



        if(is_array($rows)){
            foreach ($rows as $feriadoID => $feriado){

                $perID                  = $feriado['fer_Per_Id'];
                $a_items[$perID][]      = $feriado;
            }
            //printear('a_items');
            //printear($a_items);

            foreach ($a_items as $perID => $items_persona){
                $a_feriados[$perID] = self::ordenarPorDia($items_persona);
            }

        }
        return $a_feriados;


    }

    public static function ordenarPorDia($a_items){

        $_array_por_fecha =  array();
        foreach ($a_items as $itemID => $item){

            $Fecha_Desde                = $item['fer_Fecha_Inicio'];
            $Fecha_Hasta                = $item['fer_Fecha_Fin'];

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

        $rows = $cnn->Select_Lista("SELECT * FROM feriados WHERE fer_Per_Id ='".$p_perID."' AND fer_Fecha_Inicio <= '".$p_Fecha."' AND fer_Fecha_Fin >= '".$p_Fecha."'  ORDER BY fer_Id");

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $rows;
    }


    public static function obtenerPorDiayPersona($p_Fecha,$p_persona_id) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_Fecha = (string) $p_Fecha . ' 00:00:00';
        $p_persona_id = (integer) $p_persona_id;

        $rows = $cnn->Select_Lista("SELECT * FROM feriados WHERE fer_Fecha_Inicio <= '".$p_Fecha."' AND fer_Fecha_Fin >= '".$p_Fecha."'  ORDER BY fer_Id");

        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Feriado_O();
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
            foreach($list as $index => $feriado){
                switch ($feriado->getPersonaOGrupo()){
                    case 'Todas las Personas'://si es para todas las personas, devuelvo la lista que encontre
                        break;
                    case 'grupo'://si es un grupo, me fijo si la persona pertenece al grupo
                        if(is_null(Grupos_Personas_L::obtenerPorPerIdyGrupo($p_persona_id,$feriado->getGrupoId()))) {
                            unset($list[$index]);
                        }
                        break;
                    case 'persona':
                        if($feriado->getPerId()!=$p_persona_id) {
                            unset($list[$index]);
                        }
                        break;
                }
            }
        }

        return $list;
    }

    public static function obtenerPorGrupo($p_Grupo) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_Grupo = (integer) $p_Grupo;

        $rows = $cnn->Select_Lista("SELECT * FROM feriados WHERE fer_Gru_Id = ".$p_Grupo." ORDER BY fer_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Feriado_O();
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


    public static function obtenerTodos($p_Condicion='') {
        /* @var $cnn mySQL */

        $cnn = Registry::getInstance()->DbConn;

        if ($p_Condicion != '') {
            $p_Condicion = 'WHERE ' . $p_Condicion;
        }

        $rows = $cnn->Select_Lista("SELECT * FROM feriados {$p_Condicion} ORDER BY fer_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Feriado_O();
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

    //rahul
    public static function obtenerTodosSinPasadosNew($p_Condicion='') {

        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        // Date filter
        if(isset($_REQUEST['fechaD']) && isset($_REQUEST['fechaH'])){
            $p_Condicion.=" AND fer_Fecha_Inicio >= '".$_REQUEST['fechaD']."' and fer_Fecha_Fin <= '".$_SESSION['filtro']['fechaH']."'";

        }else if(isset($_SESSION['filtro']['fechaD']) && isset($_SESSION['filtro']['fechaH'])){
            $p_Condicion.=" AND fer_Fecha_Inicio >= '".$_SESSION['filtro']['fechaD']."' and fer_Fecha_Fin <= '".$_SESSION['filtro']['fechaH']."'";

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
                $p_Condicion .=" AND fer_Per_Id=0 AND fer_Gru_Id='".$rolf."'";
                break;
            default:
                $p_Condicion .=" AND fer_Per_Id='".$person."' AND fer_Gru_Id=0";

        }

        $p_Condicion = trim($p_Condicion," AND");
        if ($p_Condicion != '') {
            $p_Condicion = 'WHERE ' . $p_Condicion;
        }

        $rows = $cnn->Select_Lista("SELECT * FROM feriados {$p_Condicion} ORDER BY fer_Fecha_Inicio DESC");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Feriado_O();
                $object->loadArray($row);
                if(!$object->checkPasado())
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
    //rahul
    public static function obtenerTodosEntreDosFechasNew($p_f_desde,$p_f_hasta) {

        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;
        $p_Condicion = 'WHERE fer_Fecha_Inicio >= "'.$p_f_desde.'" AND fer_Fecha_Fin <= "'.$p_f_hasta.'"';
        //pre($_SESSION);
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
                $p_Condicion .=" AND fer_Per_Id=0 AND fer_Gru_Id='".$rolf."'";
                break;
            default:
                $p_Condicion .=" AND fer_Per_Id='".$person."' AND fer_Gru_Id=0";

        }

        $rows = $cnn->Select_Lista("SELECT * FROM feriados {$p_Condicion} ORDER BY fer_Fecha_Inicio DESC");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Feriado_O();
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
    public static function obtenerTodosSinPasados($p_Condicion='') {

        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        // Date filter
        if(isset($_REQUEST['fechaD']) && isset($_REQUEST['fechaH'])){
            $p_Condicion.="fer_Fecha_Inicio >= '".$_REQUEST['fechaD']."' and fer_Fecha_Fin <= '".$_REQUEST['fechaH']."'";

        }



        if ($p_Condicion != '') {
            $p_Condicion = 'WHERE ' . $p_Condicion;
        }

        $rows = $cnn->Select_Lista("SELECT * FROM feriados {$p_Condicion} ORDER BY fer_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Feriado_O();
                $object->loadArray($row);
                if(!$object->checkPasado())
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

    public static function obtenerTodosEntreDosFechas($p_f_desde,$p_f_hasta) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_Condicion = 'WHERE fer_Fecha_Inicio >= "'.$p_f_desde.'" AND fer_Fecha_Fin <= "'.$p_f_hasta.'"';

        $rows = $cnn->Select_Lista("SELECT * FROM feriados {$p_Condicion} ORDER BY fer_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Feriado_O();
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
    public static function fetchInterval(){
        //MANEJO DE LOS INTERVALOS DE FECHAS
        if(isset($_POST['persona'])){
            $_SESSION['filtro']['persona'] = $_POST['persona'];}
        if(isset($_POST['rolF'])){
            $_SESSION['filtro']['rolF'] = $_POST['rolF'];}
        if(isset($_POST['intervaloFecha'])){
            $_SESSION['filtro']['T_Intervalo'] = $_POST['intervaloFecha'];}
        if(isset($_POST['pasados'])){
            $_SESSION['filtro']['pasados'] = $_POST['pasados'];}
        $T_Intervalo = isset($_REQUEST['intervaloFecha']) ? (string) $_REQUEST['intervaloFecha'] : "";
        if(isset($T_Intervalo) && $T_Intervalo!=''){
            switch($T_Intervalo){
                case 'F_Hoy'://diario
                    $_SESSION['filtro']['fechaD']=date('Y-m-d H:i:s',strtotime('today 00:00'));
                    $_SESSION['filtro']['fechaH']=date('Y-m-d H:i:s',strtotime('today 23:59:59'));
                    break;
                case 'F_Ayer'://diario
                    $_SESSION['filtro']['fechaD']=date('Y-m-d H:i:s',strtotime('yesterday 00:00'));
                    $_SESSION['filtro']['fechaH']=date('Y-m-d H:i:s',strtotime('yesterday 23:59:59'));
                    break;
                case 'F_Semana'://semana
                    $_SESSION['filtro']['fechaD']=date('Y-m-d H:i:s',strtotime('this week 00:00'));
                    $_SESSION['filtro']['fechaH']=date('Y-m-d H:i:s',strtotime('next week 00:00'));
                    break;
                case 'F_Semana_Pasada'://semana
                    $_SESSION['filtro']['fechaD']=date('Y-m-d H:i:s',strtotime('last week 00:00'));
                    $_SESSION['filtro']['fechaH']=date('Y-m-d H:i:s',strtotime('this week 00:00'));
                    break;
                case 'F_Quincena'://quincena
                    $primerDiadelMes=strtotime('first day of this month 00:00');
                    $ultimoDiadelMes=strtotime('first day of next month 00:00');
                    $mitadDelMes=strtotime('+15 days',$primerDiadelMes);
                    if(time()<$mitadDelMes){//primera quincena
                        $_SESSION['filtro']['fechaD']= date('Y-m-d H:i:s',$primerDiadelMes);
                        $_SESSION['filtro']['fechaH']= date('Y-m-d H:i:s',$mitadDelMes);
                    }
                    else {
                        $_SESSION['filtro']['fechaD']= date('Y-m-d H:i:s',$mitadDelMes);
                        $_SESSION['filtro']['fechaH']= date('Y-m-d H:i:s',$ultimoDiadelMes);
                    }
                    break;
                case 'F_Mes'://mes
                    $_SESSION['filtro']['fechaD']=date('Y-m-d H:i:s',strtotime('first day of this month 00:00'));
                    $_SESSION['filtro']['fechaH']=date('Y-m-d H:i:s',strtotime('first day of next month 00:00'));
                    break;
                case 'F_Mes_Pasado'://mes
                    $_SESSION['filtro']['fechaD']=date('Y-m-d H:i:s',strtotime('first day of last month 00:00'));
                    $_SESSION['filtro']['fechaH']=date('Y-m-d H:i:s',strtotime('first day of this month 00:00'));
                    break;
                case 'F_Ano'://mes
                    $_SESSION['filtro']['fechaD']=date('Y-m-d H:i:s',strtotime("first day of january " . date('Y') . " 00:00 "));
                    $_SESSION['filtro']['fechaH']=date('Y-m-d H:i:s',strtotime("first day of january " . date('Y') . " 00:00 +1 year"));
                    break;
                case 'F_Personalizado':
                    $_SESSION['filtro']['fechaD'] = (!isset($_POST['fechaD'])) ? (isset($_SESSION['filtro']['fechaD'])) ? $_SESSION['filtro']['fechaD'] : date('Y-m-d H:i:s', strtotime('-1 day'))  : $_POST['fechaD'];
                    $_SESSION['filtro']['fechaH'] = (!isset($_POST['fechaH'])) ? (isset($_SESSION['filtro']['fechaH'])) ? $_SESSION['filtro']['fechaH'] : date('Y-m-d H:i:s')  : $_POST['fechaH'];
                    break;
            }
        }else{//selecciono el dropdown si la fecha ya viene
            if($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime('today 00:00')) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime('today 23:59:59')))
                $T_Intervalo='F_Hoy';
            elseif($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime('yesterday 00:00')) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime('yesterday 23:59:59')))
                $T_Intervalo='F_Ayer';
            elseif($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime('this week 00:00')) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime('next week 00:00')))
                $T_Intervalo='F_Semana';
            elseif($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime('last week 00:00')) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime('this week 00:00')))
                $T_Intervalo='F_Semana_Pasada';
            elseif($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime('first day of this month 00:00')) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime('+15 days',strtotime('first day of this month 00:00'))))
                $T_Intervalo='F_Quincena';
            elseif($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime('+15 days',strtotime('first day of this month 00:00'))) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime('first day of next month 00:00')))
                $T_Intervalo='F_Quincena';
            elseif($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime('first day of this month 00:00')) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime('first day of next month 00:00')))
                $T_Intervalo='F_Mes';
            elseif($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime('first day of last month 00:00')) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime('first day of this month 00:00')))
                $T_Intervalo='F_Mes_Pasado';
            elseif($_SESSION['filtro']['fechaD']==date('Y-m-d H:i:s',strtotime("first day of january " . date('Y') . " 00:00 ")) && $_SESSION['filtro']['fechaH']==date('Y-m-d H:i:s',strtotime("first day of january " . date('Y') . " 00:00 +1 year")))
                $T_Intervalo='F_Ano';
            else	$T_Intervalo='F_Personalizado';
        }
    }

    public static function obtenerTodosEntreFechasArray($p_Fecha_Desde, $p_Fecha_Hasta, $p_Person = 'TodasLasPersonas', $p_Grupo){

        $cnn = Registry::getInstance()->DbConn;

        $p_Condicion = "WHERE ";
        $p_Condicion .= "((fer_Fecha_Inicio <= '".$p_Fecha_Desde."' AND fer_Fecha_Fin >= '".$p_Fecha_Hasta."')";
        $p_Condicion .= " OR (fer_Fecha_Inicio > '".$p_Fecha_Desde."' AND fer_Fecha_Fin < '".$p_Fecha_Desde."')";
        $p_Condicion .= " OR (fer_Fecha_Inicio > '".$p_Fecha_Desde."' AND fer_Fecha_Inicio < '".$p_Fecha_Hasta."')";
        $p_Condicion .= " OR (fer_Fecha_Fin > '".$p_Fecha_Desde."' AND fer_Fecha_Fin < '".$p_Fecha_Hasta."'))";

        switch($p_Person){
            case "TodasLasPersonas":
                break;
            case 'SelectRol':
                $a_Ids_personas_a_controlar         = Grupos_Personas_L::obtenerARRAYPorGrupo($p_Grupo);
                $p_Condicion .= " AND (fer_Per_Id IN (" . implode(',', array_map('intval', $a_Ids_personas_a_controlar)) . ")) ";
                break;
            default:
                $p_Condicion .=" AND (fer_Per_Id='".$p_Person."')";
        }

        $rows = $cnn->Select_Lista("SELECT * FROM feriados {$p_Condicion} ORDER BY fer_Fecha_Inicio ASC");

        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $perID = $row['fer_Per_Id'];
                $list[$perID][] = $row;
            }
        }
        else{
            $list = null;
        }


        return $list;
    }

    public static function generar_string_condiciones ($p_Filtro_Personas='', $p_Filtro_Intervalo='', $p_Filtro_Feriado=''){

        // VARIBALES : ID PERSONAS
        $string_condiciones_personas    = '';
        $array_ids_personas             = Persona_L::obtenerIdsDesdeFiltro($p_Filtro_Personas);
        // CONDICION: IDs PERSONAS
        if (!empty($array_ids_personas)){
            $string_condiciones_personas .= "fer_Per_Id IN (" . implode(',', array_map('intval', $array_ids_personas)) . ") ";
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
            $string_condiciones_intervalo       .= "((fer_Fecha_Inicio <= '".$p_Fecha_Desde."' AND fer_Fecha_Fin >= '".$p_Fecha_Hasta."')";
            $string_condiciones_intervalo       .= " OR (fer_Fecha_Inicio > '".$p_Fecha_Desde."' AND fer_Fecha_Fin < '".$p_Fecha_Desde."')";
            $string_condiciones_intervalo       .= " OR (fer_Fecha_Inicio > '".$p_Fecha_Desde."' AND fer_Fecha_Inicio < '".$p_Fecha_Hasta."')";
            $string_condiciones_intervalo       .= " OR (fer_Fecha_Fin > '".$p_Fecha_Desde."' AND fer_Fecha_Fin < '".$p_Fecha_Hasta."'))";
        }
        // CONDICION: FECHA DESDE
        else if (!empty($p_Fecha_Desde)) {
            $string_condiciones_intervalo       .= "fer_Fecha_Inicio > '".$p_Fecha_Desde."'";
        }
        // CONDICION: FECHA HASTA
        else if (!empty($p_Fecha_Hasta)) {
            $string_condiciones_intervalo      .= "fer_Fecha_Inicio < '".$p_Fecha_Hasta."'";
        }
        // CONDICION: SIN FECHA ()
        else{
            return "";
        }





        // VARIBALES : IDs FERIADOS
        $string_condiciones_feriado     = '';
        // CONDICION: IDs FERIADOS
        if (!empty($p_Filtro_Feriado)){
            $string_condiciones_feriado = "fer_Id IN (" . $p_Filtro_Feriado . ")";
        }




        // CONDICION
        $string_condiciones = $string_condiciones_personas . " AND " . $string_condiciones_intervalo  . " AND " . $string_condiciones_feriado;
        $string_condiciones = ltrim($string_condiciones, " AND ");
        $string_condiciones = rtrim($string_condiciones, " AND ");


        $string_condiciones = "WHERE " . $string_condiciones;

        return $string_condiciones;


    }



    public static function obtenerDesdeFiltro($p_Filtro_Personas, $p_Filtro_Intervalo, $p_Filtro_Feriado){

        // VARIABLES
        $list = array();

        // CONDICION
        $p_Condicion = Feriado_L::generar_string_condiciones($p_Filtro_Personas,$p_Filtro_Intervalo, $p_Filtro_Feriado);

        if(is_null($p_Condicion)){
            //printear("No habia personAS QUE Ccumplan con el filtro de personas");
            return $list;
        }
        // CONEXIÓN
        $cnn = Registry::getInstance()->DbConn;

        // CONSULTA SQL
        $rows = $cnn->Select_Lista("SELECT * FROM feriados {$p_Condicion} ORDER BY fer_Fecha_Inicio ASC");

        // RESULTADO
        if ($rows) {
            foreach ($rows as $row) {
                $object = new Feriado_O();
                $object->loadArray($row);
                $list[$row['fer_Id']] = $object;
            }
        }


        // RETORNO
        return $list;


    }


    public static function obtenerTodosEntreFechas($p_Fecha_Desde, $p_Fecha_Hasta, $p_Person = 'TodasLasPersonas', $p_Grupo){

        $cnn = Registry::getInstance()->DbConn;

        $p_Condicion = "WHERE ";
        $p_Condicion .= "((fer_Fecha_Inicio <= '".$p_Fecha_Desde."' AND fer_Fecha_Fin >= '".$p_Fecha_Hasta."')";
        $p_Condicion .= " OR (fer_Fecha_Inicio > '".$p_Fecha_Desde."' AND fer_Fecha_Fin < '".$p_Fecha_Desde."')";
        $p_Condicion .= " OR (fer_Fecha_Inicio > '".$p_Fecha_Desde."' AND fer_Fecha_Inicio < '".$p_Fecha_Hasta."')";
        $p_Condicion .= " OR (fer_Fecha_Fin > '".$p_Fecha_Desde."' AND fer_Fecha_Fin < '".$p_Fecha_Hasta."'))";

        switch($p_Person){
            case "TodasLasPersonas":
                break;
            case 'SelectRol':
                $a_Ids_personas_a_controlar         = Grupos_Personas_L::obtenerARRAYPorGrupo($p_Grupo);
                $p_Condicion .= " AND (fer_Per_Id IN (" . implode(',', array_map('intval', $a_Ids_personas_a_controlar)) . ")) ";
                break;
            default:
                $p_Condicion .=" AND (fer_Per_Id='".$p_Person."')";
        }


        $rows = $cnn->Select_Lista("SELECT * FROM feriados {$p_Condicion} ORDER BY fer_Fecha_Inicio ASC");

        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Feriado_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        }
        else{
            $list = null;
        }




        return $list;
    }

    public static function obtenerTodosArray(){

        $cnn = Registry::getInstance()->DbConn;

        $rows = $cnn->Select_Lista("SELECT * FROM feriados ORDER BY fer_Fecha_Inicio ASC");

        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $list[$row['fer_Id']] = $row;
            }
        }
        else{
            $list = null;
        }


        return $list;
    }


}
