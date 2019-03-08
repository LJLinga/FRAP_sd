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
                p.id = '$postId' ;
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

if(isset($_POST['btnSubmit'])) {

    $title = $crud->escape_string($_POST['post_title']);
    $body = $crud->escape_string($_POST['post_content']);
    $status = $_POST['btnSubmit'];

    if(empty($status)){
        $crud->execute("UPDATE posts SET title='$title', body='$body' WHERE id='$postId';");
    }else if($crud->execute("UPDATE posts SET title='$title', body='$body', statusId='$status' WHERE id='$postId';")) {
        if($status=='3' && $cmsRole=='3'){
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
include 'CMS_SIDEBAR_Admin.php';
?>
<style>
    @media screen and (min-width: 1200px) {
        #publishColumn{
            position: fixed;
            right:1rem;
        }
    }
    @media screen and (max-width: 1199px) {
        #publishColumn{
            position: relative;
        }
    }
    .fr-view {
        font-family: "Verdana", Georgia, Serif;
        font-size: 14px;
        color: #444444;
    }
</style>
    <script>
    $(document).ready( function(){

        let mode = '<?php echo $mode; ?>';
        let postId = "<?php echo $postId?>";

        $('#btnUpdate').hide();

        $('#post_content').froalaEditor({
            videoUpload: false,
            imageUploadURL: 'CMS_SERVER_INCLUDES/CMS_SERVER_IMAGE_Upload.php',
            fileUpload: false,
            width: 750,
            toolbarInline: false
        });

        if(mode==='view' || mode==='view_with_button'){
            $('#post_content').froalaEditor("edit.off");
        }

        $('#post_content').on('froalaEditor.contentChanged', function (e, editor) {
            $('#btnUpdate').show();
        });

        $('#post_content').froalaEditor('html.set', '<?php echo $body?>');

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

//        $('#modalTriggerSubmit').click(function() {
//            $('#changeText').text($('.btn').val());
//            $('#submit').click(function() {
//                document.getElementById("addToPay").click();
//            });
//        });
//        $('#modalTriggerUpdate').click(function() {
//            $('#changeText').text('50 % immediately');
//            $('#submit').click(function() {
//                document.getElementById("addFifty").click();
//            });
//        });



    });

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

                    <div class="card" style="margin-top: 1rem;">
                        <div class="card-body">
                            <button type="button" class="btn btn-primary fa fa-comment" data-toggle="modal" data-target="#myModal" name="addComment" id="addComment"> Comment </button>
                            <span id="comment_message"></span>
                            <div id="display_comment"></div>
                        </div>
                    </div>
                </div>
                <div id="publishColumn" class="column col-lg-4" style="margin-top: 1rem; margin-bottom: 1rem; ">

                    <div class="card" style="margin-bottom: 1rem;">
                        <div class="card-body">
                            No references
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-default"><i class="fa fa-fw fa-plus"></i><i class="fa fa-fw fa-file"></i> Add New Document</button>
                            <button class="btn btn-default"><i class="fa fa-fw fa-link"></i><i class="fa fa-fw fa-file"></i> Link Existing Document</button>
                        </div>
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
                            <?php if($status == '3' && !empty($reviewer)){ echo "Reviewed by: <b>".$reviewer."</b><br>"; }?>
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
                                            echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Back to Author</button> ';
                                            echo '<button type="submit" class="btn btn-danger" name="btnSubmit" id="btnSubmit" value="5">Trash</button> ';
                                        } else if ($status == '4') {
                                            echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="3">Unpublish</button> ';
                                            echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="2">For Review</button> ';
                                            echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Reject</button> ';
                                            echo '<button type="submit" class="btn btn-danger" name="btnSubmit" id="btnSubmit" value="5">Trash</button> ';
                                        }
                                    }else if($cmsRole == '3') {
                                        if($status == '1'){
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
                                    }
                                    echo '<button type="submit" class="btn btn-primary" name="btnUnlock" id="btnUnlock"> Exit </button> ';
                                }else if($availability == '2'){
                                    if($mode == 'view_with_button'){
                                        if($status == '5'){
                                            echo '<button type="submit" class="btn btn-success" name="btnSubmit" id="btnSubmit" value="' . $prevStatus . '">Restore</button> ';
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


            <div class="row">

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
<?php include 'GLOBAL_FOOTER.php' ?>