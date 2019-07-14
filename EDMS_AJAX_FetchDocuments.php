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
                                                    JOIN steps s ON g.id = s.groupId
                                                    JOIN process pr ON s.processId = pr.id
                                                    WHERE ug.userId = '$userId') 
                AND d.firstAuthorId !='$userId' ORDER BY d.lastUpdated DESC;";

        $rows = $crud->getData($query);
        $data = [];
        foreach ((array) $rows as $key => $row) {
            $buttons = '<a class="btn btn-info btn-sm" data-toggle="tooltip" title="View document" name="documentId" href="EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
            $buttons .= ' <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"><i class="fa fa-download"></i></a>';

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
            $buttons = '<a class="btn btn-info btn-sm" data-toggle="tooltip" title="View document" name="documentId" href="EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
            $buttons .= ' <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"><i class="fa fa-download"></i></a>';

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

    }else if ($_POST['requestType'] == 'MANUAL_REFERENCES'){
        $sectionId = $_POST['sectionId'];

        $rows = $crud->getManualReferencableDocuments($sectionId);
        $data = [];
        foreach ((array) $rows as $key => $row) {
            $buttons = '<button type="button" class="btn btn-success btn-sm fa fa-plus add_doc_ref" data-toggle="tooltip" value="'.$row['versionId'].'" title="Add"></button>';
            //$buttons = '<a class="btn btn-info" data-toggle="tooltip" title="View document" name="documentId" href="EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
            $buttons .= ' <a class="btn btn-secondary btn-sm fa fa-download" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"></a>';

            $data[] =  array(
                'title' => $row['title'],
                'vers' => $row['versionNo'],
                'type' => $row['type'],
                'submitted_by' => $crud->getUserName($row['firstAuthorId']),
                'submitted_on' => date("m/d/Y g:i:s A ", strtotime($row['timeCreated'])),
                'approved_by' => $crud->getUserName($row['statusedById']),
                'approved_on' => date("m/d/Y g:i:s A ", strtotime($row['statusedOn'])),
                'actions' => $buttons
            );

        }
        echo json_encode($data);
        exit;

    }else if ($_POST['requestType'] == 'INSERT_MANUAL_REFERENCE'){
        $sectionId = $_POST['sectionId'];
        $versionId = $_POST['versionId'];

        try{
            $crud->execute("INSERT INTO section_ref_versions (sectionId, versionId, referencedById) VALUES ('$sectionId','$versionId','$userId')");
            echo 'success';
        }catch(Exception $e){
            echo $e;
        }
        exit;
    }else if ($_POST['requestType'] == 'ADDED_MANUAL_REFERENCES'){
        $sectionId = $_POST['sectionId'];

        $rows = $crud->getData("SELECT ref.*, dv.*, dt.type  FROM section_ref_versions ref
                                        JOIN doc_versions dv on ref.versionId = dv.versionId
                                        JOIN doc_type dt ON dt.id = dv.typeId
                                        WHERE ref.sectionId = '$sectionId'");
        $data = [];
        if(!empty($rows)){
            foreach((array) $rows AS $key => $row){

                $buttons = '<button type="button" class="btn btn-success btn-sm fa fa-plus remove_doc_ref" data-toggle="tooltip" value="'.$row['versionId'].'" title="Add"></button>';
                //$buttons = '<a class="btn btn-info" data-toggle="tooltip" title="View document" name="documentId" href="EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
                $buttons .= ' <a class="btn btn-secondary btn-sm fa fa-download" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"></a>';
                $data[] =  array(
                    'title' => $row['title'],
                    'vers' => $row['versionNo'],
                    'type' => $row['type'],
                    'submitted_by' => $crud->getUserName($row['firstAuthorId']),
                    'submitted_on' => date("m/d/Y g:i:s A ", strtotime($row['timeCreated'])),
                    'approved_by' => $crud->getUserName($row['statusedById']),
                    'approved_on' => date("m/d/Y g:i:s A ", strtotime($row['statusedOn'])),
                    'referenced_by' => $crud->getUserName($row['referencedById']),
                    'referenced_on' => date("m/d/Y g:i:s A ", strtotime($row['referencedOn'])),
                    'actions' => $buttons
                );
            }
        }
        echo json_encode($data);
        exit;
    }
}


?>