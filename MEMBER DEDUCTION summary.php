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
    $choice = 1;
    if(isset($_POST['Period'])){
        
            $choice = $_POST['Period'];
        
    }
    require_once('mysql_connect_FA.php');
    $query = "SELECT ha.Record_ID as 'has_HA', f.Amount as 'FFee', b.Amount as 'BFee'
              from member m
              left join (SELECT * from health_aid where app_status = 2) ha
              on m.member_id = ha.member_id
              left join (SELECT member_id,sum(PER_PAYMENT) as 'Amount' 
                         from Loans
                         where member_id = {$_SESSION['idnum']} and loan_status = 2 and loan_detail_id = 1) f
              on f.member_id = m.member_id
              left join (SELECT member_id,sum(PER_PAYMENT) as 'Amount' 
                         from Loans
                         where member_id = {$_SESSION['idnum']} and loan_status = 2 and loan_detail_id != 1) b
              on b.member_id = m.member_id
              where m.member_id = {$_SESSION['idnum']}";
    $result = mysqli_query($dbc,$query);
    $ans = mysqli_fetch_assoc($result);

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

                    <li class="dropdown sideicons"> <!------This is where the Name of the signed in member is ---->
                        <?php
                        $query = "SELECT LASTNAME, FIRSTNAME FROM MEMBER 
                                    
                    WHERE MEMBER_ID =" . $_SESSION['idnum'].";";

                        $result = mysqli_query($dbc, $query);
                        $row = mysqli_fetch_array($result);

                        $displayName = $row['LASTNAME']." , ".$row['FIRSTNAME'][0].". ";


                        ?>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $displayName ?><b class="caret"></b></a>

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

                        <a href="javascript:" data-toggle="collapse" data-target="#applicationformsdd"><i class="fa fa-wpforms" aria-hidden="true"></i> Application Forms <i class="fa fa-fw fa-caret-down"></i></a>

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

                        <a href="MEMBER DEDUCTION summary.php"><i class="fa fa-book" aria-hidden="true"></i> Salary Deduction Summary</a>

                    </li>

                    <li>

                        <a href="javascript:" data-toggle="collapse" data-target="#loantrackingdd"><i class="fa fa-money" aria-hidden="true"></i> Loan Tracking <i class="fa fa-fw fa-caret-down"></i></a>

                        <ul id="loantrackingdd" class="collapse">

                            <li>
                                <a href="MEMBER FALP summary.php"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;FALP Loan</a>
                            </li>


                        </ul>

                    </li>

                    <li>

                        <a href="javascript:" data-toggle="collapse" data-target="#servicessummarydd"><i class="fa fa-university" aria-hidden="true"></i> Services Summary <i class="fa fa-fw fa-caret-down"></i></a>

                        <ul id="servicessummarydd" class="collapse">

                            <li>
                                <a href="MEMBER HA summary.php"><i class="fa fa-medkit" aria-hidden="true"></i>&nbsp;&nbsp;Health Aid Summary</a>
                            </li>


                        </ul>

                    </li>

                    <li>

                        <a href="MEMBER AUDITRAIL.php"><i class="fa fa-backward" aria-hidden="true"></i> Audit Trail</a>

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

                        <h1 class="page-header">Deduction Summary</h1>
                    
                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-3 col-1">

                        <div class="panel panel-green" align="center">

                            <div class="panel-heading">

                                <b>FA Membership Fee</b>

                            </div>

                            <div class="panel-body">
                                <?php if($choice == 1)
                                        echo "₱ 100.00";
                                      else
                                        echo "₱ 0.00";?>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3 col-2">

                        <div class="panel panel-green" align="center">

                            <div class="panel-heading">

                                <b>Health Aid Program Fee</b>

                            </div>

                            <div class="panel-body">
                                <?php


                                if(!empty($ans['has_HA']) && $choice == 1)
                                    echo "₱ 100.00";
                                else
                                    echo "₱ 0.00";
                                ?>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3 col-3">

                        <div class="panel panel-green" align="center">

                            <div class="panel-heading">

                                <b>FALP Loan</b>

                            </div>

                            <div class="panel-body">

                                ₱ <?php 

                                if($choice==2)
                                echo sprintf("%.2f",(float)$ans['FFee']);
                            else
                                echo sprintf("%.2f",((float)$ans['FFee'])*2);?>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3 col-4">

                        <div class="panel panel-green" align="center">

                            <div class="panel-heading">

                                <b>Bank Loan</b>

                            </div>

                            <div class="panel-body">

                                ₱ <?php 
                                if($choice==2)
                                echo sprintf("%.2f",(float)$ans['BFee']);
                                 else
                                    echo sprintf("%.2f",((float)$ans['BFee'])*2);?>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-3">


                    </div>

                    <div class="col-lg-6">

                        <div class="row">

                            <form action="MEMBER DEDUCTION summary.php" method="POST">

                                <div class="col-lg-3">

                                    <label>View Summary As</label>

                                    <select name = "Period" class="form-control" style="margin-bottom: 20px;"> 

                                        <option value = 1 <?php if($choice == 1) echo "selected"?>>Per Month</option>
                                        <option value = 2 <?php if($choice == 2) echo "selected"?>>Per Pay Period</option>

                                    </select>

                                </div>

                                <input type="submit" class="btn btn-success" value="View As" name="viewas" style="margin-top: 25px;">

                            </form>

                        </div>

                        <table class="table table-bordered">
                            
                        <thread>

                            <tr>

                            <td align="center"><b>Payable</b></td>
                            <td align="center" width="50%"><b>Amount</b></td>

                            </tr>

                        </thread>

                         <tbody>

                            <tr>

                            <td>FA Membership Fee</td>
                            <td><?php $mf = 0;
                                if($choice == 1){
                                        echo "₱ 100.00";
                                        $mf = 100;
                                    }
                                      else
                                        echo "₱ 0.00";?></td>

                            </tr>

                            <tr>

                            <td>Health Aid Program Fee</td>
                            <td><?php

                                $ha = 0;
                                if(!empty($ans['has_HA']) && $choice == 1){
                                    echo "₱ 100.00";
                                    $ha = 100;
                                }
                                else{
                                    echo "₱ 0.00";
                                }
                                ?></td>

                            </tr>

                            <tr>

                            <td>FALP Loan</td>
                            <td id = "FALP">₱ <?php 
                                if ($choice==2){
                                    $ff = (float)$ans['FFee'];
                                echo sprintf("%.2f",(float)$ans['FFee']);
                            }
                                 else{
                                    $ff = (float)$ans['FFee']*2;
                                    echo sprintf("%.2f",((float)$ans['FFee'])*2);}?></td>
                            

                            </tr>

                            <tr>

                            <td>Bank Loan</td>
                            <td id = "Bank">₱ <?php 
                                if ($choice==2){
                                echo sprintf("%.2f",(float)$ans['BFee']);
                                $bf = (float)$ans['BFee'];
                            }
                                 else{
                                    echo sprintf("%.2f",((float)$ans['BFee'])*2);

                                    $bf = (float)$ans['BFee']*2;
                                }
                                    ?></td>

                                

                            </tr>

                            <tr>

                            <td><b>TOTAL</td>
                            <td><b>₱ <?php echo sprintf("%.2f",$mf+$ha+$ff+$bf);?></td>

                            </tr>

                        </tbody>

                        </table>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12" align="center">

                         <a href="MEMBER dashboard.php" class="btn btn-default" role="button">Go Back</a>

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

</body>

</html>
