<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

//include_once('GLOBAL_CLASS_CRUD.php');
//$crud = new GLOBAL_CLASS_CRUD();
//require_once('mysql_connect_FA.php');
//session_start();
//include('GLOBAL_USER_TYPE_CHECKING.php');
//include('GLOBAL_CMS_ADMIN_CHECKING.php');

$page_title = 'System Admin - Edit Workflow';
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR.php';

//include 'SYS_MODAL_ChooseDocument.php';
//$userId = $_SESSION['idnum'];

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
                        <p id="txtprocessname">Step Name</p>
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
            <div class="col-lg-6">
                <!-- Routes -->
                <div class="card">
                    <div class="card-header">
                        Routes
                    </div>
                    <div class="class-body">
                        <div class="row" style="margin-top: 1rem;">
                            <div class="col-sm-1"></div>
                            <div class="col-lg-9">
                                <label>Route 1</label>
                                <input type="text" class="form-control input-md option-input" value=" Route 1 Name">
                            </div>
                            <div class="col-lg-1">
                                <!-- <button class="btn btn-default removeStep" style="margin-top: 2rem;"><i class="fa fa-fw fa-trash"></i></button>
                                <!-- <h4><i class="fa fa-fw fa-plus-circle addStep" style="cursor: pointer; "></i></h4> -->
                            </div>
                        </div>
                        <span id="routeLocation"></span>
                        <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-lg-1"><button class="btn btn-default addRoute">Add</button></div>
                        </div>
                    </div>
                </div>
                <!-- END of Routes Card -->
                <a href="SYS_EditProcess.php" class="btn btn-default" style="margin-top: 2.5rem;">Back</a>
                <button type="button" class="btn btn-default" id="" style="margin-top: 2.5rem;">Save</button>
                <!-- NOTICE: ID is still blank ^ -->
            </div>
            <div class="col-lg-6">
                <!-- Personnel -->
                <div class="card">
                    <div class="card-header">
                        Personnel
                    </div>
                    <div class="class-body">
                        <div class="row" style="margin-top: 1rem;">
                            <div class="col-sm-1"></div>
                            <div class="col-lg-9">
                                <label>Step 1</label>
                                <input type="text" class="form-control input-md option-input" value=" Step 1 Name">
                            </div>
                            <div class="col-lg-1">
                                <!-- <button class="btn btn-default removeStep" style="margin-top: 2rem;"><i class="fa fa-fw fa-trash"></i></button>
                                <!-- <h4><i class="fa fa-fw fa-plus-circle addStep" style="cursor: pointer; "></i></h4> -->
                            </div>
                        </div>
                        <span id="stepLocation"></span>
                        <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-lg-1"><button class="btn btn-default addStep">Add</button></div>
                        </div>
                    </div>
                </div>
                <!-- END of Personnel Card -->
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
        let routenum = 1; //for the additional steps
        let varField = "#fieldLocation" + routenum;

        // This part hides teh elements upon load
        $('#editPName').hide();


        $('.addRoute').on('click', function(){
            routenum++;
            let varField = "#fieldLocation" + routenum;
            //alert(varField);
            $('#routeLocation').append('<div class="row" style="margin-top: 1rem;"> <div class="col-sm-1"></div> <div class="col-lg-9"> <label>Step' + routenum + '</label> <input type="text" class="form-control input-md option-input" value=" Route '+ routenum + ' Name"> </div> <div class="col-lg-1"> <button class="btn btn-default removeRoute" style="margin-top: 2.5rem;"><i class="fa fa-fw fa-trash"></i></button> </div> </div>');
            $('.removeRoute').on('click', function(){
                $(this).closest('.row').remove();
                routenum--;
                let varField = "#fieldLocation" + routenum;
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


