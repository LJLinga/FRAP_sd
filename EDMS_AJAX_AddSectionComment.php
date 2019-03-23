<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/23/2019
 * Time: 8:51 PM
 */

$connect = new PDO('mysql:host=localhost;dbname=facultyassocnew', 'root', '1234');

$error = '';
$comment_name = '';
$comment_content = '';
$sectionId = '';

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

if(empty($_POST["section_id"]))
{
    $error .= '<p class="text-danger">Cannot identify post</p>';
}
else
{
    $sectionId = $_POST["section_id"];
}

if($error == '')
{
    $query = "INSERT INTO section_comments 
 (parentCommentId, sectionId, content, commenterId) 
 VALUES (:parent_comment_id, :sectionId, :comment, :comment_sender_name)
 ";
    $statement = $connect->prepare($query);
    $statement->execute(
        array(
            ':parent_comment_id' => $_POST["comment_id"],
            ':comment'    => $comment_content,
            ':comment_sender_name' => $comment_name,
            ':sectionId' => $sectionId
        )
    );
    $error = '<label class="text-success">Comment Added</label>';
}

$data = array(
    'error'  => $error
);

echo json_encode($data);

?>