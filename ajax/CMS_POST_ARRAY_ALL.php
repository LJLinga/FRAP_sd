<?php

include_once ('../GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$json= $crud->getData("SELECT p.id, p.title, CONCAT(a.firstName,' ', a.lastName) AS name, s.description AS status, p.lastUpdated FROM mydb.posts p JOIN mydb.authors a ON p.authorId = a.id JOIN mydb.post_status s ON s.id = p.statusId;");
echo json_encode($json);

?>