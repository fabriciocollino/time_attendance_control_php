<?php

/*
 * Usuario (Object)
 */

class Reporte_O
{

    private $_tipo;
    /*
    private $_persona;
    private $_grupo;
    */
    private $_filtro_persona;
    private $_filtro_intervalo;

    private $_fecha_desde;
    private $_fecha_hasta;
    private $_css;
    private $_titulo;
    private $_nombre_archivo;

    public function __construct($p_tipo = 0, $p_intervalo = "", $p_filtro = array()){//$Filtro_Intervalo = "", $p_persona = 0, $p_grupo = 0 ) {

        $this->_tipo                    = $p_tipo;
        $this->_filtro_intervalo        = $p_intervalo;



        if($p_filtro['Persona'] == 0)
            $p_filtro['Persona'] = "TodasLasPersonas";

        if($p_filtro['Grupo'] != 0)
            $p_filtro['Persona']  = 'SelectRol';

        $this->_filtro_persona          = $p_filtro;



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
                    border-bottom: 0.5px solid #D8D8D8;
                    width: 10%;
                    height: 40px;
             
                }';


        $this->set_css($css);

        if(is_array($p_intervalo)){
            $this->set_fecha_hora_intervalo($p_intervalo['desde'],$p_intervalo['hasta']);
        }
        else{
            $this->set_filtro_intervalo($p_intervalo);
        }
        $this->set_titulo_nombre($p_tipo);

    }


    public function set_fecha_hora_intervalo($fecha_desde,$fecha_hasta){
        $this->_fecha_desde = $fecha_desde;
        $this->_fecha_hasta = $fecha_hasta;
    }


    public function set_filtro_intervalo($T_Intervalo){

        if ($T_Intervalo == '') {
            return false;
        }

        $fecha_desde = date('Y-m-d H:i:s', strtotime('today 00:00:00'));
        $fecha_hasta = date('Y-m-d H:i:s', strtotime('today 23:59:59'));

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


        $this->_fecha_desde = $fecha_desde;
        $this->_fecha_hasta = $fecha_hasta;

        $_SESSION['filtro']['fechaD'] = $fecha_desde;
        $_SESSION['filtro']['fechaH'] = $fecha_hasta;
        $_SESSION['filtro']['intervalo'] = $T_Intervalo;
    }
    public function set_titulo_nombre($p_tipo){

        /* VARIABLES */
        switch ($p_tipo) {

            case 'Registros':
            case 'Modelo_Importar_Registros':
            case '39':
                $this->_titulo = _('Registros por Persona');
                $this->_nombre_archivo = _('Reporte_Registros');
                break;

            case 'Marcaciones':
            case '40':
                $this->_titulo = _('Marcaciones por Persona');
                $this->_nombre_archivo = _('Reporte_Marcaciones');
                break;

            case 'Jornadas':
            case '41':
                $this->_titulo = _('Jornadas por Persona');
                $this->_nombre_archivo = _('Reporte_Jornadas');
                break;

            case 'Intervalo':
            case '42':
                $this->_titulo = _('Intervalo por Persona');
                $this->_nombre_archivo = _('Reporte_Intervalo');
                break;

            case 'Llegadas_Tarde':
            case '43':
                $this->_titulo = _('Llegadas Tarde por Persona');
                $this->_nombre_archivo = _('Reporte_Llegadas_Tarde');
                break;

            case 'Salidas_Temprano':
            case '44':
                $this->_titulo = _('Salidas Temprano por Persona');
                $this->_nombre_archivo = _('Reporte_Salidas_Temprano');
                break;

            case 'Listado_Asistencias':
            case '45':
                $this->_titulo = _('Asistencias por Persona');
                $this->_nombre_archivo = _('Reporte_Asistencias');
                break;

            case 'Listado_Ausencias':
            case '46':
                $this->_titulo = _('Ausencias por Persona');
                $this->_nombre_archivo = _('Reporte_Ausencias');
                break;


            case 'Personas':
            case 'Modelo_Importar_Personas':
            case '50':
                $this->_titulo = _('Personas');
                $this->_nombre_archivo = _('Reporte_Personas');
                break;

            case 'Grupos':
            case '51':
                $this->_titulo = _('Grupos');
                $this->_nombre_archivo = _('Reporte_Grupos');
                break;


            case 'Horarios_Trabajo':
            case '61':
                $this->_titulo = _('Horarios de Trabajo');
                $this->_nombre_archivo = _('Reporte_Horarios_Trabajo');
                break;

            case 'Horarios_Flexibles':
            case '62':
                $this->_titulo = _('Horarios Flexibles');
                $this->_nombre_archivo = _('Reporte_Horarios_Flexibles');
                break;

            case 'Horarios_Multiples':
            case '63':
                $this->_titulo = _('Horarios Múltiples');
                $this->_nombre_archivo = _('Reporte_Horarios_Multiples');
                break;

            case 'Horarios_Rotativos':
            case '64':
                $this->_titulo = _('Horarios Rotativos');
                $this->_nombre_archivo = _('Reporte_Horarios_Rotativos');
                break;


            case 'Suspensiones':
            case '71':
                $this->_titulo = _('Suspensiones por Persona');
                $this->_nombre_archivo = _('Reporte_Suspensiones');
                break;

            case 'Licencias':
            case '72':
                $this->_titulo = _('Licencias por Persona');
                $this->_nombre_archivo = _('Reporte_Licencias');
                break;

            case 'Feriados':
            case '73':
                $this->_titulo = _('Feriados por Persona');
                $this->_nombre_archivo = _('Reporte_Feriados');
                break;


            case 'Alertas':
            case '81':
                $this->_titulo = _('Alertas');
                $this->_nombre_archivo = _('Reporte_Alertas');
                break;

            case 'Reportes_Automaticos':
            case '82':
                $this->_titulo = _('Reportes Automáticos');
                $this->_nombre_archivo = _('Reporte_Reportes_Automaticos');
                break;


            default:
                $this->_titulo = _('Reportes');
                $this->_nombre_archivo = _('Reporte');
                break;
        }

        /*
        $fecha_desde            = date('d-m-Y',strtotime($this->_fecha_desde));
        $fecha_hasta            = date('d-m-Y',strtotime($this->_fecha_hasta));
        */
        $this->_nombre_archivo  .=  '_' .  date('d_m_Y_H_i_s');


    }
    public function set_css($p_css){

        $this->_css = $p_css;

    }

    public function generar_adjunto($a_data = array(), $csv_excel_data = array(), $guardar_descargar = "Guardar", $Archivo_Tipo ='csv') {

        $url_adjunto = '';

        /* REPORTE ADJUNTO */
        switch ($Archivo_Tipo){
            /* PDF */
            case 'pdf':
                $url_adjunto = $this->generar_pdf($a_data, $csv_excel_data, $guardar_descargar);
                break;
            /* CSV */
            case 'csv':
                $url_adjunto = $this->generar_csv($csv_excel_data, $guardar_descargar);
                break;
            /* EXCEL */
            case 'xls':
                $url_adjunto = $this->generar_excel($csv_excel_data, $guardar_descargar);
                break;
        }

        return $url_adjunto;
    }



    public function generar_pdf($a_data = array(), $csv_excel_data = array(), $guardar_descargar = "Guardar") {

        /* VARIABLES */
        $T_Titulo               = $this->_titulo;
        $T_Nombre_Archivo       = $this->_nombre_archivo.".pdf";
        $fecha_desde            = date('d-m-Y',strtotime($this->_fecha_desde));
        $fecha_hasta            = date('d-m-Y',strtotime($this->_fecha_hasta));
        $html                   = '';
        $T_Tipo                 = $this->_tipo;
        $img_logo               = "https://storage.googleapis.com/enpunto/img/logo_flat.png";

        /* VARIABLES */
        $margen_izquierdo       = 10;
        $margen_derecho         = 10;
        $margen_superior        = 30;
        $margen_inferior        = 25;

        /* VARIABLES */
        $dia_actual             = date('d/m/Y');
        $hora_actual            = date('H:y:s');


        /* VARIABLES */
        $_headerhtml            = ' <table style="text-align: left;width: 100%; height: 20%;border-bottom: 0.5px solid #CCCCCC;"  >
                        <tr>
                            <td align="left" style="width: 25%; position:relative; text-align:center;border-bottom: 0.5px solid #CCCCCC;" >
                                <img src="' . $img_logo . '" width="150" />
                            </td>
                            <td align="left" style="width:75%;  font-style: normal;font-size: 12px;border-bottom: 0.5px solid #CCCCCC;">'
                                    . '<b>Reportes enPunto</b>'
                                    . '<br>'
                                    . $T_Titulo . "<br>" . _("Generado el ").$dia_actual._(" a las ").$hora_actual
                                    . "<br>" . _(" Desde el ").$fecha_desde._(" hasta el ").$fecha_hasta._(" inclusive") . '
                            </td>
                        </tr>
                    </table>';
        $_footerhtml            = ' <table width="100%">
                        <tr>
                            <td width="30%" style="border-bottom: 0px solid;">
                            </td>
                            <td width="40%" align="center" style="border-bottom: 0px solid;">
                                '.'enPunto'.'
                            </td>
                            <td width="30%" style="text-align: right;border-bottom: 0px solid;">
                                {PAGENO}
                            </td>
                        </tr>
                    </table>';


        /* HTML REPORTE */
        if(count($csv_excel_data) > 1) {
            switch ($T_Tipo) {

                case 'Marcaciones':
                case '40':
                    if (!is_null($a_data)) {
                        $html .= '
                    <table style="width: 100%;">

                        <tr>
                            <td><b>Legajo</b></td>
                            <td><b>Nombre</b></td>

                            <td><b>Fecha</b></td>
                            <td><b>Horario</b></td>

                            <td><b>Entrada</b></td>
                            <td><b>Salida</b></td>

                            <td><b>Horas</b></td>
                        </tr>';

                        foreach ($a_data as $per_Id => $item) {

                            $log1 = $a_data[$per_Id]['logs'][0];
                            $html .= '
                        <tr>
                            <!-- LEGAJO -->
                            <td>
                                ' . $item['per_Legajo'] . '
                            </td>

                            <!-- NOMBRE -->
                            <td>
                                ' . mb_convert_case($item['per_Apellido'] . ", " . $item['per_Nombre'], MB_CASE_TITLE, "UTF-8") . '
                            </td>

                            <!-- FECHA -->
                            <td>
                                ' . date("d-m-Y", strtotime($log1['Fecha_Trabajo_Inicio'])) . '
                            </td>

                             <!-- HORARIO -->
                            <td>
                                ' . $log1['Hora_Trabajo_Inicio'] . "-" . $log1['Hora_Trabajo_Fin'] . '
                            </td>

                            <!-- ENTRADA -->
                            <td>
                                ' . $log1['Hora_Inicio'] . ' ' . $log1['Equipo_Inicio'] . '
                            </td>

                             <!-- SALIDA -->
                            <td>
                                ' . $log1['Hora_Fin'] . ' ' . $log1['Equipo_Fin'] . '
                            </td>

                            <!-- TOTAL INTERVALO -->
                            <td>
                                ' . $log1['Total_Intervalo'] . '
                            </td>
                        </tr>';


                            array_shift($item['logs']);
                            foreach ($item['logs'] as $key_log => $log) {
                                $html .= '
                            <tr>
                                <!-- LEGAJO -->
                                <td>
                                    <br>
                                </td>

                                <!-- NOMBRE -->
                                <td>
                                     <br>
                                </td>

                                <!-- FECHA -->
                                <td>
                                    ' . date("d-m-Y", strtotime($log['Fecha_Trabajo_Inicio'])) . '
                                </td>

                                 <!-- HORARIO -->
                                <td>
                                    ' . $log['Hora_Trabajo_Inicio'] . "-" . $log['Hora_Trabajo_Fin'] . '
                                </td>

                                <!-- ENTRADA -->
                                <td>
                                    ' . $log['Hora_Inicio'] . ' ' . $log['Equipo_Inicio'] . '
                                </td>

                                 <!-- SALIDA -->
                                <td>
                                    ' . $log['Hora_Fin'] . ' ' . $log['Equipo_Fin'] . '
                                </td>

                                 <!-- TOTAL INTERVALO -->
                                <td>
                                    ' . $log['Total_Intervalo'] . '
                                </td>
                            </tr>';
                            }
                        }

                        $html .= '
                    </table>';
                    }
                    break;

                case 'Jornadas':
                case '41':
                    if (!is_null($a_data)) {
                        $html .= '
                <table style="width: 100%;">
                    <tr>
                        <td><b>Legajo</b></td>
                        <td><b>Nombre</b></td>

                        <td><b>Fecha</b></td>
                        <td><b>Horario</b></td>

                        <td><b>Ausencia</b></td>
                        <td><b>Llegada Tarde</b></td>
                        <td><b>Salida Temprano</b></td>

                        <td><b>Horas Regulares</b></td>
                        <td><b>Horas Extra</b></td>
                        <td><b>Horas Feriado</b></td>
                    </tr>';

                        foreach ($a_data as $per_Id => $item) {

                            $log1 = $a_data[$per_Id]['jornadas'][0];
                            $html .= '
                        <tr>
                            <!-- LEGAJO -->
                            <td>
                               ' . $item['per_Legajo'] . '
                            </td>

                            <!-- NOMBRE -->
                            <td>
                                ' . mb_convert_case($item['per_Apellido'] . ", " . $item['per_Nombre'], MB_CASE_TITLE, "UTF-8") . '
                            </td>

                            <!-- FECHA -->
                            <td>
                                ' . date("d-m-Y", strtotime($log1['Fecha_Trabajo_Inicio'])) . '
                            </td>

                             <!-- HORARIO -->
                            <td>
                                ' . $log1['Hora_Trabajo_Inicio'] . "-" . $log1['Hora_Trabajo_Fin'] . '
                            </td>

                            <!-- AUSENCIAS -->
                            <td>
                                 ' . $log1['Ausencia'] . '
                            </td>

                            <!-- LLEGADAS TARDE -->
                            <td>
                                ' . $log1['Llegada_Tarde'] . '
                            </td>

                            <!-- SALIDAS TEMPRANO -->
                            <td>
                              ' . $log1['Salida_Temprano'] . '
                            </td>

                             <!-- HORAS REGULARES -->
                            <td>
                                ' . $log1['Horas_Regulares'] . '
                            </td>

                             <!-- HORAS EXTRA -->
                            <td>
                                ' . $log1['Horas_Extra'] . '
                            </td>

                             <!-- HORAS FERIADO -->
                            <td>
                                ' . $log1['Horas_Feriado'] . '
                            </td>
                        </tr>';


                            array_shift($item['jornadas']);
                            foreach ($item['jornadas'] as $key_log => $log) {
                                $html .= '
                            <tr>
                                <!-- LEGAJO -->
                                <td>
                                    <br>
                                </td>

                                <!-- NOMBRE -->
                                <td>
                                     <br>
                                </td>

                                <!-- FECHA -->
                                <td>
                                    ' . date("d-m-Y", strtotime($log['Fecha_Trabajo_Inicio'])) . '
                                </td>

                                 <!-- HORARIO -->
                                <td>
                                    ' . $log['Hora_Trabajo_Inicio'] . "-" . $log['Hora_Trabajo_Fin'] . '
                                </td>

                                <!-- AUSENCIAS -->
                                <td>
                                     ' . $log['Ausencia'] . '
                                </td>

                                <!-- LLEGADAS TARDE -->
                                <td>
                                    ' . $log['Llegada_Tarde'] . '
                                </td>

                                <!-- SALIDAS TEMPRANO -->
                                <td>
                                    ' . $log['Salida_Temprano'] . '
                                </td>

                                 <!-- HORAS REGULARES -->
                                <td>
                                    ' . $log['Horas_Regulares'] . '
                                </td>

                                 <!-- HORAS EXTRA -->
                                <td>
                                    ' . $log['Horas_Extra'] . '
                                </td>

                                 <!-- HORAS FERIADO -->
                                <td>
                                    ' . $log['Horas_Feriado'] . '
                                </td>
                            </tr>';
                            }
                        }

                        $html .= '
                    </table>';
                    }
                    break;

                case 'Intervalo':
                case '42':
                    if (!is_null($a_data)) {
                        $html .= '
                    <table style="width: 100%;">

                        <tr>
                                <td><b>Legajo</b></td>
                                <td><b>Nombre</b></td>

                                <td><b>Asistencias</b></td>
                                <td><b>Ausencias</b></td>
                                <td><b>Licencias</b></td>
                                <td><b>Suspensiones</b></td>

                                <td><b>Horas Horario</b></td>
                                <td><b>Horas Regulares</b></td>
                                <td><b>Horas Extra</b></td>
                                <td><b>Horas Feriado</b></td>

                                <td><b>Llegada Tarde</b></td>
                                <td><b>Salida Temprano</b></td>


                            </tr>';


                        foreach ($a_data as $per_Id => $item) {

                            $html .= '
                             <tr>
                                    <!-- LEGAJO -->
                                    <td class="line-bottom">
                                        ' . $item['per_Legajo'] . '
                                    </td>

                                    <!-- NOMBRE -->
                                    <td class="line-bottom">
                                        ' . mb_convert_case($item['per_Apellido'] . ", " . $item['per_Nombre'], MB_CASE_TITLE, "UTF-8") . '
                                    </td>


                                    <!-- ASISTENCIAS -->
                                    <td class="line-bottom">
                                        ' . $item['Asistencias'] . '
                                    </td>

                                    <!-- AUSENCIAS -->
                                   <td class="line-bottom">
                                        ' . $item['Ausencias'] . '
                                    </td>



                                    <!-- LICENCIAS -->
                                    <td class="line-bottom">
                                        ' . $item['Licencias'] . '
                                    </td>

                                    <!-- SUSPENSIONES -->
                                     <td class="line-bottom">
                                        ' . $item['Suspensiones'] . '
                                    </td>




                                    <!-- HORAS HORARIO -->
                                    <td class="line-bottom">
                                        ' . $item['Horas_Horario'] . '
                                    </td>


                                    <!-- HORAS REGULARES -->
                                    <td class="line-bottom">
                                        ' . $item['Horas_Regulares'] . '
                                    </td>

                                    <!-- HORAS EXTRA -->
                                        <td class="line-bottom">
                                        ' . $item['Horas_Extra'] . '
                                    </td>



                                    <!-- HORAS FERIADO -->
                                     <td class="line-bottom">
                                        ' . $item['Horas_Feriado'] . '
                                    </td>


                                    <!-- LLEGADAS TARDE -->
                                      <td class="line-bottom">
                                        ' . $item['Llegadas_Tarde'] . '
                                    </td>


                                    <!-- SALIDAS TEMPRANO -->
                                      <td class="line-bottom">
                                        ' . $item['Salidas_Temprano'] . '
                                    </td>

                             </tr>';
                        }

                        $html .= '
                    </table>';
                    }
                    break;

                case 'Llegadas_Tarde':
                case '43':

                    if (!is_null($a_data)) {
                        $html .= '
                      <table style="width: 100%;">
                            <tr>
                                <td><b>Legajo</b></td>
                                <td><b>Nombre</b></td>

                                 <td><b>Acumula</b></td>

                                <td><b>Fecha</b></td>
                                <td><b>Horario</b></td>

                                <td><b>Entrada</b></td>
                            </tr>';

                        foreach ($a_data as $per_Id => $item) {

                            if (count($item['Llegadas_Tarde']) == 0) continue;

                            $log1 = $a_data[$per_Id]['Llegadas_Tarde'][0];
                            $html .= '
                                <tr>
                                    <!-- LEGAJO -->
                                    <td>
                                        ' . $item['per_Legajo'] . '
                                    </td>

                                    <!-- NOMBRE -->
                                    <td>
                                        ' . mb_convert_case($item['per_Apellido'] . ", " . $item['per_Nombre'], MB_CASE_TITLE, "UTF-8") . '
                                    </td>

                                    <!-- TOTAL -->
                                    <td>
                                        ' . $item['Total'] . '
                                     </td>

                                    <!-- FECHA -->
                                    <td>
                                        ' . date("d-m-Y", strtotime($log1['Fecha_Fin'])) . '
                                    </td>

                                     <!-- HORARIO -->
                                    <td>
                                        ' . $log1['Hora_Trabajo_Inicio'] . "-" . $log1['Hora_Trabajo_Fin'] . '
                                    </td>

                                     <!-- REGISTRO -->
                                    <td>
                                        ' . $log1['Hora_Inicio'] . '
                                    </td>

                                </tr>';


                            array_shift($item['Llegadas_Tarde']);
                            foreach ($item['Llegadas_Tarde'] as $key_log => $log) {
                                $html .= '
                                <tr>
                                    <!-- LEGAJO -->
                                    <td>
                                        <br>
                                    </td>

                                    <!-- NOMBRE -->
                                    <td>
                                         <br>
                                    </td>

                                    <!-- TOTAL -->
                                    <td>
                                       <br>
                                     </td>

                                       <!-- FECHA -->
                                    <td>
                                        ' . date("d-m-Y", strtotime($log['Fecha_Trabajo_Inicio'])) . '
                                    </td>

                                     <!-- HORARIO -->
                                    <td>
                                        ' . $log['Hora_Trabajo_Inicio'] . "-" . $log['Hora_Trabajo_Fin'] . '
                                    </td>

                                     <!-- REGISTRO -->
                                    <td>
                                        ' . $log['Hora_Inicio'] . '
                                    </td>

                                </tr>';
                            }
                        }

                        $html .= '
                        </table>';
                    }


                    break;

                case 'Salidas_Temprano':
                case '44':
                    if (!is_null($a_data)) {
                        $html .= '
                   <table style="width: 100%;">
                        <tr>
                            <td><b>Legajo</b></td>
                            <td><b>Nombre</b></td>

                             <td><b>Acumula</b></td>

                            <td><b>Fecha</b></td>
                            <td><b>Horario</b></td>

                            <td><b>Entrada</b></td>
                        </tr>';

                        foreach ($a_data as $per_Id => $item) {

                            if (count($item['Salidas_Temprano']) == 0) continue;

                            $log1 = $a_data[$per_Id]['Salidas_Temprano'][0];
                            $html .= '
                            <tr>
                                <!-- LEGAJO -->
                                <td>
                                    ' . $item['per_Legajo'] . '
                                </td>

                                <!-- NOMBRE -->
                                <td>
                                    ' . mb_convert_case($item['per_Apellido'] . ", " . $item['per_Nombre'], MB_CASE_TITLE, "UTF-8") . '
                                </td>

                                <!-- TOTAL -->
                                <td>
                                    ' . $item['Total'] . '
                                 </td>

                                <!-- FECHA -->
                                <td>
                                    ' . date("d-m-Y", strtotime($log1['Fecha_Fin'])) . '
                                </td>

                                 <!-- HORARIO -->
                                <td>
                                    ' . $log1['Hora_Trabajo_Inicio'] . "-" . $log1['Hora_Trabajo_Fin'] . '
                                </td>

                                 <!-- REGISTRO -->
                                <td>
                                    ' . $log1['Hora_Fin'] . '
                                </td>

                            </tr>';


                            array_shift($item['Salidas_Temprano']);
                            foreach ($item['Salidas_Temprano'] as $key_log => $log) {
                                $html .= '
                            <tr>
                                <!-- LEGAJO -->
                                <td>
                                    <br>
                                </td>

                                <!-- NOMBRE -->
                                <td>
                                     <br>
                                </td>

                                <!-- TOTAL -->
                                <td>
                                   <br>
                                 </td>

                                   <!-- FECHA -->
                                <td>
                                    ' . date("d-m-Y", strtotime($log['Fecha_Trabajo_Inicio'])) . '
                                </td>

                                 <!-- HORARIO -->
                                <td>
                                    ' . $log['Hora_Trabajo_Inicio'] . "-" . $log['Hora_Trabajo_Fin'] . '
                                </td>

                                 <!-- REGISTRO -->
                                <td>
                                    ' . $log['Hora_Fin'] . '
                                </td>

                            </tr>';
                            }
                        }

                        $html .= '
                    </table>';
                    }
                    break;

                case 'Listado_Asistencias':
                case '45':


                    if (!is_null($a_data)) {
                        $html .= '
                   <table style="width: 100%;">
                        <tr>
                            <td><b>Legajo</b></td>
                            <td><b>Nombre</b></td>

                             <td><b>Acumula</b></td>

                            <td><b>Fecha</b></td>
                            <td><b>Horario</b></td>

                            <td><b>Entrada</b></td>

                            <td><b>Salida</b></td>
                            <td><b>Feriado</b></td>
                        </tr>';

                        foreach ($a_data as $per_Id => $item) {

                            if (count($item['jornadas']) == 0) continue;

                            $log1 = $a_data[$per_Id]['jornadas'][0];
                            $html .= '
                            <tr>
                                <!-- LEGAJO -->
                                <td>
                                    ' . $item['per_Legajo'] . '
                                </td>

                                <!-- NOMBRE -->
                                <td>
                                    ' . mb_convert_case($item['per_Apellido'] . ", " . $item['per_Nombre'], MB_CASE_TITLE, "UTF-8") . '
                                </td>

                                <!-- TOTAL -->
                                <td>
                                    ' . $item['Total'] . '
                                 </td>

                                <!-- FECHA -->
                                <td>
                                    ' . date("d-m-Y", strtotime($log1['Fecha_Fin'])) . '
                                </td>

                                 <!-- HORARIO -->
                                <td>
                                    ' . $log1['Hora_Trabajo_Inicio'] . "-" . $log1['Hora_Trabajo_Fin'] . '
                                </td>

                                 <!-- ENTRADA -->
                                <td>
                                    ' . $log1['Hora_Inicio'] . '
                                </td>

                                <!-- SALIDA -->
                                <td>
                                   ' . $log1['Hora_Fin'] . '
                                </td>

                                <!-- FERIADO -->
                                <td>
                                    ' . $log1['Feriado'] . '
                                </td>

                            </tr>';


                            array_shift($item['jornadas']);
                            foreach ($item['jornadas'] as $key_log => $log) {
                                $html .= '
                            <tr>
                                <!-- LEGAJO -->
                                <td>
                                    <br>
                                </td>

                                <!-- NOMBRE -->
                                <td>
                                     <br>
                                </td>

                                <!-- TOTAL -->
                                <td>
                                   <br>
                                 </td>

                                   <!-- FECHA -->
                                <td>
                                    ' . date("d-m-Y", strtotime($log['Fecha_Trabajo_Inicio'])) . '
                                </td>

                                 <!-- HORARIO -->
                                <td>
                                    ' . $log['Hora_Trabajo_Inicio'] . "-" . $log['Hora_Trabajo_Fin'] . '
                                </td>

                                    <!-- ENTRADA -->
                                <td>
                                    ' . $log['Hora_Inicio'] . '
                                </td>

                                <!-- SALIDA -->
                                <td>
                                    ' . $log['Hora_Fin'] . '
                                </td>

                                <!-- FERIADO -->
                                <td>
                                    ' . $log['Feriado'] . '
                                </td>

                            </tr>';
                            }
                        }

                        $html .= '
                    </table>';
                    }
                    break;

                case 'Listado_Ausencias':
                case '46':
                    if (!is_null($a_data)) {
                        $html .= '
                <table style="width: 100%;">
                    <tr>
                        <td><b>Legajo</b></td>
                        <td><b>Nombre</b></td>

                        <td><b>Total</b></td>
                        <td><b>Justificadas</b></td>
                        <td><b>Acumula</b></td>

                        <td><b>Fecha</b></td>
                        <td><b>Horario</b></td>

                        <td><b>Feriado</b></td>
                        <td><b>Licencia</b></td>
                        <td><b>Suspensión</b></td>
                    </tr>';

                        foreach ($a_data as $per_Id => $item) {

                            if (count($item['Ausencias']) == 0) continue;

                            $log1 = $a_data[$per_Id]['Ausencias'][0];
                            $html .= '
                        <tr>
                            <!-- LEGAJO -->
                            <td>
                                ' . $item['per_Legajo'] . '
                            </td>

                            <!-- NOMBRE -->
                            <td>
                                ' . mb_convert_case($item['per_Apellido'] . ", " . $item['per_Nombre'], MB_CASE_TITLE, "UTF-8") . '
                            </td>

                            <!-- TOTAL -->
                            <td>
                                ' . $item['Total'] . '
                             </td>

                            <!-- TOTAL JUSTIFICADAS -->
                            <td>
                                ' . $item['Total_Justificadas'] . '
                             </td>

                              <!-- TOTAL NO JUSTIFICADAS -->
                            <td>
                                ' . $item['Total_No_Justificadas'] . '
                             </td>

                            <!-- FECHA -->
                            <td>
                                ' . date("d-m-Y", strtotime($log1['Fecha_Trabajo_Inicio'])) . '
                            </td>

                             <!-- HORARIO -->
                            <td>
                                ' . $log1['Hora_Trabajo_Inicio'] . "-" . $log1['Hora_Trabajo_Fin'] . '
                            </td>

                             <!-- FERIADO -->
                            <td>
                                ' . $log1['Feriado'] . '
                            </td>

                             <!-- LICENCIA -->
                            <td>
                                ' . $log1['Licencia'] . '
                            </td>

                             <!-- TOTAL -->
                            <td>
                                ' . $log1['Suspension'] . '
                            </td>
                        </tr>';


                            array_shift($item['Ausencias']);
                            foreach ($item['Ausencias'] as $key_log => $log) {
                                $html .= '
                        <tr>
                            <!-- LEGAJO -->
                            <td>
                                <br>
                            </td>

                            <!-- NOMBRE -->
                            <td>
                                 <br>
                            </td>

                            <!-- TOTAL -->
                            <td>
                               <br>
                             </td>

                            <!-- TOTAL JUSTIFICADAS -->
                            <td>
                               <br>
                             </td>

                              <!-- TOTAL NO JUSTIFICADAS -->
                            <td>
                                <br>
                            </td>
                               <!-- FECHA -->
                            <td>
                                ' . date("d-m-Y", strtotime($log['Fecha_Trabajo_Inicio'])) . '
                            </td>

                             <!-- HORARIO -->
                            <td>
                                ' . $log['Hora_Trabajo_Inicio'] . "-" . $log['Hora_Trabajo_Fin'] . '
                            </td>

                             <!-- FERIADO -->
                            <td>
                                ' . $log['Feriado'] . '
                            </td>

                             <!-- LICENCIA -->
                            <td>
                                ' . $log['Licencia'] . '
                            </td>

                             <!-- TOTAL -->
                            <td>
                                ' . $log['Suspension'] . '
                            </td>
                        </tr>';
                            }
                        }

                        $html .= '
                </table>';
                    }
                    break;

                case 'Personas':
                case '50':
                    break;

                case 'Grupos':
                case '51':
                    break;


            }
        }
        /* REPORTE VACÍO */
        else {
            $html = 'No hay registros';
        }

        require_once APP_PATH . '/libs/mpdf/mpdf.php';


        /* CREO PDF */
        $mpdf = new mPDF('', 'A4', 8, 'freesans', $margen_izquierdo, $margen_derecho, $margen_superior, $margen_inferior, 5, 5, 'landscape');

        /* SETEO PDF */
        $mpdf->SetTitle($T_Titulo);
        $mpdf->SetSubject($dia_actual);
        $mpdf->SetHTMLHeader($_headerhtml);
        $mpdf->SetHTMLFooter($_footerhtml);
        $mpdf->WriteHTML($this->_css, 1);
        $mpdf->WriteHTML($html);


        switch ($guardar_descargar){

            /* GUARDAR EN BUCKET */
            case 'Guardar':

                if (!file_exists(GS_CLIENT_TEMP_FOLDER)) mkdir(GS_CLIENT_TEMP_FOLDER, 0777, true);

                $file_url = GS_CLIENT_TEMP_FOLDER . $T_Nombre_Archivo;
                $mpdf->Output($file_url,"F");
                break;

            /* DESCARGO PDF */
            case 'Descargar':

                $file_url = $T_Nombre_Archivo;
                $mpdf->Output($file_url  , "D");
                break;

            default:
                $file_url = false;
                break;

        }

        return $file_url;



    }
    public function generar_csv($csv_excel_data = array(), $guardar_descargar = "Guardar") {

        /* VARIABLES */
        $T_Nombre_Archivo       = $this->_nombre_archivo.".csv";

        /* CSV */
        switch ($guardar_descargar){

            /* GUARDAR EN BUCKET */
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


                /* FILE URL */
                $file_url = GS_CLIENT_TEMP_FOLDER . $T_Nombre_Archivo;


                /* CSV HANDLER */
                $csv_handler = fopen ($file_url,'w');
                fwrite ($csv_handler,$csv_content);
                fclose ($csv_handler);
                break;


            /* DESCARGO PDF */
            case 'Descargar':

                /* HEADER */
                header('Content-Encoding: UTF-8');
                header('Content-type: text/csv; charset=UTF-8');
                header("Content-Disposition: attachment; filename=" . $T_Nombre_Archivo);

                /* VARIABLES */
                $outstream = fopen("php://output", 'w');

                foreach ($csv_excel_data as $row) {
                    $_row = str_replace('"', '', $row);
                    fputs($outstream, implode(';', $_row)."\n");
                }

                /* CERRAR ARCHIVO */
                fclose($outstream);

                /* FILE URL */
                $file_url = $T_Nombre_Archivo;

                break;

            default:
                $file_url = false;
                break;

        }

        return $file_url;

    }
    public function generar_excel($csv_excel_data = array(), $guardar_descargar = "Guardar") {

        /* VARIABLES */
        $T_Nombre_Archivo       = $this->_nombre_archivo.".xls";

        /* CSV */
        switch ($guardar_descargar){

            /* GUARDAR EN BUCKET */
            case 'Guardar':

                /* VARIABLES */
                $alpha  = array(_("A"), _("B"), _("C"), _("D"), _("E"), _("F"), _("G"), _("H"), _("I"), _("J"), _("K"), _("L"), _("M"), _("N"), _("O"), _("P"), _("Q"), _("R"), _("S"), _("T"), _("U"), _("V"), _("W"), _("X"), _("Y"), _("Z"),
                    _("AA"), _("AB"), _("AC"), _("AD"), _("AE"), _("AF"), _("AG"), _("AH"), _("AI"), _("AJ"), _("AK"), _("AL"), _("AM"), _("AN"), _("AO"), _("AP"), _("AQ"), _("AR"), _("AS"), _("AT"), _("AU"), _("AV"), _("AW"), _("AX"), _("AY"), _("AZ"),
                    _("BA"), _("BB"), _("BC"), _("BD"), _("BE"), _("BF"), _("BG"), _("BH"), _("BI"), _("BJ"), _("BK"), _("BL"), _("BM"), _("BN"), _("BO"), _("BP"), _("BQ"), _("BR"), _("BS"), _("BT"), _("BU"), _("BV"), _("BW"), _("BX"), _("BY"), _("BZ"),
                    _("CA"), _("CB"), _("CC"), _("CD"), _("CE"), _("CF"), _("CG"), _("CH"), _("CI"), _("CJ"), _("CK"), _("CL"), _("CM"), _("CN"), _("CO"), _("CP"), _("CQ"), _("CR"), _("CS"), _("CT"), _("CU"), _("CV"), _("CW"), _("CX"), _("CY"), _("CZ"))
                ;

                $row = 1;
                $file_url = GS_CLIENT_TEMP_FOLDER . $T_Nombre_Archivo;

                // Create new PHPExcel object
                $objPHPExcel = new PHPExcel();

                // Set document properties
                $objPHPExcel->getActiveSheet()->setTitle('Registros');


                foreach ($csv_excel_data as $list) {
                    $columna = 0;
                    foreach ($list as $d_list) {
                        $_value = mb_convert_case($d_list, MB_CASE_TITLE, "UTF-8");

                        $objPHPExcel->getActiveSheet()->setCellValue($alpha[$columna] . $row, $_value)->getStyle($alpha[$columna] . $row)->getAlignment();
                        $columna++;
                    }
                    $row++;
                }

                /* CREAR CARPETA CLIENTE */
                if (!file_exists(GS_CLIENT_TEMP_FOLDER)) mkdir(GS_CLIENT_TEMP_FOLDER, 0777, true);

                /* ESCRIBIR ARCHIVO */
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

                /* ESCRIBIR ARCHIVO */
                $objWriter->save($file_url);

                break;


            /* DESCARGO PDF */
            case 'Descargar':



                /* VARIABLES */
                $alpha  = array(_("A"), _("B"), _("C"), _("D"), _("E"), _("F"), _("G"), _("H"), _("I"), _("J"), _("K"), _("L"), _("M"), _("N"), _("O"), _("P"), _("Q"), _("R"), _("S"), _("T"), _("U"), _("V"), _("W"), _("X"), _("Y"), _("Z"),
                    _("AA"), _("AB"), _("AC"), _("AD"), _("AE"), _("AF"), _("AG"), _("AH"), _("AI"), _("AJ"), _("AK"), _("AL"), _("AM"), _("AN"), _("AO"), _("AP"), _("AQ"), _("AR"), _("AS"), _("AT"), _("AU"), _("AV"), _("AW"), _("AX"), _("AY"), _("AZ"),
                    _("BA"), _("BB"), _("BC"), _("BD"), _("BE"), _("BF"), _("BG"), _("BH"), _("BI"), _("BJ"), _("BK"), _("BL"), _("BM"), _("BN"), _("BO"), _("BP"), _("BQ"), _("BR"), _("BS"), _("BT"), _("BU"), _("BV"), _("BW"), _("BX"), _("BY"), _("BZ"),
                    _("CA"), _("CB"), _("CC"), _("CD"), _("CE"), _("CF"), _("CG"), _("CH"), _("CI"), _("CJ"), _("CK"), _("CL"), _("CM"), _("CN"), _("CO"), _("CP"), _("CQ"), _("CR"), _("CS"), _("CT"), _("CU"), _("CV"), _("CW"), _("CX"), _("CY"), _("CZ"))
                ;

                $row = 1;
                $file_url = $T_Nombre_Archivo;


                /* HEADER */
                header('Content-Encoding: UTF-8');
                header("Content-type: application/vnd.ms-excel; name='excel'");
                header("Content-Disposition: attachment; filename=" . $file_url);



                // Create new PHPExcel object
                $objPHPExcel = new PHPExcel();

                // Set document properties
                $objPHPExcel->getActiveSheet()->setTitle('Registros');


                foreach ($csv_excel_data as $list) {
                    $columna = 0;
                    foreach ($list as $d_list) {
                        $_value = mb_convert_case($d_list, MB_CASE_TITLE, "UTF-8");


                        $objPHPExcel->getActiveSheet()->setCellValue($alpha[$columna] . $row, $_value)->getStyle($alpha[$columna] . $row)->getAlignment();
                        $columna++;
                    }
                    $row++;
                }

                /* ESCRIBIR ARCHIVO */
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

                /* ESCRIBIR ARCHIVO */
                $objWriter->save('php://output');

                break;

            default:
                $file_url = false;
                break;

        }

        return $file_url;

    }


    public function generar_asistencias($array_personas_a_controlar = array(), $o_Listado = array(), $a_Feriados = array()) {


        $a_Asistencias = array();

        /* LOGS POR PAR */
        foreach ($array_personas_a_controlar as $perID => $a_persona) {

            /* PERSONA */
            $o_persona              = $array_personas_a_controlar[$perID];

            /* DATOS DE LA PERSONA */
            $a_Asistencias[$perID]['per_Nombre']                = $o_persona['per_Nombre'] ;
            $a_Asistencias[$perID]['per_Apellido']              = $o_persona['per_Apellido'] ;
            $a_Asistencias[$perID]['per_Legajo']                = $o_persona['per_Legajo'];
            $a_Asistencias[$perID]['per_Imagen']                = $o_persona['per_Imagen'];
            $a_Asistencias[$perID]['Hora_Trabajo_Detalle']      = $o_persona['Hora_Trabajo_Detalle'];
            $a_Asistencias[$perID]['jornadas']                  = array();
            $a_Asistencias[$perID]['Total']                     = 0;


            /* VARIABLES */
            $jornada_Id                     = 0;
            $inicio_de_jornada              = true;
            $final_de_jornada               = false;

            /* JORNADA INICIO */
            $dia_hora_jornada_inicio        = '';
            $dia_jornada_inicio             = '';
            $hora_jornada_inicio            = '';

            /* HORARIO INICIO */
            $dia_hora_horario_inicio        = '';
            $dia_horario_inicio             = '';
            $hora_horario_inicio            = '';

            $horario_horas                  = 0;

            /* HORAS */
            $horas_acumuladas               = 0;
            $horas_regulares                = 0;
            $horas_extra                    = 0;
            $horas_feriado                  = 0;

            /* LLEGADA TARDE */
            $llegada_tarde                  = '';

            /* SALIDA TEMPRANO */
            $salida_temprano                = '';

            /* EXCEPCIONES */
            $feriado                        = '';

            /* NO HAY LOGS */
            if(!isset($o_Listado[$perID]['logs'])){
                continue;
            }

            /* LOGS */
            foreach ($o_Listado[$perID]['logs'] as $logID => $log) {

                /* INICIO DE JORNADA */
                if($inicio_de_jornada){

                    /* VARIABLES */
                    $fecha_inicio       = $o_Listado[$perID]['logs'][$logID]['Fecha_Inicio'];

                    /* JORNADA */
                    $dia_hora_jornada_inicio        = $o_Listado[$perID]['logs'][$logID]['Fecha_Hora_Inicio'];
                    $dia_jornada_inicio             = $o_Listado[$perID]['logs'][$logID]['Fecha_Inicio'];
                    $hora_jornada_inicio            = $o_Listado[$perID]['logs'][$logID]['Hora_Inicio'];

                    /* HORARIO INICIO*/
                    $dia_hora_horario_inicio        = $o_Listado[$perID]['logs'][$logID]['Fecha_Hora_Trabajo_Inicio'];
                    $dia_horario_inicio             = $o_Listado[$perID]['logs'][$logID]['Fecha_Trabajo_Inicio'];
                    $hora_horario_inicio            = $o_Listado[$perID]['logs'][$logID]['Hora_Trabajo_Inicio'];

                    /* HORARIO HHORAS */
                    $horario_horas                  = $o_Listado[$perID]['logs'][$logID]['Total_Horario'];

                    /* ASISTENCIA */
                    $asistencia                     = $o_Listado[$perID]['logs'][$logID]['Asistencia'];

                    /* AUSENCIA */
                    $ausencia                       = $o_Listado[$perID]['logs'][$logID]['Ausencia'];

                    /* LLEGADA TARDE */
                    $llegada_tarde                  = $o_Listado[$perID]['logs'][$logID]['Llegada_Tarde'];


                    /* EXCEPCIONES */
                    $o_feriado          = isset($a_Feriados[$perID][$fecha_inicio]) ? $a_Feriados[$perID][$fecha_inicio] : null;

                    /* EXCEPCIONES DETALLE */
                    $es_feriado     = !is_null($o_feriado)      ? 'Si'                                  : 'No';
                    $feriado        = !is_null($o_feriado)      ? $o_feriado[0]['fer_Descripcion']      : 'No';

                    /* JORNADA */
                    $inicio_de_jornada      = false;
                }

                /* ES FINAL DE JORNADA */
                if(!isset($o_Listado[$perID]['logs'][$logID+1])) {
                    /* JORNADA */
                    $final_de_jornada = true;
                }

                /* ES FINAL DE JORNADA */
                if(isset($o_Listado[$perID]['logs'][$logID+1])      &&      $dia_hora_horario_inicio  !=  $o_Listado[$perID]['logs'][$logID+1]['Fecha_Hora_Trabajo_Inicio']){
                    /* JORNADA */
                    $final_de_jornada = true;
                }

                /* HORAS ACUMULADAS */
                $horas_acumuladas           = $horas_acumuladas + $o_Listado[$perID]['logs'][$logID]['Total_Intervalo'];

                /* FINAL DE JORNADA */
                if($final_de_jornada){

                    /* HORARIO */
                    $horario_fin                        = $o_Listado[$perID]['logs'][$logID]['Fecha_Hora_Trabajo_Fin'];

                    /* SALIDA TEMPRANO */
                    $salida_temprano                    = $o_Listado[$perID]['logs'][$logID]['Salida_Temprano'];


                    /* VARIABLES HORAS */
                    $horas_regulares        = $es_feriado == 'Si' ? 0 : $horas_acumuladas > $horario_horas ? $horario_horas : $horas_acumuladas;
                    $horas_extra            = $es_feriado == 'Si' ? 0 : $horas_acumuladas > $horario_horas ? $horas_acumuladas - $horario_horas : 0;
                    $horas_feriado          = $es_feriado == 'No' ? 0 : $horas_acumuladas;

                    /* DIA SEMANA */
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Dia_Semana_Inicio']            = $o_Listado[$perID]['logs'][$logID]['Dia_Semana_Inicio'] ;
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Dia_Semana_Fin']               = $o_Listado[$perID]['logs'][$logID]['Dia_Semana_Fin'] ;

                    /* FECHA HORA HORARIO */
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Fecha_Hora_Trabajo_Inicio']    = $dia_hora_horario_inicio;
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Fecha_Hora_Trabajo_Fin']       = $o_Listado[$perID]['logs'][$logID]['Fecha_Hora_Trabajo_Fin'];

                    /* FECHA HORARIO */
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Fecha_Trabajo_Inicio']         = $dia_horario_inicio;
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Fecha_Trabajo_Fin']            = $o_Listado[$perID]['logs'][$logID]['Fecha_Trabajo_Fin'];

                    /* HORA HORARIO */
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Hora_Trabajo_Inicio']          = $hora_horario_inicio;
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Hora_Trabajo_Fin']             = $o_Listado[$perID]['logs'][$logID]['Hora_Trabajo_Fin'];



                    /* DIA Y HORA JORNADA */
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Fecha_Hora_Inicio']            = $dia_hora_jornada_inicio;
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Fecha_Hora_Fin']               = $o_Listado[$perID]['logs'][$logID]['Fecha_Hora_Fin'];

                    /* DIA JORNADA */
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Fecha_Inicio']                 = $dia_jornada_inicio;
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Fecha_Fin']                    = $o_Listado[$perID]['logs'][$logID]['Fecha_Fin'];

                    /* HORA JORNADA */
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Hora_Inicio']                  = $hora_jornada_inicio;
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Hora_Fin']                     = $o_Listado[$perID]['logs'][$logID]['Hora_Fin'];


                    /* HORARIO */
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Horas_Horario']                = $horario_horas;

                    /* HORAS */
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Horas_Regulares']              = $horas_regulares;
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Horas_Extra']                  = round($horas_extra,2);
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Horas_Feriado']                = $horas_feriado;


                    /* ASISTENCIA */
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Asistencia']                   = $asistencia;

                    /* AUSENCIA */
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Ausencia']                     = $ausencia;

                    /* LLEGADA TARDE */
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Llegada_Tarde']                = $llegada_tarde;

                    /* SALIDA TEMPRANO */
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Salida_Temprano']              = $salida_temprano;


                    /* FERIADOS */
                    $a_Asistencias[$perID]['jornadas'][$jornada_Id]['Feriado']                      = $feriado;




                    /* VARIABLES */
                    $jornada_Id             = $jornada_Id +1;
                    $inicio_de_jornada      = true;
                    $final_de_jornada       = false;

                    /* JORNADA INICIO */
                    $dia_hora_jornada_inicio        = '';
                    $dia_jornada_inicio             = '';
                    $hora_jornada_inicio            = '';


                    /* HORARIO */
                    $dia_hora_horario_inicio         = '';
                    $horario_fin            = '';
                    $horario_horas          = 0;

                    /* HORAS */
                    $horas_acumuladas       = 0;
                    $horas_regulares        = 0;
                    $horas_extra            = 0;
                    $horas_feriado          = 0;

                    /* LLEGADA TARDE */
                    $llegada_tarde          = '';

                    /* SALIDA TEMPRANO */
                    $salida_temprano        = '';

                }
            }

            $a_Asistencias[$perID]['Total']   = count($a_Asistencias[$perID]['jornadas']);



        }

        return $a_Asistencias;
    }
    public function generar_llegadas_tarde($a_Asistencias = array(), $array_personas_a_controlar = array()) {

        $a_Llegadas_Tarde = array();

        foreach ($a_Asistencias as $perID => $a_persona) {

            /* PERSONA */
            $o_persona              = $array_personas_a_controlar[$perID];

            /* DATOS DE LA PERSONA */
            $a_Llegadas_Tarde[$perID]['per_Nombre']                 = $o_persona['per_Nombre'] ;
            $a_Llegadas_Tarde[$perID]['per_Apellido']               = $o_persona['per_Apellido'] ;
            $a_Llegadas_Tarde[$perID]['per_Legajo']                 = $o_persona['per_Legajo'];
            $a_Llegadas_Tarde[$perID]['per_Imagen']                 = $o_persona['per_Imagen'];
            $a_Llegadas_Tarde[$perID]['Hora_Trabajo_Detalle']       = $o_persona['Hora_Trabajo_Detalle'];
            $a_Llegadas_Tarde[$perID]['Llegadas_Tarde']             = array();
            $a_Llegadas_Tarde[$perID]['Total']                      = 0;


            /* NO HAY ASISTENCIAS */
            if(count($a_Asistencias[$perID]['jornadas']) == 0){
                continue;
            }
            /* VARIABLES */
            $index_tarde   =   0;


            foreach ($a_persona['jornadas'] as $jornadaID => $jornada) {

                /* JORNADA SIN HORARIO */
                if($jornada['Hora_Trabajo_Inicio'] == '00:00:00' && $jornada['Hora_Trabajo_Fin'] == '00:00:00' ){
                    continue;
                }

                /* JORNADA TARDE */
                if( $jornada['Llegada_Tarde'] == 'Si'){

                    /* FECHA Y HORA JORNADA */
                    $a_Llegadas_Tarde[$perID]['Llegadas_Tarde'][$index_tarde]       = $jornada;

                    /* PROXIMA JORNADA TARDE */
                    $index_tarde  = $index_tarde +1;
                }

            }

            /* TOTAL LLEGADAS TARDE */
            $a_Llegadas_Tarde[$perID]['Total']   = count( $a_Llegadas_Tarde[$perID]['Llegadas_Tarde']);
        }


        return $a_Llegadas_Tarde;
    }
    public function generar_salidas_temprano($a_Asistencias = array(), $array_personas_a_controlar = array()) {

        $a_Salidas_Temprano = array();

        foreach ($a_Asistencias as $perID => $a_persona) {

            /* PERSONA */
            $o_persona              = $array_personas_a_controlar[$perID];

            /* DATOS DE LA PERSONA */
            $a_Salidas_Temprano[$perID]['per_Nombre']                   = $o_persona['per_Nombre'] ;
            $a_Salidas_Temprano[$perID]['per_Apellido']                 = $o_persona['per_Apellido'] ;
            $a_Salidas_Temprano[$perID]['per_Legajo']                   = $o_persona['per_Legajo'];
            $a_Salidas_Temprano[$perID]['per_Imagen']                   = $o_persona['per_Imagen'];
            $a_Salidas_Temprano[$perID]['Hora_Trabajo_Detalle']         = $o_persona['Hora_Trabajo_Detalle'];

            $a_Salidas_Temprano[$perID]['Salidas_Temprano']             = array();
            $a_Salidas_Temprano[$perID]['Total']                        = 0;


            /* VARIABLES */
            $index_temprano   =   0;

            foreach ($a_persona['jornadas'] as $jornadaID => $jornada) {

                /* JORNADA SIN HORARIO */
                if($jornada['Hora_Trabajo_Inicio'] == '00:00:00' && $jornada['Hora_Trabajo_Fin'] == '00:00:00' ){
                    continue;
                }

                /* JORNADA TEMPRANO */
                if( $jornada['Salida_Temprano'] == 'Si'){

                    /* FECHA Y HORA JORNADA */
                    $a_Salidas_Temprano[$perID]['Salidas_Temprano'][$index_temprano]  = $jornada;

                    /* PROXIMA JORNADA S TEMPRANO */
                    $index_temprano = $index_temprano +1;
                }

            }

            /* TOTAL SALIDAS TEMPRANO */
            $a_Salidas_Temprano[$perID]['Total'] = count( $a_Salidas_Temprano[$perID]['Salidas_Temprano']);

        }

        return $a_Salidas_Temprano;
    }
    public function generar_horarios_dias($Fecha_Desde, $Fecha_Hasta, $array_personas_a_controlar, $a_Hora_Trabajo, $a_Horarios_Rotativos, $a_Horarios_Multiples, $a_Horarios_Flexibles, $dias, $a_Feriados , $a_Licencias, $a_Suspensiones) {

        $a_Horarios_Dias = array();

        $dia_inicio_filtro          = date('Y-m-d', strtotime($Fecha_Desde)); //arranco con el primer dia
        $dias_cantidad_filtro       = DateTimeHelper::diff_Fecha_En_Dias($Fecha_Desde,$Fecha_Hasta);


        /* VARIABLES */
        $a_dias_filtro                  = array();


        /* DÍAS FILTRO */
        for ($i = 0; $i <= $dias_cantidad_filtro; $i++) {

            /* VARIABLES DIAS FILTRO */
            $dia_filtro_time            = strtotime($dia_inicio_filtro . ' +' . $i . ' day');
            $dia_filtro                 = date('Y-m-d', $dia_filtro_time);

            /* DIAS FILTRO */
            $a_dias_filtro[$i]          = $dia_filtro;
        }

        /* HORARIOS DE DIAS FILTRO */
        foreach ($array_personas_a_controlar as $perID => $a_persona) {

            $index_horario = 0;
            foreach ($a_dias_filtro as $diaID => $dia) {
                switch ($a_persona['per_Hor_Tipo']) {
                    case HORARIO_NORMAL:
                        /* VARIABLES HORARIO */
                        $o_hora_trabajo                                                             = $a_Hora_Trabajo[$a_persona['per_Hor_Id']];
                        $horario                                                                    = $o_hora_trabajo->obtenerHorarioPorDia($dia);

                        /* DIA SEMANA */
                        $a_Horarios_Dias[$perID][$index_horario]['Dia_Semana']                      = $dias[date('w', strtotime($dia))];

                        /* FECHA FILTRO */
                        $a_Horarios_Dias[$perID][$index_horario]['Fecha_Filtro']                    = $dia;

                        /* HORARIO */
                        $a_Horarios_Dias[$perID][$index_horario]['Fecha_Hora_Trabajo_Inicio']       = $horario[0];
                        $a_Horarios_Dias[$perID][$index_horario]['Fecha_Hora_Trabajo_Fin']          = $horario[1];

                        $a_Horarios_Dias[$perID][$index_horario]['Fecha_Trabajo_Inicio']            = date('Y-m-d', strtotime($horario[0]));
                        $a_Horarios_Dias[$perID][$index_horario]['Fecha_Trabajo_Fin']               = date('Y-m-d', strtotime($horario[1]));

                        $a_Horarios_Dias[$perID][$index_horario]['Hora_Trabajo_Inicio']             = date('H:i:s', strtotime($horario[0]));
                        $a_Horarios_Dias[$perID][$index_horario]['Hora_Trabajo_Fin']                = date('H:i:s', strtotime($horario[1]));

                        /* HORAS HORARIO */
                        $horario_inicio_date                                                        = date_create($horario[0]);
                        $horario_fin_date                                                           = date_create($horario[1]);
                        $diff                                                                       = date_diff($horario_fin_date, $horario_inicio_date);
                        $interval                                                                   = $diff->format("%H:%I:%S");
                        $a_Horarios_Dias[$perID][$index_horario]['Horas_Horario']                   = round(abs(DateTimeHelper::time_to_sec($interval) / 3600), 2);

                        /* VARIABLES PROXIMO HORARIO */
                        $index_horario = $index_horario + 1;
                        break;

                    case HORARIO_ROTATIVO:
                        /* VARIABLES HORARIO */
                        $o_hora_trabajo                                                             = $a_Horarios_Rotativos[$a_persona['per_Hor_Id']];
                        $horario                                                                    = $o_hora_trabajo->obtenerHorarioPorDia($dia);

                        /* DIA SEMANA */
                        $a_Horarios_Dias[$perID][$index_horario]['Dia_Semana']                      = $dias[date('w', strtotime($dia))];

                        /* FECHA FILTRO */
                        $a_Horarios_Dias[$perID][$index_horario]['Fecha_Filtro']                    = $dia;

                        /* HORARIO */
                        $a_Horarios_Dias[$perID][$index_horario]['Fecha_Hora_Trabajo_Inicio']       = $horario[0];
                        $a_Horarios_Dias[$perID][$index_horario]['Fecha_Hora_Trabajo_Fin']          = $horario[1];

                        $a_Horarios_Dias[$perID][$index_horario]['Fecha_Trabajo_Inicio']            = date('Y-m-d', strtotime($horario[0]));
                        $a_Horarios_Dias[$perID][$index_horario]['Fecha_Trabajo_Fin']               = date('Y-m-d', strtotime($horario[1]));

                        $a_Horarios_Dias[$perID][$index_horario]['Hora_Trabajo_Inicio']             = date('H:i:s', strtotime($horario[0]));
                        $a_Horarios_Dias[$perID][$index_horario]['Hora_Trabajo_Fin']                = date('H:i:s', strtotime($horario[1]));

                        /* HORAS HORARIO */
                        $horario_inicio_date                                                        = date_create($horario[0]);
                        $horario_fin_date                                                           = date_create($horario[1]);
                        $diff                                                                       = date_diff($horario_fin_date, $horario_inicio_date);
                        $interval                                                                   = $diff->format("%H:%I:%S");
                        $a_Horarios_Dias[$perID][$index_horario]['Horas_Horario']                   = round(abs(DateTimeHelper::time_to_sec($interval) / 3600), 2);

                        /* VARIABLES PROXIMO HORARIO */
                        $index_horario = $index_horario + 1;
                        break;


                    case HORARIO_MULTIPLE:
                        /* VARIABLES HORARIOS */
                        $o_hora_trabajo                                                                 = $a_Horarios_Multiples[$a_persona['per_Hor_Id']];
                        $a_horarios                                                                     = $o_hora_trabajo->obtenerHorariosPorDia($dia);

                        /* HORARIOS */
                        foreach ($a_horarios as $horarioID => $horario){

                            /* DIA SEMANA */
                            $a_Horarios_Dias[$perID][$index_horario]['Dia_Semana']                      = $dias[date('w', strtotime($dia))];

                            /* FECHA FILTRO */
                            $a_Horarios_Dias[$perID][$index_horario]['Fecha_Filtro']                    = $dia;

                            /* HORARIO */
                            $a_Horarios_Dias[$perID][$index_horario]['Fecha_Hora_Trabajo_Inicio']       = $horario[0];
                            $a_Horarios_Dias[$perID][$index_horario]['Fecha_Hora_Trabajo_Fin']          = $horario[1];

                            $a_Horarios_Dias[$perID][$index_horario]['Fecha_Trabajo_Inicio']            = date('Y-m-d', strtotime($horario[0]));
                            $a_Horarios_Dias[$perID][$index_horario]['Fecha_Trabajo_Fin']               = date('Y-m-d', strtotime($horario[1]));

                            $a_Horarios_Dias[$perID][$index_horario]['Hora_Trabajo_Inicio']             = date('H:i:s', strtotime($horario[0]));
                            $a_Horarios_Dias[$perID][$index_horario]['Hora_Trabajo_Fin']                = date('H:i:s', strtotime($horario[1]));

                            /* HORAS HORARIO */
                            $horario_inicio_date                                                        = date_create($horario[0]);
                            $horario_fin_date                                                           = date_create($horario[1]);
                            $diff                                                                       = date_diff($horario_fin_date, $horario_inicio_date);
                            $interval                                                                   = $diff->format("%H:%I:%S");
                            $a_Horarios_Dias[$perID][$index_horario]['Horas_Horario']                   = round(abs(DateTimeHelper::time_to_sec($interval) / 3600), 2);

                            /* VARIABLES PROXIMO HORARIO */
                            $index_horario = $index_horario + 1;
                        }
                        break;

                    case HORARIO_FLEXIBLE:
                        /* VARIABLES HORARIOS */
                        $o_hora_trabajo                                                                 = $a_Horarios_Flexibles[$a_persona['per_Hor_Id']];
                        $a_horarios                                                                     = $o_hora_trabajo->obtenerHorariosPorDia($dia);

                        /* VARIABLES HORARIOS */
                        foreach ($a_horarios as $horarioID => $horario){

                            /* DIA SEMANA */
                            $a_Horarios_Dias[$perID][$index_horario]['Dia_Semana']                      = $dias[date('w', strtotime($dia))];

                            /* FECHA FILTRO */
                            $a_Horarios_Dias[$perID][$index_horario]['Fecha_Filtro']                    = $dia;

                            /* HORARIO */
                            $a_Horarios_Dias[$perID][$index_horario]['Fecha_Hora_Trabajo_Inicio']       = $horario[0];
                            $a_Horarios_Dias[$perID][$index_horario]['Fecha_Hora_Trabajo_Fin']          = $horario[1];

                            $a_Horarios_Dias[$perID][$index_horario]['Fecha_Trabajo_Inicio']            = date('Y-m-d', strtotime($horario[0]));
                            $a_Horarios_Dias[$perID][$index_horario]['Fecha_Trabajo_Fin']               = date('Y-m-d', strtotime($horario[1]));

                            $a_Horarios_Dias[$perID][$index_horario]['Hora_Trabajo_Inicio']             = date('H:i:s', strtotime($horario[0]));
                            $a_Horarios_Dias[$perID][$index_horario]['Hora_Trabajo_Fin']                = date('H:i:s', strtotime($horario[1]));

                            /* HORAS HORARIO */
                            $horario_inicio_date                                                        = date_create($horario[0]);
                            $horario_fin_date                                                           = date_create($horario[1]);
                            $diff                                                                       = date_diff($horario_fin_date, $horario_inicio_date);
                            $interval                                                                   = $diff->format("%H:%I:%S");
                            $a_Horarios_Dias[$perID][$index_horario]['Horas_Horario']                   = round(abs(DateTimeHelper::time_to_sec($interval) / 3600), 2);

                            /* VARIABLES PROXIMO HORARIO */
                            $index_horario = $index_horario + 1;
                        }

                        break;


                    case 0:
                    default:

                        /* DIA SEMANA */
                        $a_Horarios_Dias[$perID][$index_horario]['Dia_Semana']                      = $dias[date('w', strtotime($dia))];

                        /* FECHA FILTRO */
                        $a_Horarios_Dias[$perID][$index_horario]['Fecha_Filtro']                    = $dia;

                        /* HORARIO */
                        $a_Horarios_Dias[$perID][$index_horario]['Fecha_Hora_Trabajo_Inicio']       = '';
                        $a_Horarios_Dias[$perID][$index_horario]['Fecha_Hora_Trabajo_Fin']          = '';

                        $a_Horarios_Dias[$perID][$index_horario]['Fecha_Trabajo_Inicio']            = '';
                        $a_Horarios_Dias[$perID][$index_horario]['Fecha_Trabajo_Fin']               = '';

                        $a_Horarios_Dias[$perID][$index_horario]['Hora_Trabajo_Inicio']             = '';
                        $a_Horarios_Dias[$perID][$index_horario]['Hora_Trabajo_Fin']                = '';

                        $a_Horarios_Dias[$perID][$index_horario]['Horas_Horario']                   = 0;


                        /* VARIABLES PROXIMO HORARIO */
                        $index_horario = $index_horario + 1;
                        break;
                }
            }
        }

        /* EXCEPCIONES */
        foreach ($a_Horarios_Dias  as $perID => $a_persona) {
            foreach ($a_persona as $jornadaID => $jornada){

                /* VARABLES */
                $fecha_inicio = $a_Horarios_Dias[$perID][$jornadaID]['Fecha_Filtro'];

                /* EXCEPCIONES */
                //$o_feriado          = Feriado_L::obtenerPorDia($fecha_inicio, $perID);
                //$o_suspension       = Suspensions_L::obtenerPorDia($fecha_inicio, $perID);
                //$o_licencia         = Licencias_L::obtenerPorDia($fecha_inicio, $perID);

                /* EXCEPCIONES */
                $o_feriado          = isset($a_Feriados[$perID][$fecha_inicio]) ? $a_Feriados[$perID][$fecha_inicio] : null;
                $o_licencia       = isset($a_Licencias[$perID][$fecha_inicio]) ? $a_Licencias[$perID][$fecha_inicio] : null;
                $o_suspension         = isset($a_Suspensiones[$perID][$fecha_inicio]) ? $a_Suspensiones[$perID][$fecha_inicio] : null;

                /* EXCEPCIONES DETALLE */
                $feriado        = !is_null($o_feriado)          ? $o_feriado[0]['fer_Descripcion']      : 'No';
                $licencia       = !is_null($o_licencia)         ? $o_licencia[0]['lic_Motivo']          : 'No';
                $suspension     = !is_null($o_suspension)       ? $o_suspension[0]['sus_Motivo']        : 'No';

                /* FERIADOS */
                $a_Horarios_Dias[$perID][$jornadaID]['Feriado']                      = $feriado;

                /* LICENCIAS */
                $a_Horarios_Dias[$perID][$jornadaID]['Licencia']                     = $licencia;

                /* SUSPENSIONES */
                $a_Horarios_Dias[$perID][$jornadaID]['Suspension']                   = $suspension;



            }
        }

        return $a_Horarios_Dias;

    }
    public function generar_jornadas($a_Asistencias , $a_Horarios_Dias , $array_personas_a_controlar){


        $a_Jornadas_Filtro_Persona = array();

        /* VARIABLE PARA JORNADAS */
        $a_Jornada_temp                     = $a_Asistencias;

        /* JORNADAS FILTRO */
        foreach ($a_Horarios_Dias as $perID => $a_persona){

            /* PERSONA */
            $o_persona              = $array_personas_a_controlar[$perID];

            /* DATOS DE LA PERSONA */
            $a_Jornadas_Filtro_Persona[$perID]['per_Nombre']                = $o_persona['per_Nombre'] ;
            $a_Jornadas_Filtro_Persona[$perID]['per_Apellido']              = $o_persona['per_Apellido'] ;
            $a_Jornadas_Filtro_Persona[$perID]['per_Legajo']                = $o_persona['per_Legajo'];
            $a_Jornadas_Filtro_Persona[$perID]['per_Imagen']                = $o_persona['per_Imagen'];
            $a_Jornadas_Filtro_Persona[$perID]['Hora_Trabajo_Detalle']                = $o_persona['Hora_Trabajo_Detalle'];


            /* JORNADAS FILTRO */
            foreach ($a_persona as $jornada_filtroID => $jornada_filtro){

                /* JORNADA FILTRO_PERSONA */
                $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]                           = $jornada_filtro;

                /* DIA Y HORA JORNADA */
                $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Fecha_Hora_Inicio']      = '';
                $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Fecha_Hora_Fin']         = '';

                /* DIA JORNADA */
                $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Fecha_Inicio']           = '';
                $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Fecha_Fin']              = '';

                /* HORA JORNADA */
                $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Hora_Inicio']            = '';
                $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Hora_Fin']               = '';

                /* HORAS */
                $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Horas_Regulares']        = 0;
                $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Horas_Extra']            = 0;
                $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Horas_Feriado']          = 0;

                /* ASISTENCIA */
                $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Asistencia']             = 'No';

                /* AUSENCIA */
                $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Ausencia']               = 'Si';
                $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Ausencia_Por_Margen']    = 'No';
                $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Ausencia_Justificada']   = ($jornada_filtro['Feriado'] == 'No') && ($jornada_filtro['Licencia'] == 'No') && ($jornada_filtro['Suspension'] == 'No') ? 'No' : 'Si';

                /* LLEGADA TARDE */
                $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Llegada_Tarde']          = 'No';

                /* SALIDA TEMPRANO */
                $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Salida_Temprano']        = 'No';



                /* JORNADAS PERSONA VACIA */
                if(!isset($a_Jornada_temp[$perID]['jornadas']) || count($a_Jornada_temp[$perID]['jornadas']) == 0){
                    continue;
                }

                /* VARIABLE JORNADA FILTRO */
                $horario_jornada_filtro = $a_Horarios_Dias[$perID][$jornada_filtroID]['Fecha_Hora_Trabajo_Inicio'];

                /* JORNADAS PERSONA */
                foreach ($a_Jornada_temp[$perID]['jornadas'] as $jornada_personaID => $jornada_persona){

                    /* VARIABLE JORNADA PERSONA */
                    $horario_jornada_persona = $jornada_persona['Fecha_Hora_Trabajo_Inicio'];

                    /* JORNADA_FILTRO == JORNADA_PERSONA */
                    if($horario_jornada_filtro == $horario_jornada_persona){

                        /* DIA Y HORA JORNADA */
                        $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Fecha_Hora_Inicio']      = $a_Jornada_temp[$perID]['jornadas'][$jornada_personaID]['Fecha_Hora_Inicio'];
                        $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Fecha_Hora_Fin']         = $a_Jornada_temp[$perID]['jornadas'][$jornada_personaID]['Fecha_Hora_Fin'];

                        /* DIA JORNADA */
                        $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Fecha_Inicio']           = $a_Jornada_temp[$perID]['jornadas'][$jornada_personaID]['Fecha_Inicio'];
                        $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Fecha_Fin']              = $a_Jornada_temp[$perID]['jornadas'][$jornada_personaID]['Fecha_Fin'];

                        /* HORA JORNADA */
                        $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Hora_Inicio']            = $a_Jornada_temp[$perID]['jornadas'][$jornada_personaID]['Hora_Inicio'];
                        $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Hora_Fin']               = $a_Jornada_temp[$perID]['jornadas'][$jornada_personaID]['Hora_Fin'];

                        /* HORAS */
                        $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Horas_Regulares']        = $a_Jornada_temp[$perID]['jornadas'][$jornada_personaID]['Horas_Regulares'];
                        $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Horas_Extra']            = $a_Jornada_temp[$perID]['jornadas'][$jornada_personaID]['Horas_Extra'];
                        $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Horas_Feriado']          = $a_Jornada_temp[$perID]['jornadas'][$jornada_personaID]['Horas_Feriado'];

                        /* ASISTENCIA */
                        $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Asistencia']             = 'Si';

                        /* AUSENCIA */
                        $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Ausencia']               = 'No';
                        $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Ausencia_Por_Margen']    = $a_Jornada_temp[$perID]['jornadas'][$jornada_personaID]['Ausencia'];

                        /* LLEGADA TARDE */
                        $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Llegada_Tarde']          = $a_Jornada_temp[$perID]['jornadas'][$jornada_personaID]['Llegada_Tarde'];

                        /* SALIDA TEMPRANO */
                        $a_Jornadas_Filtro_Persona[$perID]['jornadas'][$jornada_filtroID]['Salida_Temprano']        = $a_Jornada_temp[$perID]['jornadas'][$jornada_personaID]['Salida_Temprano'];


                        /* JORNADA PERSONA FUERA DE PROXIMA BUSQUEDA */
                        unset($a_Jornada_temp[$perID]['jornadas'][$jornada_personaID]);
                        break;
                    }
                }
            }
        }

        return $a_Jornadas_Filtro_Persona;
    }
    public function generar_ausencias($a_Jornadas_Filtro_Persona , $array_personas_a_controlar, $margen_ausencia , $a_Asistencias) {

        $a_Ausencias = array();

        /* HORARIOS DE DIAS FILTRO */
        foreach ($a_Jornadas_Filtro_Persona as $perID => $a_persona) {

            /* PERSONA */
            $o_persona = $array_personas_a_controlar[$perID];

            /* DATOS DE LA PERSONA */
            $a_Ausencias[$perID]['per_Nombre']                  = $o_persona['per_Nombre'];
            $a_Ausencias[$perID]['per_Apellido']                = $o_persona['per_Apellido'];
            $a_Ausencias[$perID]['per_Legajo']                  = $o_persona['per_Legajo'];
            $a_Ausencias[$perID]['per_Imagen']                  = $o_persona['per_Imagen'];
            $a_Ausencias[$perID]['Hora_Trabajo_Detalle']        = $o_persona['Hora_Trabajo_Detalle'];

            $a_Ausencias[$perID]['Ausencias']                   = array();
            $a_Ausencias[$perID]['Ausencias_Por_Margen']        = array();
            $a_Ausencias[$perID]['Ausencias_Justificadas']      = array();
            $a_Ausencias[$perID]['Ausencias_No_Justificadas']   = array();
            $a_Ausencias[$perID]['Total']                       = 0;
            $a_Ausencias[$perID]['Total_Por_Margen']            = 0;
            $a_Ausencias[$perID]['Total_Justificadas']          = 0;
            $a_Ausencias[$perID]['Total_No_Justificadas']       = 0;

            /* VARIABLES */
            $index_ausencia                             = 0;
            $index_ausencia_por_margen                  = 0;
            $index_ausencia_justificada                 = 0;
            $index_ausencia_no_justificada              = 0;

            /* JORNADAS */
            foreach ($a_persona['jornadas'] as $jornadaID => $jornada) {

                /* JORNADA SIN HORARIO */
                if($jornada['Hora_Trabajo_Inicio'] == '00:00:00' && $jornada['Hora_Trabajo_Fin'] == '00:00:00' ){
                    continue;
                }

                /* VARIABLES */
                $horario_jornada        = $jornada['Fecha_Hora_Trabajo_Inicio'];
                $horario_jornada_time   = strtotime($horario_jornada.' +'.$margen_ausencia.' second');
                $filtro_ahora_time      = strtotime(date('Y-m-d H:i:s'));

                /* JORNADA EN CURSO */
                if($filtro_ahora_time < $horario_jornada_time){
                    continue;
                }


                /* ES AUSENCIA */
                if ($jornada['Ausencia'] == 'Si') {

                    /* AUSENCIA */
                    $a_Ausencias[$perID]['Ausencias'][$index_ausencia] = $jornada;
                    /* VARIABLE PROXIMA AUSENCIA */
                    $index_ausencia = $index_ausencia + 1;
                }

                /* ES AUSENCIA POR MARGEN */
                if ($jornada['Ausencia_Por_Margen'] == 'Si') {

                    /* AUSENCIA */
                    $a_Ausencias[$perID]['Ausencias_Por_Margen'][$index_ausencia_por_margen] = $jornada;
                    /* VARIABLE PROXIMA AUSENCIA */
                    $index_ausencia_por_margen = $index_ausencia_por_margen + 1;
                }

                if ($jornada['Ausencia'] == 'Si' && $jornada['Ausencia_Justificada'] == 'Si') {
                    /* AUSENCIA */
                    $a_Ausencias[$perID]['Ausencias_Justificadas'][$index_ausencia_justificada] = $jornada;
                    /* VARIABLE PROXIMA AUSENCIA */
                    $index_ausencia_justificada = $index_ausencia_justificada + 1;
                }

                if ($jornada['Ausencia'] == 'Si' && $jornada['Ausencia_Justificada'] == 'No') {
                    /* AUSENCIA */
                    $a_Ausencias[$perID]['Ausencias_No_Justificadas'][$index_ausencia_no_justificada] = $jornada;
                    /* VARIABLE PROXIMA AUSENCIA */
                    $index_ausencia_no_justificada = $index_ausencia_no_justificada + 1;
                }


            }

            if($o_persona['per_Hor_Tipo'] == HORARIO_FLEXIBLE){

                /* AUSENCIAS POR JORNADA */
                foreach ($a_Ausencias[$perID]['Ausencias'] as $ausenciaID => $ausencia) {
                    /* VARIABLE */
                    $fecha_ausencia = $ausencia['Fecha_Filtro'];

                    /* JORNADAS CON ASISTENCIA  */
                    foreach ($a_Asistencias[$perID]['jornadas'] as $jornadaID => $jornada) {

                        /* VARIABLE */
                        $fecha_jornada = $jornada['Fecha_Trabajo_Inicio'];

                        /* NO ES AUSENCIA */
                        if($fecha_ausencia == $fecha_jornada){
                            unset($a_Ausencias[$perID]['Ausencias'][$ausenciaID]);
                        }
                    }

                }

                /* AUSENCIAS POR MARGEN */
                foreach ($a_Ausencias[$perID]['Ausencias_Por_Margen'] as $ausenciaID => $ausencia) {
                    /* VARIABLE */
                    $fecha_ausencia = $ausencia['Fecha_Filtro'];

                    /* JORNADAS CON ASISTENCIA  */
                    foreach ($a_Asistencias[$perID]['jornadas'] as $jornadaID => $jornada) {

                        /* VARIABLE */
                        $fecha_jornada = $jornada['Fecha_Trabajo_Inicio'];

                        /* NO ES AUSENCIA */
                        if($fecha_ausencia == $fecha_jornada){
                            unset($a_Ausencias[$perID]['Ausencias_Por_Margen'][$ausenciaID]);
                        }
                    }

                }

                /* AUSENCIAS JUSTIFICADAS */
                foreach ($a_Ausencias[$perID]['Ausencias_Justificadas'] as $ausenciaID => $ausencia) {
                    /* VARIABLE */
                    $fecha_ausencia = $ausencia['Fecha_Filtro'];

                    /* JORNADAS CON ASISTENCIA  */
                    foreach ($a_Asistencias[$perID]['jornadas'] as $jornadaID => $jornada) {

                        /* VARIABLE */
                        $fecha_jornada = $jornada['Fecha_Trabajo_Inicio'];

                        /* NO ES AUSENCIA */
                        if($fecha_ausencia == $fecha_jornada){
                            unset($a_Ausencias[$perID]['Ausencias_Justificadas'][$ausenciaID]);
                        }
                    }

                }

                /* AUSENCIAS JUSTIFICADAS */
                foreach ($a_Ausencias[$perID]['Ausencias_No_Justificadas'] as $ausenciaID => $ausencia) {
                    /* VARIABLE */
                    $fecha_ausencia = $ausencia['Fecha_Filtro'];

                    /* JORNADAS CON ASISTENCIA  */
                    foreach ($a_Asistencias[$perID]['jornadas'] as $jornadaID => $jornada) {

                        /* VARIABLE */
                        $fecha_jornada = $jornada['Fecha_Trabajo_Inicio'];

                        /* NO ES AUSENCIA */
                        if($fecha_ausencia == $fecha_jornada){
                            unset($a_Ausencias[$perID]['Ausencias_No_Justificadas'][$ausenciaID]);
                        }
                    }

                }



                /* VARIABLES */
                $temp_array_ausencias                   = array();
                $temp_array_ausencias_por_margen        = array();
                $temp_array_ausencias_justificadas      = array();
                $temp_array_ausencias_no_justificadas   = array();

                /* AUSENCIAS POR JORNADA */
                foreach ($a_Ausencias[$perID]['Ausencias'] as $ausenciaID => $ausencia) {
                    $fecha_ausencia = $ausencia['Fecha_Filtro'];
                    $temp_array_ausencias[$fecha_ausencia] = $ausencia;
                }
                $a_Ausencias[$perID]['Ausencias'] = $temp_array_ausencias;


                /* AUSENCIAS POR MARGEN */
                foreach ($a_Ausencias[$perID]['Ausencias_Por_Margen'] as $ausenciaID => $ausencia) {
                    $fecha_ausencia = $ausencia['Fecha_Filtro'];
                    $temp_array_ausencias_por_margen[$fecha_ausencia] = $ausencia;
                }
                $a_Ausencias[$perID]['Ausencias_Por_Margen'] = $temp_array_ausencias_por_margen;


                /* AUSENCIAS JUSTIFICADA */
                foreach ($a_Ausencias[$perID]['Ausencias_Justificadas'] as $ausenciaID => $ausencia) {
                    $fecha_ausencia = $ausencia['Fecha_Filtro'];
                    $temp_array_ausencias_justificadas[$fecha_ausencia] = $ausencia;
                }
                $a_Ausencias[$perID]['Ausencias_Justificadas'] = $temp_array_ausencias_justificadas;

                /* AUSENCIAS JUSTIFICADA */
                foreach ($a_Ausencias[$perID]['Ausencias_No_Justificadas'] as $ausenciaID => $ausencia) {
                    $fecha_ausencia = $ausencia['Fecha_Filtro'];
                    $temp_array_ausencias_no_justificadas[$fecha_ausencia] = $ausencia;
                }
                $a_Ausencias[$perID]['Ausencias_No_Justificadas'] = $temp_array_ausencias_no_justificadas;

            }


            /* TOTAL AUSENCIAS */
            $a_Ausencias[$perID]['Total']                   = count($a_Ausencias[$perID]['Ausencias']);
            $a_Ausencias[$perID]['Total_Por_Margen']        = count($a_Ausencias[$perID]['Ausencias_Por_Margen']);
            $a_Ausencias[$perID]['Total_Justificadas']      = count($a_Ausencias[$perID]['Ausencias_Justificadas']);
            $a_Ausencias[$perID]['Total_No_Justificadas']   = count($a_Ausencias[$perID]['Ausencias_No_Justificadas']);

        }

        return $a_Ausencias;
    }
    public function generar_intervalo($a_Jornadas_Filtro_Persona, $array_personas_a_controlar, $a_Asistencias, $a_Ausencias, $a_Llegadas_Tarde, $a_Salidas_Temprano, $a_Suspensiones, $a_Licencias) {

        $a_Intervalo = array();

        /* HORARIOS DE DIAS FILTRO */
        foreach ($a_Jornadas_Filtro_Persona as $perID => $a_persona) {

            /* PERSONA */
            $o_persona              = $array_personas_a_controlar[$perID];

            /* DATOS DE LA PERSONA */
            $a_Intervalo[$perID]['per_Nombre']              = $o_persona['per_Nombre'] ;
            $a_Intervalo[$perID]['per_Apellido']            = $o_persona['per_Apellido'] ;
            $a_Intervalo[$perID]['per_Legajo']              = $o_persona['per_Legajo'];
            $a_Intervalo[$perID]['per_Imagen']              = $o_persona['per_Imagen'];

            /* ASISTENCIAS */
            $a_Intervalo[$perID]['Asistencias']             = $a_Asistencias[$perID]['Total'];

            /* CONTROL */
            $a_Intervalo[$perID]['Ausencias']               = $a_Ausencias[$perID]['Total_No_Justificadas'];
            $a_Intervalo[$perID]['Llegadas_Tarde']          = $a_Llegadas_Tarde[$perID]['Total'];
            $a_Intervalo[$perID]['Salidas_Temprano']        = $a_Salidas_Temprano[$perID]['Total'];

            /* EXCEPCIONES */
            $a_Intervalo[$perID]['Suspensiones']            = isset($a_Suspensiones[$perID]) ? count($a_Suspensiones[$perID]) : 0;
            $a_Intervalo[$perID]['Licencias']               = isset($a_Licencias[$perID]) ? count($a_Licencias[$perID]) : 0;

            /* HORAS */
            $a_Intervalo[$perID]['Horas_Horario']           = 0;
            $a_Intervalo[$perID]['Horas_Regulares']         = 0;
            $a_Intervalo[$perID]['Horas_Extra']             = 0;
            $a_Intervalo[$perID]['Horas_Feriado']           = 0;


            /* TOTALES */
            foreach ($a_persona['jornadas'] as $jornadaID => $jornada) {

                /* HORAS */
                $a_Intervalo[$perID]['Horas_Horario']   += $jornada['Horas_Horario'];
                $a_Intervalo[$perID]['Horas_Regulares'] += $jornada['Horas_Regulares'];
                $a_Intervalo[$perID]['Horas_Extra']     += $jornada['Horas_Extra'];
                $a_Intervalo[$perID]['Horas_Feriado']   += $jornada['Horas_Feriado'];
            }

            if($o_persona['per_Hor_Tipo'] == HORARIO_FLEXIBLE){
                $a_Intervalo[$perID]['Horas_Horario']   = 'Flexible';
            }

        }

        return $a_Intervalo;
    }

    public function generar_reporte() {

        $a_data = array();

        $Filtro_Tipo = $this->_tipo;

        switch ($Filtro_Tipo) {

            case 'Llegadas_Tarde_Log':
                break;
            case 'Salidas_Temprano_Log':
            case 'Listado_Ausencias_Log':
                break;
            case 'Registros':
            case '39':
            case 'Marcaciones':
            case '40':
            case 'Jornadas':
            case '41':
            case 'Intervalo':
            case '42':
            case 'Llegadas_Tarde':
            case '43':
            case 'Salidas_Temprano':
            case '44':
            case 'Listado_Asistencias':
            case '45':
            case 'Listado_Ausencias':
            case '46':
                $a_data                 = $this->generar_reporte_control_personal();
                break;

            case 'Personas':
            case '51':
                $a_data                 = $this->generar_reporte_personas();
                break;

            case 'Grupos':
            case '51':
                $a_data                 = $this->generar_reporte_grupos();
                break;

            case 'Horarios_Trabajo':
            case '61':
                $a_data                 = $this->generar_reporte_horarios_trabajo();
                break;

            case 'Horarios_Flexibles':
            case '62':
                $a_data                 = $this->generar_reporte_horarios_flexibles();
                break;


            case 'Horarios_Multiples':
            case '63':
                $a_data                 = $this->generar_reporte_horarios_multiples();
                break;

            case 'Horarios_Rotativos':
            case '64':
                $a_data                 = $this->generar_reporte_horarios_rotativos();
                break;

            case 'Suspensiones':
            case '71':
                $a_data                 = $this->generar_reporte_suspensiones();
                break;

            case 'Licencias':
            case '72':
                $a_data                 = $this->generar_reporte_licencias();
                break;

            case 'Feriados':
            case '73':
                $a_data                 = $this->generar_reporte_feriados();
                break;

            case 'Alertas':
            case '81':
                $a_data                 = $this->generar_reporte_alertas();
                break;

            case 'Reportes_Automaticos':
            case '82':
                $a_data                 = $this->generar_reporte_reportes_automaticos();
                break;

            case 'Modelo_Importar_Registros':
                $a_data                 = array();
                break;

            case 'Modelo_Importar_Personas':
                $a_data                 = array();
                break;

            default:
                break;
        }

        return $a_data;

    }

    public function get_personas_a_controlar(){
        $array_personas_a_controlar      = Persona_L::obtenerDesdeFiltroArray($this->_filtro_persona);
        return $array_personas_a_controlar;
    }

    public function generar_reporte_control_personal() {

        $dias_red_2                             = array( 1 => _("Dom"), _("Lun"), _("Mar"), _("Mie"), _("Jue"), _("Vie"), _("Sab"));
        $dias                                   = array("Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb");

        $T_Tipo                                 = $this->_tipo;
        $T_Filtro_Persona                       = $this->_filtro_persona;

        $Fecha_Desde                            = $this->_fecha_desde;
        $Fecha_Hasta                            = $this->_fecha_hasta;

        // VARUABLES
        $a_Logs_Con_Horario                     = array();
        $o_Listado                              = array();
        $o_Logs_Por_Persona                     = array();
        $csv_excel_data                         = array();

        // PERSONAS
        $array_personas_a_controlar             = $this->get_personas_a_controlar();

        //printear('$array_personas_a_controlar');
        //printear($array_personas_a_controlar);

        // LOGS
        $o_Logs         = Logs_Equipo_L::obtenerDesdeFiltroArray($T_Filtro_Persona["Persona"],$T_Filtro_Persona["Grupo"],$Fecha_Desde,$Fecha_Hasta,$T_Filtro_Persona["Equipo"]);
        //printear('$o_Logs');
        //printear($o_Logs);


        // EQUIPOS
        $a_Equipos               = Equipo_L::obtenerTodosenArray();


        // MARGENES
        $margen_llegada_tarde    = Config_L::p("margen_llegada_tarde") * 60;
        $margen_salidas_temprano = Config_L::p("margen_salida") * 60;
        $margen_ausencia         = Config_L::obtenerPorParametro("margen_ausencia")->getValor() * 60;


        // HORARIOS
        $a_Hora_Trabajo          = Hora_Trabajo_L::obtenerTodosenArray();
        $a_Horarios_Rotativos    = Horario_Rotativo_L::obtenerTodosenArray();
        $a_Horarios_Flexibles    = Horario_Flexible_L::obtenerTodosenArray();
        $a_Horarios_Multiples    = Horario_Multiple_L::obtenerTodosenArray();



        // LOGS POR PERSONA
        if (!is_null($o_Logs)) {
            foreach ($o_Logs as $logID => $log) {
                $perID = $log['leq_Per_Id'];
                if (isset($array_personas_a_controlar[$perID])) {
                    $o_Logs_Por_Persona[$perID][] = $log;
                }
            }
        }

        // LOGS CON HORARIOS
        if (!is_null($o_Logs_Por_Persona)) {

            /* DETALLE DE HORARIO A PERSONA */
            foreach($array_personas_a_controlar as $perID => $o_persona){
                /* DETALLE DEL HORARIO */
                switch ($o_persona['per_Hor_Tipo']) {
                    case HORARIO_NORMAL:
                        $o_hora_trabajo = $a_Hora_Trabajo[$o_persona['per_Hor_Id']];
                        break;

                    case HORARIO_FLEXIBLE:
                        $o_hora_trabajo = $a_Horarios_Flexibles[$o_persona['per_Hor_Id']];
                        break;

                    case HORARIO_ROTATIVO:
                        $o_hora_trabajo = $a_Horarios_Rotativos[$o_persona['per_Hor_Id']];
                        break;

                    case HORARIO_MULTIPLE:
                        $o_hora_trabajo = $a_Horarios_Multiples[$o_persona['per_Hor_Id']];
                        break;

                    case 0: default:
                    $o_hora_trabajo = null;
                    break;
                }

                $array_personas_a_controlar[$perID]['Hora_Trabajo_Detalle']     = !is_null($o_hora_trabajo)     ?       $o_hora_trabajo->getDetalle()                           : 'Sin Horario' ;
            }


            /* LOGS POR PERSONA */
            foreach ($o_Logs_Por_Persona as $perID => $logs_por_persona) {

                /* PERSONA */
                $o_persona              = $array_personas_a_controlar[$perID];


                /* DATOS DE LA PERSONA */
                $a_Logs_Con_Horario[$perID]['per_Nombre']                = $o_persona['per_Nombre'] ;
                $a_Logs_Con_Horario[$perID]['per_Apellido']              = $o_persona['per_Apellido'] ;
                $a_Logs_Con_Horario[$perID]['per_Legajo']                = $o_persona['per_Legajo'];
                $a_Logs_Con_Horario[$perID]['per_Imagen']                = $o_persona['per_Imagen'];


                /* DETALLE DEL HORARIO */
                switch ($o_persona['per_Hor_Tipo']) {
                    case HORARIO_NORMAL:
                        $o_hora_trabajo = $a_Hora_Trabajo[$o_persona['per_Hor_Id']];
                        break;

                    case HORARIO_FLEXIBLE:
                        $o_hora_trabajo = $a_Horarios_Flexibles[$o_persona['per_Hor_Id']];
                        break;

                    case HORARIO_ROTATIVO:
                        $o_hora_trabajo = $a_Horarios_Rotativos[$o_persona['per_Hor_Id']];
                        break;

                    case HORARIO_MULTIPLE:
                        $o_hora_trabajo = $a_Horarios_Multiples[$o_persona['per_Hor_Id']];
                        break;

                    case 0: default:
                    $o_hora_trabajo = null;
                    break;
                }
                $a_Logs_Con_Horario[$perID]['Hora_Trabajo']                     = !is_null($o_hora_trabajo)     ?       $o_hora_trabajo->getTextoDiasResumido($dias_red_2)      : ''            ;
                $a_Logs_Con_Horario[$perID]['Hora_Trabajo_Detalle']             = !is_null($o_hora_trabajo)     ?       $o_hora_trabajo->getDetalle()                           : 'Sin Horario' ;

                /* DATOS DEL LOG */
                foreach($logs_por_persona as $logID => $log) {

                    /* VARIABLES DATE */
                    $dia_hora_log       = date('Y-m-d H:i:s', strtotime($log['leq_Fecha_Hora']));
                    $dia_log            = date('Y-m-d', strtotime($log['leq_Fecha_Hora']));
                    $hora_log           = date('H:i:s', strtotime($log['leq_Fecha_Hora']));

                    /* VARIABLES TIME */
                    $time_dia_hora_log  =   strtotime($dia_hora_log);
                    $time_dia_log       =   strtotime($dia_log);
                    $time_hora_log      =   strtotime($dia_log);

                    /* VARIABLES HORARIO */
                    $horario            = array();

                    /* HORA DE HORARIO */
                    switch ($o_persona['per_Hor_Tipo']) {

                        case HORARIO_NORMAL:
                            $o_hora_trabajo         = $a_Hora_Trabajo[$o_persona['per_Hor_Id']];
                            $horario                = $o_hora_trabajo->obtenerHorarioCercano($dia_hora_log);
                            break;

                        case HORARIO_FLEXIBLE:
                            $o_hora_trabajo         = $a_Horarios_Flexibles[$o_persona['per_Hor_Id']];
                            $horario                = $o_hora_trabajo->obtenerHorarioCercano($dia_hora_log);
                            break;

                        case HORARIO_ROTATIVO:
                            $o_hora_trabajo         = $a_Horarios_Rotativos[$o_persona['per_Hor_Id']];
                            $horario                = $o_hora_trabajo->obtenerHorarioCercano($dia_hora_log);
                            break;

                        case HORARIO_MULTIPLE:
                            $o_hora_trabajo         = $a_Horarios_Multiples[$o_persona['per_Hor_Id']];
                            $horario                = $o_hora_trabajo->obtenerHorarioCercano($dia_hora_log);
                            break;

                        case 0:default:
                        $horario = null;
                        break;
                    }

                    /* DIA SEMANA */
                    $a_Logs_Con_Horario[$perID]['logs'][$logID]['Dia_Semana']                   = $dias[date('w', strtotime($dia_hora_log))];

                    /* FECHA Y HORA HORARIO */
                    $a_Logs_Con_Horario[$perID]['logs'][$logID]['Fecha_Hora_Trabajo_Inicio']    =  $horario == null ?    ''     : $horario[0];
                    $a_Logs_Con_Horario[$perID]['logs'][$logID]['Fecha_Hora_Trabajo_Fin']       =  $horario == null ?    ''     : $horario[1];

                    /* FECHA HORARIO */
                    $a_Logs_Con_Horario[$perID]['logs'][$logID]['Fecha_Trabajo_Inicio']         = $horario == null ?    ''      : date('Y-m-d', strtotime($horario[0]));
                    $a_Logs_Con_Horario[$perID]['logs'][$logID]['Fecha_Trabajo_Fin']            = $horario == null ?    ''      : date('Y-m-d', strtotime($horario[1]));

                    /* HORA HORARIO */
                    $a_Logs_Con_Horario[$perID]['logs'][$logID]['Hora_Trabajo_Inicio']          = $horario == null ?    ''      : date('H:i:s', strtotime($horario[0]));
                    $a_Logs_Con_Horario[$perID]['logs'][$logID]['Hora_Trabajo_Fin']             = $horario == null ?    ''      : date('H:i:s', strtotime($horario[1]));


                    /* FECHA Y HORA LOG */
                    $a_Logs_Con_Horario[$perID]['logs'][$logID]['Fecha_Hora']       = $dia_hora_log;
                    $a_Logs_Con_Horario[$perID]['logs'][$logID]['Fecha']            = $dia_log;
                    $a_Logs_Con_Horario[$perID]['logs'][$logID]['Hora']             = $hora_log;

                    /* LOG ID */
                    $a_Logs_Con_Horario[$perID]['logs'][$logID]['Log_Id']           = $log['leq_Id'];
                    $a_Logs_Con_Horario[$perID]['logs'][$logID]['Log_Editado']      = $log['leq_Editado'];

                    /* EQUIPO */
                    $a_Logs_Con_Horario[$perID]['logs'][$logID]['Equipo']           = isset($a_Equipos[$log['leq_Eq_Id']])      ?       $a_Equipos[$log['leq_Eq_Id']]->getDetalle()     :       "Web";
                    $a_Logs_Con_Horario[$perID]['logs'][$logID]['EquipoID']         = $log['leq_Eq_Id'];;
                    $a_Logs_Con_Horario[$perID]['logs'][$logID]['Lector']           = $log['leq_Lector'];
                    $a_Logs_Con_Horario[$perID]['logs'][$logID]['Dedo']             = $log['leq_Dedo'];

                }


            }
        }


        // LOGS POR PAR
        if (!is_null($a_Logs_Con_Horario)){

            $o_Listado = array();

            foreach ($a_Logs_Con_Horario as $perID => $a_persona) {

                /* PERSONA */
                $o_persona              = $array_personas_a_controlar[$perID];

                /* DATOS DE LA PERSONA */
                $o_Listado[$perID]['per_Nombre']                = $o_persona['per_Nombre'] ;
                $o_Listado[$perID]['per_Apellido']              = $o_persona['per_Apellido'] ;
                $o_Listado[$perID]['per_Legajo']                = $o_persona['per_Legajo'];
                $o_Listado[$perID]['per_Imagen']                = $o_persona['per_Imagen'];
                $o_Listado[$perID]['Hora_Trabajo_Detalle']      = $o_persona['Hora_Trabajo_Detalle'];
                $o_Listado[$perID]['Hora_Trabajo']              = $a_Logs_Con_Horario[$perID]['Hora_Trabajo'];
                $o_Listado[$perID]['logs']                      = array();


                $pair_logId                 = 0;
                $dia_hora_log_inicio        = '';
                $ingreso_egreso             = 'Ingreso';
                $dia_hora_horario_inicio    = '';
                $dia_hora_horario_ingreso   = '';
                $dia_hora_horario_egreso    = '';

                $dia_hora_horario_egreso_log_ingreso = '';

                /* DATOS DEL LOG */
                foreach($a_persona['logs'] as $logID => $log) {

                    /* VARIABLES DATE */
                    $dia_hora_log       = date('Y-m-d H:i:s', strtotime($log['Fecha_Hora']));

                    /* ES PROXIMA JORNADA */
                    if($ingreso_egreso == "Egreso"){

                        $dia_hora_horario_egreso    = $a_Logs_Con_Horario[$perID]['logs'][$logID]['Fecha_Hora_Trabajo_Inicio'];

                        /* ES PROXIMA JORNADA */
                        if($dia_hora_horario_ingreso != $dia_hora_horario_egreso){

                            if(strtotime($dia_hora_log) > strtotime($dia_hora_horario_egreso_log_ingreso." +12 hours") ){
                                $pair_logId                 = $pair_logId +1;
                                $ingreso_egreso             = 'Ingreso';
                            }
                        }


                        /* VARIABLES PARA CALCULO DE INTERVALOS
                        $dia_hora_log_fin                                               = date_create($dia_hora_log);
                        $dia_hora_log_inicio_con_margen_doce_horas                      = date('Y-m-d H:i:s', strtotime("+12 hour", strtotime($o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Inicio'])));
                        $dia_hora_log_inicio_con_margen_doce_horas                      = date_create($dia_hora_log_inicio_con_margen_doce_horas);
                        */
                        /* ES PROXIMA JORNADA
                        if($dia_hora_log_fin > $dia_hora_log_inicio_con_margen_doce_horas){
                            $pair_logId = $pair_logId +1;
                            $ingreso_egreso = 'Ingreso';
                        } */
                    }

                    /* INGRESO/EGRESO */
                    switch ($ingreso_egreso){
                        case "Ingreso":

                            /* DIA SEMANA */
                            $o_Listado[$perID]['logs'][$pair_logId]['Dia_Semana_Inicio']        = $dias[date('w', strtotime($log['Fecha_Hora']))];
                            $o_Listado[$perID]['logs'][$pair_logId]['Dia_Semana_Fin']           = '';

                            /* FECHA Y HORA LOG */
                            $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Inicio']        = $log['Fecha_Hora'];
                            $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Fin']           = '';

                            /* FECHA LOG */
                            $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Inicio']             = $log['Fecha'];
                            $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Fin']                = $log['Fecha'];

                            /* HORA LOG */
                            $o_Listado[$perID]['logs'][$pair_logId]['Hora_Inicio']              = $log['Hora'];
                            $o_Listado[$perID]['logs'][$pair_logId]['Hora_Fin']                 = '';

                            /* LOG ID */
                            $o_Listado[$perID]['logs'][$pair_logId]['Log_Id_Inicio']            =  $log['Log_Id']  ;
                            $o_Listado[$perID]['logs'][$pair_logId]['Log_Id_Fin']               = "";

                            /* LOG EDITADO */
                            $o_Listado[$perID]['logs'][$pair_logId]['Log_Editado_Inicio']       =  $log['Log_Editado'];
                            $o_Listado[$perID]['logs'][$pair_logId]['Log_Editado_Fin']          = "";

                            /* EQUIPO */
                            $o_Listado[$perID]['logs'][$pair_logId]['Equipo_Inicio']            = $log['Equipo'];
                            $o_Listado[$perID]['logs'][$pair_logId]['Equipo_Fin']               = "";

                            /* EQUIPO ID */
                            $o_Listado[$perID]['logs'][$pair_logId]['EquipoID_Inicio']          = $log['EquipoID'];
                            $o_Listado[$perID]['logs'][$pair_logId]['EquipoID_Fin']             = "";

                            /* LECTOR */
                            $o_Listado[$perID]['logs'][$pair_logId]['Lector_Inicio']            = $log['Lector'];
                            $o_Listado[$perID]['logs'][$pair_logId]['Lector_Fin']               = "";

                            /* DEDO */
                            $o_Listado[$perID]['logs'][$pair_logId]['Dedo_Inicio']              = $log['Dedo'];
                            $o_Listado[$perID]['logs'][$pair_logId]['Dedo_Fin']                 = "";






                            if($pair_logId > 0 && strtotime($dia_hora_log) < strtotime($o_Listado[$perID]['logs'][$pair_logId-1]['Fecha_Hora_Trabajo_Fin'])){

                                /* DIA Y HORA HORARIO */
                                $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Trabajo_Inicio']=  $o_Listado[$perID]['logs'][$pair_logId-1]['Fecha_Hora_Trabajo_Inicio'];
                                $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Trabajo_Fin']   =  $o_Listado[$perID]['logs'][$pair_logId-1]['Fecha_Hora_Trabajo_Fin'];

                                /* DIA HORARIO */
                                $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Trabajo_Fin']        = $o_Listado[$perID]['logs'][$pair_logId-1]['Fecha_Trabajo_Fin'];
                                $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Trabajo_Inicio']     = $o_Listado[$perID]['logs'][$pair_logId-1]['Fecha_Trabajo_Inicio'];

                                /* HORA HORARIO */
                                $o_Listado[$perID]['logs'][$pair_logId]['Hora_Trabajo_Inicio']      = $o_Listado[$perID]['logs'][$pair_logId-1]['Hora_Trabajo_Inicio'];
                                $o_Listado[$perID]['logs'][$pair_logId]['Hora_Trabajo_Fin']         = $o_Listado[$perID]['logs'][$pair_logId-1]['Hora_Trabajo_Fin'];

                            }
                            else{
                                /* DIA Y HORA HORARIO */
                                $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Trabajo_Inicio']= $log['Fecha_Hora_Trabajo_Inicio'];
                                $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Trabajo_Fin']   = $log['Fecha_Hora_Trabajo_Fin'];

                                /* DIA HORARIO */
                                $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Trabajo_Fin']        = $log['Fecha_Trabajo_Fin'];
                                $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Trabajo_Inicio']     = $log['Fecha_Trabajo_Inicio'];

                                /* HORA HORARIO */
                                $o_Listado[$perID]['logs'][$pair_logId]['Hora_Trabajo_Inicio']      = $log['Hora_Trabajo_Inicio'];
                                $o_Listado[$perID]['logs'][$pair_logId]['Hora_Trabajo_Fin']         = $log['Hora_Trabajo_Fin'];
                            }


                            /* VARIABLES PARA CALCULO DE INTERVALOS */
                            $dia_hora_log_inicio                                                = date_create($dia_hora_log);
                            $dia_hora_horario_inicio                                            = date_create($o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Trabajo_Inicio']);
                            $dia_hora_horario_fin                                               = date_create( $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Trabajo_Fin']);


                            /* HORAS HORARIO */
                            $diff                                                               = date_diff($dia_hora_horario_fin ,$dia_hora_horario_inicio);
                            $interval                                                           = $diff->format("%H:%I:%S");
                            $o_Listado[$perID]['logs'][$pair_logId]['Total_Horario']            = round(abs(DateTimeHelper::time_to_sec($interval) / 3600), 2);

                            /* TOTAL INTERVALO */
                            $o_Listado[$perID]['logs'][$pair_logId]['Total_Intervalo']          = 0;

                            /* HORAS EXTRA */
                            $o_Listado[$perID]['logs'][$pair_logId]['Horas_Extra']              = 0;

                            /* HORAS REGULARES */
                            $o_Listado[$perID]['logs'][$pair_logId]['Horas_Regulares']          = 0;



                            /* VARIABLES PARA CALCULOS DE CONTROL */
                            $dia_hora_horario_inicio_con_margen_tarde                       = date('Y-m-d H:i:s', strtotime("+".$margen_llegada_tarde." second", strtotime($o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Trabajo_Inicio'])));
                            $dia_hora_horario_inicio_con_margen_ausencia                    = date('Y-m-d H:i:s', strtotime("+".$margen_ausencia." second", strtotime($o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Trabajo_Inicio'])));

                            $dia_hora_horario_inicio_con_margen_tarde                       = date_create($dia_hora_horario_inicio_con_margen_tarde);
                            $dia_hora_horario_inicio_con_margen_ausencia                    = date_create($dia_hora_horario_inicio_con_margen_ausencia);

                            /* ASISTENCIA */
                            $o_Listado[$perID]['logs'][$pair_logId]['Asistencia']           = 'Si';

                            /* AUSENCIA */
                            $o_Listado[$perID]['logs'][$pair_logId]['Ausencia']             = ($dia_hora_log_inicio > $dia_hora_horario_inicio_con_margen_ausencia)     ? "Si" : "No";

                            /* LLEGADA TARDE */
                            $o_Listado[$perID]['logs'][$pair_logId]['Llegada_Tarde']        = ($dia_hora_log_inicio > $dia_hora_horario_inicio_con_margen_tarde)        ? "Si" : "No";

                            /* SALIDA TEMPRANO */
                            $o_Listado[$perID]['logs'][$pair_logId]['Salida_Temprano']      = 'No registra salida';



                            /* VARIABLES PARA SIGUIENTE BUCLE */
                            $ingreso_egreso                             = 'Egreso';
                            $dia_hora_horario_ingreso                   = $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Trabajo_Inicio'];
                            $dia_hora_horario_egreso_log_ingreso        = $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Trabajo_Fin'];

                            break;

                        case "Egreso":


                            /* DIA SEMANA */
                            $o_Listado[$perID]['logs'][$pair_logId]['Dia_Semana_Fin']       = $dias[date('w', strtotime($log['Fecha_Hora']))];

                            /* FECHA Y HORA LOG */
                            $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Fin']       = $log['Fecha_Hora'];

                            /* FECHA LOG */
                            $o_Listado[$perID]['logs'][$pair_logId]['Fecha_Fin']            = $log['Fecha'];

                            /* HORA LOG */
                            $o_Listado[$perID]['logs'][$pair_logId]['Hora_Fin']             = $log['Hora'];

                            /* LOG ID */
                            $o_Listado[$perID]['logs'][$pair_logId]['Log_Id_Fin']           = $log['Log_Id'];

                            /* LOG EDITADO */
                            $o_Listado[$perID]['logs'][$pair_logId]['Log_Editado_Fin']      = $log['Log_Editado'];

                            /* EQUIPO */
                            $o_Listado[$perID]['logs'][$pair_logId]['Equipo_Fin']           = $log['Equipo'];

                            /* EQUIPO ID */
                            $o_Listado[$perID]['logs'][$pair_logId]['EquipoID_Fin']         = $log['EquipoID'];

                            /* LECTOR */
                            $o_Listado[$perID]['logs'][$pair_logId]['Lector_Fin']           = $log['Lector'];

                            /* DEDO */
                            $o_Listado[$perID]['logs'][$pair_logId]['Dedo_Fin']             = $log['Dedo'];


                            /* VARIABLES PARA CALCULO DE INTERVALOS */
                            $dia_hora_horario_inicio                                        = date_create($o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Trabajo_Inicio']);
                            $dia_hora_horario_fin                                           = date_create($o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Trabajo_Fin']);
                            $dia_hora_log_fin                                               = date_create($dia_hora_log);

                            /* TOTAL INTERVALO */
                            $diff                                                           = date_diff($dia_hora_log_fin ,$dia_hora_log_inicio);
                            $interval                                                       = $diff->format("%H:%I:%S");
                            $total_horas_intervalo                                          = round(abs(DateTimeHelper::time_to_sec($interval) / 3600), 2);
                            $o_Listado[$perID]['logs'][$pair_logId]['Total_Intervalo']      = $total_horas_intervalo;

                            /* HORAS EXTRA */
                            $diff                                                           = date_diff($dia_hora_horario_fin ,$dia_hora_horario_inicio);
                            $interval                                                       = $diff->format("%H:%I:%S");
                            $total_horas_horario                                            = round(abs(DateTimeHelper::time_to_sec($interval) / 3600), 2);
                            $total_horas_extra                                              = ($total_horas_intervalo - $total_horas_horario) > 0 ? ($total_horas_intervalo - $total_horas_horario) : 0;
                            $o_Listado[$perID]['logs'][$pair_logId]['Horas_Extra']          = round($total_horas_extra,2);

                            /* HORAS REGULARES */
                            $o_Listado[$perID]['logs'][$pair_logId]['Horas_Regulares']      = $total_horas_intervalo - $total_horas_extra;


                            /* VARIABLES PARA CALCULOS DE CONTROL */
                            $dia_hora_horario_fin_con_margen_temprano                       = date('Y-m-d H:i:s', strtotime("-".$margen_salidas_temprano." second", strtotime($o_Listado[$perID]['logs'][$pair_logId]['Fecha_Hora_Trabajo_Fin'])));
                            $dia_hora_horario_fin_con_margen_temprano                       = date_create($dia_hora_horario_fin_con_margen_temprano);


                            /* SALIDA TEMPRANO */
                            $o_Listado[$perID]['logs'][$pair_logId]['Salida_Temprano']      = ($dia_hora_log_fin < $dia_hora_horario_fin_con_margen_temprano)           ? "Si" : "No";


                            /* VARIABLES PARA SIGUIENTE BUCLE */
                            $pair_logId = $pair_logId +1;
                            $ingreso_egreso = 'Ingreso';
                            break;
                    }

                }


            }

        }

        /* EXCEPCIONES */
        $a_Feriados         = Feriado_L::obtenerPorIntervalo($Fecha_Desde,$Fecha_Hasta);
        $a_Licencias        = Licencias_L::obtenerPorIntervalo($Fecha_Desde,$Fecha_Hasta);
        $a_Suspensiones     = Suspensions_L::obtenerPorIntervalo($Fecha_Desde,$Fecha_Hasta);


        // REPORTES
        switch ($T_Tipo) {

            case 'Registros':
            case '39':
                return $a_Logs_Con_Horario;
                break;

            case 'Marcaciones':
            case '40':

                return $o_Listado;
                break;

            case 'Jornadas':
            case '41':

                // ASISTENCIAS
                $a_Asistencias              = $this->generar_asistencias($array_personas_a_controlar, $o_Listado, $a_Feriados);

                // HORARIOS DIAS
                $a_Horarios_Dias            = $this->generar_horarios_dias($Fecha_Desde, $Fecha_Hasta, $array_personas_a_controlar, $a_Hora_Trabajo, $a_Horarios_Rotativos, $a_Horarios_Multiples, $a_Horarios_Flexibles, $dias, $a_Feriados , $a_Licencias, $a_Suspensiones);

                /* JORNADAS */
                $a_Jornadas_Filtro_Persona  = $this->generar_jornadas($a_Asistencias , $a_Horarios_Dias , $array_personas_a_controlar);


                return $a_Jornadas_Filtro_Persona;

                break;

            case 'Intervalo':
            case '42':

                // ASISTENCIAS
                $a_Asistencias              = $this->generar_asistencias($array_personas_a_controlar, $o_Listado, $a_Feriados);

                // LLEGADAS TARDE
                $a_Llegadas_Tarde           = $this->generar_llegadas_tarde($a_Asistencias,$array_personas_a_controlar);

                // SALIDAS TEMPRANO
                $a_Salidas_Temprano         = $this->generar_salidas_temprano($a_Asistencias,$array_personas_a_controlar);

                // HORARIOS DIAS
                $a_Horarios_Dias            = $this->generar_horarios_dias($Fecha_Desde, $Fecha_Hasta, $array_personas_a_controlar, $a_Hora_Trabajo, $a_Horarios_Rotativos, $a_Horarios_Multiples, $a_Horarios_Flexibles, $dias, $a_Feriados , $a_Licencias, $a_Suspensiones);

                /* JORNADAS */
                $a_Jornadas_Filtro_Persona  = $this->generar_jornadas($a_Asistencias , $a_Horarios_Dias , $array_personas_a_controlar);

                // AUSENCIAS
                $a_Ausencias                = $this->generar_ausencias($a_Jornadas_Filtro_Persona , $array_personas_a_controlar, $margen_ausencia , $a_Asistencias);

                // INTERVALO
                $a_Intervalo                = $this->generar_intervalo($a_Jornadas_Filtro_Persona, $array_personas_a_controlar, $a_Asistencias, $a_Ausencias, $a_Llegadas_Tarde, $a_Salidas_Temprano, $a_Suspensiones, $a_Licencias);


                return $a_Intervalo;

                break;

            case 'Llegadas_Tarde':
            case '43':

                // ASISTENCIAS
                $a_Asistencias              = $this->generar_asistencias($array_personas_a_controlar, $o_Listado, $a_Feriados);

                // LLEGADAS TARDE
                $a_Llegadas_Tarde           = $this->generar_llegadas_tarde($a_Asistencias,$array_personas_a_controlar);

                $csv_excel_data[] = array('Legajo', 'Apellido', 'Nombre', 'Acumula','Fecha','Horario Ingreso','Horario Egreso','Registro');

                foreach ($a_Llegadas_Tarde as $per_Id => $item){
                    foreach ($item['Llegadas_Tarde'] as $key_log => $log){
                        $csv_excel_data[] = array(
                            $item['per_Legajo'],
                            $item['per_Apellido'],
                            $item['per_Nombre'],
                            $item['Total'],
                            date("d-m-Y", strtotime($log['Fecha_Inicio'])),
                            $log['Hora_Trabajo_Inicio'],
                            $log['Hora_Trabajo_Fin'],
                            $log['Hora_Inicio']);
                    }
                }

                return $a_Llegadas_Tarde;
                break;

            case 'Salidas_Temprano':
            case '44':

                // ASISTENCIAS
                $a_Asistencias              = $this->generar_asistencias($array_personas_a_controlar, $o_Listado, $a_Feriados);

                // SALIDAS TEMPRANO
                $a_Salidas_Temprano         = $this->generar_salidas_temprano($a_Asistencias,$array_personas_a_controlar);

                return $a_Salidas_Temprano;

                break;

            case 'Listado_Asistencias':
            case '45':

                // ASISTENCIAS
                $a_Asistencias              = $this->generar_asistencias($array_personas_a_controlar, $o_Listado, $a_Feriados);

                return $a_Asistencias;
                break;

            case 'Listado_Ausencias':
            case '46':

                // ASISTENCIAS
                $a_Asistencias              = $this->generar_asistencias($array_personas_a_controlar, $o_Listado, $a_Feriados);

                // HORARIOS DIAS
                $a_Horarios_Dias            = $this->generar_horarios_dias($Fecha_Desde, $Fecha_Hasta, $array_personas_a_controlar, $a_Hora_Trabajo, $a_Horarios_Rotativos, $a_Horarios_Multiples, $a_Horarios_Flexibles, $dias, $a_Feriados , $a_Licencias, $a_Suspensiones);

                /* JORNADAS */
                $a_Jornadas_Filtro_Persona  = $this->generar_jornadas($a_Asistencias , $a_Horarios_Dias , $array_personas_a_controlar);

                // AUSENCIAS
                $a_Ausencias                = $this->generar_ausencias($a_Jornadas_Filtro_Persona , $array_personas_a_controlar, $margen_ausencia , $a_Asistencias);


                return $a_Ausencias;

                break;

            default:
                return;
                break;
        }



    }

    public function generar_reporte_personas() {

        $o_Listado      = Persona_L::obtenerDesdeFiltroArray($this->_filtro_persona);

        return $o_Listado;

    }

    public function generar_reporte_grupos(){
        $o_Listado = Grupo_L::obtenerTodosArray();
        //printear($o_Listado);
        return $o_Listado;
    }

    public function generar_reporte_horarios_trabajo(){
        $o_Listado = Hora_Trabajo_L::obtenerTodosArray();
        //printear($o_Listado);
        return $o_Listado;
    }

    public function generar_reporte_horarios_flexibles(){
        $o_Listado = Horario_Flexible_L::obtenerTodosArray();
        //printear($o_Listado);
        return $o_Listado;
    }

    public function generar_reporte_horarios_multiples(){
        $o_Listado = Horario_Multiple_L::obtenerTodosArray();
        //printear($o_Listado);
        return $o_Listado;
    }

    public function generar_reporte_horarios_rotativos(){
        $o_Listado = Horario_Rotativo_L::obtenerTodosArray();
        //printear($o_Listado);
        return $o_Listado;
    }

    public function generar_reporte_suspensiones(){
        $o_Listado              = Suspensions_L::obtenerDesdeFiltro($this->_filtro_persona, $this->_filtro_intervalo, '');
        return $o_Listado;
    }

    public function generar_reporte_licencias(){
        $o_Listado              = Licencias_L::obtenerDesdeFiltro($this->_filtro_persona, $this->_filtro_intervalo, '');
        return $o_Listado;
    }

    public function generar_reporte_feriados(){
        $o_Listado              = Feriado_L::obtenerDesdeFiltro($this->_filtro_persona, $this->_filtro_intervalo, '');
        return $o_Listado;
    }

    public function generar_reporte_alertas(){
        $o_Listado = Notificaciones_L::obtenerTodosAlertasArray();
        //printear($o_Listado);
        return $o_Listado;
    }

    public function generar_reporte_reportes_automaticos(){
        $o_Listado = Notificaciones_L::obtenerTodosReportesArray();
        //printear($o_Listado);
        return $o_Listado;
    }


    public function generar_csv_excel_data($a_reporte = array()) {


        $T_Tipo     = $this->_tipo;

        $a_empresas = Empresa_L::obtenerTodosARRAY();
        $a_equipos  = Equipo_L::obtenerTodosArrayv2();


        $descarga_tipo[1] = 'PDF';
        $descarga_tipo[2] = 'CSC';
        $descarga_tipo[3] = 'XLS';

        // REPORTES CSV
        switch ($T_Tipo) {

            case 'Modelo_Importar_Registros':
            case 'Registros':
            case '39':

                $csv_excel_data[] = array(
                    'Legajo',
                    'Apellido',
                    'Nombre',
                    'Fecha',
                    'Horario Ingreso',
                    'Horario Egreso',
                    'Hora'
                );

                if(count($a_reporte) <= 0) break;

                foreach ($a_reporte as $per_Id => $item){
                    foreach ($item['logs'] as $key_log => $log){
                        $csv_excel_data[] = array(
                            $item['per_Legajo'],
                            $item['per_Apellido'],
                            $item['per_Nombre'],
                            $log['Fecha'],
                            $log['Hora_Trabajo_Inicio'],
                            $log['Hora_Trabajo_Fin'],
                            $log['Hora']
                        );

                    }
                }

                break;

            case 'Marcaciones':
            case '40':

                $csv_excel_data[] = array('Legajo','Apellido','Nombre','Fecha','Horario Ingreso','Horario Egreso', 'Hora Entrada','Equipo','Hora Salida','Equipo','Horas');
                if(count($a_reporte) <= 0) break;
                foreach ($a_reporte as $per_Id => $item){
                    foreach ($item['logs'] as $key_log => $log){

                        $csv_excel_data[] = array(
                            $item['per_Legajo'],
                            $item['per_Apellido'],
                            $item['per_Nombre'],
                            date("d-m-Y", strtotime($log['Fecha_Inicio'])),
                            $log['Hora_Trabajo_Inicio'],
                            $log['Hora_Trabajo_Fin'],
                            $log['Hora_Inicio'],
                            $log['Equipo_Inicio'],
                            $log['Hora_Fin'],
                            $log['Equipo_Fin'],
                            $log['Total_Intervalo']);
                    }
                }


                break;

            case 'Jornadas':
            case '41':

                $csv_excel_data[] = array('Legajo','Apellido','Nombre','Fecha','Horario Ingreso','Horario Egreso','Ausencia','Llegada Tarde','Salida Temprano','Horas Regulares','Horas Extra','Horas Feriado','Feriado','Licencia','Suspension');
                if(count($a_reporte) <= 0) break;
                foreach ($a_reporte as $per_Id => $item){
                    foreach ($item['jornadas'] as $key_jornada => $jornada){
                        $csv_excel_data[] = array(
                            $item['per_Legajo'],
                            $item['per_Apellido'],
                            $item['per_Nombre'],
                            $jornada['Fecha_Trabajo_Inicio'],
                            $jornada['Hora_Trabajo_Inicio'],
                            $jornada['Hora_Trabajo_Fin'],
                            $jornada['Ausencia'],
                            $jornada['Llegada_Tarde'],
                            $jornada['Salida_Temprano'],
                            $jornada['Horas_Regulares'],
                            $jornada['Horas_Extra'],
                            $jornada['Horas_Feriado'],
                            $jornada['Feriado'],
                            $jornada['Licencia'],
                            $jornada['Suspension']);
                    }
                }


                break;

            case 'Intervalo':
            case '42':

                $csv_excel_data[] = array('Legajo','Apellido','Nombre','Asistencias','Ausencias','Licencias','Suspensiones','Horas Horario','Horas Regulares','Horas Extra','Horas Feriado','Llegadas Tarde','Salidas Temprano');
                if(count($a_reporte) <= 0) break;
                foreach ($a_reporte as $per_Id => $item){
                    $csv_excel_data[] = array(
                        $item['per_Legajo'],
                        $item['per_Apellido'],
                        $item['per_Nombre'],
                        $item['Asistencias'],
                        $item['Ausencias'],
                        $item['Licencias'],
                        $item['Suspensiones'],
                        $item['Horas_Horario'],
                        $item['Horas_Regulares'],
                        $item['Horas_Extra'],
                        $item['Horas_Feriado'],
                        $item['Llegadas_Tarde'],
                        $item['Salidas_Temprano']);
                }


                break;

            case 'Llegadas_Tarde':
            case '43':

                $csv_excel_data[] = array('Legajo', 'Apellido', 'Nombre', 'Acumula','Fecha','Horario Ingreso','Horario Egreso','Registro');
                if(count($a_reporte) <= 0) break;
                foreach ($a_reporte as $per_Id => $item){
                    foreach ($item['Llegadas_Tarde'] as $key_log => $log){
                        $csv_excel_data[] = array(
                            $item['per_Legajo'],
                            $item['per_Apellido'],
                            $item['per_Nombre'],
                            $item['Total'],
                            date("d-m-Y", strtotime($log['Fecha_Inicio'])),
                            $log['Hora_Trabajo_Inicio'],
                            $log['Hora_Trabajo_Fin'],
                            $log['Hora_Inicio']);
                    }
                }

                break;

            case 'Salidas_Temprano':
            case '44':

                $csv_excel_data[] = array('Legajo', 'Apellido', 'Nombre', 'Acumula','Fecha','Horario Ingreso','Horario Egreso','Registro');
                if(count($a_reporte) <= 0) break;
                foreach ($a_reporte as $per_Id => $item){
                    foreach ($item['Salidas_Temprano'] as $key_log => $log){
                        $csv_excel_data[] = array(
                            $item['per_Legajo'],
                            $item['per_Apellido'],
                            $item['per_Nombre'],
                            $item['Total'],
                            date("d-m-Y", strtotime($log['Fecha_Fin'])),
                            $log['Hora_Trabajo_Inicio'],
                            $log['Hora_Trabajo_Fin'],
                            $log['Hora_Fin']);
                    }
                }

                break;

            case 'Listado_Asistencias':
            case '45':

                $csv_excel_data[] = array('Legajo', 'Apellido', 'Nombre','Acumula','Fecha','Horario Ingreso','Horario Egreso','Entrada','Salida','Feriado');
                if(count($a_reporte) <= 0) break;
                foreach ($a_reporte as $per_Id => $item){
                    foreach ($item['jornadas'] as $key_log => $log){
                        $csv_excel_data[] = array(
                            $item['per_Legajo'],
                            $item['per_Apellido'],
                            $item['per_Nombre'],
                            $item['Total'],
                            date("d-m-Y", strtotime($log['Fecha_Trabajo_Inicio'])),
                            $log['Hora_Trabajo_Inicio'],
                            $log['Hora_Trabajo_Fin'],
                            count($item['jornadas']) == 0 ? '-' : $log['Hora_Inicio'],
                            count($item['jornadas']) == 0 ? '-' : $log['Hora_Fin'],
                            count($item['jornadas']) == 0 ? '-' : $log['Feriado']);
                    }
                }

                break;

            case 'Listado_Ausencias':
            case '46':

                $csv_excel_data[] = array('Legajo', 'Apellido', 'Nombre','Total','Justificadas','Acumula','Fecha','Horario Ingreso','Horario Egreso','Feriado','Licencia','Suspension');
                if(count($a_reporte) <= 0) break;
                foreach ($a_reporte as $per_Id => $item){
                    foreach ($item['Ausencias'] as $key_log => $log){
                        $csv_excel_data[] = array(
                            $item['per_Legajo'],
                            $item['per_Apellido'],
                            $item['per_Nombre'],
                            $item['Total'],
                            $item['Total_Justificadas'],
                            $item['Total_No_Justificadas'],
                            date("d-m-Y", strtotime($log['Fecha_Trabajo_Inicio'])),
                            $log['Hora_Trabajo_Inicio'],
                            $log['Hora_Trabajo_Fin'],
                            $log['Feriado'],
                            $log['Licencia'],
                            $log['Suspension']);
                    }
                }

                break;



            case 'Personas':
            case 'Modelo_Importar_Personas':
            case '51':

                $csv_excel_data[] = array(
                    'Legajo',
                    'Id Alternativo',
                    'Nombre',
                    'Segundo Nombre',
                    'Apellido',
                    'Fecha Nacimiento',
                    'Género',
                    'DNI',
                    'Estado Civil',

                    'Tipo de Horario',
                    'Horario',
                    'Teléfono Celular',
                    'Teléfono Fijo',
                    'Email',
                    'Email Personal',
                    'TAG',

                    'Equipos',
                    'Imágen',
                    'Inicio de Actividad',
                    'Fin de Actividad',

                    'Nro. Contribuyente',
                    'Talle Camisa',

                    'Direción 1',
                    'Direción 2',
                    'Ciudad',
                    'Provincia',
                    'Código Postal',
                    'País',
                    'Teléfono Trabajo',

                    'Linkedin',
                    'Twitter',
                    'Facebook',
                    'Estado',
                    'Empresa'
                );
                if(count($a_reporte) <= 0) break;
                foreach ($a_reporte as $per_Id => $item){



                    // ARREGLO: SI NO EXISTE EMPRESA CREADA
                    $per_empresaId = $item['per_Empresa'];
                    if(isset( $a_empresas[$per_empresaId])){
                        $nombre_Empresa = $a_empresas[$per_empresaId]['emp_Nombre'];
                        $item['per_Empresa'] = $nombre_Empresa;
                    }
                    else{$item['per_Empresa'] = '';}

                    // ARREGLO: NOMBRES DE EQUIPOS
                    $array_equipos      =   explode(':', $item['per_Equipos']);
                    $string_equipos     =   '';


                    foreach ($array_equipos as $key_Equipo => $item_equipo){
                        if(isset($a_equipos[$key_Equipo])){
                            $string_equipos .= $a_equipos[$key_Equipo]['eq_Detalle'] . ", ";
                        }
                    }
                    rtrim($string_equipos,",");
                    $item['per_Equipos'] = $string_equipos;

                    $csv_excel_data[] = array(
                        $item['per_Legajo'],
                        $item['per_RID'],
                        $item['per_Nombre'],
                        $item['per_Segundo_Nombre'],
                        $item['per_Apellido'],
                        $item['per_Fecha_Nacimiento'] == '0000-00-00 00:00:00' ? '' : $item['per_Fecha_Nacimiento'] ,
                        $item['per_Genero'],
                        $item['per_Dni'],
                        $item['per_Estado_Civil'],

                        $item['per_Hor_Tipo'],
                        $item['per_Hor_Id'],
                        $item['per_Te_Celular'],
                        $item['per_Te_Fijo'],
                        $item['per_E_Mail'],
                        $item['per_Email_Personal'],
                        $item['per_Tag'],

                        $item['per_Equipos'],

                        $item['per_Imagen_URL'],
                        $item['per_fechaD'] == '0000-00-00 00:00:00' ? '' : $item['per_fechaD'],
                        $item['per_fechaH'] == '0000-00-00 00:00:00' ? '' : $item['per_fechaH'],


                        $item['per_Nro_Contribuyente'],
                        $item['per_Talle_Camisa'],
                        $item['per_Direccion_1'],
                        $item['per_Direccion_2'],
                        $item['per_Ciudad'],
                        $item['per_Provincia'],
                        $item['per_Codigo_Postal'],
                        $item['per_Pais'],
                        $item['per_Te_Trabajo'],

                        $item['per_Linkedin'],
                        $item['per_Twitter'],
                        $item['per_Facebook'],
                        $item['per_Excluir']== 0 ? "Activo" : "Inactivo",
                        $item['per_Empresa']

                    );
                }

                break;

            case 'Grupos':
            case '51':
                $csv_excel_data[] = array(
                    'Id',
                    'Detalle',
                    'En Vivo',
                    'Empresa' //,
                    // 'Eliminado',
                    // 'Fecha Modificación'
                );
            if(count($a_reporte) <= 0) break;
                foreach ($a_reporte as $per_Id => $item){
                    $item['gru_Empresa'] = isset($a_empresas[ $item['gru_Empresa']]['emp_Nombre']) ? $a_empresas[ $item['gru_Empresa']]['emp_Nombre'] : "";

                    $csv_excel_data[] = array(
                        $item['gru_Id'],
                        $item['gru_Detalle'],
                        $item['gru_En_Vivo'],
                        $item['gru_Empresa']  //,
                        //$item['gru_Eliminado'],
                        //$item['gru_Fecha_Mod']
                    );
                }

                break;

            case 'Horarios_Trabajo':
            case '61':
                $csv_excel_data[] = array(
                    'Id',
                    'Detalle',
                    'Inicio Lunes',
                    'Fin Lunes',
                    'Inicio Martes',
                    'Fin Martes',
                    'Inicio Miércoles',
                    'Fin Miércoles',
                    'Inicio Jueves',
                    'Fin Jueves',
                    'Inicio Viernes',
                    'Fin Viernes',
                    'Inicio Sábado',
                    'Fin Sábado',
                    'Inicio Domingo',
                    'Fin Domingo',
                    'Empresa'  //,
                    //  'Eliminado',
                    //  'Fecha Modificación'
                );
            if(count($a_reporte) <= 0) break;
                foreach ($a_reporte as $per_Id => $item){
                    $item['hor_Empresa'] = isset($a_empresas[ $item['hor_Empresa']]['emp_Nombre']) ? $a_empresas[ $item['hor_Empresa']]['emp_Nombre'] : "";

                    //$item['hor_Empresa'] = $a_empresas[ $item['hor_Empresa']]['emp_Nombre'];
                    $csv_excel_data[] = array(
                        $item['hor_Id'],
                        $item['hor_Detalle'],
                        $item['hor_Inicio_Lun'],
                        $item['hor_Fin_Lun'],
                        $item['hor_Inicio_Mar'],
                        $item['hor_Fin_Mar'],
                        $item['hor_Inicio_Mie'],
                        $item['hor_Fin_Mie'],
                        $item['hor_Inicio_Jue'],
                        $item['hor_Fin_Jue'],
                        $item['hor_Inicio_Vie'],
                        $item['hor_Fin_Vie'],
                        $item['hor_Inicio_Sab'],
                        $item['hor_Fin_Sab'],
                        $item['hor_Inicio_Dom'],
                        $item['hor_Fin_Dom'],
                        $item['hor_Empresa']  //,
                        //    $item['hor_Eliminado'],
                        //    $item['hor_Fecha_Mod']
                    );
                }

                break;

            case 'Horarios_Flexibles':
            case '62':
                $csv_excel_data[] = array(
                    'Id',
                    'Detalle',
                    'Horarios',
                    'Empresa'  //,
                    //     'Eliminado',
                    //     'Fecha Modificación'
                );

                if(count($a_reporte) <= 0) break;

                foreach ($a_reporte as $per_Id => $item){
                    $item['hfle_Empresa'] = isset($a_empresas[ $item['hfle_Empresa']]['emp_Nombre']) ? $a_empresas[ $item['hfle_Empresa']]['emp_Nombre'] : "";

                    $csv_excel_data[] = array(
                        $item['hfle_Id'],
                        $item['hfle_Detalle'],
                        $item['hfle_Horarios'],
                        $item['hfle_Empresa']  //,
                        //    $item['hfle_Eliminado'],
                        //     $item['hfle_Fecha_Mod']
                    );
                }




                break;

            case 'Horarios_Multiples':
            case '63':
                $csv_excel_data[] = array(
                    'Id',
                    'Detalle',
                    'Horarios',
                    'Empresa'  //,
                     // 'Eliminado',
                    // 'Fecha Modificación'
                );
            if(count($a_reporte) <= 0) break;
                foreach ($a_reporte as $per_Id => $item){
                    $item['hmu_Empresa'] = isset($a_empresas[ $item['hmu_Empresa']]['emp_Nombre']) ? $a_empresas[ $item['hmu_Empresa']]['emp_Nombre'] : "";

                    $csv_excel_data[] = array(
                        $item['hmu_Id'],
                        $item['hmu_Detalle'],
                        $item['hmu_Horarios'],
                        $item['hmu_Empresa']  //,
                    //      $item['hmu_Eliminado'],
                        //   $item['hmu_Fecha_Mod']
                    );
                }

                break;

            case 'Horarios_Rotativos':
            case '64':
                $csv_excel_data[] = array(

                    'Id',
                    'Detalle',
                    'Horarios',
                    'Empresa'  //,
                    //      'Eliminado',
                    //     'Fecha Modificación'
                );
            if(count($a_reporte) <= 0) break;
                foreach ($a_reporte as $per_Id => $item){
                    $item['hrot_Empresa'] = isset($a_empresas[ $item['hrot_Empresa']]['emp_Nombre']) ? $a_empresas[ $item['hrot_Empresa']]['emp_Nombre'] : "";

                    $csv_excel_data[] = array(
                        $item['hrot_Id'],
                        $item['hrot_Detalle'],
                        $item['hrot_Horarios'],
                        $item['hrot_Fecha_Inicio'],
                        $item['hrot_Empresa'] //,
                        //  $item['hrot_Eliminado'],
                       //  $item['hrot_Fecha_Mod']
                    );
                }

                break;

            case 'Suspensiones':
            case '71':
                $csv_excel_data[] = array(
                    'Id',
                    'Persona',
                    'Grupo',
                    'Tipo',
                    'Fecha Inicio',
                    'Fecha Fin',
                    'Duracion',
                    'Motivo',
                    'Repetitiva',
                    'Activo',
                    'Empresa'  //,
                    //    'Eliminada',
                    //    'Fecha Modificación'
                );
                if(count($a_reporte) <= 0) break;
                foreach ($a_reporte as $per_Id => $item){
                    $item['sus_Empresa'] = isset($a_empresas[ $item['sus_Empresa']]['emp_Nombre']) ? $a_empresas[ $item['sus_Empresa']]['emp_Nombre'] : "";


                    $csv_excel_data[] = array(
                        $item['sus_Id'],
                        $item['sus_Per_Id'],
                        $item['sus_Gru_Id'],
                        $item['sus_Tipo'],
                        $item['sus_Fecha_Inicio'],
                        $item['sus_Fecha_Fin'],
                        $item['sus_Duracion'],
                        $item['sus_Motivo'],
                        $item['sus_Repetitiva'],
                        $item['sus_Enabled'] == 1 ? "Si" : "No",
                        $item['sus_Empresa']  //,
                        //$item['sus_Eliminada'],
                        // $item['sus_Fecha_Mod']
                    );
                }

            break;

            case 'Licencias':
            case '72':
                $csv_excel_data[] = array(
                    'Id',
                    'Persona',
                    'Grupo',
                    'Tipo',
                    'Fecha Inicio',
                    'Fecha Fin',
                    'Duracion',
                    'Motivo',
                    'Repetitiva',
                    'Activo',
                    'Empresa'  //,
                    //'Eliminada',
                    // 'Fecha Modificación'
                );
                if(count($a_reporte) <= 0) break;
                foreach ($a_reporte as $per_Id => $item){
                    $item['lic_Empresa'] = isset($a_empresas[ $item['lic_Empresa']]['emp_Nombre']) ? $a_empresas[ $item['lic_Empresa']]['emp_Nombre'] : "";

                    $csv_excel_data[] = array(
                        $item['lic_Id'],
                        $item['lic_Per_Id'],
                        $item['lic_Gru_Id'],
                        $item['lic_Tipo'],
                        $item['lic_Fecha_Inicio'],
                        $item['lic_Fecha_Fin'],
                        $item['lic_Duracion'],
                        $item['lic_Motivo'],
                        $item['lic_Repetitiva'],
                        $item['lic_Enabled'] == 1 ? "Si" : "No",
                        $item['lic_Empresa']  //,
                        //  $item['lic_Eliminada'],
                        //  $item['lic_Fecha_Mod']
                    );
                }

                break;

            case 'Feriados':
            case '73':
                $csv_excel_data[] = array(
                    'Id',
                    'Persona',
                    'Grupo',
                    'Tipo',
                    'Fecha Inicio',
                    'Fecha Fin',
                    'Descripcion',
                    'Activo',
                    'Empresa'  //,
                    //  'Eliminado',
                    // 'Fecha Modificación'
                );
                if(count($a_reporte) <= 0) break;
                foreach ($a_reporte as $per_Id => $item){
                    $item['fer_Empresa'] = isset($a_empresas[ $item['fer_Empresa']]['emp_Nombre']) ? $a_empresas[ $item['fer_Empresa']]['emp_Nombre'] : "";

                    $csv_excel_data[] = array(
                        $item['fer_Id'],
                        $item['fer_Per_Id'],
                        $item['fer_Gru_Id'],
                        $item['fer_Tipo'],
                        $item['fer_Fecha_Inicio'],
                        $item['fer_Fecha_Fin'],
                        $item['fer_Descripcion'],
                        $item['fer_Enabled'] == 1 ? "Si" : "No",
                        $item['fer_Empresa']  //,
                        //    $item['fer_Eliminado'],
                        //    $item['fer_Fecha_Mod']
                    );
                }

                break;



            case 'Alertas':
            case '81':
                $csv_excel_data[] = array(
                    'Id',
                    'Detalle',
                    'Destinatario Usuario',
                    'Destinatario Persona',
                    //   'Mensaje',
                    'Email',
                    'Tipo',
                    'Destinatario Grupo',
                    'Tipo',
                    'Disparador',
                    'Hora Disparador',
                    'Hora',
                    // 'Equipo',
                    // 'Alarma',
                    // 'Dispositivo',
                    'Persona',
                    'Grupo',
                    'Zona',
                    //  'Log',
                    'Horarios',
                    'Tipo',
                    'Tipo de Archivo',
                    'Detalle',
                    'Texto',
                    // 'Equipo',
                    'Intervalo',
                    // 'Alarma',
                    //   'Dispositivo',
                    'Persona',
                    'Grupo',
                    //    'Zona',
                    'Usuario',
                    'Activo',
                    'Empresa'
                );


                if(count($a_reporte) <= 0) break;
                foreach ($a_reporte as $per_Id => $item){

                    $item['nco_Tipo'] = isset($a_empresas[ $item['nco_Tipo']]['emp_Nombre']) ? $a_empresas[ $item['nco_Tipo']]['emp_Nombre'] : "";

                    $csv_excel_data[] = array(
                        $item['not_Id'],
                        $item['not_Dest_Usu_Id'],
                        $item['not_Dest_Per_Id'],
                        $item['not_Detalle'],
                        //     $item['not_Men_Id'],
                        $item['not_Email_Me'],
                        $item['not_Tipo'],
                        $item['not_Dest_Grupo'],
                        $item['ndi_Tipo'],
                        $item['ndi_Disparador'],
                        $item['not_Disparador_Hora'],
                        $item['ndi_Hora'],
                        //    $item['ndi_Equipo'],
                        //    $item['ndi_Alarma'],
                        //    $item['ndi_Dispositivo'],
                        $item['ndi_Persona'],
                        $item['ndi_Grupo'],
                        $item['ndi_Zona'],
                        // $item['ndi_Log'],
                        $item['ndi_Horarios'],
                        $item['nco_Tipo'],
                        $item['nco_DescargarTipo'],
                        $item['nco_Detalle'],
                        $item['nco_Texto'],
                        // $item['nco_Equipo'],
                        $item['nco_Intervalo'],
                        //  $item['nco_Alarma'],
                        //   $item['nco_Dispositivo'],
                        $item['nco_Persona'],
                        $item['nco_Grupo'],
                        //  $item['nco_Zona'],
                        $item['nco_Usuario'],
                        $item['not_Activa'] == 1 ? "Si" : "No",
                        $item['not_Empresa']
                    );
                }

                break;


            case 'Reportes_Automaticos':
            case '82':
                $csv_excel_data[] = array();
                if(count($a_reporte) <= 0) break;

                foreach ($a_reporte as $per_Id => $item){
                    $csv_excel_data[] = array(


                    );
                }

                break;




            default:
                $csv_excel_data[] = array();
                break;
        }


        return $csv_excel_data;

    }


}
