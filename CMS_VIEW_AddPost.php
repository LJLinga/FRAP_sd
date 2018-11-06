<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

//hardcoded value for userType, will add MYSQL verification query
$userType = 'editor';

$mode = "add";
$head = "Add Post";
$body = "";


//I was attempting to make the AddPost also become EditPost
//If coming from the Posts Dashboard, this is edit, if from Add Post this is add.
//If new post was submitted, refreesh nalang to Posts Dashboard
//Modify posts dashboard to show most recent post first based on date (SQL)
//Add the Author credentials to the post


//If the user came from a submit from the same page YOU CAN REMOVE THIS IF NEEDED
if(isset($_POST['btnSubmit'])){
    $mode = "edit";

    $title = $_POST['post_title'];
    $body = $_POST['post_content'];

    $head = " Edit: ".$title;

    if($_POST['hidMode']=="add"){
        $crud->execute("INSERT INTO posts (title, body, authorId, statusId) values ($title, $body, 1,1)");
        //header("Location: CMS_VIEW_PostsDashboard.php");
    }elseif($_POST['hidMode']=="edit"){
        $postId = $_POST['hidPostId'];
        $crud->execute("UPDATE posts SET title=$title, body=$body WHERE id=$postId ");
    }
}

// if user came from the posts dashboard
if(isset($_POST['edit'])){
    $mode = "edit";

    $postId = $_POST['edit'];
    $rows = $crud->getData("SELECT title, body FROM posts WHERE id='$postId'");
    foreach ($rows as $key => $row) {
        $title = $row['title'];
        $body = $row['body'];
    }
    $head = "Edit: ".$title;
}

//check if post has been published before



$page_title = 'Santinig - Add Post';
include 'GLOBAL_TEMPLATE_Header.php';
include 'CMS_TEMPLATE_NAVIGATION_Editor.php';
?>

<link href="quill/quill.snow.css" rel="stylesheet">
<script src="quill/quill.js"></script>

<script>

    $(document).ready( function(){

        var preloaded = "<?php echo $body; ?>";

        var quill = new Quill('#editor', {
            modules: {
                toolbar: '#editorToolbar'
            },
            theme: 'snow'
        });

        quill.setContents({
            "ops":[
                {"insert": preloaded}
            ]
        });

        var form = document.querySelector('form');
        form.onsubmit = function() {
            // Populate hidden form on submit
            var about = document.querySelector('input[name=post_content]');
            about.value = JSON.stringify(quill.getContents());

            console.log("Submitted", $(form).serialize(), $(form).serializeArray());

            // No back end to actually submit to!
            alert('Open the console to see the submit data!')
            return false;
        };

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
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
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
                                <?php //echo $body;
                                ?>
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
                            <?php echo $body; ?>
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


