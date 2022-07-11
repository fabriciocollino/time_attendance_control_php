<?php

/*
 * Usuario (Object)
 */

class Persona_O
{

    private $_id;
    //vinculos
    private $_hor_tipo;
    private $_hor_id;
    //datos
    private $_legajo;
    private $_nombre;
    private $_apellido;
    private $_te_celular;
    private $_te_fijo;
    private $_email;
    private $_dni;
    private $_tag;
    private $_equipos;
    private $_excluir;
    private $_imagen;
    private $_imagen_url;
    private $_notas;
    //control
    private $_creado_usu_id;
    private $_creado_el;
    private $_bloqueado_usu_id;
    private $_bloqueado_el;
    private $_fecha_mod;
    private $_empresa;
    private $_eliminada;
    private $_eliminada_usu_id;
    private $_eliminada_el;
    private $_enabled;
    // user
    private $_user_enabled;
    private $_user_id;
    //objects
    private $_hora_trabajo_object;
    private $_creado_usu_object;
    private $_eliminado_usu_object;
    private $_fechaD;
    private $_fechaH;
    private $_RID;
    //error
    private $_errores;

    // new vars
    private $_segundo_nombre;
    private $_fecha_nacimiento;
    private $_genero;
    private $_estado_civil;
    private $_nro_contribuyente;
    private $_talle_camisa;

    private $_direccion1;
    private $_direccion2;
    private $_ciudad;
    private $_provincia;
    private $_codigo_postal;
    private $_pais;

    private $_te_trabajo;

    private $_linkedin;
    private $_twitter;
    private $_facebook;

    private $_email_personal;

    private $_estado;
    private $_edad;

    public function __construct() {
        $this->_id = 0; //int(11)
        //vinculos
        $this->_hor_tipo = 0;
        $this->_hor_id = 0; //int(11)
        //datos
        $this->_legajo = ''; //varchar(20)
        $this->_nombre = ''; //varchar(50)
        $this->_apellido = ''; //varchar(50)
        $this->_te_celular = ''; //varchar(20)
        $this->_te_fijo = ''; //varchar(20)
        $this->_email = ''; //varchar(255)
        $this->_dni = ''; //varchar(8)
        $this->_tag = ''; //varchar(10)
        $this->_excluir = 0; //int
        $this->_equipos = ''; //varchar(200)
        $this->_imagen = ''; //varchar 200
        $this->_imagen_url = ''; //varchar 300
        $this->_notas= '';
        //control
        $this->_creado_usu_id = 0; //int(11)
        $this->_creado_el = null;
        $this->_bloqueado_usu_id = 0; //int(11)
        $this->_bloqueado_el = null;
        $this->_fecha_mod = 0;
        $this->_empresa = 1;

        $this->_eliminada = 0;
        $this->_eliminada_usu_id = 0;
        $this->_eliminada_el = null;

        $this->_enabled = 1;
        $this->_fechaD='0000-00-00 00:00:00';
        $this->_fechaH='0000-00-00 00:00:00';
        $this->_RID='';

        // account
        $this->_user_enabled  = 0;
        $this->_user_id  = 0;

        //objects
        $this->_hora_trabajo_object = null;
        $this->_creado_usu_object = null;
        $this->_eliminado_usu_object = null;

        //error
        $this->_errores = array();

        // new vars
        $this->_segundo_nombre          = ''; //varchar(50)
        $this->_fecha_nacimiento        = '0000-00-00 00:00:00'; //datetime
        $this->_estado_civil            = ''; //varchar(20)
        $this->_genero                  = ''; //varchar(20)
        $this->_nro_contribuyente       = ''; //varchar(20)
        $this->_talle_camisa            = ''; //varchar(20)

        $this->_direccion1              = ''; //varchar(255)
        $this->_direccion2              = ''; //varchar(255)
        $this->_ciudad                  = ''; //varchar(255)
        $this->_provincia               = ''; //varchar(255)
        $this->_codigo_postal           = ''; //varchar(8)
        $this->_pais                    = ''; //varchar(255)

        $this->_te_trabajo              = ''; //varchar(20)

        $this->_linkedin                = ''; //varchar(255)
        $this->_twitter                 = ''; //varchar(255)
        $this->_facebook                = ''; //varchar(255)

        $this->_email_personal          = ''; //varchar(255)

        $this->_estado                  = 'Activo';

        $this->set_edad();
    }



    /**********************************************************************
     * EDAD
     ***********************************************************************/
    private function set_edad()
    {
        $fecha_nacimiento       = new DateTime($this->_fecha_nacimiento);
        $fecha_hoy              = new DateTime(date("Y-m-d"));
        $diferencia_fechas      = $fecha_hoy->diff($fecha_nacimiento);

        $this->_edad = $diferencia_fechas;
    }
    private function get_edad()
    {
        return $this->_edad;
    }

    /**********************************************************************
     * CONTROL
     ***********************************************************************/
    /*
     * Controla vacio, contidad de caracteres max y min
     */
    private function control($p_valor, $p_texto, $p_min, $p_max, $p_articulo = 'El', $p_genero = 'o') {
        if ($p_valor == '') {
            $this->_errores[ValidateHelper::Cadena($p_texto)] = _("Debe proporcionar") . " " . strtolower($p_articulo) . " {$p_texto}.";
        } elseif (strlen($p_valor) < $p_min) {
            $this->_errores[ValidateHelper::Cadena($p_texto)] = "{$p_articulo} {$p_texto} " . _("especificad") . "{$p_genero} " . _("es demasiado corto.");
        } elseif (strlen($p_valor) > $p_max) {
            $this->_errores[ValidateHelper::Cadena($p_texto)] = "{$p_articulo} {$p_texto} " . _("especificad") . "{$p_genero} " . _("no debe superar los") . " {$p_max} " . _("caracteres.");
        } elseif (strpos($p_valor, ':') !== false) {
            $this->_errores[ValidateHelper::Cadena($p_texto)] = "{$p_articulo} {$p_texto} " . _("especificad") . "{$p_genero} " . _("no debe contener el carácter dos puntos (:).");
        }
    }
    private function seleccionado($p_valor, $p_texto) {
        if (is_int($p_valor)) {
            if ($p_valor == 0) {
                $this->_errores[ValidateHelper::Cadena($p_texto)] = _("Debe seleccionar un") . " {$p_texto}.";
            }
        } else {
            if ($p_valor == '') {
                $this->_errores[ValidateHelper::Cadena($p_texto)] = _("Debe seleccionar un") . " {$p_texto}.";
            }
        }
    }
    private function control_numero($p_valor, $p_texto, $p_Tipo) {
        if (!empty($p_valor)) {
            if (is_numeric($p_valor)) {
                switch ($p_Tipo) {
                    case 'int':
                        $p_valor = (integer)$p_valor;
                        break;
                    case 'float':
                        $p_valor = (float)$p_valor;
                        break;
                }
                if (is_int($p_valor)) {
                    /* if ($p_valor == 0) {
                      $this->_errores[ValidateHelper::Cadena($p_texto)] = "Debe proporcionar {$p_texto} valido.";
                      } */
                } elseif (is_float($p_valor)) {
                    /* if ($p_valor == 0) {
                      $this->_errores[ValidateHelper::Cadena($p_texto)] = "Debe proporcionar {$p_texto} valido.";
                      } */
                }
            } else {
                $this->_errores[ValidateHelper::Cadena($p_texto)] = _("Debe proporcionar") . " {$p_texto} " . _("valido.");
            }
        }
    }

    /**********************************************************************
     * RID
     ***********************************************************************/
    public function setRID($RID) {
        $this->_RID = $RID;
    }
    public function getRID() {
        return $this->_RID;
    }

    /**********************************************************************
     * FECHA DESDE
     ***********************************************************************/

    public function setFechaD($p_fechaD) {

        if($p_fechaD == ''){
            $this->_fechaD= '0000-00-00';
            return true;
        }

        if (is_string($p_fechaD)) {
            $this->_fechaD = date('Y-m-d', strtotime($p_fechaD));
            return true;
        }

        if (is_int($p_fechaD)) {
            $this->_fechaD = date('Y-m-d', $p_fechaD);
            return true;
        }

        $this->_errores['inicio_de_actividad'] = "La fecha de inicio de actividad tiene un formato incorrecto.";


    }
    public function getFechaD($p_Format = null) {


        if (is_null($p_Format)){
            return $this->_fechaD;
        }

        if ($this->_fechaD == 0) {
            return '';
        }

        if (!is_string($p_Format)) {
            return '';
        }

        $var = date($p_Format, strtotime($this->_fechaD));

        return $var;



    }

    /**********************************************************************
     * FECHA HASTA
     ***********************************************************************/

    public function setFechaH($fechaH) {

        if($fechaH == ''){
            $this->_fechaH= '0000-00-00';
            return true;
        }

        if (is_string($fechaH)) {
            $this->_fechaH = date('Y-m-d', strtotime($fechaH));
            return true;
        }

        if (is_int($fechaH)) {
            $this->_fechaH = date('Y-m-d', $fechaH);
            return true;
        }

        $this->_errores['inicio_de_actividad'] = "La fecha de inicio de actividad tiene un formato incorrecto.";


    }
    public function getFechaH($p_Format = null) {


        if (is_null($p_Format)){
            return $this->_fechaH;
        }

        if ($this->_fechaH == 0) {
            return '';
        }

        if (!is_string($p_Format)) {
            return '';
        }

        $var = date($p_Format, strtotime($this->_fechaH));

        return $var;



    }

    /**********************************************************************
     * ID
     ***********************************************************************/
    public function getId() {
        return $this->_id;
    }
    public function selectLastPerson(){
        $l_Persona = new Persona_L();
        $l_Persona->selectLastPerson();


    }

    /**********************************************************************
     * HORARIO
     ***********************************************************************/
    public function getHorId() {
        return $this->_hor_id;
    }
    public function setHorId($p_HorId) {
        $p_HorId = (integer)$p_HorId;
        //$this->seleccionado($p_HorId, _('Horario de Trabajo'));
        $this->_hor_id = $p_HorId;
        switch ($this->_hor_tipo) {
            case HORARIO_NORMAL:
                if (is_null(Hora_Trabajo_L::obtenerPorId($this->_hor_id))) {
                    $this->_errores['horario_id'] = 'El Horario Normal ID ' . ' \'' . $this->_hor_id . '\' ' . 'no existe';
                }
                break;
            case HORARIO_FLEXIBLE:
                if (is_null(Horario_Flexible_L::obtenerPorId($this->_hor_id))) {
                    $this->_errores['horario_id'] = 'El Horario Flexible ID ' . ' \'' . $this->_hor_id . '\' ' . 'no existe';
                }
                break;
            case HORARIO_ROTATIVO:
                if (is_null(Horario_Rotativo_L::obtenerPorId($this->_hor_id))) {
                    $this->_errores['horario_id'] = 'El Horario Rotativo ID ' . ' \'' . $this->_hor_id . '\' ' . 'no existe';
                }
                break;
            case 0:
                break;
        }
    }
    public function getHorTipo() {
        return $this->_hor_tipo;
    }
    public function setHorTipo($p_HorTipo) {
        $p_HorTipo = (integer)$p_HorTipo;
        //$this->seleccionado($p_HorTipo, _('Tipo de Horario'));
        $this->_hor_tipo = $p_HorTipo;
    }

    /**********************************************************************
     * LEGAJO
     ***********************************************************************/
    public function getLegajo() {
        return $this->_legajo;
    }
    public function setLegajo($p_Legajo) {
        $p_Legajo = trim($p_Legajo);
        $this->_legajo = $p_Legajo;

        if ($this->_legajo == ''){
            $this->_errores['legajo'] = _('El legajo está vacío.');
        }

        $this->control($this->_legajo, _('Legajo'), 2, 20);


        if ($this->_legajo != '' && !is_null(Persona_L::obtenerPorLegajo($this->_legajo, $this->_id, false))) {
            $this->_errores['legajo'] =_('El legajo ya existe.');
        }
    }

    /**********************************************************************
     * NOMBRE
     ***********************************************************************/
    public function getNombre() {
        return $this->_nombre;
    }
    public function setNombre($p_Nombre) {
        $p_Nombre = trim($p_Nombre);
        $this->control($p_Nombre, _('Nombre'), 2, 50);
        $this->_nombre = $p_Nombre;
    }

    /**********************************************************************
     * SEGUNDO NOMBRE
     ***********************************************************************/
    public function getSegundoNombre() {
        return $this->_segundo_nombre;
    }
    public function setSegundoNombre($p_Segundo_Nombre) {
        $p_Nombre = trim($p_Segundo_Nombre);
        $this->_segundo_nombre = $p_Nombre;
    }

    /**********************************************************************
     * APELLIDO
     ***********************************************************************/
    public function getApellido() {
        return $this->_apellido;
    }
    public function setApellido($p_Apellido) {
        $p_Apellido = trim($p_Apellido);
        $this->control($p_Apellido, _('Apellido'), 2, 50);
        $this->_apellido = $p_Apellido;
    }

    /**********************************************************************
     * NOMBRE COMPLETO
     ***********************************************************************/
    public function getNombreCompleto() {
        return $this->_nombre . " " . $this->_apellido;
    }
    public function getNombreCompletoINV() {
        if($this->_apellido == '')return $this->_nombre;
        else return $this->_apellido . ", " . $this->_nombre;
    }

    /**********************************************************************
     * FECHA NACIMIENTO
     ***********************************************************************/
    public function setFechaNacimiento($p_FechaNacimiento) {

        if($p_FechaNacimiento == ''){
            $this->_fecha_nacimiento= '0000-00-00';
            return true;
        }

        if (is_string($p_FechaNacimiento)) {
            $this->_fecha_nacimiento = date('Y-m-d', strtotime($p_FechaNacimiento));
            return true;
        }

        if (is_int($p_FechaNacimiento)) {
            $this->_fecha_nacimiento = date('Y-m-d', $p_FechaNacimiento);
            return true;
        }

        $this->_errores['fecha_nacimiento'] = "La fecha de nacimiento tiene un formato incorrecto.";


    }
    public function getFechaNacimiento($p_Format = null) {


        if (is_null($p_Format)){
            return $this->_fecha_nacimiento;
        }

        if ($this->_fecha_nacimiento == 0) {
            return '';
        }

        if (!is_string($p_Format)) {
           return '';
        }

        $var = date($p_Format, strtotime($this->_fecha_nacimiento));

        return $var;



    }


    /**********************************************************************
     * ESTADO CIVIL
     ***********************************************************************/
    public function getEstadoCivil() {
        return $this->_estado_civil;
    }
    public function setEstadoCivil($p_EstadoCivil) {
        $p_EstadoCivil = trim($p_EstadoCivil);
        $this->_estado_civil = $p_EstadoCivil;
    }

    /**********************************************************************
     * GENERO
     ***********************************************************************/
    public function getGenero() {
        return $this->_genero;
    }
    public function setGenero($p_Genero) {
        $p_Genero = trim($p_Genero);
        $this->_genero = $p_Genero;
    }

    /**********************************************************************
     * NOTAS
     ***********************************************************************/
    public function setNotas($p_Notas){
        $p_Notas = trim($p_Notas);
        $this->_notas = $p_Notas;
    }
    public function getNotas() {
        return $this->_notas;
    }

    /**********************************************************************
     * HUELLAS
     ***********************************************************************/
    public function getHuellasId() {
        //echo "id".$this->_id;
        $a_o_Huellas = Huella_L::obtenerPorPersona($this->_id);
        $string_huellas = '';
        if (!empty($a_o_Huellas)) {
            foreach ($a_o_Huellas as $o_Huella) {
                $string_huellas .= $o_Huella->getId() . "_";
            }
            $string_huellas = rtrim($string_huellas, '_');
        }
        //echo "string".$string_huellas;
        return $string_huellas;
    }

    /**********************************************************************
     * TELÉFONO DE TRABAJO
     ***********************************************************************/
    public function getTeTrabajo() {
        return $this->_te_trabajo;
    }
    public function setTeTrabajo($p_Te_trabajo) {
        $p_Te_per = trim($p_Te_trabajo);
        if ($p_Te_per != '') {
            $this->control($p_Te_per, _('Teléfono Trabajo'), 5, 15);
        }
        $this->_te_trabajo = $p_Te_per;
    }

    /**********************************************************************
     * CELULAR
     ***********************************************************************/
    public function getTeCelular() {
        return $this->_te_celular;
    }
    public function setTeCelurar($p_Te_Cel) {
        $p_Te_Cel = trim($p_Te_Cel);
        if ($p_Te_Cel != '') {
            $this->control($p_Te_Cel, _('Teléfono Celular'), 5, 15);
        }
        $this->_te_celular = $p_Te_Cel;
    }

    /**********************************************************************
     * TELÉFONO PERSONAL
     ***********************************************************************/
    public function getTeFijo() {
        return $this->_te_fijo;
    }
    public function setTeFijo($p_Te_per) {
        $p_Te_per = trim($p_Te_per);
        if ($p_Te_per != '') {
            $this->control($p_Te_per, _('Teléfono Fijo'), 5, 15);
        }
        $this->_te_fijo = $p_Te_per;
    }

    /**********************************************************************
     * EMAIL DE TRABAJO
     ***********************************************************************/
    public function getEmail() {
        return $this->_email;
    }
    public function setEmail($p_Email) {
        $p_Email = trim($p_Email);
        if ($p_Email != '') {
            $this->control($p_Email, _('E-Mail'), 4, 255);
            if (!ValidateHelper::ValidateEmail($p_Email) && $p_Email != '') {
                $this->_errores['e-mail'] = _('El E-mail') . ' \'' . $this->_email . '\' ' . ('no es valido.');
            }
        }
        $this->_email = $p_Email;
    }

    /**********************************************************************
     * EMAIL PERSONAL
     ***********************************************************************/
    public function getEmailPersonal() {
        return $this->_email_personal;
    }
    public function setEmailPersonal($p_Email) {
        $p_Email = trim($p_Email);
        if ($p_Email != '') {
            $this->control($p_Email, _('E-Mail'), 4, 255);
            if (!ValidateHelper::ValidateEmail($p_Email) && $p_Email != '') {
                $this->_errores['e-mail'] = _('El E-mail Personal') . ' \'' . $this->_email_personal . '\' ' . ('no es valido.');
            }
        }
        $this->_email_personal = $p_Email;
    }

    /**********************************************************************
     * DNI
     ***********************************************************************/
    public function getDni() {
        return $this->_dni;
    }
    public function setDni($p_Valor) {
        $p_Valor = trim($p_Valor);
        $this->_dni = $p_Valor;

        $this->control($this->_dni, _('DNI'), 8, 8);

        if ($this->_dni != '' && !is_null(Persona_L::obtenerPorDni($this->_dni, $this->_id, false))) {
            $this->_errores['dni'] = _('El DNI') . ' \'' . $this->_dni .  '\' ' . _('ya existe.');
        }
    }

    /**********************************************************************
     * NRO CONTRIBUYENTE
     ***********************************************************************/
    public function getNroContribuyente() {
        return $this->_nro_contribuyente;
    }
    public function setNroContribuyente($p_Valor) {
        $p_Valor = trim($p_Valor);
        $this->_nro_contribuyente = $p_Valor;
    }

    /**********************************************************************
     * TALLE CAMISA
     ***********************************************************************/
    public function getTalleCamisa() {
        return $this->_talle_camisa;
    }
    public function setTalleCamisa($p_Valor) {
        $p_Valor = trim($p_Valor);
        $this->_talle_camisa = $p_Valor;
    }

    /**********************************************************************
     * DIRECCIÓN 1
     ***********************************************************************/
    public function getDireccion1() {
        return $this->_direccion1;
    }
    public function setDireccion1($p_Valor) {
        $p_Valor = trim($p_Valor);
        $this->_direccion1 = $p_Valor;
    }

    /**********************************************************************
     * DIRECCIÓN 2
     ***********************************************************************/
    public function getDireccion2() {
        return $this->_direccion2;
    }
    public function setDireccion2($p_Valor) {
        $p_Valor = trim($p_Valor);
        $this->_direccion2 = $p_Valor;
    }

    /**********************************************************************
     * CIUDAD
     ***********************************************************************/
    public function getCiudad() {
        return $this->_ciudad;
    }
    public function setCiudad($p_Valor) {
        $p_Valor = trim($p_Valor);
        $this->_ciudad = $p_Valor;
    }

    /**********************************************************************
     * PROVINCIA
     ***********************************************************************/

    public function getProvincia() {
        return $this->_provincia;
    }
    public function setProvincia($p_Valor) {
        $p_Valor = trim($p_Valor);
        $this->_provincia = $p_Valor;
    }

    /**********************************************************************
     * CODIGO POSTAL
     ***********************************************************************/
    public function getCodigoPostal() {
        return $this->_codigo_postal;
    }
    public function setCodigoPostal($p_Valor) {
        $p_Valor = trim($p_Valor);
        $this->_codigo_postal = $p_Valor;
    }

    /**********************************************************************
     * PAIS
     ***********************************************************************/
    public function getPais() {
        return $this->_pais;
    }
    public function setPais($p_Valor) {
        $p_Valor = trim($p_Valor);
        $this->_pais = $p_Valor;
    }

    /**********************************************************************
     * LINKEDIN
     ***********************************************************************/
    public function getLinkedin() {
        return $this->_linkedin;
    }
    public function setLinkedin($p_Valor) {
        $p_Valor = trim($p_Valor);
        $this->_linkedin = $p_Valor;
    }

    /**********************************************************************
     * TWITTER
     ***********************************************************************/
    public function getTwitter() {
        return $this->_twitter;
    }
    public function setTwitter($p_Valor) {
        $p_Valor = trim($p_Valor);
        $this->_twitter = $p_Valor;
    }

    /**********************************************************************
     * FACEBOOK
     ***********************************************************************/
    public function getFacebook() {
        return $this->_facebook;
    }
    public function setFacebook($p_Valor) {
        $p_Valor = trim($p_Valor);
        $this->_facebook = $p_Valor;
    }

    /**********************************************************************
     * TAG
     ***********************************************************************/
    public function getTag() {
        return $this->_tag;
    }
    public function setTag($p_Valor) {
        $p_Valor = trim($p_Valor);

        if ($p_Valor != '' || $p_Valor != 0) {
            $this->_tag = $p_Valor;

            $this->control($this->_tag, _('TAG'), 10, 10);
            if (!ctype_xdigit($this->_tag)) {
                $this->_errores['tag'] = _('El TAG') . ' \'' . $this->_tag . '\' ' . _('debe ser hexadecimal.');
                return;
            }
            if (!is_null(Persona_L::obtenerPorTag($this->_tag, $this->_id,false))) {
                $this->_errores['tag'] = _('El TAG') . ' \'' . $this->_tag . '\' ' . _('ya existe.');
            }
        }
    }
    public function removeTag() {
        $this->_tag = '';
    }

    /**********************************************************************
     * ESTADO
     ***********************************************************************/
    public function getEstado() {

        $p_estado = "Inactivo";

        if(!$this->_excluir){
            $p_estado = "Activo";
        }

        return $p_estado;
    }
    public function setEstado($p_Estado) {

        $this->_estado = $p_Estado;


        // ACTIVO
        if ($p_Estado == "Activo") {
            $this->setEnabled(1);
            $this->setExcluir(0);
        }
        else{
            $this->setEnabled(0);
            $this->setExcluir(1);
        }
    }

    /**********************************************************************
     * EXCLUIR
     ***********************************************************************/
    public function getExcluir() {
        return $this->_excluir;
    }
    public function setExcluir($p_Excluir) {
        $this->_excluir = (int)$p_Excluir;
    }

    /**********************************************************************
     * EQUIPOS
     ***********************************************************************/
    public function getEquipos() {
        return $this->_equipos;
    }
    public function setEquipos($p_Equipos) {
        $p_Equipos = trim($p_Equipos);
        //$this->control($p_Cadena_Sync, 'Cadena Sync', 5, 100);
        $this->_equipos = $p_Equipos;
    }

    /**********************************************************************
     * IMAGEN
     ***********************************************************************/
    public function getImagen() {
        return $this->_imagen;
    }
    public function setImagen($p_Imagen) {
        $p_Imagen = (string)$p_Imagen;
        $this->_imagen = $p_Imagen;
    }
    public function getImagenURL() {
        return $this->_imagen_url;
    }
    public function setImagenURL($p_Imagen) {
        $p_Imagen = (string)$p_Imagen;
        $this->_imagen_url = $p_Imagen;
    }

    /**********************************************************************
     * HABILITAR
     ***********************************************************************/
    public function getEnabled() {
        return $this->_enabled;
    }
    public function setEnabled($p_Enabled) {
        $p_Enabled = (integer)$p_Enabled;
        $this->_enabled = $p_Enabled;
    }

    /**********************************************************************
     * CREADO POR USUARIO
     ***********************************************************************/
    public function setCreadoUsuarioId($p_Id) {
        $p_Id = (integer)$p_Id;
        $this->seleccionado($p_Id, _('Creado por el Usuario'));
        $this->_creado_usu_id = $p_Id;
    }
    public function getCreadoUsuarioId() {
        return $this->_creado_usu_id;
    }
    public function getCreadoEl($pFormat = null) {
        if (!is_null($pFormat) && is_string($pFormat)) {
            if (is_null($this->_creado_el)) {
                return '';
            } else {
                return date($pFormat, $this->_creado_el);
            }
        }
        return $this->_creado_el;
    }

    /**********************************************************************
     * BLOQUEAR
     ***********************************************************************/
    public function getBloqueadoUsuarioId() {
        return $this->_bloqueado_usu_id;
    }
    public function setBloqueadoUsuarioId($p_Id) {
        $p_Id = (integer)$p_Id;
        $this->_bloqueado_usu_id = $p_Id;
    }
    public function getBloqueadoEl($pFormat = null) {
        if (!is_null($pFormat) && is_string($pFormat)) {
            if (is_null($this->_bloqueado_el)) {
                return null;
            } else {
                return date($pFormat, $this->_bloqueado_el);
            }
        }
        return $this->_bloqueado_el;
    }

    /**********************************************************************
     * VÍNCULOS CON OTROS OBJETOS
     ***********************************************************************/
    public function getHoraTrabajoObject() {
        if (is_null($this->_hora_trabajo_object) && $this->_hor_id > 0) {
            switch ($this->_hor_tipo) {
                case HORARIO_NORMAL:
                    $this->_hora_trabajo_object = Hora_Trabajo_L::obtenerPorId($this->_hor_id);
                    break;
                case HORARIO_FLEXIBLE:
                    $this->_hora_trabajo_object = Horario_Flexible_L::obtenerPorId($this->_hor_id);
                    break;
                case HORARIO_ROTATIVO:
                    $this->_hora_trabajo_object = Horario_Rotativo_L::obtenerPorId($this->_hor_id);
                    break;
            }

        }
        return $this->_hora_trabajo_object;
    }
    public function getCreadoUsuarioObject() {
        if (is_null($this->_creado_usu_object) && $this->_creado_usu_id > 0) {
            $this->_creado_usu_object = Usuario_L::obtenerPorId($this->_creado_usu_id);
        }
        return $this->_creado_usu_object;
    }
    public function getEliminadoUsuarioObject() {
        if (is_null($this->_eliminado_usu_object) && $this->_bloqueado_usu_id > 0) {
            $this->_eliminado_usu_object = Usuario_L::obtenerPorId($this->_bloqueado_usu_id);
        }
        return $this->_eliminado_usu_object;
    }

    /**********************************************************************
     * ELIMINAR
     ***********************************************************************/
    public function delete($p_Debug = false) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if (!$this->esValido()) {
            return false;
        }

        if ($this->_id == 0) {
            return false;
        }


        $this->_eliminada_el = time();

        $datos['per_Eliminada_Usu_Id'] = $this->_eliminada_usu_id;
        $datos['per_Eliminada'] = 1;
        $datos['per_Eliminada_Date'] = date('Y-m-d H:i:s', $this->_eliminada_el);

        if(trim($this->_equipos) == ''){  //la persona ya no esta sincronizada con ningun equipo, la puedo borrar.
            $resultado = $cnn->Delete('personas', "per_Id = " . $this->_id);
        }else{
            $resultado = $cnn->Update('personas', $datos, "per_Id = {$this->_id}");   //sino, pongo el elmiinado en 1
        }



        if ($resultado === false) {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
            return false;
        }

        return true;
    }
    public function undelete($p_Debug) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($this->_id == 0) {
            return false;
        }

        $this->_eliminado_el = null;

        $datos['per_Eliminada_Usu_Id'] = 0;
        $datos['per_Eliminada'] = 0;
        $datos['per_Eliminada_Date'] = $this->_eliminada_el;

        $resultado = $cnn->Update('personas', $datos, "per_Id = {$this->_id}");

        if ($resultado === false) {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
            return false;
        }

        return true;
    }
    public function setEliminada($p_Eliminada) {
        $this->_eliminada = (int)$p_Eliminada;
    }
    public function getEliminada() {
        return $this->_eliminada;
    }
    public function getEliminadoEl($pFormat = null) {
        if (!is_null($pFormat) && is_string($pFormat)) {
            if (is_null($this->_eliminada_el)) {
                return '';
            } else {
                return date($pFormat, $this->_eliminada_el);
            }
        }
        return $this->_eliminada_el;
    }
    public function setEliminadoUsuarioId($p_Id) {
        $p_Id = (integer)$p_Id;
        $this->seleccionado($p_Id, _('Eliminado por el Usuario'));
        $this->_eliminada_usu_id = $p_Id;
    }
    public function getEliminadoUsuarioId() {
        return $this->_eliminada_usu_id;
    }

    /**********************************************************************
     * FECHA MODIFICACIÓN
     ***********************************************************************/
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

    /**********************************************************************
     * EMPRESA
     ***********************************************************************/
    public function getEmpresa() {
        return $this->_empresa;
    }
    public function setEmpresa($p_Empresa) {
        $this->_empresa = (int)$p_Empresa;
    }

    /**********************************************************************
     * VALIDAR DATOS
     ***********************************************************************/

    /**
     * Devuelve TRUE/FALSE dependiendo de si el objeto es valido o no.
     *
     * @return boolean
     */
    public function esValido() {
        //Si el array errores no tiene elementos entonces el objeto es valido.

        return count($this->_errores) == 0;
    }
    public function getErrores() {
        return $this->_errores;
    }
    public function setErrores($p_Nombre, $p_Error) {
        $this->_errores[$p_Nombre] = trim($p_Error);
    }

    /**********************************************************************
     * BLOQUEAR
     ***********************************************************************/
    public function bloquear($p_Debug=false) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if (!$this->esValido()) {
            return false;
        }

        if ($this->_id == 0) {
            return false;
        }

        $this->_bloqueado_el = time();

        $datos['per_Enable_Usu_Id'] = $this->_bloqueado_usu_id;
        $datos['per_Enable'] = 0;
        $datos['per_Enable_Date'] = date('Y-m-d H:i:s', $this->_bloqueado_el);

        $resultado = $cnn->Update('personas', $datos, "per_Id = {$this->_id}");

        if ($resultado === false) {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
            return false;
        }

        return true;
    }
    public function desbloquear($p_Debug = false) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if ($this->_id == 0) {
            return false;
        }

        $this->_bloqueado_el = null;

        $datos['per_Enable_Usu_Id'] = 0;
        $datos['per_Enable'] = 1;
        $datos['per_Enable_Date'] =  $this->_bloqueado_el;

        $resultado = $cnn->Update('personas', $datos, "per_Id = {$this->_id}");

        if ($resultado === false) {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
            return false;
        }

        return true;
    }

    /**********************************************************************
     * USER
     ***********************************************************************/
    public function setUserActivo($p_User_Enabled) {
        $p_User_Enabled = (integer)$p_User_Enabled;
        $this->_user_enabled = $p_User_Enabled;
    }
    public function getUserActivo() {
        return $this->_user_enabled;
    }

    /**********************************************************************
     * USER
     ***********     $this->_user_id = (integer)$p_Datos["per_User_Id"]; ************************************************************/
    public function setUserId($p_User_Id) {
        $p_User_Id = (integer)$p_User_Id;
        $this->_user_id = $p_User_Id;
    }
    public function getUserId() {
        return $this->_user_id;
    }

    /**********************************************************************
     * EQUIPOS
     ***********************************************************************/
    public function deleteEquipo($equipoID) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if (!$this->esValido()) {
            return false;
        }

        if ($this->_id == 0) {
            return false;
        }


        $array_equipos = explode(':', $this->getEquipos());

        $array_equipos_nuevo = array_diff($array_equipos, array($equipoID));

        $datos['per_Equipos'] = implode(':',$array_equipos_nuevo);
        $this->_equipos = $datos['per_Equipos'];


        if(trim($this->_equipos) == ''){  //la persona ya no esta sincronizada con ningun equipo, la puedo borrar.
            $resultado = $cnn->Delete('personas', "per_Id = " . $this->_id);
        }else{
            $resultado = $cnn->Update('personas', $datos, "per_Id = {$this->_id}");   //sino, pongo el elmiinado en 1
        }



        if ($resultado === false) {
            $this->_errores['mysql'] = $cnn->get_Error(false);
            return false;
        }

        return true;
    }
    public function Purge($p_Debug) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;
        $resultado = '';

        if ($this->_id == 0) {
            return false;
        }

        $resultado .= $cnn->Delete('personas', "per_Id = " . $this->_id);

        if ($resultado === false) {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
            return false;
        }

        return true;
    }


    /**
     * Actualiza algunos datos de la persona desde un array
     * Se utiliza en la API
     *
     * @param $p_Datos array()
     *
     * @return array()
     */
    public function fromArray($p_Datos) {


        if(array_key_exists('legajo',$p_Datos))$this::setLegajo((string)$p_Datos["legajo"]);
        if(array_key_exists('dni',$p_Datos))$this::setDni((string)$p_Datos["dni"]);
        if(array_key_exists('tag',$p_Datos))$this::setTag((string)$p_Datos["tag"]);
        if(array_key_exists('horario_tipo',$p_Datos))$this::setHorTipo((integer)$p_Datos["horario_tipo"]);
        if(array_key_exists('horario_id',$p_Datos))$this::setHorId((integer)$p_Datos["horario_id"]);
        if(array_key_exists('apellido',$p_Datos))$this::setApellido((string)$p_Datos["apellido"]);
        if(array_key_exists('nombre',$p_Datos))$this::setNombre((string)$p_Datos["nombre"]);
        if(array_key_exists('telefono_celular',$p_Datos))$this::setTeCelurar((string)$p_Datos["telefono_celular"]);
        if(array_key_exists('telefono_fijo',$p_Datos))$this::setTeFijo((string)$p_Datos["telefono_fijo"]);
        if(array_key_exists('email',$p_Datos))$this::setEmail((string)$p_Datos["email"]);
        if(array_key_exists('exluir',$p_Datos))$this::setExcluir((integer)$p_Datos["exluir"]);
        if(array_key_exists('equipos',$p_Datos))$this::setEquipos((string)$p_Datos["equipos"]);
        if(array_key_exists('empresa',$p_Datos))$this::setEmpresa((integer)$p_Datos["empresa"]);
        if(array_key_exists('enabled',$p_Datos))$this::setEnabled((integer)$p_Datos["enabled"]);
        if(array_key_exists('fechaD',$p_Datos))$this::setFechaD($p_Datos["fechaD"]." 00:00:00");
        if(array_key_exists('fechaH',$p_Datos))$this::setFechaH($p_Datos["fechaH"]." 23:59:59");
        if(array_key_exists('RID',$p_Datos))$this::setRID($p_Datos["RID"]);
        if(array_key_exists('notas',$p_Datos))$this::setNotas($p_Datos["notas"]);
        else $this::setNotas(' ');

    }

    /**
     * Devuelve un array con algunos datos de la persona
     * Se utiliza en la API
     *
     * @param $incluir_imagen Bool incluye la url y datos de la imagen
     *
     * @return array()
     */
    public function toArray($incluir_imagen = false) {
        $array = array();
        $array['id'] = $this->_id;
        $array['legajo'] = $this->_legajo;
        $array['dni'] = $this->_dni;
        $array['tag'] = $this->_tag;
        $array['horario_tipo'] = $this->_hor_tipo;
        $array['horario_id'] = $this->_hor_id;
        $array['apellido'] = $this->_apellido;
        $array['nombre'] = $this->_nombre;
        $array['telefono_celular'] = $this->_te_celular;
        $array['telefono_fijo'] = $this->_te_fijo;
        $array['email'] = $this->_email;
        if($incluir_imagen) {
            $array['imagen_url'] = $this->_imagen_url;
            if($this->_imagen!='')
                $array['imagen_data'] = $fileContents = base64_encode(file_get_contents($this->_imagen));
        }
        $array['exluir'] = $this->_excluir;
        $array['equipos'] = $this->_equipos;
        $array['empresa'] = $this->_empresa;
        $array['enabled'] = $this->_enabled;
        $array['fechaD'] = $this->_fechaD;
        $array['fechaH'] = $this->_fechaH;
        $array['RID'] = $this->_RID;
        return $array;
    }

    /**********************************************************************
     * SYNC
     ***********************************************************************/

    /**
     * Actualiza los datos de la persona desde un array
     * Se utiliza en el proceso de sincronizacion
     *
     * @param $p_Datos array()
     *
     * @return array()
     */
    public function fromSyncArray($p_Datos) {


        if(array_key_exists('per_Legajo',$p_Datos))$this::setLegajo((string)$p_Datos["per_Legajo"]);
        if(array_key_exists('per_Dni',$p_Datos))$this::setDni((string)$p_Datos["per_Dni"]);
        if(array_key_exists('per_Tag',$p_Datos))$this::setTag((string)$p_Datos["per_Tag"]);
        if(array_key_exists('per_Hor_Tipo',$p_Datos))$this::setHorTipo((integer)$p_Datos["per_Hor_Tipo"]);
        if(array_key_exists('per_Hor_Id',$p_Datos))$this::setHorId((integer)$p_Datos["per_Hor_Id"]);
        if(array_key_exists('per_Apellido',$p_Datos))$this::setApellido((string)$p_Datos["per_Apellido"]);
        if(array_key_exists('per_Nombre',$p_Datos))$this::setNombre((string)$p_Datos["per_Nombre"]);
        if(array_key_exists('per_Imagen',$p_Datos))$this::setImagen(base64_decode($p_Datos["per_Imagen"]));
        if(array_key_exists('per_Fecha_Mod',$p_Datos))$this::setFechaMod((integer)$p_Datos["per_Fecha_Mod"]);
        if(array_key_exists('per_Enabled',$p_Datos))$this::setEnabled((integer)$p_Datos["per_Enabled"]);


    }

    /**
     * Devuelve un array con los datos de la persona para sync
     * Se utiliza en el proceso de sincronizacion
     *
     * @return array()
     */
    public function toSyncArray() {
        $array = array();
        $array['per_Id'] = $this->_id;
        $array['per_Legajo'] = $this->_legajo;
        $array['per_Dni'] = $this->_dni;
        $array['per_Tag'] = $this->_tag;
        $array['per_Hor_Tipo'] = $this->_hor_tipo;
        $array['per_Hor_Id'] = $this->_hor_id;
        $array['per_Apellido'] = $this->_apellido;
        $array['per_Nombre'] = $this->_nombre;
        if($this->getImagen()!='')
            $array['per_Imagen'] = base64_encode(file_get_contents($this->_imagen));
        else
            $array['per_Imagen'] = '';
        $array['per_Fecha_Mod'] = strtotime($this->_fecha_mod);
        $array['per_Enabled'] = $this->_enabled;
        $array['per_Eliminada'] = $this->_eliminada;

        return $array;
    }

    /**********************************************************************
     * SAVE
     ***********************************************************************/
    public function save($p_Debug=false, $p_Max_Persona = 0) {
        /* @var $cnn mySQL */
        $cnn = Registry::getInstance()->DbConn;

        if (!$this->esValido()) {
            return false;
        }


        $this->_fecha_mod = date("Y-m-d H:i:s");


        $datos = array(
            //vinculos
            'per_Hor_Tipo'          => $this->_hor_tipo,
            'per_Hor_Id'            => $this->_hor_id,
            //datos
            'per_Legajo'            =>  $this->_legajo,
            'per_Nombre'            =>  $this->_nombre,
            'per_Apellido'          =>  $this->_apellido,
            'per_Te_Celular'        =>  $this->_te_celular,
            'per_Te_Fijo'           =>  $this->_te_fijo,
            'per_E_Mail'            =>  $this->_email,
            'per_Dni'               =>  $this->_dni,
            'per_Tag'               =>  $this->_tag,
            'per_Notas'             =>  $this->_notas,
            'per_Excluir'           =>  $this->_excluir,
            'per_Equipos'           =>  $this->_equipos,
            'per_Imagen'            =>  $this->_imagen,
            'per_Imagen_URL'        =>  $this->_imagen_url,
            //user
            'per_User_Enabled'      =>  $this->_user_enabled,
            'per_User_Id'           =>  $this->_user_id,

            'per_Fecha_Mod'         =>  $this->_fecha_mod,
            'per_Empresa'           =>  $this->_empresa,
            'per_Eliminada'         =>  $this->_eliminada,
            'per_Enable'            =>  $this->_enabled,
            'per_fechaD'            =>  $this->_fechaD,
            'per_fechaH'            =>  $this->_fechaH,
            'per_RID'               =>  $this->_RID,

            //new vars
            'per_Segundo_Nombre'    =>  $this->_segundo_nombre,
            'per_Fecha_Nacimiento'  =>  $this->_fecha_nacimiento,
            'per_Genero'            =>  $this->_genero,
            'per_Estado_Civil'      =>  $this->_estado_civil,
            'per_Nro_Contribuyente' =>  $this->_nro_contribuyente,
            'per_Talle_Camisa'      =>  $this->_talle_camisa,

            'per_Direccion_1'       =>  $this->_direccion1,
            'per_Direccion_2'       =>  $this->_direccion2,
            'per_Ciudad'            =>  $this->_ciudad,
            'per_Provincia'         =>  $this->_provincia,
            'per_Codigo_Postal'     =>  $this->_codigo_postal,
            'per_Pais'              =>  $this->_pais,

            'per_Te_Trabajo'        =>  $this->_te_trabajo,

            'per_Linkedin'          =>  $this->_linkedin,
            'per_Twitter'           =>  $this->_twitter,
            'per_Facebook'          =>  $this->_facebook,

            'per_Email_Personal'    =>  $this->_email_personal


        );

        // TODO: AGREGAR ESTOS DATOS ESTANDO SEGURO
        /*
        if(isset($_POST['bloqueado']) && $_POST['bloqueado']="on"){
            $this->_bloqueado_el = time();
            $datos['per_Enable_Usu_Id']=$this->$this->_bloqueado_usu_id;
            $datos['per_Enable']=0;
            $datos['per_Enable_Date']=date('Y-m-d H:i:s', $this->_bloqueado_el);
        }
        */

        if ($this->_id == 0) {
            $this->_creado_el = time();

            $datos['per_Creado_Usu_Id'] = $this->_creado_usu_id;
            $datos['per_Creado'] = date('Y-m-d H:i:s', $this->_creado_el);

            $resultado = $cnn->Insert('personas', $datos);

            if ($resultado !== false) {
                $this->_id = $cnn->Devolver_Insert_Id();
            }
        } else {
            //print_r($datos);
            $resultado = $cnn->Update('personas', $datos, "per_Id = {$this->_id}");
        }
        if ($resultado === false) {
            $this->_errores['mysql'] = $cnn->get_Error($p_Debug);
        }

        return $resultado;
    }

    /**********************************************************************
     * DATOS DESDE ARRAY
     ***********************************************************************/
    public function loadArray($p_Datos) {


        $this->_id                      = (integer)$p_Datos["per_Id"];
        //vinculos
        $this->_hor_tipo                = (integer)$p_Datos["per_Hor_Tipo"];
        $this->_hor_id                  = (integer)$p_Datos["per_Hor_Id"];
        //datos
        $this->_legajo                  = (string)$p_Datos["per_Legajo"];
        $this->_nombre                  = (string)$p_Datos["per_Nombre"];
        $this->_apellido                = (string)$p_Datos["per_Apellido"];
        $this->_te_celular              = (string)$p_Datos["per_Te_Celular"];
        $this->_te_fijo                 = (string)$p_Datos["per_Te_Fijo"];
        $this->_email                   = (string)$p_Datos["per_E_Mail"];
        $this->_dni                     = (string)$p_Datos["per_Dni"];
        $this->_tag                     = (string)$p_Datos["per_Tag"];
        $this->_notas                   = (string)$p_Datos["per_Notas"];
        $this->_excluir                 = (int)$p_Datos["per_Excluir"];
        $this->_equipos                 = (string)$p_Datos["per_Equipos"];
        $this->_imagen                  = (string)$p_Datos["per_Imagen"];
        $this->_imagen_url              = (string)$p_Datos["per_Imagen_URL"];
        $this->_fechaD                  = (string)$p_Datos["per_fechaD"];
        $this->_fechaH                  = (string)$p_Datos["per_fechaH"];
        $this->_RID                     = (string)$p_Datos["per_RID"];
        //control
        $this->_creado_usu_id           = (integer)$p_Datos["per_Creado_Usu_Id"];
        $this->_creado_el               = strtotime($p_Datos["per_Creado"]);
        $this->_bloqueado_usu_id        = (integer)$p_Datos["per_Enable_Usu_Id"];
        $this->_bloqueado_el            = (is_null($p_Datos["per_Enable_Date"])) ? null : strtotime($p_Datos["per_Enable_Date"]);
        $this->_enabled                 = (integer)$p_Datos["per_Enable"];
        //user
        $this->_user_enabled            = (integer)$p_Datos["per_User_Enabled"];
        $this->_user_id                 = (integer)$p_Datos["per_User_Id"];
        //
        $this->_fecha_mod               = (string)$p_Datos["per_Fecha_Mod"];
        $this->_empresa                 = (integer)$p_Datos["per_Empresa"];
        $this->_eliminada               = (integer)$p_Datos["per_Eliminada"];
        $this->_eliminada_usu_id        = (integer)$p_Datos["per_Eliminada_Usu_Id"];
        $this->_eliminada_el            = (is_null($p_Datos["per_Eliminada_Date"])) ? null : strtotime($p_Datos["per_Eliminada_Date"]);

        // new vars
        $this->_segundo_nombre          = (string)$p_Datos["per_Segundo_Nombre"];
        $this->_fecha_nacimiento        = (string)$p_Datos["per_Fecha_Nacimiento"];
        $this->_genero                  = (string)$p_Datos["per_Genero"];
        $this->_estado_civil            = (string)$p_Datos["per_Estado_Civil"];
        $this->_nro_contribuyente       = (string)$p_Datos["per_Nro_Contribuyente"];
        $this->_talle_camisa            = (string)$p_Datos["per_Talle_Camisa"];

        $this->_direccion1              = (string)$p_Datos["per_Direccion_1"];
        $this->_direccion2              = (string)$p_Datos["per_Direccion_2"];
        $this->_ciudad                  = (string)$p_Datos["per_Ciudad"];
        $this->_provincia               = (string)$p_Datos["per_Provincia"];
        $this->_codigo_postal           = (string)$p_Datos["per_Codigo_Postal"];
        $this->_pais                    = (string)$p_Datos["per_Pais"];

        $this->_te_trabajo              = (string)$p_Datos["per_Te_Trabajo"];

        $this->_linkedin                = (string)$p_Datos["per_Linkedin"];
        $this->_twitter                 = (string)$p_Datos["per_Twitter"];
        $this->_facebook                = (string)$p_Datos["per_Facebook"];

        $this->_email_personal          = (string)$p_Datos["per_Email_Personal"];

        $this->_estado                  = $this->_excluir == 0 &&  $this->_enabled == 1 ? "Activo" : "Inactivo" ;

        //error
        $this->_errores = array();

        $this->set_edad();

    }
    public function loadArrayExcel($p_Datos = array()) {


        $_legajo                  = isset($p_Datos["Legajo"])                 ?  $p_Datos["Legajo"]   : "" ;
        $this->setLegajo($_legajo);
        $_RID                     = isset($p_Datos["Id Alternativo"])         ?  $p_Datos["Id Alternativo"]   : "" ;
        $this->setRID($_RID);
        $_nombre                  = isset($p_Datos["Nombre"])                 ?  $p_Datos["Nombre"]   : "" ;
        $this->setNombre($_nombre);
        $_segundo_nombre          = isset($p_Datos["Segundo Nombre"])         ?  $p_Datos["Segundo Nombre"]   : "" ;
        $this->setSegundoNombre($_segundo_nombre);
        $_apellido                = isset($p_Datos["Apellido"])               ?  $p_Datos["Apellido"]   : "" ;
        $this->setApellido($_apellido);


        $_fecha_nacimiento        = isset($p_Datos["Fecha Nacimiento"])       ?  $p_Datos["Fecha Nacimiento"]   : "" ;
        $this->setFechaNacimiento($_fecha_nacimiento);
        $_genero                  = isset($p_Datos["Género"])                 ?  $p_Datos["Género"]   : "" ;
        $this->setGenero($_genero);
        $_dni                     = isset($p_Datos["Dni"])                    ?  $p_Datos["Dni"]   : "" ;
        $this->setDni($_dni);
        $_estado_civil            = isset($p_Datos["Estado Civil"])           ?  $p_Datos["Estado Civil"]   : "" ;
        $this->setEstadoCivil($_estado_civil);

        $_hor_tipo                = isset($p_Datos["Tipo De Horario"])        ?  $p_Datos["Tipo De Horario"]   : "" ;
        $this->setHorTipo($_hor_tipo);
        $_hor_id                  = isset($p_Datos["Horario"])                ?  $p_Datos["Horario"]   : "" ;
        $this->setHorId($_hor_id);

        $_te_celular              = isset($p_Datos["Teléfono Celular"])       ?  $p_Datos["Teléfono Celular"]   : "" ;
        $this->setTeCelurar($_te_celular);
        $_te_fijo                 = isset($p_Datos["Teléfono Fijo"])          ?  $p_Datos["Teléfono Fijo"]   : "" ;
        $this->setTeFijo($_te_fijo);

        $_email                   = isset($p_Datos["Email"])                  ?  $p_Datos["Email"]   : "" ;
        $this->setEmail($_email);
        $_email_personal          = isset($p_Datos["Email Personal"])         ?  $p_Datos["Email Personal"]   : "" ;
        $this->setEmailPersonal($_email_personal);
        $_tag                     = isset($p_Datos["Tag"])                    ?  $p_Datos["Tag"]   : "" ;
        $this->setTag($_tag);

        $_equipos                 = isset($p_Datos["Equipos"])                ?  $p_Datos["Equipos"]   : "" ;
        //$this->setEquipos($_equipos);
        $_imagen_url              = isset($p_Datos["Imágen"])                 ?  $p_Datos["Imágen"]   : "" ;
        //$this->setImagenURL($_imagen_url);
        $_fechaD                  = isset($p_Datos["Inicio De Actividad"])    ?  $p_Datos["Inicio De Actividad"]   : "" ;
        $this->setFechaD($_fechaD);
        $_fechaH                  = isset($p_Datos["Fin De Actividad"])       ?  $p_Datos["Fin De Actividad"]   : "" ;
        $this->setFechaH($_fechaH);

        $_nro_contribuyente       = isset($p_Datos["Nro. Contribuyente"])     ?  $p_Datos["Nro. Contribuyente"]   : "" ;
        $this->setNroContribuyente($_nro_contribuyente);
        $_talle_camisa            = isset($p_Datos["Talle Camisa"])           ?  $p_Datos["Talle Camisa"]   : "" ;
        $this->setTalleCamisa($_talle_camisa);


        $_direccion1              = isset($p_Datos["Direción 1"])             ?  $p_Datos["Direción 1"]   : "" ;
        $this->setDireccion1($_direccion1);
        $_direccion2              = isset($p_Datos["Direción 2"])             ?  $p_Datos["Direción 2"]   : "" ;
        $this->setDireccion2($_direccion2);
        $_ciudad                  = isset($p_Datos["Ciudad"])                 ?  $p_Datos["Ciudad"]   : "" ;
        $this->setCiudad($_ciudad);
        $_provincia               = isset($p_Datos["Provincia"])              ?  $p_Datos["Provincia"]   : "" ;
        $this->setProvincia($_provincia);
        $_codigo_postal           = isset($p_Datos["Código Postal"])          ?  $p_Datos["Código Postal"]   : "" ;
        $this->setCodigoPostal($_codigo_postal);
        $_pais                    = isset($p_Datos["País"])                   ?  $p_Datos["País"]   : "" ;
        $this->setPais($_pais);
        $_te_trabajo              = isset($p_Datos["Teléfono Trabajo"])       ?  $p_Datos["Teléfono Trabajo"]   : "" ;
        $this->setTeTrabajo($_te_trabajo);

        $_linkedin                = isset($p_Datos["Linkedin"])               ?  $p_Datos["Linkedin"]   : "" ;
        $this->setLinkedin($_linkedin);
        $_twitter                 = isset($p_Datos["Twitter"])                ?  $p_Datos["Twitter"]   : "" ;
        $this->setTwitter($_twitter);
        $_facebook                = isset($p_Datos["Facebook"])               ?  $p_Datos["Facebook"]   : "" ;
        $this->setFacebook($_facebook);


        $_estado                  = isset($p_Datos["Estado"]) ? $p_Datos["Estado"] : "Activo" ;
        $this->setEstado($_estado);
        $_empresa                 = isset($p_Datos["per_Empresa"])              ?  $p_Datos["per_Empresa"]   : "" ;
        $this->setEmpresa($_empresa);

        $this->set_edad();

    }

}
