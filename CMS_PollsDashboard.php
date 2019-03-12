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
include('GLOBAL_CMS_ADMIN_CHECKING.php');

$page_title = 'Santinig - Posts Dashboard';
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR.php';

$userId = $_SESSION['idnum'];
$cmsRole = $_SESSION['CMS_ROLE'];

?>

<script>
    $(document).ready(function() {

        let userId = '<?php echo $userId; ?>';
        let cmsRole = '<?php echo $cmsRole; ?>';
        let columns = [
            { data: "question" },
            { data: "author" },
            { data: "lastUpdated" },
            { data: "count" },
            { data: "actions" }
        ];

        let table = $('table.table').DataTable( {
            "ajax": {
                "url":"CMS_AJAX_FetchPolls.php",
                "type":"POST",
                "data":{ userId: userId },
                "dataSrc": ''
            },
            columns: columns,
            rowReorder: false
        });

        setInterval(function(){
            table.ajax.reload(null, false);
            $('.card-footer').html('Updated on '+table.cell(0,2).data());
        },1000);

    });
</script>

<div id="content-wrapper">

    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-8">
                <h3 class="page-header">
                    Santinig Polls
                    <a class="btn btn-primary" href="CMS_AddPoll.php"> Create Poll </a>
                </h3>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-8">

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>

                                    <th align="left" width="300px"><b>Question</b></th>
                                    <th align="left" width="200px"><b>Author</b></th>
                                    <th align="left" width="200px"><b>Last Updated</b></th>
                                    <th align="left" width="100px"><b>Responses</b></th>
                                    <th align="right" width="100px"><b>Action</b></th>

                                </tr>
                                </thead>
                                <tfoot>
                                <tr>

                                    <th align="left" width="300px"><b>Question</b></th>
                                    <th align="left" width="200px"><b>Author</b></th>
                                    <th align="left" width="200px"><b>Last Updated</b></th>
                                    <th align="left" width="100px"><b>Responses</b></th>
                                    <th align="right" width="100px"><b>Action</b></th>

                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer small text-muted"></div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-4">
                </div>
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->


</div>
<!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->
