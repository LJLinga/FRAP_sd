<?php
session_start();
require_once('mysql_connect_FA.php');
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';



$query = "SELECT * FROM LOANS where LOAN_ID = {$_SESSION['details']} 
                                                AND    loan_status != 3";
$result = mysqli_query($dbc,$query);
$ans = mysqli_fetch_assoc($result);

$query1 = "SELECT l2.STATUS as 'Status' FROM LOANS l1 JOIN LOAN_STATUS l2 ON l1.LOAN_STATUS = l2.STATUS_ID where l1.LOAN_ID = {$_SESSION['details']} 
                                                 AND     l1.loan_status != 3";
$result1 = mysqli_query($dbc,$query1);
$ans1 = mysqli_fetch_assoc($result1);


if(isset($_POST['addToPay'])){

    //check if the amount to be added is 50% or will exceed

    if($_POST['terms'] != 0) {

        //get the terms to be paid - calculate the payment to be made based on this

        $paymentToBeAdded = $ans['AMOUNT_PAID']+ ($_POST['terms']*$ans['PER_PAYMENT']);

        $payment = $_POST['terms']*$ans['PER_PAYMENT'];

        $termsLeft = $ans['PAYMENTS_MADE'] + $_POST['terms'];

        $update = "UPDATE loans SET AMOUNT_PAID = {$paymentToBeAdded},PAYMENTS_MADE = {$termsLeft} where LOAN_ID  = {$_SESSION['details']}";
        mysqli_query($dbc,$update);

        //this is for checking if the loan is matured. Also updates the data.
        $queryForMatured = "SELECT * FROM LOANS where LOAN_ID = {$_SESSION['details']} ";
        $maturedcheck = mysqli_query($dbc,$queryForMatured);
        $matured = mysqli_fetch_assoc($maturedcheck);


        $totalPayments = $matured['PAYMENT_TERMS']*2;

        //update query for matured stuff
        if($matured['PAYMENTS_MADE'] >= $totalPayments){ //checks if the loan will mature

            $update = "UPDATE loans SET DATE_MATURED = NOW(), LOAN_STATUS = 3 where LOAN_ID  = {$_SESSION['details']}";
            mysqli_query($dbc,$update);

            $description = 'Loan has successfully Matured';

            $query = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE , LOAN_REF, EMP_ID, SERVICE_ID)
                                      values({$ans['MEMBER_ID']}, 2, '{$description}' ,{$payment}, NOW(), {$ans['LOAN_ID']}, {$_SESSION['idnum']}, 4);";


            if (!mysqli_query($dbc,$query))
            {
                echo("Error description: " . mysqli_error($dbc));
            }

            //we reverse the fucking deductions code into something else.

            $query = "UPDATE to_deduct SET HAS_PAID = 2 WHERE LOAN_REF = {$_SESSION['details']} AND HAS_PAID = 1 ORDER BY ID DESC LIMIT {$_POST['terms']} ";

            if (!mysqli_query($dbc,$query))
            {
                echo("Error description: " . mysqli_error($dbc));
            }

        }else{

            $description = 'Deduction from Loan';

            $query = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE , LOAN_REF, EMP_ID, SERVICE_ID)
                                      values({$ans['MEMBER_ID']}, 2, '{$description}' ,{$payment}, NOW(), {$ans['LOAN_ID']}, {$_SESSION['idnum']}, 4);";


            if (!mysqli_query($dbc,$query))
            {
                echo("Error description: " . mysqli_error($dbc));
            }


            $query = "UPDATE to_deduct SET HAS_PAID = 2 WHERE LOAN_REF = {$_SESSION['details']} AND HAS_PAID = 1 ORDER BY ID DESC LIMIT {$_POST['terms']} ";

            if (!mysqli_query($dbc,$query))
            {
                echo("Error description: " . mysqli_error($dbc));
            }


        }


        //updates transaction table



    }else{
        echo '<script language="javascript">';
        echo 'alert(" You cant pay for 0 terms ")';
        echo '</script>';
    }







}else if(isset($_POST['addFifty'])){


    //check if the 50% has beeen surpassed first - baka lang na click ni sir melton to
    if(($ans['PAYMENT_TERMS']*2 - $ans['PAYMENTS_MADE']) > ($ans['PAYMENT_TERMS'])){

        //get the current Amount paid, and get the 50% of the Payable Amount,

        $termsLeftForFifty =($ans['PAYMENT_TERMS']*2 - $ans['PAYMENTS_MADE'])-($ans['PAYMENT_TERMS']); // this variable calculates the remaining 50% to be updated in the loan.

        $payment = $ans['AMOUNT_PAID']+($termsLeftForFifty*$ans['PER_PAYMENT']);

        $perPayment = $termsLeftForFifty*$ans['PER_PAYMENT'];

        $newPayments = $ans['PAYMENTS_MADE']+$termsLeftForFifty;


        //update query

        $update = "UPDATE loans SET AMOUNT_PAID = {$payment},PAYMENTS_MADE = {$newPayments}, DATE_MATURED = NOW() where LOAN_ID  = {$_SESSION['details']}";
        mysqli_query($dbc,$update);

        //update transaction table -  FOR SOME FUCKING SHITTY FUCKING SHIT ASS REASON THIS AND THE PART ABOVE WONT FUCKING INSERT AND IM GOING CRAZY LOOKING FOR A
        // PROBABLY STUPID FUCKING BUG

        $description = "Deduction from Loan";

        $query = "  INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE , LOAN_REF, EMP_ID, SERVICE_ID)
                                      values({$ans['MEMBER_ID']}, 2, '{$description}' , {$perPayment}, now(), {$ans['LOAN_ID']}, {$_SESSION['idnum']}, 4);";

        if (!mysqli_query($dbc,$query))
        {
            echo("Error description: " . mysqli_error($dbc));
        }


        $query = "UPDATE to_deduct SET HAS_PAID = 2 WHERE LOAN_REF = {$_SESSION['details']} AND HAS_PAID = 1 ORDER BY ID DESC LIMIT {$termsLeftForFifty} ";

        if (!mysqli_query($dbc,$query))
        {
            echo("Error description: " . mysqli_error($dbc));
        }





    }else{
        echo '<script language="javascript">';
        echo 'alert(" Payment is already at 50% and more.")';
        echo '</script>';


    }









}

$show = "SELECT * FROM LOANS where LOAN_ID = {$_SESSION['details']} ";
$showme = mysqli_query($dbc,$show);
$updated = mysqli_fetch_assoc($showme);

$query3 = "SELECT l2.STATUS as 'Status' FROM LOANS l1 JOIN LOAN_STATUS l2 ON l1.LOAN_STATUS = l2.STATUS_ID where l1.LOAN_ID = {$_SESSION['details']} ";
$result3 = mysqli_query($dbc,$query3);
$status = mysqli_fetch_assoc($result3);



$page_title = 'FALP - View FALP Details';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';
?>

<style>
    .slidecontainer {
        width: 100%;
    }

    .slider {
        -webkit-appearance: none;
        width: 100%;
        height: 25px;
        background: #d3d3d3;
        outline: none;
        opacity: 0.7;
        -webkit-transition: .2s;
        transition: opacity .2s;
    }

    .slider:hover {
        opacity: 1;
    }

    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 25px;
        height: 25px;
        background: #1066af;
        cursor: pointer;
    }

    .slider::-moz-range-thumb {
        width: 25px;
        height: 25px;
        background: #4CAF50;
        cursor: pointer;
    }
</style>

<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">

            <div class="col-lg-12">
                <?php
                $query2 = "SELECT m.firstname as 'First',m.lastname as 'Last' FROM LOANS l join member m on l.member_id = m.member_id where LOAN_ID = {$_SESSION['details']} 
                                                 ";
                $result2 = mysqli_query($dbc,$query2);
                $ans2 = mysqli_fetch_assoc($result2);

                ?>
                <h1 class="page-header"><?php echo $ans2['First']." ".$ans2['Last'];?> 's FALP Loan Summary</h1>

            </div>

        </div>

        <div class="row">

            <div class="col-lg-6">

                <div class="panel panel-primary">

                    <div class="panel-heading">

                        <b>Current FALP Loan Plan</b>

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
                                <td>₱ <?php echo number_format($updated['AMOUNT'],2)."<br>";?></td>

                            </tr>

                            <tr>

                                <td>Amount Payable</td>
                                <td>₱ <?php echo number_format($updated['PAYABLE'],2)."<br>";?></td>

                            </tr>

                            <tr>

                                <td>Payment Terms</td>
                                <td><?php echo $updated['PAYMENT_TERMS'];?> months</td>

                            </tr>

                            <tr>

                                <td>Monthly Deduction</td>
                                <td>₱ <?php echo number_format($updated['PER_PAYMENT']*2,2)."<br>";?></td>

                            </tr>

                            <tr>

                                <td>Number of Payments</td>
                                <td><?php echo $updated['PAYMENT_TERMS']*2;?> payments</td>

                            </tr>

                            <tr>

                                <td>Per Payment Deduction</td>
                                <td>₱ <?php echo number_format($updated['PER_PAYMENT'],2)."<br>";?></td>

                            </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            <div class="col-lg-6">

                <div class="panel panel-green">

                    <div class="panel-heading">

                        <b>Current FALP Loan Summary</b>

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
                                <td><?php echo date('Y, M d', strtotime($updated['DATE_APPROVED']));?></td>

                            </tr>

                            <tr>

                                <td>Payments Made</td>
                                <td><?php echo (int)$updated['PAYMENTS_MADE'];?> Payments</td>

                            </tr>

                            <tr>

                                <td>Payments Left</td>
                                <td><?php echo ($updated['PAYMENT_TERMS']*2) - $updated['PAYMENTS_MADE'];?> Payments</td>

                            </tr>

                            <tr>

                                <td>Total Amount Paid</td>
                                <td>₱ <?php echo number_format($updated['AMOUNT_PAID'],2)."<br>";?></td>

                            </tr>

                            <tr>

                                <td>Outstanding Balance</td>
                                <td>₱ <?php echo number_format($updated['PAYABLE']-$updated['AMOUNT_PAID'],2)."<br>";?></td>

                            </tr>

                            <tr>

                                <td>Status</td>
                                <td><?php echo $status['Status'];?></td>

                            </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        <div class="row">
            <div class = "col-lg-4">


            </div>

            <div class="col-lg-4">
                <form action = "ADMIN%20FALP%20viewdetails.php" method = "POST">
                    <div class="panel panel-primary">

                        <div class="panel-heading">

                            <b>Manual Add to FALP</b>
                        </div>

                        <div class="panel-body">

                            <!---<input type="number" value="0" min="0" max="" class="form-control" placeholder="Amount to Add" name="terms"  id="terms">
                            --->
                            <div class="slidecontainer">
                                <input type="range" min="0" max="<?php echo ($updated['PAYMENT_TERMS']*2)-$updated['PAYMENTS_MADE']; ?>" value="0" class="slider" name="terms" id="myRange">
                                <p>Number of Payments to be Paid For: <span id="demo"> </span></p>
                            </div>

                            <button type="submit" name="addToPay" id="addToPay" hidden>Pay with number of terms</button>
                            <button type="submit" name="addFifty" id="addFifty" hidden>Pay 50% Immediately</button>

                            <button type="button" name="modalTriggerPay" id="modalTriggerPay" class="btn btn-primary" data-toggle="modal" data-target="#confirm-submit">Pay with number of terms</button>
                            <br><br>
                            <button type="button" name="modalTriggerFifty" id="modalTriggerFifty" class="btn btn-primary" data-toggle="modal" data-target="#confirm-submit">Pay 50% Immediately</button>

                        </div>

                    </div>

                </form>

            </div>

            <div class = "col-lg-4">


            </div>


        </div>



        <div class="row">

            <div class="col-lg-12">

                <div align="center">
                    <form action = "ADMIN FALP viewactivity.php" method = "POST">
                        <button type = "submit" class="btn btn-success" role="button" value = <?php echo $_SESSION['details']?> name = "details" >View Payment Activity</button>

                        <a href="OLD ADMIN FALP/ADMIN dashboard.php" class="btn btn-default" role="button">Go Back</a>
                    </form>
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

<!-- Modal by xtian pls dont delete hehe -->
<div class="modal fade" id="confirm-submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Confirm Payment
            </div>
            <div class="modal-body">
                Are you sure you want to pay <b id="changeText"></b> ?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="#" id="submit" class="btn btn-success success">Yes, I'm sure</a>
            </div>
        </div>
    </div>
</div>
<script>
    var slider = document.getElementById("myRange");
    var output = document.getElementById("demo");
    var addToPay = document.getElementById("modalTriggerPay");

    addToPay.disabled = true;
    output.innerHTML = slider.value;

    slider.oninput = function() {
        output.innerHTML = this.value;
        if(this.value==0){
            addToPay.disabled=true;
        }else if(this.value>0){
            addToPay.disabled=false;
        }
    }

    $('#modalTriggerPay').click(function() {
        $('#changeText').text('for '+output.innerHTML+' month(s)');
        $('#submit').click(function() {
            document.getElementById("addToPay").click();
        });
    });
    $('#modalTriggerFifty').click(function() {
        $('#changeText').text('50 % immediately');
        $('#submit').click(function() {
            document.getElementById("addFifty").click();
        });
    });

</script>

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->

<?php include "GLOBAL_FOOTER.php"; ?>
