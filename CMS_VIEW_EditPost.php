<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$userType = 'editor';
$body = 'hello';

//hardcoded value for userType, will add MYSQL verification query

if(isset($_GET['edit'])){
    $mode = "edit";
    $postId = $_GET['edit'];
    $rows = $crud->getData("SELECT title, body FROM posts WHERE id='$postId'");
    foreach ((array) $rows as $key => $row) {
        $title = $row['title'];
        $body = $row['body'];
    }
    $head = "Edit: ".$title;
}else{
    header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/CMS_VIEW_PostsDashboard.php");
}

$page_title = 'Santinig - Add Post';
include 'GLOBAL_TEMPLATE_Header.php';
include 'CMS_TEMPLATE_NAVIGATION_Editor.php';
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

        quill.setContents((<?php echo json_encode($body) ?>));

        console.log((<?php echo json_encode($body) ?>));

        $('#form').submit(function(event){

            var title = $('input[name=post_title]').val();

            $.ajax({
                method: 'POST',
                url: 'ajax/CMS_POST_INSERT.php',
                data: {
                    'title': title,
                    'body': quill.getContents()
                },
                success: function(result) {
                    console.log('Post ID: ' + result);
                    window.location = 'CMS_VIEW_EditPost.php?edit=' + result;
                }
            });
            event.preventDefault();
        });
    });

    function alertBox(){
        alert("Replace this alert with modal for document selection.");
    };
    function submit(){
        alert("Replace ");
    };

</script>

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    <?php
                    echo $head;
                    if($userType == 'editor'){
                        if($mode == 'edit'){
                            echo '<p></p><button type="button" class="btn btn-primary">Save and Publish</button>';
                            echo ' <button type="button" class="btn btn-default">Preview Article</button>';
                        }
                    }else{
                        if($mode == 'edit'){
                            echo '<p></p><button type="button" class="btn btn-default">Preview Article</button>';
                        }
                    }
                    ?>
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
        <form id="form" name="form" method="POST" action="ajax/CMS_POST_INSERT.php">
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
                        <div id="editor" class="height: 500px">
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
                    <input type="hidden" id="hidMode" name="hidMode" value="add">
                    <input type="hidden" id="hidPostId" name="hidPostId" value="<?php if(isset($postId)){ echo $postId; }; ?>">
                    <?php
                    if($userType == 'editor'){
                        $btnSubmitLabel = 'Save and Publish';
                    }else{
                        $btnSubmitLabel = 'Save';
                    }
                    ?>
                    <button type="submit" class="btn btn-success" name="btnSubmit" id="btnSubmit">
                        <?php echo $btnSubmitLabel; ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- /#page-wrapper -->
<?php include 'GLOBAL_TEMPLATE_Footer.php' ?>


