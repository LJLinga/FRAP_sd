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

if(isset($_POST['btnAccept'])){
    $invitationId = $_POST['invitationId'];
    $groupId = $_POST['groupId'];
    $asAdmin = $_POST['asAdmin'];
    $key = $crud->execute("INSERT INTO user_groups (groupId, userId, isAdmin) VALUES ('$groupId','$userId','$asAdmin')");
    if($key){
        $crud->execute("DELETE FROM group_invitations WHERE id='$invitationId';");
    }
}

if(isset($_POST['btnDecline'])){
    $invitationId = $_POST['invitationId'];
    $crud->execute("DELETE FROM group_invitations WHERE id='$invitationId';");
}


$page_title = 'Groups';
include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';
?>

<div class="content-wrapper" >
    <div class="container-fluid" id="printable">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    My Groups
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Groups</strong>
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
                            $rows = $crud->getUserGroupsWithCount($userId);

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
                                            <a href="GRP_Group_Settings.php?id=<?php echo $row['id'];?>" id="btnEdit" class="btn btn-default"><i class="fa fa-edit"></i></a>
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
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Group Invitations</strong>
                    </div>
                    <div class="panel-body">


                            <?php
                            $rows = $crud->getData("SELECT i.*,g.groupDesc FROM group_invitations i JOIN groups g ON g.id = i.groupId WHERE invitedId = '$userId'");

                            if(!empty($rows)){ ?>
                        <table class="table table-striped table-responsive" align="center" id="dataTable">
                            <thead>
                            <tr>
                                <th>From</th>
                                <th>Group Name</th>
                                <th>Invited as</th>
                                <th>Invited on</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php foreach((array) $rows as $key => $row){


                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $crud->getUserName($row['inviterId']); ?>
                                        </td>
                                        <td>
                                            <?php echo $row['groupDesc']; ?>
                                        </td>
                                        <td>
                                            <?php if($row['isAdmin'] == '2') echo 'ADMIN'; else echo 'MEMBER'; ?>
                                        </td>
                                        <td>
                                            <?php echo $crud->friendlyDate($row['timestamp']); ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-info fa fa-mail-reply" data-toggle="modal" data-target="#inviteModal"></button>
                                            <div id="inviteModal" class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <strong><?php echo $crud->getUserName($row['inviterId']); ?></strong> is inviting you as a
                                                                <strong><?php if($row['isAdmin'] == '2') echo 'ADMIN'; else echo 'MEMBER'; ?></strong> of
                                                                <strong><?php echo $row['groupDesc']; ?></strong>
                                                                <div class="alert alert-info">
                                                                    <i>"<?php echo $row['message'];?>"</i>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form method="POST" action="">
                                                                    <input name="invitationId" type="hidden" value="<?php echo $row['id'];?>">
                                                                    <input name="groupId" type="hidden" value="<?php echo $row['groupId'];?>">
                                                                    <input name="asAdmin" type="hidden" value="<?php echo $row['isAdmin'];?>">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button name="btnDecline" type="submit" class="btn btn-danger" data-toggle="tooltip" title="Decline invitation"> Decline </button>
                                                                    <button name="btnAccept" type="submit" class="btn btn-success" data-toggle="tooltip" title="Accept invitation"> Accept </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <?php } ?>
                            </tbody>
                        </table>
                            <?php }else { ?>
                                <div class="alert alert-info">
                                    You have no pending group invitations.
                                </div>
                        <?php } ?>

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
    $('#dataTable').DataTable({});
</script>

