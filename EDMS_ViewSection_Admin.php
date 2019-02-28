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
include 'EDMS_SIDEBAR_ViewSection.php';
?>
<script src="js/aesthetics.js"></script>

<div id="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7">
                <h1 class="page-header">Faculty Manual</h1>
                <ol class="breadcrumb">
                    <li>
                        1.0 Membership
                    </li>
                    <li>
                        1.0.1 Requirements
                    </li>
                    <li class="active">
                        1.0.1.1 Beneficiaries
                    </li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-7">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>
                            The member must acquire the form from the office and accomplish it. They should have it notarized before submitting it back to the office for processing. This is required before their membership is approved
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-fw fa-file"></i> Sub Sections</div>
                    <div class="panel-body">
                        <div class="collapse navbar-collapse navbar-ex1-collapse">
                            <ul class="nav">
                                <li><a href="#" class="active"><i class="fa fa-fw fa-folder"></i> 1.0.1.1 Beneficiaries</a></li>
                                <li><a href="#"><i class="fa fa-fw fa-folder"></i> 1.0.1.1.1 Exceptions</a></li>
                                <li><a href="#"><i class="fa fa-fw fa-folder"></i> 1.0.1.1.2 Adding More Beneficiaries</a></li>
                                <li><a href=""><i class="fa fa-fw fa-folder"></i> 1.0.1.2 Beneficiaries</a></li>
                                <li><a href="#"><i class="fa fa-fw fa-folder"></i> 1.0.1.2.1 Exceptions</a></li>
                                <li><a href="#"><i class="fa fa-fw fa-folder"></i> 1.0.1.2.2 Adding More Beneficiaries</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-fw fa-file"></i> References</div>
                    <div class="panel-body">No Document Referenced</div>
                    <div class="panel-footer">
                        <button class="btn btn-default"><i class="fa fa-fw fa-plus"></i><i class="fa fa-fw fa-file"></i> Add New Doc</button>
                        <button class="btn btn-default"><i class="fa fa-fw fa-link"></i><i class="fa fa-fw fa-file"></i> Link Existing Doc</button>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-fw fa-comment"></i> Comments</div>
                        <div class="panel-body">
                            <div class="panel panel-default">
                                <div id="" class="panel-heading"><i class="fa fa-fw fa-user"></i><b>Director</b>  February 14, 2019 14:45:01</div>
                                <div id="">
                                    <div class="panel-body">
                                        <p>I think revise this section of the page</p>
                                    </div>
                                    <div class="panel-footer">
                                        <button class="btn btn-default"><i class="fa fa-fw fa-thumbs-up"></i><b>5</b></button>
                                        <button class="btn btn-default"><i class="fa fa-fw fa-thumbs-down"></i></button>
                                        <button class="btn btn-default"><i class="fa fa-fw fa-comment"></i>Reply</button></br>
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <b>Darna</b> February 14, 2019 14:55:01 </br>
                                                I agree COMPELETELY gurl
                                            </div>
                                            <div class="panel-footer">
                                                <button class="btn btn-default"><i class="fa fa-fw fa-thumbs-up"></i><b>3</b></button>
                                                <button class="btn btn-default" class="btn btn-default"><i class="fa fa-fw fa-thumbs-down"></i></button>
                                                <button class="btn btn-default"><i class="fa fa-fw fa-comment"></i>Reply</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer"><button>Comment</button></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'GLOBAL_FOOTER.php';?>
