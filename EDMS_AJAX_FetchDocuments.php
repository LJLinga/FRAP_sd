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

$userId = $_SESSION['idnum'];
if(isset($_POST['role'])){

    $role = $_POST['role'];

    $query = "SELECT d.documentId, CONCAT(e.lastName,', ',e.firstName) AS originalAuthor, d.stepId, t.type, stat.statusName,
                v.versionId as vid, v.versionNo, v.title, v.timeCreated, pr.processName, s.stepNo, s.stepName,
                (SELECT CONCAT(e.lastName,', ',e.firstName) FROM doc_versions v JOIN employee e ON v.authorId = e.EMP_ID 
                WHERE v.versionId = vid) AS currentAuthor
                FROM documents d JOIN doc_versions v ON d.documentId = v.documentId
                JOIN doc_status stat ON stat.id = d.statusId
                JOIN doc_type t ON t.id = d.typeId
                JOIN employee e ON e.EMP_ID = d.firstAuthorId 
                JOIN steps s ON s.id = d.stepId
                JOIN step_roles sr ON sr.stepId = s.id
                JOIN process pr ON pr.id = s.processId
                WHERE s.processId = pr.id AND v.versionId = 
                (SELECT MAX(v1.versionId) FROM doc_versions v1 WHERE v1.documentId = d.documentId)
                AND t.isActive = 2 AND sr.roleId = '$role' 
                AND sr.read = 2 ORDER BY v.timeCreated DESC;";


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
            'actions'=> '<a class="btn btn-default" name="documentId" href="http://localhost/FRAP_sd/EDMS_ViewDocument.php?docId='.$row['documentId'].'">Edit</a>'
        );

    }
    echo json_encode($data);
    exit;
}
?>