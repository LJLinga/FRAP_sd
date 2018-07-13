<?php
session_start();
 $_SESSION=array();
 session_destroy();
 setcookie('PHPSESSID','',time()-300,'/','',0);

       header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");

?>
