<?php


require_once(dirname(__FILE__) . '/_ruta.php');
require_once(APP_PATH . '/libs/firebase/firebaseLib.php');


$firebase = new \Firebase\FirebaseLib(FIREBASE_URL, FIREBASE_TOKEN);



//forzar sync de personas
$a_o_Personas = Persona_L::obtenerTodos();
$array=array();
foreach ($a_o_Personas as $o_persona){ //@var $o_persona Persona_O */
    $array[$o_persona->getId()] = $o_persona->toArray(true);
}
$firebase->set(FIREBASE_BASE_REF.'/personas', $array);



//forzar sync de horarios
$a_o_Horario = Hora_Trabajo_L::obtenerTodos();
$array=array();
foreach ($a_o_Horario as $o_horario){ // @var $o_horario Hora_Trabajo_O */
    $array[$o_horario->getId()] = $o_horario->toArray();
}
$firebase->set(FIREBASE_BASE_REF.'/hora_trabajo', $array);

//forzar sync de horarios flexibles
$a_o_Horariof = Horario_Flexible_L::obtenerTodos();
$array=array();
foreach ($a_o_Horariof as $o_horario){ /* @var $o_horario Horario_Flexible_L */
    $array[$o_horario->getId()] = $o_horario->toArray();
}
$firebase->set(FIREBASE_BASE_REF.'/horarios_flexibles', $array);

//forzar sync de horarios rotativos
$a_o_Horarior = Horario_Rotativo_L::obtenerTodos();
$array=array();
foreach ($a_o_Horarior as $o_horario){ /* @var $o_horario Horario_Rotativo_L */
    $array[$o_horario->getId()] = $o_horario->toArray();
}
$firebase->set(FIREBASE_BASE_REF.'/horarios_rotativos', $array);





//forzar sync de equipos
$a_o_Equipos = Equipo_L::obtenerTodos();
$array=array();
foreach ($a_o_Equipos as $o_equipo){ /* @var $o_equipo Equipo_O */
    $array[$o_equipo->getId()] = $o_equipo->toArray();
}
$firebase->set(FIREBASE_BASE_REF.'/equipos', $array);


//forzar sync de huellas
$a_o_Huellas = Huella_L::obtenerTodos();
$array=array();
foreach ($a_o_Huellas as $o_huella){ /* @var $o_huella Huella_O */
    $array[$o_huella->getId()] = $o_huella->toArray(false);
}
$firebase->set(FIREBASE_BASE_REF.'/huellas', $array);




//forzar sync de usuarios
$a_o_Usuarios = Usuario_L::obtenerTodosSP();
$array=array();
foreach ($a_o_Usuarios as $o_usuario){ /* @var $o_usuario Usuario_O */
    $array[$o_usuario->getId()] = $o_usuario->toArray(true);
}
$firebase->set(FIREBASE_BASE_REF.'/usuarios', $array);



$firebase->set(FIREBASE_BASE_REF . '/client_id', $o_Cliente->getId());



