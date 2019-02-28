<?php

    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';

    //to check if the user has applied in FALP but this code can be edited to check other applications. e.g. Health aid and shi like th sort

    $query = "SELECT LOAN_ID, LOAN_STATUS, APP_STATUS from loans where member_id = {$_SESSION['idnum']} && app_status != 3 ORDER BY LOAN_ID DESC LIMIT 1";
    $result = mysqli_query($dbc,$query);
    $row = mysqli_fetch_assoc($result);

    if(!empty($row)){

        if($row['LOAN_STATUS'] == 1){ //checks if you have a pending loan

            header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP failed.php");

        }else if($row['LOAN_STATUS'] == 2) { //checks if you have a loan that is ongoing.

            header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP summary.php");

        }

    }


    if(isset($ans)){

        $query2 = "SELECT MAX(LOAN_ID), member_id,amount, amount_paid,date_matured from loans where member_id = {$_SESSION['idnum']} AND date_matured is null AND app_status = 2;";
        $result2 = mysqli_query($dbc,$query2);
        $ans = mysqli_fetch_assoc($result2);
        //this means it checks if you have an on going loan and you have not paid 50% of it.
					
		if(!isset($ans['amount_paid'])){

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP failed.php");
			
		}

    }






    $page_title = 'Loans - FALP Application';
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

                    <div class="col-lg-3 col-1">

                        <div class="panel panel-success" align="center">

                            <div class="panel-heading">

                            <b>Loan Amount Range</b>

                            </div>



                            <div class="panel-body">

                                <?php
                                    $userStatus = "SELECT USER_STATUS from member where MEMBER_ID = {$_SESSION['idnum']}";
                                    $userStatusResult = mysqli_query($dbc,$userStatus);
                                    $userStatusRow= mysqli_fetch_assoc($userStatusResult);

                                    if($userStatusRow['USER_STATUS'] = 1) { // this means the user is a full time.

                                        echo "₱ 5,000.00 to ₱ 25,000.00";

                                    }else { // meaning the user is a part time

                                        echo"₱ 5,000.00 to ₱ 15,000.00";

                                    }
                                ?>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3 col-2">

                        <div class="panel panel-success" align="center">

                            <div class="panel-heading">

                            <b>Interest Amount (Fixed)</b>
                            
                            </div>

                            <div class="panel-body">

                                ₱ 500.00

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3 col-3">

                        <div class="panel panel-success" align="center">

                            <div class="panel-heading">

                            <b>Payment Terms </b>
                            
                            </div>

                            <div class="panel-body">
                                <?php
                                    if($userStatusRow['USER_STATUS'] = 1){
                                        echo "5 Months";
                                    }else{
                                        echo "3 Months";
                                    }
                                ?>


                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3 col-3">

                        <div class="panel panel-success" align="center">

                            <div class="panel-heading">

                                <b>Payment Terms </b>

                            </div>

                            <div class="panel-body">
                                <?php
                                if($userStatusRow['USER_STATUS'] = 1){
                                    echo "Full-Time";
                                }else{
                                    echo "Part-Time";
                                }


                                $userStatusType = $userStatusRow['USER_STATUS'];
                                ?>


                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="panel panel-primary">

                            <div class="panel-heading">

                                <b>APPLICATION REQUIREMENTS</b>

                            </div>

                            <div class="panel-body">

                            Requirements, upload on next page

                            </div>

                        </div>

                    </div>

                </div>

                <hr>

                <div class="row">

                    <div class="col-lg-2 col-1">

                    </div>

                    <div class="col-lg-8 col-2">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>Loan Calculator</b>

                            </div>

                            <div class="panel-body">

                            <form method="POST" id = "formA" action="MEMBER FALP requirements.php" onSubmit="return checkform()"> <!-- SERVERSELF, REDIRECT TO NEXT PAGE -->

                                <div class="row">

                                    <div class="col-lg-6 col-1">

                                        <label>Enter Amount to Borrow</label>

                                        <div class="form-group input-group">

                                            <span class="input-group-addon"><b>₱</b></span>
                                            <input type="text" name = "amount" id = "amount" class="form-control" placeholder="Enter Amount">

                                        </div>

                                    </div>

                                    <div class="col-lg-4 col-2">

                                        <div class="form-group">

                                            <label>Payment Terms</label>

                                            <select class="form-control" name = "terms" id = "terms">

                                                <?php
                                                    if($userStatusRow['USER_STATUS'] = 1) {
                                                        ?>

                                                        <option value=1>1</option>
                                                        <option value=2>2</option>
                                                        <option value=3>3</option>
                                                        <option value=4>4</option>
                                                        <option value=5>5</option>

                                                        <?php
                                                    }else {
                                                        ?>
                                                        <option value=1>1</option>
                                                        <option value=2>2</option>
                                                        <option value=3>3</option>
                                                        <?php
                                                    }
                                                ?>

                                            </select>

											<input type = "text" name = "interest" value = 5 hidden>

                                        </div>

                                    </div>

                                    <div class="col-lg-2 col-3">

                                        <input type="button" name="compute" class="btn btn-success" value="Compute" id="falpcompute">

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-lg-2 col-1">


                                    </div>

                                    <div class="col-lg-8 col-2">

                                        <div class="well" align="center">

                                          <div id = "totalI">   </div> <p>
                                            <p>
                                            <div id = "totalP"> </div><p>
                                            <p>
                                            <div id = "PerP"></div><p>
                                            <p>
                                            <div id = "Monthly"></div>

                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-lg-12">

                                        <div align="center">


                                        <input type="submit" name="apply" class="btn btn-success" value="Submit">
                                        <a href="MEMBER dashboard.php" class="btn btn-default" role="button">Go Back</a>

                                        </div>

                                    </div>

                                </div>

                                </div>

                            </form>

                        </div>

                    </div>

                    <div class="col-lg-2 col-3">

                    </div>

                </div>

                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>

<script>

    $(document).ready(function(){


        let userType = <?php echo json_encode($userStatusType); ?>;


        document.getElementById("falpcompute").onclick = function() {
            checkform();
        };




        function calculate(){
            let amount = parseFloat(document.getElementById("amount").value);
            let terms = parseFloat(document.getElementById("terms").value);
            let interest = 0;

            if(userType = 1){
                interest = 500;
            }else{
                interest = 300;
            }

            document.getElementById("totalI").innerHTML ="<b>Total Interest Payable: </b>₱"+ parseFloat((interest)).toFixed(2);
            document.getElementById("totalP").innerHTML ="<b>Total Amount Payable: </b> ₱"+ parseFloat((amount+interest)).toFixed(2);
            document.getElementById("PerP").innerHTML ="<b>Per Payment Period Payable: </b> ₱ "+ parseFloat(((amount+interest)/(terms*2))).toFixed(2);
            document.getElementById("Monthly").innerHTML ="<b>Monthly Payable: </b> ₱"+ parseFloat(((amount+interest)/(terms))).toFixed(2);

        }

        function checkform(){

            let elemAmount = document.getElementById("amount");
            let elemTerms = document.getElementById("terms");

            let amount = parseFloat(elemAmount.value);
            let terms = parseFloat(elemTerms.value);

            let amountLimit;
            let termMax;

            if(userType = 1){
                amountLimit = 25000;
                termMax = 5;
            }else{
                amountLimit = 15000;
                termMax = 3;
            }

            elemAmount.setAttribute("max",amountLimit+"");
            elemTerms.setAttribute("max",termMax+"");


            if(amount<5000){
                alert("Amount entered is below minimum. Please enter amount within the range.");
                return false;
            }
            else if(amount > amountLimit){
                alert("Amount entered is above maximum. Please enter amount within the range.");
                return false;
            }
            else if(terms  < 0){ // if terms are below 3, deins dapat to
                alert("Terms entered is below minimum . Please enter amount within the range.");
                return false;
            }else if(terms > termMax) {
                alert("Terms entered is above maximum . Please enter amount within the range.");
                return false;
            } else if(isNaN(amount)){
                alert("Invalid Input");
                return false;
            }else if (isNaN(terms)){
                alert("No Terms");
                return false;
            } else{
                calculate();
                return true;
            }

        }
    });



</script>
        <!-- /#page-wrapper -->
<?php include 'GLOBAL_FOOTER.php' ?>

