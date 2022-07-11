<?php

SeguridadHelper::Pasar(90);

$T_Titulo       = _('Mi Cuenta');
$T_Script       = 'cuenta';
$Item_Name      = 'cuenta';
$T_Link         = '';

$T_Error        = array();
$T_Mensaje      = '';

$T_Tipo         = (isset($_REQUEST['tipo'])) ? $_REQUEST['tipo'] : '';
$T_CompanyData  = (isset($_POST['data'])) ? $_POST['data'] : array();

$_Listado_Paises    = array();
$_Listado_Paises    = [    "Afganistán",
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


switch ($T_Tipo) {

    // SAVE
    case 'save':
        // SECCIONES
        foreach ($T_CompanyData as $_data_Id => $_valor) {

            // CONFIG OBJECT
            $o_Config       =   Config_L::obtenerPorId($_data_Id);

            // NEW VALUE
            $new_CompanyData     =   $T_CompanyData[$_data_Id];

            //
            if ( $o_Config->getTipo()    ==  'si_no' ) {

                // NEW CONFIG VALUE: ON
                if( $new_CompanyData  ==  'on' ) {
                    $new_CompanyData='1';
                }
                // NEW CONFIG VALUE: OFF
                else {
                    $new_CompanyData='0';
                }
            }

            // OLD VALUE
            $old_CompanyData = $o_Config->getValor();

            // IF NEW VALUE & OLD VALUE DIFFER
            if ($new_CompanyData != $old_CompanyData) {

                // SET NEW VALUE
                $o_Config->setValor($new_CompanyData);

                // SAVE NEW VALUE
                if (!$o_Config->save(Registry::getInstance()->general['debug'])) {
                    // LOG ERROR
                    $T_Error['e' . $_data_Id] = $o_Config->getErrores();
                }
                else {
                    // LOG SAVE
                    SeguridadHelper::Reporte(Registry::getInstance()->Usuario->getId(), $T_Titulo . '-' . $a_Acciones[1], 'Tabla - ' . $T_Script . ' Id - ' . $o_Config->getId());

                    // OBJECT PARAMETER: BACKUP
                    if ($o_Config->getParametro() == 'backup') {

                        // REPLACE FILE
                        if (!file_exists($rutaBackup . '/no_borrar.aux')) {

                            // SAVE COMMAND
                            $resultado = SOHelper::umountRed(Config_L::p('backup'));

                            // ERROR CHECK
                            if ($resultado != array()) {
                                // ERROR
                                $T_Error = implode(' ', $resultado);
                            }
                        }
                        // DO NOT REPLACE FILE
                        else{

                        }
                    }



                }
            }
        }
        // NO ERRORS, SAVE SUCCESS
        if (count($T_Error) == 0) {
            $T_Mensaje = _('La cambios fueron guardados correctamente.');
        }

        goto defaultlabel;

        break;

    // DEFAULT
    default:
        defaultlabel:

        $o_Datos_Empresa = Config_L::obtenerTodosPorSeccionParametro('Datos de la Empresa');

        break;
}




