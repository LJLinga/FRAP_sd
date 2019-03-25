<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/25/2019
 * Time: 10:49 AM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$timeCreated = $_GET['timeCreated'];
$query = "SELECT v.timeCreated, v.content, v.title, v.sectionNo, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS versionAuthor 
                                  FROM section_versions v 
                                  JOIN employee e ON v.authorId = e.EMP_ID 
                                  WHERE v.timeCreated = '$timeCreated' LIMIT 1;";
$rows = $crud->getData($query);
foreach((array) $rows AS $key => $row){
    $title = $row['title'];
    $sectionNo = $row['sectionNo'];
    $content = $row['content'];
    $versionAuthor = $row['versionAuthor'];
}
$output = '';
$output .= '<h4>'.$title.'</h4>';
$output .= '<h5>'.$title.'</h5>';
?>
