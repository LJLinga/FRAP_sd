<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/23/2019
 * Time: 11:46 AM
 */


include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
session_start();
$userId = $_SESSION['idnum'];

if(isset($_POST['requestType'])){
    $requestType = $_POST['requestType'];
    if($requestType == 'MANUAL_SECTIONS_WRITEROUTE'){
        $query = "SELECT s.*, st.stepNo, st.stepName
                FROM sections s 
                JOIN steps st ON st.id = s.stepId 
                WHERE s.statusId = 2
                AND s.lifecycleId = 1
                AND s.stepId IN (SELECT st.id FROM user_groups ug
                                                    JOIN groups g ON ug.groupId = g.id
                                                    JOIN steps st ON g.id = st.groupId
                                                    WHERE (ug.userId = '$userId' AND (st.groute = 2 OR st.gwrite = 2) AND s.firstAuthorId != '$userId')
                                                    OR (st.route = 2 OR st.write = 2 AND s.firstAuthorId = '$userId'))
                ORDER BY s.sectionNo;";

        //First subquery condition => Displays data given that you are in the steps assigned group with w/r permissions.
        //Second subquery condition => Displays data given that you are its creator and has creator w/r permissions.

        $rows = $crud->getData($query);
        $data = [];
        foreach ((array) $rows as $key => $row) {
            $data[] =  array(
                'section_no' => $row['sectionNo'],
                'title' => $row['title'],
                'ver_no' => $row['versionNo'],
                'created_on' => date("F j, Y g:i:s A ", strtotime($row['timeCreated'])),
                'modified_by' => $crud->getUserName($row['authorId']),
                'modified_on' => date("F j, Y g:i:s A ", strtotime($row['lastUpdated'])),
                'status' => $crud->coloriseStatus($row['statusId']),
                'action' => '<a type="button" class="btn btn-primary fa fa-eye" target="_blank" href="MANUAL_EditSection.php?secId='.$row['id'].'"></a>'
            );

        }
        echo json_encode($data);
        exit;
    }else if($requestType == 'MANUAL_SECTIONS_ARCHIVED'){
        $query = "SELECT s.*, st.stepNo, st.stepName
                FROM sections s 
                JOIN steps st ON st.id = s.stepId 
                WHERE s.lifecycleId = 2
                AND s.stepId IN (SELECT st.id FROM user_groups ug
                                                    JOIN groups g ON ug.groupId = g.id
                                                    JOIN steps st ON g.id = st.groupId
                                                    WHERE ug.userId = '$userId' AND (st.gcycle = 2)
                                                    OR (st.cycle = 2 AND s.firstAuthorId = '$userId'))
                ORDER BY s.sectionNo;";

        $rows = $crud->getData($query);
        $data = [];
        foreach ((array) $rows as $key => $row) {
            $data[] =  array(
                'section_no' => $row['sectionNo'],
                'title' => $row['title'],
                'ver_no' => $row['versionNo'],
                'created_on' => date("F j, Y g:i:s A ", strtotime($row['timeCreated'])),
                'modified_by' => $crud->getUserName($row['authorId']),
                'modified_on' => date("F j, Y g:i:s A ", strtotime($row['lastUpdated'])),
                'status' => $crud->coloriseStatus($row['statusId']),
                'action' => '<a type="button" class="btn btn-primary fa fa-eye" target="_blank" href="MANUAL_EditSection.php?secId='.$row['id'].'"></a>'
            );

        }
        echo json_encode($data);
        exit;
    }else if($requestType == 'MANUAL_SECTIONS_READCOMMENT'){
        $query = "SELECT s.*, st.stepNo, st.stepName
                FROM sections s 
                JOIN steps st ON st.id = s.stepId 
                WHERE
                s.lifecycleId = 1
                AND s.stepId IN (SELECT st.id FROM steps st
                                JOIN groups g ON st.groupId = g.id 
                                JOIN user_groups u on g.id = u.groupId
                                WHERE u.userId = '$userId')
                ORDER BY s.sectionNo;";

        //First subquery checks if you are in process_groups
        //Second subquery checks if you are in step_groups

        $rows = $crud->getData($query);
        $data = [];
        foreach ((array) $rows as $key => $row) {
            $data[] =  array(
                'section_no' => $row['sectionNo'],
                'title' => $row['title'],
                'ver_no' => $row['versionNo'],
                'created_on' => date("F j, Y g:i:s A ", strtotime($row['timeCreated'])),
                'modified_by' => $crud->getUserName($row['authorId']),
                'modified_on' => date("F j, Y g:i:s A ", strtotime($row['lastUpdated'])),
                'status' => $crud->coloriseStatus($row['statusId']),
                'action' => '<a type="button" class="btn btn-primary fa fa-eye" target="_blank" href="MANUAL_EditSection.php?secId='.$row['id'].'"></a>'
            );

        }
        echo json_encode($data);
        exit;
    }else if($requestType == 'MANUAL_SECTIONS_EDITING'){
        $query = "SELECT s.*, st.stepNo, st.stepName
                FROM sections s 
                JOIN steps st ON st.id = s.stepId 
                WHERE s.lifecycleId = 1
                AND s.stepId IN (SELECT st.id FROM user_groups ug
                                                    JOIN groups g ON ug.groupId = g.id
                                                    JOIN steps st ON g.id = st.groupId
                                                    WHERE ug.userId = '$userId' 
                                                    AND st.gwrite = 2 OR (st.write = 2 AND s.firstAuthorId = '$userId'))
                AND s.availabilityId = '2' AND s.availabilityById = '$userId'
                ORDER BY s.sectionNo;";

        $rows = $crud->getData($query);
        $data = [];
        foreach ((array) $rows as $key => $row) {
            $data[] =  array(
                'section_no' => $row['sectionNo'],
                'title' => $row['title'],
                'ver_no' => $row['versionNo'],
                'created_on' => date("F j, Y g:i:s A ", strtotime($row['timeCreated'])),
                'modified_by' => $crud->getUserName($row['authorId']),
                'modified_on' => date("F j, Y g:i:s A ", strtotime($row['lastUpdated'])),
                'status' => $crud->coloriseStatus($row['statusId']),
                'action' => '<a type="button" class="btn btn-primary fa fa-eye" target="_blank" href="MANUAL_EditSection.php?secId='.$row['id'].'"></a>'
            );

        }
        echo json_encode($data);
        exit;
    }
}


?>