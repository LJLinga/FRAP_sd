<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 11/08/2019
 * Time: 5:18 AM
 */
require_once ("mysql_connect_FA.php");

$query = "INSERT INTO test(message)
                                      values('HELLOW TEST');";

if (!mysqli_query($dbc,$query))
{
    echo("Error description: " . mysqli_error($dbc));
}













?>




