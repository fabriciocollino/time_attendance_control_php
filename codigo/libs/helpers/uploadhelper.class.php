<?php

class UploadHelper
{


    public static function upload_post_file($p_post_file_object, $p_path = '', $p_name = '')
    {

        // PATH
        if ($p_path != '') {
            $file_path = GS_CLIENT_BUCKET . $p_path;
        } else {
            $file_path = GS_CLIENT_TEMP_FOLDER;
        }

        // FILE NAME
        if ($p_name != '') {
            $file_name = $p_name;
        }
        else {
            $file_name = $p_post_file_object['name'];
        }

        // FILE TEMP LOCATION
        $file_temp_url = $p_post_file_object['tmp_name'];

        // FILE DESTINATION URL
        $file_dest_url = $file_path . $file_name;



        if(!move_uploaded_file($file_temp_url, $file_dest_url)){
            return  false;
        }


        return $file_dest_url;

    }

    public static function scan_bucket_directory($p_path = '', $p_sort = '' )
    {


        // PATH
        if ($p_path != '') {
            $scan_path = GS_CLIENT_BUCKET . $p_path;
        }
        else {
            $scan_path = GS_CLIENT_TEMP_FOLDER;
        }

        if ($p_sort != '') {
            $scan_sort = $p_sort;
        }
        else{
            $scan_sort = SCANDIR_SORT_NONE;
        }

        return scandir($scan_path,$scan_sort);

    }



    public static function TOODOLODEMAS($p_post_file_object, $p_path = '', $p_name = '')
    {

        /*
         *


$storageService = new Google_Service_Storage($psclient);


$file_name = 'example.txt';
$file_content = 'este es el contenido: loren epsun';
$bucket_name = 'enpunto';

        try
        {
            if (!file_exists(GS_CLIENT_TEMP_FOLDER)) mkdir(GS_CLIENT_TEMP_FOLDER, 0777, true);


            $postbody = array(
                'name' => $file_name,
                'data' => $file_content//,
                //'uploadType' => "media"
            );

            $gsso = new Google_Service_Storage_StorageObject();
            $gsso->setName( $file_name );

            $storage_object_insert = $storageService->objects->insert( $bucket_name, $gsso, $postbody );

            printear('$storage_object_insert'); ;
            printear($storage_object_insert); ;

            rename("gs://enpunto/" . $file_name, GS_CLIENT_TEMP_FOLDER. $file_name);





        }
        catch (Exception $e)
        {
            printear('error'); ;
            printear($e->getMessage());
        }





    use google\appengine\api\cloud_storage\CloudStorageTools;

        $upload_url = CloudStorageTools::createUploadUrl('/upload/handler', ['gs_bucket_name' => 'enpunto']);

        printear('$upload_url');
        printear($upload_url);
        printear('$_REQUEST');
        printear($_REQUEST);
        printear('$_FILES');
        printear($_FILES);
















        /* FILE URL
        $file_url = GS_CLIENT_TEMP_FOLDER . $T_Nombre_Archivo;
        */

        /* CSV HANDLER
        $csv_handler = fopen ($file_url,'w');
        fwrite ($csv_handler,$csv_content);
        fclose ($csv_handler);

        */


        /*

        $STORAGE = new Google_Service_Storage($psclient);

        $gooCs = new CloudStorageTools();

        $gaeFormUploadUrl = $gooCs->createUploadUrl('__YOUR_UPLOAD_URL', $options);

        $upload_url = CloudStorageTools::createUploadUrl('/upload/handler', ['gs_bucket_name' => 'enpunto']);

        printear('$upload_url');
        printear($upload_url);

        printear('$_REQUEST');
        printear($_REQUEST);
        printear($_FILES);



        if(1){

            if (!file_exists(GS_CLIENT_TEMP_FOLDER)) mkdir(GS_CLIENT_TEMP_FOLDER, 0777, true);

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

            $objWriter->save(GS_CLIENT_TEMP_FOLDER . $T_Filename. ".xls");


        }

        echo file_put_contents("gs://enpunto/clients/temp/dev/" . "test.txt","Hello World. Testing!");

        if(isset($_FILES['uploaded_files'])) {
            printear('llego a uploaded files');
            $file_name = $_FILES['uploaded_files']['name'];
            $temp_name = $_FILES['uploaded_files']['tmp_name'];
            move_uploaded_file($temp_name, "gs://enpunto/clients/temp/dev/" . $file_name);
        }
        else{
            printear('NONONO hay uploaded');
        }



        use google\appengine\api\cloud_storage\CloudStorageTools;

        $upload_url = CloudStorageTools::createUploadUrl('/upload/handler', ['gs_bucket_name' => 'enpunto']);

        printear('$upload_url');
        printear($upload_url);


        $file_name = 'nombre_archivo.csv';
        if(isset($_FILES['uploaded_files'])) {
            printear('$_FILES[uploaded_files]');
            printear($_FILES['uploaded_files']);
        }
        move_uploaded_file($upload_url, "gs://enpunto/clients/temp/dev/" . $file_name);
        */


    }
}