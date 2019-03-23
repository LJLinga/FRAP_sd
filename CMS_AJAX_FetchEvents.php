<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/23/2019
 * Time: 11:34 PM
 */


include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$query = "";


$rows = $crud->getData($query);
$data = [];
foreach ((array) $rows as $key => $row) {
    $data[] =  array(
        'title_version' => '<span class="badge badge-success">'.$row['type'].'</span> <b>'.$row['title'].'</b> 
                                <span class="badge">'.$row['versionNo'].'</span><br>
                                Author: '.$row['originalAuthor'].'<br>
                                Modified by: '.$row['currentAuthor'].'<br>
                                on : <i>'.date("F j, Y g:i:s A ", strtotime($row['timeCreated'])).'</i><br>',
        'currentProcess' => '<span><b>' . $row['processName'] . '</b></span><br><span class="badge">Step ' . $row['stepNo'] . ' '. $row['stepName'].'</span><br><span class="badge">'.$row['statusName'].'</span>',
        'actions'=> '<a class="btn btn-default" name="documentId" href="http://localhost/FRAP_sd/EDMS_ViewDocument.php?docId='.$row['documentId'].'&versId='.$row['vid'].'">Edit</a>'
    );

}
echo json_encode($data);