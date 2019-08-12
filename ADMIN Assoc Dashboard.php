<?php

require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';

error_reporting(NULL);


$page_title = 'Admin - Loans Dashboard';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';



?>
<div id="content-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->

        <!-- Check if the date is for deductions, and launch an alert only once if done so -->

        <div class="row">

            <div class="col-lg-12">

                <h3 class="page-header"> Association Overview  </h3>

            </div>

        </div>



        <div class="row">

            <div class="row">

                <div class="col-lg-2"> <!-- This is for the the amount of applications -->

                    <div class="panel panel-default" style="margin-top: 1rem;">
                        <div class="panel-heading">
                            <b> New FAP Applications </b>
                        </div>
                        <div class="panel-body" >
                            <!--- Link to the shits---->

                        </div>
                        <div class="panel-footer" >
                            <!--  Link to the Applications -->
                        </div>
                    </div>

                    <div class="panel panel-default" style="margin-top: 1rem;">
                        <div class="panel-heading">
                            <b> New Health Aid Applications </b>
                        </div>
                        <div class="panel-body" >

                        </div>
                        <div class="panel-footer" >

                        </div>
                    </div>


                    <div class="panel panel-default" style="margin-top: 1rem;">
                        <div class="panel-heading">
                            <b> New Membership Applications </b>
                        </div>
                        <div class="panel-body" >

                        </div>
                        <div class="panel-footer" >

                        </div>
                    </div>




                </div>


                <div class="col-lg-10"> <!-- This is for the event screen  -->

                    <div class="container">

                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#home">All Applications</a></li>
                            <li><a data-toggle="tab" href="#membership">Membership</a></li>
                            <li><a data-toggle="tab" href="#fap">Faculty Assistance Program</a></li>
                            <li><a data-toggle="tab" href="#healthaid">Health Aid</a></li>
                        </ul>

                        <div class="tab-content">

                            <h3 class="page-header"> Recent Applications </h3>

                            <div id="home" class="tab-pane fade in active">

                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> <!-- SERVER SELF -->

                                    <table id="table" class="table table-bordered table-striped">

                                        <thead>

                                        <tr>

                                            <td align="center"><b>ID Number</b></td>
                                            <td align="center"><b>Name</b></td>
                                            <td align="center"><b>Date of Deduction</b></td>
                                            <td align="center"><b>Amount to Deduct</b></td>
                                            <td align="center"><b>Action</b></td>

                                        </tr>

                                        </thead>

                                        <tbody>

                                        <?php
                                        // remember to change the  td.DEDUCTION_DATE >= DATE (NOW()) to  td.DEDUCTION_DATE <= DATE (NOW()). This is merely for testing purposes.
                                        $query = " SELECT m.MEMBER_ID, l.LOAN_ID, m.FIRSTNAME, m.LASTNAME,l.PER_PAYMENT ,td.DEDUCTION_DATE, td.ID
                                            from loans l
                                            join to_deduct td
                                            on  l.LOAN_ID = td.LOAN_REF
                                            join member m
                                            on l.MEMBER_ID = m.MEMBER_ID
                                            where td.DEDUCTION_DATE <= DATE (NOW())
                                            AND td.HAS_PAID = 1
                                            LIMIT 5
                                            ";
                                        $result = mysqli_query($dbc, $query);

                                        $today = date("d");

                                        foreach ($result as $resultRow) {
                                            ?>

                                            <tr>

                                                <td align="center"><?php echo $resultRow['MEMBER_ID']; ?></td>
                                                <td align="center"><?php echo $resultRow['FIRSTNAME'] ." ". $resultRow['LASTNAME'];  ?> </td>
                                                <td align="center"><?php echo date('Y, M d', strtotime($resultRow['DEDUCTION_DATE'])); ?></td>
                                                <td align="center">₱ <?php echo number_format($resultRow['PER_PAYMENT'],2)."<br>"; ?></td>
                                                <?php if($today == '8' || $today == '23'){ //this is for when the date is the same as the Deduction Periods?>
                                                    <td align="center"><button type='submit' class='btn btn-warning' name='toDeductID' value='<?php echo $resultRow['ID']; ?>'>Deduct  </button>&nbsp;&nbsp;&nbsp;</td>
                                                <?php }else{ //this is when the date is  not the deduction period?>
                                                    <td align="center"><button type='submit' class='btn btn-warning' name='toDeductID' value='<?php echo $resultRow['ID']; ?>' disabled>Deduct </button>&nbsp;&nbsp;&nbsp;</td>

                                                <?php } ?>
                                            </tr>

                                        <?php }?>
                                        </tbody>

                                    </table>

                                </form>


                            </div>
                            <div id="membership" class="tab-pane fade">

                            </div>
                            <div id="fap" class="tab-pane fade">


                            </div>
                            <div id="healthaid" class="tab-pane fade">




                            </div>

                        </div>

                    </div>



                </div>


            </div>




        </div>



        <div class="row">

            <div class="col-lg-12 col-1">

                <!-- PUT DEDUCTIONS SUMMARY HERE -->

            </div>

        </div>
        <!-- /.row -->

    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
<script type="text/javascript" src="DataTables/datatables.min.js"></script>
<script>

    $(document).ready(function(){

        $('#table').DataTable();

    });

</script>
<?php include 'GLOBAL_FOOTER.php' ?>
