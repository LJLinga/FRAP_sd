<?php
/**
 * Created by PhpStorm.
 * User: Serus Caligo
 * Date: 10/4/2018
 * Time: 3:48 PM
 */

include 'GLOBAL_TEMPLATE_Header.php';
include 'EDMS_TEMPLATE_MenuHeader.php';
include 'EDMS_TEMPLATE_NAVIGATION_ViewDocument.php';
?>
<script src="js/aesthetics.js"></script>s

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
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
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <p>
                                    The member must acquire the form from the office and accomplish it. They should have it notarized before submitting it back to the office for processing. This is required before their membership is approved
                                </p>
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
        </div>
    </div>
</div>

<?php include 'GLOBAL_TEMPLATE_Footer.php';?>
