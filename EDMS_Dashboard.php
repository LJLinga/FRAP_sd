<?php
/**
 * Created by PhpStorm.
 * User: Serus Caligo
 * Date: 10/4/2018
 * Time: 3:48 PM
 */

//include_once('GLOBAL_CLASS_CRUD.php');
//$crud = new GLOBAL_CLASS_CRUD();
//require_once('mysql_connect_FA.php');
session_start();
//include('GLOBAL_USER_TYPE_CHECKING.php');
//include('GLOBAL_EDMS_ADMIN_CHECKING.php');

include 'GLOBAL_HEADER.php';
//include 'EDMS_USER_SIDEBAR_DocumentDashboard.php';
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
                        <h1 class="page-header"><i class="fa fa-fw fa-folder"></i> General Files</h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                General Files
                            </li>
                        </ol>
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="panel-default">
                                    <div class="panel-heading">
                                        My Groups
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-2">All <b class="caret"></b></div>
                                        <div class="col-lg-7"></div>
                                        <div class="col-lg-3"><i class="fa fa-fw fa-plus-circle"></i>Create Groups</div>
                                        <hr>
                                        <div class="panel-body">Test</div>
                                        <hr>
                                        <div class="panel-body">Test</div>
                                    </div>
                                </div>
                                <div class="panel-default">
                                    <div class="panel-heading">
                                        My Tasks
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-2">All <b class="caret"></b></div>
                                        <div class="col-lg-7"></div>
                                        <div class="col-lg-3"><i class="fa fa-fw fa-plus-circle"></i>Create Groups</div>
                                        <hr>
                                        <div class="panel-body">Test</div>
                                        <hr>
                                        <div class="panel-body">Test</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-7">
                                <div class="panel-default">
                                    <div class="panel-heading">
                                        My Activities
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-2">All <b class="caret"></b></div>
                                        <div class="col-lg-7"></div>
                                        <div class="col-lg-3"><i class="fa fa-fw fa-plus-circle"></i>Create Groups</div>
                                        <hr>
                                        <div class="panel-body">Test</div>
                                    </div>
                                </div>
                                <div class="panel-default">
                                    <div class="panel-heading">
                                        My Documents
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-2">

                                            Documents I modified <b class="caret"></b>
                                        </div>
                                        <div class="col-lg-7"></div>
                                        <div class="col-lg-3"><i class="fa fa-fw fa-plus-circle"></i>Create Groups</div>
                                        <hr>
                                        <div class="panel-body">Test</div>
                                    </div>
                                </div>
                            </div>

                        </div>
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
