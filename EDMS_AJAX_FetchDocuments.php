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

    $query = "SELECT d.documentId, CONCAT(e.lastName,', ',e.firstName) AS originalAuthor, d.processId AS pid,
v.versionId as vid, v.versionNo, v.title, v.timeCreated,
(SELECT CONCAT(e.lastName,', ',e.firstName) FROM doc_versions v JOIN employee e ON v.authorId = e.EMP_ID WHERE v.versionId = vid) AS currentAuthor,
(SELECT s.stepNo FROM documents d JOIN steps s ON d.currentStepId = s.id WHERE s.processId = pid ) AS currentStepNo,
(SELECT s.step FROM documents d JOIN steps s ON d.currentStepId = s.id WHERE s.processId = pid ) AS currentStepName
FROM documents d JOIN doc_versions v ON d.documentId = v.documentId JOIN employee e ON e.EMP_ID = d.firstAuthorId;";


    $rows = $crud->getData($query);
    $data = [];
    foreach ((array) $rows as $key => $row) {
        $data[] =  array(
            'originalAuthor' => $row['originalAuthor'],
            'title_version' => $row['title'].' <span class="label label-default">'.$row['versionNo'].'</span>',
            'currentAuthor' => $row['currentAuthor'],
            'currentStep' => '<span class="label label-default">Step '.$row['currentStepNo'].'</span> '.$row['currentStepName'],
            'lastUpdated' => $row['timeCreated'],
            'actions'=> '<a class="btn btn-default" name="documentId" href="http://localhost/FRAP_sd/EDMS_ViewDocument.php?docId='.$row['documentId'].'">Edit</a>'
        );
    }
    echo json_encode($data);
    exit;
}
?>