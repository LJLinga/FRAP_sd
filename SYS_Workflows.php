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

if(isset($_POST['btnAddProcess'])){
    $processName = $crud->esc($_POST['processName']);
    if($processId = $crud->executeGetKey("INSERT INTO process (processName) VALUES ('$processName');")){
        echo 'success';
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_Step_Routes.php?id=".$processId);
    }else{
        echo 'Database error.';
    }
}

$page_title = 'Configuration - Workflow';
include 'GLOBAL_HEADER.php';
include 'SYS_SIDEBAR.php';

$userId = $_SESSION['idnum'];

?>
<script>

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
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="position:relative;">
                        <div class="row">
                            <div class="col-lg-10">
                                <b>Documents</b>
                            </div>
                            <div class="col-lg-2">
                                <button id="addWorkflow" class="btn btn-primary" data-toggle="modal" data-target="#modalAddWorkflow">New Document Workflow</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-responsive" align="center" id="dataTable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th width="200px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php

                            $rows = $crud->getData("SELECT pr.id, pr.processName FROM facultyassocnew.process pr 
                                                        WHERE pr.processForId=1 AND pr.editableId=1 ORDER BY pr.id ASC;");
                            if(!empty($rows)){
                                foreach((array) $rows as $key => $row){

                                    ?>
                                    <tr>
                                        <th>
                                            <?php echo $row['processName']; ?>
                                        </th>
                                        <td>
                                            <a href="SYS_Process.php?id=<?php echo $row['id'];?>" id="btnEdit" class="btn btn-default">Edit</a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Manual Revisions</b>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-responsive" align="center" id="dataTable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th width="200px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php

                            $rows = $crud->getData("SELECT pr.id, pr.processName FROM facultyassocnew.process pr 
                                                        WHERE pr.processForId=2 AND pr.editableId=1 ORDER BY pr.id ASC LIMIT 1;");
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Posts</b>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-responsive" align="center" id="dataTable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th width="200px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php

                            $rows = $crud->getData("SELECT pr.id, pr.processName FROM facultyassocnew.process pr 
                                                        WHERE pr.processForId=3 AND pr.editableId=1 ORDER BY pr.id ASC LIMIT 1;");
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

<div class="modal fade" id="modalAddWorkflow" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
            <div class="modal-header">
                <h5 class="modal-title">
                    New Document Workflow
                </h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="title">
                        Name
                    </label>
                    <input type="text" name="processName" id="processName" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="btnAddProcess" class="btn btn-primary" data-dismiss="modal">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>


