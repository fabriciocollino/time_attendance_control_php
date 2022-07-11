<?php
require_once dirname(__FILE__) . '/../../_ruta.php'; 
SeguridadHelper::Pasar(90);



$T_Tipo             = isset($_REQUEST['tipo'])  ? $_REQUEST['tipo']     : '';
$T_Event            = isset($_POST['event'])    ? $_POST['event']       : '';
$T_Id               = isset($_REQUEST['id'])    ? $_REQUEST['id']       : '';

$Item_Name          =   "planning";
$T_Titulo           =   "Planning";
$T_Script           =   "Planning";
$T_Titulo_Singular  =   _('Planning');
$T_Titulo_Pre       =   _('el');


switch ($T_Tipo) {
	case 'saveEvent':

	    if($T_Event!=''){

	        $event = json_decode($T_Event,true);

	        $eventDB = Planning_L::obtenerPorJSON('TRIM(BOTH \'"\' FROM CAST(`plan_Event` -> \'$._id\' as CHAR)) = "'.$event['_id'].'"');

	        if(isset($eventDB) && !is_null($eventDB)){

                //esto funciona
                $eventDB->setEvent(stripslashes($T_Event));

                if($eventDB->save()){
                    echo "OK";
                }

                else{
                    echo "ERROR";
                }

            }
	        else {
                $event = new Planning_O();
                $event->setEvent(stripslashes($T_Event));

                if($event->save()){
                    echo "OK";
                }
                else{
                    echo "ERROR";
                }

            }

        }


		break;

    case 'deleteEvent':

        if($T_Id!=''){


            $eventDB = Planning_L::obtenerPorJSON('TRIM(BOTH \'"\' FROM CAST(`plan_Event` -> \'$._id\' as CHAR)) = "'.$T_Id.'"');

            if(isset($eventDB) && !is_null($eventDB)){

                if($eventDB->delete())
                    echo "OK";
                else
                    echo "ERROR";
            }else{
               echo "ERROR, ID NO ENCONTRADO";
            }

        }else{
            echo "ERROR, ID NO EXISTE";
        }


        break;


	default:
	defaultlabel:

        $T_Start = isset($_GET['start']) ? $_GET['start'] : '';
        $T_End = isset($_GET['end']) ? $_GET['end'] : '';

        if($T_Start != '' && $T_End != '') {
            header('Content-Type: application/json');
            echo Planning_L::obtenerJSONFeedPorFechas($T_Start, $T_End);

        }


        $T_Link = '';
}





