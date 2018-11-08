<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
require_once("mysql_connect_FA.php");
if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}



if(isset($_POST['details'])){

    $_SESSION['currID'] = "";

    $_SESSION['currID'] = $_POST['details'];

    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN MEMBERS viewdetails.php");

}



$page_title = 'Loans - View Members';
include 'GLOBAL_HEADER.php';
include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';
?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Current Active Members
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

                                        <td align="center"><b>ID Number</b></td>
                                        <td align="center" width="300px"><b>Name</b></td>
                                        <td align="center"><b>Department</b></td>
                                        <td align="center"><b>Member Since</b></td>
                                        <td align="center"><b>Actions</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>
                                        <?php

                                        $query = "SELECT m.MEMBER_ID, m.FIRSTNAME, m.LASTNAME, m.DATE_APPROVED,  d.DEPT_NAME
                                        FROM member m 
                                        join ref_department d
                                        on m.dept_id = d.dept_id where m.membership_status = 2";
                                        $result = mysqli_query($dbc,$query);


                                        foreach ($result as $rows) {

                                            ?>
                                        <tr>

                                        <td align="center"><?php echo $rows['MEMBER_ID'];?></td>
                                        <td align="center"><?php echo $rows['FIRSTNAME']." ".$rows['LASTNAME'];?> </td>
                                        <td align="center"><?php echo $rows['DEPT_NAME'];?></td>
                                        <td align="center"><?php echo $rows['DATE_APPROVED'];?></td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<button type="submit" name="details" class="btn btn-success" value=<?php echo $rows['MEMBER_ID'];?>>Details</button>&nbsp;&nbsp;&nbsp;</td>

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

    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script>

        $(document).ready(function(){
    
            $('#table').DataTable();

        });

    </script>

</body>

</html>
