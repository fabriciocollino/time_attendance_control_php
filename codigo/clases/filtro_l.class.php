<?php

/**
 * Usuario (List)
 * 
 */
class Filtro_L {


	public static function ejemplo($p_Usuario, $p_Clave) {




	}


    public static function get_filtro_intervalo($T_Intervalo){

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
                $fecha_desde =  isset($_REQUEST['fechaD'])     ? $_REQUEST['fechaD'] :    $_SESSION['filtro']['fechaD'];
                $fecha_hasta =  isset($_REQUEST['fechaH'])     ? $_REQUEST['fechaH'] :    $_SESSION['filtro']['fechaH'];
                break;
        }


        $resultado = array(
            "fechaD" =>  $fecha_desde ,
            "fechaH" =>  $fecha_hasta
        );

        $_SESSION['filtro']['fechaD'] = $fecha_desde;
        $_SESSION['filtro']['fechaH'] = $fecha_hasta;

        return $resultado;
    }

    public static function get_filtro_persona($p_Filtro_Post = array(), $p_Filtro_Session = array(), $p_Limpiar_Filtro = false){

        if ($p_Limpiar_Filtro){
            $T_Filtro_Array = array(
                "Persona"               =>      'TodasLasPersonas',//$T_Filtro_Persona,
                "Grupo"                 =>      '',//$T_Filtro_Grupo,
                "Estado"                =>      'Activo',//$T_Filtro_Estado,
                'Genero'                =>      '',//$T_Filtro_Genero,
                'DNI'                   =>      '',//$T_Filtro_Dni,
                "Legajo"                =>      '',//$T_Filtro_Legajo,
                'Estado Civil'          =>      '',//$T_Filtro_Estado_Civil,
                "Tipo de Horario"       =>      '',//$T_Filtro_Horario_Tipo_Id,
                "Horario Normal"        =>      '',//$T_Filtro_Horario_Normal_Id,
                "Horario Flexible"      =>      '',//$T_Filtro_Horario_Flexible_Id,
                "Horario Multiple"      =>      '',//$T_Filtro_Horario_Multiple_Id,
                "Horario Rotativo"      =>      '',//$T_Filtro_Horario_Rotativo_Id,
                "Equipo"                =>      '',//$T_Filtro_Equipo,
                "Inicio de Actividad"   =>      '',//$T_Filtro_Inicio_Actividad,
                "Fin de Actividad"      =>      '',//$T_Filtro_Fin_Actividad,
                "Nro. Contribuyente"    =>      '',//$T_Filtro_Nro_Contribuyente,
                "Talle Camisa"          =>      '',//$T_Filtro_Talle_Camisa,
                "Email"                 =>      '',//$T_Filtro_Email,
                "Edad Desde"            =>      '',//$T_Filtro_Edad_Desde,
                "Edad Hasta"            =>      ''//$T_Filtro_Edad_Hasta
            );

            return $T_Filtro_Array;
        }

        /* VARIABLES FILTRO */
        $T_Filtro_Persona               =   !isset($p_Filtro_Post['persona']) ? isset($p_Filtro_Session['filtro']['persona']) ? $p_Filtro_Session['filtro']['persona'] : 'TodasLasPersonas' : $p_Filtro_Post['persona'];
        $T_Filtro_Grupo                 =   !isset($p_Filtro_Post['rolF']) ? isset($p_Filtro_Session['filtro']['rolF']) ? $p_Filtro_Session['filtro']['rolF'] : '' : $p_Filtro_Post['rolF'];
        $T_Filtro_Estado                =   !isset($p_Filtro_Post['estado']) ? isset($p_Filtro_Session['filtro']['estado']) ? $p_Filtro_Session['filtro']['estado'] : "Activo" : $p_Filtro_Post['estado'];
        $T_Filtro_Genero                =   !isset($p_Filtro_Post['genero']) ? isset($p_Filtro_Session['filtro']['genero']) ? $p_Filtro_Session['filtro']['genero'] : "" : $p_Filtro_Post['genero'];
        $T_Filtro_Dni                   =   !isset($p_Filtro_Post['dni']) ? isset($p_Filtro_Session['filtro']['dni']) ? $p_Filtro_Session['filtro']['dni'] : "" : $p_Filtro_Post['dni'];
        $T_Filtro_Estado_Civil          =   !isset($p_Filtro_Post['estadoCivil']) ? isset($p_Filtro_Session['filtro']['estadoCivil']) ? $p_Filtro_Session['filtro']['estadoCivil'] : "" : $p_Filtro_Post['estadoCivil'];
        $T_Filtro_Nro_Contribuyente     =   !isset($p_Filtro_Post['nroContribuyente']) ? isset($p_Filtro_Session['filtro']['nroContribuyente']) ? $p_Filtro_Session['filtro']['nroContribuyente'] : "" : $p_Filtro_Post['nroContribuyente'];
        $T_Filtro_Talle_Camisa          =   !isset($p_Filtro_Post['talleCamisa']) ? isset($p_Filtro_Session['filtro']['talleCamisa']) ? $p_Filtro_Session['filtro']['talleCamisa'] : "" : $p_Filtro_Post['talleCamisa'];
        $T_Filtro_Email                 =   !isset($p_Filtro_Post['email']) ? isset($p_Filtro_Session['filtro']['email']) ? $p_Filtro_Session['filtro']['email'] : "" : $p_Filtro_Post['email'];
        $T_Filtro_Legajo                =   !isset($p_Filtro_Post['legajo']) ? isset($p_Filtro_Session['filtro']['legajo']) ? $p_Filtro_Session['filtro']['legajo'] : "" : $p_Filtro_Post['legajo'];
        $T_Filtro_Horario_Tipo_Id       =   !isset($p_Filtro_Post['horario_tipo_id']) ? isset($p_Filtro_Session['filtro']['horario_tipo_id']) ? $p_Filtro_Session['filtro']['horario_tipo_id'] : "" : $p_Filtro_Post['horario_tipo_id'];

        $T_Filtro_Horario_Normal_Id     =   !isset($p_Filtro_Post['horario_normal_id']) ? isset($p_Filtro_Session['filtro']['horario_normal_id']) ? $p_Filtro_Session['filtro']['horario_normal_id'] : "" : $p_Filtro_Post['horario_normal_id'];
        $T_Filtro_Horario_Flexible_Id   =   !isset($p_Filtro_Post['horario_flexible_id']) ? isset($p_Filtro_Session['filtro']['horario_flexible_id']) ? $p_Filtro_Session['filtro']['horario_flexible_id'] : "" : $p_Filtro_Post['horario_flexible_id'];
        $T_Filtro_Horario_Rotativo_Id   =   !isset($p_Filtro_Post['horario_rotativo_id']) ? isset($p_Filtro_Session['filtro']['horario_rotativo_id']) ? $p_Filtro_Session['filtro']['horario_rotativo_id'] : "" : $p_Filtro_Post['horario_rotativo_id'];
        $T_Filtro_Horario_Multiple_Id   =   !isset($p_Filtro_Post['horario_multiple_id']) ? isset($p_Filtro_Session['filtro']['horario_multiple_id']) ? $p_Filtro_Session['filtro']['horario_multiple_id'] : "" : $p_Filtro_Post['horario_multiple_id'];

        $T_Filtro_Equipo                =   !isset($p_Filtro_Post['equipo_id']) ? isset($p_Filtro_Session['filtro']['equipo_id']) ? $p_Filtro_Session['filtro']['equipo_id'] : "" : $p_Filtro_Post['equipo_id'];
        $T_Filtro_Inicio_Actividad      =   ""; // ! !isset($p_Filtro_Post['inicio_actividad']) ? isset($p_Filtro_Session['filtro']['inicio_actividad']) ? $p_Filtro_Session['filtro']['inicio_actividad'] : "" : $p_Filtro_Post['inicio_actividad'];
        $T_Filtro_Fin_Actividad         =   ""; // !isset($p_Filtro_Post['fin_actividad']) ? isset($p_Filtro_Session['filtro']['fin_actividad']) ? $p_Filtro_Session['filtro']['fin_actividad'] : "" : $p_Filtro_Post['fin_actividad'];
        $T_Filtro_Edad_Desde            =   !isset($p_Filtro_Post['edad_desde']) ? isset($p_Filtro_Session['filtro']['edad_desde']) ? $p_Filtro_Session['filtro']['edad_desde'] : "" : $p_Filtro_Post['edad_desde'];
        $T_Filtro_Edad_Hasta            =   !isset($p_Filtro_Post['edad_hasta']) ? isset($p_Filtro_Session['filtro']['edad_hasta']) ? $p_Filtro_Session['filtro']['edad_hasta'] : "" : $p_Filtro_Post['edad_hasta'];


        /* ARRAY FILTRO */
        $T_Filtro_Array = array(
            "Persona"               =>      $T_Filtro_Persona,
            "Grupo"                 =>      $T_Filtro_Grupo,
            "Estado"                =>      $T_Filtro_Estado,
            'Genero'                =>      $T_Filtro_Genero,
            'DNI'                   =>      $T_Filtro_Dni,
            "Legajo"                =>      $T_Filtro_Legajo,
            'Estado Civil'          =>      $T_Filtro_Estado_Civil,
            "Tipo de Horario"       =>      $T_Filtro_Horario_Tipo_Id,
            "Horario Normal"        =>      $T_Filtro_Horario_Normal_Id,
            "Horario Flexible"      =>      $T_Filtro_Horario_Flexible_Id,
            "Horario Multiple"      =>      $T_Filtro_Horario_Multiple_Id,
            "Horario Rotativo"      =>      $T_Filtro_Horario_Rotativo_Id,
            "Equipo"                =>      $T_Filtro_Equipo,
            "Inicio de Actividad"   =>      $T_Filtro_Inicio_Actividad,
            "Fin de Actividad"      =>      $T_Filtro_Fin_Actividad,
            "Nro. Contribuyente"    =>      $T_Filtro_Nro_Contribuyente,
            "Talle Camisa"          =>      $T_Filtro_Talle_Camisa,
            "Email"                 =>      $T_Filtro_Email,
            "Edad Desde"            =>      $T_Filtro_Edad_Desde,
            "Edad Hasta"            =>      $T_Filtro_Edad_Hasta
        );


        /*

        $p_Filtro_Session['filtro']['persona']=$T_Filtro_Persona;
        $p_Filtro_Session['filtro']['rolF']=$T_Filtro_Grupo;
        $p_Filtro_Session['filtro']['estado']=$T_Filtro_Estado;
        $p_Filtro_Session['filtro']['genero']=$T_Filtro_Genero;
        $p_Filtro_Session['filtro']['dni']=$T_Filtro_Dni;
        $p_Filtro_Session['filtro']['estadoCivil']=$T_Filtro_Estado_Civil;
        $p_Filtro_Session['filtro']['nroContribuyente']=$T_Filtro_Nro_Contribuyente;
        $p_Filtro_Session['filtro']['talleCamisa']=$T_Filtro_Talle_Camisa;
        $p_Filtro_Session['filtro']['email']=$T_Filtro_Email;
        $p_Filtro_Session['filtro']['legajo']=$T_Filtro_Legajo;
        $p_Filtro_Session['filtro']['horario_tipo_id']=$T_Filtro_Horario_Tipo_Id;

        $p_Filtro_Session['filtro']['horario_normal_id']=$T_Filtro_Horario_Normal_Id;
        $p_Filtro_Session['filtro']['horario_flexible_id']=$T_Filtro_Horario_Flexible_Id;
        $p_Filtro_Session['filtro']['horario_rotativo_id']=$T_Filtro_Horario_Rotativo_Id;
        $p_Filtro_Session['filtro']['horario_multiple_id']=$T_Filtro_Horario_Multiple_Id;

        $p_Filtro_Session['filtro']['equipo_id']=$T_Filtro_Equipo;
        $p_Filtro_Session['filtro']['inicio_actividad']=$T_Filtro_Inicio_Actividad;
        $p_Filtro_Session['filtro']['fin_actividad']=$T_Filtro_Fin_Actividad;
        $p_Filtro_Session['filtro']['edad_desde']=$T_Filtro_Edad_Desde;
        $p_Filtro_Session['filtro']['edad_hasta']=$T_Filtro_Edad_Hasta;
*/
        return $T_Filtro_Array;
    }


}
