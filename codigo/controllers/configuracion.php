<?php

SeguridadHelper::Pasar(90);

$T_Titulo       = _('Configuración General');
$T_Script       = 'configuracion';
$Item_Name      = 'configuracion';
$T_Link         = '';

$T_Error        = array();
$T_Mensaje      = '';

$T_Tipo         = (isset($_REQUEST['tipo']))    ? $_REQUEST['tipo']     : ''        ;
$T_Pregunta     = (isset($_POST['pregunta']))   ? $_POST['pregunta']    : array()   ;
$T_CompanyData  = (isset($_POST['data']))       ? $_POST['data']        : array()   ;

$_Listado_MinBloqueo    = [0,2,5,10,15];
$_Listado_MinTarde      = [0,5,10,15,20,30];
$_Listado_MinSalida     = [0,5,10,15,20,30];
$_Listado_MinAusencia   = [15,20,30,40,50,60];

$Mostrar_Configuraciones_Control= true;
$Mostrar_Configuraciones_Interfaz= false; // Estas configuraciones ya no sirven porque las variables se usan en toda la aplicación

$o_Parametros_Interfaz = array();
$o_Parametros_Control = array();

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
            $T_Mensaje = _('Los combios fueron guardados correctamente.');
        }

        goto defaultlabel;

        break;

    // DEFAULT
    default:
        defaultlabel:

        if($Mostrar_Configuraciones_Control){
            $o_Parametros_Control  =  Config_L::obtenerTodosPorSeccionParametro('Parametros de Control de Personal');
        }
        if($Mostrar_Configuraciones_Interfaz){
            $o_Parametros_Interfaz =  Config_L::obtenerTodosPorSeccionParametro('Configuraciones de Interfaz');
        }

        break;
}



// Parametros de Control de Personal
// Configuraciones de Interfaz