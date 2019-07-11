<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/25/2019
 * Time: 4:05 AM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');

$userId = $_SESSION['idnum'];



if(isset($_POST['btnUpdateGroup'])){
    $groupId = $_POST['groupId'];
    $groupDesc = $_POST['groupName'];

    if($crud->setGroupDisplayName($groupDesc)){
        echo 'success1';
    }else{
        echo 'Database error.';
    }
    header("Location: http://". $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/SYS_Group_Settings.php?id=".$groupId);
}

if(isset($_POST['btnAddMember'])){
    $groupId = $_POST['groupId'];
    $memberId = $_POST['memberId'];

    if($crud->addUserToGroup($groupId, $memberId)){
        echo 'success1';
        if(isset($_POST['isAdmin'])) {
            if($crud->setGroupAdmin($groupId, $memberId)){
                echo 'success2';
            }else{
                echo 'Database error.';
            }
        }
    }else{
        echo 'Database error.';
    }
    header("Location: http://". $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/SYS_Group_Settings.php?id=".$groupId);
}

if(isset($_POST['btnRemoveMember'])){
    $groupId = $_POST['groupId'];
    $memberId = $_POST['memberId'];
    echo $groupId.$memberId;
    if($crud->removeUserFromGroup($groupId, $memberId)){
        echo 'success4';
    }
    header("Location: http://". $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/SYS_Group_Settings.php?id=".$groupId);
}

if(isset($_POST['btnMakeAdmin'])){
    $groupId = $_POST['groupId'];
    $memberId = $_POST['memberId'];

    if($crud->setGroupAdmin($groupId, $memberId)){
        echo 'success2';
    }else{
        echo 'Database error.';
    }
    header("Location: http://". $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/SYS_Group_Settings.php?id=".$groupId);
}
if(isset($_POST['btnUnmakeAdmin'])){
    $groupId = $_POST['groupId'];
    $memberId = $_POST['memberId'];

    if($crud->removeGroupAdmin($groupId, $memberId)){
        echo 'success3';
    }else{
        echo 'Database error.';
    }
    header("Location: http://". $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/SYS_Group_Settings.php?id=".$groupId);
}

if(isset($_GET['id'])){
    $groupId = $_GET['id'];
    $rows = $crud->getGroup($groupId);

    if(!empty($rows)) {
        foreach ((array)$rows AS $key => $row) {
            $groupId = $row['id'];
            $groupName = $row['groupName'];
            $groupDesc = $row['groupDesc'];
        }
    }else{
        header("Location: http://" . $_SERVER['HTTP_HOST'] .dirname($_SERVER['PHP_SELF'])."/SYS_Groups.php");
    }
}



$page_title = 'Configuration - Group - Settings';
include 'GLOBAL_HEADER.php';
include 'SYS_SIDEBAR.php';

?>

<div class="content-wrapper" >
    <div class="container-fluid" id="printable">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    <div class="form-inline">
                        <a class="btn btn-info" href="SYS_Groups.php"><i class="fa fa-arrow-left"></i> Groups</a>
                        <span id="editName">
                            <input class="form-control" id="inputProcessName" value="<?php echo $groupDesc;?>">
                            <button onclick="saveProcessName();" class="btn btn-primary">Save</button>
                            <button id="btnCancelEditName" class="btn btn-secondary">Cancel</button>
                        </span>
                        <span id="displayName">
                            <span id="spanProcessName"><?php echo $groupDesc;?></span> (<?php echo $groupName;?>)
                            <button id="btnEditName" class="btn btn-default"><i class="fa fa-edit"></i> Edit </button>
                        </span>
                    </div>
                </h3>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="form-inline">
                            <b>Members</b>
                            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalAddMember">Add Member</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php
                        $rows = $crud->getGroupMembers($groupId);

                        if(!empty($rows)) {?>
                            <table class="table table-responsive table-striped" align="center" id="dataTable">
                                <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Role</th>
                                    <th width="400px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ((array)$rows as $key => $row) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['name'];?>
                                        </td>
                                        <td>
                                            <?php echo $crud->groupRoleString($row['isAdmin']);?>
                                        </td>
                                        <td>
                                            <form action="" method="POST">
                                                <input type="hidden" name="groupId" value="<?php echo $groupId;?>"/>
                                                <input type="hidden" name="memberId" value="<?php echo $row['EMP_ID'];?>"/>
                                                <button type="submit" class="btn btn-danger" name="btnRemoveMember"><i class="fa fa-trash"></i> Remove</button>

                                                <?php if($row['isAdmin'] == '1') { ?>
                                                <button type="submit" class="btn btn-info" name="btnMakeAdmin"><i class="fa fa-user-circle"></i> Make Admin</button>
                                                <?php } else if($row['isAdmin'] == '2') { ?>
                                                    <button type="submit" class="btn btn-warning" name="btnUnmakeAdmin"><i class="fa fa-user-circle"></i> Unmake Admin</button>
                                                <?php } ?>
                                            </form>
                                        </td>
                                    </tr>

                                <?php } ?>
                                </tbody>
                            </table>
                            <?php
                        }else{
                            ?>
                            <div class="alert alert-info">
                                No group members so far.
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Group Workflow Involvement</b>
                    </div>
                    <div class="panel-body">
                        <?php
                        $rows = $crud->getGroupWorkflows($groupId);

                        if(!empty($rows)) {?>
                            <table class="table table-responsive table-striped" align="center" id="dataTable">
                                <thead>
                                <tr>
                                    <th>Workflow for</th>
                                    <th>Workflow Name</th>
                                    <th>Step</th>
                                    <th>Read</th>
                                    <th>Comment</th>
                                    <th>Write</th>
                                    <th>Route</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ((array)$rows as $key => $row) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php
                                            echo $crud->processForString($row['processForId']);
                                            if($row['processForId'] == 1){?>
                                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalListDocTypes<?php echo $row['id'];?>"><i class="fa fa-eye"></i></button>
                                                <div class="modal fade" role="dialog" id="modalListDocTypes<?php echo $row['id'];?>" data-backdrop="static" data-keyboard="false">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <b class="modal-title">Document Types that undergo <?php echo $row['processName'];?></b>
                                                            </div>
                                                            <div class="modal-body">
                                                                <?php
                                                                $rows2 = $crud->getWorkflowDocTypes($row['processId']);
                                                                if(!empty($rows2)){ ?>
                                                                    <div clas
                                                                    <?php
                                                                    foreach((array)$rows2 AS $key2=> $row2){
                                                                        echo $row2['type'];
                                                                    }
                                                                }else{ ?>
                                                                    There are no documents using this workflow so far.
                                                                <?php }?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $row['processName'];?>
                                        </td>
                                        <td>
                                            Step <?php echo $row['stepNo'].': '.$row['stepName'];?>
                                        </td>

                                        <td>
                                            <?php echo $crud->permissionString($row['read']);?>
                                        </td>
                                        <td>
                                            <?php echo $crud->permissionString($row['comment']);?>
                                        </td>
                                        <td>
                                            <?php echo $crud->permissionString($row['write']);?>
                                        </td>
                                        <td>
                                            <?php echo $crud->permissionString($row['route']);
                                            if($row['route'] == 2){?>
                                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalListRoutes<?php echo $row['id'];?>"><i class="fa fa-road"></i></button>
                                                <div class="modal fade" role="dialog" id="modalListRoutes<?php echo $row['id'];?>" data-backdrop="static" data-keyboard="false">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Step <?php echo $row['stepNo'].': '.$row['stepName'];?> Routes</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <?php
                                                                $rows2 = $crud->getStepRoutes($row['stepId']);
                                                                if(!empty($rows2)){?>
                                                                    <table class="table table-striped">
                                                                        <thead>
                                                                        <th>No.</th>
                                                                        <th>Name</th>
                                                                        <th>Going to </th>
                                                                        <th>Assigned Status</th>
                                                                        </thead>
                                                                        <tbody>

                                                                        <?php
                                                                        foreach((array)$rows2 AS $key2=> $row2){ ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <?php echo $row2['orderNo'];?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php echo $row2['routeName'];?>
                                                                                </td>
                                                                                <td>
                                                                                    Step <?php echo $row2['stepNo'].': '.$row2['stepName'];?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php echo $crud->assignStatusString($row2['assignStatus']);?>
                                                                                </td>
                                                                            </tr>
                                                                            <?php
                                                                        }?>

                                                                        </tbody>
                                                                    </table>
                                                                    <?php
                                                                }else{ ?>
                                                                    There are no documents using this workflow so far.
                                                                <?php }?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>

                                <?php } ?>
                                </tbody>
                            </table>
                            <?php
                        }else{
                            ?>
                            <div class="alert alert-info">
                                No workflow involvements so far.
                            </div>
                            <?php
                        }
                        ?>
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

<div class="modal fade" role="dialog" id="modalAddMember" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h4 class="modal-title">Add Member</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group form-inline">
                        <input type="hidden" name="groupId" value="<?php echo $groupId;?>">
                        <label>User</label>
                        <select class="form-control" name="memberId" style="width: 100%;">
                            <?php

                            $rows = $crud->getUsersNotInGroup($groupId);
                            if(!empty($rows)){
                                foreach((array)$rows AS $key => $row){
                                    ?>
                                    <option value="<?php echo $row['EMP_ID'];?>">
                                        <?php echo $row['name'];?>
                                    </option>
                                    <?php
                                }
                            }else{
                                echo 'No users to add.';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="isAdmin" value="true">
                            <label class="form-check-label" >Make Group Admin</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="btnAddMember" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    let groupId = "<?php echo $groupId;?>";

    $(document).ready(function(){
        $('#editName').hide();
        $('#btnEditName').on('click', function() { editName(); });
        $('#btnCancelEditName').on('click',function() { displayName(); });
        $('select').select2({
            placeholder: 'Select or search...'
        });
    });

    function editName(){
        $('#displayName').fadeOut("fast", function(){
            $('#editName').fadeIn("fast");
        });
    }
    function displayName(){
        $('#editName').fadeOut("fast", function(){
            $('#displayName').fadeIn("fast");
        });
    }

    function saveProcessName(){
        let groupDesc = $('#inputProcessName').val();
        $.ajax({
            url:"SYS_AJAX_SaveGroup.php",
            method:"POST",
            data:{groupId: groupId, groupDesc: groupDesc},
            dataType:"JSON"
        });
        $('#spanProcessName').html(groupDesc);
        displayName();
    }
</script>
