<?php
session_start();
require_once("mysql_connect_FA.php");
if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}
$bank_loan_id = $_SESSION['bank_loan_id'];



// checks if the shit that was posted was loan_id
// first we would want to get the personal information
$query = "select m.member_id,m.firstname, m.lastname, m.middlename
from loans l
join member m
on l.member_id = m.member_id
where l.loan_id ={$bank_loan_id}" ;
        
$result= mysqli_query($dbc,$query);

$personal_info = mysqli_fetch_array($result,MYSQLI_ASSOC); // use this when referring to the personal information of the person

// first we would want to get the loan information
$query2 = "select b.bank_name, l.payable, l.amount, l.payment_terms, l.per_payment
            from loans l 
            join loan_plan lp 
            on l.loan_detail_id = lp.loan_id
            join banks b
            on lp.bank_id = b.bank_id
            where l.loan_ID = {$bank_loan_id}" ;

$result2= mysqli_query($dbc,$query2);

$loan_info = mysqli_fetch_array($result2,MYSQLI_ASSOC);

// first we would want to get the directories of the files 
$query3= " select br.ICR_DIR, br.PAYSLIP_DIR, br.EMP_ID_DIR, br.GOV_ID_DIR
            from loans l
            join bank_requirements br
            on l.loan_id = br.loan_id
            where l.loan_id = {$bank_loan_id}" ;

$result3= mysqli_query($dbc,$query3);

$directories = mysqli_fetch_array($result3,MYSQLI_ASSOC);


    if(isset($_POST['Accept'])){     // checks if it was the accept/reject

        // updates the condition to 2 - which is accepted 
        $query="UPDATE loans SET APP_STATUS = 2, LOAN_STATUS = 2 WHERE '".$_SESSION['bank_loan_id']."' = loan_id";
        
        mysqli_query($dbc,$query);

        $query5 = "INSERT INTO txn_reference(MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF,EMP_ID , DATE_APPROVED, SERVICE_ID) 
                    values('".$personal_info['member_id']."', 1 ,'Approved','".$loan_info["amount"]."',DATE(NOW()),{$bank_loan_id}, '".$_SESSION['user_id']."' ,NOW(),4) ";


         if (!mysqli_query($dbc,$query5)){ // error checking

             echo("Error description: " . mysqli_error($dbc) . "<br>");

            }else{

                 echo'Sucessfully inserted into Transaction referrences without any problems!';

                 header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER BANKLOAN appsent.php");


        }
        //updates user's transaction shit list

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN BANK applications.php");

    }else if(isset($_POST['Reject'])){ // checks if it was reject

            // updates the condition to 2 - which is accepted 
        $query="UPDATE loans SET APP_STATUS = 3 WHERE '".$_SESSION['bank_loan_id']."' = loan_id";
            
        mysqli_query($dbc,$query); 


        //updates user's transaction shit list

         $query5 = "INSERT INTO txn_reference(MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF,EMP_ID , SERVICE_ID) 
                        values('".$personal_info['member_id']."', 1 ,'Rejected','".$loan_info["amount"]."',DATE(NOW()),{$bank_loan_id}, '".$_SESSION['user_id']."' ,4) ";



         if (!mysqli_query($dbc,$query5)){ // error checking

             echo("Error description: " . mysqli_error($dbc) . "<br>");

            }else{

                 echo'Sucessfully inserted into Transaction referrences without any problems!';

                 header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER BANKLOAN appsent.php");


            }






        //redirection 

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN BANK applications.php");


    }else if(isset($_POST['ITR'])){ // if the icr was clicked

        $ITR = $directories['ICR_DIR'];

        header("Location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/downloadFile.php?loanID=".urlencode(''.$ITR) ); 

     
    }else if(isset($_POST['payslip'])){ // if the payslip

        $ITR = $directories['PAYSLIP_DIR'];

        header("Location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/downloadFile.php?loanID=".urlencode(''.$ITR) ); 

    }else if(isset($_POST['empID'])){ // if the shit was clikced

        $ITR = $directories['EMP_ID_DIR'];

        header("Location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/downloadFile.php?loanID=".urlencode(''.$ITR) ); 

    }else if(isset($_POST['govID'])){

        $ITR = $directories['GOV_ID_DIR'];

        header("Location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/downloadFile.php?loanID=".urlencode(''.$ITR) ); 


    }else{

            echo "I dont the hell know what you clicked. Seriously. wtf. ";
    }

?>

<!DOCTYPE html>
<html lang="en">

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
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>

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
                            View Bank Loan Details
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">

                       <div class="row">

                            <div class="col-lg-12">

                                <form action="#" method="POST"> <!-- SERVER SELF -->

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Personal Information</b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <b>ID Number: <?php echo $personal_info['member_id'];?> </b> <p>
                                            <b>First Name: <?php echo $personal_info['firstname'];?></b> <p>
                                            <b>Last Name: <?php echo $personal_info['lastname'];?></b> <p>
                                            <b>Middle Name: <?php echo $personal_info['middlename'];?></b> <p>
                                            
                                        </div>

                                    </div>


                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Bank Loan Details</b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <b>Bank of Choice:  <?php echo $loan_info['bank_name'];?></b> <p>
                                            <b>Amount to Borrow:  <?php echo $loan_info['amount'];?></b> <p>
                                            <b>Amount Payable:  <?php echo $loan_info['payable'];?></b> <p>
                                            <b>Payment Terms:  <?php echo $loan_info['payment_terms'];?></b> <p>
                                            <b>Monthly Deductions:  <?php echo ($loan_info['per_payment']*2); ?></b> <p>
                                            <b>Number of Payments:  <?php echo ($loan_info['payment_terms']*2);?></b> <p>
                                            <b>Per Payment Deduction:  <?php echo $loan_info['per_payment'];?></b> <p>

                                        </div>

                                    </div>

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Download Uploaded Requirements</b>

                                        </div>

                                        <div class="panel-body"><p>
                                         

                                            <input type="submit" class="btn btn-primary"  name="ITR" value="Download Income Tax Return">
                                            <input type="submit" class="btn btn-primary"  name="payslip" value="Download Payslip">
                                            <input type="submit" class="btn btn-primary"  name="govID" value="Download Government ID">
                                            <input type="submit" class="btn btn-primary"  name="empID" value="Download Employee ID">


                                        </div>

                                    </div>


                                    <div class="panel panel-primary">

                                        <div class="panel-heading">

                                            <b>Actions</b>
                                      
                                        </div>

                                        <div class="panel-body"><p>

                                            <input type="submit" class="btn btn-success" name="Accept" value="Accept Application">
                                            <input type="submit" class="btn btn-danger" name="Reject" value="Reject Application">

                                            </form>
                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

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

    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script>

        $(document).ready(function(){
    
            $('#table').DataTable();

        });

    </script>

</body>

</html>
