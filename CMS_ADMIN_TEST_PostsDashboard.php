<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$page_title = 'Santinig - Posts Dashboard';
include 'GLOBAL_HEADER.php';
include 'GLOBAL_NAV_TopBar.php';
include 'CMS_ADMIN_NAV_Sidebar.php';
?>

<script>
    $(document).ready(function() {

        $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });


        $('table.table').DataTable( {
            ajax:           'ajax/CMS_POST_ARRAY_ALL.php',
            scrollY:        200,
            scrollCollapse: true,
            paging:         false
        } );

        $('#tablePublished').DataTable().search('Published').draw();
        $('#tablePending').DataTable().search('Pending').draw();
        $('#tableDraft').DataTable().search('Draft').draw();
        $('#tableArchived').DataTable().search('Archived').draw();


    });
</script>

<div id="content-wrapper">

    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    Santinig Posts
                    <a class="btn btn-primary" href="CMS_ADMIN_AddPost.php"> Add New Post </a>
                </h3>
            </div>
            <div class="col-lg-12">



            </div>
        </div>

        <div class="row">


        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tableAll" width="100%" cellspacing="0">
                        <thead>
                        <tr>

                            <th align="left" width="200px"><b>Title</b></th>
                            <th align="left" width="200px"><b>Author</b></th>
                            <th align="left" width="100px"><b>Status</b></th>
                            <th align="left" width="200px"><b>Last Updated</b></th>
                            <th align="right" width="200px"><b>Actions</b></th>

                        </tr>
                        </thead>
                        <tfoot>
                        <tr>

                            <th align="left" width="200px"><b>Title</b></th>
                            <th align="left" width="200px"><b>Author</b></th>
                            <th align="left" width="100px"><b>Status</b></th>
                            <th align="left" width="200px"><b>Last Updated</b></th>
                            <th align="right" width="200px"><b>Actions</b></th>

                        </tr>
                        </tfoot>
                    </table>
                    <table class="table table-bordered" id="tablePublished" width="100%" cellspacing="0">
                        <thead>
                        <tr>

                            <th align="left" width="200px"><b>Title</b></th>
                            <th align="left" width="200px"><b>Author</b></th>
                            <th align="left" width="200px"><b>Last Updated</b></th>
                            <th align="right" width="200px"><b>Actions</b></th>

                        </tr>
                        </thead>
                        <tfoot>
                        <tr>

                            <th align="left" width="200px"><b>Title</b></th>
                            <th align="left" width="200px"><b>Author</b></th>
                            <th align="left" width="200px"><b>Last Updated</b></th>
                            <th align="right" width="200px"><b>Actions</b></th>

                        </tr>
                        </tfoot>
                    </table>
                    <table class="table table-bordered" id="tablePending" width="100%" cellspacing="0">
                        <thead>
                        <tr>

                            <th align="left" width="200px"><b>Title</b></th>
                            <th align="left" width="200px"><b>Author</b></th>
                            <th align="left" width="200px"><b>Last Updated</b></th>
                            <th align="right" width="200px"><b>Actions</b></th>

                        </tr>
                        </thead>
                        <tfoot>
                        <tr>

                            <th align="left" width="200px"><b>Title</b></th>
                            <th align="left" width="200px"><b>Author</b></th>
                            <th align="left" width="200px"><b>Last Updated</b></th>
                            <th align="right" width="200px"><b>Actions</b></th>

                        </tr>
                        </tfoot>
                    </table>
                    <table class="table table-bordered" id="tableDraft" width="100%" cellspacing="0">
                        <thead>
                        <tr>

                            <th align="left" width="200px"><b>Title</b></th>
                            <th align="left" width="200px"><b>Author</b></th>
                            <th align="left" width="200px"><b>Last Updated</b></th>
                            <th align="right" width="200px"><b>Actions</b></th>

                        </tr>
                        </thead>
                        <tfoot>
                        <tr>

                            <th align="left" width="200px"><b>Title</b></th>
                            <th align="left" width="200px"><b>Author</b></th>
                            <th align="left" width="200px"><b>Last Updated</b></th>
                            <th align="right" width="200px"><b>Actions</b></th>

                        </tr>
                        </tfoot>
                    </table>
                    <table class="table table-bordered" id="tableArchived" width="100%" cellspacing="0">
                        <thead>
                        <tr>

                            <th align="left" width="200px"><b>Title</b></th>
                            <th align="left" width="200px"><b>Author</b></th>
                            <th align="left" width="200px"><b>Last Updated</b></th>
                            <th align="right" width="200px"><b>Actions</b></th>

                        </tr>
                        </thead>
                        <tfoot>
                        <tr>

                            <th align="left" width="200px"><b>Title</b></th>
                            <th align="left" width="200px"><b>Author</b></th>
                            <th align="left" width="200px"><b>Last Updated</b></th>
                            <th align="right" width="200px"><b>Actions</b></th>

                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div>

    </div>
    <!-- /.container-fluid -->


</div>
<!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->

