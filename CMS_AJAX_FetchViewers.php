<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/19/2019
 * Time: 2:36 AM
 */


require 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();

if(isset($_POST['postId'])){
    $postId = $_POST['postId'];

    $query = "SELECT timeStamp, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) as name 
                FROM post_views v JOIN employee e ON e.EMP_ID = v.viewerId 
                WHERE typeId = 2 AND id = '$postId' GROUP BY v.viewerId ORDER BY timestamp DESC;";

    $rows = $crud->getData($query);
    $html = '';
    $output = '';
    $count = 0;
    if(!empty($rows)){
        foreach ((array) $rows as $key => $row) {
            $html .= '<b>'.$row['name'].'</b> ('.date("F j, Y g:i:s A ", strtotime($row['timeStamp'])).')<br>';
            $count++;
        }
        $output = 'Seen by '.$count.' people.<br>';
        $output .= $html;
    }
    echo $output;
}else{
    echo 'post not set';
}
?>