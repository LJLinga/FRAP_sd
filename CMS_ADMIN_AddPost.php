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

if(isset($_POST['btnSaveDraft']) || isset($_POST['btnPublish'])){

    $title = $_POST['post_title'];
    $body = $_POST['post_content'];
    $status = '1';

    if(isset($_POST['btnPublish'])){ $status = '2';}

    $id = $crud->executeGetKey("INSERT INTO posts (title, body, authorId, statusId) values ('$title', '$body', 1,'$status')");
    header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/CMS_ADMIN_EditPost.php?postId=".$id);
}

$page_title = 'Santinig - Add Post';
include 'GLOBAL_HEADER.php';
include 'GLOBAL_NAV_TopBar.php';
include 'CMS_ADMIN_NAV_Sidebar.php';
?>

<link href="quill/quill.snow.css" rel="stylesheet">
<script src="quill/quill.js"></script>

<script>
    $(document).ready( function(){

        var quill = new Quill('#editor', {
            modules: {
                toolbar: '#editorToolbar'
            },
            theme: 'snow'
        });

        $('#form').on('submit', function(){
            $('#post_content').val(JSON.stringify(quill.getContents()));
            //$('#post_content').val(quill.root.innerHTML);
            //alert(quill.root.innerHTML);
            alert(JSON.stringify(quill.getContents()));
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
                    <?php
                    if(isset($message)){
                        echo"  
                                <div class='alert alert-warning'>
                                    ". $message ."
                                </div>
                                ";
                    }
                    ?>
                </div>
            </div>
            <!--Insert success page-->
            <form id="form" name="form" method="POST" action="<?php  ?>">
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
                            <div id="editorToolbar">
                                <span class="ql-formats">
                                  <select class="ql-font"></select>
                                  <select class="ql-size"></select>
                                </span>
                                <span class="ql-formats">
                                  <button class="ql-bold"></button>
                                  <button class="ql-italic"></button>
                                  <button class="ql-underline"></button>
                                  <button class="ql-strike"></button>
                                </span>
                                <span class="ql-formats">
                                  <select class="ql-color"></select>
                                  <select class="ql-background"></select>
                                </span>
                                <span class="ql-formats">
                                  <button class="ql-script" value="sub"></button>
                                  <button class="ql-script" value="super"></button>
                                </span>
                                <span class="ql-formats">
                                  <button class="ql-header" value="1"></button>
                                  <button class="ql-header" value="2"></button>
                                  <button class="ql-blockquote"></button>
                                  <button class="ql-code-block"></button>
                                </span>
                                <span class="ql-formats">
                                  <button class="ql-list" value="ordered"></button>
                                  <button class="ql-list" value="bullet"></button>
                                  <button class="ql-indent" value="-1"></button>
                                  <button class="ql-indent" value="+1"></button>
                                </span>
                                <span class="ql-formats">
                                  <button class="ql-direction" value="rtl"></button>
                                  <select class="ql-align"></select>
                                </span>
                                <span class="ql-formats">
                                  <button class="ql-link"></button>
                                  <button class="ql-image"></button>
                                  <button class="ql-video"></button>
                                  <button class="ql-formula"></button>
                                </span>
                                <span class="ql-formats">
                                  <button class="ql-clean"></button>
                                </span>
                            </div>
                            <div id="editor">
                            </div>
                            <input type="hidden" name="post_content" id="post_content">
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


