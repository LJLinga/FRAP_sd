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
$cmsRole = $_SESSION['CMS_ROLE'];

if(!empty($_GET['postId'])){

    $postId = $_GET['postId'];

    $rows1 = $crud->getData("SELECT p.authorId FROM posts p WHERE p.id = '$postId'");
    foreach((array) $rows1 as $key => $row){
        $authorId = $row['authorId'];
    }

    if($cmsRole != '3' && $authorId != $userId){
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_PostsDashboard.php");
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

if(isset($_POST['btnSubmit'])) {

    $title = $crud->escape_string($_POST['post_title']);
    $body = $crud->escape_string($_POST['post_content']);
    $status = $_POST['btnSubmit'];

    if($crud->execute("UPDATE posts SET title='$title', body='$body', statusId='$status' WHERE id='$postId';")) {
        if($status=='3' && $cmsRole=='3'){
            $crud->execute("UPDATE posts SET publisherId='$userId' WHERE id='$postId';");
            $result = $crud->execute("SELECT permalink FROM posts WHERE id='$postId' AND permalink IS NULL");
            if(empty($result[0]['permalink'])) {
                include('CMS_FUNCTION_Permalink.php');
                $permalink = generate_permalink($title);
                $crud->execute("UPDATE posts SET permalink='$permalink' WHERE id='$postId' AND permalink IS NULL");
            }
        }
        if($status=='4'){
            $crud->execute("UPDATE posts SET archivedById='$userId' WHERE id='$postId';");
        }
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_EditPost.php?postId=" . $postId);
    }

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
    <script type="text/javascript" src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
    <script>
        $(document).ready( function(){

            let status = <?php echo $status; ?>;
            let cmsRole = <?php echo $cmsRole; ?>;

            $('#btnUpdate').hide();

            $('textarea').froalaEditor({
                //Disables video upload
                videoUpload: false,
                // Set the image upload URL
                imageUploadURL: 'CMS_SERVER_INCLUDES/CMS_SERVER_IMAGE_Upload.php',
                // Set the file upload URL.
                fileUploadURL: 'CMS_SERVER_INCLUDES/CMS_SERVER_FILE_Upload.php',
                //Allow comments
                width: 750
            });

            if(status == 3 && cmsRole!= 3){
                $('textarea').froalaEditor("edit.off");
            }

            $('textarea').on('froalaEditor.contentChanged', function (e, editor) {
                $('#btnUpdate').show();
            });

            $('textarea').froalaEditor('html.set', '<?php echo $body?>');

            $('#btnComment').onclick( function(){
                $('#comment').html($('textarea').froalaEditor('html.getSelected'));
                alert('hello');
            });

            $('textarea').on('froalaEditor.image');

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

        function addComment(){
            $('#comment').html($('textarea').froalaEditor('html.getSelected'));
        }
    </script>

    <div id="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">
                        <?php echo $head;?>
                    </h3>

                </div>
            </div>
            <!--Insert success page-->
            <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                <div class="row">
                    <div class="column col-lg-7">
                        <!-- Text input-->
                        <div class="form-group">
                            <label for="post_title">Title</label>
                            <input <?php if($cmsRole != '3' && $status == '3') echo 'disabled' ?> id="post_title" name="post_title" type="text" placeholder="Put your post title here..." class="form-control input-md" value="<?php if(isset($title)){ echo $title; }; ?>" required>
                        </div>

                        <!-- Textarea -->
                        <div class="form-group">
                            <label for="post_content">Content</label>
                            <textarea name="post_content" id="post_content"></textarea>
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
                                <?php if(!empty($publisher)){ echo "Publisher: <b>".$publisher."</b><br>"; }?>
                                <i>Last updated: <b><?php  echo date("F j, Y g:i:s A ", strtotime($lastUpdated));?></b></i><br><br>
                            </div>

                            <div class="card-footer">
                                <input type="hidden" id="post_id" name="post_id" value="<?php if(isset($postId)){ echo $postId;}; ?>">
                                <?php


                                if($cmsRole == '3') {
                                    if ($status == '2' || $status == '1') {
                                        echo '<button type="submit" class="btn btn-primary" name="btnSubmit" id="btnSubmit" value="3">Publish</button> ';
                                        echo '<button type="submit" class="btn btn-danger" name="btnSubmit" id="btnSubmit" value="4">Trash</button> ';
                                    } else if ($status == '3') {
                                        echo '<button type="submit" class="btn btn-primary" name="btnSubmit" id="btnUpdate" value="3" hidden>Publish Changes</button> ';
                                        echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Switch to Draft</button> ';
                                        echo '<button type="submit" class="btn btn-danger" name="btnSubmit" id="btnSubmit" value="4">Trash</button> ';
                                    } else if ($status == '4') {
                                        echo '<button type="submit" class="btn btn-success" name="btnSubmit" id="btnSubmit" value="' . $prevStatus . '">Restore</button> ';
                                    }
                                }else if($cmsRole == '2'){
                                    if ($status == '1') {
                                        echo '<button type="submit" class="btn btn-primary" name="btnSubmit" id="btnSubmit" value="2">Submit for Review</button> ';
                                        echo '<button type="submit" class="btn btn-danger" name="btnSubmit" id="btnSubmit" value="4">Trash</button> ';
                                    } else if ($status == '2') {
                                        echo '<button type="submit" class="btn btn-primary" name="btnSubmit" id="btnUpdate" value="2">Resubmit for Review</button> ';
                                        echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Switch to Draft</button> ';
                                        echo '<button type="submit" class="btn btn-danger" name="btnSubmit" id="btnSubmit" value="4">Trash</button> ';
                                    } else if ($status == '4') {
                                        echo '<button type="submit" class="btn btn-success" name="btnSubmit" id="btnSubmit" value="' . $prevStatus . '">Restore</button> ';
                                    } else if ($status == '3') {
                                        echo '<b>Ask your publisher to unpublish to edit.</b>';
                                    }
                                }
                                ?>
                            </div>
                        </div>

                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body" >
                                <button type="button" class="btn btn-default" name="btnComment" id="btnComment" onclick="addComment()">Comment</button>
                                <p id="comment" name="comment"></p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">

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
<?php include 'GLOBAL_FOOTER.php' ?>