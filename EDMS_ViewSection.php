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
}

if(isset($_POST['btnEdit'])){
    $sectionId = $_POST['section_id'];
    $lockedById = $_POST['locked_by_id'];
    $rows = $crud->getData("SELECT availabilityId FROM sections WHERE id = '$sectionId'");
    foreach((array) $rows as $key => $row){
        $availabilityId = $row['availabilityId'];
    }
    if($availabilityId == '2'){
        $crud->execute("UPDATE sections SET availabilityId='1', lockedById='$userId' WHERE id='$sectionId'");
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_EditSection.php?secId=".$sectionId);
}

if(isset($_POST['btnAccept'])){
    //Clickable only when unlocked. Thesis 2.
    $sectionId = $_POST['btnAccept'];
    $rows = $crud->getData("SELECT availabilityId FROM sections WHERE id = '$sectionId'");
    foreach((array) $rows as $key => $row){
        $availabilityId = $row['availabilityId'];
    }
    if($availabilityId == '2'){
        $crud->execute("UPDATE sections SET statusId='2', approvedById='$userId' WHERE id='$sectionId'");
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_ViewSection.php?secId=".$sectionId);
}

if(isset($_POST['btnReject'])){
    $sectionId= $_POST['btnReject'];
    $rows = $crud->getData("SELECT availabilityId FROM sections WHERE id = '$sectionId'");
    foreach((array) $rows as $key => $row){
        $availability = $row['availabilityId'];
    }
    if($availability == '2'){
        $crud->execute("UPDATE sections SET statusId='3', approvedById='$userId' WHERE id='$sectionId'");
    }

    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_ViewSection.php?secId=".$sectionId);
}

if(isset($_POST['btnRoute'])){
    $nextStepId = $_POST['btnRoute'];
    $sectionId = $_POST['section_id'];
    $lockedById = $_POST['locked_by_id'];
    $rows = $crud->getData("SELECT availabilityId FROM sections WHERE id = '$sectionId'");
    foreach((array) $rows as $key => $row){
        $availabilityId = $row['availabilityId'];
    }
    if($availabilityId == '2'){
        $crud->execute("UPDATE sections SET statusId = '1', stepId='$nextStepId' WHERE id ='$sectionId'");
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_ViewSection.php?secId=".$sectionId);
}

if(isset($_GET['secId'])){
    $sectionId = $_GET['secId'];

    $rows = $crud->getData("SELECT d.stepId, p.processName, s.stepName, s.isFinal,
              d.availabilityId, d.lockedById, d.statusId, st.status
              FROM sections d
              JOIN steps s ON d.stepId = s.id 
              JOIN section_status st ON st.id = d.statusId 
              JOIN process p ON s.processId = p.id 
              WHERE d.id='$sectionId';");
    if(!empty($rows)){
        foreach((array) $rows as $key => $row){
            $currentStepId= $row['stepId'];
            $processName = $row['processName'];
            $stepName = $row['stepName'];
            $availabilityId = $row['availabilityId'];
            $lockedById = $row['lockedById'];
            $statusId = $row['statusId'];
            $statusName = $row['status'];
            $isFinal = $row['isFinal'];
        }
    }

    if($availabilityId == '1' && $lockedById != $userId){
        $rows = $crud->getData("SELECT CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS name FROM employee e WHERE e.EMP_ID = '$lockedById' LIMIT 1;");
        if(!empty($rows)){
            foreach((array) $rows as $key=> $row){
                $lockedByName = $row['name'];
            }
        }
    }

    $rows = $crud->getData("SELECT s.authorId, s.firstAuthorId, s.approvedById, s.sectionNo, s.title, s.content, s.timeCreated, s.lastUpdated,
                                    CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS firstAuthorName,
                                    (SELECT CONCAT(e.LASTNAME,', ',e.FIRSTNAME) FROM employee e2 WHERE e2.EMP_ID = s.authorId) AS authorName
                                    FROM facultyassocnew.sections s
                                    JOIN employee e ON e.EMP_ID = s.firstAuthorId
                                    WHERE s.id = '$sectionId';");
    if(!empty($rows)){
        foreach((array) $rows as $key => $row){
            $authorId = $row['authorId'];
            $authorName = $row['authorName'];
            $firstAuthorId = $row['firstAuthorId'];
            $firstAuthorName = $row['firstAuthorName'];
            $approvedById = $row['approvedById'];
            $sectionNo = $row['sectionNo'];
            $title = $row['title'];
            $content = $row['content'];
            $timeCreated = $row['timeCreated'];
            $lastUpdated = $row['lastUpdated'];
        }
    }

    $rows = $crud->getData("SELECT CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS name FROM employee e WHERE e.EMP_ID = '$approvedById' LIMIT 1;");
    if(!empty($rows)){
        foreach((array) $rows as $key=> $row){
            $approvedByName = $row['name'];
        }
    }

    $query = "SELECT su.read, su.write, su.route, su.comment FROM step_roles su
                WHERE su.stepId='$currentStepId' AND su.roleId='$edmsRole' LIMIT 1;";
    $rows = $crud->getData($query);
    if(!empty($rows)){
        foreach((array) $rows as $key => $row){
            $read = $row['read'];
            $write = $row['write'];
            $route = $row['route'];
            $comment = $row['comment'];
        }
    }else{
        //need to add a read permission in the db if user wants to access files continiously
        //$read = 2;
        //$write = 1;
        //$route = 1;
        //$comment = 2;
        //header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ManualRevisions.php");
    }

    if($revisions=='closed'){
        $read = 2;
        $write = 1;
        $route = 1;
        $comment = 2;
    }

}else{
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ManualRevisions.php");
    echo 'nothing found';
}

$page_title = 'Faculty Manual - Edit Section';
include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';
?>
    <script>
        $(document).ready( function(){

        });
    </script>

    <div id="content-wrapper">
        <div class="container-fluid">
            <!--Insert success page-->
            <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                <input type="hidden" name="section_id" value="<?php echo $sectionId;?>">
                <div class="row" style="margin-top: 2rem;">
                    <div class="column col-lg-7" >
                        <!-- Text input-->
                        <div class="card" style="margin-top: 1rem;">
                            <div class="card-body">
                                <h3 class="card-title"><b>Section <?php echo $sectionNo.' '.$title; ?></b></h3>
                                <p class="card-text" style="text-align: justify;"><?php echo $content; ?></p>
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

                    <div id="publishColumn" class="column col-lg-4" style="margin-bottom: 1rem;">
                        <?php
                        $rows = $crud->getData("SELECT CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName, 
                                                                v.filePath, v.title, v.versionNo, v.timeCreated, d.lastUpdated,
                                                                stat.statusName, s.stepNo, s.stepName, t.type,
                                                                pr.processName, v.versionId AS vid,
                                                                (SELECT CONCAT(e.FIRSTNAME,', ',e.LASTNAME) FROM employee e2 WHERE e2.EMP_ID = d.firstAuthorId) AS firstAuthorName 
                                                                FROM doc_versions v 
                                                                JOIN documents d ON v.documentId = d.documentId
                                                                JOIN section_ref_versions ref ON ref.versionId = v.versionId
                                                                JOIN employee e ON e.EMP_ID = v.authorId
                                                                JOIN doc_status stat ON stat.id = d.statusId 
                                                                JOIN doc_type t ON t.id = d.typeId
                                                                JOIN steps s ON s.id = d.stepId
                                                                JOIN process pr ON pr.id = s.processId
                                                                WHERE ref.sectionId = $sectionId");

                        if(!empty($rows)) {
                            echo '<div class="card" style="margin-top: 1rem;">
                                        <div class="card-header"><b>Referenced Minutes</b></div>
                                        <div class="card-body">';
                            foreach ((array)$rows as $key => $row) {
                                $title = $row['title'];
                                $versionNo = $row['versionNo'];
                                $originalAuthor = $row['firstAuthorName'];
                                $currentAuthor = $row['authorName'];
                                $processName = $row['processName'];
                                $updatedOn = date("F j, Y g:i:s A ", strtotime($row['timeCreated']));
                                $filePath = $row['filePath'];
                                $fileName = $title.'_ver'.$versionNo.'_'.basename($filePath);
                                echo '<div class="card" style="position: relative;">';
                                echo '<input type="hidden" class="refDocuments" value="'.$row['vid'].'">';
                                echo '<a style="text-align: left;" class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse' . $row['vid'] . '" aria-expanded="true" aria-controls="collapse' . $row['vid'] . '"><b>' . $title . ' </b><span class="badge">' . $versionNo . '</span></a>';
                                echo '<div class="btn-group" style="position: absolute; right: 2px; top: 2px;" >';
                                echo '<a class="btn fa fa-download"  href="'.$filePath.'" download="'.$fileName.'"></a>';
                                echo '</div>';
                                echo '<div id="collapse' . $row['vid'] . '" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">';
                                echo '<div class="card-body">';
                                echo 'Process: ' . $processName . '<br>';
                                echo 'Created by: ' . $originalAuthor . '<br>';
                                echo 'Modified by: ' . $currentAuthor . '<br>';
                                echo 'on: <i>' . $updatedOn . '</i><br>';
                                echo '</div></div></div>';
                            }
                            echo '</div></div>';
                        }
                        ?>
                        <div class="card" style="margin-top: 1rem;">
                            <div class="card-header">
                                <b>Section Actions</b>
                            </div>
                            <div class="card-body">
                                Status: <b><?php echo $statusName;?></b> (by <?php echo $approvedByName;?>)<br>
                                Stage: <b><?php echo $stepName; ?></b><br>
                                Created by: <b><?php echo $firstAuthorName ?></b><br>
                                Modified by: <b><?php echo $authorName ?></b><br>
                                on <i><b><?php  echo date("F j, Y g:i:s A ", strtotime($lastUpdated));?></b></i>
                            </div>
                            <div class="card-body">
                                <div class="btn-group btn-group-vertical" style="width: 100%;">
                                    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>">
                                        <input type="hidden" name="locked_by_id" value="<?php echo $lockedById;?>">
                                        <?php
                                        $disabledButtons = "enabled";
                                        if($availabilityId == '1'){
                                            $disabledButtons = "disabled";
                                        }
                                        if(isset($route) && $route=='2') {
                                            if($isFinal == '2'){
                                                if($statusId == '1'){
                                                    echo '<button class="btn btn-success" style="text-align: left; width: 100%" type="submit" name="btnAccept" value="'.$sectionId.'" '.$disabledButtons.'>Accept</button>';
                                                    echo '<button class="btn btn-danger" style="text-align: left; width: 100%" type="submit" name="btnReject" value="'.$sectionId.'" '.$disabledButtons.'>Reject</button>';
                                                }else if($statusId == '2'){
                                                    echo '<button class="btn btn-danger" style="text-align: left; width: 100%" type="submit" name="btnReject" value="'.$sectionId.'" '.$disabledButtons.'>Reject</button>';
                                                }else if($statusId == '3'){
                                                    echo '<button class="btn btn-success" style="text-align: left; width: 100%" type="submit" name="btnAccept" value="'.$sectionId.'" '.$disabledButtons.'>Accept</button>';
                                                }
                                            }
                                            $query = "SELECT nextStepId, routeName FROM step_routes WHERE currentStepId ='$currentStepId';";
                                            $rows = $crud->getData($query);
                                            if (!empty($rows)) {
                                                echo '<input type="hidden" name="sectionId" value="'.$sectionId.'">';
                                                foreach ((array)$rows as $key => $row) {
                                                    echo '<button class="btn btn-primary" style="text-align: left; width: 100%" type="submit" name="btnRoute" value="'.$row['nextStepId'].'" '.$disabledButtons.'>'.$row['routeName'].'</button>';
                                                }
                                            }
                                        }
                                        if(isset($write) && $write=='2'){
                                            if($availabilityId == '1' && $userId != $lockedById) {
                                                echo '<button class="btn btn-default" type="submit" name="btnEdit" style="text-align: left; width:100%;" disabled>Lock and Edit</button>';
                                            }else if($availabilityId == '1' && $userId == $lockedById){
                                                echo '<button class="btn btn-default" type="submit" name="btnEdit" style="text-align: left; width:100%;">Continue Editing</button>';
                                            }
                                            else{
                                                echo '<button class="btn btn-default" type="submit" name="btnEdit" style="text-align: left; width:100%;">Lock and Edit</button>';
                                            }
                                            //echo 'button type="button" name="btnArchive" class="btn btn-default" style="text-align: left; width: 100%;">Archive</button>';
                                        }
                                        //echo $edmsRole.','.$read.','.$write.','.$route;
                                        ?>
                                    </form>
                                </div>
                            </div>
                            <?php if (isset($lockedByName)) {
                                echo '<div class="card-footer">';
                                echo 'Section is locked by ' . $lockedByName . ' for editing.';
                                echo '</div>';
                            }
                            ?>
                        </div>
                        <?php
                        $query = "SELECT v.timeCreated, v.title, v.sectionNo, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS versionAuthor 
                                  FROM section_versions v 
                                  JOIN employee e ON v.authorId = e.EMP_ID 
                                  WHERE v.sectionId = '$sectionId' AND v.timeCreated != '$lastUpdated' ORDER BY v.timeCreated DESC;";
                        $rows = $crud->getData($query);
                        if (!empty($rows)) {

                            echo '<div class="card" style="margin-top: 1rem;">';
                            echo '<div class="card-header"><b>Version History</b></div>';
                            echo '<div class="card-body" style="max-height: 50vh; overflow-y: auto;">';
                            if(!empty($rows)) {
                                foreach ((array)$rows as $key => $row) {
                                    echo '<div class="card" style="margin-bottom: 1rem;">';
                                    echo '<div class="card-body">';
                                    echo '<span class="badge">Version ' . $row['timeCreated'] . '</span> ';
                                    echo '<button type="button" id="btnPreview" class="btn btn-default btn-sm">Preview</button><br>';
                                    echo '<b>Section '.$row['sectionNo'].': '. $row['title'] . ' </b><br>';
                                    echo 'Created by: ' . $row['versionAuthor'] . '<br>';
                                    echo 'on: <i>' . date("F j, Y g:i:s A ", strtotime($row['timeCreated'])) . '</i><br>';
                                    echo '</div></div>';
                                }
                            }
                            echo '</div></div>';
                        }

                        ?>
                        <!-- Button -->
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal by xtian pls dont delete hehe -->
    <div class="modal fade" id="confirm-submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Confirm Action
                </div>
                <div class="modal-body">
                    Are you sure you want to <b id="changeText"></b> ?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a href="#" id="submit" class="btn btn-success success">Yes, I'm sure</a>
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
<script>
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
                    load_comment(postId);
                }
            }
        })
    });

    setInterval(function() {
        load_comment('<?php echo $sectionId; ?>');
    }, 1000);

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