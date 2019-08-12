<?php
require('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
session_start();


if(isset($_POST['count'])){
    $countWhat = $_POST['count'];
    $body = '0';
    if($countWhat == 'DOC_PENDING'){
        $rows = $crud->displayPendingDocumentsCount($_SESSION['idnum']);
        if(!empty($rows)){
            $body = $rows[0]['count'];
        }else{
            $body = '';
        }
    }else if($countWhat == 'DOC_IN_PROCESS'){
        $rows = $crud->displayInProcessDocumentsCount($_SESSION['idnum']);
        if(!empty($rows)){
            $body = $rows[0]['count'];
        }else{
            $body = '';
        }
    }
    echo $body;
}

if(isset($_POST['typeId'])){
    $typeId = $_POST['typeId'];
    $rows = $crud->displayUserNotificationsByType($_SESSION['idnum'],$typeId);
    $html = '<a>No document notifications.</a>';
    $ctr = 0;
    $output = array();
    if(!empty($rows)){
        $html = '';
        foreach ((array) $rows as $key => $row) {
            $html.= '<a><form method="post" action="notification_intercept.php">
                <input name="id" type="hidden" value="'.$row['id'].'">
                <button name="hyperlink" style="text-align: left; background: transparent; border: none !important;" type="submit" value="'.$row['hyperlink'].'">
                ';
            $html.= $crud->getUserName($row['senderId']).' ';
            $html.= $row['message'];
            $html.='<br>'.$crud->friendlyDate($row['timestamp']);
            $html.= '</button></form></a>';
            $html.= '';
            $ctr++;
        }
    }

    $data =  array(
        'notifications' => $html,
        'count' => $ctr
    );
    echo json_encode($data);
    exit;
}


?>