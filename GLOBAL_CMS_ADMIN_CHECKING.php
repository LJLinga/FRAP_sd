<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 08/11/2018
 * Time: 4:00 PM
 */

    $cmsRole = $_SESSION['CMS_ROLE'];
    if($cmsRole!=2 && $cmsRole!=3) { // 1 = Member in frap/cms/edms terms basically the most basic privilege.
        echo '<script language="javascript">';
        echo 'alert("Unauthorized module access is prohibited.")';  //not showing an alert box.
        echo '</script>';
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/MEMBER dashboard.php");
    }

?>
