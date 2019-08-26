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
   
        $query="SELECT m.member_ID as 'ID', m.firstname as 'First',m.lastname as 'Last',m.middlename as 'Middle',t.txn_desc as 'Description',t.txn_type as 'Type',t.loan_ref as 'Ref',m.emp_type as 'Employee Type',l.per_payment as 'Per Deduction'
        from member m
        join txn_reference t
        on t.member_id = m.member_id
        left join loans l
        on l.loan_id = t.loan_ref
        join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type =1) latest
        where  (date(latest.Date) = date(t.txn_date )) && (t.txn_desc = 'Membership Application Approved'||(t.txn_desc ='Loan has been Picked up! Deductions will start now.' && t.loan_ref IS NOT NULL)||t.txn_type = 3)
        order by m.member_id,t.loan_ref;
        

        ";


}
else {
   $dateStart = $_POST['event_start'];
            
            $yearStart = substr($dateStart,0,strpos($dateStart," "));
            $monthStart = date('m', strtotime(substr($dateStart,strpos($dateStart," ")+1)));
            if(!empty($_POST['event_end'])){
                $dateEnd = $_POST['event_end'];
                $yearEnd = substr($dateEnd,0,strpos($dateEnd," "));
                $monthEnd = date('m', strtotime(substr($dateEnd,strpos($dateEnd," ")+1)));
            }
    if(!isset($yearEnd)){
        $query = "SELECT m.member_ID as 'ID', m.firstname as 'First',m.lastname as 'Last',m.middlename as 'Middle',t.txn_desc as 'Description',t.txn_type as 'Type',t.loan_ref as 'Ref',m.emp_type as 'Employee Type', l.per_payment as 'Per Deduction'
        from member m
        join txn_reference t
        on t.member_id = m.member_id
        left join loans l
        on l.loan_id = t.loan_ref
                    where $monthStart = Month(t.txn_date) AND $yearStart = Year(t.txn_date) && (t.txn_desc = 'Membership Application Approved'||(t.txn_desc ='Loan has been Picked up! Deductions will start now.' && t.loan_ref IS NOT NULL)||t.txn_type = '3')
                    order by m.member_id;
                    ";
    }
    else{
        $query = "SELECT m.member_ID as 'ID', m.firstname as 'First',m.lastname as 'Last',m.middlename as 'Middle',t.txn_desc as 'Description',t.txn_type as 'Type',t.loan_ref as 'Ref',m.emp_type as 'Employee Type',l.PER_PAYMENT as 'Per Deduction'
        from member m
        join txn_reference t
        on t.member_id = m.member_id
        left join loans l
        on l.loan_id = t.loan_ref
                    where t.txn_date between '$yearStart-$monthStart-01 00:00:00' AND '$yearEnd-$monthEnd-31 23:59:59' && (t.txn_desc = 'Membership Application Approved'||(t.txn_desc ='Loan has been Picked up! Deductions will start now.' && t.loan_ref IS NOT NULL)||t.txn_type = '3')
        order by m.member_id;
                    ";
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
                           New Deductions for <?php if(isset($yearStart)){echo date('F', strtotime($dateStart))." ".$yearStart;
                                if(isset($yearEnd)){

                                    echo " - ".date('F', strtotime($dateEnd))." ".$yearEnd;

                                }}else{
                                    $latestQuery = "SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2";
                                    $resultLatest = mysqli_query($dbc,$latestQuery);

                                    if(!empty($resultLatest)){
                                    $latest = mysqli_fetch_assoc($resultLatest);
                                    $date = $latest['Date'];
                                    
                                    echo date('F Y', strtotime($date));
                                }else echo "Latest";
                                }?>
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->

                <div class="row">

                    <div class="col-lg-12">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>View Report for: </b>

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
                                        <td align="center" width="200px"><b>Deduction Type</b></td>
                                        <td align="center" width="200px"><b>Deduction Amount(₱)</b></td>
                                        <td align="center" width="200px"><b>Deduction Frequency</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>
                                        <?php 
                                        $repeat = 0;
                                        $old = 'qweqwe';
                                        while($ans = mysqli_fetch_assoc($result2)){
                                       
                                            if($ans['Description']=='Membership Application Approved'){

                                                ?>
                                        <tr>

                                        <td align="center"><?php echo $ans['ID'];?></td>
                                        <td align="left"><?php echo $ans['First']." ".$ans['Middle']." ".$ans['Last'];?></td>
                                        <td align="left"> Membership</td>
                                        <td align="right"><?php if($ans['Employee Type']==1) echo '183.00 </td>
                                        <td align="left"> Per Year</td>'; else echo '91.67 </td>
                                        <td align="left"> Per Term</td>'; ?>    

                                        </tr>
                                            <?php }

                                        if($ans['Description']=='Loan has been Picked up! Deductions will start now.'){
                                        ?>
                                        <tr>

                                        <td align="center"><?php echo $ans['ID'];?></td>
                                        <td align="left"><?php echo $ans['First']." ".$ans['Middle']." ".$ans['Last'];?></td>
                                        <td align="left"> FALP Loan</td>
                                        <td align="right"> <?php echo number_format($ans['Per Deduction'],2)."<br>";?></td>
                                        <td align="left"> Per Payday</td>

                                        </tr>
                                            <?php }
                                            
                                        if($ans['Type']=='3'){?>
                                                 <tr>

                                        <td align="center"><?php echo $ans['ID'];?></td>
                                        <td align="left"><?php echo $ans['First']." ".$ans['Middle']." ".$ans['Last'];?></td>
                                        <td align="left"> Health Aid</td>
                                        <td align="right"> 100.00 </td>
                                        <td align="left"> Per Term</td>

                                        </tr>
                                        <?php }
                                    }
                                ?>

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
