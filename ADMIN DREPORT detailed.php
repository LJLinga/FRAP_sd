<!DOCTYPE html>
<html lang="en">
<?php
require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';



$flag=0;
if(isset($_POST['print'])){
    $_SESSION['event_start'] = null;
    $_SESSION['event_start']=$_POST['event_start'];
    $_SESSION['event_end'] = null;
    if(!empty($_POST['event_end']))
    $_SESSION['event_end'] = $_POST['event_end'];
    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/generateDOD.php");
}
if(!isset($_POST['event_start'])){
   
        $query2="SELECT m.member_ID as 'ID', firstname as 'FIRST',lastname as 'LAST',middlename as 'MIDDLE',DEPT_NAME,mf.amount  as 'MFee',ha.amount as 'HAFee',f.amount as 'FFee'
from member m
join ref_department d
on m.dept_id = d.dept_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_ID = 1 AND DATE(TXN_DATE) = DATE(latest.Date) AND txn_type = 2 group by member_id) mf
on m.MEMBER_ID = mf.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_ID = 2 AND DATE(TXN_DATE) = DATE(latest.Date) AND txn_type = 2 group by member_id) ha
on m.MEMBER_ID = ha.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_ID = 4 AND DATE(TXN_DATE) = DATE(latest.Date) AND txn_type = 2 group by member_id) f
on m.MEMBER_ID = f.member_id
join txn_reference t
on t.member_id = m.member_id
join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest
where DATE(latest.Date) = date(TXN_DATE) AND txn_type = 2 group by m.member_ID";

}
else {
         $date = $_POST['event_start'];
        $dayStart = substr($date,0,strpos($date,"-"));
        $monthStart = substr($date,(strpos($date,"-")+1),strpos($date,"- ")-3);
        $yearStart = substr($date,strpos($date,"- ")+1);
            if(!empty($_POST['event_end'])){
                $date = $_POST['event_end'];

                $dayEnd = substr($date,0,strpos($date,"-"));
                $monthEnd = substr($date,(strpos($date,"-")+1),strpos($date,"- ")-3);
                $yearEnd = substr($date,strpos($date,"- ")+1);
            }
        if(!isset($yearEnd)){
        $query2 = "SELECT m.member_ID as 'ID', firstname as 'FIRST',lastname as 'LAST',middlename as 'MIDDLE',DEPT_NAME,mf.amount  as 'MFee',ha.amount as 'HAFee',f.amount as 'FFee'
from member m
join ref_department d
on m.dept_id = d.dept_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference where SERVICE_ID = 1 AND $monthStart = Month(txn_date) AND $yearStart = Year(txn_date) AND $dayStart = DAY(txn_date) AND txn_type = 2 group by member_id) mf
on m.MEMBER_ID = mf.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference where SERVICE_ID = 2 AND $monthStart = Month(txn_date) AND $yearStart = Year(txn_date) AND $dayStart = DAY(txn_date) AND txn_type = 2 group by member_id) ha
on m.MEMBER_ID = ha.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference where SERVICE_ID = 4 AND $monthStart = Month(txn_date) AND $yearStart = Year(txn_date) AND $dayStart = DAY(txn_date) AND txn_type = 2 group by member_id) f
on m.MEMBER_ID = f.member_id

join txn_reference t
        on t.MEMBER_ID = m.MEMBER_ID
        where TXN_TYPE =2 and $monthStart = Month(txn_date) AND $yearStart = Year(txn_date) AND $dayStart = DAY(txn_date)
group by m.member_ID";
        }
        else{
             $query2 = "SELECT m.member_ID as 'ID', firstname as 'FIRST',lastname as 'LAST',middlename as 'MIDDLE',DEPT_NAME,mf.amount  as 'MFee',ha.amount as 'HAFee',f.amount as 'FFee'
from member m
join ref_department d
on m.dept_id = d.dept_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference where SERVICE_ID = 1 AND (txn_date between '$yearStart-$monthStart-$dayStart 00:00:00' AND '$yearEnd-$monthEnd-$dayEnd 23:59:59') AND TXN_TYPE =2 group by member_id) mf
on m.MEMBER_ID = mf.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference where SERVICE_ID = 2 AND (txn_date between '$yearStart-$monthStart-$dayStart 00:00:00' AND '$yearEnd-$monthEnd-$dayEnd 23:59:59') AND TXN_TYPE =2 group by member_id) ha
on m.MEMBER_ID = ha.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference where SERVICE_ID = 4 AND(txn_date between '$yearStart-$monthStart-$dayStart 00:00:00' AND '$yearEnd-$monthEnd-$dayEnd 23:59:59') AND TXN_TYPE =2 group by member_id)  f
on m.MEMBER_ID = f.member_id

join txn_reference t
        on t.MEMBER_ID = m.MEMBER_ID
        where TXN_TYPE =2 and (txn_date between '$yearStart-$monthStart-$dayStart 00:00:00' AND '$yearEnd-$monthEnd-$dayEnd 23:59:59')
group by m.member_ID";
        }
    
}
$result2=mysqli_query($dbc,$query2);

    $page_title = 'Loans - Detailed Report';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_ADMIN_SIDEBAR.php';

?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Detailed Deductions Report for <?php if(isset($yearStart)){echo date('d F', mktime(0, 0, 0, $monthStart, $dayStart)).' '.$yearStart;
                                if(isset($yearEnd)){

                                    echo ' - '.date('d F', mktime(0, 0, 0, $monthEnd, $dayEnd)).' '.$yearEnd;

                                }
                            } else{
                                    $latestQuery = "SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2";
                                    $resultLatest = mysqli_query($dbc,$latestQuery);

                                    if(!empty($resultLatest)){
                                    $latest = mysqli_fetch_assoc($resultLatest);
                                    $date = $latest['Date'];
                                    
                                    echo date('d F Y', strtotime($date));
                                }else echo "Latest";}?>
                            
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">

                    <div class="col-lg-12">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>View Report </b>

                            </div>

                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-lg-12">

                                    <form action="ADMIN DREPORT detailed.php" method="POST" autocomplete="off">

                                         <div class="col-lg-12lg-4">
                            <div class="form-group">
                                <label for="event_start">Start Date</label>
                                <div class="input-group date" id="datetimepicker1">
                                    <input id="event_start" name="event_start" type="text" class="form-control"
                                    >
                                  
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12lg-4">
                            <div class="form-group">
                                <label for="event_end">End Date</label>
                                <div class="input-group date" id="datetimepicker2">
                                    <input id="event_end" name="event_end" type="text" class="form-control" onClick = "myFunction()"  >
                                    
                                </div>
                            </div>
                        </div>
                                    <div class="col-lg-3" align="left">

                                        <input onclick="$('form').attr('target', '')" type="submit" class="btn btn-success" name="select_date" value="Generate Report">

                                    </div>

                                    <div class="col-lg-3" align="left">

                                        <input onclick="$('form').attr('target', '_blank')" type="submit" class="btn btn-default" name="print" value="Print Report">
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
                                        <td align="left">Name</td>
                                        <td align="right">Membership Fee(₱)</td>
                                        <td align="right">Health Aid Fee(₱)</td>
                                        <td align="right">FALP Loan(₱)</td>
                                       
                                        <td align="right" width="110px">Total(₱)</td>
                                        
                                        </tr>
                                    </thead>

                                    <tbody>

                                     

                                        <?php
                                        $totals = 0.00;
                                      

                                            
                                        while($ans = mysqli_fetch_assoc($result2)){
                                            $total  =(float)$ans['MFee']+(float)$ans['HAFee']+(float)$ans['FFee'];
                                            $totals+=$total; 
                                        ?>
                                        <tr>

                                        <td align="center"><?php echo $ans['ID'];?></td>
                                        <td align="left" width="250px"><?php echo $ans['FIRST']." ".$ans['MIDDLE']." ".$ans['LAST'];?></td>
                                        <td align="right"> <?php echo number_format($ans['MFee'],2)."<br>";?></td>
                                        <td align="right"> <?php echo number_format($ans['HAFee'],2)."<br>";?></td>
                                        <td align="right"> <?php echo number_format($ans['FFee'],2)."<br>";?></td>
                                        <td align="right"> <?php echo number_format($total,2)."<br>";?></td>

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
    <script>
function myFunction() {
    $('#event_start').datepicker('destroy');
     $('#event_start').attr('value', '');
  $('#event_start').datetimepicker( {
                locale: moment().local('ph'),
                maxDate: $("#event_end").data("datetimepicker").getDate();,
                
                format: 'DD-MM- YYYY'

            });
}</script>
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script>

        $(document).ready(function(){
    
            $('#table').DataTable({
        "dom": '<lf<t><<"#total">ip>>'
    });
             document.getElementById("total").align = "right";
             document.getElementById("total").style = "position:relative;right:25px;font-size: 20px;";
            document.getElementById("total").innerHTML="Total amount to be collected: ₱"+<?php echo '"'.number_format((float)$totals,2).'"';?>;
        });
        $(function () {
                
             $('#event_start').datetimepicker( {
                locale: moment().local('ph'),
                maxDate: moment(),
              
                format: 'DD-MM- YYYY'

            });
            <?php
            if(isset($_POST['event_start'])){
                echo "document.getElementById('event_start').value = '".$_POST['event_start']."'";
            }?>;
            $('#event_end').datetimepicker( {
                locale: moment().local('ph'),
                
                
                format: 'DD-MM- YYYY'
            });
            <?php
            if(isset($_POST['event_end'])){
                echo "document.getElementById('event_end').value = '".$_POST['event_end']."'";
            }?>;
        
        });

    </script>

</body>

</html>
