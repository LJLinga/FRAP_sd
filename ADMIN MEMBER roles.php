<!DOCTYPE html>
<html lang="en">
<?php

/**
 * Created by PhpStorm.
 * User: sam
 * Date: 06/11/2018
 * Time: 11:29 PM
 */

require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';



$page_title = 'Loans - View Member Roles';
include 'GLOBAL_HEADER.php';
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

                                    $query = "SELECT e.MEMBER_ID,  e.FIRSTNAME, e.LASTNAME, f.description AS 'FRAP', c.description AS 'CMS', ed.description AS 'EDMS'
                                           FROM  employee e
                                           JOIN frap_roles f
                                           ON e.FRAP_ROLE = f.id
                                           JOIN cms_roles c
                                           ON e.CMS_ROLE = c.id
                                           JOIN edms_roles ed 
                                           ON e.EDMS_ROLE = ed.id";
                                    $result = mysqli_query($dbc,$query);


                                    foreach ($result as $row) {

                                    ?>
                                    <td align="center"><?php echo $row['MEMBER_ID'];?></td>
                                    <td align="center"><?php echo $row['FIRSTNAME']." ".$row['LASTNAME'];?> </td>
                                    <td align="center"><?php echo $row['FRAP'];?> </td>
                                    <td align="center"><?php echo $row['CMS'];?> </td>
                                    <td align="center"><?php echo $row['EDMS'];?> </td>


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
