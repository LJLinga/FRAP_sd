<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/24/2019
 * Time: 6:10 AM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$processId = $_POST['processId'];
$typeId = $_POST['typeId'];

$crud->execute("UPDATE doc_type SET processId = '$processId' WHERE id = '$typeId'");

if(isset($_POST['isActive'])){
    $isActive = $_POST['isActive'];
    $crud->execute("UPDATE doc_type SET isActive = '$isActive' WHERE id = '$typeId'");
}