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

if(isset($_GET['pollId'])){

    $pollId = $_GET['pollId'];

    $query = "SELECT p.id, p.question, p.authorId, p.timeCreated, p.lastUpdated, p.responseType, t.type, CONCAT(e.LASTNAME, ', ',e.FIRSTNAME) as name 
              FROM polls p JOIN poll_response_type t ON p.responseType = t.id 
              JOIN employee e ON p.authorId=e.EMP_ID
              WHERE p.id = '$pollId'";
    $rows = $crud->getData($query);
    if(!empty($rows)){
        foreach ((array) $rows as $key => $row) {
            $question = $row['question'];
            $authorId = $row['authorId'];
            $responseTypeId = $row['responseType'];
            $responseType = $row['type'];
            $timeCreated = $row['timeCreated'];
            $lastUpdated = $row['lastUpdated'];
            $authorName = $row['name'];
        }
    }

}else{
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_PostsDashboard.php");
}

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

$page_title = 'Santinig - Edit Poll';
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR.php';
?>

    <style>
        @media screen and (min-width: 1200px) {
            #publishColumn{
                position: fixed;
                right:1rem;
            }
        }
        @media screen and (max-width: 1199px) {
            #publishColumn{
                position: relative;
            }
        }
        .fr-view {
            font-family: "Verdana", Georgia, Serif;
            font-size: 14px;
            color: #444444;
        }
    </style>
    <div id="content-wrapper">
        <div class="container-fluid">
            <!--Insert success page-->
            <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                <div class="row" style="margin-top: 2rem;">
                    <div class="column col-lg-7">
                        <!-- Text input-->
                        <div class="card">
                            <div class="card-body">
                                    <div class="form-group">
                                        <label>Question</label>
                                        <input name="question" type="text" class="form-control input-md" placeholder="Ask a question" value="<?php echo $question; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Response Type</label>
                                        <select name="responseType" class="form-control">
                                            <option value="1" selected>Single Response</option>
                                            <option value="2">Multiple Response</option>
                                        </select>
                                    </div>
                                    <div class="form-group fieldGroup">
                                        <label>Responses</label>
                                        <?php
                                            $query = "SELECT o.id, o.option FROM poll_options o WHERE o.pollId = $pollId";
                                            $rows = $crud->getData($query);
                                            if(!empty($rows)){
                                                foreach ((array) $rows as $key => $row) {
                                                    echo '<div class="row fieldRow">
                                                            <div class="col-lg-10">
                                                                <input name="option[]" type="text" class="form-control input-md option-input" value="'.$row['option'].'">
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <button type="button" class="btn btn-danger removeField"><i class="fa fa-trash"></i></button>
                                                            </div>
                                                        </div>';
                                                }
                                            }
                                        ?>
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
                                <button type="button" class="btn btn-default"><i class="fa fa-fw fa-plus"></i><i class="fa fa-fw fa-file"></i> Add New Document</button>
                                <button type="button" class="btn btn-default"><i class="fa fa-fw fa-link"></i><i class="fa fa-fw fa-file"></i> Link Existing Document</button>
                            </div>
                        </div>

                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body">
                                Author: <b><?php echo $authorName; ?></b><br>
                                Response Type: <b><?php echo $responseType?></b><br>
                                <i>Created on: <b><?php echo date("F j, Y g:i:s A ", strtotime($timeCreated)); ?></b></i><br>
                                <i>Last updated: <b><?php  echo date("F j, Y g:i:s A ", strtotime($lastUpdated));?></b></i>
                                <?php if(!empty($pollId)){ ?>
                                    (<a href="<?php echo "http://localhost/FRAP_sd/poll.php?pl=".$pollId?>" >Preview</a>)
                                <?php } ?>
                                <br>
                                <input type="hidden" id="post_id" name="post_id" value="<?php if(isset($postId)){ echo $postId;}; ?>">
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="btnSubmit" class="btn btn-primary">Submit</button>
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
            $('.addField').closest('.form-group').find('input').last().keyup(function(){
                if($(this).val().length !=0)
                    $('.addField').attr('disabled', false);
                else
                    $('.addField').attr('disabled', true);
            });

            // $('.option-input').on('keyup', function(){
            //     $('.addField').attr('disabled', function(){
            //         $('.option-input').each(function(){
            //             return($(this).val().length === 0);
            //         });
            //     });
            // });

            $('.addField').on('click', function(){
                $('.fieldRow').last().after('<br><div class="row fieldRow">\n' +
                    '                                        <div class="col-lg-10">\n' +
                    '                                            <input name="option[]" type="text" class="form-control input-md option-input" placeholder="Add an answer">\n' +
                    '                                        </div>\n' +
                    '                                        <div class="col-lg-2">\n' +
                    '                                            <button type="button" class="btn btn-danger removeField"><i class="fa fa-trash"></i></button>\n' +
                    '                                        </div>\n' +
                    '                                    </div>');
                $('.removeField').on('click', function(){
                    $(this).closest('.fieldRow').remove();
                });
                $('.addField').attr('disabled',true);
                $('.addField').closest('.form-group').find('input').last().keyup(function(){
                    if($(this).val().length !=0)
                        $('.addField').attr('disabled', false);
                    else
                        $('.addField').attr('disabled',true);
                });
                // $('.option-input').on('keyup', function(){
                //     $('.addField').attr('disabled', function(){
                //         $('.option-input').each(function(){
                //             return($(this).val().length === 0);
                //         });
                //     });
                // });
            });

        });

    </script>
<?php include 'GLOBAL_FOOTER.php' ?>