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
include 'EDMS_SIDEBAR_Dashboard.php';
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
                <h3 class="page-header">By Laws</h3>

            </div>
        </div>
        <div class="row">
            <div class="column-lg-12">
                <div class="card">
                    <div class="card-body">
                        <ol class="breadcrumb">
                            <li class="active">
                                General Files
                            </li>
                            <!-- *this succeeding lines of codes are for the breakdown of the files.
                            Modify this to be updated when user clicks on a directory and the page refreshes.
                            The next <li> should be appended here.
                            Ex. Gen files > User clicks on contract > Gen Files/Contracts is displayed.
                            Append the class active on the current directory being displayed. See ex. below
                            <li>
                                Contracts
                            </li>
                            <li class="active">
                                2018
                            </li> -->
                        </ol>
                        <!-- This table should be updated based on the contents of the current directory being displayed.
                        Ex. Default, display all the directory as such on the screen. When user navigates to Contracts
                        > 2018. It should display all contracts for 2018 -->
                        <table class="table table-bordered" align="center" id="dataTable">
                            <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Date Created</th>
                                <th>Created By</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-file"></i> Application Requiements_JuanDelaCruz</a></td>
                                <td>8/4/2018 7:59pm</td>
                                <td> - </td>
                            </tr>
                            <tr>
                                <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-file"></i> Application Requiements_JuanDelaCruz</a></td>
                                <td>8/4/2018 7:59pm</td>
                                <td> - </td>
                            </tr>
                            <tr>
                                <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-file"></i> Application Requiements_JuanDelaCruz</a></td>
                                <td>8/4/2018 7:59pm</td>
                                <td> - </td>
                            </tr>
                            <tr>
                                <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-file"></i> Application Requiements_JuanDelaCruz</a></td>
                                <td>8/4/2018 7:59pm</td>
                                <td> - </td>
                            </tr>
                            <tr>
                                <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-file"></i> Application Requiements_JuanDelaCruz</a></td>
                                <td>8/4/2018 7:59pm</td>
                                <td> - </td>
                            </tr>
                            <tr>
                                <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-file"></i> Application Requiements_JuanDelaCruz</a></td>
                                <td>8/4/2018 7:59pm</td>
                                <td> - </td>
                            </tr>
                            <tr>
                                <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-file"></i> Application Requiements_JuanDelaCruz</td>
                                <td>8/4/2018 7:59pm</td>
                                <td> - </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'GLOBAL_FOOTER.php';?>
