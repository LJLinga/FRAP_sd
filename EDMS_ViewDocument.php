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
//include('GLOBAL_EDMS_ADMIN_CHECKING.php');

$edmsRole = $_SESSION['EDMS_ROLE'];
$userId = $_SESSION['idnum'];
//$read = 2;
//$write = 1;
//$route = 1;
//$comment = 1;



if(isset($_GET['docId'])){

    $documentId = $_GET['docId'];

    // Load Process and Steps assigned to current document
    $query = "SELECT d.stepId, p.processName, s.stepName, s.isFinal,
              d.availabilityId, d.lockedById, d.statusId
              FROM documents d 
              JOIN steps s ON d.stepId = s.id 
              JOIN doc_status st ON st.id = d.statusId 
              JOIN process p ON s.processId = p.id 
              WHERE d.documentId='$documentId';";
    $rows = $crud->getData($query);
    foreach((array) $rows as $key => $row){
        $currentStepId= $row['stepId'];
        $processName = $row['processName'];
        $stepName = $row['stepName'];
        $availability = $row['availabilityId'];
        $lockedById = $row['lockedById'];
        $statusId = $row['statusId'];
        $isFinal = $row['isFinal'];
    }

    // Load Current User Permissions

    //Get the rest of the document.
    $query = "SELECT d.firstAuthorId, d.timeCreated, d.availabilityId,  d.versionNo, d.authorId, d.title, d.filePath, 
              CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS originalAuthor,
              (SELECT CONCAT(e.LASTNAME,', ',e.FIRSTNAME) FROM employee e WHERE e.EMP_ID = d.authorId) AS currentAuthor
              FROM documents d
              JOIN employee e ON d.firstAuthorId = e.EMP_ID WHERE d.documentId='$documentId';";

    $rows = $crud->getData($query);
    foreach((array) $rows as $key => $row){
        $firstAuthorId = $row['firstAuthorId'];
        $originalAuthor = $row['originalAuthor'];
        $timeFirstPosted = $row['timeCreated'];
        $timeUpdated = $row['timeCreated'];
        //$versionId = $row['versionId'];
        $versionNo = $row['versionNo'];
        $currentAuthorId = $row['authorId'];
        $currentAuthor = $row['currentAuthor'];
        $title = $row['title'];
        $filePath = $row['filePath'];
        $availability = $row['availabilityId'];
    }

    if($userId == $firstAuthorId){
        $query = "SELECT su.read, su.write, su.route, su.comment FROM step_author su
                WHERE su.stepId='$currentStepId' LIMIT 1;";
        $rows = $crud->getData($query);
        if(!empty($rows)){
            foreach((array) $rows as $key => $row){
                $read= $row['read'];
                $write= $row['write'];
                $route= $row['route'];
                $comment = $row['comment'];
            }
        }else{
            $query = "SELECT su.read, su.write, su.route, su.comment FROM step_roles su
                WHERE su.stepId='$currentStepId' AND su.roleId='$edmsRole' LIMIT 1;";
            $rows = $crud->getData($query);
            if(!empty($rows)){
                //header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_Workspace.php");
                foreach((array) $rows as $key => $row){
                    $read= $row['read'];
                    $write= $row['write'];
                    $route= $row['route'];
                    $comment = $row['comment'];
                }
            }
        }
    }else{
        $query = "SELECT su.read, su.write, su.route, su.comment FROM step_roles su
                WHERE su.stepId='$currentStepId' AND su.roleId='$edmsRole' LIMIT 1;";
        $rows = $crud->getData($query);
        if(!empty($rows)){
            //header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_Workspace.php");
            foreach((array) $rows as $key => $row){
                $read= $row['read'];
                $write= $row['write'];
                $route= $row['route'];
                $comment = $row['comment'];
            }
        }
    }

    if($availability == '1'){
        $route = '1';
        if($lockedById == $userId) $write = '2';
        else $write = '1';
    }

}else{
   header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_Workspace.php");
}

if(isset($_POST['btnUnlock'])){
    $documentId= $_POST['btnUnlock'];
    $crud->execute("UPDATE documents SET availabilityId='2', lockedById=NULL WHERE documentId='$documentId'");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ViewDocument.php?docId=".$documentId);
}

if(isset($_POST['btnAccept'])){
    //Clickable only when unlocked. Thesis 2.
    $documentId = $_POST['documentId'];
    //echo '<script> alert('.$documentId.')</script>';
    $rows = $crud->getData("SELECT availabilityId FROM documents WHERE documentId = '$documentId'");
    foreach((array) $rows as $key => $row){
        $availability = $row['availabilityId'];
    }
    if($availability == '2'){
        $userId = $_POST['userId'];
        $remarks = $_POST['remarks'];
        $crud->execute("UPDATE documents SET statusId='2', statusedById='$userId', remarks='$remarks' WHERE documentId='$documentId'");
    }
    //$crud->execute("UPDATE documents SET statusId='2', availabilityId='2', lockedById=NULL WHERE documentId='$documentId'");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ViewDocument.php?docId=".$documentId);
}

if(isset($_POST['btnReject'])){
    $documentId= $_POST['documentId'];
    $rows = $crud->getData("SELECT availabilityId FROM documents WHERE documentId = '$documentId'");
    foreach((array) $rows as $key => $row){
        $availability = $row['availabilityId'];
    }
    if($availability == '2'){
        $userId = $_POST['userId'];
        $crud->execute("UPDATE documents SET statusId='3', statusedById='$userId' WHERE documentId='$documentId'");
    }
    //$crud->execute("UPDATE documents SET statusId='3', availabilityId='2', lockedById=NULL WHERE documentId='$documentId'");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ViewDocument.php?docId=".$documentId);
}

if(isset($_POST['btnLock'])){
    $documentId = $_POST['btnLock'];
    $rows = $crud->getData("SELECT availabilityId FROM documents WHERE documentId = '$documentId'");
    foreach((array) $rows as $key => $row){
        $availability = $row['availabilityId'];
    }
    if($availability == '2'){
        $userId = $_POST['userId'];
        $crud->execute("UPDATE documents SET availabilityId='1', lockedById='$userId' WHERE documentId='$documentId'");
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
    $nextStepId=$_POST['btnRoute'];
    $rows = $crud->getData("SELECT availabilityId FROM documents WHERE documentId = '$documentId'");
    foreach((array) $rows as $key => $row){
        $availability = $row['availabilityId'];
    }
    if($availability == '2'){
        $userId = $_POST['userId'];
        $crud->execute("UPDATE documents SET statusId = '1', stepId='$nextStepId' WHERE documentId='$documentId'");
    }
    //$crud->execute("UPDATE documents SET statusId = '1', availabilityId='2', stepId='$nextStepId', lockedById=NULL WHERE documentId='$documentId'");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ViewDocument.php?docId=" .$documentId);
}

include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';
?>
<div id="content-wrapper" xmlns="http://www.w3.org/1999/html">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 2rem;">
                <ol class="breadcrumb">
                    <li>
                        <a href="http://localhost/FRAP_sd/EDMS_Workspace.php">Workspace</a>
                    </li>
                    <li>
                        <?php echo $processName;?>
                    </li>
                    <li class="active">
                        <?php echo $title; ?>
                    </li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">

                <?php
                    $ext = pathinfo($filePath, PATHINFO_EXTENSION);
                    if($ext == 'pdf' || $ext == 'jpg'){
                        echo '<iframe src="/ViewerJS/../FRAP_sd/'.$filePath.'" width=\'850\' style="height:80vh;"; allowfullscreen webkitallowfullscreen></iframe>';
                    }else{
                        echo '<div class="card" style="margin-top: 1rem;">
                                <div class="card-header"><b>Document File</b></div>
                                <div class="card-body">
                                    <p>Viewer does not support format : <b>'.$ext.'</b></p>
                                    <a class="btn fa fa-download"  href="'.$filePath.'" download="'.$title.'_ver'.$versionNo.'_'.basename($filePath).'"> Download </a>
                                </div>
                            </div>';
                    }
                ?>

                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header"><b>Comments</b></div>
                    <div class="card-body">
                        <button type="button" class="btn btn-primary fa fa-comment" data-toggle="modal" data-target="#myModal" name="addComment" id="addComment"> Comment </button>
                        <span id="comment_message"></span>
                        <div id="display_comment"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">

                <div class="card">
                    <div class="card-header">
                        <b>Document Details</b>
                    </div>
                    <div class="card-body">
                        <table class="table table-condensed table-responsive table-bordered">
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
                                <th>Process</th>
                                <td><?php echo $processName; ?></td>
                            </tr>
                            <tr>
                                <th>Stage</th>
                                <td><?php echo $stepName; ?></td>
                            </tr>
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
                <?php
                    $query = "SELECT a.timeStamp, s.statusName, a.remarks, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) as statusAuthor, a.versionNo 
                                FROM doc_status_audit a 
                                JOIN doc_status s ON a.statusId = s.id
                                JOIN employee e ON a.statusedById = e.EMP_ID 
                                WHERE documentId='$documentId'
                                ORDER BY a.timeStamp DESC";
                    $rows = $crud->getData($query);
                if (!empty($rows)) { ?>
                    <div class="card" style="margin-top: 1rem; max-height: 25rem;">
                        <div class="card-header">
                            <b>Status History</b>
                        </div>
                        <div class="card-body" style="overflow-y: auto;">
                    <?php foreach ((array)$rows as $key => $row) { ?>
                        <div class="card">
                            <div class="card-body">
                                <span class="badge"><?php echo $row['statusName'];?></span> by <b><?php echo $row['statusAuthor'];?></b><br>
                                <i>on <?php echo date("F j, Y g:i:s A ", strtotime($row['timeStamp']));?></i><br>
                                "<?php echo $row['remarks']?>"
                            </div>
                        </div>
                    <?php } ?>
                        </div>
                    </div>
                <?php
                }
                ?>


                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b>Document Actions</b>
                        <b><?php echo $userId; ?></b>
                    </div>
                    <div class="card-body">
                        <div class="btn-group btn-group-vertical" style="width: 100%;">
                            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>">
                            <?php
                                if($currentStepId == '999'){
                                    //Later
                                    //echo '<button class="btn btn-info" style="text-align: left; width: 100%;" type="button" id="btnAssignTask">Change Type</button>';
                                }else if(isset($route) && $route=='2' && $availability=='2') {
                                    if($isFinal == '2'){
                                        if($statusId == 1){
                                            echo '<button class="btn btn-success" style="text-align: left; width: 100%" type="button" data-toggle="modal" data-target="#approveModal">Approve</button>';
                                            echo '<button class="btn btn-danger" style="text-align: left; width: 100%" type="button" data-toggle="modal" data-target="#rejectsModal">Reject</button>';
                                        }else if($statusId == 2){
                                            echo '<button class="btn btn-danger" style="text-align: left; width: 100%" type="button" data-toggle="modal" data-target="#rejectModal">Reject</button>';
                                        }else if($statusId == 3){
                                            echo '<button class="btn btn-success" style="text-align: left; width: 100%" type="button" data-toggle="modal" data-target="#approveModal">Approve</button>';
                                        }
                                    }
                                    $query = "SELECT nextStepId, routeName FROM step_routes WHERE currentStepId ='$currentStepId';";
                                    $rows = $crud->getData($query);
                                    if (!empty($rows)) {
                                        foreach ((array)$rows as $key => $row) {
                                            echo '<button class="btn btn-primary" style="text-align: left; width: 100%" type="button" data-toggle="modal" data-target="#'.$row['nextStepId'].'Modal">'.$row['routeName'].'</button>';
                                        }
                                    }
                                }
                                if(isset($write) && $write=='2' && $availability=='2'){
                                    echo '<input type="hidden" name="filePath" value="'.$filePath.'">';
                                    echo '<input type="hidden" name="userId" value="'.$userId.'">';
                                    echo '<button class="btn btn-default" type="submit" name="btnLock" value="'.$documentId.'" style="text-align: left; width:100%;">Lock and Edit</button>';
                                }else if(isset($write) && $write=='2' && $availability=='1'){
                                    echo '<button class="btn btn-default" type="submit" name="btnUnlock" id="btnUnlock" value="'.$documentId.'" style="text-align: left; width: 100%;">Finish Editing</button>';
                                    echo '<button type="button" class="btn btn-default" id="btnUpload" data-toggle="modal" data-target="#uploadModal" style="text-align: left; width: 100%;">Upload New Version</button>';
                                }

                                //echo $edmsRole.','.$read.','.$write.','.$route;
                                ?>
                                <a href="<?php echo $filePath?>" download><button type="button" class="btn btn-default" style="text-align: left; width: 100%;">Download</button></a>
                                <button type="button" name="btnArchive" class="btn btn-default" style="text-align: left; width: 100%;">Archive</button>
                            </form>
                        </div>
                    </div>
                </div>
               
                <?php
                    $query = "SELECT v.timeCreated, v.versionId as vid, v.versionNo, v.title, v.filePath, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS versionAuthor 
                              FROM doc_versions v JOIN employee e ON v.authorId = e.EMP_ID 
                              WHERE v.documentId = '$documentId' AND v.versionNo < '$versionNo' ORDER BY v.timeCreated DESC;";
                    $rows = $crud->getData($query);
                    if (!empty($rows)) {

                        echo '<div class="card" style="margin-top: 1rem; max-height: 20rem; ">';
                        echo '<div class="card-header"><b>Version History</b></div>';
                        echo '<div class="card-body" style="overflow-y: auto;">';
                        if(!empty($rows)) {
                            foreach ((array)$rows as $key => $row) {
                                echo '<div class="card" style="position: relative;">';
                                echo '<a style="text-align: left;" class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse' . $row['vid'] . '" aria-expanded="true" aria-controls="collapse' . $row['vid'] . '"><span class="badge">Version ' . $row['versionNo'] . '</span> <b>' . $row['title'] . ' </b></a>';
                                echo '<div class="btn-group" style="position: absolute; right: 2px; top: 2px;" >';
                                echo '<a class="btn fa fa-download"  href="'.$row['filePath'].'" download="'.$row['title'].'_ver'.$row['versionNo'].'_'.basename($row['filePath']).'"></a>';
                                echo '</div>';
                                echo '<div id="collapse' . $row['vid'] . '" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">';
                                echo '<div class="card-body">';
                                echo 'Created by: ' . $row['versionAuthor'] . '<br>';
                                echo 'on: <i>' . date("F j, Y g:i:s A ", strtotime($row['timeCreated'])) . '</i><br>';
                                echo '</div></div></div>';
                            }
                        }
                        echo '</div></div>';
                    }


                ?>
            </div>
        </div>
    </div>
</div>

    <div id="approveModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>">
            <div class="modal-content">
                <div class="modal-header">
                   <h5 class="modal-title"><b>Confirm Approve?</b></h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p>Please provide remarks before confirming.</p>
                        <textarea name="remarks" id="remarks" class="form-control" placeholder="Your remarks..." rows="10" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="hidden" name="documentId" value="<?php echo $documentId; ?>">
                        <button type="submit" name="btnAccept" class="btn btn-primary">Confirm Approve</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

    <div id="rejectModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Confirm Reject?</b></h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p>Please provide remarks before confirming.</p>
                        <textarea name="remarks" id="remarks" class="form-control" placeholder="Required remarks here..." rows="10" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="hidden" name="documentId" value="<?php echo $documentId; ?>">
                        <button type="submit" name="btnReject" class="btn btn-primary">Confirm Reject</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

    <?php

        $query = "SELECT nextStepId, routeName FROM step_routes WHERE currentStepId ='$currentStepId';";
        $rows = $crud->getData($query);
        if (!empty($rows)) {
            foreach ((array)$rows as $key => $row) {
                ?>
                <div id="<?php echo $row['nextStepId'];?>Modal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>">
                            <input type="hidden" name="documentId" value="<?php echo $documentId; ?>">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><b>Confirm '<?php echo $row['routeName'];?>'?</b></h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <p>Please provide remarks before confirming.</p>
                                        <textarea name="remarks" id="remarks" class="form-control" placeholder="Your remarks..." rows="10" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button class="btn btn-primary" type="submit" name="btnRoute" value="<?php echo $row['nextStepId'];?>">Confirm</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
            }
        }
    ?>
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
                    $("#err").html(response);
                    $("#contact-modal").modal('hide');
                    if(response !== 'error') location.href = response;
                },
                error: function(){
                    alert("Error");
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