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

$page_title = 'Santinig - Posts Dashboard';
include 'GLOBAL_HEADER.php';
include 'SYS_SIDEBAR.php';

$userId = $_SESSION['idnum'];

if(isset($_POST['btnAdd'])){
    $stepNo = $_POST['step_no'];
    $stepName = $_POST['step_name'];
    $isFinal = $_POST['is_final'];
    $processId = $_POST['process_id'];
    $crud->execute("INSERT INTO steps (stepNo, stepName, isFinal, processId) VALUES ('$stepNo','$stepName','$isFinal','$processId');");
}

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
                    <?php echo $processName;?>
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered" align="center" id="dataTable">
                            <thead>
                            <tr>
                                <th width="50px">No.</th>
                                <th width="200px">Name</th>
                                <th width="50px">An End</th>
                                <th width="100px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <form method="POST" action="SYS_Step.php">
                            <?php

                            $rows = $crud->getData("SELECT id, stepName, stepNo, isFinal FROM steps s WHERE s.processId='$processId' ORDER BY stepNo;");
                            if(!empty($rows)){
                                foreach((array) $rows as $key => $row){
                                    echo '<tr>';
                                    echo '<td>';
                                    echo '<input type="hidden" class="step_id" name="step_id" id="step_id" value="'.$row['id'].'">';
                                    echo '<input type="number" class="form-control step_no" name="step_no" id="step_no" value="'.$row['stepNo'].'">';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<input type="text" class="form-control step_name" name="step_name" id="step_name" value="'.$row['stepName'].'">';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<select class="form-control is_final" name="is_final" id="is_final">';
                                    if($row['isFinal'] == '2') { echo '<option value="2" selected>Yes</option>'; echo '<option value="1">No</option>';
                                    }else{ echo '<option value="2">Yes</option>';  echo '<option value="1" selected>No</option>'; }
                                    echo '</select>';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<button type="button" name="btnSave" id="btnSave" onclick="saveSteps(this)" class="btn btn-primary">Save</button> ';
                                    echo '<a href="SYS_RoutesAndPeople.php?id='.$row['id'].'" class="btn btn-default">Routes and People</button>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                            </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        Add Step
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
                            <div class="form-group">
                                <label>Step No</label>
                                <input type="hidden" class="form-control" name="process_id" value="<?php echo $processId?>">
                                <input type="number" class="form-control" name="step_no" placeholder="Step No.">
                            </div>
                            <div class="form-group">
                                <label>Step Name</label>
                                <input type="text" class="form-control" name="step_name" placeholder="Step Name">
                            </div>
                            <div class="form-group">
                                <label>An End</label>
                                <select class="form-control" name="is_final">
                                    <option value="1">No</option>
                                    <option value="2">Yes</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="btnAdd" class="btn btn-primary">Add Step</button>
                            </div>
                        </form>
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

<script>

    function saveSteps(element){
        var stepId = $(element).closest('tr').find('.step_id').val();
        var stepNo = $(element).closest('tr').find('.step_no').val();
        var stepName = $(element).closest('tr').find('.step_name').val();
        var isFinal = $(element).closest('tr').find('.is_final').val();
        $(element).closest('tr').children('td, th').css('background-color','#5CB85C');
        $.ajax({
            url:"SYS_AJAX_SaveStep.php",
            method:"POST",
            data:{stepId: stepId, stepNo: stepNo, stepName:stepName, isFinal: isFinal},
            dataType:"JSON",
            success:function(data)
            {
            }
        });
    }

</script>


