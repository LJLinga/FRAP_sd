<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/24/2019
 * Time: 5:01 AM
 */


include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$cms = $_POST['cms'];
$edms = $_POST['edms'];
$frap = $_POST['frap'];
$userId = $_POST['userId'];

$crud->execute("UPDATE employee SET EDMS_ROLE = '$edms', CMS_ROLE = '$cms', FRAP_ROLE = '$frap' WHERE EMP_ID = '$userId'");