<?php
require_once dirname(__FILE__) . '/../../_ruta.php';
use google\appengine\api\cloud_storage\CloudStorageTools;


$T_Titulo       =   _('Importar');
$T_Script       =   'importar';
$Item_Name      =   "importar";
$T_Link         =   '';
$T_Mensaje      =   '';
$o_Listado      =   null;
$a_Atributos    = null;
$a_Nombres      = array();
$a_Required     = null;
$o_Data_out     = null;
$T_Accion       =   isset($_POST['accion'])   ?  $_POST['accion']         : '';
$Botones_Exportar   = true;

$T_Tipo             =   isset   ($_REQUEST['tipo']   )       ?   $_REQUEST['tipo']                     : '';
$Tline_index        =   isset   ($_REQUEST['id']    )       ?   (integer)$_REQUEST['id']              : 0;
$_SESSION['tipo']   = $T_Tipo;
$a_Data             = isset( $_POST['data'] )     ? json_decode($_POST['data'],true)  : null;





$array_dominio          =   explode         (   "."     ,   $_SERVER['HTTP_HOST']   );
$subdominio_inseguro    =   array_shift     (   $array_dominio                      );
$subdominio             =   preg_replace    (   "/[^a-zA-Z0-9]+/", "", $subdominio_inseguro);

/*
if($subdominio == 'demo3'){
    $personas = Persona_L::obtenerDesdeFiltro(Filtro_L::get_filtro_persona(['estado' => 'Todos']));

    foreach ($personas as $perID => $persona){
        $email = $persona->getEmail();
        if ($email != ''){
            $arr = explode('@', $email);
            $new_email = $arr[0]. "@enpuntocontrol.com";
            $persona->setEmail($new_email);
            $persona->save();
        }
    }
}
*/


if($subdominio == 'demo3'){
    $Persona_nro_factura = Persona_L::obtenerPorId(5);
    $nro_factura = $Persona_nro_factura->getRID();
    $_hora = $Persona_nro_factura->getNombre();
    $_dia = $Persona_nro_factura->getSegundoNombre();
    $T_nro_comprobante_inicio       =   isset   ($_REQUEST['nro_comp']   )                  ?   (int) $_REQUEST['nro_comp']                 : $nro_factura;

    $T_email_destinatario           =   isset   ($_REQUEST['email_destinatario']   )        ?   $_REQUEST['email_destinatario']             : 'emiliano@facturante.com';//"logistica@sokogo.com,gp@sokogo.com,fgs@sokogo.com";
    $T_email_cc                     =   isset   ($_REQUEST['email_cc']   )                  ?   $_REQUEST['email_cc']                       : '';//"ff@sokogo.com,sf@sokogo.com,fc@sokogo.com,frs@sokogo.com";
}
else{
    $Persona_nro_factura = Persona_L::obtenerPorId(10);
    $nro_factura = $Persona_nro_factura->getRID();
    $_hora = $Persona_nro_factura->getNombre();
    $_dia = $Persona_nro_factura->getSegundoNombre();
    $T_nro_comprobante_inicio       =   isset   ($_REQUEST['nro_comp']   )                  ?   (int) $_REQUEST['nro_comp']                 : $nro_factura;

    $T_email_destinatario           =   isset   ($_REQUEST['email_destinatario']   )        ?   $_REQUEST['email_destinatario']             : "logistica@sokogo.com,gp@sokogo.com,fgs@sokogo.com";
    $T_email_cc                     =   isset   ($_REQUEST['email_cc']   )                  ?   $_REQUEST['email_cc']                       : "ff@sokogo.com,sf@sokogo.com,fc@sokogo.com";
}
$T_hora_hasta                   =   isset   ($_REQUEST['hora_hasta']   )                ?   $_REQUEST['hora_hasta']                   :    date('H')."_00";
$T_dia_hasta                    =   isset   ($_REQUEST['dia_hasta']   )                 ?   $_REQUEST['dia_hasta']                     :   date("d_m_Y");;


define('IMPORTAR_PEDIDOS',      0);
define('IMPORTAR_PRODUCTOS',    1);

$a_Import_Type = array(
    IMPORTAR_PEDIDOS    => "Pedidos"//,
);

$iva_105["7799037060011"] = 	10.5	;
$iva_105["7799037060028"] = 	10.5	;
$iva_105["7799037060035"] = 	10.5	;
$iva_105["7792180134509"] = 	10.5	;

$condicion_venta["Depósito bancario"]   = 1;

$condicion_venta["Maestro"]             = 3;
$condicion_venta["Visa Electron"]       = 3;
$condicion_venta["Cabal"]               = 3;
$condicion_venta["Shopping"]            = 3;
$condicion_venta["Diners"]              = 3;

$condicion_venta["Visa"]                = 4;
$condicion_venta["American Express"]    = 4;
$condicion_venta["Naranja"]             = 4;
$condicion_venta["Mastercard"]          = 4;
$condicion_venta["Vale"]                = 1;


$razon_social["Seller-A-Kolor"] = "VENDIDO POR CUENTA Y ORDEN DE H20 S.R.L.";
$razon_social["Seller-Alaska-Bolsos"] = "VENDIDO POR CUENTA Y ORDEN DE MURES BLACO PABLO GEMAN";
$razon_social["Seller-Arcafé"] = "VENDIDO POR CUENTA Y ORDEN DE ARCAFÉ S.A.";
$razon_social["Seller-Arrorro-Pañalera"] = "VENDIDO POR CUENTA Y ORDEN DE ARIEL MONTI";
$razon_social["Seller-Articulos-Exclusivos"] = "VENDIDO POR CUENTA Y ORDEN DE ARTÍCULOS ESCLUSIVOS S.R.L.";
$razon_social["Seller-Beauty-24"] = "VENDIDO POR CUENTA Y ORDEN DE BEAUTY 24 S.A.S.";
$razon_social["Seller-Bebidas-Perez"] = "VENDIDO POR CUENTA Y ORDEN DE MOLINATTI TOMÁS";
$razon_social["Seller-Berlín-Iluminaria"] = "VENDIDO POR CUENTA Y ORDEN DE RAMOS MARCELO ALEJANDRO";
$razon_social["Seller-Bristol"] = "VENDIDO POR CUENTA Y ORDEN DE BRISTOL S.A.";
$razon_social["Seller-Care-City"] = "VENDIDO POR CUENTA Y ORDEN DE CAPETTINI MARIANA LAURA";
$razon_social["Seller-Club-Dos-Copas"] = "VENDIDO POR CUENTA Y ORDEN DE RODRIGUEZ GRACIELA PILAR";
$razon_social["Seller-Del-Alma-Eventos"] = "VENDIDO POR CUENTA Y ORDEN DE GARCIA ICELY ANA CARLA";
$razon_social["Seller-Derman"] = "VENDIDO POR CUENTA Y ORDEN DE DEL ROSSO DAMIÁN OSCAR MOISES";
$razon_social["Seller-Etiquet"] = "VENDIDO POR CUENTA Y ORDEN DE MURES BLANCO PABLO GERMÁN";
$razon_social["Seller-Fabrica-Asiarg"] = "VENDIDO POR CUENTA Y ORDEN DE HOLSTEIN AILEN NERINA";
$razon_social["Seller-Farma-Botiquin"] = "VENDIDO POR CUENTA Y ORDEN DE SADOWSKI ERICA MARCELA";
$razon_social["Seller-Gas-Tank"] = "VENDIDO POR CUENTA Y ORDEN DE RIVERA NELSON ROBINSON";
$razon_social["Seller-Gazzo-Sartoria"] = "VENDIDO POR CUENTA Y ORDEN DE FERRER GONZALO";
$razon_social["Seller-Joyeria-Angel-Azul"] = "VENDIDO POR CUENTA Y ORDEN DE LIRUSSO MARÍA LAURA";
$razon_social["Seller-Juana-de-la-Fuente"] = "VENDIDO POR CUENTA Y ORDEN DE NASIF MARÍA BELÉN";
$razon_social["Seller-Kit-Cuarenteno"] = "VENDIDO POR CUENTA Y ORDEN DE ARAYA RODRIGO ARIEL";
$razon_social["Seller-La-Casa-Del-Peinador"] = "VENDIDO POR CUENTA Y ORDEN DE GONZALEZ MÓNICA B. Y MIRIAM A.";
$razon_social["Seller-Laila-Petshop"] = "VENDIDO POR CUENTA Y ORDEN DE MOYANO STEFFANIA NATALI";
$razon_social["Seller-Laprida-Hogar"] = "VENDIDO POR CUENTA Y ORDEN DE GONZALES VISCIO GUSTAVO SEBASTIAN";
$razon_social["Seller-Liveslow-Bottles"] = "VENDIDO POR CUENTA Y ORDEN DE VALESE MARTÍN LAHUEL";
$razon_social["Seller-Mia-Fragancias"] = "VENDIDO POR CUENTA Y ORDEN DE TORRI MARIA ALEJANDRA";
$razon_social["Seller-Natalia-Hogar"] = "VENDIDO POR CUENTA Y ORDEN DE TRIVELLINI PATRICIA DEL VALLE";
$razon_social["Seller-Own-Vision"] = "VENDIDO POR CUENTA Y ORDEN DE COHEN JACOBO EITAN";
$razon_social["Seller-Pañalandia"] = "VENDIDO POR CUENTA Y ORDEN DE RESKIN MIRTHA NOEMI";
$razon_social["Seller-Pañalera-GK"] = "VENDIDO POR CUENTA Y ORDEN DE AUGUSTO DARIO PRANDI";
$razon_social["Seller-Provimat"] = "VENDIDO POR CUENTA Y ORDEN DE ROMERA JUAN CARLOS";
$razon_social["Seller-Sol-Urburu-Joyas"] = "VENDIDO POR CUENTA Y ORDEN DE URBURU MARÍA SOLEDAD";
$razon_social["Seller-Total-25"] = "VENDIDO POR CUENTA Y ORDEN DE TOTAL 25 SANITARIOS S.A.";
$razon_social["Soko-Decoración"] = "";
$razon_social["Soko-Supermercado"] = "";
$razon_social["Chaski"] = "";
$razon_social["Seller-The-Light-Speed"] = "VENDIDO POR CUENTA Y ORDEN DE THE LIGHT SPEED ";
$razon_social["Seller-Quimex"] = "VENDIDO POR CUENTA Y ORDEN DE QUIMEX";
$razon_social["Seller-Pinturerías-de-Color-SAS"] = "VENDIDO POR CUENTA Y ORDEN DE PINTURERÍAS DE COLOR SAS";

$descuentos_ignorar["Europcasal"]               = 	-1745.00	;
$descuentos_ignorar["Europfernandez"]           = 	-1773.00	;
$descuentos_ignorar["Europroscino"]             = 	-1723.00	;
$descuentos_ignorar["Europoviedo"]              = 	-1955.00	;
$descuentos_ignorar["Europrios"]                = 	-1625.00	;
$descuentos_ignorar["Europmansilla"]            = 	-1779.00	;
$descuentos_ignorar["Europdias"]                = 	-2218.00	;
$descuentos_ignorar["Europestevez"]             = 	-1908.00	;
$descuentos_ignorar["Europserafino"]            = 	-2702.00	;
$descuentos_ignorar["Europmegarejo"]            = 	-2082.00	;
$descuentos_ignorar["Europabad"]                = 	-1521.00	;
$descuentos_ignorar["Europgonzalez"]            = 	-2247.00	;
$descuentos_ignorar["Europdantas"]              = 	-1633.00	;

$descuentos_ignorar["Townpuesto1"]                = 	-5000.00	;
$descuentos_ignorar["Townpuesto2"]            = 	-5000.00	;
$descuentos_ignorar["Townpuesto3"]              = 	-5000.00	;

$descuentos_ignorar["GIFTCARD-ENVIO-GRATIS"]    = 	-249.00	;

$descuentos_ignorar["EuropRIOS"]        =   -1675.00;
$descuentos_ignorar["EuropOBST"]        =   -1611.00;
$descuentos_ignorar["EuropMANS"]        =   -1752.00;
$descuentos_ignorar["EuropDIAS"]        =   -2446.00;
$descuentos_ignorar["EuropPERE"]        =   -1671.00;
$descuentos_ignorar["EuropCORD"]        =   -1763.00;
$descuentos_ignorar["EuropRUIZ"]        =   -1957.00;
$descuentos_ignorar["EuropESTE"]        =   -1952.00;
$descuentos_ignorar["EuropSERA"]        =   -3090.00;
$descuentos_ignorar["EuropIDAR"]        =   -1664.00;
$descuentos_ignorar["EuropMELG"]        =   -1484.00;
$descuentos_ignorar["EuropGONZ"]        =   -1530.00;
$descuentos_ignorar["EuropDANT"]        =   -2143.00;
$descuentos_ignorar["EuropHERE"]        =   -1543.00;
$descuentos_ignorar["EuropCATA"]        =   -1757.00;
$descuentos_ignorar["EuropIGLE"]        =   -1595.00;
$descuentos_ignorar["EuropFERN"]        =   -2103.00;
$descuentos_ignorar["EuropALBA"]        =   -1510.00;
$descuentos_ignorar["EuropROSC"]        =   -1693.00;
$descuentos_ignorar["EuropOVIE"]        =   -1805.00;
$descuentos_ignorar["EuropMEND"]        =   -1830.00;

$descuentos_ignorar["EuropRIOS"]        =   -1675.00;
$descuentos_ignorar["EuropOBST"]        =   -1611.00;
$descuentos_ignorar["EuropMANS"]        =   -1752.00;
$descuentos_ignorar["EuropDIAS"]        =   -2446.00;
$descuentos_ignorar["EuropPERE"]        =   -1671.00;
$descuentos_ignorar["EuropCORD"]        =   -1763.00;
$descuentos_ignorar["EuropRUIZ"]        =   -1957.00;
$descuentos_ignorar["EuropESTE"]        =   -1952.00;
$descuentos_ignorar["EuropSERA"]        =   -3090.00;
$descuentos_ignorar["EuropIDAR"]        =   -1664.00;
$descuentos_ignorar["EuropMELG"]        =   -1484.00;
$descuentos_ignorar["EuropGONZ"]        =   -1530.00;
$descuentos_ignorar["EuropDANT"]        =   -2143.00;
$descuentos_ignorar["EuropHERE"]        =   -1543.00;
$descuentos_ignorar["EuropCATA"]        =   -1757.00;
$descuentos_ignorar["EuropIGLE"]        =   -1595.00;
$descuentos_ignorar["Europfern"]        =   -2103.00;
$descuentos_ignorar["EuropALBA"]        =   -1510.00;
$descuentos_ignorar["EuropROSC"]        =   -1693.00;
$descuentos_ignorar["EuropOVIE"]        =   -1805.00;
$descuentos_ignorar["EuropMEND"]        =   -1830.00;

$descuentos_ignorar["EuropALBA1E"]      =   -2304.00;
$descuentos_ignorar["EuropBIAN1E"]      =   -2935.00;
$descuentos_ignorar["EuropCORD1E"]      =   -1553.00;
$descuentos_ignorar["EuropDIAS1E"]      =   -1795.00;
$descuentos_ignorar["EuropESTE1E"]      =   -1495.00;
$descuentos_ignorar["EuropFERN1E"]      =   -1567.00;
$descuentos_ignorar["EuropFUNE1E"]      =   -1873.00;
$descuentos_ignorar["EuropGONZ1E"]      =   -1770.00;
$descuentos_ignorar["EuropGUTI1E"]      =   -1562.00;
$descuentos_ignorar["EuropLEDE1E"]      =   -2133.00;
$descuentos_ignorar["EuropLUNA1E"]      =   -4050.00;
$descuentos_ignorar["EuropMANS1E"]      =   -1588.00;
$descuentos_ignorar["EuropOBST1E"]      =   -1602.00;
$descuentos_ignorar["EuropROSC1E"]      =   -1885.00;
$descuentos_ignorar["EuropRUIZ1E"]      =   -1761.00;
$descuentos_ignorar["EuropSERA1E"]      =   -2880.00;
$descuentos_ignorar["EuropLAST1E"]      =   -3469.00;
$descuentos_ignorar["EuropPIRI1E"]      =   -3096.00;

$descuentos_ignorar["europalba1e"]      =   -2304.00;
$descuentos_ignorar["europbian1e"]      =   -2935.00;
$descuentos_ignorar["europcord1e"]      =   -1553.00;
$descuentos_ignorar["europdias1e"]      =   -1795.00;
$descuentos_ignorar["europeste1e"]      =   -1495.00;
$descuentos_ignorar["europfern1e"]      =   -1567.00;
$descuentos_ignorar["europfune1e"]      =   -1873.00;
$descuentos_ignorar["europgonz1e"]      =   -1770.00;
$descuentos_ignorar["europguti1e"]      =   -1562.00;
$descuentos_ignorar["europlede1e"]      =   -2133.00;
$descuentos_ignorar["europluna1e"]      =   -4050.00;
$descuentos_ignorar["europmans1e"]      =   -1588.00;
$descuentos_ignorar["europobst1e"]      =   -1602.00;
$descuentos_ignorar["europrosc1e"]      =   -1885.00;
$descuentos_ignorar["europruiz1e"]      =   -1761.00;
$descuentos_ignorar["europsera1e"]      =   -2880.00;
$descuentos_ignorar["europlast1e"]      =   -3469.00;
$descuentos_ignorar["europpiri1e"]      =   -3096.00;

$descuentos_ignorar["EuropIGLE1F"] = -1605.00 ;
$descuentos_ignorar["EuropFUNE1F"] = -1916.00 ;
$descuentos_ignorar["EuropFERN1F"] = -1542.00 ;
$descuentos_ignorar["EuropROSC1F"] = -1712.00 ;
$descuentos_ignorar["EuropLUNA1F"] = -2169.00 ;
$descuentos_ignorar["EuropCOND1F"] = -1613.00 ;
$descuentos_ignorar["EuropDELA1F"] = -1790.00 ;
$descuentos_ignorar["EuropLAST1F"] = -3140.00 ;
$descuentos_ignorar["EuropLESC1F"] = -1512.00 ;
$descuentos_ignorar["EuropMART1F"] = -1980.00 ;
$descuentos_ignorar["EuropCAST1F"] = -3060.00 ;
$descuentos_ignorar["EuropLODE1F"] = -3510.00 ;
$descuentos_ignorar["EuropPIRI1F"] = -3415.00 ;
$descuentos_ignorar["EuropBAUE1F"] = -1509.00 ;
$descuentos_ignorar["EuropCONT1F"] = -1620.00 ;
$descuentos_ignorar["EuropFIGU1F"] = -1922.00 ;
$descuentos_ignorar["EuropRUIZ1F"] = -1502.00 ;
$descuentos_ignorar["EuropGONZ1F"] = -1683.00 ;
$descuentos_ignorar["EuropSERA1F"] = -2407.00 ;
$descuentos_ignorar["EuropIGLE2E"] = -1676.00 ;
$descuentos_ignorar["EuropFUNE2E"] = -1628.00 ;
$descuentos_ignorar["EuropLEDE2E"] = -1843.00 ;
$descuentos_ignorar["EuropMEND2E"] = -1560.00 ;
$descuentos_ignorar["EuropLUNA2E"] = -1615.00 ;
$descuentos_ignorar["EuropDEL 2E"] = -3320.00 ;
$descuentos_ignorar["EuropROSC2E"] = -1896.00 ;
$descuentos_ignorar["EuropBENI2E"] = -1555.00 ;
$descuentos_ignorar["EuropGALE2E"] = -1505.00 ;
$descuentos_ignorar["EuropBIAN2E"] = -1716.00 ;
$descuentos_ignorar["EuropCAST2E"] = -2124.00 ;
$descuentos_ignorar["EuropLODE2E"] = -2352.00 ;
$descuentos_ignorar["EuropESTE2E"] = -1812.00 ;
$descuentos_ignorar["EuropSERA2E"] = -2631.00 ;
$descuentos_ignorar["europigle1f"] = -1605.00 ;
$descuentos_ignorar["europfune1f"] = -1916.00 ;
$descuentos_ignorar["europfern1f"] = -1542.00 ;
$descuentos_ignorar["europrosc1f"] = -1712.00 ;
$descuentos_ignorar["europluna1f"] = -2169.00 ;
$descuentos_ignorar["europcond1f"] = -1613.00 ;
$descuentos_ignorar["europdela1f"] = -1790.00 ;
$descuentos_ignorar["europlast1f"] = -3140.00 ;
$descuentos_ignorar["europlesc1f"] = -1512.00 ;
$descuentos_ignorar["europmart1f"] = -1980.00 ;
$descuentos_ignorar["europcast1f"] = -3060.00 ;
$descuentos_ignorar["europlode1f"] = -3510.00 ;
$descuentos_ignorar["europpiri1f"] = -3415.00 ;
$descuentos_ignorar["europbaue1f"] = -1509.00 ;
$descuentos_ignorar["europcont1f"] = -1620.00 ;
$descuentos_ignorar["europfigu1f"] = -1922.00 ;
$descuentos_ignorar["europruiz1f"] = -1502.00 ;
$descuentos_ignorar["europgonz1f"] = -1683.00 ;
$descuentos_ignorar["europsera1f"] = -2407.00 ;
$descuentos_ignorar["europigle2e"] = -1676.00 ;
$descuentos_ignorar["europfune2e"] = -1628.00 ;
$descuentos_ignorar["europlede2e"] = -1843.00 ;
$descuentos_ignorar["europmend2e"] = -1560.00 ;
$descuentos_ignorar["europluna2e"] = -1615.00 ;
$descuentos_ignorar["europdel 2e"] = -3320.00 ;
$descuentos_ignorar["europrosc2e"] = -1896.00 ;
$descuentos_ignorar["europbeni2e"] = -1555.00 ;
$descuentos_ignorar["europgale2e"] = -1505.00 ;
$descuentos_ignorar["europbian2e"] = -1716.00 ;
$descuentos_ignorar["europcast2e"] = -2124.00 ;
$descuentos_ignorar["europlode2e"] = -2352.00 ;
$descuentos_ignorar["europeste2e"] = -1812.00 ;
$descuentos_ignorar["europsera2e"] = -2631.00 ;
$descuentos_ignorar["EuropESTE2E"] = -1812.00 ;




$o_Data                 = null;
$o_Envios               = array();

$intereses_21           = array();
$intereses_105          = array();

$costo_acumulado_21     = array();
$costo_acumulado_105    = array();


$vales        = array();
$descuentos         = array();
$comprobante        = array();
$nombres            = array();
$sellers            = array();
$comentario         = array();
$items_pedido       = array();
$direccion_pedido   = array();
$a_Pedidos          = array();
$cupon              = array();
$correo_electronico = array();
$medio_de_pago      = array();
$fecha_pedido       = array();
$estado_pedido       = array();
$codigo_autorizacion_pago        = array();




switch ($T_Tipo) {

    case IMPORTAR_PEDIDOS:

        $o_Data_out = array();
        if(is_null($a_Data))  break;

        $a_Nombres_csv = $a_Data[0];

        array_shift($a_Data);
        //array_pop($a_Data);


        $total_envio        = 0;
        $_index             = 0;
        $seller_key         = str_replace(" ", "-", $a_Data[0][21]);
        $orderline_index    = $a_Data[0][1]."-".$seller_key;
        $o_Envios[$orderline_index] =0;
        $sellers[$orderline_index] = $a_Data[0][21];
        $comentario[$orderline_index] = $razon_social[$seller_key];




        foreach ($a_Data as $_id => $data){

            if(!isset($data[21])) continue;

            $seller_key = str_replace(" ", "-", $data[21]);


            $current_orderID    = $data[1]."-".$seller_key;
            $pedidos[$current_orderID] = $data[1];


            if($orderline_index != $current_orderID){
                $orderline_index    = $current_orderID;

                if(!isset($o_Data[$orderline_index])){
                    $_index = 0;
                    $o_Envios[$orderline_index] = 0;
                    $sellers[$orderline_index] = $data[21];
                    $comentario[$orderline_index] = $razon_social[$seller_key];
                }
                else{
                    $_index = count($o_Data[$orderline_index]);
                }
            }

            $o_Data[$orderline_index][$_index]  =    $data;
            $o_Envios[$orderline_index] += $data[47];



            $_index++;

        }






        $line_index = 0;

        $a_Nombres = array("RAZONSOCIAL","NRODOCUMENTO","TIPODOCUMENTO"
        ,"DIRECCIONFISCAL","PROVINCIA","LOCALIDAD","CODIGOPOSTAL","PERCIBEIVA","PERCIBEIIBB","TRATAMIENTOIMPOSITIVO","ENVIARCOMPROBANTE","MAILFACTURACION","CONTACTO","TELEFONO","CONVENIOMULTILATERAL","ENVIARBOTONPAGO","NUMEROCOMPROBANTE","FECHAHORA","TIPOCOMPROBANTE","OBSERVACIONES","DESCUENTO","PERCEPCIONIVA","PERCEPCIONIIBB","ORDENCOMPRA","REMITO","IMPORTEIMPUESTOSINTERNOS","IMPORTEPERCEPCIONESMUNIC","MONEDA","TIPODECAMBIO","CONDICIONVENTA","PRODUCTOSERVICIO","FECHAVENCIMIENTO","FECHAINICIOABONO","FECHAFINABONO","CANTIDAD","DETALLE","CODIGO","BONIFICACION","IVA","PRECIOUNITARIO","CBU","ESANULACION");

        $contador_pedidos = 1;
        $contador_pedidos_pago_pendiente = 0;
        $contador_pedidos_seller = 0;
        $contador_pedidos_a_despachar = 0;
        $contador_pedidos_cancelados = 0;
        foreach ($o_Data as $pedidoID => $pedido){


            $comprobante[$pedidoID]="00003-0000".$T_nro_comprobante_inicio;
            $nombres[$pedidoID]             = mb_convert_case($pedido[0][4]." ".$pedido[0][5], MB_CASE_TITLE, "UTF-8");
            $descuentos[$pedidoID]          = 0;
            $costo_acumulado_21[$pedidoID]  = 0;
            $costo_acumulado_105[$pedidoID] = 0;

            $costo_acumulado_21_sinIVA[$pedidoID]  = 0;
            $costo_acumulado_105_sinIVA[$pedidoID] = 0;
            $vales[$pedidoID] = 0;
            $medio_de_pago[$pedidoID] = "";

            $items_pedido[$pedidoID]        = array();

            $a_Pedidos [$pedidoID]["ORDEN"]             = $pedido[0][1];
            $a_Pedidos [$pedidoID]["SELLER"]            = $pedido[0][21];
            $a_Pedidos [$pedidoID]["FECHA"]             = substr($pedido[0][3], 0, -1);
            $a_Pedidos [$pedidoID]["CLIENTE"]           = mb_convert_case($pedido[0][4]." ".$pedido[0][5], MB_CASE_TITLE, "UTF-8");
            $a_Pedidos [$pedidoID]["NOMBRE"]            = mb_convert_case($pedido[0][4], MB_CASE_TITLE, "UTF-8");
            $a_Pedidos [$pedidoID]["APELLIDO"]          = mb_convert_case($pedido[0][5], MB_CASE_TITLE, "UTF-8");
            $a_Pedidos [$pedidoID]["DNI"]               = $pedido[0][6];
            $a_Pedidos [$pedidoID]["FORMA DE PAGO"]     = trim(((string)$pedido[0][30]));
            $a_Pedidos [$pedidoID]["EMAIL"]             = $pedido[0][7];
            $a_Pedidos [$pedidoID]["TELÉFONO"]          = $pedido[0][8];

            $a_Pedidos [$pedidoID]["RECIBE"]            = mb_convert_case($pedido[0][13]." ".$pedido[0][14], MB_CASE_TITLE, "UTF-8");
            $a_Pedidos [$pedidoID]["CALLE"]             = mb_convert_case($pedido[0][14], MB_CASE_TITLE, "UTF-8");
            $a_Pedidos [$pedidoID]["ALTURA"]            = $pedido[0][15];
            $a_Pedidos [$pedidoID]["ACLARACIONES"]      = mb_convert_case($pedido[0][16], MB_CASE_TITLE, "UTF-8");
            $a_Pedidos [$pedidoID]["CIUDAD"]            = mb_convert_case($pedido[0][10], MB_CASE_TITLE, "UTF-8");
            $a_Pedidos [$pedidoID]["PROVINCIA"]         = mb_convert_case($pedido[0][9], MB_CASE_TITLE, "UTF-8");
            $a_Pedidos [$pedidoID]["C.P."]              = $pedido[0][19];
            $a_Pedidos [$pedidoID]["DEMORA ESTIMADA"]   = str_replace("bd","",$pedido[0][23]). " día(s) hábil(es) ";

            $a_Pedidos [$pedidoID]["STATUS"]            = $pedido[0][91];
            $estado_pedido[$pedidoID]                   =  $a_Pedidos [$pedidoID]["STATUS"];

            if($pedido[0][64] != ''){
                $codigo_autorizacion_pago[$pedidoID]        =  $pedido[0][64] . "(AUTH ID), " . $pedido[0][65]  . "(TID)";
            }
            else{
                $codigo_autorizacion_pago[$pedidoID]        = " - (AUTH ID), " . $pedido[0][65]  . "(TID)";
            }




            $cupon_pedido[$pedidoID]        = str_replace(" ","-",$pedido[0][29]);
            $direccion_pedido[$pedidoID]    = $a_Pedidos [$pedidoID]["CALLE"]. " ". $a_Pedidos [$pedidoID]["ALTURA"]. ". ".$a_Pedidos [$pedidoID]["CIUDAD"].", ".$a_Pedidos [$pedidoID]["PROVINCIA"].". C.P.: ".$a_Pedidos [$pedidoID]["C.P."];
            $cupon[$pedidoID]               = $sellers[$orderline_index] != "Soko Supermercado" ? "" : $pedido[0][29];
            $correo_electronico[$pedidoID]  = $pedido[0][7];
            printear('$pedido');
            printear($pedido);
            $medio_de_pago[$pedidoID]       = $pedido[0][30];

            $telefono_pedido[$pedidoID]     = $pedido[0][8];
            $fecha_pedido[$pedidoID]        = substr($pedido[0][3], 0, -1);


            $condicion_venta_pedido_actual = isset($condicion_venta[$medio_de_pago[$pedidoID]]) ? $condicion_venta[$medio_de_pago[$pedidoID]] : 1 ;


            //printear("--------------------- NRO PEDIDO ----------------------------");
            //printear($pedidoID);
            //printear("comentario[$pedidoID]");
            //printear($comentario[$pedidoID]);



            //// ///// /////// RUTA //// /// /// /
            $a_RUTA [$pedidoID][]	    =	$contador_pedidos	;
            $a_RUTA [$pedidoID][]	=	$a_Pedidos [$pedidoID]["FECHA"]	;
            $a_RUTA [$pedidoID][]	=	$a_Pedidos [$pedidoID]["ORDEN"]	;
            $a_RUTA [$pedidoID][]	=	$a_Pedidos [$pedidoID]["NOMBRE"]	;
            $a_RUTA [$pedidoID][]	=	$a_Pedidos [$pedidoID]["APELLIDO"]	;
            $a_RUTA [$pedidoID][]	=	$a_Pedidos [$pedidoID]["TELÉFONO"]	;
            $a_RUTA [$pedidoID][]	=	""	;
            $a_RUTA [$pedidoID][]	=	""	;
            $a_RUTA [$pedidoID][]	=	$a_Pedidos [$pedidoID]["CIUDAD"]	;
            $a_RUTA [$pedidoID][]	=	$a_Pedidos [$pedidoID]["CALLE"]	;
            $a_RUTA [$pedidoID][]	=	$a_Pedidos [$pedidoID]["ALTURA"]	;
            $a_RUTA [$pedidoID][]	=	$a_Pedidos [$pedidoID]["ACLARACIONES"]	;
            $a_RUTA [$pedidoID][]	=	$a_Pedidos [$pedidoID]["STATUS"]	;
            $a_RUTA [$pedidoID][]	=	""	;
            $a_RUTA [$pedidoID][]	=	""	;
            $a_RUTA [$pedidoID][]	=	""	;
            $a_RUTA [$pedidoID][]	=	""	;
            $a_RUTA [$pedidoID][]	=	""	;


            /*
            foreach ($a_RUTA as $id_pedido => $pedido) {

                foreach ($pedido as $id_pedido => $columna) {

                }
            }
            */


            foreach ($pedido as $column_index => $data){

                $o_Data_out[$line_index]["RAZONSOCIAL"]=$nombres[$pedidoID];
                $o_Data_out[$line_index]["NRODOCUMENTO"]=$data[6];
                $o_Data_out[$line_index]["TIPODOCUMENTO"]='DNI';
                $o_Data_out[$line_index]["DIRECCIONFISCAL"]= "NO INFORMADA";//$data[14]." ".$data[15]." - ".$data[16];
                $o_Data_out[$line_index]["PROVINCIA"]="Buenos Aires";//$data[9];
                $o_Data_out[$line_index]["LOCALIDAD"]="Buenos Aires";//$data[10];
                $o_Data_out[$line_index]["CODIGOPOSTAL"]=$data[19];
                $o_Data_out[$line_index]["PERCIBEIVA"]= "";
                $o_Data_out[$line_index]["PERCIBEIIBB"]= "";
                $o_Data_out[$line_index]["TRATAMIENTOIMPOSITIVO"]="CONSUMIDOR FINAL";
                $o_Data_out[$line_index]["ENVIARCOMPROBANTE"]="S";
                $o_Data_out[$line_index]["MAILFACTURACION"]="logistica@sokogo.com";
                $o_Data_out[$line_index]["CONTACTO"]= "";
                $o_Data_out[$line_index]["TELEFONO"]=$data[8];
                $o_Data_out[$line_index]["CONVENIOMULTILATERAL"]="N";
                $o_Data_out[$line_index]["ENVIARBOTONPAGO"]="N";
                $o_Data_out[$line_index]["NUMEROCOMPROBANTE"]=$comprobante[$pedidoID];
                $o_Data_out[$line_index]["FECHAHORA"]=date("d/m/Y"); ;
                $o_Data_out[$line_index]["TIPOCOMPROBANTE"]="FB";
                $o_Data_out[$line_index]["OBSERVACIONES"]= $comentario[$pedidoID];
                $o_Data_out[$line_index]["DESCUENTO"]="";
                $o_Data_out[$line_index]["PERCEPCIONIVA"]="";
                $o_Data_out[$line_index]["PERCEPCIONIIBB"]="";
                $o_Data_out[$line_index]["ORDENCOMPRA"]=$data[1];
                $o_Data_out[$line_index]["REMITO"]="";
                $o_Data_out[$line_index]["IMPORTEIMPUESTOSINTERNOS"]="";
                $o_Data_out[$line_index]["IMPORTEPERCEPCIONESMUNIC"]="";
                $o_Data_out[$line_index]["MONEDA"]=2;
                $o_Data_out[$line_index]["TIPODECAMBIO"]=1;
                $o_Data_out[$line_index]["CONDICIONVENTA"]= $condicion_venta_pedido_actual;
                $o_Data_out[$line_index]["PRODUCTOSERVICIO"]=3;
                $o_Data_out[$line_index]["FECHAVENCIMIENTO"]=date("d/m/Y");
                $o_Data_out[$line_index]["FECHAINICIOABONO"]=date("d/m/Y");
                $o_Data_out[$line_index]["FECHAFINABONO"]=date("d/m/Y");
                $o_Data_out[$line_index]["CANTIDAD"]=$data[33];
                $o_Data_out[$line_index]["DETALLE"]=$data[37];
                $o_Data_out[$line_index]["CODIGO"]= (string) $data[36];
                $o_Data_out[$line_index]["BONIFICACION"]="";
                $o_Data_out[$line_index]["IVA"]= isset($iva_105[$data[36]]) ? $iva_105[$data[36]] : 21;
                $o_Data_out[$line_index]["PRECIOUNITARIO"]= round( (float) $data[38] / (1+($o_Data_out[$line_index]["IVA"]/100)) ,2	) ;
                $o_Data_out[$line_index]["CBU"]="";
                $o_Data_out[$line_index]["ESANULACION"]="";

                $costo=  (float)$data[38] * $data[33];

                if( $o_Data_out[$line_index]["IVA"] == 10.5){
                    $costo_acumulado_105[$pedidoID] += $costo;
                }
                else{
                    $costo_acumulado_21[$pedidoID] += $costo;
                }

                $costo_sinIVA =   $o_Data_out[$line_index]["PRECIOUNITARIO"] * $o_Data_out[$line_index]["CANTIDAD"];

                if( $o_Data_out[$line_index]["IVA"] == 10.5){
                    $costo_acumulado_105_sinIVA[$pedidoID] += $costo_sinIVA;
                }
                else{
                    $costo_acumulado_21_sinIVA[$pedidoID] += $costo_sinIVA;
                }


                //printear("costo acumulado");
                //printear( $costo_acumulado[$pedidoID]);

                $items_pedido[$pedidoID][] = array(
                    "CODIGO"            => $o_Data_out[$line_index]["CODIGO"],
                    "DETALLE"           => $o_Data_out[$line_index]["DETALLE"],
                    "PRECIOUNITARIO"    => $data[38],
                    "CANTIDAD"          => $o_Data_out[$line_index]["CANTIDAD"],
                    "IVA"               => $o_Data_out[$line_index]["IVA"]
                );

                $line_index++;

            }

            $o_Data_out[$line_index]["RAZONSOCIAL"]=$nombres[$pedidoID];
            $o_Data_out[$line_index]["NRODOCUMENTO"]=$data[6];
            $o_Data_out[$line_index]["TIPODOCUMENTO"]='DNI';
            $o_Data_out[$line_index]["DIRECCIONFISCAL"]= "NO INFORMADA";//$data[14]." ".$data[15]." - ".$data[16];
            $o_Data_out[$line_index]["PROVINCIA"]="Buenos Aires";//$data[9];
            $o_Data_out[$line_index]["LOCALIDAD"]="Buenos Aires";//$data[10];
            $o_Data_out[$line_index]["CODIGOPOSTAL"]=$data[19];
            $o_Data_out[$line_index]["PERCIBEIVA"]= "";
            $o_Data_out[$line_index]["PERCIBEIIBB"]= "";
            $o_Data_out[$line_index]["TRATAMIENTOIMPOSITIVO"]="CONSUMIDOR FINAL";
            $o_Data_out[$line_index]["ENVIARCOMPROBANTE"]="S";
            $o_Data_out[$line_index]["MAILFACTURACION"]="logistica@sokogo.com";
            $o_Data_out[$line_index]["CONTACTO"]= "";
            $o_Data_out[$line_index]["TELEFONO"]=$data[8];
            $o_Data_out[$line_index]["CONVENIOMULTILATERAL"]="N";
            $o_Data_out[$line_index]["ENVIARBOTONPAGO"]="N";
            $o_Data_out[$line_index]["NUMEROCOMPROBANTE"]=$comprobante[$pedidoID];
            $o_Data_out[$line_index]["FECHAHORA"]=date("d/m/Y"); ;
            $o_Data_out[$line_index]["TIPOCOMPROBANTE"]="FB";
            $o_Data_out[$line_index]["OBSERVACIONES"]= $comentario[$pedidoID];
            $o_Data_out[$line_index]["DESCUENTO"]="";
            $o_Data_out[$line_index]["PERCEPCIONIVA"]="";
            $o_Data_out[$line_index]["PERCEPCIONIIBB"]="";
            $o_Data_out[$line_index]["ORDENCOMPRA"]=$data[1];
            $o_Data_out[$line_index]["REMITO"]="";
            $o_Data_out[$line_index]["IMPORTEIMPUESTOSINTERNOS"]="";
            $o_Data_out[$line_index]["IMPORTEPERCEPCIONESMUNIC"]="";
            $o_Data_out[$line_index]["MONEDA"]=2;
            $o_Data_out[$line_index]["TIPODECAMBIO"]=1;
            $o_Data_out[$line_index]["CONDICIONVENTA"]=$condicion_venta_pedido_actual;
            $o_Data_out[$line_index]["PRODUCTOSERVICIO"]=3;
            $o_Data_out[$line_index]["FECHAVENCIMIENTO"]=date("d/m/Y");
            $o_Data_out[$line_index]["FECHAINICIOABONO"]=date("d/m/Y");
            $o_Data_out[$line_index]["FECHAFINABONO"]=date("d/m/Y");
            $o_Data_out[$line_index]["CANTIDAD"]=1;
            $o_Data_out[$line_index]["DETALLE"]= $data[20];
            $o_Data_out[$line_index]["CODIGO"]= (string) "1000000000001";
            $o_Data_out[$line_index]["BONIFICACION"]="";
            $o_Data_out[$line_index]["IVA"]=21;

            if( isset($descuentos_ignorar[$cupon_pedido[$pedidoID]])){
                $o_Envios[$pedidoID] += 319.00;
            }

            $o_Data_out[$line_index]["PRECIOUNITARIO"]= round($o_Envios[$pedidoID]/ (1.21),2);
            $o_Data_out[$line_index]["CBU"]="";
            $o_Data_out[$line_index]["ESANULACION"]="";

            $costo=  $o_Envios[$pedidoID];
            $costo_acumulado_21[$pedidoID] += $costo;
            $costo_acumulado_21_sinIVA[$pedidoID] += $o_Data_out[$line_index]["PRECIOUNITARIO"];


            if($o_Data_out[$line_index]["PRECIOUNITARIO"] == 0){
                unset($o_Data_out[$line_index]);
            }
            else{
                /* INDICE NUEVA LINEA */
                $line_index++;
            }

            $o_Data_out[$line_index]["RAZONSOCIAL"]=$nombres[$pedidoID];
            $o_Data_out[$line_index]["NRODOCUMENTO"]=$data[6];
            $o_Data_out[$line_index]["TIPODOCUMENTO"]='DNI';
            $o_Data_out[$line_index]["DIRECCIONFISCAL"]= "NO INFORMADA";//$data[14]." ".$data[15]." - ".$data[16];
            $o_Data_out[$line_index]["PROVINCIA"]="Buenos Aires";//$data[9];
            $o_Data_out[$line_index]["LOCALIDAD"]="Buenos Aires";//$data[10];
            $o_Data_out[$line_index]["CODIGOPOSTAL"]=$data[19];
            $o_Data_out[$line_index]["PERCIBEIVA"]= "";
            $o_Data_out[$line_index]["PERCIBEIIBB"]= "";
            $o_Data_out[$line_index]["TRATAMIENTOIMPOSITIVO"]="CONSUMIDOR FINAL";
            $o_Data_out[$line_index]["ENVIARCOMPROBANTE"]="S";
            $o_Data_out[$line_index]["MAILFACTURACION"]="logistica@sokogo.com";
            $o_Data_out[$line_index]["CONTACTO"]= "";
            $o_Data_out[$line_index]["TELEFONO"]=$data[8];
            $o_Data_out[$line_index]["CONVENIOMULTILATERAL"]="N";
            $o_Data_out[$line_index]["ENVIARBOTONPAGO"]="N";
            $o_Data_out[$line_index]["NUMEROCOMPROBANTE"]=$comprobante[$pedidoID];
            $o_Data_out[$line_index]["FECHAHORA"]=date("d/m/Y"); ;
            $o_Data_out[$line_index]["TIPOCOMPROBANTE"]="FB";
            $o_Data_out[$line_index]["OBSERVACIONES"]= $comentario[$pedidoID];
            $o_Data_out[$line_index]["DESCUENTO"]="";
            $o_Data_out[$line_index]["PERCEPCIONIVA"]="";
            $o_Data_out[$line_index]["PERCEPCIONIIBB"]="";
            $o_Data_out[$line_index]["ORDENCOMPRA"]=$data[1];
            $o_Data_out[$line_index]["REMITO"]="";
            $o_Data_out[$line_index]["IMPORTEIMPUESTOSINTERNOS"]="";
            $o_Data_out[$line_index]["IMPORTEPERCEPCIONESMUNIC"]="";
            $o_Data_out[$line_index]["MONEDA"]=2;
            $o_Data_out[$line_index]["TIPODECAMBIO"]=1;
            $o_Data_out[$line_index]["CONDICIONVENTA"]=$condicion_venta_pedido_actual;
            $o_Data_out[$line_index]["PRODUCTOSERVICIO"]=3;
            $o_Data_out[$line_index]["FECHAVENCIMIENTO"]=date("d/m/Y");
            $o_Data_out[$line_index]["FECHAINICIOABONO"]=date("d/m/Y");
            $o_Data_out[$line_index]["FECHAFINABONO"]=date("d/m/Y");
            $o_Data_out[$line_index]["CANTIDAD"]=1;
            $o_Data_out[$line_index]["DETALLE"]="Descuentos";
            $o_Data_out[$line_index]["CODIGO"]= (string)"1000000000003";
            $o_Data_out[$line_index]["BONIFICACION"]="";
            $o_Data_out[$line_index]["IVA"]=21;

            $descuentos[$pedidoID] = $data[49];

            //printear($sellers[$pedidoID]);


            if( isset($descuentos_ignorar[$cupon_pedido[$pedidoID]]) || $sellers[$pedidoID] != "Soko Supermercado" ){
                $descuentos[$pedidoID] = 0.00;
            }

            $o_Data_out[$line_index]["PRECIOUNITARIO"]=round(((float) $descuentos[$pedidoID])/ (1.21),2 );
            $o_Data_out[$line_index]["CBU"]="";
            $o_Data_out[$line_index]["ESANULACION"]="";

            $costo=  $descuentos[$pedidoID];
            $costo_acumulado_21[$pedidoID] += $costo;
            $costo_acumulado_21_sinIVA[$pedidoID] += $o_Data_out[$line_index]["PRECIOUNITARIO"];


            if($o_Data_out[$line_index]["PRECIOUNITARIO"] == 0){
                unset($o_Data_out[$line_index]);
            }
            else{
                /* INDICE NUEVA LINEA */
                $line_index++;
            }

            //  //  //  //  //  //  //  //  //  //  //  // GIFTCARD - VALE  //  //  //  //  //  //  //  //

            $medios_de_pago = (string) $medio_de_pago[$pedidoID];

            printear('$medios_de_pago');
            printear($medios_de_pago);


            $divisor_medios_de_pago   = ',';
            $flag_multiples_medios_de_pago = strpos($medios_de_pago, $divisor_medios_de_pago);

            $vales[$pedidoID] = 0.00;

            if($flag_multiples_medios_de_pago){

                $array_medios_de_pago = explode(", ", $medios_de_pago);
                $array_valores_de_pago = explode(", ", $data[32]);

                $data[32] = 0.00;

                foreach ($array_medios_de_pago as $index => $item_medio_de_pago) {
                    $nuevo_array [$item_medio_de_pago] = $array_valores_de_pago[$index];

                    $data[32]   = $data[32] + $nuevo_array [$item_medio_de_pago];

                }

                if(isset($nuevo_array ['Vale'])){
                    $vales[$pedidoID] = - ((float)($nuevo_array ['Vale']));//payment value
                }


                printear('$array_medios_de_pago');
                printear($array_medios_de_pago);
                printear('$array_valores_de_pago');
                printear($array_valores_de_pago);
                printear('$nuevo_array');
                printear($nuevo_array);
                printear('  $data[32]');
                printear(  $data[32]);


            }

            if(isset($vales[$pedidoID])){

                $o_Data_out[$line_index]["RAZONSOCIAL"]=$nombres[$pedidoID];
                $o_Data_out[$line_index]["NRODOCUMENTO"]=$data[6];
                $o_Data_out[$line_index]["TIPODOCUMENTO"]='DNI';
                $o_Data_out[$line_index]["DIRECCIONFISCAL"]= "NO INFORMADA";//$data[14]." ".$data[15]." - ".$data[16];
                $o_Data_out[$line_index]["PROVINCIA"]="Buenos Aires";//$data[9];
                $o_Data_out[$line_index]["LOCALIDAD"]="Buenos Aires";//$data[10];
                $o_Data_out[$line_index]["CODIGOPOSTAL"]=$data[19];
                $o_Data_out[$line_index]["PERCIBEIVA"]= "";
                $o_Data_out[$line_index]["PERCIBEIIBB"]= "";
                $o_Data_out[$line_index]["TRATAMIENTOIMPOSITIVO"]="CONSUMIDOR FINAL";
                $o_Data_out[$line_index]["ENVIARCOMPROBANTE"]="S";
                $o_Data_out[$line_index]["MAILFACTURACION"]="logistica@sokogo.com";
                $o_Data_out[$line_index]["CONTACTO"]= "";
                $o_Data_out[$line_index]["TELEFONO"]=$data[8];
                $o_Data_out[$line_index]["CONVENIOMULTILATERAL"]="N";
                $o_Data_out[$line_index]["ENVIARBOTONPAGO"]="N";
                $o_Data_out[$line_index]["NUMEROCOMPROBANTE"]=$comprobante[$pedidoID];
                $o_Data_out[$line_index]["FECHAHORA"]=date("d/m/Y"); ;
                $o_Data_out[$line_index]["TIPOCOMPROBANTE"]="FB";
                $o_Data_out[$line_index]["OBSERVACIONES"]= $comentario[$pedidoID];
                $o_Data_out[$line_index]["DESCUENTO"]="";
                $o_Data_out[$line_index]["PERCEPCIONIVA"]="";
                $o_Data_out[$line_index]["PERCEPCIONIIBB"]="";
                $o_Data_out[$line_index]["ORDENCOMPRA"]=$data[1];
                $o_Data_out[$line_index]["REMITO"]="";
                $o_Data_out[$line_index]["IMPORTEIMPUESTOSINTERNOS"]="";
                $o_Data_out[$line_index]["IMPORTEPERCEPCIONESMUNIC"]="";
                $o_Data_out[$line_index]["MONEDA"]=2;
                $o_Data_out[$line_index]["TIPODECAMBIO"]=1;
                $o_Data_out[$line_index]["CONDICIONVENTA"]=$condicion_venta_pedido_actual;
                $o_Data_out[$line_index]["PRODUCTOSERVICIO"]=3;
                $o_Data_out[$line_index]["FECHAVENCIMIENTO"]=date("d/m/Y");
                $o_Data_out[$line_index]["FECHAINICIOABONO"]=date("d/m/Y");
                $o_Data_out[$line_index]["FECHAFINABONO"]=date("d/m/Y");
                $o_Data_out[$line_index]["CANTIDAD"]=1;
                $o_Data_out[$line_index]["DETALLE"]= "Descuento Giftcard";
                $o_Data_out[$line_index]["CODIGO"]= (string) "1000000000009";
                $o_Data_out[$line_index]["BONIFICACION"]="";
                $o_Data_out[$line_index]["IVA"]=21;


                /* INTERESES 21 */
                $o_Data_out[$line_index]["PRECIOUNITARIO"]      =  round(  $vales[$pedidoID] / (1.21),2 );
                $o_Data_out[$line_index]["CBU"]                 =   "";
                $o_Data_out[$line_index]["ESANULACION"]         =   "";


                $costo=  $vales[$pedidoID];
                $costo_acumulado_21[$pedidoID] += $costo;
                $costo_acumulado_21_sinIVA[$pedidoID] += $o_Data_out[$line_index]["PRECIOUNITARIO"];


                if($o_Data_out[$line_index]["PRECIOUNITARIO"] == 0){
                    unset($o_Data_out[$line_index]);
                }
                else{
                    /* INDICE NUEVA LINEA */
                    $line_index++;
                }

            }
            else{
                $vales[$pedidoID]                = 0.00;

            }
            //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //


            /* INTERESES 21% */
            $o_Data_out[$line_index]["RAZONSOCIAL"]=$nombres[$pedidoID];
            $o_Data_out[$line_index]["NRODOCUMENTO"]=$data[6];
            $o_Data_out[$line_index]["TIPODOCUMENTO"]='DNI';
            $o_Data_out[$line_index]["DIRECCIONFISCAL"]= "NO INFORMADA";//$data[14]." ".$data[15]." - ".$data[16];
            $o_Data_out[$line_index]["PROVINCIA"]="Buenos Aires";//$data[9];
            $o_Data_out[$line_index]["LOCALIDAD"]="Buenos Aires";//$data[10];
            $o_Data_out[$line_index]["CODIGOPOSTAL"]=$data[19];
            $o_Data_out[$line_index]["PERCIBEIVA"]= "";
            $o_Data_out[$line_index]["PERCIBEIIBB"]= "";
            $o_Data_out[$line_index]["TRATAMIENTOIMPOSITIVO"]="CONSUMIDOR FINAL";
            $o_Data_out[$line_index]["ENVIARCOMPROBANTE"]="S";
            $o_Data_out[$line_index]["MAILFACTURACION"]="logistica@sokogo.com";
            $o_Data_out[$line_index]["CONTACTO"]= "";
            $o_Data_out[$line_index]["TELEFONO"]=$data[8];
            $o_Data_out[$line_index]["CONVENIOMULTILATERAL"]="N";
            $o_Data_out[$line_index]["ENVIARBOTONPAGO"]="N";
            $o_Data_out[$line_index]["NUMEROCOMPROBANTE"]=$comprobante[$pedidoID];
            $o_Data_out[$line_index]["FECHAHORA"]=date("d/m/Y"); ;
            $o_Data_out[$line_index]["TIPOCOMPROBANTE"]="FB";
            $o_Data_out[$line_index]["OBSERVACIONES"]= $comentario[$pedidoID];
            $o_Data_out[$line_index]["DESCUENTO"]="";
            $o_Data_out[$line_index]["PERCEPCIONIVA"]="";
            $o_Data_out[$line_index]["PERCEPCIONIIBB"]="";
            $o_Data_out[$line_index]["ORDENCOMPRA"]=$data[1];
            $o_Data_out[$line_index]["REMITO"]="";
            $o_Data_out[$line_index]["IMPORTEIMPUESTOSINTERNOS"]="";
            $o_Data_out[$line_index]["IMPORTEPERCEPCIONESMUNIC"]="";
            $o_Data_out[$line_index]["MONEDA"]=2;
            $o_Data_out[$line_index]["TIPODECAMBIO"]=1;
            $o_Data_out[$line_index]["CONDICIONVENTA"]=$condicion_venta_pedido_actual;
            $o_Data_out[$line_index]["PRODUCTOSERVICIO"]=3;
            $o_Data_out[$line_index]["FECHAVENCIMIENTO"]=date("d/m/Y");
            $o_Data_out[$line_index]["FECHAINICIOABONO"]=date("d/m/Y");
            $o_Data_out[$line_index]["FECHAFINABONO"]=date("d/m/Y");
            $o_Data_out[$line_index]["CANTIDAD"]=1;
            $o_Data_out[$line_index]["DETALLE"]= "Interés por ".$data[30]." (21%)";
            $o_Data_out[$line_index]["CODIGO"]= (string) "1000000000002";
            $o_Data_out[$line_index]["BONIFICACION"]="";
            $o_Data_out[$line_index]["IVA"]=21;






            /* VARIABLES GENERALES */
            $total_compra               = $data[48] == 0 ? 1 : $data[48];
            $interes_total              = $data[32] - $data[48];

            /* VARIABLES INTERESES 21 */
            $tasa_interes_parcial_21                        =   $costo_acumulado_21[$pedidoID]/$total_compra;
            $intereses_21[$pedidoID]                        =   $interes_total * $tasa_interes_parcial_21 ;
            $costo_acumulado_21[$pedidoID]                  +=  $intereses_21[$pedidoID];

            /* INTERESES 21 */
            $o_Data_out[$line_index]["PRECIOUNITARIO"]      =  round( $intereses_21[$pedidoID] / (1.21),2 );
            $o_Data_out[$line_index]["CBU"]                 =   "";
            $o_Data_out[$line_index]["ESANULACION"]         =   "";

            $costo_acumulado_21_sinIVA[$pedidoID] += $o_Data_out[$line_index]["PRECIOUNITARIO"];


            if($o_Data_out[$line_index]["PRECIOUNITARIO"] == 0){
                unset($o_Data_out[$line_index]);
            }
            else{
                /* INDICE NUEVA LINEA */
                $line_index++;
            }



            /* INTERESES 10.5% */
            $o_Data_out[$line_index]["RAZONSOCIAL"]=$nombres[$pedidoID];
            $o_Data_out[$line_index]["NRODOCUMENTO"]=$data[6];
            $o_Data_out[$line_index]["TIPODOCUMENTO"]='DNI';
            $o_Data_out[$line_index]["DIRECCIONFISCAL"]= "NO INFORMADA";//$data[14]." ".$data[15]." - ".$data[16];
            $o_Data_out[$line_index]["PROVINCIA"]="Buenos Aires";//$data[9];
            $o_Data_out[$line_index]["LOCALIDAD"]="Buenos Aires";//$data[10];
            $o_Data_out[$line_index]["CODIGOPOSTAL"]=$data[19];
            $o_Data_out[$line_index]["PERCIBEIVA"]= "";
            $o_Data_out[$line_index]["PERCIBEIIBB"]= "";
            $o_Data_out[$line_index]["TRATAMIENTOIMPOSITIVO"]="CONSUMIDOR FINAL";
            $o_Data_out[$line_index]["ENVIARCOMPROBANTE"]="S";
            $o_Data_out[$line_index]["MAILFACTURACION"]="logistica@sokogo.com";
            $o_Data_out[$line_index]["CONTACTO"]= "";
            $o_Data_out[$line_index]["TELEFONO"]=$data[8];
            $o_Data_out[$line_index]["CONVENIOMULTILATERAL"]="N";
            $o_Data_out[$line_index]["ENVIARBOTONPAGO"]="N";
            $o_Data_out[$line_index]["NUMEROCOMPROBANTE"]=$comprobante[$pedidoID];
            $o_Data_out[$line_index]["FECHAHORA"]=date("d/m/Y"); ;
            $o_Data_out[$line_index]["TIPOCOMPROBANTE"]="FB";
            $o_Data_out[$line_index]["OBSERVACIONES"]= $comentario[$pedidoID];
            $o_Data_out[$line_index]["DESCUENTO"]="";
            $o_Data_out[$line_index]["PERCEPCIONIVA"]="";
            $o_Data_out[$line_index]["PERCEPCIONIIBB"]="";
            $o_Data_out[$line_index]["ORDENCOMPRA"]=$data[1];
            $o_Data_out[$line_index]["REMITO"]="";
            $o_Data_out[$line_index]["IMPORTEIMPUESTOSINTERNOS"]="";
            $o_Data_out[$line_index]["IMPORTEPERCEPCIONESMUNIC"]="";
            $o_Data_out[$line_index]["MONEDA"]=2;
            $o_Data_out[$line_index]["TIPODECAMBIO"]=1;
            $o_Data_out[$line_index]["CONDICIONVENTA"]=$condicion_venta_pedido_actual;
            $o_Data_out[$line_index]["PRODUCTOSERVICIO"]=3;
            $o_Data_out[$line_index]["FECHAVENCIMIENTO"]=date("d/m/Y");
            $o_Data_out[$line_index]["FECHAINICIOABONO"]=date("d/m/Y");
            $o_Data_out[$line_index]["FECHAFINABONO"]=date("d/m/Y");
            $o_Data_out[$line_index]["CANTIDAD"]=1;
            $o_Data_out[$line_index]["DETALLE"]= "Interés por ".$data[30]." (10.5%)";
            $o_Data_out[$line_index]["CODIGO"]= (string) "1000000000002";
            $o_Data_out[$line_index]["BONIFICACION"]="";
            $o_Data_out[$line_index]["IVA"]=10.5;


            /* VARIABLES GENERALES */
            $total_compra               = $data[48] == 0 ? 1 : $data[48];
            $interes_total              = $data[32] - $data[48];

            /* VARIABLES INTERESES 10.5 */
            $tasa_interes_parcial_105                        =   $costo_acumulado_105[$pedidoID]/$total_compra;
            $intereses_105[$pedidoID]                        =   $interes_total * $tasa_interes_parcial_105 ;
            $costo_acumulado_105[$pedidoID]                  +=  $intereses_105[$pedidoID];

            /* INTERESES 10.5 */
            $o_Data_out[$line_index]["PRECIOUNITARIO"]      =  round( $intereses_105[$pedidoID] / (1.105),2 );
            $o_Data_out[$line_index]["CBU"]                 =   "";
            $o_Data_out[$line_index]["ESANULACION"]         =   "";

            $costo_acumulado_105_sinIVA[$pedidoID] += $o_Data_out[$line_index]["PRECIOUNITARIO"];


            if($o_Data_out[$line_index]["PRECIOUNITARIO"] == 0){
                unset($o_Data_out[$line_index]);
            }
            else{
                /* INDICE NUEVA LINEA */
                $line_index++;
            }




            /* AJUSTES POR REDONDEO */
            $o_Data_out[$line_index]["RAZONSOCIAL"]=$nombres[$pedidoID];
            $o_Data_out[$line_index]["NRODOCUMENTO"]=$data[6];
            $o_Data_out[$line_index]["TIPODOCUMENTO"]='DNI';
            $o_Data_out[$line_index]["DIRECCIONFISCAL"]= "NO INFORMADA";//$data[14]." ".$data[15]." - ".$data[16];
            $o_Data_out[$line_index]["PROVINCIA"]="Buenos Aires";//$data[9];
            $o_Data_out[$line_index]["LOCALIDAD"]="Buenos Aires";//$data[10];
            $o_Data_out[$line_index]["CODIGOPOSTAL"]=$data[19];
            $o_Data_out[$line_index]["PERCIBEIVA"]= "";
            $o_Data_out[$line_index]["PERCIBEIIBB"]= "";
            $o_Data_out[$line_index]["TRATAMIENTOIMPOSITIVO"]="CONSUMIDOR FINAL";
            $o_Data_out[$line_index]["ENVIARCOMPROBANTE"]="S";
            $o_Data_out[$line_index]["MAILFACTURACION"]="logistica@sokogo.com";
            $o_Data_out[$line_index]["CONTACTO"]= "";
            $o_Data_out[$line_index]["TELEFONO"]=$data[8];
            $o_Data_out[$line_index]["CONVENIOMULTILATERAL"]="N";
            $o_Data_out[$line_index]["ENVIARBOTONPAGO"]="N";
            $o_Data_out[$line_index]["NUMEROCOMPROBANTE"]=$comprobante[$pedidoID];
            $o_Data_out[$line_index]["FECHAHORA"]=date("d/m/Y"); ;
            $o_Data_out[$line_index]["TIPOCOMPROBANTE"]="FB";
            $o_Data_out[$line_index]["OBSERVACIONES"]= $comentario[$pedidoID];
            $o_Data_out[$line_index]["DESCUENTO"]="";
            $o_Data_out[$line_index]["PERCEPCIONIVA"]="";
            $o_Data_out[$line_index]["PERCEPCIONIIBB"]="";
            $o_Data_out[$line_index]["ORDENCOMPRA"]=$data[1];
            $o_Data_out[$line_index]["REMITO"]="";
            $o_Data_out[$line_index]["IMPORTEIMPUESTOSINTERNOS"]="";
            $o_Data_out[$line_index]["IMPORTEPERCEPCIONESMUNIC"]="";
            $o_Data_out[$line_index]["MONEDA"]=2;
            $o_Data_out[$line_index]["TIPODECAMBIO"]=1;
            $o_Data_out[$line_index]["CONDICIONVENTA"]=$condicion_venta_pedido_actual;
            $o_Data_out[$line_index]["PRODUCTOSERVICIO"]=3;
            $o_Data_out[$line_index]["FECHAVENCIMIENTO"]=date("d/m/Y");
            $o_Data_out[$line_index]["FECHAINICIOABONO"]=date("d/m/Y");
            $o_Data_out[$line_index]["FECHAFINABONO"]=date("d/m/Y");
            $o_Data_out[$line_index]["CANTIDAD"]=1;
            $o_Data_out[$line_index]["DETALLE"]= "Ajuste por redondeo";
            $o_Data_out[$line_index]["CODIGO"]=(string)"1000000000004";
            $o_Data_out[$line_index]["BONIFICACION"]="";
            $o_Data_out[$line_index]["IVA"]=21;


            /* VARIABLES GENERALES */

            $total_acumulado_IVA21_control = round( $costo_acumulado_21_sinIVA[$pedidoID] * 1.21,2);
            $total_acumulado_IVA105_control = round( $costo_acumulado_105_sinIVA[$pedidoID] * 1.105,2);
            $total_acumulado_IVA_control = $total_acumulado_IVA21_control + $total_acumulado_IVA105_control;

            $total_acumulado_IVA21 = round( $costo_acumulado_21[$pedidoID],2);
            $total_acumulado_IVA105 = round( $costo_acumulado_105[$pedidoID],2);
            $total_acumulado_IVA = $total_acumulado_IVA21 + $total_acumulado_IVA105;

            $ajuste_por_redondeo = $total_acumulado_IVA-$total_acumulado_IVA_control;


            /*
            printear("total_acumulado_IVA_control");
            printear($total_acumulado_IVA_control);

            printear("total_acumulado_IVA");
            printear($total_acumulado_IVA);

            printear("ajuste_por_redondeo");
            printear($ajuste_por_redondeo);
            */

            $o_Data_out[$line_index]["PRECIOUNITARIO"]      = round( (float) $ajuste_por_redondeo / (1.21),2 );
            $o_Data_out[$line_index]["CBU"]                 =   "";
            $o_Data_out[$line_index]["ESANULACION"]         =   "";

            $costo_acumulado_21_sinIVA[$pedidoID] += $o_Data_out[$line_index]["PRECIOUNITARIO"];
            //$costo_acumulado_21[$pedidoID]                  +=  $ajuste_por_redondeo;

            if($ajuste_por_redondeo == 0.00){
                unset($o_Data_out[$line_index]);
            }
            else{
                /* INDICE NUEVA LINEA */
                $line_index++;
            }

            $a_Pedidos [$pedidoID]["MONTO"]             = round($costo_acumulado_21[$pedidoID]+$costo_acumulado_105[$pedidoID],2);
            $a_RUTA [$pedidoID][]	=	$a_Pedidos[$pedidoID]["MONTO"]	;

            /* INDICE NUEVA FACTURA */
            $T_nro_comprobante_inicio++;
            $contador_pedidos++;


            if(!strpos($pedidoID, "Seller")){

            }
            else{
                $contador_pedidos_seller++;
            }


            if($a_Pedidos [$pedidoID]["STATUS"] == "Pago pendiente"){
                $contador_pedidos_pago_pendiente++;
            }

            if($a_Pedidos [$pedidoID]["STATUS"] == "Cancelado"){
                $contador_pedidos_cancelados++;
            }




        }

        $contador_pedidos_a_despachar = ($contador_pedidos - 1) - $contador_pedidos_seller - $contador_pedidos_pago_pendiente - $contador_pedidos_cancelados;

        $a_RUTA [][]= "";
        $a_RUTA [][]= "";

        $a_RUTA []= array("Total de Pedidos", $contador_pedidos - 1);
        $a_RUTA []= array("Pedidos Seller", $contador_pedidos_seller);
        $a_RUTA []= array("Pedidos Pendientes Pago", $contador_pedidos_pago_pendiente);
        $a_RUTA []= array("Pedidos Cancelados", $contador_pedidos_cancelados);
        $a_RUTA []= array("Pedidos a Despachar", $contador_pedidos_a_despachar);






        $string1 = "Cantidad de Pedidos: "                  . ($contador_pedidos - 1);
        $string1 .= "\n" ."Pedidos Seller: "             . $contador_pedidos_seller;
        $string1 .= "\n" ."Pedidos Pendientes Pago: "    . $contador_pedidos_pago_pendiente;
        $string1 .= "\n" ."Pedidos Cancelados: "         . $contador_pedidos_cancelados;
        $string1 .= "\n" ."Pedidos a Despachar: "        . $contador_pedidos_a_despachar. "\n";


        foreach ($o_Data as $pedidoID => $pedido){

            $string1 .=
                "\n - " .$pedidos[$pedidoID]. " (" .$sellers[$pedidoID].")"
            ;


        }
        $string1 .= "\n";

        foreach ($o_Data as $pedidoID => $pedido){

                $string1 .=
                "\n\n Pedido NRO: "                 .$pedidos[$pedidoID] . " - " .$sellers[$pedidoID]
                ."\n - Estado: "                    .$estado_pedido[$pedidoID]
                ."\n - Fecha y Hora: "              .$fecha_pedido[$pedidoID]
                ."\n - Nombre Completo: "           .$nombres[$pedidoID]
                ."\n - Medio Pago: "                .$medio_de_pago[$pedidoID]
                ."\n - Correo Electrónico: "        .$correo_electronico[$pedidoID]
                ."\n - Cupones: "                   .$cupon[$pedidoID]
                ."\n - Giftcard: $"                 .$vales[$pedidoID]
                ."\n - Comprobante NRO: "           .$comprobante[$pedidoID]
                ."\n - Seguimiento de Pago: "       .$codigo_autorizacion_pago[$pedidoID]
                ."\n - Observaciones: "             .$comentario[$pedidoID]
                ."\n - Envío: $"                    .$o_Envios[$pedidoID]
                ."\n - Descuentos: $"               .$descuentos[$pedidoID]
                ."\n - Cantidad Items: "            .count($items_pedido[$pedidoID])
                ."\n - Intereses 21%: $"            .round($intereses_21[$pedidoID], 2)
                ."\n - Intereses 10.5%: $"          .round($intereses_105[$pedidoID],2)
                ."\n - Total 21%: $"                .round($costo_acumulado_21[$pedidoID],2)
                ."\n - Total 10.5%: $"              .round($costo_acumulado_105[$pedidoID],2)
                ."\n - Total Facturado: $"          .round($costo_acumulado_21[$pedidoID]+$costo_acumulado_105[$pedidoID],2)
                ."\n - Dirección Envío: "           .$direccion_pedido[$pedidoID]
                ."\n - Nro. Teléfono: "             .$telefono_pedido[$pedidoID]
            ."\n\n";


        }



        printear($string1);









        $o_Email       = new Email_O();










        /* CSV */
        $_SESSION['filtro']['tipo']                 =   "Pedidos_Vtex"       ;
        $_SESSION['titulo']                         =   "Lista de pedidos sokogo.com"      ;
        $_SESSION['items']                          =   $items_pedido;
        $_SESSION['filtro']['Guardar_Descargar']      = 'Guardar';



        $_SESSION['filename']                       =   "Pedidos ".$T_dia_hasta." hasta ".$T_hora_hasta ." hs";
        array_unshift($a_Data,$a_Nombres_csv);
        $_SESSION['Report']['Data']                 =   $a_Data;



        include_once(APP_PATH . '/../csv_vtex.php');



        $adjuntos[] = array(
            "URL"       => GS_CLIENT_TEMP_FOLDER.$_SESSION['filename']. ".csv",
            "NOMBRE"    => $_SESSION['filename']. ".csv"
        );




        /* EXCEL */
        $_SESSION['filtro']['tipo']                 =   "Pedidos_Vtex"       ;
        $_SESSION['titulo']                         =   "Lista de pedidos sokogo.com"      ;
        $_SESSION['items']                          =   $items_pedido;
        $_SESSION['filtro']['Guardar_Descargar']      = 'Guardar';


        $_SESSION['filename']                       =   "(Facturante) Pedidos ".$T_dia_hasta." hasta ".$T_hora_hasta ." hs";
        array_unshift($o_Data_out,$a_Nombres);
        $_SESSION['Report']['Data']                 = $o_Data_out  ;


        include_once(APP_PATH . '/../excel_vtex.php');

        $adjuntos[] = array(
            "URL"       => GS_CLIENT_TEMP_FOLDER.$_SESSION['filename']. ".xls",
            "NOMBRE"    => $_SESSION['filename']. ".xls"
        );

        array_shift($o_Data_out);




        /* EXCEL RUTA */
        $encabezado_ruta = array("N","Fecha","Orden","Nombre","Apellido","Telefono","Hora","Zona","Localidad","Calle","Altura","Detalle","Estatus","Condicion","Bulto","Chofer","Ruta","Observacion","MONTO");

        $_SESSION['filtro']['tipo']                 =   "Rutas_Vtex"       ;
        $_SESSION['titulo']                         =   "Lista de rutas sokogo.com"      ;
        $_SESSION['items']                          =   $items_pedido;
        $_SESSION['filtro']['Guardar_Descargar']      = 'Guardar';


        $_SESSION['filename']                       =   "(Logística) Rutas ".$T_dia_hasta." hasta ".$T_hora_hasta ." hs";
        array_unshift($a_RUTA,$encabezado_ruta);
        $_SESSION['Report']['Data']                 = $a_RUTA  ;


        include_once(APP_PATH . '/../excel_vtex_ruta.php');

        $adjuntos[] = array(
            "URL"       => GS_CLIENT_TEMP_FOLDER.$_SESSION['filename']. ".xls",
            "NOMBRE"    => $_SESSION['filename']. ".xls"
        );




        //printear('$a_RUTA');
        //printear($a_RUTA);




        /* PDF  */
        $_SESSION['filtro']['tipo']                 =   "Pedidos_Vtex"       ;
        $_SESSION['titulo']                         =   "Detalle de pedido www.sokogo.com"      ;
        $_SESSION['items']                          =   $items_pedido;
        $_SESSION['filtro']['Guardar_Descargar']      = 'Guardar';


        foreach ($a_Pedidos as $pedidoID => $pedido) {

            unset($_SESSION['pedidos']);

            $_SESSION['pedidos'][$pedidoID]         =   $pedido;
            $_SESSION['filename']                   =   $pedidoID;


            include(APP_PATH . '/../pdf_vtex.php');

            $adjuntos[] = array(
                "URL"       => GS_CLIENT_TEMP_FOLDER.$_SESSION['filename']. ".pdf",
                "NOMBRE"    => $_SESSION['filename']. ".pdf"
            );
        }


        $o_Email->setDestinatario($T_email_destinatario);
        $o_Email->setDestinatarioBCC($T_email_cc);


        $o_Email->setEstado(1); //para enviar
        $o_Email->setFrom('Pedidos Soko');
        $o_Email->setFecha(date("Y-m-d H:i:s"),"Y-m-d H:i:s");
        $o_Email->setTipo(0);
        $o_Email->setSujeto("Pedidos ".$T_dia_hasta." hasta ".$T_hora_hasta ." hs");



        if($o_Email->enviar_vtex($adjuntos,str_replace("\n","<br>",$string1))){
            $o_Email->setEstado(2);
        }

        $o_Email->save('Off');

        printear("Pedidos enviados");

        $Persona_nro_factura->setRID($T_nro_comprobante_inicio);
        $Persona_nro_factura->setNombre($_hora);
        $Persona_nro_factura->setSegundoNombre($_dia);


        if($Persona_nro_factura->save()){
            printear("Nro de factura guardado");
        }
        else{
            //printear("Error al guardar nro de factura");
        }

        break;

    default:

        break;
}

