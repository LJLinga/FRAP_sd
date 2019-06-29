<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 2/28/2019
 * Time: 10:26 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
session_start();


if(isset($_POST['userId'])){



    $userId = $_POST['userId'];


    $query = "SELECT d.documentId, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName, 
                d.filePath, d.title, d.versionNo, d.timeCreated, d.lastUpdated,
                stat.statusName, s.stepNo, s.stepName, t.type,
                pr.processName, 
                (SELECT CONCAT(e.FIRSTNAME,', ',e.LASTNAME) FROM employee e2 WHERE e2.EMP_ID = d.firstAuthorId) AS firstAuthorName 
                FROM facultyassocnew.documents d 
                JOIN employee e ON e.EMP_ID = d.authorId
                JOIN doc_status stat ON stat.id = d.statusId 
                JOIN doc_type t ON t.id = d.typeId
                JOIN steps s ON s.id = d.stepId
                JOIN process pr ON pr.id = s.processId
                WHERE t.isActive = 2 AND (d.firstAuthorId = '$userId' OR d.authorId = '$userId')
                ORDER BY d.lastUpdated DESC;";

    $rows = $crud->getData($query);
    $data = [];
    foreach ((array) $rows as $key => $row) {
        $domain = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
        $buttons = '<a class="btn btn-info" data-toggle="tooltip" title="View document" name="documentId" href="http://'.$domain.'/EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
        $buttons .= ' <a class="btn btn-success" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"><i class="fa fa-download"></i></a>';

        $data[] =  array(
            'title' => $row['title'],
            'type' => $row['type'],
            'vers' => $row['versionNo'],
            'status' => $row['statusName'],
            'timestamp' => date("m/d/Y g:i:s A ", strtotime($row['lastUpdated'])),
            'actions' => $buttons
        );

    }
    echo json_encode($data);
    exit;
}
?>