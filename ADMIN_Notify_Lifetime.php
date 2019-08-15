<?php


require_once("mysql_connect_FA.php");
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 13/08/2019
 * Time: 1:49 PM
 */

$allMemberIdQuery = "SELECT MEMBER_ID, USER_STATUS,YEAR(DATE_APPROVED) as 'year' from member WHERE MEMBERSHIP_STATUS = 2 && LIFETIME_STATUS = 1";
$allMemberIdResult = mysqli_query($dbc,$allMemberIdQuery);

foreach($allMemberIdResult as $memberDetails){

    $yearHired = $memberDetails['year'];

    $yearNOW = date('Y');

    if(($yearNOW - $yearHired) >= 10 ){

        $query = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE, TXN_DESC, AMOUNT, SERVICE_ID)
                                  values({$memberDetails['MEMBER_ID']}, 4, 'You can now apply for lifetime!' , 0.00, 6);";

        if (!mysqli_query($dbc,$query))
        {
            echo("Error description: " . mysqli_error($dbc));
        }

    }
}




