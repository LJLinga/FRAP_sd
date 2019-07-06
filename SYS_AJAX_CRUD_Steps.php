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


if(isset($_POST['btnUpdateStep'])){
    $processId = $_POST['processId'];
    $stepId = $_POST['stepId'];
    $stepNo = $_POST['stepNo'];
    $stepName = $_POST['stepName'];
    $canApprove = $_POST['isFinal'];
    if($crud->execute("UPDATE steps SET stepName = '$stepName', stepNo = '$stepNo', isFinal = '$canApprove' WHERE id = '$stepId'")){
        echo 'success';
    }else{
        alert('Database errors.');
    }
    exit;
    //header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Process_Steps.php?id=".$processId);
}

exit;

