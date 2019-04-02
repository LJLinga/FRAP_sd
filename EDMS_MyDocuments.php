0<?php
/**
 * Created by PhpStorm.
 * User: Serus Caligo
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

<div id="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header"> My Documents
                    <button name="btnAddDocument" id="btnAddDocument" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Add Document</button>
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="btn-group">
                                    <a type="button" class="btn btn-default" id="btnAll" onclick="searchTable('',1)">All</a>
                                    <?php
                                    $rows = $crud->getData("SELECT statusName FROM facultyassocnew.doc_status;");
                                    foreach((array) $rows as $key => $row){
                                        echo '<a type="button" class="btn btn-default" onclick="searchTable(&quot;'.$row['statusName'].'&quot;,1)">'.$row['statusName'].'</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-inline">
                                    <label for="sel1">Type</label>
                                    <select class="form-control" onchange="searchTable(this.value,0)">
                                        <option value="All">All</option>
                                        <?php
                                        $rows = $crud->getData("SELECT t.type FROM facultyassocnew.doc_type t WHERE isActive = 2;");
                                        foreach((array)$rows as $key => $row){
                                            echo '<option value="'.$row['type'].'">'.$row['type'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <table id="myTable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th width="500px;">Title</th>
                                <th width="300px;">Process</th>
                                <th width="100px;">Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="panel-footer">
                        <span>Last Updated on....</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <b>
                            <?php
                            $rows = $crud->getData("SELECT roleName FROM edms_roles WHERE id = ".$_SESSION['EDMS_ROLE']." LIMIT 1;");
                            echo $rows[0]['roleName'];
                            ?>
                        </b> Workflows
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
                                echo '<div class="card">';
                                echo '<div class="card-body">';
                                echo $row['processName'].' <i class="fa fa-arrow-right"></i> Step '.$row['stepNo'].' - '.$row['name'].'<br>';
                                echo '</div></div>';
                            }
                        }else{
                            echo 'You have no workflows but is still able to submit documents to their respective workflows.';
                        }
                        ?>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        My Activities
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-2">All <b class="caret"></b></div>
                        <div class="col-lg-7"></div>
                        <div class="col-lg-3"><i class="fa fa-fw fa-plus-circle"></i>Create Groups</div>
                    </div>
                </div>
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
                    <label for="documentTitle">Type</label>
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

        $("#documentUploadForm").on('submit', function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "EDMS_AJAX_UploadDocument.php",
                cache: false,
                processData: false,
                contentType: false,
                data: new FormData(this),
                success: function(response){
                    $("#err").html(response);
                    $("#contact-modal").modal('hide');
                    if(response !== 'error') location.href = "http://localhost/FRAP_sd/EDMS_ViewDocument.php?docId="+response;
                },
                error: function(){
                    alert("Error");
                }
            });
            return false;
        });

    } );

    let mode = '<?php echo $userId; ?>';

    searchTable('',1);

    function searchTable(searchText, col){
        let table = $('table.table').DataTable( {
            bSort: false,
            destroy: true,
            pageLength: 5,
            "ajax": {
                "url":"EDMS_AJAX_FetchMyDocuments.php",
                "type":"POST",
                "data":{ userId : mode },
                "dataSrc": ''
            },
            columns: [
                { data: "title_version" },
                { data: "currentProcess" },
                { data: "actions"}
            ]
        });
        if(searchText === 'All'){
            searchText = '';
        }
        table.column(col).search(searchText).draw();
        // setInterval(function(){
        //     table.ajax.reload();
        // },1000)
    }

</script>

<?php include 'GLOBAL_FOOTER.php';?>
