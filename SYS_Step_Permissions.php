<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/25/2019
 * Time: 4:05 AM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_CMS_ADMIN_CHECKING.php');

$userId = $_SESSION['idnum'];

function permissionString($num){
    if($num == '2'){
        return 'YES';
    }else{
        return 'NO';
    }
}

if(isset($_POST['btnUpdateGroup'])){
    $stepId = $_POST['stepId'];
    $groupId = $_POST['groupId'];
    $write = 1; $route = 1; $cycle = 1;
    if(isset($_POST['write'])) { $write = 2; }
    if(isset($_POST['route'])) { $route = 2; }
    if(isset($_POST['cycle'])) { $cycle = 2; }

    if($crud->execute("UPDATE steps SET gcycle='$cycle', gwrite='$write', groute='$route', groupId = '$groupId' WHERE id = '$stepId';")){
        echo 'success1';
    }else{
        echo 'Database error.';
    }
    header("Location: http://". $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/SYS_Step_Permissions.php?id=".$stepId);
}

if(isset($_POST['btnDeleteGroup'])){
    $stepId = $_POST['stepId'];
    if($crud->removeStepGroup($stepId)){
        echo 'success3';
    }else{
        echo 'Database error.';
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Step_Permissions.php?id=".$stepId);
}

if(isset($_POST['btnUpdateCreator'])){
    $stepId = $_POST['stepId'];
    $write = 1; $route = 1; $cycle =1;
    if(isset($_POST['write'])) { $write = 2; }
    if(isset($_POST['route'])) { $route = 2; }
    if(isset($_POST['cycle'])) { $cycle = 2; }

    if($crud->execute("UPDATE steps SET cycle='$cycle', `write`='$write', route='$route' WHERE id = '$stepId';")){
        echo 'success1';
    }else{
        echo 'Database error.';
    }
    header("Location: http://". $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/SYS_Step_Permissions.php?id=".$stepId);
}

if(isset($_GET['id'])){
    $stepId = $_GET['id'];
    $rows = $crud->getData("SELECT s.*, g.id AS groupId, g.groupName, g.groupDesc, p.processName 
                                    FROM steps s
                                    JOIN process p ON s.processId = p.id 
                                    LEFT JOIN groups g ON s.groupId = g.id
                                    WHERE s.id = ' $stepId'");

    if(!empty($rows)) {
        foreach ((array)$rows AS $key => $row) {
            $processId = $row['processId'];
            $processName = $row['processName'];
            $stepName = $row['stepName'];
            $stepNo = $row['stepNo'];
            $stepTypeId = $row['stepTypeId'];
            $groupId = $row['groupId'];
            $groupName = $row['groupName'];
            $groupDesc = $row['groupDesc'];
            $groupWrite = $row['gwrite'];
            $groupCycle = $row['gcycle'];
            $groupRoute = $row['groute'];
            $route= $row['route'];
            $write = $row['write'];
            $cycle = $row['cycle'];
        }
    }else{
        header("Location: http://" . $_SERVER['HTTP_HOST'] .dirname($_SERVER['PHP_SELF'])."/SYS_Workflows.php");
    }
}



$page_title = 'Configuration - Process - Permissions';
include 'GLOBAL_HEADER.php';
include 'SYS_SIDEBAR.php';

?>

<div class="content-wrapper" >
    <div class="container-fluid" id="printable">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    <div class="form-inline">
                        <a class="btn btn-info" href="SYS_Workflow_Settings.php?id=<?php echo $processId;?>"><i class="fa fa-arrow-left"></i> <?php echo $processName;?></a>
                        Step <?php echo $stepNo.': '.$stepName; ?>
                    </div>
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <small>
                            <strong>Author Permissions:</strong> One and only one group can be assigned to each step, the group will have permissions as configured here.
                            Creator permissions are set to give control to the persons who have submitted the document.
                            It is recommended that the DRAFT stage contain no group permissions and have full creator permissions and the COMPLETE stage can have the public group.
                        </small>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="form-inline">
                            <b>Group Permissions</b>
                            <?php if($groupId == '') { ?>
                                <button class="btn btn-primary" type = "button" data-toggle="modal" data-target="#modalAddGroup" > Assign a group </button >
                            <?php } ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php if($groupId != ''){ ?>
                            <table class="table table-responsive table-striped" align="center" id="dataTable">
                                <thead>
                                <tr>
                                    <th>Group Name</th>
                                    <th>Display Name</th>
                                    <th>Write</th>
                                    <th>Cycle</th>
                                    <th>Route</th>
                                    <th width="250px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <?php echo $groupName;?>
                                    </td>
                                    <td>
                                        <?php echo $groupDesc;?>
                                    </td>
                                    <td>
                                        <?php echo permissionString($groupWrite);?>
                                    </td>
                                    <td>
                                        <?php echo permissionString($groupCycle);?>
                                    </td>
                                    <td>
                                        <?php echo permissionString($groupRoute);?>
                                    </td>
                                    <td>
                                        <form action="" method="POST">
                                            <input type="hidden" name="stepId" value="<?php echo $stepId;?>"/>
                                            <button type="button" data-toggle="modal" data-target="#modalEditGroup<?php echo $groupId;?>"
                                                    class="btn btn-default"><i class="fa fa-edit"></i>Group
                                            </button>
                                            <button type="submit" class="btn btn-danger" name="btnDeleteGroup"><i class="fa fa-trash"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <div class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" id="modalEditGroup<?php echo $groupId;?>">
                                    <div class="modal-dialog">
                                        <form method="POST" action="">
                                            <input type="hidden" name="groupId" value="<?php echo $groupId;?>"/>
                                            <input type="hidden" name="stepId" value="<?php echo $stepId;?>"/>
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Update Permissions for: <?php echo $groupName.' ('.$groupDesc.')';?></h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" name="write" value="true" <?php if($write == '2') { echo 'checked'; } ?>>
                                                            <label class="form-check-label" >Write (Edit content) </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" name="cycle" value="true" <?php if($cycle == '2') { echo 'checked'; } ?>>
                                                            <label class="form-check-label" >Cycle (Archive/Restore) </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" name="route" value="true" <?php if($route == '2') { echo 'checked'; } ?>>
                                                            <label class="form-check-label" >Route (Move to step, approve, reject) </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="btnUpdateGroup" class="btn btn-primary">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                </tbody>
                            </table>
                        <?php }else{ ?>
                            <div class="alert alert-info">
                                No group has been given permissions so far.
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Creator Permissions</b>
                    </div>
                    <div class="panel-body">
                        <table class="table table-responsive table-striped" align="center" id="dataTable">
                            <thead>
                            <tr>
                                <th>Write</th>
                                <th>Cycle</th>
                                <th>Route</th>
                                <th width="250px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <?php echo permissionString($write);?>
                                </td>
                                <td>
                                    <?php echo permissionString($cycle);?>
                                </td>
                                <td>
                                    <?php echo permissionString($route);?>
                                </td>
                                <td>
                                    <button type="button" data-toggle="modal" data-target="#modalEditCreator" class="btn btn-default"><i class="fa fa-edit"></i>Group
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" id="modalEditCreator">
                                <div class="modal-dialog">
                                    <form method="POST" action="">
                                        <input type="hidden" name="groupId" value="<?php echo $groupId;?>"/>
                                        <input type="hidden" name="stepId" value="<?php echo $stepId;?>"/>
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Update Creator Permissions</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" name="write" value="true" <?php if($write == '2') { echo 'checked'; } ?>>
                                                        <label class="form-check-label" >Write (Update content) </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" name="cycle" value="true" <?php if($cycle == '2') { echo 'checked'; } ?>>
                                                        <label class="form-check-label" >Cycle (Archive/Restore) </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" name="route" value="true" <?php if($route == '2') { echo 'checked'; } ?>>
                                                        <label class="form-check-label" >Route (Move to step, approve, reject) </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" name="btnUpdateCreator" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->


</div>
<!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->

<div class="modal fade" role="dialog" id="modalAddGroup" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h4 class="modal-title">Add Group to Step</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group form-inline">
                        <input type="hidden" name="stepId" value="<?php echo $stepId;?>">
                        <label>Group</label>
                        <select class="form-control" name="groupId">
                            <?php

                            $rows = $crud->getWorkflowGroups($processId);
                            if(!empty($rows)){
                                foreach((array)$rows AS $key => $row){
                                    ?>
                                    <option value="<?php echo $row['id'];?>">
                                        <?php echo $row['groupName'];?> (<?php echo $row['groupDesc'];?>)
                                    </option>
                                    <?php
                                }
                            }else{
                                echo 'No group to add.';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="write" value="true">
                            <label class="form-check-label" >Write (Update content) </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="cycle" value="true">
                            <label class="form-check-label" >Cycle (Archive, restore) </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="route" value="true">
                            <label class="form-check-label" >Route (Move to step, approve, reject) </label>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="btnUpdateGroup" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('select').select2({
            placeholder: 'Select or search...'
        });
    })
</script>
