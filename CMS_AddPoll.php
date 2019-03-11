<?php

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_CMS_ADMIN_CHECKING.php');

/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */
$userId = $_SESSION['idnum'];
$cmsRole = $_SESSION['CMS_ROLE'];

if(isset($_POST['btnSubmit'])){

}

$page_title = 'Santinig - Add Poll';
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR_Admin.php';
?>
    <div id="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">
                        Create Poll
                    </h3>

                </div>
            </div>
            <!--Insert success page-->
            <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                <div class="row">
                    <div class="column col-lg-8">
                        <!-- Text input-->
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Response Type</label>
                                    <select class="form-control">
                                        <option>Single Response</option>
                                        <option>Multiple Response</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Question</label>
                                    <input type="text" class="form-control input-md" placeholder="Ask a question">
                                </div>
                                <div class="form-group">
                                    <label>Responses</label>
                                    <div class="row fieldRow">
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control input-md option-input" placeholder="Add an answer">
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="button" class="btn btn-danger removeField"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <br><button type="button" class="btn btn-default addField">Add Option</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="publishColumn" class="column col-lg-4" style="margin-top: 1rem; margin-bottom: 1rem;">

                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body">
                                No references
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-default"><i class="fa fa-fw fa-plus"></i><i class="fa fa-fw fa-file"></i> Add New Document</button>
                                <button class="btn btn-default"><i class="fa fa-fw fa-link"></i><i class="fa fa-fw fa-file"></i> Link Existing Document</button>
                            </div>
                        </div>

                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body">
                                Unsaved
                            </div>
                            <div class="card-footer">
                                <?php
                                if($cmsRole == '3') {
                                    echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Save as Draft</button> ';
                                    echo '<button type="submit" class="btn btn-primary" name="btnSubmit" id="btnSubmit" value="3">Submit for Publication</button> ';
                                }else if($cmsRole == '2'){
                                    echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Save as Draft</button> ';
                                    echo '<button type="submit" class="btn btn-primary" name="btnSubmit" id="btnSubmit" value="2">Submit for Review</button> ';
                                }else if($cmsRole == '4'){
                                    echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit" value="1">Save as Draft</button> ';
                                    echo '<button type="submit" class="btn btn-primary" name="btnSubmit" id="btnSubmit" value="4">Publish</button> ';
                                }
                                ?>
                            </div>
                        </div>

                        <!-- Button -->

                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /#page-wrapper -->

    <script>
        $(document).ready( function(){
            $('.addField').attr('disabled',true);
            $('.addField').closest('.form-group').find('input').keyup(function(){
                if($(this).val().length !=0)
                    $('.addField').attr('disabled', false);
                else
                    $('.addField').attr('disabled',true);
            });

            $('.addField').on('click', function(){
                $('.fieldRow').last().after('<br><div class="row fieldRow">\n' +
                    '                                        <div class="col-lg-10">\n' +
                    '                                            <input type="text" class="form-control input-md option-input" placeholder="Add an answer">\n' +
                    '                                        </div>\n' +
                    '                                        <div class="col-lg-2">\n' +
                    '                                            <button type="button" class="btn btn-danger removeField"><i class="fa fa-trash"></i></button>\n' +
                    '                                        </div>\n' +
                    '                                    </div>');
                $('.removeField').on('click', function(){
                    $(this).closest('.fieldRow').remove();
                });
            });

        });

    </script>
<?php include 'GLOBAL_FOOTER.php' ?>