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


if(isset($_POST['mode'])){

    $mode = $_POST['mode'];

    $query = "SELECT d.documentId, CONCAT(e.lastName,', ',e.firstName) AS originalAuthor,
                v.versionId as vid, v.versionNo, v.title, v.timeCreated, pr.id AS processId, pr.processName, s.stepNo, s.stepName,
                (SELECT CONCAT(e.lastName,', ',e.firstName) FROM doc_versions v JOIN employee e ON v.authorId = e.EMP_ID 
                WHERE v.versionId = vid) AS currentAuthor
                FROM documents d JOIN doc_versions v ON d.documentId = v.documentId
                JOIN employee e ON e.EMP_ID = d.firstAuthorId 
                JOIN steps s ON s.id = d.stepId
                JOIN process pr ON pr.id = d.processId 
                WHERE s.processId = pr.id AND v.versionId = 
                (SELECT MAX(v1.versionId) FROM doc_versions v1 WHERE v1.documentId = d.documentId);";


    $rows = $crud->getData($query);
    $data = [];
    foreach ((array) $rows as $key => $row) {
        $data[] =  array(
            'title_version' => '<b>'.$row['title'].'</b> 
                                <span class="badge">'.$row['versionNo'].'</span><br>
                                Author: '.$row['originalAuthor'].'<br>
                                Modified by: '.$row['originalAuthor'].'<br>
                                on : <i>'.date("F j, Y g:i:s A ", strtotime($row['timeCreated'])).'</i><br>',
            'currentProcess' => '<span>' . $row['processName'] . '</span><br><span class="badge">Step ' . $row['stepNo'] . '</span> ' . $row['stepName'],
            'actions'=> '<a class="btn btn-default" name="documentId" href="http://localhost/FRAP_sd/EDMS_ViewDocument.php?docId='.$row['documentId'].'&versId='.$row['vid'].'">Edit</a>'
        );

    }
    echo json_encode($data);
    exit;
}
?>