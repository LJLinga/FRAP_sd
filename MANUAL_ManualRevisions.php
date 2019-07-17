<?php
/**
 * Created by PhpStorm.
 * User: Christian Alderite
 * Date: 10/4/2018
 * Time: 3:48 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
//include('GLOBAL_EDMS_ADMIN_CHECKING.php');

$userId = $_SESSION['idnum'];
$revisions = 'closed';

$rows = $crud->doesUserHaveWorkflow($_SESSION['idnum'],7);

$boolInGroup = false;
if(!empty($rows)){
    $boolInGroup = true;
}

if(isset($_POST['btnPrint'])){
    //$crud->execute("INSERT INTO revisions (initiatedById, statusId) VALUES ('$userId','2')");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_PrintManual.php");
}

if(isset($_POST['btnPublish'])){
    $year = $_POST['year'];
    $title = $_POST['title'];
    $publishedById = $userId;

    $manualId = $crud->executeGetKey("INSERT INTO faculty_manual (year, title, publishedById) VALUES ('$year','$title','$publishedById');");

    $rows = $crud->getData("SELECT v.versionId, v.sectionId FROM facultyassocnew.section_versions v 
                                    WHERE v.versionId = (SELECT MAX(v2.versionId) FROM section_versions v2 WHERE v.sectionId = v2.sectionId)
                                    AND v.statusId = 3");
    //Get the newly APPROVED sections from section_versions first -> Copy them to new manual
    //Get the old PUBLISHED sections from old manual WHERE sectionId NOT IN current manual sectionId -> Copy them to new manual
    foreach((array)$rows AS $key=>$row){
        $sectionId = $row['sectionId'];
        $versionId = $row['versionId'];
        $crud->execute("INSERT INTO published_versions (manualId, sectionId, versionId) VALUES ('$manualId','$sectionId','$versionId')");
    }
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_PrintManual.php?id=".$manualId);
}

if(isset($_POST['btnOpen'])){
    $crud->execute("INSERT INTO revisions (initiatedById, statusId) VALUES ('$userId','2')");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_ManualRevisions.php");
}

if(isset($_POST['btnClose'])){
    $revisionsId = $_POST['btnClose'];
    $crud->execute("UPDATE revisions SET closedById = '$userId', statusId = 1 WHERE id = '$revisionsId' ");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_ManualRevisions.php");
}

$query = "SELECT r.id, r.revisionsOpened FROM revisions r WHERE r.statusId = 2 ORDER BY r.id DESC LIMIT 1;";
$rows = $crud->getData($query);
if(!empty($rows)){
    $revisions = 'open';
    foreach ((array) $rows as $key => $row){
        $revisionsOpened = $row['revisionsOpened'];
        $revisionsId = $row['id'];
    }
}

$boolPres = $crud->isUserInGroupName($userId,"GRP_PRESIDENT");

include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';
?>

<div id="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header"> Manual Revisions
                    <?php if($revisions == 'open' && $boolInGroup) echo '<a class="btn btn-primary" href="MANUAL_AddSection.php">Add Section</a>'; ?>
                </h3>
            </div>
        </div>
        <div class="row" style="height: 100%;">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body" style="position: relative;">
                        <form method="POST" action="">
                            <?php if($revisions == 'open') {
                                echo '<b> Faculty Manual Revisions </b> started last '.$revisionsOpened;
                                if($boolPres){
                                    echo '<span style="position: absolute; top:4px; right:4px;">';
                                    echo 'Revisions Actions: ';
                                    echo '<button class="btn btn-danger" name="btnClose" value="'.$revisionsId.'"> Close Revisions </button> ';
                                    echo '</span>';
                                }
                            }else{
                                echo 'Faculty Manual Revisions are closed.';
                                if($boolPres) {
                                    echo '<span style="position: absolute; top:4px; right:4px;" class="btn-group">';
                                    echo '<button class="btn btn-success" name="btnOpen"> Open Revisions </button>';
                                    echo '<button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Publish Changes</button>';
                                    echo '</span>';
                                }
                            }
                            ?>
                        </form>
                    </div>
                </div>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Needs attention</a></li>
                    <li role="presentation"><a href="#editing" aria-controls="editing" role="tab" data-toggle="tab">I'm currently editing</a></li>
                    <li role="presentation"><a href="#active" aria-controls="active" role="tab" data-toggle="tab">Active</a></li>
                    <li role="presentation"><a href="#archived" aria-controls="archived" role="tab" data-toggle="tab">Archived</a></li>
                    <li role="presentation"><a href="#published" aria-controls="published" role="tab" data-toggle="tab">Manual Editions</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="home">
                        <div class="panel panel-secondary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-inline">
                                            <label for="sel1">Ver. No. </label>
                                            <select class="form-control" id="selectedNo" name="selectedNo">
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-inline">
                                            <label for="sel1">Modified by </label>
                                            <select class="form-control" id="selectedUser" name="selectedUser">
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-inline">
                                            <label for="sel1">Search</label>
                                            <input type="text" id="searchField" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <table id="tblSections" class="table table-striped table-responsive table-condensed table-sm" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Title</th>
                                        <th>Ver. No.</th>
                                        <th>Created on</th>
                                        <th>Modified by</th>
                                        <th>Modified on</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="editing">
                        <div class="panel panel-secondary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-inline">
                                            <label for="sel1">Ver. No. </label>
                                            <select class="form-control" id="selectedNo2" name="selectedNo">
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-inline">
                                            <label for="sel1">Modified by </label>
                                            <select class="form-control" id="selectedUser2" name="selectedUser">
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-inline">
                                            <label for="sel1">Search</label>
                                            <input type="text" id="searchField2" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <table id="tblSections2" class="table table-striped table-responsive table-condensed table-sm" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Title</th>
                                        <th>Ver. No.</th>
                                        <th>Created on</th>
                                        <th>Modified by</th>
                                        <th>Modified on</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="active">
                        <div class="panel panel-secondary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-inline">
                                            <label for="sel1">Ver. No. </label>
                                            <select class="form-control" id="selectedNo3" name="selectedNo">
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-inline">
                                            <label for="sel1">Modified by </label>
                                            <select class="form-control" id="selectedUser3" name="selectedUser">
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-inline">
                                            <label for="sel1">Search</label>
                                            <input type="text" id="searchField3" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <table id="tblSections3" class="table table-striped table-responsive table-condensed table-sm" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Title</th>
                                        <th>Ver. No.</th>
                                        <th>Created on</th>
                                        <th>Modified by</th>
                                        <th>Modified on</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="archived">
                        <div class="panel panel-secondary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-inline">
                                            <label for="sel1">Ver. No. </label>
                                            <select class="form-control" id="selectedNo4" name="selectedNo">
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-inline">
                                            <label for="sel1">Modified by </label>
                                            <select class="form-control" id="selectedUser4" name="selectedUser">
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-inline">
                                            <label for="sel1">Search</label>
                                            <input type="text" id="searchField4" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <table id="tblSections4" class="table table-striped table-responsive table-condensed table-sm" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Title</th>
                                        <th>Ver. No.</th>
                                        <th>Created on</th>
                                        <th>Modified by</th>
                                        <th>Modified on</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="published">
                        <div class="panel panel-secondary">
                            <div class="panel-heading">
                            </div>
                            <div class="panel-body" style="max-height:50rem; inherit;overflow-y: auto;">
                                <?php
                                $rows = $crud->getData("SELECT id, year, title, timePublished, publishedById 
                                        FROM facultyassocnew.faculty_manual ORDER BY id DESC;");
                                if(!empty($rows)){
                                    foreach((array)$rows AS $key => $row){
                                        echo '<div class="card" style="position: relative;">';
                                        echo '<div class="card-body">';
                                        echo $row['title'].' ('.$row['year'].')<br>';
                                        echo '<a href="MANUAL_PrintManual.php?id='.$row['id'].'" target="_blank" class="btn btn-primary btn-sm" style="position: absolute; right: 2rem; top: 0.5rem;"><i class="fa fa-print"></i></a>';
                                        echo '</div></div>';
                                    }
                                }else{
                                    echo 'You have no published manuals editions.';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <form method="POST" action="">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <strong class="modal-title">Publish Manual Edition</strong>
                </div>
                <div class="modal-body">
                    <?php

                    $rows = $crud->getData("SELECT v.versionId, v.sectionId FROM facultyassocnew.section_versions v 
                                    WHERE v.versionId = (SELECT MAX(v2.versionId) FROM section_versions v2 WHERE v.sectionId = v2.sectionId
                                    AND v2.statusId = 3 LIMIT 1) AND v.lifecycleId = 1");
                    if(!empty($rows)){
                    ?>
                    <div class="form-group">
                        <label for="year">Edition Year</label>
                        <div class="input-group date" id="datetimepicker1">
                            <input id="year" name="year" type="text" class="form-control">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="AFED Inc. Manual" required>
                    </div>
                    <span id="err"></span>
                    <?php }else { ?>
                        <div class="alert alert-warning">
                            <strong>There are no updated and publishable (<?php echo $crud->coloriseStatus(3);?>) sections as of the moment. </strong>
                        </div>
                    <?php  } ?>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <?php if(!empty($rows)){ ?>
                        <input type="submit" name="btnPublish" id="btnPublish" class="btn btn-primary">
                        <?php } ?>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<script>

    $(document).ready(function(){
        $('#datetimepicker1').datetimepicker( {
            locale: moment().local('ph'),
            defaultDate: moment(),
            minDate: moment(),
            format: 'YYYY'
        });
    });

    let table = $('#tblSections').DataTable( {
        bSort: true,
        destroy: true,
        pageLength: 10,
        aaSorting: [],
        "ajax": {
            "url":"EDMS_AJAX_FetchSections.php",
            "type":"POST",
            "dataSrc": '',
            "data": {requestType: 'MANUAL_SECTIONS_WRITEROUTE'}
        },
        columns: [
            { data: "section_no" },
            { data: "title" },
            { data: "ver_no" },
            { data: "created_on" },
            { data: "modified_by" },
            { data: "modified_on" },
            { data: "status" },
            { data: "action" },
        ],
        initComplete: function(){
            var columnSecNo = this.api().column(0);
            var selectSecNo = $('#selectedNo').on( 'change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                columnSecNo.search( val ? '^'+val+'$' : '', true, false ).draw();
            } );
            columnSecNo.data().unique().sort().each( function ( d, j ) {
                selectSecNo.append( '<option value="'+d+'">'+d+'</option>' )
            } );
            var columnUser = this.api().column(4);
            var selectUser = $('#selectedUser').on( 'change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                columnUser.search( val ? '^'+val+'$' : '', true, false ).draw();
            } );
            columnUser.data().unique().sort().each( function ( d, j ) {
                selectUser.append( '<option value="'+d+'">'+d+'</option>' )
            } );
        }
    });

    let table2 = $('#tblSections2').DataTable( {
        bSort: true,
        destroy: true,
        pageLength: 10,
        aaSorting: [],
        "ajax": {
            "url":"EDMS_AJAX_FetchSections.php",
            "type":"POST",
            "dataSrc": '',
            "data": {requestType: 'MANUAL_SECTIONS_EDITING'}
        },
        columns: [
            { data: "section_no" },
            { data: "title" },
            { data: "ver_no" },
            { data: "created_on" },
            { data: "modified_by" },
            { data: "modified_on" },
            { data: "status" },
            { data: "action" },
        ],
        initComplete: function(){
            var columnSecNo = this.api().column(0);
            var selectSecNo = $('#selectedNo2').on( 'change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                columnSecNo.search( val ? '^'+val+'$' : '', true, false ).draw();
            } );
            columnSecNo.data().unique().sort().each( function ( d, j ) {
                selectSecNo.append( '<option value="'+d+'">'+d+'</option>' )
            } );
            var columnUser = this.api().column(4);
            var selectUser = $('#selectedUser2').on( 'change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                columnUser.search( val ? '^'+val+'$' : '', true, false ).draw();
            } );
            columnUser.data().unique().sort().each( function ( d, j ) {
                selectUser.append( '<option value="'+d+'">'+d+'</option>' )
            } );
        }
    });

    let table3 = $('#tblSections3').DataTable( {
        bSort: true,
        destroy: true,
        pageLength: 10,
        aaSorting: [],
        "ajax": {
            "url":"EDMS_AJAX_FetchSections.php",
            "type":"POST",
            "dataSrc": '',
            "data": {requestType: 'MANUAL_SECTIONS_READCOMMENT'}
        },
        columns: [
            { data: "section_no" },
            { data: "title" },
            { data: "ver_no" },
            { data: "created_on" },
            { data: "modified_by" },
            { data: "modified_on" },
            { data: "status" },
            { data: "action" },
        ],
        initComplete: function(){
            var columnSecNo = this.api().column(0);
            var selectSecNo = $('#selectedNo3').on( 'change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                columnSecNo.search( val ? '^'+val+'$' : '', true, false ).draw();
            } );
            columnSecNo.data().unique().sort().each( function ( d, j ) {
                selectSecNo.append( '<option value="'+d+'">'+d+'</option>' )
            } );
            var columnUser = this.api().column(4);
            var selectUser = $('#selectedUser3').on( 'change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                columnUser.search( val ? '^'+val+'$' : '', true, false ).draw();
            } );
            columnUser.data().unique().sort().each( function ( d, j ) {
                selectUser.append( '<option value="'+d+'">'+d+'</option>' )
            } );
        }
    });

    let table4 = $('#tblSections4').DataTable( {
        bSort: true,
        destroy: true,
        pageLength: 10,
        aaSorting: [],
        "ajax": {
            "url":"EDMS_AJAX_FetchSections.php",
            "type":"POST",
            "dataSrc": '',
            "data": {requestType: 'MANUAL_SECTIONS_ARCHIVED'}
        },
        columns: [
            { data: "section_no" },
            { data: "title" },
            { data: "ver_no" },
            { data: "created_on" },
            { data: "modified_by" },
            { data: "modified_on" },
            { data: "status" },
            { data: "action" },
        ],
        initComplete: function(){
            var columnSecNo = this.api().column(0);
            var selectSecNo = $('#selectedNo4').on( 'change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                columnSecNo.search( val ? '^'+val+'$' : '', true, false ).draw();
            } );
            columnSecNo.data().unique().sort().each( function ( d, j ) {
                selectSecNo.append( '<option value="'+d+'">'+d+'</option>' )
            } );
            var columnUser = this.api().column(4);
            var selectUser = $('#selectedUser4').on( 'change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                columnUser.search( val ? '^'+val+'$' : '', true, false ).draw();
            } );
            columnUser.data().unique().sort().each( function ( d, j ) {
                selectUser.append( '<option value="'+d+'">'+d+'</option>' )
            } );
        }
    });

    $(".dataTables_filter").hide();
    $('#searchField').keyup(function(){
        table.search($('#searchField').val()).draw();
    });
    $('#searchField2').keyup(function(){
        table2.search($('#searchField').val()).draw();
    });
    $('#searchField3').keyup(function(){
        table3.search($('#searchField').val()).draw();
    });
    $('#searchField4').keyup(function(){
        table4.search($('#searchField').val()).draw();
    });

    setInterval(function(){
        table.ajax.reload(null,false);
        table2.ajax.reload(null,false);
        table3.ajax.reload(null,false);
        table4.ajax.reload(null,false);
    },5000);

</script>

<?php include 'GLOBAL_FOOTER.php';?>
