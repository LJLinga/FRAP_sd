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

    $query = "SELECT s.*, CONCAT(e.LASTNAME, ', ',e.FIRSTNAME) AS authorName, st.stepNo, st.stepName 
FROM sections s 
JOIN employee e ON e.EMP_ID = s.authorId
JOIN steps st ON st.id = s.stepId 
JOIN step_roles sr ON sr.stepId = st.id
WHERE sr.roleId = 5 AND sr.read = 2 ORDER BY s.sectionNo;
";


    $rows = $crud->getData($query);
    $data = [];
    foreach ((array) $rows as $key => $row) {
        $data[] =  array(
            'title_version' => '<span class="badge badge-success">'.$row['type'].'</span> <b>'.$row['title'].'</b> 
                                <span class="badge">'.$row['versionNo'].'</span><br>
                                Author: '.$row['originalAuthor'].'<br>
                                Modified by: '.$row['currentAuthor'].'<br>
                                on : <i>'.date("F j, Y g:i:s A ", strtotime($row['timeCreated'])).'</i><br>',
            'currentProcess' => '<span><b>' . $row['processName'] . '</b></span><br><span class="badge">Step ' . $row['stepNo'] . ' '. $row['stepName'].'</span>',
            'actions'=> '<a class="btn btn-default" name="documentId" href="http://localhost/FRAP_sd/EDMS_ViewDocument.php?docId='.$row['documentId'].'&versId='.$row['vid'].'">Edit</a>'
        );

    }
    echo json_encode($data);
    exit;
}
?>