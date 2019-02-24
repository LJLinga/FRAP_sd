<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 2/24/2019
 * Time: 4:55 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
session_start();


$query = "SELECT p.id,p.title, p.authorId,CONCAT(a.firstName,' ', a.lastName) AS name,
          s.description AS status,p.lastUpdated 
          FROM posts p JOIN employee a ON p.authorId = a.EMP_ID
          JOIN post_status s ON s.id = p.statusId WHERE s.id = 2 || s.id=3
          OR s.id = 1 AND p.authorId = '$userId' OR s.id = 4 AND p.archivedById = '$userId'
          ORDER BY p.lastUpdated DESC;";

$query2 = "SELECT p.id, p.title, p.authorId, CONCAT(a.firstName,' ', a.lastName) AS name,
           s.description AS status, p.lastUpdated FROM posts p 
           JOIN employee a ON p.authorId = a.EMP_ID
           JOIN post_status s ON s.id = p.statusId WHERE p.authorId = '$userId'
           ORDER BY p.lastUpdated DESC;"


?>