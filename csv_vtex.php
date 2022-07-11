<?php

require_once(dirname(__FILE__) . '/_ruta.php');





/* VARIABLES DE ARCHIVO */
$T_Tipo                     = $_SESSION['filtro']['tipo'] ;
$T_Titulo                   = $_SESSION['titulo'];
$T_Filename                 = $_SESSION['filename'];
$T_Guardar                  = $_SESSION['filtro']['Guardar_Descargar'];
$csv_excel_data             = $_SESSION['Report']['Data'];


/* GUARDAR / DESCARGAR */
switch ($T_Guardar){

    /* GUARDAR */
    case 'Guardar':

        $csv_content = '';

        foreach($csv_excel_data as $list){
            foreach($list as $d_list){
                $csv_content.= $d_list.';';
            }
            $csv_content.= ''.PHP_EOL;
        }

        /* CREAR CARPETA CLIENTE */
        if (!file_exists(GS_CLIENT_TEMP_FOLDER)) mkdir(GS_CLIENT_TEMP_FOLDER, 0777, true);

        /* CSV HANDLER */
        $csv_handler = fopen (GS_CLIENT_TEMP_FOLDER . $T_Filename. ".csv",'w');

        /* ESCRIBIR ARCHIVO */
        fwrite ($csv_handler,$csv_content);

        /* CERRAR ARCHIVO */
        fclose ($csv_handler);



        break;


    /* DESCARGO PDF */
    case 'Descargar':

        /* HEADER */
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header("Content-Disposition: attachment; filename=" . $T_Filename. ".csv");

        /* VARIABLES */
        $outstream = fopen("php://output", 'w');

        foreach ($csv_excel_data as $row) {
            $_row = str_replace('"', '', $row);
            fputs($outstream, implode(',', $_row)."\n");
        }

        /* CERRAR ARCHIVO */
        fclose($outstream);
        break;

}

