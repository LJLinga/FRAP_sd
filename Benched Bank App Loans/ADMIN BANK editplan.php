<?php 
session_start();
require_once("mysql_connect_FA.php");
if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}

if(isset($_POST['action'])){
    if($_POST['action']=="Enable Loan Plan"){
        $query = "UPDATE loan_plan
                  set STATUS = 1
                  where LOAN_ID = {$_POST['details']}";
        mysqli_query($dbc,$query);
    }
    else if($_POST['action']=="Disable Loan Plan"){
        $query = "UPDATE loan_plan
                  set STATUS = 2
                  where LOAN_ID = {$_POST['details']}";
        mysqli_query($dbc,$query);
    }
}

if(isset($_POST['choice'])){
    $id = $_POST['choice'];
    $query = "SELECT LOAN_ID as 'ID',BANK_ID as 'BID', MIN_AMOUNT,MAX_AMOUNT,INTEREST,MIN_TERM,MAX_TERM,MINIMUM_SALARY,STATUS
                from loan_plan
                where bank_id = $id ";
    
}
else{
    $query = "SELECT LOAN_ID as 'ID',l.BANK_ID as 'BID', MIN_AMOUNT,MAX_AMOUNT,INTEREST,MIN_TERM,MAX_TERM,MINIMUM_SALARY,STATUS
from loan_plan l 
join (SELECT bank_id,status as 'Bank_Status' from Banks) b
on l.BANK_ID = b.bank_id
where l.bank_id != 1 AND b.Bank_Status = 1";
}
$result = mysqli_query($dbc,$query);

$page_title = 'Loans - Edit Bank Plan';
include 'GLOBAL_TEMPLATE_Header.php';
include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';
?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Enable/Disable Loan Plans
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                
                <div class="row">

                    <div class="col-lg-6">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>Select Bank</b>

                            </div>

                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-lg-9">

                                   <form action="ADMIN BANK editplan.php" method="POST">

                                        <select class="form-control" name = "choice">
                                            <?php 
                                            
                                            $query1 = "SELECT * 
                                                            from banks
                                                            where bank_id != 1 AND status != 2";
                                            $result1 = mysqli_query($dbc,$query1);
                                            while($ans = mysqli_fetch_assoc($result1)){
                                            ?>
                                            <option value = <?php echo $ans['BANK_ID'];
                                            if(isset($_POST['choice'])){
                                                if($ans['BANK_ID']==$_POST['choice']){
                                                    echo " selected";
                                                }
                                            }?>><?php echo $ans['BANK_NAME'];echo " ";echo $ans['BANK_ABBV'];?></option>
                                            <?php }; ?>
                                        </select>

                                    

                                    </div>

                                    <div class="col-lg-3">

                                        <input type="submit" class="btn btn-success" name="select_date" value="Refresh Table">
</form>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <form action="" method="POST"> <!-- SERVER SELF -->

                        <table id="table" class="table table-bordered table-striped">
                            
                            <thead>

                                <tr>

                                <td align="center"><b>Select Loan Plan</b></td>
                                <td align="center"><b>Amount to Borrow (Range)</b></td>
                                <td align="center"><b>Interest (Fixed)</b></td>
                                <td align="center"><b>Payment Terms (Range)</b></td>
                                <td align="center"><b>Minimum Monthly Salary</b></td>
                                <td align="center"><b>Status</b></td>

                                </tr>

                            </thead>

                            <tbody>

                                <?php while($ans = mysqli_fetch_assoc($result)){?>
                                <tr>
                                <td align="center">&nbsp;&nbsp;&nbsp;<input type="radio" name="details" value=<?php echo $ans['ID'];?>>&nbsp;&nbsp;&nbsp;</td>

                                <td align="center">₱ <?php echo $ans['MIN_AMOUNT'];?><input type = "text" name = "min" value = <?php echo $ans['MIN_AMOUNT'];?> hidden> - ₱ <?php echo $ans['MAX_AMOUNT'];?><input type = "text" name = "max" value = <?php echo $ans['MAX_AMOUNT'];?> hidden></td>
                                <td align="center"><?php echo $ans['INTEREST'];?>%</td>
                                <td align="center"><?php echo $ans['MIN_TERM'];?> months - <?php echo $ans['MAX_TERM'];?> months</td>
                                <td align="center">₱ <?php echo $ans['MINIMUM_SALARY'];?>
                                <td align="center"><?php if($ans['STATUS']=="1")
                                                                echo "Enabled";
                                                                else
                                                                    echo "Disabled";?>
                                </td>
                                
                                
                                </tr>
                                <?php };?>

                            </tbody>

                        </table>

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>Actions</b>

                            </div>

                            <div class="panel-body"><p>

                                <input type="submit" class="btn btn-success" name="action" value="Enable Loan Plan">
                                <input type="submit" class="btn btn-danger" name="action" value="Disable Loan Plan">

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
