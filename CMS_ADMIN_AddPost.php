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

if(isset($_POST['btnSubmit'])){
    $title = $_POST['post_title'];
    $body = $crud->escape_string($_POST['post_content']);
    $status = $_POST['btnSubmit'];

    $postId = $crud->executeGetKey("INSERT INTO posts (title, body, authorId, statusId) values ('$title', '$body','$userId','$status')");
    if(!empty ($postId)) {
        if($status=='3' && $cmsRole=='3'){
            $crud->execute("UPDATE posts SET publisherId='$userId' WHERE id='$postId';");
            $result = $crud->execute("SELECT timePublished FROM posts WHERE id='$postId' AND permalink IS NULL");
            if(empty($result[0]['permalink'])) {
                include('CMS_FUNCTION_PERMALINK.php');
                $permalink = generate_permalink($title);
                $crud->execute("UPDATE posts SET permalink='$permalink' WHERE id='$postId' AND permalink IS NULL");
            }
        }
        if($status=='4'){
            $crud->execute("UPDATE posts SET archivedById='$userId' WHERE id='$postId';");
        }
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_ADMIN_EditPost.php?postId=" . $postId);
    }else{
        echo '<script language="javascript">';
        echo 'alert("something went wrong")';
        echo '</script>';
    }
}

$page_title = 'Santinig - Add Post';
include 'GLOBAL_HEADER.php';
include 'CMS_ADMIN_SIDEBAR.php';
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
    </style>
    <script>
        $(document).ready( function(){
            $('textarea').froalaEditor({
                // Disables video upload
                videoUpload: false,
                //
                imageUploadURL: 'CMS_SERVER_INCLUDES/CMS_SERVER_IMAGE_Upload.php',
                // Set the file upload URL.
                fileUploadURL: 'CMS_SERVER_INCLUDES/CMS_SERVER_FILE_Upload.php'
            }).on('froalaEditor.image.error', function (e, editor, error, response) {
                console.log(error);
                console.log(response);
            }).on('froalaEditor.file.error', function (e, editor, error, response) {
                console.log(error);
                console.log(response);
            });
        });
    </script>

    <div id="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">
                        Add New Post
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
                            <input id="post_title" name="post_title" type="text" placeholder="Put your post title here..." class="form-control input-md" value="<?php if(isset($title)){ echo $title; }; ?>" required>
                        </div>

                        <!-- Textarea -->
                        <div class="form-group">
                            <label for="post_content">Content</label>
                            <textarea name="post_content" id="post_content"></textarea>
                        </div>
                    </div>
                    <div id="publishColumn" class="column col-lg-4" style="margin-bottom: 1rem;">

                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="reference">References</label>
                                    <div id="reference">
                                        <button type="button" onclick="alertBox();" id="btnReference" name="btnReference" class="btn btn-sm">Add Reference</button><p></p>
                                        <input id="ref_1" name="ref_1" type="text" placeholder="No document referenced yet..." class="form-control input-sm" disabled required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body">
                                <div class="form-group">
                                    <?php
                                    if($cmsRole == '3') {
                                        echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Save as Draft</button> ';
                                        echo '<button type="submit" class="btn btn-primary" name="btnSubmit" id="btnSubmit" value="3">Publish</button> ';
                                    }else if($cmsRole == '2'){
                                        echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Save as Draft</button> ';
                                        echo '<button type="submit" class="btn btn-primary" name="btnSubmit" id="btnSubmit" value="2">Submit for Review</button> ';
                                    }
                                    ?>
                                </div>
                                <input type="hidden" id="post_id" name="post_id" value="<?php if(isset($postId)){ echo $postId;};?>">
                            </div>
                        </div>

                        <!-- Button -->

                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /#page-wrapper -->
<?php include 'GLOBAL_FOOTER.php' ?>