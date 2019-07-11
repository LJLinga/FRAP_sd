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

$rows = $crud->getPersonalDocuments($userId);

$data = [];
foreach ((array) $rows as $key => $row) {
    $domain = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
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
?>