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

    $responseType = $_POST['responseType'];
    $options = $_POST['option'];
    $question = $_POST['question'];

    echo $responseType.', '.$question;

    $query = "INSERT INTO polls (question, authorId, approvedById, responseType) VALUES ('$question','$userId','$userId','$responseType');";
    $pollKey = $crud->executeGetKey($query);

    foreach($options AS $key => $value){
        echo $options[$key];
        $query = "INSERT INTO poll_options (option, pollId) VALUES ('$options[$key]','$pollKey');";
    }

}

$page_title = 'Santinig - Add Poll';
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR.php';
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
                            <form>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Response Type</label>
                                    <select name="responseType" class="form-control">
                                        <option value="1" selected>Single Response</option>
                                        <option value="2">Multiple Response</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Question</label>
                                    <input name="question" type="text" class="form-control input-md" placeholder="Ask a question" required>
                                </div>
                                <div class="form-group fieldGroup">
                                    <label>Responses</label>
                                    <div class="row fieldRow">
                                        <div class="col-lg-10">
                                            <input name="option[]" type="text" class="form-control input-md option-input" placeholder="Add an answer" required>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row fieldRow">
                                        <div class="col-lg-10">
                                            <input name="option[]" type="text" class="form-control input-md option-input" placeholder="Add an answer" required>
                                        </div>
                                    </div>
                                    <br>
                                    <button type="button" class="btn btn-default addField">Add Option</button>
                                </div>
                            </div>
                                <div class="card-footer">
                                    <button type="submit" name="btnSubmit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /#page-wrapper -->

    <script>
        $(document).ready( function(){
            disabledAddResponse($('.addField'));
            $('.addField').on('click', function(){
                $('.fieldRow').last().after('<div class="row fieldRow"><br>\n' +
                    '                                        <div class="col-lg-10">\n' +
                    '                                            <input name="option[]" type="text" class="form-control input-md option-input" placeholder="Add an answer" required>\n' +
                    '                                        </div>\n' +
                    '                                        <div class="col-lg-2">\n' +
                    '                                            <button type="button" class="btn btn-danger removeField"><i class="fa fa-trash"></i></button>\n' +
                    '                                        </div>\n' +
                    '                                    </div>');
                $('.removeField').on('click', function(){
                    $(this).closest('.fieldRow').remove();
                });
                disabledAddResponse($('.addField'));
            });
        });

        function disabledAddResponse(element){
            element.attr('disabled',true);
            element.closest('.form-group').find('input').last().keyup(function(){
                if($(this).val().length !=0)
                    $(element).attr('disabled', false);
                else
                    $(element).attr('disabled', true);
            });
        }

    </script>
<?php include 'GLOBAL_FOOTER.php' ?>