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
$edmsRole = $_SESSION['EDMS_ROLE'];

$query = "SELECT r.id, r.revisionsOpened FROM revisions r WHERE r.statusId = 2 ORDER BY r.id DESC LIMIT 1;";
$rows = $crud->getData($query);
if(!empty($rows)){
    $revisions = 'open';
    foreach ((array) $rows as $key => $row){
        $revisionsOpened = $row['revisionsOpened'];
        $revisionsId = $row['id'];
    }
}else{
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_ManualRevisions.php");
}

$rows = $crud->getData("SELECT sr.* FROM step_roles sr WHERE stepId = 9 AND roleId = '$edmsRole';");
if(!empty($rows)){
    foreach((array) $rows as $key => $row){
        //$read= $row['read'];
        $write= $row['write'];
        $route= $row['route'];
        //$comment = $row['comment'];
    }
}else{
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_ManualRevisions.php");
}

if(isset($_POST['btnRoute'])){
    $title = $crud->escape_string($_POST['section_title']);
    $sectionNo = $crud->escape_string($_POST['section_number']);
    $content = $crud->escape_string($_POST['section_content']);
    $nextStepId = $_POST['btnRoute'];
    //$parentSectionId = $_POST['section_parent'];
    //$siblingSectionId = $_POST['section_sibling'];
    if(isset($_POST['toAddDocRefs'])) {
        $toAddDocRefs = $_POST['toAddDocRefs'];
        foreach((array) $toAddDocRefs AS $key => $ref){
            $query = "INSERT INTO section_ref_versions(sectionId,versionId) VALUES ('$sectionId','$ref');";
            $crud->execute($query);
        }
    }
    $sectionId = $crud->executeGetKey("INSERT INTO sections (authorId, firstAuthorId, sectionNo, stepId, title, content) VALUES ('$userId','$userId','$sectionNo','$nextStepId', '$title', '$content')");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_EditSection.php?secId=".$sectionId);
}

$page_title = 'Faculty Manual - Add Section';
include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';
?>
    <div id="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">
                        Create New Section
                    </h3>

                </div>
            </div>
            <!--Insert success page-->
            <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                <div class="row">
                    <div class="column col-lg-7">
                        <!-- Text input-->
                        <div class="form-group">
                            <label for="section_number">Section Marker</label>
                            <input id="section_number" name="section_number" type="text" placeholder="Put your section number here..." class="form-control input-md"  required>
                        </div>
                        <div class="form-group">
                            <label for="section_title">Title</label>
                            <input id="section_title" name="section_title" type="text" placeholder="Put your section title here..." class="form-control input-md" required>
                        </div>
                        <div class="form-group">
                            <label for="section_content">Content</label>
                            <textarea name="section_content" class="form-control" rows="20" id="section_content"></textarea>
                        </div>
                    </div>

                    <div id="publishColumn" class="column col-lg-4" style="margin-top: 1rem; margin-bottom: 1rem;">
                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-header"><b>Referenced Minutes</b></div>
                            <div class="card-body" style="max-height: 20rem; overflow-y: scroll;">
                                <span id="noRefsYet">No References</span>
                                <span id="refDocuments" style="font-size: 12px;">
                                </span>
                            </div>
                            <div class="card-footer">
                                <button id="btnRefModal" type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalRED"><i class="fa fa-fw fa-link"></i>Add</button>
                            </div>
                        </div>

                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body">
                                Unsaved
                            </div>
                            <div class="card-footer">
                                <?php
                                    if(isset($route) && $route == '2') {
                                        $rows = $crud->getData("SELECT sr.* FROM step_routes sr WHERE sr.currentStepId = 9;");
                                        if(!empty($rows)){
                                            foreach((array) $rows as $key => $row){
                                                echo '<button type="submit" class="btn btn-primary" name="btnRoute" value="'.$row['nextStepId'].'">'.$row['routeName'].'</button>';
                                            }
                                        }
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
<script>
    $(document).ready( function(){
        $('#section_parent').on('change', function(){
            alert('100');
            // AJAX query the latest section #
            // Based on looping
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
            "pageLength": 3,
            "ajax": {
                "url":"EDMS_AJAX_LoadToReferences.php",
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
    function addRef(element, verId, oA, cA, vN, uO, t, pN, fP, fN){
        $('#noRefsYet').remove();
        $('#btnUpdate').show();
        $('#refDocuments').append('<div class="card" style="background-color: #e2fee2; position: relative;">'+
            '<input type="hidden" name="toAddDocRefs[]" class="refDocuments" value="'+verId+'">'+
            '<a style="text-align: left;" class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse'+verId+'" aria-expanded="true" aria-controls="collapse'+verId+'"><b>'+t+'</b> <span class="badge">'+vN+'</span></a>'+
            '<div class="btn-group" style="position: absolute; right: 2px; top: 2px;" >'+
            '<a class="btn fa fa-download"  href="'+fP+'" download="'+fN+'"></a>'+
            '<a class="btn fa fa-remove" onclick="removeRef(this)" ></a>'+
            '</div>'+
            '<div id="collapse'+verId+'" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">'+
            '<div class="card-body">'+
            'Process: '+pN+'<br>'+
            'Created by: '+oA+'<br>'+
            'Modified by: '+cA+'<br>'+
            'on: <i>'+uO+'</i><br>'+
            '</div></div></div>');
        reloadDataTable();
    }

    function removeRef(element) {
        $(element).closest('div.card').remove();
    }
</script>
<?php include 'GLOBAL_FOOTER.php' ?>