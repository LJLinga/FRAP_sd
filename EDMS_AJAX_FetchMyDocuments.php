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


    $query = "SELECT d.documentId, CONCAT(e.lastName,', ',e.firstName) AS originalAuthor, d.stepId, t.type, stat.statusName,
                v.versionId as vid, v.versionNo, v.title, v.timeCreated, pr.processName, s.stepNo, s.stepName,
                (SELECT CONCAT(e.lastName,', ',e.firstName) FROM doc_versions v JOIN employee e ON v.authorId = e.EMP_ID 
                WHERE v.versionId = vid) AS currentAuthor
                FROM documents d JOIN doc_versions v ON d.documentId = v.documentId
                JOIN doc_status stat ON stat.id = d.statusId
                JOIN doc_type t ON d.typeId = t.id
                JOIN employee e ON e.EMP_ID = d.firstAuthorId 
                JOIN steps s ON s.id = d.stepId
                JOIN process pr ON pr.id = s.processId
                WHERE v.versionId = (SELECT MAX(v1.versionId) FROM doc_versions v1 WHERE v1.documentId = d.documentId)
                AND d.firstAuthorId = '$userId' OR v.authorId = '$userId'
                GROUP BY vid ORDER BY v.timeCreated DESC;";


    $rows = $crud->getData($query);
    $data = [];
    foreach ((array) $rows as $key => $row) {
        $data[] =  array(
            'title_version' => '<span class="badge badge-success">'.$row['type'].'</span> <b>'.$row['title'].'</b> 
                                <span class="badge">'.$row['versionNo'].'</span><br>
                                Author: '.$row['originalAuthor'].'<br>
                                Modified by: '.$row['currentAuthor'].'<br>
                                on : <i>'.date("F j, Y g:i:s A ", strtotime($row['timeCreated'])).'</i><br>',
            'currentProcess' => '<span><b>' . $row['processName'] . '</b></span><br><span class="badge">Step ' . $row['stepNo'] . ' '. $row['stepName'].'</span><br><span class="badge">'.$row['statusName'].'</span>',
            'actions'=> '<a class="btn btn-primary" name="documentId" href="http://localhost/FRAP_sd/EDMS_ViewDocument.php?docId='.$row['documentId'].'">View</a>'
        );

    }
    echo json_encode($data);
    exit;
}
?>