<?php

require_once(dirname(__FILE__) . '/_ruta.php');





function xlsBOF3() {
    echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
}
function xlsEOF3() {
    echo pack("ss", 0x0A, 0x00);
}
function xlsWriteNumber3($Row, $Col, $Value) {
    echo pack("sssss", 0x203, 25, $Row, $Col, 0x0);
    echo pack("d", $Value);
}
function xlsWriteLabel3($Row, $Col, $Value) {
    $L = strlen($Value)+10;
    echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
    echo $Value;
}




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

        /* VARIABLES*/
        $alpha  = array(_("A"), _("B"), _("C"), _("D"), _("E"), _("F"), _("G"), _("H"), _("I"), _("J"), _("K"), _("L"), _("M"), _("N"), _("O"), _("P"), _("Q"), _("R"), _("S"), _("T"), _("U"), _("V"), _("W"), _("X"), _("Y"), _("Z"),
            _("AA"), _("AB"), _("AC"), _("AD"), _("AE"), _("AF"), _("AG"), _("AH"), _("AI"), _("AJ"), _("AK"), _("AL"), _("AM"), _("AN"), _("AO"), _("AP"), _("AQ"), _("AR"), _("AS"), _("AT"), _("AU"), _("AV"), _("AW"), _("AX"), _("AY"), _("AZ"),
            _("BA"), _("BB"), _("BC"), _("BD"), _("BE"), _("BF"), _("BG"), _("BH"), _("BI"), _("BJ"), _("BK"), _("BL"), _("BM"), _("BN"), _("BO"), _("BP"), _("BQ"), _("BR"), _("BS"), _("BT"), _("BU"), _("BV"), _("BW"), _("BX"), _("BY"), _("BZ"),
            _("CA"), _("CB"), _("CC"), _("CD"), _("CE"), _("CF"), _("CG"), _("CH"), _("CI"), _("CJ"), _("CK"), _("CL"), _("CM"), _("CN"), _("CO"), _("CP"), _("CQ"), _("CR"), _("CS"), _("CT"), _("CU"), _("CV"), _("CW"), _("CX"), _("CY"), _("CZ"))
        ;
        /** Include PHPExcel */
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        // Set document properties
        $objPHPExcel->getActiveSheet()->setTitle('COMPROBANTES');

        $row = 1;
        foreach ($csv_excel_data as $list) {
            $columna = 0;
            foreach ($list as $d_list) {
                $objPHPExcel->getActiveSheet()->setCellValue($alpha[$columna] . $row, $d_list)->getStyle($alpha[$columna] . $row)->getAlignment();
                $columna++;
            }
            $row++;
        }

        /* CREAR CARPETA CLIENTE */
        if (!file_exists(GS_CLIENT_TEMP_FOLDER)) mkdir(GS_CLIENT_TEMP_FOLDER, 0777, true);

        /* ESCRIBIR ARCHIVO */
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        /* ESCRIBIR ARCHIVO*/
        $objWriter->save(GS_CLIENT_TEMP_FOLDER . $T_Filename. ".xls");


        break;



    /* DESCARGAR */
    case 'Descargar':


        /* HEADER */
        header('Content-Encoding: UTF-8');
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: attachment; filename=" . $T_Filename. ".xls");

        /* VARIABLES */
        xlsBOF3();
        $key = 0;

        foreach($csv_excel_data as $row) {
            $key1 = 0;
            foreach($row as $itemID => $item) {
                if(is_int($item)){
                    xlsWriteNumber3($key, $key1 , $item);
                }
                else{
                    xlsWriteLabel3($key, $key1 , $item);

                }
                $key1++;
            }
            $key++;
        }

        /* FINAL DE ARCHIVO */
        xlsEOF3();


        break;

}

