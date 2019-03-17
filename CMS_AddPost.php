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
$cmsRole = $_SESSION['CMS_ROLE'];

if(isset($_POST['btnSubmit'])){
    $title = $crud->escape_string($_POST['post_title']);
    $body = $crud->escape_string($_POST['post_content']);
    $status = $_POST['btnSubmit'];

    $postId = $crud->executeGetKey("INSERT INTO posts (title, body, authorId, statusId) values ('$title', '$body','$userId','$status')");
    if(!empty ($postId)) {

        if(isset($_POST['toAddRefs'])) {
            $toAddRefs = $_POST['toAddRefs'];
            foreach((array) $toAddRefs AS $key => $ref){
                echo $toAddRefs[$key];
                $query = "INSERT INTO post_ref_versions(postId,versionId) VALUES ('$postId','$ref');";
                $crud->execute($query);
            }
        }

        if($status=='3' && $cmsRole=='3'){
            $crud->execute("UPDATE posts SET reviewedById='$userId' WHERE id='$postId';");
        }
        if($status=='4' && $cmsRole=='4'){
            $crud->execute("UPDATE posts SET publisherId='$userId' WHERE id='$postId';");
            $result = $crud->execute("SELECT timePublished FROM posts WHERE id='$postId' AND permalink IS NULL");
            if(empty($result[0]['permalink'])) {
                include('CMS_FUNCTION_Permalink.php');
                $permalink = generate_permalink($title);
                $crud->execute("UPDATE posts SET permalink='$permalink' WHERE id='$postId' AND permalink IS NULL");
            }
        }
        if($status=='5'){
            $crud->execute("UPDATE posts SET archivedById='$userId' WHERE id='$postId';");
        }
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_EditPost.php?postId=" . $postId);
    }
}

$page_title = 'Santinig - Add Post';
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR.php';
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
        .fr-view {
            font-family: "Verdana", Georgia, Serif;
            font-size: 14px;
            color: #444444;
        }
    </style>
    <script>

        //let table = $('table').dataTable();

        $(document).ready( function(){
            $('textarea').froalaEditor({
                // Disables video upload
                videoUpload: false,
                //
                imageUploadURL: 'CMS_SERVER_INCLUDES/CMS_SERVER_IMAGE_Upload.php',
                // Set the file upload URL.
                fileUploadURL: 'CMS_SERVER_INCLUDES/CMS_SERVER_FILE_Upload.php',
                width: 750,
                pastePlain: false
            });

            $('#btnRefModal').on('click', function(){
                reloadDataTable();
            });
        });


        function reloadDataTable(){
            let loadedRefs = [];
            $(".refDocuments").each(function() {
                loadedRefs.push($(this).val());
            });
            $('table').dataTable({
                destroy: true,
                "pageLength": 5,
                "ajax": {
                    "url":"CMS_AJAX_LoadToAddReferences.php",
                    "type":"POST",
                    "data":{ loadedReferences: loadedRefs },
                    "dataSrc": ''
                },
                columns: [
                    { data: "Document" },
                    { data: "Status" },
                    { data: "Action" }
                ]
            });
        }

        function addRef(element, verId, oA, cA, vN, uO, t, pN){
            $('#noRefsYet').remove();
            $('#refDocuments').append('<div class="card">'+
                    '<div class="row"><div class="col-sm-8">'+
                    '<a style="text-align: left;" class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse'+verId+'" aria-expanded="true" aria-controls="collapse'+verId+'"><b>'+t+'</b> <span class="badge">'+vN+'</span></a>'+
                    '</div>'+
                    '<div class="col-sm-4">'+
                    '</div></div>'+
                    '<div id="collapse'+verId+'" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">'+
                    '<div class="card-body">'+
                        'Process: '+pN+'<br>'+
                    'Created by: '+oA+'<br>'+
                    'Modified by: '+cA+
                    ' on: <i>'+uO+'</i><br>'+
                    '</div></div></div>'+'<input type="hidden" name="toAddRefs[]" class="refDocuments" value="'+verId+'">');
            reloadDataTable();
        }

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
                    <div id="publishColumn" class="column col-lg-4" style="margin-top: 1rem; margin-bottom: 1rem;">

                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body">
                                <span id="noRefsYet">No References</span>
                                <span id="refDocuments" style="font-size: 12px;">
                                </span>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-default"><i class="fa fa-fw fa-plus"></i> Ref. New Document </button>
                                <button id="btnRefModal" type="button" class="btn btn-default" data-toggle="modal" data-target="#modalRED"><i class="fa fa-fw fa-link"></i> Ref. Existing Document </button>
                            </div>
                        </div>

                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body">
                                No Faculty Manual References
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-default"><i class="fa fa-fw fa-link"></i> Ref. Existing Section </button>
                            </div>
                        </div>

                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body">
                                Unsaved
                            </div>
                            <div class="card-footer">
                                <?php
                                if($cmsRole == '3') {
                                    echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Save as Draft</button> ';
                                    echo '<button type="submit" class="btn btn-primary" name="btnSubmit" id="btnSubmit" value="3">Submit for Publication</button> ';
                                }else if($cmsRole == '2'){
                                    echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Save as Draft</button> ';
                                    echo '<button type="submit" class="btn btn-primary" name="btnSubmit" id="btnSubmit" value="2">Submit for Review</button> ';
                                }else if($cmsRole == '4'){
                                    echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Save as Draft</button> ';
                                    echo '<button type="submit" class="btn btn-primary" name="btnSubmit" id="btnSubmit" value="4">Publish</button> ';
                                }
                                ?>
                            </div>
                        </div>

                        <!-- Button -->

                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /#page-wrapper -->

    <!-- Modal -->
    <div id="modalRED" class="modal fade" role="dialog" data-backdrop="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    <h5 class="modal-title">Reference Document</h5>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" align="center" id="dataTable">
                        <thead>
                        <tr>
                            <th> Document </th>
                            <th> Assigned Process </th>
                            <th width="20px"> Add </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>
<?php include 'GLOBAL_FOOTER.php' ?>