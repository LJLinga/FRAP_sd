<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
//include('GLOBAL_CMS_ADMIN_CHECKING.php');

$edmsRole= $_SESSION['EDMS_ROLE'];
$userId = $_SESSION['idnum'];
//Buttons

$revisions = 'closed';

$query = "SELECT r.id, r.revisionsOpened FROM revisions r WHERE r.statusId = 2 ORDER BY r.id DESC LIMIT 1;";
$rows = $crud->getData($query);
if(!empty($rows)){
    $revisions = 'open';
    foreach ((array) $rows as $key => $row){
        $revisionsOpened = $row['revisionsOpened'];
        $revisionsId = $row['id'];
    }
}else{
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_ManualRevisions.php");
}


if(isset($_POST['btnFinish'])){
    $sectionId = $_POST['section_id'];
    $title = $crud->escape_string($_POST['section_title']);
    $sectionNo = $crud->escape_string($_POST['section_number']);
    $content = $crud->escape_string($_POST['section_content']);
    $crud->execute("UPDATE sections SET title = '$title', sectionNo = '$sectionNo', content = '$content', availabilityId='2', lockedById=NULL WHERE id = '$sectionId'");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_ViewSection.php?secId=".$sectionId);
}

if(isset($_GET['secId'])){
    $sectionId = $_GET['secId'];

    $rows = $crud->getData("SELECT s.*, st.stepNo, st.stepName FROM sections s 
                                    JOIN steps st ON s.stepId = st.id
                                    WHERE s.id = '$sectionId';");
    if(!empty($rows)){
        foreach((array) $rows as $key => $row){
            $authorId = $row['authorId'];
            $authorName = $crud->getUserName($row['authorId']);
            $lastUpdated = $row['lastUpdated'];

            $firstAuthorId = $row['firstAuthorId'];
            $firstAuthorName = $crud->getUserName($row['firstAuthorId']);
            $timeCreated = $row['timeCreated'];

            $currentStepId = $row['stepId'];
            $stepName = 'Step '.$row['stepNo'].': '.$row['stepName'];
            $steppedById = $row['steppedById'];
            $steppedByName = $crud->getUserName($steppedById);
            $steppedOn = $row['steppedOn'];

            $statusId = $row['statusId'];
            $statusName = $crud->assignStatusString($statusId);
            $statusedById = $row['statusedById'];
            $statusedByName = $crud->getUserName($statusedById);
            $statusedOn = $row['statusedOn'];

            $lifecycleId = $row['lifecycleId'];
            $lifecycleName = $crud->lifecycleString($lifecycleId);
            $lifecycledById = $row['lifecycledById'];
            $lifecycledByName = $crud->getUserName($lifecycledById);
            $lifecycledOn = $row['lifecycledOn'];

            $availability = $row['availabilityId'];
            $availabilityName = $crud->availabilityString($availability);
            $availabilityById = $row['availabilityById'];
            $availabilityByName = $crud->getUserName($availabilityById);
            $availabilityOn = $row['availabilityOn'];

            $remarkType = $row['remark_action_type'];
            $remarkedOn = $row['remark_timestamp'];
            $remarkedById = $row['remark_user_id'];
            $remarkedByName = $crud->getUserName($remarkedById);
            $remarks = $row['remarks'];

            $sectionNo = $row['sectionNo'];
            $title = $row['title'];
            $content = $row['content'];
            $versionNo = $row['versionNo'];
        }

        $rows = $crud->getStepUserPermissions($currentStepId, $firstAuthorId, $userId);
        if(!empty($rows)){
            foreach((array) $rows AS $key => $row){
                $read = $row['read'];
                $write = $row['write'];
                $route = $row['route'];
                $comment = $row['comment'];
            }
        }else{
            header("Location:".$crud->redirectToPreviousWithAlert("DOC_NO_PERMISSIONS"));
        }

        if($availability == '2'){
            $route = '1';
            if($availabilityById == $userId) $write = '2'; //Display FORM inputs instead of the text preview. Lock add reference buttons.
            else $write = '1';
        }
    }else{
        header("Location:".$crud->redirectToPreviousWithAlert("DOC_NOT_LOAD"));
    }

}else{
    header("Location:".$crud->redirectToPreviousWithAlert("DOC_NO_PERMISSIONS"));
}

if(isset($_POST['btnUnlock'])){
    $sectionId= $_POST['btnUnlock'];
    $crud->execute("UPDATE sections SET availabilityId='1' WHERE id='$sectionId'");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_EditSection.php?secId=".$sectionId);
}

if(isset($_POST['btnLock'])){
    $sectionId = $_POST['btnLock'];
    $rows = $crud->getData("SELECT availabilityId FROM sections WHERE id = '$sectionId'");
    foreach((array) $rows as $key => $row){
        $availability = $row['availabilityId'];
    }
    if($availability == '1'){
        $userId = $_POST['userId'];
        $crud->execute("UPDATE sections SET availabilityId='2', availabilityById='$userId' WHERE id='$sectionId'");
        header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_EditSection.php?secId=".$sectionId);
    }else{
        header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_EditSection.php?secId=".$sectionId);
    }
}

if(isset($_POST['btnPrint'])){
    //Print logic
}

if(isset($_POST['btnRoute'])){
    $sectionId = $_POST['sectionId'];
    $nextStepId = $_POST['nextStepId'];
    $statusId = $_POST['assignStatusId'];
    $currentStatusId = $_POST['currentStatusId'];
    $remarks = $_POST['remarks'];
    $rows = $crud->getData("SELECT availabilityId FROM sections WHERE id = '$sectionId'");
    foreach((array) $rows as $key => $row){
        $availability = $row['availabilityId'];
    }
    if($statusId >= 5){
        $statusId = $currentStatusId;
    }
    if($availability == '1'){
        if($statusId != $currentStatusId){
            $crud->execute("UPDATE sections SET statusId = '$statusId', stepId='$nextStepId', statusedById='$userId', steppedById='$userId', remarks = '$remarks' WHERE id='$sectionId'");
        }else if($statusId == $currentStatusId){
            $crud->execute("UPDATE sections SET stepId='$nextStepId', steppedById='$userId', remarks = '$remarks' WHERE id='$sectionId'");
        }
    }
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_EditSection.php?secId=".$sectionId);
}

if(isset($_POST['btnSave'])){
    $title = $crud->escape_string($_POST['sectionTitle']);
    $sectionNo = $crud->escape_string($_POST['sectionNo']);
    $content = $crud->escape_string($_POST['sectionContent']);
    $remarks = $crud->escape_string($_POST['remarks']);
    $versionNo = $_POST['newVersionNo'];

    $crud->execute("UPDATE sections SET versionNo='$versionNo', title='$title', sectionNo='$sectionNo', content='$content', authorId='$userId', availabilityId='1', availabilityById='$userId', remarks='$remarks' WHERE id='$sectionId';");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_EditSection.php?secId=".$sectionId);
}

if(isset($_POST['btnArchive'])){
    //$crud->execute("UPDATE documents SET availabilityId='2', availabilityById='$userId' WHERE documentId='$documentId'");
}

if(isset($_GET['alert'])){
    $alertType = $_GET['alert'];
    if($alertType == 'DOC_LOCK_FAIL') { $alertColor = 'danger'; $alertMessage = "Unable to check the document out. <strong>".$availabilityByName."</strong> has locked it first."; }
    else if($alertType == 'DOC_LOCK_SUCCESS'){ $alertColor = 'success'; $alertMessage = 'You have successfully checked the document out!'; }
}

$page_title = 'Faculty Manual - Edit Section';

include 'GLOBAL_ALERTS.php';
include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';
?>
    <div id="content-wrapper">
        <div class="container-fluid">
            <!--Insert success page-->

                <div class="row" style="margin-top: 2rem;">
                    <div class="column col-lg-8">
                        <?php if($write=='2' && $availability=='2'){ ?>
                        <div class="panel panel-default" style="">
                            <div class="panel-body">
                                <form id="form" name="form" method="POST" action="">
                                    <input type="hidden" name="sectionId" value="<?php echo $sectionId;?>">
                                    <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                                <div class="form-group">
                                    <label for="sectionNo">Section Marker</label>
                                    <input id="sectionNo" name="sectionNo" type="text" class="form-control input-md" value="<?php echo $sectionNo;?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="sectionTitle">Title</label>
                                    <input id="sectionTitle" name="sectionTitle" type="text" class="form-control input-md" value="<?php echo $title;?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="sectionContent">Content</label>
                                    <textarea name="sectionContent" class="form-control" rows="20" id="sectionContent"><?php echo $content;?></textarea>
                                </div>
                                <div id="modalConfirmSave" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for=".radio"> Save New Version As </label>
                                                    <div class="radio">
                                                        <label><input type="radio" name="newVersionNo" value="<?php echo floatval($versionNo) + 0.1; ?>" checked><?php echo floatval($versionNo) + 0.1; ?> (Minor Update)</label>
                                                    </div>
                                                    <div class="radio">
                                                        <label><input type="radio" name="newVersionNo" value="<?php echo ceil(floatval($versionNo) + 0.1); ?>"><?php echo ceil(floatval($versionNo) + 0.1); ?> (Major Update)</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="remarks"> Please provide remarks before confirming </label>
                                                    <textarea name="remarks" id="remarks" class="form-control" placeholder="Your remarks..." rows="5" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                    <input type="submit" name="btnSave" id="btnSave" class="btn btn-primary">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                            <div class="panel-footer">
                                <form method="POST" action="">
                                    <button type="button" class="btn btn-primary" id="btnSave" data-toggle="modal" data-target="#modalConfirmSave">Finish Editing</button>
                                    <button type="submit" class="btn btn-warning" name="btnUnlock" id="btnUnlock" value="<?php echo $sectionId;?>">Cancel Editing</button>
                                </form>
                            </div>

                        </div>
                        <?php }else{ ?>
                            <div class="panel panel-default">
                                <div class="panel-body" style="overflow-y: auto;">
                                    <h4><strong>Section <?php echo $sectionNo.' - '.$title; ?></strong></h4>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-body" style="height: 50rem; overflow-y: auto;">
                                    <p style="text-align: justify;"><?php echo $content; ?></p>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <b class="panel-title">Section History</b>
                                        <button class="btn btn-default btn-sm fa fa-expand" type="button" data-toggle="collapse" data-target="#collapseHistory" style="position: absolute; top: 0px; right: 15px;">
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body collapse in" id="collapseHistory">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <div class="form-inline">
                                            <label for="sel1">Ver. No. </label>
                                            <select class="form-control" id="selectedVersion" name="selectedVersion">
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
                                                <option value="" selected>All</option>
                                                <option value="created">CREATED</option>
                                                <option value="updated">UPDATED</option>
                                                <option value="moved">MOVED</option>
                                                <option value="checked out">CHECKED OUT</option>
                                                <option value="checked in">CHECKED IN</option>
                                                <option value="draft">DRAFT</option>
                                                <option value="pending">PENDING</option>
                                                <option value="approved">APPROVED</option>
                                                <option value="rejected">REJECTED</option>
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
                                    <th>Timestamp </th>
                                    <th>Ver. No.</th>
                                    <th>User </th>
                                    <th>Action </th>
                                    <th></th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $query = "SELECT v.*, st.stepName, st.stepNo FROM section_versions v
                                            JOIN steps st ON v.stepId = st.id
                                            WHERE v.sectionId = $sectionId
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
                                                    <?php echo $crud->getUserName($row['audit_user_id']);?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if($row['audit_action_type'] == 'LOCKED') { $labelCol = 'default';?>
                                                        <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->availabilityString($row['availabilityId']);?></span> the section.
                                                    <?php }else if($row['audit_action_type'] == 'STATUSED') {
                                                        if($row['statusId'] ==  1) { $labelCol = 'info'; }
                                                        else if($row['statusId'] ==  2) { $labelCol = 'primary'; }
                                                        else if($row['statusId'] ==  3) { $labelCol = 'success'; }
                                                        else if($row['statusId'] ==  4) { $labelCol = 'danger'; } ?>
                                                        <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->assignStatusString($row['statusId']);?></span> status assigned to the section.
                                                    <?php }else if($row['audit_action_type'] == 'MOVED') {
                                                        $labelCol = 'primary'?>
                                                        <span class="label label-<?php echo $labelCol;?>">MOVED</span> the section to <strong>Step <?php echo $row['stepNo'];?>: <?php echo $row['stepName'];?></strong>.
                                                    <?php }else if($row['audit_action_type'] == 'CYCLED'){
                                                        if($row['lifecycleStateId'] ==  1) $labelCol = 'info';
                                                        if($row['lifecycleStateId'] ==  2) $labelCol = 'warning';?>
                                                        <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->lifecycleString($row['lifecycleStateId']);?></span> the section.
                                                    <?php }else if($row['audit_action_type'] == 'UPDATED' || $row['audit_action_type'] == 'CREATED') {
                                                        $labelCol = 'success'?>
                                                        <span class="label label-<?php echo $labelCol;?>"><?php echo $row['audit_action_type'];?></span> the section.<br>
                                                        <span class="label label-default">CHECKED IN</span> the section.
                                                    <?php }else if($row['audit_action_type'] == 'STATUSED/MOVED') {
                                                        $labelCol = 'primary'?>
                                                        <span class="label label-<?php echo $labelCol;?>">MOVED</span> the section to <strong>Step <?php echo $row['stepNo'];?>: <?php echo $row['stepName'];?></strong>.<br>
                                                        <?php if($row['statusId'] ==  1) { $labelCol = 'info'; }
                                                        else if($row['statusId'] ==  2) { $labelCol = 'primary'; }
                                                        else if($row['statusId'] ==  3) { $labelCol = 'success'; }
                                                        else if($row['statusId'] ==  4) { $labelCol = 'danger'; }
                                                        ?>
                                                        <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->assignStatusString($row['statusId']);?></span> status assigned to the section.
                                                    <?php }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if($row['audit_action_type'] != 'LOCKED' && $row['audit_action_type'] != 'CREATED'){?>
                                                        <button type="button" class="btn btn-info btn-sm fa fa-eye" data-toggle="modal" data-target="#modalVersionPreview<?php echo $row['versionId'];?>"></button>
                                                        <button type="button" class="btn btn-sm btn-secondary fa fa-print"></button>
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
                                                                                        <strong><?php echo $crud->getUserName($row['audit_user_id']);?></strong> on
                                                                                        <i><?php echo date("F j, Y g:i:s A ", strtotime($row['audit_timestamp']));?></i><br>
                                                                                        <?php
                                                                                        if($row['audit_action_type'] == 'LOCKED') { $labelCol = 'default';?>
                                                                                            <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->availabilityString($row['availabilityId']);?></span> the section.
                                                                                        <?php }else if($row['audit_action_type'] == 'STATUSED') {
                                                                                            if($row['statusId'] ==  1) { $labelCol = 'info'; }
                                                                                            else if($row['statusId'] ==  2) { $labelCol = 'primary'; }
                                                                                            else if($row['statusId'] ==  3) { $labelCol = 'success'; }
                                                                                            else if($row['statusId'] ==  4) { $labelCol = 'danger'; } ?>
                                                                                            <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->assignStatusString($row['statusId']);?></span> status assigned to the section.
                                                                                        <?php }else if($row['audit_action_type'] == 'MOVED') {
                                                                                            $labelCol = 'primary'?>
                                                                                            <span class="label label-<?php echo $labelCol;?>">MOVED</span> the section to <strong>Step <?php echo $row['stepNo'];?>: <?php echo $row['stepName'];?></strong>.
                                                                                        <?php }else if($row['audit_action_type'] == 'CYCLED'){
                                                                                            if($row['lifecycleStateId'] ==  1) $labelCol = 'info';
                                                                                            if($row['lifecycleStateId'] ==  2) $labelCol = 'warning';?>
                                                                                            <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->lifecycleString($row['lifecycleStateId']);?></span> the section.
                                                                                        <?php }else if($row['audit_action_type'] == 'UPDATED' || $row['audit_action_type'] == 'CREATED') {
                                                                                            $labelCol = 'success'?>
                                                                                            <span class="label label-<?php echo $labelCol;?>"><?php echo $row['audit_action_type'];?></span> the section.<br>
                                                                                            <span class="label label-default">CHECKED IN</span> the section.
                                                                                        <?php }else if($row['audit_action_type'] == 'STATUSED/MOVED') {
                                                                                            $labelCol = 'primary'?>
                                                                                            <span class="label label-<?php echo $labelCol;?>">MOVED</span> the section to <strong>Step <?php echo $row['stepNo'];?>: <?php echo $row['stepName'];?></strong>.<br>
                                                                                            <?php if($row['statusId'] ==  1) { $labelCol = 'info'; }
                                                                                            else if($row['statusId'] ==  2) { $labelCol = 'primary'; }
                                                                                            else if($row['statusId'] ==  3) { $labelCol = 'success'; }
                                                                                            else if($row['statusId'] ==  4) { $labelCol = 'danger'; }
                                                                                            ?>
                                                                                            <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->assignStatusString($row['statusId']);?></span> status assigned to the section.
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
                                                                                            </tr
                                                                                            <tr>
                                                                                                <th>Stage</th>
                                                                                                <td><?php echo $row['stepName']; ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Status</th>
                                                                                                <?php

                                                                                                if($statusId == 3){
                                                                                                    $labelCol = 'success';
                                                                                                }else if($statusId == 4){
                                                                                                    $labelCol = 'danger';
                                                                                                }else if($statusId == 2){
                                                                                                    $labelCol = 'primary';
                                                                                                }else if($statusId == 1){
                                                                                                    $labelCol = 'info';
                                                                                                }
                                                                                                ?>
                                                                                                <td>
                                                                                            <span class="label label-<?php echo $labelCol;?>">
                                                                                                <?php echo $crud->assignStatusString($row['statusId']);?>
                                                                                            </span>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php if($row['statusedById'] != ''){?>

                                                                                                <tr>
                                                                                                    <th>Status updated by</th>
                                                                                                    <td><?php echo $crud->getUserName($row['statusedById']);?></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th>Status updated on</th>
                                                                                                    <td><?php echo date("F j, Y g:i:s A ", strtotime($row['statusedOn']));?></td>
                                                                                                </tr>
                                                                                            <?php } ?>
                                                                                            <?php if($row['lifecycledById'] != ''){ ?>
                                                                                                <tr>
                                                                                                    <th>State</th>
                                                                                                    <?php

                                                                                                    if($row['lifecycleId'] == 1){
                                                                                                        $labelCol = 'success';
                                                                                                    }else if($statusId == 2){
                                                                                                        $labelCol = 'warning';
                                                                                                    }                                    ?>
                                                                                                    <td>
                                                                                                <span class="label label-<?php echo $labelCol;?>">
                                                                                                    <?php echo $crud->lifecycleString($row['lifecycleId']);?>
                                                                                                </span>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th>State updated by</th>
                                                                                                    <td><?php echo $row['lifecycledByName'] ?></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th>State updated on</th>
                                                                                                    <td><?php echo date("F j, Y g:i:s A ", strtotime($row['lifecycledOn']));?></td>
                                                                                                </tr>
                                                                                            <?php }?>
                                                                                            <tr>
                                                                                                <th>Created by</th>
                                                                                                <td><?php echo $firstAuthorName; ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Created on</th>
                                                                                                <td><?php echo date("F j, Y g:i:s A ", strtotime($timeCreated)); ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Content updated by</th>
                                                                                                <td><?php echo $crud->getUserName($row['authorId']); ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <th>Content updated on</th>
                                                                                                <td><?php echo date("F j, Y g:i:s A ", strtotime($row['lastUpdated']));?></td>
                                                                                            </tr>
                                                                                            <?php if($row['availabilityById'] != '' && $availability == '2') {  ?>
                                                                                            <tr>
                                                                                                <th>Currently checked out by</th>
                                                                                                <td><?php echo $crud->getUserName($row['availabilityById']); ?></td>
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
                                                                            <div class="col-lg-6" style="max-height: 60rem; overflow-y: auto;">
                                                                                <div class="alert alert-info">
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
                                <b>Section Details</b>
                            </div>
                            <div class="panel-body">
                                <table class="table table-responsive table-striped table-condensed table-sm">
                                    <tbody>
                                    <tr>
                                        <th>Section No</th>
                                        <td><?php echo $sectionNo; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Title</th>
                                        <td><?php echo $title; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Version No.</th>
                                        <td><?php echo $versionNo; ?></td>
                                    </tr
                                    <tr>
                                        <th>Stage</th>
                                        <td><?php echo $stepName; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <?php

                                        if($statusId == 3){
                                            $labelCol = 'success';
                                        }else if($statusId == 4){
                                            $labelCol = 'danger';
                                        }else if($statusId == 2){
                                            $labelCol = 'primary';
                                        }else if($statusId == 1){
                                            $labelCol = 'info';
                                        }
                                        ?>
                                        <td>
                                    <span class="label label-<?php echo $labelCol;?>">
                                        <?php echo $statusName;?>
                                    </span>
                                        </td>
                                    </tr>
                                    <?php if($statusedById != ''){?>

                                        <tr>
                                            <th>Status updated by</th>
                                            <td><?php echo $statusedByName; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status updated on</th>
                                            <td><?php echo date("F j, Y g:i:s A ", strtotime($statusedOn));?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if($lifecycledById != '' && $lifecycleId == '2'){ ?>
                                        <tr>
                                            <th>State</th>
                                            <?php

                                            if($statusId == 1){
                                                $labelCol = 'success';
                                            }else if($statusId == 2){
                                                $labelCol = 'warning';
                                            }                                    ?>
                                            <td>
                                    <span class="label label-<?php echo $labelCol;?>">
                                        <?php echo $lifecycleName;?>
                                    </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>State updated by</th>
                                            <td><?php echo $lifecycledByName; ?></td>
                                        </tr>
                                        <tr>
                                            <th>State updated on</th>
                                            <td><?php echo date("F j, Y g:i:s A ", strtotime($lifecycledOn));?></td>
                                        </tr>
                                    <?php }?>
                                    <tr>
                                        <th>Created by</th>
                                        <td><?php echo $firstAuthorName; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Created on</th>
                                        <td><?php echo date("F j, Y g:i:s A ", strtotime($timeCreated)); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Content updated by</th>
                                        <td><?php echo $authorName; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Content updated on</th>
                                        <td><?php echo date("F j, Y g:i:s A ", strtotime($lastUpdated));?></td>
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

                        <?php if($remarkType!= 'LOCKED' && $remarkType != 'CREATED' && $remarkType != ''){ ?>
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
                                if($remarkType == 'STATUSED') {
                                    if($statusId ==  1) { $labelCol = 'info'; }
                                    else if($statusId ==  2) { $labelCol = 'primary'; }
                                    else if($statusId  ==  3) { $labelCol = 'success'; }
                                    else if($statusId  ==  4) { $labelCol = 'danger'; } ?>
                                    <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->assignStatusString($statusId);?></span> status assigned to the section.
                                <?php }else if($remarkType == 'MOVED') {
                                    $labelCol = 'primary'?>
                                    <span class="label label-<?php echo $labelCol;?>">MOVED</span> the section to <strong><?php echo $stepName ;?></strong>.
                                <?php }else if($remarkType == 'CYCLED'){
                                    if($row['lifecycleStateId'] ==  1) $labelCol = 'info';
                                    if($row['lifecycleStateId'] ==  2) $labelCol = 'warning';?>
                                    <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->lifecycleString($lifecycleId);?></span> the section.
                                <?php }else if($remarkType == 'UPDATED' || $remarkType == 'CREATED') {
                                    $labelCol = 'success'?>
                                    <span class="label label-<?php echo $labelCol;?>"><?php echo $remarkType;?></span> the section.<br>
                                    <span class="label label-default">CHECKED IN</span> the section.
                                <?php }else if($remarkType == 'STATUSED/MOVED') {
                                    $labelCol = 'primary'?>
                                    <span class="label label-<?php echo $labelCol;?>">MOVED</span> the section to <strong><?php echo $stepName ;?></strong>.<br>
                                    <?php if($statusId  ==  1) { $labelCol = 'info'; }
                                    else if($statusId  ==  2) { $labelCol = 'primary'; }
                                    else if($statusId  ==  3) { $labelCol = 'success'; }
                                    else if($statusId  ==  4) { $labelCol = 'danger'; }
                                    ?>
                                    <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->assignStatusString($statusId);?></span> status assigned to the section.
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
                                            if($remarkType == 'STATUSED') {
                                                if($statusId ==  1) { $labelCol = 'info'; }
                                                else if($statusId ==  2) { $labelCol = 'primary'; }
                                                else if($statusId  ==  3) { $labelCol = 'success'; }
                                                else if($statusId  ==  4) { $labelCol = 'danger'; } ?>
                                                <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->assignStatusString($statusId);?></span> status assigned to the section.
                                            <?php }else if($remarkType == 'MOVED') {
                                                $labelCol = 'primary'?>
                                                <span class="label label-<?php echo $labelCol;?>">MOVED</span> the section to <strong><?php echo $stepName ;?></strong>.
                                            <?php }else if($remarkType == 'CYCLED'){
                                                if($row['lifecycleStateId'] ==  1) $labelCol = 'info';
                                                if($row['lifecycleStateId'] ==  2) $labelCol = 'warning';?>
                                                <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->lifecycleString($lifecycleId);?></span> the section.
                                            <?php }else if($remarkType == 'UPDATED' || $remarkType == 'CREATED') {
                                                $labelCol = 'success'?>
                                                <span class="label label-<?php echo $labelCol;?>"><?php echo $remarkType;?></span> the section.<br>
                                                <span class="label label-default">CHECKED IN</span> the section.
                                            <?php }else if($remarkType == 'STATUSED/MOVED') {
                                                $labelCol = 'primary'?>
                                                <span class="label label-<?php echo $labelCol;?>">MOVED</span> the section to <strong><?php echo $stepName ;?></strong>.<br>
                                                <?php if($statusId  ==  1) { $labelCol = 'info'; }
                                                else if($statusId  ==  2) { $labelCol = 'primary'; }
                                                else if($statusId  ==  3) { $labelCol = 'success'; }
                                                else if($statusId  ==  4) { $labelCol = 'danger'; }
                                                ?>
                                                <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->assignStatusString($statusId);?></span> status assigned to the section.
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

                        <?php if($availability == '1'){ ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <b>Section Actions</b>
                            </div>
                            <div class="panel-body">
                                <?php if($route=='2') {
                                $rows = $crud->getStepRoutes($currentStepId);
                                if (!empty($rows)) {
                                    foreach ((array)$rows as $key => $row) {
                                        $btnClass = 'btn btn-primary';
                                        if($row['assignStatus'] == 3){
                                        $btnClass = 'btn btn-success';
                                        }else if($row['assignStatus'] == 4){
                                        $btnClass = 'btn btn-danger';
                                        }

                                ?>
                                <button class="<?php echo $btnClass;?>" style="text-align: left; width: 100%" type="button" data-toggle="modal" data-target="#modalRoute<?php echo $row['routeId'];?>">
                                    <?php echo $row['routeName'];?>
                                </button>
                                <div id="modalRoute<?php echo $row['routeId'];?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <form method="POST" action="">
                                            <input type="hidden" name="sectionId" value="<?php echo $sectionId; ?>">
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
                            }

                            if( $write=='2'){?>
                                <form method="POST" action="">
                                    <input type="hidden" name="userId" value="<?php echo $userId;?>">
                                    <button class="btn btn-default" type="submit" name="btnLock" value="<?php echo $sectionId;?>" style="text-align: left; width:100%;">Check Out and Edit</button>
                                    <button type="button" class="btn btn-default" style="text-align: left; width: 100%;">Print</button>
                                    <button type="button" class="btn btn-warning" style="text-align: left; width: 100%;">Archive</button>
                                </form>
                            <?php } ?>

                            </div>
                        </div>
                        <?php } ?>
                        <?php if($availability == '2' && ($availabilityById != $userId)) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <b>Section Actions</b>
                                </div>
                                <div class="panel-body">
                                    <div class="alert alert-warning">
                                        The section has been checked out by <strong><?php echo $availabilityByName;?></strong> on <i><?php echo date("F j, Y g:i:s A ", strtotime($availabilityOn));?></i> which means most section actions are restricted.
                                    </div>
                                    <button type="button" class="btn btn-default" style="text-align: left; width: 100%;">Print</button>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <b>References</b>
                                <button id="btnRefModal" type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalRED"><i class="fa fa-fw fa-link"></i>Add</button>
                            </div>
                            <div class="panel-body">
                                <table class="table table-responsive table-striped table-sm">
                                <thead>
                                <tr>
                                    <th> Title </th>
                                    <th> Ver No </th>
                                    <th> Type </th>
                                    <th> Submitted by </th>
                                    <th> Submitted on </th>
                                    <th> Approved by </th>
                                    <th> Approved on </th>
                                    <th> Referenced by </th>
                                    <th> Referenced on </th>
                                    <th> Action </th>
                                </tr>
                                </thead>
                                </table>
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
                            <input type="hidden" name="section_id" id="version_id" value="<?php echo $sectionId; ?>" />
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" name="submit" id="submit" class="btn btn-info" value="Submit"/>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>

    <div id="modalRED" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    <strong class="modal-title">Reference Document</strong>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-sm" id="tblReferences">
                        <thead>
                        <tr>
                            <th> Title </th>
                            <th> Ver No </th>
                            <th> Type </th>
                            <th> Submitted by </th>
                            <th> Submitted on </th>
                            <th> Approved by </th>
                            <th> Approved on </th>
                            <th> Add </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <script>
        $(document).ready( function(){


            let tableHistory = $('#tblHistory').DataTable( {
                bLengthChange: false,
                pageLength: 10,
                bSort: false,
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
                        columnAction.search($('#selectedAction').val()).draw();
                    } );

                    var columnAll = this.api();
                    $('#searchField').keyup(function(){
                        columnAll.search($('#searchField').val()).draw();
                    });

                }
            });



            let tableAddRef = $('#tblAddReferences').DataTable( {
                bSort: true,
                bLengthChange: false,
                destroy: true,
                pageLength: 10,
                scrollX,
                "ajax": {
                    "url":"EDMS_AJAX_FetchDocuments.php",
                    "type":"POST",
                    "dataSrc": '',
                    "data": {
                        requestType: 'MANUAL_REFERENCES',
                        sectionId: "<?php echo $sectionId;?>"
                    },
                },
                columns: [
                    { data: "title" },
                    { data: "vers"},
                    { data: "type" },
                    { data: "submitted_by"},
                    { data: "submitted_on"},
                    { data: "approved_by"},
                    { data: "approved_on"},
                    { data: "actions"}
                ],
                initComplete: function () {


                }
            });



            $('#btnRefModal').on('click', function(){
                reloadDataTable(tableAddRef);
            });

            $('.add_doc_ref').on('click', function(){
                reloadDataTable(tableAddRef);
                reloadDataTable(tableRef);
            });

            function reloadDataTable(table){
                table.ajax.reload(null,false);
            }

            $(".dataTables_filter").hide();
        });



        $('#comment_form').on('submit', function(event){
            event.preventDefault();
            $('#myModal').modal('toggle');
            var form_data = $(this).serialize();
            $.ajax({
                url:"EDMS_AJAX_AddSectionComment.php",
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
                        load_comment('<?php echo $sectionId; ?>');
                    }
                }
            })
        });

        setInterval(function() {
            load_comment('<?php echo $sectionId; ?>');
        }, 5000);

        function load_comment(sectionId)
        {
            $.ajax({
                url:"EDMS_AJAX_FetchSectionComments.php",
                method:"POST",
                data:{sectionId: sectionId},
                success:function(data)
                {
                    $('#display_comment').html(data);
                }
            })
        }

        $(document).on('click', '.reply', function(){
            var comment_id = $(this).attr("id");
            $('#comment_id').val(comment_id);
            $('#comment_name').focus();
        });

    </script>
<?php include 'GLOBAL_FOOTER.php' ?>