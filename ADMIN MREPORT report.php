<!DOCTYPE html>
<html lang="en">
<?php
error_reporting(0); 
session_start();
 if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
            
    }
require_once('mysql_connect_FA.php');
if(!isset($_POST['select_date'])){
   $query = "SELECT max(day(txn_date)) as 'Day',max(month(txn_date)) as 'Month',max(Year(txn_date)) as 'Year' from txn_reference where txn_type = 2";
   $result = mysqli_query($dbc,$query);
   $row = mysqli_fetch_assoc($result);
   $date = $row['Month']."-".$row['Year'];
   
   $month =$row['Month'];
   $year =$row['Year'];
}
else {
    if($_POST['date'] != "0"){
        $date = $_POST['date'];

        $month = substr($date,0,strpos($date,"-"));
        $year = substr($date,strpos($date,"-")+1);
        }
    
    else{
        $query = "SELECT max(day(txn_date)) as 'Day',max(month(txn_date)) as 'Month',max(Year(txn_date)) as 'Year' from txn_reference where txn_type = 2";
        $result = mysqli_query($dbc,$query);
        $row = mysqli_fetch_assoc($result);
        $date = $row['Month']."-".$row['Year'];
        
           $month =$row['Month'];
           $year =$row['Year'];
    }
    $page_title = 'Loans - Collected Membership Fees';
    include 'GLOBAL_HEADER.php';
    include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';
}
?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Collected Membership Fees for December 2017
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->

                <div class="row">

                    <div class="col-lg-4">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>View Report for (Month & Year)</b>

                            </div>

                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-lg-7">

                                    <form action="ADMIN MREPORT report.php" method="POST">
<!--     Date                                                                                                   -->
                                        <div class='input-group date' id='datetimepicker1'>
                                            <input type='text' class="form-control" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
<!-- Date End                                                                                                   -->
                                        <select class="form-control" name = "date">
                                        
                                            <option value = "0">This Current Date</option>  
                                        <?php
                                        $query="SELECT DISTINCT MONTH(txn_date) as 'Month',YEAR(txn_date) as 'Year' from txn_reference
                                            where txn_type = 2 AND service_type != 4";
                                        $result1 = mysqli_query($dbc,$query);

                                        while($ans = mysqli_fetch_assoc($result1)){?>
                                            <option value = "<?php echo $ans['Day']." ".$ans['Month']."-".$ans['Year'];
                                                                
                                                                ?>" <?php if(isset($_POST['date'])){
                                                                    if($_POST['date']== $ans['Month']."-".$ans['Year']){
                                                                        echo " selected";
                                                                    }
                                                                }?> >
                                                <?php 
                                                $month1 = "January";
                                                if($ans['Month']=="1"){
                                                    $month1 = "January";
                                                }
                                                else if($ans['Month']=="2"){
                                                    $month1 = "February";
                                                }
                                                else if($ans['Month']=="3"){
                                                    $month1 = "March";
                                                }
                                                else if($ans['Month']=="4"){
                                                    $month1 = "April";
                                                }
                                                else if($ans['Month']=="5"){
                                                    $month1 = "May";
                                                }
                                                else if($ans['Month']=="6"){
                                                    $month1 = "June";
                                                }
                                                else if($ans['Month']=="7"){
                                                    $month1 = "July";
                                                }
                                                else if($ans['Month']=="8"){
                                                    $month1 = "August";
                                                }
                                                else if($ans['Month']=="9"){
                                                    $month1 = "September";
                                                }
                                                else if($ans['Month']=="10"){
                                                    $month1 = "October";
                                                }
                                                else if($ans['Month']=="11"){
                                                    $month1 = "November";
                                                }
                                                else if($ans['Month']=="12"){
                                                    $month1 = "December";
                                                }



                                                echo $month1." ".$ans['Year']?></option>
                                        <?php }?>
                                        </select>

                                    

                                    </div>

                                    <div class="col-lg-5" align="left">

                                        <input type="submit" class="btn btn-success" name="select_date" value="Generate Report">
                                        </form>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="panel panel-primary">

                            <div class="panel-heading">

                                <b>Membership Fees Collected</b>

                            </div>

                            <div class="panel-body"><p>
                                <?php
                                    $query1 = "SELECT s.SERVICE as 'Type',sum(amount)  as 'Amount',count(amount) as 'Count'
                                                from service_type s
                                                left join txn_reference t
                                                on t.SERVICE_TYPE = s.SERVICE_ID
                                                join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest
                                                where $month = Month(txn_date) AND $year = Year(txn_date)  AND t.TXN_TYPE = 2 AND s.SERVICE_ID = 1 
                                                group by t.SERVICE_TYPE ";
                                    $result1 = mysqli_query($dbc,$query1);
                                    $row1 = mysqli_fetch_assoc($result1);

                                ?>
                                <b>Total Number of Fees Collected: <?php if(!empty($row1)){
                                    echo $row1['Count'];

                                }
                                else echo "0"?></b> <p>
                                <b>Total Amount Collected:₱ <?php if(!empty($row1)){
                                    echo $row1['Amount'];

                                }
                                else echo "0.00";?></b> <p>
                                
                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="panel panel-primary">

                            <div class="panel-heading">

                                <b>Health Aid Fees Collected</b>

                            </div>

                            <div class="panel-body"><p>
                                <?php
                                    $query1 = "SELECT s.SERVICE as 'Type',sum(amount)  as 'Amount',count(amount) as 'Count'
                                                from service_type s
                                                left join txn_reference t
                                                on t.SERVICE_TYPE = s.SERVICE_ID
                                                join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest
                                                where $month = Month(txn_date) AND $year = Year(txn_date)  AND t.TXN_TYPE = 2 AND s.SERVICE_ID = 2 
                                                group by t.SERVICE_TYPE ";
                                    $result1 = mysqli_query($dbc,$query1);
                                    $row1 = mysqli_fetch_assoc($result1);

                                ?>
                                <b>Total Number of Fees Collected: <?php if(!empty($row1)){
                                    echo $row1['Count'];

                                }
                                else echo "0"?></b> <p>
                                <b>Total Amount Collected:₱ <?php if(!empty($row1)){
                                    echo $row1['Amount'];

                                }
                                else echo "0.00";?></b> <p>
                                            
                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="panel panel-primary">

                            <div class="panel-heading">

                                <b>FALP Revenue Collected</b>

                            </div>

                            <div class="panel-body"><p>

                                 <?php
                                    $query1 = "SELECT s.SERVICE as 'Type',sum(amount)  as 'Amount',count(amount) as 'Count'
                                                from service_type s
                                                left join txn_reference t
                                                on t.SERVICE_TYPE = s.SERVICE_ID
                                                join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest
                                                where $month = Month(txn_date) AND $year = Year(txn_date)  AND t.TXN_TYPE = 2 AND s.SERVICE_ID = 3 
                                                group by t.SERVICE_TYPE ";
                                    $result1 = mysqli_query($dbc,$query1);
                                    $row1 = mysqli_fetch_assoc($result1);

                                ?>
                                <b>Total Number of Fees Collected: <?php if(!empty($row1)){
                                    echo $row1['Count'];

                                }
                                else echo "0"?></b> <p>
                                <b>Total Amount Collected:₱ <?php if(!empty($row1)){
                                    echo $row1['Amount'];

                                }
                                else echo "0.00";?></b> <p>
                                            
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
    <script type="text/javascript">

        $(document).ready(function(){
            $('#table').DataTable();
        });
        //Date Picker script
        $(function () {
                $('#datetimepicker1').datetimepicker();
        });

    </script>

</body>

</html>
