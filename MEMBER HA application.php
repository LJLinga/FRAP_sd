<?php
require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';

$_SESSION['errorsFromHAUpload'] = null;



//gets the latest addition if the member has applied for this shit already.

$checkForHealthAidApplicationQuery = "SELECT * FROM health_aid where MEMBER_ID = {$_SESSION['idnum']} ORDER BY RECORD_ID DESC LIMIT 1";
$checkForHealthAidApplicationResult = mysqli_query($dbc,$checkForHealthAidApplicationQuery);
$checkForHealthAidApplication = mysqli_fetch_array($checkForHealthAidApplicationResult);

if(!empty($checkForHealthAidApplication)){

    if($checkForHealthAidApplication['APP_STATUS'] == 1 && $checkForHealthAidApplication['PICKED_UP_STATUS'] == 1){

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA summary.php");

    }else if($checkForHealthAidApplication['APP_STATUS'] == 2 && $checkForHealthAidApplication['PICKED_UP_STATUS'] == 1){

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA summary.php");

    }else if($checkForHealthAidApplication['APP_STATUS'] == 2 && $checkForHealthAidApplication['PICKED_UP_STATUS'] == 2){

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA summary.php");

    }else if($checkForHealthAidApplication['APP_STATUS'] == 4){

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA review.php");

    }else if($checkForHealthAidApplication['APP_STATUS'] == 5){

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA summary.php");

    }
//    else if($checkForHealthAidApplication['APP_STATUS'] == 4){ //for the draft status
//
//        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA summary.php");
//    }
////    else{
//        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA application.php");
//
//    }

}




$page_title = 'Loans - Health Aid Application';
include 'GLOBAL_HEADER.php';
include 'FRAP_USER_SIDEBAR.php';

?>
<style>
    #noresize {
        resize: none;
    }

</style>

<script type="text/javascript">


    $(document).ready(function() {
        $("input[id^='upload_file']").each(function() {
            var id = parseInt(this.id.replace("upload_file", ""));
            $("#upload_file" + id).change(function() {
                if ($("#upload_file" + id).val() != "") {
                    $("#moreImageUploadLink").show();
                }
            });
        });
    });

    $(document).ready(function() {
        var upload_number = 2;
        $('#attachMore').click(function() {
            //add more file
            var moreUploadTag = '';
            moreUploadTag += '<div class="element">';
            moreUploadTag += '<input type="file" id="upload_file' + upload_number + '" name="upload_file[]" required/>';
            moreUploadTag += ' <a href="javascript:del_file(' + upload_number + ')" style="cursor:pointer;" onclick="return confirm("Are you really want to delete ?")"><i class="fa fa-trash"></i>  Delete </a></div>';
            $('<dl id="delete_file' + upload_number + '">' + moreUploadTag + '</dl>').fadeIn('slow').appendTo('#moreImageUpload');
            upload_number++;
        });
    });

    function del_file(eleId) {
        var ele = document.getElementById("delete_file" + eleId);
        ele.parentNode.removeChild(ele);
    }

</script>

    <div id="page-wrapper">

        <div class="container-fluid">

            <form action="MEMBER_UploadDocument_HA.php" method="POST" enctype="multipart/form-data">

                <div class="row"> <!-- Title & Breadcrumb -->

                    <div class="col-lg-12">
                        <?php if(!empty($_SESSION['errorsFromHAUpload'])){?>
                        <div class="alert alert-danger">
                            <strong>Failure!</strong> Something Wrong Happened. Whoops Errors are as follows.

                            <?php foreach($_SESSION['errorsFromHAUpload'] as $errorsHA){
                                echo $errorsHA.' ';
                            }?>


                        </div>

                        <?php }?>

                        <h1 class="page-header">Health Aid Application Form</h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                Application
                            </li>
                            <li>
                                In-Process Summary
                            </li>
                            <li>
                                Final Summary
                            </li>
                        </ol>

                    </div>

                    <div class = "col-lg-12">

                        <label>Please fill out the necessary fields below in order for the committee to process your request. Upload the receipts as proof of your situation.</label>

                    </div>
                </div>

                <div class = "row" style="margin-top: 5px;">

                    <div class="col-lg-8">

                        <div class="panel panel-default">

                            <div class="panel-heading">

                                <b> Apply for Health Aid</b>

                            </div>


                            <div class="panel-body">

                                <div class="form-group">

                                    <label for="usr">Amount to Borrow:</label>

                                    <input type="number" name="amount" placeholder="Range: 1000-20000 PhP" class="form-control" id="usr" size="5" style="width:250px;" required>

                                </div>

                                <div class="form-group">

                                    <label>Reason for the Health Aid Application: </label>

                                    <textarea placeholder="Place your reason here" id="noresize" name="message" class="form-control" rows="5" cols="125" required></textarea>


                                </div>

                            </div>

                        </div>

                    </div>





                    <div class= "col-lg-4">

                        <div class="panel panel-default">

                            <div class="panel-heading">

                                <b>Upload Files Here</b>

                            </div>


                            <div class="panel-body">

                                <div class="element">
                                    <input type="file" name="upload_file[]" id="upload_file1" required/>
                                </div>

                                <div id="moreImageUpload">
                                    <br>
                                </div>

                                <div class="clear">

                                </div>

                                <div id="moreImageUploadLink" style="display:none;margin-left: 10px;">
                                    <i class="fa fa-plus"></i>   <a href="javascript:void(0);" id="attachMore">Attach another file</a>
                                </div>


                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">
                    <div class="col-lg-1">
                        <input type="submit" name="applyHA" class="btn btn-success" value="Submit Application">
                    </div>
                </div>

            </form>

            </div>

        </div>


    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

<?php include 'GLOBAL_FOOTER.php' ?>
