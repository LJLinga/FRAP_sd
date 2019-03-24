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
$revisions = 'closed';

if(isset($_POST['btnPrint'])){
    //$crud->execute("INSERT INTO revisions (initiatedById, statusId) VALUES ('$userId','2')");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_PublishSections.php");
}

if(isset($_POST['btnPublish'])){

    $rows = $crud->execute("SELECT v.* FROM facultyassocnew.section_versions v 
                    WHERE v.timeCreated = (SELECT MAX(v2.timeCreated) FROM section_versions v2 WHERE v.sectionId = v2.sectionId)
                    AND v.statusId = 2");
    //$crud->execute("INSERT INTO revisions (initiatedById, statusId) VALUES ('$userId','2')");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_PublishSections.php");
}

if(isset($_POST['btnOpen'])){
    $crud->execute("INSERT INTO revisions (initiatedById, statusId) VALUES ('$userId','2')");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ManualRevisions.php");
}

if(isset($_POST['btnClose'])){
    $revisionsId = $_POST['btnClose'];
    $crud->execute("UPDATE revisions SET closedById = '$userId', statusId = 1 WHERE id = '$revisionsId' ");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ManualRevisions.php");
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
                        if($revisions == 'open' && isset($write) && $write = '2') echo '<a class="btn btn-primary" href="EDMS_AddSection.php">Add Section</a>';
                        else if ($revisions == 'closed') echo '';
                        ?>
                    <form method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                        <button name="btnPrint">Generate Manual PDF</button>
                    </form>
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body" style="position: relative;">
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
                            echo 'Faculty Manual Revisions are still closed.';
                            if($edmsRole == '4') {
                                echo '<span style="position: absolute; top:4px; right:4px;">';
                                echo '<button class="btn btn-success" name="btnOpen"> Open Revisions </button>';
                                echo '</span>';
                            }
                        }
                        ?>
                        </form>
                    </div>
                </div>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header btn-group">
                        <a type="button" class="btn btn-default" id="btnAll" onclick="searchTable('')">All</a>
                        <?php
                        $rows = $crud->getData("SELECT status FROM facultyassocnew.section_status;");
                        foreach((array) $rows as $key => $row){
                            echo '<a type="button" class="btn btn-default" onclick="searchTable(&quot;'.$row['status'].'&quot;)">'.$row['status'].'</a>';
                        }
                        ?>
                    </div>
                    <div class="card-body">
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
                    <div class="card-footer">
                        <span id="lastUpdatedOn">Last Updated on....</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        Your (<b>
                            <?php
                            $rows = $crud->getData("SELECT roleName FROM edms_roles WHERE id = ".$_SESSION['EDMS_ROLE']." LIMIT 1;");
                            echo $rows[0]['roleName'];
                            ?>
                        </b>) Workflows
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
                        foreach((array)$rows AS $key => $row){
                            echo '<div class="card">';
                            echo '<div class="card-body">';
                            echo $row['processName'].' -> Step '.$row['stepNo'].' '.$row['name'].'<br>';
                            echo '</div></div>';
                        }
                        ?>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        My Activities
                    </div>
                    <div class="card-body">
                        <div class="col-lg-2">All <b class="caret"></b></div>
                        <div class="col-lg-7"></div>
                        <div class="col-lg-3"><i class="fa fa-fw fa-plus-circle"></i>Create Groups</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<script>


    $('table.table').DataTable( {
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
    } );

</script>

<?php include 'GLOBAL_FOOTER.php';?>
