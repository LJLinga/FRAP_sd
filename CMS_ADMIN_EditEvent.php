<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

require_once __DIR__ . '/vendor/autoload.php';

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_CMS_ADMIN_CHECKING.php');

if(!empty($_GET['postId'])){

    $postId = $_GET['postId'];
    $rows = $crud->getData("SELECT 
            p.title,
            CONCAT(u.firstName,' ', u.lastName) AS author,
            p.body,
            p.firstCreated,
            p.lastUpdated,
            p.statusId,
            s.description
        FROM
            users u
                JOIN
            posts p ON p.authorId = u.id
                JOIN
            post_status s ON p.statusId = s.id
        WHERE
            p.id = '$postId'   ");

    foreach ((array) $rows as $key => $row) {
        $title = $row['title'];
        $body = $row['body'];
        $author = $row['author'];
        $status = $row['statusId'];
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
                users pub ON pub.id = p.publisherId
            WHERE
                p.id = '$postId' ;
        ");
        foreach((array) $pubQuery as $key => $row){
            $publisher = $row['publisher'];
        }
    }

    $head = "Edit: ".$title;

}

if(isset($_POST['btnSubmit'])) {

    $title = $_POST['post_title'];
    $body = $crud->escape_string($_POST['post_content']);
    $status = $_POST['submitStatus'];

    if($crud->execute("UPDATE posts SET title='$title', body='$body', statusId='$status' WHERE id='$postId';")) {

        if($status=='3' && $cmsRole=='3'){
            $crud->execute("UPDATE posts SET publisherId='$userId' WHERE id='$postId';");
        }
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_ADMIN_EditPost.php?postId=" . $postId);
    }
}

$page_title = 'Santinig - Edit Post';
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
            //Disables video upload
            videoUpload: false,
            // Set the image upload URL
            imageUploadURL: 'CMS_SERVER_INCLUDES/CMS_SERVER_IMAGE_Upload.php',
            // Set the file upload URL.
            fileUploadURL: 'CMS_SERVER_INCLUDES/CMS_SERVER_FILE_Upload.php'
            //Allow comments
        });

        $('textarea').froalaEditor('html.set', '<?php echo $body?>');

        $('#btnComment').onclick( function(){
            $('#comment').html($('textarea').froalaEditor('html.getSelected'));
            alert('hello');
        });

    });

    $('#datetimepicker1').datetimepicker( {
        minDate: moment(),
        locale: moment().local('ph')
    });

    $('#datetimepicker2').datetimepicker( {
        minDate: moment(),
        locale: moment().local('ph')
    });
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
                        <input id="post_title" name="post_title" type="text" placeholder="Put your post title here..." class="form-control input-md" value="<?php if(isset($title)){ echo $title; }; ?>" required>
                    </div>

                    <!-- Textarea -->
                    <div class="form-group">
                        <label for="post_content">Content</label>
                        <textarea name="post_content" id="post_content"></textarea>
                    </div>
                </div>
                <div id="publishColumn" class="column col-lg-4" style="margin-bottom: 1rem; right:1rem;">

                    <div class="card" style="margin-bottom: 1rem;">
                        <div class="card-body" >
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
                        <div class="card-body" >
                            Author: <b><?php echo $author; ?></b><br>
                            <i>Created on: <b><?php echo $firstPosted?></b></i><br><br>

                            Current Status: <b><?php echo $statusDesc?></b><br>
                            <?php if(!empty($publisher)){ echo "Publisher: <b>".$publisher."</b><br>"; }?>
                            <i>Last updated: <b><?php echo $lastUpdated?></b></i><br>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="submitStatus">Submit Action</label>
                                <select class="form-control" id="submitStatus" name="submitStatus">
                                    <option <?php if ($status == '1' ) echo 'selected' ; ?> value="1">Save as Draft</option>
                                    <option <?php if ($status == '2' ) echo 'selected' ; ?> value="2">Submit for Review</option>
                                    <?php if($cmsRole == '3'){
                                        $text = '';
                                        if($status == '3'){
                                            $text='selected';
                                        }
                                        echo "<option ".$text." value=\"3\">Publish</option>" ?>

                                    <?php };?>
                                    <option <?php if ($status == '4' ) echo 'selected' ; ?> value="4">Archive</option>
                                </select>
                            </div>
                            <input type="hidden" id="post_id" name="post_id" value="<?php if(isset($postId)){ echo $postId;};?>">
                            <button type="submit" class="btn btn-primary" name="btnSubmit" id="btnSubmit">Submit</button>

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
<!-- /#page-wrapper -->
<?php include 'GLOBAL_FOOTER.php' ?>


