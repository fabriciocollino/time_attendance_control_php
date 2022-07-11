<?php

$T_Titulo           = _('Mensajes');
$T_Script           = 'message';
$Item_Name          = "message";
$T_Link             = '';
$T_Mensaje          = '';


$T_Tipo             = (isset($_REQUEST['tipo']))                ?           $_REQUEST['tipo']               : $T_Tipo;
$T_Accion           = (isset($_REQUEST['accion']))              ?           $_REQUEST['accion']             : 0;



$T_Id               = isset($_REQUEST["men_Id"])                ?           $_REQUEST["men_Id"]             : 0;
$T_ChainedId        = isset($_REQUEST["men_Chained_Id"])        ?           $_REQUEST["men_Chained_Id"]     : 0;

$T_Subject          = isset($_REQUEST["men_Subject"])           ?           $_REQUEST["men_Subject"]        : "";
$T_Body             = isset($_REQUEST["men_Body"])              ?           $_REQUEST["men_Body"]           : "";

$T_StateSeen        = isset($_REQUEST["men_State_Seen"])        ?           $_REQUEST["men_State_Seen"]     : 0;
$T_StateSent        = isset($_REQUEST["men_State_Sent"])        ?           $_REQUEST["men_State_Sent"]     : 0;

$T_SenderId         = isset($_REQUEST["men_Sender_id"])         ?           $_REQUEST["men_Sender_id"]      : 0;
$T_ReceiverId       = isset($_REQUEST["men_Receiver_Id"])       ?           $_REQUEST["men_Receiver_Id"]    : 0;

$T_IsDraft          = isset($_REQUEST["men_Is_Draft"])          ?           $_REQUEST["men_Is_Draft"]       : 0;
$T_IsScheduled      = isset($_REQUEST["men_Is_Scheduled"])      ?           $_REQUEST["men_Is_Scheduled"]   : 0;



$T_Fecha            = (isset($_REQUEST['fecha']))               ?           $_REQUEST['fecha']              : '';
$T_Hora             = (isset($_REQUEST['hora']))                ?           $_REQUEST['hora']               : '';


$T_SentDate         = isset($_REQUEST["men_Sent_Date"])         ?           $_REQUEST["men_Sent_Date"]      : "";

$T_CreationDate     = isset($_REQUEST["men_Creation_Date"])     ?           $_REQUEST["men_Creation_Date"]  : "";

$o_Messages = null;
$_message = null;


switch ($T_Tipo) {

    case 'view':

        $_message           = Message_L::getById($T_Id);

        $o_Messages         = Message_L::getAllChained($_message->getChainedId());

        $T_Subject          = $_message->getSubject();

        $T_ReceiverId       = $_message->getReceiverId();
        $T_SenderId         =  $currentUser_Id = Registry::getInstance()->Usuario->getId();

        if($T_ReceiverId == $T_SenderId){

            if($_message->getStateSeen() == 0){
                $_message->setStateSeen(1);
                $_message->setseenDateTime(date('Y-m-d H:i:s'));
                $_message->save();
            }
            $T_ReceiverId = $_message->getSenderId();
        }


        $T_ChainedId        = $_message->getChainedId();

        $T_Destinatario_Usuarios = HtmlHelper::array2htmloptions(Usuario_L::obtenerListaEmails(), $_message->getReceiverId(), true, false, 'Usuarios', _('Seleccione un Usuario'));

        break;

    // VIEW MESSAGE: EDITTING
    case 'edit':

        $_message           = Message_L::getById($T_Id);

        $T_Id               = $_message->getId();
        $T_Subject          = $_message->getSubject();
        $T_ReceiverId       = $_message->getReceiverId();
        $T_Body             = $_message->getBody();
        $T_Fecha            = substr($_message->getScheduledDate(),0,10);
        $T_Hora             = substr($_message->getScheduledDate(),-8,5);

        $T_Destinatario_Usuarios = HtmlHelper::array2htmloptions(Usuario_L::obtenerListaEmails(), $_message->getReceiverId(), true, false, 'Usuarios', _('Seleccione un Usuario'));

        break;

    // SAVE MESSAGE: EDITTED
    case 'save':

        $_message           = Message_L::getById($T_Id);

        $currentUser_Id     = Registry::getInstance()->Usuario->getId();

        $_message->setSubject($T_Subject);
        $_message->setBody($T_Body);

        $_message->setSenderId($currentUser_Id);
        $_message->setReceiverId($T_ReceiverId);

        $_message->setStateSeen($T_StateSeen);
        $_message->setStateSent($T_StateSent);

        $_message->setIsDraft($T_IsDraft);

        if($T_IsScheduled == "") {
            $_message->setIsScheduled(0);
            $_message->setScheduledDate('00-00-00 00:00:00', 'Y-m-d H:i:s');
        }
        else {
            $_message->setIsScheduled(1);
            $_message->setScheduledDate($T_Fecha . ' ' . $T_Hora . ':00', 'Y-m-d H:i:s');
        }

        $_message->setChainedId($T_ChainedId);

        if($_message->save()){

            // NEW CHAIN OF MESSAGES
            if($_message->getChainedId() == 0){
                $_message->setChainedId($_message->getId());
            }
            if($_message->getIsScheduled() == 0 && $_message->getIsDraft() == 0) {
                $_message->setStateSent(1);
                $_message->setSentDateTime(date('Y-m-d H:i:s'));
            }

            $_message->setCreationDate(date('Y-m-d H:i:s'));

            $_message->save();
            $T_Mensaje = _('El mensaje fué modificado correctamente.');
        }
        else{
            $_message->getErrores();
        }

        break;


    // NEW MESSAGE: FIRST OF CHAIN
    case 'new':

        $o_Message         = new Message_O();

        $T_Id               = 0;
        $T_ChainedId        = 0;

        $T_Subject          = "";
        $T_Body             = "";

        $T_StateSeen        = 0;
        $T_StateSent        = 0;

        $T_SenderId         = 0;
        $T_ReceiverId       = 0;

        $T_IsDraft          = 0;
        $T_IsScheduled      = 0;

        $T_SentDate         = "";

        $T_CreationDate     = "";


        $T_Destinatario_Usuarios = HtmlHelper::array2htmloptions(Usuario_L::obtenerListaEmails(), "", true, false, 'Usuarios', _('Seleccione un Usuario'));

        break;

    case 'add':
        $o_Message          = new Message_O();
        $currentUser_Id     = Registry::getInstance()->Usuario->getId();

        $o_Message->setSubject($T_Subject);
        $o_Message->setBody($T_Body);

        $o_Message->setSenderId($currentUser_Id);
        $o_Message->setReceiverId($T_ReceiverId);

        $o_Message->setStateSeen($T_StateSeen);
        $o_Message->setStateSent($T_StateSent);

        $o_Message->setIsDraft($T_IsDraft);

        if($T_IsScheduled == "") {
            $o_Message->setIsScheduled(0);
        }
        else {
            $o_Message->setIsScheduled(1);
            $o_Message->setScheduledDate($T_Fecha . ' ' . $T_Hora . ':00', 'Y-m-d H:i:s');
        }

        $o_Message->setChainedId($T_ChainedId);

        if($o_Message->save()){

            // NEW CHAIN OF MESSAGES
            if($o_Message->getChainedId() == 0){
                $o_Message->setChainedId($o_Message->getId());
            }
            if($o_Message->getIsScheduled() == 0 && $o_Message->getIsDraft() == 0) {
                $o_Message->setStateSent(1);
                $o_Message->setSentDateTime(date('Y-m-d H:i:s'));
            }

            $o_Message->setCreationDate(date('Y-m-d H:i:s'));

            $o_Message->save();
            $T_Mensaje = _('El mensaje fué guardado correctamente.');
        }
        else{
            $o_Message->getErrores();
        }

        break;

    case 'delete':
        $o_Message = Message_L::getById($T_Id);

        if ($o_Message->delete(Registry::getInstance()->general['debug'])) {
            $T_Eliminado = true;
            $o_Message = "Mensaje Eliminado";
        }
        else {
            $T_Error = $o_Notificacion->getErrores();
        }
        break;


    case 'viewAllReceived':
        $o_Messages         = Message_L::getAllReceived();
        break;

    case 'viewAllSent':
        $o_Messages         = Message_L::getAllSent();
        break;

    case 'viewAllScheduled':
        $o_Messages         = Message_L::getAllScheduled();
        break;
    case 'viewAllDrafts':
        $o_Messages         = Message_L::getAllDrafts();
        break;
}
