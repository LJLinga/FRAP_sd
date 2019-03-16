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
    $references = $_POST['references'];



    $postId = $crud->executeGetKey("INSERT INTO posts (title, body, authorId, statusId) values ('$title', '$body','$userId','$status')");
    if(!empty ($postId)) {
        foreach($references AS $key => $value){
            echo $references[$key];
            $query = "INSERT INTO post_ref_versions(postId,versionId) VALUES ('$postId','$references[$key]');";
            $crud->execute($query);
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

        let table = $('table').dataTable();

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

            table.dataTable({
                "pageLength": 5
            });
        });

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
                    '</div></div></div>'+'<input type="hidden" name="documentReferences[]" value="'+verId+'">');
            reloadToAddTable(verId);
        }

        function reloadToAddTable(){

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
                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalRED"><i class="fa fa-fw fa-link"></i> Ref. Existing Document </button>
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

                    <form id="formRED" name="formRED" method="POST"">
                        <table class="table table-bordered" align="center" id="dataTable" style="font-size: 12px;">
                            <thead>
                            <tr>
                                <th> Document </th>
                                <th> Assigned Process </th>
                                <th width="20px"> Add </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                                $rows = $crud->getData("SELECT d.documentId, CONCAT(e.lastName,', ',e.firstName) AS originalAuthor,
                                                            v.versionId as vid, v.versionNo, v.title, v.timeCreated, pr.id AS processId, pr.processName, s.stepNo, s.stepName,
                                                            (SELECT CONCAT(e.lastName,', ',e.firstName) FROM doc_versions v JOIN employee e ON v.authorId = e.EMP_ID 
                                                            WHERE v.versionId = vid) AS currentAuthor
                                                            FROM documents d JOIN doc_versions v ON d.documentId = v.documentId
                                                            JOIN employee e ON e.EMP_ID = d.firstAuthorId 
                                                            JOIN steps s ON s.id = d.stepId
                                                            JOIN process pr ON pr.id = d.processId 
                                                            WHERE s.processId = pr.id AND v.versionId = 
                                                            (SELECT MAX(v1.versionId) FROM doc_versions v1 WHERE v1.documentId = d.documentId)");
                                if(!empty($rows)){
                                    foreach ((array) $rows as $key => $row){
                                        $title=$row['title'];
                                        $versionNo = $row['versionNo'];
                                        $originalAuthor = $row['originalAuthor'];
                                        $currentAuthor = $row['currentAuthor'];
                                        $processName=$row['processName'];
                                        $updatedOn = date("F j, Y g:i:s A ", strtotime($row['timeCreated']));
                                        echo '<tr>';
                                        echo '<td><b>'.$title.'</b> 
                                                <span class="badge">'.$versionNo.'</span><br>
                                                Author: '.$originalAuthor.'<br>
                                                Modified by: '.$currentAuthor.'<br>
                                                on : <i>'.$updatedOn.'</i><br></td>';
                                        echo '<td>'.$processName.'</td>';
                                        echo '<td><button type="button" id="btnAddDocument" class="btn btn-default" onclick="addRef(this,&quot;'.$row['vid'].'&quot;,&quot;'.$originalAuthor.'&quot;,&quot;'.$currentAuthor.'&quot;,&quot;'.$versionNo.'&quot;,&quot;'.$updatedOn.'&quot;,&quot;'.$title.'&quot;,&quot;'.$processName.'&quot;);" value="'.$row['vid'].'">Add</button></td>';
                                        echo '</tr>';
                                    }
                                }
                            ?>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>
<?php include 'GLOBAL_FOOTER.php' ?>