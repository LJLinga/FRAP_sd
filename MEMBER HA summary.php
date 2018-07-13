<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>FRAP | Health Aid Summary</title>

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

                        <h1 class="page-header">Health Aid Program Summary</h1>
                    
                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="alert alert-info">

                            The names listed below are eligible to enjoy the benefits of the Health Aid Program.

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-2">

                    </div>

                    <div class="col-lg-8">

                        <div class="well" align="center">
                            <?php
                                $query = "SELECT DATE_APPROVED FROM HEALTH_AID WHERE MEMBER_ID = ". $_SESSION['idnum'] .";";
                                $result = mysqli_query($dbc, $query);
                                $row = mysqli_fetch_array($result);
                            ?>
                            <b>Health Aid Program Member Since:</b> <?php echo $row['DATE_APPROVED']; ?>

                            <div>
                                <!-- Insert code here to update every month -->
                                <b>Total Health Aid Contribution:</b> ₱ 1,800.00

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12 col-1">

                        <div class="panel panel-default">

                            <div class="panel-heading">

                                <b>Health Aid Program Beneficiaries</b>

                            </div>

                        <div class="panel-body">

                        <div class="row">

                            <div class="col-lg-1 col-1">

                            </div>

                            <div class="col-lg-10 col-2">

                                    <div class="panel panel-primary">

                                        <div class="panel-heading">

                                            Father's Information

                                        </div>

                                        <div class="panel-body">

                                            <table class="table table-bordered">
                                
                                                <thread>

                                                    <tr>

                                                    <td align="center"><b>Name</b></td>
                                                    <td align="center"><b>Age</b></td>
                                                    <td align="center"><b>Birthday</b></td>

                                                    </tr>

                                                </thread>

                                                <tbody>

                                                    <tr>
                                                    <?php 
                                                        $query = "SELECT * FROM FATHER WHERE MEMBER_ID =" . $_SESSION['idnum'].";";
                                                        $result = mysqli_query($dbc, $query);
                                                        $row = mysqli_fetch_array($result);

                                                        $today = date("Y-m-d");
                                                        $diff = date_diff(date_create($row['BIRTHDATE']), date_create($today));
                                                        $diff->format('%y'); //Displays computed age
                                                    ?>
                                                    <td align="center"><?php echo $row['FIRSTNAME']. " ". $row['LASTNAME']; ?></td>
                                                    <td align="center"><?php echo $diff->format('%y'); ?></td>
                                                    <td align="center"><?php echo $row['BIRTHDATE']; ?></td>

                                                    </tr>

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                    <div class="panel panel-primary">

                                        <div class="panel-heading">

                                            Mother's Information

                                        </div>

                                        <div class="panel-body">

                                            <table class="table table-bordered">
                                
                                                <thread>

                                                    <tr>

                                                    <td align="center"><b>Name</b></td>
                                                    <td align="center"><b>Age</b></td>
                                                    <td align="center"><b>Birthday</b></td>

                                                    </tr>

                                                </thread>

                                                <tbody>

                                                    <tr>

                                                    <?php 
                                                        $query = "SELECT * FROM MOTHER WHERE MEMBER_ID =" . $_SESSION['idnum'].";";
                                                        $result = mysqli_query($dbc, $query);
                                                        $row = mysqli_fetch_array($result);

                                                        $today = date("Y-m-d");
                                                        $diff = date_diff(date_create($row['BIRTHDATE']), date_create($today));
                                                        $diff->format('%y');
                                                    ?>
                                                    <td align="center"><?php echo $row['FIRSTNAME']. " ". $row['LASTNAME']; ?></td>
                                                    <td align="center"><?php echo $diff->format('%y'); ?></td>
                                                    <td align="center"><?php echo $row['BIRTHDATE']; ?></td>

                                                    </tr>

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                    <div class="panel panel-primary">

                                        <div class="panel-heading">

                                            Spouse's Information

                                        </div>

                                        <div class="panel-body">

                                            <table class="table table-bordered">
                                
                                                <thread>

                                                    <tr>

                                                    <td align="center"><b>Name</b></td>
                                                    <td align="center"><b>Age</b></td>
                                                    <td align="center"><b>Birthday</b></td>

                                                    </tr>

                                                </thread>

                                                <tbody>

                                                    <tr>

                                                    <?php 
                                                        $query = "SELECT * FROM SPOUSE WHERE MEMBER_ID =" . $_SESSION['idnum'].";";
                                                        $result = mysqli_query($dbc, $query);
                                                        $row = mysqli_fetch_array($result);

                                                        $today = date("Y-m-d");
                                                        $diff = date_diff(date_create($row['BIRTHDATE']), date_create($today));
                                                    ?>
                                                    <td align="center"><?php echo $row['FIRSTNAME']. " ". $row['LASTNAME']; ?></td>
                                                    <td align="center"><?php echo $diff->format('%y'); ?></td>
                                                    <td align="center"><?php echo $row['BIRTHDATE']; ?></td>

                                                    </tr>

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                    <div class="panel panel-primary">

                                        <div class="panel-heading">

                                            Sibling's Information

                                        </div>

                                        <div class="panel-body">

                                            <table class="table table-bordered">
                                
                                                <thread>

                                                    <tr>

                                                    <td align="center"><b>Name</b></td>
                                                    <td align="center"><b>Age</b></td>
                                                    <td align="center"><b>Birthday</b></td>

                                                    </tr>

                                                </thread>

                                                <tbody>

                                                    <tr>

                                                    <?php 
                                                        $query = "SELECT * FROM SIBLINGS WHERE MEMBER_ID =" . $_SESSION['idnum'].";";
                                                        $result = mysqli_query($dbc, $query);

                                                        foreach ($result as $resultRow) {
                                                            $today = date("Y-m-d");
                                                            $diff = date_diff(date_create($resultRow['BIRTHDATE']), date_create($today));

                                                            echo"
                                                                <tr>
                                                                    <td align='center'>". $resultRow['FIRSTNAME'] ." ". $resultRow['LASTNAME'] ."</td>
                                                                    <td align='center'>". $diff->format('%y') ."</td>
                                                                    <td align='center'>". $resultRow['BIRTHDATE'] ."</td>
                                                                </tr>
                                                            ";
                                                        }
                                                    ?>

                                                    </tr>

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                    <div class="panel panel-primary">

                                        <div class="panel-heading">

                                            Children's Information

                                        </div>

                                        <div class="panel-body">

                                            <table class="table table-bordered">
                                
                                                <thread>

                                                    <tr>

                                                    <td align="center"><b>Name</b></td>
                                                    <td align="center"><b>Age</b></td>
                                                    <td align="center"><b>Birthday</b></td>

                                                    </tr>

                                                </thread>

                                                <tbody>

                                                    <tr>

                                                    <?php 
                                                        $query = "SELECT * FROM CHILDREN WHERE MEMBER_ID =" . $_SESSION['idnum'].";";
                                                        $result = mysqli_query($dbc, $query);

                                                        foreach ($result as $resultRow) {
                                                            $today = date("Y-m-d");
                                                            $diff = date_diff(date_create($resultRow['BIRTHDATE']), date_create($today));

                                                            echo"
                                                                <tr>
                                                                    <td align='center'>". $resultRow['FIRSTNAME'] ." ". $resultRow['LASTNAME'] ."</td>
                                                                    <td align='center'>". $diff->format('%y') ."</td>
                                                                    <td align='center'>". $resultRow['BIRTHDATE'] ."</td>
                                                                </tr>
                                                            ";
                                                        }
                                                    ?>

                                                    </tr>

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12" align="center">

                         <a href="MEMBER dashboard.php" class="btn btn-default" role="button">Go Back</a>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">
                        &nbsp;
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
