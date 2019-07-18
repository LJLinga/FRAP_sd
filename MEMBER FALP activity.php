<?php
    require_once ("mysql_connect_FA.php");
    session_start();
    error_reporting(0);
    include 'GLOBAL_USER_TYPE_CHECKING.php';


    $query = "SELECT * from loans where member_id = {$_SESSION['idnum']} && loan_status != 3 && loan_status != 4 && app_status != 3 ORDER BY LOAN_ID DESC LIMIT 1";
    $result = mysqli_query($dbc,$query);
    $row = mysqli_fetch_assoc($result);

    $queryIfPartTimeLoaned = "SELECT PART_TIME_LOANED from member  where member_id = {$_SESSION['idnum']} ";
    $resultIfPartTimeLoaned  = mysqli_query($dbc,$queryIfPartTimeLoaned );
    $ifPartTimeLoaned= mysqli_fetch_assoc($resultIfPartTimeLoaned);

    if(!empty($row)){

        if($row['LOAN_STATUS'] == 1){ //checks if you have a pending loan

            header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP summary.php");

        } else if($row['LOAN_STATUS'] == 2 ) { //checks if you have a loan that is ongoing.

//            if (($row['PAYMENT_TERMS']/2) > $row['PAYMENTS_MADE']){ //checks if the loan is 50%
//
//
//                $_SESSION['GLOBAL_MESSAGE'] = ' Your Loan has not been paid to 50% yet.  ';
//
//                header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP summary.php");
//
//
//            }

        }

    }

    if($ifPartTimeLoaned['PART_TIME_LOANED'] == "YES"){

        $_SESSION['GLOBAL_MESSAGE'] = ' You cannot loan again for this month. Please wait for another term before you can loan again. ';

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP summary.php");

    }


    
    $query = "SELECT * FROM LOANS where MEMBER_ID = {$_SESSION['idnum']} ORDER  BY LOAN_ID DESC LIMIT 1";
    $result = mysqli_query($dbc,$query);
    $ans = mysqli_fetch_assoc($result);

    $query2 = "SELECT * FROM txn_reference where LOAN_REF = {$ans['LOAN_ID']} AND txn_type = 2";
    $result2 = mysqli_query($dbc,$query2);

    $page_title = 'Loans - FALP Activity';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_USER_SIDEBAR.php';
?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">FALP Loan Activity</h1>
                    
                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="panel panel-default">

                            <div class="panel-heading">

                                <b>FALP Loan Payment Activity</b>

                            </div>

                            <div class="panel-body">

                                <table id = "table" class="table table-bordered">

                                    <thread>

                                        <tr>

                                            <td align="center"><b>Date</b></td>
                                            <td align="center"><b>Deducted Amount</b></td>
                                            <td align="center"><b>Status</b></td>

                                        </tr>

                                    </thread>

                                    <tbody>

                                    <?php


                                    while($ans2 = mysqli_fetch_assoc($result2)){
                                        $dt = new DateTime($ans2['TXN_DATE']);
                                        $date = $dt->format('d/m/Y');
                                        $amount = $ans2['AMOUNT'];


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

                            <a href="MEMBER FALP summary.php" class="btn btn-default" role="button">Go Back</a>

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
