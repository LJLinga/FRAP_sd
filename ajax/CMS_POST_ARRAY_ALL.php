<?php

include_once ('../GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$json= $crud->getData("SELECT p.id, 
                                                                  p.title, 
                                                                  CONCAT(a.firstName,' ', a.lastName) AS name, 
                                                                  s.description AS status, 
                                                                  p.lastUpdated 
                                                                  FROM posts p JOIN users a ON p.authorId = a.id 
                                                                  JOIN post_status s ON s.id = p.statusId 
                                                                  WHERE s.id = 1 || s.id = 2 || s.id=3 || s.id=4
                                                                  ;
                                                                  ");
echo json_encode($json);

?>