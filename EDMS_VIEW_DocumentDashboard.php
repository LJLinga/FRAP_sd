<?php
/**
 * Created by PhpStorm.
 * User: Serus Caligo
 * Date: 10/4/2018
 * Time: 3:48 PM
 */

include 'GLOBAL_TEMPLATE_Header.php';
include 'EDMS_TEMPLATE_MenuHeader.php';
include 'EDMS_TEMPLATE_NAVIGATION_DocumentDashboard.php';
?>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Documents</h1>

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
                                    <td><a href="#"><i class="fa fa-fw fa-folder"></i> Applications</a></td>
                                    <td>8/4/2018 7:59pm</td>
                                    <td> - </td>
                                </tr>
                                <tr>
                                    <td><a href="#"><i class="fa fa-fw fa-folder"></i> By-Laws</a></td>
                                    <td>8/4/2018 7:59pm</td>
                                    <td> - </td>
                                </tr>
                                <tr>
                                    <td><a href="#"><i class="fa fa-fw fa-folder"></i> Contracts</a></td>
                                    <td>8/4/2018 7:59pm</td>
                                    <td> - </td>
                                </tr>
                                <tr>
                                    <td><a href="#"><i class="fa fa-fw fa-folder"></i> Faculty Manual</a></td>
                                    <td>8/4/2018 7:59pm</td>
                                    <td> - </td>
                                </tr>
                                <tr>
                                    <td><a href="#"><i class="fa fa-fw fa-folder"></i> Executive Board Minutes</a></td>
                                    <td>8/4/2018 7:59pm</td>
                                    <td> - </td>
                                </tr>
                                <tr>
                                    <td><a href="#"><i class="fa fa-fw fa-folder"></i> General Assembly Minutes</a></td>
                                    <td>8/4/2018 7:59pm</td>
                                    <td> - </td>
                                </tr>
                                <tr>
                                    <td><a href="#"><i class="fa fa-fw fa-folder"></i> Technical Panel Minutes</td>
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

<?php include 'EDMS_TEMPLATE_Footer.php';?>
