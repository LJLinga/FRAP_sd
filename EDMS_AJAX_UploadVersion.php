<?php

/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 2/28/2019
 * Time: 9:27 AM
 */

require 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();

function passErrors($errors){

    $msg = array(
        'success' => 0,
        'html' => $errors
    );
    return json_encode($msg);
}

$uploadDirectory = "EDMS_Documents/";

$errors = []; // Store all foreseen and unforseen errors here

$fileExtensions = ['jpeg','jpg','png','ppt','doc','docx','pptx','pdf']; // Get all the file extensions

if(!empty($_POST['documentId']) && !empty($_POST['newVersionNo']) && !empty($_POST['userId']) && !empty($_POST['versionTitle'])) {

    $userId = $_POST['userId'];
    $title = $_POST['versionTitle'];
    $versionNo = $_POST['newVersionNo'];
    $documentId = $_POST['documentId'];
    $remarks = $_POST['remarks'];

    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileType = $_FILES['file']['type'];
    $string = explode('.', $fileName);
    $fileExtension = strtolower(end($string));

    $temp = explode(".", $fileName);
    $newfilename = round(microtime(true)) . '.' . end($temp);

    //$uploadPath = $currentDir . $uploadDirectory . basename($fileName);
    $uploadPath = $uploadDirectory . basename($newfilename);

    if (!in_array($fileExtension, $fileExtensions)) {
        $errors[] = "This file extension is not allowed. Only JPEG, PNG, PPT, DOC, DOCX, PPTX, and PDF are accepted.";
    }

    if ($fileSize > 25000000) {
        $errors[] = "This file is more than 25MB. Sorry, it has to be less than or equal to 25MB";
    }

    if (empty($errors)) {
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

        if ($didUpload) {
            if($crud->execute("UPDATE documents SET authorId='$userId', availabilityId='1', availabilityById='$userId', remarks='$remarks',
                                    versionNo='$versionNo', title='$title',filePath='$uploadPath' WHERE documentId='$documentId';")){
                $msg = array(
                    'success' => 1
                );
                echo json_encode($msg);
                exit;
            }else{
                $errors[] = "Cannot insert to database.";
                echo passErrors($errors);
                exit;
            }
        } else {
            $errors[] = "File did not upload.";
            echo passErrors($errors);
            exit;
        }

    } else {
        echo passErrors($errors);
        exit;
    }

}else{
    $errors[] = "One of the variables is not set.";
    echo passErrors($errors);
    exit;
}