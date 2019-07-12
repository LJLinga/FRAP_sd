<?php

//Christian

include('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');

if(isset($_GET['id'])){

    $groupId = $_GET['id'];

    $rows = $crud->getGroup($groupId);
    if(!empty($rows)){
        foreach((array) $rows AS $key => $row){
            $groupName = $row['groupName'];
            $groupDesc = $row['groupDesc'];
            $isActive = $row['isActive'];
        }
    }

    if($isActive == '1'){
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_Workspace.php");
        exit;
    }

}

include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';

?>

<div class="content-wrapper">
    <div class="container-fluid">
        <h3 class="page-header">
            <div class="row">
                <div class="col-lg-8">
                    <?php echo $groupDesc;?> (<?php echo $groupName;?>) Space
                    <button name="btnAddDocument" id="btnAddDocument" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Add Document</button>
                    <button name="btnMyWorkflows" id="btnMyWorkflows" data-toggle="modal" data-target="#myWorkflowsModal" class="btn btn-info">My Workflows</button>
                </div>
                <div class="col-lg-4">
                    <div class="form-inline">
                        Search
                        <input type="text" id="searchField" class="form-control">
                    </div>
                </div>
            </div>
        </h3>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <p class="panel-title"><?php echo $row['processName'];?></p>

                    </div>
                    <div class="panel-body">
                        <table id="myTable1" class="table table-striped table-responsive" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Processes</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <?php

                $rows = $crud->getGroupWorkflows2($groupId);
                if(!empty($rows)){
                    foreach((array) $rows AS $key => $row){
                        $processName = $row['processName'];
                        $processId = $row['id'];?>

                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <p class="panel-title"><?php echo $row['processName'];?></p>

                            </div>
                            <div class="panel-body">
                                <table id="myTable1" class="table table-striped table-responsive" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Processes</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>


                    <?php }
                }

                ?>

            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        $('#navSideItemWorkspace').addClass('active');

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
                success: function(response){
                    if(JSON.parse(response).success == '1'){ location.href = "EDMS_ViewDocument.php?docId="+JSON.parse(response).id }
                    else { $("#err").html('<div class="alert alert-warning">'+JSON.parse(response).html+'</div>'); };
                },
                error: function(){
                    alert("Error");
                }
            });
            return false;
        });

        $('#inputFileUpload').bind('change', function() {
            if(this.files[0].size > 25000000){
                $("#err").html('<div class="alert alert-warning">File size limit of 25 MB exceeded.</div>');
            }else{
                $("#err").html('');
            }

        });

    } );

    let table = $('#myTable1').DataTable( {
        bSort: true,
        bLengthChange: false,
        scrollX: true,
        destroy: true,
        pageResize: true,
        pageLength: 10,
        "ajax": {
            "url":"EDMS_AJAX_FetchDocuments.php",
            "type":"POST",
            "dataSrc": '',
            "data": {
                requestType: 'GROUPSPACE'
            },
        },
        columns: [
            { data: "title" },
            { data: "type" },
            { data: "vers"},
            { data: "submitted_by"},
            { data: "submitted_on"},
            { data: "status"},
            { data: "timestamp"},
            { data: "actions"}
        ]
    });

    $(".dataTables_filter").hide();
    $('#searchField').keyup(function(){
        table.search($('#searchField').val()).draw();
    });

    function filterStatus(status){
        table.column(5).search(status).draw();
    }
    function filterType(type){
        table.column(1).search(type).draw();
    }

    setInterval(function(){
        table.ajax.reload();
    },1000)
</script>