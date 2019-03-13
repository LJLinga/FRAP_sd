<?php
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
include('GLOBAL_EDMS_ADMIN_CHECKING.php');

include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';


$userId = $_SESSION['idnum'];
?>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();

        $('#btnAddDocument').on('click', function(){

        });
    });
</script>

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
                <div class="card">
                    <div class="card-header btn-group">
                        <a type="button" class="btn btn-default" id="btnAll">All</a>
                        <a type="button" class="btn btn-default" id="btnMine">Mine</a>
                        <a type="button" class="btn btn-success" id="btnPublished">Published</a>
                        <a type="button" class="btn btn-warning" id="btnPending">Pending Review</a>
                        <a type="button" class="btn btn-primary" id="btnDraft">Drafts</a>
                        <a type="button" class="btn btn-danger" id="btnArchived">Archived</a>
                    </div>
                    <div class="card-body">
                        <table id="myTable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th width="600px;">Title</th>
                                <th width="200px;">Process</th>
                                <th width="100px;">Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="card-footer">
                        <span>Last Updated on....</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        My Activities
                    </div>
                    <div class="card-body">
                        <div class="col-lg-2">All <b class="caret"></b></div>
                        <div class="col-lg-7"></div>
                        <div class="col-lg-3"><i class="fa fa-fw fa-plus-circle"></i>Create Groups</div>
                    </div>
                </div>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        My Documents
                    </div>
                    <div class="card-body">
                        <div class="col-lg-2">
                            Documents I modified <b class="caret"></b>
                        </div>
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
                    <label for="documentTitle">Assigned Task</label>
                    <div class="form-group">
                        <select class="form-control" id="selectedTask" name="selectedTask">
                            <option value="1" selected>FALP Application</option>
                            <option value="2">Post Attachment</option>
                            <option value="99">No Process</option>
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


        $('table.table').DataTable( {
            "ajax": {
                "url":"EDMS_AJAX_FetchDocuments.php",
                "type":"POST",
                "data":{ mode: '1'},
                "dataSrc": ''
            },
            columns: [
                { data: "title_version" },
                { data: "currentProcess" },
                { data: "actions"}
                ]
        } );


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

</script>

<?php include 'GLOBAL_FOOTER.php';?>
