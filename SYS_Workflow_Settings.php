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


if(isset($_GET['id'])){
    $processId = $_GET['id'];
    $rows = $crud->getData("SELECT processName, editableId, processForId FROM process WHERE id = '$processId' LIMIT 1");
    if(!empty($rows)){
        foreach((array)$rows AS $key => $row){
            $processName = $row['processName'];
            $editableId = $row['editableId'];
            $processForId = $row['processForId'];
        }
    }else{
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Workflows.php");
    }

}

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
    if($crud->execute("DELETE FROM steps WHERE id = '$stepId';")){
        echo 'success';
    }else{
        echo 'Cannot delete steps currently in use by items or routes.';
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Workflow_Settings.php?id=".$processId);
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
