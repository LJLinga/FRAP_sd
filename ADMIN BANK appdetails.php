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

    $page_title = 'Loans - Bank Application Details';
    include 'GLOBAL_HEADER.php';
    include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';

?>
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

    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script>
        $(document).ready(function(){
    
            $('#table').DataTable();

        });
    </script>
</body>
</html>
