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
        $data[] =  array(
            'title_version' => '<span class="badge badge-success">'.$row['type'].'</span> <b>'.$row['title'].'</b> 
                                <span class="badge">'.$row['versionNo'].'</span><br>
                                Author: '.$row['firstAuthorName'].'<br>
                                Modified by: '.$row['authorName'].'<br>
                                on : <i>'.date("F j, Y g:i:s A ", strtotime($row['lastUpdated'])).'</i><br>',
            'currentProcess' => '<span><b>' . $row['processName'] . '</b></span><br><span class="badge">Step ' . $row['stepNo'] . ' '. $row['stepName'].'</span><br><span class="badge">'.$row['statusName'].'</span>',
            'actions'=> '<a class="btn btn-default" name="documentId" href="http://localhost/FRAP_sd/EDMS_ViewDocument.php?docId='.$row['documentId'].'">View</a>'
        );

    }
    echo json_encode($data);
    exit;
}
?>