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

    // Load Process and Steps assigned to current document
    $query = "SELECT d.processId, d.currentStepId, p.processName, s.stepName FROM documents d 
              JOIN process p ON d.processId = p.id 
              JOIN steps s ON d.currentStepId = s.id WHERE d.documentId='$documentId';";
    $rows = $crud->getData($query);
    foreach((array) $rows as $key => $row){
        $processId = $row['processId'];
        $currentStepId= $row['currentStepId'];
        $processName = $row['processName'];
        $stepName = $row['stepName'];
    }

    $userId = $_SESSION['idnum'];

    // Load User Permissions
    $query = "SELECT su.read, su.write, su.route FROM step_users su
                JOIN employee e ON su.userId = e.EMP_ID
                WHERE su.stepId='$currentStepId' AND e.EMP_ID = '$userId' LIMIT 1;";
    $rows = $crud->getData($query);
    if(empty($rows)){
        // If user does not have individual permissions, check group permissions of user.
        // Individual rights supersede collective rights.
        // If user belongs to multiple groups in the same step, query only the 1st one.
        $query= "SELECT sg.read, sg.write, sg.route FROM step_groups sg 
                JOIN user_groups ug ON sg.groupId = ug.groupId
                JOIN employee e ON ug.employeeId = e.EMP_ID
                WHERE sg.stepId='$currentStepId' AND e.EMP_ID='$userId' LIMIT 1;";
        $rows = $crud->getData($query);
        if(empty($rows)){
            // If user also does not have group rights, redirect out of page.
            echo '<script language="javascript">';
            echo 'alert("empty rows, redirect out")';
            echo '</script>';
        }else{
            foreach((array) $rows as $key => $row){
                $read= $row['read'];
                $write= $row['write'];
                $route= $row['route'];
                $comment = $row['comment'];
            }
        }
    }else{
        foreach((array) $rows as $key => $row){
            $read= $row['read'];
            $write= $row['write'];
            $route= $row['route'];
            $comment = $row['comment'];
        }
    }

    //Get the rest of the document.
    $query = "SELECT d.firstAuthorId, d.timeFirstPosted, v.timeCreated, v.versionId, v.versionNo, v.authorId, v.title, v.filePath, 
              CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS originalAuthor,
              (SELECT CONCAT(e.LASTNAME,', ',e.FIRSTNAME) FROM employee e WHERE e.EMP_ID = v.authorId) AS currentAuthor
              FROM documents d JOIN doc_versions v ON d.documentId = v.documentId 
              JOIN employee e ON d.firstAuthorId = e.EMP_ID WHERE d.documentId='$documentId';";

    $rows = $crud->getData($query);
    foreach((array) $rows as $key => $row){
        $firstAuthorId = $row['firstAuthorId'];
        $originalAuthor = $row['originalAuthor'];
        $timeFirstPosted = $row['timeFirstPosted'];
        $timeUpdated = $row['timeCreated'];
        $versionId = $row['versionId'];
        $versionNo = $row['versionNo'];
        $currentAuthorId = $row['authorId'];
        $currentAuthor = $row['originalAuthor'];
        $title = $row['title'];
        $filePath = $row['filePath'];
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
                        <?php echo $processName;?>
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
                        Process: <b><?php echo $processName?></b><br>
                        Stage: <b><?php echo $stepName?></b><br>
                        Created by <b><?php echo $originalAuthor; ?></b><br>
                        <i>on <b><?php echo date("F j, Y g:i:s A ", strtotime($timeFirstPosted)); ?></b></i><br>
                        Updated by <b><?php echo $currentAuthor; ?></b><br>
                        <i>on <b><?php echo date("F j, Y g:i:s A ", strtotime($timeUpdated)); ?></b></i>
                    </div>
                </div>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b>Document Actions</b>
                    </div>
                    <div class="card-body" >
                        <div class="btn-group btn-group-vertical" style="width: 100%;">
                            <?php
                                $query = "SELECT routeName, nextProcessId, nextStepId FROM routes WHERE stepId ='$currentStepId' AND processId = '$processId';";
                                $rows = $crud->getData($query);
                                if(!empty($rows)) {
                                    foreach ((array)$rows as $key => $row) {
                                        $nextStepId = $row['nextStepId'];
                                        $nextProcessId = $row['nextStepId'];
                                        echo '<button class="btn btn-info" style="text-align: left" type="submit">' . $row['routeName'] . '</button>';
                                    }
                                }
                                ?>
                            <button class="btn btn-default" style="text-align: left">Download <?php if(isset($write) && $write=='2'){ echo "and Edit"; }?></button>
                            <?php if(isset($write) && $write=='2'){ echo '<button class="btn btn-default" style="text-align: left">Upload New Version</button>' ; }?>
                            <button class="btn btn-default" style="text-align: left">Archive</button>
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
