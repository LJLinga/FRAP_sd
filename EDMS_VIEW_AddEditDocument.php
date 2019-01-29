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
include 'EDMS_USER_SIDEBAR_ViewSection.php';
?>

<div id="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <form>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-lg-3">
                                <h3 class="page-header">Add/Edit Section</h3>
                            </div>
                            <div class="col-lg-4">
                                <br><br>
                                <button class="btn btn-success">Save</button>
                                <button class="btn btn-default">Discard</button>
                            </div>
                            <h1><input type="text" size="57" height="300px"></h1>
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
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <textarea style="resize: none;" rows="4" cols="145">

                                    </textarea>
                                </div>
                                <div class="panel-footer">
                                    <div class="panel panel-default">
                                        <div id="docRef1B" class="panel-heading"><i class="fa fa-fw fa-file"></i> Document Referenced</div>
                                        <div id="docRef1">
                                            <div class="panel-body">No Document Referenced</div>
                                            <div class="panel-footer"><button>Add</button></div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><i class="fa fa-fw fa-file-text"></i> Document Section Referenced</div>
                                        <div id="docRef2">
                                            <div class="panel-body">
                                                No Minutes Section Referenced <br>
                                                No Faculty Manual Section Referenced <br>
                                            </div>
                                            <div class="panel-footer"><button>Add</button></div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><i class="fa fa-fw fa-file-o"></i> Other Document Referenced</div>
                                        <div id="docRef3">
                                            <div class="panel-body">No Document Refferenced</div>
                                            <div class="panel-footer"><button>Add</button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'GLOBAL_FOOTER.php';?>
