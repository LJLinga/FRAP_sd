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

    if ($_POST['requestType'] == 'WORKSPACE_WRITEROUTE'){

        $query = "SELECT d.documentId, d.statusId, pr.processName, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName,
                d.filePath, d.title, d.versionNo, d.timeCreated, d.lastUpdated,
                s.stepNo, s.stepName, t.type, pr.processName,
                (SELECT CONCAT(e.FIRSTNAME,', ',e.LASTNAME) FROM employee e2 WHERE e2.EMP_ID = d.firstAuthorId) AS firstAuthorName 
                FROM documents d 
                LEFT JOIN employee e ON e.EMP_ID = d.authorId
                JOIN doc_type t ON t.id = d.typeId
                JOIN steps s ON s.id = d.stepId
                JOIN process pr ON pr.id = s.processId
                WHERE t.isActive = 2 
                AND d.lifecycleStateId = 1
                AND d.statusId = 2
                AND d.stepId IN (SELECT s.id FROM user_groups ug
                                                    JOIN groups g ON ug.groupId = g.id
                                                    JOIN steps s ON g.id = s.groupId
                                                    WHERE (ug.userId = '$userId' AND (s.groute = 2 OR s.gwrite = 2))
                                                    OR (s.route = 2 OR s.`write` = 2 AND d.firstAuthorId = '$userId'))
                ORDER BY d.lastUpdated DESC;";

        //First subquery condition => Displays data given that you are in the steps assigned group with w/r permissions.
        //Second subquery condition => Displays data given that you are its creator and has creator w/r permissions.

        $rows = $crud->getData($query);
        $data = [];
        foreach ((array) $rows as $key => $row) {
            $buttons = '<a class="btn btn-info btn-sm" data-toggle="tooltip" title="View document" name="documentId" target="_blank" href="EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
            $buttons .= ' <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"><i class="fa fa-download"></i></a>';

            $data[] =  array(
                'title' => $row['title'],
                'type' => $row['type'],
                'vers' => $row['versionNo'],
                'submitted_by' => $row['firstAuthorName'],
                'submitted_on' => date("F j, Y g:i:s A ", strtotime($row['timeCreated'])),
                'status' => $crud->coloriseStatus($row['statusId']),
                'timestamp' => date("F j, Y g:i:s A ", strtotime($row['lastUpdated'])),
                'actions' => $buttons
            );

        }
        echo json_encode($data);
        exit;

    }else if ($_POST['requestType'] == 'MYDOCUMENTS'){

        $rows = $crud->getPersonalDocuments($userId);

        $data = [];
        foreach ((array) $rows as $key => $row) {
            $buttons = '<a class="btn btn-info btn-sm" data-toggle="tooltip" title="View document" name="documentId" target="_blank" href="EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
            $buttons .= ' <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"><i class="fa fa-download"></i></a>';

            $data[] =  array(
                'title' => $row['title'],
                'type' => $row['type'],
                'vers' => $row['versionNo'],
                'timeCreated' => date("F j, Y g:i:s A ", strtotime($row['timeCreated'])),
                'status' => $crud->coloriseStatus($row['statusId']),
                'modified_by' => $row['authorName'],
                'lastUpdated' => date("F j, Y g:i:s A ", strtotime($row['lastUpdated'])),
                'actions' => $buttons
            );

        }
        echo json_encode($data);
        exit;

    }else if ($_POST['requestType'] == 'GROUPSPACE'){
        $groupId = $_POST['groupId'];

        $query = "SELECT d.documentId, d.statusId, pr.processName, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName,
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
            $buttons = '<a class="btn btn-info" data-toggle="tooltip" title="View document" name="documentId" target="_blank" href="EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
            $buttons .= ' <a class="btn btn-success" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"><i class="fa fa-download"></i></a>';

            $data[] =  array(
                'title' => $row['title'],
                'type' => $row['type'],
                'vers' => $row['versionNo'],
                'submitted_by' => $row['firstAuthorName'],
                'submitted_on' => date("F j, Y g:i:s A ", strtotime($row['timeCreated'])),
                'status' => $crud->coloriseStatus($row['statusId']),
                'timestamp' => date("F j, Y g:i:s A ", strtotime($row['lastUpdated'])),
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
            $buttons = '<button type="button" class="btn btn-success btn-sm fa fa-plus add_doc_ref" onclick="addRef(' . $row['versionId'] . ')" data-toggle="tooltip" title="Add"></button>';
            //$buttons = '<a class="btn btn-info" data-toggle="tooltip" title="View document" name="documentId" href="EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
            $buttons .= ' <a class="btn btn-secondary btn-sm fa fa-download" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"></a>';

            $data[] =  array(
                'title' => $row['title'],
                'vers' => $row['versionNo'],
                'type' => $row['type'],
                'submitted_by' => $crud->getUserName($row['firstAuthorId']),
                'submitted_on' => date("F j, Y g:i:s A ", strtotime($row['timeCreated'])),
                'approved_by' => $crud->getUserName($row['statusedById']),
                'approved_on' => date("F j, Y g:i:s A ", strtotime($row['statusedOn'])),
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
        $write = $_POST['write'];

        $rows = $crud->getData("SELECT ref.*, dv.*, dt.type  FROM section_ref_versions ref
                                        JOIN doc_versions dv on ref.versionId = dv.versionId
                                        JOIN doc_type dt ON dt.id = dv.typeId
                                        WHERE ref.sectionId = '$sectionId'");
        $data = [];
        if(!empty($rows)){
            foreach((array) $rows AS $key => $row){
                $buttons = '';
                if($write == '2'){
                    $buttons = '<button type="button" class="btn btn-sm btn-danger fa fa-trash" data-toggle="tooltip" onclick="removeRef('.$row['versionId'].')" title="Remove"></button>';
                }
               //$buttons = '<a class="btn btn-info" data-toggle="tooltip" title="View document" name="documentId" href="EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
                $buttons .= ' <a class="btn btn-secondary btn-sm fa fa-download" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"></a>';
                $data[] =  array(
                    'title' => $row['title'],
                    'vers' => $row['versionNo'],
                    'type' => $row['type'],
                    'submitted_by' => $crud->getUserName($row['firstAuthorId']),
                    'submitted_on' => date("F j, Y g:i:s A ", strtotime($row['timeCreated'])),
                    'referenced_by' => $crud->getUserName($row['referencedById']),
                    'referenced_on' => date("F j, Y g:i:s A ", strtotime($row['referencedOn'])),
                    'actions' => $buttons
                );
            }
        }
        echo json_encode($data);
        exit;
    }else if ($_POST['requestType'] == 'REMOVE_MANUAL_REFERENCE'){
        $sectionId = $_POST['sectionId'];
        $versionId = $_POST['versionId'];

        try{
            $crud->execute("DELETE FROM section_ref_versions WHERE sectionId = '$sectionId' AND versionId = '$versionId';");
            echo 'success';
        }catch(Exception $e){
            echo $e;
        }
        exit;
    }else if ($_POST['requestType'] == 'WORKSPACE_READCOMMENT'){

        $query = "SELECT d.documentId, d.statusId, pr.processName, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName,
                d.filePath, d.title, d.versionNo, d.timeCreated, d.lastUpdated,
                s.stepNo, s.stepName, t.type, pr.processName,
                (SELECT CONCAT(e.FIRSTNAME,', ',e.LASTNAME) FROM employee e2 WHERE e2.EMP_ID = d.firstAuthorId) AS firstAuthorName 
                FROM documents d 
                LEFT JOIN employee e ON e.EMP_ID = d.authorId
                JOIN doc_type t ON t.id = d.typeId
                JOIN steps s ON s.id = d.stepId
                JOIN process pr ON pr.id = s.processId
                WHERE t.isActive = 2
                AND d.lifecycleStateId = 1
                AND pr.id IN (SELECT p.id FROM user_groups ug
                                                    JOIN groups g ON ug.groupId = g.id
                                                    JOIN steps st ON g.id = st.groupId
                                                    JOIN process p on st.processId = p.id
                                                    WHERE ug.userId = '$userId')
                ORDER BY d.lastUpdated DESC;";

                //Second subquery checks if you are in step_groups

        $rows = $crud->getData($query);
        $data = [];
        foreach ((array) $rows as $key => $row) {
            $buttons = '<a class="btn btn-info btn-sm" data-toggle="tooltip" title="View document" name="documentId" target="_blank" href="EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
            $buttons .= ' <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"><i class="fa fa-download"></i></a>';

            $data[] =  array(
                'title' => $row['title'],
                'type' => $row['type'],
                'vers' => $row['versionNo'],
                'submitted_by' => $row['firstAuthorName'],
                'submitted_on' => date("F j, Y g:i:s A ", strtotime($row['timeCreated'])),
                'status' => $crud->coloriseStatus($row['statusId']),
                'timestamp' => date("F j, Y g:i:s A ", strtotime($row['lastUpdated'])),
                'actions' => $buttons
            );

        }
        echo json_encode($data);
        exit;

    }else if ($_POST['requestType'] == 'WORKSPACE_EDITING'){

        $query = "SELECT d.documentId, d.statusId, pr.processName, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName,
                d.filePath, d.title, d.versionNo, d.timeCreated, d.lastUpdated,
                s.stepNo, s.stepName, t.type, pr.processName,
                (SELECT CONCAT(e.FIRSTNAME,', ',e.LASTNAME) FROM employee e2 WHERE e2.EMP_ID = d.firstAuthorId) AS firstAuthorName 
                FROM documents d 
                LEFT JOIN employee e ON e.EMP_ID = d.authorId
                JOIN doc_type t ON t.id = d.typeId
                JOIN steps s ON s.id = d.stepId
                JOIN process pr ON pr.id = s.processId
                WHERE t.isActive = 2 
                AND d.lifecycleStateId = 1
                AND d.stepId IN (SELECT s.id FROM user_groups ug
                                                    JOIN groups g ON ug.groupId = g.id
                                                    JOIN steps s ON g.id = s.groupId
                                                    WHERE (ug.userId = '$userId' AND (s.gwrite = 2 OR s.gcycle = 2 OR s.groute = 2)))
                AND d.availabilityId = '2' AND d.availabilityById = '$userId'
                ORDER BY d.lastUpdated DESC;";

        $rows = $crud->getData($query);
        $data = [];
        foreach ((array) $rows as $key => $row) {
            $buttons = '<a class="btn btn-info btn-sm" data-toggle="tooltip" title="View document" name="documentId" target="_blank" href="EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
            $buttons .= ' <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"><i class="fa fa-download"></i></a>';

            $data[] =  array(
                'title' => $row['title'],
                'type' => $row['type'],
                'vers' => $row['versionNo'],
                'submitted_by' => $row['firstAuthorName'],
                'submitted_on' => date("F j, Y g:i:s A ", strtotime($row['timeCreated'])),
                'status' => $crud->coloriseStatus($row['statusId']),
                'timestamp' => date("F j, Y g:i:s A ", strtotime($row['lastUpdated'])),
                'actions' => $buttons
            );

        }
        echo json_encode($data);
        exit;

    }else if ($_POST['requestType'] == 'MYDOCUMENTS_EDITING'){

        $rows = $crud->getPersonalDocumentsEditing($userId);

        $data = [];
        foreach ((array) $rows as $key => $row) {
            $buttons = '<a class="btn btn-info btn-sm" data-toggle="tooltip" title="View document" name="documentId" target="_blank" href="EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
            $buttons .= ' <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"><i class="fa fa-download"></i></a>';

            $data[] =  array(
                'title' => $row['title'],
                'type' => $row['type'],
                'vers' => $row['versionNo'],
                'timeCreated' => date("F j, Y g:i:s A ", strtotime($row['timeCreated'])),
                'status' => $crud->coloriseStatus($row['statusId']),
                'modified_by' => $row['authorName'],
                'lastUpdated' => date("F j, Y g:i:s A ", strtotime($row['lastUpdated'])),
                'actions' => $buttons
            );

        }
        echo json_encode($data);
        exit;

    }else if ($_POST['requestType'] == 'WORKSPACE_ARCHIVED'){

        $query = "SELECT d.documentId, d.statusId, pr.processName, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName,
                d.filePath, d.title, d.versionNo, d.timeCreated, d.lastUpdated, d.lifecycleStatedOn,
                s.stepNo, s.stepName, t.type, pr.processName,
                (SELECT CONCAT(e2.FIRSTNAME,', ',e2.LASTNAME) FROM employee e2 WHERE e2.EMP_ID = d.firstAuthorId) AS firstAuthorName,
                (SELECT CONCAT(e3.FIRSTNAME,', ',e3.LASTNAME) FROM employee e3 WHERE e3.EMP_ID = d.lifecycleStatedById) AS lifecycleStatedByName
                FROM documents d 
                LEFT JOIN employee e ON e.EMP_ID = d.authorId
                JOIN doc_type t ON t.id = d.typeId
                JOIN steps s ON s.id = d.stepId
                JOIN process pr ON pr.id = s.processId
                WHERE t.isActive = 2
                AND d.lifecycleStateId = 2
                AND pr.id IN (SELECT p.id FROM user_groups ug
                                                    JOIN groups g ON ug.groupId = g.id
                                                    JOIN steps st ON g.id = st.groupId
                                                    JOIN process p on st.processId = p.id
                                                    WHERE ug.userId = '$userId')
                ORDER BY d.lastUpdated DESC;";

        $rows = $crud->getData($query);
        $data = [];
        foreach ((array) $rows as $key => $row) {
            $buttons = '<a class="btn btn-info btn-sm" data-toggle="tooltip" title="View document" name="documentId" target="_blank" href="EDMS_ViewDocument.php?docId='.$row['documentId'].'"><i class="fa fa-eye"></i></a>';
            $buttons .= ' <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Download document" href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"><i class="fa fa-download"></i></a>';

            $data[] =  array(
                'title' => $row['title'],
                'type' => $row['type'],
                'vers' => $row['versionNo'],
                'submitted_by' => $row['firstAuthorName'],
                'submitted_on' => date("F j, Y g:i:s A ", strtotime($row['timeCreated'])),
                'status' => $crud->coloriseStatus($row['statusId']),
                'archived_by' => $row['lifecycleStatedByName'],
                'archived_on' => date("F j, Y g:i:s A ", strtotime($row['lifecycleStatedOn'])),
                'actions' => $buttons
            );

        }
        echo json_encode($data);
        exit;

    }
}


?>