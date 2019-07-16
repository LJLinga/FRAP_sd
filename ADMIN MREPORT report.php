<?php
    error_reporting(0);
    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';
    include 'GLOBAL_FRAP_ADMIN_CHECKING.php';

if(date('d')==30 || (date('m') == 2 && date('d')==28)){
    $queryDed = "SELECT m.MEMBER_ID, count(t.txn_id) from member m  
left join (SELECT m.MEMBER_ID as 'Member_ID',t.txn_id from member m right join txn_reference t on m.MEMBER_ID=t.MEMBER_ID where date(txn_date) = date(now())) t 
on m.MEMBER_ID = t.MEMBER_ID 
where m.membership_status = 2 and m.user_status = 1  
group by m.MEMBER_ID
having count(TXN_ID)=0;";
    $result = mysqli_query($dbc,$queryDed);
    while($row = mysqli_fetch_assoc($result)){
        $queryMemDed = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE,TXN_DESC,AMOUNT,TXN_DATE,SERVICE_ID) VALUES({$row['MEMBER_ID']},2,'Deduction for membership',100,date(now()),1);";
        mysqli_query($dbc,$queryMemDed);
    }
}
    if(!isset($_POST['select_date'])){
       $query = "SELECT month(max(txn_date)) as 'Month',Year(max(txn_date)) as 'Year' from txn_reference where txn_type = 2";
       $result = mysqli_query($dbc,$query);
       $row = mysqli_fetch_assoc($result);
       $date = $row['Month']."-".$row['Year'];

       $monthStart =$row['Month'];
       $yearStart =$row['Year'];
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

       

}
$page_title = 'Loans - Collected Membership Fees';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';
?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Collected Membership Fees for <?php if(isset($yearStart)){echo date('F', mktime(0, 0, 0, $monthStart, 10)).' '.$yearStart;
                                if(isset($yearEnd)){

                                    echo ' - '.date('F', mktime(0, 0, 0, $monthEnd, 10)).' '.$yearEnd;

                                }
                            }
                            else{
                               echo "Latest date"; 
                            }?>
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->

                <div class="row">

                    <div class="col-lg-12">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>View Report for (Month & Year)</b>

                            </div>

                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-lg-12">
                                  <form action = "ADMIN MREPORT report.php" method = "post">

                        <div class="col-lg-12lg-4">
                            
                                <label for="event_start">Start Date</label>
                                <div class="input-group date" id="datetimepicker1">
                                    <input id="event_start" name="event_start" type="text" class="form-control">
                                    <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                </div>
                           
                        </div>
                        <div class="col-lg-12lg-4">
                            <div class="form-group">
                                <label for="event_start">End Date</label>
                                <div class="input-group date" id="datetimepicker2">
                                    <input id="event_start" name="event_end" type="text" class="form-control">
                                    <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                </div>
                            </div>
                        </div>
                       
                        

                                    <div class="col-lg-12" align="left">

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

                        <div class="panel panel-info">

                            <div class="panel-heading">

                                <b>Membership Fees Collected</b>

                            </div>

                            <div class="panel-body"><p>
                                <?php
                                    if(!isset($yearEnd)){
                                        $query1 = "SELECT s.SERVICE as 'Type',sum(amount)  as 'Amount',count(amount) as 'Count'
                                                    from service_type s
                                                    left join txn_reference t
                                                    on t.SERVICE_ID = s.SERVICE_ID
                                                    join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest
                                                    where $monthStart = Month(txn_date) AND $yearStart = Year(txn_date)  AND t.TXN_TYPE = 2 AND s.SERVICE_ID = 1 
                                                    group by t.SERVICE_ID ";
                                    }
                                    else{
                                        $query1 = "SELECT s.SERVICE as 'Type',sum(amount)  as 'Amount',count(amount) as 'Count'
                                                    from service_type s
                                                    left join txn_reference t
                                                    on t.SERVICE_ID = s.SERVICE_ID
                                                    join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest
                                                    where (txn_date between '$yearStart-$monthStart-01 00:00:00' AND '$yearEnd-$monthEnd-31 23:59:59')  AND t.TXN_TYPE = 2 AND s.SERVICE_ID = 1 
                                                    group by t.SERVICE_ID ";
                                    }

                                    $result1 = mysqli_query($dbc,$query1);
                                    $row1 = mysqli_fetch_assoc($result1);

                                ?>
                                <b>Total Number of Fees Collected: <?php if(!empty($row1)){
                                    echo $row1['Count'];

                                }
                                else echo "0"?></b> <p>
                                <b>Total Amount Collected:₱ <?php if(!empty($row1)){
                                    echo number_format($row1['Amount'],2)."<br>";

                                }
                                else echo "0.00";?></b> <p>
                                
                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="panel panel-info">

                            <div class="panel-heading">

                                <b>Health Aid Fees Collected</b>

                            </div>

                            <div class="panel-body"><p>
                                <?php
                                    if(!isset($yearEnd)){
                                        $query1 = "SELECT s.SERVICE as 'Type',sum(amount)  as 'Amount',count(amount) as 'Count'
                                                    from service_type s
                                                    left join txn_reference t
                                                    on t.SERVICE_ID = s.SERVICE_ID
                                                    
                                                    where $monthStart = Month(txn_date) AND $yearStart = Year(txn_date)  AND t.TXN_TYPE = 2 AND s.SERVICE_ID = 2 
                                                    group by t.SERVICE_ID ";
                                    }
                                    else{
                                        $query1 = "SELECT s.SERVICE as 'Type',sum(amount)  as 'Amount',count(amount) as 'Count'
                                                from service_type s
                                                left join txn_reference t
                                                on t.SERVICE_ID = s.SERVICE_ID
                                                
                                                where (txn_date between '$yearStart-$monthStart-01 00:00:00' AND '$yearEnd-$monthEnd-31 23:59:59')  AND t.TXN_TYPE = 2 AND s.SERVICE_ID = 2 
                                                group by t.SERVICE_ID ";
                                    }
                                    $result1 = mysqli_query($dbc,$query1);
                                    $row1 = mysqli_fetch_assoc($result1);

                                ?>
                                <b>Total Number of Fees Collected: <?php if(!empty($row1)){
                                    echo $row1['Count'];

                                }
                                else echo "0"?></b> <p>
                                <b>Total Amount Collected:₱ <?php if(!empty($row1)){
                                    echo number_format($row1['Amount'],2)."<br>";

                                }
                                else echo "0.00";?></b> <p>
                                            
                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="panel panel-info">

                            <div class="panel-heading">

                                <b>FALP Revenue Collected</b>

                            </div>

                            <div class="panel-body"><p>

                                 <?php
                                    if(!isset($yearEnd)){
                                    $query1 = "SELECT s.SERVICE as 'Type',sum(amount)  as 'Amount',count(amount) as 'Count'
                                                from service_type s
                                                left join txn_reference t
                                                on t.SERVICE_ID = s.SERVICE_ID
                                               
                                                where $monthStart = Month(txn_date) AND $yearStart = Year(txn_date)  AND t.TXN_TYPE = 2 AND s.SERVICE_ID = 4 
                                                group by t.SERVICE_ID ";

                                    }
                                    else{
                                        $query1 = "SELECT s.SERVICE as 'Type',sum(amount)  as 'Amount',count(amount) as 'Count'
                                                from service_type s
                                                left join txn_reference t
                                                on t.SERVICE_ID = s.SERVICE_ID
                                               
                                                where (txn_date between '$yearStart-$monthStart-01 00:00:00' AND '$yearEnd-$monthEnd-31 23:59:59')  AND t.TXN_TYPE = 2 AND s.SERVICE_ID = 4 
                                                group by t.SERVICE_ID ";
                                    }
                                    $result1 = mysqli_query($dbc,$query1);
                                    $row1 = mysqli_fetch_assoc($result1);

                                ?>
                                <b>Total Number of Fees Collected: <?php if(!empty($row1)){
                                    echo $row1['Count'];

                                }
                                else echo "0"?></b> <p>
                                <b>Total Amount Collected:₱ <?php if(!empty($row1)){
                                    echo number_format($row1['Amount'],2)."<br>";

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
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function(){
            $('#table').DataTable();
        });
        //Date Picker script
        $(function () {
                
            $('#datetimepicker1').datetimepicker( {
                locale: moment().local('ph'),
                maxDate: moment(),
                format: 'YYYY MMM'
            });
            $('#datetimepicker2').datetimepicker( {
                locale: moment().local('ph'),
                
                
                format: 'YYYY MMM'
            });

        
        });

    </script>

<?php include "GLOBAL_FOOTER.php" ?>
