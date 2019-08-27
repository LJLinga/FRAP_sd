<?php

require_once ("mysql_connect_FA.php");
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



    $queryMem = "SELECT *
                 FROM MEMBER AS M
                 JOIN REF_DEPARTMENT AS D
                 ON M.DEPT_ID = D.DEPT_ID
                 JOIN CIV_STATUS AS C
                 ON M.CIV_STATUS = C.STATUS_ID
                 WHERE M.MEMBER_ID = '{$_SESSION['memapp_selected_id']}';";

    $resultMem = mysqli_query($dbc, $queryMem);
    $rowMem = mysqli_fetch_array($resultMem);

    if ($rowMem['SEX'] == 1) {
        $sex = "Male";
    }

    else {
        $sex = "Female";
    }

    if (!empty($rowMem['BUSINESS_NUM'])) {
        $businessnum = $rowMem['BUSINESS_NUM'];
    }

    else {
         $businessnum = "No business number";
    }

    if (!empty($rowMem['BUSINESS_ADDRESS'])) {
        $businessaddress = $rowMem['BUSINESS_ADDRESS'];
    }

    else {
         $businessadd = "No business address";
    }
 $query = "SELECT * FROM member m 
              where m.member_id = {$_SESSION['memapp_selected_id']}";
    $result = mysqli_query($dbc,$query);
    $ans = mysqli_fetch_assoc($result);
    if (isset($_POST['accept'])) {

       
         

         // Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
         


// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "duminacc@gmail.com";
$mail->Password = "rewq/4321";
$mail->SetFrom("duminacc@gmail.com");
$mail->Subject = "RE: Membership account for Faculty Association";
$mail->Body = 'THIS IS AN AUTO-GENERATED MESSAGE PLEASE DO NOT REPLY.<br>------------<br>Your account has been approved and now able to use the system.<br> To login use the password 1234 to change your password<br>';
$mail->AddAddress($ans['EMAIL']);

 if(!$mail->Send()) {
    echo '<script language="javascript">';

                echo 'alert("Was not able to send email. Please check if email is valid or if you are online")';

                echo '</script>';;;
 } else {
     $queryAccept = "UPDATE MEMBER SET MEMBERSHIP_STATUS = 2, DATE_APPROVED = DATE(NOW()), EMP_ID_APPROVE = {$_SESSION['idnum']}
                        WHERE MEMBER_ID = {$_SESSION['memapp_selected_id']};";

        $resultAccept = mysqli_query($dbc, $queryAccept);

        $queryTxn = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, EMP_ID, SERVICE_ID) 
                     VALUES ('{$_SESSION['memapp_selected_id']}', 1, 'Membership Application Approved', 0, DATE(NOW()), '{$_SESSION['idnum']}', 1);";

        $resultAccept = mysqli_query($dbc, $queryTxn);;
        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN MEMBERSHIP applications.php");
 }


    }


    else if (isset($_POST['reject'])) {

        
         
         // Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "duminacc@gmail.com";
$mail->Password = "rewq/4321";
$mail->SetFrom("duminacc@gmail.com");
$mail->Subject = "RE: Membership account for Faculty Association";
$mail->Body = 'THIS IS AN AUTO-GENERATED MESSAGE PLEASE DO NOT REPLY.<br>------------<br>Your account has been Rejected. Please send the correct or missing information when applying again';
$mail->AddAddress($rowMem['EMAIL']);
$queryReject = "DELETE FROM MEMBER_ACCOUNT WHERE MEMBER_ID = {$_SESSION['memapp_selected_id']};";

        $resultReject = mysqli_query($dbc, $queryReject);

        $queryReject = "DELETE FROM MEMBER WHERE MEMBER_ID = {$_SESSION['memapp_selected_id']};";

        $resultReject = mysqli_query($dbc, $queryReject);
        $queryTxn = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, EMP_ID, SERVICE_ID) 
                     VALUES ('{$_SESSION['memapp_selected_id']}', 1, 'Membership Application Rejected', 0, DATE(NOW()), '{$_SESSION['idnum']}', 1);";

        $resultAccept = mysqli_query($dbc, $queryTxn);;
 if(!$mail->Send()) {
    echo '<script language="javascript">';

                echo 'alert("User was rejected but was not able to send email. Please remind the member for rejection")';

                echo '</script>';;
 } else {
    
        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN MEMBERSHIP applications.php");
 }



    }else if (isset($_POST['compare'])) {

        //first checks if the excel is empty

        //after checking and if its not empty, compare if the value exists in the goddamn id row.


        $queryCompare = "select count(ID) as 'num' from excel_members;";
        $queryCompareResult = mysqli_query($dbc, $queryCompare);
        $queryCompareRow = mysqli_fetch_array($queryCompareResult);



        if($queryCompareRow['num'] == 0){

            echo '<script language="javascript">';

            echo 'alert(" You have not filled up the Database yet! Upload an excel sheet for reference first. Head to Migration Tools, and click on Import Members Referrence")';

            echo '</script>';

        }else {

            $queryCompare = "SELECT * from excel_members where DLSU_ID = {$rowMem['MEMBER_ID']} ";
            $queryCompareResult = mysqli_query($dbc, $queryCompare);
            $queryCompareRow = mysqli_fetch_array($queryCompareResult);

            if(!empty($queryCompareRow)){ //meaning they matched!

                $message = "ID Number was found in the excel sheet! Name associated with it is ".$queryCompareRow['DLSU_NAME'];

                echo '<script type="text/javascript">alert("'.$message.'");</script>';

            }else{

                echo '<script language="javascript">';

                echo 'alert("User was not found in the Member List.")';

                echo '</script>';

            }

        }





    }

$page_title = 'Loans - Membership Application Details';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';

?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            View Member Details
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">

                       <div class="row">

                            <div class="col-lg-12">

                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> <!-- SERVER SELF -->

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Personal Information <?php  ;?><p></b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <b>ID Number: </b><?php echo $rowMem['MEMBER_ID']; ?> <p>
                                            <b>First Name: </b><?php echo $rowMem['FIRSTNAME']; ?> <p>
                                            <b>Last Name: </b><?php echo $rowMem['LASTNAME']; ?> <p>
                                            <b>Middle Name: </b><?php echo $rowMem['MIDDLENAME']; ?> <p>
                                            <b>Email: </b><?php echo $rowMem['EMAIL']; ?> <p>
                                            <b>Civil Status: </b><?php echo $rowMem['STATUS']; ?> <p>
                                            <b>Date of Birth: </b><?php echo date('Y, M d', strtotime($rowMem['BIRTHDATE'])); ?> <p>
                                            <b>Sex: </b><?php echo $sex; ?> <p>
                                            
                                        </div>

                                    </div>

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Employment Information</b>

                                        </div>

                                        <div class="panel-body"><p>
                                            <b>Employee Category:</b> 
                                            <?php if($rowMem['EMP_TYPE']==1)
                                                    echo "Full Time";
                                                    else
                                                        echo "Part Time";
                                            ?> <p>
                                            <b>Employee TYPE:</b> <?php echo $rowMem['TYPE'];?> <p>
                                            <b>Employee Status:</b> <?php echo $rowMem['EMP_STATUS'];?> <p>
                                            
                                            <b>Date of Hiring: </b><?php echo date('Y, M d', strtotime($rowMem['DATE_HIRED'])); ?> <p>
                                            <b>Department: </b><?php echo $rowMem['DEPT_NAME']; ?> <p>

                                        </div>

                                    </div>

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Contact Information</b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <b>Home Phone Number: </b><?php echo $rowMem['HOME_NUM']; ?> <p>
                                            <b>Business Phone Number: </b><?php echo $businessnum ?> <p>
                                            <b>Home Address: </b><?php echo $rowMem['HOME_ADDRESS']; ?> <p>
                                            <b>Business Address: </b><?php echo $businessadd ?> <p>

                                        </div>

                                    </div>

                                    <div class="panel panel-primary">

                                        <div class="panel-heading">

                                            <b>Actions</b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <input type="submit" class="btn btn-success" name="accept" value="Accept Application">
                                            <input type="submit" class="btn btn-danger" name="reject" value="Reject Application">
                                                <input type="submit" class="btn btn-info" name="compare" value="Check Applicant ID">

                                        </div>

                                    </div>

                                    <a href="ADMIN MEMBERSHIP applications.php" class="btn btn-default">Go Back</a><p><p>&nbsp;

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
   

    <!-- Bootstrap Core JavaScript -->
   <?php include 'GLOBAL_FOOTER.php' ?>
