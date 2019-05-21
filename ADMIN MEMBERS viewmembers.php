<!DOCTYPE html>
<html lang="en">
<?php

    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';
    include 'GLOBAL_FRAP_ADMIN_CHECKING.php';



if(isset($_POST['details'])){

    $_SESSION['currID'] = "";

    $_SESSION['currID'] = $_POST['details'];

    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN MEMBERS viewdetails.php");

}



$page_title = 'Loans - View Members';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';
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
                                            <td align="center"><b>Employee Type</b></td>
                                            <td align="center"><b>Actions</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>
                                        <?php

                                        $query = "SELECT m.MEMBER_ID, m.FIRSTNAME, m.LASTNAME, m.DATE_APPROVED,  d.DEPT_NAME, us.STATUS
                                        FROM member m 
                                        join ref_department d
                                        on m.dept_id = d.dept_id 
                                        join user_status us 
                                        on m.USER_STATUS = us.STATUS_ID
                                        where m.membership_status = 2
                                        ORDER BY m.DATE_APPROVED DESC";

                                        $result = mysqli_query($dbc,$query);


                                        foreach ($result as $rows) {

                                            ?>
                                        <tr>

                                            <td align="center"><?php echo $rows['MEMBER_ID'];?></td>
                                            <td align="center"><?php echo $rows['FIRSTNAME']." ".$rows['LASTNAME'];?> </td>
                                            <td align="center"><?php echo $rows['DEPT_NAME'];?></td>
                                            <td align="center"><?php echo date(' M d, Y', strtotime($rows['DATE_APPROVED']));?></td>
                                            <td align="center"><?php echo $rows['STATUS'];?></td>
                                            <td align="center"><button type="submit" name="details" class="btn btn-info" value=<?php echo $rows['MEMBER_ID'];?>>View</button>&nbsp;&nbsp;&nbsp;</td>

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
