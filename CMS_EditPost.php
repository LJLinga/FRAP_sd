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
include('GLOBAL_CMS_ADMIN_CHECKING.php');

//hardcoded value for userType, will add MYSQL verification
$userId = $_SESSION['idnum'];
$mode = 'view';
$authorId = '';
$availability = '2';
$cmsRole = $_SESSION['CMS_ROLE'];
$lockedById = '';
$archivedById = '';

if(!empty($_GET['postId'])){

    $postId = $_GET['postId'];

    $rows = $crud->getData("SELECT p.authorId, p.reviewedById, p.publisherId, p.archivedById, p.lockedById, p.availabilityId, p.statusId FROM posts p WHERE p.id = '$postId'");
    foreach((array) $rows as $key => $row){
        $authorId = $row['authorId'];
        $status = $row['statusId'];
        $availability = $row['availabilityId'];
        $reviewerId = $row['reviewedById'];
        $lockedById = $row['lockedById'];
        $publisherId = $row['publisherId'];
        $archivedById = $row['archivedById'];
    }

    if(($status == '1' && $authorId == $userId) || ($status == '2' && $cmsRole == '3') || (($status == '3' || $status == '4') && $cmsRole == '4' ) || ($status == '5' && $archivedById == $userId)){
        //can view, can click button
        $mode = 'view_with_button';
    }else if(($status == '2' && $authorId == $userId) || ($status == '3' || $status == '4' && ($authorId == $userId || $reviewerId == $userId))){
        $mode = 'view';
    }else{
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_PostsDashboard.php");
    }

    if($availability == '1'){
        $rows = $crud->getData("SELECT p.lockedById, CONCAT(e.firstName,' ', e.lastName) AS lockedByName FROM posts p JOIN employee e ON p.lockedById = e.EMP_ID WHERE p.id = '$postId'");
        foreach((array) $rows as $key => $row){
            $lockedById = $row['lockedById'];
            $lockedByName = $row['lockedByName'];
        }
        if($lockedById == $userId){
            $mode = 'edit';
        }
    }

    $rows = $crud->getData("SELECT 
            p.title,
            CONCAT(u.firstName,' ', u.lastName) AS author,
            p.body, p.permalink,
            p.firstCreated,
            p.lastUpdated,
            p.statusId,
            p.previousStatusId,
            s.description
        FROM
            employee u
                JOIN
            posts p ON p.authorId = u.EMP_ID
                JOIN
            post_status s ON p.statusId = s.id
        WHERE
            p.id = '$postId'   ");

    foreach ((array) $rows as $key => $row) {
        $title = $row['title'];
        $body = $row['body'];
        $permalink = $row['permalink'];
        $author = $row['author'];
        $status = $row['statusId'];
        $prevStatus = $row['previousStatusId'];
        $firstPosted = $row['firstCreated'];
        $lastUpdated = $row['lastUpdated'];
        $statusDesc = $row['description'];
    }

    if($status == '3'){
        $pubQuery= $crud->getData(" 
            SELECT 
                CONCAT(pub.firstName,' ',pub.lastName) AS reviewer
            FROM
                posts p
                    JOIN
                employee pub ON pub.EMP_ID = p.reviewedById
            WHERE
                p.id = '$postId' ;
        ");
        foreach((array) $pubQuery as $key => $row){
            $reviewer = $row['reviewer'];
        }
    }else if($status == '4'){
        $pubQuery= $crud->getData(" 
            SELECT 
                CONCAT(pub.firstName,' ',pub.lastName) AS publisher
            FROM
                posts p
                    JOIN
                employee pub ON pub.EMP_ID = p.publisherId
            WHERE
                p.id = '$postId';
        ");
        foreach((array) $pubQuery as $key => $row){
            $publisher = $row['publisher'];
        }
    }

    $head = "Edit: ".$title;

}else{
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_PostsDashboard.php");
}

if(isset($_POST['btnEdit'])){
    $rows = $crud->getData("SELECT p.availabilityId FROM posts p WHERE p.id = '$postId'");
    foreach((array) $rows as $key => $row){
        $availability = $row['availabilityId'];
    }
    if($availability == '2'){
        $availQuery = "UPDATE posts SET lockedById='$userId', availabilityId='1' WHERE id='$postId'";
        $crud->execute($availQuery);
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_EditPost.php?postId=" . $postId);
}

if(isset($_POST['btnUnlock'])){
    $availQuery = "UPDATE posts SET availabilityId='2' WHERE id='$postId'";
    $crud->execute($availQuery);
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_EditPost.php?postId=" . $postId);
}

if(isset($_POST['btnRestore'])){
    $status = $_POST['btnRestore'];
    $crud->execute("UPDATE posts SET statusId = '$status' WHERE id='$postId';");
}

if(isset($_POST['btnSubmit'])) {

    $title = $crud->escape_string($_POST['post_title']);
    $body = $crud->escape_string($_POST['post_content']);
    $status = $_POST['btnSubmit'];

    if(isset($_POST['toRemoveDocRefs'])) {
        $toRemoveDocRefs = $_POST['toRemoveDocRefs'];
        foreach((array) $toRemoveDocRefs AS $key => $ref){
            $query = "DELETE FROM post_ref_versions WHERE postId = '$postId' AND versionId = '$ref'";
            $crud->execute($query);
        }
    }

    if(isset($_POST['toAddDocRefs'])) {
        $toAddDocRefs = $_POST['toAddDocRefs'];
        foreach((array) $toAddDocRefs AS $key => $ref){
            $query = "INSERT INTO post_ref_versions(postId,versionId) VALUES ('$postId','$ref');";
            $crud->execute($query);
        }
    }

    if(isset($_POST['toRemovePolls'])) {
        $toRemovePolls = $_POST['toRemovePolls'];
        foreach((array) $toRemovePolls AS $key => $ref){
            $query = "DELETE FROM polls WHERE id = '$ref'";
            $crud->execute($query);
        }
    }

    if(isset($_POST['toUpdatePollId']) && isset($_POST['toUpdatePollQuestion']) && isset($_POST['toUpdateTypeId'])){
        $typeId = $_POST['toUpdateTypeId'];
        $question = $_POST['toUpdatePollQuestion'];
        $pollId = $_POST['toUpdatePollId'];
        $crud->execute("UPDATE polls SET typeId = '$typeId', question = '$question' WHERE id = '$pollId'");
    }

    if(isset($_POST['toUpdateOptionId']) && isset($_POST['toUpdateOptionContent'])) {
        $pollId = $_POST['toUpdatePollId'];
        $toUpdateId = $_POST['toUpdateOptionId'];
        $toUpdateContent = $_POST['toUpdateOptionContent'];
        foreach((array) $toUpdateContent AS $key => $ref){
            $optionId = $toUpdateId[$key];
            $crud->execute("UPDATE poll_options SET response = '$ref' WHERE pollId = '$pollId' AND optionId = '$optionId';");
        }
    }

    if(isset($_POST['option']) && isset($_POST['post_question'])) {
        $question = $_POST['post_question'];
        $typeId = $_POST['responseType'];
        $pollId = $crud->executeGetKey("INSERT INTO polls(postId, typeId, question) VALUES ('$postId','$typeId','$question')");
        $options = $_POST['option'];
        foreach((array) $options AS $key => $ref){
            $query = "INSERT INTO poll_options(pollId,response) VALUES ('$pollId','$ref');";
            $crud->execute($query);
        }
    }

    if(empty($status)){
        $crud->execute("UPDATE posts SET title='$title', body='$body' WHERE id='$postId';");
    }else if($crud->execute("UPDATE posts SET title='$title', body='$body', statusId='$status' WHERE id='$postId';")) {
        if($status=='3'){
            // we can add an UNPUBLISHERID in the future;
            $crud->execute("UPDATE posts SET reviewedById='$userId' WHERE id='$postId';");
        }else if($status=='4' && $cmsRole=='4'){
            $crud->execute("UPDATE posts SET publisherId='$userId' WHERE id='$postId';");
            $result = $crud->execute("SELECT permalink FROM posts WHERE id='$postId' AND permalink IS NULL");
            if(empty($result[0]['permalink'])) {
                include('CMS_FUNCTION_Permalink.php');
                $permalink = generate_permalink($title);
                $crud->execute("UPDATE posts SET permalink='$permalink' WHERE id='$postId' AND permalink IS NULL");
            }
        }else if($status=='5'){
            $crud->execute("UPDATE posts SET archivedById='$userId' WHERE id='$postId';");
        }
        $crud->execute("UPDATE posts SET availabilityId='2' WHERE id='$postId'");
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_EditPost.php?postId=" . $postId);
}


$page_title = 'Santinig - Edit Post';
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR.php';
?>
<style>

</style>
    <script>
    $(document).ready( function(){

        let mode = '<?php echo $mode; ?>';
        let postId = "<?php echo $postId?>";
        let content = $('#post_content');

        $('#btnUpdate').hide();
        $('#form :input').on('keyup', function(){
            $('#btnUpdate').show();
        });

        content.froalaEditor({
            videoUpload: false,
            imageUploadURL: 'CMS_SERVER_INCLUDES/CMS_SERVER_IMAGE_Upload.php',
            fileUpload: false,
            width: 750,
            toolbarInline: false
        });

        if(mode==='view' || mode==='view_with_button'){
            content.froalaEditor("edit.off");
            $('#form :input').attr("disabled", true);
            $('.btn').attr("disabled", false);
            $('.removePoll').attr("disabled", true);
            $('#btnAddQuestion').attr("disabled", true);
        }

        content.on('froalaEditor.contentChanged', function (e, editor) {
            $('#btnUpdate').show();
        });

        content.froalaEditor('html.set', '<?php echo $body?>');

        $('#comment_form').on('submit', function(event){
            event.preventDefault();
            $('#myModal').modal('toggle');
            var form_data = $(this).serialize();
            $.ajax({
                url:"CMS_AJAX_AddEditComment.php",
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
            load_comment(postId);
        }, 1000);

        function load_comment(postId)
        {
            $.ajax({
                url:"CMS_AJAX_FetchEditComments.php",
                method:"POST",
                data:{postId: postId},
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

        $('#btnRefModal').on('click', function(){
            reloadDataTable();
        });

    });
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
    function removePoll(element, pollId){
        $(element).closest('div.card').remove();
        $('#questionCardArea').append("<button type=\"button\" class=\"btn btn-default\" onclick=\"addPoll(this)\"><i class=\"fa fa-fw fa-plus\"></i>Add Question</button>");
        $('#toRemovePolls').append('<input type="hidden" name="toRemovePolls[]" value="'+pollId+'">');
    }
    function removeResponse(element){
        $(element).closest('.fieldRow').remove();
    }
    function addResponse(element){
        $('.fieldRow').last().after('<div class="row fieldRow"><br>\n' +
            '                                        <div class="col-lg-10">\n' +
            '                                            <input name="option[]" type="text" class="form-control input-md option-input" placeholder="Add an answer" required>\n' +
            '                                        </div>\n' +
            '                                        <div class="col-lg-2">\n' +
            '                                            <button type="button" class="btn btn-danger" onclick="removeResponse(this)"><i class="fa fa-trash"></i></button>\n' +
            '                                        </div>\n' +
            '                                    </div>');
    }
    function addPoll(element){
        $(element).remove();
        $('#questionCardArea').append("<div class=\"card\" id=\"questionCard\">\n" +
            "                            <div class=\"card-header\">\n" +
            "                                <div class=\"row fieldRow\">\n" +
            "                                    <div class=\"col-lg-2\">\n" +
            "                                        <b>Question: </b>\n" +
            "                                    </div>\n" +
            "                                    <div class=\"col-lg-9\">\n" +
            "                                        <input id=\"post_question\" name=\"post_question\" type=\"text\" placeholder=\"Put your question here...\" class=\"form-control input-md\" required>\n" +
            "                                    </div>\n" +
            "                                    <div class=\"col-lg-1\">\n" +
            "                                        <button type=\"button\" class=\"btn btn-danger removePoll\" onclick=\"removePoll(this)\"><i class=\"fa fa-trash\"></i></button>\n" +
            "                                    </div>\n" +
            "                                </div>\n" +
            "                            </div>\n" +
            "                            <div class=\"card-body\">\n" +
            "                                <div class=\"form-group\">\n" +
            "                                    <label>Response Type</label>\n" +
            "                                    <select name=\"responseType\" class=\"form-control\">\n" +
            "                                        <option value=\"1\" selected>Single Response</option>\n" +
            "                                        <option value=\"2\">Multiple Response</option>\n" +
            "                                    </select>\n" +
            "                                </div>"+
            "                                <div class=\"form-group fieldGroup\">\n" +
            "                                    <label>Responses</label>\n" +
            "                                    <div class=\"row fieldRow\">\n" +
            "                                        <div class=\"col-lg-10\">\n" +
            "                                            <input name=\"option[]\" type=\"text\" class=\"form-control input-md option-input\" placeholder=\"Add an answer\" required>\n" +
            "                                        </div>\n" +
            "                                    </div>\n" +
            "                                    <br>\n" +
            "                                    <div class=\"row fieldRow\">\n" +
            "                                        <div class=\"col-lg-10\">\n" +
            "                                            <input name=\"option[]\" type=\"text\" class=\"form-control input-md option-input\" placeholder=\"Add an answer\" required>\n" +
            "                                        </div>\n" +
            "                                    </div>\n" +
            "                                    <br>\n" +
            "                                    <button type=\"button\" class=\"btn btn-default addResponse\" >Add Option</button>\n" +
            "                                </div>\n" +
            "                            </div>\n" +
            "                        </div>");
    }

</script>

<div id="content-wrapper">
    <div class="container-fluid">
        <!--Insert success page-->
        <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
            <div class="row" style="margin-top: 2rem;">
                <div class="column col-lg-7">
                    <!-- Text input-->
                    <div class="form-group">
                        <label for="post_title">Title</label>
                        <input <?php if($mode == 'view') echo 'disabled' ?> id="post_title" name="post_title" type="text" placeholder="Put your post title here..." class="form-control input-md" value="<?php if(isset($title)){ echo $title; }; ?>" required>
                    </div>

                    <!-- Textarea -->
                    <div class="form-group">
                        <label for="post_content">Content</label>
                        <textarea name="post_content" id="post_content"></textarea>
                    </div>
                    <span id="questionCardArea">
                        <?php
                            $rows = $crud->getData("SELECT pl.id, pl.typeId, pl.question 
                                      FROM facultyassocnew.polls pl 
                                      JOIN posts pt ON pl.postId = pt.id WHERE pt.id='$postId';");
                            if(!empty($rows)) {
                                foreach ((array)$rows as $key => $row) {
                                    $pollId = $row['id'];
                                    $type1 = '';
                                    $type2 = '';
                                    if($row['typeId'] == '1'){
                                        $type1 = 'selected';
                                    }else if($row['typeId'] == '2') {
                                        $type2 = 'selected';
                                    }
                                    echo '<div class="card" id="questionCard">
                                            <div class="card-header">
                                                <div class="row fieldRow">
                                                    <div class="col-lg-2">
                                                        <b>Question: </b>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <input type="hidden" name="toUpdatePollId" value="'.$pollId.'">
                                                        <input id="post_question" name="toUpdatePollQuestion" type="text" placeholder="Put your question here..." class="form-control input-md" required value="'.$row['question'].'">
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <button type="button" class="btn btn-danger" onclick="removePoll(this,'.$pollId.')"><i class="fa fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>Response Type</label>
                                                    <select name="toUpdateTypeId" class="form-control">
                                                        <option value="1" '.$type1.'>Single Response</option>
                                                        <option value="2" '.$type2.'>Multiple Response</option>
                                                    </select>
                                                </div>                                
                                                <div class="form-group fieldGroup">
                                                    <label>Responses</label>';
                                    $rows2 = $crud->getData("SELECT optionId, response FROM poll_options WHERE pollId = '$pollId';");
                                    if(!empty($rows2)){
                                        foreach ((array)$rows2 as $key2 => $row2) {
                                            echo '<div class="row fieldRow">
                                                        <div class="col-lg-10">
                                                            <input type="hidden" name="toUpdateOptionId[]" value="'.$row2['optionId'].'">
                                                            <input type="text" name="toUpdateOptionContent[]" class="form-control input-md option-input" placeholder="Add an answer" value="'.$row2['response'].'" required>
                                                        </div>
                                                    </div>
                                                    <br>';
                                        }
                                    }
                                    echo '<button type="button" class="btn btn-default addResponse" onclick="addResponse(this)">Add Option</button>';
                                    echo '</div></div></div>';
                                }
                            }
                        ?>
                    </span>
                    <span id="toRemovePolls"></span>

                    <?php
                        if(!isset($pollId)) echo '<button type="button" id="btnAddQuestion" class="btn btn-default" onclick="addPoll(this)"><i class="fa fa-fw fa-plus"></i>Add Question</button>';
                    ?>
                    <div class="card" style="margin-top: 1rem;">
                        <div class="card-body">
                            <button type="button" class="btn btn-primary fa fa-comment" data-toggle="modal" data-target="#myModal" name="addComment" id="addComment"> Comment </button>
                            <span id="comment_message"></span>
                            <div id="display_comment"></div>
                        </div>
                    </div>
                </div>
                <div id="publishColumn" class="col-lg-4" style="margin-top: 1rem; margin-bottom: 1rem; ">

                    <div class="card" style="margin-bottom: 1rem; ">
                        <div class="card-header"><b>Document References</b></div>
                        <div class="card-body" style="max-height: 20rem; overflow-y: scroll;">
                        <span id="refDocuments" style="font-size: 12px;">
                        <?php

                            $rows = $crud->getData("SELECT d.documentId, CONCAT(e.lastName,', ',e.firstName) AS originalAuthor, v.filePath,
                                            v.versionId as vid, v.versionNo, v.title, v.timeCreated, pr.id AS processId, pr.processName, s.stepNo, s.stepName,
                                            (SELECT CONCAT(e.lastName,', ',e.firstName) FROM doc_versions v JOIN employee e ON v.authorId = e.EMP_ID 
                                            WHERE v.versionId = vid) AS currentAuthor
                                            FROM documents d JOIN doc_versions v ON d.documentId = v.documentId
                                            JOIN employee e ON e.EMP_ID = d.firstAuthorId 
                                            JOIN steps s ON s.id = d.stepId
                                            JOIN process pr ON pr.id = d.processId 
                                            JOIN post_ref_versions ref ON ref.versionId = v.versionId
                                            WHERE ref.postId = $postId;");
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
                                    echo '<input type="hidden" class="refDocuments" value="'.$row['vid'].'">';
                                    echo '<a style="text-align: left;" class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse' . $row['vid'] . '" aria-expanded="true" aria-controls="collapse' . $row['vid'] . '"><b>' . $title . ' </b><span class="badge">' . $versionNo . '</span></a>';
                                    echo '<div class="btn-group" style="position: absolute; right: 2px; top: 2px;" >';
                                    echo '<a class="btn fa fa-download"  href="'.$filePath.'" download="'.$fileName.'"></a>';
                                    if($mode == 'edit') echo '<a class="btn fa fa-remove" onclick="removeRef(this, &quot;'.$row['vid'].'&quot;)" ></a>';
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
                            else{
                                    echo 'No References';
                                }
                            ?>
                            </span>
                            <span id="toRemoveDocRefs"></span>
                        </div>
                        <?php

                            if($mode == 'edit'){
                                echo '<div class="card-footer">
                                <button type="button" class="btn btn-default"><i class="fa fa-fw fa-plus"></i>New</button>
                                <button id="btnRefModal" type="button" class="btn btn-default" data-toggle="modal" data-target="#modalRED"><i class="fa fa-fw fa-link"></i>Existing</button>
                            </div>';
                            }
                        ?>
                    </div>

                    <div class="card" style="margin-bottom: 1rem;">
                        <div class="card-body" >
                            Author: <b><?php echo $author; ?></b><br>
                            <i>Created on: <b><?php echo date("F j, Y g:i:s A ", strtotime($firstPosted)); ?></b></i><br><br>

                            Current Status: <b><?php echo $statusDesc?></b>
                            <?php if(!empty($permalink)){ ?>
                                (<a href="<?php echo "http://localhost/FRAP_sd/read.php?pl=".$permalink?>" >Preview</a>)
                            <?php } ?>
                            <br>
                            <?php if(!empty($reviewer)){ echo "Reviewed by: <b>".$reviewer."</b><br>"; }?>
                            <?php if($status == '4'  && !empty($publisher)){ echo "Publisher: <b>".$publisher."</b><br>"; }?>
                            <i>Last updated: <b><?php  echo date("F j, Y g:i:s A ", strtotime($lastUpdated));?></b></i>
                            <input type="hidden" id="post_id" name="post_id" value="<?php if(isset($postId)){ echo $postId;}; ?>">
                        </div>

                        <div class="card-footer">
                            <?php
                            if($mode == 'edit'){
                                echo '<button type="submit" class="btn btn-primary" name="btnSubmit" id="btnUpdate" hidden>Save</button> ';
                                if($cmsRole == '4') {
                                    if($status == '1'){
                                        echo '<button type="submit" class="btn btn-success" name="btnSubmit" id="btnSubmit" value="4">Publish</button> ';
                                        echo '<button type="submit" class="btn btn-danger" name="btnSubmit" id="btnSubmit" value="5">Trash</button> ';
                                    }else if ($status == '3') {
                                        echo '<button type="submit" class="btn btn-success" name="btnSubmit" id="btnSubmit" value="4">Publish</button> ';
                                        echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="2">For Review</button> ';
                                        echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Reject</button> ';
                                        echo '<button type="submit" class="btn btn-danger" name="btnSubmit" id="btnSubmit" value="5">Trash</button> ';
                                    } else if ($status == '4') {
                                        echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="3">Unpublish</button> ';
                                        echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="2">For Review</button> ';
                                        echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Reject</button> ';
                                        echo '<button type="submit" class="btn btn-danger" name="btnSubmit" id="btnSubmit" value="5">Trash</button> ';
                                    }
                                }else if($cmsRole == '3') {
                                    if($status == '1'){
                                        echo '<button type="submit" class="btn btn-success" name="btnSubmit" id="btnSubmit" value="3">Submit for Publication</button> ';
                                        echo '<button type="submit" class="btn btn-danger" name="btnSubmit" id="btnSubmit" value="5">Trash</button> ';
                                    } else if ($status == '2') {
                                        echo '<button type="submit" class="btn btn-success" name="btnSubmit" id="btnSubmit" value="3">Submit for Publication</button> ';
                                        echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Reject</button> ';
                                        echo '<button type="submit" class="btn btn-danger" name="btnSubmit" id="btnSubmit" value="5">Trash</button> ';
                                    }
                                }else if($cmsRole == '2'){
                                    if ($status == '1') {
                                        echo '<button type="submit" class="btn btn-success" name="btnSubmit" id="btnSubmit" value="2">Submit for Review</button> ';
                                        echo '<button type="submit" class="btn btn-danger" name="btnSubmit" id="btnSubmit" value="5">Trash</button> ';
                                    }
                                }else if($cmsRole == '5'){
                                    if($status == '1'){
                                        echo '<button type="submit" class="btn btn-success" name="btnSubmit" id="btnSubmit" value="4">Publish</button> ';
                                        echo '<button type="submit" class="btn btn-danger" name="btnSubmit" id="btnSubmit" value="5">Trash</button> ';
                                    }else if ($status == '3') {
                                        echo '<button type="submit" class="btn btn-success" name="btnSubmit" id="btnSubmit" value="4">Publish</button> ';
                                        echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="2">For Review</button> ';
                                        echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Reject</button> ';
                                        echo '<button type="submit" class="btn btn-danger" name="btnSubmit" id="btnSubmit" value="5">Trash</button> ';
                                    } else if ($status == '4') {
                                        echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="3">Unpublish</button> ';
                                        echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="2">For Review</button> ';
                                        echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Reject</button> ';
                                        echo '<button type="submit" class="btn btn-danger" name="btnSubmit" id="btnSubmit" value="5">Trash</button> ';
                                    }
                                }
                                echo '<button type="submit" class="btn btn-primary" name="btnUnlock" id="btnUnlock"> Exit </button> ';
                            }else if($availability == '2'){
                                if($mode == 'view_with_button'){
                                    if($status == '5'){
                                        echo '<button type="submit" class="btn btn-success" name="btnRestore" id="btnSubmit" value="' . $prevStatus . '">Restore</button> ';
                                    }else{
                                        echo '<button type="submit" class="btn btn-primary" name="btnEdit" id="btnEdit"> Lock and Edit </button> ';
                                    }
                                }else{
                                    echo 'Post is currently '.$statusDesc;
                                }
                            }else if($availability == '1'){
                                echo 'Post is currently locked by ' . $lockedByName . '.';
                            }

                            ?>
                        </div>
                    </div>
                    </div>

            </div>
        </form>
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
                            <input type="hidden" name="post_id" id="post_id" value="<?php echo $postId?>" />
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" name="submit" id="submit" class="btn btn-info" value="Submit"/>
                        </div>
                    </div>
                </div>

            </form>

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
<?php include 'GLOBAL_FOOTER.php' ?>