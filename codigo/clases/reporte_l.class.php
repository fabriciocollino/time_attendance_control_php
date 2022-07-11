<?php

/**
 * Usuario (List)
 * 
 */
class Reporte_L {


	public static function ejemplo($p_Usuario, $p_Clave) {




	}


    public static function get_filtro_intervalo($T_Intervalo=''){

        if ($T_Intervalo == '') {
            return false;
        }


        switch ($T_Intervalo) {
            case 'F_Hoy':
                $fecha_desde = date('Y-m-d H:i:s', strtotime('today 00:00:00'));
                $fecha_hasta = date('Y-m-d H:i:s', strtotime('today 23:59:59'));
                break;

            case 'F_Ayer':
                $fecha_desde = date('Y-m-d H:i:s', strtotime('yesterday 00:00:00'));
                $fecha_hasta = date('Y-m-d H:i:s', strtotime('yesterday 23:59:59'));
                break;

            case 'F_Semana':
                $fecha_desde = date('Y-m-d H:i:s', strtotime('monday this week 00:00:00'));
                $fecha_hasta = date('Y-m-d H:i:s', strtotime('sunday this week 23:59:59'));
                break;

            case 'F_Semana_Pasada':
                $fecha_desde = date('Y-m-d H:i:s', strtotime('monday last week 00:00:00'));
                $fecha_hasta = date('Y-m-d H:i:s', strtotime('sunday last week 23:59:59'));
                break;

            case 'F_Quincena':
                $primerDiaPrimerQuincena = strtotime('first day of this month 00:00:00');
                $ultimoDiaPrimerQuincena = strtotime('+14 days 23:59:59',$primerDiaPrimerQuincena);

                $primerDiaSegundaQuincena = strtotime('+15 days', $primerDiaPrimerQuincena);
                $ultimoDiaSegundaQuincena = strtotime('last day of this month 23:59:59');

                // PRIMERA QUINCENA
                if (time() < $ultimoDiaPrimerQuincena) {
                    $fecha_desde = date('Y-m-d H:i:s', $primerDiaPrimerQuincena);
                    $fecha_hasta = date('Y-m-d H:i:s', $ultimoDiaPrimerQuincena);
                }
                // SEGUNDA QUINCENA
                else {
                    $fecha_desde= date('Y-m-d H:i:s', $primerDiaSegundaQuincena);
                    $fecha_hasta = date('Y-m-d H:i:s', $ultimoDiaSegundaQuincena);
                }
                break;

            case 'F_Quincena_Pasada':

                // PRIMER QUINCENA DE ESTE MES
                if(date('d' > 15) ){
                    $primerDiaQuincena = strtotime('first day of this month 00:00:00');
                    $ultimoDiaQuincena = strtotime('+14 days 23:59:59',$primerDiaQuincena);
                }
                // SEGUNDA QUINCENA DEL MES PASADO
                else{
                    $primerDiaMes = strtotime('first day of last month 00:00:00');
                    $primerDiaQuincena = strtotime('+15 days', $primerDiaMes);
                    $ultimoDiaQuincena = strtotime('last day of last month 23:59:59');
                }

                $fecha_desde = date('Y-m-d H:i:s', $primerDiaQuincena);
                $fecha_hasta = date('Y-m-d H:i:s', $ultimoDiaQuincena);


                break;

            case 'F_Mes'://mes
                $fecha_desde = date('Y-m-d H:i:s', strtotime('first day of this month 00:00:00'));
                $fecha_hasta = date('Y-m-d H:i:s', strtotime('last day of this month 23:59:59'));
                break;

            case 'F_Mes_Pasado'://mes
                $fecha_desde = date('Y-m-d H:i:s', strtotime('first day of last month 00:00:00'));
                $fecha_hasta = date('Y-m-d H:i:s', strtotime('last day of last month 23:59:59'));
                break;

            case 'F_Ano'://mes
                $fecha_desde = date('Y-m-d H:i:s', strtotime("first day of january " . date('Y') . " 00:00:00 "));
                $fecha_hasta = date('Y-m-d H:i:s', strtotime("last day of december " . date('Y') . " 23:59:59"));
                break;

            case 'F_Personalizado':
                if(     isset($_REQUEST['fechaD'])       ||      isset($_SESSION['filtro']['fechaD'])    ){
                    $fecha_desde =  isset($_REQUEST['fechaD'])     ? $_REQUEST['fechaD'] :    $_SESSION['filtro']['fechaD'];
                    $fecha_hasta =  isset($_REQUEST['fechaH'])     ? $_REQUEST['fechaH'] :    $_SESSION['filtro']['fechaH'];
                }
                else{
                    $fecha_desde = date('Y-m-d H:i:s', strtotime('today 00:00:00'));
                    $fecha_hasta = date('Y-m-d H:i:s', strtotime('today 23:59:59'));
                }
                break;
        }


        $resultado = array(
            "fechaD" =>  $fecha_desde ,
            "fechaH" =>  $fecha_hasta
        );

        $_SESSION['filtro']['fechaD'] = $fecha_desde;
        $_SESSION['filtro']['fechaH'] = $fecha_hasta;
        $_SESSION['filtro']['intervalo'] = $T_Intervalo;
        return $resultado;
    }



}
