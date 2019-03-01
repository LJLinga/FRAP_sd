<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 2/28/2019
 * Time: 9:27 AM
 */


require 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();

//$currentDir = getcwd();
//$uploadDirectory = "/EDMS_Documents/";
$uploadDirectory = "EDMS_Documents/";

$errors = []; // Store all foreseen and unforseen errors here

$fileExtensions = ['jpeg','jpg','png','ppt','doc','docx','pptx','pdf']; // Get all the file extensions

if(!empty($_POST['documentTitle']) && !empty($_POST['selectedTask']) && !empty($_POST['userId']) ) {

    $userId = $_POST['userId'];
    $title = $_POST['documentTitle'];
    $process = $_POST['selectedTask'];

    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileType = $_FILES['file']['type'];
    $string = explode('.', $fileName);
    $fileExtension = strtolower(end($string));

    //$uploadPath = $currentDir . $uploadDirectory . basename($fileName);
    $uploadPath = $uploadDirectory . basename($fileName);

    if (!in_array($fileExtension, $fileExtensions)) {
        $errors[] = "This file extension is not allowed. Only JPEG, PNG, PPT, DOC, DOCX, PPTX, and PDF are accepted.";
    }

    if ($fileSize > 25000000) {
        $errors[] = "This file is more than 25MB. Sorry, it has to be less than or equal to 25MB";
    }




    if (empty($errors)) {
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

        if ($didUpload) {
//            $insertDocument = $crud->executeGetKey("INSERT INTO documents (firstAuthorId, processId) VALUES ('$userId', '$process')");
//            if(!$insertDocument){
//                echo "Database document insert error.";
//                $insertVersion = $crud->execute("INSERT into doc_versions (documentId, authorId, versionNo, title, filePath) VALUES ('$insertDocument','$userId','1.0','$title','$uploadPath')");
//                if(!$insertVersion){
//                    echo "Database version insert error.";
//                }else{
//                    echo $insertDocument;
//                }
//            }

            //$insertDocument = $crud->executeGetKey("INSERT INTO documents (firstAuthorId, processId) VALUES ('$userId', '$process')");
            //$crud->execute("INSERT into doc_versions (documentId, authorId, versionNo, title, filePath) VALUES ('$insertDocument','$userId','1.0','$title','$uploadPath')");

        } else {
            echo "An error occurred somewhere. Try again or contact the admin";
        }


        $insertDocument = $crud->executeGetKey("INSERT INTO documents (firstAuthorId, processId) VALUES ('$userId', '$process')");
        $crud->execute("INSERT into doc_versions (documentId, authorId, versionNo, title, filePath) VALUES ('$insertDocument','$userId','1.0','$title','$uploadPath')");


    } else {
        foreach ($errors as $error) {
            echo $error . "These are the errors" . "\n";
        }
    }

}else{
    echo 'PHP Error.'.$_POST['userId'].' '.$_POST['documentTitle'].' '.$_POST['selectedTask'].' '.$_FILES['file'];
}