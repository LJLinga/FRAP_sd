<?php 

	require_once('mysql_connect_FA.php');

	$queryFALP = "SELECT L.LOAN_ID, L.MEMBER_ID, L.PER_PAYMENT, L.PAYMENTS_MADE, L.PAYMENT_TERMS 
				  FROM LOANS L
				  JOIN LOAN_PLAN LP ON L.LOAN_DETAIL_ID = LP.LOAN_ID
				  WHERE L.PAYMENTS_MADE < (L.PAYMENT_TERMS * 2) AND L.APP_STATUS = 2 AND L.LOAN_STATUS = 2 AND LP.BANK_ID = 1;";

	$resultFALP = mysqli_query($dbc, $queryFALP);

	/* Insert to TXN_REFERENCE table */

	foreach ($resultFALP as $rowFALP) {

		$queryFALPTxn = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, SERVICE_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF) 
					 	VALUES ('{$rowFALP['MEMBER_ID']}', 2, 3, 'Salary Deducted for FALP Loan', '{$rowFALP['PER_PAYMENT']}', NOW(), '{$rowFALP['LOAN_ID']}')";

		$resultFALPTxn = mysqli_query($dbc, $queryFALPTxn);

		/* Update curent loan info at LOANS table */

		if ($rowFALP['PAYMENTS_MADE'] == ($rowFALP['PAYMENT_TERMS'] * 2 - 1)) { /* This is the loan's last deduction */

			echo "Final deduction for: " . $rowFALP['LOAN_ID'];

			$queryFALPUpdate = "UPDATE LOANS SET PAYMENTS_MADE = PAYMENTS_MADE + 1, LOAN_STATUS = 3, AMOUNT_PAID = AMOUNT_PAID + {$rowFALP['PER_PAYMENT']}, DATE_MATURED = DATE(NOW()) WHERE LOAN_ID = {$rowFALP['LOAN_ID']};";

			$resultUpdate = mysqli_query($dbc, $queryFALPUpdate);

		}

		else {

			$queryFALPUpdate = "UPDATE LOANS SET PAYMENTS_MADE = PAYMENTS_MADE + 1, AMOUNT_PAID = AMOUNT_PAID + {$rowFALP['PER_PAYMENT']} 
								WHERE LOAN_ID = {$rowFALP['LOAN_ID']};";

			$resultUpdate = mysqli_query($dbc, $queryFALPUpdate);

		}

	}

	/* -------------------------------- */
	/* FALP LOAN ABOVE, BANK LOAN BELOW */
	/* -------------------------------- */

	$queryBank = "SELECT L.LOAN_ID, L.MEMBER_ID, L.PER_PAYMENT, L.PAYMENTS_MADE, L.PAYMENT_TERMS 
				  FROM LOANS L
				  JOIN LOAN_PLAN LP ON L.LOAN_DETAIL_ID = LP.LOAN_ID
				  WHERE L.PAYMENTS_MADE < (L.PAYMENT_TERMS * 2) AND L.APP_STATUS = 2 AND L.LOAN_STATUS = 2 AND LP.BANK_ID != 1;";

	$resultBank = mysqli_query($dbc, $queryBank);

	foreach ($resultBank as $rowBank) {

		$queryBANKTxn = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, SERVICE_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF) 
					 	VALUES ('{$rowBank['MEMBER_ID']}', 2, 3, 'Salary Deducted for Bank Loan', '{$rowBank['PER_PAYMENT']}', NOW(), '{$rowBank['LOAN_ID']}')";

		$resultBANKTxn = mysqli_query($dbc, $queryBANKTxn);

		/* Update curent loan info at LOANS table */

		if ($rowBank['PAYMENTS_MADE'] == ($rowBank['PAYMENT_TERMS'] * 2 - 1)) { /* This is the loan's last deduction */

			$queryBANKUpdate = "UPDATE LOANS SET PAYMENTS_MADE = PAYMENTS_MADE + 1, LOAN_STATUS = 3, AMOUNT_PAID = AMOUNT_PAID + {$rowBank['PER_PAYMENT']}, DATE_MATURED = DATE(NOW()) WHERE LOAN_ID = {$rowBank['LOAN_ID']};";

			$resultUpdate = mysqli_query($dbc, $queryBANKUpdate);

		}

		else {

			$queryBANKUpdate = "UPDATE LOANS SET PAYMENTS_MADE = PAYMENTS_MADE + 1, AMOUNT_PAID = AMOUNT_PAID + {$rowBank['PER_PAYMENT']} 
								WHERE LOAN_ID = {$rowBank['LOAN_ID']};";

			$resultUpdate = mysqli_query($dbc, $queryBANKUpdate);

		}

	}	

?>