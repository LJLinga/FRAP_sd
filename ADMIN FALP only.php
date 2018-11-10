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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<?php

session_start();
require_once("mysql_connect_FA.php");
/*-------FILE REPO STUFF------*/
//$_SESSION['parentFolderID']="";
//$_SESSION['currentFolderID']="1HyfFzGW48DJfK26lN_cYtKBhRCrQJbso";
/*-------FILE REPO STUFF END------*/
    if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

    header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

    }
    $success = null;




    if (isset($_POST['submit'])) {

        if(!empty($_POST['idNum'])){

            $idNum = $_POST['idNum'];

            //add an if statement here to see if the guy you are going to add has paid 50% of his loan, then say if he cannot have another loan unless he has paid 50%
            $query = "SELECT * FROM LOANS where {$idNum} = MEMBER_ID && APP_STATUS = 2  ";
            $result = mysqli_query($dbc, $query);
            $row = mysqli_fetch_assoc($result);

            $halfAmount = $row['PAYABLE']/2; //the halved amount of the payment the person needs to pay with salary deduction

            if($halfAmount > $row['AMOUNT_PAID']){

                echo '<script language="javascript">';
                echo 'alert("This person has a current Loan and has not paid 50% of it!")';
                echo '</script>';

            }else{

                $query = "INSERT INTO loans(MEMBER_ID,LOAN_DETAIL_ID,AMOUNT,INTEREST,PAYMENT_TERMS,PAYABLE,PER_PAYMENT,APP_STATUS,LOAN_STATUS,DATE_APPLIED,PICKUP_STATUS)
                      values({$idNum},1,{$_POST['amount']},5,{$_POST['terms']},{$_POST['amount']}+{$_POST['amount']}*5/100,({$_POST['amount']}+{$_POST['amount']}*5/100)/{$_POST['terms']},2,2,DATE(now()),1);";

                mysqli_query($dbc,$query);

                $success = "yes";

            }

        }else{
            echo '<script language="javascript">';
            echo 'alert("Select a Member first!")';
            echo '</script>';
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

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Add FALP Account to Member
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">

                        <!--Insert success page--> 
                        <form method="POST" action="ADMIN%20FALP%20only.php" id="addAccount" onSubmit="return checkform()">

                            <div class="panel panel-green">

                                <div class="panel-heading">

                                    <b>List of People</b>

                                </div>
                               

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <table id="table" class="table table-bordered table-striped">

                                                <thead>

                                                <tr>
                                                    <td align="center"></td>
                                                    <td align="center"><b>ID Number</b></td>
                                                    <td align="center" width="300px"><b>Name</b></td>
                                                    <td align="center"><b>Department</b></td>
                                                    <td align="center"><b>Member Since</b></td>


                                                </tr>

                                                </thead>

                                                <tbody>
                                                <?php
                                                $query2 = "SELECT * FROM member m join ref_department d
                                                          on m.dept_id = d.dept_id where m.membership_status = 2";
                                                        $result2 = mysqli_query($dbc,$query2);



                                                while($row2 = mysqli_fetch_assoc($result2)){

                                                    ?>
                                                    <tr>

                                                        <td align="center"><?php echo "<input type='radio' name='idNum' value='".$row2['MEMBER_ID']."'>" ; ?></td>
                                                        <td align="center"><?php echo $row2['MEMBER_ID'];?></td>
                                                        <td align="center"><?php echo $row2['FIRSTNAME']." ".$row2['LASTNAME'];?> </td>
                                                        <td align="center"><?php echo $row2['DEPT_NAME'];?></td>
                                                        <td align="center"><?php echo $row2['DATE_APPROVED'];?></td>

                                                    </tr>
                                                <?php }?>


                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                            <div class="panel panel-green">

                                <div class="panel-heading">

                                    <b>FALP  Information</b>
                                </div>

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-4">

                                            <label class="memfieldlabel">Amount</label><big class="req"> *</big>
                                            <input type="number" class="form-control" placeholder="Enter Amount (Peso)" name="amount" id="amount">

                                        </div>

                                    </div>

                                    <p>

                                    <div class="row">

                                        <div class="col-lg-4">
                                            
                                            <label class="memfieldlabel">Payment Terms</label><big class="req"> *</big>
                                            <input type="number" class="form-control" placeholder="Payment Terms" name="terms"  id="terms">
                                            <p>
                                            <div id = "totalI">   </div> <p>
                                            <p>
                                            <div id = "totalP"> </div><p>
                                            <p>
                                            <div id = "PerP"></div><p>
                                            <p>
                                            <div id = "Monthly"></div>

                                            <input type="button" name="compute" class="btn btn-success" value="Compute" id="falpcompute">
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <input class="btn btn-success" type="submit" name="submit" value="Submit"></p>

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
       
        
   
    <?php if (!empty($success)) {
        
        echo "<script type='text/javascript'>alert('Success!');</script>";
        
    }

    ?>
    <!-- Bootstrap Core JavaScript -->

    <script type="text/javascript" src="DataTables/datatables.min.js"></script>

    <script src="js/bootstrap.min.js"></script>
<script>
        document.getElementById("falpcompute").onclick = function() {calculate()};
        
        function calculate(){
            
            var amount = parseFloat(document.getElementById("amount").value);
            var terms = parseFloat(document.getElementById("terms").value);
            var interest = 5;
            
            document.getElementById("totalI").innerHTML ="<b>Total Interest Payable: </b>₱"+ parseFloat((amount*interest/100)).toFixed(2);
            document.getElementById("totalP").innerHTML ="<b>Total Amount Payable: </b> ₱"+ parseFloat((amount+amount*interest/100)).toFixed(2);
            document.getElementById("PerP").innerHTML ="<b>Per Payment Period Payable: </b> ₱ "+ parseFloat(((amount+amount*interest/100)/terms)).toFixed(2);
            document.getElementById("Monthly").innerHTML ="<b>Monthly Payable: </b> ₱"+ parseFloat(((amount+amount*interest/100)/(terms/2))).toFixed(2);
            
        }
        
        function checkform(){

            var amount = parseFloat(document.getElementById("amount").value);
            var terms = parseFloat(document.getElementById("terms").value);

            if(isNaN(amount)||isNaN(terms)){
                alert("Invalid Input in FALP Account Information");
                return false;
            }
            return true;

        }
    </script>

</body>

</html>
