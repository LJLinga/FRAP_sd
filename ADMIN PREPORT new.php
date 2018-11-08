<!DOCTYPE html>
<html lang="en">
<?php
session_start();
 if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
            
    }
require_once('mysql_connect_FA.php');
$flag=0;
if(isset($_POST['print'])){
    $_SESSION['date']=$_POST['date'];
     $_SESSION['daystart'] = $_POST['daystart'];
     $_SESSION['dayend'] = $_POST['dayend'];
    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/generateND.php");
}
if(!isset($_POST['select_date'])){
   
        $query="SELECT m.member_ID as 'ID', firstname as 'First',lastname as 'Last',middlename as 'Middle',l.per_payment as 'Amount'
        from member m
        join loans l
        on l.member_id = m.member_id
        join (SELECT max(date_applied) as 'Date' from loans where loan_detail_id = 1) latest
        where loan_detail_id =1 and date(latest.Date) = date(l.DATE_APPLIED)
        group by m.member_ID";

}
else {
    if($_POST['date'] != "0"){
        $date = $_POST['date'];
        $daystart = $_POST['daystart'];
        $dayend = $_POST['dayend'];
        $month = substr($date,0,strpos($date,"-"));
        $year = substr($date,strpos($date,"-")+1);
        $query="SELECT m.member_ID as 'ID', firstname as 'First',lastname as 'Last',middlename as 'Middle',l.per_payment as 'Amount'
        from member m  
        join loans l
        on l.member_id = m.member_id
        where loan_detail_id =1 and $month = Month(l.date_applied) AND $year = Year(l.date_applied) AND DAY(l.date_applied) between {$daystart} and {$dayend}
        group by m.member_ID";
    }
    else{
        $query="SELECT m.member_ID as 'ID', firstname as 'First',lastname as 'Last',middlename as 'Middle',l.per_payment as 'Amount'
        from member m
        
        
        join loans l
        on l.member_id = m.member_id
        join (SELECT max(date_applied) as 'Date' from loans where loan_detail_id = 1) latest
        where loan_detail_id =1 and date(latest.Date) = date(l.DATE_APPLIED)
        group by m.member_ID";
    }
}
$result=mysqli_query($dbc,$query);

$page_title = 'Loans - New Deductions';
include 'GLOBAL_HEADER.php';
include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';
?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                           New Deductions
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->

                <div class="row">

                    <div class="col-lg-1">

                    </div>

                    <div class="col-lg-10">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>View Report for (Month & Year) and Day Range</b>

                            </div>

                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-lg-3">

                                   <form action="ADMIN PREPORT new.php" method="POST">

                                        <select class="form-control" name = "date">
                                            <option value = "0">This Current Date</option>  
                                        <?php
                                        $query="SELECT DISTINCT MONTH(txn_date) as 'Month',YEAR(txn_date) as 'Year' from txn_reference
                                            where txn_type = 2
                                            order by txn_date desc";
                                        $result1 = mysqli_query($dbc,$query);

                                        while($ans = mysqli_fetch_assoc($result1)){?>
                                            <option value = "<?php echo $ans['Month']."-".$ans['Year'];
                                                                
                                                                ?>" <?php if(isset($_POST['date'])){
                                                                    if($_POST['date']== $ans['Month']."-".$ans['Year']){
                                                                        echo " selected";
                                                                    }
                                                                }?> >
                                                <?php 
                                                $month = "January";
                                                if($ans['Month']=="1"){
                                                    $month = "January";
                                                }
                                                else if($ans['Month']=="2"){
                                                    $month = "February";
                                                }
                                                else if($ans['Month']=="3"){
                                                    $month = "March";
                                                }
                                                else if($ans['Month']=="4"){
                                                    $month = "April";
                                                }
                                                else if($ans['Month']=="5"){
                                                    $month = "May";
                                                }
                                                else if($ans['Month']=="6"){
                                                    $month = "June";
                                                }
                                                else if($ans['Month']=="7"){
                                                    $month = "July";
                                                }
                                                else if($ans['Month']=="8"){
                                                    $month = "August";
                                                }
                                                else if($ans['Month']=="9"){
                                                    $month = "September";
                                                }
                                                else if($ans['Month']=="10"){
                                                    $month = "October";
                                                }
                                                else if($ans['Month']=="11"){
                                                    $month = "November";
                                                }
                                                else if($ans['Month']=="12"){
                                                    $month = "December";
                                                }



                                                echo $month." ".$ans['Year']?></option>
                                        <?php }?>
                                        </select>

                                    </div>

                                    <div class="col-lg-2" align="center">

                                        <input type="number" class="form-control" name="daystart" placeholder="Start Day" value=<?php if(isset($_POST['daystart']))
                                                         echo ($_POST['daystart']);
                                                         else
                                                            echo '""';?>>

                                    </div>

                                    <div class="col-lg-2" align="center">

                                        <input type="number" class="form-control" name="dayend" placeholder="End Day" value=<?php if(isset($_POST['dayend']))
                                                         echo ($_POST['dayend']);
                                                         else
                                                            echo '""';?>>

                                    </div>

                                    <div class="col-lg-2" align="center">

                                        <input type="submit" class="btn btn-success" name="select_date" value="Generate Report">

                                    </div>

                                    <div class="col-lg-2" align="center">

                                        <input type="submit" class="btn btn-default" name="print" value="Print Report">
                                         </form>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                       <div class="row">

                            <div class="col-lg-12">

                                <form action="ADMIN BANK appdetails.php" method="POST"> <!-- SERVER SELF -->

                                <table id="table" class="table table-bordered table-striped">
                                    
                                    <thead>

                                        <tr>

                                        <td align="center" width="200px"><b>ID Number</b></td>
                                        <td align="center" width="250px"><b>Name</b></td>
                                        <td align="center" width="200px"><b>Loan Type</b></td>
                                        <td align="center" width="200px"><b>Deduction Amount</b></td>
                                        <td align="center" width="200px"><b>Deduction Frequency</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>
                                        <?php 
                                        while($ans = mysqli_fetch_assoc($result)){


                                        ?>
                                        <tr>

                                        <td align="center"><?php echo $ans['ID'];?></td>
                                        <td align="center"><?php echo $ans['First']." ".$ans['Middle']." ".$ans['Last'];?></td>
                                        <td align="center"> FALP Loan</td>
                                        <td align="center"><?php echo sprintf("%.2f",(float)$ans['Amount']);?></td>
                                        <td align="center"> Per Payday</td>

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

    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script>

        $(document).ready(function(){
    
            $('#table').DataTable();

        });

    </script>

</body>

</html>
