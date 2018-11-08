<?php
session_start();
require_once("mysql_connect_FA.php");

if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}

    $message = NULL;
    if(isset($_POST['submit'])){

        //Input Validation
        if(isset($_POST['bank'])){
            $_SESSION['apBank'] = $_POST['bank'];
        }
        else{
            $message .= "You forgot to choose a bank!";
        }

        if(isset($_POST['amountMin'])){
            if(is_numeric($_POST['amountMin']) && $_POST['amountMin'] >= 0){
                $_SESSION['apAmtMin'] = $_POST['amountMin'];
            }
            else{
                $message .= "Please enter valid numeric values only for amount (minimum)!";
            }
        }
        else{
            $message .= "You forgot to enter an amount (minimum)!";
        }

        if(isset($_POST['amountMax'])){
            if(is_numeric($_POST['amountMax']) && $_POST['amountMax'] >= 0){
                $_SESSION['apAmtMax'] = $_POST['amountMax'];
            }
            else{
                $message .= "Please enter valid numeric values only for amount (maximum)!";
            }
        }
        else{
            $message .= "You forgot to enter an amount (maximum)!";
        }

        if(isset($_POST['interest'])){
            if(is_numeric($_POST['interest']) && $_POST['interest'] >= 0){
                $_SESSION['apInterest'] = $_POST['interest'];
            }
            else{
                $message .= "Please enter valid numeric values only for interest!";
            }
        }
        else{
            $message .= "You forgot to enter an interest!";
        }

        if(isset($_POST['payTermsMin'])){
            if(is_numeric($_POST['payTermsMin']) && $_POST['payTermsMin'] >= 0){
                $_SESSION['apPayTermsMin'] = $_POST['payTermsMin'];
            }
            else{
                $message .= "Please enter valid numeric values only for Payment Terms (Minimum)!";
            }
        }
        else{
            $message .= "You forgot to enter Payment Terms (Minimum)!";
        }

        if(isset($_POST['payTermsMax'])){
            if(is_numeric($_POST['payTermsMax']) && $_POST['payTermsMax'] >= 0){
                $_SESSION['apPayTermsMax'] = $_POST['payTermsMax'];
            }
            else{
                $message .= "Please enter valid numeric values only for Payment Terms (Maximum)!";
            }
        }
        else{
            $message .= "You forgot to enter Payment Terms (Maximum)!";
        }

        //Query
        if(!isset($message)){
            $query = "INSERT INTO LOAN_PLAN (BANK_ID, MIN_AMOUNT, MAX_AMOUNT, INTEREST, MIN_TERM, MAX_TERM) 
            VALUES('{$_SESSION['apBank']}', '{$_SESSION['apAmtMin']}', '{$_SESSION['apAmtMax']}','{$_SESSION['apInterest']}', '{$_SESSION['apPayTermsMin']}', '{$_SESSION['apPayTermsMax']}');";
            $result = mysqli_query($dbc, $query);
            $message .= "Bank plan added successfully!";
        }
    }

    $page_title = 'Loans - Add Plan';
    include 'GLOBAL_HEADER.php';
    include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';
?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Add Loan Plan for Bank
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

                        <p><i>Fields with <big class="req">*</big> are required to be filled out and those without are optional.</i></p>

                        <!--Insert success page--> 
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="addAccount">

                            <div class="row">

                                <div class="col-lg-6">

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Select Bank</b>

                                        </div>

                                        <div class="panel-body">

                                            <div class="row">

                                                <div class="col-lg-12">

                                                    <select class="form-control" name="bank">
                                                <?php 
                                                    $query = "SELECT * FROM BANKS WHERE BANK_ID != 1;";
                                                    $result = mysqli_query($dbc, $query);
                                    
                                                    foreach ($result as $resultRow){
                                                        echo "<option value='" .  $resultRow['BANK_ID'] ."'>" . $resultRow['BANK_ABBV'] . " (" . $resultRow['BANK_NAME'] . ")" . "</option>";
                                                    }
                                                ?>
                                                    </select>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <b>Enter Amount Range:</b><p>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-lg-3">

                                    Amount (Minimum): <input type="number" name="amountMin" class="form-control">

                                </div>

                                <div class="col-lg-3">

                                    Amount (Maximum): <input type="number" name="amountMax" class="form-control">

                                </div>

                            </div><p>

                            <div class="row">
                                <div class="col-lg-12">
                                    <b>Enter Interest %:</b><p>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-lg-3">

                                    Interest %: <input type="number" name="interest" class="form-control">

                                </div>

                            </div><p>

                            <div class="row">
                                <div class="col-lg-12">
                                    <b>Enter Payment Term Range:</b><p>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-lg-3">

                                    Payment Terms (Minimum): <input type="number" name="payTermsMin" class="form-control">

                                </div>

                                <div class="col-lg-3">

                                    Payment Terms (Maximum): <input type="number" name="payTermsMax" class="form-control">

                                </div>

                            </div><p>

                            <div class="row">

                                <div class="col-lg-12">

                                    <input type="submit" class="btn btn-success" name="submit" value="submit">

                                </div>

                            </div>

                        </form>

                    </div>
                </div>


            </div>

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

</body>

</html>
