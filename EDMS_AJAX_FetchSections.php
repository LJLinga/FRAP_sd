<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/23/2019
 * Time: 11:46 AM
 */


include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();


if(isset($_POST['role'])){

    $role = $_POST['role'];

//    $query = "SELECT s.*, CONCAT(e.LASTNAME, ', ',e.FIRSTNAME) AS authorName, st.stepNo, st.stepName, stat.status
//                FROM sections s
//                JOIN employee e ON e.EMP_ID = s.authorId
//                JOIN section_status stat ON stat.id = s.statusId
//                JOIN steps st ON st.id = s.stepId
//                JOIN step_roles sr ON sr.stepId = st.id
//                WHERE sr.roleId = '$role' AND sr.read = 2 ORDER BY s.sectionNo;
//                ";

    $query = "SELECT s.*, CONCAT(e.LASTNAME, ', ',e.FIRSTNAME) AS authorName, st.stepNo, st.stepName, stat.status 
                FROM sections s 
                JOIN employee e ON e.EMP_ID = s.authorId
                JOIN section_status stat ON stat.id = s.statusId
                JOIN steps st ON st.id = s.stepId ORDER BY s.sectionNo;
                ";

    $rows = $crud->getData($query);
    $data = [];
    foreach ((array) $rows as $key => $row) {
        $data[] =  array(
            'section_no' => $row['sectionNo'],
            'title' => $row['title'],
            'modified_by' => $row['authorName'],
            'status' => $row['status'].' <br>('.$row['stepName'].')',
            'action' => '<a type="button" class="btn btn-primary" href="http://localhost/FRAP_sd/MANUAL_ViewSection.php?secId='.$row['id'].'"> View </a>'
            );

    }
    echo json_encode($data);
    exit;
}
?>