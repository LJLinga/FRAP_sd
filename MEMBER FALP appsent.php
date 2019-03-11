<!DOCTYPE html>
<html lang="en">
<?php
    error_reporting(0);
    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';


    $queryCHECK = "SELECT MAX(LOAN_ID), APP_STATUS from loans where member_id = {$_SESSION['idnum']} ";
    $resultCHECK = mysqli_query($dbc,$queryCHECK);
    $rowCHECK = mysqli_fetch_assoc($resultCHECK);

    if($rowCHECK['LOAN_STATUS'] == 1){ //checks if you have a pending loan

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP failed.php");

    }else if($rowCHECK['LOAN_STATUS'] == 2) { //checks if you have a loan that is ongoing.

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP summary.php");

    }else if(!$_POST['fromReqPage'] && empty($rowCHECK) ){ //if they have not applied yet

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP application.php");

    }

//MENTAL NOTE TO SELF..... DAPAT AYUSIN WHEN THELOAN STARTS! Loans should start when it is APPROVED! just keep this code here for now as placeholder.

    $query = "INSERT INTO loans(MEMBER_ID,AMOUNT,INTEREST,PAYMENT_TERMS,PAYABLE,PER_PAYMENT,APP_STATUS,LOAN_STATUS,DATE_APPLIED)
    values({$_SESSION['idnum']},{$_POST['amount']},{$_POST['interest']},{$_POST['payT']},{$_POST['amountP']},{$_POST['monD']}/2,1,1,DATE(now()));";

    mysqli_query($dbc,$query);

    //heres the code to insert into the transaction referrence (audit trail)  database.

    $query = "SELECT MAX(loan_ID) as 'ID' FROM LOANS WHERE MEMBER_ID = {$_SESSION['idnum']};";

    $result = mysqli_query($dbc,$query);

    $ans = mysqli_fetch_assoc($result);

    $desc = "Applied for FALP"; //always change the description

    $amount = $_POST['amount'];

    $query2 = "INSERT INTO txn_reference(MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF, SERVICE_ID)
                             values ({$_SESSION['idnum']}, 1, {$desc}, {$amount}, DATE(now()), {$ans['ID']}, 4)";
    // SERVICE ID : 1 - Membership, 2 - Health Aid, 4 - FALP
    // TXN_TYPE : 1 - Application 2 - Deduction
    mysqli_query($dbc,$query2);



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

    $query = "SELECT LOAN_ID  from loans where member_id = '{$_SESSION['idnum']}' ORDER BY LOAN_ID DESC LIMIT 1;";

    $result = mysqli_query($dbc,$query);

    $loan = mysqli_fetch_assoc($result);

    $loan_id = $loan['LOAN_ID'];

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




        }

        // then updates the transaction table to reflect that I have done this transaction already


        $query3 = "INSERT INTO txn_reference(MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF, SERVICE_ID) 
                                                    values({$user_id}, 1 ,'Pending','" . $_POST["amount"] . "',DATE(NOW()),{$loan_id}, 4) ";


        if (!mysqli_query($dbc, $query3)) { // error checking

            echo("Error description: " . mysqli_error($dbc) . "<br>");

        } else {




        }

$page_title = 'Loans - FALP Application Sent';
include 'GLOBAL_HEADER.php';
include 'FRAP_USER_SIDEBAR.php';

?>

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

<?php include 'GLOBAL_FOOTER.php' ?>
