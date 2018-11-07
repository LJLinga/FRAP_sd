<?php

include_once ('../GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$id = $_POST['id'];
$title = $_POST['title'];
$body = $_POST['body'];

echo $id;
echo $title;
echo $body;

$crud->execute("UPDATE posts SET title = '$title', body = '$body' WHERE posts.id = '$id'; ");

?>