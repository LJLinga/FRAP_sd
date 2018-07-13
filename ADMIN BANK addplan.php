<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
require_once("mysql_connect_FA.php");

if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}

    $message = NULL;
    if(isset($_POST['submit'])){

        //Input Validation
        if(isset($_POST['bank'])){
            $_SESSION['apBank'] = $_POST['bank'];
        }
        else{
            $message .= "You forgot to choose a bank!";
        }

        if(isset($_POST['amountMin'])){
            if(is_numeric($_POST['amountMin']) && $_POST['amountMin'] >= 0){
                $_SESSION['apAmtMin'] = $_POST['amountMin'];
            }
            else{
                $message .= "Please enter valid numeric values only for amount (minimum)!";
            }
        }
        else{
            $message .= "You forgot to enter an amount (minimum)!";
        }

        if(isset($_POST['amountMax'])){
            if(is_numeric($_POST['amountMax']) && $_POST['amountMax'] >= 0){
                $_SESSION['apAmtMax'] = $_POST['amountMax'];
            }
            else{
                $message .= "Please enter valid numeric values only for amount (maximum)!";
            }
        }
        else{
            $message .= "You forgot to enter an amount (maximum)!";
        }

        if(isset($_POST['interest'])){
            if(is_numeric($_POST['interest']) && $_POST['interest'] >= 0){
                $_SESSION['apInterest'] = $_POST['interest'];
            }
            else{
                $message .= "Please enter valid numeric values only for interest!";
            }
        }
        else{
            $message .= "You forgot to enter an interest!";
        }

        if(isset($_POST['payTermsMin'])){
            if(is_numeric($_POST['payTermsMin']) && $_POST['payTermsMin'] >= 0){
                $_SESSION['apPayTermsMin'] = $_POST['payTermsMin'];
            }
            else{
                $message .= "Please enter valid numeric values only for Payment Terms (Minimum)!";
            }
        }
        else{
            $message .= "You forgot to enter Payment Terms (Minimum)!";
        }

        if(isset($_POST['payTermsMax'])){
            if(is_numeric($_POST['payTermsMax']) && $_POST['payTermsMax'] >= 0){
                $_SESSION['apPayTermsMax'] = $_POST['payTermsMax'];
            }
            else{
                $message .= "Please enter valid numeric values only for Payment Terms (Maximum)!";
            }
        }
        else{
            $message .= "You forgot to enter Payment Terms (Maximum)!";
        }

        //Query
        if(!isset($message)){
            $query = "INSERT INTO LOAN_PLAN (BANK_ID, MIN_AMOUNT, MAX_AMOUNT, INTEREST, MIN_TERM, MAX_TERM) 
            VALUES('{$_SESSION['apBank']}', '{$_SESSION['apAmtMin']}', '{$_SESSION['apAmtMax']}','{$_SESSION['apInterest']}', '{$_SESSION['apPayTermsMin']}', '{$_SESSION['apPayTermsMax']}');";
            $result = mysqli_query($dbc, $query);
            $message .= "Bank plan added successfully!";
        }
    }

?>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin - Bootstrap Admin Template</title>

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

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Jo, Melton <b class="caret"></b></a>

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

    <!--<li>

        <a href="ADMIN addaccount.php"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Admin Account</a>

    </li>

    <li>

    <a href="javascript:;" data-toggle="collapse" data-target="#applications"><i class="fa fa-wpforms" aria-hidden="true"></i>&nbsp;Applications<i class="fa fa-fw fa-caret-down"></i></a>

        <ul id="applications" class="collapse">

            <li>
                <a href="ADMIN MEMBERSHIP applications.php"><i class="fa fa-id-card-o" aria-hidden="true"></i>&nbsp;&nbsp;Membership Pending Applications</a>
            </li>

            <li>
                <a href="ADMIN FALP applications.php"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;&nbsp;FALP Pending Applications</a>
            </li>

            <li>
                <a href="ADMIN BANK applications.php"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;Bank Loan Pending Applications</a>
            </li>

            <li>
                <a href="ADMIN HEALTHAID applications.php"><i class="fa fa-medkit" aria-hidden="true"></i>&nbsp;&nbsp;Health Aid Pending Applications</a>
            </li>

        </ul>

    </li>

    <li>

        <a href="ADMIN LIFETIME addmember.php"><i class="fa fa-id-card-o" aria-hidden="true"></i> Add Lifetime Member</a>

    </li>

    <li>

    <a href="javascript:;" data-toggle="collapse" data-target="#bankloans"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;Bank Loans<i class="fa fa-fw fa-caret-down"></i></a>

        <ul id="bankloans" class="collapse">

            <li>
                <a href="ADMIN BANK addbank.php"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;Add Partner Bank</a>
            </li>

            <li>
                <a href="ADMIN BANK editbank.php"><i class="fa fa-gears" aria-hidden="true"></i>&nbsp;&nbsp;Enable/Disable Partner Bank</a>
            </li>

            <li>
                <a href="ADMIN BANK addplan.php"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;&nbsp;Add Bank Loan Plan</a>
            </li>

            <li>
                <a href="ADMIN BANK editplan.php"><i class="fa fa-gears" aria-hidden="true"></i>&nbsp;&nbsp;Enable/Disable Bank Loan Plan</a>
            </li>

        </ul>

    </li>-->
    
            <li>
                <a href="ADMIN MEMBERS viewmembers.php"><i class="fa fa-group" aria-hidden="true"></i>&nbsp;&nbsp;View All Members</a>
            </li>

    <li>

    <a href="javascript:;" data-toggle="collapse" data-target="#loans"><i class="fa fa-money" aria-hidden="true"></i>&nbsp;On-going Loans<i class="fa fa-fw fa-caret-down"></i></a>

        <ul id="loans" class="collapse">

            <li>
                <a href="ADMIN FALP viewactive.php"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;&nbsp;FALP Loans</a>
            </li>

            <!--<li>
                <a href="ADMIN BANK viewactive.php"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;Bank Loans</a>
            </li>-->

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

    <!--<li>

    <a href="javascript:;" data-toggle="collapse" data-target="#repo"><i class="fa fa-folder-open-o" aria-hidden="true"></i>&nbsp;File Repository<i class="fa fa-fw fa-caret-down"></i></a>

        <ul id="repo" class="collapse">

            <li>
                <a href="ADMIN FILEREPO.php"><i class="fa fa-files-o" aria-hidden="true"></i>&nbsp;&nbsp;View Documents</a>
            </li>

            <li>

                <a href="ADMIN FILEREPO upload.php"><i class="fa fa-upload" aria-hidden="true"></i> Upload Documents</a>

            </li>

        </ul>

    </li>

    <li>

        <a href="ADMIN MANAGE.php"><i class="fa fa-gears" aria-hidden="true"></i> Admin Management</a>

    </li>-->

</ul>

            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Add Loan Plan for Bank
                        </h1>
                        <?php
                            if(isset($message)){
                                echo"  
                                <div class='alert alert-warning'>
                                    ". $message ."
                                </div>
                                ";
                            }
                        ?>
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">

                        <p><i>Fields with <big class="req">*</big> are required to be filled out and those without are optional.</i></p>

                        <!--Insert success page--> 
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="addAccount">

                            <div class="row">

                                <div class="col-lg-6">

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Select Bank</b>

                                        </div>

                                        <div class="panel-body">

                                            <div class="row">

                                                <div class="col-lg-12">

                                                    <select class="form-control" name="bank">
                                                <?php 
                                                    $query = "SELECT * FROM BANKS WHERE BANK_ID != 1;";
                                                    $result = mysqli_query($dbc, $query);
                                    
                                                    foreach ($result as $resultRow){
                                                        echo "<option value='" .  $resultRow['BANK_ID'] ."'>" . $resultRow['BANK_ABBV'] . " (" . $resultRow['BANK_NAME'] . ")" . "</option>";
                                                    }
                                                ?>
                                                    </select>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <b>Enter Amount Range:</b><p>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-lg-3">

                                    Amount (Minimum): <input type="number" name="amountMin" class="form-control">

                                </div>

                                <div class="col-lg-3">

                                    Amount (Maximum): <input type="number" name="amountMax" class="form-control">

                                </div>

                            </div><p>

                            <div class="row">
                                <div class="col-lg-12">
                                    <b>Enter Interest %:</b><p>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-lg-3">

                                    Interest %: <input type="number" name="interest" class="form-control">

                                </div>

                            </div><p>

                            <div class="row">
                                <div class="col-lg-12">
                                    <b>Enter Payment Term Range:</b><p>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-lg-3">

                                    Payment Terms (Minimum): <input type="number" name="payTermsMin" class="form-control">

                                </div>

                                <div class="col-lg-3">

                                    Payment Terms (Maximum): <input type="number" name="payTermsMax" class="form-control">

                                </div>

                            </div><p>

                            <div class="row">

                                <div class="col-lg-12">

                                    <input type="submit" class="btn btn-success" name="submit" value="submit">

                                </div>

                            </div>

                        </form>

                    </div>
                </div>


            </div>

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
