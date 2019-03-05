<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/5/2019
 * Time: 10:17 AM
 */
require('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

if(!empty($_POST['documentId']) && !empty($_POST['filePath']) && !empty($_POST['userId'])){
    $file = $_POST['filePath'];
    $documentId = $_POST['documentId'];
    $userId = $_POST['userId'];

    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        if($crud->execute("UPDATE documents SET availabilityId='1', lockedById='$userId' WHERE documentId='$documentId'")){
            echo $documentId;
        } else {
            echo 'error';
        };
    }else{
        echo 'error';
    }
}else{
    echo 'error';
}
?>