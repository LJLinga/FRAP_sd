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

if(isset($_POST['requestType'])){

    if ($_POST['requestType'] == 'WORKSPACE'){

        $query = "SELECT d.documentId, pr.processName, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName,
                d.filePath, d.title, d.versionNo, d.timeCreated, d.lastUpdated,
                stat.statusName, s.stepNo, s.stepName, t.type, pr.processName,
                (SELECT CONCAT(e.FIRSTNAME,', ',e.LASTNAME) FROM employee e2 WHERE e2.EMP_ID = d.firstAuthorId) AS firstAuthorName 
                FROM documents d 
                LEFT JOIN employee e ON e.EMP_ID = d.authorId
                JOIN doc_status stat ON stat.id = d.statusId 
                JOIN doc_type t ON t.id = d.typeId
                JOIN steps s ON s.id = d.stepId
                JOIN process pr ON pr.id = s.processId
                WHERE t.isActive = 2 AND pr.id IN (SELECT pr.id FROM user_groups ug
                                                    JOIN groups g ON ug.groupId = g.id
                                                    JOIN step_groups sg ON g.id = sg.groupId
                                                    JOIN steps s ON sg.stepId = s.id
                                                    JOIN process pr ON s.processId = pr.id
                                                    WHERE ug.userId = '$userId') 
                AND d.firstAuthorId !='$userId' ORDER BY d.lastUpdated DESC;";

        $rows = $crud->getData($query);
        $data = [];
        foreach ((array) $rows as $key => $row) {
            $buttons = '<a class="btn btn-info" data-toggle="tooltip" title="View document" name="documentId" href="EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
            $buttons .= ' <a class="btn btn-success" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"><i class="fa fa-download"></i></a>';

            $data[] =  array(
                'title' => $row['title'],
                'type' => $row['type'],
                'vers' => $row['versionNo'],
                'submitted_by' => $row['firstAuthorName'],
                'submitted_on' => date("m/d/Y g:i:s A ", strtotime($row['timeCreated'])),
                'status' => $row['statusName'],
                'timestamp' => date("m/d/Y g:i:s A ", strtotime($row['lastUpdated'])),
                'actions' => $buttons
            );

        }
        echo json_encode($data);
        exit;

    }else if ($_POST['requestType'] == 'MYDOCUMENTS'){

        $rows = $crud->getPersonalDocuments($userId);

        $data = [];
        foreach ((array) $rows as $key => $row) {
            $buttons = '<a class="btn btn-info" data-toggle="tooltip" title="View document" name="documentId" href="EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
            $buttons .= ' <a class="btn btn-success" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"><i class="fa fa-download"></i></a>';

            $data[] =  array(
                'title' => $row['title'],
                'type' => $row['type'],
                'vers' => $row['versionNo'],
                'timeCreated' => date("m/d/Y g:i:s A ", strtotime($row['timeCreated'])),
                'status' => $row['statusName'],
                'modified_by' => $row['authorName'],
                'lastUpdated' => date("m/d/Y g:i:s A ", strtotime($row['lastUpdated'])),
                'actions' => $buttons
            );

        }
        echo json_encode($data);
        exit;

    }else if ($_POST['requestType'] == 'GROUPSPACE'){
        $groupId = $_POST['groupId'];

        $query = "SELECT d.documentId, pr.processName, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName,
                d.filePath, d.title, d.versionNo, d.timeCreated, d.lastUpdated,
                stat.statusName, s.stepNo, s.stepName, t.type, pr.processName,
                (SELECT CONCAT(e.FIRSTNAME,', ',e.LASTNAME) FROM employee e2 WHERE e2.EMP_ID = d.firstAuthorId) AS firstAuthorName 
                FROM documents d 
                LEFT JOIN employee e ON e.EMP_ID = d.authorId
                JOIN doc_status stat ON stat.id = d.statusId 
                JOIN doc_type t ON t.id = d.typeId
                JOIN steps s ON s.id = d.stepId
                JOIN process pr ON pr.id = s.processId
                WHERE t.isActive = 2 AND pr.id IN (SELECT pr.id FROM user_groups ug
                                                    JOIN groups g ON ug.groupId = g.id
                                                    JOIN process_groups pg ON g.id = pg.groupId
                                                    JOIN process pr ON pg.processId = pr.id
                                                    WHERE ug.userId = '$userId') 
                ORDER BY d.lastUpdated DESC;";

        $rows = $crud->getData($query);
        $data = [];
        foreach ((array) $rows as $key => $row) {
            $buttons = '<a class="btn btn-info" data-toggle="tooltip" title="View document" name="documentId" href="EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
            $buttons .= ' <a class="btn btn-success" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"><i class="fa fa-download"></i></a>';

            $data[] =  array(
                'title' => $row['title'],
                'type' => $row['type'],
                'vers' => $row['versionNo'],
                'submitted_by' => $row['firstAuthorName'],
                'submitted_on' => date("m/d/Y g:i:s A ", strtotime($row['timeCreated'])),
                'status' => $row['statusName'],
                'timestamp' => date("m/d/Y g:i:s A ", strtotime($row['lastUpdated'])),
                'actions' => $buttons
            );

        }
        echo json_encode($data);
        exit;

    }
}


?>