<!DOCTYPE html>
<html lang="en">
<?php
require_once ("mysql_connect_FA.php");
session_start();
error_reporting(0);
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';


$flag=0;
if(isset($_POST['print'])){
    $_SESSION['event_start']=null;
    $_SESSION['event_start']=$_POST['event_start'];
    $_SESSION['event_end'] = null;
    if(!empty($_POST['event_end']))
    $_SESSION['event_end'] = $_POST['event_end'];
    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/generateCD.php");
}

    
if(!isset($_POST['event_start'])){
   
        $query="SELECT m.member_id as 'ID',m.firstName as 'First',m.middlename as 'Middle', m.lastname as 'Last',l.LOAN_ID
from loans l  

join member m
on l.member_id = m.member_id
join (SELECT max(date_applied) as 'Date' from loans) latest
        where  l.LOAN_STATUS = 3 AND date(latest.Date) = date(l.DATE_APPLIED)
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
        $query = "SELECT m.member_id as 'ID',m.firstName as 'First',m.middlename as 'Middle', m.lastname as 'Last',l.LOAN_ID
from loans l  

join member m
on l.member_id = m.member_id
                    where  l.LOAN_STATUS = 3 AND $monthStart = Month(l.date_applied) AND $yearStart = Year(l.date_applied) 
                    group by l.loan_id";
    }
    else{
        $query = "SELECT m.member_id as 'ID',m.firstName as 'First',m.middlename as 'Middle', m.lastname as 'Last',l.LOAN_ID
from loans l  

join member m
on l.member_id = m.member_id
                    where l.LOAN_STATUS = 3 AND (l.date_applied between '$yearStart-$monthStart-01 00:00:00' AND '$yearEnd-$monthEnd-31 23:59:59') 
                    group by l.loan_id ";
    }

}
$result2 = mysqli_query($dbc,$query);

$page_title = 'Loans - Completed Deductions';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';

?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Completed Deductions for <?php if(isset($yearStart)){echo date('F', mktime(0, 0, 0, $monthStart, 10)).' '.$yearStart;
                                if(isset($yearEnd)){

                                    echo ' - '.date('F', mktime(0, 0, 0, $monthEnd, 10)).' '.$yearEnd;

                                }}
                                else{
                                    echo "Latest date";
                                }?>

                            
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->

                <div class="row">

                    <div class="col-lg-6">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>View Report for (Year & Month)</b>

                            </div>

                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-lg-6">

                                       <form action="ADMIN PREPORT completed.php" method="POST">

                                        <div class="col-lg-12lg-4">
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
<div class="col-lg-12lg-4">
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
                                    

                                    

                                    <div class="col-lg-3" align="left">

                                        <input type="submit" class="btn btn-success" name="select_date" value="Generate Report">
                                        <input type="submit" class="btn btn-default" name="print" value="Print Report">
                                    </div>

                                    

                                        
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

                                        <td align="center" width="250px"><b>ID Number</b></td>
                                        <td align="center"><b>Name</b></td>
                                        <td align="center"><b>Loan Completed</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>

                                     <?php 
                                        while($ans = mysqli_fetch_assoc($result2)){


                                        ?>
                                        <tr>

                                        <td align="center"><?php echo $ans['ID'];?></td>
                                        <td align="center"><?php echo $ans['First']." ".$ans['Middle']." ".$ans['Last'];?></td>
                                        <td align="center">FALP Loan</td>
                                        

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
