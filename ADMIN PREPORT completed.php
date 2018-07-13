<!DOCTYPE html>
<html lang="en">
<?php
session_start();
 if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
            
    }
require_once('mysql_connect_FA.php');
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


?>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin - Bootstrap Admin Template</title>

    <link href="css/montserrat.css" rel="stylesheet">
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            
            <div class="navbar-header"> <!-- Logo -->
                
                <img src="images/I-FA Logo Edited.png" id="ifalogo">
            
            
            <ul class="nav navbar-right top-nav"> <!-- Top Menu Items / Notifications area -->
                
                <li class="dropdown sideicons">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>

                    <ul class="dropdown-menu alert-dropdown">

                        <li>
                            <a href="#">Alert Name <span class="label label-default">Alert Badge</span></a>
                        </li>

                        <li>
                            <a href="#">Alert Name <span class="label label-primary">Alert Badge</span></a>
                        </li>

                        <li>
                            <a href="#">Alert Name <span class="label label-success">Alert Badge</span></a>
                        </li>

                        <li>
                            <a href="#">Alert Name <span class="label label-info">Alert Badge</span></a>
                        </li>

                        <li>
                            <a href="#">Alert Name <span class="label label-warning">Alert Badge</span></a>
                        </li>

                        <li>
                            <a href="#">Alert Name <span class="label label-danger">Alert Badge</span></a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="#">View All</a>
                        </li>

                    </ul>

                </li>

                <li class="dropdown sideicons">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Jo, Melton <b class="caret"></b></a>

                    <ul class="dropdown-menu">

                        <li>

                            <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>

                        </li>

                        <li class="divider"></li>

                        <li>

                            <a href="login.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>

                        </li>

                    </ul>

                </li>

            </ul>
			
            </div>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">

                <ul class="nav navbar-nav side-nav">

    <li id="top">

        <a href="ADMIN FALP manual.php"><i class="fa fa-gears" aria-hidden="true"></i> Add Member & FALP Account</a>

    </li>

    <!--<li>

        <a href="ADMIN addaccount.php"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Admin Account</a>

    </li>

    <li>

    <a href="javascript:;" data-toggle="collapse" data-target="#applications"><i class="fa fa-wpforms" aria-hidden="true"></i>&nbsp;Applications<i class="fa fa-fw fa-caret-down"></i></a>

        <ul id="applications" class="collapse">

            <li>
                <a href="ADMIN MEMBERSHIP applications.php"><i class="fa fa-id-card-o" aria-hidden="true"></i>&nbsp;&nbsp;Membership Pending Applications</a>
            </li>

            <li>
                <a href="ADMIN FALP applications.php"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;&nbsp;FALP Pending Applications</a>
            </li>

            <li>
                <a href="ADMIN BANK applications.php"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;Bank Loan Pending Applications</a>
            </li>

            <li>
                <a href="ADMIN HEALTHAID applications.php"><i class="fa fa-medkit" aria-hidden="true"></i>&nbsp;&nbsp;Health Aid Pending Applications</a>
            </li>

        </ul>

    </li>

    <li>

        <a href="ADMIN LIFETIME addmember.php"><i class="fa fa-id-card-o" aria-hidden="true"></i> Add Lifetime Member</a>

    </li>

    <li>

    <a href="javascript:;" data-toggle="collapse" data-target="#bankloans"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;Bank Loans<i class="fa fa-fw fa-caret-down"></i></a>

        <ul id="bankloans" class="collapse">

            <li>
                <a href="ADMIN BANK addbank.php"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;Add Partner Bank</a>
            </li>

            <li>
                <a href="ADMIN BANK editbank.php"><i class="fa fa-gears" aria-hidden="true"></i>&nbsp;&nbsp;Enable/Disable Partner Bank</a>
            </li>

            <li>
                <a href="ADMIN BANK addplan.php"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;&nbsp;Add Bank Loan Plan</a>
            </li>

            <li>
                <a href="ADMIN BANK editplan.php"><i class="fa fa-gears" aria-hidden="true"></i>&nbsp;&nbsp;Enable/Disable Bank Loan Plan</a>
            </li>

        </ul>

    </li>-->
    
            <li>
                <a href="ADMIN MEMBERS viewmembers.php"><i class="fa fa-group" aria-hidden="true"></i>&nbsp;&nbsp;View All Members</a>
            </li>

    <li>

    <a href="javascript:;" data-toggle="collapse" data-target="#loans"><i class="fa fa-money" aria-hidden="true"></i>&nbsp;On-going Loans<i class="fa fa-fw fa-caret-down"></i></a>

        <ul id="loans" class="collapse">

            <li>
                <a href="ADMIN FALP viewactive.php"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;&nbsp;FALP Loans</a>
            </li>

            <!--<li>
                <a href="ADMIN BANK viewactive.php"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;Bank Loans</a>
            </li>-->

        </ul>

    </li>

    <li>

    <a href="javascript:;" data-toggle="collapse" data-target="#dreports"><i class="fa fa-minus" aria-hidden="true"></i>&nbsp;Deduction Reports<i class="fa fa-fw fa-caret-down"></i></a>

        <ul id="dreports" class="collapse">

            <li>
                <a href="ADMIN DREPORT general.php"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;&nbsp;General Deductions</a>
            </li>

            <li>
                <a href="ADMIN DREPORT detailed.php"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;&nbsp;Detailed Deductions</a>
            </li>

        </ul>

    </li>

    <li>

    <a href="javascript:;" data-toggle="collapse" data-target="#preports"><i class="fa fa-table" aria-hidden="true"></i>&nbsp;Periodical Reports<i class="fa fa-fw fa-caret-down"></i></a>

        <ul id="preports" class="collapse">

            <li>
                <a href="ADMIN PREPORT completed.php"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;&nbsp;Completed Loans</a>
            </li>

            <li>
                <a href="ADMIN PREPORT new.php"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;&nbsp;New Deductions</a>
            </li>

        </ul>

    </li>

                        <li>

        <a href="ADMIN MREPORT report.php"><i class="fa fa-table" aria-hidden="true"></i> Monthly Report</a>

    </li>

    <!--<li>

    <a href="javascript:;" data-toggle="collapse" data-target="#repo"><i class="fa fa-folder-open-o" aria-hidden="true"></i>&nbsp;File Repository<i class="fa fa-fw fa-caret-down"></i></a>

        <ul id="repo" class="collapse">

            <li>
                <a href="ADMIN FILEREPO.php"><i class="fa fa-files-o" aria-hidden="true"></i>&nbsp;&nbsp;View Documents</a>
            </li>

            <li>

                <a href="ADMIN FILEREPO upload.php"><i class="fa fa-upload" aria-hidden="true"></i> Upload Documents</a>

            </li>

        </ul>

    </li>

    <li>

        <a href="ADMIN MANAGE.php"><i class="fa fa-gears" aria-hidden="true"></i> Admin Management</a>

    </li>-->

</ul>

            </div>
            <!-- /.navbar-collapse -->
        </nav>

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

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

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
