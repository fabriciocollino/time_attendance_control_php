<?php

class Message_O
{

    private $_id;
    private $_chainedId;

    private $_subject;
    private $_body;

    private $_senderId;
    private $_receiverId;

    private $_stateSeen;
    private $_stateSent;

    private $_isDraft;
    private $_isScheduled;

    private $_scheduledDate;
    private $_sentDate;

    private $_creationtDate;
    private $_seenDate;

    private $_errores;


    // CONSTRUCTOR
    public function __construct()
    {
        $this->_id              = 0;
        $this->_chainedId       = 0;

        $this->_subject         = "";
        $this->_body            = "";

        $this->_senderId        = 0;
        $this->_receiverId      = 0;

        $this->_stateSeen       = null;
        $this->_stateSent       = null;

        $this->_isDraft         = 0;
        $this->_isScheduled     = 0;

        $this->_scheduledDate   = "";
        $this->_sentDate        = "";

        $this->_creationtDate   = "";
        $this->_seenDate        = "";
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
    // CHAINED ID
    public function getChainedId()
    {
        return $this->_chainedId;
    }
    public function setChainedId($p_id)
    {
        $p_id = (integer)$p_id;
        $this->_chainedId = $p_id;
    }


    // SUBJECT
    public function getSubject($p_max = '')
    {
        $salida = trim($this->_subject);

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
    public function setSubject($p_titulo)
    {
        $p_titulo = (string)$p_titulo;
        $this->_subject = $p_titulo;
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


    // SENDER ID
    public function getSenderId()
    {
        return $this->_senderId;
    }
    public function setSenderId($p_id)
    {
        $p_id = (integer)$p_id;
        $this->_senderId = $p_id;
    }
    // RECEIVER ID
    public function getReceiverId()
    {
        return $this->_receiverId;
    }
    public function setReceiverId($p_id)
    {
        $p_id = (integer)$p_id;
        $this->_receiverId = $p_id;
    }


    // STATE SEEN
    public function getStateSeen()
    {
        return $this->_stateSeen;
    }
    public function setStateSeen($p_stateSeen)
    {
        $p_stateSeen = (integer)$p_stateSeen;
        $this->_stateSeen = $p_stateSeen;
    }
    // STATE SENT
    public function getStateSent()
    {
        return $this->_stateSent;
    }
    public function setStateSent($p_stateSent)
    {
        $p_stateSent = (integer)$p_stateSent;
        $this->_stateSent = $p_stateSent;
    }


    // IS DRAFT
    public function getIsDraft()
    {
        return $this->_isDraft;
    }
    public function setIsDraft($p_isDraft)
    {
        $p_isDraft = (integer)$p_isDraft;
        $this->_isDraft = $p_isDraft;
    }
    // IS SCHEDULED
    public function getIsScheduled()
    {
        return $this->_isScheduled;
    }
    public function setIsScheduled($p_isScheduled)
    {
        $p_isScheduled = (integer)$p_isScheduled;
        $this->_isScheduled = $p_isScheduled;
    }


    // SCHEDULED DATE
    public function getScheduledDate($p_Format = null)
    {
        if (!is_null($p_Format) && is_string($p_Format)) {
            if (is_int($this->_scheduledDate)) {
                return date($p_Format, $this->_scheduledDate);
            }
            else {
                return $this->_scheduledDate;
            }
        }
        return $this->_scheduledDate;
    }
    public function setScheduledDate($p_Date, $p_Format='Y-m-d H:i:s')
    {
        $this->_scheduledDate = $this->Fecha($p_Date, $p_Format, 'fecha programada');
        $this->_scheduledDate = $p_Date;
    }
    // SENT DATE
    public function getSentDateTime($p_Format = null)
    {
        if (!is_null($p_Format) && is_string($p_Format)) {
            if (is_int($this->_sentDate)) {
                return date($p_Format, $this->_sentDate);
            }
            else {
                return $this->_sentDate;
            }
        }
        return $this->_sentDate;
    }
    public function setSentDateTime($p_Date, $p_Format='Y-m-d H:i:s')
    {
        $this->_sentDate = $this->Fecha($p_Date, $p_Format, 'fecha de envío');
        $this->_sentDate = $p_Date;
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
    // CREATION DATE
    public function getSeenDateTime($p_Format = null)
    {
        if (!is_null($p_Format) && is_string($p_Format)) {
            if (is_int($this->_seenDate)) {
                return date($p_Format, $this->_seenDate);
            }
            else {
                return $this->_seenDate;
            }
        }
        return $this->_seenDate;
    }
    public function setseenDateTime($p_Hora, $p_Format='Y-m-d H:i:s')
    {
        $this->_seenDate = $this->Fecha($p_Hora, $p_Format, 'fecha creación');
        $this->_seenDate = $p_Hora;
    }


    // SAVE MESSAGE
    public function save($p_Debug = false)
    {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $datos = array(
            'men_Chained_Id' => $this->_chainedId,

            'men_Subject' => $this->_subject,
            'men_Body' => $this->_body,

            'men_Sender_Id' => $this->_senderId,
            'men_Receiver_Id' => $this->_receiverId,

            'men_State_Seen' => $this->_stateSeen,
            'men_State_Sent' => $this->_stateSent,

            'men_Is_Draft' => $this->_isDraft,
            'men_Is_Scheduled' => $this->_isScheduled,

            'men_Scheduled_Date' => $this->_scheduledDate,
            'men_Sent_Date' => $this->_sentDate,

            'men_Creation_Date' => $this->_creationtDate,
            'men_Seen_Date' => $this->_seenDate

        );
        $resultado = "";

        if($this->_id == 0){
            $resultado = $cnn->Insert('message', $datos);

            if ($resultado === true) {
                $this->_id = $cnn->Devolver_Insert_Id();
            }
        }
        else{
            $resultado = $cnn->Update('message', $datos, "men_Id = {$this->_id}");
        }

        if ($resultado === false) {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
        }

        return $resultado;

    }

    public function getErrores()
    {
        return $this->_errores;

    }


    public function loadArray($p_Datos)
    {
        $this->_id              = (integer)$p_Datos["men_Id"];
        $this->_chainedId       = (integer)$p_Datos["men_Chained_Id"];;

        $this->_subject         = (string)$p_Datos["men_Subject"];
        $this->_body            = (string)$p_Datos["men_Body"];

        $this->_senderId        = (integer)$p_Datos["men_Sender_Id"];
        $this->_receiverId      = (integer)$p_Datos["men_Receiver_Id"];

        $this->_stateSeen       = (integer)$p_Datos["men_State_Seen"];
        $this->_stateSent       = (integer)$p_Datos["men_State_Sent"];

        $this->_isDraft         = (integer)$p_Datos["men_Is_Draft"];
        $this->_isScheduled     = (integer)$p_Datos["men_Is_Scheduled"];

        $this->_scheduledDate   = (string)$p_Datos["men_Scheduled_Date"];
        $this->_sentDate        = (string)$p_Datos["men_Sent_Date"];

        $this->_creationtDate   = (string)$p_Datos["men_Creation_Date"];
        $this->_seenDate        = (string)$p_Datos["men_Seen_Date"];
    }

    public function delete($p_Debug) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($cnn->Delete('message', "men_Id = {$this->_id}")) {
            return true;
        }
        else {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
            return false;
        }
    }



    // EXTRA FUNCTIONS HELPERS
    public function Fecha($p_Fecha, $p_Format, $p_texto)
    {
        $_fecha_hora = DateTimeHelper::getTimestampFromFormat($p_Fecha, $p_Format);
        if ($_fecha_hora === false) {
            $this->_errores[ValidateHelper::Cadena($p_texto)] = _("La") . " {$p_texto} " . _("es incorrecta.");
            return $p_Fecha;
        }
        return $_fecha_hora;
    }


}
