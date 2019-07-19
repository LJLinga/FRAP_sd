<?php
    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';
    include 'GLOBAL_FRAP_ADMIN_CHECKING.php';



    //in the future, add Deduct everything currently being shown. t.hanks.


    $page_title = 'Admin - Deductions Screen';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_ADMIN_SIDEBAR.php';

?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Pending FALP Deductions
                        </h1>
                    </div>
                    <div class="col-lg-12">

                        <div class="well">The Deductions button is only available during the 10th and 25th of the month to signify the cutoff dates of the AFED Inc. This can be changed however, simply contact your website administrator.</div>
                    </div>

                </div>
                <!-- alert -->
                <?php
                    if(isset($_POST['toDeductID'])){
                        echo "
                            <div class='alert alert-success'>
                                Deducted succesfully!
                            </div>
                        ";

                    //get loan ID from deduct ID
                    $getLoanIDQuery = "SELECT LOAN_REF from to_deduct WHERE ID = {$_POST['toDeductID']}";
                    $getLoanIDResult = mysqli_query($dbc,$getLoanIDQuery);
                    $getLoanID = mysqli_fetch_assoc($getLoanIDResult);

                    //get the loan details based on the loan details
                    $getLoanDetailsQuery = "SELECT * from loans WHERE LOAN_ID = {$getLoanID['LOAN_REF']}";
                    $getLoanDetailsResult = mysqli_query($dbc,$getLoanDetailsQuery);
                    $getLoanDetails = mysqli_fetch_assoc($getLoanDetailsResult);

                    //update loan - remember only for one payment!
                    // payments made.
                    // amount paid

                    $newPaymentsMade = $getLoanDetails['PAYMENTS_MADE'] + 1;
                    $newAmountPaid = $getLoanDetails['PER_PAYMENT'] * $newPaymentsMade;
                    $paymentTerms = $getLoanDetails['PAYMENT_TERMS']*2;

                    if($newPaymentsMade == $paymentTerms){ // checks if the loan has matured.
                        $update = "UPDATE loans SET PAYMENTS_MADE = {$newPaymentsMade} , AMOUNT_PAID = {$newAmountPaid}, DATE_MATURED = DATE(NOW()), LOAN_STATUS = 3 where LOAN_ID  = {$getLoanDetails['LOAN_ID']}";
                        mysqli_query($dbc,$update);


                        //insert into transactions
                        $description = 'FALP Loan Has Matured!';

                        $query = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE , LOAN_REF, EMP_ID, SERVICE_ID)
                                      values({$getLoanDetails['MEMBER_ID']}, 2, '{$description}' ,{$getLoanDetails['PER_PAYMENT']}, DATE(NOW()), {$getLoanDetails['LOAN_ID']}, {$_SESSION['idnum']}, 4);";

                        if (!mysqli_query($dbc,$query))
                        {
                            echo("Error description: " . mysqli_error($dbc));
                        }

                        $update = "UPDATE loans SET PAYMENTS_MADE = {$newPaymentsMade} , AMOUNT_PAID = {$newAmountPaid}, DATE_MATURED = DATE(NOW()), LOAN_STATUS = 3 where LOAN_ID  = {$getLoanDetails['LOAN_ID']}";
                        mysqli_query($dbc,$update);


                    }else{ //if its not matured

                        $update = "UPDATE loans SET PAYMENTS_MADE = {$newPaymentsMade} , AMOUNT_PAID = {$newAmountPaid}, DATE_MATURED = DATE(NOW()) where LOAN_ID  = {$getLoanDetails['LOAN_ID']}";
                        mysqli_query($dbc,$update);

                        $description = 'Your Account has been Deducted due to FALP!';

                        $query = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE , LOAN_REF, EMP_ID, SERVICE_ID)
                                      values({$getLoanDetails['MEMBER_ID']}, 2, '{$description}' ,{$getLoanDetails['PER_PAYMENT']}, NOW(), {$getLoanDetails['LOAN_ID']}, {$_SESSION['idnum']}, 4);";

                        if (!mysqli_query($dbc,$query))
                        {
                            echo("Error description: " . mysqli_error($dbc));
                        }


                    }

                    //update the to_deduct table
                    $update = "UPDATE to_deduct SET HAS_PAID = 2 where ID = {$_POST['toDeductID']}";
                    mysqli_query($dbc,$update);

                }

                ?>
                <div class="row">
                    <div class="col-lg-12">

                       <div class="row">

                            <div class="col-lg-12">

                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> <!-- SERVER SELF -->

                                <table id="table" class="table table-bordered table-striped">
                                    
                                    <thead>

                                        <tr>

                                        <td align="center"><b>ID Number</b></td>
                                        <td align="center"><b>Name</b></td>
                                        <td align="center"><b>Date of Deduction</b></td>
                                        <td align="center"><b>Amount to Deduct</b></td>
                                        <td align="center"><b>Action</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php 
                                            // remember to change the  td.DEDUCTION_DATE >= DATE (NOW()) to  td.DEDUCTION_DATE <= DATE (NOW()). This is merely for testing purposes.
                                            $query = " SELECT m.MEMBER_ID, l.LOAN_ID, m.FIRSTNAME, m.LASTNAME,l.PER_PAYMENT ,td.DEDUCTION_DATE, td.ID
                                            from loans l
                                            join to_deduct td
                                            on  l.LOAN_ID = td.LOAN_REF
                                            join member m
                                            on l.MEMBER_ID = m.MEMBER_ID
                                            where td.DEDUCTION_DATE <= DATE (NOW())
                                            AND td.HAS_PAID = 1
                                            ";
                                            $result = mysqli_query($dbc, $query);

                                        $today = date("d");

                                            
                                            foreach ($result as $resultRow) {
                                        ?>

                                        <tr>

                                        <td align="center"><?php echo $resultRow['MEMBER_ID']; ?></td>
                                        <td align="center"><?php echo $resultRow['FIRSTNAME'] ." ". $resultRow['LASTNAME'];  ?> </td>
                                        <td align="center"><?php echo date('Y, M d', strtotime($resultRow['DEDUCTION_DATE'])); ?></td>
                                        <td align="center">₱ <?php echo number_format($resultRow['PER_PAYMENT'],2)."<br>"; ?></td>
                                            <?php if($today == '10' || $today == '25'){ //this is for when the date is not the same as the Deduction Periods?>
                                        <td align="center"><button type='submit' class='btn btn-warning' name='toDeductID' value='<?php echo $resultRow['ID']; ?>'>Deduct  </button>&nbsp;&nbsp;&nbsp;</td>
                                                <?php }else{ //this is when the date is the deduction period?>
                                                <td align="center"><button type='submit' class='btn btn-warning' name='toDeductID' value='<?php echo $resultRow['ID']; ?>' disabled>Deduct </button>&nbsp;&nbsp;&nbsp;</td>

                                            <?php } ?>
                                        </tr>

                                        <?php }?>
                                    </tbody>

                                </table>

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
    

    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script>

        $(document).ready(function(){
    
            $('#table').DataTable();

        });

    </script>

<?php include "GLOBAL_FOOTER.php" ?>