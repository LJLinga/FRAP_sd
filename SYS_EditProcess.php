<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_CMS_ADMIN_CHECKING.php');

$page_title = 'System Admin - Edit Process';
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR.php';

//include 'SYS_MODAL_ChooseDocument.php';

//$userId = $_SESSION['idnum'];

if(isset($_POST['loadSteps'])){
  $stepId = $_POST['loadSteps'];
  $rows = $crud->getData("SELECT * FROM steps WHERE id='$stepId'");

}
if(isset($_POST['loadRoutes'])){
    $stepId = $_POST['loadSteps'];
    $rows = $crud->getData("SELECT * FROM steps WHERE id='$stepId'");

}
if(isset($_POST['loadRoles'])){
    $stepId = $_POST['loadSteps'];
    $rows = $crud->getData("SELECT * FROM steps WHERE id='$stepId'");

}

if(isset($_POST['btnEdit'])){

    $processId = $_POST['btnEdit'];
    $rows = $crud->getData("");

    // Load steps

    //AJAX saving and loading.

}else{
    echo 'hehehe';
}


?>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>

<div class="content-wrapper" >
    <div class="container-fluid" id="printable">

        <div class="row">
            <div class="col-lg-7">
                <h3 class="page-header">
                    <label id="displayPName">
                        <p id="txtprocessname">Process Name</p>
                        <button class="btn btn-default" id="editprocessname">Edit</button>
                    </label>
                    <label id="editPName">
                        <input type="text" id="tbxprocessname">
                        <button class="btn btn-default" id="saveprocessname">Save</button>
                    </label>
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-7">
                <!-- Steps Card -->
                <div class="card">
                    <div class="card-header">

                    </div>
                    <div class="class-body">
                        <div class="row" style="margin-top: 1rem;">
                            <div class="col-lg-9" style="margin-left: 2rem;">
                                <label>Step 1</label>
                                <input type="text" class="form-control input-md option-input" value=" Step 1 Name" onclick="ClickTextbox()">
                            </div>
                            <div class="col-lg-1">
                                <a href="SYS_EditSteps.php" class="btn btn-default" style="margin-top: 2.5rem;"><i class="fa fa-fw fa-gear"></i></a>
                                <!-- <button class="btn btn-default removeStep" style="margin-top: 2rem;"><i class="fa fa-fw fa-trash"></i></button>
                                <!-- <h4><i class="fa fa-fw fa-plus-circle addStep" style="cursor: pointer; "></i></h4> -->
                            </div>
                        </div>
                        <span id="stepLocation"></span>
                        <div class="row">
                            <div class="col-lg-1" style="margin-left: 2rem;"><button class="btn btn-default addStep">Add</button></div>
                        </div>
                    </div>
                </div>
                <!-- END of Steps Card -->
                <button type="button" class="btn btn-default" href="SYS_EditWorkflow_RooutePersonel.php">Save</button>
            </div>
            <div class="col-lg-5">
                <!-- Leave this empty -->
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->


</div>
<!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->

<script>
    $(document).ready( function(){
        // This part is for the Step values
        let stepnum = 1; //for the additional steps
        let varField = "#fieldLocation" + stepnum;

        // This part hides teh elements upon load
        $('#editPName').hide();


        $('.addStep').on('click', function(){
            stepnum++;
            let varField = "#fieldLocation" + stepnum;
            //alert(varField);
            $('#stepLocation').append('<div class="row" style="margin-top: 1rem;"> <div class="col-lg-9" style="margin-left: 2rem;"> <label>Step' + stepnum + '</label> <input type="text" class="form-control input-md option-input" value=" Step '+ stepnum + ' Name"> </div> <div class="col-lg-2"> <a href="SYS_EditSteps.php" class="btn btn-default" style="margin-top: 2.5rem;"><i class="fa fa-fw fa-gear"></i></a> <button class="btn btn-default removeStep" style="margin-top: 2rem;"><i class="fa fa-fw fa-trash"></i></button> </div> </div>');
            $('.addField').attr('disabled','disabled');
            $('.removeStep').on('click', function(){
                $(this).closest('.row').remove();
                stepnum--;
                let varField = "#fieldLocation" + stepnum;
            });
        });

        $('#editprocessname').on('click', function(){
            txt = $('#txtprocessname').text();
            $('#displayPName').hide();
            $('#tbxprocessname').val(txt);
            $('#editPName').show();
        });

        $('#saveprocessname').on('click', function(){
            txt = $('#tbxprocessname').val();
            $('#editPName').hide();
            $('#txtprocessname').text(txt);
            $('#displayPName').show();
            //alert(txt);
            //document.getElementById("p2").style.color = "blue";
        });
    });

    function ClickTextbox() {
        $('#myModal').modal('show');
    }

</script>


