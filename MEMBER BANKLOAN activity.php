<?php

    session_start();
    require_once("mysql_connect_FA.php");

    if ($_SESSION['usertype'] != 1) {

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
        
    }

    $query = "select TXN_DATE, AMOUNT, TXN_DESC from txn_reference 
            where  LOAN_REF  = '".$_SESSION['loanID']."' && TXN_TYPE = 2";

    $result= mysqli_query($dbc,$query);

$page_title = 'Loans - Bank Activity';
include 'GLOBAL_HEADER.php';
include 'LOAN_TEMPLATE_NAVIGATION_Member.php';
?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Bank Loan Activity</h1>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-green">
                                <div class="panel-heading">
                                    <b>Bank Loan Payment Activity</b>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-bordered">
                                        <thread>
                                            <tr>
                                            <td align="center"><b>Date</b></td>
                                            <td align="center"><b>Deducted Amount</b></td>
                                            <td align="center"><b>Status</b></td>
                                            </tr>
                                        </thread>
                                        <tbody>
                                           <?php
                                                while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                                $dt = new DateTime($row['TXN_DATE']);
                                                $date = $dt->format('d/m/Y');
                                                $amount = $row['AMOUNT'];
                                                $status = $row['TXN_DESC'];
                                                ?>
                                                <tr>
                                                <td align="center"><?php echo $date;?></td>
                                                <td align="center">₱ <?php echo $amount;?></td>
                                                <td align="center"><?php echo $status;?></td>
                                                </tr>
                                            <?php } ?>

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div align="center">
                            <a href="MEMBER BANKLOAN summary.php" class="btn btn-default" role="button">Go Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->
<?php include 'GLOBAL_FOOTER.php' ?>
