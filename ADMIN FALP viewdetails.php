<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}
require_once("mysql_connect_FA.php");

$page_title = 'Loans - View Details';
include 'GLOBAL_TEMPLATE_Header.php';
include 'LOAN_TEMPLATE_NAVIGATION_Membership.php';
?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">
                        <?php
                            $query2 = "SELECT m.firstname as 'First',m.lastname as 'Last' FROM LOANS l join member m on l.member_id = m.member_id where LOAN_ID = {$_POST['details']} 
                                                  AND loan_detail_id = 1 AND    loan_status != 3";
                                        $result2 = mysqli_query($dbc,$query2);
                                        $ans2 = mysqli_fetch_assoc($result2);

                        ?>
                        <h1 class="page-header"><?php echo $ans2['First']." ".$ans2['Last'];?> 's FALP Loan Summary</h1>
                    
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
                                    <?php
                                        $query = "SELECT * FROM LOANS where LOAN_ID = {$_POST['details']} 
                                                  AND loan_detail_id = 1 AND    loan_status != 3";
                                        $result = mysqli_query($dbc,$query);
                                        $ans = mysqli_fetch_assoc($result);

                                        $query1 = "SELECT l2.STATUS as 'Status' FROM LOANS l1 JOIN LOAN_STATUS l2 ON l1.LOAN_STATUS = l2.STATUS_ID where l1.LOAN_ID = {$_POST['details']} 
                                                  AND l1.loan_detail_id = 1 AND     l1.loan_status != 3";
                                        $result = mysqli_query($dbc,$query1);
                                        $ans1 = mysqli_fetch_assoc($result);
                                    ?>
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
                                    <td>₱ <?php echo $ans['PER_PAYMENT'];?></td>

                                    </tr>

                                    <tr>

                                    <td>Number of Payments</td>
                                    <td><?php echo $ans['PAYMENT_TERMS']*2;?> payments</td>

                                    </tr>

                                    <tr>

                                    <td>Per Payment Deduction</td>
                                    <td>₱ <?php echo $ans['PER_PAYMENT'];?></td>

                                    </tr>

                                </tbody>

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
                                    <td><?php echo $ans['PAYMENT_TERMS'] - $ans['PAYMENTS_MADE'];?> Payments</td>

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
                            <form action = "ADMIN FALP viewactivity.php" method = "POST">
                            <button type = "submit" class="btn btn-success" role="button" value = <?php echo $_POST['details']?> name = "details" >View Payment Activity</button>

                            <a href="ADMIN dashboard.php" class="btn btn-default" role="button">Go Back</a>
                            </form>
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

    </div>
    <!-- /#wrapper -->

</body>

</html>
