<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}
require_once('mysql_connect_FA.php');

$flag=0;
if(isset($_POST['print'])){
    $_SESSION['date']=$_POST['date'];
    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/generateDOD.php");
}
if(!isset($_POST['select_date'])){
   
        $query="SELECT m.member_ID as 'ID', firstname as 'FIRST',lastname as 'LAST',middlename as 'MIDDLE',DEPT_NAME,mf.amount  as 'MFee',ha.amount as 'HAFee',f.amount as 'FFee',b.amount as 'BFee'
from member m
join ref_department d
on m.dept_id = d.dept_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_TYPE = 1 AND DATE(TXN_DATE) = DATE(latest.Date) group by member_id) mf
on m.MEMBER_ID = mf.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_TYPE = 2 AND DATE(TXN_DATE) = DATE(latest.Date) group by member_id) ha
on m.MEMBER_ID = ha.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_TYPE = 3 AND DATE(TXN_DATE) = DATE(latest.Date) group by member_id) f
on m.MEMBER_ID = f.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_TYPE = 4 AND DATE(TXN_DATE) = DATE(latest.Date) group by member_id) b
on m.MEMBER_ID = b.member_id
join txn_reference t
on t.member_id = m.member_id
join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest
where DATE(latest.Date) = date(TXN_DATE) group by m.member_ID";

}
else {
    if($_POST['date'] != "0"){
        $date = $_POST['date'];
        $day = substr($date,0,strpos($date," "));
        $month = substr($date,(strpos($date," ")+1),strpos($date,"-")-strpos($date," ")-1);
        $year = substr($date,strpos($date,"-")+1);
        $query="SELECT m.member_ID as 'ID', firstname as 'FIRST',lastname as 'LAST',middlename as 'MIDDLE',DEPT_NAME,mf.amount  as 'MFee',ha.amount as 'HAFee',f.amount as 'FFee',b.amount as 'BFee'
from member m
join ref_department d
on m.dept_id = d.dept_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_TYPE = 1 AND $month = Month(txn_date) AND $year = Year(txn_date) AND $day = DAY(txn_date) group by member_id) mf
on m.MEMBER_ID = mf.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_TYPE = 2 AND $month = Month(txn_date) AND $year = Year(txn_date) AND $day = DAY(txn_date) group by member_id) ha
on m.MEMBER_ID = ha.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_TYPE = 3 AND $month = Month(txn_date) AND $year = Year(txn_date) AND $day = DAY(txn_date) group by member_id) f
on m.MEMBER_ID = f.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_TYPE = 4 AND $month = Month(txn_date) AND $year = Year(txn_date) AND $day = DAY(txn_date) group by member_id) b
on m.MEMBER_ID = b.member_id
join txn_reference t
        on t.MEMBER_ID = m.MEMBER_ID
        where TXN_TYPE =2 and $month = Month(txn_date) AND $year = Year(txn_date) AND $day = DAY(txn_date)
group by m.member_ID";
    }
    else{
        $query="SELECT m.member_ID as 'ID', firstname as 'FIRST',lastname as 'LAST',middlename as 'MIDDLE',DEPT_NAME,mf.amount  as 'MFee',ha.amount as 'HAFee',f.amount as 'FFee',b.amount as 'BFee'
from member m
join ref_department d
on m.dept_id = d.dept_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_TYPE = 1 AND DATE(TXN_DATE) = DATE(latest.Date) group by member_id) mf
on m.MEMBER_ID = mf.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_TYPE = 2 AND DATE(TXN_DATE) = DATE(latest.Date) group by member_id) ha
on m.MEMBER_ID = ha.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_TYPE = 3 AND DATE(TXN_DATE) = DATE(latest.Date) group by member_id) f
on m.MEMBER_ID = f.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_TYPE = 4 AND DATE(TXN_DATE) = DATE(latest.Date) group by member_id) b
on m.MEMBER_ID = b.member_id
join txn_reference t
on t.member_id = m.member_id
join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest
where DATE(latest.Date) = date(TXN_DATE) group by m.member_ID";
    }
}
$result=mysqli_query($dbc,$query);

    $page_title = 'Loans - Detailed Report';
    include 'GLOBAL_TEMPLATE_Header.php';
    include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';

?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Detailed Deductions Report
                            
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">

                    <div class="col-lg-6">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>View Report for (Month, Day, Year)</b>

                            </div>

                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-lg-6">

                                    <form action="ADMIN DREPORT detailed.php" method="POST">

                                        <select class="form-control" name = "date">
                                        
                                            <option value = "0">This Current Date</option>  
                                        <?php
                                        $query="SELECT DISTINCT MONTH(txn_date) as 'Month',YEAR(txn_date) as 'Year', DAY(txn_date) as 'Day' from txn_reference
                                            where txn_type = 2";
                                        $result1 = mysqli_query($dbc,$query);

                                        while($ans = mysqli_fetch_assoc($result1)){?>
                                            <option value = "<?php echo $ans['Day']." ".$ans['Month']."-".$ans['Year'];
                                                                
                                                                ?>" <?php if(isset($_POST['date'])){
                                                                    if($_POST['date']== $ans['Day']." ".$ans['Month']."-".$ans['Year']){
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



                                                echo $ans['Day']." ".$month." ".$ans['Year']?></option>
                                        <?php }?>

                                        </select>

                                    

                                    </div>

                                    <div class="col-lg-3" align="left">

                                        <input type="submit" class="btn btn-success" name="select_date" value="Generate Report">

                                    </div>

                                    <div class="col-lg-3" align="left">

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

                                        <td align="center">ID Number</td>
                                        <td align="center">Name</td>
                                        <td align="center">Membership Fee</td>
                                        <td align="center">Health Aid Fee</td>
                                        <td align="center">FALP Loan</td>
                                        <td align="center">Bank Loan</td>
                                        <td align="center" width="110px">Total</td>
                                        
                                        </tr>
                                    </thead>

                                    <tbody>

                                     

                                        <?php 
                                        while($ans = mysqli_fetch_assoc($result)){
                                            $total  =(float)$ans['MFee']+(float)$ans['HAFee']+(float)$ans['FFee']+(float)$ans['BFee'];

                                        ?>
                                        <tr>

                                        <td align="center"><b><?php echo $ans['ID'];?></b></td>
                                        <td align="center" width="250px"><b><?php echo $ans['FIRST']." ".$ans['MIDDLE']." ".$ans['LAST'];?></b></td>
                                        <td align="center"><b><?php echo sprintf("%.2f",(float)$ans['MFee']);?></b></td>
                                        <td align="center"><b><?php echo sprintf("%.2f",(float)$ans['HAFee']);?></b></td>
                                        <td align="center"><b><?php echo sprintf("%.2f",(float)$ans['FFee']);?></b></td>
                                        <td align="center"><b><?php echo sprintf("%.2f",(float)$ans['BFee']);?></b></td>
                                        <td align="center"><b><?php echo sprintf("%.2f",(float)$total);?></b></td>

                                        </tr>
                                        <?php } ?>

                                       

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
