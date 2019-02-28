<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 2/25/2019
 * Time: 2:29 AM
 */


require 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();

$type = '1';
$interval = '';
$option= '12';
$start = '';
$end = '';

if(isset($_POST['option'])){
    $option = $_POST['option'];
    if($option == '1'){
        $interval = "+ INTERVAL -24 HOUR";
    }else if($option == '2'){
        $interval = "+ INTERVAL -7 DAY";
    }else if($option == '3'){
        $interval = "+ INTERVAL -1 MONTH";
    }else if($option == '4'){
        $interval = "+ INTERVAL -1 YEAR";
    }else if($option == '5'){
        $interval = "+ INTERVAL -5 YEAR";
    }

    $query = "SELECT p.id, p.title, p.permalink,
                COUNT(CASE WHEN v.typeId = '1' THEN v.viewerId END) AS 'total views', 
                COUNT(CASE WHEN v.typeId = '2' THEN v.viewerId END) AS 'total previews', 
                COUNT(DISTINCT CASE WHEN v.typeId = '1' THEN v.viewerId END) AS 'unique views', 
                COUNT(DISTINCT CASE WHEN v.typeId = '2' THEN v.viewerId END) AS 'unique previews',
                (SELECT COUNT(CASE WHEN p.id = c.postId THEN c.id ELSE 0 END) FROM post_comments c WHERE c.postId = p.id AND c.timePosted >= NOW() $interval AND c.timePosted <  NOW()) AS 'total comments',
                (SELECT COUNT(DISTINCT CASE WHEN p.id = c.postId THEN c.commenterId ELSE 0 END) FROM post_comments c WHERE c.postId = p.id AND c.timePosted >= NOW() $interval AND c.timePosted <  NOW()) AS 'unique commenters'
                FROM posts p JOIN post_views v ON p.id = v.id
                WHERE timeStamp >= NOW() $interval
                AND timeStamp <  NOW()
                GROUP BY p.id;";

    if($option == '6' && isset($_POST['start']) && isset ($_POST['end'])){
        $start = $_POST['start'];
        $end = $_POST['end'];
        $query = "SELECT p.id, p.title, p.permalink,
                COUNT(CASE WHEN v.typeId = '1' THEN v.viewerId END) AS 'total views', 
                COUNT(CASE WHEN v.typeId = '2' THEN v.viewerId END) AS 'total previews', 
                COUNT(DISTINCT CASE WHEN v.typeId = '1' THEN v.viewerId END) AS 'unique views', 
                COUNT(DISTINCT CASE WHEN v.typeId = '2' THEN v.viewerId END) AS 'unique previews',
                (SELECT COUNT(CASE WHEN p.id = c.postId THEN c.id ELSE 0 END) FROM post_comments c WHERE c.postId = p.id AND c.timePosted BETWEEN ('$start') AND ('$end')) AS 'total comments',
                (SELECT COUNT(DISTINCT CASE WHEN p.id = c.postId THEN c.commenterId ELSE 0 END) FROM post_comments c WHERE c.postId = p.id AND c.timePosted BETWEEN ('$start') AND ('$end')) AS 'unique commenters'
                FROM posts p JOIN post_views v ON p.id = v.id
                WHERE timeStamp BETWEEN ('$start') AND ('$end')
                GROUP BY p.id;";
    }

    $rows = $crud->getData($query);
    $data = [];
    foreach ((array) $rows as $key => $row) {
        $data[] =  array(
            'title' => $row['title'],
            'views' => $row['total views'].' ('.$row['unique views'].')',
            'previews' => $row['total previews'].' ('.$row['unique previews'].')',
            'comments' => $row['total comments'].' ('.$row['unique commenters'].')',
            'action' => '<form method="GET" action="CMS_EditPost.php"><button type="submit" name="postId" class="btn btn-default" value='.$row['id'].'>Edit</button></form>'
        );
    }
    echo json_encode($data);
// DO NOT FORGET TO ECHO
    exit;
}else{
    echo  'lzl';
}

?>