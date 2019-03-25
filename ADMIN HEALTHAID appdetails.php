<?php

require_once ("mysql_connect_FA.php");
session_start();
include('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
include 'GLOBAL_USER_TYPE_CHECKING.php';


 
        $queryHA = "SELECT * FROM health_aid  WHERE RECORD_ID = {$_SESSION['showHAID']}";
        $result = mysqli_query($dbc, $queryHA);
        $checkForHealthAidApplication = mysqli_fetch_array($result);

        $queryNameOfMember = "SELECT FIRSTNAME, LASTNAME from member where MEMBER_ID = {$checkForHealthAidApplication['MEMBER_ID']}";
        $resultNameOfMember = mysqli_query($dbc, $queryNameOfMember);
        $nameOfApplicant = mysqli_fetch_array($resultNameOfMember);

        


$page_title = 'Loans - Health Aid Application';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';

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

                <h1 class="page-header"><i class="fa fa-plus fa-border"></i> Health Aid Application Summary of <?php echo $nameOfApplicant['FIRSTNAME']." ".$nameOfApplicant['LASTNAME']?></h1>

            </div>

            <div class = "col-lg-12">

                <label> Please review the details below and check the receipt that was uploaded by the member.  </label>

            </div>

        </div>

        <div class = "row" style="margin-top: 5px;">

            <div class="col-lg-8">

                <div class="panel panel-green">

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

                <div class="panel panel-green">

                    <div class="panel-heading">

                        <b>View Uploaded Files and their Status</b>

                    </div>

                    <div class="panel-body">

                        <?php
                        //gets the document ids and their
                        $query = "SELECT d.documentId, dv.title, d.statusId,ds.statusName
                                         from ref_document_healthaid rdh
                                         join documents d 
                                         ON rdh.DOC_ID = d.documentId
                                         join doc_versions dv
                                         on d.documentId = dv.documentId
                                         join doc_status ds
                                         on d.statusId = ds.id
                                         WHERE rdh.RECORD_ID = {$checkForHealthAidApplication['RECORD_ID']}
                                         AND rdh.DOC_REF_TYPE = 1";
                        $rows = $crud->getData($query);


                        foreach((array) $rows as $key => $row){   ?>

                            <a href ="EDMS_ViewDocument.php?docId=<?php echo $row['documentId'];?>">

                                <button type="button" class="btn btn-success"><?php echo $row['title'] ?></button></a>

                            <?php echo $row['statusName'] ?>


                        <?php }?>

                    </div>

                </div>

            </div>

        </div>

        <?php
        //this is for checking if the amount of documents is all goods

        $queryCheckDocs = "SELECT d.statusId
                                             from ref_document_healthaid rdh
                                             join documents d 
                                             ON rdh.DOC_ID = d.documentId
                                             join doc_versions dv
                                             on d.documentId = dv.documentId
                                             join doc_status ds
                                             on d.statusId = ds.id
                                             WHERE rdh.RECORD_ID = {$checkForHealthAidApplication['RECORD_ID']}
                                             AND rdh.DOC_REF_TYPE = 1";

        $resultCheckDocs = mysqli_query($dbc,$queryCheckDocs);




        $numAccepted = 0; //to check if all the files have been accepted. Remember it works both ways. If numAccepted
        $isThereRejected = false;


        while($row = mysqli_fetch_assoc($resultCheckDocs)){

            if($row['statusId'] == 2){ //check if rejected
                $numAccepted++;
            }else if($row['statusId'] == 3){
                $isThereRejected = true;
            }
        }

        $checkDocs = mysqli_fetch_array($resultCheckDocs);



        $queryGetNumReceipts = "SELECT COUNT (DOC_ID) as 'NUMDOCS'
                                             from ref_document_healthaid rdh
                                             join documents d 
                                             ON rdh.DOC_ID = d.documentId
                                             join doc_versions dv
                                             on d.documentId = dv.documentId
                                             join doc_status ds
                                             on d.statusId = ds.id
                                             WHERE rdh.RECORD_ID = {$checkForHealthAidApplication['RECORD_ID']}
                                             AND rdh.DOC_REF_TYPE = 1";
        $resultGetNumReceiptss = mysqli_query($dbc,$queryGetNumReceipts);
        $checkReceipts = mysqli_fetch_array($resultCheckDocs);




        ?>

        <form action="ADMIN_FRAP_HA_UploadDocument.php" method="POST" enctype="multipart/form-data" >
            <div class="col-lg-12">

                <h3 class="page-header"><i class="fa fa-reply fa-border"></i> Your Response to the Application </h3>

            </div>

            <div class = "col-lg-12">
                <label> Please type your response here and do not forget to upload the receipt for the application if it is approved.  </label>

            </div>



            <div class="row">

                <div class="col-lg-8">

                    <div class="panel panel-primary">

                        <div class="panel-heading">

                            <b> Response Details </b>

                        </div>


                        <div class="panel-body">

                            <div class="form-group">

                                <label for="usr">Amount to Give: </label>

                                <?php if($isThereRejected){ ?>

                                <input type="number" name="amount_to_give"  class="form-control" id="usr" size="5" style="width:250px;">

                                <?php }else if($checkForHealthAidApplication['APP_STATUS'] == 2 ){ ?>

                                    <input type="number" placeholder="<?php echo $checkForHealthAidApplication['AMOUNT_GIVEN']?>" name="amount_to_give"  class="form-control" id="usr" size="5" style="width:250px;" disabled>

                               <?php }else { ?>

                                    <input type="number" name="amount_to_give"  class="form-control" id="usr" size="5" style="width:250px;" required>

                                <?php }?>
                            </div>

                            <div class="form-group">

                                <label> Justification for the Amount Given: </label>
                                <?php if($checkForHealthAidApplication['APP_STATUS'] == 2 ){ ?>

                                <textarea   id="noresize" placeholder="<?php echo $checkForHealthAidApplication['RESPONSE'] ?>" name="response" class="form-control" rows="5" cols="125" disabled></textarea>

                                <?php }else { ?>

                                    <textarea   id="noresize" name="response" class="form-control" rows="5" cols="125" required></textarea>

                                <?php }?>

                            </div>

                        </div>

                    </div>


                    <!-- Check first if the doc status has been accepted. check if the Doc status = 2 - accepted. But you have to be dynamic. Check everything.-->
                    <!--  Btw, when there is even but one receipt that has been rejected, then you enable the reject button. You gotta hve the count. -->


                    <?php

                        //these are the sets of buttons that you must be able to display.
                        if($checkForHealthAidApplication['APP_STATUS'] == 2 || $checkForHealthAidApplication['APP_STATUS'] == 3 ){

                            echo "<input type='submit' class='btn btn-success' name='accept' value ='Accept Application' disabled>&nbsp&nbsp&nbsp";
                            echo "<input type='submit' class='btn btn-danger' name='reject' value ='Reject Application' disabled>";


                        } else if($isThereRejected){ //check first if there are rejected
                            echo "<input type='submit' class='btn btn-success'  value ='Accept Application' name='accept' disabled>&nbsp&nbsp&nbsp";
                            echo "<input type='submit' class='btn btn-danger'  value ='Reject Application' name='reject'>";

                        }else if($checkReceipts['NUMDOCS'] = $numAccepted){ //then check if the num accepted are all goods

                            echo "<input type='submit' class='btn btn-success'  value ='Accept Application' name='accept'>&nbsp&nbsp&nbsp";
                            echo "<input type='submit' class='btn btn-danger'  value ='Reject Application' name='reject'>";

                        }else{ //if none, then disable both buttons.

                            echo "<input type='submit' class='btn btn-success' name='accept' value ='Accept Application' disabled>&nbsp&nbsp&nbsp";
                            echo "<input type='submit' class='btn btn-danger' name='reject' value ='Reject Application' disabled>";

                        }

                    ?>
                </div>
                <div class= "col-lg-4">

                    <div class="panel panel-primary">

                        <div class="panel-heading">

                            <b>Pickup Status</b>

                        </div>

                        <div class="panel-body">
                            <div class ="row">
                                <div class= "col-lg-12">
                                    <?php

                                    $query = "SELECT ps.STATUS
                                            from health_aid ha
                                            join pickup_status ps
                                            on ha.PICKED_UP_STATUS = ps.STATUS_ID
                                            WHERE ha.RECORD_ID = {$checkForHealthAidApplication['RECORD_ID']}";

                                    $result = mysqli_query($dbc,$query);
                                    $rows = mysqli_fetch_array($result);


                                    echo $rows['STATUS'];


                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(!$isThereRejected){?>
                        <div class="panel panel-primary">

                            <div class="panel-heading">

                                <b>Upload Receipt for Health Aid</b>

                            </div>

                            <div class="panel-body">

                                <div class ="row">

                                    <div class= "col-lg-12">



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

                        </div>
                    <?php }?>


                </form>
                </div>


            </div>


    </div>

</div>


</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

<?php include 'GLOBAL_FOOTER.php' ?>
