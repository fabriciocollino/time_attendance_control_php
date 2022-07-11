<?php

class Notas_O
{

    private $_id;
    private $_personaId;
    private $_body;
    private $_userWriterId;
    private $_creationtDate;
    private $_errores;

    // CONSTRUCTOR
    public function __construct()
    {
        $this->_id              = 0;
        $this->_personaId       = 0;
        $this->_body            = "";
        $this->_userWriterId    = 0;
        $this->_creationtDate   = "";

    }


    // ID
    public function getId()
    {
        return $this->_id;
    }
    public function setId($p_id)
    {
        $p_id = (integer)$p_id;
        $this->_id = $p_id;
    }

    // PERSONA ID
    public function getPersonaId()
    {
        return $this->_personaId;
    }
    public function setPersonaId($p_id)
    {
        $p_id = (integer)$p_id;
        $this->_personaId = $p_id;
    }


    // BODY
    public function getBody($p_max = '')
    {

        $salida = trim($this->_body);

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
    public function setBody($p_body)
    {
        $p_body = (string)$p_body;
        $this->_body = $p_body;
    }


    // USER WRITER ID
    public function getUserWriterId()
    {
        return $this->_userWriterId;
    }
    public function setUserWriterId($p_id)
    {
        $p_id = (integer)$p_id;
        $this->_userWriterId = $p_id;
    }

    // CREATION DATE
    public function getCreationDateTime($p_Format = null)
    {
        if (!is_null($p_Format) && is_string($p_Format)) {
            if (is_int($this->_scheduledDate)) {
                return date($p_Format, $this->_scheduledDate);
            }
            else {
                return $this->_scheduledDate;
            }
        }
        return $this->_creationtDate;
    }
    public function setCreationDate($p_Hora, $p_Format='Y-m-d H:i:s')
    {
        $this->_creationtDate = $this->Fecha($p_Hora, $p_Format, 'fecha creación');
        $this->_creationtDate = $p_Hora;
    }

    
    // SAVE NOTA
    public function save($p_Debug = false)
    {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $datos = array(
            'not_Persona_Id' => $this->_personaId,
            'not_Body' => $this->_body,
            'not_User_Writer_Id' => $this->_userWriterId,
            'not_Creation_Date' => $this->_creationtDate
        );
        $resultado = "";

        if($this->_id == 0){
            $resultado = $cnn->Insert('notas', $datos);

            if ($resultado === true) {
                $this->_id = $cnn->Devolver_Insert_Id();
            }
        }
        else{
            $resultado = $cnn->Update('notas', $datos, "not_Id = {$this->_id}");
        }

        if ($resultado === false) {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
        }

        return $resultado;

    }

    // DELETE NOTA
	public function delete($p_Debug) {
		/* @var $cnn mySQL */
		$cnn = Registry::getInstance()->DbConn;

		if ($cnn->Delete('notas', "not_Id = {$this->_id}")) {
			return true;
		}
		else {
			$this->_errores['mysql'] = $cnn->get_Error($p_Debug);
			return false;
		}
	}



	// EXTRA FUNCTIONS HELPERS
    public function loadArray($p_Datos)
    {
        $this->_id              = (integer)$p_Datos["not_Id"];
        $this->_personaId       = (integer)$p_Datos["not_Persona_Id"];;
        $this->_body            = (string)$p_Datos["not_Body"];
        $this->_userWriterId    = (integer)$p_Datos["not_User_Writer_Id"];
        $this->_creationtDate   = (string)$p_Datos["not_Creation_Date"];
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
    public function getErrores()
    {
        return $this->_errores;

    }


}
