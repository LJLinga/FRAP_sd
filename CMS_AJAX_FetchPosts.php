<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 2/24/2019
 * Time: 4:55 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
session_start();


$query = "SELECT p.id,p.title, p.authorId,CONCAT(a.firstName,' ', a.lastName) AS name,
          s.description AS status, p.lastUpdated 
          FROM posts p JOIN employee a ON p.authorId = a.EMP_ID
          JOIN post_status s ON s.id = p.statusId
          ORDER BY p.lastUpdated DESC;";

$cmsRole = '69';

if($cmsRole == '4'){
    // Editor can see all his posts and drafts, and all "pending","published",and "archived" posts that are not his but not other's drafts
    $query = "SELECT p.id,
                                                                  p.title, p.authorId,
                                                                  CONCAT(a.firstName,' ', a.lastName) AS name,
                                                                  s.description AS status,
                                                                  p.lastUpdated
                                                                  FROM posts p JOIN employee a ON p.authorId = a.EMP_ID
                                                                  JOIN post_status s ON s.id = p.statusId
                                                                  WHERE s.id = 3 OR s.id = 4
                                                                  OR p.authorId = '$userId'
                                                                  OR p.archivedById = '$userId'
                                                                  ORDER BY p.lastUpdated DESC;";
}else if($cmsRole == '3'){
    // Non-editors can only view their posts, can also see their "published" and "archived" but would not be able to modify them.
    $query = "SELECT p.id,
                                            p.title, p.authorId,
                                            CONCAT(a.firstName,' ', a.lastName) AS name,
                                            s.description AS status,
                                                                  p.lastUpdated
                                                                  FROM posts p JOIN employee a ON p.authorId = a.EMP_ID
                                                                  JOIN post_status s ON s.id = p.statusId
                                                                  WHERE s.id = 2 
                                                                  OR s.id = 3 AND p.reviewedById = '$userId'
                                                                  OR s.id = 4 AND p.reviewedById = '$userId'
                                                                  OR p.authorId = '$userId'
                                                                  OR p.archivedById = '$userId'
                                                                  ORDER BY p.lastUpdated DESC;";
}else if($cmsRole == '2'){
    $query = "SELECT p.id,
                                            p.title, p.authorId,
                                            CONCAT(a.firstName,' ', a.lastName) AS name,
                                            s.description AS status,
                                                                  p.lastUpdated
                                                                  FROM posts p JOIN employee a ON p.authorId = a.EMP_ID
                                                                  JOIN post_status s ON s.id = p.statusId
                                                                  WHERE p.authorId = '$userId'
                                                                  OR p.archivedById = '$userId'
                                                                  ORDER BY p.lastUpdated DESC;";
}



$rows = $crud->getData($query);
$data = [];
foreach ((array) $rows as $key => $row) {
    $data[] =  array(
        'title' => $row['title'],
        'name' => $row['name'],
        'status' => $row['status'],
        'lastUpdated' => $row['lastUpdated'],
        'actions'=> '<form method="GET" action="CMS_EditPost.php"><button type="submit" name="postId" class="btn btn-default" value='.$row['id'].'>Edit</button></form>'
    );
}
echo json_encode($data);
exit;

?>