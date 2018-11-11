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

    if (isset($_POST['submitbutton'])){ //Start of Update Code
        echo "Hello";
        $message=NULL;
        if (empty($_POST['addBankName'])){
          $aBankName=NULL;
          $message.='<p>You forgot to enter a valid Bank name!';
        }else
          $aBankName=$_POST['addBankName'];
        
        if (empty($_POST['addBankAbbv'])){
          $aBankAbbv=NULL;
          $message.='<p>You forgot to enter a valid Bank Abbreviation!';
        }else
          $aBankAbbv=$_POST['addBankAbbv'];
        
        
        
        if(!isset($message)){
            $query="INSERT INTO BANKS (BANK_NAME, BANK_ABBV, STATUS,EMP_ID_ADDED, DATE_ADDED, DATE_REMOVED) 
            
            VALUES('{$aBankName}','{$aBankAbbv}',1,'{$_SESSION['idnum']}',NOW(),NULL);";
            $resultMPB=mysqli_query($dbc,$query);
            $message="<b><p>Product Name: {$aBankName} added!</b>";
        }
    }/*End of main Submit conditional*/

    $page_title = 'Loans - Add Bank';
    include 'GLOBAL_TEMPLATE_Header.php';
    include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';
?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Add Partner Bank
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
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                            <div class="addaccountdiv">
                                <label class="signfieldlabel">Bank Name</label><big class="req"> *</big>
                                <input type="text" name="addBankName" class="form-control signupfield" placeholder="Bank Name" name="idnum">
                            </div><p>

                            <div class="row">

                                <div class="col-lg-12">
                                    <label class="signfieldlabel">Bank Abbreviation</label><big class="req"> *</big>
                                    <input type="text" name="addBankAbbv" class="form-control signupfield" placeholder="Bank Abbv." name="idnum">
                                </div>

                            </div><p>

                            <div id="subbutton">
                                <button type="submit" name="submitbutton" value="Add Bank" class="btn btn-success">Add Bank</button>

                            </div>

                        </form>

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

</body>

</html>
