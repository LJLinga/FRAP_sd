<?php
/**
 * Created by PhpStorm.
 * User: Serus Caligo
 * Date: 10/4/2018
 * Time: 3:48 PM
 */
include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
//include('GLOBAL_EDMS_ADMIN_CHECKING.php');

include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR_ViewDocument.php';

if(isset($_GET['docId'])){

    $query = "SELECT d.firstAuthorId, d.timeFirstPosted, d.processId, v.versionId, v.versionNo, v.authorId, v.title, v.filePath 
              FROM documents d JOIN doc_versions v ON d.documentId = v.documentId;";

    $rows = $crud->getData($query);
    foreach((array) $rows as $key => $row){
        $firstAuthorId = $row['firstAuthorId'];
        $timeFirstPosted = $row['timeFirstPosted'];
        $processId = $row['processId'];
        $versionId = $row['versionId'];
        $versionNo = $row['versionNo'];
        $currentAuthorId = $row['authorId'];
        $title = $row['title'];
        $filePath = $row['filePath'];
    }

    $query = "SELECT name FROM facultyassocnew.process WHERE id='$processId';";
    $rows = $crud->getData($query);
    foreach((array) $rows as $key => $row){
        $folder = $row['name'];
    }

}
?>

<div id="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <h3 class="page-header"><?php echo $title ?></h3>
                <ol class="breadcrumb">
                    <li>
                        <?php echo $folder;?>s
                    </li>
                    <li class="active">

                    </li>

                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <iframe src = "/ViewerJS/../FRAP_sd/<?php echo $filePath;?>" width='850' style="position:fixed !important; position:absolute; height:66.67vh;"; allowfullscreen webkitallowfullscreen></iframe>
            </div>
            <div class="col-lg-4">

                <div class="card">

                <div class="card-header">
                    Title: <b><?php echo $title?></b><br>
                    Version No.: <b><?php echo $versionNo;?></b>
                </div>
                <div class="card-body" >
                    Assigned Process: <b><?php echo $folder?></b><br>
                    Creator: <b><?php echo $firstAuthorId; ?></b><br>
                    <i>Created on: <b><?php echo date("F j, Y g:i:s A ", strtotime($timeFirstPosted)); ?></b></i><br><br>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'GLOBAL_FOOTER.php';?>
