<!DOCTYPE html>
<html lang="en">
<?php

/**
 * Created by PhpStorm.
 * User: sam
 * Date: 06/11/2018
 * Time: 11:29 PM
 */

session_start();
require_once("mysql_connect_FA.php");
if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

    header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$rows = $crud->getData("SELECT e.MEMBER_ID,  e.FIRSTNAME, e.LASTNAME, f.description AS 'FRAP', c.description AS 'CMS', ed.description AS 'EDMS'
                               FROM  employee e
                               JOIN frap_roles f
                               ON e.FRAP_ROLE = f.id
                               JOIN cms_roles c
                               ON e.CMS_ROLE = c.id
                               JOIN edms_roles ed 
                               ON e.EDMS_ROLE = ed.id");





$page_title = 'Loans - View Member Roles';
include 'GLOBAL_TEMPLATE_Header.php';
include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';
?>

<div id="page-wrapper">

    <div class="container-fluid">

        <div class="row">

            <div class="col-lg-12">

                <h1 class="page-header">
                    Member's Roles
                </h1>

            </div>

        </div>
        <!-- alert -->
        <div class="row">
            <div class="col-lg-12">

                <div class="row">

                    <div class="col-lg-12">

                        <form action="#" method="POST"> <!-- SERVER SELF -->

                            <table id="table" class="table table-bordered table-striped">

                                <thead>

                                <tr>

                                    <td align="center" width="200px"><b>ID Number</b></td>
                                    <td align="center" width="400px"><b>Name</b></td>
                                    <td align="center" width="100px"><b>FRAP Role</b></td>
                                    <td align="center" width="100px"><b>CMS Role</b></td>
                                    <td align="center" width="100px"><b>EDMS Role</b></td>

                                </tr>

                                </thead>

                                <tbody>

                                <tr>
                                    <?php
                                    foreach ($rows as $key => $row){

                                    $id = $row['MEMBER_ID'];
                                    $name = $row['FIRSTNAME']." ".$row['LASTNAME'];
                                    $frap = $row['FRAP'];
                                    $cms =  $row['CMS'];
                                    $edms = $row['EDMS'];



                                    



                                    ?>
                                    <td align="center"><?php echo $id;?></td>
                                    <td align="center"><?php echo $name;?> </td>
                                    <td align="center"><?php echo $frap;?> </td>
                                    <td align="center"><?php echo $cms;?> </td>
                                    <td align="center"><?php echo $edms;?> </td>


                                </tr>
                                <?php }?>

                                </tbody>

                            </table>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<script type="text/javascript" src="DataTables/datatables.min.js"></script>
<script>

    $(document).ready(function(){

        $('#table').DataTable();

    });

</script>

</body>

</html>
