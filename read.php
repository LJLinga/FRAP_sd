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

    if($statusId!='4'){
        if($authorId!=$userId && $cmsRole!='4' && $cmsRole!='3'){
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
    @media screen and (min-width: 1200px) {
        #calendarColumn{
            position: fixed;
            right:1rem;
        }
    }
    @media screen and (max-width: 1199px) {
        #calendarColumn{
            position: relative;
        }
    }
    .card {
        font-family: "Verdana", Georgia, Serif;
        font-size: 12px;
    }
</style>
<script>

</script>
    <div class="container-fluid">
        <div class="row">
            <div class="column col-lg-7" style="margin-top: 2rem; margin-bottom: 2rem;"">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><b><?php echo $page_title;?></b></h4>
                        <h5 class="card-subtitle">by <?php echo $author;?> | <?php echo date("F j, Y g:i A ", strtotime($lastUpdated)) ;?></h5>
                        <br><p class="card-text"><?php echo $body ?></p>
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
            <div id="calendarColumn" class="column col-lg-4" style="margin-top: 1rem; margin-bottom: 2rem;">
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b> Events </b>
                    </div>
                    <div class="card-body" >
                        <iframe src="https://calendar.google.com/calendar/b/3/embed?showTitle=0&amp;showCalendars=0&amp;mode=AGENDA&amp;height=800&amp;wkst=2&amp;bgcolor=%23ffffff&amp;src=noreply.lapdoc%40gmail.com&amp;color=%231B887A&amp;src=en.philippines%23holiday%40group.v.calendar.google.com&amp;color=%23125A12&amp;ctz=Asia%2FManila" style="border-width:0" width="480" height="360" frameborder="0" scrolling="no"></iframe>
                    </div>
                </div>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b> Polls </b>
                    </div>
                    <div class="card-body" >
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


        setInterval(function() {
            load_comment(postId);
        }, 1000); //5

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
