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
$status = '1';
$statusDesc = 'Draft';
$body = '';
$author = 'no author';
$firstPosted = 'Jan 1 2000';
$lastUpdated = 'Jan 1 2000';

//hardcoded value for userType, will add MYSQL verification query

if(!empty($_GET['postId'])){

    $postId = $_GET['postId'];
    $rows = $crud->getData("SELECT p.title, 
                                  CONCAT(a.firstName,a.lastName) AS author, 
                                  CONCAT(pub.firstName, pub.lastName) AS publisher, 
                                  p.body, p.firstCreated, p.lastUpdated, p.statusId, s.description 
                                  FROM authors a JOIN posts p ON p.authorId=a.id JOIN 
                                  FROM posts p JOIN post_status s ON p.statusId = s.id WHERE p.id='$postId'");
    foreach ((array) $rows as $key => $row) {
        $title = $row['title'];
        $body = $row['body'];
        $author = $row['author'];
        $status = $row['statusId'];
        $firstPosted = $row['firstPosted'];
        $lastUpdated = $row['lastUpdated'];
        $statusDesc = $row['description'];
    }

    if($userType=='author'){
        if($status=='3'){
            echo '';
        }
    }

    $head = "Edit: ".$title;
}

if(isset($_POST['btnSubmit'])) {

    $title = $_POST['post_title'];
    $body = $crud->escape_string($_POST['post_content']);
    $status = $_POST['submitStatus'];

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
                    <?php echo $head;?>
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

                    <div class="card" style="margin-bottom: 1rem;">
                        <div class="card-body">
                            Author: <b><?php echo $author; ?></b><br>
                            Publisher: <b>Christian Nicole Alderite</b><br>
                            Created on: <?php echo $firstPosted?><br><br>
                            Current Status: <b><?php echo $statusDesc?></b><br>
                            <i>Last updated: <b><?php echo $lastUpdated?></b></i><br>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="submitStatus">Submit Action</label>
                                <select class="form-control" id="submitStatus" name="submitStatus">
                                    <option value="1">Save as Draft</option>
                                    <option value="2">Submit for Review</option>
                                    <?php if($userType=='editor'){?>
                                    <option value="3">Publish</option>
                                    <?php };?>
                                    <option value="4">Archive</option>
                                </select>
                            </div>
                            <input type="hidden" id="post_id" name="post_id" value="<?php if(isset($postId)){ echo $postId;};?>">
                            <button type="submit" class="btn btn-primary" name="btnSubmit" id="btnSubmit">Submit</button>

                        </div>
                    </div>

                    <div class="card">
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

                    <!-- Button -->

                </div>

            </div>
            <div class="row">

            </div>
        </form>
    </div>
</div>
<!-- /#page-wrapper -->
<?php include 'GLOBAL_FOOTER.php' ?>


