<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/22/2019
 * Time: 12:08 AM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

if(isset($_POST['userId']) && isset($_POST['responseId'])){
    $userId = $_POST['userId'];
    $responseId = $_POST['responseId'];
    $crud->execute("INSERT INTO poll_responses(responseId, responderId) VALUES ('$responseId','$userId')");
}