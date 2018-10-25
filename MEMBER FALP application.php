<?php

    session_start();
    require_once('mysql_connect_FA.php');
    if ($_SESSION['usertype'] != 1) {

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
        
    }

    //to check if the user has applied in FALP but this code can be edited to check other applications. e.g. Health aid and shi like th sort

    $query = "SELECT MAX(LOAN_ID), LOAN_STATUS from loans where member_id = {$_SESSION['idnum']} ";
    $result = mysqli_query($dbc,$query);

    $row = mysqli_fetch_assoc($result);

    if($row['LOAN_STATUS'] = 1){ //checks if you have a pending loan

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP failed.php");

    }else if($row['LOAN_STATUS'] = 2) { //checks if you have a loan that is ongoing.

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP failed.php");

    }

    /*
    $query = "SELECT MAX(LOAN_ID), member_id,amount, amount_paid,date_matured from loans where member_id = {$_SESSION['idnum']} AND date_matured is null AND app_status = 2;";
    $result = mysqli_query($dbc,$query);

    $ans = mysqli_fetch_assoc($result);
    //this means it checks if you have an on going loan and you have not paid 50% of it.
    if(isset($ans)){
					
		if(!isset($ans['amount_paid'])){
			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP failed.php");
			
		}

    }
    */
    $page_title = 'Loans - FALP Application';
    include 'GLOBAL_TEMPLATE_Header.php';
    include 'LOAN_TEMPLATE_NAVIGATION_Member.php';
?>
<script>
    document.getElementById("falpcompute").onclick = function() {calculate()};

    function calculate(){

        var amount = parseFloat(document.getElementById("amount").value);
        var terms = parseFloat(document.getElementById("terms").value);
        var interest = 5;

        document.getElementById("totalI").innerHTML ="<b>Total Interest Payable: </b>₱"+ parseFloat((amount*interest/100)).toFixed(2);
        document.getElementById("totalP").innerHTML ="<b>Total Amount Payable: </b> ₱"+ parseFloat((amount+amount*interest/100)).toFixed(2);
        document.getElementById("PerP").innerHTML ="<b>Per Payment Period Payable: </b> ₱ "+ parseFloat(((amount+amount*interest/100)/terms/2)).toFixed(2);
        document.getElementById("Monthly").innerHTML ="<b>Monthly Payable: </b> ₱"+ parseFloat(((amount+amount*interest/100)/terms)).toFixed(2);

    }

    function checkform(){

        var amount = parseFloat(document.getElementById("amount").value);

        if(amount<5000){
            alert("Amount entered is below minimum. Please enter amount within the range.");
            return false;

        }
        else if(amount >20000){
            alert("Amount entered is above maximum.Please enter amount within the range.");
            return false;
        }
        else if(isNaN(amount)){
            alert("Invalid Input");
            return false;
        }
        return true;

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

                    <div class="col-lg-4 col-1">

                        <div class="panel panel-success" align="center">

                            <div class="panel-heading">

                            <b>Loan Amount Range</b>

                            </div>

                            <div class="panel-body">

                                ₱ 5,000.00 to ₱ 20,000.00

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4 col-2">

                        <div class="panel panel-success" align="center">

                            <div class="panel-heading">

                            <b>Interest Amount (Fixed)</b>
                            
                            </div>

                            <div class="panel-body">

                                5%

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4 col-3">

                        <div class="panel panel-success" align="center">

                            <div class="panel-heading">

                            <b>Payment Terms </b>
                            
                            </div>

                            <div class="panel-body">

                                5 Months to 10 Months

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

                                                <option value = 5>5</option>
                                                <option value = 6>6</option>
                                                <option value = 7>7</option>
                                                <option value = 8>8</option>
                                                <option value = 9>9</option>
                                                <option value = 10>10</option>

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
        <!-- /#page-wrapper -->
<?php include 'GLOBAL_TEMPLATE_Footer.php' ?>

