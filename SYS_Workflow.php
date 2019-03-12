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

$page_title = 'Santinig - Posts Dashboard';
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR_Admin.php';

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
            <div class="col-lg-12">
                <h3 class="page-header">
                    Workflows
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
                                <th>Workflow</th>
                                <th>Steps</th>
                                <th>Personnel</th>
                                <th>Route</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Applications</td>
                                <td>
                                    <span class="badge">2 Steps</span><br>
                                    [1] Review<br>
                                    [2] Approved<br>
                                </td>
                                <td>
                                    <span class="badge">1 Personnel</span><br>
                                    Melton Jo<br>
                                </td>
                                <td>
                                    <span class="badge">2 Routes</span><br>
                                    [1] Approve > [-][2]<br>
                                    [2] Reject > [-][-]<br>
                                </td>
                            </tr>
                            <tr>
                                <td>Applications</td>
                                <td>
                                    <span class="badge">2 Steps</span><br>
                                    [1] Review<br>
                                    [2] Approved<br>
                                </td>
                                <td>
                                    <span class="badge">1 Personnel</span><br>
                                    Melton Jo<br>
                                </td>
                                <td>
                                    <span class="badge">2 Routes</span><br>
                                    [1] Approve > [-][2]<br>
                                    [2] Reject > [-][-]<br>
                                </td>
                            </tr>
                            <tr>
                                <td>Applications</td>
                                <td>
                                    <span class="badge">2 Steps</span><br>
                                    [1] Review<br>
                                    [2] Approved<br>
                                </td>
                                <td>
                                    <span class="badge">1 Personnel</span><br>
                                    Melton Jo<br>
                                </td>
                                <td>
                                    <span class="badge">2 Routes</span><br>
                                    [1] Approve > [-][2]<br>
                                    [2] Reject > [-][-]<br>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">Add Workflow</div>
                    <div class="card-body">
                        <label>Process Name</label>
                        <input type="text" class="form-control input-md option-input" placeholder="Enter Process Name">
                        <br>
                        <label>Step 1 Name</label>
                        <input type="text" class="form-control input-md option-input" placeholder="Enter Step 1 Name">
                        <br>
                        <a href="SYS_EditWorkflow_Steps.php" class="btn btn-default">Submit</a>

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
    $(document).ready( function(){
        $('.addField').on('click', function(){
            $('#fieldLocation').append('<div class="row"><div class="col-lg-2"><h4><i class="fa fa-fw fa-minus-circle removeField" style="cursor: pointer; "></i></h4></div><div class="col-lg-10"><input type="text" onclick="ClickTextbox()" class="form-control input-md option-input" placeholder="Add an answer"></div> </div>');
            $('.addField').attr('disabled','disabled');
            $('.removeField').on('click', function(){
                $(this).closest('.row').remove();
            });
        });
        $('.option-input').on('change', function(){
            $('.addField').removeAttr('disabled');
        });
    });

    function ClickTextbox() {
        $('#myModal').modal('show');
    }

</script>


