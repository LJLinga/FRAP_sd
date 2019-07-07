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
    $read = 1; $write = 1; $route = 1; $comment = 1;
    if(isset($_POST['read'])) { $read = 2; }
    if(isset($_POST['write'])) { $write = 2; }
    if(isset($_POST['route'])) { $route = 2; }
    if(isset($_POST['comment'])) { $comment = 2; }

    if($crud->execute("UPDATE step_groups SET read = '$read', write='$write', route='$route', comment='$comment' WHERE stepId = '$stepId' AND groupId = '$groupId';")){
        echo 'success1';
    }else{
        echo 'Database error.';
    }
    header("Location: http://". $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/SYS_Step_Permissions.php?id=".$stepId);
}
if(isset($_POST['btnAddGroup'])){
    $stepId = $_POST['stepId'];
    $groupId = $_POST['groupId'];
    $read = 1; $write = 1; $route = 1; $comment = 1;
    if(isset($_POST['read'])) { $read = 2; }
    if(isset($_POST['write'])) { $write = 2; }
    if(isset($_POST['route'])) { $route = 2; }
    if(isset($_POST['comment'])) { $comment = 2; }
    if($crud->execute("INSERT INTO `step_groups` (`groupId`, `stepId`, `read`, `write`, `route`, `comment`) VALUES ('$groupId','$stepId','$read','$write','$route','$comment');")){
        echo 'success2';
    }else{
        echo 'Database error.';
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Step_Permissions.php?id=".$stepId);
}
if(isset($_POST['btnDeleteGroup'])){
    $stepId = $_POST['stepId'];
    $groupId = $_POST['groupId'];
    if($crud->execute("DELETE FROM step_groups WHERE groupId = '$groupId';")){
        echo 'success3';
    }else{
        echo 'Database error.';
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Step_Permissions.php?id=".$stepId);
}

if(isset($_GET['id'])){
    $stepId = $_GET['id'];
    $rows = $crud->getData("SELECT s.id, s.processId, s.stepName, s.stepNo, s.isFinal, s.stepTypeId, p.processName FROM steps s
                                    JOIN process p ON s.processId = p.id WHERE s.id = ' $stepId'");

    if(!empty($rows)) {
        foreach ((array)$rows AS $key => $row) {
            $processId = $row['processId'];
            $processName = $row['processName'];
            $stepName = $row['stepName'];
            $stepNo = $row['stepNo'];
            $canApprove = $row['isFinal'];
            $stepTypeId = $row['stepTypeId'];
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
                        <a class="btn btn-info" href="SYS_Process.php?id=<?php echo $processId;?>"><i class="fa fa-arrow-left"></i> <?php echo $processName;?></a>
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
                            Note: The system enforces that there be one and only one START and COMPLETE steps per process; These can't be removed.
                            The START step is always the first step, and the COMPLETE step is always the step the process goes to after approve/reject.
                            All the custom steps to be added will be NORMAL steps that can have multiple groups to other steps.
                            There are no preconfigured groups and group assignments.
                            To get started, we preconfigured the START step to allow approve/reject to connect to the COMPLETE step.
                            The step numbers and names are arbitrary and can be customized to your own liking.
                        </small>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="form-inline">
                            <b>Group Permissions</b>
                            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalAddGroup">Add Group to Step</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php
                        $rows = $crud->getData("SELECT g.id, g.groupName, g.groupDesc, sg.read, sg.write, sg.comment, sg.route 
                                                            FROM groups g JOIN step_groups sg on g.id = sg.groupId
                                                            WHERE sg.stepId = '$stepId'");

                        if(!empty($rows)) {?>
                            <table class="table table-responsive table-striped" align="center" id="dataTable">
                                <thead>
                                <tr>
                                    <th>Group Name</th>
                                    <th>Display Name</th>
                                    <th>Read</th>
                                    <th>Comment</th>
                                    <th>Write</th>
                                    <th>Route</th>
                                    <th width="250px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ((array)$rows as $key => $row) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['groupName'];?>
                                        </td>
                                        <td>
                                            <?php echo $row['groupDesc'];?>
                                        </td>
                                        <td>
                                            <?php echo permissionString($row['read']);?>
                                        </td>
                                        <td>
                                            <?php echo permissionString($row['comment']);?>
                                        </td>
                                        <td>
                                            <?php echo permissionString($row['write']);?>
                                        </td>
                                        <td>
                                            <?php echo permissionString($row['route']);?>
                                        </td>
                                        <td>
                                            <form action="" method="POST">
                                                <input type="hidden" name="groupId" value="<?php echo $row['id'];?>"/>
                                                <input type="hidden" name="stepId" value="<?php echo $stepId;?>"/>
                                                <button type="button" data-toggle="modal" data-target="#modalEditGroup<?php echo $row['id'];?>"
                                                        class="btn btn-default"><i class="fa fa-edit"></i>Group
                                                </button>
                                                <button type="submit" class="btn btn-danger" name="btnDeleteGroup"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <div class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" id="modalEditGroup<?php echo $row['id'];?>">
                                        <div class="modal-dialog">
                                            <form method="POST" action="">
                                                <input type="hidden" name="groupId" value="<?php echo $row['id'];?>"/>
                                                <input type="hidden" name="stepId" value="<?php echo $stepId;?>"/>
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Update Permissions for: <?php echo $row['groupName'].' ('.$row['groupDesc'].')';?></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label>
                                                            Permissions
                                                        </label>
                                                        <div class="form-group form-inline">
                                                            Read <input type="checkbox" name="read" value="true" <?php if($row['read'] == '2') { echo 'checked'; } ?>>
                                                            Comment <input type="checkbox" name="comment" value="true" <?php if($row['comment'] == '2') { echo 'checked'; } ?>>
                                                            Write <input type="checkbox" name="write" value="true" <?php if($row['write'] == '2') { echo 'checked'; } ?>>
                                                            Route <input type="checkbox" name="route" value="true" <?php if($row['route'] == '2') { echo 'checked'; } ?>>
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
                                <?php } ?>
                                </tbody>
                            </table>
                            <?php
                        }else{
                            ?>
                            <b>No groups given permissions so far.</b>
                            <?php
                        }
                        ?>
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

                            $rows = $crud->getData("SELECT g.id, g.groupName, g.groupDesc FROM groups g
                                                            WHERE g.id NOT IN (SELECT sg.groupId FROM step_groups sg WHERE sg.stepId = '$stepId');");
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
                    <label>
                        Permissions
                    </label>
                    <div class="form-group">
                        <div class="form-check">
                            <label class="form-check-label" >Read</label>
                            <input type="checkbox" class="form-check-input" name="read" value="true">
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" >Comment</label>
                            <input type="checkbox" class="form-check-input" name="comment" value="true">
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" >Write (Update content) </label>
                            <input type="checkbox" class="form-check-input" name="write" value="true">
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" >Route (Move to step, approve, reject) </label>
                            <input type="checkbox" class="form-check-input" name="route" value="true">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="btnAddGroup" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
