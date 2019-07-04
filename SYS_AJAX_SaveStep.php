<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/25/2019
 * Time: 4:55 AM
 */

/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/24/2019
 * Time: 5:01 AM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$stepNo = $_POST['stepNo'];
$stepName = $_POST['stepName'];
$canApprove = $_POST['isFinal'];
$stepType = $_POST['stepType'];

$requestType = $_POST['requestType'];

if($requestType == 'insertStep'){
    $processId = $_POST['processId'];
    if($crud->execute("INSERT INTO steps (stepNo, stepName, isFinal, processId) VALUES ('$stepNo','$stepName','$canApprove','$processId')")){
        echo 'success';
    }else{
        echo 'failed';
    }
}else if($requestType == 'updateStep'){
    $stepId = $_POST['stepId'];
    if($crud->execute("UPDATE steps SET stepName = '$stepName', stepNo = '$stepNo', isFinal = '$canApprove' WHERE id = '$stepId'")){
        echo 'success';
    }else{
        echo 'failed';
    }
}

exit;

