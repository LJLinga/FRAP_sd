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
    $query = "SELECT d.processId, d.currentStepId, p.processName, s.stepName,
              d.availabilityId, d.lockedById
              FROM documents d 
              JOIN process p ON d.processId = p.id 
              JOIN steps s ON d.currentStepId = s.id WHERE d.documentId='$documentId';";
    $rows = $crud->getData($query);
    foreach((array) $rows as $key => $row){
        $processId = $row['processId'];
        $currentStepId= $row['currentStepId'];
        $processName = $row['processName'];
        $stepName = $row['stepName'];
        $availability = $row['availabilityId'];
        $lockedById = $row['lockedById'];
    }

    $userId = $_SESSION['idnum'];

    // Load User Permissions
    $query = "SELECT su.read, su.write, su.route, su.comment FROM step_users su
                JOIN employee e ON su.userId = e.EMP_ID
                WHERE su.stepId='$currentStepId' AND e.EMP_ID = '$userId' LIMIT 1;";
    $rows = $crud->getData($query);
    if(empty($rows)){
        // If user does not have individual permissions, check group permissions of user.
        // Individual rights supersede collective rights.
        // If user belongs to multiple groups in the same step, query only the 1st one.
        $query= "SELECT sg.read, sg.write, sg.route, sg.comment FROM step_groups sg 
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
        $currentAuthor = $row['originalAuthor'];
        $title = $row['title'];
        $filePath = $row['filePath'];
        $availability = $row['availabilityId'];
    }
}

if(isset($_POST['btnUnlock'])){
    $documentId= $_POST['btnUnlock'];
    $crud->execute("UPDATE documents SET availabilityId='2', lockedById=NULL WHERE documentId='$documentId'");
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_ViewDocument.php?docId=" . $documentId);
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
                                if($processId == '1' || $currentStepId == '1'){
                                    echo '<button class="btn btn-info" style="text-align: left" type="button" id="btnAssignTask">';
                                }else if(isset($route) && $route=='2') {
                                    $query = "SELECT routeName, nextProcessId, nextStepId FROM routes WHERE stepId ='$currentStepId' AND processId = '$processId';";
                                    $rows = $crud->getData($query);
                                    if (!empty($rows)) {
                                        foreach ((array)$rows as $key => $row) {
                                            $nextStepId = $row['nextStepId'];
                                            $nextProcessId = $row['nextStepId'];
                                            echo '<button class="btn btn-info" style="text-align: left" type="submit">' . $row['routeName'] . '</button>';
                                        }
                                    }
                                }


                                if(isset($write) && $write=='2' && $availability=='2'){
                                    echo '<form method="POST" id="downloadForm">';
                                    echo '<input type="hidden" name="filePath" value="'.$filePath.'" />';
                                    echo '<input type="hidden" name="userId" value="'.$userId.'" />';
                                    echo '<button class="btn btn-default" type="submit" name="btnLock" value="'.$documentId.'" style="text-align: left; width:100%;">Download and Edit</button>';
                                    echo '</form>';
                                }else if(isset($write) && $write=='2' && $availability=='1'){
                                    echo '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
                                    echo '<button class="btn btn-default" type="submit" name="btnUnlock" id="btnUnlock" value="'.$documentId.'" style="text-align: left; width: 100%;">Cancel Editing</button>';
                                    echo '</form>';
                                }else{
                                    echo '<a href="'.$filePath.'" class="btn btn-default" style="text-align: left" download>Download</a>';
                                }

                                ?>
                            <?php if(isset($write) && $write=='2'){ echo '<button class="btn btn-default" style="text-align: left">Upload New Version</button>' ; }?>
                            <button class="btn btn-default" style="text-align: left">Archive</button>
                         </div>
                    </div>
                </div>

                <?php

                    $query = "SELECT v.versionId, v.timeCreated, v.versionNo, v.title, v.filePath, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS versionAuthor 
                              FROM doc_versions v JOIN employee e ON v.authorId = e.EMP_ID 
                              WHERE v.documentId = '$documentId' AND v.versionId != '$versionId';";
                    $rows = $crud->getData($query);
                    if (!empty($rows)) {

                        echo '<div class="card" style="margin-top: 1rem;"><div class="card-header"><b>Version History</b></div>
                                <div class="card-body" style="max-height: 20rem; overflow: auto;" >';
                        foreach ((array)$rows as $key => $row) {
                            echo '<div class="card-body" style="position: relative;">
                                        <span class="label label-default">Version '.$row['versionNo'].'</span><br>
                                        <b>'.$row['title'].'</b><br>
                                        '.$row['timeCreated'].'<br>
                                        <div class="btn-group-sm" style="position: absolute;right: 10px;top: 10px;">
                                            <a class="btn btn-sm" href="'.$row['filePath'].'" download>Download</a>
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
                        <input type="hidden" name="documentId" id="documentId" value="<?php echo $documentId?>" />
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" name="submit" id="submit" class="btn btn-info" value="Submit"/>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>

<script>

    $(document).ready(function(){

        let documentId = "<?php echo $documentId?>";

        $('#downloadForm').on('submit', function(event){ // REFRESH PAGE AFTER FORM SUBMIT
            event.preventDefault();
            var form_data = $(this).serialize();
            alert(form_data);
            $.ajax({
                url:"EDMS_AJAX_DownloadDocument.php",
                method:"POST",
                data:form_data,
                dataType:"JSON",
                success:function(data) {
                    if(data !== 'error') {
                        location.href = "http://localhost/FRAP_sd/EDMS_ViewDocument.php?docId="+data;
                    } else {
                        alert("download error");
                    }
                }
            });
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
