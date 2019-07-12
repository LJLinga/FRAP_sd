<?php

require('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
$notifId = $_POST['id'];
$crud->execute("UPDATE notifications SET seen = '2' WHERE id = '$notifId'");
header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/".$_POST['hyperlink']);

?>