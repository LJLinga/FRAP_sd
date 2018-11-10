<?php


//get the usertypes of the employee, and its easy
// first we have to check if the user is  active, non active resigned etc.



$queryUser = "SELECT m.USER_STATUS
          from employee e
          join member m 
          on e.MEMBER_ID = m.MEMBER_ID
          where m.MEMBER_ID = {$_SESSION['idnum']}";
$rowUserStatus = mysqli_query($dbc, $queryUser);
$userStatus = mysqli_fetch_array($rowUserStatus);

if($userStatus['USER_STATUS'] != 1){ //meaning the account they are using has been deactivated.

    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");

}

//then we have to give them their respective stuffs, like the admin tools that Christian said.
//REMEMBER! If the user has at least ONE admin role or higher then the admin tools WILL appear no matter waht.


?>












