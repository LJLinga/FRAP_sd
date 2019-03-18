<?php

$connect = new PDO('mysql:host=localhost;dbname=facultyassocnew', 'root', '1234');

$error = '';
$comment_name = '';
$comment_content = '';
$documentId = '';
$versionId = '';

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

if(empty($_POST['documentId']))
{
    $error .= '<p class="text-danger">Cannot identify document</p>';
}
else
{
    $documentId = $_POST["documentId"];
}

if(empty($_POST['versionId']))
{
    $error .= '<p class="text-danger">Cannot identify document version</p>';
}
else
{
    $versionId = $_POST["versionId"];
}

if($error == '')
{
    $query = "
 INSERT INTO doc_comments 
 (parentCommentId, documentId, versionId, content, commenterId) 
 VALUES (:parent_comment_id, :documentId, :versionId, :comment, :comment_sender_name)
 ";
    $statement = $connect->prepare($query);
    $statement->execute(
        array(
            ':parent_comment_id' => $_POST["comment_id"],
            ':comment'    => $comment_content,
            ':comment_sender_name' => $comment_name,
            ':documentId' => $documentId,
            ':versionId' => $versionId
        )
    );
    $error = '<label class="text-success">Comment Added</label>';
}

$data = array(
    'error'  => $error,
    'documentId' => $documentId,
    'versionId' => $versionId
);

echo json_encode($data);

?>
