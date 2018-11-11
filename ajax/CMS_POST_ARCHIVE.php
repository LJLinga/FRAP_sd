<?php

    include_once ('../GLOBAL_CLASS_CRUD.php');
    $crud = new GLOBAL_CLASS_CRUD();

    $id = $_POST['id'];
    $crud->execute("UPDATE posts SET statusId= '4' WHERE posts.id = '$id'; ");

?>