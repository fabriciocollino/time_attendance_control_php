<?php

/**
 *
 *
 *
 *
 */
class Horario_Flexible_O
{

    private $_id;
    private $_detalle;
    private $_horarios;
    private $_fecha_mod;
    private $_a_horarios;
    private $_eliminado;
    private $_empresa;


    public function __construct() {
        $this->_id = 0;
        $this->_detalle = ''; // varchar(255)
        $this->_horarios = '';
        $this->_fecha_mod = 0;
        $this->_a_horarios = array();
        $this->_eliminado = 0;
        $this->_empresa = 1;

        $this->_errores = array();
    }

    public function getId() {
        return $this->_id;
    }

    public function getDetalle() {
        return $this->_detalle;
    }

    public function getDetalleHorarios() {
        $horarios = json_decode($this->_horarios, true);
        $salida = '';

        if (!is_null($horarios) && $horarios != '') {

            foreach ($horarios as $secuencia) {
                $salida .= Hora_Trabajo_L::obtenerPorId($secuencia['horario_id'])->getDetalle() . '<br/>';
            }
        }

        return $salida;
    }

    public function setDetalle($p_Detalle) {
        $p_Detalle = trim($p_Detalle);
        $this->_detalle = $p_Detalle;

        $o_Detalle_Existente = Horario_Rotativo_L::obtenerPorDetalle($this->_detalle, $this->_id);
        if ($this->_detalle == '') {
            $this->_errores['detalle'] = _('Debe proporcionar la descripción del horario de trabajo.');
        } elseif (strlen($this->_detalle) < 4) {
            $this->_errores['detalle'] = _('La descripción del horario de trabajo es demasiado corta.');
        } elseif (strlen($this->_detalle) > 255) {
            $this->_errores['detalle'] = _('La descripción del horario de trabajo no debe superar los 255 caracteres.');
        } elseif (!is_null($o_Detalle_Existente)) {
            $this->_errores['detalle'] = _('La descripción') . ' \'' . $this->_detalle . '\' ' . _('ya existe.');
        }
    }


    public function getHorarios() {
        return $this->_horarios;
    }

    public function setHorarios($p_Horarios) {
        $p_Horarios = trim($p_Horarios);
        $this->_horarios = $p_Horarios;
    }


    public function getArrayDias($desde_inicio = false, $hora_del_log = '') { // abduls

        $horarios = json_decode($this->_horarios, true);

        $salida = array();

        if (!is_null($horarios) && $horarios != '') {

            foreach ($horarios as $secuencia) {
                $horario = $this->_a_horarios[$secuencia['horario_id']];
                $salida[] = $horario->getArrayDias();
            }
        }

        return $salida;
    }

    public function getArrayDiasQueTrabaja() {

        $horarios = json_decode($this->_horarios, true);

        $salida = array();

        if (!is_null($horarios) && $horarios != '') {

            foreach ($horarios as $secuencia) {
                $horario = $this->_a_horarios[$secuencia['horario_id']];
                $salida[] = $horario->getArrayDiasQueTrabaja();
            }
        }

        return $salida;
    }

    public function getArrayDiasString() {

        $horarios = json_decode($this->_horarios, true);

        $salida = array();

        if (!is_null($horarios) && $horarios != '') {

            foreach ($horarios as $secuencia) {
                $horario = $this->_a_horarios[$secuencia['horario_id']];
                $salida[] = $horario->getArrayDiasString();
            }
        }

        return $salida;
    }

    public function getTextoDiasResumido($dias_red) {
        $texto = '';

        foreach($this->_a_horarios as $horario) {
            $texto.=$horario->getTextoDiasResumido($dias_red);
        }
        return $texto;
    }

    public function obtenerHorariosPorDia($t_fecha){

        /* VARIABLES */
        $horarios                   = json_decode($this->_horarios, true);
        $dia_semana                 = date('w', strtotime($t_fecha));
        $dia                        = date('Y-m-d', strtotime($t_fecha));

        /* MAÑANA */
        $dia_posterior_time         = strtotime($dia.' +1 day');
        $dia_posterior              = date('Y-m-d', $dia_posterior_time);

        /* SIN HORARIO */
        if (is_null($horarios) || $horarios == '') {
            return null;
        }

        /* VARIABLES HORARIOS */
        $array_horarios      = array();


        foreach ($horarios as $horarioID => $horario) {

            $secuencia     = $this->_a_horarios[$horario['horario_id']]->getArrayDiasString();


            /* HORARIO HOY NOCTURNO */
            if (strtotime($secuencia[$dia_semana][0]) > strtotime($secuencia[$dia_semana][1])){
                $array_horarios[$horarioID][0]          = $dia." ".$secuencia[$dia_semana][0];
                $array_horarios[$horarioID][1]          = $dia_posterior." ".$secuencia[$dia_semana][1] ;
            }
            /* HORARIO HOY NORMAL */
            else{
                $array_horarios[$horarioID][0]          = $dia." ".$secuencia[$dia_semana][0];
                $array_horarios[$horarioID][1]          = $dia." ".$secuencia[$dia_semana][1] ;
            }
        }

        return $array_horarios;

    }


    public function obtenerHorarioCercano($dia_hora) {

        /* HORARIOS */
        $horarios   = json_decode($this->_horarios, true);

        /* SIN HORARIO */
        if (is_null($horarios) || $horarios == '') {
            return null;
        }

        /* DIA */
        $dia_semana                 = date('w',strtotime($dia_hora));

        /* HOY */
        $dia_hora_hoy_time          = strtotime($dia_hora);
        $dia                        = date('Y-m-d', $dia_hora_hoy_time);


        /* MAÑANA */
        $dia_posterior_time         = strtotime($dia.' +1 day');
        $dia_posterior              = date('Y-m-d', $dia_posterior_time);

        /* HORARIO SELECCIONADO */
        $dia_hora_horario   = array();

        /* HORARIOS INICIO */
        $array_horarios_inicio      = array();

        /* HORARIOS FIN */
        $array_horarios_fin         = array();


        /* AYER */
        $dia_ayer_time                  = strtotime($dia.' -1 day');
        $dia_ayer                       = date('Y-m-d', $dia_ayer_time);
        $dia_ayer_semana                = date('w',strtotime($dia_hora));
        $array_horarios_ayer_inicio     = array();
        $array_horarios_ayer_fin        = array();


        /* ARRAY HORARIOS INICIO - ARRAY HORARIOS FIN */
        foreach ($horarios as $horarioID => $horario) {

            $secuencia     = $this->_a_horarios[$horario['horario_id']]->getArrayDiasString();


            /* HORARIO HOY NOCTURNO */
            if (strtotime($secuencia[$dia_semana][0]) > strtotime($secuencia[$dia_semana][1])){
                $array_horarios_inicio[$horarioID]      = $dia." ".$secuencia[$dia_semana][0] ;
                $array_horarios_fin[$horarioID]         = $dia_posterior." ".$secuencia[$dia_semana][1] ;
            }
            /* HORARIO HOY NORMAL */
            else{
                $array_horarios_inicio[$horarioID]      = $dia." ".$secuencia[$dia_semana][0] ;
                $array_horarios_fin[$horarioID]         = $dia." ".$secuencia[$dia_semana][1] ;
            }



            /* HORARIO AYER NOCTURNO */
            if (strtotime($secuencia[$dia_ayer_semana][0]) > strtotime($secuencia[$dia_ayer_semana][1])){
                $array_horarios_ayer_inicio[$horarioID]      = $dia_ayer." ".$secuencia[$dia_ayer_semana][0] ;
                $array_horarios_ayer_fin[$horarioID]         = $dia." ".$secuencia[$dia_ayer_semana][1] ;
            }
            /* HORARIO AYER NORMAL */
            else{
                $array_horarios_ayer_inicio[$horarioID]      = $dia_ayer." ".$secuencia[$dia_ayer_semana][0] ;
                $array_horarios_ayer_fin[$horarioID]         = $dia_ayer." ".$secuencia[$dia_ayer_semana][1] ;
            }



        }


        /* HORARIO INICIO HOY CERCANO  */
        $horario_inicio_cercano_time        = strtotime($array_horarios_inicio[0]);
        $diff_horario_inicio_cercano        = abs(($dia_hora_hoy_time - $horario_inicio_cercano_time) / 60);
        $index_horario_inicio_cercano       = 0;

        foreach ($array_horarios_inicio as $index => $horario) {

            $dia_hora_horario_time          = strtotime($horario);
            $diff_horario                   = abs(($dia_hora_hoy_time - $dia_hora_horario_time) / 60);

            if($diff_horario_inicio_cercano > $diff_horario){
                $diff_horario_inicio_cercano    = $diff_horario;
                $index_horario_inicio_cercano   = $index;
            }

        }
        /* HORARIO FIN HOY CERCANO  */
        $horario_fin_cercano_time        = strtotime($array_horarios_fin[0]);
        $diff_horario_fin_cercano        = abs(($dia_hora_hoy_time - $horario_fin_cercano_time) / 60);
        $index_horario_fin_cercano       = 0;

        foreach ($array_horarios_fin as $index => $horario) {

            $dia_hora_horario_time          = strtotime($horario);
            $diff_horario                   = abs(($dia_hora_hoy_time - $dia_hora_horario_time) / 60);

            if($diff_horario_fin_cercano > $diff_horario){
                $diff_horario_fin_cercano    = $diff_horario;
                $index_horario_fin_cercano   = $index;
            }

        }


        /* HORARIO INICIO AYER CERCANO  */
        $horario_inicio_cercano_time_ayer        = strtotime($array_horarios_ayer_inicio[0]);
        $diff_horario_inicio_cercano_ayer        = abs(($dia_hora_hoy_time - $horario_inicio_cercano_time_ayer) / 60);
        $index_horario_inicio_cercano_ayer       = 0;

        foreach ($array_horarios_ayer_inicio as $index => $horario) {

            $dia_hora_horario_time          = strtotime($horario);
            $diff_horario                   = abs(($dia_hora_hoy_time - $dia_hora_horario_time) / 60);

            if($diff_horario_inicio_cercano_ayer > $diff_horario){
                $diff_horario_inicio_cercano_ayer    = $diff_horario;
                $index_horario_inicio_cercano_ayer   = $index;
            }

        }
        /* HORARIO FIN AYER CERCANO  */
        $horario_fin_cercano_time_ayer        = strtotime($array_horarios_ayer_fin[0]);
        $diff_horario_fin_cercano_ayer        = abs(($dia_hora_hoy_time - $horario_fin_cercano_time_ayer) / 60);
        $index_horario_fin_cercano_ayer       = 0;
        foreach ($array_horarios_ayer_fin as $index => $horario) {

            $dia_hora_horario_time          = strtotime($horario);
            $diff_horario                   = abs(($dia_hora_hoy_time - $dia_hora_horario_time) / 60);

            if($diff_horario_fin_cercano_ayer > $diff_horario){
                $diff_horario_fin_cercano_ayer    = $diff_horario;
                $index_horario_fin_cercano_ayer   = $index;
            }

        }
        //printear("Diff horarios");
        //printear($diff_horario_inicio_cercano);
        //printear($diff_horario_fin_cercano);
        //printear($diff_horario_inicio_cercano_ayer);
        //printear($diff_horario_fin_cercano_ayer);



        /* HORARIO DE INICIO MAS CERCANO */
        if($diff_horario_inicio_cercano <= $diff_horario_fin_cercano && $diff_horario_inicio_cercano <= $diff_horario_inicio_cercano_ayer &&  $diff_horario_inicio_cercano <= $diff_horario_fin_cercano_ayer){
            //printear(" lowest inicio_cercano_hoy");
            $dia_hora_horario[0]    = $array_horarios_inicio[$index_horario_inicio_cercano];
            $dia_hora_horario[1]    = $array_horarios_fin[$index_horario_inicio_cercano];
            $dia_hora_horario[2]    = $diff_horario_inicio_cercano;
            //printear("Diff 1");
            //printear($dia_hora_horario);
            return $dia_hora_horario;
        }

        if($diff_horario_fin_cercano <= $diff_horario_inicio_cercano && $diff_horario_fin_cercano <=$diff_horario_inicio_cercano_ayer &&  $diff_horario_fin_cercano <=$diff_horario_fin_cercano_ayer){
            //printear(" lowest fin_cercano_hoy");
            $dia_hora_horario[0]    = $array_horarios_inicio[$index_horario_fin_cercano];
            $dia_hora_horario[1]    = $array_horarios_fin[$index_horario_fin_cercano];
            $dia_hora_horario[2]    = $diff_horario_fin_cercano;
            //printear("Diff 2");
            //printear($dia_hora_horario);
            return $dia_hora_horario;
        }

        if($diff_horario_inicio_cercano_ayer <= $diff_horario_inicio_cercano && $diff_horario_inicio_cercano_ayer <= $diff_horario_fin_cercano &&  $diff_horario_inicio_cercano_ayer <= $diff_horario_fin_cercano_ayer){
            //printear(" lowest inicio_cercano_ayer");
            $dia_hora_horario[0]    = $array_horarios_ayer_inicio[$index_horario_inicio_cercano_ayer];
            $dia_hora_horario[1]    = $array_horarios_ayer_fin[$index_horario_inicio_cercano_ayer];
            $dia_hora_horario[2]    = $diff_horario_inicio_cercano_ayer;
            //printear("Diff 3");
            //printear($dia_hora_horario);
            return $dia_hora_horario;
        }

        if($diff_horario_fin_cercano_ayer <= $diff_horario_inicio_cercano && $diff_horario_fin_cercano_ayer <=$diff_horario_fin_cercano &&  $diff_horario_fin_cercano_ayer <=$diff_horario_inicio_cercano_ayer){
            //printear(" lowest fin_cercano_ayer");
            $dia_hora_horario[0]    = $array_horarios_ayer_inicio[$index_horario_fin_cercano_ayer];
            $dia_hora_horario[1]    = $array_horarios_ayer_fin[$index_horario_fin_cercano_ayer];
            $dia_hora_horario[2]    = $diff_horario_fin_cercano_ayer;
            //printear("Diff 4");
            //printear($dia_hora_horario);
            return $dia_hora_horario;
        }


    }


    public function getHorarioByClosestTime($p_Time, $preferenciaFin = false) {

        $horarios   = json_decode($this->_horarios, true);
        $dia_n      = date('w', strtotime($p_Time));
        $hora       = date('H:i:s', strtotime($p_Time));

        if (!is_null($horarios) && $horarios != '') {

            $array_horarios = array();
            $array_horarios_fin = array();

            foreach ($horarios as $secuencia) {
                $horario = $this->_a_horarios[$secuencia['horario_id']]->getArrayDiasString();
                $array_horarios[] = $horario[$dia_n][0];  //0 es el horario de inicio
                $array_horarios_fin[] = $horario[$dia_n][1];  //1 es el horario de fin
            }
            $fin = $array_horarios_fin[0];
            $inicio = $array_horarios[0];
            $diferencia = 28800; //8hs de diferencia minima

            //aca tengo un array de todos los horarios de inicio del dia de la semana w
            if(!$preferenciaFin) {

                $closest = $inicio;

                foreach ($array_horarios as $index => $horario) {

                    $dif = abs(DateTimeHelper::time_to_sec($horario) - DateTimeHelper::time_to_sec($hora));

                    if ($dif < $diferencia) {
                        $closest = $horario;
                        $diferencia = $dif;
                        $fin = $array_horarios_fin[$index];
                    }
                }
                return array($closest, $fin);
            }

            //busco entre los horarios de fin
            else{
                $closest = $fin;

                foreach ($array_horarios_fin as $index => $horario) {

                    $dif = abs(DateTimeHelper::time_to_sec($horario) - DateTimeHelper::time_to_sec($hora));

                    if (intval($dif) < $diferencia) {
                        $closest = $horario;
                        $diferencia = $dif;
                        $inicio = $array_horarios[$index];
                    }
                }
                return array($inicio, $closest);
            }

        }

        return '';
    }



    public function getFechaMod($p_Format = null) {
        if (!is_null($p_Format) && is_string($p_Format)) {
            if (is_int($this->_fecha_mod)) {
                if ($this->_fecha_mod == 0) return '';
                return date($p_Format, $this->_fecha_mod);
            } else {
                return $this->_fecha_mod;
            }
        }
        return $this->_fecha_mod;
    }

    public function setFechaModFormat($p_Fecha, $p_Format) {
        $_fecha_hora = DateTimeHelper::getTimestampFromFormat($p_Fecha, $p_Format);
        if ($_fecha_hora === false) {
            $this->_errores['fecha_mod'] = 'La fecha de modificación tiene un formato incorrecto';
        } else {
            $this->_fecha_mod = $_fecha_hora;
        }
    }

    public function setFechaMod($p_Timestamp) {
        $this->_fecha_mod = (integer)$p_Timestamp;
    }

    /**
     * Devuelve TRUE/FALSE dependiendo de si el objeto es valido o no.
     *
     * @return boolean
     */
    public function esValido() {
        //$this->_errores = array();
        //Si el array errores no tiene elementos entonces el objeto es valido.


        return count($this->_errores) == 0;
    }

    public function getErrores() {
        return $this->_errores;
    }

    public function loadArray($p_Datos) {

        $this->_id = (integer)$p_Datos["hfle_Id"];
        $this->_detalle = (string)$p_Datos["hfle_Detalle"];
        $this->_horarios = (string)$p_Datos["hfle_Horarios"];
        $this->_fecha_mod = (string) $p_Datos["hfle_Fecha_Mod"];

        //aca hago un preload de todos los horarios de la secuencia, para no tener que estar buscandolos en la BD cada vez que hago un getHorariobyclosestime
        $horarios = json_decode($this->_horarios, true);
        if (!is_null($horarios) && $horarios != '') {
            foreach ($horarios as $secuencia) {
                $horario = Hora_Trabajo_L::obtenerPorId($secuencia['horario_id']);
                $this->_a_horarios[$horario->getId()] = $horario;
            }
        }

    }

    public function save($p_Debug) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if (!$this->esValido()) {
            return false;
        }

        $this->_fecha_mod = date("Y-m-d H:i:s");

        $datos = array(
            'hfle_Detalle' => $this->_detalle,
            'hfle_Horarios' => $this->_horarios,
            'hfle_Fecha_Mod' => $this->_fecha_mod
        );

        if ($this->_id == 0) {
            $resultado = $cnn->Insert('horarios_flexibles', $datos);
            if ($resultado !== false) {
                $this->_id = $cnn->Devolver_Insert_Id();
            }
        } else {
            $resultado = $cnn->Update('horarios_flexibles', $datos, "hfle_Id = {$this->_id}");
        }

        if ($resultado === false) {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
        }

        return $resultado;
    }

    public function delete($p_Debug) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($this->_id == 0) {
            return false;
        }
        $resultado = '';

        $cantidad_personas = Persona_L::obtenerPorHorariodeTrabajoCOUNT($this->_id, HORARIO_FLEXIBLE);
        if ($cantidad_personas == 0) {
            // elimino el registo de un hora_trabajo
            $resultado = $cnn->Delete('horarios_flexibles', "hfle_Id = " . $this->_id);
        } else {
            $this->_errores['mysql'] = _('Hay una o más personas asignadas a este horario de trabajo, no se puede borrar hasta que todas las personas sean reasignadas.');
            return false;
        }

        if ($resultado === false) {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
            return false;
        }
        return true;
    }


    /**
     * Devuelve un array con algunos datos del horario
     * Se utiliza en la API
     *
     * @return array()
     */
    public function toArray() {
        $array = array();
        $array['id'] = $this->_id;
        $array['nombre'] = $this->_detalle;
        $array['horarios'] = $this->_horarios;

        return $array;
    }

    /**
     * Actualiza algunos datos del horario desde un array
     * Se utiliza en la API
     *
     * @param $p_Datos array()
     *
     * @return array()
     */
    public function fromArray($p_Datos) {

        if(array_key_exists('nombre',$p_Datos))$this::setDetalle((string)$p_Datos["nombre"]);
        if(array_key_exists('horarios',$p_Datos))$this::setHorarios((string)$p_Datos["horarios"]);

    }

    /**
     * Devuelve un array con los datos del horario para sync
     * Se utiliza en el proceso de sincronizacion
     *
     * @return array()
     */
    public function toSyncArray() {
        $array = array();

        $array['hfle_Id'] = $this->_id;
        $array['hfle_Detalle'] = $this->_detalle;
        $array['hfle_Horarios'] = $this->_horarios;
        $array['hfle_Eliminado'] = $this->_eliminado;


        return $array;
    }

    /**
     * Actualiza los datos del horario desde un array
     * Se utiliza en el proceso de sincronizacion
     *
     * @param $p_Datos array()
     *
     * @return array()
     */
    public function fromSyncArray($p_Datos) {


        if(array_key_exists('hfle_Detalle',$p_Datos))$this::setDetalle((string)$p_Datos["hfle_Detalle"]);
        if(array_key_exists('hfle_Horarios',$p_Datos))$this::setHorarios((string)$p_Datos["hfle_Horarios"]);

    }

}
