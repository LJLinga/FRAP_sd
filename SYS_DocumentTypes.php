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
    $crud->execute("INSERT INTO doc_type(type, processId) VALUES ('$typeName','$processId');");
}

if(isset($_POST['btnUpdateDocType'])){
    $typeName = $crud->esc($_POST['docTypeName']);
    $processId = $_POST['assignedProcess'];
    $docTypeId = $_POST['docTypeId'];
    $crud->execute("UPDATE doc_type SET processId = '$processId', type='$typeName' WHERE id = '$docTypeId';");
}

if(isset($_POST['btnActivate'])){
    $docTypeId = $_POST['docTypeId'];
    $crud->execute("UPDATE doc_type SET isActive = 2 WHERE id = '$docTypeId';");
}

if(isset($_POST['btnDeactivate'])){
    $docTypeId = $_POST['docTypeId'];
    $crud->execute("UPDATE doc_type SET isActive = 1 WHERE id = '$docTypeId';");
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
                            $rows2 = $crud->getDocumentWorkflows();
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
                                            <form action="" method="POST">
                                                <input type="hidden" name="docTypeId" value="<?php echo $row['typeId'];?>">
                                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalEditDocType<?php echo $row['typeId'];?>"><i class="fa fa-edit"></i> Edit</button>
                                                <?php if($row['isActive'] == 2) { ?>
                                                    <button class="btn btn-danger" type="submit" name="btnDeactivate"><i class="fa fa-power-off"></i> Deactivate</button>
                                                <?php }else if($row['isActive'] == 1){ ?>
                                                    <button class="btn btn-success" type="submit" name="btnActivate"><i class="fa fa-power-off"></i> Activate</button>
                                                <?php } ;?>
                                            </form>
                                        </td>
                                    </tr>
                                    <div class="modal fade" role="dialog" id="modalEditDocType<?php echo $row['typeId'];?>" data-backdrop="static" data-keyboard="false">
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
                                                            <input type="hidden" name="docTypeId" value="<?php echo $row['typeId'];?>">
                                                            <input type="text" name="docTypeName" class="form-control" value="<?php echo $row['type'];?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Assigned Process</label>
                                                            <?php
                                                            if(!empty($rows2)){?>
                                                                <select name="assignedProcess" class="form-control">
                                                                    <?php
                                                                    foreach((array)$rows2 AS $key2 => $row2){
                                                                        $selected = '';
                                                                        if($row['processId'] == $row2['id']) { $selected = 'selected'; }
                                                                        ?>
                                                                        <option value="<?php echo $row2['id'];?>" <?php echo $selected?>><?php echo $row2['processName'];?></option>
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
                                                        <button type="submit" name="btnUpdateDocType" class="btn btn-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
                        $rows = $crud->getDocumentWorkflows();
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
    $(document).ready(function(){
        //$('#dataTable').DataTable({});
    });

    //$('select').select2({});
</script>