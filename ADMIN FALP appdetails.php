<?php
    session_start();
    require_once('mysql_connect_FA.php');
    if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}
     //Test value
    //$_SESSION['idnum']=1141231234;
    $_SESSION['curFALPAmount'] = Null;
    //$message = "MEM ID" . $_SESSION['showFMID'] . " Loan ID " . $_SESSION['showFID'] . " Admin " . $_SESSION['adminidnum'];
    if(isset($_POST['action'])){
        $query = "SELECT AMOUNT FROM LOANS WHERE LOAN_ID =". $_SESSION['showFID'] .";";
        $result = mysqli_query($dbc, $query);

        if($_POST['action'] == "Accept Application"){
            //Change the status into Approved (APP_STATUS =2)
            $query = "UPDATE LOANS SET APP_STATUS = '2', LOAN_STATUS= '2', DATE_APPROVED = NOW(), EMP_ID =". $_SESSION['idnum'] ." WHERE LOAN_ID =" . $_SESSION['showFID'].";";
            $result = mysqli_query($dbc, $query);

           //Insert into transaction table
            $queryTnx = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF, EMP_ID, SERVICE_TYPE) 
            VALUES({$_SESSION['showFMID']}, '1', 'FALP Approved', 0, NOW(), {$_SESSION['showFID']}, {$_SESSION['idnum']}, '2'); ";
            $resultTnx = mysqli_query($dbc, $queryTnx);

            $message = "Accepted" . $queryTnx;
        }
        else if($_POST['action'] == "Reject Application"){
            //Change the status into Approved (APP_STATUS =2)
            $query = "UPDATE LOANS SET APP_STATUS = '1', LOAN_STATUS= '1', DATE_APPROVED = NOW(), EMP_ID =". $_SESSION['idnum'] ." WHERE LOAN_ID =" . $_SESSION['showFID'].";";
            $result = mysqli_query($dbc, $query);

           //Insert into transaction table
            $queryTnx = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF, EMP_ID, SERVICE_TYPE) 
            VALUES({$_SESSION['showFMID']}, '1', 'FALP Rejected', 0, NOW(), {$_SESSION['showFID']}, {$_SESSION['idnum']}, '2'); ";
            $resultTnx = mysqli_query($dbc, $queryTnx);

            $message = "Rejected";
        }
    }
$page_title = 'Loans - Membership Application Details';
include 'GLOBAL_HEADER.php';
include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';
?>
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

                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> <!-- SERVER SELF -->

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Personal Information</b>

                                        </div>

                                        <div class="panel-body"><p>
                                            <?php 
                                                $query = "SELECT FIRSTNAME, LASTNAME, MIDDLENAME FROM MEMBER M WHERE MEMBER_ID = ". $_SESSION['showFMID'] .";";
                                                $result = mysqli_query($dbc, $query);
                                                $row = mysqli_fetch_array($result);
                                            ?>

                                            <b>ID Number:</b><?php echo $_SESSION['showFMID']; ?> <p>
                                            <b>First Name:</b><?php echo $row['FIRSTNAME']; ?> <p>
                                            <b>Last Name:</b><?php echo $row['LASTNAME']; ?> <p>
                                            <b>Middle Name:</b><?php echo $row['MIDDLENAME']; ?> <p>
                                            
                                        </div>

                                    </div>

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>FALP Details</b>

                                        </div>

                                        <div class="panel-body"><p>
                                            <?php 
                                                $query = "SELECT AMOUNT, PAYABLE, PAYMENT_TERMS, PER_PAYMENT FROM LOANS WHERE MEMBER_ID = ". $_SESSION['showFMID'] .";";
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
