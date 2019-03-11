
<?php

require_once('mysql_connect_FA.php');
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';



    if(isset($_POST['action'])){

        $query = "SELECT MEMBER_ID FROM loans WHERE LOAN_ID = ". $_SESSION['showFID'] .";";
        $result = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($result);

        if($_POST['action'] == "Accept Application"){

            //Change the status into Approved (APP_STATUS =2)
            $query = "UPDATE LOANS SET APP_STATUS = '2', LOAN_STATUS= '2', DATE_APPROVED = NOW(), EMP_ID =". $_SESSION['idnum'] ." WHERE LOAN_ID =" . $_SESSION['showFID'].";";
            $result = mysqli_query($dbc, $query);

           //Insert into transaction table
            $queryTnx = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF, EMP_ID, SERVICE_ID) 
            VALUES({$row['MEMBER_ID']}, '1', 'FALP Approved', 0, NOW(), {$_SESSION['showFID']}, {$_SESSION['idnum']}, '4'); ";
            $resultTnx = mysqli_query($dbc, $queryTnx);

            $message = "Accepted" ;


            // put the insert code for the To_Deduct table here.

            //get the current month
            $loanDetails = "SELECT * FROM LOANS WHERE LOAN_ID = {$_SESSION['showFID']}";
            $loanDetailsResult = mysqli_query($dbc,$query);
            $loanDetailsRow = mysqli_fetch_assoc($loanDetailsResult);

            $currMonth = date('m');
            $currDay = date('D');
            $currYear = date('Y');

            //remember that $ans['ID'] is the one that keeps track of the loan id!

            $i = 0; //this will keep track on how many payments has been inserted.
            $payments =  $loanDetailsRow['PAYMENT_TERMS']*2;

            while($i < $payments){  //get the payment terms, and have the counter keep track on how many has been  inserted already.

                //check if it is February first so we can adjust the end date by February 28.

                if($currMonth == 2){
                    if($currDay < 15){

                        $i++;
                        $i++;
                    }else if($currDay < 28){

                        $i++;
                    }else{


                    }

                }else { //this means its just any day of the month

                    if($currDay < 15){


                        $i++;
                    }else if($currDay < 30){

                        $i++;
                    }else{

                        $i++;
                    }
                    //make sure that the curr day resets to 1 - cause all we are after is the first insert in order for us to do thi s\
                }





                //account for the 15th and 30th of the month. This only affects the first date, no worries.

                //check if the current day falls within the 15th and 30th of the month. If the day is over 30, which means 31, then move CurrMoth to the nxt.

                //after pin pointing the month that the shit will fall onto is

                //okay okay

                if($currMoth == 2){ //put an if, february, then feb 28 will be the date to pay the shit.

                    //$dateToInsert = ;

                    $query2 = "INSERT INTO to_deduct(LOAN_REF, DEDUCTION_DATE)
                             values ( )";

                }else{


                }

                //find a way to insert into the To_Deduct table



                //get the number of payments/payment terms.


                //merge the shits into one entire date time


            }


        }

        else if($_POST['action'] == "Reject Application"){
            //Change the status into Approved (APP_STATUS =2)
            $query = "UPDATE LOANS SET APP_STATUS = '3', LOAN_STATUS= '1', DATE_APPROVED = NOW(), EMP_ID =". $_SESSION['idnum'] ." WHERE LOAN_ID =" . $_SESSION['showFID'].";";
            $result = mysqli_query($dbc, $query);

           //Insert into transaction table
            $queryTnx = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF, EMP_ID, SERVICE_ID) 
            VALUES({$row['MEMBER_ID']}, '1', 'FALP Rejected', 0, NOW(), {$_SESSION['showFID']}, {$_SESSION['idnum']}, '4'); ";
            $resultTnx = mysqli_query($dbc, $queryTnx);

            $message = "Rejected";
        }

    }


     //prepare the code in order for us to link the stuff from the database to the View Document Screen.




 if(isset($_POST['download'])){

        $query = "SELECT * FROM falp_requirements WHERE LOAN_ID = ". $_SESSION['showFID'] .";";
       $result = mysqli_query($dbc, $query);
      $row = mysqli_fetch_array($result);

       if($_POST['download'] == "Download ICR"){

          header("Location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/downloadFile.php?loanID=".urlencode(''.$row['ICR_DIR']) );

        }else if($_POST['download'] == "Download Payslip"){

           header("Location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/downloadFile.php?loanID=".urlencode(''.$row['PAYSLIP_DIR']) );

      }else if($_POST['download'] == "Download Employee ID"){

          header("Location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/downloadFile.php?loanID=".urlencode(''.$row['EMP_ID_DIR']) );

      }else if($_POST['download'] == "Download Government ID"){

           header("Location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/downloadFile.php?loanID=".urlencode(''.$row['GOV_ID_DIR']) );

      }

    }

$page_title = 'Loans - Membership Application Details';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';
?>


<body>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            View FALP Loan Details
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

                       <div class="row">

                            <div class="col-lg-12">

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Personal Information</b>

                                        </div>

                                        <div class="panel-body"><p>
                                            <?php 
                                                $query = "SELECT M.MEMBER_ID, M.FIRSTNAME, M.LASTNAME, M.MIDDLENAME FROM LOANS L 
                                                JOIN MEMBER M 
                                                ON L.MEMBER_ID = M.MEMBER_ID
                                                WHERE L.LOAN_ID= ". $_SESSION['showFID'] .";";
                                                $result = mysqli_query($dbc, $query);
                                                $row = mysqli_fetch_array($result);
                                            ?>

                                            <b>ID Number: </b><?php echo $row['MEMBER_ID']; ?> <p>
                                            <b>First Name: </b><?php echo $row['FIRSTNAME']; ?> <p>
                                            <b>Last Name: </b><?php echo $row['LASTNAME']; ?> <p>
                                            <b>Middle Name: </b><?php echo $row['MIDDLENAME']; ?> <p>
                                            
                                        </div>

                                    </div>

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>FALP Details</b>

                                        </div>

                                        <div class="panel-body"><p>
                                            <?php 
                                                $query = "SELECT AMOUNT, PAYABLE, PAYMENT_TERMS, PER_PAYMENT FROM LOANS WHERE LOAN_ID = ". $_SESSION['showFID'] .";";
                                                $result = mysqli_query($dbc, $query);
                                                $row = mysqli_fetch_array($result);
                                            ?>
                                            <b>Amount to Borrow:</b><?php echo $row['AMOUNT']; ?> <p>
                                            <b>Amount Payable:</b><?php echo $row['PAYABLE']; ?> <p>
                                            <b>Payment Terms:</b><?php echo $row['PAYMENT_TERMS']; ?> <p>
                                            <b>Monthly Deductions:</b><?php echo $row['PER_PAYMENT'] * 2; ?> <p>
                                            <b>Number of Payments:</b><?php echo $row['PAYMENT_TERMS'] * 2; ?> <p>
                                            <b>Per Payment Deduction:</b><?php echo $row['PER_PAYMENT']; ?> <p>

                                        </div>

                                    </div>

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b> Application Forms to Review </b>

                                        </div>

                                        <div class="panel-body"><p>
                                                <?php

                                                ?>

                                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

                                                <input type="submit" class="btn btn-success" name="download" value="Download ICR">
                                                <input type="submit" class="btn btn-success" name="download" value="Download Payslip">
                                                <input type="submit" class="btn btn-success" name="download" value="Download Employee ID">
                                                <input type="submit" class="btn btn-success" name="download" value="Download Government ID">

                                            </form>

                                        </div>

                                    </div>

                                <?php
                                $query = "SELECT APP_STATUS FROM LOANS WHERE LOAN_ID = ". $_SESSION['showFID'] .";";
                                $result = mysqli_query($dbc, $query);
                                $row = mysqli_fetch_array($result);

                                if($row['APP_STATUS'] == 1){
                                ?>

                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> <!-- SERVER SELF -->

                                    <div class="panel panel-primary">

                                        <div class="panel-heading">

                                            <b>Actions</b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <input type="submit" class="btn btn-success" name="action" value="Accept Application">
                                            <input type="submit" class="btn btn-danger" name="action" value="Reject Application">

                                        </div>

                                    </div>

                                </form>
                            <?php } ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>



    <script>

        $(document).ready(function(){
    
            $('#table').DataTable();

        });

    </script>

</body>

</html>
