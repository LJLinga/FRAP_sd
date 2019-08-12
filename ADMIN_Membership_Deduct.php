<?php

require_once ("mysql_connect_FA.php");

// first things first -  get all the members in the system


$allMemberIdQuery = "SELECT MEMBER_ID, USER_STATUS from member WHERE MEMBERSHIP_STATUS = 2";
$allMemberIdResult = mysqli_query($dbc,$allMemberIdQuery);


// then for each member, insert into the transaction table where in it deducts.  Basically
foreach ($allMemberIdResult as $member) {

    if($member['USER_STATUS'] == 1){ // means full time
        $query = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE, TXN_DESC, AMOUNT, SERVICE_ID)
                                  values({$member['MEMBER_ID']}, 2, 'Membership Fee has been deducted from your salary.' , 183.33, 1);";

        if (!mysqli_query($dbc,$query))
        {
            echo("Error description: " . mysqli_error($dbc));
        }

    }else if($member['USER_STATUS'] == 2){ //means part time

        $query = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE, TXN_DESC, AMOUNT, SERVICE_ID)
                                  values({$member['MEMBER_ID']}, 2, 'Membership Fee has been deducted from your salary.' , 91.67, 1);";

        if (!mysqli_query($dbc,$query))
        {
            echo("Error description: " . mysqli_error($dbc));
        }

    }

}


?>