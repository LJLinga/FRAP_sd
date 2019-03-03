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
v.versionId as vid, v.versionNo, v.title, v.timeCreated, pr.processName, s.stepNo, s.stepName,
(SELECT CONCAT(e.lastName,', ',e.firstName) FROM doc_versions v JOIN employee e ON v.authorId = e.EMP_ID WHERE v.versionId = vid) AS currentAuthor
FROM documents d JOIN doc_versions v ON d.documentId = v.documentId 
JOIN employee e ON e.EMP_ID = d.firstAuthorId 
JOIN steps s ON s.id = d.currentStepId
JOIN process pr ON pr.id = d.processId WHERE s.processId = pr.id;";


    $rows = $crud->getData($query);
    $data = [];
    foreach ((array) $rows as $key => $row) {
        $data[] =  array(
            'originalAuthor' => $row['originalAuthor'],
            'title_version' => $row['title'].' <span class="label label-default">'.$row['versionNo'].'</span>',
            'currentAuthor' => $row['currentAuthor'],
            'currentProcess' => '<span>'.$row['processName'].'</span><br><span class="label label-default">Step '.$row['stepNo'].'</span> '.$row['stepName'],
            'lastUpdated' => $row['timeCreated'],
            'actions'=> '<a class="btn btn-default" name="documentId" href="http://localhost/FRAP_sd/EDMS_ViewDocument.php?docId='.$row['documentId'].'">Edit</a>'
        );
    }
    echo json_encode($data);
    exit;
}
?>