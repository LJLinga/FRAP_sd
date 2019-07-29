<?php


require 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();
session_start();
$userId = $_SESSION['idnum'];


if(isset($_POST['requestType'])){
    $requestType = $_POST['requestType'];
    if($requestType == 'POST_EDITING'){
        // Your post has been approved/rejected/whaterver by user -> redirect to edit post
    }else if($requestType == 'POST_COMMENTING'){
        // This person commented "X" on your post "title" -> redirect to post
    }else if($requestType == 'POST_VOTING'){
        // This person has voted X on your poll -> redirect to post containing poll
    }else if($requestType == 'EVENT_INVITE'){
        // You are invited to an event -> redirect to Google Calendar
    }else if($requestType == 'GROUP_INVITE'){
        // You have been invited into group -> redirect to GRPs screen
    }else if($requestType == 'GROUP_MEMBERSHIP'){
        // You are now a member of the group -> redirect to GRP_Settings screen
    }
}

?>