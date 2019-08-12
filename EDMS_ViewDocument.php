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
                d.firstAuthorId, d.timeCreated, d.availabilityId, d.versionNo, d.authorId, d.title, d.filePath, d.lastUpdated, d.typeId, d.old_versionNo,
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
            $docTypeNum = $row['typeId'];
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
            $old_versionNo = $row['old_versionNo'];
        }

        $rows = $crud->getStep($currentStepId);
        if(!empty($rows)){
            $groupId = $rows[0]['groupId'];
            $boolInGroup = $crud->isUserInGroup($userId, $groupId);
            if($boolInGroup){
                $write = $rows[0]['gwrite'];
                $route = $rows[0]['groute'];
                $cycle = $rows[0]['gcycle'];
                $rows = $crud->getGroup($groupId);
                if(!empty($rows)){
                    $groupDesc = $rows[0]['groupDesc'];
                    $groupName = $rows[0]['groupName'];
                }
            }else if($userId == $firstAuthorId){
                $write = $rows[0]['write'];
                $route = $rows[0]['route'];
                $cycle = $rows[0]['cycle'];
            }else{
                $boolInWorkflow = $crud->isUserInWorkflow($userId, $currentStepId);
                if($boolInWorkflow){
                    $write = '1';
                    $route = '1';
                    $cycle = '1';
                }else{
                    header("Location:".$crud->redirectToPreviousWithAlert("DOC_NO_PERMISSIONS"));
                }
            }
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


/**
 * This is for my parts christian, just dont touch these, as these will be needed to load data. I will claim this bit of your code.
 * i used the non crud version as I am only getting one row and it is impractical to for each if you know you are only getting one
 * row of code.
 *
 * I have tested these and these do not affect you. Do not worry brother, we got each others backs!
 */
    if($docTypeNum == 2){
        $loanDetailsQuery = "SELECT l.LOAN_ID, l.MEMBER_ID ,l.AMOUNT,l.PAYMENT_TERMS, l.PER_PAYMENT,l.DATE_APPLIED, l.DATE_APPROVED, ls.STATUS as 'LOAN_STATUS'
                                                                    FROM ref_document_loans rdl
                                                                    JOIN loans l 
                                                                    on rdl.LOAN_ID = l.LOAN_ID
                                                                    JOIN loan_status ls 
                                                                    on l.LOAN_STATUS = ls.STATUS_ID
                                                                    JOIN pickup_status ps 
                                                                    on l.PICKUP_STATUS = ps.STATUS_ID
                                                                    WHERE rdl.DOC_ID = {$documentId} ";
        $loanDetailsResult = mysqli_query($dbc,$loanDetailsQuery);
        $loanDetails = mysqli_fetch_array($loanDetailsResult);
    }else if($docTypeNum == 3){

        $healthAidDetailsQuery = "SELECT ha.RECORD_ID, ha.MEMBER_ID as 'MEMBER_ID', ha.AMOUNT_TO_BORROW , aps.STATUS as 'APP_STATUS' , ps.STATUS as 'PICKUP_STATUS', ha.MESSAGE, ha.DATE_APPLIED, ha.DATE_APPROVED
                                                                FROM ref_document_healthaid rdh
                                                                JOIN health_aid ha
                                                                on rdh.RECORD_ID = ha.RECORD_ID
                                                                JOIN app_status aps
                                                                on ha.APP_STATUS = aps.STATUS_ID
                                                                JOIN pickup_status ps 
                                                                on ha.PICKED_UP_STATUS = ps.STATUS_ID
                                                                WHERE rdh.DOC_ID = {$documentId}";
        $healthAidDetailsResult = mysqli_query($dbc,$healthAidDetailsQuery);
        $healthAidDetails = mysqli_fetch_array($healthAidDetailsResult);

    }


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
                        <?php
                        /**
                         * BREADCRUMBS CODE - This relies on the queries below the header.
                         *
                         * What these do is that it makes the user go back to the link. Or could be used by an admin to quick jump
                         * back to the user application.
                         *
                         * So basically, functional breadcrumbs to. Again, no need to worry about this part brother!
                         *
                         * $docTypeNum - What type of document? Is it FALP or Health Aid?  2 For FALP and 3 for Health Aid
                         *
                         *
                         *
                         */
                        if($docTypeNum == 2){ //checks if its from FALP

                            if(!empty($loanDetails)) {

                                if ($loanDetails['MEMBER_ID'] == $_SESSION['idnum']) {  //checks if member or admin. Also checks if its the same user that applied for this
                                    ?>

                                    <a href="MEMBER%20FALP%20summary.php"><?php echo $processName; ?></a>

                                <?php } else if ($loanDetails['MEMBER_ID'] != $_SESSION['idnum'] && $_SESSION['FRAP_ROLE'] == 2){ // meaning you are the secretary
                                    $_SESSION['showFID'] = $loanDetails['LOAN_ID']; // thjis is jsut for safety purposes so that the admin is redirected back to the application he/she/they were viewing. ?>

                                    <a href="ADMIN%20FALP%20appdetails.php"><?php echo $processName; ?></a>

                                <?php }
                            }?>

                        <?php }else if($docTypeNum == 3){  //checks if its from health aid

                            if(!empty($healthAidDetails)) {

                                if ($healthAidDetails['MEMBER_ID'] == $_SESSION['idnum']) {  //checks if member or admin. Also checks if its the same user that applied for this
                                    ?>

                                    <a href="MEMBER%20HA%20summary.php"><?php echo $processName; ?></a>

                                <?php } else if ($healthAidDetails['MEMBER_ID'] != $_SESSION['idnum'] && $_SESSION['FRAP_ROLE'] == 2){ // meaning you are the secretary
                                    $_SESSION['showHAID'] = $healthAidDetails['RECORD_ID']; // thjis is jsut for safety purposes so that the admin is redirected back to the application he/she/they were viewing. ?>

                                    <a href="ADMIN%20HEALTHAID%20appdetails.php"><?php echo $processName; ?></a>

                                <?php }
                            }?>


                        <?php }else{?>

                            <?php echo $processName;?>

                        <?php }?>
                    </li>
                    <li class="active">
                        <?php echo $title; ?>
                    </li>
                </ol>
                <div class="panel panel-default">
                <?php
                $ext = pathinfo($filePath, PATHINFO_EXTENSION);
                if($ext == 'pdf' || $ext == 'jpg'){?>
                    <iframe src="/ViewerJS/../FRAP_sd/<?php echo $filePath; ?>" style="width: 100%; height:70rem;" allowfullscreen webkitallowfullscreen></iframe>
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
                                <strong>Document History</strong>
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
                                        <option value="created|updated|archived|restored|draft|pending|approved|rejected|moved" selected>Main Activity</option>
                                        <option value="">All</option>
                                        <option value="moved">Process Updates</option>
                                        <option value="draft|pending|approved|rejected">Status Updates</option>
                                        <option value="created|updated">Content Updates</option>
                                        <option value="archived|restored">Archive/Restore</option>
                                        <option value="checked out|checked in">Check-In/Check-Out</option>
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
                            <th width="150px">Action</th>
                            </thead>
                            <tbody>
                            <?php
                            $query = "SELECT v.audit_action_type, v.versionId,
                                                v.audit_timestamp, 
                                                v.versionNo, 
                                                v.old_versionNo,
                                                v.title, 
                                                v.old_title,
                                                v.filePath, 
                                                v.old_filePath,
                                                v.old_authorId,
                                                v.old_lastUpdated,
                                                v.authorId,
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

                            $issetLastAction = false;
                            $lastActionPanel = '';
                            $btnLastRemark = '';
                            $btnLastComparison = '';
                            $modalLastRemark = '';
                            $modalLastComparison = '';

                            $rows = $crud->getData($query);

                            if(!empty($rows)) {
                                foreach ((array)$rows as $key => $row) {
                                    $actionDisp = '';
                                    $actionPanel = '';
                                    $btnActionRemark = '';
                                    $btnActionComparison = '';
                                    $modalActionRemark = '';
                                    $modalActionComparison = '';

                                    if($row['audit_action_type'] == 'LOCKED') {
                                        $actionDisp = $crud->coloriseAvailability($row['availabilityId']).' the document.';
                                    }else if($row['audit_action_type'] == 'STATUSED') {
                                        $actionDisp = $crud->coloriseStatus($row['statusId']).' status assigned to the document.<br>
                                                            <span class="label label-default">CHECKED IN</span> the document.';
                                    }else if($row['audit_action_type'] == 'MOVED') {
                                        $actionDisp = $crud->coloriseStep().' the document to <strong>Step '.$row['stepNo'].': '.$row['stepName'].'</strong>.<br>
                                                            <span class="label label-default">CHECKED IN</span> the document.';
                                    }else if($row['audit_action_type'] == 'CYCLED'){
                                        $actionDisp = $crud->coloriseCycle($row['lifecycleStateId']).' the document.<br>';
                                    }else if($row['audit_action_type'] == 'UPDATED' || $row['audit_action_type'] == 'CREATED') {
                                        $actionDisp = '<span class="label label-success">'.$row['audit_action_type'].'</span> the document.<br>';
                                    }else if($row['audit_action_type'] == 'STATUSED/MOVED') {
                                        $actionDisp = $crud->coloriseStep() . ' the document to <strong>Step ' . $row['stepNo'] . ': ' . $row['stepName'] . '</strong>.<br>';
                                        $actionDisp .= $crud->coloriseStatus($row['statusId']) . ' status assigned to the document.<br>
                                                            <span class="label label-default">CHECKED IN</span> the document.';
                                    }

                                    if($row['audit_action_type'] != 'LOCKED') {

                                        $actionPanel = '<div class="panel panel-default">
                                        <div class="panel-body">
                                        <strong>' . $row['name'] . '</strong> on
                                        <i>' . date("F j, Y g:i:s A ", strtotime($row['audit_timestamp'])) . '</i><br>';
                                        $actionPanel .= $actionDisp;
                                        $actionPanel .= '</div></div>';

                                        $btnActionRemark = '<button class="btn btn-default btn-info btn-sm" data-toggle="modal" data-target="#modalRemark' . $row['versionId'] . '" title="Read remarks"><i class="fa fa-quote-left"></i></button>';
                                        $modalActionRemark = '<div id="modalRemark' . $row['versionId'] . '" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <strong class="modal-title">Remarks</strong>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-lg-12">
                                                                            ' . $actionPanel . '
                                                                            <div class="panel panel-default">
                                                                                <div class="panel-body alert-info" style="max-height: 40rem; overflow-y: auto;">
                                                                                    "<i>' . $row['remarks'] . '</i>"
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>';

                                        if($row['old_versionNo'] != ''){
                                            $btnActionComparison = '<button class="btn btn-primary fa fa-file-text" title="Version comparison" data-toggle="modal" data-target="#modalComparison'.$row['versionId'].'"></button>';
                                            $modalActionComparison = '<div id="modalComparison'.$row['versionId'].'" class="modal fade" role="dialog">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <strong class="modal-title">Content Comparison</strong>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <ul class="nav nav-tabs" role="tablist">
                                                                            <li role="presentation" class="active"><a href="#current" aria-controls="current" role="tab" data-toggle="tab">Current</a></li>
                                                                            <li role="presentation"><a href="#previous" aria-controls="previous" role="tab" data-toggle="tab">Previous</a></li>
                                                                        </ul>
                                                                        <div class="tab-content">
                                                                            <div role="tabpanel" class="tab-pane active" id="current">
                                                                                <div class="panel panel-default">
                                                                                    <div class="panel-body">
                                                                                        <div class="panel panel-default" style="height:50rem; max-height: 50rem;">
                                                                                            <div class="panel-heading">
                                                                                                <strong>Ver. No.: </strong> '.$row['versionNo'].' <strong>Title: </strong> '.$row['title'].'<br>
                                                                                                <strong>Updated by:</strong> '.$crud->getUserName($row['authorId']).' <strong>Updated on: </strong>'.$crud->friendlyDate($row['lastUpdated']).'
                                                                                            </div>
                                                                                            ';

                                            $ext = pathinfo($filePath, PATHINFO_EXTENSION);
                                            if($ext == 'pdf' || $ext == 'jpg') {
                                                $modalActionComparison.= '<iframe src="/ViewerJS/../FRAP_sd/' . $row['filePath'] . '" style="width: 100%; height:45rem;" allowfullscreen webkitallowfullscreen></iframe>';
                                            }else{
                                                $modalActionComparison.='<p>Viewer does not support format : <b>'.$ext.'</b></p>
                                                                         <a class="btn fa fa-download"  href="'.$row['filePath'].'" download="'.$title.'_ver'.$versionNo.'_'.basename($filePath).'"> Download </a>';

                                                }
                                            $modalActionComparison.= '
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                            <div role="tabpanel" class="tab-pane" id="previous">
                                                                                <div class="panel panel-default">
                                                                                    <div class="panel-body">
                                                                                        <div class="panel panel-default" style="height:50rem; max-height: 50rem;">
                                                                                            <div class="panel-heading">
                                                                                                <strong>Ver. No.: </strong> '.$row['old_versionNo'].' <strong>Title: </strong> '.$row['old_title'].'<br>
                                                                                                <strong>Updated by:</strong> '.$crud->getUserName($row['old_authorId']).' <strong>Updated on: </strong>'.$crud->friendlyDate($row['old_lastUpdated']).'
                                                                                            </div>';

                                            $ext = pathinfo($filePath, PATHINFO_EXTENSION);
                                            if($ext == 'pdf' || $ext == 'jpg') {
                                                $modalActionComparison.= '<iframe src="/ViewerJS/../FRAP_sd/' . $row['old_filePath'] .'" style="width: 100%; height:45rem;" allowfullscreen webkitallowfullscreen></iframe>';
                                            }else{
                                                $modalActionComparison.='<p>Viewer does not support format : <b>'.$ext.'</b></p>
                                                                         <a class="btn fa fa-download"  href="'.$row['old_filePath'].'" download="'.$title.'_ver'.$versionNo.'_'.basename($filePath).'"> Download </a>';

                                                }
                                            $modalActionComparison.= '</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>';
                                        }

                                        if ($issetLastAction == false) {
                                            if ($row['remarks'] != '') {
                                                $btnLastRemark = '<button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalRemark'.$row['versionId'].'" title="Read remarks"><i class="fa fa-quote-left"></i> Read remark</button>';
                                                $modalLastRemark = $modalActionRemark;
                                            }
                                            if ($row['audit_action_type'] == 'UPDATED') {
                                                $btnLastComparison = '<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalComparison'.$row['versionId'].'" title="Content comparison"><i class="fa fa-file-text"></i> Content comparison</button>';
                                                $modalLastComparison = $modalActionComparison;
                                            }
                                            $lastActionPanel = $actionPanel . $btnLastRemark . $modalLastRemark .$btnLastComparison .$modalLastComparison;
                                            $issetLastAction = true;
                                        }

                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['audit_timestamp'];?>
                                        </td>
                                        <td>
                                            <?php echo $row['versionNo'];?>
                                        </td>
                                        <td>
                                            <?php echo $row['name'];?>
                                        </td>
                                        <td>
                                            <?php echo $actionDisp; ?>
                                        </td>
                                        <td>

                                            <?php if($row['audit_action_type'] != 'LOCKED'){ ?>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <?php if($row['remarks'] != '') { echo $btnActionRemark.$modalActionRemark; } ?>
                                                    <?php if($row['old_versionNo'] != ''){ echo $btnActionComparison.$modalActionComparison; } ?>
                                                    <?php if($row['versionNo'] != $versionNo) { ?>
                                                <button class="btn btn-default" data-toggle="modal" data-target="#modalVersionPreview<?php echo $row['versionId'];?>" title="Version details"><i class="fa fa-eye"></i></button>
                                                <div id="modalVersionPreview<?php echo $row['versionId'];?>" class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <strong class="modal-title">Version Details</strong>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <?php echo $actionPanel;?>
                                                                        <div class="panel panel-default">
                                                                            <div class="panel-body" style="max-height: 40rem; overflow-y: auto;">
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
                                                                                    <?php if($old_versionNo != ''){ ?>
                                                                                        <tr>
                                                                                            <th>Content updated by</th>
                                                                                            <td><?php echo $row['authorName']; ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>Content updated on</th>
                                                                                            <td><?php echo date("F j, Y g:i:s A ", strtotime($row['lastUpdated']));?></td>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
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
                                                <?php if($write == '2'){ ?>
                                                    <button class="btn btn-warning" data-toggle="modal" data-target="#modalRevert<?php echo $row['versionId'];?>" title="Revert to this version"><i class="fa fa-refresh"></i></button>
                                                    <div id="modalRevert<?php echo $row['versionId'];?>" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <form method="POST" action="">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <strong>Revert to version <?php echo $row['versionNo'];?></strong>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="alert alert-info">
                                                                            Reverting only brings back older document content.
                                                                            It will not bring back other information such as status, state, etc.
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for=".radio"> Revert to old version as version </label><br>
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
                                                                            <textarea name="remarks" id="remarks" class="form-control" placeholder="Your remarks..." rows="10" cols="60" required></textarea>
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
                                                <a class="btn btn-sm btn-success" href="<?php echo $filePath;?>" target="_blank" download="<?php echo $row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']);?>" title="Download version"><i class="fa fa-download"></i></a>
                                                    <?php } ?>
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
            <?php /**
             * LOAN/HEALTH AID DETAILS CODE
             *
             * This is where the Table for the Loan details/Health Aid details is. Basically it gets the document type, and adjusts the table
             * as it sees fit.
             *
             *
             */

            if($docTypeNum == 2){ // checks kung FALP


                if(!empty($loanDetails)){ ?>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b>Loan Details</b>
                        </div>
                        <div class="panel-body">
                            <table class="table table-responsive table-striped table-condensed table-sm">
                                <tbody>

                                <tr>
                                    <th>
                                        Amount to be Borrowed
                                    </th>
                                    <td>
                                        ₱ <?php echo number_format($loanDetails['AMOUNT'],2); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Payment Terms
                                    </th>
                                    <td>
                                        <?php echo $loanDetails['PAYMENT_TERMS']; ?> Months
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Per Payment Deduction
                                    </th>
                                    <td>
                                        ₱ <?php echo number_format($loanDetails['PER_PAYMENT'],2); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Monthly Deduction
                                    </th>
                                    <td>
                                        ₱ <?php echo number_format(($loanDetails['PER_PAYMENT']*2),2); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Date Applied
                                    </th>
                                    <td>
                                        <?php echo date("m/d/y", strtotime($loanDetails['DATE_APPLIED'])); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Date Approved
                                    </th>
                                    <td>
                                        <?php if(!empty($loanDetails['DATE_APPROVED'])){
                                        echo  date("m/d/y", strtotime($loanDetails['DATE_APPROVED']));
                                        }else{
                                            echo "-------------";
                                        }?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Loan Status
                                    </th>
                                    <td>
                                        <?php echo $loanDetails['LOAN_STATUS']; ?>
                                    </td>
                                </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>



                <?php } ?>

            <?php }else if($docTypeNum == 3){ // checks if Health Aid

                if(!empty($healthAidDetails)){
                    ?>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b>Health Aid Details</b>
                        </div>
                        <div class="panel-body">
                            <table class="table table-responsive table-striped table-condensed table-sm">
                                <tbody>

                                <tr>
                                    <th>
                                        Amount to Borrow
                                    </th>
                                    <td>
                                        ₱ <?php echo number_format($healthAidDetails['AMOUNT_TO_BORROW'],2); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Message
                                    </th>
                                    <td>
                                        <?php echo $healthAidDetails['MESSAGE']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Application Status
                                    </th>
                                    <td>
                                        <?php echo $healthAidDetails['APP_STATUS']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Date Applied
                                    </th>
                                    <td>
                                        <?php echo date("m/d/y", strtotime($healthAidDetails['DATE_APPLIED'])); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Date Approved
                                    </th>
                                    <td>
                                        <?php echo  date("m/d/y", strtotime($healthAidDetails['DATE_APPROVED']));?>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php    }
            } ?>
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
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($lastActionPanel != ''){ ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-12">
                                <strong>Last Document Activity</strong>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php echo $lastActionPanel;?>
                    </div>
                </div>
                <?php } ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-12">
                                <strong>Document Actions</strong>
                                <a class="btn btn-sm fa fa-info" id="btnPermissionsInfo" data-toggle="modal" data-target="#modalPermissionsInfo" title="Your permissions" style="position: absolute; top: 0px; right: 15px;"></a>
                            </div>
                        </div>
                    </div>
                    <div id="modalPermissionsInfo" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <strong class="modal-title">Your permissions</strong>
                                </div>
                                <div class="modal-body">
                                    <?php
                                    $infoPermissions = "You don't have permissions in this step.";
                                    if($boolInGroup){
                                        $infoPermissions = 'This step is assigned to the <strong>'.$groupDesc.' ('.$groupName.')</strong> group. As a member of this group, the following permissions apply to you.';
                                    }else if($userId == $firstAuthorId) {
                                        $infoPermissions = 'You are the creator of this document, therefore creator permissions granted in this stage will apply to you: ';
                                    }

                                    if($write == '2' || $cycle == '2' || $route == '2'){
                                        if($write == '2'){
                                            $infoPermissions.= '<br><strong>WRITE</strong> (Upload new document, revert to previous version)';
                                        }
                                        if($cycle == '2'){
                                            $infoPermissions.= '<br><strong>CYCLE</strong> (Archive or restore document)';
                                        }
                                        if($route == '2'){
                                            $infoPermissions.= '<br><strong>ROUTE</strong> (Move to a stage, assign status)';
                                        }
                                    }else{
                                        $infoPermissions.='<br><strong>READ-ONLY</strong> (No special permissions granted)';
                                    }
                                    ?>
                                    <div class="alert alert-info">
                                        <?php echo $infoPermissions;?>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="btn-group btn-group-vertical" style="width: 100%;">
                            <?php if($availability == '1'){ ?>
                                <?php if($write=='2' || $route =='2' || $cycle == '2'){?>
                                    <form method="POST" action="">
                                        <button class="btn btn-primary" type="submit" name="btnLock" value="<?php echo $documentId;?>" style="text-align: left; width:100%;"><i class="fa fa-lock"></i> Check Out for Processing</button>
                                    </form>
                                <?php }  ?>
                            <?php } ?>
                            <?php if($availability == '2'){ ?>
                                <?php if($route=='2') {
                                    $rows = $crud->getStepRoutes($currentStepId);
                                    if (!empty($rows)) {
                                        foreach ((array)$rows as $key => $row) {
                                            $btnClass = 'btn btn-primary';
                                            $btnIcon = 'fa fa-arrow-right';
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
                                                                <strong>Confirm '<?php echo $row['routeName'];?>'?</strong>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label><input type="checkbox" class="remark_checkbox"> Provide remarks</label>
                                                                    <textarea name="remarks" id="remarks" class="form-control" placeholder="Your remarks..." rows="10" style="display: none;"></textarea>
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
                                                                <label><input type="checkbox" class="remark_checkbox"> Provide remarks</label>
                                                                <textarea name="remarks" class="form-control" rows="10" style="display: none;"></textarea>
                                                            </div>
                                                            <div class="alert alert-warning">
                                                                <strong>Archiving this document will mean that it will not be processable until it is restored.
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
                                                                <label><input type="checkbox" class="remark_checkbox"> Provide remarks</label>
                                                                <textarea name="remarks" class="form-control" rows="10" style="display: none;"></textarea>
                                                            </div>
                                                            <div class="alert alert-info">
                                                                <strong>Restoring this document will make it processable again.
                                                                    The original document permissions will be restored to the participants of the <?php echo $processName;?> process.
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
                                        <button class="btn btn-secondary" type="submit" name="btnUnlock" id="btnUnlock" value="<?php echo $documentId;?>" style="text-align: left; width: 100%;"><i class="fa fa-unlock"></i> Check In</button>
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
                    if(JSON.parse(response).success == '1'){ location.href = 'EDMS_ViewDocument.php?docId='+documentId; }
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
        }, 5000);

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

                columnAction.search("created|updated|archived|restored|draft|pending|approved|rejected|moved",true,false).draw();

            }
        });

        $(".dataTables_filter").hide();
        $('#searchField').keyup(function(){
            table.search($('#searchField').val()).draw();
        });

        $('.remark_checkbox').on('change', function () {
            var ta = $(this).closest('.form-group').find('textarea');
            if($(this).prop("checked") == true){
                ta.show().fadeIn("fast", function(){
                    ta.attr("required",true);
                });
            }else if($(this).prop("checked") == false){
                ta.show().fadeOut("fast", function(){
                    ta.attr("required",false);
                });
            }
        });

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