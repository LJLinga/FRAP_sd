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

    $rows = $crud->getData("SELECT p.authorId, p.reviewedById, p.restoredById, p.remark_user_id, p.publisherId, p.archivedById, p.lockedById, p.availabilityId, p.statusId FROM posts p WHERE p.id = '$postId'");
    foreach((array) $rows as $key => $row){
        $authorId = $row['authorId'];
        $status = $row['statusId'];
        $availability = $row['availabilityId'];
        $reviewerId = $row['reviewedById'];
        $lockedById = $row['lockedById'];
        $publisherId = $row['publisherId'];
        $archivedById = $row['archivedById'];
        $restoredById = $row['restoredById'];
        $remarkedById = $row['remark_user_id'];
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
            CONCAT(u.lastName,', ', u.firstName) AS author,
            p.body, p.permalink,
            p.firstCreated,
            p.lastUpdated,
            p.statusId,
            p.previousStatusId,
            s.description, p.remarks
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
        $remarks = $row['remarks'];
    }

    if($status == '3'){
        $reviewer = $crud->getUserName($reviewerId);
    }else if($status == '4'){
        $publisher = $crud->getUserName($publisherId);
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
    $crud->execute("UPDATE posts SET statusId = '$status', restoredById='$userId' WHERE id='$postId';");
}

if(isset($_POST['btnSubmit'])) {

    $title = $crud->escape_string($_POST['post_title']);
    $body = $crud->escape_string($_POST['post_content']);
    $remarks = $crud->escape_string($_POST['remarks']);
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

    if(isset($_POST['toRemoveResponse'])) {
        $toRemoveResponse = $_POST['toRemoveResponse'];
        foreach((array) $toRemoveResponse AS $key => $ref){
            $query = "DELETE FROM  WHERE id = '$ref'";
            $bool = $crud->execute("DELETE FROM poll_responses WHERE responseId = '$ref' ");
            if($bool){
                $crud->execute("DELETE FROM poll_options WHERE optionId = '$ref'");
            }
        }
    }

    if(isset($_POST['toUpdatePollId']) && isset($_POST['toUpdatePollQuestion'])){
        $typeId = '1';
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

    if(isset($_POST['toAddResponse'])) {
        $options = $_POST['toAddResponse'];
        $pollId = $_POST['toUpdatePollId'];
        foreach((array) $options AS $key => $ref){
            $query = "INSERT INTO poll_options(pollId,response) VALUES ('$pollId','$ref');";
            $crud->execute($query);
        }
    }

    if(isset($_POST['option']) && isset($_POST['post_question'])) {
        $question = $_POST['post_question'];
        $typeId = '1';
        $pollId = $crud->executeGetKey("INSERT INTO polls(postId, typeId, question) VALUES ('$postId','$typeId','$question')");
        $options = $_POST['option'];
        foreach((array) $options AS $key => $ref){
            $query = "INSERT INTO poll_options(pollId,response) VALUES ('$pollId','$ref');";
            $crud->execute($query);
        }
    }

    if(empty($status)){
        $crud->execute("UPDATE posts SET title='$title', body='$body', remark_user_id='$userId', remarks='$remarks' WHERE id='$postId';");
    }else if($crud->execute("UPDATE posts SET title='$title', body='$body', statusId='$status', remarks='$remarks' WHERE id='$postId';")) {
        if($status=='3'){
            // we can add an UNPUBLISHERID in the future;
            $crud->execute("UPDATE posts SET reviewedById='$userId' WHERE id='$postId';");
        }else if($status=='4' && $cmsRole=='4'){
            $crud->execute("UPDATE posts SET publisherId='$userId' WHERE id='$postId';");
            $result = $crud->execute("SELECT permalink FROM posts WHERE id='$postId' AND permalink IS NULL");
            if(empty($result[0]['permalink'])) {
                $permalink = $crud->generate_permalink($title);
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
    .pull-left{
        float: left !important;
    }
</style>
    <script>
    $(document).ready( function(){

        $("#modalAddEvent").on("show.bs.modal", function() {
            $('#err').html('');
            removeAllEmails();
            $('#datetimepicker1').datetimepicker( {
                minDate: moment(),
                locale: moment().local('ph'),
                defaultDate: moment().add(5,'minutes'),
                format: 'YYYY-MM-DD HH:mm:ss'
            });
            $('#datetimepicker2').datetimepicker( {
                minDate: moment().add(15, 'minutes'),
                locale: moment().local('ph'),
                defaultDate: moment().add(20, 'minutes'),
                format: 'YYYY-MM-DD HH:mm:ss'
            });
        });


        let table = $('#tblEvents').DataTable( {
            bSort: true,
            bFilter: false,
            destroy: true,
            pageLength: 3,
            lengthChange: false,
            aaSorting: [],
            "ajax": {
                "url":"CMS_AJAX_FetchEvents.php",
                "type":"POST",
                "dataSrc": '',
                "data":{requestType: 'POST_ATTACHED_EVENTS', postId: '<?php echo $postId;?>' }
            },
            columns: [
                { data: "event" }
            ],
            fnDrawCallback: function() {
                $("#tblEvents thead").remove();
            }
        });

        let mode = '<?php echo $mode; ?>';
        let postId = "<?php echo $postId?>";
        let content = $('#post_content');

        $('#remark_checkbox').on('change', function () {
            var ta = $('#remarks');
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

        $('#btnUpdate').attr("disabled",true);
        $('#form :input').not('#remarks, #remark_checkbox').on('keyup', function(){
            $('#btnUpdate').attr("disabled",false);
            $('#labelRemarks').html("Please describe this update");
            $('#remarks').show();
        });

        $('#btnUpdate').on('click', function(){
           $('#modalAction').html($('#btnUpdate').html());
           $('#btnModalSubmit').val($('#btnUpdate').val());
        });

        $('#btnPublication').on('click', function(){
            $('#modalAction').html('Submit for Publication');
            $('#btnModalSubmit').val('3');
        });

        $('#btnTrash').on('click', function(){
            $('#modalAction').html('Trash');
            $('#btnModalSubmit').val('5');
        });

        $('#btnUnpublish').on('click', function(){
            $('#modalAction').html('Unpublish');
            $('#btnModalSubmit').val('3');
        });

        $('#btnReview').on('click', function(){
            $('#modalAction').html('For Review');
            $('#btnModalSubmit').val('2');
        });

        $('#btnReject').on('click', function(){
            $('#modalAction').html('Reject');
            $('#btnModalSubmit').val('1');
        });

        $('#btnPublish').on('click', function(){
            $('#modalAction').html('Publish');
            $('#btnModalSubmit').val('4');
        });

        $('#btnUnlock').on('click', function(){
            $('#remarks').removeAttr('required');
        });

        $("#addEventForm").on('submit', function(e){
            e.preventDefault();
            //$('#myModal').modal({backdrop: 'static', keyboard: false});
            $('#btnSubmitEvent').attr('disabled',true);
            $('#err').html('<div class="alert alert-info">Adding event into Google Calendar. Please wait. </div>');
            $.ajax({
                type: "POST",
                url: "CMS_AJAX_FetchEvents.php",
                cache: false,
                processData: false,
                contentType: false,
                data: new FormData(this),
                success: function(response){
                    if(response === 'success'){
                        $('#modalAddEvent').modal('toggle');
                    }else{
                        $('#err').html('<div class="alert alert-warning"><strong> Adding of event unsuccessful: </strong>'+response+'</div>');
                    }
                    $('#btnSubmitEvent').attr('disabled',false);
                    table.ajax.reload();
                },
                error: function(){
                    $('#modalAddEvent').modal('toggle');
                    table.ajax.reload();
                    $('#btnSubmitEvent').attr('disabled',false);
                }
            });
            return false;
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
            $('.btn-danger').attr("disabled",true);
        }

        content.on('froalaEditor.contentChanged', function (e, editor) {
            $('#btnUpdate').attr("disabled",false);
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

    function addAllEmails(){
        $('#btnInviteAll').attr('disabled',true);
        $('#btnRemoveAll').attr('disabled',false);
        $('.btn_remove_email').click();
        $('.btn_add_email').click();
    }
    function removeAllEmails(){
        $('#btnInviteAll').attr('disabled',false);
        $('#btnRemoveAll').attr('disabled',true);
        $('.btn_remove_email').click();
    }

    function addEmail(element, email, name){
        $(element).hide()
        let element_id = $(element).attr('id');
        $('#addEmails').append('<div class="btn btn-success btn-sm btn_remove_email" onclick="removeEmail(this,&quot;'+element_id+'&quot;,&quot;'+email+'&quot;,&quot;'+name+'&quot;)" style="text-align: left;"><b>'+name+' ('+email+')</b>' +
            '<input type="hidden" name="toAddEmails[]" value="'+email+'"></div>');
    }
    function removeEmail(element,id, email, name){
        $(element).remove();
        $('#btnInviteAll').attr('disabled',false);
        $('#'+id).show();
    }

    function reloadDataTable(){
        let loadedRefs = [];
        $(".refDocuments").each(function() {
            loadedRefs.push($(this).val());
        });
        $('#dataTable').dataTable({
            destroy: true,
            bSort: true,
            aaSorting: [],
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
        $('#btnUpdate').attr('disabled',false);
    }
    function addRef(element, verId, oA, cA, vN, uO, t, pN, fP, fN){
        $('#noRefsYet').remove();
        $('#btnUpdate').attr('disabled',false);
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
        $('#btnUpdate').attr('disabled',false);
        $('#questionCardArea').append("<button type=\"button\" class=\"btn btn-default\" onclick=\"addPoll(this)\"><i class=\"fa fa-fw fa-plus\"></i>Add Question</button>");
        $('#toRemovePolls').append('<input type="hidden" name="toRemovePolls[]" value="'+pollId+'">');
    }
    function removeResponse(element){
        $(element).closest('.fieldRow').remove();
        $('#btnUpdate').attr('disabled',false);
    }
    function removeOldResponse(element,responseId){
        $(element).closest('.fieldRow').remove();
        $('#btnUpdate').attr('disabled',false);
        $('#toRemoveResponse').append('<input type="hidden" name="toRemoveResponse[]" value="'+responseId+'">');
    }
    function addResponse(element){
        $('#btnUpdate').attr('disabled',false);
        $('.fieldRow').last().after('<div class="row fieldRow"><br>\n' +
            '                                        <div class="col-lg-10">\n' +
            '                                            <input name="toAddResponse[]" type="text" class="form-control input-md option-input" placeholder="Add an answer" required>\n' +
            '                                        </div>\n' +
            '                                        <div class="col-lg-2">\n' +
            '                                            <button type="button" class="btn btn-danger" onclick="removeResponse(this)"><i class="fa fa-trash"></i></button>\n' +
            '                                        </div>\n' +
            '                                    </div>');
    }
    function addPoll(element){
        $(element).remove();
        $('#btnUpdate').attr('disabled',false);
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
                                                        <div class="col-lg-2">
                                                            <button type="button" class="btn btn-danger" onclick="removeOldResponse(this,'.$row2['optionId'].')"><i class="fa fa-trash"></i></button>
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
                    <span id="toRemoveResponse"></span>
                    <?php
                        if(!isset($pollId)) echo '<button type="button" id="btnAddQuestion" class="btn btn-default" onclick="addPoll(this)"><i class="fa fa-fw fa-plus"></i>Add Question</button>';
                    ?>
                    <div class="card" style="margin-top: 1rem; display: none">
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

                            $rows = $crud->getData("SELECT CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName, 
                                                                v.filePath, v.title, v.versionNo, v.timeCreated, d.lastUpdated,
                                                                stat.statusName, s.stepNo, s.stepName, t.type,
                                                                pr.processName, v.versionId AS vid,
                                                                (SELECT CONCAT(e.FIRSTNAME,', ',e.LASTNAME) FROM employee e2 WHERE e2.EMP_ID = d.firstAuthorId) AS firstAuthorName 
                                                                FROM doc_versions v 
                                                                JOIN documents d ON v.documentId = d.documentId
                                                                JOIN post_ref_versions ref ON ref.versionId = v.versionId
                                                                JOIN employee e ON e.EMP_ID = v.authorId
                                                                JOIN doc_status stat ON stat.id = d.statusId 
                                                                JOIN doc_type t ON t.id = d.typeId
                                                                JOIN steps s ON s.id = d.stepId
                                                                JOIN process pr ON pr.id = s.processId
                                                                WHERE ref.postId = $postId");
                            if(!empty($rows)) {
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
                            ?>
                            </span>
                            <span id="toRemoveDocRefs"></span>
                        </div>
                        <?php

                            if($mode == 'edit'){
                                echo '<div class="card-footer">
                                <button id="btnRefModal" type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalRED"><i class="fa fa-fw fa-link"></i>Add</button>
                            </div>';
                            }
                        ?>
                    </div>
                    <?php if($remarkedById != ''){ ?>
                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-header">
                                <strong>Recent Remarks</strong>
                            </div>
                            <div class="card-body">
                                Remarks made by <strong><?php echo $crud->getUserName($remarkedById);?></strong>
                                <br>on <strong><i><?php echo $crud->friendlyDate($lastUpdated);?></i></strong>
                                <br><br>
                                <div class="alert alert-info" style="max-height: 20rem; overflow-y: auto;">
                                    <i>"<?php echo $remarks;?>"</i>
                                </div>
                            </div>
                        </div>
                    <?php }?>
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
                            <?php if($status != '5'  && !empty($crud->getUserName($restoredById))){ echo "Restored by: <b>".$crud->getUserName($restoredById)."</b><br>"; }?>
                            <?php if($status == '5'  && !empty($crud->getUserName($archivedById))){ echo "Archived by: <b>".$crud->getUserName($archivedById)."</b><br>"; }?>
                            <i>Last updated: <b><?php  echo date("F j, Y g:i:s A ", strtotime($lastUpdated));?></b></i>
                            <input type="hidden" id="post_id" name="post_id" value="<?php if(isset($postId)){ echo $postId;}; ?>">
                        </div>

                        <div class="card-footer">
                            <?php
                            if($mode == 'edit'){ ?>
                                <div id="modalConfirm" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"><strong>Confirm '<span id="modalAction"></span>'?</strong></h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label id="labelRemarks"><input type="checkbox" id="remark_checkbox"> Provide remarks</label>
                                                    <textarea name="remarks" id="remarks" class="form-control" placeholder="Your remarks..." rows="10" style="display: none;"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button class="btn btn-primary" type="submit" name="btnSubmit" id="btnModalSubmit">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalConfirm" id="btnUpdate" hidden>Save</button> ';
                                if($cmsRole == '4') {
                                    if($status == '1'){
                                        echo '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalConfirm" id="btnPublish" value="4">Publish</button> ';
                                        echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalConfirm" id="btnTrash" value="5">Trash</button> ';
                                    }else if ($status == '3') {
                                        echo '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalConfirm" id="btnPublish" value="4">Publish</button> ';
                                        echo '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalConfirm" id="btnReview" value="2">For Review</button> ';
                                        echo '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalConfirm" id="btnReject" value="1">Reject</button> ';
                                        echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalConfirm" id="btnTrash" value="5">Trash</button> ';
                                    } else if ($status == '4') {
                                        echo '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalConfirm" id="btnUnpublish" value="3">Unpublish</button> ';
                                        echo '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalConfirm" id="btnReview" value="2">For Review</button> ';
                                        echo '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalConfirm" id="btnReject" value="1">Reject</button> ';
                                        echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalConfirm" id="btnTrash" value="5">Trash</button> ';
                                    }
                                }else if($cmsRole == '3') {
                                    if($status == '1'){
                                        echo '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalConfirm" id="btnPublication" value="3">Submit for Publication</button> ';
                                        echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalConfirm" id="btnTrash" value="5">Trash</button> ';
                                    } else if ($status == '2') {
                                        echo '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalConfirm" id="btnPublication" value="3">Submit for Publication</button> ';
                                        echo '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalConfirm" id="btnReject" value="1">Reject</button> ';
                                        echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalConfirm" id="btnTrash" value="5">Trash</button> ';
                                    }
                                }else if($cmsRole == '2'){
                                    if ($status == '1') {
                                        echo '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalConfirm" id="btnReview" value="2">Submit for Review</button> ';
                                        echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalConfirm" id="btnTrash" value="5">Trash</button> ';
                                    }
                                }else if($cmsRole == '5'){
                                    if($status == '1'){
                                        echo '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalConfirm" id="btnPublish" value="4">Publish</button> ';
                                        echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalConfirm" id="btnTrash" value="5">Trash</button> ';
                                    }else if ($status == '3') {
                                        echo '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalConfirm" id="btnPublish" value="4">Publish</button> ';
                                        echo '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalConfirm" id="btnReview" value="2">For Review</button> ';
                                        echo '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalConfirm" id="btnReject" value="1">Reject</button> ';
                                        echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalConfirm" id="btnTrash" value="5">Trash</button> ';
                                    } else if ($status == '4') {
                                        echo '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalConfirm" id="btnUnpublish" value="3">Unpublish</button> ';
                                        echo '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalConfirm" id="btnReview" value="2">For Review</button> ';
                                        echo '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalConfirm" id="btnReject" value="1">Reject</button> ';
                                        echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalConfirm" id="btnTrash" value="5">Trash</button> ';
                                    }
                                }
                                echo '<button type="submit" class="btn btn-primary" name="btnUnlock" id="btnUnlock"> Exit </button> ';
                            }else if($availability == '2'){
                                if($mode == 'view_with_button'){
                                    if($status == '5'){
                                        echo '<button type="submit" class="btn btn-success" name="btnRestore" id="btnRestore" value="' . $prevStatus . '">Restore</button> ';
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
                    <?php if($cmsRole == '4' && $status == '4'){ ?>
                    <div class="card">
                        <div class="card-header">
                            <strong>Associated Events</strong>
                            <button type="button" id="btnAddEvent" data-toggle="modal" data-target="#modalAddEvent" class="btn btn-primary btn-sm">Add Event</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-responsive table-condensed" id="tblEvents">
                                <thead>
                                    <th></th>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <?php }?>
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
    <div id="modalAddEvent" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content" id="myModalContent">
                <form method="POST" id="addEventForm">
                    <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                    <input type="hidden" name="requestType" value="ADD_POST_EVENT">
                    <input type="hidden" name="postId" value="<?php echo $postId;?>">
                    <div class="modal-header">
                        <b>Add Event</b>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="eventTitle">Name</label>
                            <input type="text" name="event_title" id="eventTitle" class="form-control" value="<?php echo $title;?>" required>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="eventStart">Start Date</label>
                                    <div class="input-group date" id="datetimepicker1">
                                        <input id="event_start" name="event_start" type="text" class="form-control">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="eventEnd">End Date</label>
                                    <div class="input-group date" id="datetimepicker2">
                                        <input id="event_end" name="event_end" type="text" class="form-control">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="event_desc">Description</label>
                            <textarea name="event_content" id="eventDescription" class="form-control" rows="5" required>For more details, refer to this post: http://localhost/FRAP_sd/read.php?pl=<?php echo $permalink;?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="event_desc">Invite Members</label>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-success" id="btnInviteAll" onclick="addAllEmails()">Invite All</button>
                                <button type="button" class="btn btn-sm btn-warning" id="btnRemoveAll" onclick="removeAllEmails()" disabled>Remove All</button>
                            </div>
                            <div class="card" style="min-height: 10rem; max-height: 10rem; overflow: auto;" id="addEmails">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="card" style="min-height: 10rem; max-height: 10rem; overflow: auto;" id="toAddEmails">
                                <?php
                                $rows = $crud->getData("SELECT CONCAT(e.LASTNAME,', ',e.LASTNAME) as name, e.EMAIL, e.MEMBER_ID FROM MEMBER e;");
                                foreach((array)$rows as $key=>$row){
                                    echo '<div class="btn btn-default btn-sm btn_add_email" id="email'.$row['MEMBER_ID'].'" onclick="addEmail(this,&quot;'.$row['EMAIL'].'&quot;,&quot;'.$row['name'].'&quot;)" style="text-align: left;"><b>'.$row['name'].' ('.$row['EMAIL'].')</b></div>';
                                }
                                ?>
                            </div>
                        </div>
                        <span id="err"></span>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <input type="submit" name="btnSubmit" id="btnSubmitEvent" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php include 'GLOBAL_FOOTER.php' ?>