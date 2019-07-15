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
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_PublishSections.php");
}

if(isset($_POST['btnPublish'])){
    $year = $_POST['year'];
    $title = $_POST['title'];
    $publishedById = $userId;

    $manualId = $crud->executeGetKey("INSERT INTO faculty_manual (year, title, publishedById) VALUES ('$year','$title','$publishedById');");

    $rows = $crud->getData("SELECT v.timeCreated, v.sectionId FROM facultyassocnew.section_versions v 
                                    WHERE v.timeCreated = (SELECT MAX(v2.timeCreated) FROM section_versions v2 WHERE v.sectionId = v2.sectionId)
                                    AND v.statusId = 2");

    foreach((array)$rows AS $key=>$row){
        $sectionId = $row['sectionId'];
        $timeCreated = $row['timeCreated'];
        $crud->execute("INSERT INTO published_versions (manualId, sectionId, timeCreated) VALUES ('$manualId','$sectionId','$timeCreated')");
    }
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_PublishSections.php");
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
                    <?php
                        if($revisions == 'open' && $boolInGroup) echo '<a class="btn btn-primary" href="MANUAL_AddSection.php">Add Section</a>';
                        ?>
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-body" style="position: relative;">
                        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>">
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
                                echo '<button type="button" id="btnPublish" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Publish Changes</button>';
                                echo '</span>';
                            }
                        }
                        ?>
                        </form>
                    </div>
                </div>
                <div class="panel panel-default" style="margin-top: 1rem;">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-inline">
                                    <label for="sel1">Ver. No. </label>
                                    <select class="form-control" id="selectedVersion" name="selectedUser">
                                        <option value="">All</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-inline">
                                    <label for="sel1">User </label>
                                    <select class="form-control" id="selectedUser" name="selectedUser">
                                        <option value="">All</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-inline">
                                    <label for="sel1">Action</label>
                                    <select class="form-control" id="selectedAction" name="selectedAction">
                                        <option value="" selected>All</option>
                                        <option value="created">CREATED</option>
                                        <option value="updated">UPDATED</option>
                                        <option value="moved">MOVED</option>
                                        <option value="checked out">CHECKED OUT</option>
                                        <option value="checked in">CHECKED IN</option>
                                        <option value="draft">DRAFT</option>
                                        <option value="pending">PENDING</option>
                                        <option value="approved">APPROVED</option>
                                        <option value="rejected">REJECTED</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-inline">
                                    <label for="sel1">Search</label>
                                    <input type="text" id="searchField" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table id="tblSections" class="table table-striped">
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
                    <div class="panel-footer">
                        <span id="lastUpdatedOn">Last Updated on....</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <b>
                            <?php
                            $rows = $crud->getData("SELECT roleName FROM edms_roles WHERE id = ".$_SESSION['EDMS_ROLE']." LIMIT 1;");
                            echo $rows[0]['roleName'];
                            ?>
                        </b> Workflows
                    </div>
                    <div class="panel-body">
                        <?php
                        $rows = $crud->getData("SELECT  pr.processName, s.stepNo, s.stepName AS name 
                                                FROM employee e 
                                                JOIN edms_roles er ON e.EDMS_ROLE = er.id 
                                                JOIN step_roles sr ON sr.roleId = er.id
                                                JOIN steps s ON s.id = sr.stepId
                                                JOIN process pr ON pr.id = s.processId
                                                WHERE e.EMP_ID = '$userId'
                                                ORDER BY pr.processName, s.stepNo");
                        if(!empty($rows)){
                            foreach((array)$rows AS $key => $row){
                                echo '<div class="card">';
                                echo '<div class="card-body">';
                                echo $row['processName'].' <i class="fa fa-arrow-right"></i> Step '.$row['stepNo'].' - '.$row['name'].'<br>';
                                echo '</div></div>';
                            }
                        }else{
                            echo 'You have no workflows but is still able to submit documents to their respective workflows.';
                        }
                        ?>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Published Manual Editions
                    </div>
                    <div class="panel-body">
                        <?php
                        $rows = $crud->getData("SELECT id, year, title, timePublished, publishedById 
                                        FROM facultyassocnew.faculty_manual ORDER BY id DESC;");
                        if(!empty($rows)){
                            foreach((array)$rows AS $key => $row){
                                echo '<div class="card" style="position: relative;">';
                                echo '<div class="card-body">';
                                echo $row['title'].' ('.$row['year'].')<br>';
                                echo '<a href="MANUAL_PublishSections.php?id='.$row['id'].'" target="_blank" class="btn btn-primary btn-sm" style="position: absolute; right: 2rem; top: 0.5rem;"><i class="fa fa-print"></i></a>';
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

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
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
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <input type="submit" name="btnPublish" id="btnPublish" class="btn btn-primary">
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
        bSort: false,
        destroy: true,
        pageLength: 5,
        "ajax": {
            "url":"EDMS_AJAX_FetchSections.php",
            "type":"POST",
            "dataSrc": ''
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
            var selectSecNo = $('#selectedSection').on( 'change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                columnSecNo.search( val ? '^'+val+'$' : '', true, false ).draw();
            } );
            var columnVer = this.api().column(2);
            var selectVer = $('#selectedVersion').on( 'change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                columnVer.search( val ? '^'+val+'$' : '', true, false ).draw();
            } );
            columnVer.data().unique().sort().each( function ( d, j ) {
                selectVer.append( '<option value="'+d+'">'+d+'</option>' )
            } );

            var columnUser = this.api().column(4);
            var selectUser = $('#selectedUser').on( 'change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                columnUser.search( val ? '^'+val+'$' : '', true, false ).draw();
            } );
            columnUser.data().unique().sort().each( function ( d, j ) {
                selectUser.append( '<option value="'+d+'">'+d+'</option>' )
            } );

            var columnAction = this.api().column(6);
            var selectAction = $('#selectedStatus').on( 'change', function () {
                columnAction.search($('#selectedStatus').val()).draw();
            } );
        }
    });

    $(".dataTables_filter").hide();
    $('#searchField').keyup(function(){
        table.search($('#searchField').val()).draw();
    });

    setInterval(function(){
        table.ajax.reload(null,false);
    },5000);

</script>

<?php include 'GLOBAL_FOOTER.php';?>
