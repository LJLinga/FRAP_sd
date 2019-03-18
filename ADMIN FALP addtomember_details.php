<?php
require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';




        //this is for  the  stuff below, remember that if the Part Time Loaned  == Yes, then the button below will be disabled and will have a well.

        if (isset($_POST['submit'])) {

            //Part timer part check

            //first check if the part timer is eligible for a loan

            // which means the selected user is not a part timer

                //add an if statement here to see if the guy you are going to add has paid 50% of his loan, then say if he cannot have another loan unless he has paid 50%

                    $payments = $_POST['terms']*2;
                    $paidterms = $_POST['paidterms'];

                    $payable = $_POST['amount'] +500;
                    $perPayment = (($_POST['amount']+500) / $payments);


                    $amountPaid = $perPayment * $paidterms;
                    $dateapproved = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $_POST['approveddate'])));;
                    $dateapplied = date('Y-m-d H:i:s', strtotime(str_replace('-', '/',  $_POST['applieddate'])));;

                    $query3 = "INSERT INTO loans(MEMBER_ID,AMOUNT,INTEREST,PAYMENT_TERMS,PAYABLE,PER_PAYMENT, AMOUNT_PAID, PAYMENTS_MADE ,APP_STATUS,LOAN_STATUS,DATE_APPROVED, DATE_APPLIED)
                                      values({$_SESSION['chosenMemID']},{$_POST['amount']},500,{$_POST['terms']},{$payable},{$perPayment},{$amountPaid},{$paidterms},2,2,'{$dateapproved}','{$dateapplied}');";

                    if (!mysqli_query($dbc,$query3))
                    {
                        echo("Error description: " . mysqli_error($dbc));
                    }


                    //get the loan id of the goddamn shit
                     $loanIDQuery = "SELECT LOAN_ID, PAYMENT_TERMS, PAYMENTS_MADE from loans where MEMBER_ID = {$_SESSION['chosenMemID']} ORDER BY LOAN_ID DESC  LIMIT 1";
                     $loanIDresult = mysqli_query($dbc, $loanIDQuery);
                     $loanIDref = mysqli_fetch_assoc($loanIDresult);

                    //Inserts into the transaction table

                    $desc = "Loan has been Accepted!";
                    $query6 = "INSERT INTO txn_reference(MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF, EMP_ID, SERVICE_ID)
                             values ({$_SESSION['chosenMemID']}, 1, '{$desc}', {$_POST['amount']}, DATE(now()),{$loanIDref['LOAN_ID']} ,{$_SESSION['idnum']}, 4)";
                    // SERVICE ID : 1 - Membership, 2 - Health Aid, 3 - FALP
                    // TXN_TYPE : 1 - Application 2 - Deduction
                    if (!mysqli_query($dbc,$query6))
                    {
                        echo("Error description: " . mysqli_error($dbc));
                    }

                    $query3 = "SELECT FIRSTNAME, LASTNAME, PART_TIME_LOANED, USER_STATUS FROM member where {$_SESSION['chosenMemID']} = MEMBER_ID";
                    $result3 = mysqli_query($dbc, $query3);
                    $row3 = mysqli_fetch_assoc($result3);


                    if($row3['USER_STATUS'] == 2){
                        $updatePTLStatus = "UPDATE member SET  PART_TIME_LOANED = 'Y' WHERE {$_SESSION['chosenMemID']} = MEMBER_ID";

                        mysqli_query($dbc, $updatePTLStatus);
                    }

            // put the insert code for the To_Deduct table here.

            $currYear = date("Y");
            $currDay = date('d');
            $currMonth = date("m");

            $dateToInsert = date("Y-m-d", strtotime($currYear."-".$currMonth."-".$currDay));


            $i = 0; //this will keep track on how many payments has been inserted.

            while($i < (($loanIDref['PAYMENT_TERMS']*2) - $loanIDref['PAYMENTS_MADE'])){  //get the payment terms, and have the counter keep track on how many has been  inserted already.

                //check if it is February first so we can adjust the end date by February 28.

                if($currMonth == 2){ //assumes that the current month is February

                    if($currDay < 15){

                        $currDay = 15;

                        $dateToInsert = date("Y-m-d", strtotime($currYear."-".$currMonth."-".$currDay));

                        $query5 = "INSERT INTO to_deduct(LOAN_REF, DEDUCTION_DATE)
                             values ({$loanIDref['LOAN_ID']},'{$dateToInsert}')";

                        if (!mysqli_query($dbc,$query5))
                        {
                            echo("Error description: " . mysqli_error($dbc));
                        }

                        $i++;

                    }else if($currDay < 28){

                        $currDay = 28;

                        $dateToInsert = date("Y-m-d", strtotime($currYear."-".$currMonth."-".$currDay));

                        $query5 = "INSERT INTO to_deduct(LOAN_REF, DEDUCTION_DATE)
                             values ({$loanIDref['LOAN_ID']},'{$dateToInsert}')";

                        if (!mysqli_query($dbc,$query5))
                        {
                            echo("Error description: " . mysqli_error($dbc));
                        }

                        $i++;

                    }else{

                        $currMonth++;

                        $currDay = 1;

                    }

                }else { //this means its just any day of the month

                    if($currDay < 15){

                        $currDay = 15;

                        $dateToInsert = date("Y-m-d", strtotime($currYear."-".$currMonth."-".$currDay));

                        $query5 = "INSERT INTO to_deduct(LOAN_REF, DEDUCTION_DATE)
                             values ({$loanIDref['LOAN_ID']},'{$dateToInsert}')";

                        if (!mysqli_query($dbc,$query5))
                        {
                            echo("Error description: " . mysqli_error($dbc));
                        }

                        $i++;

                    }else if($currDay < 30){

                        $currDay = 30;

                        $dateToInsert = date("Y-m-d", strtotime($currYear."-".$currMonth."-".$currDay));

                        $query5 = "INSERT INTO to_deduct(LOAN_REF, DEDUCTION_DATE)
                             values ({$loanIDref['LOAN_ID']},'{$dateToInsert}')";

                        if (!mysqli_query($dbc,$query5))
                        {
                            echo("Error description: " . mysqli_error($dbc));
                        }

                        $i++;

                    }else {

                        $currMonth++;

                        if ($currMonth == 13) {
                            $currMonth = 1;

                            $currYear++;
                        }

                        $currDay = 1;
                    }

                }


            }

        }






$query3 = "SELECT FIRSTNAME, LASTNAME, PART_TIME_LOANED, USER_STATUS FROM member where {$_SESSION['chosenMemID']} = MEMBER_ID";
$result3 = mysqli_query($dbc, $query3);
$row3 = mysqli_fetch_assoc($result3);

$query4 = "SELECT * FROM LOANS where {$_SESSION['chosenMemID']} = MEMBER_ID && APP_STATUS = 2 ORDER BY LOAN_ID DESC LIMIT 1 ";
$result4 = mysqli_query($dbc, $query4);
$row4 = mysqli_fetch_assoc($result4);

$halfAmount = $row4['PAYABLE']/2;



$page_title = 'FALP - Only ';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';
?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Add Existing FALP to <?php echo $row3['FIRSTNAME']." ".$row3['LASTNAME'] ?>
                        </h1>
                    
                    </div>

                    <?php if($row3['PART_TIME_LOANED'] == 'YES' && $row3['USER_STATUS'] == 2){?>

                    <div class="col-lg-12">
                        <div class="alert alert-danger" role="alert">
                            This Part Time Member has already had a loan this term, and cannot loan anymore!
                        </div>
                    </div>

                    <?php }else if($halfAmount > $row4['AMOUNT_PAID']){ ?>

                        <div class="col-lg-12">
                            <div class="alert alert-danger" role="alert">
                              This Member still has an existing loan and has not paid 50% of it!
                            </div>
                        </div>

                    <?php } ?>

                </div>
                <!-- alert -->
                <div class="row">

                    <div class="col-lg-6">

                        <div class="panel panel-primary">

                            <div class="panel-heading">

                                <b>Current FALP Loan Plan</b>

                            </div>


                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

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
                                        <td><div id = "amountToBorrow"></div></td>

                                    </tr>

                                    <tr>

                                        <td>Amount Payable</td>
                                        <td><div id = "amountPayable"></div></td>

                                    </tr>

                                    <tr>

                                        <td>Payment Terms</td>
                                        <td><div id = "paymentTerms"></div></td>

                                    </tr>

                                    <tr>

                                        <td>Monthly Deduction</td>
                                        <td><div id = "monthlyDeduction"></div></td>

                                    </tr>

                                    <tr>

                                        <td>Number of Payments</td>
                                        <td><div id = "numPayments"></div></td>

                                    </tr>

                                    <tr>

                                        <td>Per Payment Deduction</td>
                                        <td><div id = "perPayment"></div> </td>

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
                                        <td><div id = "dateApproved"></div></td>

                                    </tr>

                                    <tr>

                                        <td>Payments Made</td>
                                        <td><div id = "paymentsMade"></div> </td>

                                    </tr>

                                    <tr>

                                        <td>Payments Left</td>
                                        <td><div id = "paymentsLeft"></div> </td>

                                    </tr>

                                    <tr>

                                        <td>Total Amount Paid</td>
                                        <td><div id = "totalPaid"></div> </td>

                                    </tr>

                                    <tr>

                                        <td>Outstanding Balance</td>
                                        <td><div id = "outstandingBalance"></div> </td>

                                    </tr>

                                    <tr>

                                        <td>Status</td>
                                        <td> <div id = "Status"></div></td>

                                    </tr>

                                    </tbody>

                                </table>


                            </div>

                        </div>

                    </div>

                    <div class = "col-lg-4">


                    </div>
                    <div class = "col-lg-4">
                        <div class="panel panel-primary">

                            <div class="panel-heading">

                                <b>Actions</b>

                            </div>

                            <div align="center" class="row">

                                <div class="panel-body">
                                    <div class = "col-lg-1">


                                    </div>

                                        <div align="left" class = "col-lg-10">
                                            <div class="form-group">
                                                <label class="memfieldlabel">Amount to Borrow</label>
                                                <input type="number" class="form-control" placeholder="Enter Amount (Peso)" name="amount" id="amount"  maxlength="5" required>

                                                <label class="memfieldlabel">Payment Terms</label>
                                                <input type="number" class="form-control" placeholder="Enter Payment Terms" name="terms" id="terms"  maxlength="5" required>

                                                <label class="memfieldlabel">Date Applied</label>
                                                <div class="input-group date" id="datetimepicker1">
                                                    <input id="event_end" name="applieddate" type="text" class="form-control" required>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>

                                                <label class="memfieldlabel">Date Approved</label>
                                                <div class="input-group date" id="datetimepicker2">
                                                    <input id="event_end" name="approveddate" type="text" class="form-control" required>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>


                                                <label class="memfieldlabel">Payments Made</label>
                                                <input type="number" class="form-control" placeholder="Enter Payments Made" name="paidterms" id="paidterms"  maxlength="5" required>


                                                <a href="ADMIN%20FALP%20addtomember.php"><button type="button" class="btn btn-default">Back</button></a>
                                                <button type="button" class="btn btn-info" id="falpcompute">Compute</button>
                                                <?php if($row3['PART_TIME_LOANED'] == "YES"){?>
                                                <button type="submit" class="btn btn-success" name="submit" value="Submit" disabled>Submit</button>

                                                <?php }else if($halfAmount > $row4['AMOUNT_PAID']){?>
                                                    <button type="submit" class="btn btn-success" name="submit" value="Submit" disabled>Submit</button>


                                                <?php }else {?>

                                                    <button type="submit" class="btn btn-success" name="submit" value="Submit">Submit</button>

                                                <?php }?>

                                            </div>


                                        </div>

                                        <div class = "col-lg-1">


                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>



                    </div>
                    <div class = "col-lg-4">


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
<!--    <div id="myModal" class="modal fade" role="dialog">-->
<!--        <div class="modal-dialog">-->
<!---->
<!--            <form method="POST" id="addToFALPForm">-->
<!---->
<!--                <!-- Modal content-->-->
<!--                <div class="modal-content">-->
<!--                    <div class="modal-header">-->
<!--                        Add <span id="modalFullName"></span> to FALP-->
<!--                    </div>-->
<!--                    <div class="modal-body">-->
<!--                        <div class="form-group">-->
<!--                            <label class="memfieldlabel">Amount</label><big class="req"> *</big>-->
<!--                            <input type="number" class="form-control" placeholder="Enter Amount (Peso)" name="amount" id="amount" min="5000" max="15000" maxlength="5" required>-->
<!--                        </div>-->
<!--                        <div class="form-group">-->
<!--                            <label class="memfieldlabel">Payment Terms</label><big class="req"> *</big>-->
<!--                            <input type="number" class="form-control" placeholder="Payment Terms" name="terms"  id="terms" min="1">-->
<!--                        </div>-->
<!--                        <div class="form-group">-->
<!--                            <p>-->
<!--                            <div id = "totalI">   </div> <p>-->
<!--                            <p>-->
<!--                            <div id = "totalP"> </div><p>-->
<!--                            <p>-->
<!--                            <div id = "PerP"></div><p>-->
<!--                            <p>-->
<!--                            <div id = "Monthly"></div>-->
<!--                            <input type="button" name="compute" class="btn btn-success" value="Compute" id="falpcompute">-->
<!--                        </div>-->
<!--                        <span id="err"></span>-->
<!--                    </div>-->
<!--                    <div class="modal-footer">-->
<!--                        <div class="form-group">-->
<!--                            <input type="hidden" name="userId" value="--><!--">-->
<!--                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>-->
<!--                            <input class="btn btn-success" type="submit" name="submit" value="Submit"></p>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--            </form>-->
<!---->
<!--        </div>-->
<!--    </div>-->
    <script>

        $(document).ready(function(){

            $('#datetimepicker1').datetimepicker( {
                locale: moment().local('ph'),
                maxDate: moment(),

                format: 'YYYY-MM-DD'
            });


            $('#datetimepicker2').datetimepicker( {
                locale: moment().local('ph'),
                maxDate: moment(),

                format: 'YYYY-MM-DD'
            });


        });



    </script>
    <script>

        document.getElementById("falpcompute").onclick = function() {
            checkform();
        };

         let type = <?php echo $row3['USER_STATUS']; ?>;
        
        function calculate(){
            
            let amount = parseFloat(document.getElementById("amount").value);
            let terms = parseInt(document.getElementById("terms").value);
            let dateapproved = document.getElementById("event_end").value;
            let paidterms = parseInt(document.getElementById("paidterms").value);



            let interest = 0;

            if(type == 1){
                interest = 500
            }else{
                interest = 300;
            }

            let perPayment= (amount+interest)/(terms*2);
            let paidAmount = perPayment * paidterms;
            let outstanding = (amount+interest);
            outstanding = outstanding - paidAmount;


            // first half
            document.getElementById("amountToBorrow").innerHTML ="₱ "+ parseFloat((amount)).toFixed(2) ;
            document.getElementById("amountPayable").innerHTML ="₱ "+ parseFloat((amount+interest)).toFixed(2);
            document.getElementById("paymentTerms").innerHTML =""+ parseInt(((terms))).toFixed(2)+" Months";
            document.getElementById("monthlyDeduction").innerHTML ="₱ "+ parseFloat((perPayment*2)).toFixed(2);
            document.getElementById("numPayments").innerHTML =""+ parseInt((terms*2)).toFixed(2) + " Payments";
            document.getElementById("perPayment").innerHTML ="₱ "+ parseFloat((perPayment)).toFixed(2);



            document.getElementById("dateApproved").innerHTML =""+dateapproved.toString();
            document.getElementById("paymentsMade").innerHTML =""+ parseInt((paidterms)).toFixed(2)+ " Payments";
            document.getElementById("paymentsLeft").innerHTML =""+ parseInt((terms*2)-paidterms).toFixed(2)+ " Payments";
            document.getElementById("totalPaid").innerHTML ="₱ "+ parseFloat((paidAmount)).toFixed(2);
            document.getElementById("outstandingBalance").innerHTML ="₱ "+ parseFloat((outstanding)).toFixed(2);

        }


        function checkform(){

            let elemAmount = parseFloat(document.getElementById("amount").value);
            let elemTerms = parseFloat(document.getElementById("terms").value);

            let amountLimit;
            let termMax;

            if(type == 1){
                amountLimit = 25000;
                termMax = 5;
            }else{
                amountLimit = 15000;
                termMax = 3;
            }



            if(elemAmount <5000){
                alert("Amount entered is below minimum. Please enter amount within the range.");
                return false;
            }
            else if(elemAmount > amountLimit){
                alert("Amount entered is above maximum. Please enter amount within the range.");
                return false;
            }
            else if(elemTerms < 0){ // if terms are below 3, deins dapat to
                alert("Terms entered is below minimum . Please enter amount within the range.");
                return false;
            }else if(elemTerms > termMax) {
                alert("Terms entered is above maximum . Please enter amount within the range.");
                return false;
            } else if(isNaN(elemAmount)){
                alert("Invalid Input");
                return false;
            }else if (isNaN(elemTerms)){
                alert("No Terms");
                return false;
            } else{
                calculate();
                return true;
            }

        }
    </script>

<?php include 'GLOBAL_FOOTER.php'; ?>