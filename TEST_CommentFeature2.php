<?php

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_CMS_ADMIN_CHECKING.php');
include('GLOBAL_COMMENT_FUNCTIONS.php');

$page_title = 'Santinig - Comments';
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR.php';
?>
<style>
    form button { margin: 5px 0px; }
    textarea { display: block; margin-bottom: 10px; }
    /*post*/
    .post { border: 1px solid #ccc; margin-top: 10px; }
    /*comments*/
    .comments-section { margin-top: 10px; border: 1px solid #ccc; }
    .comment { margin-bottom: 10px; }
    .comment .comment-name { font-weight: bold; }
    .comment .comment-date {
        font-style: italic;
        font-size: 0.8em;
    }
    .comment .reply-btn, .edit-btn { font-size: 0.8em; }
    .comment-details { width: 91.5%; float: left; }
    .comment-details p { margin-bottom: 0px; }
    .comment .profile_pic {
        width: 35px;
        height: 35px;
        margin-right: 5px;
        float: left;
        border-radius: 50%;
    }
    /*replies*/
    .reply { margin-left: 30px; }
    .reply_form {
        margin-left: 40px;
        display: none;
    }
    #comment_form { margin-top: 10px; }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 post" style="background-color: white;">
            <h2><?php //echo $post['title'] ?></h2>
            <p><?php //echo $post['body']; ?></p>
        </div>
        <div class="col-md-6 col-md-offset-3 comments-section" style="background-color: white;">
            <!-- if user is not signed in, tell them to sign in. If signed in, present them with comment form -->
            <?php if (isset($user_id)): ?>
                <form class="clearfix" action="TEST_CommentFeature2.php" method="post" id="comment_form">
                    <textarea name="comment_text" id="comment_text" class="form-control" cols="30" rows="3"></textarea>
                    <button class="btn btn-primary btn-sm pull-right" id="submit_comment">Submit comment</button>
                </form>
            <?php else: ?>
                <div class="well" style="margin-top: 20px;">
                    <h4 class="text-center"><a href="#">Sign in</a> to post a comment</h4>
                </div>
            <?php endif ?>
            <!-- Display total number of comments on this post  -->
            <h2><span id="comments_count"><?php echo count($comments) ?></span> Comment(s)</h2>
            <hr>
            <!-- comments wrapper -->
            <div id="comments-wrapper">
                <?php if (isset($comments)): ?>
                    <!-- Display comments -->
                    <?php foreach ($comments as $comment): ?>
                        <!-- comment -->
                        <div class="comment clearfix">
                            <img src="profile.png" alt="" class="profile_pic">
                            <div class="comment-details">
                                <span class="comment-name"><?php echo getUsernameById($comment['commenterId']) ?></span>
                                <span class="comment-date"><?php echo date("F j, Y g:i:s A", strtotime($comment["timePosted"])); ?></span>
                                <p><?php echo $comment['content']; ?></p>
                                <a class="reply-btn" href="#" data-id="<?php echo $comment['id']; ?>">reply</a>
                            </div>
                            <!-- reply form -->
                            <form action="TEST_CommentFeature2.php" class="reply_form clearfix" id="comment_reply_form_<?php echo $comment['id'] ?>" data-id="<?php echo $comment['id']; ?>">
                                <textarea class="form-control" name="reply_text" id="reply_text" cols="30" rows="2"></textarea>
                                <button class="btn btn-primary btn-xs pull-right submit-reply">Submit reply</button>
                            </form>

                            <!-- GET ALL REPLIES -->
                            <?php $replies = getRepliesByCommentId($comment['id']) ?>
                            <div class="replies_wrapper_<?php echo $comment['id']; ?>">
                                <?php if (isset($replies)): ?>
                                    <?php foreach ($replies as $reply): ?>
                                        <!-- reply -->
                                        <div class="comment reply clearfix">
                                            <img src="profile.png" alt="" class="profile_pic">
                                            <div class="comment-details">
                                                <span class="comment-name"><?php echo getUsernameById($reply['commenterId']) ?></span>
                                                <span class="comment-date"><?php echo date("F j, Y g:i:s A", strtotime($reply["timePosted"])); ?></span>
                                                <p><?php echo $reply['content']; ?></p>
                                                <a class="reply-btn" href="#">reply</a>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </div>
                        </div>
                        <!-- // comment -->
                    <?php endforeach ?>
                <?php else: ?>
                    <h2>Be the first to comment on this post</h2>
                <?php endif ?>
            </div><!-- comments wrapper -->
        </div><!-- // all comments -->
    </div>
</div>

$_POST['comment_text']

<script >
    $(document).ready(function(){
        // When user clicks on submit comment to add comment under post
        $(document).on('click', '#submit_comment', function(e) {
            e.preventDefault();

            var comment_text = $('#comment_text').val();
            var url = $('#comment_form').attr('action');
            // Stop executing if not value is entered
            if (comment_text === "" ) return;
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    comment_text: comment_text,
                    comment_posted: 1
                },
                success: function(data){
                    var response = JSON.parse(data);
                    if (data === "error") {
                        alert('There was an error adding comment. Please try again');
                    } else {
                        $('#comments-wrapper').prepend(response.comment);
                        $('#comments_count').text(response.comments_count);
                        $('#comment_text').val('');
                    }
                }
            });
        });
        // When user clicks on submit reply to add reply under comment
        $(document).on('click', '.reply-btn', function(e){
            e.preventDefault();
            // Get the comment id from the reply button's data-id attribute
            var comment_id = $(this).data('id');
            // show/hide the appropriate reply form (from the reply-btn (this), go to the parent element (comment-details)
            // and then its siblings which is a form element with id comment_reply_form_ + comment_id)
            $(this).parent().siblings('form#comment_reply_form_' + comment_id).toggle(500);
            $(document).on('click', '.submit-reply', function(e){
                e.preventDefault();
                // elements
                var reply_textarea = $(this).siblings('textarea'); // reply textarea element
                var reply_text = $(this).siblings('textarea').val();
                var url = $(this).parent().attr('action');
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        comment_id: comment_id,
                        reply_text: reply_text,
                        reply_posted: 1
                    },
                    success: function(data){
                        if (data === "error") {
                            alert('There was an error adding reply. Please try again');
                        } else {
                            $('.replies_wrapper_' + comment_id).append(data);
                            reply_textarea.val('');
                        }
                    }
                });
            });
        });
    });
</script>