<?php

//fetch_comment.php
//
//
//include_once('GLOBAL_CLASS_CRUD.php');
//$crud = new GLOBAL_CLASS_CRUD();
//
//
//$query = "
//SELECT id, commenterId, parentCommentId, content, timePosted FROM doc_comments
//WHERE parentCommentId = '0'
//ORDER BY id DESC
//";
//
//$output = '';
//$rows1 = $crud->getData($query);
//foreach((array) $rows1 as $key => $row){
//    $output .= '
// <div class="panel panel-default">
//  <div class="panel-heading">By <b>'.$row["commenterId"].'</b> on <i>'.$row["timePosted"].'</i></div>
//  <div class="panel-body">'.$row["content"].'</div>
//  <div class="panel-footer" align="right"><button type="button" class="btn btn-default reply" id="'.$row["id"].'">Reply</button></div>
// </div>
// ';
//    $output .= get_reply_comment($crud, $row["id"]);
//}
//
//echo $output;
//
//function get_reply_comment($crud, $parent_id, $marginleft = 0)
//{
//    $query = "
//SELECT id, commenterId, parentCommentId, content, timePosted FROM doc_comments
//WHERE parentCommentId = '$parent_id'
//ORDER BY id DESC
//";
//    $output = '';
//    $rows1 = $crud->getData($query);
//
//
//    if($parent_id == 0)
//    {
//        $marginleft = 0;
//    }
//    else
//    {
//        $marginleft = $marginleft + 48;
//    }
//
//
//
//        foreach((array) $rows1 as $key => $row){
//            $output .= '
//   <div class="panel panel-default" style="margin-left:'.$marginleft.'px">
//    <div class="panel-heading">By <b>'.$row["commenterId"].'</b> on <i>'.$row["timePosted"].'</i></div>
//    <div class="panel-body">'.$row["comment"].'</div>
//    <div class="panel-footer" align="right"><button type="button" class="btn btn-default reply" id="'.$row["id"].'">Reply</button></div>
//   </div>
//   ';
//            $output .= get_reply_comment($crud, $row["id"], $marginleft);
//        }
//
//    return $output;
//}

$postId = $_POST['postId'];

$connect = new PDO('mysql:host=localhost;dbname=facultyassocnew', 'root', '1234');

$query = "
SELECT c.id, c.commenterId, c.parentCommentId, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS commenterName, 
c.content, c.timePosted FROM edit_post_comments c JOIN employee e WHERE c.commenterId = e.EMP_ID
AND c.parentCommentId = '0' AND c.postId = '$postId'
ORDER BY id DESC
";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();
$output = '';
foreach($result as $row)
{
    $output .= '
 <div class="card" style="margin-top: 1rem; font-size: small">
  <div class="card-header" style="font-size: x-small">by <b>'.$row["commenterName"].'</b> on <i>'.date("F j, Y g:i A ", strtotime($row['timePosted'])).'</i></div>
  <div class="card-body">'.$row["content"].'</div>
  <div class="card-footer" align="right"><button type="button" class="btn btn-default fa fa-reply reply" data-toggle="modal" data-target="#myModal" id="'.$row["id"].'"> Reply </button></div>
 </div>
 ';
    $output .= get_reply_comment($connect, $row["id"]);
}

echo $output;

function get_reply_comment($connect, $parent_id = 0, $marginleft = 0)
{

    $query = "
SELECT c.id, c.commenterId, c.parentCommentId, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS commenterName, 
c.content, c.timePosted FROM edit_post_comments c JOIN employee e WHERE c.commenterId = e.EMP_ID
AND c.parentCommentId = '$parent_id'
ORDER BY id DESC
";
    $output = '';
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $count = $statement->rowCount();
    if($parent_id == 0)
    {
        $marginleft = 0;
    }
    else
    {
        $marginleft = $marginleft + 48;
    }
    if($count > 0)
    {
        foreach($result as $row)
        {
            $output .= '
   <div class="card" style="margin-left:'.$marginleft.'px; margin-top: 1rem; font-size: small">
    <div class="card-header" style="font-size: x-small">by <b>'.$row["commenterName"].'</b> on <i>'.date("F j, Y g:i A ", strtotime($row['timePosted'])).'</i></div>
    <div class="card-body">'.$row["content"].'</div>
    <div class="card-footer" align="right">
        <button class="btn btn-default fa fa-reply reply" data-toggle="modal" data-target="#myModal" id="'.$row["id"].'"> Reply </button>
    </div>
   </div>
   ';
            $output .= get_reply_comment($connect, $row["id"], $marginleft);
        }
    }
    return $output;
}

?>