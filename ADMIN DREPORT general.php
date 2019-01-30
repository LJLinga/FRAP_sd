
<?php
require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';


$flag=0;
if(isset($_POST['print'])){
    $_SESSION['date']=$_POST['date'];
    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/generateGOD.php");
}
if(!isset($_POST['select_date'])){
   
        $query="SELECT m.member_ID as 'ID', firstname as 'First',lastname as 'Last',middlename as 'Middle',DEPT_NAME,sum(t.amount) as 'Total'
        from member m
        join ref_department d
        on m.dept_id = d.dept_id
        join txn_reference t
        on t.MEMBER_ID = m.MEMBER_ID
        join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest
        where TXN_TYPE =2 and DATE(latest.Date) = DATE(txn_date)
        group by m.member_ID";

}
else {
    if($_POST['date'] != "0"){
        $date = $_POST['date'];
        $day = substr($date,0,strpos($date," "));
        $month = substr($date,(strpos($date," ")+1),strpos($date,"-")-strpos($date," ")-1);
        $year = substr($date,strpos($date,"-")+1);
        $query="SELECT m.member_ID as 'ID', firstname as 'First',lastname as 'Last',middlename as 'Middle',DEPT_NAME,sum(t.amount) as 'Total'
        from member m
        join ref_department d
        on m.dept_id = d.dept_id
        join txn_reference t
        on t.MEMBER_ID = m.MEMBER_ID
        where TXN_TYPE =2 and $month = Month(txn_date) AND $year = Year(txn_date) AND $day = DAY(txn_date)
        group by m.member_ID";
    }
    else{
        $query="SELECT m.member_ID as 'ID', firstname as 'First',lastname as 'Last',middlename as 'Middle',DEPT_NAME,sum(t.amount) as 'Total'
        from member m
        join ref_department d
        on m.dept_id = d.dept_id
        join txn_reference t
        on t.MEMBER_ID = m.MEMBER_ID
        join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest
        where TXN_TYPE =2 and DATE(latest.Date) = DATE(txn_date)
        group by m.member_ID";
    }
}
$result=mysqli_query($dbc,$query);

$page_title = 'Loans - General Report';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';
?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            General Deductions Report
                            
                            
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

                                    <form action="ADMIN DREPORT general.php" method="POST">

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

                                        <td align="center" width="250px"><b>ID Number</b></td>
                                        <td align="center"><b>Name</b></td>
                                        <td align="center" width="200px"><b>Department</b></td>
                                        <td align="center" width="200px"><b>Total Salary Deduction</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>
                                        <?php 
                                        while($ans = mysqli_fetch_assoc($result)){


                                        ?>
                                        <tr>

                                        <td align="center"><?php echo $ans['ID'];?></td>
                                        <td align="center"><?php echo $ans['First']." ".$ans['Middle']." ".$ans['Last'];?></td>
                                        <td align="center"><?php echo $ans['DEPT_NAME'];?></td>
                                        <td align="center"><?php echo $ans['Total'];?></td>

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

    
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script>

        $(document).ready(function(){
    
            $('#table').DataTable();

        });

    </script>

</body>

</html>
