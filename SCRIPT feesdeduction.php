<?php 

	require_once('mysql_connect_FA.php');

	$queryMember = "SELECT MEMBER_ID FROM MEMBER
					WHERE MEMBERSHIP_STATUS = 2 AND USER_STATUS = 1";

	$resultMember = mysqli_query($dbc, $queryMember);

	/* Insert to TXN_REFERENCE table */

	foreach ($resultMember as $rowMember) {

		$queryMemTxn = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, SERVICE_TYPE, TXN_DESC, AMOUNT, TXN_DATE) 
					 	VALUES ('{$rowMember['MEMBER_ID']}', 2, 1, 'Salary Deducted for Membership Fee', 100.00, NOW())";

		$resultMemTxn = mysqli_query($dbc, $queryMemTxn);

	}

	/* --------------------------------------- */
	/* MEMBERSHIP FEES ABOVE, HEALTH AID BELOW */
	/* --------------------------------------- */

	$queryHA = "SELECT MEMBER_ID FROM HEALTH_AID WHERE APP_STATUS = 2";

	$resultHA = mysqli_query($dbc, $queryHA);

	/* Insert to TXN_REFERENCE table */

	foreach ($resultHA as $rowHA) {

		$queryHATxn = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, SERVICE_TYPE, TXN_DESC, AMOUNT, TXN_DATE) 
					 	VALUES ('{$rowHA['MEMBER_ID']}', 2, 1, 'Salary Deducted for Health Aid Contribution', 100.00, NOW())";

		$resultMemTxn = mysqli_query($dbc, $queryHATxn);

		$queryHAInsert = "UPDATE HEALTH_AID SET CONTRIBUTION = CONTRIBUTION + 100 WHERE MEMBER_ID = '{$rowHA['MEMBER_ID']}'";

		$resultHAInsert = mysqli_query($dbc, $queryHAInsert);

	}

?>