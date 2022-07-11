<?php


$_SERVER['X-Appengine-Cron']=true;

require_once(dirname(__FILE__) . '/_ruta.php');

//SeguridadHelper::Pasar(999);
//a este archivo solo se accede mediante un cron


$o_Cliente = Cliente_L::obtenerPorSubdominio('demo');
if (is_null($o_Cliente))die('la demo no existe');



$subdominio = $o_Cliente->getSubdominio();
$o_Suscripcion = Suscripcion_L::obtenerPorId($o_Cliente->getSuscripcion());

$o_Plan = Planes_L::obtenerPorId($o_Suscripcion->getPlan());
$G_DbConn1 = new mySQL(
    $o_Cliente->getDBname(),
    $o_Cliente->getDBuser(),
    $o_Cliente->getDBpass(),
    $o_Cliente->getDBhost(),
    $o_Cliente->getDBport()
);


if (!$G_DbConn1->ConectarSocket()) {
    die($G_DbConnMGR->get_Error($Registry->general['debug']));
}

Registry::getInstance()->DbConn = $G_DbConn1;



$personas_ID=array(
	1,
	2,
	3,
	4,
	5,
	6,
	7,
	8
);

$persona_min = 1;
$persona_max = 8;
$max_ausencias_por_dia = 4;

$equipos_ID_ID=array(
	1,
	2,
	3,
	4
);

//die('desactivado');

$fecha_base=date('Y-m-d', time());
//$fecha_base='2016-05-23';
if(isset($_REQUEST['fecha']) && $_REQUEST['fecha']!='')
	$fecha_base=$_REQUEST['fecha'];


$ausencias_del_dia=array();

//para generar asencias
$NumerodeAusencias=rand(1,$max_ausencias_por_dia);
for($a=0;$a<$NumerodeAusencias;$a++){
	$ausencias_del_dia[]=rand($persona_min,$persona_max);
}





/***********************************************************************************************************************/
/********************************      HORARIOS DE ENTRADA      ********************************************************/
/***********************************************************************************************************************/
for($i=0;$i<1;$i++){


	$hora_base='07:50:00';

	


	foreach($personas_ID as $persona_id){

		$o_persona=Persona_L::obtenerPorId($persona_id);
		
		if($o_persona){
			if(in_array($persona_id,$ausencias_del_dia)){
				echo "<pre>";echo $o_persona->getNombreCompleto().", No se presento a trabajar hoy...  :("; echo "</pre>";
				continue;
			}
			
			$a_temp=array();
	//esto todavia no esta terminado, la idea es obtener el horario de inicio de cada persona
			switch($o_persona->getHorTipo()){
				case HORARIO_NORMAL:
					$o_hora_trabajo = $o_persona->getHoraTrabajoObject();
					$a_Horas_compor = $o_hora_trabajo->getArrayDiasString();
					foreach ($a_Horas_compor as $key => $value) {
						if (!is_null($value[0]) && $value[0] != '00:00:00') {
							$inidice = $value[0] . '.' . $value[1];
							if (!isset($a_temp[$inidice]['dias'])) {
								$a_temp[$inidice]['dias'] = $dias_red[$key] . ', ';
							} else {
								$a_temp[$inidice]['dias'] .= $dias_red[$key] . ', ';
							}
							$a_temp[$inidice]['hora_inicio'] = $value[0];
							$a_temp[$inidice]['hora_fin'] = $value[1];
						}
					}

					break;
				case HORARIO_FLEXIBLE:
					$o_hora_trabajo = $o_persona->getHoraTrabajoObject();
					$a_Horas_horario_flexible = $o_hora_trabajo->getArrayDiasString();
					foreach ($a_Horas_horario_flexible as $a_Horas_compor) {
						foreach ($a_Horas_compor as $key => $value) {
							if (!is_null($value[0]) && $value[0] != '00:00:00') {
								$inidice = $value[0] . '.' . $value[1];
								if (!isset($a_temp[$inidice]['dias'])) {
									$a_temp[$inidice]['dias'] = $dias_red[$key] . ', ';
								} else {
									$a_temp[$inidice]['dias'] .= $dias_red[$key] . ', ';
								}
								$a_temp[$inidice]['hora_inicio'] = $value[0];
								$a_temp[$inidice]['hora_fin'] = $value[1];
							}
						}
					}
					break;
				case HORARIO_ROTATIVO:
					$o_hora_trabajo = $o_persona->getHoraTrabajoObject();
					$a_Horas_horario_rotativo = $o_hora_trabajo->getArrayDiasString();
					foreach ($a_Horas_horario_rotativo as $a_Horas_compor) {
						foreach ($a_Horas_compor as $key => $value) {
							if (!is_null($value[0]) && $value[0] != '00:00:00') {
								$inidice = $value[0] . '.' . $value[1];
								if (!isset($a_temp[$inidice]['dias'])) {
									$a_temp[$inidice]['dias'] = $dias_red[$key] . ', ';
								} else {
									$a_temp[$inidice]['dias'] .= $dias_red[$key] . ', ';
								}
								$a_temp[$inidice]['hora_inicio'] = $value[0];
								$a_temp[$inidice]['hora_fin'] = $value[1];
							}
						}
					}
					break;
			}
		

			$hora_log= $fecha_base.' '.$hora_base;
			$random=rand(0,50);
			$hora_log = date('Y-m-d H:i:s',strtotime('+'.$random.' minutes',strtotime($hora_log)));



			$log=new Logs_Equipo_O();
			$log->setAccion(1);
			$log->setEqId(2);
			$log->setFechaHora($hora_log , "Y-m-d H:i:s");
			$log->setPerId($persona_id);
			$log->save('off');
			//echo "<pre>";print_r($a_temp);echo "</pre>";
			
		}

	}


}












/***********************************************************************************************************************/
/********************************      HORARIOS DE SALIDA       ********************************************************/
/***********************************************************************************************************************/


for($i=0;$i<1;$i++){


	$hora_base='17:00:00';



	foreach($personas_ID as $persona_id){

		$o_persona=Persona_L::obtenerPorId($persona_id);
	
		if($o_persona){
			if(in_array($persona_id,$ausencias_del_dia)){
				echo "<pre>";echo $o_persona->getNombreCompleto().", No se presento a trabajar hoy...  :("; echo "</pre>";
				continue;
			}

			$a_temp=array();
	//esto todavia no esta terminado, la idea es obtener el horario de inicio de cada persona
			switch($o_persona->getHorTipo()){
				case HORARIO_NORMAL:
					$o_hora_trabajo = $o_persona->getHoraTrabajoObject();
					$a_Horas_compor = $o_hora_trabajo->getArrayDiasString();
					foreach ($a_Horas_compor as $key => $value) {
						if (!is_null($value[0]) && $value[0] != '00:00:00') {
							$inidice = $value[0] . '.' . $value[1];
							if (!isset($a_temp[$inidice]['dias'])) {
								$a_temp[$inidice]['dias'] = $dias_red[$key] . ', ';
							} else {
								$a_temp[$inidice]['dias'] .= $dias_red[$key] . ', ';
							}
							$a_temp[$inidice]['hora_inicio'] = $value[0];
							$a_temp[$inidice]['hora_fin'] = $value[1];
						}
					}

					break;
				case HORARIO_FLEXIBLE:
					$o_hora_trabajo = $o_persona->getHoraTrabajoObject();
					$a_Horas_horario_flexible = $o_hora_trabajo->getArrayDiasString();
					foreach ($a_Horas_horario_flexible as $a_Horas_compor) {
						foreach ($a_Horas_compor as $key => $value) {
							if (!is_null($value[0]) && $value[0] != '00:00:00') {
								$inidice = $value[0] . '.' . $value[1];
								if (!isset($a_temp[$inidice]['dias'])) {
									$a_temp[$inidice]['dias'] = $dias_red[$key] . ', ';
								} else {
									$a_temp[$inidice]['dias'] .= $dias_red[$key] . ', ';
								}
								$a_temp[$inidice]['hora_inicio'] = $value[0];
								$a_temp[$inidice]['hora_fin'] = $value[1];
							}
						}
					}
					break;
				case HORARIO_ROTATIVO:
					$o_hora_trabajo = $o_persona->getHoraTrabajoObject();
					$a_Horas_horario_rotativo = $o_hora_trabajo->getArrayDiasString();
					foreach ($a_Horas_horario_rotativo as $a_Horas_compor) {
						foreach ($a_Horas_compor as $key => $value) {
							if (!is_null($value[0]) && $value[0] != '00:00:00') {
								$inidice = $value[0] . '.' . $value[1];
								if (!isset($a_temp[$inidice]['dias'])) {
									$a_temp[$inidice]['dias'] = $dias_red[$key] . ', ';
								} else {
									$a_temp[$inidice]['dias'] .= $dias_red[$key] . ', ';
								}
								$a_temp[$inidice]['hora_inicio'] = $value[0];
								$a_temp[$inidice]['hora_fin'] = $value[1];
							}
						}
					}
					break;
			}


			$hora_log= $fecha_base.' '.$hora_base;
			$random=rand(0,50);
			$hora_log = date('Y-m-d H:i:s',strtotime('+'.$random.' minutes',strtotime($hora_log)));

			$log=new Logs_Equipo_O();
			$log->setAccion(1);
			$log->setEqId(2);
			$log->setFechaHora($hora_log , "Y-m-d H:i:s");
			$log->setPerId($persona_id);
			$log->save('off');
			// echo "<pre>";print_r($a_temp);echo "</pre>";
			// echo "<pre>";print_r($log);echo "</pre>";

		}
	}


}
