<?php
/**
 * Created by PhpStorm.
 * User: Serus Caligo
 * Date: 11/10/2018
 * Time: 11:00 AM
 */
include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
//include('GLOBAL_USER_TYPE_CHECKING.php');
//include('GLOBAL_EDMS_ADMIN_CHECKING.php');

include 'GLOBAL_HEADER.php';
include 'EDMS_USER_SIDEBAR_DocumentDashboard.php';
?>

<div id="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Lifetime Membership Document</h1>
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
            <div class="col-lg-12">

                <!-- IMPORTANT always put the Main Directory /FRAP_sd when refferencing documents using ViewerJS. If you don't, it will fail. -->
                <iframe src = "/ViewerJS/../FRAP_sd/Lifetime_Document/Lifetime_Membership_Application_Form.pdf" width='800' height='800' allowfullscreen webkitallowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>
