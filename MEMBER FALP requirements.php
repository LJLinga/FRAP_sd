<?php

    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';

    //check if the person has anongoing  loan first dammit then if he/she has, redirect to a pending. but if its accepted, redirect to activity
    //to check if the user has applied in FALP but this code can be edited to check other applications. e.g. Health aid and shi like th sort
    $query = "SELECT * from loans where member_id = {$_SESSION['idnum']} && loan_status != 3 && app_status != 3 ORDER BY LOAN_ID DESC LIMIT 1";
    $result = mysqli_query($dbc,$query);
    $row = mysqli_fetch_assoc($result);

    $queryIfPartTimeLoaned = "SELECT PART_TIME_LOANED from member  where member_id = {$_SESSION['idnum']} ";
    $resultIfPartTimeLoaned  = mysqli_query($dbc,$queryIfPartTimeLoaned );
    $ifPartTimeLoaned= mysqli_fetch_assoc($resultIfPartTimeLoaned);


    //check first if the guy is a part time.


    //then check if the guy has currently a pending loan - which brings the dude to the summary page.


    //then check if the guy has paid 50% of the Loan.... shit. we actually have a screen that keeps track of TWO Loans at the fucking same time jesus fucking christ




    if(!empty($row)){

            if($row['LOAN_STATUS'] == 1){ //checks if you have a pending loan

                header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP summary.php");

            } else if($row['LOAN_STATUS'] == 2 ) { //checks if you have a loan that is ongoing.

                if ($row['PAYMENT_TERMS'] > $row['PAYMENTS_MADE']){ //checks if the loan is 50%

                    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP summary.php");

                }

            }

    }

    if($ifPartTimeLoaned['PART_TIME_LOANED'] == "YES"){

        $_SESSION['GLOBAL_MESSAGE'] = ' You cannot loan again for this month. Please wait for another term before you can loan again. ';

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP summary.php");

    }


    $page_title = 'Loans - FALP Requirements';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_USER_SIDEBAR.php';
?>

        <div id="page-wrapper">

            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">FALP Application</h1>
                    
                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="panel panel-primary">

                            <div class="panel-heading">

                                <b>APPLICATION REQUIREMENTS</b>

                            </div>

                            <div class="panel-body">

                            These are the current requirements for getting a FALP loan. You may upload a scanned PDF version or other.

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <form action="MEMBER_UploadDocument_Loans.php" method = "post" enctype="multipart/form-data" >  <!-- SERVERSELF, REDIRECT TO NEXT PAGE -->

                        <div class="col-lg-3 col-1">

                            <div class="panel panel-green" align="center">

                                <div class="panel-heading">

                                    <b>Income Tax Return</b>

                                </div>

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-2">

                                        </div>

                                        <div class="col-lg-10">

                                            <input type="file" accept = ".jpeg, .jpg, .png, .pdf, .doc, .docx" name = "upload_file[]" id = "IncomeTax" required>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-lg-3 col-2">

                            <div class="panel panel-green" align="center">

                                <div class="panel-heading">

                                    <b>Payslip (current month)</b>

                                </div>

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-2">

                                        </div>

                                        <div class="col-lg-10">

                                            <input type="file" accept = ".jpeg, .jpg, .png, .pdf, .doc, .docx" name = "upload_file[]" id="payslip" required>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-lg-3 col-3">

                            <div class="panel panel-green" align="center">

                                <div class="panel-heading">

                                    <b>Employee ID</b>

                                </div>

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-2">

                                        </div>

                                        <div class="col-lg-10">

                                            <input type="file" accept = ".jpeg, .jpg, .png, .pdf, .doc, .docx" name = "upload_file[]" id = "emp_ID" required>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-lg-3 col-4">

                            <div class="panel panel-green" align="center">

                                <div class="panel-heading">

                                    <b>Government ID</b>

                                </div>

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-2">

                                        </div>

                                        <div class="col-lg-10">

                                            <input type="file" accept = ".jpeg, .jpg, .png, .pdf, .doc, .docx" name = "upload_file[]" id = "gov_ID" required>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                </div>

                <hr>



                    

                    <div class="row">

                        <div class="col-lg-3">


                        </div>

                        <div class="col-lg-6">

                            <table class="table table-bordered">
                            
                            <thread>

                                <tr>

                                <td align="center"><b>Description</b></td>
                                <td align="center"><b>Amount</b></td>

                                </tr>

                            </thread>

                            <tbody>

                                <tr>

                                <td><b>Amount to Borrow</td>
                                <td>₱ <?php echo $_POST['amount'];?></td>

                                </tr>

                                <tr>

                                <td><b>Amount Payable</td>
                                <td>₱ <?php echo $_POST['amount']+500;?></td>

                                </tr>

                                <tr>

                                <td><b>Payment Terms</td>
                                <td><?php echo $_POST['terms'];?> months</td>

                                </tr>

                                <tr>

                                <td><b>Monthly Deduction</td>
                                <td>₱ <?php echo ($_POST['amount']+500)/$_POST['terms'] ;?></td>

                                </tr>

                                <tr>

                                <td><b>Number of Payments</td>
                                <td><?php echo $_POST['terms']*2;?> payments</td>

                                </tr>

                                <tr>

                                <td><b>Per Payment Deduction</td>
                                <td>₱ <?php echo  ($_POST['amount']+500)/($_POST['terms']*2) ;?></td>

                                </tr>

                            </tbody>

                        </table>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-12">

                            <div align="center">

                            <input type="submit" name="apply" class="btn btn-success" value="Submit">
                            <a href="MEMBER FALP application.php" class="btn btn-default" role="button">Go Back</a>
							<input type = "text" name = "amount" value =<?php echo $_POST['amount']; ?> hidden>
								<input type = "text" name = "interest" value =<?php echo $_POST['interest']; ?> hidden>
								<input type = "text" name = "terms" value =<?php echo $_POST['terms']; ?> hidden>
								<input type = "text" name = "amountP" value =<?php echo $_POST['amount']+500; ?>  hidden>
								<input type = "text" name = "payT" value = <?php echo $_POST['terms'];?> hidden>
								<input type = "text" name = "monD" value = <?php echo ($_POST['amount']+500)/$_POST['terms'];?> hidden>
								<input type = "text" name = "numP" value = <?php echo $_POST['terms']*2;?> hidden>
                                <?php
                                //this is a hidden variable that you came from this page before the nest
                                $fromReqPage = true;
                                ?>
                            </div>

                        </div>
                    </div>
            </form>
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
