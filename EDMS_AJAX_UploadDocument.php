<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 2/28/2019
 * Time: 9:27 AM
 */


require 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();

$currentDir = getcwd();
$uploadDirectory = "/EDMS_Documents/";

$errors = []; // Store all foreseen and unforseen errors here


$fileExtensions = ['jpeg','jpg','png','ppt','doc','docx','pptx','pdf']; // Get all the file extensions

if(isset($_POST['btnSubmit']) && !empty($_POST['documentTitle']) && !empty($_FILES['fileName']) && $_FILES['selectedTask'] && !empty($_POST['userId']) ) {

    $fileName = $_FILES['fileName']['name'];
    $fileSize = $_FILES['fileName']['size'];
    $fileTmpName = $_FILES['fileName']['tmp_name'];
    $fileType = $_FILES['fileName']['type'];
    $fileExtension = strtolower(end(explode('.', $fileName)));

    $uploadPath = $currentDir . $uploadDirectory . basename($fileName);

    if (!in_array($fileExtension, $fileExtensions)) {
        $errors[] = "This file extension is not allowed. Only JPEG, PNG, PPT, DOC, DOCX, PPTX, and PDF are accepted.";
    }

    if ($fileSize > 25000000) {
        $errors[] = "This file is more than 25MB. Sorry, it has to be less than or equal to 25MB";
    }




    if (empty($errors)) {
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

        if ($didUpload) {
            echo "The file " . basename($fileName) . " has been uploaded";
        } else {
            echo "An error occurred somewhere. Try again or contact the admin";
        }

        $insert = $crud->execute("INSERT document (name,email,file_name) VALUES ('".$name."','".$email."','".$path."')");
        if(!$insert){
            echo "Database insert error.";
        }

    } else {
        foreach ($errors as $error) {
            echo $error . "These are the errors" . "\n";
        }
    }

}