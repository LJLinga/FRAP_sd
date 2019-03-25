
<?php

require_once('mysql_connect_FA.php');
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';
include('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();



$page_title = 'Loans - FALP Application Details';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';


?>
<style>
    #noresize {
        resize: none;
    }

</style>

<div id="page-wrapper">

    <div class="container-fluid">
        <?php
        $query = "SELECT * FROM LOANS where LOAN_ID = {$_SESSION['showFID']} ORDER BY LOAN_ID DESC LIMIT 1";
        $result = mysqli_query($dbc,$query);
        $ans = mysqli_fetch_assoc($result);

        $query = "SELECT l1.APP_STATUS,l2.STATUS as 'Status' FROM LOANS l1 JOIN LOAN_STATUS l2 ON l1.LOAN_STATUS = l2.STATUS_ID where l1.LOAN_ID = {$_SESSION['showFID']}
                    ORDER BY loan_id DESC LIMIT 1";
        $result = mysqli_query($dbc,$query);
        $ans1 = mysqli_fetch_assoc($result);

        $queryMemName = "SELECT M.LASTNAME, M.FIRSTNAME FROM LOANS L
                          join member M
                          on L.MEMBER_ID = M.MEMBER_ID
                         where LOAN_ID = {$_SESSION['showFID']} ORDER BY LOAN_ID DESC LIMIT 1";
        $resultMemName = mysqli_query($dbc,$queryMemName);
        $memName = mysqli_fetch_assoc($resultMemName);


        ?>


        <!-- Page Heading -->
        <div class="row">

            <div class="col-lg-12">

                <h1 class="page-header"><i class="fa fa-border fa-dollar"></i> FALP Loan Summary of <?php echo $memName['LASTNAME'].", ".$memName['FIRSTNAME'] ?></h1>

            </div>

        </div>

            <div class="col-lg-8">

                <div class="col-lg-6">

                    <div class="panel panel-green">

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

                    <div class="panel panel-green">

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



                <?php


                $queryCheckDocs = "SELECT   d.statusId
                                             from ref_document_loans rdl
                                             join documents d 
                                             ON rdl.DOC_ID = d.documentId
                                             join doc_status ds
                                             on d.statusId = ds.id
                                             WHERE  rdl.LOAN_ID = {$_SESSION['showFID']}
                                             AND rdl.DOC_REF_TYPE = 1";

                $resultCheckDocs = mysqli_query($dbc,$queryCheckDocs);


                $numAccepted = 0; //to check if all the files have been accepted. Remember it works both ways. If numAccepted
                $isThereRejected = false;

                while($queryRows = mysqli_fetch_assoc($resultCheckDocs)){

                    if($queryRows['statusId'] == 2){ //check if rejected
                        $numAccepted++;
                    }else if($queryRows['statusId'] == 3){
                        $isThereRejected = true;
                    }
                }

                $checkDocs = mysqli_fetch_array($resultCheckDocs);




                ?>

                <form action="ADMIN_FRAP_FALP_UploadDocument.php" method="POST" enctype="multipart/form-data" >

                    <div class="row">

                        <div class="col-lg-12">

                            <h1 class="page-header"><i class="fa fa-border fa-reply"></i> Your Reply </h1>

                        </div>

                    </div>



                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-primary">

                                <div class="panel-heading">

                                    <b>Justification from the Administrator if Rejected</b>

                                </div>

                                <div class="panel-body">

                                    <?php if($isThereRejected){ // meaning it was rejected?>
                                        <textarea   id="noresize" name="response" class="form-control" rows="5" cols="125" required></textarea>
                                    <?php }else{ ?>
                                        <textarea   id="noresize" name="response" class="form-control" rows="5" cols="125" disabled></textarea>
                                    <?php } ?>
                                </div>

                            </div>

                        </div>
                    </div>

            </div>

            <!--                        Containsthe Statuses + Receipt-->

            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>View Uploaded Files and their Status</b>

                            </div>

                            <div class="panel-body">

                                <table id="table" name="table" class="table table-bordered table-striped">

                                    <thead>

                                    <tr>

                                        <td align="center"><b>Document Name</b></td>
                                        <td align="center"><b>Status</b></td>
                                        <td align="center"><b>Link to Doc</b></td>

                                    </tr>

                                    </thead>

                                    <tbody>

                                    <?php
                                    //gets the document ids and their
                                    $query = "SELECT d.documentId, d.title, ds.statusName, rdlrq.REQ_TYPE
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
                                            <td align='center'> <?php echo $row['REQ_TYPE']; ?></td>
                                            <td align='center'> <?php echo $row['statusName']; ?></td>
                                            <td align='center'> <a href ="EDMS_ViewDocument.php?docId=<?php echo $row['documentId'];?>">

                                                    <button type="button" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i></button></a></td>
                                        </tr>
                                    <?php }?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>
                <!--                            here is where the uploaded files coming from the Admin will be. dont forgetto spaghetto.-->

                    <!--                                this is where we track the pickup status-->
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-primary">

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
                    <?php if($numAccepted == 4){?>
                    <div class="row">
                        <div class="col-lg-12">


                            <div class="panel panel-primary">

                                <div class="panel-heading">

                                    <b>Upload Receipt</b>

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

            </div>
            <?php } ?>
        </div>

        <div class="row">

            <div class="col-lg-12">

                <div align="center">
                    <!--                            put an if here that will stop them from accessing the Payment activity UNTIL the shit is accepted.-->
                    <?php

                    if($isThereRejected){ //check first if there are rejected
                        echo "<input type='submit' class='btn btn-success'  value ='Accept Application' name='accept' disabled>&nbsp&nbsp&nbsp";
                        echo "<input type='submit' class='btn btn-danger'  value ='Reject Application' name='reject'>";

                    }else if($numAccepted == 4){ //then check if the num accepted are all goods

                        echo "<input type='submit' class='btn btn-success'  value ='Accept Application' name='accept'>&nbsp&nbsp&nbsp";
                        echo "<input type='submit' class='btn btn-danger'  value ='Reject Application' name='reject'>";

                    }else{ //if none, then disable both buttons.

                        echo "<input type='submit' class='btn btn-success' name='accept' value ='Accept Application' disabled>&nbsp&nbsp&nbsp";
                        echo "<input type='submit' class='btn btn-danger' name='reject' value ='Reject Application' disabled>";

                    }


                    ?>
                </div>

            </div>

        </div>
        </form>


    </div>

    <!-- /.row -->

</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
<?php include 'GLOBAL_FOOTER.php' ?>
