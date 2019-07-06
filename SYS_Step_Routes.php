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


if(isset($_GET['id'])){
    $processId = $_GET['id'];
    $rows = $crud->getData("SELECT processName, editableId, processForId FROM process WHERE id = '$processId' LIMIT 1");
    foreach((array)$rows AS $key => $row){
        $processName = $row['processName'];
        $editableId = $row['editableId'];
        $processForId = $row['processForId'];
    }
}

if(isset($_POST['btnUpdateRoute'])){
    $processId = $_POST['processId'];
    $stepId = $_POST['stepId'];
    $stepNo = $_POST['stepNo'];
    $stepName = $_POST['stepName'];
    $canApprove = $_POST['isFinal'];
    if($crud->execute("UPDATE steps SET stepName = '$stepName', stepNo = '$stepNo', isFinal = '$canApprove' WHERE id = '$stepId'")){
        echo 'success';
    }else{
        alert('Database error.');
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Process_Steps.php?id=".$processId);
}

if(isset($_POST['btnAddRoute'])){
    $stepId = $_POST['stepId'];
    if($crud->execute("INSERT INTO steps (stepNo, stepName, isFinal) VALUES ('$stepNo','$stepName','$canApprove');")){
        echo 'success';
    }else{
        alert('Database error.');
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Process_Steps.php?id=".$processId);
}

if(isset($_POST['btnDeleteStep'])){
    $processId = $_POST['processId'];
    $stepId = $_POST['stepId'];
    if($crud->execute("DELETE FROM steps WHERE id = '$stepId';")){
        echo "<script type='text/javascript'> alert('Deleted step successfully.'); </script>";
    }else{
        echo "<script type='text/javascript'> alert('Some items or routes are in this is step as of the moment. Thus, preventing it from getting deleted.'); </script>";
    }
    //header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Process_Steps.php?id=".$processId);
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
                                <th>Can approve/reject?</th>
                                <th width="500px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $rows = $crud->getData("SELECT id, stepName, stepNo, isFinal, stepTypeId FROM steps s WHERE s.processId='$processId' ORDER BY stepNo;");
                            if(!empty($rows)) {
                                foreach ((array)$rows as $key => $row) {
                                    ?>
                                    <tr>
                                        <th>
                                            <?php echo $row['stepNo']; ?>
                                        </th>
                                        <td>
                                            <?php echo $row['stepName'];?>
                                        </td>
                                        <td>
                                            <?php
                                            if($row['stepTypeId'] == 1){
                                                echo 'START (First step)';
                                            }else if($row['stepTypeId'] == 2){
                                                echo 'NORMAL (In-between steps)';
                                            }else if($row['stepTypeId'] == 3){
                                                echo 'COMPLETE (Last step after approve/reject)';
                                            }else if($row['stepTypeId'] == 4){
                                                echo 'RESTART (Routes only to start)';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if($row['isFinal'] == 1){
                                                echo 'NO';
                                            }else if($row['isFinal'] == 2){
                                                echo 'REJECT ONLY';
                                            }else if($row['isFinal'] == 3){
                                                echo 'APPROVE ONLY';
                                            }else if($row['isFinal'] == 4){
                                                echo 'APPROVE AND REJECT';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <form action="" method="POST">
                                                <input type="hidden" name="processId" value="<?php echo $processId;?>"/>
                                                <input type="hidden" name="stepId" value="<?php echo $row['id'];?>"/>
                                                <button type="button" name="btnEditStep" id="btnEditStep" data-toggle="modal" data-target="#modalEditStep<?php echo $row['id'];?>"
                                                        class="btn btn-default"><i class="fa fa-edit"></i>Step
                                                </button>
                                                <button type="button" class="btn btn-default" name="btnEditRoutes"
                                                        id="btnEditRoutes" data-toggle="modal" data-target="#modalEditRoutes<?php echo $row['id'];?>"><i class="fa fa-road"></i>
                                                    Routes
                                                </button>
                                                <button type="button" class="btn btn-default" name="btnEditGroups"
                                                        id="btnEditGroups" data-toggle="modal" data-target="#modalEditGroups<?php echo $row['id'];?>"><i class="fa fa-group"></i>
                                                    Groups
                                                </button>
                                                <button type="submit" class="btn btn-danger" name="btnDeleteStep"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <div class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" id="modalEditStep<?php echo $row['id'];?>">
                                        <div class="modal-dialog">
                                            <form name="updateStep" id="updateStep" method="POST" action="">
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
                                                        <div class="form-group form-inline">
                                                            <label>Can approve/reject? </label>
                                                            <select class="form-control" name="isFinal">
                                                                <option value="1" <?php if($row['isFinal'] == 1) echo 'selected';?>>NO</option>
                                                                <option value="2" <?php if($row['isFinal'] == 2) echo 'selected';?>>REJECT ONLY</option>
                                                                <option value="3" <?php if($row['isFinal'] == 3) echo 'selected';?>>APPROVE ONLY</option>
                                                                <option value="4" <?php if($row['isFinal'] == 4) echo 'selected';?>>APPROVE AND REJECT</option>
                                                            </select>
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
                                    <div class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" id="modalEditRoutes<?php echo $row['id'];?>">
                                        <div class="modal-dialog">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4><?php echo $row['stepName'];?> Routes</h4>
                                                </div>
                                                <div class="panel-body" style="max-height: 25rem; overflow-y: auto;">
                                                    <?php
                                                    $tempStepId = $row['id'];
                                                    $query = "SELECT r.routeName, r.currentStepId, s.stepName, r.nextStepId, s2.stepName FROM steps s
                                                                JOIN step_routes r ON s.id = r.currentStepId
                                                                JOIN steps s2 ON r.nextStepId = s2.id
                                                                ;";

                                                    $rows2 = $crud->getData($query);

                                                    if(!empty($rows2)){
                                                        foreach((array)$rows2 AS $key2 => $row2){
                                                            ?>
                                                            <div class="card card-body" style="margin-top: 1rem;">

                                                                <?php echo $row2['routeName'];?>
                                                            </div>
                                                            <?php
                                                        }
                                                    }else{
                                                        ?>
                                                        No routes to show.
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="panel-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="btnUpdateRoutes" class="btn btn-primary">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
            <form name="formAddStep" id="formAddStep" method="POST" action="">
                <input type="hidden" name="requestType" value="insertStep">
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
                    <div class="form-group form-inline">
                        <label>Can approve/reject? </label>
                        <select class="form-control" name="isFinal">
                            <option value="1" selected>No</option>
                            <option value="2">Reject Only</option>
                            <option value="3">Approve Only</option>
                            <option value="4">Approve and Reject</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="btnAddStep" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    let processId = "<?php echo $processId;?>";

    $('#editName').hide();

    $('#btnEditName').on('click', function() { editName(); });
    $('#btnCancelEditName').on('click',function() { displayName(); });

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

    function saveStep(element){
        let stepId = $(element).closest('tr').find('.step_id').val();
        let stepNo = $(element).closest('tr').find('.step_no').val();
        let stepName = $(element).closest('tr').find('.step_name').val();
        let isFinal = $(element).closest('tr').find('.is_final').val();
        $(element).closest('tr').children('td, th').css('background-color','#5CB85C');
        $.ajax({
            url:"SYS_AJAX_SaveStep.php",
            method:"POST",
            data:{ requestType: 'updateStep', stepId: stepId, stepNo: stepNo, stepName:stepName, canApprove: isFinal},
            dataType:"JSON",
            success:function(data){

            }
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
