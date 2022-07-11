<?php

class Mensaje_O
{

    private $_id;
    private $_eq_id;
    private $_per_id;
    private $_gru_id;
    private $_tipo;
    private $_usu_id;
    private $_titulo;
    private $_mensaje;
    private $_disparador;
    private $_disparador_tipo;
    private $_visto;
    private $_status;
    private $_fecha;
    private $_disparador_hota;
    private $_fecha_visto;
    private $_errores;

    //tipos
    //0 message
    //1 warning
    //2 error

    public function __construct($p_eq_id = 0, $p_tipo = 0, $p_titulo = '', $p_mensaje = '', $p_visto = 0)
    {
        $this->_id = 0;
        $this->_eq_id = $p_eq_id;
        $this->_per_id = 0;
        $this->_gru_id = 0;
        $this->_usu_id = 0;
        $this->_tipo = $p_tipo;
        $this->_titulo = $p_titulo;
        $this->_mensaje = $p_mensaje;
        $this->_disparador = 0 ;
        $this->_disparador_tipo = 0 ;
        $this->_visto = $p_visto;
        $this->_status = 0;
        $this->_fecha = '';
        $this->_disparador_hota = '';
        $this->_fecha_visto = '';
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setId($p_id)
    {
        $p_id = (integer)$p_id;
        $this->_id = $p_id;
    }

    public function getEqId()
    {
        return $this->_eq_id;
    }

    public function setEqId($p_eq_id)
    {
        $p_eq_id = (integer)$p_eq_id;
        $this->_eq_id = $p_eq_id;
    }

    public function getPerId()
    {
        return $this->_per_id;
    }

    public function setPerId($p_per_id)
    {
        $p_per_id = (integer)$p_per_id;
        $this->_per_id = $p_per_id;
    }

    public function getGruId()
    {
        return $this->_gru_id;
    }

    public function setGruId($p_gru_id)
    {
        $p_gru_id = (integer)$p_gru_id;
        $this->_gru_id = $p_gru_id;
    }

    public function getUsuId()
    {
        return $this->_usu_id;
    }

    public function setUsuId($_usu_id)
    {
        $_usu_id = (integer)$_usu_id;
        $this->_usu_id = $_usu_id;
    }

    public function getTipo()
    {
        return $this->_tipo;
    }

    public function getTipoDetalle()
    {
        $salida = '';
        switch ($this->_tipo) {
            case 0:
                $salida = 'Mensaje';
                break;
            case 1:
                $salida = 'Email';
                break;
        }
        return $salida;
    }

    public function getDestinatarios(){
        if($this->_gru_id != 0){
            $o_Grupo = Grupo_L::obtenerPorId($this->_gru_id);
            if(!is_null($o_Grupo)){
                $_g_Detalle = mb_convert_case($o_Grupo->getDetalle(), MB_CASE_TITLE, "UTF-8");
                return    '<b>Grupo:</b> ' . htmlentities($_g_Detalle, ENT_QUOTES, 'utf-8');
            }
            else{
                return    '<b>Grupo:</b> ';
            }
        }else if($this->_per_id != 0){
            $persona = Persona_L::obtenerPorId($this->_per_id);
            return '<b>Persona : </b>'.$persona->getNombreCompleto();
        }else if($this->_usu_id != 0){
            $o_Usuario = Usuario_L::obtenerPorId($this->_usu_id);
            return '<b>Usuario : </b>'.$o_Usuario->getNombreCompleto();
        }
    }

    public function setTipo($p_tipo)
    {
        $p_tipo = (integer)$p_tipo;
        $this->_tipo = $p_tipo;
    }

    public function getMensaje($p_max = '')
    {

        $salida = trim($this->_mensaje);

        $append = "&hellip;";

        if ($p_max != '') {
            $p_max = (integer)$p_max;
            if (strlen($salida) > $p_max) {
                $salida = wordwrap($salida, $p_max);
                $salida = explode("\n", $salida);
                $salida = array_shift($salida) . $append;
            }
        }


        return $salida;
    }

    public function setMensaje($p_mensaje)
    {
        $p_mensaje = (string)$p_mensaje;
        $this->_mensaje = $p_mensaje;
    }

    public function getTitulo($p_max = '')
    {
        $salida = trim($this->_titulo);

        $append = "&hellip;";

        if ($p_max != '') {
            $p_max = (integer)$p_max;
            if (strlen($salida) > $p_max) {
                $salida = wordwrap($salida, $p_max);
                $salida = explode("\n", $salida);
                $salida = array_shift($salida) . $append;
            }
        }
        return $salida;
    }

    public function setTitulo($p_titulo)
    {
        $p_titulo = (string)$p_titulo;
        $this->_titulo = $p_titulo;
    }

    public function getDisparador()
    {
        return $this->_disparador;
    }

    public function setDisparador($p_disparador)
    {
        $p_disparador = (integer)$p_disparador;
        $this->_disparador = $p_disparador;
    }

    public function getDisparadorTipo()
    {
        return $this->_disparador_tipo;
    }

    public function getDisparadorTipoDetalle()
    {
        $salida = '';
        switch ($this->_disparador_tipo) {
            case 0:
                $salida = 'Ahora';
                break;
            case 1:
                $salida = 'Programado';
                break;
        }
        return $salida;
    }

    public function setDisparadorTipo($p_disparador_tipo)
    {
        $p_disparador_tipo = (integer)$p_disparador_tipo;
        $this->_disparador_tipo = $p_disparador_tipo;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function setStatus($p_status)
    {
        $p_status = (integer)$p_status;
        $this->_status = $p_status;
    }

    public function getVisto()
    {
        return $this->_visto;
    }

    public function setVisto($p_visto)
    {
        $p_visto = (integer)$p_visto;
        $this->_visto = $p_visto;
    }

    public function Fecha($p_Fecha, $p_Format, $p_texto)
    {
        $_fecha_hora = DateTimeHelper::getTimestampFromFormat($p_Fecha, $p_Format);
        if ($_fecha_hora === false) {
            $this->_errores[ValidateHelper::Cadena($p_texto)] = _("La") . " {$p_texto} " . _("es incorrecta.");
            return $p_Fecha;
        }
        return $_fecha_hora;
    }


    public function getDisparadorHora($p_Format = null)
    {
        if (!is_null($p_Format) && is_string($p_Format)) {
            if (is_int($this->_disparador_hota)) {
                return date($p_Format, $this->_disparador_hota);
            } else {
                return $this->_disparador_hota;
            }
        }
        return $this->_disparador_hota;
    }

    public function setDisparadorHora($p_Hora, $p_Format='Y-m-d H:i:s', $p_Ignore = false)
    {
        if (!$p_Ignore) {
            $this->_disparador_hota = $this->Fecha($p_Hora, $p_Format, 'Fecha Creacion');
            $this->_disparador_hota = $p_Hora;
        } else {
            $this->_disparador_hota = 0;
        }
    }


    public function getFecha($p_Format = null)
    {
        if (!is_null($p_Format) && is_string($p_Format)) {
            if (is_int($this->_fecha)) {
                return date($p_Format, $this->_fecha);
            } else {
                return $this->_fecha;
            }
        }
        return $this->_fecha;
    }

    public function setFecha($p_Hora, $p_Format, $p_Ignore = false)
    {
        if (!$p_Ignore) {
            $this->_fecha = $this->Fecha($p_Hora, $p_Format, 'Fecha Creacion');
            $this->_fecha = $p_Hora;
        } else {
            $this->_fecha = 0;
        }
    }


    public function getFechaFormatoGmail()
    {
        $fecha=strtotime($this->_fecha);
        if (date('Ymd') == date('Ymd',$fecha )) {//si es de hoy
            return date('H:i a', $fecha);
        } else {
            // return date('M t', $fecha);
            return date('Y-m-d', $fecha);
        }
    }


    public function getFechaVisto($p_Format = null)
    {
        if (!is_null($p_Format) && is_string($p_Format)) {
            if (is_int($this->_fecha_visto)) {
                return date($p_Format, $this->_fecha_visto);
            } else {
                return $this->_fecha_visto;
            }
        }
        return $this->_fecha_visto;
    }

    public function setFechaVisto($p_Hora, $p_Format, $p_Ignore = false)
    {
        if (!$p_Ignore) {
            $this->_fecha_visto = $this->Fecha($p_Hora, $p_Format, 'Fecha Visto');
            $this->_fecha_visto = $p_Hora;
        } else {
            $this->_fecha_visto = 0;
        }
    }


    public function loadArray($p_Datos)
    {
        $this->_id = (integer)$p_Datos["men_Id"];
        $this->_eq_id = (integer)$p_Datos["men_Eq_Id"];
        $this->_per_id = (integer)$p_Datos["men_Per_Id"];
        $this->_gru_id = (integer)$p_Datos["men_Gru_Id"];
        $this->_usu_id = (integer)$p_Datos["men_Usu_Id"];
        $this->_tipo = (integer)$p_Datos["men_Tipo"];
        $this->_mensaje = (string)$p_Datos["men_Mensaje"];
        $this->_titulo = (string)$p_Datos["men_Titulo"];
        $this->_disparador = (integer)$p_Datos["men_Disparador"];
        $this->_disparador_tipo = (integer)$p_Datos["men_Disparador_Tipo"];
        $this->_visto = (integer)$p_Datos["men_Visto"];
        $this->_status = (integer)$p_Datos["men_Status"];
        $this->_fecha = (string)$p_Datos["men_Fecha"];
        $this->_disparador_hota = (string)$p_Datos["men_DisparadoHora"];
        $this->_fecha_visto = (string)$p_Datos["men_Fecha_Visto"];
    }

    public function save($p_Debug , $flag = false)
    {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $datos = array(
            'men_Id' => $this->_id,
            'men_Eq_Id' => $this->_eq_id,
            'men_Per_Id' => $this->_per_id,
            'men_Gru_Id' => $this->_gru_id,
            'men_Usu_Id' => $this->_usu_id,
            'men_Tipo' => $this->_tipo,
            'men_Mensaje' => $this->_mensaje,
            'men_Titulo' => $this->_titulo,
            'men_Disparador' => $this->_disparador,
            'men_Disparador_Tipo' => $this->_disparador_tipo,
            'men_DisparadoHora' => $this->_disparador_hota,
            'men_Visto' => $this->_visto,
            'men_Status' => $this->_status,
            'men_Fecha' => $this->_fecha,
            'men_Fecha_Visto' => $this->_fecha_visto
        );
        /*
        if ($this->_id == 0)
            $datos['men_Fecha'] = date('Y-m-d H:i:s');

        if ($this->_id == 0) {
            $resultado = $cnn->Insert('mensajes', $datos);
            if ($resultado !== false) {
                $this->_id = $cnn->Devolver_Insert_Id();
            }
        } else {
            $resultado = $cnn->Update('mensajes', $datos, "men_Id = {$this->_id}");
        }
        */

        // abduls
        // meaning : $flag == true update  o $flag == false insert
        if ($flag === false) {
            $datos['men_Fecha'] = date('Y-m-d H:i:s');
        }
        if ($flag === false) {
            $resultado = $cnn->Insert('mensajes', $datos);
            if ($resultado === true) {
                $this->_id = $cnn->Devolver_Insert_Id();
            }
        }else{
            $resultado = $cnn->Update('mensajes', $datos, "men_Id = {$this->_id}");
        }
        if ($resultado === false) {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
        }
        // abduls

        return $resultado;
    }

    public function delete($p_Debug) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($cnn->Delete('mensajes', "men_Id = {$this->_id}")) {
            return true;
        } else {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
            return false;
        }
    }
}
