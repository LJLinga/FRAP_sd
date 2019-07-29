<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/23/2019
 * Time: 11:28 PM
 */

require_once __DIR__ . '/vendor/autoload.php';
include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

    $title = $crud->escape_string($_POST['event_title']);
    $body = $crud->escape_string($_POST['event_content']);
    $startTime =  $_POST['event_start'];
    $endTime =  $_POST['event_end'];
    $userId =  $_POST['userId'];
    $email_array = $_POST['toAddEmails'];
    $startTime = preg_replace('/\s+/', 'T', $startTime);
    $endTime = preg_replace('/\s+/', 'T', $endTime);

    $bool = $crud->insertCalendarEvent($userId, $title, $body, $startTime, $endTime, $email_array, 'DAILY','1');
    if($bool == false){
        echo 'Adding event failed.';
    }else{
        echo 'success';
    }
