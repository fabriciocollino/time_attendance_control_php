<?php

class Planning_O
{

    private $_id;
    private $_event;

    private $_fecha_mod;
    private $_errores;

    public function __construct() {
        $this->_id = 0;
        $this->_event = ''; //varchar(50)

        $this->_errores = array();
    }

    public function getId() {
        return $this->_id;
    }

    public function getEvent() {
        return $this->_event;
    }

    public function setEvent($p_event) {
        $p_event = trim($p_event);
        $this->_event = $p_event;
    }

    public function esValido() {
        $this->_errores = array();

        //Validaciones del detalle
        if ($this->_event == '') {
            $this->_errores['event'] = _('Debe proporcionar el event.');
        }

        //Si el array errores no tiene elementos entonces el objeto es valido.
        return count($this->_errores) == 0;
    }

    public function getErrores() {
        return $this->_errores;
    }

    public function loadArray($p_Datos) {

        $this->_id = (integer)$p_Datos["plan_Id"];
        $this->_event = (string)$p_Datos["plan_Event"];
    }



    public function save($p_Debug = 'Off') {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if (!$this->esValido()) {
            return false;
        }

        $this->_fecha_mod = date("Y-m-d H:i:s");

        $datos = array(
            'plan_Event' => $this->_event,
            'plan_Fecha_Mod' => $this->_fecha_mod
        );

        if ($this->_id == 0) {
            $resultado = $cnn->Insert('planning', $datos, true);
            if ($resultado !== false) {
                $this->_id = $cnn->Devolver_Insert_Id();
            }
        } else {
            $resultado = $cnn->Update('planning', $datos, "plan_Id = {$this->_id}");
        }

        if ($resultado === false) {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
        }

        return $resultado;
    }

    public function delete($p_Debug = 'Off') {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($cnn->Delete('planning', "plan_Id = {$this->_id}")) {
            return true;
        } else {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
            return false;
        }
    }

}
