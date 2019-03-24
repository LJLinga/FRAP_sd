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

$stepId = $_POST['stepId'];
$stepNo = $_POST['stepNo'];
$stepName = $_POST['stepName'];
$isFinal = $_POST['isFinal'];

$crud->execute("UPDATE steps SET stepName = '$stepName', stepNo = '$stepNo', isFinal = '$isFinal' WHERE id = '$stepId'");