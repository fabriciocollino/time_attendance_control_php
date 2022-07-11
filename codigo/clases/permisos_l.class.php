<?php
//permisos de personas
//
class Permisos_L {

    public static function obtenerPorId($pId) {

        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $pId = (integer) $pId;

        $row = $cnn->Select_Fila("SELECT * FROM permisos WHERE per_Id = ? ORDER BY per_Id", array($pId));
        $object = null;

        if (!empty($row)) {
            $object = new Permisos_O();
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

        $rows = $cnn->Select_Lista("SELECT * FROM permisos WHERE per_Enabled = 1 ORDER BY per_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Permisos_O();
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

        $rows = $cnn->Select_Lista("SELECT * FROM permisos WHERE per_Per_Id = ".$p_Persona." ORDER BY per_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Permisos_O();
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

        $rows = $cnn->Select_Lista("SELECT * FROM permisos WHERE per_Gru_Id = ".$p_Grupo." ORDER BY per_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Permisos_O();
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

    public static function obtenerPorDia($p_Fecha,$p_perID) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_Fecha = (string) $p_Fecha . ' 00:00:00';

        $rows = $cnn->Select_Lista("SELECT * FROM permisos WHERE per_Per_Id ='".$p_perID."' AND per_Fecha_Inicio <= '".$p_Fecha."' AND per_Fecha_Fin >= '".$p_Fecha."'  ORDER BY per_Id");

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }

        return $rows;
    }


    public static function obtenerPorDiaCompletoyPersona($p_Fecha,$p_persona_id) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $p_Fecha = (string) $p_Fecha . ' 00:00:00';

        $rows = $cnn->Select_Lista("SELECT * FROM permisos WHERE (per_Tipo=".PERMISO_DIA_COMPLETO." OR per_Tipo=".PERMISO_PERSONALIZADA.") AND per_Fecha_Inicio <= '".$p_Fecha."' AND per_Fecha_Fin >= '".$p_Fecha."'  ORDER BY per_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Permisos_O();
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

        $rows = $cnn->Select_Lista("SELECT * FROM permisos WHERE (per_Tipo=".PERMISO_LLEGADA_TARDE." OR per_Tipo=".PERMISO_PERSONALIZADA." OR per_Tipo=".PERMISO_DIA_COMPLETO.") AND per_Fecha_Inicio <= '".$fecha_sql."' AND per_Fecha_Fin >= '".$fecha_sql."'  ORDER BY per_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Permisos_O();
                $object->loadArray($row);
                $list[] = $object;
            }
        }else{
            $list = $object;
        }

        if($rows === false) { // devuelve el error si algo fallo con MySql
            echo $cnn->get_Error(Registry::getInstance()->general['debug']);
        }


        if($list != array()){
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
                if($licencia->getTipo()==PERMISO_LLEGADA_TARDE) {//solo me fijo para las llegadas tarde, si es personalizada o dia completo, y esta en el rango de tiempo, esta OK
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

        $rows = $cnn->Select_Lista("SELECT * FROM permisos WHERE (per_Tipo=".PERMISO_SALIDA_TEMPRANO." OR per_Tipo=".PERMISO_PERSONALIZADA." OR per_Tipo=".PERMISO_DIA_COMPLETO.") AND per_Fecha_Inicio <= '".$fecha_sql."' AND per_Fecha_Fin >= '".$fecha_sql."'  ORDER BY per_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Permisos_O();
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
                if($licencia->getTipo()==PERMISO_SALIDA_TEMPRANO) {//solo me fijo para las salidas temprano, si es personalizada o dia completo, y esta en el rango de tiempo, esta OK
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

        $rows = $cnn->Select_Lista("SELECT * FROM permisos {$p_Condicion} ORDER BY per_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {

                $object = new Permisos_O();
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
        echo "1"; die;
        $cnn = Registry::getInstance()->DbConn;

        if ($p_Condicion != '') {
            $p_Condicion = 'WHERE ' . $p_Condicion;
        }

        $rows = $cnn->Select_Lista("SELECT * FROM permisos {$p_Condicion} ORDER BY per_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Permisos_O();
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

        $rows = $cnn->Select_Lista("SELECT * FROM permisos WHERE per_Repetitiva <> 0 ORDER BY per_Id");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new Permisos_O();
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
    public static function obtenerTodosNew($p_Condicion='') {

        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;
        if(isset($_REQUEST['fechaD']) && isset($_REQUEST['fechaH'])){
            $p_Condicion.=" AND per_Fecha_Inicio >= '".$_REQUEST['fechaD']."' and per_Fecha_Fin <= '".$_SESSION['filtro']['fechaH']."'";

        }else if(isset($_SESSION['filtro']['fechaD']) && isset($_SESSION['filtro']['fechaH'])){
            $p_Condicion.=" AND per_Fecha_Inicio >= '".$_SESSION['filtro']['fechaD']."' and per_Fecha_Fin <= '".$_SESSION['filtro']['fechaH']."'";

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
                $p_Condicion .=" AND per_Per_Id=0 AND per_Gru_Id='".$rolf."'";
                break;
            default:
                $p_Condicion .=" AND per_Per_Id='".$person."' AND per_Gru_Id=0";

        }
        $p_Condicion = trim($p_Condicion," AND");
        if ($p_Condicion != '') {
            $p_Condicion = 'WHERE ' . $p_Condicion;
        }

        $rows = $cnn->Select_Lista("SELECT * FROM permisos {$p_Condicion} ORDER BY per_Fecha_Inicio DESC");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new permisos_O();
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
            $p_Condicion.=" AND per_Fecha_Inicio >= '".$_REQUEST['fechaD']."' and per_Fecha_Fin <= '".$_SESSION['filtro']['fechaH']."'";

        }else if(isset($_SESSION['filtro']['fechaD']) && isset($_SESSION['filtro']['fechaH'])){
            $p_Condicion.=" AND per_Fecha_Inicio >= '".$_SESSION['filtro']['fechaD']."' and per_Fecha_Fin <= '".$_SESSION['filtro']['fechaH']."'";

        }
        $person = $_SESSION['filtro']['persona'];
        switch($person){
            case "TodasLasPersonas":
                $p_Condicion .="";
                break;
            case "SelectRol":
                $p_Condicion .=" AND per_Per_Id=0 AND per_Gru_Id='".$_SESSION['filtro']['rolF']."'";
                break;
            default:
                $p_Condicion .=" AND per_Per_Id='".$person."' AND per_Gru_Id=0";

        }
        $p_Condicion = trim($p_Condicion," AND");

        if ($p_Condicion != '') {
            $p_Condicion = 'WHERE ' . $p_Condicion;
        }
        //echo "SELECT * FROM permisos {$p_Condicion} ORDER BY per_Id"; die;
        $rows = $cnn->Select_Lista("SELECT * FROM permisos {$p_Condicion} ORDER BY per_Fecha_Inicio DESC");
        $object = null;
        $list = array();

        if ($rows) {
            foreach ($rows as $row) {
                $object = new permisos_O();
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


}
