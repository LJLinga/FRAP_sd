<?php
/**
 * Created by PhpStorm.
 * User: Christian Alderite
 * Date: 10/4/2018
 * Time: 3:48 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
//include('GLOBAL_EDMS_ADMIN_CHECKING.php');

include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';

$edmsRole = $_SESSION['EDMS_ROLE'];
$userId = $_SESSION['idnum'];
$rows = $crud->getData("SELECT CONCAT(e.FIRSTNAME, ,e.LASTNAME) AS name FROM employee e WHERE e.EMP_ID ='$userId' LIMIT 1;");
$userName = $rows[0]['name'];
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header"> My Documents
                    <button name="btnAddDocument" id="btnAddDocument" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Add Document</button>
                    <button name="btnMyWorkflows" id="btnMyWorkflows" data-toggle="modal" data-target="#myWorkflowsModal" class="btn btn-info">My Workflows</button>
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-inline">
                                    <label for="sel1">Category</label>
                                    <select class="form-control" onchange="filterType(this.value)">
                                        <option value="" selected>All</option>
                                        <?php
                                        $rows = $crud->getData("SELECT t.type FROM facultyassocnew.doc_type t WHERE isActive = 2;");
                                        foreach((array)$rows as $key => $row){
                                            echo '<option value="'.$row['type'].'">'.$row['type'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-inline">
                                    <label for="sel1">Status</label>
                                    <select class="form-control" onchange="filterStatus(this.value)">
                                        <option value="" selected>All</option>
                                        <?php
                                        $rows = $crud->getData("SELECT statusName FROM facultyassocnew.doc_status;");
                                        foreach((array)$rows as $key => $row){
                                            echo '<option value="'.$row['statusName'].'">'.$row['statusName'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-inline">
                                    <label for="sel1">Search</label>
                                    <input type="text" id="searchField" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table id="myTable1" class="table table-striped" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Version</th>
                                <th>Submitted on</th>
                                <th>Status</th>
                                <th>Last updated by</th>
                                <th>Last updated on</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="myWorkflowsModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="panel panel-green">
            <div class="panel-heading">
                <b> As
                    <?php
                    $rows = $crud->getData("SELECT roleName FROM edms_roles WHERE id = ".$_SESSION['EDMS_ROLE']." LIMIT 1;");
                    echo $rows[0]['roleName'];
                    ?>
                </b> your workflows are
            </div>
            <div class="panel-body">
                <?php
                    $rows = $crud->getData("SELECT  pr.processName, s.stepNo, s.stepName AS name 
                                                    FROM employee e 
                                                    JOIN edms_roles er ON e.EDMS_ROLE = er.id 
                                                    JOIN step_roles sr ON sr.roleId = er.id
                                                    JOIN steps s ON s.id = sr.stepId
                                                    JOIN process pr ON pr.id = s.processId
                                                    WHERE e.EMP_ID = '$userId'
                                                    ORDER BY pr.processName, s.stepNo");
                    if(!empty($rows)){
                        foreach((array)$rows AS $key => $row){
                            echo '<div class="card" style="margin-top: 1rem;">';
                            echo '<div class="card-body">';
                            echo $row['processName'].' <i class="fa fa-arrow-right"></i> Step '.$row['stepNo'].' - '.$row['name'].'<br>';
                            echo '</div></div>';
                        }
                    }else{
                        echo 'You have no workflows but is still able to submit documents to their respective workflows.';
                    }
                ?>
            </div>
            <div class="panel-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <form method="POST" id="documentUploadForm">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="documentTitle">Document</label>
                        <input type="text" name="documentTitle" id="documentTitle" class="form-control" placeholder="Document Title" required>
                    </div>
                    <label for="selectedType">Category</label>
                    <div class="form-group">
                        <select class="form-control" id="selectedType" name="selectedType">
                            <?php
                            $rows = $crud->getData("SELECT t.id, t.type FROM facultyassocnew.doc_type t WHERE isActive = 2;");
                            if(!empty($rows)){
                                foreach ((array) $rows as $key => $row) {
                                    echo '<option value="'.$row['id'].'">'.$row['type'].'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="file">Upload</label>
                        <input type="file" class="form-control-file" id="file" name="file" required>
                    </div>
                    <span id="err"></span>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <input type="submit" name="btnSubmit" id="btnSubmit" class="btn btn-primary">
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>


<script>
    $(document).ready(function() {

        $('[data-toggle="tooltip"]').tooltip();

        $("#documentUploadForm").on('submit', function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "EDMS_AJAX_UploadDocument.php",
                cache: false,
                processData: false,
                contentType: false,
                data: new FormData(this),
                dataType: 'json',
                success: function(response){
                    if(JSON.parse(response).success == '1'){ location.href = "http://localhost/FRAP_sd/EDMS_ViewDocument.php?docId="+JSON.parse(response).id }
                    else { $("#err").html('<div class="alert alert-info">'+JSON.parse(response).html+'</div>'); };
                },
                error: function(){
                    alert("Error");
                }
            });
            return false;
        });

    } );

    let mode = '<?php echo $userId; ?>';

    let table = $('#myTable1').DataTable( {
        bSort: true,
        bLengthChange: false,
        destroy: true,
        pageResize: true,
        pageLength: 10,
        order: [[3, 'desc']],
        "ajax": {
            "url":"EDMS_AJAX_FetchDocuments.php",
            "type":"POST",
            "dataSrc": '',
            "data": {
                requestType: 'MYDOCUMENTS'
            },
        },
        columns: [
            { data: "title" },
            { data: "type" },
            { data: "vers"},
            { data: "timeCreated"},
            { data: "status"},
            { data: "modified_by"},
            { data: "lastUpdated"},
            { data: "actions"}
        ]
    });

    $(".dataTables_filter").hide();
    $('#searchField').keyup(function(){
        table.search($('#searchField').val()).draw();
    });

    function filterStatus(status){
        table.column(4).search(status).draw();
    }
    function filterType(type){
        table.column(1).search(type).draw();
    }

    setInterval(function(){
        table.ajax.reload();
    },1000)

</script>

<?php include 'GLOBAL_FOOTER.php';?>
