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
if(isset($_POST['idnums'])){
    if(isset($_POST['frap'])){
            

                foreach($_POST['idnums'] as $idnum){
                     $query = "UPDATE employee set FRAP_ROLE = {$_POST['frap']} where EMP_ID = {$idnum}";
                     mysqli_query($dbc,$query);
                }
            
    }
    else if(isset($_POST['cms'])){

             foreach($_POST['idnums'] as $idnum){
                     $query = "UPDATE employee set CMS_ROLE = {$_POST['cms']} where EMP_ID = {$idnum}";
                     mysqli_query($dbc,$query);
                }
    }
    else if(isset($_POST['edms'])){
         foreach($_POST['idnums'] as $idnum){
                     $query = "UPDATE employee set EDMS_ROLE = {$_POST['edms']} where EMP_ID = {$idnum}";
                     mysqli_query($dbc,$query);
                }
    }
}


$page_title = 'Loans - View Member Roles';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';


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

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> <!-- SERVER SELF -->

                                <table id="table" class="table table-bordered table-striped">

                                <thead>

                                <tr>
                                    <td align="center" width="200px"><b></b></td>
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

                                    $query = "SELECT e.MEMBER_ID,  e.FIRSTNAME, e.LASTNAME, f.rolename AS 'FRAP', c.rolename AS 'CMS', ed.rolename AS 'EDMS'
                                           FROM  employee e
                                           JOIN frap_roles f
                                           ON e.FRAP_ROLE = f.id
                                           JOIN cms_roles c
                                           ON e.CMS_ROLE = c.id
                                           JOIN edms_roles ed 
                                           ON e.EDMS_ROLE = ed.id
                                           where e.acc_status = 2";
                                    $result = mysqli_query($dbc,$query);
                                    $row = mysqli_fetch_array($result);

                                    foreach ($result as $row) {

                                    ?>
                                    <td align="center"><input type = "checkbox" name = "idnums[]" value = <?php echo $row['MEMBER_ID'];?>></td>
                                    <td align="center"><?php echo $row['MEMBER_ID'];?></td>
                                    <td align="center"><?php echo $row['FIRSTNAME']." ".$row['LASTNAME'];?> </td>
                                    <td align="center"><?php echo $row['FRAP'];?> </td>
                                    <td align="center"><?php echo $row['CMS'];?> </td>
                                    <td align="center"><?php echo $row['EDMS'];?> </td>


                                </tr>
                                <?php }?>

                                </tbody>

                            </table>

                            <table border = 4>
                                <thead>
                                <tr>
                                    <td align="center" width="300px">FRAP ACTIONS</td>
                                    <td align="center" width="300px">CMS ACTIONS</td>
                                    <td align="center" width="300px">EDMS ACTIONS</td>
                                </tr>
                            </thead>
                            <tr>
                                    <td align="center">
                                        <p><button type = "submit" class="btn btn-action" name = "frap" value = 1>Members</button>
                                       
                                        <p><button type = "submit" class="btn btn-action" name = "frap" value = 3>Executive Board</button>
                                        <p><button type = "submit" class="btn btn-action" name = "frap" value = 4>President</button></td>
                                    <td align="center">
                                        <p><button type = "submit" class="btn btn-action" name = "cms" value = 1>Reader</button>
                                        <p><button type = "submit" class="btn btn-action" name = "cms" value = 2>Contributor</button>
                                        <p><button type = "submit" class="btn btn-action" name = "cms" value = 3>Reviewer</button>
                                        <p><button type = "submit" class="btn btn-action" name = "cms" value = 4>Publisher</button></td>
                                    <td align="center">
                                         <p><button type = "submit" class="btn btn-action" name = "edms" value = 1>Readers</button>
                                        
                                        <p><button type = "submit" class="btn btn-action" name = "edms" value = 3>Executive Board</button>
                                        <p><button type = "submit" class="btn btn-action" name = "edms" value = 4>President</button></td>
                                    </td>
                            </tr>
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


<!-- Bootstrap Core JavaScript -->


<script type="text/javascript" src="DataTables/datatables.min.js"></script>
<script>

    $(document).ready(function(){

        $('#table').DataTable();

    });

</script>

</body>

</html>
