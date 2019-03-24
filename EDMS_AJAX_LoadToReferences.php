<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/24/2019
 * Time: 9:07 PM
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

    $rows = $crud->getData("SELECT d.documentId, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName, 
                d.filePath, d.title, d.versionNo, d.timeCreated, d.lastUpdated,
                stat.statusName, s.stepNo, s.stepName, t.type,
                pr.processName, v.versionId AS vid,
                (SELECT CONCAT(e.FIRSTNAME,', ',e.LASTNAME) FROM employee e2 WHERE e2.EMP_ID = d.firstAuthorId) AS firstAuthorName 
                FROM facultyassocnew.documents d 
                JOIN doc_versions v ON v.documentId = d.documentId
                JOIN employee e ON e.EMP_ID = d.authorId
                JOIN doc_status stat ON stat.id = d.statusId 
                JOIN doc_type t ON t.id = d.typeId
                JOIN steps s ON s.id = d.stepId
                JOIN process pr ON pr.id = s.processId
                WHERE v.versionId = (SELECT MAX(v2.versionId) FROM doc_versions v2 WHERE v2.documentId = d.documentId)
                AND (t.id = 4 OR t.id = 5)
                AND stat.id = 2
                $append");

    $data = [];
    if(!empty($rows)) {
        foreach ((array)$rows as $key => $row) {
            $vid = $row['vid'];
            $title = $row['title'];
            $type = $row['type'];
            $versionNo = $row['versionNo'];
            $originalAuthor = $row['firstAuthorName'];
            $currentAuthor = $row['authorName'];
            $processName = $row['processName'];
            $stepNo = $row['stepNo'];
            $stepName = $row['stepName'];
            $updatedOn = date("F j, Y g:i:s A ", strtotime($row['timeCreated']));
            $filePath = $row['filePath'];
            $fileName = $title.'_ver'.$versionNo.'_'.basename($filePath);
            $htmlA = '<span class="badge badge-success">'.$row['type'].'</span><br><b>'.$title.'</b>
                        <span class="badge">'.$versionNo.'</span><br>
                      Author:'.$originalAuthor.'<br>
                      Modified by: '.$currentAuthor .'<br>
                      on : <i>'.$updatedOn.'</i><br>';
            $htmlB = '<span><b>' . $row['processName'] . '</b></span><br><span class="badge">Step ' . $stepNo . ' '. $stepName.'</span><br><span class="badge">'.$row['statusName'].'</span>';
            $htmlC = '<button type="button" class="btn btn-default" onclick="addRef(this,&quot;' . $vid . '&quot;,&quot;' . $originalAuthor . '&quot;,&quot;' . $currentAuthor . '&quot;,&quot;' . $versionNo . '&quot;,&quot;' . $updatedOn . '&quot;,&quot;' . $title . '&quot;,&quot;' . $processName . '&quot;,&quot;' . $filePath. '&quot;,&quot;' . $fileName . '&quot;);" value="' . $vid . '">Add</button>';
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