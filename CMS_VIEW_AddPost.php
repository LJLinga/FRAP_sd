<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

include_once('DB_CLASS_CRUD.php');
$crud = new DB_CLASS_CRUD();

if(isset($_POST['btnAddPost'])){

    //$postId = $_POST['postId'];
    $postId = "";
    $authorId = $_POST['userId'];
    $title = $_POST['title'];
    $body = $_POST['body'];

    if($postId==null || $postId==""){

        $result = $crud->execute("INSERT INTO posts(authorId, title, body) values('$authorId','$title','$body')");
        echo 'Added new post! <br>';
        echo $_POST['userId'].', '.$_POST['title'].', '.$_POST['body'];

    }else if($postId!=""){

        $result = $crud->execute("UPDATE posts SET
                      title = '$title',
                      body = '$body'
                      WHERE id = '$postId' ");
        echo 'Updated the post! <br>';
        echo $_POST['userId'].', '.$_POST['title'].', '.$_POST['body'];
    }
}

include 'CMS_TEMPLATE_Navigation.php';
?>
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Add Post
                    </h1>
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
            <!-- alert -->
            <div class="row">
                <div class="col-lg-12">
                    <p><i>Fields with <big class="req">*</big> are required to be filled out and those without are optional.</i></p>
                    <!--Insert success page-->
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="postId" id="postId" value="<?php echo $_POST['postId']; ?>">
                        <input type="hidden" name="userId" id="userId" value="1">
                        <div class="addaccountdiv">
                            <label class="signfieldlabel">Title</label><big class="req"> *</big>
                            <input type="text" name="title" id="title" class="form-control signupfield" placeholder="Post Title" required>
                        </div><br>
                        <div class="addaccountdiv">
                            <label class="signfieldlabel">Content</label><big class="req"> *</big>
                            <input type="textarea" name="body" id="body" class="form-control signupfield" placeholder="Write content here..." required>
                        </div><br>
                        <div id="subbutton">
                            <button type="submit" class="btn btn-success" name="btnAddPost">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /#page-wrapper -->
<?php include 'CMS_TEMPLATE_Footer.php' ?>


