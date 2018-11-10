<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}
require_once("mysql_connect_FA.php");

$query = "SELECT * FROM LOANS where LOAN_ID = {$_SESSION['details']} 
                                                  AND loan_detail_id = 1 AND    loan_status != 3";
$result = mysqli_query($dbc,$query);
$ans = mysqli_fetch_assoc($result);

$query1 = "SELECT l2.STATUS as 'Status' FROM LOANS l1 JOIN LOAN_STATUS l2 ON l1.LOAN_STATUS = l2.STATUS_ID where l1.LOAN_ID = {$_SESSION['details']} 
                                                  AND l1.loan_detail_id = 1 AND     l1.loan_status != 3";
$result1 = mysqli_query($dbc,$query1);
$ans1 = mysqli_fetch_assoc($result1);


if(isset($_POST['addToPay'])){

    //check if the amount to be added is 50% or will exceed

    if($_POST['terms'] != 0) {

        //get the terms to be paid - calculate the payment to be made based on this

        $paymentToBeAdded =$ans['AMOUNT_PAID']+ ($_POST['terms']*$ans['PER_PAYMENT']);

        $termsLeft = $ans['PAYMENTS_MADE'] + $_POST['terms'];

        //update query
        if($paymentTermsLeft = 0){ //checks if the loan will mature
            $update = "UPDATE loans SET AMOUNT_PAID = {$paymentToBeAdded},PAYMENTS_MADE = {$termsLeft}, DATE_MATURED = NOW(), STATUS = 3 where LOAN_ID  = {$_SESSION['details']}";
            mysqli_query($dbc,$update);
        }else{
            $update = "UPDATE loans SET AMOUNT_PAID = {$paymentToBeAdded},PAYMENTS_MADE = {$termsLeft} where LOAN_ID  = {$_SESSION['details']}";
            mysqli_query($dbc,$update);
        }

        //update transaction table

    }else{
        echo '<script language="javascript">';
        echo 'alert(" You cant pay for 0 terms ")';
        echo '</script>';
    }







}else if(isset($_POST['addFifty'])){


    //check if the 50% has beeen surpassed first - baka lang na click ni sir melton to
    if(($ans['PAYMENT_TERMS'] - $ans['PAYMENTS_MADE']) > ($ans['PAYMENT_TERMS']/2)){

        //get the current Amount paid, and get the 50% of the Payable Amount,

        $termsLeftForFifty =($ans['PAYMENT_TERMS'] - $ans['PAYMENTS_MADE'])-($ans['PAYMENT_TERMS']/2); // this variable calculates the remaining 50% to be updated in the loan.

        $payment = $ans['AMOUNT_PAID']+($termsLeftForFifty*$ans['PER_PAYMENT']);

        $newPayments = $ans['PAYMENTS_MADE']+$termsLeftForFifty;


        //update query

        $update = "UPDATE loans SET AMOUNT_PAID = {$payment},PAYMENTS_MADE = {$newPayments}, DATE_MATURED = NOW() where LOAN_ID  = {$_SESSION['details']}";
        mysqli_query($dbc,$update);

        //update transaction table




    }else{
        echo '<script language="javascript">';
        echo 'alert(" Payment is already at 50% and more.")';
        echo '</script>';


    }









}

$show = "SELECT * FROM LOANS where LOAN_ID = {$_SESSION['details']} 
                                                  AND loan_detail_id = 1 AND    loan_status != 3";
$showme = mysqli_query($dbc,$show);
$updated = mysqli_fetch_assoc($showme);

$query3 = "SELECT l2.STATUS as 'Status' FROM LOANS l1 JOIN LOAN_STATUS l2 ON l1.LOAN_STATUS = l2.STATUS_ID where l1.LOAN_ID = {$_SESSION['details']} 
                                                  AND l1.loan_detail_id = 1 AND     l1.loan_status != 3";
$result3 = mysqli_query($dbc,$query3);
$status = mysqli_fetch_assoc($result3);





?>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>FRAP | Falp Summary</title>

    <link href="css/montserrat.css" rel="stylesheet">
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<style>
    .slidecontainer {
        width: 100%;
    }

    .slider {
        -webkit-appearance: none;
        width: 100%;
        height: 25px;
        background: #d3d3d3;
        outline: none;
        opacity: 0.7;
        -webkit-transition: .2s;
        transition: opacity .2s;
    }

    .slider:hover {
        opacity: 1;
    }

    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 25px;
        height: 25px;
        background: #1066af;
        cursor: pointer;
    }

    .slider::-moz-range-thumb {
        width: 25px;
        height: 25px;
        background: #4CAF50;
        cursor: pointer;
    }
</style>

<body>

    <div id="wrapper">

        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

            <div class="navbar-header"> <!-- Logo -->

                <img src="images/I-FA Logo Edited.png" id="ifalogo">


                <ul class="nav navbar-right top-nav"> <!-- Top Menu Items / Notifications area -->

                    <li class="dropdown sideicons">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>

                        <ul class="dropdown-menu alert-dropdown">

                            <li>
                                <a href="#">Alert Name <span class="label label-default">Alert Badge</span></a>
                            </li>

                            <li>
                                <a href="#">Alert Name <span class="label label-primary">Alert Badge</span></a>
                            </li>

                            <li>
                                <a href="#">Alert Name <span class="label label-success">Alert Badge</span></a>
                            </li>

                            <li>
                                <a href="#">Alert Name <span class="label label-info">Alert Badge</span></a>
                            </li>

                            <li>
                                <a href="#">Alert Name <span class="label label-warning">Alert Badge</span></a>
                            </li>

                            <li>
                                <a href="#">Alert Name <span class="label label-danger">Alert Badge</span></a>
                            </li>

                            <li class="divider"></li>

                            <li>
                                <a href="#">View All</a>
                            </li>

                        </ul>

                    </li>

                    <li class="dropdown sideicons">

                        <?php
                        $query = "SELECT FIRSTNAME, LASTNAME
                                    from employee
                                    where EMP_ID =" . $_SESSION['idnum'].";";

                            $result = mysqli_query($dbc, $query);
                            $row = mysqli_fetch_array($result);

                            $displayName = $row['LASTNAME']." , ".$row['FIRSTNAME'][0].". ";

                        ?>

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $displayName ?><b class="caret"></b></a>

                        <ul class="dropdown-menu">

                            <li>

                                <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>

                            </li>

                            <li class="divider"></li>

                            <li>

                                <a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>

                            </li>

                        </ul>

                    </li>

                </ul>

            </div>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">

                <ul class="nav navbar-nav side-nav">

                    <li id="top">

                        <a href="ADMIN FALP manual.php"><i class="fa fa-gears" aria-hidden="true"></i> Add Member & FALP Account</a>

                    </li>

                    <li>
                        <a href="ADMIN MEMBERS viewmembers.php"><i class="fa fa-group" aria-hidden="true"></i>&nbsp;&nbsp;View All Members</a>
                    </li>

                    <li>

                        <a href="javascript:;" data-toggle="collapse" data-target="#loans"><i class="fa fa-money" aria-hidden="true"></i> FALP Loans<i class="fa fa-fw fa-caret-down"></i></a>

                        <ul id="loans" class="collapse">

                            <li>
                                <a href="ADMIN FALP viewactive.php"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;View FALP Loans</a>
                            </li>

                            <li>
                                <a href="ADMIN FALP only.php"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp; Add FALP to Member </a>
                            </li>

                        </ul>

                    </li>

                    <li>

                        <a href="javascript:;" data-toggle="collapse" data-target="#dreports"><i class="fa fa-minus" aria-hidden="true"></i>&nbsp;Deduction Reports<i class="fa fa-fw fa-caret-down"></i></a>

                        <ul id="dreports" class="collapse">

                            <li>
                                <a href="ADMIN DREPORT general.php"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;&nbsp;General Deductions</a>
                            </li>

                            <li>
                                <a href="ADMIN DREPORT detailed.php"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;&nbsp;Detailed Deductions</a>
                            </li>

                        </ul>

                    </li>

                    <li>

                        <a href="javascript:;" data-toggle="collapse" data-target="#preports"><i class="fa fa-table" aria-hidden="true"></i>&nbsp;Periodical Reports<i class="fa fa-fw fa-caret-down"></i></a>

                        <ul id="preports" class="collapse">

                            <li>
                                <a href="ADMIN PREPORT completed.php"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;&nbsp;Completed Loans</a>
                            </li>

                            <li>
                                <a href="ADMIN PREPORT new.php"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;&nbsp;New Deductions</a>
                            </li>

                        </ul>

                    </li>

                    <li>

                        <a href="ADMIN MREPORT report.php"><i class="fa fa-table" aria-hidden="true"></i> Monthly Report</a>

                    </li>

                    <li>

                        <a href="ADMIN FILEREPO.php"><i class="fa fa-folder-open-o" aria-hidden="true"></i>&nbsp;File Repository</i></a>

                    </li>
                    <!--
                    <li>

                        <a href="ADMIN MANAGE.php"><i class="fa fa-gears" aria-hidden="true"></i> Admin Management</a>

                    </li>-->

                </ul>

            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">
                        <?php
                            $query2 = "SELECT m.firstname as 'First',m.lastname as 'Last' FROM LOANS l join member m on l.member_id = m.member_id where LOAN_ID = {$_SESSION['details']} 
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

                                    <tr>

                                    <td>Amount to Borrow</td>
                                    <td>₱ <?php echo $updated['AMOUNT'];?></td>

                                    </tr>

                                    <tr>

                                    <td>Amount Payable</td>
                                    <td>₱ <?php echo $updated['PAYABLE'];?></td>

                                    </tr>

                                    <tr>

                                    <td>Payment Terms</td>
                                    <td><?php echo $updated['PAYMENT_TERMS']/2;?> months</td>

                                    </tr>

                                    <tr>

                                    <td>Monthly Deduction</td>
                                    <td>₱ <?php echo sprintf("%.2f",(float)$updated['PER_PAYMENT']*2);?></td>

                                    </tr>

                                    <tr>

                                    <td>Number of Payments</td>
                                    <td><?php echo $updated['PAYMENT_TERMS'];?> payments</td>

                                    </tr>

                                    <tr>

                                    <td>Per Payment Deduction</td>
                                    <td>₱ <?php echo $updated['PER_PAYMENT'] ;?></td>

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
                                    <td><?php echo $updated['DATE_APPROVED'];?></td>

                                    </tr>

                                    <tr>

                                    <td>Payments Made</td>
                                    <td><?php echo (int)$updated['PAYMENTS_MADE'];?> Payments</td>

                                    </tr>

                                    <tr>

                                    <td>Payments Left</td>
                                    <td><?php echo ($updated['PAYMENT_TERMS']) - $updated['PAYMENTS_MADE'];?> Payments</td>

                                    </tr>

                                    <tr>

                                    <td>Total Amount Paid</td>
                                    <td>₱ <?php echo sprintf("%.2f",(float)$updated['AMOUNT_PAID']);?></td>

                                    </tr>

                                    <tr>

                                    <td>Outstanding Balance</td>
                                    <td>₱ <?php echo sprintf("%.2f",$updated['PAYABLE']-$updated['AMOUNT_PAID']);?></td>

                                    </tr>

                                    <tr>

                                    <td>Status</td>
                                    <td><?php echo $status['Status'];?></td>

                                    </tr>

                                </tbody>

                                </table>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="row">
                        <div class = "col-lg-4">


                        </div>

                        <div class="col-lg-4">
                            <form action = "ADMIN%20FALP%20viewdetails.php" method = "POST">
                                <div class="panel panel-primary">

                                    <div class="panel-heading">

                                        <b>Manual Add to FALP</b>
                                    </div>

                                    <div class="panel-body">

                                        <!---<input type="number" value="0" min="0" max="" class="form-control" placeholder="Amount to Add" name="terms"  id="terms">
                                        --->
                                        <div class="slidecontainer">
                                            <input type="range" min="0" max="<?php echo ($updated['PAYMENT_TERMS'])-$updated['PAYMENTS_MADE']; ?>" value="0" class="slider" name="terms" id="myRange">
                                            <p>Number of Payments to be Paid For: <span id="demo"> </span></p>
                                        </div>

                                        <br>
                                        <button type="submit" name="addToPay" class="btn btn-primary">Pay with number of terms</button>
                                        <button type="submit" name="addFifty" class="btn btn-primary">Pay 50% Immediately</button>
                                    </div>

                                </div>

                            </form>

                        </div>

                        <div class = "col-lg-4">


                        </div>


                    </div>



                    <div class="row">

                        <div class="col-lg-12">

                            <div align="center">
                            <form action = "ADMIN FALP viewactivity.php" method = "POST">
                            <button type = "submit" class="btn btn-success" role="button" value = <?php echo $_SESSION['details']?> name = "details" >View Payment Activity</button>

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
    <script>
        var slider = document.getElementById("myRange");
        var output = document.getElementById("demo");
        output.innerHTML = slider.value;

        slider.oninput = function() {
            output.innerHTML = this.value;
        }
    </script>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
