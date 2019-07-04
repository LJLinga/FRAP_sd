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

$page_title = 'Configuration - Process';
include 'GLOBAL_HEADER.php';
include 'SYS_SIDEBAR.php';

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
                                <th>Can approve/reject?</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <form method="POST" action="SYS_Process.php">
                            <?php

                            $rows = $crud->getData("SELECT id, stepName, stepNo, isFinal FROM steps s WHERE s.processId='$processId' ORDER BY stepNo;");
                            if(!empty($rows)) {
                                foreach ((array)$rows as $key => $row) {
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" class="step_id" name="step_id" id="step_id"
                                                   value="<?php echo $row['id']; ?>">
                                            <input type="number" class="form-control step_no" name="step_no"
                                                   id="step_no" value="<?php echo $row['stepNo']; ?>">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control step_name" name="step_name"
                                                   id="step_name" value="<?php echo $row['stepName']; ?>">
                                        </td>
                                        <td>
                                            <select class="form-control is_final" name="is_final" id="is_final">
                                                <option value="<?php echo $row['isFinal'] ?>" <?php if ($row['isFinal'] == 1) {
                                                    echo ' selected ';
                                                } ?>>No
                                                </option>
                                                <option value="<?php echo $row['isFinal'] ?>" <?php if ($row['isFinal'] == 2) {
                                                    echo ' selected ';
                                                } ?>>Reject Only
                                                </option>
                                                <option value="<?php echo $row['isFinal'] ?>" <?php if ($row['isFinal'] == 3) {
                                                    echo ' selected ';
                                                } ?>>Approve Only
                                                </option>
                                                <option value="<?php echo $row['isFinal'] ?>" <?php if ($row['isFinal'] == 4) {
                                                    echo ' selected ';
                                                } ?>>Approve/Reject
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button" name="btnSave" id="btnSave" onclick="saveStep(this)"
                                                    class="btn btn-primary">Save
                                            </button>
                                            <button type="button" class="btn btn-default" name="btnEditRoutes"
                                                    id="btnEditRoutes" data-toggle="modal" data-target="modalRoutes">
                                                Routes
                                            </button>
                                            <button type="button" class="btn btn-default" name="btnEditGroups"
                                                    id="btnEditGroups" data-toggle="modal" data-target="modalGroups">
                                                Groups
                                            </button>
                                        </td>
                                    </tr>
                                <?php }
                            }?>
                            </form>
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

<div class="modal fade" role="dialog" id="modalUpdateStep">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Update Step: <?php echo $row['stepName'];?></h3>
            </div>
            <div class="modal-body">
                <div class="form-group form-inline">
                    <label>Step No. </label>
                    <input type="hidden" class="form-control" name="processId" value="<?php echo $processId?>">
                    <input type="number" class="form-control" name="stepNo" min="0" max="97" required>
                </div>
                <div class="form-group form-inline">
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
        </div>
    </div>
</div>

<div class="modal fade" role="dialog" id="modalAddStep">
    <div class="modal-dialog">
        <div class="modal-content">
            <form name="formAddStep" id="formAddStep" method="POST">
                <input type="hidden" name="requestType" value="insertStep">
            <div class="modal-header">
                <h3 class="modal-title">Add New Step</h3>
            </div>
            <div class="modal-body">
                <div class="form-group form-inline">
                    <label>Step No. </label>
                    <input type="hidden" class="form-control" name="processId" value="<?php echo $processId?>">
                    <input type="number" class="form-control" name="stepNo" min="0" max="97" required>
                </div>
                <div class="form-group form-inline">
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" id="btnSubmitAddStep" class="btn btn-primary">Add Step</button>
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

    $('form.formAddStep').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'SYS_AJAX_SaveStep.php',
            data: new FormData(this),
            success: function(response) {
                alert('success: '+response);
            },
            error: function() {
                alert('error');
            }
        });
        //return false;
    });

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
