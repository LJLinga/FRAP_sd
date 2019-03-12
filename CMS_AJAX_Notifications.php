<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/9/2019
 * Time: 1:51 PM
 */

require 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();

if(isset($_POST['userId']) && isset($_POST['limit'])){

    $userId = $_POST['userId'];

    $limit = '';
    if($_POST['limit'] != 0) {
        $limit = "LIMIT " . $_POST['limit'];
    }

    $query = "SELECT a1.postId, a1.message, a1.timeStamp FROM post_activity a1 WHERE a1.displayToId = $userId AND a1.timeStamp =
                (SELECT MAX(a2.timeStamp)
                    FROM post_activity a2 WHERE a2.postId = a1.postId
                    GROUP BY a2.postId 
                ) 
                ORDER BY a1.timeStamp DESC $limit;";

    $rows = $crud->getData($query);
    $output = '';
    $count = 0;
    if(!empty($rows)){
        foreach ((array) $rows as $key => $row){
            $output .= '<a href="CMS_EditPost.php?postId='.$row['postId'].'" >';
            $output .= '<div class="card-body"  href="CMS_EditPost.php?postId="'.$row['postId'].'">';
            $output .= $row['message'];
            $output .= '<br>'.date("F j, Y g:i:s A ", strtotime($row['timeStamp']));
            $output .= '<br>';
            $output .= '</div>';
            $output .= '</a>';
            $count++;
        }
    }else{
        $output = 'No Posts to Show';
    }

    $data = array(
        'notification' => $output,
        'count' => $count
    );

    echo json_encode($data);
}else{
    echo 'user Id not set';
}

?>