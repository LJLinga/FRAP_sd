<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>FRAP | Lifetime Membership Application</title>

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
    error_reporting(null);
    require_once("mysql_connect_FA.php");

    if ($_SESSION['usertype'] != 1) {

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
        
    }

    // compute the date first 

        $query = "SELECT YEAR(DATE_HIRED) from member where member_id = ".$_SESSION['idnum']." ";

        $hireDate = mysqli_fetch_assoc(mysqli_query($dbc,$query));

        $yearHired = $hireDate['DATE_HIRED'];

        $yearNOW = date('Y');


        if(isset($_POST['download'])){


            if(($yearNOW - $yearHired) >= 10 ){

                $ITR = "Lifetime_Document/Lifetime_Membership_Application_Form.pdf";

                header("Location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/downloadLifetime.php?loanID=".urlencode(''.$ITR) );

            }else{

                echo '<script language="javascript">';

                echo 'alert("You cannot as you have to serve for at least 10 years in the faculty association. ")';

                echo '</script>';

            }


        }


        


    // then once the download has been clicked g it my amigo 

    // 

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
        <!-- /.navbar-collapse -->
    </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">Lifetime Membership Application</h1>
                    
                    </div>

                </div>
                <div class = "row">

                    <div align="center">
                    <div class="col-lg-2">

                    
                    </div>

                    <div class = "col-lg-8">

                         <div class="well">

                            <p class="text-center">

                            <h3> 
                                You can download the Lifetime application form here once you are Eligible, at least 10 years of being a member of the Faculty Association.
                            </h3>

                            </p>
                        </div>
                    </div>


                     <div class="col-lg-2">

                    
                    </div>
                </div>


                </div>

                <div class="row"> <!-- Well -->

                    <div class="col-lg-1 col-1">

                    </div>

                    <div class="col-lg-10 col-2">

                        <div align="center">

                        <form action="#" method="POST" > 

                        <img class="pdficon10" src="images/pdficon.png">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                         <input type = "submit" class="btn btn-success  btn-lg" name="download" value = "Download Lifetime Application Form">

                         </form>


                        </div>

                    </div>

                </div>

                <p>&nbsp;

                <div class="row">

                    <div class="col-lg-12">

                        <div align="center">

                            <a href="MEMBER dashboard.php" class="btn btn-success" role="button">Return to Dashboard</a>

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

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
