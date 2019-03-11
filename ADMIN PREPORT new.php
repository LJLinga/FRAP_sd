<!DOCTYPE html>
<html lang="en">
<?php
session_start();
error_reporting(0);
require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';

$flag=0;
if(isset($_POST['print'])){
   
   
    $_SESSION['event_start']=$_POST['event_start'];
    $_SESSION['event_end'] = null;
    if(!empty($_POST['event_end']))
    $_SESSION['event_end'] = $_POST['event_end'];
   
    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/generateND.php");
}
if(!isset($_POST['event_start'])){
   
        $query="SELECT m.member_ID as 'ID', firstname as 'First',lastname as 'Last',middlename as 'Middle',l.per_payment as 'Amount'
        from member m
        join loans l
        on l.member_id = m.member_id
        join (SELECT max(date_applied) as 'Date' from loans) latest
        where  date(latest.Date) = date(l.DATE_APPLIED)
        group by m.member_ID";

}
else {
   $dateStart = $_POST['event_start'];
            
            $yearStart = substr($dateStart,0,strpos($dateStart,"-"));
            $monthStart = substr($dateStart,strpos($dateStart,"-")+1);
            if(!empty($_POST['event_end'])){
                $dateEnd = $_POST['event_end'];
                $yearEnd = substr($dateEnd,0,strpos($dateEnd,"-"));
                $monthEnd = substr($dateEnd,strpos($dateEnd,"-")+1);
            }
    if(!isset($yearEnd)){
        $query = "SELECT m.member_ID as 'ID', firstname as 'First',lastname as 'Last',middlename as 'Middle',l.per_payment as 'Amount'
        from member m
        join loans l
        on l.member_id = m.member_id
                    where $monthStart = Month(l.date_applied) AND $yearStart = Year(l.date_applied) 
                    group by m.member_ID";
    }
    else{
        $query = "SELECT m.member_ID as 'ID', firstname as 'First',lastname as 'Last',middlename as 'Middle',l.per_payment as 'Amount'
        from member m
        join loans l
        on l.member_id = m.member_id
                    where (l.date_applied between '$yearStart-$monthStart-01 00:00:00' AND '$yearEnd-$monthEnd-31 23:59:59') 
                    group by m.member_ID ";
    }

}
$result2=mysqli_query($dbc,$query);

$page_title = 'Loans - New Deductions';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';
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

                    <div class="col-lg-12">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>View Report for <?php echo date('F', mktime(0, 0, 0, $monthStart, 10)).' '.$yearStart;
                                if(isset($yearEnd)){

                                    echo ' - '.date('F', mktime(0, 0, 0, $monthEnd, 10)).' '.$yearEnd;

                                }?></b>

                            </div>

                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-lg-4">

                                   <form action="ADMIN PREPORT new.php" method="POST" >

                                        
                            <div class="form-group">
                                <label for="event_start">Start Date</label>
                                <div class="input-group date" id="datetimepicker1">
                                    <input id="event_start" name="event_start" type="text" class="form-control">
                                    <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                </div>
                            </div>
                        </div>
                        
                            <div class="form-group">
                                <label for="event_start">End Date</label>
                                <div class="input-group date" id="datetimepicker2">
                                    <input id="event_end" name="event_end" type="text" class="form-control">
                                    <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                </div>
                            </div>
                        </div>

                                    <div class="col-lg-2" align="center">
                                        <label>
                                        <input type="submit" class="btn btn-success" name="select_date" value="Generate Report"></label>
                                        <label>
                                        <input type="submit" class="btn btn-default" name="print" value="Print Report" ></label>
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
                                        while($ans = mysqli_fetch_assoc($result2)){


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
        $(function () {
                
            $('#datetimepicker1').datetimepicker( {
                locale: moment().local('ph'),
                maxDate: moment(),
                format: 'YYYY-MM'
            });
            $('#datetimepicker2').datetimepicker( {
                locale: moment().local('ph'),
                
                
                format: 'YYYY-MM'
            });

        
        });

    </script>

</body>

</html>
