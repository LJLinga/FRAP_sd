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

            if($row['USER_STATUS'] == 2){

                //Y  Means they have not loaned yet
                //N means they have.
                if($row['PART_TIME_LOANED'] = 'Y'){

                    echo '<script language="javascript">';
                    echo 'alert("This person is no longer eligible for a loan for this term!")';
                    echo '</script>';


                }

            } else{ // which means the selected user is not a part timer

                //add an if statement here to see if the guy you are going to add has paid 50% of his loan, then say if he cannot have another loan unless he has paid 50%
                $query2 = "SELECT * FROM LOANS where {$idNum} = MEMBER_ID && APP_STATUS = 2  ";
                $result2 = mysqli_query($dbc, $query2);
                $row2 = mysqli_fetch_assoc($result2);

                $halfAmount = $row2['PAYABLE']/2; //the halved amount of the payment the person needs to pay with salary deduction

                if($halfAmount > $row2['AMOUNT_PAID']){

                    echo '<script language="javascript">';
                    echo 'alert("This person has a current Loan and has not paid 50% of it!")';
                    echo '</script>';

                }else {

                    $payments = $_POST['terms']*2;
                    $payable = $_POST['amount'] +500;
                    $perPayment = (($_POST['amount']+500) / $payments);

                    $query3 = "INSERT INTO loans(MEMBER_ID,AMOUNT,INTEREST,PAYMENT_TERMS,PAYABLE,PER_PAYMENT,APP_STATUS,LOAN_STATUS,DATE_APPLIED)
                                      values({$idNum},{$_POST['amount']},500,{$_POST['terms']},{$payable},{$perPayment},2,2,DATE(now()));";
                    if (!mysqli_query($dbc,$query3))
                    {
                        echo("Error description: " . mysqli_error($dbc));
                    }


                    //get the loan id of the goddamn shit
                     $loanIDQuery = "SELECT LOAN_ID from loans where MEMBER_ID = {$idNum} ORDER BY LOAN_ID DESC  LIMIT 1";
                     $loanIDresult = mysqli_query($dbc, $loanIDQuery);
                     $loanIDref = mysqli_fetch_assoc($loanIDresult);

                    //Inserts into the transaction table

                    $desc = "Loan has been Accepted!";
                    $query2 = "INSERT INTO txn_reference(MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF, EMP_ID, SERVICE_ID)
                             values ({$idNum}, 1, {$desc}, {$_POST['amount']}, DATE(now()),{$loanIDref['LOAN_ID']} ,{$_SESSION['idnum']}, 4)";
                    // SERVICE ID : 1 - Membership, 2 - Health Aid, 3 - FALP
                    // TXN_TYPE : 1 - Application 2 - Deduction
                    mysqli_query($dbc,$query2);





                    if($row['USER_STATUS'] == 2){
                        $query = "UPDATE member SET  PART_TIME_LOANED = 'Y' WHERE {$idNum} = MEMBER_ID";

                        mysqli_query($dbc, $query);
                    }



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
                                                    <td align="center"><b>ID Number</b></td>
                                                    <td align="center" width="300px"><b>Name</b></td>
                                                    <td align="center"><b>Department</b></td>
                                                    <td align="center"><b>Full-Time/Part-Time?</b></td>
                                                    <td align="center"><b>Part Time FALP Eligible?</b></td>
                                                    <td align="center"><b>Member Since</b></td>
                                                    <td align="center"><b>Action</b></td>

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

                                                        <td align="center"><?php echo $row2['MEMBER_ID'];?></td>
                                                        <td align="center" id="nameTD"><?php echo $row2['FIRSTNAME']." ".$row2['LASTNAME'];?> </td>
                                                        <td align="center"><?php echo $row2['DEPT_NAME'];?></td>
                                                        <td align="center" id="statusTD"><?php echo $row2['STATUS'];?></td>
                                                        <td align="center"><?php echo $row2['PART_TIME_LOANED'];?></td>
                                                        <td align="center"><?php echo $row2['DATE_APPROVED'];?></td>
                                                        <td align="center"><button type="button" id="btnAdd" class="btn btn-default" data-toggle="modal" data-target="#myModal" value="<?php echo $row2['MEMBER_ID']; ?>">Add</button></td>

                                                    </tr>
                                                <?php }?>


                                                </tbody>

                                            </table>

                                        </div>

                                    </div>



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
    
    <!-- jQuery -->
    <!-- Bootstrap Core JavaScript -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <form method="POST" id="addToFALPForm">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        Add <span id="modalFullName"></span> to FALP
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="memfieldlabel">Amount</label><big class="req"> *</big>
                            <input type="number" class="form-control" placeholder="Enter Amount (Peso)" name="amount" id="amount" min="5000" max="15000" maxlength="5" required>
                        </div>
                        <div class="form-group">
                            <label class="memfieldlabel">Payment Terms</label><big class="req"> *</big>
                            <input type="number" class="form-control" placeholder="Payment Terms" name="terms"  id="terms" min="1">
                        </div>
                        <div class="form-group">
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
                        <span id="err"></span>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <input class="btn btn-success" type="submit" name="submit" value="Submit"></p>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>

   
    <?php if (!empty($success)) {
        
        echo "<script type='text/javascript'>alert('Success!');</script>";
        
    }

    ?>

    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script>
        $(document).ready(function(){

            $('#table').DataTable();
            $('#btnAdd').on('click',function(){
                let name = $(this).closest("tr.td#nameTD").html();
                $("#modalFullName").html(name);
                alert(name);
            });
            $("#falpcompute").attr("disabled", "disabled");
            $('#amount').on("change", function(){
                let a = $('#amount').val();
                if((a > 4999 && a < 15001)){
                    $("#falpcompute").removeAttr("disabled");
                }else{
                    $("#falpcompute").attr("disabled", "disabled");
                }
            });
        });
    </script>
    <script>

        document.getElementById("falpcompute").onclick = function() {
            checkform();
        };

         let type = <?php echo $userStatus['STATUS']; ?>;
        
        function calculate(){
            
            let amount = parseFloat(document.getElementById("amount").value);
            let terms = parseFloat(document.getElementById("terms").value);
            let interest = 0;

            if(type == 1){
                interest = 500
            }else{
                interest = 300;
            }
            
            document.getElementById("totalI").innerHTML ="<b>Total Interest Payable: </b>₱"+ parseFloat((interest)).toFixed(2);
            document.getElementById("totalP").innerHTML ="<b>Total Amount Payable: </b> ₱"+ parseFloat((amount+interest)).toFixed(2);
            document.getElementById("PerP").innerHTML ="<b>Per Payment Period Payable: </b> ₱ "+ parseFloat(((amount+interest)/(terms*2))).toFixed(2);
            document.getElementById("Monthly").innerHTML ="<b>Monthly Payable: </b> ₱"+ parseFloat(((amount+interest)/(terms))).toFixed(2);

        }


        function checkform(){

            let elemAmount = document.getElementById("amount");
            let elemTerms = document.getElementById("terms");

            let amount = parseFloat(elemAmount.value);
            let terms = parseFloat(elemTerms.value);

            let amountLimit;
            let termMax;

            if(type == 1){
                amountLimit = 25000;
                termMax = 5;
            }else{
                amountLimit = 15000;
                termMax = 3;
            }

            elemAmount.setAttribute("max",amountLimit+"");
            elemTerms.setAttribute("max",termMax+"");


            if(amount<5000){
                alert("Amount entered is below minimum. Please enter amount within the range.");
                return false;
            }
            else if(amount > amountLimit){
                alert("Amount entered is above maximum. Please enter amount within the range.");
                return false;
            }
            else if(terms  < 0){ // if terms are below 3, deins dapat to
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
    </script>

<?php include 'GLOBAL_FOOTER.php'; ?>