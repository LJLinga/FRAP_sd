<?php
    session_start();
    require_once('mysql_connect_FA.php');
    if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

    header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

    }
    

    $bank_loan_id = $_SESSION['bank_loan_id'];

    $query1 = "select m.member_id,m.firstname, m.lastname, l.amount, l.payable, l.payment_terms, l.per_payment, l.date_approved, l.payments_made, l.amount_paid, ls.status
    from loans l
    join member m
    on l.member_id = m.member_id
    join loan_status ls
    on l.loan_status = ls.status_id
    where l.loan_id ={$bank_loan_id}" ;
            
    $result1 = mysqli_query($dbc,$query1);

    $loan_info = mysqli_fetch_array($result1 ,MYSQLI_ASSOC); // use this when referring to the personal information of the person


?>




<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>FRAP | Bank Loan Summary</title>

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

                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header"><?php echo $loan_info['firstname']." ".$loan_info['lastname'] ?> 's Bank Loan Summary</h1>
                    
                    </div>

                </div>

                    <div class="row">

                        <div class="col-lg-6">

                            <div class="panel panel-primary">

                                <div class="panel-heading">

                                    <b>Current Bank Loan Plan</b>

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
                                    <td><?php echo $loan_info['amount']; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Amount Payable</td>
                                    <td><?php echo $loan_info['payable']; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Payment Terms</td>
                                    <td><?php echo $loan_info['payment_terms']; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Monthly Deduction</td>
                                    <td><?php echo $loan_info['per_payment']*2; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Number of Payments</td>
                                    <td><?php echo $loan_info['payment_terms']*2; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Per Payment Deduction</td>
                                    <td><?php echo $loan_info['per_payment']; ?></td>

                                    </tr>

                                </tbody>

                                </table>

                                </div>

                            </div>

                        </div>

                        <div class="col-lg-6">

                            <div class="panel panel-green">

                                <div class="panel-heading">

                                    <b>Current Bank Loan Summary</b>

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
                                    <td><?php echo DATE($loan_info['date_approved']); ?></td>

                                    </tr>

                                    <tr>

                                    <td>Payments Made</td>
                                    <td><?php echo $loan_info['payments_made']; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Payments Left</td>
                                    <td><?php echo $loan_info['payment_terms']*2-$loan_info['payments_made']; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Total Amount Paid</td>
                                    <td><?php echo $loan_info['amount_paid']; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Outstanding Balance</td>
                                    <td><?php echo $loan_info['amount']-$loan_info['amount_paid']; ?></td>

                                    </tr>

                                    <tr>

                                    <td>Status</td>
                                    <td><?php echo $loan_info['status']; ?></td>

                                    </tr>

                                </tbody>

                                </table>

                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="row">

                        <div class="col-lg-12">

                            <div align="center">

                            <a href="ADMIN BANK viewactivity.php" class="btn btn-success" role="button">View Payment Activity</a>
                            <a href="ADMIN viewactive.php" class="btn btn-default" role="button">Go Back</a>

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

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
