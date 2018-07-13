<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>FRAP | Bank Loan Application</title>

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

<?php

    session_start();
    require_once('mysql_connect_FA.php');
    if ($_SESSION['usertype'] != 1) {

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
        
    }
    $query = "SELECT member_id,amount, amount_paid,date_matured from loans where member_id = {$_SESSION['idnum']} AND date_matured is null AND app_status = 2;";
    $result = mysqli_query($dbc,$query);

    $ans = mysqli_fetch_assoc($result);
    if(isset($ans)){
					
		if(!isset($ans['amount_paid'])){
			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP failed.php");
			
		}
        if(doubleval($ans['amount_paid'])/doubleval($ans['amount']) < 0.5){
			 $_SESSION['Paid'] = doubleval($ans['amount_paid'])/doubleval($ans['amount']);	
            header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP failed.php");
           
        }
    }

?>

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

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> John Smith <b class="caret"></b></a>

                    <ul class="dropdown-menu">

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

                        <a href="MEMBER dashboard.php"><i class="fa fa-area-chart" aria-hidden="true"></i> Overview</a>

                    </li>

                    <li>

                        <a href="javascript:;" data-toggle="collapse" data-target="#applicationformsdd"><i class="fa fa-wpforms" aria-hidden="true"></i> Application Forms <i class="fa fa-fw fa-caret-down"></i></a>

                        <ul id="applicationformsdd" class="collapse">

                            <li>
                                <a href="MEMBER FALP application.php"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;FALP Application</a>
                            </li>

                            <li>
                                <a href="MEMBER HA application.php"><i class="fa fa-medkit" aria-hidden="true"></i>&nbsp;&nbsp;Health Aid Application</a>
                            </li>

                            <li>
                                <a href="MEMBER LIFETIME form.php"><i class="fa fa-handshake-o" aria-hidden="true"></i>&nbsp;&nbsp;Lifetime Member Application</a>
                            </li>

                        </ul>

                    </li>

                    <li>

                        <a href="MEMBER BANKLOAN list.php"><i class="fa fa-dollar" aria-hidden="true"></i> Bank Loans</a>

                    </li>

                    <li>

                    <a href="MEMBER DEDUCTION summary.php"><i class="fa fa-book" aria-hidden="true"></i> Salary Deduction Summary</a>

                    </li>

                    <li>

                    <a href="javascript:;" data-toggle="collapse" data-target="#loantrackingdd"><i class="fa fa-money" aria-hidden="true"></i> Loan Tracking <i class="fa fa-fw fa-caret-down"></i></a>

                        <ul id="loantrackingdd" class="collapse">

                            <li>
                                <a href="MEMBER FALP summary.php"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;FALP Loan</a>
                            </li>

                            <li>
                                <a href="MEMBER BANKLOAN summary.php"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;&nbsp;Bank Loan</a>
                            </li>

                        </ul>

                    </li>

                    <li>

                    <a href="javascript:;" data-toggle="collapse" data-target="#servicessummarydd"><i class="fa fa-university" aria-hidden="true"></i> Services Summary <i class="fa fa-fw fa-caret-down"></i></a>

                        <ul id="servicessummarydd" class="collapse">

                            <li>
                                <a href="MEMBER HA summary.php"><i class="fa fa-medkit" aria-hidden="true"></i>&nbsp;&nbsp;Health Aid Summary</a>
                            </li>

                            <li>
                                <a href="MEMBER LIFETIME summary.php"><i class="fa fa-handshake-o" aria-hidden="true"></i>&nbsp;&nbsp;Lifetime Membership Summary</a>
                            </li>

                        </ul>

                    </li>

                    <li>

                        <a href="MEMBER AUDITRAIL.php"><i class="fa fa-backward" aria-hidden="true"></i> Audit Trail</a>

                    </li>

                    <li>

                        <a href="MEMBER FILEREPO.php"><i class="fa fa-folder" aria-hidden="true"></i> File Repository</a>

                    </li>

                </ul>

            </div>
            <!-- /.navbar-collapse -->
        </nav>

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

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
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

</body>

</html>
