<?php
    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';
    include 'GLOBAL_FRAP_ADMIN_CHECKING.php';
require 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();

    If(isset($_POST['Fdetails'])){

        $_SESSION['showFID'] = NULL;    //Loan ID

        $_SESSION['showFID'] = $_POST['Fdetails'];
       
        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN FALP appdetails.php");

    }else if(isset($_POST['pickup'])){

        $query = "UPDATE loans set PICKUP_STATUS = 2 WHERE LOAN_ID= {$_POST['pickup']}";
        $result = mysqli_query($dbc, $query);


        $query = "SELECT MEMBER_ID,AMOUNT FROM loans  WHERE LOAN_ID = {$_POST['pickup']}";
        $result = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($result);

        $crud->execute("INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, LOAN_REF,EMP_ID ,SERVICE_ID) 
                          VALUES({$row['MEMBER_ID']}, 1, 'Loan Ready to Pickup!', 0.00, {$_POST['pickup']},{$_SESSION['idnum']},4)");


    } else if(isset($_POST['pickedup'])){

        $query = "UPDATE loans set PICKUP_STATUS = 3  WHERE LOAN_ID= {$_POST['pickedup']}";
        $result = mysqli_query($dbc, $query);

        $query = "SELECT MEMBER_ID FROM loans  WHERE LOAN_ID = {$_POST['pickedup']}";
        $result = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($result);

        $crud->execute("INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, LOAN_REF,EMP_ID ,SERVICE_ID) 
                          VALUES({$row['MEMBER_ID']}, 1, 'Loan has been Picked up! Deductions will start now.', 0.00, {$_POST['pickedup']},{$_SESSION['idnum']} , 4)");


        //put the insert deductions query here REMEMBER THAT LOAN STARTS AFTER THE LOAN IS PICKED UP

        $query = "UPDATE loans set LOAN_STATUS = 2  WHERE LOAN_ID= {$_POST['pickedup']}";
        $result = mysqli_query($dbc, $query);



        $loanDetails = "SELECT * FROM LOANS WHERE LOAN_ID = {$_POST['pickedup']}";
        $loanDetailsResult = mysqli_query($dbc,$loanDetails);
        $loanDetailsRow = mysqli_fetch_assoc($loanDetailsResult);

        $currYear = date("Y");
        $currDay = date('d');
        $currMonth = date("m");

        $dateToInsert = date("Y-m-d", strtotime($currYear."-".$currMonth."-".$currDay));


        $i = 0; //this will keep track on how many payments has been inserted.

        while($i < (($loanDetailsRow['PAYMENT_TERMS']*2) - $loanDetailsRow['PAYMENTS_MADE'])){  //get the payment terms, and have the counter keep track on how many has been  inserted already.

            //check if it is February first so we can adjust the end date by February 28.

            if($currMonth == 2){ //assumes that the current month is February

                if($currDay < 15){

                    $currDay = 15;

                    $dateToInsert = date("Y-m-d", strtotime($currYear."-".$currMonth."-".$currDay));

                    $query5 = "INSERT INTO to_deduct(LOAN_REF, DEDUCTION_DATE)
                             values ({$loanDetailsRow['LOAN_ID']},'{$dateToInsert}')";

                    if (!mysqli_query($dbc,$query5))
                    {
                        echo("Error description: " . mysqli_error($dbc));
                    }

                    $i++;

                }else if($currDay < 28){

                    $currDay = 28;

                    $dateToInsert = date("Y-m-d", strtotime($currYear."-".$currMonth."-".$currDay));

                    $query5 = "INSERT INTO to_deduct(LOAN_REF, DEDUCTION_DATE)
                             values ({$loanDetailsRow['LOAN_ID']},'{$dateToInsert}')";

                    if (!mysqli_query($dbc,$query5))
                    {
                        echo("Error description: " . mysqli_error($dbc));
                    }

                    $i++;

                }else{

                    $currMonth++;

                    $currDay = 1;

                }

            }else { //this means its just any day of the month

                if($currDay < 15){

                    $currDay = 15;

                    $dateToInsert = date("Y-m-d", strtotime($currYear."-".$currMonth."-".$currDay));

                    $query5 = "INSERT INTO to_deduct(LOAN_REF, DEDUCTION_DATE)
                             values ({$loanDetailsRow['LOAN_ID']},'{$dateToInsert}')";

                    if (!mysqli_query($dbc,$query5))
                    {
                        echo("Error description: " . mysqli_error($dbc));
                    }

                    $i++;

                }else if($currDay < 30){

                    $currDay = 30;

                    $dateToInsert = date("Y-m-d", strtotime($currYear."-".$currMonth."-".$currDay));

                    $query5 = "INSERT INTO to_deduct(LOAN_REF, DEDUCTION_DATE)
                             values ({$loanDetailsRow['LOAN_ID']},'{$dateToInsert}')";

                    if (!mysqli_query($dbc,$query5))
                    {
                        echo("Error description: " . mysqli_error($dbc));
                    }

                    $i++;

                }else {

                    $currMonth++;

                    if ($currMonth == 13) {
                        $currMonth = 1;

                        $currYear++;
                    }

                    $currDay = 1;
                }

            }

        }

    }

    $page_title = 'Admin - Dashboard';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_ADMIN_SIDEBAR.php';

?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Pending FALP Applications
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

                                        <td align="center"><b>Date Applied</b></td>
                                        <td align="center"><b>Name</b></td>
                                        <td align="center"><b>Department</b></td>
                                        <td align="center"><b>Amount</b></td>
                                        <td align="center"><b>Document Statuses</b></td>
                                        <td align="center"><b>Actions</b></td>
                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php 

                                            $query = "SELECT L.DATE_APPLIED, M.MEMBER_ID, M.FIRSTNAME, M.LASTNAME, RD.DEPT_NAME, L.AMOUNT, L.LOAN_ID , L.APP_STATUS, L.PICKUP_STATUS, S.stepName
                                                      FROM MEMBER M 
                                                      JOIN LOANS L 
                                                      ON M.MEMBER_ID = L.MEMBER_ID 
                                                      JOIN REF_DEPARTMENT RD 
                                                      ON M.DEPT_ID = RD.DEPT_ID 
                                                      JOIN ref_document_loans RDL
                                                      ON L.LOAN_ID = RDL.LOAN_ID
                                                      JOIN documents D
                                                      ON RDL.DOC_ID = D.documentId
                                                      JOIN steps S
                                                      ON D.stepId = S.id
                                                      WHERE L.LOAN_STATUS != 3 && L.LOAN_STATUS != 4 && L.LOAN_STATUS != 6
                                                      AND L.PICKUP_STATUS != 3
                                                      AND RDL.DOC_REQ_TYPE = 1;";
                                            $result = mysqli_query($dbc, $query);
                                            
                                            foreach ($result as $resultRow) {
                                        ?>

                                        <tr>

                                        <td align="center"><?php echo date(' M d, Y', strtotime($resultRow['DATE_APPLIED'])); ?></td>
                                        <td align="center"><?php echo $resultRow['FIRSTNAME'] ." ". $resultRow['LASTNAME']; ?></td>
                                        <td align="center"><?php echo $resultRow['DEPT_NAME']; ?></td>
                                        <td align="center">₱ <?php echo number_format($resultRow['AMOUNT'],2)."<br>"; ?></td>
                                            <td align="center"><?php echo $resultRow['stepName']; ?></td>
                                            <?php if($resultRow['APP_STATUS'] == 2 && $resultRow['PICKUP_STATUS'] == 1){ ?>
                                                <td align="center">&nbsp;&nbsp;&nbsp;<button type='submit' class='btn-xs btn-success' name='Fdetails' value='<?php echo $resultRow['LOAN_ID']; ?>'>Details</button>&nbsp;&nbsp;&nbsp;
                                                    <button type='submit' class='btn-xs btn-success' name='pickup'  value='<?php echo $resultRow['LOAN_ID']; ?>'>Ready For Pick Up</button></td>
                                            <?php }else if($resultRow['APP_STATUS'] == 2 && $resultRow['PICKUP_STATUS'] == 2){?>
                                                <td align="center">&nbsp;&nbsp;&nbsp;<button type='submit' class='btn-xs btn-success' name='Fdetails' value='<?php echo $resultRow['LOAN_ID']; ?>'>Details</button>&nbsp;&nbsp;&nbsp;
                                                    <button type='submit' class='btn-xs btn-success' name='pickedup'  value='<?php echo $resultRow['LOAN_ID']; ?>'>Picked Up By Member</button></td>
                                            <?php }else{?>
                                                <td align="center">&nbsp;&nbsp;&nbsp;<button type='submit' class='btn-xs btn-info' name='Fdetails' value='<?php echo $resultRow['LOAN_ID']; ?>'>View</button>&nbsp;&nbsp;&nbsp;</td>

                                            <?php }?>
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