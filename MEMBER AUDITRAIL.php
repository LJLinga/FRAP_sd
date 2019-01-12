<?php

    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';



    $page_title = 'Loans - Audit Trail';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_USER_SIDEBAR.php';

?>
<script>
    $(document).ready(function(){
        $('#table').DataTable();
    });
</script>
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">Audit Trail</h1>
                    
                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="alert alert-info">

                            This table displays all the transaction activities done inside the system involving you.

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                Audit Table

                            </div>
                            <?php

                                $query = "SELECT tr.TXN_DATE,tr.TXN_DESC, tr.AMOUNT, tt.TYPE, e.FIRSTNAME, e.LASTNAME  FROM txn_reference tr
                                          join txn_type tt
                                          on tr.txn_type = tt.type_id
                                          join employee e
                                          on tr.EMP_ID = e.EMP_ID
                                          WHERE tr.MEMBER_ID =" . $_SESSION['idnum'].";";


                                $result = mysqli_query($dbc, $query);
                                $row = mysqli_fetch_array($result);



                            ?>

                            <div class="panel-body">

                                <table id="table" class="table table-bordered">
                                    
                                    <thead>
                                        
                                        <tr>

                                            <td align="center"><b> Transaction Date </b></td>
                                            <td align="center"><b> Transaction Type </b></td>
                                            <td align="center"><b> Transaction Description </b></td>
                                            <td align="center"><b> Amount </b></td>
                                            <td align="center"><b>  Employee Involved </b></td>

                                        </tr>

                                    </thead>

                                    <tbody>

                                    <?php

                                     while($row = mysqli_fetch_assoc($result)){

                                        ?>
                                        <tr>

                                            <td align="center"><?php echo $row['TXN_DATE'];?></td>
                                            <td align="center"><?php echo $row['TYPE'];?> </td>
                                            <td align="center"><?php echo $row['TXN_DESC'];?></td>
                                            <td align="center"><?php echo $row['AMOUNT'];?></td>
                                            <td align="center">&nbsp;<?php echo $row['FIRSTNAME']." ".$row['LASTNAME'];  ?></td>

                                        </tr>
                                    <?php }?>



                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12" align="center">

                        <a href="MEMBER dashboard.php" class="btn btn-default" role="button">Go Back</a>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">
                        &nbsp;
                    </div>

                </div>

                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
<?php include 'GLOBAL_FOOTER.php' ?>
