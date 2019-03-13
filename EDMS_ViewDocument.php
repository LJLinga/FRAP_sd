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
//$edmsRole = 2;
$userId = $_SESSION['idnum'];

if(isset($_GET['docId'])){

    $documentId = $_GET['docId'];

    if(isset($_GET['versId'])){
        $versionId = $_GET['versId'];
    }

    // Load Process and Steps assigned to current document
    $query = "SELECT d.processId, d.stepId, p.processName, s.stepName,
              d.availabilityId, d.lockedById
              FROM documents d 
              JOIN process p ON d.processId = p.id 
              JOIN steps s ON d.stepId = s.id WHERE d.documentId='$documentId';";
    $rows = $crud->getData($query);
    foreach((array) $rows as $key => $row){
        $processId = $row['processId'];
        $currentStepId= $row['stepId'];
        $processName = $row['processName'];
        $stepName = $row['stepName'];
        $availability = $row['availabilityId'];
        $lockedById = $row['lockedById'];
    }

    // Load Current User Permissions
    $query = "SELECT su.read, su.write, su.route, su.comment FROM step_roles su
                WHERE su.stepId='$currentStepId' AND su.roleId='$edmsRole' AND su.processId = $processId LIMIT 1;";
    $rows = $crud->getData($query);
    if(empty($rows)){
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_Dashboard.php");
    }else{
        foreach((array) $rows as $key => $row){
            $read= $row['read'];
            $write= $row['write'];
            $route= $row['route'];
            $comment = $row['comment'];
        }
    }

    if($availability == '1'){
        $route = '1';
        if($lockedById == $userId) $write = '2';
        else $write = '1';
    }

    //Get the rest of the document.
    $query = "SELECT d.firstAuthorId, d.timeFirstPosted, d.availabilityId, v.timeCreated, v.versionId, v.versionNo, v.authorId, v.title, v.filePath, 
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
        $currentAuthor = $row['currentAuthor'];
        $title = $row['title'];
        $filePath = $row['filePath'];
        $availability = $row['availabilityId'];
    }

    if($firstAuthorId == $userId || $currentAuthorId == $userId){
        $read = '2';
        $comment = '2';
    }

}else{
   header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_Dashboard.php");
}

if(isset($_POST['btnUnlock'])){
    $documentId= $_POST['btnUnlock'];
    $crud->execute("UPDATE documents SET availabilityId='2', lockedById=NULL WHERE documentId='$documentId'");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ViewDocument.php?docId=".$documentId);
}

if(isset($_POST['btnLock'])){
    $documentId = $_POST['btnLock'];
    $userId = $_POST['userId'];
    $crud->execute("UPDATE documents SET availabilityId='1', lockedById='$userId' WHERE documentId='$documentId'");
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
    $routeId=$_POST['btnRoute'];
    $rows = $crud->getData("SELECT nextStepId, nextProcessId FROM step_routes WHERE id='$routeId' LIMIT 1");
    foreach ((array)$rows as $key => $row) {
        $nextStepId = $row['nextStepId'];
        $nextProcessId = $row['nextProcessId'];
    }
    $crud->execute("UPDATE documents SET availabilityId='2', stepId='$nextStepId', processId = '$nextProcessId', lockedById=NULL WHERE documentId='$documentId'");
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
                        <a href="http://localhost/FRAP_sd/EDMS_Dashboard.php">Documents</a>
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
                <iframe src = "/ViewerJS/../FRAP_sd/<?php echo $filePath;?>" width='850' style="height:80vh;"; allowfullscreen webkitallowfullscreen></iframe>

                <div class="card" style="margin-top: 1rem;">
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
                        Title: <b><?php echo $title; ?></b><br>
                        Version No.: <b><?php echo $versionNo; ?></b>
                    </div>
                    <div class="card-body" >
                        Process: <b><?php echo $processName; ?></b><br>
                        Stage: <b><?php echo $stepName; ?></b><br>
                        Created by <b><?php echo $originalAuthor; ?></b><br>
                        <i>on <b><?php echo date("F j, Y g:i:s A ", strtotime($timeFirstPosted)); ?></b></i><br>
                        Modified by <b><?php echo $currentAuthor; ?></b><br>
                        <i>on <b><?php echo date("F j, Y g:i:s A ", strtotime($timeUpdated)); ?></b></i>
                    </div>
                </div>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b>Document Actions</b>
                    </div>
                    <div class="card-body">
                        <div class="btn-group btn-group-vertical" style="width: 100%;">
                            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>">
                            <?php

                                if($processId == '99' || $currentStepId == '1'){
                                    echo '<button class="btn btn-info" style="text-align: left" type="button" id="btnAssignTask">';
                                }else if(isset($route) && $route=='2') {
                                    $query = "SELECT id, routeName FROM step_routes WHERE stepId ='$currentStepId' AND processId = '$processId';";
                                    $rows = $crud->getData($query);
                                    if (!empty($rows)) {
                                        echo '<input type="hidden" name="documentId" value="'.$documentId.'">';
                                        foreach ((array)$rows as $key => $row) {
                                            echo '<button class="btn btn-primary" style="text-align: left; width: 100%" type="submit" name="btnRoute" value="'.$row['id'].'">'.$row['routeName'].'</button>';
                                        }
                                    }
                                }
                                if(isset($write) && $write=='2' && $availability=='2'){
                                    echo '<input type="hidden" name="filePath" value="'.$filePath.'">';
                                    echo '<input type="hidden" name="userId" value="'.$userId.'">';
                                    echo '<button class="btn btn-default" type="submit" name="btnLock" value="'.$documentId.'" style="text-align: left; width:100%;">Lock and Edit</button>';
                                }else if(isset($write) && $write=='2' && $availability=='1'){
                                    echo '<button class="btn btn-default" type="submit" name="btnUnlock" id="btnUnlock" value="'.$documentId.'" style="text-align: left; width: 100%;">Cancel Editing</button>';
                                    echo '<button type="button" class="btn btn-default" id="btnUpload" data-toggle="modal" data-target="#uploadModal" style="text-align: left; width: 100%;">Upload New Version</button>';
                                }
                                ?>
                                <a href="<?php echo $filePath?>" download><button type="button" class="btn btn-default" style="text-align: left; width: 100%;">Download</button></a>
                                <button type="button" name="btnArchive" class="btn btn-default" style="text-align: left; width: 100%;">Archive</button>
                            </form>

                        </div>
                    </div>
                </div>
                <?php
                    $query = "SELECT v.versionId, v.timeCreated, v.versionNo, v.title, v.filePath, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS versionAuthor 
                              FROM doc_versions v JOIN employee e ON v.authorId = e.EMP_ID 
                              WHERE v.documentId = '$documentId' AND v.versionId != '$versionId' ORDER BY v.versionId DESC;";
                    $rows = $crud->getData($query);
                    if (!empty($rows)) {

                        echo '<div class="card" style="margin-top: 1rem;"><div class="card-header"><b>Version History</b></div>
                                <div class="card-body" style="max-height: 100rem; overflow: auto;" >';
                        foreach ((array)$rows as $key => $row) {
                            echo '<div class="card-body" style="position: relative;">
                                        <span class="label label-default">Version '.$row['versionNo'].'</span>
                                        <b>'.$row['title'].'</b><br>
                                        '.date("F j, Y g:i:s A ", strtotime($row['timeCreated'])).'<br>
                                        <div class="btn-group-sm" style="position: absolute;right: 10px;top: 10px;">
                                            <a class="btn fa fa-download" href="'.$row['filePath'].'" download></a>
                                            <button type="button" class="btn btn-sm">Revert</button>
                                        </div>
                                    </div>';
                        }
                        echo '</div></div>';
                    }
                ?>
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
                            <label for="documentTitle">Title</label>
                            <input type="text" name="versionTitle" id="versionTitle" class="form-control" placeholder="Title" required>
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
                            <label for="file">Upload</label>
                            <input type="file" class="form-control-file" id="file" name="file" required>
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
                        load_comment(documentId);
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
    });
</script>
<?php include 'GLOBAL_FOOTER.php';?>