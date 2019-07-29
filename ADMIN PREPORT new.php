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
   
   $_SESSION['event_start']=null;
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
            
            $yearStart = substr($dateStart,0,strpos($dateStart," "));
            $monthStart = date('m', strtotime(substr($dateStart,strpos($dateStart," ")+1)));
            if(!empty($_POST['event_end'])){
                $dateEnd = $_POST['event_end'];
                $yearEnd = substr($dateEnd,0,strpos($dateEnd," "));
                $monthEnd = date('m', strtotime($monthsubstr($dateEnd,strpos($dateEnd," ")+1)));
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
                
                    <div class="col-lg-6">

                        <h1 class="page-header">
                           New Deductions 
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->

                <div class="row">

                    <div class="col-lg-12">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>View Report for <?php if(isset($yearStart)){echo $monthStart." ".$yearStart;
                                if(isset($yearEnd)){

                                    echo " - ".$monthEnd." ".$yearEnd;

                                }}else{
                                    echo "Latest Date";
                                }?></b>

                            </div>

                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-lg-12">

                                   <form action="ADMIN PREPORT new.php" method="POST" >

                                        <div class="col-lg-12lg-4">
                            <div class="form-group">
                                <label for="event_start">Start Date</label>
                                <div class="input-group date" id="datetimepicker1">
                                    <input id="event_start" name="event_start" type="text" class="form-control">
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12lg-4">
                            <div class="form-group">
                                <label for="event_start">End Date</label>
                                <div class="input-group date" id="datetimepicker2">
                                    <input id="event_end" name="event_end" type="text" class="form-control">
                                    
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3" align="left">
                            <input onclick="$('form').attr('target', '')"  type="submit" class="btn btn-success" name="select_date" value="Generate Report">
                        </div>
                           <div class="col-lg-3" align="left">
                               <input onclick="$('form').attr('target', '_blank')"  type="submit" class="btn btn-default" name="print" value="Print Report">
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
                                        <td align="center" width="200px"><b>Deduction Amount(₱)</b></td>
                                        <td align="center" width="200px"><b>Deduction Frequency</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>
                                        <?php 
                                        while($ans = mysqli_fetch_assoc($result2)){


                                        ?>
                                        <tr>

                                        <td align="center"><?php echo $ans['ID'];?></td>
                                        <td align="left"><?php echo $ans['First']." ".$ans['Middle']." ".$ans['Last'];?></td>
                                        <td align="left"> FALP Loan</td>
                                        <td align="right"> <?php echo number_format($ans['Amount'],2)."<br>";?></td>
                                        <td align="left"> Per Payday</td>

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
                
            $('#event_start').datetimepicker( {
                locale: moment().local('ph'),
                maxDate: moment(),
                format: 'YYYY MMM'
            });

            <?php
            if(isset($_POST['event_start'])){
                echo "document.getElementById('event_start').value ='".$_POST['event_start']."'";
            }?>;
            $('#event_end').datetimepicker( {
                locale: moment().local('ph'),
                
                
                format: 'YYYY MMM'
            });
             <?php
            if(isset($_POST['event_end'])){
                echo "document.getElementById('event_end').value ='".$_POST['event_end']."'";
            }?>;

        
        });

    </script>

</body>

</html>
