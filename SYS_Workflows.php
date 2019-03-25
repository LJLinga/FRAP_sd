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
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered" align="center" id="dataTable">
                            <thead>
                            <tr>
                                <th>Workflow</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php

                            $rows = $crud->getData("SELECT pr.id, pr.processName FROM facultyassocnew.process pr WHERE pr.id != 1;");
                            if(!empty($rows)){
                                foreach((array) $rows as $key => $row){
                                    echo '<tr>';
                                    echo '<td>';
                                    echo '<input type="hidden" class="process_id" value="'.$row['id'].'">';
                                    echo '<input type="text" class="form-control process_name" value="'.$row['processName'].'">';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<button type="button" onclick="saveProcess(this)" class="btn btn-primary">Save</button> ';
                                    echo '<a href="SYS_Process.php?id='.$row['id'].'" id="btnEdit" class="btn btn-default">Edit</a>';
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
        </div>
    </div>
    <!-- /.container-fluid -->


</div>
<!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->

<script>
    function saveProcess(element){
        var processId = $(element).closest('tr').find('.process_id').val();
        var processName = $(element).closest('tr').find('.process_name').val();
        $(element).closest('tr').children('td, th').css('background-color','#5CB85C');
        $.ajax({
            url:"SYS_AJAX_SaveProcess.php",
            method:"POST",
            data:{processId: processId, processName: processName},
            dataType:"JSON",
            success:function(data)
            {
            }
        });
    }

    function editProcess(element, processId){

    }

</script>


