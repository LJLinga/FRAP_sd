<?php
/**
 * Created by PhpStorm.
 * User: Christian Alderite
 * Date: 10/4/2018
 * Time: 3:48 PM
 */
include('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');

$edmsRole = $_SESSION['EDMS_ROLE'];
$userId = $_SESSION['idnum'];
$error = '';

if(isset($_POST['btnUnlock'])){
    $documentId= $_POST['btnUnlock'];
    $crud->execute("UPDATE documents SET availabilityId='1', availabilityById='$userId' WHERE documentId='$documentId'");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ViewDocument.php?docId=".$documentId);
    exit;
}


if(isset($_POST['btnLock'])){
    $documentId = $_POST['btnLock'];
    $rows = $crud->getData("SELECT availabilityId FROM documents WHERE documentId = '$documentId'");
    foreach((array) $rows as $key => $row){
        $availability = $row['availabilityId'];
    }
    if($availability == '1'){
        $crud->execute("UPDATE documents SET availabilityId='2', availabilityById='$userId' WHERE documentId='$documentId'");
    }else{
        $error = '&alert=DOC_LOCK_FAIL';
    }
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ViewDocument.php?docId=" .$documentId.$error);
    exit;
}

if(isset($_POST['btnRevert'])){
    $documentId = $_POST['documentId'];
    $remarks = $_POST['remarks'];
    $title = $_POST['title'];
    $filePath = $_POST['filePath'];
    $versionNo = $_POST['newVersionNo'];
    try{
        $crud->execute("UPDATE documents SET authorId='$userId', remarks='$remarks',
                                    versionNo='$versionNo', title='$title',filePath='$filePath' WHERE documentId='$documentId';");
    }catch(Exception $e){
        $error='&alert=DATABASE_ERROR';
    }
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ViewDocument.php?docId=".$documentId.$error);
    exit;
}

if(isset($_POST['btnRoute'])){
    $documentId = $_POST['documentId'];
    $nextStepId = $_POST['nextStepId'];
    $statusId = $_POST['assignStatusId'];
    $currentStatusId = $_POST['currentStatusId'];
    $remarks = $_POST['remarks'];

    if($statusId >= 5){
        $statusId = $currentStatusId;
    }
    try{
        if($statusId != $currentStatusId){
            $crud->execute("UPDATE documents SET statusId = '$statusId', stepId='$nextStepId', statusedById='$userId', steppedById='$userId', remarks = '$remarks' WHERE documentId='$documentId'");
        }else if($statusId == $currentStatusId){
            $crud->execute("UPDATE documents SET stepId='$nextStepId', steppedById='$userId', remarks = '$remarks' WHERE documentId='$documentId'");
        }
    }catch(Exception $e){
        $error='&alert=DATABASE_ERROR';
    }
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ViewDocument.php?docId=".$documentId.$error);
    exit;
}

if(isset($_POST['btnArchive'])){
    $documentId = $_POST['documentId'];
    $remarks = $crud->escape_string($_POST['remarks']);
    try{
        $crud->execute("UPDATE documents SET lifecycleStateId = 2, remarks='$remarks', lifecycleStatedById ='$userId' WHERE documentId = '$documentId' ");
    }catch(Exception $e){
        $error='&alert=DATABASE_ERROR';
    }
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ViewDocument.php?docId=".$documentId.$error);
}

if(isset($_POST['btnRestore'])){
    $documentId = $_POST['documentId'];
    $remarks = $crud->escape_string($_POST['remarks']);
    try{
        $crud->execute("UPDATE documents SET lifecycleStateId = '1', remarks='$remarks', lifecycleStatedById ='$userId' WHERE documentId = '$documentId' ");
    }catch(Exception $e){
        $error='&alert=DATABASE_ERROR';
    }
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ViewDocument.php?docId=".$documentId.$error);
    exit;
}

if(isset($_GET['docId'])){
    $documentId = $_GET['docId'];

    // Load document
    $query = "SELECT 
                d.firstAuthorId, d.timeCreated, d.availabilityId, d.versionNo, d.authorId, d.title, d.filePath, d.lastUpdated,
		        CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS originalAuthor, dt.type,
                (SELECT CONCAT(e.LASTNAME,', ',e.FIRSTNAME) FROM employee e WHERE e.EMP_ID = d.authorId) AS currentAuthor,
                (SELECT CONCAT(e2.LASTNAME,', ',e2.FIRSTNAME) FROM employee e2 WHERE e2.EMP_ID = d.statusedById) AS statusedByName,
                (SELECT CONCAT(e3.LASTNAME,', ',e3.FIRSTNAME) FROM employee e3 WHERE e3.EMP_ID = d.availabilityById) AS availabilityByName,
                (SELECT CONCAT(e4.LASTNAME,', ',e4.FIRSTNAME) FROM employee e4 WHERE e4.EMP_ID = d.lifecycleStatedById) AS statedByName,
                (SELECT CONCAT(e5.LASTNAME,', ',e5.FIRSTNAME) FROM employee e5 WHERE e5.EMP_ID = d.remark_user_id) AS remarkedByName, d.remarks, d.remark_timestamp, d.remark_action_type,
		        d.stepId,  p.processName, s.stepName, d.lifecycleStatedById, d.availabilityById, d.statusId, d.lifecycleStateId, d.lifecycleStatedOn, d.availabilityOn, d.statusedOn
                FROM documents d 
                JOIN employee e ON d.firstAuthorId = e.EMP_ID
                JOIN steps s ON d.stepId = s.id 
                JOIN doc_status st ON st.id = d.statusId 
                
                JOIN process p ON s.processId = p.id 
                JOIN doc_type dt ON d.typeId = dt.id
                WHERE d.documentId='$documentId' 
                LIMIT 1;";

    $rows = $crud->getData($query);
    if(!empty($rows)){


        foreach((array) $rows as $key => $row){
            $firstAuthorId = $row['firstAuthorId'];
            $originalAuthor = $row['originalAuthor'];
            $timeFirstPosted = $row['timeCreated'];
            $timeUpdated = $row['lastUpdated'];
            $versionNo = $row['versionNo'];
            $currentAuthorId = $row['authorId'];
            $currentAuthor = $row['currentAuthor'];
            $title = $row['title'];
            $filePath = $row['filePath'];
            $currentStepId= $row['stepId'];
            $processName = $row['processName'];
            $stepName = $row['stepName'];
            $availability = $row['availabilityId'];
            $availabilityById = $row['availabilityById'];
            $statusId = $row['statusId'];
            $stateId = $row['lifecycleStateId'];
            $docType = $row['type'];
            $statusName = $crud->assignStatusString($statusId);
            $statusUpdaterName = $row['statusedByName'];
            $statusedOn = $row['statusedOn'];
            $statedOn = $row['lifecycleStatedOn'];
            $availabilityOn = $row['availabilityOn'];
            $stateName = $crud->lifecycleString($stateId);
            $stateUpdaterName = $row['statedByName'];
            $availabilityName = $crud->availabilityString($availability);
            $availabilityByName = $row['availabilityByName'];
            $remarkedByName = $row['remarkedByName'];
            $remarks = $row['remarks'];
            $remarkedOn = $row['remark_timestamp'];
            $remarkType = $row['remark_action_type'];
        }

        $rows = $crud->getStepUserPermissions($currentStepId, $firstAuthorId, $userId);
        if(!empty($rows)){
            foreach((array) $rows AS $key => $row){
                $write = $row['write'];
                $route = $row['route'];
                $cycle = $row['cycle'];
            }
        }else{
            header("Location:".$crud->redirectToPreviousWithAlert("DOC_NO_PERMISSIONS"));
            exit;
        }

        if($stateId == '2'){
            $write = '1';
            $route = '1';
            if($cycle != '2'){
                header("Location:".$crud->redirectToPreviousWithAlert("DOC_IS_ARCHIVED"));
                exit;
            }
        }

        if($availability == '2'){
            if($availabilityById != $userId){
                $write = '1';
                $route = '1';
                $cycle = '1';
            }
        }

    }else{
        header("Location:".$crud->redirectToPreviousWithAlert("DOC_NOT_LOAD"));
        exit;
    }

}else{
    header("Location:".$crud->redirectToPreviousWithAlert("DOC_NOT_LOAD"));
    exit;
}

$page_title = $docType.' > '.$title;

include 'GLOBAL_ALERTS.php';
include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';
?>



<div id="content-wrapper">
    <div class="container-fluid">
        <div class="row" style="margin-top: 2rem;">
            <div class="col-lg-8">
                <ol class="breadcrumb">
                    <li>
                        <a href="EDMS_Workspace.php">Workspace</a>
                    </li>
                    <li>
                        <?php echo $processName;?>
                    </li>
                    <li class="active">
                        <?php echo $title; ?>
                    </li>
                </ol>
                <div class="panel panel-default">
                <?php
                $ext = pathinfo($filePath, PATHINFO_EXTENSION);
                if($ext == 'pdf' || $ext == 'jpg'){?>
                    <iframe src="/ViewerJS/../FRAP_sd/<?php echo $filePath; ?>" width="850" style="height:80vh;" allowfullscreen webkitallowfullscreen></iframe>
                <?php }else{ ?>
                    <div class="panel-heading"><b>Document File</b></div>
                    <div class="panel-body">
                        <p>Viewer does not support format : <b><?php echo $ext;?></b></p>
                        <a class="btn fa fa-download"  href="<?php echo $filePath; ?>" download="<?php echo $title.'_ver'.$versionNo.'_'.basename($filePath);?>"> Download </a>
                    </div>
                <?php }
                ?>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-12">
                                <b class="panel-title">Document History</b>
                                <button class="btn btn-default btn-sm fa fa-expand" type="button" data-toggle="collapse" data-target="#collapseHistory" style="position: absolute; top: 0px; right: 15px;">
                            </div>
                        </div>

                    </div>
                    <div class="panel-body" id="collapseHistory">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-inline">
                                    <label for="sel1">Ver. No. </label>
                                    <select class="form-control" id="selectedVersion" name="selectedUser">
                                        <option value="">All</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-inline">
                                    <label for="sel1">User </label>
                                    <select class="form-control" id="selectedUser" name="selectedUser">
                                        <option value="">All</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-inline">
                                    <label for="sel1">Action</label>
                                    <select class="form-control" id="selectedAction" name="selectedAction">
                                        <option value="" selected>ALL</option>
                                        <option value="created|updated|archived|restored|draft|pending|approved|rejected|moved">MAIN</option>
                                        <option value="created|updated">CONTENT UPDATES</option>
                                        <option value="archived|restored">ARCHIVE/RESTORE</option>
                                        <option value="draft|pending|approved|rejected">ALL STATUS</option>
                                        <option value="draft">DRAFT</option>
                                        <option value="pending">PENDING</option>
                                        <option value="approved">APPROVED</option>
                                        <option value="rejected">REJECTED</option>
                                        <option value="moved">STEP UPDATES</option>
                                        <option value="checked out|checked in">CHECK-IN/CHECK-OUT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-inline">
                                    <label for="sel1">Search</label>
                                    <input type="text" id="searchField" class="form-control">
                                </div>
                            </div>
                        </div>
                        <table id="tblHistory" class="table table-condensed table-sm table-striped" cellspacing="0" width="100%">
                            <thead>
                            <th>Timestamp</th>
                            <th>Ver. No.</th>
                            <th>User</th>
                            <th>Description</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                            <?php
                            $query = "SELECT v.audit_action_type, v.versionId,
                                                v.audit_timestamp, 
                                                v.versionNo, 
                                                v.title, 
                                                v.filePath, 
                                                v.remarks,
                                                v.lifecycleStateId, v.statusedById, v.lifecycleStatedById, v.availabilityById,
                                                v.availabilityId, dt.type AS docType, v.lastUpdated,
                                                s.statusname, v.statusId, p.processName, v.availabilityOn, v.lifecycleStatedOn,
                                                st.stepName, st.stepNo, v.statusedOn,
                                                CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS name,
                                                CONCAT(e1.LASTNAME,', ',e1.FIRSTNAME) AS authorName,
                                                CONCAT(e2.LASTNAME,', ',e2.FIRSTNAME) AS statusedByName,
                                                CONCAT(e3.LASTNAME,', ',e3.FIRSTNAME) AS lifecycleStatedByName,
                                                CONCAT(e4.LASTNAME,', ',e4.FIRSTNAME) AS availabilityByName
                                            
                                            FROM doc_versions v 
                                            JOIN doc_status s ON v.statusId= s.id
                                            JOIN steps st ON v.stepId = st.id
                                            JOIN process p ON st.processId = p.id
                                            JOIN doc_type dt ON dt.id = v.typeId
                                            LEFT JOIN employee e ON v.audit_user_id = e.EMP_ID
                                            LEFT JOIN employee e1 ON v.authorId = e1.EMP_ID
                                            LEFT JOIN employee e2 ON v.statusedById = e2.EMP_ID
                                            LEFT JOIN employee e3 ON v.lifecycleStatedById = e3.EMP_ID
                                            LEFT JOIN employee e4 ON v.availabilityById = e4.EMP_ID
                                            WHERE v.documentId = $documentId
                                            ORDER BY v.audit_timestamp DESC;";



                            $rows = $crud->getData($query);

                            if(!empty($rows)) {
                                $labelCol = '';
                                foreach ((array)$rows as $key => $row) {

                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo date("F j, Y g:i:s A ", strtotime($row['audit_timestamp']));?>
                                        </td>
                                        <td>
                                            <?php echo $row['versionNo'];?>
                                        </td>
                                        <td>
                                            <?php echo $row['name'];?>
                                        </td>
                                        <td>
                                            <?php
                                            if($row['audit_action_type'] == 'LOCKED') { ?>
                                                <?php echo $crud->coloriseAvailability($row['availabilityId']);?> the document.
                                            <?php }else if($row['audit_action_type'] == 'STATUSED') { ?>
                                                <?php echo $crud->coloriseStatus($row['statusId']);?> status assigned to the document.<br>
                                                <span class="label label-default">CHECKED IN</span> the document.
                                            <?php }else if($row['audit_action_type'] == 'MOVED') { ?>
                                                <?php echo $crud->coloriseStep();?> the document to <strong>Step <?php echo $row['stepNo'];?>: <?php echo $row['stepName'];?></strong>.<br>
                                                <span class="label label-default">CHECKED IN</span> the document.
                                            <?php }else if($row['audit_action_type'] == 'CYCLED'){ ?>
                                                <?php echo $crud->coloriseCycle($row['lifecycleStateId']);?> the document.<br>
                                                <span class="label label-default">CHECKED IN</span> the document.
                                            <?php }else if($row['audit_action_type'] == 'UPDATED' || $row['audit_action_type'] == 'CREATED') {
                                                $labelCol = 'success'?>
                                                <span class="label label-<?php echo $labelCol;?>"><?php echo $row['audit_action_type'];?></span> the document.<br>
                                                <span class="label label-default">CHECKED IN</span> the document.
                                            <?php }else if($row['audit_action_type'] == 'STATUSED/MOVED') {
                                                $labelCol = 'primary'?>
                                                <?php echo $crud->coloriseStep();?> the document to <strong>Step <?php echo $row['stepNo'];?>: <?php echo $row['stepName'];?></strong>.<br>
                                                <?php echo $crud->coloriseStatus($row['statusId']);?> status assigned to the document.<br>
                                                <span class="label label-default">CHECKED IN</span> the document.
                                            <?php }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if($row['audit_action_type'] != 'LOCKED' && $row['audit_action_type'] != 'CREATED'){?>
                                                <a class="btn btn-sm" data-toggle="modal" data-target="#modalVersionPreview<?php echo $row['versionId'];?>"><i class="fa fa-eye"></i></a>
                                                <div id="modalVersionPreview<?php echo $row['versionId'];?>" class="modal fade" role="dialog">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <strong class="modal-title">Remarks Preview</strong>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="panel panel-default">
                                                                            <div class="panel-body">
                                                                                <strong><?php echo $row['name'];?></strong> on
                                                                                <i><?php echo date("F j, Y g:i:s A ", strtotime($row['audit_timestamp']));?></i><br>
                                                                                <?php
                                                                                if($row['audit_action_type'] == 'LOCKED') { ?>
                                                                                    <?php echo $crud->coloriseAvailability($row['availabilityId']);?> the document.
                                                                                <?php }else if($row['audit_action_type'] == 'STATUSED') { ?>
                                                                                    <?php echo $crud->coloriseStatus($row['statusId']);?> status assigned to the document.<br>
                                                                                    <span class="label label-default">CHECKED IN</span> the document.
                                                                                <?php }else if($row['audit_action_type'] == 'MOVED') { ?>
                                                                                    <?php echo $crud->coloriseStep();?> the document to <strong>Step <?php echo $row['stepNo'];?>: <?php echo $row['stepName'];?></strong>.<br>
                                                                                    <span class="label label-default">CHECKED IN</span> the document.
                                                                                <?php }else if($row['audit_action_type'] == 'CYCLED'){ ?>
                                                                                    <?php echo $crud->coloriseCycle($row['lifecycleStateId']);?> the document.<br>
                                                                                    <span class="label label-default">CHECKED IN</span> the document.
                                                                                <?php }else if($row['audit_action_type'] == 'UPDATED' || $row['audit_action_type'] == 'CREATED') {
                                                                                    $labelCol = 'success'?>
                                                                                    <span class="label label-<?php echo $labelCol;?>"><?php echo $row['audit_action_type'];?></span> the document.<br>
                                                                                    <span class="label label-default">CHECKED IN</span> the document.
                                                                                <?php }else if($row['audit_action_type'] == 'STATUSED/MOVED') {
                                                                                    $labelCol = 'primary'?>
                                                                                    <?php echo $crud->coloriseStep();?> the document to <strong>Step <?php echo $row['stepNo'];?>: <?php echo $row['stepName'];?></strong>.<br>
                                                                                    <?php echo $crud->coloriseStatus($row['statusId']);?> status assigned to the document.<br>
                                                                                    <span class="label label-default">CHECKED IN</span> the document.
                                                                                <?php }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="panel panel-default">
                                                                            <div class="panel-heading">
                                                                                <b>Version Details</b>
                                                                            </div>
                                                                            <div class="panel-body">
                                                                                <table class="table table-responsive table-striped table-condensed table-sm">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <th>Title</th>
                                                                                        <td><?php echo $row['title']; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>Version No.</th>
                                                                                        <td><?php echo $row['versionNo']; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>Type</th>
                                                                                        <td><?php echo $row['docType']; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>Process</th>
                                                                                        <td><?php echo $row['processName']; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>Stage</th>
                                                                                        <td><?php echo $row['stepName']; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>Status</th>
                                                                                        <td>
                                                                                            <?php echo $crud->coloriseStatus($row['statusId'])?>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?php if($row['statusedById'] != ''){?>

                                                                                        <tr>
                                                                                            <th>Status updated by</th>
                                                                                            <td><?php echo $row['statusedByName']; ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>Status updated on</th>
                                                                                            <td><?php echo date("F j, Y g:i:s A ", strtotime($row['statusedOn']));?></td>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                    <?php if($row['lifecycleStatedById'] != ''){ ?>
                                                                                        <tr>
                                                                                            <th>State</th>
                                                                                            <td>
                                                                                                <?php echo $crud->coloriseCycle($row['lifecycleStateId']);?>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>State updated by</th>
                                                                                            <td><?php echo $row['lifecycleStatedByName'] ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>State updated on</th>
                                                                                            <td><?php echo date("F j, Y g:i:s A ", strtotime($row['lifecycleStatedOn']));?></td>
                                                                                        </tr>
                                                                                    <?php }?>
                                                                                    <tr>
                                                                                        <th>Created by</th>
                                                                                        <td><?php echo $originalAuthor; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>Created on</th>
                                                                                        <td><?php echo date("F j, Y g:i:s A ", strtotime($timeFirstPosted)); ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>Content updated by</th>
                                                                                        <td><?php echo $row['authorName']; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>Content updated on</th>
                                                                                        <td><?php echo date("F j, Y g:i:s A ", strtotime($row['lastUpdated']));?></td>
                                                                                    </tr>
                                                                                    <?php if($row['availabilityById'] != '' && $availability == '2') {  ?>
                                                                                    <tr>
                                                                                        <th>Currently checked out by</th>
                                                                                        <td><?php echo $row['availabilityByName']; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>Checked out on </th>
                                                                                        <td><?php echo date("F j, Y g:i:s A ", strtotime($row['availabilityOn'])); ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <?php } ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <label>Remarks </label>
                                                                        <div class="alert alert-info" style="max-height: 60rem; overflow-y: auto;">
                                                                            "<i><?php echo $row['remarks'];?></i>"
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php }?>
                                            <?php if($row['audit_action_type'] != 'LOCKED' && $write == '2') { ?>
                                                <a class="btn btn-sm" data-toggle="modal" data-target="#modalRevert<?php echo $row['versionId'];?>"><i class="fa fa-refresh"></i></a>
                                                <div id="modalRevert<?php echo $row['versionId'];?>" class="modal fade" role="dialog">
                                                    <div class="modal-dialog modal-lg">
                                                        <form method="POST" action="">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <strong>Revert to Version <?php echo $row['versionNo'];?></strong>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <div class="panel panel-default">
                                                                                <div class="panel-heading">
                                                                                    <b>Version Details</b>
                                                                                </div>
                                                                                <div class="panel-body">
                                                                                    <table class="table table-responsive table-striped table-condensed table-sm">
                                                                                        <tbody>
                                                                                        <tr>
                                                                                            <th>Title</th>
                                                                                            <td><?php echo $row['title']; ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>Version No.</th>
                                                                                            <td><?php echo $row['versionNo']; ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>Type</th>
                                                                                            <td><?php echo $row['docType']; ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>Process</th>
                                                                                            <td><?php echo $row['processName']; ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>Stage</th>
                                                                                            <td><?php echo $row['stepName']; ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>Status</th>
                                                                                            <td>
                                                                                            <?php echo $crud->coloriseStatus($row['statusId']) ;?>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <?php if($row['statusedById'] != ''){?>

                                                                                            <tr>
                                                                                                <th>Status updated by</th>
                                                                                                <td><?php echo $row['statusedByName']; ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Status updated on</th>
                                                                                                <td><?php echo date("F j, Y g:i:s A ", strtotime($row['statusedOn']));?></td>
                                                                                            </tr>
                                                                                        <?php } ?>
                                                                                        <?php if($row['lifecycleStatedById'] != ''){ ?>
                                                                                            <tr>
                                                                                                <th>State</th>
                                                                                                <td>
                                                                                                <?php echo $crud->coloriseCycle($row['lifecycleStateId']);?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>State updated by</th>
                                                                                                <td><?php echo $row['lifecycleStatedByName'] ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>State updated on</th>
                                                                                                <td><?php echo date("F j, Y g:i:s A ", strtotime($row['lifecycleStatedOn']));?></td>
                                                                                            </tr>
                                                                                        <?php }?>
                                                                                        <tr>
                                                                                            <th>Created by</th>
                                                                                            <td><?php echo $originalAuthor; ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>Created on</th>
                                                                                            <td><?php echo date("F j, Y g:i:s A ", strtotime($timeFirstPosted)); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>Content updated by</th>
                                                                                            <td><?php echo $row['authorName']; ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>Content updated on</th>
                                                                                            <td><?php echo date("F j, Y g:i:s A ", strtotime($row['lastUpdated']));?></td>
                                                                                        </tr>
                                                                                        <?php if($row['availabilityById'] != '' && $availability == '2') {  ?>
                                                                                        <tr>
                                                                                            <th>Currently checked out by</th>
                                                                                            <td><?php echo $row['availabilityByName']; ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>Checked out on </th>
                                                                                            <td><?php echo date("F j, Y g:i:s A ", strtotime($row['availabilityOn'])); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <?php } ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <div class="alert alert-info">
                                                                                Reverting only brings back older document content.
                                                                                It will not bring back other information such as status, state, etc.
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for=".radio"> Revert to old version as Version </label><br>
                                                                                <?php
                                                                                $trueNo = explode(" ",$versionNo);
                                                                                $rowNo = explode(" ",$row['versionNo'])[0];
                                                                                $tempVerNo = explode(".", $trueNo[0]);
                                                                                if($tempVerNo){
                                                                                    $largeNum = $tempVerNo[0];
                                                                                    $smallNum = $tempVerNo[1];
                                                                                }else{
                                                                                    $largeNum = $versionNo;
                                                                                    $smallNum = '0';
                                                                                }
                                                                                ?>
                                                                                <div class="radio">
                                                                                    <label><input type="radio" name="newVersionNo" value="<?php echo $largeNum.'.'.(floatval($smallNum) + 1); ?> (rev. <?php echo $rowNo;?>)" checked><?php echo $largeNum.'.'.(floatval($smallNum) + 1); ?> (rev. <?php echo $rowNo;?>) (Minor Update)</label>
                                                                                </div><br>
                                                                                <div class="radio">
                                                                                    <label><input type="radio" name="newVersionNo" value="<?php echo (floatval($largeNum) + 1).'.0';?> (rev. <?php echo $rowNo;?>)"><?php echo (floatval($largeNum) + 1).'.0'; ?> (rev. <?php echo $rowNo;?>) (Major Update)</label>
                                                                                </div>
                                                                            </div><br><br>
                                                                            <div class="form-group">
                                                                                <label for="remarks">Reason why I'm reverting back to this version</label>
                                                                                <textarea name="remarks" id="remarks" class="form-control" placeholder="Your remarks..." rows="20" cols="48" required></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="form-group">
                                                                        <input type="hidden" name="documentId" value="<?php echo $documentId; ?>">
                                                                        <input type="hidden" name="filePath" value="<?php echo $row['filePath']; ?>">
                                                                        <input type="hidden" name="title" value="<?php echo $row['title']; ?>">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                        <input type="submit" name="btnRevert" class="btn btn-primary">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if($row['audit_action_type'] != 'LOCKED') { ?>
                                            <a class="btn btn-sm fa fa-download"  href="<?php echo $filePath;?>" download="<?php echo $row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']);?>"></a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php }
                            }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Comments</b></div>
                    <div class="panel-body" style="max-width: 20rem; overflow-y: auto;">
                        <button type="button" class="btn btn-primary fa fa-comment" data-toggle="modal" data-target="#myModal" name="addComment" id="addComment"> Comment </button>
                        <span id="comment_message"></span>
                        <div id="display_comment"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Document Details</b>
                    </div>
                    <div class="panel-body">
                        <table class="table table-responsive table-striped table-condensed table-sm">
                            <tbody>
                            <tr>
                                <th>Title</th>
                                <td><?php echo $title; ?></td>
                            </tr>
                            <tr>
                                <th>Version No.</th>
                                <td><?php echo $versionNo; ?></td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td><?php echo $docType; ?></td>
                            </tr>
                            <tr>
                                <th>Process</th>
                                <td><?php echo $processName; ?></td>
                            </tr>
                            <tr>
                                <th>Stage</th>
                                <td><?php echo $stepName; ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <?php echo $crud->coloriseStatus($statusId);?>
                                </td>
                            </tr>
                            <?php if($statusUpdaterName != ''){?>

                            <tr>
                                <th>Status updated by</th>
                                <td><?php echo $statusUpdaterName; ?></td>
                            </tr>
                                <tr>
                                    <th>Status updated on</th>
                                    <td><?php echo date("F j, Y g:i:s A ", strtotime($statusedOn));?></td>
                                </tr>
                            <?php } ?>
                            <?php if($stateUpdaterName != '' && $stateId == '2'){ ?>
                                <tr>
                                    <th>State</th>
                                    <td>
                                    <?php echo $crud->coloriseCycle($stateId); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>State updated by</th>
                                    <td><?php echo $stateUpdaterName; ?></td>
                                </tr>
                                <tr>
                                    <th>State updated on</th>
                                    <td><?php echo date("F j, Y g:i:s A ", strtotime($statedOn));?></td>
                                </tr>
                            <?php }?>
                            <tr>
                                <th>Created by</th>
                                <td><?php echo $originalAuthor; ?></td>
                            </tr>
                            <tr>
                                <th>Created on</th>
                                <td><?php echo date("F j, Y g:i:s A ", strtotime($timeFirstPosted)); ?></td>
                            </tr>
                            <tr>
                                <th>Content updated by</th>
                                <td><?php echo $currentAuthor; ?></td>
                            </tr>
                            <tr>
                                <th>Content updated on</th>
                                <td><?php echo date("F j, Y g:i:s A ", strtotime($timeUpdated));?></td>
                            </tr>
                            <?php if($availabilityByName != '' && $availability == '2') {  ?>
                            <tr>
                                <th>Currently checked out by</th>
                                <td><?php echo $availabilityByName; ?></td>
                            </tr>
                            <tr>
                                <th>Checked out on </th>
                                <td><?php echo date("F j, Y g:i:s A ", strtotime($availabilityOn)); ?></td>
                            </tr>
                            <tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($remarkType != ''){ ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-10">
                                <b>Most Recent Remark</b>
                            </div>
                            <div class="col-lg-2">
                                <a class="btn btn-sm fa fa-eye" id="btnComfyView" data-toggle="modal" data-target="#modalComfyView" title="Comfy view"></a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body" style="max-height: 20rem; overflow-y: scroll;">
                        <b><?php echo $remarkedByName;?></b> on <i><?php echo date("F j, Y g:i:s A ", strtotime($remarkedOn));?></i><br>
                        <?php
                        if($remarkType == 'STATUSED') {?>
                            <?php echo $crud->coloriseStatus($statusId);?> status assigned to the document.<br>
                            <span class="label label-default">CHECKED IN</span> the document.
                        <?php }else if($remarkType == 'MOVED') { ?>
                            <span class="label label-primary">MOVED</span> the document to <strong><?php echo $stepName ;?></strong>.<br>
                            <span class="label label-default">CHECKED IN</span> the document.
                        <?php }else if($remarkType == 'CYCLED'){ ?>
                            <?php echo $crud->coloriseCycle($stateId);?> the document.<br>
                            <span class="label label-default">CHECKED IN</span> the document.
                        <?php }else if($remarkType == 'UPDATED' || $remarkType == 'CREATED') { ?>
                            <span class="label label-success"><?php echo $remarkType;?></span> the document.<br>
                            <span class="label label-default">CHECKED IN</span> the document.
                        <?php }else if($remarkType == 'STATUSED/MOVED') { ?>
                            <span class="label label-primary">MOVED</span> the document to <strong><?php echo $stepName ;?></strong>.<br>
                            <?php echo $crud->coloriseStatus($statusId);?> status assigned to the document.<br>
                            <span class="label label-default">CHECKED IN</span> the document.
                        <?php }
                        ?>
                        <br><br>
                        <div class="alert alert-info">
                            <i>"<?php echo $remarks;?>"</i>
                        </div>
                    </div>
                    <div id="modalComfyView" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <strong class="modal-title">Most Recent Remark</strong>
                                </div>
                                <div class="modal-body">
                                    <b><?php echo $remarkedByName;?></b> on <i><?php echo date("F j, Y g:i:s A ", strtotime($remarkedOn));?></i><br>
                                    <?php
                                    if($remarkType == 'STATUSED') {?>
                                        <?php echo $crud->coloriseStatus($statusId);?> status assigned to the document.<br>
                                        <span class="label label-default">CHECKED IN</span> the document.
                                    <?php }else if($remarkType == 'MOVED') { ?>
                                        <span class="label label-primary">MOVED</span> the document to <strong><?php echo $stepName ;?></strong>.<br>
                                        <span class="label label-default">CHECKED IN</span> the document.
                                    <?php }else if($remarkType == 'CYCLED'){ ?>
                                        <?php echo $crud->coloriseCycle($stateId);?> the document.<br>
                                        <span class="label label-default">CHECKED IN</span> the document.
                                    <?php }else if($remarkType == 'UPDATED' || $remarkType == 'CREATED') { ?>
                                        <span class="label label-success"><?php echo $remarkType;?></span> the document.<br>
                                        <span class="label label-default">CHECKED IN</span> the document.
                                    <?php }else if($remarkType == 'STATUSED/MOVED') { ?>
                                        <span class="label label-primary">MOVED</span> the document to <strong><?php echo $stepName ;?></strong>.<br>
                                        <?php echo $crud->coloriseStatus($statusId);?> status assigned to the document.<br>
                                        <span class="label label-default">CHECKED IN</span> the document.
                                    <?php }
                                    ?>
                                </div>
                                <div class="modal-body" style="max-height: 50rem; overflow-y: auto;">
                                    <div class="alert alert-info">
                                        <i>"<?php echo $remarks;?>"</i>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Document Actions</b>
                    </div>
                    <div class="panel-body">
                        <div class="btn-group btn-group-vertical" style="width: 100%;">
                            <?php if($availability == '1'){ ?>
                                <?php if($write=='2' || $route =='2' || $cycle == '2'){?>
                                    <form method="POST" action="">
                                        <button class="btn btn-primary" type="submit" name="btnLock" value="<?php echo $documentId;?>" style="text-align: left; width:100%;">Check Out for Processing</button>
                                    </form>
                                <?php }  ?>
                            <?php } ?>
                            <?php if($availability == '2'){ ?>
                                <?php if($route=='2') {
                                    $rows = $crud->getStepRoutes($currentStepId);
                                    if (!empty($rows)) {
                                        foreach ((array)$rows as $key => $row) {
                                            $btnClass = 'btn btn-primary';
                                            $btnIcon = '';
                                            if($row['assignStatus'] == 3){
                                                $btnClass = 'btn btn-success';
                                                $btnIcon = 'fa fa-thumbs-up';
                                            }else if($row['assignStatus'] == 4){
                                                $btnClass = 'btn btn-danger';
                                                $btnIcon = 'fa fa-thumbs-down';
                                            }
                                            ?>
                                            <button class="<?php echo $btnClass;?>" style="text-align: left; width: 100%" type="button" data-toggle="modal" data-target="#modalRoute<?php echo $row['routeId'];?>">
                                                <i class="<?php echo $btnIcon;?>"></i>
                                                <?php echo $row['routeName'];?>
                                            </button>
                                            <div id="modalRoute<?php echo $row['routeId'];?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                    <form method="POST" action="">
                                                        <input type="hidden" name="documentId" value="<?php echo $documentId; ?>">
                                                        <input type="hidden" name="assignStatusId" value="<?php echo $row['assignStatus'];?>">
                                                        <input type="hidden" name="nextStepId" value="<?php echo $row['nextStepId'];?>">
                                                        <input type="hidden" name="currentStatusId" value="<?php echo $statusId;?>">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><b>Confirm '<?php echo $row['routeName'];?>'?</b></h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <p>Please provide remarks first.</p>
                                                                    <textarea name="remarks" id="remarks" class="form-control" placeholder="Your remarks..." rows="10" required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="form-group">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <button class="btn btn-primary" type="submit" name="btnRoute">Confirm</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                } ?>
                                <?php if($write=='2') { ?>
                                    <button type="button" class="btn btn-primary" id="btnUpload" data-toggle="modal" data-target="#uploadModal" style="text-align: left; width: 100%;"><i class="fa fa-upload"></i> Upload New Version</button>
                                <?php } ?>
                                <?php if( $cycle=='2') {?>
                                    <?php if($stateId == 1) { ?>
                                        <button type="button" data-toggle="modal" data-target="#modalArchive" class="btn btn-warning" style="text-align: left; width: 100%;"><i class="fa fa-archive"></i> Archive</button>
                                        <div id="modalArchive" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <form method="POST" action="">
                                                        <div class="modal-header">
                                                            <strong>Confirm Archive?</strong>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Reason I'm archiving this document</label>
                                                                <textarea name="remarks" class="form-control" rows="10" required></textarea>
                                                            </div>

                                                            <div class="alert alert-warning">
                                                                <strong>Archiving this document will mean that it will not get published in any manual edition until it is restored.
                                                                    It will also remain read-only to other users except for those who have edit permissions in this current step.
                                                                    Are you sure you want to archive?</strong>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="documentId" value="<?php echo $documentId;?>">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" name="btnArchive" class="btn btn-primary">Yes, I'm sure</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }else if($stateId == 2){?>
                                        <button type="button" data-toggle="modal" data-target="#modalRestore" class="btn btn-info" style="text-align: left; width: 100%;"><i class="fa fa-archive"></i> Restore</button>
                                        <div id="modalRestore" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <form method="POST" action="">
                                                        <div class="modal-header">
                                                            <strong>Confirm Restore?</strong>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Reason I'm restoring this document</label>
                                                                <textarea name="remarks" class="form-control" rows="10" required></textarea>
                                                            </div>

                                                            <div class="alert alert-info">
                                                                <strong>Restoring this document will put it back into the the process.
                                                                    The original document permissions will be restored to the participants of the Manual Revisions process.
                                                                    Are you sure you want to restore?</strong>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="documentId" value="<?php echo $documentId;?>">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" name="btnRestore" class="btn btn-primary">Yes, I'm sure</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <?php if($write!='2' && $route!='2' && $cycle!='2'){ ?>
                                    <div class="alert alert-warning">
                                        The document has been checked out for processing by <strong><?php echo $availabilityByName;?></strong> on <i><?php echo date("F j, Y g:i:s A ", strtotime($availabilityOn));?></i> which means editing, archiving, and approval actions are restricted.
                                    </div>
                                <?php }else{ ?>
                                    <form method="POST" action="">
                                        <button class="btn btn-secondary" type="submit" name="btnUnlock" id="btnUnlock" value="<?php echo $documentId;?>" style="text-align: left; width: 100%;">Check In</button>
                                    </form>
                                <?php } ?>
                            <?php } ?>
                            <a href="<?php echo $filePath?>" download><button type="button" class="btn btn-default" style="text-align: left; width: 100%;"><i class="fa fa-download"></i> Download</button></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <form method="POST" id="comment_form">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="comment_name" id="comment_name" class="form-control" placeholder="Enter Name" value="<?php echo $userId; ?>"/>
                    </div>
                    <div class="form-group">
                        <textarea name="comment_content" id="comment_content" class="form-control" placeholder="Enter Comment" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <input type="hidden" name="comment_id" id="comment_id" value="0" />
                        <input type="hidden" name="documentId" id="documentId" value="<?php echo $documentId; ?>" />
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" name="submit" id="submit" class="btn btn-info" value="Submit"/>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>
    <div id="uploadModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form method="POST" id="documentUploadForm">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">Upload File</label>
                            <input type="file" class="form-control-file" id="file" name="file" required>
                        </div>
                        <div class="form-group">
                            <label for="documentTitle">Title</label>
                            <input type="text" name="versionTitle" id="versionTitle" class="form-control" placeholder="Title" required value="<?php echo $title; ?>">
                        </div>
                        <div class="form-group">
                            <label for=".radio"> Save New Version As </label>
                            <?php
                            $tempVerNo = explode(".",$versionNo);
                            if($tempVerNo){
                                $largeNum = $tempVerNo[0];
                                $smallNum = $tempVerNo[1];
                            }else{
                                $largeNum = $versionNo;
                                $smallNum = '0';
                            }

                            ?>
                            <div class="radio">
                                <label><input type="radio" name="newVersionNo" value="<?php echo $largeNum.'.'.(floatval($smallNum) + 1); ?>" checked><?php echo $largeNum.'.'.(floatval($smallNum) + 1); ?> (Minor Update)</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="newVersionNo" value="<?php echo (floatval($largeNum) + 1).'.0';?>"><?php echo (floatval($largeNum) + 1).'.0'; ?> (Major Update)</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remarks"> Please provide remarks before confirming </label>
                            <textarea name="remarks" id="remarks" class="form-control" placeholder="Your remarks..." rows="5" required></textarea>
                        </div>
                        <span id="err"></span>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                            <input type="hidden" name="documentId" value="<?php echo $documentId; ?>">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <input type="submit" name="btnSubmit" id="btnSubmit" class="btn btn-primary">
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
<script>

    $(document).ready(function(){

        let documentId = "<?php echo $documentId; ?>";

        $('[data-toggle="tooltip"]').tooltip();

        $("#documentUploadForm").on('submit', function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "EDMS_AJAX_UploadVersion.php",
                cache: false,
                processData: false,
                contentType: false,
                data: new FormData(this),
                success: function(response){
                    if(JSON.parse(response).success == '1'){ location.href = 'EDMS_ViewDocument.php?docId='+documentId+'&alert=DOC_UPDATE_SUCCESS'; }
                    else { $("#err").html('<div class="alert alert-info">'+JSON.parse(response).html+'</div>'); };
                },
                error: function(){
                    alert("Something went wrong :(");
                }
            });
            return false;
        });

        $('#comment_form').on('submit', function(event){
            event.preventDefault();
            $('#myModal').modal('toggle');
            var form_data = $(this).serialize();
            $.ajax({
                url:"EDMS_AJAX_AddEditComment.php",
                method:"POST",
                data:form_data,
                dataType:"JSON",
                success:function(data)
                {
                    if(data.error != '')
                    {
                        $('#comment_form')[0].reset();
                        $('#comment_message').html(data.error);
                        $('#comment_id').val('0');
                        load_comment(data.documentId, data.versionId);
                    }
                }
            });
        });

        $(document).on('click', '.reply', function(){
            var comment_id = $(this).attr("id");
            $('#comment_id').val(comment_id);
            $('#comment_name').focus();
        });

        setInterval(function() {
            load_comment(documentId);
        }, 1000);

        table = $('#tblHistory').DataTable( {
            bLengthChange: false,
            pageLength: 10,
            bSort:true,
            search: {regex: true},
            aaSorting: [],
            initComplete: function () {

                var columnVer = this.api().column(1);
                var selectVer = $('#selectedVersion').on( 'change', function () {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                    columnVer.search( val ? '^'+val+'$' : '', true, false ).draw();
                } );
                columnVer.data().unique().sort().each( function ( d, j ) {
                    selectVer.append( '<option value="'+d+'">'+d+'</option>' )
                } );

                var columnUser = this.api().column(2);
                var selectUser = $('#selectedUser').on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        columnUser.search( val ? '^'+val+'$' : '', true, false ).draw();
                    } );
                columnUser.data().unique().sort().each( function ( d, j ) {
                    selectUser.append( '<option value="'+d+'">'+d+'</option>' )
                } );

                var columnAction = this.api().column(3);
                var selectAction = $('#selectedAction').on( 'change', function () {
                    columnAction.search($('#selectedAction').val(), true, false).draw();
                } );

            }
        });

        $(".dataTables_filter").hide();
        $('#searchField').keyup(function(){
            table.search($('#searchField').val()).draw();
        });


        // setInterval(function(){
        //     table.ajax.reload();
        // },1000)


    });


    function load_comment(documentId)
    {
        $.ajax({
            url:"EDMS_AJAX_FetchEditComments.php",
            method:"POST",
            data:{documentId: documentId},
            success:function(data)
            {
                $('#display_comment').html(data);
            }
        })
    }

</script>

<?php include 'GLOBAL_FOOTER.php';?>