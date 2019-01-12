<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 08/11/2018
 * Time: 4:00 PM
 */


//this checks if the current user role is an admin of the admin side of the FRAP - this code is only applicable to admin screens.

$query = "SELECT FRAP_ROLE FROM employee WHERE MEMBER_ID = '{$_SESSION['idnum']}' ";
$row = mysqli_query($dbc, $query);
$frapRole = mysqli_fetch_array($row);

if($frapRole['FRAP_ROLE'] == 1) { // 1 = Member in frap/cms/edms terms basically the most basic privilege.

    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/MEMBER dashboard.php");

}else{
        $frapRole = $frapRole['FRAP_ROLE'];
        $userId = $_SESSION['idnum'];
}





