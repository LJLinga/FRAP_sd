<?php
    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';
    include 'GLOBAL_FRAP_ADMIN_CHECKING.php';



    $page_title = 'Admin - Deductions Screen';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_ADMIN_SIDEBAR.php';

?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Pending FALP Deductions
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

                                        <td align="center"><b>Date of Deduction</b></td>
                                        <td align="center"><b>Name</b></td>
                                        <td align="center"><b>Department</b></td>
                                        <td align="center"><b>Amount</b></td>
                                        <td align="center"><b>Actions</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php 

                                            $query = " SELECT 
                                            
                                            
                                            ";
                                            $result = mysqli_query($dbc, $query);
                                            
                                            foreach ($result as $resultRow) {
                                        ?>

                                        <tr>

                                        <td align="center"><?php echo $resultRow['DATE_APPLIED']; ?></td>
                                        <td align="center"><?php echo $resultRow['FIRSTNAME'] ." ". $resultRow['LASTNAME']; ?></td>
                                        <td align="center"><?php echo $resultRow['DEPT_NAME']; ?></td>
                                        <td align="center"><?php echo $resultRow['AMOUNT']; ?></td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<button type='submit' class='btn-xs btn-success' name='Fdetails' value='<?php echo $resultRow['LOAN_ID']; ?>'>Details</button>&nbsp;&nbsp;&nbsp;</td>

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
    

    <!-- Bootstrap Core JavaScript -->
    

    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script>

        $(document).ready(function(){
    
            $('#table').DataTable();

        });

    </script>

<?php include "GLOBAL_FOOTER.php" ?>