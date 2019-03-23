<?php
//
////add_comment.php
//
//include_once('GLOBAL_CLASS_CRUD.php');
//$crud = new GLOBAL_CLASS_CRUD();
//
//$error = '';
//$comment_name = '';
//$comment_content = '';
//
//if(empty($_POST["comment_name"]))
//{
//    $error .= '<p class="text-danger">Name is required</p>';
//}
//else
//{
//    $comment_name = $_POST["comment_name"];
//}
//
//if(empty($_POST["comment_content"]))
//{
//    $error .= '<p class="text-danger">Comment is required</p>';
//}
//else
//{
//    $comment_content = $_POST["comment_content"];
//}
//
//if($error == '')
//{
//
//
//    $x = $_POST['comment_id'];
//    $query = "
// INSERT INTO doc_comments
// (parentCommentId, content, commenterId)
// VALUES ('$x', $comment_content, $comment_name)
// ";
//    $statement = $crud->execute($query);
//    $error = '<label class="text-success">Comment Added</label>';
//}
//
//$data = array(
//    'error'  => $error
//);
//
//echo json_encode($data);
//
//


//add_comment.php

$connect = new PDO('mysql:host=localhost;dbname=facultyassocnew', 'root', '1234');

$error = '';
$comment_name = '';
$comment_content = '';
$postId = '';

if(empty($_POST["comment_name"]))
{
    $error .= '<p class="text-danger">Name is required</p>';
}
else
{
    $comment_name = $_POST["comment_name"];
}

if(empty($_POST["comment_content"]))
{
    $error .= '<p class="text-danger">Comment is required</p>';
}
else
{
    $comment_content = $_POST["comment_content"];
}

if(empty($_POST["post_id"]))
{
    $error .= '<p class="text-danger">Cannot identify post</p>';
}
else
{
    $postId = $_POST["post_id"];
}

if($error == '')
{
    $query = "INSERT INTO section_comments 
 (parentCommentId, postId, content, commenterId) 
 VALUES (:parent_comment_id, :postId, :comment, :comment_sender_name)
 ";
    $statement = $connect->prepare($query);
    $statement->execute(
        array(
            ':parent_comment_id' => $_POST["comment_id"],
            ':comment'    => $comment_content,
            ':comment_sender_name' => $comment_name,
            ':postId' => $postId
        )
    );
    $error = '<label class="text-success">Comment Added</label>';
}

$data = array(
    'error'  => $error
);

echo json_encode($data);

?>
