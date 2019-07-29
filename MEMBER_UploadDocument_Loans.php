<?php
session_start();
require 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();

require_once ("mysql_connect_FA.php");

function passErrors($errors){

    $msg = array(
        'success' => 0,
        'html' => $errors
    );
    return json_encode($msg);
}


//this is the cockblock from using this goddamn page


//heres the realest code

$uploadDirectory = "EDMS_Documents/";

$errors = []; // Store all foreseen and unforseen errors here

$fileExtensions = ['jpeg','jpg','png','ppt','doc','docx','pptx','pdf']; // Get all the file extensions


if(!empty($_FILES['upload_file'])) {

    //insert query for FALP
    $loanID = $crud->executeGetKey("INSERT INTO loans(MEMBER_ID,AMOUNT,INTEREST,PAYMENT_TERMS,PAYABLE,PER_PAYMENT,APP_STATUS,LOAN_STATUS, PICKUP_STATUS)
    values({$_SESSION['idnum']},{$_POST['amount']},{$_POST['interest']},{$_POST['payT']},{$_POST['amountP']},{$_POST['monD']}/2,1,4,1);");



    $reqType = 0;

    foreach ($_FILES['upload_file']['tmp_name'] as $key => $tmp_name) {

        $userId = $_SESSION['idnum'];
        $title = $_FILES['upload_file']['name'][$key];
        $typeId = 2; // check the db for what type is needed for this. 3 is for Health aid

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


                    $crud->execute("INSERT INTO ref_document_loans(LOAN_ID, DOC_ID, DOC_REQ_TYPE) VALUES ({$loanID},{$insertDocument},{$reqType})");

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


    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP reviewapp.php");
    //which means it twas a sucess!

}else{
    $errors[] = "One of the variables is not set.";
    echo passErrors($errors);
    exit;
    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP requirements.php");
}





//insert query for the reference documents
