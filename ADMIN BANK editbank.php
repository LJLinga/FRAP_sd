<?php

    //User Validation 
    session_start();
    if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}
    //Test Value
    //$_SESSION['adminidnum']=970121234;
    //Connect DB
    require_once('mysql_connect_FA.php');

    if(isset($_POST['action'])){

        $message.="Bank Statment entered" . $_POST['action'];

        if($_POST['action'] == "Enable Partner Bank" ){
            //Change the status into Active (APP_STATUS =1)
            $query = "UPDATE BANKS SET STATUS = '1', EMP_ID_ADDED =". $_SESSION['idnum'] .", DATE_REMOVED = NULL WHERE BANK_ID =" . $_POST['bankID'].";";
            $result = mysqli_query($dbc, $query);
            $message="Bank Enabled!";
        }                           
        else if($_POST['action'] == "Disable Partner Bank"){
             //Change the status into Inactive (APP_STATUS =2)
            $query = "UPDATE BANKS SET STATUS = '2', EMP_ID_ADDED =". $_SESSION['idnum'] .", DATE_REMOVED = NOW() WHERE BANK_ID =" . $_POST['bankID'].";";
            $result = mysqli_query($dbc, $query);
            $message="Bank Disabled!";
        }
    }
    else if(!isset($_POST['bankID']) && isset($_POST['action'])){
        $message="You forgot to choose a bank to modify!";
    }

    $page_title = 'Loans - Edit Bank';
    include 'GLOBAL_TEMPLATE_Header.php';
    include 'LOAN_TEMPLATE_NAVIGATION_Membership.php';
?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Edit Partner Bank
                        </h1>
                        <?php
                            if(isset($message)){
                                echo"  
                                <div class='alert alert-warning'>
                                    ". $message ."
                                </div>
                                ";
                            }
                        ?>
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

                                        <td align="center"><b>Select Row</b></td>
                                        <td align="center"><b>Partner Bank Name</b></td>
                                        <td align="center"><b>Number of Loan Plans</b></td>
                                        <td align="center"><b>Status</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>
                                        <?php
                                            $query = "SELECT BANK_ID, BANK_NAME, STATUS FROM BANKS WHERE BANK_ID != 1;";
                                            $result = mysqli_query($dbc, $query);
                                            foreach ($result as $resultRow) {
                                                //Count loan plans
                                                $querylp = "SELECT COUNT(LOAN_ID) AS numLoans FROM LOAN_PLAN";
                                                $resultlp = mysqli_query($dbc, $querylp);
                                                $rowlp = mysqli_fetch_array($resultlp);
                                        ?>
                                        <tr>
                                            <td align='center'><input type='radio' name='bankID' value='<?php echo $resultRow['BANK_ID']; ?>'></td>
                                            <td align="center"><?php echo $resultRow['BANK_NAME']; ?></td>
                                            <td align="center"><?php echo $rowlp['numLoans']; ?></td>
                                            <td align="center"><?php 
                                                if($resultRow['STATUS'] == 1) echo "Active";
                                                else echo "Disabled";
                                            ?></td>
                                        </tr>
                                                
                                        <?php
                                            } ?>
                                        
                                        

                                    </tbody>

                                </table><p>

                                <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Actions</b>

                                        </div>

                                        <div class="panel-body"><p>
                                            <input type="submit" class="btn btn-success" name="action" value="Enable Partner Bank" />
                                            <input type="submit" class="btn btn-danger" name="action" value="Disable Partner Bank" />

                                        </div>

                                </div>

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
