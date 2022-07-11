<?php
require_once(dirname(__FILE__) . '/_ruta.php');
require_once APP_PATH . '/libs/mpdf/mpdf.php';


/* VARIABLES DE DATOS */
$o_Listado                              = array();
$a_Jornadas_Filtro_Persona              = array();
$a_Ausencias                            = array();
$a_Asistencias                          = array();
$a_Llegadas_Tarde                       = array();
$a_Salidas_Temprano                     = array();
$a_Intervalo                            = array();
$csv_excel_data                         = array();
$a_Pedidos                              = array();




/* VARIABLES DE ARCHIVO */
$T_Tipo                     = $_SESSION['filtro']['tipo'] ;
$T_Titulo                   = $_SESSION['titulo'];
$T_Filename                 = $_SESSION['filename'];
$T_Guardar                  = $_SESSION['filtro']['Guardar_Descargar'];
$a_Pedidos                  = $_SESSION['pedidos'] ;
$items_pedido               = $_SESSION['items'];
$string                     = $_SESSION['Report']['Data_string'];



$html           = '';
$img_logo       = APP_PATH . '/libs/mpdf/logo_soko_2020.jpg';
$footer         = 'Soko Marketplace';


// REPORTES
switch ($T_Tipo) {

    case 'Pedidos_Vtex':
        if (!is_null($a_Pedidos)) {

            $html .= '
                    <table style="width: 100%;">
                    ';

            foreach ($a_Pedidos as $pedidoID => $pedido) {

                foreach ($pedido as $item_pedidoKEY => $item_pedido) {

                    $html .= '
                        <tr>
                            <!-- LEGAJO -->
                            <td>
                                ' . $item_pedidoKEY . '
                            </td>
            
                            <!-- NOMBRE -->
                            <td>
                                <b>' . $item_pedido . '
                            </td>
                        </tr>';
                }


                $html .= '
                        <tr>
                            <td> </td>
           
                            <td> </td>
                            
                        </tr>';

                $html .= '
                            <tr>
                                <td>
                                   <b>CÓDIGO
                                </td>
                                
                                <td>
                                  <b>DETALLE
                                </td>
                                
                                <td>
                                   <b>PRECIO
                                </td>
                                
                                <td>
                                  <b>CANTIDAD
                                </td>
                            
                                <td>
                                   <b>IVA
                                </td>
                      
                            </tr>
                        ';

                foreach ($items_pedido[$pedidoID] as $itemID => $item){


                    $html .= '
                        <tr>
                            <td>
                              ' . $item["CODIGO"] . '
                            </td>
                                <td>
                              ' . $item["DETALLE"] . '
                            </td>
                                <td>
                              ' . $item["PRECIOUNITARIO"] . '
                            </td>
                                <td>
                              ' . $item["CANTIDAD"] . '
                            </td>
                                <td>
                              ' . $item["IVA"] . '
                            </td>
                        </tr>
                    ';


                }


                $html .= '
                        <tr>
                            <td> </td>
           
                            <td> </td>
                            
                        </tr>';
            }

            $html .= '
                    </table>';




        }
        break;

}


/* FORMATO REPORTE */
$css = '
            table{
                text-align:left;
                padding: 10px;
                border-collapse: collapse;
                width: 90%;   
            }
            td {
                text-align:left;
                padding: 7px;
                border-collapse: collapse;
                border-bottom: 0px solid #ffffff;
                width: 10%;
                height: 20 px;
         
            }';

/* VARIABLES */
$margen_izquierdo       = 10;
$margen_derecho         = 10;
$margen_superior        = 30;
$margen_inferior        = 25;

/* VARIABLES */
$dia_actual         = date('d/m/Y');
$hora_actual        = date('H:y');
$encabezado         = $T_Titulo . "<br>"
    . _("Generado el ").$dia_actual._(" a las ").$hora_actual
    . "<br>";


/* DATOS PDF */
if(count($a_Pedidos) <= 0){
    $html = 'No hay registros';
}


/* CREO PDF */
$mpdf = new mPDF('', 'A4', 8, 'freesans', $margen_izquierdo, $margen_derecho, $margen_superior, $margen_inferior, 5, 5, 'landscape');

/* SETEO PDF */
$mpdf->SetTitle($T_Titulo);
$mpdf->SetSubject($dia_actual);

$_headerhtml = ' <table style="text-align: left;width: 100%; height: 20%;border-bottom: 0.5px solid #CCCCCC;"  >
                        <tr>
                            <td align="left" style="width: 25%; position:relative; text-align:center;border-bottom: 0.5px solid #CCCCCC;" > 
                                <img src="' . $img_logo . '" width="150" />
                            </td>
                            <td align="left" style="width:75%;  font-style: normal;font-size: 12px;border-bottom: 0.5px solid #CCCCCC;">
                                ' . '<b>' . 'Pedidos Soko' . '</b>' . '<br>' . $encabezado . '
                            </td>
                        </tr>
                    </table>';


$mpdf->SetHTMLHeader($_headerhtml);

$_footerhtml = '<table width="100%">
                        <tr>
                            <td width="30%" style="border-bottom: 0px solid;">    
                            </td>
                            <td width="40%" align="center" style="border-bottom: 0px solid;"> 
                                '.$footer.'
                            </td>
                            <td width="30%" style="text-align: right;border-bottom: 0px solid;"> 
                                {PAGENO}
                            </td>
                        </tr>
                    </table>';

$mpdf->SetHTMLFooter($_footerhtml);

/* ESCRIBO PDF */
$mpdf->WriteHTML($css, 1);
$mpdf->WriteHTML($html);


switch ($T_Guardar){

    /* ABRIR EN NAVEGADOR */
    case 'Guardar':
        if (!file_exists(GS_CLIENT_TEMP_FOLDER)) mkdir(GS_CLIENT_TEMP_FOLDER, 0777, true);
        $mpdf->Output( GS_CLIENT_TEMP_FOLDER . $T_Filename . ".pdf","F");
        break;

    /* DESCARGO PDF */
    case 'Descargar':

        $mpdf->Output($T_Filename. ".pdf"  , "D");
        break;

    /* ABRIR EN NAVEGADOR
    default:
        $mpdf->Output();
        break;
    */

}


