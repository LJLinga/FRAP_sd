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

if(!empty($_POST['documentTitle']) && !empty($_POST['selectedType']) && !empty($_POST['userId']) ) {

    $userId = $_POST['userId'];
    $title = $_POST['documentTitle'];
    $typeId = $_POST['selectedType'];

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

            $stepId = '999'; $statusId = '99';
            $rows = $crud->getData("SELECT s.id FROM steps s 
                                  JOIN process pr ON s.processId = pr.id JOIN doc_type t ON t.processId = pr.id 
                                  WHERE t.id = '$typeId' AND s.stepNo = 1 LIMIT 1;");
            if(!empty($rows)) {
                foreach ((array)$rows as $key => $row) {
                    $stepId = $row['id'];
                }
                if($stepId != '999'){
                    $statusId = '1';
                }
            }

            $insertDocument = $crud->executeGetKey("INSERT INTO documents (firstAuthorId, stepId, statusedById, typeId, statusId) VALUES ('$userId', '$stepId','$userId','$typeId','$statusId')");
            $crud->execute("INSERT into doc_versions (documentId, authorId, versionNo, title, filePath) VALUES ('$insertDocument','$userId','1.0','$title','$uploadPath')");
            echo $insertDocument;

        } else {
            echo "An error occurred somewhere. Try again or contact the admin";
        }

    } else {
        foreach ($errors as $error) {
            echo $error . "These are the errors" . "\n";
        }
    }

}else{
    echo 'error';
}