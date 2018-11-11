<?php
include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */
//hardcoded value for userType, will add MYSQL verification query
$userType = 'editor';
$authorId = '1';

if(isset($_POST['btnSaveDraft']) || isset($_POST['btnSubmit'])){
    $title = $_POST['post_title'];
    $body = $crud->escape_string($_POST['post_content']);
    $status = '1';

    if(isset($_POST['btnSubmit'])){ $status = '2';}

    $id = $crud->executeGetKey("INSERT INTO posts (title, body, authorId, statusId) values ('$title', '$body','$authorId','$status')");
    if(!empty ($id)) {
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_ADMIN_EditPost.php?postId=" . $id);
    }else{
        echo '<script language="javascript">';
        echo 'alert("something went wrong")';
        echo '</script>';
    }
}
$page_title = 'Santinig - Add Post';
include 'GLOBAL_HEADER.php';
include 'GLOBAL_NAV_TopBar.php';
include 'CMS_ADMIN_NAV_Sidebar.php';
?>
    <script>
        $(document).ready( function(){
            $('textarea').froalaEditor({
                //Disables video upload
                videoUpload: false,
                // Set the image upload URL.
                imageUploadURL: 'CMS_SERVER_INCLUDES/CMS_SERVER_IMAGE_Upload.php',
                // Set the file upload URL.
                fileUploadURL: 'CMS_SERVER_INCLUDES/CMS_SERVER_FILE_Upload.php'
            }).on('froalaEditor.image.error', function (e, editor, error, response) {
                console.log(error);
                console.log(response);
            });
        });
    </script>

    <div id="page-wrapper">
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
                    <div class="column col-lg-6">
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
                    <div class="column col-lg-4">

                        <div class="card">
                            <div class="card-body" style="margin-bottom: 1rem;">
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
                                    <label for="submitStatus">Submit Action</label>
                                    <select class="form-control" id="submitStatus" name="submitStatus">
                                        <option value="1">Save as Draft</option>
                                        <option value="2">Submit for Review</option>
                                        <?php if($cmsRole=='3'){ echo "<option value=\"3\">Publish</option>";}?>
                                        <option value="4">Archive</option>
                                    </select>
                                </div>
                                <input type="hidden" id="post_id" name="post_id" value="<?php if(isset($postId)){ echo $postId;};?>">
                                <button type="submit" class="btn btn-primary" name="btnSubmit" id="btnSubmit">Submit</button>

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