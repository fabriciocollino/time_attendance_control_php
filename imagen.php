<?php

require_once(dirname(__FILE__) . '/_ruta.php');


// PERSONA IMAGE
if (isset($_GET['per_id']) && $_GET['per_id'] != '') {
    $o_Persona = Persona_L::obtenerPorId($_GET['per_id'], TRUE);
    if (!is_null($o_Persona)) {

        if ($o_Persona->getImagenURL() == '') {

            $object_image_file = $o_Persona->getImagen();

            $str = ltrim($object_image_file, 'gs://');

            $object_image_url= "https://storage.cloud.google.com/".$str;

            //$object_image_url = CloudStorageTools::getImageServingUrl($object_image_file, ['secure_url' => true]);
            $o_Persona->setImagenURL($object_image_url);
            $o_Persona->save('Off');

        }
        else {
            $object_image_url = $o_Persona->getImagenURL();
        }

        header('Location:' . $object_image_url);

    }
}
// USER IMAGE
elseif (isset($_GET['usu_id']) && $_GET['usu_id'] != '') {
    $o_Usuario = Usuario_L::obtenerPorId($_GET['usu_id'], TRUE);
    if (!is_null($o_Usuario)) {

        if ($o_Usuario->getImagenURL() == '') {

            $object_image_file = $o_Usuario->getImagen();

            $str = ltrim($object_image_file, 'gs://');
            $object_image_url= "https://storage.cloud.google.com/".$str;

            //$object_image_url = CloudStorageTools::getImageServingUrl($object_image_file, ['secure_url' => true]);
            $o_Usuario->setImagenURL($object_image_url);
            $o_Usuario->save('Off');

        } else {
            $object_image_url = $o_Usuario->getImagenURL();
        }

        header('Location:' . $object_image_url);


    }
}
// TEMP IMAGE
elseif (isset($_GET['temp_img']) && $_GET['temp_img'] != '') {

    $imageName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $_GET['temp_img']);  //le saco la extension

    if (GAE)
        $file = GS_CLIENT_TEMP_FOLDER . $imageName . '.jpg';
    else
        $file = 'temp/' . $imageName . '.jpg';


    header("Content-type: image/jpeg");
    header("Content-Disposition: inline; filename=" . $imageName . ".jpg");
    header('Content-Length: ' . filesize($file));

    /*
    $bucketName = GS_CLIENT_TEMP_FOLDER;
    $objectName = $imageName. '.jpg';
    $destination = '/tmp';
    download_object($bucketName, $objectName, $destination);
    */

    readfile($file);

}

// COMPANY LOGO
elseif (isset($_GET['empresa_logo'])) {

    $imagen = Config_L::p("empresa_logo");
    $imagen_url = Config_L::p("empresa_logo_url");



    if ($imagen_url == '') {

        $object_image_file = GS_CLIENT_IMAGES_LOGO.$imagen;
        $str = ltrim($object_image_file, 'gs://');

        $object_image_url= "https://storage.cloud.google.com/".$str;

        //$object_image_url = CloudStorageTools::getImageServingUrl($object_image_file, ['secure_url' => true]);
        Config_L::s("empresa_logo_url",$object_image_url);

    } else {
        $object_image_url = $imagen_url;
    }

    header('Location:' . $object_image_url);

}

