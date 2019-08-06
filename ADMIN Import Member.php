<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 05/08/2019
 * Time: 5:45 PM
 */


require_once('mysql_connect_FA.php');
require 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();


$crud->execute("INSERT INTO test (message) 
                          VALUES('Hello TEST') ");

?>