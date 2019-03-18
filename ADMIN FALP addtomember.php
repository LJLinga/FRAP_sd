<?php
require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';

    if (isset($_POST['memDetails'])) {

        $_SESSION['chosenMemID'] = null;

        $_SESSION['chosenMemID'] = $_POST['memDetails'];

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN FALP addtomember_details.php");

    }



$page_title = 'FALP - Add an Existing FALP to Member. ';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';
?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Add FALP Account to Member
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">

                        <!--Insert success page-->


                            <div class="panel panel-green">

                                <div class="panel-heading">

                                    <b>List of Members</b>

                                </div>
                               

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

                                            <table id="table" class="table table-bordered table-striped">

                                                <thead>

                                                <tr>
                                                    <td align="center"><b>ID Number</b></td>
                                                    <td align="center" width="300px"><b>Name</b></td>
                                                    <td align="center"><b>Department</b></td>
                                                    <td align="center"><b>Full-Time/Part-Time?</b></td>
                                                    <td align="center"><b>Has this Person Loaned? (For Part-Time)</b></td>
                                                    <td align="center"><b>Member Since</b></td>
                                                    <td align="center"><b>Action</b></td>

                                                </tr>

                                                </thead>

                                                <tbody>
                                                <?php
                                                $query2 = "SELECT m.MEMBER_ID, m.FIRSTNAME, m.LASTNAME,  u.STATUS, d.DEPT_NAME, m.DATE_APPROVED, m.PART_TIME_LOANED
                                                           FROM member m 
                                                           join ref_department d
                                                           on m.dept_id = d.dept_id
                                                           join user_status u 
                                                           on m.USER_STATUS = u.STATUS_ID
                                                           where m.membership_status = 2";
                                                        $result2 = mysqli_query($dbc,$query2);


                                                foreach ($result2 as $row2) {

                                                    ?>
                                                    <tr>

                                                        <td align="center"><?php echo $row2['MEMBER_ID'];?></td>
                                                        <td align="center"><?php echo $row2['FIRSTNAME']." ".$row2['LASTNAME'];?> </td>
                                                        <td align="center"><?php echo $row2['DEPT_NAME'];?></td>
                                                        <td align="center"><?php echo $row2['STATUS'];?></td>
                                                        <td align="center"><?php echo $row2['PART_TIME_LOANED'];?></td>
                                                        <td align="center"><?php echo $row2['DATE_APPROVED'];?></td>
                                                        <td align="center"><button type="submit" name="memDetails" class="btn-xs btn-success" value=<?php echo $row2['MEMBER_ID'];?>>Add</button></td>

                                                    </tr>
                                                <?php }?>


                                                </tbody>

                                            </table>


                                        </div>
                                        </form>
                                    </div>

                                </div>

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


<?php include 'GLOBAL_FOOTER.php'; ?>