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

$userId = $_SESSION['idnum'];
$sysRole = $_SESSION['SYS_ROLE'];

if(isset($_POST['btnAddGroup'])){
    $groupDesc = $crud->esc($_POST['groupDesc']);
    $groupId = $crud->addGroup($groupDesc);
    header("Location: http://" . $_SERVER['HTTP_HOST'] .dirname($_SERVER['PHP_SELF'])."/SYS_Groups.php");
}

if(isset($_POST['btnRemove'])){
    $groupId = $_POST['groupId'];
    $crud->deleteGroup($groupId);
    header("Location: http://" . $_SERVER['HTTP_HOST'] .dirname($_SERVER['PHP_SELF'])."/SYS_Groups.php");
}

if(isset($_POST['btnDeactivate'])){
    $groupId = $_POST['groupId'];
    $crud->deactivateGroup($groupId);
    header("Location: http://" . $_SERVER['HTTP_HOST'] .dirname($_SERVER['PHP_SELF'])."/SYS_Groups.php");
}

if(isset($_POST['btnActivate'])){
    $groupId = $_POST['groupId'];
    $crud->activateGroup($groupId);
    header("Location: http://" . $_SERVER['HTTP_HOST'] .dirname($_SERVER['PHP_SELF'])."/SYS_Groups.php");
}

$page_title = 'Groups';
include 'GLOBAL_HEADER.php';
include 'SYS_SIDEBAR.php';

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
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php

                            if($sysRole == 2){
                                $rows = $crud->getNonAdminGroups();
                            }else if($sysRole == 3){
                                $rows = $crud->getNonAdminGroups();
                            }else{
                                $rows = $crud->getUserGroupsWithCount($userId);
                            }

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
                                            <?php

                                            $boolGroupAdmin = $crud->isUserGroupAdmin($userId,$row['id']);
                                            $boolEdit = false;
                                            $boolDeac = false;
                                            $boolDel = false;

                                            if($row['isEditable'] == '2' && $sysRole == '3'){
                                                $boolEdit = true;
                                            }else if($row['isEditable'] == '3' && ($sysRole == '2' || $sysRole == '3')){
                                                $boolEdit = true;
                                            }else if($row['isEditable'] == '4' && ($sysRole == '2' || $sysRole == '3' || $boolGroupAdmin)){
                                                $boolEdit = true;
                                            }

                                            if($row['isDeactivatable'] == '2' && $sysRole == '3'){
                                                $boolDeac = true;
                                            }else if($row['isDeactivatable'] == '3' && ($sysRole == '2' || $sysRole == '3')){
                                                $boolDeac = true;
                                            }else if($row['isDeactivatable'] == '4' && ($sysRole == '2' || $sysRole == '3' || $boolGroupAdmin)){
                                                $boolDeac = true;
                                            }

                                            if($row['isRemovable'] == '2' && $sysRole == '3'){
                                                $boolDel = true;
                                            }else if($row['isRemovable'] == '3' && ($sysRole == '2' || $sysRole == '3')){
                                                $boolDel = true;
                                            }else if($row['isRemovable'] == '4' && ($sysRole == '2' || $sysRole == '3' || $boolGroupAdmin)){
                                                $boolDel = true;
                                            }

                                            ?>

                                            <form action="" method="POST">
                                                <input type="hidden" name="groupId" value="<?php echo $row['id'];?>">
                                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalPermissions"><i class="fa fa-eye"></i></button>

                                                <a href="SYS_Group_Settings.php?id=<?php echo $row['id'];?>" id="btnEdit" class="btn btn-default"><i class="fa fa-edit"></i></a>

                                            <?php if($boolDeac){
                                                if($row['isActive'] == '2'){?>
                                                    <button class="btn btn-warning" type="submit" name="btnDeactivate"><i class="fa fa-power-off"></i></button>
                                                <?php }else{?>
                                                    <button class="btn btn-success" type="submit" name="btnActivate"><i class="fa fa-power-off"></i></button>
                                                <?php } ?>
                                            <?php }?>
                                            <?php if($boolDel){ ?>
                                                <button class="btn btn-danger" type="submit" name="btnRemove"><i class="fa fa-trash"></i></button>
                                            <?php }?>
                                            </form>

                                            <div class="modal fade" id="modalPermissions" role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">
                                                                Group Setting Permissions
                                                            </h5>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-striped">
                                                                <tbody>
                                                                <tr>
                                                                    <th>
                                                                        Editable by
                                                                    </th>
                                                                    <td>
                                                                        <?php echo $crud->editableString($row['isEditable']); ?>
                                                                    </td>

                                                                </tr>
                                                                <tr>
                                                                    <th>
                                                                        Deactivatable by
                                                                    </th>
                                                                    <td>
                                                                        <?php echo $crud->deactivatableString($row['isDeactivatable']); ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>
                                                                        Removable by
                                                                    </th>
                                                                    <td>
                                                                        <?php echo $crud->removableString($row['isRemovable']); ?>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" name="btnAddGroup" class="btn btn-primary">Save</button>
                                                        </div>
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

