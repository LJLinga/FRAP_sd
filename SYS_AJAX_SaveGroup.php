
<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/25/2019
 * Time: 5:06 AM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$groupId = $_POST['groupId'];
$groupDesc = $_POST['groupDesc'];

$crud->execute("UPDATE groups SET groupDesc = '$groupDesc' WHERE id = '$groupId'");