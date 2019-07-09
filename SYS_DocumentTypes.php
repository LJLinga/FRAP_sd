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

if(isset($_POST['btnAddDocType'])){
    $typeName = $crud->esc($_POST['docTypeName']);
    $processId = $_POST['assignedProcess'];
    if($crud->execute("INSERT INTO doc_type(type, processId) VALUES ('$typeName','$processId');")){
        echo 'success';
    }else{
        echo 'Database error.';
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_DocumentTypes.php");
}

if(isset($_POST['btnUpdateDocType'])){
    $typeName = $crud->esc($_POST['docTypeName']);
    $processId = $_POST['assignedProcess'];
    $docTypeId = $_POST['docTypeId'];
    if($processId = $crud->executeGetKey("UPDATE doc_type SET processId = '$processId', type='$typeName' WHERE id = '$docTypeId';")){
        echo 'success';
    }else{
        echo 'Database error.';
    }
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/SYS_DocumentTypes.php");
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
                    Document Types
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="position:relative;">
                        <div class="row">
                            <div class="col-lg-10">
                                <b class="panel-title">Document Types</b>
                            </div>
                            <div class="col-lg-2">
                                <button id="addDocType" class="btn btn-primary" data-toggle="modal" data-target="#modalAddDocType">New Document Type</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-responsive" align="center" id="dataTable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Assigned Process</th>
                                <th>Active Status</th>
                                <th width="400px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $rows = $crud->getDocTypes();
                            if(!empty($rows)){
                                foreach((array) $rows as $key => $row){
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['type'];?>
                                        </td>
                                        <td>
                                            <?php echo $row['processName'];?>
                                        </td>
                                        <td>
                                            <?php echo $crud->activeString($row['isActive']);?>
                                        </td>
                                        <td>
                                            <button class="btn btn-default" data-toggle="modal" data-target="#modalEditDocType"><i class="fa fa-edit"></i> Edit</button>
                                            <div class="modal fade" role="dialog" id="modalEditDocType" data-backdrop="static" data-keyboard="false">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="POST" action="">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">
                                                                    New Document Type
                                                                </h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label>Name</label>
                                                                    <input type="hidden" name="docTypeId" value="<?php echo $row['id']?>">
                                                                    <input type="text" name="docTypeName" class="form-control" value="<?php echo $row['type']?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Assigned Process</label>
                                                                    <?php
                                                                    $rows = $crud->getWorkflows();
                                                                    if(!empty($rows)){?>
                                                                        <select name="assignedProcess" class="form-control">
                                                                            <?php
                                                                            foreach((array)$rows AS $key => $row){
                                                                                ?>
                                                                                <option value="<?php echo $row['id'];?>"><?php echo $row['processName'];?></option>
                                                                                <?php
                                                                            }?>
                                                                        </select>
                                                                        <?php
                                                                    }else{?>
                                                                        <select name="assignedProcess" class="form-control" disabled>
                                                                            <option> No workflows to assign.</option>
                                                                        </select>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="btnAddDocType" class="btn btn-primary">Save</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->

<div class="modal fade" id="modalAddDocType" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h4 class="modal-title">
                        New Document Type
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="docTypeName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Assigned Process</label>
                        <?php
                        $rows = $crud->getWorkflows();
                        if(!empty($rows)){?>
                            <select name="assignedProcess" class="form-control" style="width: 100%">
                                <?php
                                foreach((array)$rows AS $key => $row){
                                    ?>
                                    <option value="<?php echo $row['id'];?>"><?php echo $row['processName'];?></option>
                                    <?php
                                }?>
                            </select>
                            <?php
                        }else{?>
                            <select name="assignedProcess" class="form-control" disabled>
                                <option> No workflows to assign.</option>
                            </select>
                            <?php

                        }
                        ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="btnAddDocType" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#dataTable').DataTable({});
    //$('select').select2({});
</script>