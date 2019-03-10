<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 2/24/2019
 * Time: 4:55 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

if(isset($_POST['userId']) && isset($_POST['cmsRole'])){

    $userId = $_POST['userId'];
    $cmsRole = $_POST['cmsRole'];

    if($cmsRole == 4){
        // Editor can see all his posts and drafts, and all "pending","published",and "archived" posts that are not his but not other's drafts
        $query = "SELECT p.id,p.title, p.authorId,CONCAT(a.firstName,' ', a.lastName) AS name,s.description AS status, p.lastUpdated 
                  FROM posts p JOIN employee a ON p.authorId = a.EMP_ID
                  JOIN post_status s ON s.id = p.statusId
                  WHERE s.id = 3 OR s.id = 4
                  OR p.authorId = '$userId'
                  OR p.archivedById = '$userId'
                  ORDER BY p.lastUpdated DESC;";
    }else if($cmsRole == 3){
        // Non-editors can only view their posts, can also see their "published" and "archived" but would not be able to modify them.
        $query = "SELECT p.id,p.title, p.authorId,CONCAT(a.firstName,' ', a.lastName) AS name,
                  s.description AS status,p.lastUpdated 
                  FROM posts p JOIN employee a ON p.authorId = a.EMP_ID
                  JOIN post_status s ON s.id = p.statusId
                  WHERE s.id = 2 OR p.authorId = '$userId'
                  OR p.archivedById = '$userId'
                  ORDER BY p.lastUpdated DESC;";
    }else if($cmsRole == 2){
        $query = "SELECT p.id,p.title, p.authorId,CONCAT(a.firstName,' ', a.lastName) AS name,s.description AS status,p.lastUpdated
                  FROM posts p JOIN employee a ON p.authorId = a.EMP_ID
                  JOIN post_status s ON s.id = p.statusId
                  WHERE p.authorId = '$userId'
                  OR p.archivedById = '$userId'
                  ORDER BY p.lastUpdated DESC;";
    }

    $rows = $crud->getData($query);
    $data = [];
    if(!empty($rows)){


        if($cmsRole == 3 || $cmsRole == 4) {
            foreach ((array) $rows as $key => $row) {
                $data[] =  array(
                    'title' => $row['title'],
                    'name' => $row['name'],
                    'status' => $row['status'],
                    'lastUpdated' => $row['lastUpdated'],
                    'actions'=> '<a href="CMS_EditPost.php?postId='.$row['id'].'" class="btn btn-default">Edit</a>'
                );
            }
        }else{
            foreach ((array) $rows as $key => $row) {
                $data[] =  array(
                    'title' => $row['title'],
                    'status' => $row['status'],
                    'lastUpdated' => $row['lastUpdated'],
                    'actions'=> '<a href="CMS_EditPost.php?postId='.$row['id'].'" class="btn btn-default">Edit</a>'
                );
            }
        }

        echo json_encode($data);
        exit;
    }else{
        echo 'empty';
    }


}else{
    echo 'userId or cmsRole not set';
}



?>