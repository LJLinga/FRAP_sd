<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/24/2019
 * Time: 5:28 AM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_SYS_ADMIN_CHECKING.php');

$page_title = 'Configuration - Workflow';
include 'GLOBAL_HEADER.php';
include 'SYS_SIDEBAR.php';

$userId = $_SESSION['idnum'];

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
                                <th>Summary</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php

                            $rows = $crud->getData("SELECT pr.id, pr.processName FROM facultyassocnew.process pr WHERE pr.id != 1;");
                            if(!empty($rows)){
                                foreach((array) $rows as $key => $row){
                                    $processId = $row['id'];
                                    echo '<tr>';
                                    echo '<td>';
                                    echo '<b>'.$row['processName'].'</b>';
                                    echo '</td>';
                                    echo '<td>';
                                    $rows2 = $crud->getData("SELECT s.id, s.stepNo, s.stepName FROM steps s WHERE s.processId = '$processId';");
                                    if(!empty($rows2)){
                                        foreach((array) $rows2 as $key2 => $row2){
                                            $stepId = $row2['id'];
                                            echo '<span class="badge">Step '.$row2['stepNo'].': '.$row2['stepName'].'</span><br>';
                                            $rows3 = $crud->getData("SELECT r.roleName FROM facultyassocnew.step_roles s JOIN edms_roles r ON s.roleId = r.id WHERE s.stepId = '$stepId'");
                                            if(!empty($rows3)){
                                                foreach((array) $rows3 as $key3 => $row3){
                                                    echo '     <span class="badge">'.$row3['roleName'].'</span><br>';
                                                }
                                            }else{
                                                echo "No personnel yet.";
                                            }
                                            echo '<br>';
                                        }
                                    }else{
                                        echo "No steps yet.";
                                    }
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<button name="btnEdit" id="btnEdit" class="btn btn-default" value="'.$processId.'">Edit</button>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
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
                        <button name="btnAdd" id="btnAdd" class="btn btn-primary">Submit</button>
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


