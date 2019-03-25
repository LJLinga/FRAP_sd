<?php
session_start();
require 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();

require_once ("mysql_connect_FA.php");

//heres the realest code

$uploadDirectory = "EDMS_Documents/";

$errors = []; // Store all foreseen and unforseen errors here

$fileExtensions = ['jpeg','jpg','png','ppt','doc','docx','pptx','pdf']; // Get all the file extensions

if(isset($_POST['reject'])){

    $queryHA = "UPDATE health_aid set APP_STATUS = 3,PICKED_UP_STATUS = 4 ,EMP_ID = {$_SESSION['idnum']}, RESPONSE = '{$_POST['response']}', AMOUNT_GIVEN = 0.00 WHERE RECORD_ID = {$_SESSION['showHAID']}";
    mysqli_query($dbc, $queryHA);
    //insert into transactions
    $crud->execute("INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, HA_REF, SERVICE_ID) 
                          VALUES({$_SESSION['showHAMID']}, 1, 'Health Aid Application Rejected', 0.00, {$_SESSION['showHAID']}, 2)");


    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN HEALTHAID applications.php");

}else if(!empty($_FILES['upload_file']) && isset($_POST['accept'])){

    //insert query for health aid and save the record id for transactions
    $recordID = $_SESSION['showHAID'];

//    if (!mysqli_query($con,"INSERT INTO Persons (FirstName) VALUES ('Glenn')"))
//    {
//        echo("Error description: " . mysqli_error($con));
//    }


    $crud->execute("UPDATE health_aid set EMP_ID = {$_SESSION['idnum']}, APP_STATUS = 2, DATE_APPROVED = NOW(), AMOUNT_GIVEN = {$_POST['amount_to_give']}, RESPONSE = '{$_POST['response']}' WHERE RECORD_ID = {$recordID}");

    $crud->execute("INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, HA_REF, EMP_ID, SERVICE_ID) 
                          VALUES({$_SESSION['showHAMID']}, 1, 'Health Aid Application Accepted!', {$_POST['amount_to_give']}, {$recordID},{$_SESSION['idnum']} ,2)");




    foreach($_FILES['upload_file']['tmp_name'] as $key => $tmp_name){

        $userId = $_SESSION['idnum'];
        $title = $_FILES['upload_file']['name'][$key];
        $typeId = 99; // check the db for what type is needed for this. 99 is for misc

        $file_name = $_FILES['upload_file']['name'][$key];
        $file_size =$_FILES['upload_file']['size'][$key];
        $file_tmp =$_FILES['upload_file']['tmp_name'][$key];
        $file_type=$_FILES['upload_file']['type'][$key];

        $string = explode('.', $file_name);
        $fileExtension = strtolower(end($string));

        $temp = explode(".", $file_name);
        $newfilename = round(microtime(true)) . '.' . end($temp);

        //$uploadPath = $currentDir . $uploadDirectory . basename($fileName);
        $uploadPath = $uploadDirectory . basename($newfilename);

        if (!in_array($fileExtension, $fileExtensions)) {
            $errors[] = "This file extension is not allowed. Only JPEG, PNG, PPT, DOC, DOCX, PPTX, and PDF are accepted.";
        }

        if ($file_size > 25000000) {
            $errors[] = "This file is more than 25MB. Sorry, it has to be less than or equal to 25MB";
        }

        if (empty($errors)) {
            $didUpload = move_uploaded_file($file_tmp, $uploadPath);

            if ($didUpload) {

                $stepId = '999'; $statusId = '99';
                $rows = $crud->getData("SELECT s.id FROM steps s 
                                  JOIN process pr ON s.processId = pr.id JOIN doc_type t ON t.processId = pr.id 
                                  WHERE t.id = '$typeId' AND s.stepNo = 1 LIMIT 1;");
                if(!empty($rows)) {
                    foreach ((array)$rows as $keys => $row) {
                        $stepId = $row['id'];
                    }
                    if($stepId != '999'){
                        $statusId = '1';
                    }
                }


                //insert query for the docs
                $insertDocument = $crud->executeGetKey("INSERT INTO documents (firstAuthorId, authorId, stepId, typeId, statusId, versionNo, filePath, title) VALUES ('$userId','$userId','$stepId','$typeId','$statusId','1.0','$uploadPath','$title')");
                echo $insertDocument;

                //insert query for the reference documents
                $crud->execute("INSERT INTO ref_document_healthaid(RECORD_ID, DOC_ID, DOC_REF_TYPE) VALUES ({$recordID},{$insertDocument},2) ");

            } else {
                echo "An error occurred somewhere. Try again or contact the admin";
            }

        }

    }
    //which means it twas a sucess!
    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN HEALTHAID applications.php");



}else{ //ono, theres some failure apparently

    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN HEALTHAID appdetails.php");

}
