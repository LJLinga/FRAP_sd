<?php

require_once("mysql_connect_FA.php");

$sql = "SELECT MEMBER_ID
FROM MEMBER WHERE MEMBER_ID = {$_GET['id']}";

$result = mysqli_query($dbc,$sql);
$row=mysqli_fetch_assoc($result);
	if($row!=null){
		echo '<font  color = "red">ID Number has been used.</font>';
	}
	else{
		echo '<font  color = rgb(0, 214, 0)>Available</font>';
	}
?>