<?php

require_once ("mysql_connect_FA.php");

    //get loan ID from deduct ID

    //get the loan details based on the loan details
    $getLoanDetailsQuery = "SELECT * from loans WHERE LOAN_STATUS = 2";
    $getLoanDetailsResult = mysqli_query($dbc,$getLoanDetailsQuery);

foreach ($getLoanDetailsResult as $getLoanDetails) {

    $newPaymentsMade = $getLoanDetails['PAYMENTS_MADE'] + 1;
    $newAmountPaid = $getLoanDetails['PER_PAYMENT'] * $newPaymentsMade;
    $paymentTerms = $getLoanDetails['PAYMENT_TERMS'] * 2;

    if ($newPaymentsMade == $paymentTerms) { // checks if the loan has matured.
        $update = "UPDATE loans SET PAYMENTS_MADE = {$newPaymentsMade} , AMOUNT_PAID = {$newAmountPaid}, DATE_MATURED = DATE(NOW()), LOAN_STATUS = 3 where LOAN_ID  = {$getLoanDetails['LOAN_ID']}";
        mysqli_query($dbc, $update);


        //insert into transactions
        $description = 'FAP per Payment has been deducted from your salary, and your FAP Loan has matured.';

        $query = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE, TXN_DESC, AMOUNT, LOAN_REF,  SERVICE_ID)
                                      values({$getLoanDetails['MEMBER_ID']}, 2, '{$description}' ,{$getLoanDetails['PER_PAYMENT']}, {$getLoanDetails['LOAN_ID']},  4);";

        if (!mysqli_query($dbc, $query)) {
            echo("Error description: " . mysqli_error($dbc));
        }

        $update = "UPDATE loans SET PAYMENTS_MADE = {$newPaymentsMade} , AMOUNT_PAID = {$newAmountPaid}, DATE_MATURED = DATE(NOW()), LOAN_STATUS = 3 where LOAN_ID  = {$getLoanDetails['LOAN_ID']}";
        mysqli_query($dbc, $update);


    } else { //if its not matured

        $update = "UPDATE loans SET PAYMENTS_MADE = {$newPaymentsMade} , AMOUNT_PAID = {$newAmountPaid}, DATE_MATURED = DATE(NOW()) where LOAN_ID  = {$getLoanDetails['LOAN_ID']}";
        mysqli_query($dbc, $update);

        $description = 'FAP Per Payment has been deducted from your salary.';

        $query = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE, TXN_DESC, AMOUNT, LOAN_REF,  SERVICE_ID)
                                      values({$getLoanDetails['MEMBER_ID']}, 2, '{$description}' ,{$getLoanDetails['PER_PAYMENT']}, {$getLoanDetails['LOAN_ID']},  4);";

        if (!mysqli_query($dbc, $query)) {
            echo("Error description: " . mysqli_error($dbc));
        }


    }
}


// FINISH THIS LATER!!!!!
$today = date("d");

if($today == 8){
    $queryReminder = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE, TXN_DESC, AMOUNT, SERVICE_ID)
                                      values(99999999, 2, 'Please be Reminded that you are to pass the Deductions before or by 14th of this month. ' , 0.00,  4);";


    mysqli_query($dbc, $queryReminder);


}else if($today == 23){

    $queryReminder = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE, TXN_DESC, AMOUNT, SERVICE_ID)
                                      values(99999999, 2, 'Please be Reminded that you are to pass the Deductions before or by 28th of this month. ' , 0.00,  4);";


    mysqli_query($dbc, $queryReminder);
}




?>