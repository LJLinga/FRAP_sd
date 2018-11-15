<?php
///**
// * Created by PhpStorm.
// * User: nicol
// * Date: 10/10/2018
// * Time: 3:48 PM
// */
//
//include_once('GLOBAL_CLASS_CRUD.php');
//$crud = new GLOBAL_CLASS_CRUD();
//
//$page_title = 'Santinig - Posts Dashboard';
//include 'GLOBAL_HEADER.php';
//include 'GLOBAL_NAV_TopBar.php';
//include 'CMS_ADMIN_NAV_Sidebar.php';
//?>

<!DOCTYPE html>
<head>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css" rel="stylesheet">
</head>

<script>
    $(document).ready(function() {
        $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
            $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
        } );

        $('table.table').DataTable( {
            ajax:           'ajax/CMS_POST_ARRAY_ALL.php',
            scrollY:        200,
            scrollCollapse: true,
            paging:         false
        } );

        // Apply a search to the second table for the demo
        $('#myTable2').DataTable().search( 'New York' ).draw();
    } );
</script>

<body>

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

            <table id="myTable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Office</th>
                    <th>Extn.</th>
                    <th>Start date</th>
                    <th>Salary</th>
                </tr>
                </thead>
            </table><table id="myTable2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Office</th>
                    <th>Extn.</th>
                    <th>Start date</th>
                    <th>Salary</th>
                </tr>
                </thead>
            </table>


        </div>


    </div>
    <!-- /.container-fluid -->


</div>
<!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->


</body>
