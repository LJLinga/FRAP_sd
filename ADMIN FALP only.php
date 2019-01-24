<?php
require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';

/*-------FILE REPO STUFF------*/
//$_SESSION['parentFolderID']="";
//$_SESSION['currentFolderID']="1HyfFzGW48DJfK26lN_cYtKBhRCrQJbso";
/*-------FILE REPO STUFF END------*/

    if (isset($_POST['submit'])) {

        if(!empty($_POST['idNum'])){


            //Part timer part check

            //first check if the part timer is eligible for a loan

            $idNum = $_POST['idNum'];

            $query = "SELECT PART_TIME_LOANED, USER_STATUS FROM member where {$idNum} = MEMBER_ID";
            $result = mysqli_query($dbc, $query);
            $row = mysqli_fetch_assoc($result);


            if($row['PART_TIME_LOANED'] == 'N' && $row['USER_STATUS'] = 2){

                echo '<script language="javascript">';
                echo 'alert("This person is no longer eligible for a loan for this term!")';
                echo '</script>';


            }else{ // which means the selected user is not a part timer

                //add an if statement here to see if the guy you are going to add has paid 50% of his loan, then say if he cannot have another loan unless he has paid 50%
                $query = "SELECT * FROM LOANS where {$idNum} = MEMBER_ID && APP_STATUS = 2  ";
                $result = mysqli_query($dbc, $query);
                $row = mysqli_fetch_assoc($result);

                $halfAmount = $row['PAYABLE']/2; //the halved amount of the payment the person needs to pay with salary deduction

                if($halfAmount > $row['AMOUNT_PAID']){

                    echo '<script language="javascript">';
                    echo 'alert("This person has a current Loan and has not paid 50% of it!")';
                    echo '</script>';

                }else {
                    // 500 per month.
                    // 250 per payment.
                    $halfterms = $_POST['terms']; //this fucking means monthly.
                    $perPayment = ($_POST['amount'] / $halfterms) + 250;


                    $query = "INSERT INTO loans(MEMBER_ID,LOAN_DETAIL_ID,AMOUNT,INTEREST,PAYMENT_TERMS,PAYABLE,PER_PAYMENT,APP_STATUS,LOAN_STATUS,DATE_APPLIED,PICKUP_STATUS)
                                      values({$idNum},1,{$_POST['amount']},500,{$halfterms},{$_POST['amount']}+(500*{$halfterms}),{$perPayment},2,2,DATE(now()),1);";

                    mysqli_query($dbc, $query);

                    $success = "yes";
                }

            }





        }else{
            echo '<script language="javascript">';
            echo 'alert("Select a Member first!")';
            echo '</script>';
        }




    }


$page_title = 'FALP - Only ';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';
?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Add FALP Account to Member
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">

                        <!--Insert success page--> 
                        <form method="POST" action="ADMIN%20FALP%20only.php" id="addAccount" onSubmit="return checkform()">

                            <div class="panel panel-green">

                                <div class="panel-heading">

                                    <b>List of People</b>

                                </div>
                               

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <table id="table" class="table table-bordered table-striped">

                                                <thead>

                                                <tr>
                                                    <td align="center"></td>
                                                    <td align="center"><b>ID Number</b></td>
                                                    <td align="center" width="300px"><b>Name</b></td>
                                                    <td align="center"><b>Department</b></td>
                                                    <td align="center"><b>Full-Time/Part-Time?</b></td>
                                                    <td align="center"><b>Part Time FALP Eligible?</b></td>
                                                    <td align="center"><b>Member Since</b></td>


                                                </tr>

                                                </thead>

                                                <tbody>
                                                <?php
                                                $query2 = "SELECT m.MEMBER_ID, m.FIRSTNAME, m.LASTNAME,  u.STATUS, d.DEPT_NAME, m.DATE_APPROVED, m.PART_TIME_LOANED
                                                           FROM member m 
                                                           join ref_department d
                                                           on m.dept_id = d.dept_id
                                                           join user_status u 
                                                           on m.USER_STATUS = u.STATUS_ID
                                                           where m.membership_status = 2";
                                                        $result2 = mysqli_query($dbc,$query2);


                                                while($row2 = mysqli_fetch_assoc($result2)){

                                                    ?>
                                                    <tr>

                                                        <td align="center"><?php echo "<input type='radio' name='idNum' value='".$row2['MEMBER_ID']."'>" ; ?></td>
                                                        <td align="center"><?php echo $row2['MEMBER_ID'];?></td>
                                                        <td align="center"><?php echo $row2['FIRSTNAME']." ".$row2['LASTNAME'];?> </td>
                                                        <td align="center"><?php echo $row2['DEPT_NAME'];?></td>
                                                        <td align="center"><?php echo $row2['STATUS'];?></td>
                                                        <td align="center"><?php echo $row2['PART_TIME_LOANED'];?></td>
                                                        <td align="center"><?php echo $row2['DATE_APPROVED'];?></td>

                                                    </tr>
                                                <?php }?>


                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                            <div class="panel panel-green">

                                <div class="panel-heading">

                                    <b>FALP  Information</b>
                                </div>

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-4">

                                            <input type="checkbox" name="ifPartTime" value="partTime" id="partTime"> Is the member a part time? <br>
                                            <label class="memfieldlabel">Amount</label><big class="req"> *</big>
                                            <input type="number" class="form-control" placeholder="Enter Amount (Peso)" name="amount" id="amount"  maxlength="5">

                                        </div>

                                    </div>

                                    <p>

                                    <div class="row">

                                        <div class="col-lg-4">
                                            
                                            <label class="memfieldlabel">Payment Terms</label><big class="req"> *</big>
                                            <input type="number" class="form-control" placeholder="Payment Terms" name="terms"  id="terms">
                                            <p>
                                            <div id = "totalI">   </div> <p>
                                            <p>
                                            <div id = "totalP"> </div><p>
                                            <p>
                                            <div id = "PerP"></div><p>
                                            <p>
                                            <div id = "Monthly"></div>

                                            <input type="button" name="compute" class="btn btn-success" value="Compute" id="falpcompute">
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <input class="btn btn-success" type="submit" name="submit" value="Submit"></p>

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
       
        
   
    <?php if (!empty($success)) {
        
        echo "<script type='text/javascript'>alert('Success!');</script>";
        
    }

    ?>
    <!-- Bootstrap Core JavaScript -->

    <script type="text/javascript" src="DataTables/datatables.min.js"></script>

    <script src="js/bootstrap.min.js"></script>
<script>
        document.getElementById("falpcompute").onclick = function() {
            checkform();
        };
        
        function calculate(){
            
            var amount = parseFloat(document.getElementById("amount").value);
            var terms = parseFloat(document.getElementById("terms").value);
            var interest = 500;
            
            document.getElementById("totalI").innerHTML ="<b>Total Interest Payable: </b>₱"+ parseFloat((interest*terms)).toFixed(2);
            document.getElementById("totalP").innerHTML ="<b>Total Amount Payable: </b> ₱"+ parseFloat((amount+(interest*terms))).toFixed(2);
            document.getElementById("PerP").innerHTML ="<b>Per Payment Period Payable: </b> ₱ "+ parseFloat((amount/(terms*2) + (interest/2))).toFixed(2);
            document.getElementById("Monthly").innerHTML ="<b>Monthly Payable: </b> ₱"+ parseFloat(((amount/(terms*2) + interest/2)*2)).toFixed(2);

        }


        function checkform(){

            var amount = parseFloat(document.getElementById("amount").value);
            var terms = parseFloat(document.getElementById("terms").value);


            var amountLimit;
            var termMax;

            if(document.getElementById("partTime").checked){
                amountLimit = 15000;
                termMax = 5;
            }else{
                amountLimit = 25000;
                termMax = 10;
            }



            if(amount<5000){
                alert("Amount entered is below minimum. Please enter amount within the range.");
                return false;
            }
            else if(amount > amountLimit){
                alert("Amount entered is above maximum. Please enter amount within the range.");
                return false;
            }
            else if(terms  < 5 ){ // if terms are below 3, deins dapat to
                alert("Terms entered is below minimum . Please enter amount within the range.");
                return false;
            }else if(terms > termMax) {
                alert("Terms entered is above maximum . Please enter amount within the range.");
                return false;
            } else if(isNaN(amount)){
                alert("Invalid Input");
                return false;
            }else if (isNaN(terms)){
                alert("No Terms");
                return false;
            } else{
                calculate();
                return true;
            }

        }


        $(document).ready(function(){

            $('#table').DataTable();

        });

    </script>

<?php include 'GLOBAL_FOOTER.php'; ?>