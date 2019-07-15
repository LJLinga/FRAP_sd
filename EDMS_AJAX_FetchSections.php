<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/23/2019
 * Time: 11:46 AM
 */


include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$query = "SELECT s.*, st.stepNo, st.stepName, stat.status 
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
        'ver_no' => $row['versionNo'],
        'created_on' => date("F j, Y g:i:s A ", strtotime($row['timeCreated'])),
        'modified_by' => $crud->getUserName($row['authorId']),
        'modified_on' => date("F j, Y g:i:s A ", strtotime($row['lastUpdated'])),
        'status' => $crud->coloriseStatus($row['statusId']),
        'action' => '<a type="button" class="btn btn-primary fa fa-eye" href="MANUAL_EditSection.php?secId='.$row['id'].'"></a>'
    );

}
echo json_encode($data);
exit;
?>