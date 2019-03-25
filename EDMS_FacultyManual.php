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

$edmsRole = $_SESSION['EDMS_ROLE'];
?>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    Faculty Manual
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card" style="margin-bottom: 1rem;">
                    <div class="card-header">
                        Manual Title
                    </div>
                    <div class="card-body" >
                        Manual Sections
                        <div class="card">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card" style="margin-bottom: 1rem;">
                    <div class="card-header"><i class="fa fa-archive fa-lg"></i> Old Editions</div>
                    <div class="card-body">

                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<?php include 'GLOBAL_FOOTER.php';?>
