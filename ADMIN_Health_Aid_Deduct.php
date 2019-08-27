<?php


require_once ("mysql_connect_FA.php");

$allActiveHealthAidQuery = "SELECT MEMBER_ID, USER_STATUS from member WHERE HA_STATUS = 2 && MEMBERSHIP_STATUS = 2";
$allActiveHealthAidResult= mysqli_query($dbc,$allActiveHealthAidQuery);


// then for each member, insert into the transaction table where in it deducts.  Basically
foreach ($allActiveHealthAidResult as $healthAidMember) {


    $query = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE, TXN_DESC, AMOUNT, SERVICE_ID)
                                  values({$healthAidMember['MEMBER_ID']}, 2, 'Health Aid Fee has been deducted from your salary.' , 100.00, 2);";

    if (!mysqli_query($dbc,$query))
    {
        echo("Error description: " . mysqli_error($dbc));
    }

}
?>