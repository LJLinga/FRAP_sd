<?php
session_start();
require 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();

require_once ("mysql_connect_FA.php");

$checkForHealthAidApplicationQuery = "SELECT * FROM health_aid where MEMBER_ID = {$_SESSION['idnum']} ORDER BY RECORD_ID DESC LIMIT 1";
$checkForHealthAidApplicationResult = mysqli_query($dbc,$checkForHealthAidApplicationQuery);
$checkForHealthAidApplication = mysqli_fetch_array($checkForHealthAidApplicationResult);
//this is the cockblock from using this goddamn page

if(!empty($checkForHealthAidApplication)){

    if($checkForHealthAidApplication['APP_STATUS'] == 1){

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA summary.php");

    }else if($checkForHealthAidApplication['APP_STATUS'] == 2 && $checkForHealthAidApplication['PICKED_UP_STATUS'] == 1){

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA summary.php");

    }else if($checkForHealthAidApplication['APP_STATUS'] == 2 && $checkForHealthAidApplication['PICKUP_STATUS'] == 3){ //which means it was accepted and was picked up.

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA application.php");

    }else if($checkForHealthAidApplication['APP_STATUS'] == 3){ //which means the fucker got rejected lulzzzzzzzzzz meaning you dont have to check the pickupstatus

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA application.php");

    }

}

//heres the realest code

$uploadDirectory = "EDMS_Documents/";

$errors = []; // Store all foreseen and unforseen errors here

$fileExtensions = ['jpeg','jpg','png','ppt','doc','docx','pptx','pdf']; // Get all the file extensions

if(!empty($_FILES['upload_file'])){

    //insert query for health aid and save the record id for transactions
    $recordID = $crud->executeGetKey("INSERT INTO HEALTH_AID (MEMBER_ID, AMOUNT_TO_BORROW, MESSAGE) 
                                                            VALUES({$_SESSION['idnum']}, {$_POST['amount']},'{$_POST['message']}')");

    $crud->execute("INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, HA_REF, SERVICE_ID) 
                          VALUES({$_SESSION['idnum']}, 1, 'Health Aid Application Sent!', {$_POST['amount']}, {$recordID}, 2)");




    foreach($_FILES['upload_file']['tmp_name'] as $key => $tmp_name){

        $userId = $_SESSION['idnum'];
        $title = $userId."_".$recordID."_".$_FILES['upload_file']['name'][$key];
        $typeId = 3; // check the db for what type is needed for this. 3 is for Health aid

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
                $insertDocument = $crud->executeGetKey("INSERT INTO documents (firstAuthorId, stepId, statusedById, typeId, statusId) VALUES ('$userId', '$stepId','$userId','$typeId','$statusId')");
                $crud->execute("INSERT into doc_versions (documentId, authorId, versionNo, title, filePath) VALUES ('$insertDocument','$userId','1.0','$title','$uploadPath')");
                echo $insertDocument;

                //insert query for the reference documents
                $crud->execute("INSERT INTO ref_document_healthaid(RECORD_ID, DOC_ID) VALUES ({$recordID},{$insertDocument}) ");

            } else {
                echo "An error occurred somewhere. Try again or contact the admin";
            }

        }

    }
    //which means it twas a sucess!
    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA summary.php");



}else{ //ono, theres some failure apparently

    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA application.php");

}
