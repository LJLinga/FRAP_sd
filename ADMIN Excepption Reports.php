<?php
error_reporting(0);
require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';
include 'GLOBAL_CLASS_CRUD.php';
if(date('d')==30 || (date('m') == 2 && date('d')==28)){
    /*$queryDed = "SELECT m.MEMBER_ID, count(t.txn_id) from member m
left join (SELECT m.MEMBER_ID as 'Member_ID',t.txn_id from member m right join txn_reference t on m.MEMBER_ID=t.MEMBER_ID where date(txn_date) = date(now())) t
on m.MEMBER_ID = t.MEMBER_ID
where m.membership_status = 2 and m.user_status = 1
group by m.MEMBER_ID
having count(TXN_ID)=0;";
    $result = mysqli_query($dbc,$queryDed);
    while($row = mysqli_fetch_assoc($result)){
        $queryMemDed = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE,TXN_DESC,AMOUNT,TXN_DATE,SERVICE_ID) VALUES({$row['MEMBER_ID']},2,'Deduction for membership',100,date(now()),1);";
        mysqli_query($dbc,$queryMemDed);
    }*/}
if(isset($_POST['print'])){
    $_SESSION['event_start'] = null;
    $_SESSION['event_start']=$_POST['event_start'];
    $_SESSION['event_end'] = null;
    if(!empty($_POST['event_end']))
        $_SESSION['event_end'] = $_POST['event_end'];
    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/generatePerf.php");
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

    $yearStart = substr($dateStart,0,strpos($dateStart," "));
    $monthStart =date('m', strtotime(substr($dateStart,strpos($dateStart," ")+1)));
    if(!empty($_POST['event_end'])){
        $dateEnd = $_POST['event_end'];
        $yearEnd = substr($dateEnd,0,strpos($dateEnd," "));
        $monthEnd = date('m', strtotime(substr($dateEnd,strpos($dateEnd," ")+1)));
    }



}
$page_title = 'Loans - Collected Fees';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';
?>

<div id="page-wrapper">

    <div class="container-fluid">

        <div class="row">

            <div class="col-lg-12">

                <h1 class="page-header">
                    Current Performance as of <?php echo date('d F Y');   ?>
                </h1>

            </div>

        </div>

        <div class="row">

            <div class="col-lg-12">

                <div class="panel panel-info">

                    <div class="panel-heading">
                        <h3>
                            <b>Membership</b>
                        </h3>
                    </div>

                    <div class="panel-body"><p>
                            <?php



                            if(!isset($yearEnd)){
                                $query1 = "SELECT  d.DEPT_NAME, COUNT(m.MEMBER_ID) as 'NUM_MEM_PER_DEPT'
                            from member m 
                            join ref_department d 
                            on m.DEPT_ID = d.DEPT_ID
                            GROUP BY d.DEPT_NAME;
                             ";

                            }
                            else{
                                $queryCount ="SELECT count(t.MEMBER_ID) as 'Count'
                                                    from service_type s
                                                    left join txn_reference t
                                                    on t.SERVICE_ID = s.SERVICE_ID
                                                    join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest
                                                     where (txn_date between '$yearStart-$monthStart-01 00:00:00' AND '$yearEnd-$monthEnd-31 23:59:59') AND TXN_DESC='Membership Application Approved' AND t.TXN_TYPE = 1 AND s.SERVICE_ID = 1 
                                                    group by t.SERVICE_ID ";
                                $query1 = "SELECT s.SERVICE as 'Type',sum(amount)  as 'Amount',count(amount) as 'Count'
                                                    from service_type s
                                                    left join txn_reference t
                                                    on t.SERVICE_ID = s.SERVICE_ID
                                                    join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest
                                                    where (txn_date between '$yearStart-$monthStart-01 00:00:00' AND '$yearEnd-$monthEnd-31 23:59:59')  AND t.TXN_TYPE = 2 AND s.SERVICE_ID = 1 
                                                    group by t.SERVICE_ID ";
                            }

                            $result1 = mysqli_query($dbc,$query1);
                            $result2 = mysqli_query($dbc,$queryCount);

                            ?>
                        <table id="table1" class="table table-bordered table-striped">

                            <thead>

                            <tr>
                                <td align="center"><b>Department Name</b></td>
                                <td align="center"><b>Number of Members</b></td>
                            </tr>

                            </thead>

                            <tbody>

                            <?php

                            foreach ($result1 as $resultRow) {
                                ?>

                                <tr>
                                    <td align="center"><?php echo $resultRow['DEPT_NAME']; ?></td>
                                    <td align="center"><?php echo $resultRow['NUM_MEM_PER_DEPT'] ?></td>


                                </tr>

                            <?php }?>
                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-lg-12">

                <div class="panel panel-info">

                    <div class="panel-heading">
                        <h3>
                            <b>FAP Loans</b>
                        </h3>
                    </div>

                    <div class="panel-body"><p>
                            <?php
                            if(!isset($yearEnd)){

                                $query1 = "SELECT  d.DEPT_NAME, COUNT(l.LOAN_ID) as 'LOANS_PER_DEPT'
from member m 
join ref_department d 
on m.DEPT_ID = d.DEPT_ID
join loans l 
on l.MEMBER_ID = m.MEMBER_ID
GROUP BY d.DEPT_NAME;";
                            }
                            else{
                                $queryCount ="SELECT count(t.MEMBER_ID) as 'Count'
                                                    from service_type s
                                                    left join txn_reference t
                                                    on t.SERVICE_ID = s.SERVICE_ID
                                                    join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest
                                                     where (txn_date between '$yearStart-$monthStart-01 00:00:00' AND '$yearEnd-$monthEnd-31 23:59:59') AND TXN_DESC='Health Aid Application Accepted!' AND t.TXN_TYPE = 1 AND s.SERVICE_ID = 2 
                                                    group by t.SERVICE_ID ";
                                $query1 = "SELECT s.SERVICE as 'Type',sum(amount)  as 'Amount',count(amount) as 'Count'
                                                from service_type s
                                                left join txn_reference t
                                                on t.SERVICE_ID = s.SERVICE_ID
                                                
                                                where (txn_date between '$yearStart-$monthStart-01 00:00:00' AND '$yearEnd-$monthEnd-31 23:59:59')  AND t.TXN_TYPE = 2 AND s.SERVICE_ID = 2 
                                                group by t.SERVICE_ID ";
                            }
                            $result1 = mysqli_query($dbc,$query1);
                            $result2 = mysqli_query($dbc,$queryCount);

                            ?>

                        <table id="table2" class="table table-bordered table-striped">

                            <thead>

                            <tr>
                                <td align="center"><b>Department Name</b></td>
                                <td align="center"><b>Loans per Department</b></td>
                            </tr>

                            </thead>

                            <tbody>

                            <?php

                            foreach ($result1 as $resultRow) {
                                ?>

                                <tr>
                                    <td align="center"><?php echo $resultRow['DEPT_NAME']; ?></td>
                                    <td align="center"><?php echo $resultRow['LOANS_PER_DEPT'] ?></td>


                                </tr>

                            <?php }?>
                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>
        <div class="row">

            <div class="col-lg-12">

                <div class="panel panel-info">

                    <div class="panel-heading">
                        <h3>
                            <b>Health Aid Benefit</b>
                        </h3>
                    </div>

                    <div class="panel-body"><p>
                            <?php
                            if(!isset($yearEnd)){

                                $query1 = "SELECT  d.DEPT_NAME, COUNT(m.MEMBER_ID) as 'HA_PER_DEPT'
from member m 
join ref_department d 
on m.DEPT_ID = d.DEPT_ID
join loans l 
on l.MEMBER_ID = m.MEMBER_ID
WHERE m.HA_STATUS = 2
GROUP BY d.DEPT_NAME;";
                            }
                            else{
                                $queryCount ="SELECT count(t.MEMBER_ID) as 'Count'
                                                    from service_type s
                                                    left join txn_reference t
                                                    on t.SERVICE_ID = s.SERVICE_ID
                                                    join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest
                                                     where (txn_date between '$yearStart-$monthStart-01 00:00:00' AND '$yearEnd-$monthEnd-31 23:59:59') AND TXN_DESC='Health Aid Application Accepted!' AND t.TXN_TYPE = 1 AND s.SERVICE_ID = 2 
                                                    group by t.SERVICE_ID ";
                                $query1 = "SELECT s.SERVICE as 'Type',sum(amount)  as 'Amount',count(amount) as 'Count'
                                                from service_type s
                                                left join txn_reference t
                                                on t.SERVICE_ID = s.SERVICE_ID
                                                
                                                where (txn_date between '$yearStart-$monthStart-01 00:00:00' AND '$yearEnd-$monthEnd-31 23:59:59')  AND t.TXN_TYPE = 2 AND s.SERVICE_ID = 2 
                                                group by t.SERVICE_ID ";
                            }
                            $result1 = mysqli_query($dbc,$query1);
                            $result2 = mysqli_query($dbc,$queryCount);

                            ?>

                        <table id="table3" class="table table-bordered table-striped">

                            <thead>

                            <tr>
                                <td align="center"><b>Department Name</b></td>
                                <td align="center"><b>Active Health Aid Benefit Per Dept</b></td>
                            </tr>

                            </thead>

                            <tbody>

                            <?php

                            foreach ($result1 as $resultRow) {
                                ?>

                                <tr>
                                    <td align="center"><?php echo $resultRow['DEPT_NAME']; ?></td>
                                    <td align="center"><?php echo $resultRow['HA_PER_DEPT'] ?></td>


                                </tr>

                            <?php }?>
                            </tbody>

                        </table>
                    </div>

                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-lg-12">

                <div class="panel panel-info">

                    <div class="panel-heading">
                        <h3>
                            <b>Health Aid Acquisition</b>
                        </h3>
                    </div>

                    <div class="panel-body"><p>

                            <?php
                            if(!isset($yearEnd)){

                                $query1 = "SELECT  d.DEPT_NAME, COUNT(m.MEMBER_ID) as 'HA_Acquisition'
                                from member m 
                                join ref_department d 
                                on m.DEPT_ID = d.DEPT_ID
                                join health_aid ha
                                on ha.MEMBER_ID = m.MEMBER_ID
                                WHERE m.HA_STATUS = 2
                                GROUP BY d.DEPT_NAME;";

                            }
                            else{
                                $queryCount ="SELECT count(t.MEMBER_ID) as 'Count'
                                                    from service_type s
                                                    left join txn_reference t
                                                    on t.SERVICE_ID = s.SERVICE_ID
                                                    join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest
                                                     where (txn_date between '$yearStart-$monthStart-01 00:00:00' AND '$yearEnd-$monthEnd-31 23:59:59') AND TXN_DESC ='FALP Approved, now wait for Pickup. ' AND t.TXN_TYPE = 1 AND s.SERVICE_ID = 4 
                                                    group by t.SERVICE_ID ";
                                $query1 = "SELECT s.SERVICE as 'Type',sum(amount)  as 'Amount',count(amount) as 'Count'
                                                from service_type s
                                                left join txn_reference t
                                                on t.SERVICE_ID = s.SERVICE_ID
                                               
                                                where (txn_date between '$yearStart-$monthStart-01 00:00:00' AND '$yearEnd-$monthEnd-31 23:59:59')  AND t.TXN_TYPE = 2 AND s.SERVICE_ID = 4 
                                                group by t.SERVICE_ID ";
                            }
                            $result1 = mysqli_query($dbc,$query1);
                            $row1 = mysqli_fetch_assoc($result1);
                            $result2 = mysqli_query($dbc,$queryCount);
                            $row2 = mysqli_fetch_assoc($result2);

                            ?>

                        <table id="table4" class="table table-bordered table-striped">

                            <thead>

                            <tr>
                                <td align="center"><b>Department Name</b></td>
                                <td align="center"><b>Active Health Aid Acquisition Per Dept.</b></td>
                            </tr>

                            </thead>

                            <tbody>

                            <?php

                            foreach ($result1 as $resultRow) {
                                ?>

                                <tr>
                                    <td align="center"><?php echo $resultRow['DEPT_NAME']; ?></td>
                                    <td align="center"><?php echo $resultRow['HA_Acquisition'] ?></td>


                                </tr>

                            <?php }?>
                            </tbody>

                        </table>
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
        $('#table1').DataTable();
        $('#table2').DataTable();
        $('#table3').DataTable();
        $('#table4').DataTable();
    });
    //Date Picker script
    $(function () {

        $('#event_start').datetimepicker( {
            locale: moment().local('ph'),
            maxDate: moment(),
            format: 'YYYY MMM'
        });
        $('#event_end').datetimepicker( {
            locale: moment().local('ph'),


            format: 'YYYY MMM'
        });


    });

</script>

<?php include "GLOBAL_FOOTER.php" ?>
