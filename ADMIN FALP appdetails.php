
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


        /**
         * Heres my Message to you Christian!
         * -----FOR MEMBERS----
         * So right now everything is contained by the member itself, most of the statuses and all.
         * If you want to know what department a dood is from, get the DEPT_ID, join with ref_department id.
         *
         * -----FOR HEALTH AID----
         * In order for you to know which are the eligible members or members that actually applied for health aid, you
         * would need to go back to the member table. The member table contains two columns named HA_STATUS and HA_TIMESTAMP.
         * HA_STATUS are basically just 1 and 2. 1 - not applied, 2 - applied.  HA_TIMESTAMP is needed because of the
         * 1 year for health aid thing.
         *
         * For the Health Aid Table itself,
         *
         * Then you just check the Health Aid table where the relevant details are there, Date Applied(for Acquisition),
         * Amount Requested, and so on and so forth. Just take a look at the table and jsut tell me if you have any questions.
         *
         * ----FOR FAP--------
         * Important details here:
         *Member ID - you know what this is for
         *  Amount - how much these d00ds loan
         *  Payment Terms - How much months will they get deducted for this loan.
         *  Per Payment - How much is deducted per pay period (x2 this value if you want to get monthly deduction)
         *  Loan Status - To see the status of the loan (1 - Pending, 2 - Active, 3 - Matured, 4- Draft, 5- For Reupload, 6 - Archived)
         *  Date Applied and Approved - Self Explanatory
         *
         * -----FOR TRANSACTIONS----------
         * This will be the bread and butter of the table I guess? Because the transactions table is where the deductions
         * are recorded and the applications that gets passed around, morely for notifying and all.
         *
         * So the important details are there, the
         * MEMBER_ID(The target destination for this transaction),
         * TXN_TYPE (The Transaction Type, which the Types are 1 - Application, 2 - Deduction(from services), 3 - Consent for deduction
         *  for health aid and 4 - Notification.) I think the more important parts here are 1 and 2 transaction types?,
         * TXN_DATE(When did this transaction happen),
         * LOAN_REF - if it is Loans, the Loan ID should be here, if not, this should be blank.
         *  HA_REF - If its health aid, the record ID should be here, if not health aid, this is blank.
         *  SERVICE ID - Basically to know what service is this transaction is. ( 1- Membership, 2  - HA, 3 - Bank Loan(defunct),
         * 4 - FAP loan, 5 - System Notif, and 6 - LIfetime.
         *
         *
         */
//
//        $queryGetTheDeets = "SELECT COUNT(m.MEMBER_ID) as 'Members Applied', COUNT(l.LOAN_ID) as 'Loans Applied for'
//                             from member m
//                             join ref_department d
//                             on m.DEPT_ID = d.DEPT_ID
//                             join loans l
//                             on l.MEMBER_ID = m.MEMBER_ID
//                             WHERE m.USER_STATUS = 1;";  /** Here, you insert the filter that you need, date range, department, etc. */
//                            /** ^ What this query means is that it */

        ?>


        <!-- Page Heading -->
        <div class="row">

            <div class="col-lg-12">

                <h1 class="page-header">FALP Loan Summary of <?php echo $memName['LASTNAME'].", ".$memName['FIRSTNAME'] ?></h1>

            </div>

        </div>

            <div class="col-lg-6">

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
                                    <td>₱ <?php echo number_format($ans['AMOUNT'],2)."<br>";?></td>

                                </tr>

                                <tr>

                                    <td>Amount Payable</td>
                                    <td>₱ <?php echo number_format($ans['PAYABLE'],2)."<br>";?></td>

                                </tr>

                                <tr>

                                    <td>Payment Terms</td>
                                    <td><?php echo $ans['PAYMENT_TERMS'];?> months</td>

                                </tr>

                                <tr>

                                    <td>Monthly Deduction</td>
                                    <td>₱ <?php echo number_format($ans['PER_PAYMENT']*2,2)."<br>";?></td>

                                </tr>

                                <tr>

                                    <td>Number of Payments</td>
                                    <td><?php echo $ans['PAYMENT_TERMS']*2;?> payments</td>

                                </tr>

                                <tr>

                                    <td>Per Payment Deduction</td>
                                    <td>₱ <?php echo number_format($ans['PER_PAYMENT'],2)."<br>";?></td>

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
                                    <td>₱ <?php echo number_format($ans['AMOUNT_PAID'],2)."<br>";?></td>

                                </tr>

                                <tr>

                                    <td>Outstanding Balance</td>
                                    <td>₱ <?php echo number_format($ans['PAYABLE']-$ans['AMOUNT_PAID'],2)."<br>";?></td>

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

                    if($queryRows['statusId'] == 3){ //check if rejected
                        $numAccepted++;
                    }else if($queryRows['statusId'] == 4){
                        $isThereRejected = true;
                    }
                }

                $checkDocs = mysqli_fetch_array($resultCheckDocs);




                ?>

                <form action="ADMIN_FRAP_FALP_UploadDocument.php" method="POST" enctype="multipart/form-data" >

                    <div class="row">

                        <div class="col-lg-12">

                            <h1 class="page-header"> Your Reply </h1>

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

            <div class="col-lg-6">
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
                                        <td align="center"><b>Remarks</b></td>
                                        <td align="center"><b>Link to Doc</b></td>

                                    </tr>

                                    </thead>

                                    <tbody>

                                    <?php
                                    //gets the document ids and their
                                    $query = "SELECT d.documentId, d.title,d.remarks, ds.statusName, rdlrq.REQ_TYPE
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
                                            <td align='center'> <?php echo $row['remarks']; ?></td>
                                            <td align='center'> <a href ="EDMS_ViewDocument.php?docId=<?php echo $row['documentId'];?>" target="_blank">

                                                    <button type="button" class="btn btn-success"><i class="fa fa-file" aria-hidden="true"></i></button></a></td>
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
<!--                    --><?php //if($numAccepted == 4){?>
<!--                    <div class="row">-->
<!--                        <div class="col-lg-12">-->
<!---->
<!---->
<!--                            <div class="panel panel-primary">-->
<!---->
<!--                                <div class="panel-heading">-->
<!---->
<!--                                    <b>Upload Receipt</b>-->
<!---->
<!--                                </div>-->
<!---->
<!--                                <div class="panel-body">-->
<!---->
<!--                                <div class ="row">-->
<!---->
<!--                                    <div class= "col-lg-12">-->
<!---->
<!---->
<!---->
<!--                                        <div class="element">-->
<!--                                            <input type="file" name="upload_file[]" id="upload_file1" required/>-->
<!--                                        </div>-->
<!---->
<!--                                        <div id="moreImageUpload">-->
<!--                                            <br>-->
<!--                                        </div>-->
<!---->
<!--                                        <div class="clear">-->
<!---->
<!--                                        </div>-->
<!---->
<!--                                        <div id="moreImageUploadLink" style="display:none;margin-left: 10px;">-->
<!--                                            <i class="fa fa-plus"></i>   <a href="javascript:void(0);" id="attachMore">Attach another file</a>-->
<!--                                        </div>-->
<!---->
<!---->
<!---->
<!---->
<!---->
<!--                                    </div>-->
<!---->
<!--                                </div>-->
<!---->
<!---->
<!--                            </div>-->
<!---->
<!--                        </div>-->
<!---->
<!---->
<!--                    </div>-->
<!---->
<!--            </div>-->
<!--            --><?php //} ?>
        </div>

        <div class="row">

            <div class="col-lg-12">

                <div align="center">
                    <!--                            put an if here that will stop them from accessing the Payment activity UNTIL the shit is accepted.-->
                    <?php

                    if($ans['APP_STATUS'] == 1 || $ans['APP_STATUS'] == 5){


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
