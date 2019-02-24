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
include 'EDMS_USER_SIDEBAR_ViewDocument.php';
?>
<script src="js/aesthetics.js"></script>

<div id="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7">
                <h1 class="page-header">File View</h1>
                <ol class="breadcrumb">
                    <li>
                        Miscellaneous
                    </li>
                    <li>
                        Faculty Manual Appendices
                    </li>
                    <li class="active">
                        Applications
                    </li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-7">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <!-- IMPORTANT always put the Main Directory /FRAP_sd when refferencing documents using ViewerJS. If you don't, it will fail. -->
                        <iframe src = "/ViewerJS/../FRAP_sd/Lifetime_Document/Lifetime_Membership_Application_Form.pdf" width='780' height='800'  allowfullscreen webkitallowfullscreen></iframe>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">

                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-fw fa-file"></i> References</div>
                    <div class="panel-body">No Document Referenced</div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'GLOBAL_FOOTER.php';?>
