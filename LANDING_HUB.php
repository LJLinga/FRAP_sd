<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 1/29/2019
 * Time: 7:01 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_CMS_ADMIN_CHECKING.php');

$page_title = 'Santinig - Add Event';
include 'GLOBAL_HEADER.php';


?>
<div id="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    Add New Event
                </h3>

            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    Add New Event
                </h3>

            </div>
        </div>
    </div>
</div>
