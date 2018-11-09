<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

require_once __DIR__.'/vendor/autoload.php';

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$userType = 'editor';
$head = 'Add New Post';
$title = '';
$result = 'no json';
$body = '';

//hardcoded value for userType, will add MYSQL verification query

if(isset($_GET['postId'])){
    $postId = $_GET['postId'];
    $rows = $crud->getData("SELECT title, body FROM posts WHERE id='$postId'");
    foreach ((array) $rows as $key => $row) {
        $title = $row['title'];
        $body = $row['body'];
    }
    $head = "Edit: ".$title;
}

if(isset($_POST['btnSaveDraft']) || isset($_POST['btnPublish'])) {

    $postId = $_POST['post_id'];
    $title = $_POST['post_title'];
    $body = $crud->escape_string($_POST['post_content']);
    $status = '1';

    if(isset($_POST['btnPublish'])){ $status = '2';}

    if($crud->execute("UPDATE posts SET title='$title', body='$body', authorId='1', statusId='$status' WHERE id='$postId';")) {
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_ADMIN_EditPost.php?postId=" . $postId);
    }

}

$page_title = 'Santinig - Edit Post';
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
            //imageUploadParam: 'image_param',
            imageUploadURL: 'CMS_SERVER_INCLUDES/CMS_SERVER_IMAGE_Upload.php',
            imageUploadParams: {
                id: 'my_editor'
            },

            // Set the file upload URL.
            fileUploadURL: 'CMS_SERVER_INCLUDES/CMS_SERVER_FILE_Upload.php',
            fileUploadParams: {
                id: 'my_editor'
            }

        });

        $('textarea').froalaEditor('html.set', '<?php echo $body?>');


    });
</script>

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    <?php echo $head.$postId;?>
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
                    <!-- Button -->
                    <div class="form-group">
                        <label for="reference">References</label>
                        <div id="reference">
                            <button type="button" onclick="alertBox();" id="btnReference" name="btnReference" class="btn btn-sm">Add Reference</button><p></p>
                            <input id="ref_1" name="ref_1" type="text" placeholder="No document referenced yet..." class="form-control input-sm" disabled required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label  for="customFile">Upload attachment</label>
                        <input type="file" class="" id="customFile">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <input type="hidden" id="post_id" name="post_id" value="<?php if(isset($postId)){ echo $postId;};?>">
                    <button type="submit" class="btn btn-default" name="btnSaveDraft" id="btnSaveDraft">Save Draft</button>
                    <?php if($userType=='editor'){
                        echo "<button type=\"submit\" class=\"btn btn-primary\" name=\"btnPublish\" id=\"btnPublish\">Publish</button>";
                    }?>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- /#page-wrapper -->
<?php include 'GLOBAL_FOOTER.php' ?>


