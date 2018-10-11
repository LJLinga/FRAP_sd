<?php
session_start();
require_once('mysql_connect_FA.php');
if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}

    $page_title = 'Loans - View Active';
    include 'GLOBAL_TEMPLATE_Header.php';
    include 'LOAN_TEMPLATE_NAVIGATION_Membership.php';
?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            On-going FALP Loans
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">

                       <div class="row">

                            <div class="col-lg-12">

                                <form action="ADMIN FALP viewdetails.php" method="POST"> <!-- SERVER SELF -->

                                <table id="table" class="table table-bordered table-striped">
                                    
                                    <thead>

                                        <tr>

                                        <td align="center" width="250px"><b>Name</b></td>
                                        <td align="center"><b>Department</b></td>
                                        <td align="center" width="150px"><b>Amount Paid</b></td>
                                        <td align="center" width="150px"><b>Amount Payable</b></td>
                                        <td align="center"><b>Actions</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>
										<?php 
										$query = "	SELECT m.LASTNAME,m.FIRSTNAME,r.DEPT_NAME,l.PAYABLE,l.AMOUNT_PAID,l.LOAN_ID FROM LOANS l 
													join member m 
													on l.member_id = m.member_id 
													join ref_department r
													on r.dept_id = m.dept_id
													WHERE LOAN_STATUS = 2 AND LOAN_DETAIL_ID = 1";
										$result = mysqli_query($dbc,$query);
										while($ans = mysqli_fetch_assoc($result)){
											
											?>
                                        <tr>

                                        <td align="center"><?php echo $ans['FIRSTNAME'].$ans['LASTNAME'];?></td>
                                        <td align="center"><?php echo $ans['DEPT_NAME'];?></td>
                                        <td align="center"><?php echo $ans['AMOUNT_PAID'];?></td>
                                        <td align="center"><?php echo $ans['PAYABLE'];?></td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<button type="submit" name="details" class="btn btn-success" value=<?php echo $ans['LOAN_ID'];?>>Details</button>&nbsp;&nbsp;&nbsp;</td>

                                        </tr>
										<?php } ?>
                                        
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
