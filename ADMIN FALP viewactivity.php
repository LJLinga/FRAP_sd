<?php
session_start();
if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}
require_once('mysql_connect_FA.php');
$query = "SELECT * FROM LOANS 
          where LOAN_ID = {$_POST['details']} 
          AND loan_detail_id = 1 AND    loan_status != 3";
$result = mysqli_query($dbc,$query);
$ans = mysqli_fetch_assoc($result);

$query1 = "SELECT TXN_DATE,SUM(AMOUNT) as 'AMOUNT' FROM txn_reference where LOAN_REF ={$ans['LOAN_ID']} AND txn_type = 2 AND SERVICE_TYPE = 3 group by TXN_DATE";
$result = mysqli_query($dbc,$query1);

$page_title = 'Loans - View Activity';
include 'GLOBAL_TEMPLATE_Header.php';
include 'LOAN_TEMPLATE_NAVIGATION_Membership.php';
?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">FALP Loan Activity<?php echo $query; ?></h1>
                    
                    </div>

                </div>

                    <div class="row">

                        <div class="col-lg-12">

                            <div class="panel panel-green">

                                <div class="panel-heading">

                                    <b>FALP Loan Payment Activity</b>

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
                                            
                                            
                                            while($ans= mysqli_fetch_assoc($result)){
                                            $dt = new DateTime($ans['TXN_DATE']);
                                            $date = $dt->format('d/m/Y');
                                            $amount = $ans['AMOUNT'];
                                            $status = "Complete";
                                            
                                            ?>
                                            <tr>
                                            
                                            <td align="center"><?php echo $date;?></td>
                                            <td align="center">₱ <?php echo $amount;?></td>
                                            <td align="center">Completed</td>
                                            
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

                            <a href="ADMIN FALP viewdetails.php" class="btn btn-default" role="button">Go Back</a>

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
