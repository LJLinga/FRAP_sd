<?php

    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';
    include('GLOBAL_CLASS_CRUD.php');
    $crud = new GLOBAL_CLASS_CRUD();

    $query = "SELECT * FROM LOANS where MEMBER_ID = {$_SESSION['idnum']}  ORDER BY LOAN_ID DESC LIMIT 1 ";
    $result = mysqli_query($dbc,$query);
    $ans = mysqli_fetch_assoc($result);

    $query = "SELECT l1.APP_STATUS,l2.STATUS as 'Status' FROM LOANS l1 JOIN LOAN_STATUS l2 ON l1.LOAN_STATUS = l2.STATUS_ID where l1.MEMBER_ID = {$_SESSION['idnum']} 
                      ORDER BY loan_id DESC LIMIT 1";
    $result = mysqli_query($dbc,$query);
    $ans1 = mysqli_fetch_assoc($result);


    //checks if user has pending application

    if($ans['LOAN_STATUS'] == 4){

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP reviewapp.php");

    }else if($ans['LOAN_STATUS'] == 6){

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP application.php");

    }

    //check first if the guy is a part time.


    //then check if the guy has currently a pending loan - which brings the dude to the summary page.


    //then check if the guy has paid 50% of the Loan.... shit. we actually have a screen that keeps track of TWO Loans at the fucking same time jesus fucking christ

    $page_title = 'Services - FAP Summary';
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


                <!-- Page Heading -->
                <div class="row">

                    <div class="col-lg-12">

                        <h1 class="page-header">FALP Loan Summary</h1>
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

                </div>

                <div class="row">

                        <div class="col-lg-7">

                            <div class="col-lg-6">

                                <div class="panel panel-default">

                                    <div class="panel-heading">

                                        <b>Current FALP Loan Plan</b>

                                    </div>

                                    <div class="panel-body">

                                    <table class="table table-bordered" style="width: 100%;">

                                    <thread>

                                        <tr>

                                        <td align="center"><b>Description</b></td>
                                        <td align="center"><b>Amount</b></td>

                                        </tr>

                                    </thread>

                                        <tbody>

                                        <tr>

                                            <td>Amount to Borrow</td>
                                            <td>₱ <?php echo $ans['AMOUNT'];?></td>

                                        </tr>

                                        <tr>

                                            <td>Amount Payable</td>
                                            <td>₱ <?php echo $ans['PAYABLE'];?></td>

                                        </tr>

                                        <tr>

                                            <td>Payment Terms</td>
                                            <td><?php echo $ans['PAYMENT_TERMS'];?> months</td>

                                        </tr>

                                        <tr>

                                            <td>Monthly Deduction</td>
                                            <td>₱ <?php echo sprintf("%.2f",(float)$ans['PER_PAYMENT']*2);?></td>

                                        </tr>

                                        <tr>

                                            <td>Number of Payments</td>
                                            <td><?php echo $ans['PAYMENT_TERMS']*2;?> payments</td>

                                        </tr>

                                        <tr>

                                            <td>Per Payment Deduction</td>
                                            <td>₱ <?php echo $ans['PER_PAYMENT'] ;?></td>

                                        </tr>

                                    </table>

                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-6">

                                <div class="panel panel-default">

                                    <div class="panel-heading">

                                        <b>Current FALP Loan Summary</b>

                                    </div>

                                    <div class="panel-body">

                                        <table class="table table-bordered" style="width: 100%;">

                                    <thread>

                                        <tr>

                                        <td align="center"><b>Description</b></td>
                                        <td align="center"><b>Amount</b></td>

                                        </tr>

                                    </thread>

                                            <tbody>

                                            <tr>

                                                <td>Date Approved</td>
                                                <td><?php echo $ans['DATE_APPROVED'];?></td>

                                            </tr>

                                            <tr>

                                                <td>Payments Made</td>
                                                <td><?php echo (int)$ans['PAYMENTS_MADE'];?> Payments</td>

                                            </tr>

                                            <tr>

                                                <td>Payments Left</td>
                                                <td><?php echo ($ans['PAYMENT_TERMS']*2) - $ans['PAYMENTS_MADE'];?> Payments</td>

                                            </tr>

                                            <tr>

                                                <td>Total Amount Paid</td>
                                                <td>₱ <?php echo sprintf("%.2f",(float)$ans['AMOUNT_PAID']);?></td>

                                            </tr>

                                            <tr>

                                                <td>Outstanding Balance</td>
                                                <td>₱ <?php echo sprintf("%.2f",$ans['PAYABLE']-$ans['AMOUNT_PAID']);?></td>

                                            </tr>

                                            <tr>

                                                <td>Status</td>
                                                <td><?php echo $ans1['Status'];?></td>

                                            </tr>

                                            </tbody>

                                    </table>

                                    </div>

                                </div>

                            </div>

                            <?php if($ans['APP_STATUS'] == 3){ // meaning it was rejected?>
                            <div class="row">

                                <div class="col-lg-12">

                                    <h1 class="page-header"><i class="fa fa-border fa-reply"></i> Reply from the Administrator</h1>

                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-primary">

                                        <div class="panel-heading">

                                            <b>Justification from the Administrator</b>

                                        </div>

                                        <div class="panel-body">
                                            <textarea   id="noresize" name="message" class="form-control" rows="5" cols="125" disabled><?php echo $ans['RESPONSE'] ?></textarea>

                                        </div>

                                    </div>

                                </div>
                            </div>
                            <?php } ?>
                        </div>

<!--                        Containsthe Statuses + Receipt-->
                        <div class="col-lg-5">
                            <div class="row">
                                <div class="col-lg-12">

                                    <div class="panel panel-default">

                                        <div class="panel-heading">

                                            <b>View Uploaded Files and their Status</b>

                                        </div>

                                        <div class="panel-body">

                                            <table id="table" name="table" class="table table-bordered table-striped">

                                                <thead>

                                                <tr>

                                                    <td align="center"><b>Document Name</b></td>
                                                    <td align="center"><b>Status</b></td>
                                                    <td align="center"><b>Remarks</b></td>
                                                    <td align="center"><b>View Doc</b></td>

                                                </tr>

                                                </thead>

                                                <tbody>

                                            <?php
                                            //gets the document ids and their
                                            $query = "SELECT d.documentId,d.remarks, d.title, ds.statusName, rdlrq.REQ_TYPE
                                             from ref_document_loans rdl
                                             join documents d 
                                             ON rdl.DOC_ID = d.documentId
                                             join doc_status ds
                                             on d.statusId = ds.id
                                             join ref_document_loans_req_type rdlrq
                                             on rdl.DOC_REQ_TYPE = rdlrq.ID
                                             WHERE rdl.LOAN_ID = {$ans['LOAN_ID']}
                                             AND rdl.DOC_REF_TYPE = 1";
                                                $rows = $crud->getData($query);


                                            foreach((array) $rows as $key => $row){   ?>
                                                <tr>
                                                    <td align='center'> <?php echo $row['REQ_TYPE'] ?></td>
                                                    <td align='center'> <?php echo $row['statusName'] ?></td>
                                                    <td align='center'> <?php echo $row['remarks'] ?></td>
                                                    <td align='center'> <a href ="EDMS_ViewDocument.php?docId=<?php echo $row['documentId'];?>" target="_blank">

                                                            <button type="button" class="btn btn-info" ><i class="fa fa-file" aria-hidden="true"></i> </button></a></td>
                                                </tr>
                                            <?php }?>

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                </div>

                            </div>
<!--                            here is where the uploaded files coming from the Admin will be. dont forgetto spaghetto.-->

                            <?php if($ans['APP_STATUS'] == 2){ ?>
<!--                                this is where we track the pickup status-->

                                <div class="row" style="margin-top:1rem;">
                                    <div class="col-lg-12">
                                        <div class="panel panel-default">

                                            <div class="panel-heading">

                                                <b>Pickup Status for the Loan</b>

                                            </div>

                                            <div class="panel-body">

                                        <?php

                                        $query = "SELECT ps.STATUS
                                            from loans l
                                            join pickup_status ps
                                            on l.PICKUP_STATUS = ps.STATUS_ID
                                            WHERE l.LOAN_ID = {$ans['LOAN_ID']}";

                                        $result = mysqli_query($dbc,$query);
                                        $rows = mysqli_fetch_array($result);


                                        echo $rows['STATUS'];


                                        ?>

                                            </div>

                                    </div>

                                </div>
                                </div>
<!--                            <div class="row" style="margin-top:1rem;">-->
<!--                                <div class="col-lg-12">-->
<!---->
<!---->
<!--                                    <div class="panel panel-default">-->
<!---->
<!--                                        <div class="panel-heading">-->
<!---->
<!--                                            <b>Receipt from the Admin</b>-->
<!---->
<!--                                        </div>-->
<!---->
<!--                                        <div class="panel-body">-->
<!--                                            --><?php
//                                            //gets the document ids and their
//                                            $query = "SELECT d.documentId, d.title, ds.statusName
//                                                 from ref_document_loans rdl
//                                                 join documents d
//                                                 ON rdl.DOC_ID = d.documentId
//                                                 join doc_status ds
//                                                 on d.statusId = ds.id
//                                                 WHERE rdl.LOAN_ID = {$ans['LOAN_ID']}
//                                                 AND rdl.DOC_REF_TYPE = 2";
//                                            $rows = $crud->getData($query);
//
//
//                                            foreach((array) $rows as $key => $row){   ?>
<!---->
<!--                                                <div class="row">-->
<!--                                                    <div class="col-lg-2"></div>-->
<!--                                                    <div class="col-lg-8" align="center">-->
<!--                                                        <a href ="EDMS_ViewDocument.php?docId=--><?php //echo $row['documentId'];?><!--">-->
<!--                                                            <button type="button" class="btn btn-info">View Receipt</button></a>-->
<!--                                                        <br>-->
<!--                                                        <br>-->
<!--                                                    </div>-->
<!--                                                    <div class="col-lg-2"></div>-->
<!---->
<!--                                                </div>-->
<!---->
<!--                                            --><?php //}?>
<!---->
<!--                                        </div>-->
<!---->
<!---->
<!---->
<!--                                    </div>-->
<!---->
<!--                                </div>-->
<!---->
<!---->
<!--                            </div>-->

                            <?php }?>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-12">

                            <div align="center">
<!--                            put an if here that will stop them from accessing the Payment activity UNTIL the shit is accepted.-->
                            <?php if($ans['LOAN_STATUS'] == 2) {?>
                            <a href="MEMBER FALP activity.php" class="btn btn-success" role="button">View Payment Activity</a>
                                <?php } ?>

                            <a href="MEMBER dashboard.php" class="btn btn-default" role="button">Go Back</a>

                            </div>

                        </div>

                    </div>



                </div>

                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
    <?php include 'GLOBAL_FOOTER.php' ?>
