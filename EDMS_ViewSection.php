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
                <div class="card" style="margin-bottom: 1rem;">
                    <div class="card-header"><i class="fa fa-fw fa-file"></i> Sub Sections</div>
                    <div class="card-body">
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
                <div class="card">
                    <div class="card-header"><i class="fa fa-fw fa-file"></i> References</div>
                    <div class="card-body">No Document Referenced</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'GLOBAL_FOOTER.php';?>
