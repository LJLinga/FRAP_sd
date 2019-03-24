<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/25/2019
 * Time: 5:06 AM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$processId = $_POST['processId'];
$processName = $_POST['processName'];

$crud->execute("UPDATE process SET processName = '$processName' WHERE id = '$processId'");