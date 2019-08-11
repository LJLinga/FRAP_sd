<?php
include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
//include('GLOBAL_CMS_ADMIN_CHECKING.php');

/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */
$userId = $_SESSION['idnum'];

if(isset($_POST['btnSubmit'])){
    $title = $crud->escape_string($_POST['title']);
    $body = $crud->escape_string($_POST['content']);

    $rows = $crud->getPostPublicationFirstStep();
    if(!empty($rows)){
        foreach((array) $rows AS $key => $row){
            $nextStepId = $row['id'];
        }
    }

    $postId = $crud->executeGetKey("INSERT INTO posts (title, body, authorId, statusId) values ('$title', '$body','$userId','$status')");
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_NEO_EditPost.php?postId=" . $postId);
}

$page_title = 'Faculty Manual - Add Section';
include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';
?>
<div id="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <h3 class="page-header">
                    Create New Section
                </h3>
                <div class="panel panel-default">
                    <form method="POST" action="">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="sectionNo">Section Marker</label>
                                <input id="sectionNo" name="sectionNo" type="text" class="form-control input-md" required>
                            </div>
                            <div class="form-group">
                                <label for="sectionTitle">Title</label>
                                <input id="sectionTitle" name="sectionTitle" type="text" class="form-control input-md" required>
                            </div>
                            <div class="form-group">
                                <label for="sectionContent">Content</label>
                                <textarea name="sectionContent" class="form-control" rows="20" id="sectionContent"></textarea>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="btn btn-secondary" onclick="javascript:window.close()">Cancel</button>
                            <button type="submit" name="btnSubmit" id="btnSubmit" class="btn btn-primary">Submit </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'GLOBAL_FOOTER.php' ?>
<script>
    $(document).ready(function(){
        $('#btnSubmit').hide();
        tinymce.init({selector:'#sectionContent', height: 720,
            setup:function(ed) {
                ed.on('change', function(e) {
                    let cont = ed.getContent();
                    if(cont !== ''){
                        $('#btnSubmit').show();
                    }else{
                        $('#btnSubmit').hide();
                    }
                    console.log('triggered');
                });
            }});
    });
</script>
