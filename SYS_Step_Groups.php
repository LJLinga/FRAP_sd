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

if(isset($_POST['btnUpdateGroup'])){
    $stepId = $_POST['stepId'];
    $groupId = $_POST['groupId'];
    $read = $_POST['read'];
    $write = $_POST['write'];
    $route = $_POST['route'];
    $comment = $_POST['comment'];

    if($crud->execute("UPDATE step_groups SET read = '$read', write='$write', route='$route', comment='$comment' WHERE stepId = '$stepId' AND groupId = '$groupId';")){
        echo 'success1';
    }else{
        echo 'Database error.';
    }
    header("Location: http://". $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/SYS_Step_Groups.php?id=".$stepId);
}
if(isset($_POST['btnAddGroup'])){
    $stepId = $_POST['stepId'];
    $groupId = $_POST['groupId'];
    $read = $_POST['read'];
    $write = $_POST['write'];
    $route = $_POST['route'];
    $comment = $_POST['comment'];
    if($crud->execute("INSERT INTO step_groups (groupId, stepId, read, write, route, comment) VALUES ('$read','$write','$route','$comment');")){
        echo 'success2';
    }else{
        echo 'Database error.';
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Step_Groups.php?id=".$stepId);
}
if(isset($_POST['btnDeleteGroup'])){
    $stepId = $_POST['stepId'];
    $groupId = $_POST['groupId'];
    $read = $_POST['read'];
    $write = $_POST['write'];
    $route = $_POST['route'];
    $comment = $_POST['comment'];
    if($crud->execute("DELETE FROM groups WHERE stepId = '$stepId',")){
        echo 'success3';
    }else{
        echo 'Database error.';
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Step_Groups.php?id=".$stepId);
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

$page_title = 'Configuration - Process - Step Routes';
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
                            All the custom steps to be added will be NORMAL steps that can have multiple routes to other steps.
                            There are no preconfigured routes and group assignments.
                            To get started, we preconfigured the START step to allow approve/reject to connect to the COMPLETE step.
                            The step numbers and names are arbitrary and can be customized to your own liking.
                        </small>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="form-inline">
                            <b>Step Routes</b>
                            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalAddRoute">Add New Route</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-responsive table-striped" align="center" id="dataTable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Going to (Step No.: Step Name [Type, Can Approve?])</th>
                                <th width="500px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $rows = $crud->getData("SELECT r.id, r.routeName, r.nextStepId, s.id AS stepId, s.stepName, s.stepNo, s.isFinal, s.stepTypeId
                                                    FROM step_routes r JOIN steps s ON r.nextStepId = s.id
                                                    WHERE r.currentStepId = '$stepId';");

                            $rows2 = $crud->getData("SELECT * FROM steps WHERE processId = '$processId' AND id != '$stepId';");

                            if(!empty($rows)) {
                                foreach ((array)$rows as $key => $row) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['routeName'];?>
                                        </td>
                                        <td>
                                            Step <?php echo $row['stepNo'].': '.$row['stepName'].' ['.stepTypeString($row['stepTypeId']).', '.canApproveString($row['isFinal']).']'?>
                                        </td>
                                        <td>
                                            <form action="" method="POST">
                                                <input type="hidden" name="routeId" value="<?php echo $row['id'];?>"/>
                                                <input type="hidden" name="stepId" value="<?php echo $stepId;?>"/>
                                                <button type="button" data-toggle="modal" data-target="#modalEditRoute<?php echo $row['id'];?>"
                                                        class="btn btn-default"><i class="fa fa-edit"></i>Route
                                                </button>
                                                <button type="submit" class="btn btn-danger" name="btnDeleteRoute"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <div class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" id="modalEditRoute<?php echo $row['id'];?>">
                                        <div class="modal-dialog">
                                            <form method="POST" action="">
                                                <input type="hidden" name="routeId" value="<?php echo $row['id'];?>"/>
                                                <input type="hidden" name="stepId" value="<?php echo $stepId;?>"/>
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Update Route: <?php echo $row['routeName'];?></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group form-inline">
                                                            <label>Name </label>
                                                            <input type="text" class="form-control" name="routeName" value="<?php echo $row['routeName'];?>" required>
                                                        </div>
                                                        <div class="form-group form-inline">
                                                            <label>Going to (Step No.: Step Name [Type, Can Approve?])</label>
                                                            <select class="form-control" name="nextStepId">
                                                                <?php
                                                                if(!empty($rows2)){
                                                                    foreach((array)$rows2 AS $key2 => $row2){
                                                                        $selected = '';
                                                                        $row2canApprove = canApproveString($row2['isFinal']);
                                                                        $row2stepType = stepTypeString($row2['stepTypeId']);

                                                                        if($row2['id'] == $row['nextStepId']){
                                                                            $selected = 'selected';
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $row2['id'];?>" <?php echo $selected;?>>
                                                                            <?php echo 'Step '.$row2['stepNo'].': '.$row2['stepName'].' ['.$row2stepType.', '.$row2canApprove.']'?>
                                                                        </option>
                                                                        <?php
                                                                    }
                                                                }else{
                                                                    echo 'No steps to route to.';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" name="btnUpdateRoute" class="btn btn-primary">Save</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php }
                            }
                            ?>
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

<div class="modal fade" role="dialog" id="modalAddRoute" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Route</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group form-inline">
                        <input type="hidden" name="stepId" value="<?php echo $stepId;?>">
                        <label>Route Name </label>
                        <input type="text" class="form-control" name="routeName" placeholder="Route Name" required>
                    </div>
                    <div class="form-group form-inline">
                        <label>Going to  </label>
                        <select class="form-control" name="nextStepId">
                            <?php

                            $rows2 = $crud->getData("SELECT * FROM steps WHERE processId = '$processId' AND id != '$stepId';");
                            if(!empty($rows2)){
                                foreach((array)$rows2 AS $key2 => $row2){
                                    $selected = '';
                                    $row2canApprove = canApproveString($row2['isFinal']);
                                    $row2stepType = stepTypeString($row2['stepTypeId']);

                                    if($row2['id'] == $row['nextStepId']){
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?php echo $row2['id'];?>" <?php echo $selected;?>>
                                        <?php echo 'Step '.$row2['stepNo'].': '.$row2['stepName'].' ['.$row2stepType.', '.$row2canApprove.']'?>
                                    </option>
                                    <?php
                                }
                            }else{
                                echo 'No steps to route to.';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="btnAddRoute" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
