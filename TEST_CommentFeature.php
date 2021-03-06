<?php
include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_CMS_ADMIN_CHECKING.php');

/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */
$userId = $_SESSION['idnum'];

$page_title = 'Santinig - Add Post';
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR.php';
?>
<br/>


<div class="container">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" name="addComment" id="addComment">Add Comment</button>
    <br />
    <span id="comment_message"></span>
    <br />
    <div id="display_comment"></div>
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

        $('#comment_form').on('submit', function(event){
            event.preventDefault();
            $('#myModal').modal('toggle');
            var form_data = $(this).serialize();
            $.ajax({
                url:"add_comment.php",
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
                        load_comment();
                    }
                }
            })
        });



        setInterval(function() {
            load_comment();
        }, 500); //5

        function load_comment()
        {
            $.ajax({
                url:"fetch_comment.php",
                method:"POST",
                success:function(data)
                {
                    $('#display_comment').html(data);
                }
            });
        }

        $(document).on('click', '.reply', function(){
            var comment_id = $(this).attr("id");
            $('#comment_id').val(comment_id);
            $('#comment_content').focus();
        });

    });
</script>
</script>

<?php include 'GLOBAL_FOOTER.php' ?>
