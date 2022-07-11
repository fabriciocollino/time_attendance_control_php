<?php
require_once(dirname(__FILE__) . '/_ruta.php');
//require_once APP_PATH . '/controllers/upload.php';

$T_Tipo                 = !isset($_REQUEST['tipo']) ? isset($_SESSION['filtro']['tipo']) ? $_SESSION['filtro']['tipo'] : '' : $_REQUEST['tipo'];
$upload_result          = false;



if(isset($_FILES['uploaded_files'])) {
    $post_file_object   = $_FILES['uploaded_files'];
    $upload_result      = UploadHelper::upload_post_file($post_file_object);
}


/////////// READING XLSX ////////////
///      $file_temp_url = $p_post_file_object['tmp_name'];
$return                     = array();
$return_assoc               = array();
$return_assoc['Registros']  = array();


if($upload_result){

    $filename = $upload_result;

    $objReader      =   PHPExcel_IOFactory::createReaderForFile($filename);
    $objReader->setReadDataOnly();

    $objPHPExcel    =   $objReader->load($filename);

    //get all sheet names from the file
    $worksheetNames = $objPHPExcel->getSheetNames($filename);
    $return = array();

    foreach($worksheetNames as $key => $sheetName){
        //set the current active worksheet by name
        $objPHPExcel->setActiveSheetIndexByName($sheetName);
        //create an assoc array with the sheet name as key and the sheet contents array as value
        $return[$sheetName] = $objPHPExcel->getActiveSheet()->toArray(null, true,true,true);
    }


    foreach ($return  as $sheetKey => $sheet){

        $encabezados = array_shift($sheet);

        foreach ($sheet  as $rowKey => $row){

            foreach ($row  as $colKey => $cell){

                $return_assoc[$sheetKey][$rowKey][$encabezados[$colKey]] = $cell;

            }

        }

    }

}
/////////// END: READING XLSX ////////////


$T_Status               = array();
$T_Mensaje              =   "";
$T_Mensaje_Detalle      = '';
$T_Error                =   "";
$T_Error_Detalle        = '';

//$T_Tipo = "Marcaciones";

switch ($T_Tipo){

    case 'Personas':

        $array_personas         = $return_assoc['Registros'];

        foreach ($array_personas as $personaKey => $a_personaData){


            if(!isset($a_personaData['Legajo'])){
                $T_Status['No_Importadas'][] = array(
                    'Legajo'    =>  "",
                    'Persona'   =>  "",
                    'Status'    =>  _('Datos no válidos.'),
                    'Errores'   =>  array( 0 =>"No se encontró la cabecera Legajo en el archivo importado.")
                );
                continue;
            }


            $per_Legajo = $a_personaData['Legajo'];
            $o_Persona = Persona_L::obtenerPorLegajo($per_Legajo);
            $flag_Nueva = false;

            if(is_null($o_Persona)){
                $o_Persona      = new Persona_O();
                $flag_Nueva     = true;
            }

            $o_Persona->loadArrayExcel($a_personaData);

            if(!$o_Persona->esValido()){
                $T_Status['No_Importadas'][] = array(
                    'Legajo'    =>  $o_Persona->getLegajo(),
                    'Persona'   =>  $o_Persona->toArray(),
                    'Status'    =>  _('Datos no válidos.'),
                    'Errores'   =>  $o_Persona->getErrores()
                );
                continue;
            }

            if(!$o_Persona->save()) {
                $T_Status['No_Importadas'][] = array(
                    'Legajo'    =>  $per_Legajo,
                    'Status'    =>  'Error',
                    'Persona'   =>  $o_Persona->toArray(),
                    'Errores'   =>  $o_Persona->getErrores()
                );
                continue;
            }

            $T_Status['Importadas'][] = array(
                'Legajo'    =>  $per_Legajo,
                'Status'    => $flag_Nueva ? 'OK. Nueva persona.' : 'OK.',
                'Persona'   =>  $o_Persona->toArray()
            );
        }



        if(!empty($T_Status['Importadas'])){

            $T_Mensaje  =   "La operación de procesó correctamente.";

            foreach ($T_Status['Importadas'] as $_id => $_item){
                $nombre_persona = $_item['Persona']['apellido'] . ", " . $_item['Persona']['nombre'];

                $T_Mensaje_Detalle .=
                    "\n Persona: "                    .$nombre_persona . " (Legajo:". $_item['Persona']['legajo'] . ")"
                    ."\n . Importación: "       .$_item['Status']
                    ."\n";
            }


        }


        if(!empty($T_Status['No_Importadas'])){

            $T_Error = "Lo sentimos, hubo un error en la operación.";

            foreach ($T_Status['No_Importadas'] as $_id => $_item) {

                $nombre_persona = $_item['Persona']['apellido'] . ", " . $_item['Persona']['nombre'];
                $T_Error_Detalle .=
                    "\n Persona: "                    .$nombre_persona . " (Legajo:". $_item['Persona']['legajo'] . ")"
                    . "\n . Importación: " . $_item['Status'];

                foreach ($_item['Errores'] as $errorId => $error) {
                    $T_Error_Detalle .=
                        "\n . " . $error ;
                }
                $T_Error_Detalle .=
                    "\n";
            }
        }


        break;

    case 'Marcaciones':

        $array_logs     = $return_assoc['Registros'];

        //printear('$array_logs');
        //printear($array_logs);


        foreach ($array_logs as $_item_logID => $a_log_item){




            // VARIABLES ENTRADA
            $T_Fecha                        = isset($a_log_item['Fecha'])               ?   $a_log_item['Fecha']   :   '';
            $T_Hora_Entrada                 = isset($a_log_item['Hora Entrada'])        ?   $a_log_item['Hora Entrada']   :   '';
            $T_Fecha_Hora_Entrada           = $T_Fecha.' '.$T_Hora_Entrada;
            $T_Fecha_Hora_Entrada_time      = strtotime($T_Fecha_Hora_Entrada);
            $T_Fecha_Hora_Entrada_date      = date("Y-m-d H:i:s",$T_Fecha_Hora_Entrada_time);


            // ENTRADA
            $o_log_entrada  =   new Logs_Equipo_O();
            $o_log_entrada->setFechaHora($T_Fecha_Hora_Entrada_date, 'Y-m-d H:i:s');
            $o_log_entrada->setPersonaPorLegajo(isset($a_log_item["Legajo"])             ?  $a_log_item["Legajo"]               : "");
            $o_log_entrada->setLector(3); // Registro WEB
            $o_log_entrada->setEditado(1);
            $o_log_entrada->setEditadoPor(Registry::getInstance()->Usuario->getId());
            $o_log_entrada->setAccion(1);
            $o_log_entrada->ValidarEsDuplicado();



            // VARIABLES SALIDA
            $T_Fecha                        = isset($a_log_item['Fecha'])               ?   $a_log_item['Fecha']   :   '';
            $T_Hora_Salida                 = isset($a_log_item['Hora Salida'])        ?   $a_log_item['Hora Salida']   :   '';
            $T_Fecha_Hora_Salida           = $T_Fecha.' '.$T_Hora_Salida;
            $T_Fecha_Hora_Salida_time      = strtotime($T_Fecha_Hora_Salida);
            $T_Fecha_Hora_Salida_date      = date("Y-m-d H:i:s",$T_Fecha_Hora_Salida_time);


            // SALIDA
            $o_log_salida  =   new Logs_Equipo_O();
            $o_log_salida->setFechaHora($T_Fecha_Hora_Salida_date, 'Y-m-d H:i:s');
            $o_log_salida->setPersonaPorLegajo(isset($a_log_item["Legajo"])             ?  $a_log_item["Legajo"]               : "");
            $o_log_salida->setLector(3); // Registro WEB
            $o_log_salida->setEditado(1);
            $o_log_salida->setEditadoPor(Registry::getInstance()->Usuario->getId());
            $o_log_salida->setAccion(1);
            $o_log_salida->ValidarEsDuplicado();




            /*
            $_horario_ingreso           = isset($p_Datos["Horario Ingreso"])        ?  $p_Datos["Horario Ingreso"]      : "" ;
            $this->setHorarioIngreso($_horario_ingreso);

            $_horario_egreso            = isset($p_Datos["Horario Egreso"])         ?  $p_Datos["Horario Egreso"]       : "" ;
            $this->setHorarioEgreso($_horario_egreso);
            */


            $a_o_logs = [$o_log_entrada,$o_log_salida];


            foreach ($a_o_logs as $o_logID => $o_log){


                if(!$o_log->esValido()){

                    $T_Status['No_Importadas'][] = array(
                        'Status'    =>  _('Datos no válidos.'),
                        'Errores'   =>  $o_log->getErrores()
                    );
                    continue;
                }


                if(!$o_log->save()) {

                    $T_Status['No_Importadas'][] = array(
                        'Status'    =>  'Error.',
                        'Errores'   =>  $o_log->getErrores()
                    );
                    continue;
                }

                //printear('3. Se guardo correctamente');
                $T_Fecha_Hora_Salida_time       = strtotime($o_log->getFechaHora('d-m-Y H:i:s'));
                $T_Fecha                        = date("d-m-Y",$T_Fecha_Hora_Salida_time);
                $T_Hora                         = date("H:i:s",$T_Fecha_Hora_Salida_time);
                $fechaAnterior                  = "-";

                $T_Status['Importadas'][] = array(
                    'Legajo'        => $a_log_item['Legajo'],
                    'Fecha Hora'    => $o_log->getFechaHora('d-m-Y H:i:s'),
                    'Status'        => 'OK.'
                );

                // LOG EDITAR REGISTRO
                SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_LOG_EDITAR, $a_Logs_Tipos[LOG_LOG_EDITAR],'<b>Id:</b> ' . $o_log->getId(). ' <b>Persona: </b> ' .$o_log->getPerId() . '<b><span class="labelFechaAnterior">Fecha Anterior:</span></b> ' . $fechaAnterior . ' <b><span class="labelFechaNueva">Fecha Nueva:</b> ' . $T_Fecha.' '.$T_Hora, $o_log->getId());

            }



            if(!empty($T_Status['Importadas'])){

                $T_Mensaje  =   "La operación de procesó correctamente.";

                foreach ($T_Status['Importadas'] as $_id => $_item){
                    $legajo = $_item['Legajo'];

                    $T_Mensaje_Detalle .=
                        "\n Legajo: "               .$legajo
                        ."\n . Marcación: "         .$_item['Fecha Hora']
                        ."\n . Importación: "       .$_item['Status']
                        ."\n";
                }


            }


            if(!empty($T_Status['No_Importadas'])){


                $T_Error = "Lo sentimos, hubo un error en la operación.";


                foreach ($T_Status['No_Importadas'] as $_id => $_item) {

                    $T_Error_Detalle .=
                        "\n . Importación: " . $_item['Status'];

                    foreach ($_item['Errores'] as $errorId => $error) {
                        $T_Error_Detalle .=
                            "\n . " . $error . "\n";
                    }
                }

                //printear('$T_Status');

                //printear($T_Status);

            }



        }


        break;

    case 'Registros':

        $array_logs     = $return_assoc['Registros'];



        foreach ($array_logs as $_item_logID => $a_log_item){

            // VARIABLES ENTRADA
            $T_Fecha                = isset($a_log_item['Fecha'])               ?   $a_log_item['Fecha']   :   '';
            $T_Hora                 = isset($a_log_item['Hora'])        ?   $a_log_item['Hora']   :   '';
            $T_Fecha_Hora           = $T_Fecha.' '.$T_Hora;

            $_SESSION['array_logs']['T_Fecha_Hora']= $T_Fecha_Hora;

            $T_Fecha_Hora_time      = strtotime($T_Fecha_Hora);
            $T_Fecha_Hora_date      = date("Y-m-d H:i:s",$T_Fecha_Hora_time);


            // ENTRADA
            $o_log  =   new Logs_Equipo_O();
            $o_log->setFechaHora($T_Fecha_Hora_date, 'Y-m-d H:i:s');
            $o_log->setPersonaPorLegajo(isset($a_log_item["Legajo"])             ?  $a_log_item["Legajo"]               : "");
            $o_log->setLector(3); // Registro WEB
            $o_log->setEditado(1);
            $o_log->setEditadoPor(Registry::getInstance()->Usuario->getId());
            $o_log->setAccion(1);
            $o_log->ValidarEsDuplicado();



            /*
            $_horario_ingreso           = isset($p_Datos["Horario Ingreso"])        ?  $p_Datos["Horario Ingreso"]      : "" ;
            $this->setHorarioIngreso($_horario_ingreso);

            $_horario_egreso            = isset($p_Datos["Horario Egreso"])         ?  $p_Datos["Horario Egreso"]       : "" ;
            $this->setHorarioEgreso($_horario_egreso);
            */


            if(!$o_log->esValido()){

                $T_Status['No_Importadas'][] = array(
                    'Status'    =>  _('Datos no válidos.'),
                    'Errores'   =>  $o_log->getErrores()
                );
                continue;
            }


            if(!$o_log->save()) {

                $T_Status['No_Importadas'][] = array(
                    'Status'    =>  'Error.',
                    'Errores'   =>  $o_log->getErrores()
                );
                continue;
            }


            $T_Status['Importadas'][] = array(
                'Legajo'        => $a_log_item["Legajo"],
                'Fecha Hora'    => $o_log->getFechaHora('d-m-Y H:i:s'),
                'Status'        => 'OK.'
            );

            $fechaAnterior = "Registro Importado";
            // LOG EDITAR REGISTRO
            SeguridadHelper::Log(Registry::getInstance()->Usuario->getId(), LOG_LOG_EDITAR, $a_Logs_Tipos[LOG_LOG_EDITAR],'<b>Id:</b> ' . $o_log->getId(). ' <b>Persona: </b> ' .$o_log->getPerId() . '<b><span class="labelFechaAnterior">Fecha Anterior:</span></b> ' . $fechaAnterior . ' <b><span class="labelFechaNueva">Fecha Nueva:</b> ' . $T_Fecha.' '.$T_Hora, $o_log->getId());



        }

        if(!empty($T_Status['Importadas'])){

            $T_Mensaje  =   "La operación de procesó correctamente.";

            foreach ($T_Status['Importadas'] as $_id => $_item){
                $legajo = $_item['Legajo'];

                $T_Mensaje_Detalle .=
                    "\n Legajo: "               .$legajo
                    ."\n . Marcación: "         .$_item['Fecha Hora']
                    ."\n . Importación: "       .$_item['Status']
                    ."\n";
            }


        }


        if(!empty($T_Status['No_Importadas'])){


            $T_Error = "Lo sentimos, hubo un error en la operación.";


            foreach ($T_Status['No_Importadas'] as $_id => $_item) {

                $T_Error_Detalle .=
                    "\n . Importación: " . $_item['Status'];

                foreach ($_item['Errores'] as $errorId => $error) {
                    $T_Error_Detalle .=
                        "\n . " . $error . "\n";
                }
            }

            //printear('$T_Status');

            //printear($T_Status);

        }




        break;


}

$_SESSION['T_Mensaje']          = $T_Mensaje;
$_SESSION['T_Mensaje_Detalle']  = $T_Mensaje_Detalle;
$_SESSION['T_Error']            = $T_Error;
$_SESSION['T_Error_Detalle']    = $T_Error_Detalle;

$_SESSION['T_Status']    = $T_Status;


