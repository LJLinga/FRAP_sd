<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 08/11/2018
 * Time: 4:00 PM
 */

    $edmsRole = $_SESSION['EDMS_ROLE'];
    if($edmsRole != 1 && $edmsRole != 2 && $edmsRole != 3) {
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/MEMBER dashboard.php");
    }

?>