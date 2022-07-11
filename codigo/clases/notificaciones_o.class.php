<?php


class Notificaciones_O {

    private $_id;
    private $_men_id;
    private $_email_me;
    private $_usu_id;
    private $_per_id;
    private $_grupo;
    private $_detalle;
    private $_tipo;
    private $_activa;


    private $_Dtipo;
    private $_Ddisparador;
    private $_Ddisparador_Hora;
    private $_Dhora;
    private $_Drepeticion;
    private $_Dequipo;
    private $_Dalarma;
    private $_Ddispositivo;
    private $_Dpersona;
    private $_Drol;
    private $_Dzona;
    private $_Dlog;
    private $_Dhorarios;

    private $_Cdetalle;
    private $_Ctipo;
    private $_Ctexto;
    private $_Cintervalo;
    private $_Cequipo;
    private $_Calarma;
    private $_Cdispositivo;
    private $_Cpersona;
    private $_Crol;
    private $_Czona;
    private $_Cusuario;
    private $_DescargarTipo;

    public $_errores;



    public function __construct() {
        $this->_id = 0;
        $this->_men_id = 0;
        $this->_email_me = '';
        $this->_usu_id = 0;
        $this->_per_id = 0;
        $this->_detalle = ''; //varchar(50)
        $this->_tipo = 0;
        $this->_grupo = 0;
        $this->_contenido = 0;

        $this->_Dtipo = 0;
        $this->_Ddisparador = 0;
        $this->_Ddisparador_Hora = '';
        $this->_Dhora = '';
        $this->_Dequipo = 0;
        $this->_Dalarma = 0;
        $this->_Ddispositivo = 0;
        $this->_Dpersona = 0;
        $this->_Drol = 0;
        $this->_Dzona = 0;
        $this->_Dlog = 0;
        $this->_Dhorarios = '';

        $this->_Cdetalle = '';
        $this->_Ctipo = 0;
        $this->_Ctexto = '';
        $this->_Cintervalo = 0;
        $this->_Cequipo = 0;
        $this->_Calarma = 0;
        $this->_Cdispositivo = 0;
        $this->_Cpersona = 0;
        $this->_Crol = 0;
        $this->_Czona = 0;
        $this->_Cusuario = 0;
        $this->_DescargarTipo = 0;

        $this->_errores = array();
    }


    private function seleccionado($p_valor, $p_texto) {
        if (is_int($p_valor)) {

            if ($p_valor == '') {
                $this->_errores[ValidateHelper::Cadena($p_texto)] = _("Debe seleccionar un")." {$p_texto}.";
            }
        } else {
            if ($p_valor == '') {
                $this->_errores[ValidateHelper::Cadena($p_texto)] = _("Debe seleccionar un")." {$p_texto}.";
            }
        }
    }

    private function control($p_valor, $p_texto, $p_min, $p_max, $p_articulo = 'El', $p_genero='o') {
        if ($p_valor == '') {
            $this->_errores[ValidateHelper::Cadena($p_texto)] = _("Debe proporcionar")." " . strtolower($p_articulo) . " {$p_texto}.";
        } elseif (strlen($p_valor) < $p_min) {
            $this->_errores[ValidateHelper::Cadena($p_texto)] = "{$p_articulo} {$p_texto} "._("especificad")."{$p_genero} "._("es demasiado corto.");
        } elseif (strlen($p_valor) > $p_max) {
            $this->_errores[ValidateHelper::Cadena($p_texto)] = "{$p_articulo} {$p_texto} "._("especificad")."{$p_genero} "._("no debe superar los")." {$p_max} "._("caracteres.");
        } elseif (strpos($p_valor, ':')!== false) {
            $this->_errores[ValidateHelper::Cadena($p_texto)] = "{$p_articulo} {$p_texto} "._("especificad")."{$p_genero} "._("no debe contener dos puntos (:).");
        }
    }

    public function Fecha($p_Fecha, $p_Format, $p_texto) {
        $_fecha_hora = DateTimeHelper::getTimestampFromFormat($p_Fecha, $p_Format);
        if ($_fecha_hora === false) {
            $this->_errores[ValidateHelper::Cadena($p_texto)] = _("La")." {$p_texto} "._("es incorrecta.");
            return $p_Fecha;
        }
        return $_fecha_hora;
    }






    public function getId() {
        return $this->_id;
    }

    public function getMenId() {
        return $this->_men_id;
    }

    public function setMenId($_men_id) {
        $this->_men_id = intval($_men_id);
    }

    public function getEmailMe() {
        return $this->_email_me;
    }

    public function setEmailMe($p_email_me) {
        $this->_email_me = $p_email_me;
    }

    public function getUsuId() {
        return $this->_usu_id;
    }

    public function setUsuId($_usu_id) {
        $this->_usu_id = intval($_usu_id);
    }
    public function getPerId() {
        return $this->_per_id;
    }

    public function setPerId($_per_id) {
        $this->_per_id = intval($_per_id);
    }


    public function getDetalle() {
        return $this->_detalle;
    }

    public function setDetalle($p_detalle) {
        $p_detalle = trim($p_detalle);
        $this->control($p_detalle, _('Nombre'), 4, 100);
        $this->_detalle = $p_detalle;
    }



    public function getTipo() {
        return $this->_tipo;
    }

    public function setTipo($p_tipo) {
        $this->seleccionado($p_tipo, _('Tipo'));
        $this->_tipo = $p_tipo;
    }
    public function getTipo_S() {
        global $a_Notificaciones_Tipos;
        return $a_Notificaciones_Tipos[$this->_tipo];
    }



    public function getGrupo() {
        return $this->_grupo;
    }

    public function setGrupo($p_grupo) {
        // $this->seleccionado($p_grupo, _('Grupo'));
        $this->_grupo = intval($p_grupo);
    }

    public function getGrupo_S() {
        $o_Grupo = Grupo_L::obtenerPorId($this->_grupo);
        return $o_Grupo->getDetalle();
    }
    // abduls
    public function getDestinatarios(){

        if($this->_grupo != 0){
            $o_Grupo = Grupo_L::obtenerPorId($this->_grupo);
            if(!is_null($o_Grupo)){
                $_g_Detalle = mb_convert_case($o_Grupo->getDetalle(), MB_CASE_TITLE, "UTF-8");
                return    htmlentities($_g_Detalle, ENT_QUOTES, 'utf-8');
            }
            else{
                return    '';
            }

        }else if($this->_per_id != 0){
            $persona = Persona_L::obtenerPorId($this->_per_id);
            return  $persona->getNombreCompleto();
        }else if($this->_usu_id != 0){
            $o_Usuario = Usuario_L::obtenerPorId($this->_usu_id,true);
            return $o_Usuario->getNombreCompleto();
        }else{
            return 'Todos Usuarios';
        }
    }

    public function getDestinatario(){

        $o_Usuario = Usuario_L::obtenerPorId($this->_usu_id, true);

        if (is_null($o_Usuario)){
            return '';
        }


        return $o_Usuario->getNombreCompleto();
    }


        // abduls
    public function getDisparadorPara(){
        if($this->_Drol != 0){
            $o_Grupo = Grupo_L::obtenerPorId($this->_Drol);
            if(!is_null($o_Grupo)){
                $_g_Detalle = mb_convert_case($o_Grupo->getDetalle(), MB_CASE_TITLE, "UTF-8");
                return    htmlentities($_g_Detalle, ENT_QUOTES, 'utf-8');
            }
            else{
                return    '';
            }
        }else if($this->_Dpersona != 0){
            $persona = Persona_L::obtenerPorId($this->_Dpersona);
            return $persona->getNombreCompleto();
        }else{
            return 'Todas las Personas';
        }
    }

    public function getGrupo_SC() {
        $o_Grupo = Grupo_L::obtenerPorId($this->_grupo);
        if($this->_grupo == -1)
            if(isset($_SESSION['USUARIO']['id']) && $this->_usu_id == $_SESSION['USUARIO']['id'])
                return 'Me';
            else
                if($this->_tipo == 2)
                    return 'Todos usuario';
                else
                    return 'Otros usuario';
        else if($this->_grupo==-3)
            return 'Todos Personas';
        else if($this->_grupo==-2)
            return 'Otros Usuario';
        else if($o_Grupo != null)
            return $o_Grupo->getDetalle()." (".$o_Grupo->getPersonasCount().")";
        else if($this->_grupo==0)
            return '----';

    }

    //SECCION DISPARADOR
    public function getTipoD() {
        return $this->_Dtipo;
    }

    public function setTipoD($p_Tipo) {
        $p_Tipo = (integer) $p_Tipo;
        if($p_Tipo!=0)
            $this->seleccionado($p_Tipo, _('Tipo'));
        $this->_Dtipo = $p_Tipo;

    }

    public function getTipo_SD() {
        if($this->_Dtipo==0)return _('Inmediato');
        if($this->_Dtipo==1)return _('Diario');
        if($this->_Dtipo==2)return _('Semanal');
        if($this->_Dtipo==3)return _('Quincenal');
        if($this->_Dtipo==4)return _('Mensual');
        if($this->_Dtipo==5)return _('Anual');
        if($this->_Dtipo==100)return _('Inmediato');
    }

    public function getDisparador() {
        return $this->_Ddisparador;
    }

    public function setDisparador($p_Disparador) {
        $p_Disparador = (integer) $p_Disparador;
        $this->seleccionado($p_Disparador, _('Disparador'));
        $this->_Ddisparador = $p_Disparador;
    }

    public function getDisparadorHora() {
        return $this->_Ddisparador_Hora;
    }

    public function setDisparadorHora($p_Disparador) {
        $this->_Ddisparador_Hora = $p_Disparador;
    }


    public function getHoraD($p_Format = null) {
        if (!is_null($p_Format) && is_string($p_Format)) {
            if (is_int($this->_Dhora)) {
                return date($p_Format, $this->_Dhora);
            } else {
                return $this->_Dhora;
            }
        }
        return $this->_Dhora;
    }

    public function setHoraD($p_Hora, $p_Format,$p_Ignore=false) {
        if(!$p_Ignore){
            $this->_Dhora = $this->Fecha($p_Hora, $p_Format, 'Hora');
            $this->_Dhora = $p_Hora;
        }else{
            $this->_Dhora = 0;
        }
    }

    public function getEquipoD() {
        return $this->_Dequipo;
    }

    public function setEquipoD($p_Equipo) {
        $p_Equipo = (integer) $p_Equipo;
        $this->seleccionado($p_Equipo, _('Equipo'));
        $this->_Dequipo = $p_Equipo;

    }

    public function setEquipoD_NoCHECK($p_Equipo) {
        $p_Equipo = (integer) $p_Equipo;
        $this->_Dequipo = $p_Equipo;

    }

    public function getDispositivoD() {
        return $this->_Ddispositivo;
    }

    public function setDispositivoD($p_Dispositivo) {
        $p_Dispositivo = (integer) $p_Dispositivo;
        $this->seleccionado($p_Dispositivo, _('Dispositivo'));
        $this->_Ddispositivo = $p_Dispositivo;
    }

    public function setDispositivoD_NoCHECK($p_Dispositivo) {
        $p_Dispositivo = (integer) $p_Dispositivo;
        $this->_Ddispositivo = $p_Dispositivo;
    }

    public function getPersonaD() {
        return $this->_Dpersona;
    }

    public function setPersonaD($p_Persona) {
        $p_Persona = (integer) $p_Persona;
        $this->seleccionado($p_Persona, _('Persona'));
        $this->_Dpersona = $p_Persona;
    }

    public function setPersonaD_NoCHECK($p_Persona) {
        $p_Persona = (integer) $p_Persona;
        $this->_Dpersona = $p_Persona;
    }

    public function getRolD() {
        return $this->_Drol;
    }
    public function getGrupoD() {//refactor a grupo
        return $this->_Drol;
    }

    public function setRolD($p_Grupo) {
        $p_Grupo = (integer) $p_Grupo;
        $this->seleccionado($p_Grupo, _('GrupoD'));
        $this->_Drol = $p_Grupo;
    }
    public function setGrupoD($p_Grupo) { //refactor a grupo
        $p_Grupo = (integer) $p_Grupo;
        $this->seleccionado($p_Grupo, _('GrupoD'));
        $this->_Drol = $p_Grupo;
    }

    public function setRolD_NoCHECK($p_Grupo) {
        $p_Grupo = (integer) $p_Grupo;
        $this->_Drol = $p_Grupo;
    }
    public function setGrupoD_NoCHECK($p_Grupo) { //refactor a grupo
        $p_Grupo = (integer) $p_Grupo;
        $this->_Drol = $p_Grupo;
    }

    public function getZonaD() {
        return $this->_Dzona;
    }

    public function setZonaD($p_Zona) {
        $p_Zona = (integer) $p_Zona;
        $this->seleccionado($p_Zona, _('Zona'));
        $this->_Dzona = $p_Zona;
    }

    public function setZonaD_NoCHECK($p_Zona) {
        $p_Zona = (integer) $p_Zona;
        $this->_Dzona = $p_Zona;
    }



    public function getLogD() {
        return $this->_Dlog;
    }

    public function setLogD($p_Log) {
        $p_Log = (integer) $p_Log;
        $this->_Dlog = $p_Log;
    }

    public function getReporteDescargarTipoC() {
        return $this->_DescargarTipo;
    }

    public function setReporteDescargarTipoC($p_DescargarTipo) {
        $p_DescargarTipo = (integer) $p_DescargarTipo;
        $this->_DescargarTipo = $p_DescargarTipo;
    }

    public function getHorariosD($p_Dia='') {
        if($p_Dia=='') {
            return $this->_Dhorarios;
        }else{
            $array_horarios=json_decode($this->_Dhorarios, true);
            return $array_horarios[$p_Dia];
        }
    }

    public function setHorariosD($p_Horarios) {
        $p_Horarios = (string) $p_Horarios;
        $this->_Dhorarios = $p_Horarios;
    }

    //0 for sunday
    //6 for saturday











//SECCION CONTENIDO
    public function getDetalleC() {
        return $this->_Cdetalle;
    }

    public function setDetalleC($p_Detalle) {
        $p_Detalle = (string) $p_Detalle;
        $this->control($p_Detalle, _('Asunto'), 4, 100);
        $this->_Cdetalle = $p_Detalle;
    }



    public function getTipoC() {
        return $this->_Ctipo;
    }

    public function getTipo_SC() {
        global $a_Notificaciones_Contenidos_Tipos;
        global $a_Notificaciones_Contenidos_Intervalos;
        if($this->_Ctipo==1)
            return $a_Notificaciones_Contenidos_Tipos[$this->_Ctipo];
        if($this->_Ctipo>=10)
            return $a_Notificaciones_Contenidos_Tipos[2];
    }

    public function setTipoC($p_Tipo) {
        $p_Tipo = (integer) $p_Tipo;
        $this->_Ctipo = $p_Tipo;
    }

    public function getTextoC(){
        return $this->_Ctexto;
    }

    public function setTextoC($p_Texto){
        $p_Texto = (string)$p_Texto;
        $this->_Ctexto = $p_Texto;
    }

    public function getEquipoC(){
        return $this->_Cequipo;
    }

    public function setEquipoC($p_Equipo){
        $p_Equipo = (integer)$p_Equipo;
        $this->_Cequipo = $p_Equipo;
    }

    public function getIntervaloC(){
        return $this->_Cintervalo;
    }

    public function setIntervaloC($p_Intervalo){
        $p_Intervalo = (integer)$p_Intervalo;
        $this->_Cintervalo = $p_Intervalo;
    }


    public function getDispositivoC() {
        return $this->_Cdispositivo;
    }

    public function setDispositivoC($p_Dispositivo) {
        $p_Dispositivo = (integer) $p_Dispositivo;
        $this->seleccionado($p_Dispositivo, _('Dispositivo'));
        $this->_Cdispositivo = $p_Dispositivo;
    }

    public function getPersonaC() {
        return $this->_Cpersona;
    }

    public function setPersonaC($p_Persona) {
        $p_Persona = (integer) $p_Persona;
        $this->seleccionado($p_Persona, _('Persona'));
        $this->_Cpersona = $p_Persona;
    }
    public function setPersonaC_NoCHECK($p_Persona) {
        $p_Persona = (integer) $p_Persona;
        $this->_Cpersona = $p_Persona;
    }

    public function getRolC() {
        return $this->_Crol;
    }

    public function setRolC($p_Grupo) {
        $p_Grupo = (integer) $p_Grupo;
        $this->seleccionado($p_Grupo, _('GrupoC'));
        $this->_Crol = $p_Grupo;
    }

    public function setRolC_NoCHECK($p_Grupo) {
        $p_Grupo = (integer) $p_Grupo;
        $this->_Crol = $p_Grupo;
    }

    public function getZonaC() {
        return $this->_Czona;
    }

    public function setZonaC($p_Zona) {
        $p_Zona = (integer) $p_Zona;
        $this->seleccionado($p_Zona, _('Zona'));
        $this->_Czona = $p_Zona;
    }


    public function getActiva() {
        return $this->_id;
    }

    public function setActiva($p_activa) {
        $this->_activa = (integer)$p_activa;
    }




    public function esValido() {

        //Si el array errores no tiene elementos entonces el objeto es valido.
        return count($this->_errores) == 0;
    }

    public function getErrores() {
        return $this->_errores;
    }

    public function loadArray($p_Datos) {

        $this->_id = (integer) $p_Datos["not_Id"];
        $this->_men_id = (integer) $p_Datos["not_Men_Id"];
        $this->_email_me = (string) $p_Datos["not_Email_Me"];
        $this->_usu_id = (integer) $p_Datos["not_Dest_Usu_Id"];
        $this->_per_id = (integer) $p_Datos["not_Dest_Per_Id"];
        $this->_grupo = (integer) $p_Datos["not_Dest_Grupo"];
        $this->_detalle = (string) $p_Datos["not_Detalle"];
        $this->_tipo = (integer) $p_Datos["not_Tipo"];
        $this->_activa = (integer) $p_Datos["not_Activa"];

        $this->_Dtipo = (integer) $p_Datos["ndi_Tipo"];
        $this->_Ddisparador = (integer) $p_Datos["ndi_Disparador"];
        $this->_Ddisparador_Hora = (string) $p_Datos["not_Disparador_Hora"];
        $this->_Dhora = $p_Datos["ndi_Hora"];
        $this->_Dequipo = (integer) $p_Datos["ndi_Equipo"];
        $this->_Dalarma = (integer) $p_Datos["ndi_Alarma"];
        $this->_Ddispositivo = (integer) $p_Datos["ndi_Dispositivo"];
        $this->_Dpersona = (integer) $p_Datos["ndi_Persona"];
        $this->_Drol = (integer) $p_Datos["ndi_Grupo"];
        $this->_Dzona = (integer) $p_Datos["ndi_Zona"];
        $this->_Dlog = (integer) $p_Datos["ndi_Log"];
        $this->_Dhorarios = (string) $p_Datos["ndi_Horarios"];

        $this->_Cdetalle = (string) $p_Datos["nco_Detalle"];
        $this->_Ctipo = (integer) $p_Datos["nco_Tipo"];
        $this->_Ctexto = (string) $p_Datos["nco_Texto"];
        $this->_Cintervalo=(integer)$p_Datos["nco_Intervalo"];
        $this->_Cequipo = (integer) $p_Datos["nco_Equipo"];
        $this->_Calarma = (integer) $p_Datos["nco_Alarma"];
        $this->_Cdispositivo = (integer) $p_Datos["nco_Dispositivo"];
        $this->_Cpersona = (integer) $p_Datos["nco_Persona"];
        $this->_Crol = (integer) $p_Datos["nco_Grupo"];
        $this->_Czona = (integer) $p_Datos["nco_Zona"];
        $this->_Cusuario = (integer) $p_Datos["nco_Usuario"];
        $this->_DescargarTipo = (integer) $p_Datos["nco_DescargarTipo"];


    }

    public function save($p_Debug = false) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if (!$this->esValido()) {
            return false;
        }

        $datos = array(
            'not_Men_Id' => $this->_men_id,
            'not_Email_Me' => $this->_email_me,
            'not_Dest_Usu_Id' => $this->_usu_id,
            'not_Dest_Per_Id' => $this->_per_id,
            'not_Dest_Grupo' => $this->_grupo,
            'not_Detalle' => $this->_detalle,
            'not_Tipo' => $this->_tipo,

            'ndi_Tipo' => $this->_Dtipo,
            'ndi_Disparador' => $this->_Ddisparador,
            'not_Disparador_Hora' => $this->_Ddisparador_Hora,
            'ndi_Hora' => $this->_Dhora,
            'ndi_Equipo' => $this->_Dequipo,
            'ndi_Alarma' => $this->_Dalarma,
            'ndi_Dispositivo' => $this->_Ddispositivo,
            'ndi_Persona' => $this->_Dpersona,
            'ndi_Grupo' => $this->_Drol,
            'ndi_Zona' => $this->_Dzona,
            'ndi_Log' => $this->_Dlog,
            'ndi_Horarios' => $this->_Dhorarios,

            'nco_Detalle' => $this->_Cdetalle,
            'nco_Tipo' => $this->_Ctipo,
            'nco_Texto' => $this->_Ctexto,
            'nco_Equipo' => $this->_Cequipo,
            'nco_Intervalo' => $this->_Cintervalo,
            'nco_Alarma' => $this->_Calarma,
            'nco_Dispositivo' => $this->_Cdispositivo,
            'nco_Persona' => $this->_Cpersona,
            'nco_Grupo' => $this->_Crol,
            'nco_Zona' => $this->_Czona,
            'nco_Usuario' => $this->_Cusuario,
            'nco_DescargarTipo' => $this->_DescargarTipo,

            'not_Activa' => $this->_activa
        );

        if ($this->_id == 0) {
            $resultado = $cnn->Insert('notificaciones', $datos);
            if ($resultado !== false) {
                $this->_id = $cnn->Devolver_Insert_Id();
            }
        } else {
            $resultado = $cnn->Update('notificaciones', $datos, "not_Id = {$this->_id}");
        }

        if ($resultado === false) {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
        }

        return $resultado;
    }


    public function enviarReporteAutomaticoObjeto($enviar_reporte_vacio = false){
        /* VARIABLES PROXIMA CARGA
        $_SESSION['filtro']['tipo']             =   $T_Tipo;
        $_SESSION['filtro']['intervalo']        =   $T_Intervalo;
        $_SESSION['filtro'] ['bloqueados']      =   !isset($_POST['bloqueados'])                ?       (isset($_SESSION['filtro']['bloqueados']))      ?   $_SESSION['filtro']['bloqueados']               :     ''      :     $_POST['bloqueados'];

*/

        /* NUEVO REPORTE
        $T_Filtro_Array                         =   Filtro_L::get_filtro_persona($_POST,$_SESSION);
        $o_Reporte              = new Reporte_O($T_Tipo,$T_Intervalo, $T_Filtro_Array);//, $T_Intervalo, $T_Persona, $T_Grupo);
        $o_Listado              = $o_Reporte->generar_reporte();
*/

        /* VARIABLES: REPORTE ADJUNTO */
        $reporte_intervalo[1] = 'F_Hoy';
        $reporte_intervalo[2] = 'F_Semana';
        $reporte_intervalo[3] = 'F_Quincena';
        $reporte_intervalo[4] = 'F_Mes';
        $reporte_intervalo[5] = 'F_Ano';

        /* VARIABLES: REPORTE ADJUNTO */
        $descarga_tipo[1] = '.pdf';
        $descarga_tipo[2] = '.csv';
        $descarga_tipo[3] = '.xls';

        /* VARIABLES: REPORTE ADJUNTO */
        $reporte_tipo[40] = 'Marcaciones';
        $reporte_tipo[41] = 'Jornadas';
        $reporte_tipo[42] = 'Intervalo';
        $reporte_tipo[43] = 'Llegadas_Tarde';
        $reporte_tipo[44] = 'Salidas_Temprano';
        $reporte_tipo[45] = 'Listado_Asistencias';
        $reporte_tipo[46] = 'Listado_Ausencias';

        /* VARIABLES: REPORTE ADJUNTO */
        $reporte_tipo[20] = 'Marcaciones';
        $reporte_tipo[21] = 'Jornadas';
        $reporte_tipo[26] = 'Intervalo';
        $reporte_tipo[19] = 'Llegadas_Tarde';
        $reporte_tipo[22] = 'Listado_Ausencias';





        /* VARIABLES: REPORTE ADJUNTO */
        $T_Tipo                         = $reporte_tipo[$this->getTipoC()];
        $T_Filtro_Persona ['persona']   = $this->getPersonaC();
        $T_Archivo_tipo                 = $descarga_tipo[$this->getReporteDescargarTipoC()];
        $T_Intervalo                    = $reporte_intervalo[$this->getIntervaloC()];

        /* NUEVO REPORTE */
        $T_Filtro_Array                 = Filtro_L::get_filtro_persona($T_Filtro_Persona);
        $o_Reporte                      = new Reporte_O($T_Tipo,$T_Intervalo, $T_Filtro_Array);//, $T_Intervalo, $T_Persona, $T_Grupo);
        $a_reporte_data                 = $o_Reporte->generar_reporte();
        $a_csv_excel_data               = $o_Reporte->generar_csv_excel_data($a_reporte_data);

        /* REPORTE VACIO: NO ENVIAR MAIL */
        if(count($a_csv_excel_data) == 1 && $enviar_reporte_vacio == false)
            return false;


        /* REPORTE ADJUNTO */
        switch ($T_Archivo_tipo){
            /* PDF */
            case '.pdf':
                $url_adjunto = $o_Reporte->generar_pdf($a_reporte_data, $a_csv_excel_data, 'Guardar');
                break;

            /* CSV */
            case '.csv':
                $url_adjunto = $o_Reporte->generar_csv($a_csv_excel_data, 'Guardar');
                break;

            /* EXCEL */
            case '.xls':
                $url_adjunto = $o_Reporte->generar_excel($a_csv_excel_data, 'Guardar');
                break;
        }

        unset($o_Reporte);



        /* MAIL */
        $o_Email       = new Email_O();
        $o_Email->setAdjunto($url_adjunto);
        $o_Email->setDestinatarioBCC($this->_email_me);
        $o_Email->setEstado(1); //para enviar
        $o_Email->setFrom('enPunto');
        $o_Email->setFecha(date("Y-m-d H:i:s"),"Y-m-d H:i:s");
        $o_Email->setGrupo($this->_grupo);
        $o_Email->setTipo(0);
        $o_Email->setSujeto($this->getDetalleC());
        $o_Email->setCuerpo($this->getTextoC());


        /* SET MAIL GRUPAL */
        if($this->_grupo != 0){ // grupo
            $o_Email->setGrupal(1);
        }
        else if($this->_usu_id != 0){ // user
            $o_Usuario = Usuario_L::obtenerPorId($this->_usu_id,true);

            // Si el Usuario esta Bloqueado, no envio
            if(!is_null($o_Usuario->getBloqueadoEl())) {
                return false;
            }

            $o_Email->setDestinatario($o_Usuario->getEmail());
            $o_Email->setGrupal(0);


        }
        else if($this->_per_id != 0){ // person
            $persona = Persona_L::obtenerPorId($this->_per_id);

            //
            if($persona->getExcluir()) {
                return false;
            }

            $o_Email->setDestinatario($persona->getEmail());
            $o_Email->setGrupal(0);
        }
        else{ // none
            $o_Email->setGrupal(1);
        }

        /* ENVIAR */
        $o_Email->enviar();
        $o_Email->setEstado(2);


        /* GUARDAR */
        $o_Email->save('Off');


        return true;
    }

    public function enviarReporteAutomaticoV3($enviar_reporte_vacio = false){
        /* VARIABLES PROXIMA CARGA
        $_SESSION['filtro']['tipo']             =   $T_Tipo;
        $_SESSION['filtro']['intervalo']        =   $T_Intervalo;
        $_SESSION['filtro'] ['bloqueados']      =   !isset($_POST['bloqueados'])                ?       (isset($_SESSION['filtro']['bloqueados']))      ?   $_SESSION['filtro']['bloqueados']               :     ''      :     $_POST['bloqueados'];

*/

        /* NUEVO REPORTE
        $T_Filtro_Array                         =   Filtro_L::get_filtro_persona($_POST,$_SESSION);
        $o_Reporte              = new Reporte_O($T_Tipo,$T_Intervalo, $T_Filtro_Array);//, $T_Intervalo, $T_Persona, $T_Grupo);
        $o_Listado              = $o_Reporte->generar_reporte();
*/

        /* VARIABLES: REPORTE ADJUNTO */
        $reporte_intervalo[1] = 'F_Hoy';
        $reporte_intervalo[2] = 'F_Semana';
        $reporte_intervalo[3] = 'F_Quincena';
        $reporte_intervalo[4] = 'F_Mes';
        $reporte_intervalo[5] = 'F_Ano';

        /* VARIABLES: REPORTE ADJUNTO */
        $descarga_tipo[1] = '.pdf';
        $descarga_tipo[2] = '.csv';
        $descarga_tipo[3] = '.xls';

        /* VARIABLES: REPORTE ADJUNTO */
        $reporte_tipo[40] = 'Marcaciones';
        $reporte_tipo[41] = 'Jornadas';
        $reporte_tipo[42] = 'Intervalo';
        $reporte_tipo[43] = 'Llegadas_Tarde';
        $reporte_tipo[44] = 'Salidas_Temprano';
        $reporte_tipo[45] = 'Listado_Asistencias';
        $reporte_tipo[46] = 'Listado_Ausencias';

        /* VARIABLES: REPORTE ADJUNTO */
        $reporte_tipo[20] = 'Marcaciones';
        $reporte_tipo[21] = 'Jornadas';
        $reporte_tipo[26] = 'Intervalo';
        $reporte_tipo[19] = 'Llegadas_Tarde';
        $reporte_tipo[22] = 'Listado_Ausencias';



        /* VARIABLES: REPORTE ADJUNTO */
        $Filtro_Tipo            = $reporte_tipo[$this->getTipoC()];
        $Filtro_Persona         = $this->getPersonaC();
        $archivo_tipo           = $descarga_tipo[$this->getReporteDescargarTipoC()];
        $guardar_descargar      = 'Guardar';
        $Filtro_Intervalo       = $reporte_intervalo[$this->getIntervaloC()];
        $Filtro_Grupo           = $this->getRolC();


        /* NUEVO REPORTE */
        $o_Reporte              = new Reporte_O($Filtro_Tipo, $Filtro_Intervalo, $Filtro_Persona, $Filtro_Grupo);
        $a_data                 = $o_Reporte->generar_reporte();
        $csv_excel_data         = $o_Reporte->generar_csv_excel_data($a_data);

        /* REPORTE VACIO: NO ENVIAR MAIL */
        if(count($csv_excel_data) == 1 && $enviar_reporte_vacio == false)
            return false;


        /* REPORTE ADJUNTO */
        switch ($archivo_tipo){
            /* PDF */
            case '.pdf':
                $url_adjunto = $o_Reporte->generar_pdf($a_data, $csv_excel_data, $guardar_descargar);
                break;

            /* CSV */
            case '.csv':
                $url_adjunto = $o_Reporte->generar_csv($csv_excel_data, $guardar_descargar);
                break;

            /* EXCEL */
            case '.xls':
                $url_adjunto = $o_Reporte->generar_excel($csv_excel_data, $guardar_descargar);
                break;
        }

        unset($o_Reporte);



        /* MAIL */
        $o_Email       = new Email_O();
        $o_Email->setAdjunto($url_adjunto);
        $o_Email->setDestinatarioBCC($this->_email_me);
        $o_Email->setEstado(1); //para enviar
        $o_Email->setFrom('enPunto');
        $o_Email->setFecha(date("Y-m-d H:i:s"),"Y-m-d H:i:s");
        $o_Email->setGrupo($this->_grupo);
        $o_Email->setTipo(0);
        $o_Email->setSujeto($this->getDetalleC());
        $o_Email->setCuerpo($this->getTextoC());


        /* SET MAIL GRUPAL */
        if($this->_grupo != 0){ // grupo
            $o_Email->setGrupal(1);
        }
        else if($this->_usu_id != 0){ // user
            $o_Usuario = Usuario_L::obtenerPorId($this->_usu_id,true);

            // Si el Usuario esta Bloqueado, no envio
            if(!is_null($o_Usuario->getBloqueadoEl())) {
                return;
            }

            $o_Email->setDestinatario($o_Usuario->getEmail());
            $o_Email->setGrupal(0);


        }
        else if($this->_per_id != 0){ // person
            $persona = Persona_L::obtenerPorId($this->_per_id);

            //
            if($persona->getExcluir()) {
                return;
            }

            $o_Email->setDestinatario($persona->getEmail());
            $o_Email->setGrupal(0);
        }
        else{ // none
            $o_Email->setGrupal(1);
        }

        /* ENVIAR */
        if($o_Email->enviar()){
            $o_Email->setEstado(2);
        }

        /* GUARDAR */
        $o_Email->save('Off');


        return true;
    }



    public function persona_en_alerta($p_persona_id_log){



        $condition_persona = [
            'persona'   => $this->getPersonaD(),
            'rolf'      => $this->getRolD()
        ];



        /* NUEVO REPORTE */
        $T_Filtro_Array                 = Filtro_L::get_filtro_persona($condition_persona);
        $array_personas_a_controlar     = Persona_L::obtenerDesdeFiltroArray($T_Filtro_Array);


        if(!isset($array_personas_a_controlar[$p_persona_id_log])){
            return false;
        }

        return true;

    }


    public function alerta_log_enviar($p_persona_id_log, $p_enviar_email_persona = false){


        if(!$this->persona_en_alerta($p_persona_id_log)){
            return false;
        }

        /* VARIABLES */
        $reporte_tipo[500]                  = 'Llegadas_Tarde';
        $reporte_tipo[560]                  = 'Salidas_Temprano';
        $reporte_tipo[580]                  = 'Listado_Ausencias';


        /* VARIABLES: REPORTE */
        $T_Tipo             = $reporte_tipo[$this->getDisparador()];
        $T_Intervalo        = 'F_Hoy';
        $T_Filtro_Persona   = ['persona' => $p_persona_id_log , 'rolF' => 0];

        $T_Filtro_Array     =   Filtro_L::get_filtro_persona($T_Filtro_Persona);



        /* NUEVO REPORTE */
        $o_Reporte          = new Reporte_O($T_Tipo,$T_Intervalo, $T_Filtro_Array);
        $o_listado          = $o_Reporte->generar_reporte();

        //printear('$o_listado');
        //printear($o_listado);

        //printear('$o_Reporte');
        //printear($o_Reporte);

        //printear('$T_Filtro_Array');
        //printear($T_Filtro_Array);

        /* REPORTE PERSONA VACÍO: SALIR */
        if(!isset($o_listado[$p_persona_id_log])){
            //printear("REPORTE PERSONA VACÍO: SALIR");
            return false;
        }

        /* VARIABLES */
        $mail_data                  = array();
        $o_persona                  = Persona_L::obtenerPorId($p_persona_id_log);
        $fecha_actual               = date("d-m-Y");
        $nombre_completo            = $o_persona->getApellido().", ".$o_persona->getNombre();
        $o_listado                  = $o_listado[$p_persona_id_log];
        $detalle_horario            = $o_listado['Hora_Trabajo_Detalle'];
        $horario_inicio             = '';
        $horario_fin                = '';
        $fecha_hora_trabajo_inicio  = '';
        $fecha_hora_trabajo_fin     = '';


        if ($o_listado ['Total'] <= 0 ){//&& $p_debug ==false) {
            //printear("total = 0");
            return false;
        }

        /* DISPARADOR TIPO */
        switch ($this->getDisparador()){

            case NOT_AUSENCIA:
                $horario_inicio                     = $o_listado ['Ausencias_No_Justificadas'][0]['Hora_Trabajo_Inicio'];
                $horario_fin                        = $o_listado ['Ausencias_No_Justificadas'][0]['Hora_Trabajo_Fin'];
                $fecha_hora_trabajo_inicio          = $o_listado ['Ausencias_No_Justificadas'][0]['Fecha_Hora_Trabajo_Inicio'];
                $fecha_hora_trabajo_fin             = $o_listado ['Ausencias_No_Justificadas'][0]['Fecha_Hora_Trabajo_Fin'];
                break;

            case NOT_LLEGADA_TARDE:
                $horario_inicio                     = $o_listado ['Llegadas_Tarde'][0]['Hora_Trabajo_Inicio'];
                $horario_fin                        = $o_listado ['Llegadas_Tarde'][0]['Hora_Trabajo_Fin'];
                $fecha_hora_trabajo_inicio          = $o_listado ['Llegadas_Tarde'][0]['Fecha_Hora_Trabajo_Inicio'];
                $fecha_hora_trabajo_fin             = $o_listado ['Llegadas_Tarde'][0]['Fecha_Hora_Trabajo_Fin'];

                break;

            case NOT_SALIDA_TEMPRANA:
                $horario_inicio                     = $o_listado ['Salidas_Temprano'][0]['Hora_Trabajo_Inicio'];
                $horario_fin                        = $o_listado ['Salidas_Temprano'][0]['Hora_Trabajo_Fin'];
                $fecha_hora_trabajo_inicio          = $o_listado ['Salidas_Temprano'][0]['Fecha_Hora_Trabajo_Inicio'];
                $fecha_hora_trabajo_fin             = $o_listado ['Salidas_Temprano'][0]['Fecha_Hora_Trabajo_Fin'];

                break;
        }



        // VARIABLES
        //$mail_data['Detalle']                       = $o_listado;
        $mail_data['Nombre']                        = $nombre_completo;
        $mail_data['Sujeto']                        = $this->getDetalleC().' - '.$nombre_completo . ' - '.$fecha_hora_trabajo_inicio . ' - '.$fecha_hora_trabajo_fin;
        $mail_data['Cuerpo']                        = $this->getDetalleC().' - '.$nombre_completo. '<br>'. $detalle_horario.' '.$horario_inicio. ' - '.$horario_fin.' - Fecha : '.$fecha_actual. '<br>'. $this->getTextoC();
        $mail_data['Detalle']['recipient_tipo']     = 'persona';
        $mail_data['Detalle']['recipient_id']       = $p_persona_id_log;
        $mail_data['Detalle']['recipient_sujeto']       = $mail_data['Sujeto'];


        $a_Email = Email_L::obtenerPorDetalle(json_encode( $mail_data['Detalle'] ));


        if(count($a_Email) != 0){
            //printear("el mail ya existe");
            return "Enviado anteriormente";
        }

        // VARIABLES
        $email_Destinatario                         = $this->getUsuId();
        $email_DestinatarioBCC                      = $this->getEmailMe();

        // VARIABLES DESTINATARIO PERSONA
        if($p_enviar_email_persona){
            $o_Persona_Destinatario     = Persona_L::obtenerPorId($p_persona_id_log);
            $email_persona              = $o_Persona_Destinatario->getEmail();
            if($email_persona != ''){
                $email_Destinatario      = $email_Destinatario .", ". $o_Persona_Destinatario->getEmail();
            }

        }


        // NUEVO EMAIL
        $o_Email            = new Email_O();

        // EMAIL DATA
        $o_Email->setSujeto($mail_data['Sujeto']);
        $o_Email->setDetalle(json_encode($mail_data['Detalle']));
        $o_Email->setCuerpo($mail_data['Cuerpo']);

        // EMAIL DESTINATARIO
        $o_Email->setDestinatario($email_Destinatario);
        $o_Email->setDestinatarioBCC($email_DestinatarioBCC.", soporte@enpuntocontrol.com");
        $o_Email->setGrupal(0);

        // EMAIL MORE DATA
        $o_Email->setFrom('enPunto');
        $o_Email->setFecha(date("Y-m-d H:i:s"),"Y-m-d H:i:s");
        $o_Email->setEstado(1);
        $o_Email->setTipo(1);

        // EMAIL SAVE
        $o_Email->save('Off');

        /* ENVIAR */
        if($o_Email->enviar()){
            $o_Email->setEstado(2);
        }


        return true;


    }



    public function enviarAlertaObjeto(){

        /* VARIABLES */
        $reporte_tipo[500]                  = 'Llegadas_Tarde';
        $reporte_tipo[560]                  = 'Salidas_Temprano';
        $reporte_tipo[580]                  = 'Listado_Ausencias';


        //$_SESSION['filtro']['intervalo']    = 'F_Hoy';
        //$_SESSION['filtro']['tipo']         = $reporte_tipo[$this->getDisparador()];
        //$_SESSION['filtro']['persona']      = $this->getPersonaD() == 0 && $this->getRolD() == 0 ? "TodasLasPersonas": $this->getPersonaD();
        //$_SESSION['filtro']['persona']      = $this->getRolD() != 0 ? 'SelectRol' : $_SESSION['filtro']['persona'];
        //$_SESSION['filtro']['rolF']         = $this->getRolD();

        $disparador_tipo                    = $this->getDisparador();
        $mail_data                          = array();
        $disparador_persona                 = $this->getRolD() != 0 ? 'Grupo' : ($this->getPersonaD() != 0 ? 'Persona' : 'TodasLasPersonas');




        /* VARIABLES: REPORTE ADJUNTO */
        $T_Tipo                         = $reporte_tipo[$this->getDisparador()];
        $T_Filtro_Persona ['persona']   = $this->getPersonaD();
        $T_Filtro_Persona ['grupo']     = $this->getRolD();
        $T_Intervalo                    = 'F_Hoy';

        /* NUEVO REPORTE */
        $T_Filtro_Array                 = Filtro_L::get_filtro_persona($T_Filtro_Persona);
        $o_Reporte                      = new Reporte_O($T_Tipo,$T_Intervalo, $T_Filtro_Array);//, $T_Intervalo, $T_Persona, $T_Grupo);
        $o_listado                      = $o_Reporte->generar_reporte();


        /* DISPARADOR TIPO */
        switch ($disparador_tipo){

            case NOT_AUSENCIA:
                $key_total  = 'Total_No_Justificadas';
                break;

            case NOT_LLEGADA_TARDE:
            case NOT_SALIDA_TEMPRANA:
                $key_total = 'Total';
                break;
        }

       //////printear('$o_Reporte');
       //////printear($o_Reporte);

       //////printear('$o_listado');
       //////printear($o_listado);

        /* MAIL DATA */

        $array_personas_a_controlar             = $o_Reporte->get_personas_a_controlar();
       //////printear('$array_personas_a_controlar');
       //////printear($array_personas_a_controlar);

        foreach($array_personas_a_controlar as $perID => $persona){

            /* VARIABLES */
            $total_eventos = $o_listado[$perID][$key_total];


           //////printear('$total_eventos');
           //////printear($total_eventos);

            //////printear($o_listado);
            if ($total_eventos > 0) {

                $nombre_completo    = $o_listado[$perID]['per_Apellido'].", ".$o_listado[$perID]['per_Nombre'];
                $detalle_horario    = $o_listado[$perID]['Hora_Trabajo_Detalle'];


                /* DISPARADOR TIPO */
                switch ($disparador_tipo){

                    case NOT_AUSENCIA:
                        $horario_inicio                     = $o_listado[$perID] ['Ausencias_No_Justificadas'][0]['Hora_Trabajo_Inicio'];
                        $horario_fin                        = $o_listado[$perID] ['Ausencias_No_Justificadas'][0]['Hora_Trabajo_Fin'];
                        $fecha_hora_trabajo_inicio          = $o_listado[$perID] ['Ausencias_No_Justificadas'][0]['Fecha_Hora_Trabajo_Inicio'];
                        break;

                    case NOT_LLEGADA_TARDE:
                        $horario_inicio                     = $o_listado[$perID] ['Llegadas_Tarde'][0]['Hora_Trabajo_Inicio'];
                        $horario_fin                        = $o_listado[$perID] ['Llegadas_Tarde'][0]['Hora_Trabajo_Fin'];
                        $fecha_hora_trabajo_inicio          = $o_listado[$perID] ['Llegadas_Tarde'][0]['Fecha_Hora_Trabajo_Inicio'];
                        break;

                    case NOT_SALIDA_TEMPRANA:
                        $horario_inicio                     = $o_listado[$perID] ['Salidas_Temprano'][0]['Hora_Trabajo_Inicio'];
                        $horario_fin                        = $o_listado[$perID] ['Salidas_Temprano'][0]['Hora_Trabajo_Fin'];
                        $fecha_hora_trabajo_inicio          = $o_listado[$perID] ['Salidas_Temprano'][0]['Fecha_Hora_Trabajo_Inicio'];
                        break;
                }

                $fecha_actual       = date("d-m-Y");


                $mail_data['Detalle'][]     = $o_listado[$perID];
                $mail_data['Nombre'][]      = $nombre_completo;
                $mail_data['Sujeto'][]      = $this->getDetalleC().' - '.$nombre_completo . ' - '.$fecha_hora_trabajo_inicio;

                $mail_data['Cuerpo'][]      = $this->getDetalleC().' - '.$nombre_completo. '<br>'. $detalle_horario.' '.$horario_inicio. ' - '.$horario_fin.' - Fecha : '.$fecha_actual. '<br>'. $this->getTextoC();

                if($this->getGrupo() == -3){
                    $mail_data['Email_Id'][] = $persona->getEmail();
                }
            }
        }


       //////printear('$mail_data');
       //////printear($mail_data);

        if (empty($mail_data)){
            return false;
        }

        $mail= new Email_O();
        $mail->setSujeto($this->getDetalleC());
        $mail->setCuerpo($this->getTextoC());
        $mail->setGrupo($this->getGrupo());
        $mail->setDestinatarioBCC($this->getEmailMe());
        $mail->setFrom('enPunto');
        $mail->setFecha(date("Y-m-d H:i:s"),"Y-m-d H:i:s");
        $mail->setEstado(1);

        $destinatario_tipo    = $this->getGrupo() != 0 ? 'Grupo' : ($this->getUsuId() != 0 ? 'Usuario' : 'Persona');



        switch ($destinatario_tipo){

            case 'Grupo':
                $mail->setGrupal(1);
                break;

            case 'Usuario':
                $o_Usuario = Usuario_L::obtenerPorId($this->_usu_id,true);

                if(!is_null($o_Usuario->getBloqueadoEl())) {
                    return false;
                }

                $mail->setDestinatario($o_Usuario->getEmail());
                $mail->setGrupal(0);
                break;

            case 'Persona':
                $o_Persona = Persona_L::obtenerPorId($this->_per_id);

                if($o_Persona->getExcluir()) {
                    return false;
                }

                $mail->setDestinatario($o_Persona->getEmail());
                $mail->setGrupal(0);
                break;
        }

       //////printear('$mail');
       //////printear($mail);

        foreach($mail_data['Sujeto'] as $key => $data){

            unset($mail_data['Detalle'][$key]['Hora_Trabajo_Detalle']);

            switch ($destinatario_tipo){

                case 'Grupo':
                    $mail_data['Detalle'][$key]['recipient_tipo'] = 'grupo';
                    $mail_data['Detalle'][$key]['recipient_id'] = $this->_grupo;
                    break;

                case 'Usuario':
                    $mail_data['Detalle'][$key]['recipient_tipo'] = 'usuario';
                    $mail_data['Detalle'][$key]['recipient_id'] = $this->_usu_id;
                    break;

                case 'Persona':
                    $mail_data['Detalle'][$key]['recipient_tipo'] = 'persona';
                    $mail_data['Detalle'][$key]['recipient_id'] = $this->_per_id;
                    break;
            }


            $a_Email = Email_L::obtenerPorDetalle(json_encode($mail_data['Detalle'][$key]));

           //////printear('1 - $a_Email = Email_L::obtenerPorDetalle');
           //////printear($a_Email);

            if(count($a_Email) == 0){

               //////printear('2 -     if(count($a_Email) == 0) NO HAY IGUALES, ES UNICO,SI ');


                $mail->setTipo(1);
                $mail->setDetalle(json_encode($mail_data['Detalle'][$key]));
                $mail->setSujeto($data);
                $mail->setCuerpo($mail_data['Cuerpo'][$key]);
                $mail->save('Off');
                $mail->setId(0);
            }
            else{
               //////printear('9999 - ya fue enviado ');

            }
        }


        $a_Email_Listado = Email_L::obtenerTodosAEnviar();

       //////printear('3-         $a_Email_Listado , para enviar');
       //////printear($a_Email_Listado);

        if (count($a_Email_Listado)) {

           //////printear('4 - SIII hay para enviar');


            foreach ($a_Email_Listado as $o_Email) {

                // QUITAR COMENTARIO PARA PRODUCCIÓN
                $RESULTADO_ENVIO = $o_Email->enviar();

                $o_Email->setEstado(2);
                $o_Email->setFecha($o_Email->getFecha("Y-m-d H:i:s"), "Y-m-d H:i:s");
                $o_Email->save('Off');

            }
        }
        else{
           //////printear('5 - nooooo nara para enviar');

        }
        return true;

    }

    public function enviarAlerta(){

        /* VARIABLES */
        $reporte_tipo[500]                  = 'Llegadas_Tarde';
        $reporte_tipo[560]                  = 'Salidas_Temprano';
        $reporte_tipo[580]                  = 'Listado_Ausencias';


        $_SESSION['filtro']['intervalo']    = 'F_Hoy';
        $_SESSION['filtro']['tipo']         = $reporte_tipo[$this->getDisparador()];
        $_SESSION['filtro']['persona']      = $this->getPersonaD() == 0 && $this->getRolD() == 0 ? "TodasLasPersonas": $this->getPersonaD();
        $_SESSION['filtro']['persona']      = $this->getRolD() != 0 ? 'SelectRol' : $_SESSION['filtro']['persona'];
        $_SESSION['filtro']['rolF']         = $this->getRolD();

        $a_Ausencias                        = array();
        $a_Llegadas_Tarde                   = array();
        $a_Salidas_Temprano                 = array();


        $disparador_tipo                    = $this->getDisparador();
        $mail_data                          = array();
        $array_personas_a_controlar         = array();

        $disparador_persona                 = $this->getRolD() != 0 ? 'Grupo' : ($this->getPersonaD() != 0 ? 'Persona' : 'TodasLasPersonas');



        /* REPORTE */
        include_once 'codigo/controllers/reportes.php';


        /* DISPARADOR TIPO */
        switch ($disparador_tipo){
            case NOT_AUSENCIA:
                //////printear('ausencia');

                $o_listado  = $a_Ausencias;
                $key_total  = 'Total_No_Justificadas';
                $key_logs   = 'Ausencias';
                break;
            case NOT_LLEGADA_TARDE:
                //////printear('llegada tarde');

                $o_listado = $a_Llegadas_Tarde;
                $key_total = 'Total';
                $key_logs   = 'Llegadas_Tarde';
                break;
            case NOT_SALIDA_TEMPRANA:
                //////printear('salida temprana');

                $o_listado = $a_Salidas_Temprano;
                $key_total = 'Total';
                $key_logs   = 'Salidas_Temprano';
                break;
        }

        //////printear('$o_listado');
        //////printear($o_listado);


        /* DISPARADOR PERSONA
        switch ($disparador_persona){

            case 'Persona':
                $array_personas_a_controlar[$this->getPersonaD()] = Persona_L::obtenerPorId($this->getPersonaD());
                break;

            case 'Grupo':
                $Grupos_Personas = Grupos_Personas_L::obtenerARRAYPorGrupo($this->getRolD());
                foreach($Grupos_Personas as $perId){
                    $array_personas_a_controlar[$perId] = Persona_L::obtenerPorId($perId);
                }
                break;

            case 'TodasLasPersonas':
                $array_personas_a_controlar = Persona_L::obtenerTodos('per_Eliminada = 0 and per_Excluir=0');
                break;
        }*/

        //////printear('$array_personas_a_controlar');
        //////printear($array_personas_a_controlar);
        /* MAIL DATA */
        foreach($array_personas_a_controlar as $perID => $persona){

            /* VARIABLES */
            $total_eventos = $o_listado[$perID][$key_total];


            //////printear('$total_eventos');
            //////printear($total_eventos);

            if ($total_eventos > 0) {

                $nombre_completo    = $o_listado[$perID]['per_Apellido'].", ".$o_listado[$perID]['per_Nombre'];
                $detalle_horario    = $o_listado[$perID]['Hora_Trabajo_Detalle'];
                $horario_inicio     = $o_listado[$perID]['Hora_Trabajo_Inicio'];
                $horario_fin        = $o_listado[$perID]['Hora_Trabajo_Fin'];
                $fecha_actual       = date("d-m-Y");


                $mail_data['Detalle'][]     = $o_listado[$perID];
                $mail_data['Nombre'][]      = $nombre_completo;
                $mail_data['Sujeto'][]      = $this->getDetalleC().' - '.$nombre_completo;

                $mail_data['Cuerpo'][]      = $this->getDetalleC().' - '.$nombre_completo. '<br>'. $detalle_horario.' '.$horario_inicio. ' - '.$horario_fin.' - Fecha : '.$fecha_actual. '<br>'. $this->getTextoC();

                if($this->getGrupo() == -3){
                    $mail_data['Email_Id'][] = $persona->getEmail();
                }
            }
        }


        //////printear('$mail_data');
        //////printear($mail_data);

        $mail= new Email_O();
        $mail->setSujeto($this->getDetalleC());
        $mail->setCuerpo($this->getTextoC());
        $mail->setGrupo($this->getGrupo());
        $mail->setDestinatarioBCC($this->getEmailMe());
        $mail->setFrom('enPunto');
        $mail->setFecha(date("Y-m-d H:i:s"),"Y-m-d H:i:s");
        $mail->setEstado(1);

        $destinatario_tipo    = $this->getGrupo() != 0 ? 'Grupo' : ($this->getUsuId() != 0 ? 'Usuario' : 'Persona');



        switch ($destinatario_tipo){

            case 'Grupo':
                $mail->setGrupal(1);
                break;

            case 'Usuario':
                $o_Usuario = Usuario_L::obtenerPorId($this->_usu_id,true);

                if(!is_null($o_Usuario->getBloqueadoEl())) {
                    return;
                }

                $mail->setDestinatario($o_Usuario->getEmail());
                $mail->setGrupal(0);
                break;

            case 'Persona':
                $o_Persona = Persona_L::obtenerPorId($this->_per_id);

                if($o_Persona->getExcluir()) {
                    return;
                }

                $mail->setDestinatario($o_Persona->getEmail());
                $mail->setGrupal(0);
                break;
        }

        //////printear('$mail');
        //////printear($mail);

        foreach($mail_data['Sujeto'] as $key => $data){

            unset($mail_data['Detalle'][$key]['Hora_Trabajo_Detalle']);

            switch ($destinatario_tipo){

                case 'Grupo':
                    $mail_data['Detalle'][$key]['recipient_tipo'] = 'grupo';
                    $mail_data['Detalle'][$key]['recipient_id'] = $this->_grupo;
                    break;

                case 'Usuario':
                    $mail_data['Detalle'][$key]['recipient_tipo'] = 'usuario';
                    $mail_data['Detalle'][$key]['recipient_id'] = $this->_usu_id;
                    break;

                case 'Persona':
                    $mail_data['Detalle'][$key]['recipient_tipo'] = 'persona';
                    $mail_data['Detalle'][$key]['recipient_id'] = $this->_per_id;
                    break;
            }


            $a_Email = Email_L::obtenerPorDetalle(json_encode($mail_data['Detalle'][$key]));

           //////printear('1 - $a_Email = Email_L::obtenerPorDetalle');
           //////printear($a_Email);

            if(count($a_Email) == 0){
               //////printear('2 - COUNT ES == 0 !! . -> Es un correo nuevo  nuevo nuevo nuevo nuevo');

                $mail->setTipo(1);
                $mail->setDetalle(json_encode($mail_data['Detalle'][$key]));
                $mail->setSujeto($data);
                $mail->setCuerpo($mail_data['Cuerpo'][$key]);
                $mail->save('Off');
                $mail->setId(0);
            }
            else{
               //////printear('3 - correo enviado anteriormente');
            }
        }

        //////printear('$mail2');
        //////printear($mail);


        $a_Email_Listado = Email_L::obtenerTodosAEnviar();

        //////printear('obtenerTodosAEnviar');
        //////printear($a_Email_Listado);

        if (count($a_Email_Listado)) {

           //////printear('4 - enviando a los siguientes destinatarios:');
           //////printear($a_Email_Listado);


            foreach ($a_Email_Listado as $o_Email) {

                $RESULTADO_ENVIO = $o_Email->enviar();

                //////printear('$RESULTADO_ENVIO');
                //////printear($RESULTADO_ENVIO);



                $o_Email->setEstado(2);
                $o_Email->setFecha($o_Email->getFecha("Y-m-d H:i:s"), "Y-m-d H:i:s");
                $o_Email->save('Off');

            }
        }
        else{
           //////printear('5 - la siguiente lista de destinatarios está vacía:');
           //////printear($a_Email_Listado);
        }

    }


    public function enviar($extraPersona=0,$extraRol=0,$extraEquipo=0,$extraTipo='',$extraFecha='',$extraDispositivo=0,$extraAccion=0,$ausencias = null,$llegadas_Tarde = null,$salidas_Temprano = null){

    }

    public function delete($p_Debug) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($cnn->Delete('notificaciones', "not_Id = {$this->_id}")) {
            return true;
        } else {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
            return false;
        }
    }

}
