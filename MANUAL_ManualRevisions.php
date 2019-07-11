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
$edmsRole = $_SESSION['EDMS_ROLE'];
$panelRole = $_SESSION['PANEL_ROLE'];
$revisions = 'closed';

if($_SESSION['EDMS_ROLE'] != 3 && $_SESSION['EDMS_ROLE'] != 4 && $_SESSION['EDMS_ROLE'] != 5 && $_SESSION['EDMS_ROLE'] != 6){
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/feed.php");
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
    //header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_PublishSections.php");
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

$rows = $crud->getData("SELECT sr.* FROM step_roles sr WHERE stepId = 9 AND roleId = '$edmsRole';");
if(!empty($rows)){
    foreach((array) $rows as $key => $row){
        $read= $row['read'];
        $write= $row['write'];
        $route= $row['route'];
        $comment = $row['comment'];
    }
}

include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';
?>

<div id="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header"> Manual Revisions
                    <?php
                        if($revisions == 'open' && isset($write) && $write = '2') echo '<a class="btn btn-primary" href="MANUAL_AddSection.php">Add Section</a>';
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
                            if($edmsRole == '4'){
                                echo '<span style="position: absolute; top:4px; right:4px;">';
                                echo 'Revisions Actions: ';
                                echo '<button class="btn btn-danger" name="btnClose" value="'.$revisionsId.'"> Close Revisions </button> ';
                                echo '</span>';
                            }
                        }else{
                            echo 'Faculty Manual Revisions are closed.';
                            if($edmsRole == '4') {
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
                        <div class="btn-group">
                        <a type="button" class="btn btn-default" id="btnAll" onclick="searchTable('')">All</a>
                        <?php
                        $rows = $crud->getData("SELECT status FROM facultyassocnew.section_status WHERE id!= 4;");
                        if(!empty($rows)){
                            foreach((array) $rows as $key => $row){
                                echo '<a type="button" class="btn btn-default" onclick="searchTable(&quot;'.$row['status'].'&quot;,&quot;3&quot;)">'.$row['status'].'</a>';
                            }
                        }
                        ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table id="myTable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th width="100px">No.</th>
                                <th width="400px">Title</th>
                                <th width="300px">Modified By</th>
                                <th width="200px">Status</th>
                                <th width="100px">Action</th>
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
                <div class="panel panel-info">
                    <div class="panel-heading">
                        My Activities
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-2">All <b class="caret"></b></div>
                        <div class="col-lg-7"></div>
                        <div class="col-lg-3"><i class="fa fa-fw fa-plus-circle"></i>Create Groups</div>
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

//    $('table.table').DataTable( {
//        "ajax": {
//            "url":"EDMS_AJAX_FetchSections.php",
//            "type":"POST",
//            "data":{ role: '<?php //echo $edmsRole;?>//'},
//            "dataSrc": ''
//        },
//        columns: [
//            { data: "section_no" },
//            { data: "title" },
//            { data: "modified_by" },
//            { data: "status" },
//            { data: "action" },
//        ]
//    } );

    $(document).ready(function(){
        $('#datetimepicker1').datetimepicker( {
            locale: moment().local('ph'),
            defaultDate: moment(),
            minDate: moment(),
            format: 'YYYY'
        });
    });

    searchTable('',3);

    function searchTable(searchText, col){
        let table = $('table.table').DataTable( {
            bSort: false,
            destroy: true,
            pageLength: 5,
            "ajax": {
                "url":"EDMS_AJAX_FetchSections.php",
                "type":"POST",
                "data":{ role: '<?php echo $edmsRole;?>'},
                "dataSrc": ''
            },
            columns: [
                { data: "section_no" },
                { data: "title" },
                { data: "modified_by" },
                { data: "status" },
                { data: "action" },
            ]
        });
        if(searchText === 'All'){
            searchText = '';
        }
        table.column(col).search(searchText).draw();
        // setInterval(function(){
        //     table.ajax.reload();
        // },1000)
    }
</script>

<?php include 'GLOBAL_FOOTER.php';?>
