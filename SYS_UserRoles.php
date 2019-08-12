<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 2/17/2019
 * Time: 11:09 PM
 */


include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_SYS_ADMIN_CHECKING.php');

//hardcoded value for userType, will add MYSQL verification
$userId = $_SESSION['idnum'];

$page_title = 'Configuration - User Roles';
include 'GLOBAL_HEADER.php';
include 'SYS_SIDEBAR.php';

?>
<div id="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 1rem;">
                <h3>User Roles</h3></br>
                <div class="card">
                    <div class="card-body">
                        <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                            <table class="table table-bordered" align="center" id="dataTable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th width="200px">CMS</th>
                                    <th width="200px">Loans</th>
                                    <th width="100px">Save</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $rows = $crud->getData("SELECT id, roleName FROM cms_roles;");
                                    $cmsArray = [];
                                    if(!empty($rows)){
                                        foreach((array) $rows as $key => $row){
                                            $cmsArray[] = $row;
                                        }
                                    }

                                    $rows = $crud->getData("SELECT id, roleName FROM frap_roles;");
                                    $frapArray = [];
                                    if(!empty($rows)){
                                        foreach((array) $rows as $key => $row){
                                            $frapArray[] = $row;
                                        }
                                    }

                                    $rows = $crud->getData("SELECT e.EMP_ID, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) as name, e.EDMS_ROLE, e.CMS_ROLE, e.FRAP_ROLE 
                                                                    FROM employee e;");
                                    if(!empty($rows)){
                                        foreach((array) $rows as $key => $row){
                                            ?>
                                            <tr>
                                                <td>
                                                    <b><?php echo $row['name']?></b>
                                                    <input type="hidden" class="user_id" value="<?php echo $row['EMP_ID']?>">
                                                </td>
                                                <td>
                                                    <select class="form-control select_cms">
                                                        <?php foreach((array) $cmsArray as $key2 => $row2){
                                                            if($row['CMS_ROLE'] == $row2['id']) { ?>
                                                                <option value="<?php echo $row2['id'];?>" selected><?php echo $row2['roleName'];?></option>
                                                            <?php }else{ ?>
                                                                <option value="<?php echo $row2['id'];?>"><?php echo $row2['roleName'];?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control select_frap">
                                                        <?php foreach((array) $frapArray as $key2 => $row2){
                                                            if($row['FRAP_ROLE'] == $row2['id']) { ?>
                                                                <option value="<?php echo $row2['id'];?>" selected><?php echo $row2['roleName'];?></option>
                                                            <?php }else{ ?>
                                                                <option value="<?php echo $row2['id'];?>"><?php echo $row2['roleName'];?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button class="btn btn-default" type="button" id="btnSave" onclick="saveRoles(this)">Save</button>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#dataTable').DataTable();
        // $('select').select2({
        //     placeholder: 'Select or search...'
        // });
    });

    function saveRoles(element){
        var userId = $(element).closest('tr').find('.user_id').val();
        var cms = $(element).closest('tr').find('.select_cms').val();
        var frap = $(element).closest('tr').find('.select_frap').val();
        $(element).closest('tr').children('td, th').css('background-color','#5CB85C');
        $.ajax({
            url:"SYS_AJAX_SaveRoles.php",
            method:"POST",
            data:{cms: cms, frap:frap, userId: userId},
            success:function(data)
            {
                //alert("CMS "+cms+" FRAP "+frap+" USER_ID "+userId);
            },
            error:function(e){
                alert("error");
            }
        });
    }
</script>

<?php include('GLOBAL_FOOTER.php'); ?>
