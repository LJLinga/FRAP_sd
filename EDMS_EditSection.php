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
//Buttons here

if(isset($_POST['btnSave'])){
    $sectionId = $_POST['section_id'];
    $title = $crud->escape_string($_POST['section_title']);
    $sectionNo = $crud->escape_string($_POST['section_number']);
    $content = $crud->escape_string($_POST['section_content']);
    //$parentSectionId = $_POST['section_parent'];
    //$siblingSectionId = $_POST['section_sibling'];
    $crud->execute("UPDATE sections SET title = '$title', sectionNo = '$sectionNo', content = '$content' WHERE id = '$sectionId'");

    if(isset($_POST['toAddDocRefs'])) {
        $toAddDocRefs = $_POST['toAddDocRefs'];
        foreach((array) $toAddDocRefs AS $key => $ref){
            $query = "INSERT INTO section_ref_versions(sectionId,versionId) VALUES ('$sectionId','$ref');";
            $crud->execute($query);
        }
    }

    if(isset($_POST['toRemoveDocRefs'])) {
        $toRemoveDocRefs = $_POST['toRemoveDocRefs'];
        foreach((array) $toRemoveDocRefs AS $key => $ref){
            $query = "DELETE FROM section_ref_versions WHERE sectionId = '$sectionId' AND versionId = '$ref'";
            $crud->execute($query);
        }
    }
}


if(isset($_POST['btnFinish'])){
    $sectionId = $_POST['section_id'];
    $title = $crud->escape_string($_POST['section_title']);
    $sectionNo = $crud->escape_string($_POST['section_number']);
    $content = $crud->escape_string($_POST['section_content']);
    $crud->execute("UPDATE sections SET title = '$title', sectionNo = '$sectionNo', content = '$content', availabilityId='2', lockedById=NULL WHERE id = '$sectionId'");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ViewSection.php?secId=".$sectionId);
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
        header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ManualRevisions.php");
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

    $query = "SELECT su.read, su.write, su.route, su.comment FROM step_roles su
                WHERE su.stepId='$currentStepId' AND su.roleId='$edmsRole' LIMIT 1;";
    $rows = $crud->getData($query);
    if(!empty($rows)){
        foreach((array) $rows as $key => $row){
            $write= $row['write'];
            $route= $row['route'];
            //$comment = $row['comment'];
        }
    }else{
        header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ManualRevisions.php");
    }

}else{
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ManualRevisions.php");
    echo 'nothing found';
}

$page_title = 'Faculty Manual - Edit Section';
include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';
?>
    <div id="content-wrapper">
        <div class="container-fluid">
            <!--Insert success page-->
            <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                <input type="hidden" name="section_id" value="<?php echo $sectionId;?>">
                <div class="row" style="margin-top: 2rem;">
                    <div class="column col-lg-7">
                        <!-- Text input-->
                        <div class="form-group">
                            <label for="section_number">Section Number</label>
                            <input id="section_number" name="section_number" type="text" class="form-control input-md" value="<?php echo $sectionNo;?>"required>
                        </div>
                        <div class="form-group">
                            <label for="section_title">Title</label>
                            <input id="section_title" name="section_title" type="text" class="form-control input-md" value="<?php echo $title;?>" required>
                        </div>
                        <div class="form-group">
                            <label for="section_content">Content</label>
                            <textarea name="section_content" class="form-control" rows="20" id="section_content"><?php echo $content;?></textarea>
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

                    <div id="publishColumn" class="column col-lg-4" style="margin-top: 1rem; margin-bottom: 1rem;">
                        <div class="card" style="margin-bottom: 1rem; ">
                            <div class="card-header"><b>Document References</b></div>
                            <div class="card-body" style="max-height: 20rem; overflow-y: auto;">
                                <span id="refDocuments" style="font-size: 12px;">
                                <?php
                                    $rows = $crud->getData("SELECT d.documentId, CONCAT(e.lastName,', ',e.firstName) AS originalAuthor, v.filePath,
                                                        v.versionId as vid, v.versionNo, v.title, v.timeCreated, pr.id AS processId, pr.processName, s.stepNo, s.stepName,
                                                        (SELECT CONCAT(e.lastName,', ',e.firstName) FROM doc_versions v JOIN employee e ON v.authorId = e.EMP_ID 
                                                        WHERE v.versionId = vid) AS currentAuthor
                                                        FROM documents d JOIN doc_versions v ON d.documentId = v.documentId
                                                        JOIN employee e ON e.EMP_ID = d.firstAuthorId 
                                                        JOIN steps s ON s.id = d.stepId
                                                        JOIN process pr ON pr.id = s.processId 
                                                        JOIN section_ref_versions ref ON ref.versionId = v.versionId
                                                        WHERE ref.sectionId = '$sectionId';");
                                    if(!empty($rows)) {
                                        foreach ((array)$rows as $key => $row) {
                                            $title = $row['title'];
                                            $versionNo = $row['versionNo'];
                                            $originalAuthor = $row['originalAuthor'];
                                            $currentAuthor = $row['currentAuthor'];
                                            $processName = $row['processName'];
                                            $updatedOn = date("F j, Y g:i:s A ", strtotime($row['timeCreated']));
                                            $filePath = $row['filePath'];
                                            $fileName = $title.'_ver'.$versionNo.'_'.basename($filePath);
                                            echo '<div class="card" style="position: relative;">';
                                            echo '<input type="hidden" name="toAddDocRefs[]" class="refDocuments" value="'.$row['vid'].'">';
                                            echo '<a style="text-align: left;" class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse' . $row['vid'] . '" aria-expanded="true" aria-controls="collapse' . $row['vid'] . '"><b>' . $title . ' </b><span class="badge">' . $versionNo . '</span></a>';
                                            echo '<div class="btn-group" style="position: absolute; right: 2px; top: 2px;" >';
                                            echo '<a class="btn fa fa-download"  href="'.$filePath.'" download="'.$fileName.'"></a>';
                                            echo '<a class="btn fa fa-remove" onclick="removeRef(this, &quot;'.$row['vid'].'&quot;)" ></a>';
                                            echo '</div>';
                                            echo '<div id="collapse' . $row['vid'] . '" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">';
                                            echo '<div class="card-body">';
                                            echo 'Process: ' . $processName . '<br>';
                                            echo 'Created by: ' . $originalAuthor . '<br>';
                                            echo 'Modified by: ' . $currentAuthor . '<br>';
                                            echo 'on: <i>' . $updatedOn . '</i><br>';
                                            echo '</div></div></div>';
                                        }
                                    }
                                ?>
                                </span>
                                <span id="toRemoveDocRefs"></span>
                            </div>
                            <div class="card-footer">
                                <button id="btnRefModal" type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalRED"><i class="fa fa-fw fa-link"></i>Add</button>
                            </div>
                        </div>
                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-header"><b>Referenced Minutes</b></div>
                            <div class="card-body" style="max-height: 20rem; overflow-y: scroll;">
                                <span id="noRefsYet">No References</span>
                                <span id="refDocuments" style="font-size: 12px;">
                                </span>
                            </div>
                            <div class="card-footer">
                                <button id="btnRefModal" type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalRED"><i class="fa fa-fw fa-link"></i>Add</button>
                            </div>
                        </div>
                        <div class="card" style="margin-top: 1rem;">
                            <div class="card-header">
                                <b>Section Details</b>
                            </div>
                            <div class="card-body">
                                Status: <b><?php echo $statusName;?></b><br>
                                Stage: <b><?php echo $stepName; ?></b><br>
                                Created by: <b><?php echo $firstAuthorName ?></b><br>
                                Modified by: <b><?php echo $authorName ?></b><br>
                                on <i><b><?php  echo date("F j, Y g:i:s A ", strtotime($lastUpdated));?></b></i><br>
                            </div>
                            <div class="card-footer btn-group">
                                <?php
                                if(isset($write) && $write == '2') {
                                    echo '<button type="submit" class="btn btn-primary" name="btnSave" id="btnSave">Save</button>';
                                    echo '<button type="submit" class="btn btn-warning" name="btnFinish" id="btnFinish">Finish Editing</button>';
                                }
                                ?>
                            </div>
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
    <div id="previewOld" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="previewHeader"></span>
                </div>
                <div class="modal-body">
                    <span class="previewContent"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>    <!-- Modal content-->

        </div>
    </div>
    <div id="modalRED" class="modal fade" role="dialog" data-backdrop="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    <h5 class="modal-title">Reference Document</h5>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" align="center" id="dataTable">
                        <thead>
                        <tr>
                            <th> Document </th>
                            <th> Assigned Process </th>
                            <th width="20px"> Add </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>
    <script>
        $(document).ready( function(){
            $('#btnRefModal').on('click', function(){
                reloadDataTable();
            });
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

        // $('.userinfo').click(function(){
        //     var id = this.id;
        //     var splitid = id.split('_');
        //     var userid = splitid[1];
        //
        //     // AJAX request
        //     $.ajax({
        //         url: 'ajaxfile.php',
        //         type: 'post',
        //         data: {userid: userid},
        //         success: function(response){
        //             // Add response in Modal body
        //             $('.modal-body').html(response);
        //
        //             // Display Modal
        //             $('#empModal').modal('show');
        //         }
        //     });
        // });

        function reloadDataTable(){
            let loadedRefs = [];
            $(".refDocuments").each(function() {
                loadedRefs.push($(this).val());
            });
            $('table').dataTable({
                destroy: true,
                "pageLength": 3,
                "ajax": {
                    "url":"CMS_AJAX_LoadToAddReferences.php",
                    "type":"POST",
                    "data":{ loadedReferences: loadedRefs },
                    "dataSrc": ''
                },
                columns: [
                    { data: "Document" },
                    { data: "Status" },
                    { data: "Action" }
                ]
            });
        }

        function removeRef(element, verId){
            $(element).closest('div.card').remove();
            $('#toRemoveDocRefs').append('<input type="hidden" name="toRemoveDocRefs[]" value="'+verId+'">');
            $('#btnUpdate').show();
        }
        function addRef(element, verId, oA, cA, vN, uO, t, pN, fP, fN){
            $('#noRefsYet').remove();
            $('#btnUpdate').show();
            $('#refDocuments').append('<div class="card" style="background-color: #e2fee2; position: relative;">'+
                '<input type="hidden" name="toAddDocRefs[]" class="refDocuments" value="'+verId+'">'+
                '<a style="text-align: left;" class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse'+verId+'" aria-expanded="true" aria-controls="collapse'+verId+'"><b>'+t+'</b> <span class="badge">'+vN+'</span></a>'+
                '<div class="btn-group" style="position: absolute; right: 2px; top: 2px;" >'+
                '<a class="btn fa fa-download"  href="'+fP+'" download="'+fN+'"></a>'+
                '<a class="btn fa fa-remove" onclick="removeRef(this)" ></a>'+
                '</div>'+
                '<div id="collapse'+verId+'" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">'+
                '<div class="card-body">'+
                'Process: '+pN+'<br>'+
                'Created by: '+oA+'<br>'+
                'Modified by: '+cA+'<br>'+
                'on: <i>'+uO+'</i><br>'+
                '</div></div></div>');
            reloadDataTable();
        }

    </script>
<?php include 'GLOBAL_FOOTER.php' ?>