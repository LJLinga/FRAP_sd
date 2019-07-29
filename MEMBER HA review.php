<?php
require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
require 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();



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

    }else if($checkForHealthAidApplication['APP_STATUS'] == 3 || $checkForHealthAidApplication['APP_STATUS'] == 6){

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA application.php");


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



// put submit code here





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

            <div class="row"> <!-- Title & Breadcrumb -->

                <div class="col-lg-12">

                    <h1 class="page-header">Review your Health Aid application</h1>
                    <ol class="breadcrumb">
                        <li>
                            Application
                        </li>
                        <li>
                            In-Process Summary
                        </li>
                        <li class="active">
                            Final Summary
                        </li>
                    </ol>
                </div>

                <div class = "col-lg-8">
                    <div class ="alert alert-info">
                        Below are your details that will be reviewed by the Committee and President.
                    </div>
                </div>

            </div>

            <div class = "row" style="margin-top: 5px;">

                <div class="col-lg-8">

                    <div class="panel panel-default">

                        <div class="panel-heading">

                            <b> Health Aid Application Details </b>

                        </div>


                        <div class="panel-body">

                            <div class="form-group">

                                <label for="usr">Amount to Borrow:</label>

                                <input type="number" name="amount" placeholder="<?php echo $checkForHealthAidApplication['AMOUNT_TO_BORROW'] ?>" class="form-control" id="usr" size="5" style="width:250px;" disabled>

                            </div>

                            <div class="form-group">

                                <label>Reason for the Health Aid Application: </label>

                                <textarea  placeholder="<?php echo $checkForHealthAidApplication['MESSAGE'] ?>"  id="noresize" name="message" class="form-control" rows="5" cols="125" disabled></textarea>

                            </div>

                        </div>

                    </div>

                </div>





                <div class= "col-lg-4">

                    <div class="panel panel-default">

                        <div class="panel-heading">

                            <b>View Uploaded Files and their Status</b>

                        </div>

                        <div class="panel-body">

                            <?php
                            //gets the document ids and their
                            $query = "SELECT  d.documentId,d.title, ds.statusName
                                         from ref_document_healthaid rdh
                                         join documents d 
                                         ON rdh.DOC_ID = d.documentId
                                         join doc_status ds
                                         on d.statusId = ds.id
                                         WHERE rdh.RECORD_ID = {$checkForHealthAidApplication['RECORD_ID']}
                                         AND rdh.DOC_REF_TYPE = 1";
                            $rows = $crud->getData($query);


                            foreach((array) $rows as $key => $row){   ?>

                                <a href ="EDMS_ViewDocument.php?docId=<?php echo $row['documentId'];?>">

                                    <button type="button" class="btn btn-info"><?php echo $row['title'] ?></button></a>

                                <?php echo $row['statusName'] ?>


                            <?php }?>

                        </div>

                    </div>

                </div>

            </div>


            <div class="row">
                <div class="col-lg-4">

                </div>


                <div class="col-lg-2">
                    <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete">
                        Delete Application
                    </button>

                </div>

                <div class="col-lg-2">
                    <button class="btn btn-success" type="button" data-toggle="modal" data-target="#confirmSubmit">
                        Submit Application
                    </button>
                    <!--                    <input type="submit" name="applyHA" class="btn btn-success" value="Submit Draft">-->

                </div>

                <div class="col-lg-4">

                </div>
            </div>



            <div id="confirmSubmit" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title"> Confirm Submission </h3>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <p>Are you sure you want to submit this application? You can't change the information you have inputted anymore unless
                                    you contact the Secretary. </p>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <div class="form-group">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button class="btn btn-success" type="submit" name="applyHA">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="confirmDelete" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title"> Confirm Submission </h3>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <p>Are you sure you want to delete this application? You will have to fill up a new one afterwards.</p>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <div class="form-group">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button class="btn btn-success" type="submit" name="archiveHA">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>


    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

<?php include 'GLOBAL_FOOTER.php' ?>