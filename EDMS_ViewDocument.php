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
include('GLOBAL_EDMS_ADMIN_CHECKING.php');

include 'GLOBAL_HEADER.php';
include 'EDMS_Sidebar.php';

if(isset($_GET['docId'])){

    $documentId = $_GET['docId'];

    // Load Process and Steps
    $query = "SELECT d.processId, d.currentStepId FROM documents d WHERE d.documentId='$documentId';";
    $rows = $crud->getData($query);
    foreach((array) $rows as $key => $row){
        $processId = $row['processId'];
        $currentStepId= $row['currentStepId'];
    }

    // Load User Credentials
    $userId = $_SESSION['idnum'];

    // Load User Roles and Properties relating to this document.
    $query = "SELECT sp.EMP_ID FROM documents d WHERE d.documentId='$documentId';";
    $rows = $crud->getData($query);
    foreach((array) $rows as $key => $row){
        $processId = $row['processId'];
        $currentStepId= $row['currentStepId'];
    }

    $query = "SELECT d.firstAuthorId, d.timeFirstPosted, v.versionId, v.versionNo, v.authorId, v.title, v.filePath 
              FROM documents d JOIN doc_versions v ON d.documentId = v.documentId WHERE d.documentId='$documentId';";

    $rows = $crud->getData($query);
    foreach((array) $rows as $key => $row){
        $firstAuthorId = $row['firstAuthorId'];
        $timeFirstPosted = $row['timeFirstPosted'];
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
            <div class="col-lg-12" style="margin-top: 2rem;">
                <ol class="breadcrumb">
                    <li>
                        <a href="http://localhost/FRAP_sd/EDMS_Dashboard.php">Documents</a>
                    </li>
                    <li>
                        <?php echo $folder;?>
                    </li>
                    <li class="active">
                        <?php echo $title ?>
                    </li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <iframe src = "/ViewerJS/../FRAP_sd/<?php echo $filePath;?>" width='850' style="position:fixed !important; position:absolute; height:80vh;"; allowfullscreen webkitallowfullscreen></iframe>
            </div>
            <div class="col-lg-4">

                <div class="card">
                    <div class="card-header">
                        Title: <b><?php echo $title?></b><br>
                        Version No.: <b><?php echo $versionNo;?></b>
                    </div>
                    <div class="card-body" >
                        Process: <b><?php echo $folder?></b><br>
                        Created by <b><?php echo $firstAuthorId; ?></b><br>
                        <i>on <b><?php echo date("F j, Y g:i:s A ", strtotime($timeFirstPosted)); ?></b></i>
                    </div>
                </div>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b>Document Actions</b>
                    </div>
                    <div class="card-body" >
                        <div class="btn-group btn-group-vertical">

                            <button class="btn btn-success">Approve</button>
                            <button class="btn btn-warning">Reject</button>
                            <button class="btn btn-default">Download and Edit</button>
                            <button class="btn btn-default">Upload New Version</button>
                            <button class="btn btn-default">Archive</button>
                        </div>
                    </div>
                </div>

                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b>Version History</b>
                    </div>
                    <div class="card-body" style="max-height: 20rem; overflow: auto;" >
                        <div class="card-body" style="position: relative;">
                            Version 2.0
                            <div class="btn-group-sm" style="position: absolute;right: 10px;top: 5px;">
                                <button type="button" class="btn btn-sm">Download</button>
                                <button type="button" class="btn btn-sm">Revert</button>
                            </div>
                        </div>
                        <div class="card-body" style="position: relative;">
                            Version 1.1
                            <div class="btn-group-sm" style="position: absolute;right: 10px;top: 5px;">
                                <button type="button" class="btn btn-sm">Download</button>
                                <button type="button" class="btn btn-sm">Revert</button>
                            </div>
                        </div>
                        <div class="card-body" style="position: relative;">
                            Version 1.0
                            <div class="btn-group-sm" style="position: absolute;right: 10px;top: 5px;">
                                <button type="button" class="btn btn-sm">Download</button>
                                <button type="button" class="btn btn-sm">Revert</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'GLOBAL_FOOTER.php';?>
