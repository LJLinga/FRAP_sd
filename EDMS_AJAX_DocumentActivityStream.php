<?php


include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();


if(isset($_POST['type'])){
    $typeId = $_POST['type'];
    $documentId = $_POST['documentId'];
    $query = "SELECT v.changeTypeId, v.lastUpdated, v.title, v.filePath, v.remarks, s.statusname, st.stepName,
                    CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName, 
                    CONCAT(e2.LASTNAME,', ',e2.FIRSTNAME) AS statusAuthorName
                    FROM doc_versions v 
                    LEFT JOIN doc_status s ON v.statusId= s.id
                    LEFT JOIN steps st ON v.stepId = st.id
                    LEFT JOIN employee e ON v.authorId = e.EMP_ID
                    LEFT JOIN employee e2 ON v.statusedById = e2.EMP_ID
                    WHERE v.documentId = $documentId AND v.changeTypeId < 5;";

    $rows = $crud->getData($query);
    $data = [];

    foreach ((array) $rows as $key => $row) {
        $timestamp = '<i>'.date("F j, Y g:i:s A ", strtotime($row['lastUpdated'])).'</i>';
        $remarks = '';
        if($row['changeTypeId'] == '1'){
            $title = '<b>'.$row['authorName'].'</b> created the document <span class="badge">'.$row['versionNo'].'</span><br>';
        }else if($row['changeTypeId'] == '2'){
            $title = '<b>'.$row['authorName'].'</b> modified the document <span class="badge">'.$row['versionNo'].'</span><br>';
            $remarks = '<br>'.$row['remarks'];
        }else if($row['changeTypeId'] == '3'){
            $title = '<b>'.$row['statusAuthorName'].'</b> <span class="badge">'.$row['statusname'].'S</span> the document.<br>';
            $remarks = '<br>'.$row['remarks'];
        }else if($row['changeTypeId'] == '4'){
            $title = '<b>'.$row['authorName'].'</b> submits the document for <span class="badge">'.$row['stepName'].'</span><br>';
            $timestamp = '<i>'.date("F j, Y g:i:s A ", strtotime($row['lastUpdated'])).'</i>';
            $remarks = '<br>'.$row['remarks'];
        }


        $data[] =  array(
            'card_content' => $title.$timestamp.$remarks
        );

    }
    echo json_encode($data);
    exit;
}
?>
