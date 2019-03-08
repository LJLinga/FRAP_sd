<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/8/2019
 * Time: 6:36 PM
 */

function checkGroup($groupName){

    require 'GLOBAL_CLASS_CRUD.php';
    $crud = new GLOBAL_CLASS_CRUD();
    $userId = $_SESSION['idnum'];

    $rows = $crud->getData("SELECT x.groupName FROM facultyassocnew.user_groups JOIN groups x ON groupId = x.id WHERE user_groups.employeeId = '$userId' AND groupName='$groupName';");
    if(!empty($rows)){
        return true;
    }

    return false;
}

function hasGroup(){

    require 'GLOBAL_CLASS_CRUD.php';
    $crud = new GLOBAL_CLASS_CRUD();
    $userId = $_SESSION['idnum'];

    $rows = $crud->getData("SELECT x.groupName FROM facultyassocnew.user_groups JOIN groups x ON groupId = x.id WHERE user_groups.employeeId ='$userId';");
    if(!empty($rows)){
        return true;
    }

    return false;
}