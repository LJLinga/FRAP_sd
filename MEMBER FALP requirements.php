<?php

    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';

    //check if the person has anongoing  loan first dammit then if he/she has, redirect to a pending. but if its accepted, redirect to activity
    //to check if the user has applied in FALP but this code can be edited to check other applications. e.g. Health aid and shi like th sort

    $query = "SELECT MAX(LOAN_ID), LOAN_STATUS from loans where member_id = {$_SESSION['idnum']} ";
    $result = mysqli_query($dbc,$query);

    $row = mysqli_fetch_assoc($result);

    if($row['LOAN_STATUS'] == 1){ //checks if you have a pending loan

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP failed.php");

    }else if($row['LOAN_STATUS'] == 2) { //checks if you have a loan that is ongoing.

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP failed.php");

    }

    $page_title = 'Loans - FALP Requirements';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_USER_SIDEBAR.php';
?>
<script>


    function checkForm(){

        if(document.getElementById("IncomeTax").files.length == 0){ // checks if the income tax field is emptty
            alert("please enter incomeTax");
            return false;
        }else if(document.getElementById("payslip").files.length == 0){ // checks if the payslip is empty
            alert("please enter payslip");
            return false;
        }else if(document.getElementById("emp_ID").files.length == 0){ // checks if the emp_id is empty
            alert("no files selected for emp_id");
            return false;
        }else if(document.getElementById("gov_ID").files.length == 0){ // checks if the gov_id is empty
            alert("No Files selected for gov_ID");
            return false;
        }else{
            alert("All files uploaded!");
            return true;
        }

    }
</script>
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

                    <form action="MEMBER%20FALP%20appsent.php" method = "post" enctype="multipart/form-data" onsubmit="return checkForm()">  <!-- SERVERSELF, REDIRECT TO NEXT PAGE -->

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

                                            <input type="file" accept = ".jpeg, .jpg, .png, .pdf, .doc, .docx" name = "IncomeTax" id = "IncomeTax" >

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

                                            <input type="file" accept = ".jpeg, .jpg, .png, .pdf, .doc, .docx" name = "payslip" id="payslip">

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

                                            <input type="file" accept = ".jpeg, .jpg, .png, .pdf, .doc, .docx" name = "emp_ID" id = "emp_ID">

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

                                            <input type="file" accept = ".jpeg, .jpg, .png, .pdf, .doc, .docx" name = "gov_ID" id = "gov_ID">

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
                                <td>₱ <?php echo $_POST['amount']+(500*$_POST['terms']);?></td>

                                </tr>

                                <tr>

                                <td><b>Payment Terms</td>
                                <td><?php echo $_POST['terms'];?> months</td>

                                </tr>

                                <tr>

                                <td><b>Monthly Deduction</td>
                                <td>₱ <?php echo 500.00 ;?></td>

                                </tr>

                                <tr>

                                <td><b>Number of Payments</td>
                                <td><?php echo $_POST['terms']*2;?> payments</td>

                                </tr>

                                <tr>

                                <td><b>Per Payment Deduction</td>
                                <td>₱ <?php echo 250.00 ;?></td>

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
								<input type = "text" name = "amountP" value =<?php echo $_POST['amount']+$_POST['amount']*$_POST['interest']/100; ?>  hidden>
								<input type = "text" name = "payT" value = <?php echo $_POST['terms'];?> hidden>
								<input type = "text" name = "monD" value = <?php echo ($_POST['amount']+$_POST['amount']*$_POST['interest']/100)/$_POST['terms'];?> hidden>
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
