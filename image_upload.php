<?php

require_once(dirname(__FILE__) . '/_ruta.php');


if(isset($_POST['dataURL']) && $_POST['dataURL'] !=''){
    $str = $_POST['dataURL'];
    $image_parts = explode(";base64,", $str);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    $str = $image_base64;
}
else{
    $str = file_get_contents('php://input');
}

// FILE NAME
echo $filename = md5(time().uniqid()).".jpg";

// FILE TEMPORAL UPLOAD
file_put_contents(GS_CLIENT_TEMP_FOLDER.$filename,$str);
