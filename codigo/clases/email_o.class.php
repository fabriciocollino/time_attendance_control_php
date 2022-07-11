<?php
use google\appengine\api\mail\Message;


class Email_O
{

    private $_id;
    private $_tipo;
    private $_detalle;
    private $_fecha;
    private $_destinatario;
    private $_sujeto;
    private $_cuerpo;
    private $_estado;
    private $_grupal;
    private $_grupo;
    private $_adjunto;
    private $_bcc;
    private $_from;

    private $_errores;

    public function __construct($p_destinatario = '', $p_sujeto = '', $p_cuerpo = '', $p_estado = 0, $p_grupal = 0, $p_grupo = 0, $adjunto = '', $from='')
    {
        $this->_id = 0;
        $this->_destinatario = $p_destinatario;
        $this->_sujeto = $p_sujeto;
        $this->_detalle = '';
        $this->_cuerpo = $p_cuerpo;
        $this->_estado = 0;
        $this->_grupal = $p_grupal;
        $this->_grupo = $p_grupo;
        $this->_bcc = '';
        $this->_fecha = 0;
        $this->_adjunto = $adjunto;
        $this->_from = $from;
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

    public function getTipo()
    {
        return $this->_tipo;
    }

    public function setTipo($p_tipo)
    {
        $p_tipo = (integer)$p_tipo;
        $this->_tipo = $p_tipo;
    }


    public function getDetalle()
    {
        return $this->_detalle;
    }

    public function setDetalle($p_detalle)
    {
        $p_detalle = (string)$p_detalle;
        $this->_detalle = $p_detalle;
    }

    public function getDestinatario()
    {
        return $this->_destinatario;
    }

    public function setDestinatario($p_destinatario)
    {
        $p_destinatario = (string)$p_destinatario;
        $this->_destinatario = $p_destinatario;
    }

    public function getDestinatarioBCC()
    {
        return $this->_bcc;
    }

    public function setDestinatarioBCC($p_destinatariobcc)
    {
        $p_destinatariobcc = (string)$p_destinatariobcc;
        $this->_bcc = $p_destinatariobcc;
    }

    public function getSujeto()
    {
        return $this->_sujeto;
    }

    public function setSujeto($p_sujeto)
    {
        $p_sujeto = (string)$p_sujeto;
        $this->_sujeto = $p_sujeto;
    }

    public function getFrom()
    {
        return $this->_from;
    }

    public function setFrom($p_From)
    {
        $p_From = (string)$p_From;
        $this->_from = $p_From;
    }

    public function getCuerpo()
    {
        return $this->_cuerpo;
    }

    public function setCuerpo($p_cuerpo)
    {
        $p_cuerpo = (string)$p_cuerpo;
        $this->_cuerpo = $p_cuerpo;
    }

    public function getEstado()
    {
        return $this->_estado;
    }

    public function getEstado_S()
    {

        if ($this->_estado == 1) return _("ESPERANDO");
        else if ($this->_estado == 2) return _("ENVIADO");
        else if ($this->_estado == 3) return _("ERROR");
    }

    public function setEstado($p_estado)
    {
        $p_estado = (integer)$p_estado;
        $this->_estado = $p_estado;
    }

    public function getGrupal()
    {
        return $this->_grupal;
    }

    public function setGrupal($p_grupal)
    {
        $p_grupal = (integer)$p_grupal;
        $this->_grupal = $p_grupal;
    }

    public function getGrupo()
    {
        return $this->_grupo;
    }

    public function setGrupo($p_grupo)
    {
        $p_grupo = (integer)$p_grupo;
        $this->_grupo = $p_grupo;
    }

    public function getAdjunto()
    {
        return $this->_adjunto;
    }

    public function setAdjunto($p_adjunto)
    {
        $p_adjunto = (string)$p_adjunto;
        $this->_adjunto = $p_adjunto;
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
            $this->_fecha = $p_Hora;
        } else {
            $this->_fecha = 0;
        }
    }

    public function loadArray($p_Datos)
    {
        $this->_id = (integer)$p_Datos["ema_Id"];
        $this->_tipo = (integer)$p_Datos["ema_Tipo"];
        $this->_detalle = (string)$p_Datos["ema_Detalle"];
        $this->_fecha = (is_null($p_Datos["ema_Fecha"])) ? null : strtotime($p_Datos["ema_Fecha"]);
        $this->_destinatario = (string)$p_Datos["ema_Destinatario"];
        $this->_bcc = (string)$p_Datos["ema_Destinatario_Bcc"];
        $this->_sujeto = (string)$p_Datos["ema_Sujeto"];
        $this->_cuerpo = (string)$p_Datos["ema_Cuerpo"];
        $this->_estado = (integer)$p_Datos["ema_Estado"];
        $this->_grupal = (integer)$p_Datos["ema_Grupal"];
        $this->_grupo = (integer)$p_Datos["ema_Grupo"];
        $this->_adjunto = (string)$p_Datos["ema_Adjunto"];
        $this->_from = (string)$p_Datos["ema_From"];

    }

    public function save($p_Debug=false)
    {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        $datos = array(
            'ema_Id' => $this->_id,
            'ema_Tipo' => $this->_tipo,
            'ema_Detalle' => $this->_detalle,
            'ema_Fecha' => $this->_fecha,
            'ema_Destinatario' => $this->_destinatario,
            'ema_Destinatario_Bcc' => $this->_bcc ,
            'ema_Sujeto' => $this->_sujeto,
            'ema_Cuerpo' => $this->_cuerpo,
            'ema_Estado' => $this->_estado,
            'ema_Grupal' => $this->_grupal,
            'ema_Grupo' => $this->_grupo,
            'ema_Adjunto' => $this->_adjunto,
            'ema_From' => $this->_from
        );

        if ($this->_id == 0) {
            $resultado = $cnn->Insert('email', $datos);
            if ($resultado !== false) {
                $this->_id = $cnn->Devolver_Insert_Id();
            }
        } else {
            $resultado = $cnn->Update('email', $datos, "ema_Id = {$this->_id}");
        }

        if ($resultado === false) {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
        }

        return $resultado;

    }

    public function enviar_vtex($adjuntos = array (), $cuerpo = ''){


        $mail               = new PHPMailer();
        $mail->CharSet      = 'UTF-8';
        $mail->Host         = 'localhost';
        $mail->From         = _("holasokomarket") . "@" . $mail->Host;

        if (Config_L::obtenerPorParametro('usar_smtp')->getValor()) {

            $mail->IsSMTP();
            $mail->Mailer = "smtp";
            $mail->Host = Config_L::obtenerPorParametro('host_smtp')->getValor();
            $mail->SMTPAuth = true;
            $mail->Username = Config_L::obtenerPorParametro('usuario_smtp')->getValor();
            $mail->Password = Config_L::obtenerPorParametro('password_smtp')->getValor();
            $mail->Port = Config_L::obtenerPorParametro('puerto_smtp')->getValor();
            $mail->From = Config_L::obtenerPorParametro('usuario_smtp')->getValor();

            if (Config_L::p('usar_tls'))
                $mail->SMTPSecure = 'tls';
        }

        if($this->_from==''){
            $mail->FromName = Config_L::obtenerPorParametro('from_smtp')->getValor();
        }else{
            $mail->FromName  = $this->_from;
        }

        $mail->AddReplyTo("");


        /* DESTINATARIO */
        if($this->_destinatario!=''){
            $destinatarios = explode(',',$this->_destinatario);
            foreach($destinatarios as $destinatario){
                $mail->AddAddress($destinatario);
            }
        }

        /* COPIA CC */
        if($this->_bcc!=''){
            $bccs = explode(',',$this->_bcc);
            foreach($bccs as $bcc){
                $mail->AddCC($bcc);
            }
        }

        /* ASUNTO */
        $mail->Subject = $this->_sujeto;

        /* HTML */
        $mail->IsHTML(true);

        /* ADJUNTO */
        if(count($adjuntos) > 0){
            foreach($adjuntos as $adjunto){
                $mail->AddAttachment($adjunto["URL"], $adjunto["NOMBRE"]);
            }
        }

        $mail->Body = $cuerpo;

        $mail->AltBody = strip_tags($cuerpo); // Este es el contenido alternativo sin html

        $email_semd_status = $mail->Send();


        return $email_semd_status;
    }






    public function enviar()
    {
        //TODO: antes de enviar, chequear que todos los campos sean correctos.
        $mail               = new PHPMailer();
        $mail->CharSet      = 'UTF-8';
        $mail->Host         = 'localhost';
        $mail->From         = _("notificaciones") . "@" . $mail->Host;

        if (Config_L::obtenerPorParametro('usar_smtp')->getValor()) {

            $mail->IsSMTP();
            $mail->Mailer = "smtp";
            $mail->Host = Config_L::obtenerPorParametro('host_smtp')->getValor();
            $mail->SMTPAuth = true;
            $mail->Username = Config_L::obtenerPorParametro('usuario_smtp')->getValor();
            $mail->Password = Config_L::obtenerPorParametro('password_smtp')->getValor();
            $mail->Port = Config_L::obtenerPorParametro('puerto_smtp')->getValor();
            $mail->From = Config_L::obtenerPorParametro('usuario_smtp')->getValor();

            if (Config_L::p('usar_tls'))
                $mail->SMTPSecure = 'tls';
        }

        if($this->_from==''){
            $mail->FromName = Config_L::obtenerPorParametro('from_smtp')->getValor();
        }
        else{
            $mail->FromName  = $this->_from;
        }

        $mail->AddReplyTo("noreply@enpuntocontrol.com");


        //TAGS
        $TAG_USER_NAME = '';
        $TAG_HEADER_TITLE = '';
        $TAG_BODY_TITLE = '';
        $TAG_BODY = '';
        $TAG_FOOTER_NOTE = '';
        global $subdominio;
        $TAG_SUBDOMAIN = $subdominio;


        if($this->_bcc!=''){
            $bccs = explode(',',$this->_bcc);
            foreach($bccs as $bcc){
                //$mail->AddBCC($bcc);
                $mail->AddCC($bcc);

            }
        }

        $TAG_HEADER_TITLE = $this->_sujeto;

        //por ahora no lo uso a este
        //$TAG_BODY_TITLE = $this->_sujeto;

        $TAG_BODY = $this->_cuerpo;

        $TAG_FOOTER_NOTE = "<br> <br> <br> <br> Enviado desde " . $TAG_SUBDOMAIN.".enpuntocontrol.com <br>Día " . date('d-m-Y', time()) . " a las " . date('H:i', time()) ." horas.<br> <br> ";
        if ($this->_grupal) {
            $o_Grupo = Grupo_L::obtenerPorId($this->_grupo);
            if($o_Grupo)
                $TAG_FOOTER_NOTE.="<br/><br/>Recibes este email porque eres parte del grupo ".$o_Grupo->getDetalle();
        }

        $mail->Subject = $this->_sujeto;


        $TEMPLATE = Config_L::email('general_email_tagged');

        $a_TAGS = array(
            '##HEADER_TITLE##',
            '##BODY_TITLE##',
            '##USER_NAME##',
            '##BODY##',
            '##FOOTER_NOTE##',
            '##SUBDOMAIN##'
        );


        $mail->IsHTML(true);

        if ($this->_adjunto != '') {
            $mail->AddAttachment($this->_adjunto);
        }


        if ($this->_grupal) {
            $o_Grupo = Grupo_L::obtenerPorId($this->_grupo);
            if ($o_Grupo) {
                $a_emails = Grupo_L::obtenerListaEmailsPorId($this->_grupo);
                if ($a_emails) {
                    $TAG_USER_NAME = '';
                    foreach ($a_emails as $email)
                        $mail->AddAddress($email);
                }
            }
        }
        else {
            $mail->AddAddress($this->_destinatario);

        }

        $a_TAGS_r = array(
            $TAG_HEADER_TITLE,
            $TAG_BODY_TITLE,
            $TAG_USER_NAME,
            $TAG_BODY,
            $TAG_FOOTER_NOTE,
            $TAG_SUBDOMAIN
        );

        $cuerpoHTML = str_replace($a_TAGS, $a_TAGS_r, $TEMPLATE);


        $mail->Body = $cuerpoHTML;

        $mail->AltBody = strip_tags($this->_cuerpo); // Este es el contenido alternativo sin html

        $email_semd_status = $mail->Send();

        //elimino el pdf
        if ($this->_adjunto != '') {
            if (file_exists($this->_adjunto))
                unlink($this->_adjunto);
        }

        return $email_semd_status;
    }

    public function get_email_template(){

        $template =  '
                 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
<head style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
<!-- If you delete this meta tag, Half Life 3 will never be released. -->
<meta name="viewport" content="width=device-width" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
<title style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">Basic</title>
<style style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
*{margin:0;padding:0}*{font-family:"Helvetica Neue","Helvetica",Helvetica,Arial,sans-serif}img{max-width:100%}.collapse{margin:0;padding:0}body{-webkit-font-smoothing:antialiased;-webkit-text-size-adjust:none;width:100% !important;height:100%}a{color:#2ba6cb} .btn{display: inline-block;padding: 6px 12px;margin-bottom: 0;font-size: 14px;font-weight: normal;line-height: 1.428571429;text-align: 
center;white-space: nowrap;vertical-align: middle;cursor: pointer;border: 1px solid transparent;border-radius: 4px;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;-o-user-select: none;user-select: none;color: #333;background-color: white;border-color: #CCC;} p.callout{padding:15px;background-color:#ffffff;margin-bottom:15px}.callout a{font-weight:bold;color:#2ba6cb}tabl
e.social{background-color:#ffffff}.social .soc-btn{padding:3px 7px;border-radius:2px; -webkit-border-radius:2px; -moz-border-radius:2px; font-size:12px;margin-bottom:10px;text-decoration:none;color:#FFF;font-weight:bold;display:block;text-align:center}a.fb{background-color:#19b0c7 !important}a.tw{background-color:#19b0c7 !important}a.gp{background-color:#19b0c7 !important}a.ms{background-colo
r:#000 !important}.sidebar .soc-btn{display:block;width:100%}table.head-wrap{width:100%}.header.container table td.logo{padding:15px}.header.container table td.label{padding:15px;padding-left:0}table.body-wrap{width:100%}table.footer-wrap{width:100%;clear:both !important}.footer-wrap .container td.content p{border-top:1px solid #d7d7d7;padding-top:15px}.footer-wrap .container td.content p{fon
t-size:10px;font-weight:bold}h1,h2,h3,h4,h5,h6{font-family:"HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;line-height:1.1;margin-bottom:15px;color:#000}h1 small,h2 small,h3 small,h4 small,h5 small,h6 small{font-size:60%;color:#6f6f6f;line-height:0;text-transform:none}h1{font-weight:200;font-size:44px}h2{font-weight:200;font-size:37px}h
3{font-weight:500;font-size:27px}h4{font-weight:500;font-size:23px}h5{font-weight:900;font-size:17px}h6{font-weight:900;font-size:14px;text-transform:uppercase;color:#444}.collapse{margin:0 !important}p,ul{margin-bottom:10px;font-weight:normal;font-size:14px;line-height:1.6}p.lead{font-size:17px}p.last{margin-bottom:0}ul li{margin-left:5px;list-style-position:inside}ul.sidebar{background:#fff
fff;display:block;list-style-type:none}ul.sidebar li{display:block;margin:0}ul.sidebar li a{text-decoration:none;color:#666;padding:10px 16px;margin-right:10px;cursor:pointer;border-bottom:1px solid #777;border-top:1px solid #fff;display:block;margin:0}ul.sidebar li a.last{border-bottom-width:0}ul.sidebar li a h1,ul.sidebar li a h2,ul.sidebar li a h3,ul.sidebar li a h4,ul.sidebar li a h5,ul.s
idebar li a h6,ul.sidebar li a p{margin-bottom:0 !important}.container{display:block !important;max-width:600px !important;margin:0 auto !important;clear:both !important}.content{padding:15px;max-width:600px;margin:0 auto;display:block}.content table{width:100%}.column{width:300px;float:left}.column tr td{padding:15px}.column-wrap{padding:0 !important;margin:0 auto;max-width:600px !important}
.column table{width:100%}.social .column{width:280px;min-width:279px;float:left}.clear{display:block;clear:both}@media only screen and (max-width:600px){a[class="btn"]{display:block !important;margin-bottom:10px !important;background-image:none !important;margin-right:0 !important}div[class="column"]{width:auto !important;float:none !important}table.social div[class="column"]{width:auto !impo
rtant}}
</style>
</head>
 
<body bgcolor="#FFFFFF" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;height: 100%;width: 100% !important;">
<!-- HEADER -->
<table class="head-wrap">
        <tr style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
                <td style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;"></td>
                <td class="header container" style="margin: 0 auto !important;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display: block !important;max-width: 600px !important;clear: both !important;">
                                <div class="content" style="margin: 0 auto;padding: 20px;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;max-width: 600px;display: block;">
                                        <table style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width: 100%;">
                                                <tr style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
                                                        <td style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;"><img src="https://lh3.googleusercontent.com/zLX8aEWmV6WU8H_qVEZSlRsWCkq2UOl8VbWeM1oNqr2v3s_osaehH_bya_sNKVuB8u23VtfvyvysN7TpeWjSj91P" width="170" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&q
uot;Helvetica&quot;,Helvetica,Arial,sans-serif;max-width: 100%;"></td>
                                                        <td align="right" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;"><h6 class="collapse" style="margin: 0 !important;padding: 0;font-family: &quot;HelveticaNeue-Light&quot;,&quot;Helvetica Neue Light&quot;,&quot;Helvetica Neue&quot;,Helvetica,Arial,&quot;Lucida Grande&q
uot;,sans-serif;line-height: 1.1;margin-bottom: 15px;color: #444;font-weight: 900;font-size: 14px;text-transform: uppercase;">##HEADER_TITLE##</h6></td>
                                                </tr>
                                        </table>
                                </div>
                </td>
                <td style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;"></td>
        </tr>
</table><!-- /HEADER -->
<!-- BODY -->
<table class="body-wrap" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width: 100%;">
        <tr style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
                <td style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;"></td>
                <td class="container" bgcolor="#FFFFFF" style="margin: 0 auto !important;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display: block !important;max-width: 600px !important;clear: both !important;">
                        <div class="content" style="margin: 0 auto;padding: 15px;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;max-width: 600px;display: block;">
                        <table style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width: 100%;">
                                <tr style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
                                        <td style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
                                        <div style="padding: 5px;">
                                                <h3 style="margin: 0;padding: 0;font-family: &quot;HelveticaNeue-Light&quot;,&quot;Helvetica Neue Light&quot;,&quot;Helvetica Neue&quot;,Helvetica,Arial,&quot;Lucida Grande&quot;,sans-serif;line-height: 1.1;margin-bottom: 15px;color: #000;font-weight: 500;font-size: 27px;">Hola ##USER_NAME##</h3>
                                                <p class="lead" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 17px;line-height: 1.6;">##BODY_TITLE##</p>
                                                <p style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">##BODY##</p>
                                        </div>
                                                <!-- Callout Panel -->
                                                <p class="callout" style="margin: 0;padding: 5px;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-bottom: 15px;font-weight: normal;font-size: 14px;line-height: 1.6;background-color: #ffffff;">
                                                        ##FOOTER_NOTE##
                                                </p><!-- /Callout Panel -->
                                                <!-- social & contact -->
                                                <table class="social" width="100%" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;background-color: #ffffff;width: 100%;">
                                                        <tr style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
                                                                <td style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
                                                                        <!-- column 1 -->
                                                                        <table align="left" class="column" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width: 280px;float: left;min-width: 279px;">
                                                                                <tr style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
                                                                                        <td style="margin: 0;padding: 0px;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
                                                                                                <h5 class="" style="margin: 0;padding: 0;font-family: &quot;HelveticaNeue-Light&quot;,&quot;Helvetica Neue Light&quot;,&quot;Helvetica Neue&quot;,Helvetica,Arial,&quot;Lucida Grande&quot;,sans-serif;line-height: 1.1;margin-bottom: 15px;color: #000;font-weight: 900;font-size: 17px;">Con&eacute;ctate
 con nosotros</h5>
                                                                                                <p class="" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;"><a href="https://www.facebook.com/enpuntocontrol/" class="soc-btn fb" style="margin: 0;padding:
 3px 7px;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color: #FFF;border-radius: 2px;-webkit-border-radius: 2px;-moz-border-radius: 2px;font-size: 12px;margin-bottom: 10px;text-decoration: none;font-weight: bold;display: block;text-align: center;background-color: #19b0c7 !important;">Facebook</a> <a href="https://twitter.com/enpuntocontrol" class="s
oc-btn tw" style="margin: 0;padding: 3px 7px;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color: #FFF;border-radius: 2px;-webkit-border-radius: 2px;-moz-border-radius: 2px;font-size: 12px;margin-bottom: 10px;text-decoration: none;font-weight: bold;display: block;text-align: center;background-color: #19b0c7 !important;">Twitter</a> <a href="#" class=
"soc-btn gp" style="margin: 0;padding: 3px 7px;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color: #FFF;border-radius: 2px;-webkit-border-radius: 2px;-moz-border-radius: 2px;font-size: 12px;margin-bottom: 10px;text-decoration: none;font-weight: bold;display: block;text-align: center;background-color: #19b0c7 !important;">Google+</a></p>
                                                                                        </td>
                                                                                </tr>
                                                                        </table><!-- /column 1 -->
                                                                        <!-- column 2 -->
                                                                        <table align="left" class="column" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width: 280px;float: left;min-width: 279px;">
                                                                                <tr style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
                                                                                        <td style="margin: 0;padding: unset;padding-left: 15px;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
                                                                                                <h5 class="" style="margin: 0;padding: 0;font-family: &quot;HelveticaNeue-Light&quot;,&quot;Helvetica Neue Light&quot;,&quot;Helvetica Neue&quot;,Helvetica,Arial,&quot;Lucida Grande&quot;,sans-serif;line-height: 1.1;margin-bottom: 15px;color: #000;font-weight: 900;font-size: 17px;">Contacto</h5>
                                                                                                <p style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
                                                                                                        <strong style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;"><a href="emailto:info@enpuntocontrol.com" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color
: #2ba6cb;">contacto@enpuntocontrol.com</a></strong>
                                                                                                        <br style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
                                                                                                        <br style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;">
                                                                                                        <strong style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color: #2ba6cb;">+(54) 9 351 238 6342</strong>
                                                                                                        </p>
                 
                                                                                        </td>
                                                                                </tr>
                                                                        </table><!-- /column 2 -->
                                                                        <span class="clear" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display: block;clear: both;"></span>
                                                                </td>
                                                        </tr>
                                                </table><!-- /social & contact -->
                                        </td>
                                </tr>
                        </table>
                        </div><!-- /content -->
                </td>
                <td style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;"></td>
        </tr>
</table><!-- /BODY -->
<!-- FOOTER -->
<table class="footer-wrap" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width: 100%;clear: both !important;">
</table><!-- /FOOTER -->
</body>
</html> 

       ';
        return $template;
    }


}
