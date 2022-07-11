<?php

require_once dirname(__FILE__) . '/../../_ruta.php';


use google\appengine\api\cloud_storage\CloudStorageTools;
$T_sync_checker = '';
$T_sync_js_start = '';

$Botones_Exportar   = true;

$T_Titulo                   = _('Personas');
$Item_Name                  = "persona";
$T_Titulo_Singular          = "persona";
$T_Titulo_Pre               = "una";
$T_Script                   = 'persona';

$T_Mensaje                  = '';


$T_sync_checker             = '';
$T_sync_js_start            = '';
$Reporte_Nombre             = 'Listado de personas - '.date('d-y-Y H:i:s');;
$path                       = "";
$filename                   = "";
$o_Listado                  = array();
$_Listado_Paises            = [
    "Afganistán",
    "Albania",
    "Alemania",
    "Andorra",
    "Angola",
    "Antigua y Barbuda",
    "Arabia Saudita",
    "Argelia",
    "Argentina",
    "Armenia",
    "Australia",
    "Austria",
    "Azerbaiyán",
    "Bahamas",
    "Bangladés",
    "Barbados",
    "Baréin",
    "Bélgica",
    "Belice",
    "Benín",
    "Bielorrusia",
    "Birmania",
    "Bolivia",
    "Bosnia y Herzegovina",
    "Botsuana",
    "Brasil",
    "Brunéi",
    "Bulgaria",
    "Burkina Faso",
    "Burundi",
    "Bután",
    "Cabo Verde",
    "Camboya",
    "Camerún",
    "Canadá",
    "Catar",
    "Chad",
    "Chile",
    "China",
    "Chipre",
    "Ciudad del Vaticano",
    "Colombia",
    "Comoras",
    "Corea del Norte",
    "Corea del Sur",
    "Costa de Marfil",
    "Costa Rica",
    "Croacia",
    "Cuba",
    "Dinamarca",
    "Dominica",
    "Ecuador",
    "Egipto",
    "El Salvador",
    "Emiratos Árabes Unidos",
    "Eritrea",
    "Eslovaquia",
    "Eslovenia",
    "España",
    "Estados Unidos",
    "Estonia",
    "Etiopía",
    "Filipinas",
    "Finlandia",
    "Fiyi",
    "Francia",
    "Gabón",
    "Gambia",
    "Georgia",
    "Ghana",
    "Granada",
    "Grecia",
    "Guatemala",
    "Guyana",
    "Guinea",
    "Guinea-Bisáu",
    "Guinea Ecuatorial",
    "Haití",
    "Honduras",
    "Hungría",
    "India",
    "Indonesia",
    "Irak",
    "Irán",
    "Irlanda",
    "Islandia",
    "Islas Marshall",
    "Islas Salomón",
    "Israel",
    "Italia",
    "Jamaica",
    "Japón",
    "Jordania",
    "Kazajistán",
    "Kenia",
    "Kirguistán",
    "Kiribati",
    "Kuwait",
    "Laos",
    "Lesoto",
    "Letonia",
    "Líbano",
    "Liberia",
    "Libia",
    "Liechtenstein",
    "Lituania",
    "Luxemburgo",
    "Macedonia del Norte",
    "Madagascar",
    "Malasia",
    "Malaui",
    "Maldivas",
    "Malí",
    "Malta",
    "Marruecos",
    "Mauricio",
    "Mauritania",
    "México",
    "Micronesia",
    "Moldavia",
    "Mónaco",
    "Mongolia",
    "Montenegro",
    "Mozambique",
    "Namibia",
    "Nauru",
    "Nepal",
    "Nicaragua",
    "Níger",
    "Nigeria",
    "Noruega",
    "Nueva Zelanda",
    "Omán",
    "Países Bajos",
    "Pakistán",
    "Palaos",
    "Panamá",
    "Papúa Nueva Guinea",
    "Paraguay",
    "Perú",
    "Polonia",
    "Portugal",
    "Reino Unido de Gran Bretaña e Irlanda del Norte",
    "República Centroafricana",
    "República Checa",
    "República del Congo",
    "República Democrática del Congo",
    "República Dominicana",
    "República Sudafricana",
    "Ruanda",
    "Rumanía",
    "Rusia",
    "Samoa",
    "San Cristóbal y Nieves",
    "San Marino",
    "San Vicente y las Granadinas",
    "Santa Lucía",
    "Santo Tomé y Príncipe",
    "Senegal",
    "Serbia",
    "Seychelles",
    "Sierra Leona",
    "Singapur",
    "Siria",
    "Somalia",
    "Sri Lanka",
    "Suazilandia",
    "Sudán",
    "Sudán del Sur",
    "Suecia",
    "Suiza",
    "Surinam",
    "Tailandia",
    "Tanzania",
    "Tayikistán",
    "Timor Oriental",
    "Togo",
    "Tonga",
    "Trinidad y Tobago",
    "Túnez",
    "Turkmenistán",
    "Turquía",
    "Tuvalu",
    "Ucrania",
    "Uganda",
    "Uruguay",
    "Uzbekistán",
    "Vanuatu",
    "Venezuela",
    "Vietnam",
    "Yemen",
    "Yibuti",
    "Zambia",
    "Zimbabue"
];




/* VARIABLES */
$T_Id                       = isset($_REQUEST['id'])                ? (integer)$_REQUEST['id']      : 0;
$T_Tipo                     = isset($_REQUEST['tipo'])              ? $_REQUEST['tipo']             : '';
$T_Filtro                   = isset($_REQUEST['filtro'])            ? $_REQUEST['filtro']           : 0;
$T_Activo_User              = isset($_REQUEST['activo_user'])       ? $_REQUEST['activo_user']      : '';
$T_Equipo                   = isset($_REQUEST['equipo'])            ? $_REQUEST['equipo']           : '';
$o_Notas                    = array();
$T_Estado                   = '';


/* HORARIO */
$T_HorarioTipo              = isset($_POST['horario_tipo'])         ? $_POST['horario_tipo']        : 0;
$T_HorarioNormId            = isset($_POST['horarioNormId'])        ? $_POST['horarioNormId']       : 0;
$T_HorarioFlexId            = isset($_POST['horarioFlexId'])        ? $_POST['horarioFlexId']       : 0;
$T_HorarioRotId             = isset($_POST['horarioRotId'])         ? $_POST['horarioRotId']        : 0;
$T_HorarioMultId            = isset($_POST['horarioMultId'])        ? $_POST['horarioMultId']       : 0;


/* IMAGEN */
$T_IMGBorrar                = isset($_POST['inputBorrarImagen'])    ? $_POST['inputBorrarImagen']   : '';
$T_IMGExtension             = isset($_POST['inputImageExtension'])  ? $_POST['inputImageExtension'] : '';
$T_IMG                      = isset($_POST['inputIMGsrc'])          ? $_POST['inputIMGsrc']         : '';
$T_IMGx                     = isset($_POST['inputIMGx'])            ? $_POST['inputIMGx']           : '';
$T_IMGy                     = isset($_POST['inputIMGy'])            ? $_POST['inputIMGy']           : '';
$T_IMGw                     = isset($_POST['inputIMGw'])            ? $_POST['inputIMGw']           : '';
$T_IMGh                     = isset($_POST['inputIMGh'])            ? $_POST['inputIMGh']           : '';


/* SYNC */
$T_Tipo_Check               = isset($_REQUEST['tipo_check'])        ? $_REQUEST['tipo_check']       : '';
$T_Data                     = isset($_REQUEST['data'])              ? $_REQUEST['data']             : '';
$T_Cmd                      = isset($_REQUEST['cmd'])               ? $_REQUEST['cmd']              : '';
$T_Dedo                     = isset($_REQUEST['dedo'])              ? $_REQUEST['dedo']             : '';
$T_Huella                   = isset($_REQUEST['huella'])            ? $_REQUEST['huella']           : '';
$T_Tag                      = isset($_REQUEST['tag'])               ? $_REQUEST['tag']              : '';



/* FILTRO: PERSONAS MINI */
$T_Filtro_Horario_Tipo      = isset($_REQUEST['f_horario_tipo'])    ? $_REQUEST['f_horario_tipo']   : '';
$T_Filtro_Horario_Id        = isset($_REQUEST['f_horario_id'])      ? $_REQUEST['f_horario_id']     : 0;
$T_Filtro_Horario_Detalle   = isset($_REQUEST['f_horario_detalle']) ? $_REQUEST['f_horario_detalle']: '';
$T_Filtro_Grupo_Id          = isset($_REQUEST['f_grupo_id'])        ? $_REQUEST['f_grupo_id']       : 0;
$T_Filtro_Equipo_Id         = isset($_REQUEST['f_equipo_id'])       ? $_REQUEST['f_equipo_id']      : 0;


/* VARIABLES FILTRO */
$T_Intervalo                = !isset($_REQUEST['intervaloFecha'])         ?       isset($_SESSION['filtro']['intervalo'])       ?   $_SESSION['filtro']['intervalo']            :     'F_Hoy'                 :     $_REQUEST['intervaloFecha'];
$T_Persona                  = !isset($_POST['persona'])             ? isset($_SESSION['filtro']['persona'])             ?   $_SESSION['filtro']['persona']          :     0       :     $_POST['persona'];
$T_Grupo                    = !isset($_POST['rolF'])                ? isset($_SESSION['filtro']['rolF'])             ?   $_SESSION['filtro']['rolF']          :     0       :     $_POST['rolF'];

/* VARIABLES FILTRO */
$T_Limpiar_Filtro           = !isset($_REQUEST['limpiar_filtro'])         ?       isset($_SESSION['filtro']['limpiar_filtro'])       ?   $_SESSION['filtro']['limpiar_filtro']            :     false                 :     $_REQUEST['limpiar_filtro'];


/* VARIABLES PROXIMA CARGA
$_SESSION['filtro']['intervalo']        =   $T_Intervalo;
$_SESSION['filtro']['persona']          =   $T_Persona;
$_SESSION['filtro']['rolF']             =   $T_Grupo;
$_SESSION['filtro']['activos']          =   $T_Filtro_Estado;
$_SESSION["EQUIPO"]                     =   $T_Equipo;
 */




switch ($T_Tipo) {
    case 'check':

        $o_Persona = Persona_L::obtenerPorId($T_Id);

        if (is_null($o_Persona)) {
            $o_Persona = new Persona_O();
        }

        switch ($T_Tipo_Check) {
            case 'c_tag':
                if ($o_Persona->getTag() != $_POST['tag'])
                    $o_Persona->setTag(isset($_POST['tag']) ? $_POST['tag'] : '');
                $T_Error = $o_Persona->getErrores();
                if (!is_null($T_Error) && array_key_exists('tag', $T_Error)) echo $T_Error['tag']; else echo "true";
                break;
            case 'c_dni':
                $o_Persona->setDni(isset($_POST['dni']) ? $_POST['dni'] : '');
                $T_Error = $o_Persona->getErrores();
                if (!is_null($T_Error) && array_key_exists('dni', $T_Error)) echo $T_Error['dni']; else echo "true";
                break;
            case 'c_legajo':
                $o_Persona->setLegajo(isset($_POST['legajo']) ? $_POST['legajo'] : '');
                $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Persona->getErrores();
                if (!is_null($T_Error) && array_key_exists('legajo', $T_Error)) echo $T_Error['legajo']; else echo "true";
                break;
        }
        die();
        break;


    case 'add':

        $o_Persona = new Persona_O();

        /* EQUIPOS */
        $_equipos = '';
        if (!empty($_POST['equipo'])) {
            foreach ($_POST['equipo'] as $key => $check_equipo) {
                $_equipos .= $check_equipo . ":";
            }
            $_equipos = rtrim($_equipos, ':');
        }


        if ($T_IMG != '') {//viene una imagen nueva

            $src = GS_CLIENT_TEMP_FOLDER . $T_IMG;

            switch ($T_IMGExtension){
                case 'image/png':
                    $img_r = imagecreatefrompng($src);
                    break;
                case 'image/gif':
                    $img_r = imagecreatefromgif($src);
                    break;
                case 'image/jpg':
                    $img_r = imagecreatefromjpeg($src);
                    break;
                case 'image/jpeg':
                    $img_r = imagecreatefromjpeg($src);
                    break;
                default:
                    $o_Persona->setErrores('imagen', 'formato no soportado');
                    break;
            }


            $dst_w = 250;
            $dst_h = 250;
            $dst_r = ImageCreateTrueColor($dst_w, $dst_h);

            imagecopyresampled($dst_r, $img_r, 0, 0, $T_IMGx, $T_IMGy, $dst_w, $dst_h, $T_IMGw, $T_IMGh);

            $filename = md5(time() . uniqid()) . ".jpg";
            $path = GS_CLIENT_IMAGES_PERSONAS . $o_Persona->getId() . "/";

            if (!file_exists($path))
                mkdir($path, 0777, true);

            imagejpeg($dst_r, $path . $filename, 100);
        }


        switch ($T_HorarioTipo) {
            case HORARIO_NORMAL:
                $o_Persona->setHorId($T_HorarioNormId);
                break;
            case HORARIO_FLEXIBLE:
                $o_Persona->setHorId($T_HorarioFlexId);
                break;
            case HORARIO_ROTATIVO:
                $o_Persona->setHorId($T_HorarioRotId);
                break;
            case HORARIO_MULTIPLE:
                $o_Persona->setHorId($T_HorarioMultId);
                break;
        }

        $o_Persona->setHorTipo($T_HorarioTipo);
        $o_Persona->setFechaD(isset($_POST['fechaContratacion']) ? $_POST['fechaContratacion'] : '0000-00-00 00:00:00');
        $o_Persona->setFechaH(isset($_POST['fechaH']) ? $_POST['fechaH']." 23:59:59" : '0000-00-00 00:00:00');
        $o_Persona->setRID(isset($_POST['RID']) ? $_POST['RID'] : '');
        $o_Persona->setNombre(isset($_POST['nombre']) ? $_POST['nombre'] : '');
        $o_Persona->setApellido(isset($_POST['apellido']) ? $_POST['apellido'] : '');
        $o_Persona->setDni(isset($_POST['dni']) ? $_POST['dni'] : '');
        $o_Persona->setTag(isset($_POST['tag']) ? $_POST['tag'] : '');
        //$o_Persona->setRolId(isset($_POST['grupo']) ? $_POST['grupo'] : 0);
        $o_Persona->setLegajo(isset($_POST['legajo']) ? $_POST['legajo'] : '');
        $o_Persona->setTeCelurar(isset($_POST['telefono']) ? $_POST['telefono'] : '');
        $o_Persona->setTeFijo(isset($_POST['te_personal']) ? $_POST['te_personal'] : '');
        $o_Persona->setEmail(isset($_POST['email']) ? $_POST['email'] : '');
        $o_Persona->setNotas(isset($_POST['notas']) ? $_POST['notas'] : '');
        $o_Persona->setCreadoUsuarioId(Registry::getInstance()->Usuario->getId());
        $o_Persona->setSegundoNombre(isset($_POST['segundoNombre'])?$_POST['segundoNombre']:'');
        $o_Persona->setFechaNacimiento(isset($_POST['fechaNacimiento'])?$_POST['fechaNacimiento']:'0000-00-00 00:00:00');
        $o_Persona->setGenero(isset($_POST['genero'])?$_POST['genero']:'');
        $o_Persona->setEstadoCivil(isset($_POST['estadoCivil'])?$_POST['estadoCivil']:'');
        $o_Persona->setNroContribuyente(isset($_POST['nroContribuyente'])?$_POST['nroContribuyente']:'');
        $o_Persona->setTalleCamisa(isset($_POST['talleCamisa'])?$_POST['talleCamisa']:'');
        $o_Persona->setDireccion1(isset($_POST['direccion1'])?$_POST['direccion1']:'');
        $o_Persona->setDireccion2(isset($_POST['direccion2'])?$_POST['direccion2']:'');
        $o_Persona->setCiudad(isset($_POST['ciudad'])?$_POST['ciudad']:'');
        $o_Persona->setProvincia(isset($_POST['provincia'])?$_POST['provincia']:'');
        $o_Persona->setCodigoPostal(isset($_POST['codigoPostal'])?$_POST['codigoPostal']:'');
        $o_Persona->setPais(isset($_POST['pais'])?$_POST['pais']:'');
        $o_Persona->setTeTrabajo(isset($_POST['te_trabajo'])?$_POST['te_trabajo']:'');
        $o_Persona->setTeCelurar(isset($_POST['te_movil'])?$_POST['te_movil']:'');
        $o_Persona->setTeFijo(isset($_POST['te_personal'])?$_POST['te_personal']:'');
        $o_Persona->setEmailPersonal(isset($_POST['emailPersonal'])?$_POST['emailPersonal']:'');
        $o_Persona->setLinkedin(isset($_POST['linkedin'])?$_POST['linkedin']:'');
        $o_Persona->setTwitter(isset($_POST['twitter'])?$_POST['twitter']:'');
        $o_Persona->setFacebook(isset($_POST['facebook'])?$_POST['facebook']:'');
        $o_Persona->setEstado(isset($_POST['estado'])?$_POST['estado']:'');
        $o_Persona->setEquipos($_equipos);
        $o_Persona->setImagen($path . $filename);
        $o_Persona->setImagenURL('');



        if ($o_Persona->save()) {

            SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_PERSONA_CREAR, $a_Logs_Tipos[LOG_PERSONA_CREAR], '<b>Id:</b> ' . $o_Persona->getId() . ' <b>Nombre:</b> ' . $o_Persona->getNombreCompleto(), $o_Persona->getId());


            //si la persona ya fue guardada, es decir que tengo el ID, entonces ahora le cargo los grupos
            if (!empty($_POST['grupo'])) {
                foreach ($_POST['grupo'] as $key => $check_grupo) {
                    $grupo_persona = new Grupos_Personas_O;
                    $grupo_persona->setGrupo($check_grupo);
                    $grupo_persona->setPersona($o_Persona->getId());
                    $grupo_persona->save('Off');
                }
            }





        //SeguridadHelper::Reporte(Registry::getInstance()->Usuario->getId(), $T_Titulo . '-' . $a_Acciones[0], _('Tabla') . ' - ' . $T_Script . ' Id - ' . $o_Persona->getId());
            SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_PERSONA_CREAR, $a_Logs_Tipos[LOG_PERSONA_CREAR], '<b>Id:</b> ' . $o_Persona->getId() . ' <b>Nombre:</b> ' . $o_Persona->getNombreCompleto(), $o_Persona->getId());
            $se_sincroniza=0;//1;
            $a_o_Equipo = Equipo_L::obtenerTodos();
            $array_equipos = explode(':', $o_Persona->getEquipos());
            if ($a_o_Equipo != null) {
                foreach ($a_o_Equipo as $o_Equipo) {
                    /* @var $o_Equipo Equipo_O */
                    if ($o_Equipo->isOffline()) continue;
                    if (!in_array($o_Equipo->getId(), $array_equipos)) continue;
                    $se_sincroniza=1;
                }
            }

            if($se_sincroniza){
                $T_Mensaje = 'La persona fue guardada con éxito. Sincronizando datos...';

                SyncHelper::SyncPersona($o_Persona);

                //$T_sync_checker = "syncChecker(" . $o_Persona->getId() . ",\"" . $o_Persona->getApellido() . ', ' . $o_Persona->getNombre() . "\",".count(explode(':',$o_Persona->getEquipos())).");";
                //$T_sync_js_start = "disableRow(\"" . $o_Persona->getApellido() . ', ' . $o_Persona->getNombre() . "\");";


            }else{
                $T_Mensaje = 'La persona fue guardada correctamente.';
            }

            //si la persona ya fue guardada, es decir que tengo el ID, entonces ahora le cargo los grupos
            if (!empty($_POST['grupo'])) {
                foreach ($_POST['grupo'] as $key => $check_grupo) {
                    $grupo_persona = new Grupos_Personas_O;
                    $grupo_persona->setGrupo($check_grupo);
                    $grupo_persona->setPersona($o_Persona->getId());
                    $grupo_persona->save('Off');
                }
            }


            /*
            $o_Usuario = null;

            // 1 OBTENER USUARIO POR EMAIL
            if ($o_Persona->getEmail() != '') {
                $o_Usuario = Usuario_L::obtenerPorEmail($o_Persona->getEmail());
            }

            // 2 OBTENER USUARIO POR DNI
            if (is_null($o_Usuario)) {
                if ($o_Persona->getDni() != '') {
                    $o_Usuario = Usuario_L::obtenerPorDni($o_Persona->getDni());
                }
            }

            // 3 USUARIO IS NULL: NEW USER
            if (is_null($o_Usuario)) {

                // 1 CREAR USUARIO
                // NEW USER
                $o_Usuario = new Usuario_O();
                // SET USER TYPE
                $o_Usuario->setTusId(6);
                // SET USER VISIBLE
                $o_Usuario->setVisible(1);

                // 2 SET USUARIO DATA FROM PERSONA
                // SET PERSONAL DATA
                $o_Usuario->setNombre($o_Persona->getNombre());
                $o_Usuario->setApellido($o_Persona->getApellido());
                $o_Usuario->setDni($o_Persona->getDni());
                $o_Usuario->setTeCelurar($o_Persona->getTeCelular());
                $o_Usuario->setTePersonal($o_Persona->getTeFijo());
                // SET IMAGEN
                $o_Usuario->setImagen($o_Persona->getImagen());
                $o_Usuario->setImagenURL($o_Persona->getImagenURL());
                // SET PASSWORD
                $o_Usuario->setClave($o_Persona->getLegajo());
                $o_Usuario->setConfirmacionClave($o_Persona->getLegajo());
                // SET EMAIL
                if ($o_Persona->getEmail() != "") {
                    $o_Usuario->setEmail($o_Persona->getEmail());
                }
            }

            // 4 USUARIO SET PERSONA
            $o_Usuario->setPersona($o_Persona->getId());

            // 5 SAVE USUARIO SUCCESS
            if ($o_Usuario->save(Registry::getInstance()->general['debug'])) {

                // LOG: USER SAVED
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_USUARIO_CREAR, $a_Logs_Tipos[LOG_USUARIO_CREAR], '<b>Id:</b> ' . $o_Usuario->getId() . ' <b>Usuario:</b> ' . $o_Usuario->getEmail() . ' <b>Nombre:</b> ' . $o_Usuario->getNombreCompleto(), $o_Usuario->getId());

                // 1 SET PER_USU_ID (USU_ID)
                $o_Persona->setUserId($o_Usuario->getId());

                // 2 SET PER_USU_ENABLE (true)
                $o_Persona->setUserActivo(1);

                // 3 SAVE PERSONA
                if ($o_Persona->save(Registry::getInstance()->general['debug'])) {
                    SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_PERSONA_EDITAR, $a_Logs_Tipos[LOG_PERSONA_EDITAR], '<b>Id:</b> ' . $o_Persona->getId() . ' <b>Nombre:</b> ' . $o_Persona->getNombreCompleto(), $o_Persona->getId());
                }
                else {
                    $T_Error = "Lo sentimos, hubo un error en la operación.";//$T_Error . "<br>" . $o_Persona->getErrores();
                }


            }
            */


        }
        else {
            $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Persona->getErrores();
            $_SESSION['T_Error_Detalle']    = json_encode($o_Persona->getErrores());

        }

        $T_Limpiar_Filtro = true;

        goto defaultLabel;
        break;



    case 'edit':

        // PERSONA
        $o_Persona = Persona_L::obtenerPorId($T_Id, true);

        // HORARIO
        $o_Persona->setHorTipo($T_HorarioTipo);
        switch ($T_HorarioTipo) {
            case HORARIO_NORMAL:
                $o_Persona->setHorId($T_HorarioNormId);
                break;
            case HORARIO_FLEXIBLE:
                $o_Persona->setHorId($T_HorarioFlexId);
                break;
            case HORARIO_ROTATIVO:
                $o_Persona->setHorId($T_HorarioRotId);
                break;
            case HORARIO_MULTIPLE:
                $o_Persona->setHorId($T_HorarioMultId);
                break;
        }

        // PERSONAL DATA
        $o_Persona->setFechaD(isset($_POST['fechaContratacion']) ? $_POST['fechaContratacion']." 00:00:00" : '0000-00-00 00:00:00');
        $o_Persona->setFechaH(isset($_POST['fechaH']) ? $_POST['fechaH']." 23:59:59" : '0000-00-00 00:00:00');
        $o_Persona->setRID(isset($_POST['RID']) ? $_POST['RID'] : '');
        $o_Persona->setNombre(isset($_POST['nombre']) ? $_POST['nombre'] : '');
        $o_Persona->setApellido(isset($_POST['apellido']) ? $_POST['apellido'] : '');
        $o_Persona->setDni(isset($_POST['dni']) ? $_POST['dni'] : '');
        $o_Persona->setTag(isset($_POST['tag']) ? $_POST['tag'] : '');
        $o_Persona->setLegajo(isset($_POST['legajo']) ? $_POST['legajo'] : '');
        $o_Persona->setEmail(isset($_POST['email']) ? $_POST['email'] : '');
        //$o_Persona->setCreadoUsuarioId(Registry::getInstance()->Usuario->getId());



        // NEW VARS
        $o_Persona->setSegundoNombre(isset($_POST['segundoNombre'])?$_POST['segundoNombre']:'');

        $o_Persona->setFechaNacimiento(isset($_POST['fechaNacimiento'])?$_POST['fechaNacimiento']:'0000-00-00 00:00:00');
        $o_Persona->setGenero(isset($_POST['genero'])?$_POST['genero']:'');
        $o_Persona->setEstadoCivil(isset($_POST['estadoCivil'])?$_POST['estadoCivil']:'');

        $o_Persona->setNroContribuyente(isset($_POST['nroContribuyente'])?$_POST['nroContribuyente']:'');
        $o_Persona->setTalleCamisa(isset($_POST['talleCamisa'])?$_POST['talleCamisa']:'');

        $o_Persona->setDireccion1(isset($_POST['direccion1'])?$_POST['direccion1']:'');
        $o_Persona->setDireccion2(isset($_POST['direccion2'])?$_POST['direccion2']:'');
        $o_Persona->setCiudad(isset($_POST['ciudad'])?$_POST['ciudad']:'');
        $o_Persona->setProvincia(isset($_POST['provincia'])?$_POST['provincia']:'');
        $o_Persona->setCodigoPostal(isset($_POST['codigoPostal'])?$_POST['codigoPostal']:'');
        $o_Persona->setPais(isset($_POST['pais'])?$_POST['pais']:'');

        $o_Persona->setTeTrabajo(isset($_POST['te_trabajo'])?$_POST['te_trabajo']:'');
        $o_Persona->setTeCelurar(isset($_POST['te_movil'])?$_POST['te_movil']:'');
        $o_Persona->setTeFijo(isset($_POST['te_personal'])?$_POST['te_personal']:'');

        $o_Persona->setEmailPersonal(isset($_POST['emailPersonal'])?$_POST['emailPersonal']:'');

        $o_Persona->setLinkedin(isset($_POST['linkedin'])?$_POST['linkedin']:'');
        $o_Persona->setTwitter(isset($_POST['twitter'])?$_POST['twitter']:'');
        $o_Persona->setFacebook(isset($_POST['facebook'])?$_POST['facebook']:'');

        $o_Persona->setNotas(isset($_POST['notas']) ? $_POST['notas'] : '');

        $o_Persona->setEstado(isset($_POST['estado']) ? $_POST['estado'] : 'Activo');


        // EQUIPOS
        $string_equipos = '';
        if (!empty($_POST['equipo'])) {

            foreach ($_POST['equipo'] as $key => $check_equipo) {

                $string_equipos .= $check_equipo . ":";
            }
        }
        $string_equipos = rtrim($string_equipos, ':');
        $o_Persona->setEquipos($string_equipos);

        // GROUPS
        if (!empty($_POST['grupo'])) {
            //primero borro todos los registros
            $a_o_Grupos_Personas = Grupos_Personas_L::obtenerPorPersona($o_Persona->getId());
            if (!is_null($a_o_Grupos_Personas)) {
                foreach ($a_o_Grupos_Personas as $g_persona) {
                    $g_persona->delete('Off');
                }
            }
            foreach ($_POST['grupo'] as $key => $check_grupo) {
                $grupo_persona = new Grupos_Personas_O;
                $grupo_persona->setGrupo($check_grupo);
                $grupo_persona->setPersona($o_Persona->getId());
                $grupo_persona->save('Off'); //TODO
            }
        }
        else{
            //primero borro todos los registros
            $a_o_Grupos_Personas = Grupos_Personas_L::obtenerPorPersona($o_Persona->getId());
            if (!is_null($a_o_Grupos_Personas)) {
                foreach ($a_o_Grupos_Personas as $g_persona) {
                    $g_persona->delete('Off');
                }
            }
        }

        // IMAGE
        if ($T_IMGBorrar != '') {
            $o_Persona->setImagen('');
        }
        if ($T_IMG != '') {//viene una imagen nueva

            $src = GS_CLIENT_TEMP_FOLDER . $T_IMG;
            if ($T_IMGExtension == 'image/png')
                $img_r = imagecreatefrompng($src);
            else if ($T_IMGExtension == 'image/gif')
                $img_r = imagecreatefromgif($src);
            else if ($T_IMGExtension == 'image/jpg')
                $img_r = imagecreatefromjpeg($src);
            else if ($T_IMGExtension == 'image/jpeg')
                $img_r = imagecreatefromjpeg($src);
            else
                $o_Persona->setErrores('imagen', 'formato no soportado');

            $dst_w = 250;
            $dst_h = 250;
            $dst_r = ImageCreateTrueColor($dst_w, $dst_h);

            imagecopyresampled($dst_r, $img_r, 0, 0, $T_IMGx, $T_IMGy, $dst_w, $dst_h, $T_IMGw, $T_IMGh);

            $filename = md5(time() . uniqid()) . ".jpg";
            $path = GS_CLIENT_IMAGES_PERSONAS . $o_Persona->getId() . "/";

            if (!file_exists($path))
                mkdir($path, 0777, true);

            imagejpeg($dst_r, $path . $filename, 100);

            $o_Persona->setImagen($path . $filename);
            $o_Persona->setImagenURL('');

        }

        // SAVE PERSONA: SYNC PERSONA, CREATE/ASSOCIATE USER
        if ($o_Persona->save()) {

            // SAVE LOG
            SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_PERSONA_EDITAR, $a_Logs_Tipos[LOG_PERSONA_EDITAR], '<b>Id:</b> ' . $o_Persona->getId() . ' <b>Nombre:</b> ' . $o_Persona->getNombreCompleto(), $o_Persona->getId());


            $se_sincroniza=0;
            $a_o_Equipo = Equipo_L::obtenerTodos();
            $array_equipos = explode(':', $o_Persona->getEquipos());
            if ($a_o_Equipo != null) {
                foreach ($a_o_Equipo as $o_Equipo) {
                    /* @var $o_Equipo Equipo_O */
                    if ($o_Equipo->isOffline()) continue;
                    if (!in_array($o_Equipo->getId(), $array_equipos)) continue;
                    $se_sincroniza=1;
                }
            }

            if($se_sincroniza){
                $T_Mensaje = 'La persona fue guardada con éxito. Sincronizando datos...';
                SyncHelper::SyncPersona($o_Persona);
                //$T_sync_checker = "syncChecker(" . $o_Persona->getId() . ",\"" . $o_Persona->getApellido() . ', ' . $o_Persona->getNombre() . "\",".count(explode(':',$o_Persona->getEquipos())).");";
                //$T_sync_js_start = "disableRow(\"" . $o_Persona->getApellido() . ', ' . $o_Persona->getNombre() . "\");";
            }else{
                $T_Mensaje = 'La persona fue editada correctamente.';
            }

            // Sync

            // Fin Sync


            /*
            $o_Usuario = Usuario_L::obtenerPorPerId($o_Persona->getId());


            if (!is_null($o_Usuario)) {

                // SET PERSONAL DATA
                $o_Usuario->setNombre               ( $o_Persona->getNombre()   );
                $o_Usuario->setApellido             ( $o_Persona->getApellido() );
                $o_Usuario->setDni                  ( $o_Persona->getDni()      );
                $o_Usuario->setTeCelurar            ( $o_Persona->getTeCelular());
                $o_Usuario->setTePersonal           ( $o_Persona->getTeFijo()   );

                // SET IMAGEN
                $o_Usuario->setImagen               ( $o_Persona->getImagen()   );
                $o_Usuario->setImagenURL            ( $o_Persona->getImagenURL());

                // SET EMAIL
                if( $o_Persona->getEmail() != ""){
                    $o_Usuario->setEmail            ( $o_Persona->getEmail()    );
                }

                // SAVE USUARIO
                if ($o_Usuario->save(Registry::getInstance()->general['debug'])) {

                    // LOG: USER SAVED
                    SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_USUARIO_CREAR, $a_Logs_Tipos[LOG_USUARIO_CREAR], '<b>Id:</b> ' . $o_Usuario->getId() . ' <b>Usuario:</b> ' . $o_Usuario->getEmail() . ' <b>Nombre:</b> ' . $o_Usuario->getNombreCompleto(), $o_Usuario->getId());
                }
            }
            */


        }

        // SAVE ERROR
        else {
            $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Persona->getErrores();
            $_SESSION['T_Error_Detalle']    = json_encode($o_Persona->getErrores());

        }

        $T_Limpiar_Filtro = true;

        goto defaultLabel;

        break;

    case 'delete':
        SeguridadHelper::Pasar(50);

        $o_Persona = Persona_L::obtenerPorId($T_Id, true);


        if (is_null($o_Persona)) {
            $T_Error = _('Lo sentimos, la persona que desea eliminar no existe.');
        }

        else {

            // ELIMINAR PERSONA
            $o_Persona->setEliminadoUsuarioId(Registry::getInstance()->Usuario->getId());
            // DESHABILITAR PERSONA
            $o_Persona->setEnabled(0);
            // EXCLUIR PERSONA
            $o_Persona->setExcluir(1);

            // GUARDAR PERSONA ELIMINADA
            if (!$o_Persona->save()) {
                $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Persona->getErrores();
            }

            // ELIMINAR HUELLAS PERSONA
            $a_huellas = Huella_L::obtenerPorPersona($o_Persona->getId());
            if(!is_null($a_huellas)) {
                foreach ($a_huellas as $o_huella) {
                    /* @var $o_huella Huella_O */
                    $o_huella->delete();
                }
            }


            Grupos_Personas_L::eliminarPersonaDeTodosLosGrupos($o_Persona->getId());




            if (!$o_Persona->delete()) {
                $T_Error = "Lo sentimos, hubo un error en la operación.";//$o_Persona->getErrores();
            } else {
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_PERSONA_ELIMINAR, $a_Logs_Tipos[LOG_PERSONA_ELIMINAR], '<b>Id:</b> ' . $o_Persona->getId() . ' <b>Nombre:</b> ' . $o_Persona->getNombreCompleto(), $o_Persona->getId());
                $T_Mensaje = _('La persona fue eliminada correctamente.');
            }
        }

        $T_Eliminado = true;
        $T_Link = '_mos';
        goto defaultLabel;
        break;
    case 'view':


        if($T_Id){
            $o_Persona      = Persona_L::obtenerPorId($T_Id, true);
            $o_Notas        = Notas_L::getAllbyPersonaId($T_Id);
            break;
        }

        $o_Persona = new Persona_O();

        break;
    case 'view-tag':
    case 'view-fp':

        SeguridadHelper::Pasar(10);
        $o_Persona = Persona_L::obtenerPorId($T_Id, true);
        if (is_null($o_Persona)) $o_Persona = new Persona_O();

        break;
    case 'accion':

        if ($T_Cmd == "enrollstart") {
            if ($T_Data != "") {
                $o_Equipo = Equipo_L::obtenerPorId($T_Data);

                $o_huella = new Huella_O();
                $o_huella->setPerId($T_Id);
                $o_huella->setDedo($T_Dedo);
                $o_huella->setEnabled(1);
                $o_huella->save('off');

                $o_Persona = Persona_L::obtenerPorId($T_Id, true);

                PubSubHelper::sendMessage(CMD_ENROLL_START,json_encode(array('hue_id'=>$o_huella->getId(),'per_id'=>$T_Id,'hue_dedo'=>$T_Dedo,'fecha_start'=>time())),$o_Equipo->getUUID(),array('sess_id' => session_id()));
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_HUELLA_ENROLL_START, $a_Logs_Tipos[LOG_HUELLA_ENROLL_START], '<b>Persona Id:</b> ' . $o_Persona->getId() . ' <b>Nombre:</b> ' . $o_Persona->getNombreCompleto() . ' <b>Huella ID:</b>' . $o_huella->getId() . ' <b>Dedo:</b>' . $o_huella->getDedo(), $o_Persona->getId());

                echo $o_huella->getId();
                die();
            }
        }
        else if ($T_Cmd == "enrollcancel") {
            if ($T_Data != "") {
                $o_Equipo = Equipo_L::obtenerPorUUID($T_Data);

                if(!is_null($o_Equipo)){
                    $o_huella = Huella_L::obtenerPorId($T_Huella);
                    if(!is_null($o_huella)){
                        PubSubHelper::sendMessage(CMD_ENROLL_CANCEL,json_encode(array('id'=>$o_huella->getId(),'fecha_start'=>time())),$o_Equipo->getUUID(),array('sess_id' => session_id()));
                        $o_Persona = Persona_L::obtenerPorId($o_huella->getPerId(), true);
                        SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_HUELLA_ENROLL_CANCEL, $a_Logs_Tipos[LOG_HUELLA_ENROLL_CANCEL], '<b>Persona Id:</b> ' . $o_Persona->getId() . ' <b>Nombre:</b> ' . $o_Persona->getNombreCompleto() . ' <b>Huella ID:</b>' . $o_huella->getId() . ' <b>Dedo:</b>' . $o_huella->getDedo(), $o_Persona->getId());
                        $o_huella->purge();
                        die('OK');
                    }else {
                        die('La huella no existe');
                    }

                }else{
                    die('El equipo no existe');
                }

                die('kernel panic');
            }
        } else if ($T_Cmd == "deletefp") {

            $o_huella = Huella_L::obtenerPorPersonayDedo($T_Id, $T_Dedo);
            $o_Persona = Persona_L::obtenerPorId($T_Id, true);

            if (!empty($o_huella)) {
                $o_huella->delete();
                SyncHelper::SyncHuellaEnTodosLosEquipos($o_huella);
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_HUELLA_DELETE, $a_Logs_Tipos[LOG_HUELLA_DELETE], '<b>Persona Id:</b> ' . $o_Persona->getId() . ' <b>Nombre:</b> ' . $o_Persona->getNombreCompleto() . ' <b>Huella ID:</b>' . $o_huella->getId() . ' <b>Dedo:</b>' . $o_huella->getDedo(), $o_Persona->getId());
            }
            die();
        } else if ($T_Cmd == "tagenrollstart") {

            if ($T_Data != "") {
                $o_Equipo = Equipo_L::obtenerPorId($T_Data);


                $o_Persona = Persona_L::obtenerPorId($T_Id, true);

                PubSubHelper::sendMessage(CMD_RFID_READ_START,json_encode(array('per_id'=>$T_Id,'fecha_start'=>time())),$o_Equipo->getUUID(),array('sess_id' => session_id()));
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_RFID_READ_START, $a_Logs_Tipos[LOG_RFID_READ_START], '<b>Persona Id:</b> ' . $o_Persona->getId() . ' <b>Nombre:</b> ' . $o_Persona->getNombreCompleto(), $o_Persona->getId());

                echo $o_Persona->getId();

                die();
            }
        }else if ($T_Cmd == "tagenrollcancel") {
            if ($T_Data != "") {
                $o_Equipo = Equipo_L::obtenerPorUUID($T_Data);

                if(!is_null($o_Equipo)){

                    PubSubHelper::sendMessage(CMD_RFID_READ_CANCEL,json_encode(array('fecha_start'=>time())),$o_Equipo->getUUID(),array('sess_id' => session_id()));
                    //SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_RFID_READ_CANCEL, $a_Logs_Tipos[LOG_RFID_READ_CANCEL], '<b>Persona Id:</b> ' . $o_Persona->getId() . ' <b>Nombre:</b> ' . $o_Persona->getNombreCompleto() . ' <b>Huella ID:</b>' . $o_huella->getId() . ' <b>Dedo:</b>' . $o_huella->getDedo(), $o_Persona->getId());
                    SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_RFID_READ_CANCEL, $a_Logs_Tipos[LOG_RFID_READ_CANCEL], '');
                    die('OK');


                }else{
                    die('El equipo no existe');
                }

                die('kernel panic');
            }
        } else if ($T_Cmd == "deletetag") {

            $o_Persona = Persona_L::obtenerPorId($T_Id, true);
            $tagviejo = $o_Persona->getTag();
            $o_Persona->removeTag();
            $o_Persona->save();

            SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_RFID_DELETE, $a_Logs_Tipos[LOG_RFID_DELETE], '<b>Persona Id:</b> ' . $o_Persona->getId() . ' <b>Nombre:</b> ' . $o_Persona->getNombreCompleto() . ' <b>TAG eliminado:</b>' . $tagviejo, $o_Persona->getId());

            SyncHelper::SyncPersona($o_Persona);



            die();
        } else if ($T_Cmd == "tagsave") {


            $o_persona = null;
            $o_persona = Persona_L::obtenerPorId($T_Id,true);

            $o_persona_tag = Persona_L::obtenerPorTag($T_Tag);

            if(!is_null($o_persona_tag)){
                SeguridadHelper::Log(0, LOG_RFID_READ_ERROR, $a_Logs_Tipos[LOG_RFID_READ_ERROR], 'La tarjeta ya existe. <b>Persona Id:</b> ' . $o_persona_tag->getId() . ' <b>Nombre:</b> ' . $o_persona_tag->getNombreCompleto() . ' <b>TAG:</b>' . $o_persona_tag->getTag(), $o_persona_tag->getId());
                die("La tarjeta ya existe");
            }else{
                if (!is_null($o_persona)) {

                    $tagViejo = $o_persona->getTag();

                    $o_persona->setTag($T_Tag);
                    if($o_persona->save()==false){
                        die("El formato es incorrecto. Deben ser 10 caracteres hexadecimales.");
                    }

                    if($tagViejo!='') //cambio de tag
                        SeguridadHelper::Log(0, LOG_RFID_READ_OK, $a_Logs_Tipos[LOG_RFID_READ_OK], '<b>Persona Id:</b> ' . $o_persona->getId() . ' <b>Nombre:</b> ' . $o_persona->getNombreCompleto() . ' <b>TAG Anterior:</b>' . $tagViejo . ' <b>TAG Actual:</b>' . $o_persona->getTag(), $o_persona->getId());
                    else   //tag nuevo
                        SeguridadHelper::Log(0, LOG_RFID_READ_OK, $a_Logs_Tipos[LOG_RFID_READ_OK], '<b>Persona Id:</b> ' . $o_persona->getId() . ' <b>Nombre:</b> ' . $o_persona->getNombreCompleto() . ' <b>TAG:</b>' . $o_persona->getTag(), $o_persona->getId());

                    SyncHelper::SyncPersona($o_persona);

                    die('OK');

                }
            }




            die();
        }


        break;
    case 'view-filtro':


        if ($T_Filtro_Horario_Tipo != '') {

            $o_Listado = Persona_L::obtenerTodos(0, 0, 0, 'per_Eliminada = 0 AND per_Hor_Tipo=' . $T_Filtro_Horario_Tipo. ' AND per_Hor_Id=' . $T_Filtro_Horario_Id, false);
        }
        else if ($T_Filtro_Grupo_Id != 0) {
            $o_Listado = Grupos_Personas_L::obtenerPersonasPorGrupo($T_Filtro_Grupo_Id);
        }
        else if ($T_Filtro_Equipo_Id != 0) {

            $o_Listado = array();

            // ID EQUIPO
            $equipoID = $T_Filtro_Equipo_Id;

            // DATOS PARA CONSULTA SQL
            $p_tabla     = 'personas';
            $p_condicion = "(per_equipos REGEXP '^{$equipoID}$' OR per_equipos REGEXP '^{$equipoID}:' OR  per_equipos REGEXP ':{$equipoID}$' OR  per_equipos REGEXP ':{$equipoID}:')";
            $p_key       = 'per_Id';

            // MYSQL GET: IDs PERSONAS IN EQUIPO
            $cnn                    = Registry::getInstance()->DbConn;
            $_SESSION['filtro']['activos'] = true;
            $a_Personas             = $cnn->Select_Lista_IDs($p_tabla, $p_condicion, $p_key);

            // SET ARRAY PERSONAS
            if(!is_null($a_Personas)){
                //
                foreach ($a_Personas as $_key => $per_Id){
                    $o_Listado []= Persona_L::obtenerPorId($per_Id);
                }

            }


        }



        break;
    case 'L_Blo':
    case 'L_Hor':


    default:
        defaultLabel:

        // FILTRO
        $T_Filtro_Array = array();
        $T_Filtro_Array = Filtro_L::get_filtro_persona($_POST, $_SESSION,$T_Limpiar_Filtro);
        // LISTADO PERSONAS
        $o_Listado      = Persona_L::obtenerDesdeFiltro($T_Filtro_Array);


        $_SESSION['filtro']['id_data']                  =  $T_Id;
        $_SESSION['filtro']['tipo_data']                =  $_SESSION['filtro']['tipo']  ;
        $_SESSION['filtro']['persona_data']             =  $T_Filtro_Array;
        $_SESSION['filtro']['intervalo_data']           =  "";
        break;

}
