<?php
    session_start();
    require_once('mysql_connect_FA.php');
    if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

    header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

    }
    

    $bank_loan_id = $_SESSION['bank_loan_id'];

    $query1 = "select m.member_id,m.firstname, m.lastname, l.amount, l.payable, l.payment_terms, l.per_payment, l.date_approved, l.payments_made, l.amount_paid, ls.status
    from loans l
    join member m
    on l.member_id = m.member_id
    join loan_status ls
    on l.loan_status = ls.status_id
    where l.loan_id ={$bank_loan_id}" ;
            
    $result1 = mysqli_query($dbc,$query1);

    $loan_info = mysqli_fetch_array($result1 ,MYSQLI_ASSOC); // use this when referring to the personal information of the person

$page_title = 'Loans - View Bank Details';
include 'GLOBAL_HEADER.php';
include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';
?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header"><?php echo $loan_info['firstname']." ".$loan_info['lastname'] ?> 's Bank Loan Summary</h1>
                    
                    </div>

                </div>

                    <div class="row">

                        <div class="col-lg-6">

                            <div class="panel panel-primary">

                                <div class="panel-heading">

                                    <b>Current Bank Loan Plan</b>

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
                                    <td><?php echo $loan_info['amount']; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Amount Payable</td>
                                    <td><?php echo $loan_info['payable']; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Payment Terms</td>
                                    <td><?php echo $loan_info['payment_terms']; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Monthly Deduction</td>
                                    <td><?php echo $loan_info['per_payment']*2; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Number of Payments</td>
                                    <td><?php echo $loan_info['payment_terms']*2; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Per Payment Deduction</td>
                                    <td><?php echo $loan_info['per_payment']; ?></td>

                                    </tr>

                                </tbody>

                                </table>

                                </div>

                            </div>

                        </div>

                        <div class="col-lg-6">

                            <div class="panel panel-green">

                                <div class="panel-heading">

                                    <b>Current Bank Loan Summary</b>

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
                                    <td><?php echo DATE($loan_info['date_approved']); ?></td>

                                    </tr>

                                    <tr>

                                    <td>Payments Made</td>
                                    <td><?php echo $loan_info['payments_made']; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Payments Left</td>
                                    <td><?php echo $loan_info['payment_terms']*2-$loan_info['payments_made']; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Total Amount Paid</td>
                                    <td><?php echo $loan_info['amount_paid']; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Outstanding Balance</td>
                                    <td><?php echo $loan_info['amount']-$loan_info['amount_paid']; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Status</td>
                                    <td><?php echo $loan_info['status']; ?></td>

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

                            <a href="ADMIN BANK viewactivity.php" class="btn btn-success" role="button">View Payment Activity</a>
                            <a href="ADMIN viewactive.php" class="btn btn-default" role="button">Go Back</a>

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

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
