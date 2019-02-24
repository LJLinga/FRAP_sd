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
include 'EDMS_USER_SIDEBAR_DocumentDashboard.php';
?>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>

<div id="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">
                    Faculty Manual
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-7">
                <div class="card" style="margin-bottom: 1rem;">
                    <div class="card-header">
                        <ul class="nav">
                            <i class="fa fa-fw fa-folder-open fa-3x"></i>
                            <span style="font-size: 150%;"> 2018 (Current) </span>
                        </ul>
                    </div>


                    <div class="card-body" >
                        <ul class="nav">
                            <li>
                                <a href="javascript:;" data-toggle="collapse" data-target="#introduction">
                                    <i class="fa fa-fw fa-folder fa-lg"></i> Introduction
                                    <i class="fa fa-fw fa-caret-down"></i>
                                </a>
                                <ul id="introduction" class="nav collapse">
                                    <li>
                                        <a href="#"><i class="fa fa-fw fa-file-text"></i> Message from the President </a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa fa-fw fa-file-text"></i> De La Salle University </a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa fa-fw fa-file-text"></i> Lessons from the Founder </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:;" data-toggle="collapse" data-target="#general_provisions">
                                    <i class="fa fa-fw fa-folder fa-lg"></i> General Provisions
                                    <i class="fa fa-fw fa-caret-down"></i>
                                </a>
                                <ul id="general_provisions" class="nav collapse">
                                    <li>
                                        <a href="#"><i class="fa fa-fw fa-file-text"></i> Section 1: General Directives </a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa fa-fw fa-file-text"></i> Section 2: Classification </a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa fa-fw fa-file-text"></i> Section 3: Communication </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="#"><i class="fa fa-fw fa-folder fa-lg"></i> Undergraduate </a></li>
                            <li><a href="#"><i class="fa fa-fw fa-folder fa-lg"></i> Graduate </a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card" style="margin-bottom: 1rem;">
                    <div class="card-header"><i class="fa fa-fw fa-archive fa-lg"></i> Old Editions</div>
                    <div class="card-body">
                        <ul class="nav">
                            <li><a href="#"><i class="fa fa-fw fa-folder fa-lg"></i> 2015 </a></li>
                            <li><a href="#"><i class="fa fa-fw fa-folder fa-lg"></i> 2012 </a></li>
                            <li><a href="#"><i class="fa fa-fw fa-folder fa-lg"></i> 2009 </a></li>
                            <li><a href="#"><i class="fa fa-fw fa-folder fa-lg"></i> 2006 </a></li>
                            <li><a href="#"><i class="fa fa-fw fa-folder fa-lg"></i> 2003 </a></li>
                            <li><a href="#"><i class="fa fa-fw fa-folder fa-lg"></i> 2000 </a></li>
                        </ul>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<?php include 'GLOBAL_FOOTER.php';?>
