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

if(isset($_POST['btnAddGroup'])){
    $groupDesc = $crud->esc($_POST['groupDesc']);
    $groupId = $crud->addGroup($groupDesc);
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
                    Groups
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="position:relative;">
                        <div class="row">
                            <div class="col-lg-10">
                                <b>User Groups</b>
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddGroup">Add Group</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-responsive" align="center" id="dataTable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Display Name</th>
                                <th>Active Status</th>
                                <th>Members</th>
                                <th>Editable by</th>
                                <th>Activatable by</th>
                                <th>Removable by</th>
                                <th width="100px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php

                            $rows = $crud->getNonAdminGroups();
                            if(!empty($rows)){
                                foreach((array) $rows as $key => $row){

                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['groupName']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['groupDesc']; ?>
                                        </td>
                                        <td>
                                            <?php echo $crud->activeString($row['isActive']); ?>
                                        </td>
                                        <td>
                                            <?php echo $row['member_count']; ?>
                                        </td>
                                        <td>
                                            <?php echo $crud->editableString($row['isEditable']); ?>
                                        </td>
                                        <td>
                                            <?php echo $crud->deactivatableString($row['isDeactivatable']); ?>
                                        </td>
                                        <td>
                                            <?php echo $crud->removableString($row['isRemovable']); ?>
                                        </td>
                                        <td>
                                            <a href="SYS_Group_Settings.php?id=<?php echo $row['id'];?>" id="btnEdit" class="btn btn-default">Edit</a>
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
                    <div class="panel-heading" style="position:relative;">
                        <div class="row">
                            <div class="col-lg-10">
                                <b>Admin Groups</b>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-responsive" align="center">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Display Name</th>
                                <th>Members</th>
                                <th width="200px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php

                            $rows = $crud->getAdminGroups();
                            if(!empty($rows)){
                                foreach((array) $rows as $key => $row){

                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['groupName']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['groupDesc']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['member_count']; ?>
                                        </td>
                                        <td>
                                            <a href="SYS_Group_Settings.php?id=<?php echo $row['id'];?>" id="btnEdit" class="btn btn-default">Edit</a>
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

<div class="modal fade" id="modalAddGroup" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Create Group
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">
                            Name
                        </label>
                        <input type="text" name="groupDesc" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="btnAddGroup" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#dataTable').DataTable({});
</script>

