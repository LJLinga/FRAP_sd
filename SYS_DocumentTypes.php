<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/24/2019
 * Time: 5:29 AM
 */


include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_CMS_ADMIN_CHECKING.php');

$page_title = 'Santinig - Posts Dashboard';
include 'GLOBAL_HEADER.php';
include 'SYS_SIDEBAR.php';

//$userId = $_SESSION['idnum'];

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
                    Document Types
                    <button class="btn btn-primary"> Add Document Type</button>
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered" align="center" id="dataTable">
                            <thead>
                            <tr>
                                <th>Document Type</th>
                                <th>Assigned Workflow </th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $rows = $crud->getData("SELECT id, processName FROM facultyassocnew.process WHERE processForId = 1;");
                            $processes = [];
                            if(!empty($rows)){
                                foreach((array) $rows as $key => $row){
                                    $processes[] = $row;
                                }
                            }

                            $rows = $crud->getData("SELECT * FROM facultyassocnew.doc_type;");
                            if(!empty($rows)){
                                foreach((array) $rows as $key => $row) { ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" class="type_id" value="<?php echo $row['id'];?>">
                                            <b><?php echo $row['type']?></b>
                                        </td>
                                        <td>
                                            <select class="form-control select_process">
                                                <?php foreach((array) $processes as $key2 => $row2){
                                                    if($row['processId'] == $row2['id']) { ?>
                                                        <option value="<?php echo $row2['id'];?>" selected><?php echo $row2['processName'];?></option>
                                                    <?php }else{ ?>
                                                        <option value="<?php echo $row2['id'];?>"><?php echo $row2['processName'];?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default" onclick="saveProcess(this)">Save</button>
                                            <?php if($row['isActive'] == '2') { ?>
                                                <button type="button" class="btn btn-danger" onclick="deactivate(this)">Deactivate</button>
                                            <?php }else if($row['isActive'] == '1') { ?>
                                                <button type="button" class="btn btn-success" onclick="activate(this)">Activate</button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                               <?php }
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
    $(document).ready(function(){
        $('#dataTable').DataTable();
    });

    function saveProcess(element, isActive){
        var typeId = $(element).closest('tr').find('.type_id').val();
        var processId = $(element).closest('tr').find('.select_process').val();
        $(element).closest('tr').children('td, th').css('background-color','#F0F0F0');
        $.ajax({
            url:"SYS_AJAX_SaveDocTypeProcess.php",
            method:"POST",
            data:{typeId:typeId, processId:processId, isActive: isActive},
            dataType:"JSON",
            success:function(data)
            {
            }
        });
    }

    function activate(element){
        $(element).closest('tr').find('.btn-default').after(' <button type="button" class="btn btn-danger" onclick="deactivate(this)">Deactivate</button>');
        saveProcess(element,'2');
        $(element).remove();
    }

    function deactivate(element){
        $(element).closest('tr').find('.btn-default').after(' <button type="button" class="btn btn-success" onclick="activate(this)">Activate</button>');
        saveProcess(element,'1');
        $(element).remove();
    }
</script>
<?php include 'GLOBAL_FOOTER.php' ?>

