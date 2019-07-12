<?php
require('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
session_start();

$rows = $crud->displayUnseenUserNotifications($_SESSION['idnum']);
$html = '';
$ctr = 0;
$output = array();
if(!empty($rows)){
    foreach ((array) $rows as $key => $row) {
        $html.= '<form method="post" action="notification_intercept.php">
                <input name="id" type="hidden" value="'.$row['id'].'">
                <button name="hyperlink" style="background: transparent; border: none !important;" type="submit" value="'.$row['hyperlink'].'">
                ';
        $html.= $crud->getUserName($row['senderId']).' ';
        $html.= $row['message'];
        $html.= '</button></form>';
        $html.= '';
        $ctr++;
        $output = $output + array ($ctr => $html);
    }
}else{
    $html = '<div class="card-body">All notifications seen.</div>';
    $output = array ($html);
}


$data =  array(
    'notifications' => $output,
    'count' => $ctr
);
echo json_encode($data);
exit;

?>