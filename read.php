<?php
/**
 * Created by PhpStorm.
 * User: Christian Alderite
 * Date: 10/31/2018
 * Time: 10:57 AM
 **/

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_CMS_ADMIN_CHECKING.php');

$userId = $_SESSION['idnum'];
$postId = '';
//$cmsRole = $_SESSION['CMS_ROLE'];

if(!empty($_GET['pl'])){

    $permalink = $_GET['pl'];

    $rows = $crud->getData("SELECT p.id, p.authorId, p.publisherId, p.statusId
                            FROM posts p
                            WHERE p.permalink = '$permalink'");

    foreach ((array) $rows as $key => $row) {
        $postId = $row['id'];
        $authorId = $row['authorId'];
        $publisherId = $row['publisherId'];
        $statusId = $row['statusId'];
    }

    $insertView = "INSERT INTO post_views (id, viewerId, typeId) VALUE ('$postId','$userId','2')";
    $crud->execute($insertView);

    if($statusId!=3){
        if($authorId!=$userId && $cmsRole!=3){
            header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/feed.php");
        }
    }

    $rows = $crud->getData("SELECT 
            p.title,
            CONCAT(u.firstName,' ', u.lastName) AS author,
            p.body,
            p.statusId,
            p.lastUpdated
        FROM
            employee u
                JOIN
            posts p ON p.authorId = u.EMP_ID
        WHERE
            p.permalink = '$permalink'   ");

    foreach ((array) $rows as $key => $row) {
        $title = $row['title'];
        $body = $row['body'];
        $author = $row['author'];
        $lastUpdated = $row['lastUpdated'];
        $statusId = $row['statusId'];
    }


}else{
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/feed.php");
}

$page_title = $title;
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR_Admin.php';

?>
<style>
    .card {
        font-family: "Verdana", Georgia, Serif;
        font-size: 14px;
    }
</style>
<script>

</script>
<div id="container">
    <div class="container-fluid">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="column col-lg-7" style="top: 1rem; margin-bottom: 1rem; ">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title"><b><?php echo $page_title;?></b></h3>
                        <h5 class="card-subtitle">by <?php echo $author;?> | <?php echo date("F j, Y g:i A ", strtotime($lastUpdated)) ;?></h5>
                    </div>
                    <div class="card-body" style="overflow: hidden";>
                        <p class="card-text"><?php echo $body ?></p>
                    </div>
                </div>
                <div class="card" style="margin-top: 1rem;">
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
                        <input type="hidden" name="post_id" id="post_id" value="<?php echo $postId?>" />
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

        $('.reply').click(function(){
        });

        let postId = "<?php echo $postId?>";

        $('#comment_form').on('submit', function(event){
            event.preventDefault();
            $('#myModal').modal('toggle');
            var form_data = $(this).serialize();
            $.ajax({
                url:"CMS_AJAX_AddComment.php",
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

        load_comment(postId);

        function load_comment(postId)
        {
            $.ajax({
                url:"CMS_AJAX_FetchComments.php",
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

    });
</script>
<?php include 'GLOBAL_FOOTER.php'; ?>
