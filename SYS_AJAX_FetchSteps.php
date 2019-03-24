<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/25/2019
 * Time: 4:42 AM
 */


include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
$rows = $crud->getData("SELECT id, stepName, stepNo, isFinal FROM steps s WHERE s.processId='$processId';");

$data = [];
foreach((array)$rows AS $key => $row){
    $row['id'];
    $row['stepName'];
    $row['stepNo'];
    $row['isFinal'];
}