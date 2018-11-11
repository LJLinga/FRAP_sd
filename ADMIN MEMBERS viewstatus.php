<!DOCTYPE html>
<html lang="en">
<?php
require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';


 $query = "SELECT m.member_ID, m.FIRSTNAME,m.LASTNAME,ha.Record_ID as 'has_HA', f.Amount as 'FFee', b.Amount as 'BFee', l.date_added as 'has_L'
              from member m
              left join (SELECT * from health_aid where app_status = 2) ha
              on m.member_id = ha.member_id
              left join (SELECT member_id,sum(PER_PAYMENT) as 'Amount' 
                         from Loans where LOAN_DETAIL_ID = 1 AND LOAN_STATUS = 2 group by member_id) f
              on f.member_id = m.member_id
              left join (SELECT member_id,sum(PER_PAYMENT) as 'Amount' 
                         from Loans where LOAN_DETAIL_ID != 1 AND LOAN_STATUS = 2 group by member_id ) b
              on b.member_id = m.member_id
               left join (SELECT * from lifetime where app_status = 2) l
              on m.member_id = l.member_id
              ";
$result = mysqli_query($dbc,$query);

$page_title = 'Loans - View Member Status';
include 'GLOBAL_HEADER.php';
include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';
?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Member's Services Status
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
                                        <td align="center" width="100px"><b>FALP</b></td>
                                        <td align="center" width="100px"><b>Bank Loan</b></td>
                                        <td align="center" width="100px"><b>Health Aid</b></td>
                                        <td align="center" width="100px"><b>Lifetime Member</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <tr>
                                        <?php 
                                        while($row = mysqli_fetch_assoc($result)){
                                            $ha = false;
                                            $ffee = false;
                                            $bfee = false;
                                            $l = false;
                                            if(!empty($row['has_HA'])){
                                                $ha = true;
                                            }
                                            if(!empty($row['FFee'])){
                                                $ffee = true;
                                            }
                                            if(!empty($row['BFee'])){
                                                $bfee = true;
                                            }
                                            if(!empty($row['has_L'])){
                                                $l = true;
                                            }



                                            ?>
                                        <td align="center"><?php echo $row['member_ID'];?></td>
                                        <td align="center"><?php echo $row['FIRSTNAME']." ".$row['LASTNAME'];?> </td>
                                        <td align="center"><?php if($ffee){
                                            echo '<i class="fa fa-check fa-lg statusO"></i>';
                                        }
                                        else
                                            echo '<i class="fa fa-close fa-lg statusX"></i>';?>&nbsp;&nbsp;&nbsp;</td>
                                        <td align="center"><?php if($bfee){
                                            echo '<i class="fa fa-check fa-lg statusO"></i>';
                                        }
                                        else
                                            echo '<i class="fa fa-close fa-lg statusX"></i>';?>&nbsp;&nbsp;&nbsp;</td>
                                        <td align="center"><?php if($ha){
                                            echo '<i class="fa fa-check fa-lg statusO"></i>';
                                        }
                                        else
                                            echo '<i class="fa fa-close fa-lg statusX"></i>';?>&nbsp;&nbsp;&nbsp;</td>
                                        <td align="center"><?php if($l){
                                            echo '<i class="fa fa-check fa-lg statusO"></i>';
                                        }
                                        else
                                            echo '<i class="fa fa-close fa-lg statusX"></i>';?>&nbsp;&nbsp;&nbsp;</td>
                                        

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
