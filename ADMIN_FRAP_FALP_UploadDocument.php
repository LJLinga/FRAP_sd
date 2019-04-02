<?php
session_start();
require 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();

require_once ("mysql_connect_FA.php");

//this is the cockblock from using this goddamn page


//heres the realest code

$uploadDirectory = "EDMS_Documents/";

$errors = []; // Store all foreseen and unforseen errors here

$fileExtensions = ['jpeg','jpg','png','ppt','doc','docx','pptx','pdf']; // Get all the file extensions

$query = "SELECT MEMBER_ID FROM loans WHERE LOAN_ID = ". $_SESSION['showFID'] .";";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);

if(isset($_POST['reject'])){
    //Change the status into Approved (APP_STATUS =2)
    $query = "UPDATE LOANS SET APP_STATUS = '3', PICKUP_STATUS= '4' ,RESPONSE = '{$_POST['response']}' ,DATE_APPROVED = NOW(), EMP_ID =". $_SESSION['idnum'] ." WHERE LOAN_ID =" . $_SESSION['showFID'].";";
    $result = mysqli_query($dbc, $query);

    //Insert into transaction table
    $queryTnx = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF, EMP_ID, SERVICE_ID) 
                                        VALUES({$row['MEMBER_ID']}, '1', 'FALP Rejected, check Summary for the Reason. ', 0, NOW(), {$_SESSION['showFID']}, {$_SESSION['idnum']}, '4'); ";
    $resultTnx = mysqli_query($dbc, $queryTnx);



    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN FALP applications.php");

}else if(!empty($_FILES['upload_file']) && isset($_POST['accept'])){

    $query = "SELECT MEMBER_ID FROM loans WHERE LOAN_ID = ". $_SESSION['showFID'] .";";
    $result = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($result);


    //Change the status into Approved (APP_STATUS =2)
    $query = "UPDATE LOANS SET APP_STATUS = '2',  DATE_APPROVED = NOW(), EMP_ID =". $_SESSION['idnum'] ." WHERE LOAN_ID =" . $_SESSION['showFID'].";";
    $result = mysqli_query($dbc, $query);

        //Insert into transaction table
    $queryTnx = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF, EMP_ID, SERVICE_ID) 
            VALUES({$row['MEMBER_ID']}, '1', 'FALP Approved, now wait for Pickup. ', 0, NOW(), {$_SESSION['showFID']}, {$_SESSION['idnum']}, '4'); ";
    $resultTnx = mysqli_query($dbc, $queryTnx);




    //insert query for health aid and save the record id for transactions


    foreach($_FILES['upload_file']['tmp_name'] as $key => $tmp_name){

        $userId = $_SESSION['idnum'];
        $title = $_FILES['upload_file']['name'][$key];
        $typeId = 6; // check the db for what type is needed for this. 5 is for Health aid

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
                $crud->execute("INSERT INTO ref_document_loans(LOAN_ID, DOC_ID, DOC_REF_TYPE) VALUES ({$_SESSION['showFID']},{$insertDocument},2)");



            } else {
                echo "An error occurred somewhere. Try again or contact the admin";
            }

        }

    }
    //which means it twas a sucess!
    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN FALP applications.php");



}else{ //ono, theres some failure apparently

    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN FALP appdetails.php");

}
