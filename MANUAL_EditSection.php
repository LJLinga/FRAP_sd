<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

require_once('GLOBAL_CLASS_CRUD.php');
require_once('GLOBAL_PRINT_FPDF.php');
require_once('class.Diff.php');
$crud = new GLOBAL_CLASS_CRUD();

require_once('mysql_connect_FA.php');
session_start();
require_once('GLOBAL_USER_TYPE_CHECKING.php');

$userId = $_SESSION['idnum'];
//Buttons

$revisions = 'closed';

$query = "SELECT r.id, r.revisionsOpened FROM revisions r WHERE r.statusId = 2 ORDER BY r.id DESC LIMIT 1;";
$rows = $crud->getData($query);
if(!empty($rows)){
    $revisions = 'open';
}

$error='';
$edit='2';

if(isset($_POST['btnFinish'])){
    $sectionId = $_POST['section_id'];
    $title = $crud->escape_string($_POST['section_title']);
    $sectionNo = $crud->escape_string($_POST['section_number']);
    $content = $crud->escape_string($_POST['section_content']);

    if($revisions == 'open'){
        $crud->execute("UPDATE sections SET title = '$title', sectionNo = '$sectionNo', content = '$content', availabilityId='2', lockedById=NULL WHERE id = '$sectionId'");
    }else{
        $error='&alert=SECTION_REVISIONS_CLOSED';
    }
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_EditSection.php?secId=".$sectionId.$error);
}

if(isset($_POST['btnUnlock'])){
    $sectionId= $_POST['btnUnlock'];
    $crud->execute("UPDATE sections SET availabilityId='1', availabilityById='$userId' WHERE id='$sectionId'");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_EditSection.php?secId=".$sectionId);
}

if(isset($_POST['btnLock'])){
    $sectionId = $_POST['btnLock'];
    $rows = $crud->getData("SELECT availabilityId FROM sections WHERE id = '$sectionId'");
    foreach((array) $rows as $key => $row){
        $availability = $row['availabilityId'];
    }

    if($revisions == 'open'){
        if($availability == '1'){
            $crud->execute("UPDATE sections SET availabilityId='2', availabilityById='$userId' WHERE id='$sectionId'");
        }else{
            $error='&alert=SECTION_LOCKED';
        }
    }else{
        $error='&alert=SECTION_REVISIONS_CLOSED';
    }

    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_EditSection.php?secId=".$sectionId.$error);
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
    if($revisions == 'open') {
        if ($statusId != $currentStatusId) {
            $crud->execute("UPDATE sections SET statusId = '$statusId', stepId='$nextStepId', statusedById='$userId', steppedById='$userId', remarks = '$remarks' WHERE id='$sectionId'");
        } else if ($statusId == $currentStatusId) {
            $crud->execute("UPDATE sections SET stepId='$nextStepId', steppedById='$userId', remarks = '$remarks' WHERE id='$sectionId'");
        }
    }else{
        $error='&alert=SECTION_REVISIONS_CLOSED';
    }
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_EditSection.php?secId=".$sectionId.$error);
}

if(isset($_POST['btnSave'])){
    $title = $crud->escape_string($_POST['sectionTitle']);
    $sectionNo = $crud->escape_string($_POST['sectionNo']);
    $content = $crud->escape_string($_POST['sectionContent']);
    $remarks = $crud->escape_string($_POST['remarks']);
    $versionNo = $_POST['newVersionNo'];
    $sectionId = $_POST['sectionId'];
    if($revisions == 'open'){
        $crud->execute("UPDATE sections SET versionNo='$versionNo', title='$title', sectionNo='$sectionNo', content='$content', authorId='$userId', remarks='$remarks' WHERE id='$sectionId';");
    }else{
        $error='&alert=SECTIONS_REVISIONS_CLOSED';
    }
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_EditSection.php?secId=".$sectionId.$error);
}

if(isset($_POST['btnArchive'])){
    $sectionId = $_POST['sectionId'];
    $remarks = $crud->escape_string($_POST['remarks']);

    if($revisions == 'open'){
        $crud->execute("UPDATE sections SET lifecycleId = '2', remarks='$remarks', lifecycledById ='$userId' WHERE id = '$sectionId' ");
    }else{
        $error='&alert=SECTIONS_REVISIONS_CLOSED';
    }
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_EditSection.php?secId=".$sectionId.$error);
}

if(isset($_POST['btnRevert'])){
    $sectionId = $_POST['sectionId'];
    $remarks = $_POST['remarks'];
    $versionNo = $_POST['newVersionNo'];
    $versionId = $_POST['versionId'];

    echo 'Section Id '.$sectionId;
    echo 'Remarks '.$remarks;
    echo 'versionNo '.$versionNo;
    echo 'versionId '.$versionId;
    echo 'userId'.$userId;

    if($revisions == 'open'){
        $rows = $crud->getData("SELECT content, title, sectionNo FROM section_versions WHERE versionId = '$versionId'");
        if(!empty($rows)){
            foreach((array) $rows AS $key => $row){
                $content = $row['content'];
                $title = $row['title'];
                $sectionNo = $row['sectionNo'];
                echo 'content==>'.$row['content'];
                echo 'title==>'.$row['title'];
                echo 'sectionNo==>'.$row['sectionNo'];
            }
            $crud->execute("UPDATE sections SET versionNo='$versionNo', title='$title', sectionNo='$sectionNo', content={{$content}}, authorId='$userId', remarks='$remarks' WHERE id='$sectionId';");
        }else{
            $error='&alert=DATABASE_ERROR';
        }
    }else{
        $error='&alert=SECTIONS_REVISIONS_CLOSED';
    }
    //header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_EditSection.php?secId=".$sectionId.$error);
    //exit;
}

if(isset($_POST['btnRestore'])){
    $sectionId = $_POST['sectionId'];
    $remarks = $crud->escape_string($_POST['remarks']);

    if($revisions == 'open'){
        $crud->execute("UPDATE sections SET lifecycleId = '1', remarks='$remarks', lifecycledById ='$userId' WHERE id = '$sectionId' ");
    }else{
        $error='&alert=SECTIONS_REVISIONS_CLOSED';
    }
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_EditSection.php?secId=".$sectionId.$error);
}

if(isset($_GET['secId'])){
    $sectionId = $_GET['secId'];

    //header("Location:".$crud->redirectToPreviousWithAlert("SECTION_IS_ARCHIVED"));

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
            $statusedById = $row['statusedById'];
            $statusedByName = $crud->getUserName($statusedById);
            $statusedOn = $row['statusedOn'];

            $lifecycleId = $row['lifecycleId'];
            $lifecycledById = $row['lifecycledById'];
            $lifecycledByName = $crud->getUserName($lifecycledById);
            $lifecycledOn = $row['lifecycledOn'];

            $availability = $row['availabilityId'];
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
                    header("Location:".$crud->redirectToPreviousWithAlert("SECTION_NO_PERMISSIONS"));
                }
            }
        }

        $edit = '2';
        //Not allowed to edit

        if($revisions != 'open'){
            $route = '1';
            $write = '1';
        }

        if($lifecycleId == '2'){
            if($cycle != '2'){
                header("Location:".$crud->redirectToPreviousWithAlert("SECTION_IS_ARCHIVED"));
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
        $crud->error404();
    }

}else{
    $crud->error404();
}

if(isset($_POST['btnEdit'])) {
    $edit = '1';
}

$page_title = 'Faculty Manual - Edit Section';
include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';
?>
    <div id="content-wrapper">
        <div class="container-fluid">
            <!--Insert success page-->

                <div class="row" style="margin-top: 2rem;">
                    <div class="column col-lg-8">
                        <?php if($edit == '1'){ ?>
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
                                            </div>
                                            <div class="modal-footer">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                    <input type="submit" name="btnSave" class="btn btn-primary">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                            <div class="panel-footer">
                                <form method="POST" action="">
                                    <button type="submit" class="btn btn-secondary">Cancel Editing</button>
                                    <button type="button" class="btn btn-primary" id="btnSave" data-toggle="modal" data-target="#modalConfirmSave">Finish Editing</button>
                                    <span id="change_warning" class="label label-warning">You haven't made any changes yet.</span>
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
                                <div class="panel-body" style="min-height: 50rem; max-height: 80rem; overflow-y: auto;">
                                    <p style="text-align: justify;"><?php echo nl2br($content); ?></p>
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
                                <table id="tblHistory" class="table table-condensed table-striped" cellspacing="0" width="100%">
                                    <thead>
                                    <th>Timestamp</th>
                                    <th>Ver. No.</th>
                                    <th>User</th>
                                    <th>Description</th>
                                    <th width="120px">Action</th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $query = "SELECT v.*, st.stepName, st.stepNo FROM section_versions v
                                            JOIN steps st ON v.stepId = st.id
                                            WHERE v.sectionId = $sectionId
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
                                            $modalActionRemark = '';
                                            $modalActionComparison = '';
                                            $btnActionRemark = '';
                                            $btnActionComparison = '';

                                            if($row['audit_action_type'] == 'LOCKED') {
                                                $actionDisp = $crud->coloriseAvailability($row['availabilityId']).' the document.';
                                            }else if($row['audit_action_type'] == 'STATUSED') {
                                                $actionDisp = $crud->coloriseStatus($row['statusId']).' status assigned to the document.<br>
                                                            <span class="label label-default">CHECKED IN</span> the document.';
                                            }else if($row['audit_action_type'] == 'MOVED') {
                                                $actionDisp = $crud->coloriseStep().' the document to <strong>Step '.$row['stepNo'].': '.$row['stepName'].'</strong>.<br>
                                                            <span class="label label-default">CHECKED IN</span> the document.';
                                            }else if($row['audit_action_type'] == 'CYCLED'){
                                                $actionDisp = $crud->coloriseCycle($row['lifecycleId']).' the document.<br>';
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
                                        <strong>' . $crud->getUserName($row['audit_user_id']) . '</strong> on
                                        <i>' . date("F j, Y g:i:s A ", strtotime($row['audit_timestamp'])) . '</i><br>';
                                                $actionPanel .= $actionDisp;
                                                $actionPanel .= '</div></div>';

                                                $btnActionRemark = '<button class="btn btn-info fa fa-quote-left" data-toggle="modal" data-target="#modalRemark' . $row['versionId'] . '" title="Read remarks"></button>';
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
                                                    $btnActionComparison = '<button class="btn btn-primary fa fa-file-text" title="Content comparison" data-toggle="modal" data-target="#modalContentComparison'.$row['versionId'].'"></button>';
                                                    $modalActionComparison = '<div id="modalContentComparison'.$row['versionId'].'" class="modal fade" role="dialog">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <strong class="modal-title">Content Changes</strong>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="panel panel-default">
                                                                                        <div class="panel-body">
                                                                                            <table class="table-striped table-condensed table-responsive table-sm" cellspacing="0" width="100%">
                                                                                                <thead>
                                                                                                <td></td>
                                                                                                <td><i>Previous Version</i></td>
                                                                                                <td><i>This Version</i></td>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                <tr><th>Ver.No.</th><th>'.$row['old_versionNo'].'</th><th>'.$row['versionNo'].'</th></tr>
                                                                                                <tr><th>Section No.</th><td>'.$row['old_sectionNo'].'</td><td>'.$row['sectionNo'].'</td></tr>
                                                                                                <tr><th>Title</th><td>'.$row['old_title'].'</td><td>'.$row['title'].'</td></tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    <div class="panel panel-default">
                                                                                        <div class="panel-heading">
                                                                                            <strong>Legend</strong>
                                                                                        </div>
                                                                                        <div class="panel-body">
                                                                                            <span class="removed-text">Old content</span>
                                                                                            <br><span class="added-text">New content</span>
                                                                                            <br><span class="unmodified-text">Unchanged content</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-lg-12">
                                                                                    <div class="panel panel-default">
                                                                                        <div class="panel-body" style="max-height: 40rem; overflow-y: auto;">';
                                                                                           $modalActionComparison.= Diff::toHTML(Diff::compare(nl2br($row['old_content']), nl2br($row['content'])));
                                                                                        $modalActionComparison.='</div>
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
                                                        $btnLastRemark = '<button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalRemark' . $row['versionId'] . '" title="Read remarks"><i class="fa fa-quote-left"></i> Read remark</button>';
                                                        $modalLastRemark = $modalActionRemark;
                                                    }
                                                    if($row['audit_action_type'] == 'UPDATED'){
                                                        $btnLastComparison = '<button class="btn btn-primary btn-sm" title="Content comparison" data-toggle="modal" data-target="#modalContentComparison'.$row['versionId'].'"><i class="fa fa-file-text"></i> Content comparison</button>';
                                                        $modalLastComparison = $modalActionComparison;
                                                    }
                                                    $lastActionPanel = $actionPanel . $btnLastRemark . $modalLastRemark. $btnLastComparison . $modalLastComparison;
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
                                                    <?php echo $crud->getUserName($row['audit_user_id']);?>
                                                </td>
                                                <td>
                                                    <?php echo $actionDisp; ?>
                                                </td>
                                                <td>
                                                    <?php if($row['audit_action_type'] != 'LOCKED'){ ?>
                                                        <div class="btn-group btn-sm">
                                                            <?php if($row['remarks'] != ''){ echo $btnActionRemark.$modalActionRemark; }?>
                                                            <?php echo $btnActionComparison.$modalActionComparison;?>
                                                        <button class="btn btn-default fa fa-eye" data-toggle="modal" title="Version details" data-target="#modalVersionPreview<?php echo $row['versionId'];?>"></button>
                                                        <div id="modalVersionPreview<?php echo $row['versionId'];?>" class="modal fade" role="dialog">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <strong class="modal-title">Version Details</strong>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <?php echo $actionPanel; ?>
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
                                                                                    </tr
                                                                                    <tr>
                                                                                        <th>Stage</th>
                                                                                        <td><?php echo $row['stepName']; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>Status</th>
                                                                                        <td><?php echo $crud->coloriseStatus($row['statusId']);?></td>
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
                                                                                            <td><?php echo $crud->coloriseCycle($row['lifecycleId']);?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>State updated by</th>
                                                                                            <td><?php echo $crud->getUserName($row['lifecycledById'])?></td>
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
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php if($write == '3'){ ?>
                                                        <button class="btn btn-warning fa fa-refresh" data-toggle="modal" data-target="#modalRevert<?php echo $row['versionId'];?>"></button>
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
                                                                                                </tr
                                                                                                <tr>
                                                                                                    <th>Stage</th>
                                                                                                    <td><?php echo $row['stepName']; ?></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th>Status</th>
                                                                                                    <td><?php echo $crud->coloriseStatus($row['statusId']);?></td>
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
                                                                                                        <td><?php echo $crud->coloriseCycle($row['lifecycleId']);?></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <th>State updated by</th>
                                                                                                        <td><?php echo $crud->getUserName($row['lifecycledById'])?></td>
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
                                                                                <div class="col-lg-6">
                                                                                    <div class="alert alert-info">
                                                                                        Reverting only brings back older section content, title, and number.
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
                                                                                <input type="hidden" name="sectionId" value="<?php echo $sectionId; ?>">
                                                                                <input type="hidden" name="versionId" value="<?php echo $row['versionId'];?>">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                                <input type="submit" name="btnRevert" class="btn btn-primary">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                        <a href="MANUAL_PrintOneSection.php?versionId=<?php echo $row['versionId'];?>" target="_blank" class="btn btn-success fa fa-print" title="Print section"></a>
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
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <b>References</b>
                                        <?php if($availability == '2' && $write == '2'){ ?>
                                        <button id="btnRefModal" type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalRED"><i class="fa fa-fw fa-link"></i>Add</button>
                                        <?php } ?>
                                        <button class="btn btn-default btn-sm fa fa-expand" type="button" data-toggle="collapse" data-target="#collapseReferences" style="position: absolute; top: 0px; right: 15px;">
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body collapse in" id="collapseReferences">
                                <table class="table table-striped table-sm" id="tblReferences">
                                    <thead>
                                    <tr>
                                        <th> Title </th>
                                        <th> Ver No </th>
                                        <th> Type </th>
                                        <th> Submitted by </th>
                                        <th> Submitted on </th>
                                        <th> Referenced by </th>
                                        <th> Referenced on </th>
                                        <th> Action </th>
                                    </tr>
                                    </thead>
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
                                        <td>
                                            <?php echo $crud->coloriseStatus($statusId); ?>
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
                                            <td>
                                                <?php echo $crud->coloriseCycle($lifecycleId);?>
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

                        <?php if($lastActionPanel != ''){ ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <strong>Last Section Activity</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <?php echo $lastActionPanel;?>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if($edit == '2') { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <strong>Section Actions</strong>
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
                                    <?php if($revisions != 'open') { ?>
                                        <div class="alert alert-danger">
                                            <strong>Revisions are currently closed. All section modification actions are restricted. </strong>
                                        </div>
                                    <?php }else{ ?>
                                        <?php if($availability == '1'){ ?>
                                            <?php if($write=='2' || $route =='2' || $cycle == '2'){?>
                                                <form method="POST" action="">
                                                    <button class="btn btn-primary" type="submit" name="btnLock" value="<?php echo $sectionId;?>" style="text-align: left; width:100%;"><i class="fa fa-lock"></i> Check Out for Processing</button>
                                                </form>
                                            <?php }  ?>
                                        <?php } ?>
                                        <?php if($availability == '2') { ?>
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

                                                        $dispStat = '';

                                                        if($row['assignStatus'] < 5){
                                                            $dispStat = 'with '.$crud->coloriseStatus($row['assignStatus']);
                                                        }
                                                        ?>
                                                        <button class="<?php echo $btnClass;?>" style="text-align: left; width: 100%" type="button" data-toggle="modal" data-target="#modalRoute<?php echo $row['routeId'];?>">
                                                            <i class="<?php echo $btnIcon;?>"></i> <?php echo $row['routeName'];?>
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
                                            }?>
                                            <?php if( $write=='2' && $edit == '2'){?>
                                                <form method="POST" action="">
                                                    <button class="btn btn-primary" type="submit" name="btnEdit" style="text-align: left; width:100%;"><i class="fa fa-edit"></i> Edit Content</button>
                                                </form>
                                            <?php } ?>
                                            <?php if( $cycle=='2'){?>
                                                <?php if($lifecycleId == '1') { ?>
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
                                                                            <strong>Archiving this section will mean that it will not get published in any manual edition until it is restored.
                                                                                It will also remain read-only to other users except for those who have edit permissions in this current step.
                                                                                Are you sure you want to archive?</strong>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="sectionId" value="<?php echo $sectionId;?>">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" name="btnArchive" class="btn btn-primary">Yes, I'm sure</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }else if($lifecycleId == '2'){?>
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
                                                                            <strong>Restoring this section will put it back into the the process.
                                                                                The original section permissions will be restored to the participants of the Manual Revisions process.
                                                                                Are you sure you want to restore?</strong>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="sectionId" value="<?php echo $sectionId;?>">
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
                                                    The section has been checked out by <strong><?php echo $availabilityByName;?></strong> on <i><?php echo date("F j, Y g:i:s A ", strtotime($availabilityOn));?></i> which means editing, archiving, and approval actions are restricted.
                                                </div>
                                            <?php }else{ ?>
                                                <form method="POST" action="">
                                                    <button class="btn btn-secondary" type="submit" name="btnUnlock" id="btnUnlock" value="<?php echo $sectionId;?>" style="text-align: left; width: 100%;"><i class="fa fa-unlock"></i> Check In</button>
                                                </form>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <a href="MANUAL_PrintOneSection.php?sectionId=<?php echo $sectionId;?>" target="_blank" class="btn btn-default" style="text-align: left; width: 100%;"><i class="fa fa-print"></i> Print</a>
                                </div>
                            </div>
                        <?php }?>
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
                    <table class="table table-striped table-sm table-responsive table-condense" cellspacing="0" width="100%" id="tblAddReferences">
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
    </script>
    <script>
        $(document).ready( function(){

            $('#btnSave').attr("disabled", true);
            tinymce.init({selector:'#sectionContent',
                setup:function(ed) {
                    let init_cont =  ed.getContent();
                    ed.on('change', function(e) {
                        let cont = ed.getContent();
                        if(cont !== '' && init_cont !== cont){
                            $('#btnSave').attr("disabled", false);
                            $('#change_warning').removeClass('label-warning label-danger').addClass('label-info').html('You can now save your changes.');
                        }else if(cont === '') {
                            $('#btnSave').attr("disabled", true);
                            $('#change_warning').removeClass('label-info').addClass('label-danger').html('Content cannot be empty.');
                        }else if(cont === init_cont){
                            $('#btnSave').attr("disabled", true);
                            $('#change_warning').removeClass('label-info').addClass('label-warning').html('You have not made any changes.');
                        }
                        console.log('triggered');
                    });
                }});

            let tableHistory = $('#tblHistory').DataTable( {
                bLengthChange: false,
                pageLength: 10,
                bSort: true,
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
                    columnAction.search("created|updated|archived|restored|draft|pending|approved|rejected|moved", true, false).draw();

                    var columnAll = this.api();
                    $('#searchField').keyup(function(){
                        columnAll.search($('#searchField').val()).draw();
                    });
                }
            });



            $(".dataTables_filter").hide();

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

        let tableAddRef = $('#tblAddReferences').DataTable( {
            bSort: true,
            bLengthChange: false,
            destroy: true,
            pageLength: 10,
            scrollX: true,
            aaSorting: [],
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
            initComplete: function() {



            }

        });

        let tableRef = $('#tblReferences').DataTable( {
            bSort: true,
            bLengthChange: false,
            destroy: true,
            pageLength: 10,
            scrollX: true,
            aaSorting: [],
            "ajax": {
                "url":"EDMS_AJAX_FetchDocuments.php",
                "type":"POST",
                "dataSrc": '',
                "data": {
                    requestType: 'ADDED_MANUAL_REFERENCES',
                    sectionId: "<?php echo $sectionId;?>",
                    write: "<?php echo $write;?>"
                },
            },
            columns: [
                { data: "title" },
                { data: "vers"},
                { data: "type" },
                { data: "submitted_by"},
                { data: "submitted_on"},
                { data: "referenced_by"},
                { data: "referenced_on"},
                { data: "actions"}
            ],
            initComplete: function () {


            }
        });


        $('#btnRefModal').on('click', function(){
            reloadDataTable(tableAddRef);
        });

        function reloadDataTable(table){
            table.ajax.reload(null,false);
        }

        function addRef(versionId){
            console.log('Add reference.');
            $.ajax({
                url: "EDMS_AJAX_FetchDocuments.php",
                method: "POST",
                data: {requestType: 'INSERT_MANUAL_REFERENCE', sectionId: "<?php echo $sectionId?>", versionId: versionId},
                success: function(data){ console.log(data);
                    reloadDataTable(tableAddRef);
                    reloadDataTable(tableRef);
                }
            });

        }

        function removeRef(versionId){
            console.log('Remove reference.');
            $.ajax({
                url: "EDMS_AJAX_FetchDocuments.php",
                method: "POST",
                data: {requestType: 'REMOVE_MANUAL_REFERENCE', sectionId: "<?php echo $sectionId?>", versionId: versionId},
                success: function(data){ console.log(data)
                    reloadDataTable(tableAddRef);
                    reloadDataTable(tableRef);
                }
            });
        }

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
    <style>
        .diff td{
            vertical-align : top;
            white-space    : pre;
            white-space    : pre-wrap;
        }
        .unmodified-text{
            white-space: pre;
            white-space: pre-wrap;
            font-family: monospace;
        }
        .removed-text{
            background-color: #ffd3c2;
            white-space: pre;
            white-space: pre-wrap;
            font-family: monospace;
        }
        .added-text{
            background-color: #c2f5c2;
            white-space: pre;
            white-space: pre-wrap;
            font-family: monospace;
        }

    </style>
<?php include 'GLOBAL_FOOTER.php' ?>