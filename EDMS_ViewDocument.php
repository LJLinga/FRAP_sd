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


if(isset($_GET['docId'])){

    $documentId = $_GET['docId'];

    // Load document
    $query = "SELECT 
                d.firstAuthorId, d.timeCreated, d.availabilityId, d.versionNo, d.authorId, d.title, d.filePath, d.lastUpdated,
		        CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS originalAuthor, dt.type,
                (SELECT CONCAT(e.LASTNAME,', ',e.FIRSTNAME) FROM employee e WHERE e.EMP_ID = d.authorId) AS currentAuthor,
                (SELECT CONCAT(e2.LASTNAME,', ',e2.FIRSTNAME) FROM employee e2 WHERE e2.EMP_ID = d.statusedById) AS statusedByName,
		        d.stepId,  p.processName, s.stepName, d.availabilityId, d.availabilityById, d.statusId
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
            $docType = $row['type'];
            $statusName = $crud->assignStatusString($statusId);
            $statusUpdaterName = $row['statusedByName'];
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
            //Learn return to previous.
            header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_Workspace.php");
        }

        if($availability == '2'){
            $route = '1';
            if($availabilityById == $userId) $write = '2';
            else $write = '1';
        }
    }else{
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_Workspace.php");
    }

}else{
   header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_Workspace.php");
}

if(isset($_POST['btnUnlock'])){
    $documentId= $_POST['btnUnlock'];
    $crud->execute("UPDATE documents SET availabilityId='1' WHERE documentId='$documentId'");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ViewDocument.php?docId=".$documentId);
}


if(isset($_POST['btnLock'])){
    $documentId = $_POST['btnLock'];
    $rows = $crud->getData("SELECT availabilityId FROM documents WHERE documentId = '$documentId'");
    foreach((array) $rows as $key => $row){
        $availability = $row['availabilityId'];
    }
    if($availability == '1'){
        $userId = $_POST['userId'];
        $crud->execute("UPDATE documents SET availabilityId='2', availabilityById='$userId' WHERE documentId='$documentId'");
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_ViewDocument.php?docId=".$documentId);
}

if(isset($_POST['btnDownload'])){
    $filePath = $_POST['btnDownload'];
    $name = str_replace('\'', '/', $filePath);
    header('Content-Description: File Transfer');
    header('Content-Type: application/force-download');
    header("Content-Disposition: attachment; filename=\"" . basename($name) . "\";");
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($name));
    ob_clean();
    flush();
    readfile($name); //showing the path to the server where the file is to be download
}

if(isset($_POST['btnRoute'])){
    $documentId = $_POST['documentId'];
    $nextStepId = $_POST['nextStepId'];
    $statusId = $_POST['assignStatusId'];
    $currentStatusId = $_POST['currentStatusId'];
    $remarks = $_POST['remarks'];
    $rows = $crud->getData("SELECT availabilityId FROM documents WHERE documentId = '$documentId'");
    foreach((array) $rows as $key => $row){
        $availability = $row['availabilityId'];
    }
    if($statusId >= 5){
        $statusId = $currentStatusId;
    }
    if($availability == '1'){
        if($statusId != $currentStatusId){
           $crud->execute("UPDATE documents SET statusId = '$statusId', stepId='$nextStepId', statusedById='$userId', steppedById='$userId', remarks = '$remarks' WHERE documentId='$documentId'");
        }else if($statusId == $currentStatusId){
            $crud->execute("UPDATE documents SET stepId='$nextStepId', steppedById='$userId', remarks = '$remarks' WHERE documentId='$documentId'");
        }
    }
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ViewDocument.php?docId=" .$documentId);
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
                        <?php echo $processName;?>
                    </li>
                    <li class="active">
                        <?php echo $title; ?>
                    </li>
                </ol>
                <?php
                $ext = pathinfo($filePath, PATHINFO_EXTENSION);
                if($ext == 'pdf' || $ext == 'jpg'){?>
                    <iframe src="/ViewerJS/../FRAP_sd/<?php echo $filePath; ?>" width="850" style="height:80vh;" allowfullscreen webkitallowfullscreen></iframe>
                <?php }else{ ?>
                    <div class="card" style="margin-top: 1rem;">
                        <div class="card-header"><b>Document File</b></div>
                        <div class="card-body">
                            <p>Viewer does not support format : <b><?php echo $ext;?></b></p>
                            <a class="btn fa fa-download"  href="<?php echo $filePath; ?>" download="<?php echo $title.'_ver'.$versionNo.'_'.basename($filePath);?>"> Download </a>
                        </div>
                    </div>';
                <?php }
                ?>
            </div>
            <div class="col-lg-4">

                <div class="panel panel-info">
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
                            <?php if($statusUpdaterName != ''){?>
                            <tr>
                                <th>Status updated by</th>
                                <td><?php echo $statusUpdaterName; ?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <th>Created by</th>
                                <td><?php echo $originalAuthor; ?></td>
                            </tr>
                            <tr>
                                <th>Created on</th>
                                <td><?php echo date("F j, Y g:i:s A ", strtotime($timeFirstPosted)); ?></td>
                            </tr>
                            <tr>
                                <th>Last modified by</th>
                                <td><?php echo $currentAuthor; ?></td>
                            </tr>
                            <tr>
                                <th>Last modified on</th>
                                <td><?php echo date("F j, Y g:i:s A ", strtotime($timeUpdated));?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <b>Document Actions</b>
                    </div>
                    <div class="panel-body">
                        <div class="btn-group btn-group-vertical" style="width: 100%;">
                            <?php
                            if($currentStepId == '1'){
                                //ASSIGN PROCESS BUTTON WITH CONFIRM MODAL
                            }else if($route=='2' && $availability=='1') {
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
                            }
                            ?>
                            <form method="POST" action="">
                                <?php if( $write=='2' && $availability=='1'){?>
                                    <input type="hidden" name="filePath" value="<?php echo $filePath;?>">
                                    <input type="hidden" name="userId" value="<?php echo $userId;?>">
                                    <button class="btn btn-default" type="submit" name="btnLock" value="<?php echo $documentId;?>" style="text-align: left; width:100%;">Lock and Edit</button>
                                <?php } else if($write=='2' && $availability=='2'){ ?>
                                    <button type="button" class="btn btn-primary" id="btnUpload" data-toggle="modal" data-target="#uploadModal" style="text-align: left; width: 100%;">Finish Editing</button>
                                    <button class="btn btn-warning" type="submit" name="btnUnlock" id="btnUnlock" value="<?php echo $documentId;?>" style="text-align: left; width: 100%;">Cancel Editing</button>
                                <?php } ?>
                                <a href="<?php echo $filePath?>" download><button type="button" class="btn btn-default" style="text-align: left; width: 100%;">Download</button></a>
                                <button type="button" name="btnArchive" class="btn btn-default" style="text-align: left; width: 100%;">Archive</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 1rem;">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-3">
                                <b class="panel-title">Document History</b>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-inline">
                                    <label for="sel1">User </label>
                                    <select class="form-control" id="selectedUser" name="selectedUser">
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
                                        <option value="locked">LOCKED</option>
                                        <option value="unlocked">UNLOCKED</option>
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
                    </div>
                    <div class="panel-body">
                        <table id="tblHistory" class="table table-condensed table-sm table-striped" cellspacing="0" width="100%">
                            <thead>
                            <th>Timestamp </th>
                            <th>User </th>
                            <th>Action </th>
                            <th></th>
                            </thead>
                            <tbody>
                            <?php
                            $query = "SELECT v.audit_action_type, v.versionId,
                                                v.audit_timestamp, 
                                                v.lastUpdated, 
                                                v.versionNo, 
                                                v.title, 
                                                v.filePath, 
                                                v.remarks,
                                                v.lifecycleStateId,
                                                v.availabilityId,
                                                s.statusname, v.statusId,
                                                st.stepName, st.stepNo,
                                                CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS name
                                            
                                            FROM doc_versions v 
                                            JOIN doc_status s ON v.statusId= s.id
                                            JOIN steps st ON v.stepId = st.id
                                            LEFT JOIN employee e ON v.audit_user_id = e.EMP_ID
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
                                            <?php echo $row['name'];?>
                                        </td>
                                        <td>
                                            <?php
                                            if($row['audit_action_type'] == 'LOCKED') { $labelCol = 'default';?>
                                                <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->availabilityString($row['availabilityId']);?></span> the document.
                                            <?php }else if($row['audit_action_type'] == 'STATUSED') {
                                                if($row['statusId'] ==  1) { $labelCol = 'info'; }
                                                else if($row['statusId'] ==  2) { $labelCol = 'primary'; }
                                                else if($row['statusId'] ==  3) { $labelCol = 'success'; }
                                                else if($row['statusId'] ==  4) { $labelCol = 'danger'; } ?>
                                                <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->assignStatusString($row['statusId']);?></span> status assigned to the document.
                                            <?php }else if($row['audit_action_type'] == 'MOVED') {
                                                $labelCol = 'primary'?>
                                                <span class="label label-<?php echo $labelCol;?>">MOVED</span> the document to <strong>Step <?php echo $row['stepNo'];?>: <?php echo $row['stepName'];?></strong>.
                                            <?php }else if($row['audit_action_type'] == 'CYCLED'){
                                                if($row['lifecycleStateId'] ==  1) $labelCol = 'info';
                                                if($row['lifecycleStateId'] ==  2) $labelCol = 'warning';?>
                                                <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->lifecycleString($row['lifecycleStateId']);?></span> the document.
                                            <?php }else if($row['audit_action_type'] == 'UPDATED' || $row['audit_action_type'] == 'CREATED') {
                                                $labelCol = 'success'?>
                                                <span class="label label-<?php echo $labelCol;?>"><?php echo $row['audit_action_type'];?></span> the document.
                                            <?php }else if($row['audit_action_type'] == 'STATUSED/MOVED') {
                                                $labelCol = 'primary'?>
                                                <span class="label label-<?php echo $labelCol;?>">MOVED</span> the document to <strong>Step <?php echo $row['stepNo'];?>: <?php echo $row['stepName'];?></strong>.<br>
                                            <?php if($row['statusId'] ==  1) { $labelCol = 'info'; }
                                                else if($row['statusId'] ==  2) { $labelCol = 'primary'; }
                                                else if($row['statusId'] ==  3) { $labelCol = 'success'; }
                                                else if($row['statusId'] ==  4) { $labelCol = 'danger'; } ?>
                                                <span class="label label-<?php echo $labelCol;?>"><?php echo $crud->assignStatusString($row['statusId']);?></span> status assigned to the document.
                                            <?php }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if($row['audit_action_type'] != 'LOCKED'){?>
                                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalVersionPreview<?php echo $row['versionId'];?>"><i class="fa fa-eye"></i> Remarks</button>
                                                <div id="modalVersionPreview<?php echo $row['versionId'];?>" class="modal fade" role="dialog">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-lg-8">

                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <?php echo $row['remarks'];?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">

                                                            </div>
                                                        </div>

                                                        </form>

                                                    </div>
                                                </div>
                                            <?php }?>
                                            <a class="btn btn-sm fa fa-info-circle"></a>
                                            <a class="btn btn-sm fa fa-download"  href="<?php echo $filePath;?>" download="<?php echo $row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']);?>"></a>
                                        </td>
                                    </tr>
                                    <div id="preview" class="modal fade" role="dialog">
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
                                <?php }
                            }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header"><b>Comments</b></div>
                    <div class="card-body">
                        <button type="button" class="btn btn-primary fa fa-comment" data-toggle="modal" data-target="#myModal" name="addComment" id="addComment"> Comment </button>
                        <span id="comment_message"></span>
                        <div id="display_comment"></div>
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
                    if(JSON.parse(response).success == '1'){ location.reload(); }
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

        let typeId = '';

        table = $('#tblHistory').DataTable( {
            destroy: true,
            bLengthChange: false,
            pageLength: 5,
            bSort: false,
            initComplete: function () {
                var columnUser = this.api().column(1);
                var selectUser = $('#selectedUser').on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        columnUser.search( val ? '^'+val+'$' : '', true, false ).draw();
                    } );
                columnUser.data().unique().sort().each( function ( d, j ) {
                    selectUser.append( '<option value="'+d+'">'+d+'</option>' )
                } );

                var columnAction = this.api().column(2);
                var selectAction = $('#selectedAction').on( 'change', function () {
                    columnAction.search($('#selectedAction').val()).draw();
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