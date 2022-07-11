<?php

$dias_red_2             =   array( 1 => _("Dom"), _("Lun"), _("Mar"), _("Mie"), _("Jue"), _("Vie"), _("Sab"));


$Filtro_Persona         = isset($_SESSION['filtro']['persona'])     ?       $_SESSION['filtro']['persona']      :       "TodasLasPersonas";
$Filtro_Grupo           = isset($_SESSION['filtro']['rolF'])        ?       $_SESSION['filtro']['rolF']         :       "";

$Fecha_Desde            = isset($_SESSION['filtro']['fechaD'])      ?       $_SESSION['filtro']['fechaD']       :       "";
$Fecha_Hasta            = isset($_SESSION['filtro']['fechaH'])      ?       $_SESSION['filtro']['fechaH']       :       "";

$Fecha_Desde_Widget     = isset($_SESSION['filtro']['fechaDw'])     ?       $_SESSION['filtro']['fechaDw']      :       "";
$Fecha_Hasta_Widget     = isset($_SESSION['filtro']['fechaHw'])     ?       $_SESSION['filtro']['fechaHw']      :       "";

$callStartTime = microtime(true);

$UsarFechasWidget       = isset($UsarFechasWidget)                  ?       $UsarFechasWidget                   :       false;
// COMPLETO FECHAS
if ($UsarFechasWidget == true) {
    if (strlen($Fecha_Desde_Widget) < 12) $Fecha_Desde_Widget .= " 00:00:00";
    if (strlen($Fecha_Hasta_Widget) < 12) $Fecha_Hasta_Widget .= " 00:00:00";

    $_SESSION ['filtro']['activos'] = (integer)0;
}
else {
    if (strlen($Fecha_Desde) < 12) $Fecha_Desde .= " 00:00:00";
    if (strlen($Fecha_Desde) < 12) $Fecha_Desde .= " 00:00:00";
}

if($Filtro_Persona == "") $Filtro_Persona="TodasLasPersonas";

//ARRAYS Y VARIABLES GLOBALES DONDE SE GUARDA _TODO
$margen_llegada_tarde                   = 0;
$margen_salidas_temprano                = 0;
$margen_ausencia                        = 0;

$_fecha_D                               = (DateTimeHelper::getTimestampFromFormat($Fecha_Desde, 'Y-m-d H:i:s'));
$_fecha_H                               = (DateTimeHelper::getTimestampFromFormat($Fecha_Hasta, 'Y-m-d H:i:s'));

$condicion                              = '';

$a_Equipos                              = array();
$o_Listado_Por_Persona                  = array();
$array_personas_a_controlar                             = array();

$a_Hora_Trabajo                         = array();
$a_Horarios_Rotativos                   = array();
$a_Horarios_Flexibles                   = array();
$a_Horarios_Multiples                   = array();

$array_personas_a_controlar             = array();
$a_dias_presentes                       = array();

$array_personas_a_controlar                             = array();
$o_Listado                              = array();
$new_List                               = array();



/////////////////// PERSONAS A CONTROLAR & LOGS /////////////////////

$o_persona = Persona_L::obtenerPorUsuarioActual();

if(!is_null($o_persona)) {
    $array_personas_a_controlar [$o_persona['per_Id']] = $o_persona;

    if ($UsarFechasWidget == true) {
        $condicion .= " AND leq_Fecha_Hora >= '{$Fecha_Desde_Widget}' AND leq_Fecha_Hora <= '{$Fecha_Hasta_Widget}' " . ' AND leq_Accion = 1 ';
    } else {
        $condicion .= " AND leq_Fecha_Hora >= '{$Fecha_Desde}' AND leq_Fecha_Hora <= '{$Fecha_Hasta}' " . ' AND leq_Accion = 1 ';
    }


    $condicion = ltrim($condicion, ' AND ');
    $condicion .= 'ORDER BY leq_Per_Id, leq_Fecha_Hora ASC';

    $o_Logs = Logs_Equipo_L::obtenerTodosEnArray('', '', '', $condicion, ' ', ' leq_Id, leq_Eq_Id, leq_Lector, leq_Fecha_Hora, leq_Per_Id, leq_Dedo, leq_Editado');

}




/////////////////// LOGS POR PERSONA /////////////////////

if (!is_null($o_Logs)) {
    foreach ($o_Logs as $log) {
        $perID = $log['leq_Per_Id'];
        $o_Logs_Por_Persona[$perID][] = $log;
    }
    unset($o_Logs);
}
else{
    $o_Logs_Por_Persona =   null;
}




/////////////////// LOGS POR JORNADA CON EQUIPOS /////////////////////

// EQUIPOS
$a_Equipos                  = Equipo_L::obtenerTodosenArray();

$o_Logs_Por_Persona_Fecha   = array();

if (!is_null($o_Logs_Por_Persona)) {
    foreach ($o_Logs_Por_Persona as $perID => $logs_por_persona) {

        $o_persona              = $array_personas_a_controlar[$perID];
        $jornadas               = array();
        $cantLogs               = count($logs_por_persona);
        $ultimoKeyDeJornada     = 0;
        $o_Logs_Por_Persona_Fecha[$perID] = array();
        $o_Logs_Por_Persona_Fecha[$perID] += $array_personas_a_controlar[$perID];

        unset($o_Logs_Por_Persona[$perID]);

        /* SEPARO LOS LOGS POR JORNADAS */
        for ($i = 0; $i < $cantLogs; $i++) {
            if ($i > 0) {

                $_fechaHora_LogActual = new DateTime($logs_por_persona[$i]['leq_Fecha_Hora']);
                $_fechaHora_LogAnterior  = new DateTime($logs_por_persona[$i - 1]['leq_Fecha_Hora']);

                $r_date1  = date('Y-m-d', strtotime($logs_por_persona[$i]['leq_Fecha_Hora']));
                $r_date2  = date('Y-m-d', strtotime($logs_por_persona[$i - 1]['leq_Fecha_Hora']));

                $interval = $_fechaHora_LogAnterior->diff($_fechaHora_LogActual, true);

                // SI (hay mas de 12 horas de diferencia) Ó SI (hay mas de un día de diferencia) => es de la proxima jornada!
                if ($interval->format('%h') >= 12 || $interval->format('%d') >= 1 || $r_date1 != $r_date2) {

                    // FECHA JORNADA
                    $fecha = date('Y-m-d', strtotime($logs_por_persona[$ultimoKeyDeJornada]['leq_Fecha_Hora']));

                    $jornadas[$fecha]   = array_slice($logs_por_persona, $ultimoKeyDeJornada, $i - $ultimoKeyDeJornada);
                    $ultimoKeyDeJornada = $i;
                }
            }
        }

        $fecha            = date('Y-m-d', strtotime($logs_por_persona[$ultimoKeyDeJornada]['leq_Fecha_Hora']));
        $jornadas[$fecha] = array_slice($logs_por_persona, $ultimoKeyDeJornada, $i - $ultimoKeyDeJornada);

        /* /// JUNTO EN OTRO ARRAY LOS LOGS POR JORNADAS /// */
        foreach ($jornadas as $fechaJornada => $jornada) {
            $skip = false;
            foreach ($jornada as $key => $log) {

                //si es por ejemplo, el segundo log de un par, skipeo porque ya lo procese antes.
                if ($skip) {
                    $skip = false;
                    continue;
                }

                ////////// LOG INICIO ///////////
                // FECHA_HORA_INICIO LOG
                $fecha_hora_inicio     = $log['leq_Fecha_Hora'];
                // SEGUNDOS DE FECHA_HORA_INICIO LOG
                $seg_fecha_hora_inicio = strtotime($fecha_hora_inicio);
                // FECHA_LOG_INICIO
                $fecha_log_inicio      = date('Y-m-d', strtotime($fecha_hora_inicio));
                // HORA_LOG_INICIO
                $hora_log_inicio       = date('H:i:s', strtotime($fecha_hora_inicio));
                // SEGUNDOS DE HORA LOG INICIO
                $seg_hora_Log_inicio   = DateTimeHelper::time_to_sec($hora_log_inicio);

                // EQUIPO_LOG_ID_INICIO
                $equipoID_inicio               = $log['leq_Eq_Id'];

                // EQUIPO_LOG_DETALLE_INICIO
                if ($equipoID_inicio != 0 && isset($a_Equipos[$equipoID_inicio])) {
                    $equipo_inicio = $a_Equipos[$equipoID_inicio]->getDetalle();
                }
                else{
                    $equipo_inicio = '';
                }

                $lector_inicio      = $log['leq_Lector'];
                $dedo_inicio        = $log['leq_Dedo'];
                $log_inicio_id      = $log['leq_Id'];
                $log_inicio_editado = $log['leq_Editado'];

                $nocturno = 'No';

                ////////// LOG FIN ///////////
                if (array_key_exists($key + 1, $jornada)) {
                    $log_siguiente      = $jornada[$key + 1];

                    // FECHA_HORA_FIN LOG
                    $fecha_hora_fin     = $log_siguiente['leq_Fecha_Hora'];
                    // SEGUNDOS DE FECHA_HORA_FIN LOG
                    $seg_fecha_hora_fin = strtotime($log_siguiente['leq_Fecha_Hora']);
                    // FECHA_LOG_FIN
                    $fecha_log_fin      = date('Y-m-d', strtotime($log_siguiente['leq_Fecha_Hora']));
                    // HORA_LOG_FIN
                    $hora_log_fin       = date('H:i:s', strtotime($log_siguiente['leq_Fecha_Hora']));
                    // SEGUNDOS DE HORA_LOG_FIN
                    $seg_hora_log_fin   = DateTimeHelper::time_to_sec($hora_log_fin);
                    // EQUIPO_LOG_ID_FIN
                    $equipoID_fin    = $log_siguiente['leq_Eq_Id'];

                    if ($equipoID_fin != 0 && isset($a_Equipos[$equipoID_fin])) {
                        $equipo_fin = $a_Equipos[$log_siguiente['leq_Eq_Id']]->getDetalle();
                    }
                    else{
                        $equipo_fin = '';
                    }

                    $lector_fin      = $log_siguiente['leq_Lector'];
                    $dedo_fin        = $log_siguiente['leq_Dedo'];
                    $log_fin_id      = $log_siguiente['leq_Id'];
                    $log_fin_editado = $log_siguiente['leq_Editado'];

                    $par_de_logs     = 1;
                    $skip            = true;

                }
                else {
                    $fecha_hora_fin     = '';
                    $fecha_log_fin      = '';
                    $seg_fecha_hora_fin = '';

                    $hora_log_fin       = '';
                    $seg_hora_log_fin   = '';

                    $equipo_fin         = '';
                    $equipoID_fin       = '';
                    $lector_fin         = '';
                    $dedo_fin           = '';

                    $log_fin_id         = '';
                    $log_fin_editado    = '';

                    $par_de_logs        = 0;
                }



                //$o_Logs_Por_Persona[$perID] += $array_personas_a_controlar[$perID];

                // GUARDO PAR DE LOGS JUNTOS
                $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fechaJornada][] = array(

                    'Fecha_Hora_Inicio'                     => $fecha_hora_inicio,
                    'Seg_Fecha_Hora_Inicio'                 => $seg_fecha_hora_inicio,
                    'Hora_Inicio'                           => $hora_log_inicio,
                    'Seg_Hora_Inicio'                       => $seg_hora_Log_inicio,
                    'Hora_Inicio_Log_Id'                    => $log_inicio_id,
                    'Hora_Inicio_Log_Editado'               => $log_inicio_editado,

                    'Equipo_Inicio'                         => $equipo_inicio,
                    'EquipoID_Inicio'                       => $equipoID_inicio,
                    'Lector_Inicio'                         => $lector_inicio,
                    'Dedo_Inicio'                           => $dedo_inicio,


                    'Fecha_Hora_Fin'                        => $fecha_hora_fin,
                    'Seg_Fecha_Hora_Fin'                    => $seg_fecha_hora_fin,
                    'Hora_Fin'                              => $hora_log_fin,
                    'Seg_Hora_Fin'                          => $seg_hora_log_fin,
                    'Hora_Fin_Log_Id'                       => $log_fin_id,
                    'Hora_Fin_Log_Editado'                  => $log_fin_editado,

                    'Equipo_Fin'                            => $equipo_fin,
                    'EquipoID_Fin'                          => $equipoID_fin,
                    'Lector_Fin'                            => $lector_fin,
                    'Dedo_Fin'                              => $dedo_fin,

                    'Nocturno'                              => $nocturno,

                    'Nocturno_Doble'                        => '',
                    'Continuacion_De_Horario_Nocturno'      => 'No',  //esto se pone en SI, si el log actual es una continuacion de un log que arranco el dia anterior
                    'Par'                                   => $par_de_logs
                );


            }
        }



    }
    //;
    unset($a_Equipos);
}




/////////////////// HORARIOS /////////////////////

// HORARIOS
$a_Hora_Trabajo          = Hora_Trabajo_L::obtenerTodosenArray();
$a_Horarios_Rotativos    = Horario_Rotativo_L::obtenerTodosenArray();
$a_Horarios_Flexibles    = Horario_Flexible_L::obtenerTodosenArray();
$a_Horarios_Multiples    = Horario_Multiple_L::obtenerTodosenArray();

// MARGENES
$margen_llegada_tarde    = Config_L::p("margen_llegada_tarde") * 60;
$margen_salidas_temprano = Config_L::p("margen_salida") * 60;
$margen_ausencia         = Config_L::obtenerPorParametro("margen_ausencia")->getValor() * 60;


if (!is_null($o_Logs_Por_Persona_Fecha)) {

    foreach ($o_Logs_Por_Persona_Fecha as $perID => $_persona_a_controlar) {

        $o_persona_HorarioId                   = $_persona_a_controlar ['per_Hor_Id'];
        $o_persona_HorarioTipo                 = $_persona_a_controlar ['per_Hor_Tipo'];

        // HORARIO OBJECT
        switch ($o_persona_HorarioTipo) {
            case HORARIO_NORMAL:
                $o_hora_trabajo = $a_Hora_Trabajo[$o_persona_HorarioId];
                break;
            case HORARIO_FLEXIBLE:
                $o_hora_trabajo = $a_Horarios_Flexibles[$o_persona_HorarioId];
                break;
            case HORARIO_ROTATIVO:
                $o_hora_trabajo = $a_Horarios_Rotativos[$o_persona_HorarioId];
                break;
            case HORARIO_MULTIPLE:
                $o_hora_trabajo = $a_Horarios_Multiples[$o_persona_HorarioId];
                break;
            case 0:
            default:
                $o_hora_trabajo = null;
                break;
        }

        // HORARIO DETALLE: CON HORARIO
        if (!is_null($o_hora_trabajo)) {
            $o_Logs_Por_Persona_Fecha[$perID]['Hora_Trabajo_Detalle'] = $o_hora_trabajo->getDetalle();
            $o_Logs_Por_Persona_Fecha[$perID]['Hora_Trabajo']         = $o_hora_trabajo->getTextoDiasResumido($dias_red_2);
        }
        // HORARIO DETALLE: SIN HORARIO
        else {
            $o_Logs_Por_Persona_Fecha[$perID]['Hora_Trabajo_Detalle'] = 'Sin Horario';
            $o_Logs_Por_Persona_Fecha[$perID]['Hora_Trabajo']         = '';
        }

        $log_anterior_nocturno                          = false;
        $esto_es_continuacion_del_log_anterior_nocturno = false;

        // [DIAS]
        foreach ($o_Logs_Por_Persona_Fecha[$perID]['Dias'] as $fecha => $dia) {

            // DIAS PRESENTE: POR PERSONA
            $a_dias_presentes[$perID][] = $fecha;

            // LOG INICIO: PARA LLEGADA TARDE Y HORAS EXTRA ANTES
            $log_inicio = $dia[0];
            // LOG FINAL: PARA SALIDA TEMPRANO Y HORAS EXTRA DESPUES
            $pares_de_logs_en_el_dia = count($dia);
            $log_final  = $dia[$pares_de_logs_en_el_dia - 1];


            /*************  LOGS FECHA Y HORA, HORARIOS NOCTURNOS *****************/
            foreach ($dia as $key => $log) {
                $fechaInicio = '';
                $fechaFin    = '';
                if ($log['Fecha_Hora_Inicio'] != '') {
                    $fechaInicio = date('Y-m-d', strtotime($log['Fecha_Hora_Inicio']));
                }
                if ($log['Fecha_Hora_Fin'] != '') {
                    $fechaFin = date('Y-m-d', strtotime($log['Fecha_Hora_Fin']));
                }
                if ($fechaInicio != '' && $fechaInicio != $fecha) {//fecha inicio diferente
                    $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][$key]['Nocturno']                         = "Si";
                    $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][$key]['Continuacion_De_Horario_Nocturno'] = "Si";
                }
                if ($fechaFin != '' && $fechaFin != $fecha) {//fecha diferente
                    $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][$key]['Nocturno']                         = "Si";
                    $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][$key]['Continuacion_De_Horario_Nocturno'] = "Si";
                }
                if (($fechaFin != '' && $fechaFin != $fecha) && ($fechaInicio != '' && $fechaInicio != $fecha)) {//fecha doblemente diferente
                    $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][$key]['Nocturno_Doble'] = "Si";
                }
            }

            /*************  HORARIO INICIO DIA , HORARIO FIN  DIA *****************/
            $hora_trabajo_inicio = '';
            $hora_trabajo_fin    = '';

            switch ($o_persona_HorarioTipo) {
                case HORARIO_NORMAL:

                    $o_hora_trabajo = $a_Hora_Trabajo[$o_persona_HorarioId];

                    if (!$o_hora_trabajo) continue;

                    $a_dias_del_horario  = $o_hora_trabajo->getArrayDias();
                    $hora_trabajo_inicio = date('H:i:s', strtotime($o_hora_trabajo->obtenerHoraInicio($fecha, 'H:i:s')));
                    $hora_trabajo_fin    = date('H:i:s', strtotime($o_hora_trabajo->obtenerHoraFin($fecha, 'H:i:s')));


                    break;
                case HORARIO_FLEXIBLE:

                    $o_hora_trabajo = $a_Horarios_Flexibles[$o_persona_HorarioId];

                    if (!$o_hora_trabajo) continue;
                    $a_horarios_de_trabajo_del_horario_flexible = $o_hora_trabajo->getArrayDias();
                    $horario_cercano                            = $o_hora_trabajo->getHorarioByClosestTime($fecha . date(' H:i:s'), false);
                    $hora_trabajo_inicio                        = date('H:i:s', strtotime($horario_cercano[0]));
                    $hora_trabajo_fin                           = date('H:i:s', strtotime($horario_cercano[1]));

                    break;
                case HORARIO_ROTATIVO:
                    $o_hora_trabajo = $a_Horarios_Rotativos[$o_persona_HorarioId];

                    if (!$o_hora_trabajo) continue;
                    $a_dias_del_horario  = $o_hora_trabajo->getArrayDias(true, $fecha);
                    $horario             = $o_hora_trabajo->getHorarioByDay($fecha);
                    $hora_trabajo_inicio = date('H:i:s', strtotime($horario[0]));
                    $hora_trabajo_fin    = date('H:i:s', strtotime($horario[1]));

                case HORARIO_MULTIPLE:
                    $o_hora_trabajo = $a_Horarios_Multiples[$o_persona_HorarioId];

                    if (!$o_hora_trabajo) continue;
                    $a_horarios_de_trabajo_del_horario_multiple = $o_hora_trabajo->getArrayDias();
                    $horario                                    = $o_hora_trabajo->getHorarioByClosestTime($fecha . date(' H:i:s'));
                    $hora_trabajo_inicio                        = date('H:i:s', strtotime($horario[0]));
                    $hora_trabajo_fin                           = date('H:i:s', strtotime($horario[1]));
                    break;

                case 0:
                default:
                    break;
            }


            foreach ($dia as $key => $log) {
                $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][$key]['Hora_Trabajo_Inicio']   = $hora_trabajo_inicio;
                $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][$key]['Hora_Trabajo_Fin']      = $hora_trabajo_fin;
            }


            /*************  LLEGADAS TARDE: SI  *****************/
            if (DateTimeHelper::time_to_sec($hora_trabajo_inicio) + intval($margen_llegada_tarde) < $log_inicio['Seg_Hora_Inicio']) {

                $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][0]['Llegada_Tarde'] = 'Si';
                $horaDeSalida = explode(':', $hora_trabajo_fin);
                $segundos = count($horaDeSalida) < 3 ? ':00' : '';


                $permisos = Permisos_L::obtenerPorLlegadaTardeyPersona($fecha . ' ' . $hora_trabajo_inicio . $segundos, $perID, $log_inicio['Fecha_Hora_Inicio']);

                if (!is_null($permisos) && !empty($permisos)) {  //hay licencia Or permiso, entonces no es llegada tarde
                    $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][0]['Llegada_Tarde_reason'] = 'Permiso';
                } else {
                    $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][0]['Llegada_Tarde_reason'] = 'Sin Motivo';
                }

            }
            /*************   LLEGADAS TARDE: NO ; HORAS EXTRAS ANTES  *****************/
            else {

                $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][0]['Llegada_Tarde'] = 'No';

                if (!$esto_es_continuacion_del_log_anterior_nocturno) {

                    $date1 = new DateTime('2006-04-14T' . $hora_trabajo_inicio);
                    $date2 = new DateTime('2006-04-14T' . $log_inicio['Hora_Inicio']);

                    $_key = count($o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha]) - 1;

                    if ($date2 < $date1) {
                        $diff = $date2->diff($date1, true);
                        $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][$_key]['Hora_Extra_Antes'] = $diff->format('%H:%I:%S');
                    }
                    else{
                        $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][$_key]['Hora_Extra_Antes'] = 0;
                    }
                }
            }

            /*************  SALIDA TEMPRANO: SI *****************/
            $_key = $pares_de_logs_en_el_dia - 1;

            if (DateTimeHelper::time_to_sec($hora_trabajo_fin) - intval($margen_salidas_temprano) > $log_final['Seg_Hora_Fin']) {



                if ($log_final['Fecha_Hora_Fin'] == '') {
                    $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][$_key]['Salida_Temprano']   = 'Incierto';
                }
                else {
                    $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][$_key]['Salida_Temprano']   = 'Si';
                }


                $horaDeSalida = explode(':', $hora_trabajo_fin);
                $segundos = (count($horaDeSalida) < 3) ? ':00' : '';
                $permisos   = Permisos_L::obtenerPorSalidaTempranoyPersona($fecha . ' ' . $hora_trabajo_fin . $segundos, $perID, $log_inicio['Fecha_Hora_Fin']);


                if (!is_null($permisos) && !empty($permisos)) {
                    $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][$_key]['Salida_Temprano_reason'] = 'Permiso';
                }
                else {
                    $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][$_key]['Salida_Temprano_reason'] = 'Sin Motivo';
                }

            }
            /************* SALIDA TEMPRANO: NO ; HORAS EXTRA DESPUES  *****************/
            else {

                $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][$_key]['Salida_Temprano']   = 'No';

                $date1 = new DateTime('2006-04-14T' . $hora_trabajo_fin);
                $date2 = new DateTime('2006-04-14T' . $log_final['Hora_Fin']);

                if ($date2 > $date1) {
                    $diff = $date2->diff($date1);
                    $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][$_key]['Hora_Extra_Despues'] = $diff->format('%H:%I:%S');
                }
                else{
                    $o_Logs_Por_Persona_Fecha[$perID]['Dias'][$fecha][$_key]['Hora_Extra_Despues'] = 0;
                }

            }


        }

        $callEndTime   = microtime(true);
        $executionTime = $callEndTime - $callStartTime;
        if ($executionTime > 30) {
            $setMissingData = true;
            break;
        }
    }
    $o_Listado = $o_Logs_Por_Persona_Fecha;
    unset($o_Logs_Por_Persona_Fecha);
}
/**************************************************************************************************************************************
 * E - MENSAJES
 ***********************************************************************************************************************************
 */
$a_o_Mensaje = Mensaje_L::obtenerTodosUnStatus();
if ($a_o_Mensaje) {
    $flag = true;
    foreach ($a_o_Mensaje as $o_Mensaje) {
        $o_Mensaje = Mensaje_L::obtenerPorId($o_Mensaje->getId());

        if ($o_Mensaje->getTipo() == 0 && $o_Mensaje->getVisto() == 1 && $o_Mensaje->getStatus() == 0 && $o_Mensaje->getDisparador() == 0 && strtotime(date('Y-m-d H:i:s')) > strtotime($o_Mensaje->getDisparadorHora())) {

            $o_Mensaje->setVisto(0);
            $o_Mensaje->setStatus(1);
            $o_Mensaje->save(Registry::getInstance()->general['debug'], $flag);
        }
    }
}
/**************************************************************************************************************************************
 * F - SWITCH
 ************************************************************************************************************************************
 */
if (is_null($o_Listado)) {}
else switch ($T_Tipo) {

    case 'Tarde':  //reporte de llegadas tarde
    case '19':  //reporte de llegadas tarde PDF
    case 'mod_LLegada_Tarde': //widget llegada tarde / late arrivals
        $_SESSION['Llegadas_Tarde_Email'] = array();

        /*************      $o_Listado      *****************/
        foreach ($o_Listado as $perID => $item) {

            $o_ListadoLlegadasTarde = array();

            foreach ($item['Dias'] as $fecha => $dia) {

                if ($dia[0]['Llegada_Tarde'] == "Si" && $dia[0]['Hora_Trabajo_Inicio'] != "00:00:00" && $dia[0]['Hora_Trabajo_Fin'] != "00:00:00"  ) {
                    $o_ListadoLlegadasTarde[] = $fecha;
                }
                else{
                    unset($o_Listado[$perID]['Dias'][$fecha]);
                }
            }
            /*************  Persona SI llego tarde  *****************/
            if (count($o_ListadoLlegadasTarde) > 0 && isset($o_Listado[$perID]) && $o_Listado[$perID]['per_Hor_Tipo'] != 0) {
                $o_Listado[$perID]['Llegadas_Tarde'] = $o_ListadoLlegadasTarde;
            }
            /*************  Persona NO llego tarde  *****************/
            else {
                unset($o_Listado[$perID]);
            }
        }
        /*************      $csv_excel_data[]      *****************/


        $csv_excel_data[] = array('Legajo', 'Apellido', 'Nombre', 'Horario', 'Dia', 'Fecha', 'Hora', 'Equipo', 'Motivo');

        if ($o_Listado) {
            foreach ($o_Listado as $list) {
                foreach ($list['Dias'] as $key_fecha => $fecha) {

                    $csv_excel_data[] = array(
                        $list['per_Legajo'],
                        $list['per_Apellido'],
                        $list['per_Nombre'],
                        $list['Hora_Trabajo_Detalle'],
                        $dias[date('w', strtotime($key_fecha))],
                        $key_fecha,
                        $fecha[0]['Hora_Inicio'],
                        $fecha[0]['Equipo_Inicio'] == '' ? 'Web' : $fecha[0]['Equipo_Inicio'],
                        $fecha[0]['Llegada_Tarde_reason']);
                }
            }
        }

        break;

    case 'Temprano':  //reporte de salidas temprano
    case '23':  //reporte de salidas temprano PDF
    case 'mod_Salida_Temprano': //widget salida temprano / early departure
        /*************      $o_Listado      *****************/

        $_SESSION['Salidas_Temprano_Email'] = array();

        foreach ($o_Listado as $perID => $item) {
            $o_ListadoSalidasTemprano = array();

            foreach ($item['Dias'] as $fecha => $dia) {
                $ultimologdia = count($dia) - 1;

                if ($dia[$ultimologdia]['Salida_Temprano'] == "Si" && $dia[$ultimologdia]['Hora_Trabajo_Inicio'] != "00:00:00" && $dia[$ultimologdia]['Hora_Trabajo_Fin'] != "00:00:00" ) {  //solo me fijo salida temprano en el ultimo registro del dia
                    $o_ListadoSalidasTemprano[] = $fecha;
                }
                else{
                    unset($o_Listado[$perID]['Dias'][$fecha]);
                }

            }
            /*************  Persona SI salio temprano  *****************/
            if (count($o_ListadoSalidasTemprano) > 0 && isset($o_Listado[$perID]) && $o_Listado[$perID]['per_Hor_Tipo'] != 0) {
                $o_Listado[$perID]['Salidas_Temprano'] = $o_ListadoSalidasTemprano;
            }
            /*************  Persona NO salio temprano  *****************/
            else{
                unset($o_Listado[$perID]);
            }
        }
        /*************      $csv_excel_data[]      *****************/
        $csv_excel_data[] = array('Legajo', 'Apellido', 'Nombre', 'Horario', 'Dia', 'Fecha', 'Hora', 'Equipo', 'Motivo');

        if ($o_Listado) {
            foreach ($o_Listado as $list) {

                foreach ($list['Dias'] as $key_fecha => $fecha) {
                    $ultimologdia               = count($fecha) - 1;

                    $csv_excel_data[]           = array(
                        $list['per_Legajo'],
                        $list['per_Apellido'],
                        $list['per_Nombre'],
                        $list['Hora_Trabajo_Detalle'],
                        $dias[date('w', strtotime($key_fecha))],
                        $key_fecha,
                        $fecha[$ultimologdia]['Hora_Fin'],
                        $fecha[$ultimologdia]['Equipo_Fin'] == '' ? 'Web' : $fecha[$ultimologdia]['Equipo_Fin'],
                        $fecha[$ultimologdia]['Salida_Temprano_reason']);
                }

            }
        }
        break;

    case 'Entradas':
    case '20':
        $csv_excel_data[] = array('Legajo', 'Apellido', 'Nombre', 'Horario', 'Dia', 'Fecha', 'Ingreso', 'Salida', 'Total Intervalo');
        if ($o_Listado) {
            foreach ($o_Listado as $list) {

                if(!isset($list['Dias'])) continue;

                foreach ($list['Dias'] as $key_fecha => $fecha) {
                    foreach ($fecha as $f_list) {
                        $total_inter = "";

                        if (($f_list['Hora_Inicio'] != "" && $f_list['Hora_Inicio'] != null) && ($f_list['Hora_Fin'] != "" && $f_list['Hora_Fin'] != null)) {
                            $date1   = $key_fecha . " " . $f_list['Hora_Inicio'];
                            $earlier = new DateTime($date1);
                            $date2   = $key_fecha . " " . $f_list['Hora_Fin'];
                            $later   = new DateTime($date2);
                            $diff    = $later->diff($earlier);
                            $Hours   = $diff->h;
                            if ($Hours <= 9) {
                                $Hours = "0" . $Hours;
                            }
                            $minuts = $diff->i;
                            if ($minuts <= 9) {
                                $minuts = "0" . $minuts;
                            }
                            $sec = $diff->s;
                            if ($sec <= 9) {
                                $sec = "0" . $sec;
                            }
                            $total_inter = $Hours . ":" . $minuts . ":" . $sec;
                        }
                        else {
                            $f_list['Hora_Inicio'] = "";
                            $f_list['Hora_Fin']    = "";
                        }
                        $Equipo_Inicio    = $f_list['Equipo_Inicio'] == '' ? 'Web' : $f_list['Equipo_Inicio'];
                        $Equipo_Fin       = $f_list['Equipo_Fin'] == '' ? 'Web' : $f_list['Equipo_Fin'];

                        $csv_excel_data[] = array(
                            $list['per_Legajo'],
                            $list['per_Apellido'],
                            $list['per_Nombre'],
                            $list['Hora_Trabajo_Detalle'],
                            $dias[date('w', strtotime($key_fecha))],
                            $key_fecha,
                            $f_list['Hora_Inicio'],
                            $f_list['Hora_Fin'],
                            $total_inter);
                    }
                }


            }
        }
        break;

    case 'Dias':
    case '21':

        foreach ($o_Listado as $perID => $item) {

            foreach ($item['Dias'] as $fecha => $dia) {

                $total_dia = 0;

                foreach ($dia as $key => $log) {

                    //CALCULO DE INTERVALO POR CADA LOG
                    if ($log['Seg_Fecha_Hora_Fin'] != '' && $log['Seg_Fecha_Hora_Inicio'] != '') {

                        $horas_extra_totales = 0;

                        $dateInicio = new DateTime();
                        $dateInicio->setTimestamp($log['Seg_Fecha_Hora_Inicio']);
                        $dateFin = new DateTime();
                        $dateFin->setTimestamp($log['Seg_Fecha_Hora_Fin']);

                        $diff = $dateFin->diff($dateInicio);
                        $o_Listado[$perID]['Dias'][$fecha][$key]['Total_Intervalo'] = $diff->format('%H:%I:%S');

                        $total_dia += DateTimeHelper::time_to_sec($o_Listado[$perID]['Dias'][$fecha][$key]['Total_Intervalo']);

                        // ** Conversion del total a Numero con decimal. Faltaria simplificar el resto del codigo, por ahora solo se cambia este valor para mantener la logica
                        $o_Listado[$perID]['Dias'][$fecha][$key]['Total_Intervalo'] = round(abs($total_dia / 3600), 2);

                    }
                    else{
                        $o_Listado[$perID]['Dias'][$fecha][$key]['Total_Intervalo'] = 0;
                    }

                }

            }

        }
        /*************      $csv_excel_data[]      *****************/
        $csv_excel_data[] = array('Legajo', 'Apellido', 'Nombre', 'Horario', 'Dia', 'Fecha', 'Ingreso', 'Salida', 'Total');

        if ($o_Listado) {
            foreach ($o_Listado as $list) {

                $list['Hora_Trabajo_Detalle'] = iconv("UTF-8", "ISO-8859-1", $list['Hora_Trabajo_Detalle']);

                foreach ($list['Dias'] as $key_fecha => $fecha) {
                    foreach ($fecha as $f_list) {

                        $csv_excel_data[] = array(
                            $list['per_Legajo'],
                            $list['per_Nombre'],
                            $list['per_Apellido'],
                            $list['Hora_Trabajo_Detalle'],
                            $dias[date('w', strtotime($key_fecha))],
                            $key_fecha,
                            $f_list['Hora_Inicio'],
                            $f_list['Hora_Fin'],
                            $f_list['Total_Intervalo']);
                    }
                }
            }
        }

        break;

    case 'Ausencias': //Ausencias PDF
    case '22': //Ausencias
    case 'mod_Ausencias':

        $_SESSION['Ausencias_Email']    = array();
        $o_Listado_Ausencias            = array();
        unset($o_Listado);
        /*************      $o_Listado_Ausencias   *****************/
        if (isset($array_personas_a_controlar) && !empty($array_personas_a_controlar)) {
            $cantidad_dias      = 0;
            $cantidad_dias      = DateTimeHelper::diff_Fecha_En_Dias($_SESSION['filtro']['fechaD'], $_SESSION['filtro']['fechaH']);
            $o_hora_trabajo     = null;
            $t_fecha            = date('Y-m-d', strtotime($_SESSION['filtro']['fechaD'])); //arranco con el primer dia

            for ($i = 0; $i <= $cantidad_dias; $i++) {
                if ($i != 0) { //le voy sumando un dia a la fecha para ir controlando dia x dia.
                    $t_fecha = DateTimeHelper::Sum_Dias($t_fecha, '1');
                }
                /*************      FECHA FUTURA    *****************/
                if (strtotime($t_fecha) > time()) {
                    continue;  //Para que no marque como ausencia los dias futuros!
                }
                /*************      PERSONAS A CONTROLAR    *****************/
                $dia_n = (date('w', strtotime($t_fecha)) + 1); // 1 (para domingo) hasta 7 (para sabado)

                foreach ($array_personas_a_controlar as $perID => $o_persona) {

                    /*************      PERSONA PRESENTE      *****************/
                    if (array_key_exists($perID, $a_dias_presentes) && in_array($t_fecha, $a_dias_presentes[$perID])) {
                        continue;
                    }

                    /*************      PERSONA AUSENTE      *****************/
                    else {
                        $tenia_que_venir     = false;
                        $hora_trabajo_inicio = '';
                        $hora_trabajo_fin    = '';
                        if (!$o_persona) continue;
                        switch ($o_persona['per_Hor_Tipo']) {
                            case HORARIO_NORMAL:
                                $o_hora_trabajo = $a_Hora_Trabajo[$o_persona['per_Hor_Id']];

                                if (!$o_hora_trabajo) continue;
                                $a_dias_del_horario  = $o_hora_trabajo->getArrayDias();
                                $hora_trabajo_inicio = date('H:i:s', strtotime($o_hora_trabajo->obtenerHoraInicio($t_fecha, 'H:i:s')));
                                $hora_trabajo_fin    = date('H:i:s', strtotime($o_hora_trabajo->obtenerHoraFin($t_fecha, 'H:i:s')));

                                if (date('H:i', $a_dias_del_horario[$dia_n][0]) != '00:00' && date('H:i', $a_dias_del_horario[$dia_n][1]) != '00:00') {//si las fechas de inicio y fin del dia son distintas a 00:00, tenia que venir.
                                    $tenia_que_venir = true;
                                }
                                break;
                            case HORARIO_FLEXIBLE:
                                $o_hora_trabajo = $a_Horarios_Flexibles[$o_persona['per_Hor_Id']];
                                /* @var $o_hora_trabajo Horario_Flexible_O */
                                if (!$o_hora_trabajo) continue;
                                $a_horarios_de_trabajo_del_horario_flexible = $o_hora_trabajo->getArrayDias();
                                $horario_cercano                            = $o_hora_trabajo->getHorarioByClosestTime($t_fecha . date(' H:i:s'), false);
                                $hora_trabajo_inicio                        = date('H:i:s', strtotime($horario_cercano[0]));
                                $hora_trabajo_fin                           = date('H:i:s', strtotime($horario_cercano[1]));
                                foreach ($a_horarios_de_trabajo_del_horario_flexible as $a_dias_del_horario) {
                                    if (date('H:i', $a_dias_del_horario[$dia_n][0]) != '00:00' && date('H:i', $a_dias_del_horario[$dia_n][1]) != '00:00') {//si las fechas de inicio y fin del dia son distintas a 00:00, tenia que venir.
                                        $tenia_que_venir = true;
                                        break;//si ya tenia que venir, dejo de buscar.
                                    }
                                }
                                break;
                            case HORARIO_ROTATIVO:
                                $o_hora_trabajo = $a_Horarios_Rotativos[$o_persona['per_Hor_Id']];
                                /* @var $o_hora_trabajo Horario_Rotativo_O */
                                if (!$o_hora_trabajo) continue;
                                $a_dias_del_horario  = $o_hora_trabajo->getArrayDias(true, $t_fecha);
                                $horario             = $o_hora_trabajo->getHorarioByDay($t_fecha);
                                $hora_trabajo_inicio = date('H:i:s', strtotime($horario[0]));
                                $hora_trabajo_fin    = date('H:i:s', strtotime($horario[1]));
                                if (date('H:i', $a_dias_del_horario[$dia_n][0]) != '00:00' && date('H:i', $a_dias_del_horario[$dia_n][1]) != '00:00') {//si las fechas de inicio y fin del dia son distintas a 00:00, tenia que venir.
                                    $tenia_que_venir = true;
                                }
                                break;
                            case HORARIO_MULTIPLE:
                                $o_hora_trabajo = $a_Horarios_Multiples[$o_persona['per_Hor_Id']];
                                /* @var $o_hora_trabajo Horario_Multiple_O */
                                if (!$o_hora_trabajo) continue;
                                $a_horarios_de_trabajo_del_horario_multiple = $o_hora_trabajo->getArrayDias();
                                $horario                                    = $o_hora_trabajo->getHorarioByClosestTime($t_fecha . date(' H:i:s'));
                                $hora_trabajo_inicio                        = date('H:i:s', strtotime($horario[0]));
                                $hora_trabajo_fin                           = date('H:i:s', strtotime($horario[1]));
                                foreach ($a_horarios_de_trabajo_del_horario_multiple as $a_dias_del_horario) {
                                    if (date('H:i', $a_dias_del_horario[$dia_n][0]) != '00:00' && date('H:i', $a_dias_del_horario[$dia_n][1]) != '00:00') {//si las fechas de inicio y fin del dia son distintas a 00:00, tenia que venir.
                                        $tenia_que_venir = true;
                                        break;//si ya tenia que venir, dejo de buscar.
                                    }
                                }
                                break;
                            case 0:
                            default:
                                //si la persona no tiene horario, no la controlo. 1548936000
                                continue;
                                break;
                        }
                        /** si tenia que venir y no vino, reviso feriados, licencias etc etc*/
                        if ($t_fecha == date('Y-m-d')) {
                            if (!((strtotime($hora_trabajo_inicio) + $margen_ausencia) < strtotime(date("H:i:s"))))
                                continue;
                        }

                        if ($tenia_que_venir && $hora_trabajo_inicio != '00:00:00' && $hora_trabajo_fin != '00:00:00') {

                            if (!array_key_exists($perID, $o_Listado_Ausencias)) {

                                $o_Listado_Ausencias[$perID] =      array();
                                $o_Listado_Ausencias[$perID] +=     $array_personas_a_controlar[$perID];

                            }

                            $o_Listado_Ausencias[$perID]['Ausencias'][]                         = $t_fecha;
                            $o_Listado_Ausencias[$perID]['Hora_Trabajo']                        = $o_hora_trabajo->getTextoDiasResumido($dias_red_2);
                            $o_Listado_Ausencias[$perID]['Hora_Trabajo_Detalle']                = $o_hora_trabajo->getDetalle();
                            $o_Listado_Ausencias[$perID][$t_fecha]['Hora_Trabajo_Inicio']       = $hora_trabajo_inicio;
                            $o_Listado_Ausencias[$perID][$t_fecha]['Hora_Trabajo_Fin']          = $hora_trabajo_fin;

                            $feriado     = Feriado_L::obtenerPorDiayPersona($t_fecha, $o_persona['per_Id']);
                            $licencia    = Licencias_L::obtenerPorDiaCompletoyPersona($t_fecha, $o_persona['per_Id']);
                            $permisos    = Permisos_L::obtenerPorDiaCompletoyPersona($t_fecha,$o_persona['per_Id']);
                            $suspensions = Suspensions_L::obtenerPorDiaCompletoyPersona($t_fecha, $o_persona['per_Id']);

                            if (!is_null($feriado) && !empty($feriado)) {
                                $o_Listado_Ausencias[$perID][$t_fecha]['Reason'] = "Feriado";
                            }
                            else if (!is_null($licencia) && !empty($licencia)) {
                                $o_Listado_Ausencias[$perID][$t_fecha]['Reason'] = "Licencia";
                            }
                            else if (!is_null($permisos) && !empty($permisos)) {
                                $o_Listado_Ausencias[$perID][$t_fecha]['Reason'] = "Permisos";
                            }
                            else if (!is_null($suspensions) && !empty($suspensions)) {
                                $o_Listado_Ausencias[$perID][$t_fecha]['Reason'] = "Suspensión";
                            }
                            else {
                                $o_Listado_Ausencias[$perID][$t_fecha]['Reason'] = "Sin Motivo";
                            }

                        }
                    }
                }
            }
        }


        unset($array_personas_a_controlar);
        /*************      $csv_excel_data[]      *****************/
        $csv_excel_data[]               = array('Legajo', 'Nombre', 'Apellido', 'Horario', 'Fecha', 'Motivo');
        if ($o_Listado_Ausencias) {
            foreach ($o_Listado_Ausencias as $list) {
                foreach ($list['Ausencias'] as $_fecha) {

                    $csv_excel_data[] = array(
                        $list['per_Legajo'],
                        $list['per_Nombre'],
                        $list['per_Apellido'],
                        $list['Hora_Trabajo_Detalle'],
                        $_fecha,
                        $list[$_fecha]['Reason']);
                }
            }
        }

        break;

    case 'Feriados':
    case '24':
        //DIFF FERIADOS_CONTROLDE PERSONAL INCLUDE
        if (isset($_REQUEST["selFeriado"]) && $_REQUEST["selFeriado"] != "") {
            $feriado_id = (integer)$_REQUEST["selFeriado"];
        }
        else {
            $feriado_id = $_SESSION['filtro']['feriado'];
        }
        $o_Feriado = Feriado_L::obtenerPorId($feriado_id);
        if (isset($o_Feriado) && $o_Feriado != null) {
            if (isset($_REQUEST["selFeriado"])) {  // abduls
                if ($_SESSION['filtro']['feriado'] != (integer)$_REQUEST["selFeriado"]) {
                    $_SESSION['filtro']['fechaD'] = $o_FeriadoFechaInicio = $o_Feriado->getFechaInicio();
                    $_SESSION['filtro']['fechaH'] = $o_FeriadoFechaFin = $o_Feriado->getFechaFin(); // abduls
                }
                else {
                    $o_FeriadoFechaInicio = $o_Feriado->getFechaInicio();
                    $o_FeriadoFechaFin    = $o_Feriado->getFechaFin(); // abduls
                }
            }
            else {
                $o_FeriadoFechaInicio = $o_Feriado->getFechaInicio();
                $o_FeriadoFechaFin    = $o_Feriado->getFechaFin(); // abduls
            }
            $date = DateTime::createFromFormat('Y-m-d h:i:s', $o_Feriado->getFechaInicio());
            $date->modify('+1 day');
            // $_SESSION['filtro']['fechaH'] = $date->format('Y-m-d 00:00:00'); // abduls
        }
        else {
            $_SESSION['filtro']['fechaH'] = $_SESSION['filtro']['fechaD']; //para que no devuelva resultados;
            $o_FeriadoFechaInicio         = date('Y-m-d 00:00:00');
            $o_FeriadoFechaFin            = date('Y-m-d 00:00:00');
        }
        if (isset($_REQUEST["selFeriado"]) && $_REQUEST["selFeriado"] != "") {
            $_SESSION['filtro']['feriado'] = (integer)$_REQUEST["selFeriado"];
        }
        //DIFF FERIADOS_CONTROL DE PERSONAL INCLUDE
        $csv_excel_data = array();
        if ($o_Listado) {
            foreach ($o_Listado as $list) {
                if (!$list['Nombre']) continue;
                $csv_excel_data[] = array('Legajo', 'Apellido', 'Nombre', 'Horario', 'Fecha', 'Entrada', 'Equipo', 'Salida', 'Equipo');

                foreach ($list['Dias'] as $key_fecha => $fecha) {
                    foreach ($fecha as $f_list) {
                        $Equipo_Inicio = $f_list['Equipo_Inicio'] == '' ? 'Web' : $f_list['Equipo_Inicio'];
                        $Equipo_Fin    = $f_list['Equipo_Fin'] == '' ? 'Web' : $f_list['Equipo_Fin'];

                        $u_name        = explode(",", $list['Nombre']);
                        $_apellido          = isset($u_name[0])     ?   $u_name[0]  :   ""  ;
                        $_nombre            = isset($u_name[1])     ?   $u_name[1]  :   ""  ;

                        $csv_excel_data[] = array($list['Legajo'], $_apellido, $_nombre, $list['Hora_Trabajo_Detalle'], $dias[date('w', strtotime($key_fecha))], $key_fecha, $f_list['Hora_Inicio'], $Equipo_Inicio, $f_list['Hora_Fin'], $Equipo_Fin);
                    }
                }
            }
        }
        break;

    case 'Payroll':             // POR DÍA ( la nomenclatura usada esta invertida con 'case Payroll_Por_Dia:' )
    case '25': // abduls

        // ** HORAS REGULARES ** // // ** HORAS EXTRA ** // // ** HORAS FERIADO ** //
        foreach ($o_Listado as $key_s => $o) {
            foreach ($o['Dias'] as $key_r => $dais_r) {
                $total_work_time                         = 0;
                $work_time                               = 0;
                $o_Listado[$key_s]['total_hits'][$key_r] = count($o['Dias'][$key_r]);

                foreach ($dais_r as $d) {

                    if ($d['Seg_Fecha_Hora_Fin'] != "" && $d['Seg_Fecha_Hora_Inicio'] != '') {

                        $time            = ($d['Seg_Fecha_Hora_Fin'] - $d['Seg_Fecha_Hora_Inicio']);
                        $total_work_time += $time;

                        $work_time = '';
                        if (strlen($d['Hora_Trabajo_Fin']) == 5) {
                            $work_time = (strtotime($d['Hora_Trabajo_Fin'] . ":00") - strtotime($d['Hora_Trabajo_Inicio'] . ":00"));
                        }
                        else {
                            $work_time = (strtotime($d['Hora_Trabajo_Fin']) - strtotime($d['Hora_Trabajo_Inicio']));
                        }

                    }

                    $o_Listado[$key_s]['total_work_time_min'][$key_r] = $total_work_time;
                    $o_Listado[$key_s]['work_time_min'][$key_r]       = $work_time;
                    $o_Listado[$key_s]['overtime'][$key_r]            = $work_time - $total_work_time;

                    //calculating over time
                    $f = count($o_Listado[$key_s]['Dias'][$key_r]) - 1;

                    $feriado   = Feriado_L::obtenerPorDiayPersona($key_r, $key_s);

                    if ($o_Listado[$key_s]['overtime'][$key_r] < 0) {

                        $O_Hours = round(abs(($o_Listado[$key_s]['overtime'][$key_r]) / 3600), 2);

                        $o_Listado[$key_s]['Dias'][$key_r][$f]['extratime'] = $O_Hours;

                        if (empty($feriado) )
                            $o_Listado[$key_s]['extratime'][$key_r] = $O_Hours;
                    }

                    $Hours = round(abs(($o_Listado[$key_s]['total_work_time_min'][$key_r]) / 3600), 2);

                    $o_Listado[$key_s]['Dias'][$key_r][$f]['totalTime'] = $Hours;
                    $o_Listado[$key_s]['working_time'][$key_r]          = $Hours;

                    if (!empty($feriado)) {
                        $o_Listado[$key_s]['feriado'][$key_r] = $Hours;
                    }
                    else if (empty($licencias) && empty($permisos) && empty($suspensions)) {
                        $o_Listado[$key_s]['regular'][$key_r] = $Hours;
                        if (isset($o_Listado[$key_s]['extratime'][$key_r])) {
                            $Hours                                = ($o_Listado[$key_s]['regular'][$key_r]) - ($o_Listado[$key_s]['extratime'][$key_r]);
                            $o_Listado[$key_s]['regular'][$key_r] = $Hours;
                        }
                    }

                }


            }
        }
        // ** LLEGADAS TARDE ** //
        foreach ($o_Listado as $perID => $item) {
            $o_Listado[$perID]['llegadas_tarde'] = 0;
            foreach ($item['Dias'] as $fecha => $dia) {
                if ($dia[0]['Llegada_Tarde'] == "Si")  //solo me fijo llegada tarde en el primer registro del dia
                    $o_Listado[$perID]['llegadas_tarde'] ++;
            }
        }
        // ** SALIDAS TEMPRANO ** //
        foreach ($o_Listado as $perID => $item) {
            $o_Listado[$perID]['salidas_temprano'] = 0;
            foreach ($item['Dias'] as $fecha => $dia) {
                $pares_de_logs_en_el_dia = count($dia);
                $log_final  = $dia[$pares_de_logs_en_el_dia - 1];

                if ($log_final['Salida_Temprano'] == "Si")  //solo me fijo llegada tarde en el primer registro del dia
                    $o_Listado[$perID]['salidas_temprano'] ++;
            }
        }
        // **  AUSENCIAS ** //
        if (isset($array_personas_a_controlar) && !empty($array_personas_a_controlar)) {
            $cantidad_dias      = 0;
            $cantidad_dias      = DateTimeHelper::diff_Fecha_En_Dias($_SESSION['filtro']['fechaD'], $_SESSION['filtro']['fechaH']);
            $o_hora_trabajo     = null;
            $t_fecha            = date('Y-m-d', strtotime($_SESSION['filtro']['fechaD'])); //arranco con el primer dia

            for ($i = 0; $i <= $cantidad_dias; $i++) {
                if ($i != 0) {
                    $t_fecha = DateTimeHelper::Sum_Dias($t_fecha, '1');
                }
                /*************      FECHA FUTURA    *****************/
                if (strtotime($t_fecha) > time()) {
                    continue;
                }

                /*************      PERSONAS A CONTROLAR    *****************/
                $dia_n = (date('w', strtotime($t_fecha)) + 1); // 1 (para domingo) hasta 7 (para sabado)
                foreach ($array_personas_a_controlar as $perID => $o_per) {
                    /*************      PERSONA PRESENTE      *****************/
                    if (array_key_exists($perID, $a_dias_presentes) && in_array($t_fecha, $a_dias_presentes[$perID])) {
                        continue;
                    }
                    /*************      POSIBLE AUSENTE      *****************/
                    else{
                        $tenia_que_venir     = false;
                        $hora_trabajo_inicio = '';
                        $hora_trabajo_fin    = '';
                        /*************      HORARIO      *****************/
                        switch ($o_per['per_Hor_Tipo']) {
                            case HORARIO_NORMAL:
                                $o_hora_trabajo = $a_Hora_Trabajo[$o_per['per_Hor_Id']];

                                if (!$o_hora_trabajo) continue;
                                $a_dias_del_horario  = $o_hora_trabajo->getArrayDias();
                                $hora_trabajo_inicio = date('H:i:s', strtotime($o_hora_trabajo->obtenerHoraInicio($t_fecha, 'H:i:s')));
                                $hora_trabajo_fin    = date('H:i:s', strtotime($o_hora_trabajo->obtenerHoraFin($t_fecha, 'H:i:s')));

                                if (date('H:i', $a_dias_del_horario[$dia_n][0]) != '00:00' && date('H:i', $a_dias_del_horario[$dia_n][1]) != '00:00') {//si las fechas de inicio y fin del dia son distintas a 00:00, tenia que venir.
                                    $tenia_que_venir = true;
                                }
                                break;
                            case HORARIO_FLEXIBLE:
                                $o_hora_trabajo = $a_Horarios_Flexibles[$o_per['per_Hor_Id']];
                                /* @var $o_hora_trabajo Horario_Flexible_O */
                                if (!$o_hora_trabajo) continue;
                                $a_horarios_de_trabajo_del_horario_flexible = $o_hora_trabajo->getArrayDias();
                                $horario_cercano                            = $o_hora_trabajo->getHorarioByClosestTime($t_fecha . date(' H:i:s'), false);
                                $hora_trabajo_inicio                        = date('H:i:s', strtotime($horario_cercano[0]));
                                $hora_trabajo_fin                           = date('H:i:s', strtotime($horario_cercano[1]));
                                foreach ($a_horarios_de_trabajo_del_horario_flexible as $a_dias_del_horario) {
                                    if (date('H:i', $a_dias_del_horario[$dia_n][0]) != '00:00' && date('H:i', $a_dias_del_horario[$dia_n][1]) != '00:00') {//si las fechas de inicio y fin del dia son distintas a 00:00, tenia que venir.
                                        $tenia_que_venir = true;
                                        break;//si ya tenia que venir, dejo de buscar.
                                    }
                                }
                                break;
                            case HORARIO_ROTATIVO:
                                $o_hora_trabajo = $a_Horarios_Rotativos[$o_per['per_Hor_Id']];
                                /* @var $o_hora_trabajo Horario_Rotativo_O */
                                if (!$o_hora_trabajo) continue;
                                $a_dias_del_horario  = $o_hora_trabajo->getArrayDias(true, $t_fecha);
                                $horario             = $o_hora_trabajo->getHorarioByDay($t_fecha);
                                $hora_trabajo_inicio = date('H:i:s', strtotime($horario[0]));
                                $hora_trabajo_fin    = date('H:i:s', strtotime($horario[1]));
                                if (date('H:i', $a_dias_del_horario[$dia_n][0]) != '00:00' && date('H:i', $a_dias_del_horario[$dia_n][1]) != '00:00') {//si las fechas de inicio y fin del dia son distintas a 00:00, tenia que venir.
                                    $tenia_que_venir = true;
                                }
                                break;
                            case HORARIO_MULTIPLE:
                                $o_hora_trabajo = $a_Horarios_Multiples[$o_per['per_Hor_Id']];
                                /* @var $o_hora_trabajo Horario_Multiple_O */
                                if (!$o_hora_trabajo) continue;
                                $a_horarios_de_trabajo_del_horario_multiple = $o_hora_trabajo->getArrayDias();
                                $horario                                    = $o_hora_trabajo->getHorarioByClosestTime($t_fecha . date(' H:i:s'));
                                $hora_trabajo_inicio                        = date('H:i:s', strtotime($horario[0]));
                                $hora_trabajo_fin                           = date('H:i:s', strtotime($horario[1]));
                                foreach ($a_horarios_de_trabajo_del_horario_multiple as $a_dias_del_horario) {
                                    if (date('H:i', $a_dias_del_horario[$dia_n][0]) != '00:00' && date('H:i', $a_dias_del_horario[$dia_n][1]) != '00:00') {//si las fechas de inicio y fin del dia son distintas a 00:00, tenia que venir.
                                        $tenia_que_venir = true;
                                        break;//si ya tenia que venir, dejo de buscar.
                                    }
                                }
                                break;
                            case 0:
                            default:
                                continue;
                                break;
                        }
                        /*************      MARGEN DE AUSENCIA      *****************/
                        if ($t_fecha == date('Y-m-d')) {
                            if (!((strtotime($hora_trabajo_inicio) + $margen_ausencia) < strtotime(date("H:i:s"))))
                                continue;
                        }
                        /*************      FERIADOS, LICECNIAS, PERMISOS, SUSPENSIONES      *****************/
                        if ($tenia_que_venir && $hora_trabajo_inicio != '00:00:00' && $hora_trabajo_fin != '00:00:00') {
                            $feriado     = Feriado_L::obtenerPorDiayPersona($t_fecha, $o_per['per_Id']);
                            $licencia    = Licencias_L::obtenerPorDiaCompletoyPersona($t_fecha, $o_per['per_Id']);
                            $permisos    = Permisos_L::obtenerPorDiaCompletoyPersona($t_fecha, $o_per['per_Id']);
                            $suspensions = Suspensions_L::obtenerPorDiaCompletoyPersona($t_fecha, $o_per['per_Id']);

                            if (!is_null($feriado) && !empty($feriado)) ;
                            elseif (!is_null($licencia) && !empty($licencia)) ;
                            elseif (!is_null($permisos) && !empty($permisos)) ;
                            elseif (!is_null($suspensions) && !empty($suspensions)) ;
                            else {
                                $o_Listado[$perID]['ausencias'][$t_fecha] = 1;
                                $o_Listado[$perID]['Hora_Trabajo_Inicio'][] = $hora_trabajo_inicio;
                                $o_Listado[$perID]['Hora_Trabajo_Fin'][]    = $hora_trabajo_fin;
                                $o_Listado[$perID]['Per_Id']                = $o_per['per_Hor_Id'];
                                if (!array_key_exists($perID, $o_Listado)) {
                                    $o_Listado[$perID]['Nombre']               = $o_per->getNombreCompletoINV();
                                    $o_Listado[$perID]['Legajo']               = $o_per['per_Legajo'];
                                    $o_Listado[$perID]['Imagen']               = $o_per['per_Imagen'];
                                    $o_Listado[$perID]['Hora_Trabajo']         = $o_per->getTextoDiasResumido($dias_red_2);
                                    $o_Listado[$perID]['Hora_Trabajo_Id']      = $o_per->getHorId();
                                    $o_Listado[$perID]['Hora_Trabajo_Tipo']    = $o_per->getHorTipo();
                                    $o_Listado[$perID]['Hora_Trabajo_Detalle'] = $o_per->getDetalle();
                                }
                            }
                        }
                    }
                }
            }
        }
        /*
        // ** LICENCIAS ** // // ** SUSPENSIONES ** // // ** PERMISOS ** //
        if (isset($array_personas_a_controlar) && !empty($array_personas_a_controlar)) {
            $cantidad_dias      = 0;
            $cantidad_dias      = DateTimeHelper::diff_Fecha_En_Dias($_SESSION['filtro']['fechaD'], $_SESSION['filtro']['fechaH']);
            $t_fecha            = date('Y-m-d', strtotime($_SESSION['filtro']['fechaD'])); //arranco con el primer dia
            for ($i = 0; $i <= $cantidad_dias; $i++) {
                if($i > 0){
                    $t_fecha = DateTimeHelper::Sum_Dias($t_fecha, '1');
                }
                if (strtotime($t_fecha) > time()) {
                    continue;  //Para que no marque como ausencia los dias futuros!
                }

                foreach ($array_personas_a_controlar as $perID => $o_persona) {
                    $licencia   = Licencias_L::obtenerPorDiaCompletoyPersona($t_fecha, $perID);
                    if (!is_null($licencia)     && !empty($licencia))   {
                        $o_Listado[$perID]['licencias_r'][$t_fecha] = 1;
                    }
                    $suspension = Suspensions_L::obtenerPorDiaCompletoyPersona($t_fecha, $perID);
                    if (!is_null($suspension)   && !empty($suspension)) {
                        $o_Listado[$perID]['suspensions_r'][$t_fecha] = 1;
                    }

                    $o_Listado[$perID]['Nombre']               = $o_persona->getNombreCompletoINV();
                    $o_Listado[$perID]['Per_Id']               = $o_persona->getId();
                    $o_Listado[$perID]['Legajo']               = $o_persona->getLegajo();
                }
            }
           }
     */
        // ** EXCEL CSV ARRAY ** //
        if ($o_Listado) {
            $csv_excel_data[] = array('Legajo', 'Apellido', 'Nombre', 'Horario', 'Dia', 'Fecha', 'Horas Regulares', 'Horas Extra', 'Horas Feriado', 'Licencias', 'Permisos', 'Llegadas Tarde','Suspensiones','Ausencias', 'Total Diario');
            foreach ($o_Listado as $list) {

                if (!$list['Nombre'])
                    continue;

                $list['Hora_Trabajo_Detalle'] = iconv("UTF-8", "ISO-8859-1", $list['Hora_Trabajo_Detalle']);
                $list['Nombre']               = iconv("UTF-8", "ISO-8859-1", $list['Nombre']);


                foreach ($list['Dias'] as $key_fecha => $f_list) {
                    $regular = 0;
                    if (isset($list['regular'][$key_fecha])) {
                        $regular = $list['regular'][$key_fecha];
                    }

                    $extra_time = 0;
                    if (isset($list['extratime'][$key_fecha])) {
                        $extra_time = $list['extratime'][$key_fecha];
                    }

                    $feriado_r =0;
                    if (isset($list['feriado_r'][$key_fecha])) {
                        $feriado_r = $list['feriado_r'][$key_fecha];
                    }

                    $licencias_r = 0;
                    if (isset($list['licencias_r'][$key_fecha])) {
                        $licencias_r = $list['licencias_r'][$key_fecha];
                    }

                    $total_work = 0;
                    if (isset($list['working_time'][$key_fecha])) {
                        $total_work = $list['working_time'][$key_fecha];
                    }


                    $total_permisos_r = 0;
                    if (isset($list['permisos_r'][$key_fecha]) && $list['permisos_r'][$key_fecha] != '') {
                        $total_permisos_r = $list['permisos_r'][$key_fecha];
                    }

                    $total_suspensiones_r = 0;
                    if (isset($list['suspensiones_r'][$key_fecha])) {
                        $total_suspensiones_r = $list['suspensiones_r'][$key_fecha];
                    }

                    $total_llegadas_tarde_r = 0;
                    if (isset($list['llegadas_tarde_r'][$key_fecha])) {
                        $total_llegadas_tarde_r = $list['llegadas_tarde_r'][$key_fecha];
                    }

                    $total_ausencias_r = 0;
                    if (isset($list['ausencias_r'][$key_fecha])) {
                        $total_ausencias_r = $list['ausencias_r'][$key_fecha];
                    }

                    $u_name           = explode(",", $list['Nombre']);
                    $_apellido          = isset($u_name[0])     ?   $u_name[0]  :   ""  ;
                    $_nombre            = isset($u_name[1])     ?   $u_name[1]  :   ""  ;

                    $csv_excel_data[] = array($list['Legajo'], $_apellido, $_nombre, $list['Hora_Trabajo_Detalle'], $dias[date('w', strtotime($key_fecha))], $key_fecha, $regular, $extra_time, $feriado_r, $licencias_r, $total_permisos_r, $total_llegadas_tarde_r, $total_suspensiones_r, $total_ausencias_r, $total_work);
                }

            }
        }
        break;

    case 'Payroll_Por_Dia':     // TOTALES ( la nomenclatura usada esta invertida con 'case Payroll:' )
    case '26':

        // ** HORAS REGULARES ** // // ** HORAS EXTRA ** // // ** HORAS FERIADO ** //
        foreach ($o_Listado as $perID => $item) {

            foreach ($item['Dias'] as $key_r => $dais_r) {
                $total_work_time                         = 0;
                $work_time                               = 0;

                foreach ($dais_r as $d) {

                    if ($d['Seg_Fecha_Hora_Fin'] != "" && $d['Seg_Fecha_Hora_Inicio'] != '') {

                        // HORAS TRABAJADAS
                        $time            = ($d['Seg_Fecha_Hora_Fin'] - $d['Seg_Fecha_Hora_Inicio']);
                        $total_work_time += $time;

                        // HORAS QUE DEBE TRABAJAR
                        $work_time = '';
                        $work_time = (strtotime($d['Hora_Trabajo_Fin']) - strtotime($d['Hora_Trabajo_Inicio']));

                    }

                    // HORAS TRABAJADAS
                    $o_Listado[$perID]['total_work_time_min'][$key_r] = $total_work_time;
                    // HORAS QUE DEBE TRABAJAR
                    $o_Listado[$perID]['work_time_min'][$key_r]       = $work_time;
                    // HORAS EXTRA TRABAJADAS
                    $o_Listado[$perID]['overtime'][$key_r]            = $work_time - $total_work_time;

                    // ULTIMO LOG DEL DÍA
                    $f = count($o_Listado[$perID]['Dias'][$key_r]) - 1;

                    // FERIADO EN EL DÍA
                    $feriado   = Feriado_L::obtenerPorDiayPersona($key_r, $perID);
                    $licencia    = Licencias_L::obtenerPorDiaCompletoyPersona($key_r, $perID);
                    $permisos    = Permisos_L::obtenerPorDiaCompletoyPersona($key_r, $perID);
                    $suspensions = Suspensions_L::obtenerPorDiaCompletoyPersona($key_r, $perID);

                    // SI HAY TIEMPO EXTRA
                    if ($o_Listado[$perID]['overtime'][$key_r] < 0) {

                        // CALCULO HORAS EXTRA: 1-CALCULO.HORAS 2-CAMBIO.SIGNO.POSITIVO 3-REDONDEO.DOS.CIFRAS
                        $extra_Hours = round(abs(($o_Listado[$perID]['overtime'][$key_r]) / 3600), 2);

                        // ULTIMO LOG: ASIGNO TIEMPO EXTRA
                        $o_Listado[$perID]['Dias'][$key_r][$f]['extratime'] = $extra_Hours;


                        if (empty($feriado) )
                            $o_Listado[$perID]['extratime'][$key_r] = $extra_Hours;
                    }

                    $Hours = round(abs(($o_Listado[$perID]['total_work_time_min'][$key_r]) / 3600), 2);

                    $o_Listado[$perID]['Dias'][$key_r][$f]['totalTime'] = $Hours;
                    $o_Listado[$perID]['working_time'][$key_r]          = $Hours;

                    // SI HAY FERIADO
                    if (!empty($feriado)) {
                        $o_Listado[$perID]['feriado'][$key_r] = $Hours;
                    }
                    // SI NO HAY LICENCIA, PERMISO O SUSPENSION
                    else if (empty($licencias) && empty($permisos) && empty($suspensions)) {

                        $o_Listado[$perID]['regular'][$key_r] = $Hours;

                        if (isset($o_Listado[$perID]['extratime'][$key_r])) {
                            $Hours = ($o_Listado[$perID]['regular'][$key_r]) - ($o_Listado[$perID]['extratime'][$key_r]);
                            $o_Listado[$perID]['regular'][$key_r] = $Hours;
                        }
                    }

                }


            }
        }


        // ** LLEGADAS TARDE ** //
        foreach ($o_Listado as $perID => $item) {
            $o_Listado[$perID]['llegadas_tarde'] = 0;
            foreach ($item['Dias'] as $fecha => $dia) {
                if ($dia[0]['Llegada_Tarde'] == "Si" && $dia[0]['Hora_Trabajo_Inicio'] != "00:00:00" && $dia[0]['Hora_Trabajo_Fin'] != "00:00:00")  //solo me fijo llegada tarde en el primer registro del dia
                    $o_Listado[$perID]['llegadas_tarde'] ++;
            }
        }

        // ** SALIDAS TEMPRANO ** //
        foreach ($o_Listado as $perID => $item) {
            $o_Listado[$perID]['salidas_temprano'] = 0;
            foreach ($item['Dias'] as $fecha => $dia) {
                $pares_de_logs_en_el_dia = count($dia);
                $log_final  = $dia[$pares_de_logs_en_el_dia - 1];

                if ($log_final['Salida_Temprano'] == "Si" && $log_final['Hora_Trabajo_Inicio'] != "00:00:00" && $log_final['Hora_Trabajo_Fin'] != "00:00:00")  //solo me fijo llegada tarde en el primer registro del dia
                    $o_Listado[$perID]['salidas_temprano'] ++;
            }
        }

        // **  AUSENCIAS ** //
        /*************      $o_Listado_Ausencias   *****************/
        if (isset($array_personas_a_controlar) && !empty($array_personas_a_controlar)) {
            $cantidad_dias      = 0;
            $cantidad_dias      = DateTimeHelper::diff_Fecha_En_Dias($_SESSION['filtro']['fechaD'], $_SESSION['filtro']['fechaH']);
            $o_hora_trabajo     = null;
            $t_fecha            = date('Y-m-d', strtotime($_SESSION['filtro']['fechaD'])); //arranco con el primer dia

            for ($i = 0; $i <= $cantidad_dias; $i++) {
                if ($i != 0) { //le voy sumando un dia a la fecha para ir controlando dia x dia.
                    $t_fecha = DateTimeHelper::Sum_Dias($t_fecha, '1');
                }
                /*************      FECHA FUTURA    *****************/
                if (strtotime($t_fecha) > time()) {
                    continue;  //Para que no marque como ausencia los dias futuros!
                }
                /*************      PERSONAS A CONTROLAR    *****************/
                $dia_n = (date('w', strtotime($t_fecha)) + 1); // 1 (para domingo) hasta 7 (para sabado)

                foreach ($array_personas_a_controlar as $perID => $o_persona) {

                    /*************      PERSONA PRESENTE      *****************/
                    if (array_key_exists($perID, $a_dias_presentes) && in_array($t_fecha, $a_dias_presentes[$perID])) {
                        continue;
                    }

                    /*************      PERSONA AUSENTE      *****************/
                    else {
                        $tenia_que_venir     = false;
                        $hora_trabajo_inicio = '';
                        $hora_trabajo_fin    = '';
                        if (!$o_persona) continue;
                        switch ($o_persona['per_Hor_Tipo']) {
                            case HORARIO_NORMAL:
                                $o_hora_trabajo = $a_Hora_Trabajo[$o_persona['per_Hor_Id']];

                                if (!$o_hora_trabajo) continue;
                                $a_dias_del_horario  = $o_hora_trabajo->getArrayDias();
                                $hora_trabajo_inicio = date('H:i:s', strtotime($o_hora_trabajo->obtenerHoraInicio($t_fecha, 'H:i:s')));
                                $hora_trabajo_fin    = date('H:i:s', strtotime($o_hora_trabajo->obtenerHoraFin($t_fecha, 'H:i:s')));

                                if (date('H:i', $a_dias_del_horario[$dia_n][0]) != '00:00' && date('H:i', $a_dias_del_horario[$dia_n][1]) != '00:00') {//si las fechas de inicio y fin del dia son distintas a 00:00, tenia que venir.
                                    $tenia_que_venir = true;
                                }
                                break;
                            case HORARIO_FLEXIBLE:
                                $o_hora_trabajo = $a_Horarios_Flexibles[$o_persona['per_Hor_Id']];
                                /* @var $o_hora_trabajo Horario_Flexible_O */
                                if (!$o_hora_trabajo) continue;
                                $a_horarios_de_trabajo_del_horario_flexible = $o_hora_trabajo->getArrayDias();
                                $horario_cercano                            = $o_hora_trabajo->getHorarioByClosestTime($t_fecha . date(' H:i:s'), false);
                                $hora_trabajo_inicio                        = date('H:i:s', strtotime($horario_cercano[0]));
                                $hora_trabajo_fin                           = date('H:i:s', strtotime($horario_cercano[1]));
                                foreach ($a_horarios_de_trabajo_del_horario_flexible as $a_dias_del_horario) {
                                    if (date('H:i', $a_dias_del_horario[$dia_n][0]) != '00:00' && date('H:i', $a_dias_del_horario[$dia_n][1]) != '00:00') {//si las fechas de inicio y fin del dia son distintas a 00:00, tenia que venir.
                                        $tenia_que_venir = true;
                                        break;//si ya tenia que venir, dejo de buscar.
                                    }
                                }
                                break;
                            case HORARIO_ROTATIVO:
                                $o_hora_trabajo = $a_Horarios_Rotativos[$o_persona['per_Hor_Id']];
                                /* @var $o_hora_trabajo Horario_Rotativo_O */
                                if (!$o_hora_trabajo) continue;
                                $a_dias_del_horario  = $o_hora_trabajo->getArrayDias(true, $t_fecha);
                                $horario             = $o_hora_trabajo->getHorarioByDay($t_fecha);
                                $hora_trabajo_inicio = date('H:i:s', strtotime($horario[0]));
                                $hora_trabajo_fin    = date('H:i:s', strtotime($horario[1]));
                                if (date('H:i', $a_dias_del_horario[$dia_n][0]) != '00:00' && date('H:i', $a_dias_del_horario[$dia_n][1]) != '00:00') {//si las fechas de inicio y fin del dia son distintas a 00:00, tenia que venir.
                                    $tenia_que_venir = true;
                                }
                                break;
                            case HORARIO_MULTIPLE:
                                $o_hora_trabajo = $a_Horarios_Multiples[$o_persona['per_Hor_Id']];
                                /* @var $o_hora_trabajo Horario_Multiple_O */
                                if (!$o_hora_trabajo) continue;
                                $a_horarios_de_trabajo_del_horario_multiple = $o_hora_trabajo->getArrayDias();
                                $horario                                    = $o_hora_trabajo->getHorarioByClosestTime($t_fecha . date(' H:i:s'));
                                $hora_trabajo_inicio                        = date('H:i:s', strtotime($horario[0]));
                                $hora_trabajo_fin                           = date('H:i:s', strtotime($horario[1]));
                                foreach ($a_horarios_de_trabajo_del_horario_multiple as $a_dias_del_horario) {
                                    if (date('H:i', $a_dias_del_horario[$dia_n][0]) != '00:00' && date('H:i', $a_dias_del_horario[$dia_n][1]) != '00:00') {//si las fechas de inicio y fin del dia son distintas a 00:00, tenia que venir.
                                        $tenia_que_venir = true;
                                        break;//si ya tenia que venir, dejo de buscar.
                                    }
                                }
                                break;
                            case 0:
                            default:
                                //si la persona no tiene horario, no la controlo. 1548936000
                                continue;
                                break;
                        }
                        /** si tenia que venir y no vino, reviso feriados, licencias etc etc*/
                        if ($t_fecha == date('Y-m-d')) {
                            if (!((strtotime($hora_trabajo_inicio) + $margen_ausencia) < strtotime(date("H:i:s"))))
                                continue;
                        }

                        if ($tenia_que_venir && $hora_trabajo_inicio != '00:00:00' && $hora_trabajo_fin != '00:00:00') {

                            if (!array_key_exists($perID, $o_Listado)) {

                                $o_Listado[$perID] =      array();
                                $o_Listado[$perID] +=     $array_personas_a_controlar[$perID];
                            }

                            $o_Listado[$perID]['ausencias'][]                         = $t_fecha;
                            $o_Listado[$perID]['Hora_Trabajo']                        = $o_hora_trabajo->getTextoDiasResumido($dias_red_2);
                            $o_Listado[$perID]['Hora_Trabajo_Detalle']                = $o_hora_trabajo->getDetalle();
                        }
                    }
                }
            }
        }


        // ** TOTALES ** //
        foreach ($array_personas_a_controlar as $perID => $row) {

            $new_List[$perID]['per_Nombre']         = $row['per_Nombre'];
            $new_List[$perID]['per_Apellido']       = $row['per_Apellido'];
            $new_List[$perID]['per_Imagen']         = $row['per_Imagen'];
            $new_List[$perID]['per_Id']             = $row['per_Id'];
            $new_List[$perID]['per_Legajo']         = $row['per_Legajo'];
            $new_List[$perID]['Desde']              = date("Y-m-d", strtotime($_SESSION['filtro']['fechaD']));
            $new_List[$perID]['Hasta']              = date("Y-m-d", strtotime($_SESSION['filtro']['fechaH']));

            $new_List[$perID]['regular'] = number_format(0.00, 2);

            if (isset($o_Listado[$perID]['regular'])) {
                $new_List[$perID]['regular'] = number_format(array_sum($o_Listado[$perID]['regular']), 2);
            }

            $new_List[$perID]['extratime'] = number_format(0.00, 2);
            if (isset($o_Listado[$perID]['extratime'])) {
                $new_List[$perID]['extratime'] = number_format(array_sum($o_Listado[$perID]['extratime']), 2);
            }

            $new_List[$perID]['feriado'] = number_format(0.00, 2);
            if (isset($o_Listado[$perID]['feriado'])) {
                $new_List[$perID]['feriado'] = number_format(array_sum($o_Listado[$perID]['feriado']), 2);
            }

            $new_List[$perID]['ausencias'] = 0;
            if (isset($o_Listado[$perID]['ausencias'])) {
                $new_List[$perID]['ausencias'] = count($o_Listado[$perID]['ausencias']);
            }

            $new_List[$perID]['llegadas_tarde'] = 0;
            if (isset($o_Listado[$perID]['llegadas_tarde'])) {
                $new_List[$perID]['llegadas_tarde'] = $o_Listado[$perID]['llegadas_tarde'];
            }

            $new_List[$perID]['salidas_temprano'] = 0;
            if (isset($o_Listado[$perID]['salidas_temprano'])) {
                $new_List[$perID]['salidas_temprano'] = $o_Listado[$perID]['salidas_temprano'];
            }
        }
        unset($array_personas_a_controlar);
        unset($o_Listado);

        // ** EXCEL CSV ARRAY ** //
        $csv_excel_data[] = array('Legajo', 'Apellido', 'Nombre', 'Desde', 'Hasta', 'Horas Regulares', 'Horas Extras', 'Horas Feriados','Ausencias', 'Llegadas Tarde', 'Salidas Temprano');//, 'Licencias', 'Suspensiones');

        if ($new_List) {
            foreach ($new_List as $rows) {

                $csv_excel_data[] = array(
                    $rows['per_Legajo'],
                    $rows['per_Apellido'],
                    $rows['per_Nombre'],
                    $rows['Desde'],
                    $rows['Hasta'],
                    $rows['regular'],
                    $rows['extratime'],
                    $rows['feriado'],
                    $rows['ausencias'],
                    $rows['llegadas_tarde'],
                    $rows['salidas_temprano']);
            }
        }


        break;

    case 'Widget_en_Vivo':


        break;
    default:
        break;
}



