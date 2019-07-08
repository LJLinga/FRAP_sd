<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 2/17/2019
 * Time: 11:34 PM
 */

    $sysRole = $_SESSION['SYS_ROLE'];
    if($sysRole == 1) { // 1 = Member in frap/cms/edms terms basically the most basic privilege.
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/MEMBER dashboard.php");
    }


?>