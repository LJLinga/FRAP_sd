<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/16/2019
 * Time: 3:55 PM
 */


require ('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

    $append = '';

    if(isset($_POST['loadedReferences'])) {
        $arrayRefs = $_POST['loadedReferences'];
        foreach ((array)$arrayRefs as $key => $ref) {
            $append .= " AND v.versionId !=" . $ref;
        }
    }

    $rows = $crud->getData("SELECT d.documentId, CONCAT(e.lastName,', ',e.firstName) AS originalAuthor,
                                         v.versionId as vid, v.versionNo, v.title, v.timeCreated, pr.id AS processId, pr.processName, s.stepNo, s.stepName,
                                         (SELECT CONCAT(e.lastName,', ',e.firstName) FROM doc_versions v JOIN employee e ON v.authorId = e.EMP_ID 
                                         WHERE v.versionId = vid) AS currentAuthor
                                         FROM documents d JOIN doc_versions v ON d.documentId = v.documentId
                                         JOIN employee e ON e.EMP_ID = d.firstAuthorId 
                                         JOIN steps s ON s.id = d.stepId
                                         JOIN process pr ON pr.id = d.processId 
                                         WHERE s.processId = pr.id AND v.versionId = 
                                         (SELECT MAX(v1.versionId) FROM doc_versions v1 WHERE v1.documentId = d.documentId)
                                         $append; ");

    $data = [];
    if(!empty($rows)) {
        foreach ((array)$rows as $key => $row) {
            $title = $row['title'];
            $versionNo = $row['versionNo'];
            $originalAuthor = $row['originalAuthor'];
            $currentAuthor = $row['currentAuthor'];
            $processName = $row['processName'];
            $updatedOn = date("F j, Y g:i:s A ", strtotime($row['timeCreated']));
            $htmlA = '<b>'.$title.'</b><span class="badge">'.$versionNo.'</span><br>
                      Author:'.$originalAuthor.'<br>
                      Modified by: '.$currentAuthor .'<br>
                      on : <i>'.$updatedOn.'</i><br>';
            $htmlB = $processName;
            $htmlC = '<button type="button" class="btn btn-default" onclick="addRef(this, &quot;' . $row['vid'] . '&quot;,&quot;' . $originalAuthor . '&quot;,&quot;' . $currentAuthor . '&quot;,&quot;' . $versionNo . '&quot;,&quot;' . $updatedOn . '&quot;,&quot;' . $title . '&quot;,&quot;' . $processName . '&quot;);" value="' . $row['vid'] . '">Add</button>';

            $data[] = array(
                'Document' => $htmlA,
                'Status' => $htmlB,
                'Action' => $htmlC
            );
        }
        //exit;
    }

    echo json_encode($data);


?>