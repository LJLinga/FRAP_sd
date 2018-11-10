<?php

    error_reporting(null);
    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';

    $page_title = 'Loans - FALP Application Failed';
    include 'GLOBAL_TEMPLATE_Header.php';
    include 'LOAN_TEMPLATE_NAVIGATION_Member.php';
?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">

                        <br>

                        <br>

                        <div class="well" align="center">
                            <?php
                                $query = "SELECT MAX(LOAN_ID), AMOUNT_PAID, LOAN_STATUS FROM MEMBER WHERE MEMBER_ID =" . $_SESSION['idnum'].";";

                                $result = mysqli_query($dbc, $query);
                                $row = mysqli_fetch_array($result);

                            ?>

                        <h1 class="page-header">

                            <?php
                                if($row['LOAN_STATUS'] = 1) { //checks if his loan is still pending
                                    ?>
                                    Please wait for your loan to get approved, as the admins are still reviewing it!


                                    <?php
                                }else{
                            ?>

                                Not eligible for another FALP


                                Still not yet paid 50% of the previous amount(Currently
                                paid <?php echo sprintf('%0.2f', $row['AMOUNT_PAID']) * 100; ?> % )</h1>

                                <br>

                                Please wait until you have paid 50% before you can apply for another loan. Thank you!

                                <br>

                                <br>
                            <?php
                                }
                            ?>

                            <br>

                            <br>




                            <a href="MEMBER dashboard.php" class="btn btn-default" role="button">Go Back</a>

                        </div>
                    
                    </div>

                </div>

                



                <hr>

               

                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

<?php include 'GLOBAL_TEMPLATE_Footer.php' ?>
