<!DOCTYPE html>
<html lang="en">
<?php
session_start();
require_once('mysql_connect_FA.php');

    if ($_SESSION['usertype'] != 1) {

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
        
    }

    //MENTAL NOTE TO SELF..... DAPAT AYUSIN WHEN THELOAN STARTS! Loans should start when it is APPROVED! just keep this code here for now as placeholder.

    $query = "INSERT INTO loans(MEMBER_ID,LOAN_DETAIL_ID,AMOUNT,INTEREST,PAYMENT_TERMS,PAYABLE,PER_PAYMENT,APP_STATUS,LOAN_STATUS,DATE_APPLIED,PICKUP_STATUS)
    values({$_SESSION['idnum']},1,{$_POST['amount']},{$_POST['interest']},{$_POST['payT']},{$_POST['amountP']},{$_POST['monD']}/2,1,1,DATE(now()),1);";

    mysqli_query($dbc,$query);

    $query = "SELECT MAX(loan_ID) as 'ID' FROM LOANS WHERE MEMBER_ID = {$_SESSION['idnum']};";

    $result = mysqli_query($dbc,$query);

    $ans = mysqli_fetch_assoc($result);


    $query = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE,AMOUNT,SERVICE_TYPE,TXN_DATE,LOAN_REF)
    values({$_SESSION['idnum']},1,0,3,DATE(now()),{$ans['ID']});";

    mysqli_query($dbc,$query);

//function for making the pathways
    function normalizePath($path)
    {
        $parts = array();// Array to build a new path from the good parts
        $path = str_replace('\\', '/', $path);// Replace backslashes with forwardslashes
        $path = preg_replace('/\/+/', '/', $path);// Combine multiple slashes into a single slash
        $segments = explode('/', $path);// Collect path segments
        $test = '';// Initialize testing variable
        foreach($segments as $segment)
        {
            if($segment != '.')
            {
                $test = array_pop($parts);
                if(is_null($test))
                    $parts[] = $segment;
                else if($segment == '..')
                {
                    if($test == '..')
                        $parts[] = $test;

                    if($test == '..' || $test == '')
                        $parts[] = $segment;
                }
                else
                {
                    $parts[] = $test;
                    $parts[] = $segment;
                }
            }
        }
        return implode('/', $parts);
    }


//file upload start


    $loan_id;

    $query = "SELECT MAX(LOAN_ID) as 'loan_id' from loans where member_id = '{$_SESSION['idnum']}'";

    $result = mysqli_query($dbc,$query);

    $loan = mysqli_fetch_assoc($result);

    $loan_id = $loan['loan_id'];

    $user_id = $_SESSION['idnum'];

    $query2 = "SELECT MAX(REQ_ID) from falp_requirements";

    $requirementID = mysqli_fetch_assoc(mysqli_query($dbc,$query2));

    $requirementID2 = $requirementID['MAX(REQ_ID)'];

    $requirementID3 = $requirementID2;



        $incomeTaxCheck = false; // if there are no errors in the income tax

        $payslipCheck = false;  // if there is any errors in payslip

        $emp_IDCheck = false; // if there are any errors in emp id

        $gov_IDCheck = false; // if there are any errors in gov ID

        $incomeTaxDirectory; // stores the supposed directory of the incomeTax

        $payslipDirectory; // stores the supposed payslip directoy

        $gov_IDDirectory; // stores the gov_ID directory

        $emp_IDDirectory; // stores the emp ID directory



        // income tax part

        // now it makes the directories since there are no more errors

        $directoryName = "FALP_Requirements"; // user id
        if (!is_dir($directoryName)) {
            //Directory does not exist, so lets create it.
            mkdir($directoryName, 0755);
        }


        $directoryName1 = $directoryName . "/" . $user_id; // user id
        if (!is_dir($directoryName1)) {
            //Directory does not exist, so lets create it.
            mkdir($directoryName1, 0755);
        }

        // makes a folder inside the directory folder of the user id - then it will create a folder based on the requirement id
        $directoryName2 = $directoryName1 . "/" . $requirementID3;  // bibigay niya yung loan id/requirement id - Yung sinasabi ni patrick na (recent id + 1)

        if (!is_dir($directoryName2)) {
            //Directory does not exist, so lets create it.
            mkdir($directoryName2, 0755);
        }

        $directoryName3 = $directoryName2 . "/FALPRequirements";
        //Check if the directory already exists.;
        if (!is_dir($directoryName3)) {
            //Directory does not exist, so lets create it.
            mkdir($directoryName3, 0755);
        }

        // creates a directory ICR in the directory and uploads the iCR file there

        $name = $_FILES["IncomeTax"]["name"];

        $tmp_name = $_FILES['IncomeTax']['tmp_name'];

        $location = $directoryName3 . "/ICR";

        if (!is_dir($location)) {
            //Directory does not exist, so lets create it.
            mkdir($location, 0755);
        }

        if (move_uploaded_file($tmp_name, $location . "/" . $name)) { // moves the file and checks if the file move is sucessful.

            $location = $location . "/" . $name;

            $realest = realpath($location);

            $realest = normalizePath($realest);

            $incomeTaxDirectory = $realest;

            echo $incomeTaxDirectory;

            $incomeTaxCheck = true;

        } else {

            echo '<script language="javascript">';

            echo 'alert("There was a problem in the Income Tax file upload. ")';

            echo '</script>';

            $incomeTaxCheck = false;
        }

        // creates a directory payslip and uploads the payslip file there

        $name = $_FILES["payslip"]["name"];

        $tmp_name = $_FILES['payslip']['tmp_name'];

        $location = $directoryName3 . "/Payslip";

        if (!is_dir($location)) {
            //Directory does not exist, so lets create it.
            mkdir($location, 0755);
        }


        if (move_uploaded_file($tmp_name, $location . "/" . $name)) { // moves the file and checks if the file move is sucessful.

            $location = $location . "/" . $name;

            $realest = realpath($location);

            $realest = normalizePath($realest);

            $payslipDirectory = $realest;

            echo $payslipDirectory;

            $payslipCheck = true;

        } else {

            echo '<script language="javascript">';

            echo 'alert("There was a problem in the Payslip file upload. ")';

            echo '</script>';

            $payslipCheck = false;


        }

        //creates a directory emp_ID and uploads the emp_ID there
        $name = $_FILES["emp_ID"]["name"];

        $tmp_name = $_FILES['emp_ID']['tmp_name'];

        $location = $directoryName3 . "/Employee_ID";

        if (!is_dir($location)) {
            //Directory does not exist, so lets create it.
            mkdir($location, 0755);
        }


        if (move_uploaded_file($tmp_name, $location . "/" . $name)) { // moves the file and checks if the file move is sucessful.

            $location = $location . "/" . $name;

            $realest = realpath($location);

            $realest = normalizePath($realest);

            $emp_IDDirectory = $realest;

            echo $emp_IDDirectory;

            $emp_IDCheck = true;

        } else {

            echo '<script language="javascript">';

            echo 'alert("There was a problem in the EMP ID file upload. ")';

            echo '</script>';

            $emp_IDCheck = false;

        }

        // creates a directory  gov ID and uploads gov_ID there
        $name = $_FILES["gov_ID"]["name"];

        $tmp_name = $_FILES['gov_ID']['tmp_name'];

        $location = $directoryName3 . "/Government_ID";

        if (!is_dir($location)) {
            //Directory does not exist, so lets create it.
            mkdir($location, 0755);
        }


        if (move_uploaded_file($tmp_name, $location . "/" . $name)) { // moves the file and checks if the file move is sucessful.

            $location = $location . "/" . $name;

            $realest = realpath($location);

            $realest = normalizePath($realest);

            $gov_IDDirectory = $realest;

            echo $gov_IDDirectory;

            $gov_IDCheck = true;

        } else {

            echo '<script language="javascript">';

            echo 'alert("There was a problem in the Gov_ID file upload. ")';

            echo '</script>';

            $gov_IDCheck = false;

        }


        // first insert the loan details


        // then insert the document requirement and its repsective  directories.

        $query2 = "INSERT into falp_requirements(LOAN_ID,MEMBER_ID,ICR_DIR,PAYSLIP_DIR,EMP_ID_DIR,GOV_ID_DIR)               
                        
                        values({$loan_id},{$user_id},'{$incomeTaxDirectory}','{$payslipDirectory}','{$emp_IDDirectory}','{$gov_IDDirectory}')";

        if (!mysqli_query($dbc, $query2)) { // error checking

            echo("Error description: " . mysqli_error($dbc) . "<br>");

        } else {

            echo 'Sucessfully inserted loans without any problems!';

            header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/MEMBER FALP appsent.php");


        }

        // then updates the transaction table to reflect that I have done this transaction already


        $query3 = "INSERT INTO txn_reference(MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF, SERVICE_ID) 
                                                    values({$user_id}, 1 ,'Pending','" . $_POST["amount"] . "',DATE(NOW()),{$loan_id}, 4) ";


        if (!mysqli_query($dbc, $query3)) { // error checking

            echo("Error description: " . mysqli_error($dbc) . "<br>");

        } else {

            echo 'Sucessfully inserted into Transaction referrences without any problems!';

            header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/MEMBER FALP appsent.php");


        }




?>
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


                </ul>

            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">FALP</h1>
                    
                    </div>

                </div>

                <div class="row"> <!-- Well -->

                    <div class="col-lg-1 col-1">



                    </div>

                    <div class="col-lg-10 col-2 well">

                    <p class="welltext justify">Congratulations! You have successfully completed the steps in applying for a FALP Loan.  The admins will process and evaluate your application.  You will receive a notification whether your application is approved or not. Once your application has been approved, you will receive further instructions.</p>

                    <p class="welltext justify"><font color="red">Please review your submitted values from the form before proceeding.</font></p>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <div align="center">

                            <a href="MEMBER dashboard.php" class="btn btn-success" role="button">OK</a>

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
