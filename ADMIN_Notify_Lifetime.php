<?php


require_once("mysql_connect_FA.php");
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 13/08/2019
 * Time: 1:49 PM
 */

$allMemberIdQuery = "SELECT MEMBER_ID, USER_STATUS, DATE_APPROVED, LIFETIME_ELIGIBLE, LIFETIME_STATUS from member WHERE MEMBERSHIP_STATUS = 2 && (LIFETIME_STATUS = 1 || LIFETIME_STATUS = 2 || LIFETIME_STATUS = 3)";
$allMemberIdResult = mysqli_query($dbc,$allMemberIdQuery);

foreach($allMemberIdResult as $memberDetails){

    /**
     * TO NOT FORGET!!!!!
     *
     *  - WHAT I NEED TO DO HERE:
     * CHECK IF EXPIRY DATE = NOW. IF IT IS, CHANGE LIFETIME STATUS TO 5, and UPDATE TRANSACTIONS TABLE
     *
     *
     * NEXT: CHECK IF 10 YEARS HAS PASSED FROM BEING APPROVED AS A MEMBER.
     * IF IT IS, UPDATE STATUS TO 2 FOR THE FIRST TIME AND UPDATE LIFETIME EXPIRY DATE AND ELIGIBLE DATE. 
     *
     *
     *
     */

    $dateEligible = date('Y-m-d',strtotime($checkIfConsented['LIFETIME_ELIGBLE'])); //

    $dateNow = date('Y-m-d'); //gts current Date

    $dateExpiryDate = date('Y-m-d', strtotime(date("Y-m-d", strtotime($dateEligible)). "+ 1 year")); //gets the consented date + 1 Year Full Time

    //check if the eligibility date expired

    $dateHired = $memberDetails['year'];

    $dateForEligible = date('Y-m-d', strtotime(date("Y-m-d", strtotime($dateHired)). "+ 10 year")); //gets the consented date + 1 Year Full Time



    if($dateExpiryDate <= $dateNow && $memberDetails['LIFETIME_STATUS'] == 2){ // if the eligibility date has expired

        $updateQuery = "UPDATE member set LIFETIME_STATUS = 5 WHERE MEMBER_ID = {$memberDetails['MEMBER_ID']} ";
        if (!mysqli_query($dbc,$query))
        {
            echo("Error description: " . mysqli_error($dbc));
        }


    }else{ // meaning hindi pa expired and will just notify the member

            if($memberDetails['LIFETIME_ELIGIBLE'] == 1){ // what this does is basically just do this once
                $oneMoreYear = date('Y-m-d', '+1 year');

                $updateQuery = "UPDATE member set LIFETIME_STATUS = 2, LIFETIME_ELIGIBLE = NOW() WHERE MEMBER_ID = {$memberDetails['MEMBER_ID']} ";
                if (!mysqli_query($dbc,$query))
                {
                    echo("Error description: " . mysqli_error($dbc));
                }

            }


            $query = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE, TXN_DESC, AMOUNT, SERVICE_ID)
                                      values({$memberDetails['MEMBER_ID']}, 4, 'You can now apply for lifetime!' , 0.00, 6);";

            if (!mysqli_query($dbc,$query))
            {
                echo("Error description: " . mysqli_error($dbc));
            }

        }


}





