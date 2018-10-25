<?php
session_start();
require_once('mysql_connect_FA.php');
if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}

$_SESSION['bank_loan_id'] = ''; 




    if(isset($_POST["goToDetails"])){ // first it will save the server value of the selected shit before sending it to app details for identification 
        
        $_SESSION['bank_loan_id'] =  $_POST['loan_id'];

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN BANK viewdetails.php"); // sends it to the page 


    }

$page_title = 'Loans - View Active Bank';
include 'GLOBAL_TEMPLATE_Header.php';
include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';
?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            On-going Bank Loans
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">

                       <div class="row">

                            <div class="col-lg-12">

                                <form action="ADMIN BANK viewactive.php" method="POST"> <!-- SERVER SELF -->

                                <?php

                               $query="SELECT m.firstname, m.lastname, l.loan_id, rd.dept_name, l.date_applied, l.payable, l.amount, l.amount_paid, b.bank_name 
                               from loans l 
                               join member m
                               on l.member_id = m.member_id
                               join ref_department rd
                               on m.dept_id = rd.dept_id
                               join loan_plan ld
                               on l.loan_detail_id = ld.loan_id
                               join banks b
                               on ld.bank_id = b.bank_id
                               where ld.bank_id != 1 && l.app_status = 2";
        
                                $result= mysqli_query($dbc,$query);
                                
                                echo '<table id = "table" class="display" cellspacing="0" width="100%" data-page-length="10">

                                    <thead>

                                        <tr>

                                            <td> Name </td>

                                            <td> Department  </td>

                                            <td> Amount Paid </td>

                                            <td> Amount Payable </td>

                                            <td> Bank </td>

                                            <td> View Details </td>

                                        </tr>

                                    </thead>

                                    <tbody>';


                                while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){

                                echo '
                                        <tr>
                                            <td>';echo $row['firstname']." ".$row['lastname'].'</td>

                                            <td>';echo $row['dept_name'].'</td>

                                            <td>';echo $row['amount_paid'].'</td>

                                            <td>';echo $row['payable'].'</td>

                                            <td>';echo $row['bank_name'].'</td>

                                            ';echo' <input type = "text" name = "loan_id" value = "'; echo $row['loan_id']; echo'" hidden> 

                                            <td>';echo '<input type="submit" name = "goToDetails"  class="btn btn-success" value="View Details">'; 

                                        echo '</td>';   
                                        echo '</tr>';
                                }

                                echo '</tbody>';
                                echo '</table>';

                                

                                ?>
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
