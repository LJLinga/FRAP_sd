<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>FRAP | Falp Application</title>

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

    if ($_SESSION['usertype'] != 1) {

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
        
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

                    <div class="col-lg-12">

                        <div class="panel panel-primary">

                            <div class="panel-heading">

                                <b>APPLICATION REQUIREMENTS</b>

                            </div>

                            <div class="panel-body">

                            Requirements are: e.g. 1, e.g. 2, e.g.3, only image files (since it is scanned)

                            </div>

                        </div>

                    </div>

                </div>

                <hr>

                    <form method="POST" action="MEMBER FALP appsent.php"> <!-- SERVERSELF, REDIRECT TO NEXT PAGE -->

                    

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
                                <td>₱ <?php echo $_POST['amount']+($_POST['amount']*5/100);?></td>

                                </tr>

                                <tr>

                                <td><b>Payment Terms</td>
                                <td><?php echo $_POST['terms'];?> months</td>

                                </tr>

                                <tr>

                                <td><b>Monthly Deduction</td>
                                <td>₱ <?php echo ($_POST['amount']+($_POST['amount']*5/100))/$_POST['terms'];?></td>

                                </tr>

                                <tr>

                                <td><b>Number of Payments</td>
                                <td><?php echo $_POST['terms']*2;?> payments</td>

                                </tr>

                                <tr>

                                <td><b>Per Payment Deduction</td>
                                <td>₱ <?php echo (($_POST['amount']+($_POST['amount']*5/100))/$_POST['terms'])/2;?></td>

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
                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-12">

                            &nbsp;

                        </div>

                    </div>

                    </form>

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

</body>

</html>
