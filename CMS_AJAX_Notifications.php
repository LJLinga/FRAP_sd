<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/9/2019
 * Time: 1:51 PM
 */

require 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();

if(isset($_POST['userId']) && isset($_POST['cmsRole'])){

    $userId = $_POST['userId'];
    $cmsRole = $_POST['cmsRole'];



    if($cmsRole == 3){
        $where = "WHERE s.id = 2 
                    OR s.id = 3 AND p.reviewedById = '$userId' 
                    OR s.id = 4 AND p.reviewedById = '$userId' 
                    OR p.authorId = '$userId' 
                    OR p.archivedById = '$userId'";

        // reviewer, should see recently changed into for publication
        // should also see in status = 2 that has been commented on
        // should also see in status = 3 that has been commented on


    }else if($cmsRole == 2 )
        $where = "WHERE s.id = 2 
                    OR s.id = 3 AND p.reviewedById = '$userId' 
                    OR s.id = 4 AND p.reviewedById = '$userId' 
                    OR p.authorId = '$userId' 
                    OR p.archivedById = '$userId'";

    $query = "SELECT p.id,p.title, p.authorId,CONCAT(a.firstName,' ', a.lastName) AS name,
              s.description AS status,p.lastUpdated FROM posts p JOIN employee a ON p.authorId = a.EMP_ID
              JOIN post_status s ON s.id = p.statusId
              '$where'
              ORDER BY p.lastUpdated DESC LIMIT 20;";

    $rows = $crud->getData($query);
    $output = 'No Posts to Show';
    if(!empty($rows)){
        foreach ((array) $rows as $key => $row){
            $output .= '<div class="card-body" style="position: relative;">';
            $output .= ''.$row['title'];
            $output .= '<a href="CMS_EditPost.php?postId='.$row['id'].'" class="btn btn-sm" style="position: absolute;right: 10px;top: 5px;">Continue</a>';
            $output .= '</div>';
        }
    }

    echo json_encode($output);
}else{
    echo 'user Id not set';
}

?>