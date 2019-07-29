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


$userId = $_SESSION['idnum'];


if(isset($_POST['btnUpdateStep'])){
    $processId = $_POST['processId'];
    $stepId = $_POST['stepId'];
    $stepNo = $crud->esc($_POST['stepNo']);
    $stepName = $crud->esc($_POST['stepName']);
    if($crud->execute("UPDATE steps SET stepName = '$stepName', stepNo = '$stepNo' WHERE id = '$stepId'")){
        echo 'success';
    }else{
        echo 'Database error.';
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Workflow_Settings.php?id=".$processId);
}

if(isset($_POST['btnAddStep'])){
    $processId = $crud->esc($_POST['processId']);
    $stepNo = $crud->esc($_POST['stepNo']);
    $stepName = $crud->esc($_POST['stepName']);
    if($crud->execute("INSERT INTO steps (processId, stepNo, stepName) VALUES ('$processId','$stepNo','$stepName');")){
        echo 'success';
    }else{
        echo 'Database error.';
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Workflow_Settings.php?id=".$processId);
}

if(isset($_POST['btnDeleteStep'])){
    $processId = $_POST['processId'];
    $stepId = $_POST['stepId'];
    if($crud->removeStep($stepId)){
        echo 'success';
    }else{
        echo 'Cannot delete steps currently in use by items or routes.';
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Workflow_Settings.php?id=".$processId);
}

if(isset($_POST['btnAddGroup'])){
    $processId = $_POST['processId'];
    $groupId = $_POST['groupId'];
    $write = 1;
    $route = 1;
    $cycle = 1;
    if(isset($_POST['write'])) { $write = 2; }
    if(isset($_POST['route'])) { $route = 2; }
    if (isset($_POST['cycle'])) {
        $cycle = 2;
    }
    try{
        $crud->execute("UPDATE process SET groupId='$groupId', `write`='$write', `route`='$route', `cycle`='$cycle' WHERE id='$processId'");
    }catch (Exception $e){
        $error = 'DATABASE_ERROR';
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Workflow_Settings.php?id=".$processId);
}

if (isset($_POST['btnUpdateGroup'])) {
    $processId = $_POST['processId'];
    $groupId = $_POST['groupId'];
    $write = 1;
    $route = 1;
    $cycle = 1;
    if (isset($_POST['write'])) {
        $write = 2;
    }
    if (isset($_POST['route'])) {
        $route = 2;
    }
    if (isset($_POST['cycle'])) {
        $cycle = 2;
    }
    if ($crud->execute("UPDATE `process_groups` SET `write` = '$write', `route` = '$route', `cycle`='$cycle' WHERE `groupId` = '$groupId' AND `processId` = '$processId';")) {
        echo 'success2';
    } else {
        echo 'Database error.';
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Workflow_Settings.php?id=" . $processId);
}

if(isset($_POST['btnDeleteGroup'])){
    $processId = $_POST['processId'];
    if($crud->removeProcessGroup($processId)){
        echo 'success3';
    }else{
        echo 'Database error.';
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Workflow_Settings.php?id=".$processId);
}

if(isset($_GET['id'])){
    $processId = $_GET['id'];
    $rows = $crud->getData("SELECT p.*, g.id AS groupId, g.groupDesc, g.groupName FROM process p LEFT JOIN groups g ON p.groupId = g.id  WHERE p.id = '$processId' LIMIT 1");
    if(!empty($rows)){
        foreach((array)$rows AS $key => $row){
            $processName = $row['processName'];
            $editableId = $row['editableId'];
            $processForId = $row['processForId'];
            $groupId = $row['groupId'];
            $groupName = $row['groupName'];
            $groupDesc = $row['groupDesc'];
            $route= $row['route'];
            $write = $row['write'];
            $cycle = $row['cycle'];
        }
    }else{
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Workflows.php");
    }

}

$page_title = 'Configuration - Process';
include 'GLOBAL_HEADER.php';
include 'SYS_SIDEBAR.php';

?>

<div class="content-wrapper" >
    <div class="container-fluid" id="printable">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    <div class="form-inline">
                        <?php
                        $domain = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
                        echo '<a class="btn btn-info" href="http://'.$domain.'/SYS_Workflows.php"><i class="fa fa-arrow-left"></i> Workflows</a>';
                        ?>
                        <span id="editName">
                            <input class="form-control" id="inputProcessName" value="<?php echo $processName;?>">
                            <button onclick="saveProcessName();" class="btn btn-primary">Save</button>
                            <button id="btnCancelEditName" class="btn btn-secondary">Cancel</button>
                        </span>
                        <span id="displayName">
                            <span id="spanProcessName"><?php echo $processName;?></span>
                            <button id="btnEditName" class="btn btn-default"><i class="fa fa-edit"></i> Edit </button>
                        </span>
                    </div>
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <small>
                            <strong>Steps</strong>: The system enforces that there be one and only one START and COMPLETE steps per process; These can't be removed.
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
                            <b>Process Steps</b>
                            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalAddStep">Add New Step</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-responsive table-striped" align="center" id="dataTable">
                            <thead>
                            <tr>
                                <th width="100px;">No.</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th width="500px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $rows = $crud->getData("SELECT id, stepName, stepNo, stepTypeId FROM steps s WHERE s.processId='$processId' ORDER BY stepNo;");
                            if(!empty($rows)) {
                                foreach ((array)$rows as $key => $row) {
                                    ?>
                                    <tr>
                                        <th class="displayStepNo">
                                            <?php echo $row['stepNo']; ?>
                                        </th>
                                        <td>
                                            <?php echo $row['stepName'];?>
                                        </td>
                                        <td>
                                            <?php echo $crud->stepTypeString($row['stepTypeId']); ?>
                                        </td>
                                        <td>
                                            <form action="" method="POST">
                                                <input type="hidden" name="processId" value="<?php echo $processId;?>"/>
                                                <input type="hidden" name="stepId" value="<?php echo $row['id'];?>"/>
                                                <button type="button" name="btnEditStep" id="btnEditStep" data-toggle="modal" data-target="#modalEditStep<?php echo $row['id'];?>"
                                                    class="btn btn-default"><i class="fa fa-edit"></i>Step
                                                </button>
                                                <a class="btn btn-default"  href="SYS_Step_Routes.php?id=<?php echo $row['id'];?>"><i class="fa fa-road"></i>
                                                    Routes
                                                </a>
                                                <a class="btn btn-default"  href="SYS_Step_Permissions.php?id=<?php echo $row['id'];?>"><i class="fa fa-group"></i>
                                                    Permissions
                                                </a>
                                                <button type="submit" class="btn btn-danger" name="btnDeleteStep"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
                                        </td>
                                        <div class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" id="modalEditStep<?php echo $row['id'];?>">
                                            <div class="modal-dialog">
                                                <form name="updateStep" id="updateStep" method="POST" >
                                                    <input type="hidden" name="requestType" value="updateStep"/>
                                                    <input type="hidden" name="processId" value="<?php echo $processId;?>"/>
                                                    <input type="hidden" name="stepId" value="<?php echo $row['id'];?>"/>
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Update Step: <?php echo $row['stepName'];?></h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group form-inline">
                                                                <label>No. </label>
                                                                <input type="number" class="form-control" name="stepNo" min="0" max="97" value="<?php echo $row['stepNo'];?>" required>
                                                                <label>Name </label>
                                                                <input type="text" class="form-control" name="stepName" value="<?php echo $row['stepName'];?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" name="btnUpdateStep" class="btn btn-primary">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </tr>

                                <?php }
                            }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-body">
                        <small>
                            <strong>Process Admin Group</strong>: A group that is assigned directly to the process will be able to interact with the content with the given process-wide permissions.
                            However, if there are step specific permissions assigned to the group in a particular step, these step-wide permissions will be prioritized. Also, in special workflows,
                            process admins are the ones capable of using the controls beyond the extent of the process configurability.
                        </small>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="form-inline">
                            <b>Process Admin Group</b>
                            <?php if($groupId == '') { ?>
                                <button class="btn btn-primary" type = "button" data-toggle="modal" data-target="#modalAddGroup" > Assign a group </button >
                            <?php } ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php if($groupId != '') {?>
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
                                            <?php echo $crud->permissionString($write);?>
                                        </td>
                                        <td>
                                            <?php echo $crud->permissionString($cycle); ?>
                                        </td>
                                        <td>
                                            <?php echo $crud->permissionString($route);?>
                                        </td>
                                        <td>
                                            <form action="" method="POST">
                                                <input type="hidden" name="groupId" value="<?php echo $groupId;?>"/>
                                                <input type="hidden" name="processId" value="<?php echo $processId;?>"/>
                                                <button type="button" data-toggle="modal" data-target="#modalEditGroup"
                                                        class="btn btn-default"><i class="fa fa-edit"></i>Group
                                                </button>
                                                <button type="submit" class="btn btn-danger" name="btnDeleteGroup"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <div class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" id="modalEditGroup">
                                        <div class="modal-dialog">
                                            <form method="POST" action="">
                                                <input type="hidden" name="groupId" value="<?php echo $groupId;?>"/>
                                                <input type="hidden" name="processId" value="<?php echo $processId;?>"/>
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Update Permissions for: <?php echo $groupName.' ('.$groupDesc.')';?></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" name="write" value="true" <?php if($write == '2') { echo 'checked'; } ?>>
                                                                <label class="form-check-label" >Write (Update content) </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                       name="cycle"
                                                                       value="true" <?php if ($cycle == '2') {
                                                                    echo 'checked';
                                                                } ?>>
                                                                <label class="form-check-label">Cycle
                                                                    (Archive/Restore) </label>
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
                            <?php
                        }else{
                            ?>
                            <div class="alert alert-info">
                                No group has been given permissions so far.
                            </div>
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

<div class="modal fade" role="dialog" id="modalAddStep" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form name="formAddStep" method="POST" action="">
            <div class="modal-header">
                <h4 class="modal-title">Add New Step</h4>
            </div>
            <div class="modal-body">
                <div class="form-group form-inline">
                    <label>Step No. </label>
                    <input type="hidden" class="form-control" name="processId" value="<?php echo $processId?>">
                    <input type="number" class="form-control" name="stepNo" min="0" max="97" required>
                    <label>Step Name </label>
                    <input type="text" class="form-control" name="stepName" placeholder="Step Name" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" name="btnAddStep" class="btn btn-primary">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" role="dialog" id="modalAddGroup" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h4 class="modal-title">Add Group to Process</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group form-inline">
                        <input type="hidden" name="processId" value="<?php echo $processId;?>">
                        <label>Group</label>
                        <select class="form-control" name="groupId">
                            <?php

                            $rows = $crud->getData("SELECT g.id, g.groupName, g.groupDesc FROM groups g;");
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
                            <label class="form-check-label" >Cycle (Archive, restore)</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="route" value="true">
                            <label class="form-check-label" >Route (Move to step, approve, reject) </label>
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

<script>

    let processId = "<?php echo $processId;?>";

    $(document).ready(function(){
        $('#editName').hide();
        $('#btnEditName').on('click', function() { editName(); });
        $('#btnCancelEditName').on('click',function() { displayName(); })
    });

    function editName(){
        $('#displayName').fadeOut("fast", function(){
            $('#editName').fadeIn("fast");
        });
    }
    function displayName(){
        $('#editName').fadeOut("fast", function(){
            $('#displayName').fadeIn("fast");
        });
    }

    function saveProcessName(){
        let processName = $('#inputProcessName').val();
        $.ajax({
            url:"SYS_AJAX_SaveProcess.php",
            method:"POST",
            data:{processId: processId, processName: processName},
            dataType:"JSON"
        });
        $('#spanProcessName').html(processName);
        displayName();
    }

</script>
