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

    if($checkForHealthAidApplication['APP_STATUS'] == 1 && $checkForHealthAidApplication['PICKED_UP_STATUS'] == 1){

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA summary.php");

    }else if($checkForHealthAidApplication['APP_STATUS'] == 2 && $checkForHealthAidApplication['PICKED_UP_STATUS'] == 1){

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA summary.php");

    }else if($checkForHealthAidApplication['APP_STATUS'] == 2 && $checkForHealthAidApplication['PICKED_UP_STATUS'] == 2){

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA summary.php");

    }
    else{
        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA application.php");

    }

}


//heres the realest code

$uploadDirectory = "EDMS_Documents/";

$errors = []; // Store all foreseen and unforseen errors here

$fileExtensions = ['jpeg','jpg','png','ppt','doc','docx','pptx','pdf']; // Get all the file extensions

if(!empty($_FILES['upload_file'])){

    //insert query for health aid and save the record id for transactions
    $recordID = $crud->executeGetKey("INSERT INTO HEALTH_AID (MEMBER_ID, AMOUNT_TO_BORROW, MESSAGE, APP_STATUS) 
                                                            VALUES({$_SESSION['idnum']}, {$_POST['amount']},'{$_POST['message']}', 4)");
//
//    $crud->execute("INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, HA_REF, SERVICE_ID)
//                          VALUES({$_SESSION['idnum']}, 1, 'Health Aid Application Sent!', 0.00 , {$recordID}, 2)");




    foreach ($_FILES['upload_file']['tmp_name'] as $key => $tmp_name) {

        $userId = $_SESSION['idnum'];
        $title = $_FILES['upload_file']['name'][$key];
        $typeId = 3; // check the db for what type is needed for this. 3 is for Health aid, 2 for loans


        $file_name = $_FILES['upload_file']['name'][$key];
        $file_size = $_FILES['upload_file']['size'][$key];
        $file_tmp = $_FILES['upload_file']['tmp_name'][$key];
        $file_type = $_FILES['upload_file']['type'][$key];

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

        $didUpload = move_uploaded_file($file_tmp, $uploadPath);
        $reqType++;

        if ($didUpload) {


            $stepId = '1';
            $rows = $crud->getFirstStepIdOfDocType($typeId);
            if (!empty($rows)) {
                foreach ((array)$rows as $key2 => $row) {
                    $stepId = $row['id'];
                }

                if ($insertDocument = $crud->executeGetKey("INSERT INTO documents (firstAuthorId, authorId, stepId, typeId, filePath, title) VALUES ('$userId','$userId','$stepId','$typeId','$uploadPath','$title')")) {


                    $crud->execute("INSERT INTO ref_document_healthaid(RECORD_ID, DOC_ID) VALUES ({$recordID},{$insertDocument})");

                    $msg = array(
                        'success' => '1',
                        'id' => $insertDocument
                    );
                    echo json_encode($msg);

                } else {
                    $errors[] = "Cannot insert to database.";
                    echo passErrors($errors);

                }

            } else {
                $errors[] = "Invalid workflow or step.";
                echo passErrors($errors);
            }

        } else {
            $errors[] = "File upload error.";
            echo passErrors($errors);

        }

    }


    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA summary.php");
    //which means it twas a sucess!

}else{
    $errors[] = "One of the variables is not set.";
    echo passErrors($errors);
    exit;
    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA application.php");
}
