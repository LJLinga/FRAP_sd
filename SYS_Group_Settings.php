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
$sysRole = $_SESSION['SYS_ROLE'];
//$sysRole = 1;

if(isset($_POST['btnUpdateGroup'])){
    $groupId = $_POST['groupId'];
    $groupDesc = $_POST['groupName'];

    if($crud->setGroupDisplayName($groupId,$groupDesc)){
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

if(isset($_POST['btnInviteUser'])){
    $groupId = $_POST['groupId'];
    $memberId = $_POST['memberId'];
    $message = $_POST['message'];

    $isAdmin = '1';
    if(isset($_POST['isAdmin'])){ $isAdmin = '2'; };

    if($crud->execute("INSERT INTO group_invitations (groupId, invitedId, inviterId, message, isAdmin) VALUES ('$groupId','$memberId','$userId','$message','$isAdmin');")){
        echo 'success1';
    }else{
        echo 'Database error.';
    }
    header("Location: http://". $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/SYS_Group_Settings.php?id=".$groupId);
}

if(isset($_POST['btnCancelInvite'])){
    $groupId = $_POST['groupId'];
    $inviteId = $_POST['inviteId'];

    if($crud->execute("DELETE FROM group_invitations WHERE id = '$inviteId'")){
        echo 'success1';
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

if(isset($_GET['id'])){
    $groupId = $_GET['id'];
    $rows = $crud->getGroup($groupId);

    $boolGroupAdmin = $crud->isUserGroupAdmin($userId,$groupId);
    $boolForceAdd = false;
    $boolEdit = false;
    $boolDel = false;
    $boolDeac = false;

    if(!empty($rows)) {
        foreach ((array)$rows AS $key => $row) {
            $groupId = $row['id'];
            $groupName = $row['groupName'];
            $groupDesc = $row['groupDesc'];
            $isDeactivatable = $row['isDeactivatable'];
            $isEditable = $row['isEditable'];
            $isRemovable = $row['isRemovable'];
        }

        if($isEditable == '2' && $sysRole == '3'){
            $boolEdit = true;
        }else if($isEditable == '3' && ($sysRole == '2' || $sysRole == '3')){
            $boolEdit = true;
        }else if($isEditable == '4' && ($sysRole == '2' || $sysRole == '3' || $boolGroupAdmin)){
            $boolEdit = true;
        }

        if($isDeactivatable == '2' && $sysRole == '3'){
            $boolDeac = true;
        }else if($isDeactivatable == '3' && ($sysRole == '2' || $sysRole == '3')){
            $boolDeac = true;
        }else if($isDeactivatable == '4' && ($sysRole == '2' || $sysRole == '3' || $boolGroupAdmin)){
            $boolDeac = true;
        }

        if($isRemovable == '2' && $sysRole == '3'){
            $boolDel = true;
        }else if($isRemovable == '3' && ($sysRole == '2' || $sysRole == '3')){
            $boolDel = true;
        }else if($isRemovable == '4' && ($sysRole == '2' || $sysRole == '3' || $boolGroupAdmin)){
            $boolDel = true;
        }

        if($sysRole == '2' || $sysRole == '3'){
            $boolForceAdd = true;
        }


    }else{
        header("Location: http://" . $_SERVER['HTTP_HOST'] .dirname($_SERVER['PHP_SELF'])."/SYS_Groups.php");
    }
}



$page_title = $groupDesc.' Group';
include 'GLOBAL_HEADER.php';

if($sysRole == '2' || $sysRole == '3'){
    include 'SYS_SIDEBAR.php';
}else{
    include 'EDMS_SIDEBAR.php';
}

?>

<?php



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
                            <?php if($boolEdit){ ?>
                            <button id="btnEditName" class="btn btn-default"><i class="fa fa-edit"></i> Edit </button>
                            <?php } ?>
                        </span>
                    </div>
                </h3>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="form-inline">
                            <b>Members</b>
                            <?php if($boolEdit){ ?>
                            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalAddMember">Add Member</button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="panel-body" style="overflow-y: auto">
                        <?php
                        $rows = $crud->getGroupMembers($groupId);

                        if(!empty($rows)) {?>
                            <table class="table table-responsive table-striped" align="center" id="dataTable">
                                <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Role</th>
                                    <?php if($boolEdit){ ?>
                                    <th>Action</th>
                                    <?php } ?>
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
                                            <?php if($boolEdit){ ?>
                                            <form action="" method="POST">
                                                <input type="hidden" name="groupId" value="<?php echo $groupId;?>"/>
                                                <input type="hidden" name="memberId" value="<?php echo $row['EMP_ID'];?>"/>

                                                <button type="submit" class="btn btn-danger" name="btnRemoveMember" data-toggle="tooltip" title="Remove member"><i class="fa fa-trash"></i></button>

                                                <?php if($row['isAdmin'] == '1') { ?>
                                                    <button type="submit" class="btn btn-info" name="btnMakeAdmin" data-toggle="tooltip" title="Make an admin"><i class="fa fa-user-circle"></i></button>
                                                <?php } else if($row['isAdmin'] == '2') { ?>
                                                    <button type="submit" class="btn btn-warning" name="btnUnmakeAdmin" data-toggle="tooltip" title="Remove admin"><i class="fa fa-user-circle"></i></button>
                                                <?php } ?>

                                            </form>
                                            <?php } ?>
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
            </div>
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="form-inline">
                            <b>Pending invites</b>
                            <?php if($boolEdit){ ?>
                            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalInviteUser">Invite Member</button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="panel-body" style="overflow-y: auto">
                        <?php
                        $rows = $crud->getGroupInvites($groupId);

                        if(!empty($rows)) {?>
                            <table class="table table-responsive table-striped" align="center" id="dataTable">
                                <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Invited as</th>
                                    <?php if($boolEdit){ ?>
                                    <th>Action</th>
                                    <?php } ?>
                                </tr>
                                </thead>

                                <tbody >
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
                                            <?php if($boolEdit){ ?>
                                            <form action="" method="POST">
                                                <input type="hidden" name="groupId" value="<?php echo $groupId;?>"/>
                                                <input type="hidden" name="inviteId" value="<?php echo $row['id'];?>"/>
                                                <button type="submit" class="btn btn-warning" name="btnCancelInvite" data-toggle="tooltip" title="Cancel invite"><i class="fa fa-trash"></i></button>
                                            </form>
                                            <?php } ?>
                                        </td>
                                    </tr>

                                <?php } ?>
                                </tbody>

                            </table>
                            <?php
                        }else{
                            ?>
                            <div class="alert alert-info">
                                No pending invites.
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Group Workflow Involvement</b>
                    </div>

                    <div class="panel-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#process" aria-controls="home" role="tab" data-toggle="tab">Process</a></li>
                            <li role="presentation"><a href="#steps" aria-controls="profile" role="tab" data-toggle="tab">Steps</a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="process">
                                <?php
                                $rows = $crud->getGroupWorkflowsSpectate($groupId);

                                if(!empty($rows)) {?>
                                    <table class="table table-responsive table-striped" align="center" id="dataTable">
                                        <thead>
                                        <tr>
                                            <th>Workflow for</th>
                                            <th>Workflow Name</th>
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
                                                                        if(!empty($rows2)){
                                                                            foreach((array)$rows2 AS $key2=> $row2){ ?>
                                                                                <div class="panel panel-default">
                                                                                    <div class="panel-body">
                                                                                        <?php echo $row2['type']; ?>
                                                                                    </div>
                                                                                </div>
                                                                            <?php }
                                                                        }else{ ?>
                                                                            <div class="alert alert-info">
                                                                                There are no documents using this workflow so far.
                                                                            </div>
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
                                                    <?php echo $crud->permissionString($row['read']);?>
                                                </td>
                                                <td>
                                                    <?php echo $crud->permissionString($row['comment']);?>
                                                </td>
                                                <td>
                                                    <?php echo $crud->permissionString($row['write']);?>
                                                </td>
                                                <td>
                                                    <?php echo $crud->permissionString($row['route']);?>
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
                            <div role="tabpanel" class="tab-pane" id="steps">
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
                                                                        if(!empty($rows2)){
                                                                            foreach((array)$rows2 AS $key2=> $row2){ ?>
                                                                                <div class="panel panel-default">
                                                                                    <div class="panel-body">
                                                                                        <?php echo $row2['type']; ?>
                                                                                    </div>
                                                                                </div>
                                                                            <?php }
                                                                        }else{ ?>
                                                                            <div class="alert alert-info">
                                                                                There are no documents using this workflow so far.
                                                                            </div>
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


<div class="modal fade" role="dialog" id="modalInviteUser" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h4 class="modal-title">Invite User</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group form-inline">
                        <input type="hidden" name="groupId" value="<?php echo $groupId;?>">
                        <label>User</label>
                        <select class="form-control" name="memberId" style="width: 100%;">
                            <?php

                            $rows = $crud->getGroupNoninvitedUsers($groupId);
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
                    <div class="form-group">
                        <label>Message</label>
                        <textarea rows="5" class="form-control" name="message"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="btnInviteUser" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    let groupId = "<?php echo $groupId;?>";

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
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
