<?php

    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';

    $query = "SELECT MAX(LOAN_ID), LOAN_STATUS from loans where member_id = {$_SESSION['idnum']} ";
    $result = mysqli_query($dbc,$query);

    $row = mysqli_fetch_assoc($result);

    if($row['LOAN_STATUS'] == 1){ //checks if you have a pending loan

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP failed.php");

    }

    $query = "SELECT * FROM LOANS where MEMBER_ID = {$_SESSION['idnum']} 
              AND 	loan_status != 3 ORDER BY loan_id DESC LIMIT 1";
    $result = mysqli_query($dbc,$query);
    $ans = mysqli_fetch_assoc($result);

    $query = "SELECT l2.STATUS as 'Status' FROM LOANS l1 JOIN LOAN_STATUS l2 ON l1.LOAN_STATUS = l2.STATUS_ID where l1.MEMBER_ID = {$_SESSION['idnum']} 
              AND 	l1.loan_status != 3  ORDER BY loan_id DESC LIMIT 1";
    $result = mysqli_query($dbc,$query);
    $ans1 = mysqli_fetch_assoc($result);

    $page_title = 'Loans - FALP Summary';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_USER_SIDEBAR.php';
?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">FALP Loan Summary</h1>
                    
                    </div>

                </div>

                    <div class="row">

                        <div class="col-lg-6">

                            <div class="panel panel-primary">

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

                    </div>



                    <div class="row">

                        <div class="col-lg-12">

                            <div align="center">

                            <a href="MEMBER FALP activity.php" class="btn btn-success" role="button">View Payment Activity</a>
                            <a href="MEMBER dashboard.php" class="btn btn-default" role="button">Go Back</a>

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-12">

                            &nbsp;

                        </div>

                    </div>

                </div>

                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
    <?php include 'GLOBAL_FOOTER.php' ?>
