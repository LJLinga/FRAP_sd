<?php

    include_once ('../GLOBAL_CLASS_CRUD.php');
    $crud = new GLOBAL_CLASS_CRUD();

    $title = $_POST['title'];
    $body = $_POST['body'];

    //echo $title;
    //echo $body;

    $id = $crud->executeGetKey("INSERT INTO posts (title, body, authorId, statusId) values ('$title', '$body', 1,1)");
    echo $id;
?>