<!DOCTYPE html>
<html lang="en">
<?php
require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';


$flag=0;
if(isset($_POST['print'])){
    $_SESSION['date']=$_POST['date'];
    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/generateCD.php");
}
if(!isset($_POST['select_date'])){
   
        $query="SELECT m.member_id as 'ID',m.firstName as 'First',m.middlename as 'Middle', m.lastname as 'Last',l.LOAN_DETAIL_ID as 'Ref',l.LOAN_ID
from loans l 
 
join member m
on l.member_id = m.member_id
join (SELECT max(date_matured) as 'Date' from loans) latest 
where l.LOAN_STATUS = 3 and latest.Date = l.Date_Matured";

}
else {
    if($_POST['date'] != "0"){
        $date = $_POST['date'];
        $day = substr($date,0,strpos($date," "));
        $month = substr($date,(strpos($date," ")+1),strpos($date,"-")-strpos($date," ")-1);
        $year = substr($date,strpos($date,"-")+1);
        $query="SELECT m.member_id as 'ID',m.firstName as 'First',m.middlename as 'Middle', m.lastname as 'Last',l.LOAN_DETAIL_ID as 'Ref',l.LOAN_ID
from loans l  

join member m
on l.member_id = m.member_id
where l.LOAN_STATUS = 3 AND $day = day(l.Date_Matured) AND $month = month(l.Date_Matured) AND $year = Year(l.Date_Matured)
group by l.loan_id";
    }
    else{
        $query="SELECT m.member_id as 'ID',m.firstName as 'First',m.middlename as 'Middle', m.lastname as 'Last',l.LOAN_DETAIL_ID as 'Ref',l.LOAN_ID
from loans l 
 
join member m
on l.member_id = m.member_id
join (SELECT max(date_matured) as 'Date' from loans) latest 
where l.LOAN_STATUS = 3 and latest.Date = l.Date_Matured";
    }
}
$result=mysqli_query($dbc,$query);

$page_title = 'Loans - Completed Deductions';
include 'GLOBAL_HEADER.php';
include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';

?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Completed Deductions 
                            
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->

                <div class="row">

                    <div class="col-lg-6">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>View Report for (Month & Year)</b>

                            </div>

                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-lg-6">

                                       <form action="ADMIN PREPORT completed.php" method="POST">

                                        <select class="form-control" name = "date">
                                        
                                            <option value = "0">This Current Date</option>  
                                        <?php
                                        $query="SELECT DISTINCT MONTH(Date_Matured) as 'Month',YEAR(Date_Matured) as 'Year', Day(Date_Matured) as 'Day' from loans 
                                            where loan_status = 3
                                            order by Date_Matured desc";
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
                                        <td align="center"><b>Loan Completed</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>

                                     <?php 
                                        while($ans = mysqli_fetch_assoc($result)){


                                        ?>
                                        <tr>

                                        <td align="center"><?php echo $ans['ID'];?></td>
                                        <td align="center"><?php echo $ans['First']." ".$ans['Middle']." ".$ans['Last'];?></td>
                                        <td align="center">
                                        <?php if($ans['Ref']=="1"){
                                            echo "FALP Loan";

                                        }
                                        else
                                            echo "BANK Loan";?></td>
                                        

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
