<?php


include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();


if(isset($_POST['typeId']) && isset($_POST['documentId']) ){
    $typeId = $_POST['typeId'];
    $documentId = $_POST['documentId'];
    $query = "SELECT v.changeTypeId, v.lastUpdated, v.versionNo, v.title, v.filePath, v.remarks, s.statusname, st.stepName,
                    CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName, 
                    CONCAT(e2.LASTNAME,', ',e2.FIRSTNAME) AS statusAuthorName
                    FROM doc_versions v 
                    LEFT JOIN doc_status s ON v.statusId= s.id
                    LEFT JOIN steps st ON v.stepId = st.id
                    LEFT JOIN employee e ON v.authorId = e.EMP_ID
                    LEFT JOIN employee e2 ON v.statusedById = e2.EMP_ID
                    WHERE v.documentId = $documentId AND v.changeTypeId < 5 
                    ORDER BY v.lastUpdated DESC;";

    $rows = $crud->getData($query);
    $data = [];

    foreach ((array) $rows as $key => $row) {

        $content = '<div class="card" style="margin-top: 1rem;"><div class="card-body">';
        $buttons='<a class="btn btn-sm fa fa-download"  href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"></a>';
        $buttons.='<a class="btn btn-sm fa fa-info-circle"></a>';

        if($row['changeTypeId'] == '1'){
            $content.= '<b>'.$row['authorName'].'</b> created the document <span class="badge">Version '.$row['versionNo'].'</span>';
        }else if($row['changeTypeId'] == '2'){
            $content.= '<b>'.$row['authorName'].'</b> saved the document as <span class="badge">Version '.$row['versionNo'].'</span>';
        }else if($row['changeTypeId'] == '3'){
            $content.= '<b>'.$row['statusAuthorName'].'</b> <span class="badge">'.$row['statusname'].'S</span> the document';
        }else if($row['changeTypeId'] == '4'){
            $content.= '<b>'.$row['authorName'].'</b> submits the document for <span class="badge">'.$row['stepName'].'</span>';
        }

        $content.= '<i> on '.date("F j, Y g:i:s A ", strtotime($row['lastUpdated'])).'</i>';
        $content.= $buttons;
        if($row['remarks'] != ''){
            $content.='<br><div class="card"><div class="card-body">'.$row['remarks'].'</div></div>';
        }
        $content.= '</div></div>';

        $data[] =  array(
            'card_content' => $content
        );

    }
    echo json_encode($data);
    exit;
}
?>
<div id="preview<?php echo $count;?>" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <b><p class="modal-title">Section <?php echo $row['sectionNo']?>: <?php echo $row['title'];?></p></b>
                Author: <?php echo $row['versionAuthor']?><br>
                Created on: <i><?php echo date("F j, Y g:i:s A ", strtotime($row['timeCreated']));?></i>
            </div>
            <div class="modal-body" style="overflow-y: auto; max-height: 50rem;">
                <p><?php echo $row['content'];?></p>
            </div>
            <div class="modal-header">
                <b>Remarks</b>
            </div>
            <div class="modal-body" style="overflow-y: auto; max-height: 50rem;">
                <p><?php echo $row['content'];?></p>
            </div>
        </div>
    </div>
</div>