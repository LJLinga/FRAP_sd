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

if(isset($_POST['btnUpdateRoute'])){
    $stepId = $_POST['stepId'];
    $routeId = $_POST['routeId'];
    $orderNo = $_POST['orderNo'];
    $routeName = $crud->esc($_POST['routeName']);
    $nextStepId = $_POST['nextStepId'];
    $assignStatus = $_POST['assignStatus'];
    if($crud->execute("UPDATE step_routes SET orderNo = '$orderNo', routeName = '$routeName', nextStepId = '$nextStepId', assignStatus='$assignStatus' WHERE id = '$routeId';")){
        echo 'success1';
    }else{
        echo 'Database error.';
    }
    header("Location: http://". $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/SYS_Step_Routes.php?id=".$stepId);
}
if(isset($_POST['btnAddRoute'])){
    $stepId = $_POST['stepId'];
    $orderNo = $_POST['orderNo'];
    $routeName = $crud->esc($_POST['routeName']);
    $nextStepId = $_POST['nextStepId'];
    $assignStatus = $_POST['assignStatus'];
    if($crud->execute("INSERT INTO step_routes (orderNo, routeName, currentStepId, nextStepId, assignStatus) VALUES ('$orderNo','$routeName','$stepId','$nextStepId','$assignStatus');")){
        echo 'success2';
    }else{
        echo 'Database error.';
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Step_Routes.php?id=".$stepId);
}
if(isset($_POST['btnDeleteRoute'])){
    $routeId = $_POST['routeId'];
    $stepId = $_POST['stepId'];
    if($crud->execute("DELETE FROM step_routes WHERE id = '$routeId';")){
        echo 'success3';
    }else{
        echo 'Database error.';
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Step_Routes.php?id=".$stepId);
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

function canApproveString($num){
    $string = 'Error';
    if($num == 1){
        $string ='NO';
    }else if($num == 2){
        $string = 'REJECT ONLY';
    }else if($num == 3){
        $string = 'APPROVE ONLY';
    }else if($num == 4){
        $string = 'APPROVE AND REJECT';
    }
    return $string;
}

function stepTypeString($num){
    $string = 'Error';
    if($num == 1){
        $string = 'START (First step)';
    }else if($num == 2){
        $string = 'NORMAL (In-between steps)';
    }else if($num == 3){
        $string = 'COMPLETE (Last step after approve/reject)';
    }else if($num == 4){
        $string = 'RESTART (Routes only to start)';
    }
    return $string;
}

function assignStatusString($num){
    $string = 'Error';
    if($num == 5){
        $string ='NO ASSIGNMENT';
    }else if($num == 1){
        $string = 'DRAFT';
    }else if($num == 2){
        $string = 'PENDING';
    }else if($num == 3){
        $string = 'APPROVED';
    }else if($num == 4){
        $string = 'REJECTED';
    }
    return $string;
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
                            <strong>Routing: </strong> Routes enable the item to move from one step to another, as well as to change its status at the same time.
                            It is recommended that you utilize the enforced COMPLETED stage to assign either reject or accept status.
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
                        <?php

                        $rows = $crud->getData("SELECT r.id, r.orderNo, r.routeName, r.nextStepId, r.assignStatus, s.id AS stepId, s.stepName, s.stepNo, s.isFinal, s.stepTypeId
                                                    FROM step_routes r JOIN steps s ON r.nextStepId = s.id
                                                    WHERE r.currentStepId = '$stepId';");
                        $rows2 = $crud->getData("SELECT s.* FROM steps s
                                                            WHERE s.processId = '$processId' AND s.id != '$stepId';");

                        if(!empty($rows)) { ?>
                            <table class="table table-responsive table-striped" align="center" id="dataTable">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Going to (Step [Type])</th>
                                    <th>Assign Status</th>
                                    <th width="250px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ((array)$rows as $key => $row) {
                                    ?>
                                    <tr>
                                        <th>
                                            <?php echo $row['orderNo'];?>
                                        </th>
                                        <td>
                                            <?php echo $row['routeName'];?>
                                        </td>
                                        <td>
                                            Step <?php echo $row['stepNo'].': '.$row['stepName'].' ['.stepTypeString($row['stepTypeId']).']'?>
                                        </td>
                                        <td>
                                            <?php echo assignStatusString($row['assignStatus']);?>
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
                                                            <label>No. </label>
                                                            <input type="number" class="form-control" name="orderNo" value="<?php echo $row['orderNo'];?>" min="0" max="97"required>
                                                            <label>Name </label>
                                                            <input type="text" class="form-control" name="routeName" value="<?php echo $row['routeName'];?>" required>
                                                        </div>
                                                        <div class="form-group form-inline">
                                                            <label>Going to </label>
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
                                                                            <?php echo 'Step '.$row2['stepNo'].': '.$row2['stepName'].' ['.$row2stepType.']'?>
                                                                        </option>
                                                                        <?php
                                                                    }
                                                                }else{
                                                                    echo 'No steps to route to.';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group form-inline">
                                                            <label>Assign Status</label>
                                                            <select class="form-control" name="assignStatus">
                                                                <option value="5" selected>NO ASSIGNMENT </option>
                                                                <option value="1" <?php if($row['assignStatus'] == 1){ echo 'selected'; };?>>DRAFT </option>
                                                                <option value="2" <?php if($row['assignStatus'] == 2){ echo 'selected'; };?>>PENDING </option>
                                                                <option value="3" <?php if($row['assignStatus'] == 3){ echo 'selected'; };?>>APPROVED </option>
                                                                <option value="4" <?php if($row['assignStatus'] == 4){ echo 'selected'; };?>>REJECTED </option>
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
                                <?php } ?>
                                </tbody>
                            </table>
                            <?php
                        }else{
                            ?>
                            <div class="alert alert-info">
                                No routes added so far.
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

<div class="modal fade" role="dialog" id="modalAddRoute" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Route</h4>
                </div>
                <div class="modal-body">
                    <?php
                    $btnAddRouteActive = '';
                    if(!empty($rows2)){ ?>
                        <div class="form-group form-inline">
                            <input type="hidden" name="stepId" value="<?php echo $stepId;?>">
                            <label>No. </label>
                            <input type="number" class="form-control" name="orderNo" min="0" max="97"required>
                            <label>Route Name </label>
                            <input type="text" class="form-control" name="routeName" placeholder="Route Name" required>
                        </div>
                        <div class="form-group form-inline">
                            <label>Going to  </label>
                            <select class="form-control" name="nextStepId" required>
                                <?php
                                foreach((array)$rows2 AS $key2 => $row2){
                                    $selected = '';
                                    $row2canApprove = canApproveString($row2['isFinal']);
                                    $row2stepType = stepTypeString($row2['stepTypeId']);

                                    ?>
                                    <option value="<?php echo $row2['id'];?>" <?php echo $selected;?>>
                                        <?php echo 'Step '.$row2['stepNo'].': '.$row2['stepName'].' ['.$row2stepType.']'?>
                                    </option>
                                    <?php
                                }?>
                            </select>
                        </div>
                        <div class="form-group form-inline">
                            <label>Assign Status</label>
                            <select class="form-control" name="assignStatus">
                                <option value="5" selected>NO ASSIGNMENT </option>
                                <option value="1">DRAFT </option>
                                <option value="2">PENDING </option>
                                <option value="3">APPROVED </option>
                                <option value="4">REJECTED </option>
                            </select>
                        </div>
                        <?php
                    }else{
                        $btnAddRouteActive = 'style="display: none;"';
                        ?>
                        <b>All possible steps have been routed to.</b>
                        <?php
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="btnAddRoute" class="btn btn-primary" <?php echo $btnAddRouteActive;?>>Save</button>
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
    });
</script>
