<?php session_start();
require_once('mysql_connect_FA.php');


    if ($_SESSION['usertype'] != 1) {

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
        
    }
    
    $query1 = "SELECT * from loans where member_id = ".$_SESSION['idnum']." AND (loan_status = 1 OR loan_status = 2) && loan_detail_id != 1" ; 
   
    $result1 = mysqli_query($dbc,$query1);

    $checks = mysqli_fetch_array($result1,MYSQLI_ASSOC);

    // runs under the assumption that the member can only have 1 active/pending loan. 

    if(!empty($checks)){ // checks if you have a pending loan  then sends an alert that you either have a pending loan app or an ongoing aplication

        if($checks['LOAN_STATUS'] == 1){ // redirects the user to pending 

            header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER BANKLOAN pending.php");

        }else if($checks['LOAN_STATUS'] == 2){ // redirects the user to summary 

            header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER BANKLOAN summary.php");

        }

    }

    




if(isset($_POST['choice'])){
    $id = $_POST['choice'];
    $query = "SELECT LOAN_ID as 'ID',BANK_ID as 'BID', MIN_AMOUNT,MAX_AMOUNT,INTEREST,MIN_TERM,MAX_TERM,MINIMUM_SALARY 
                from loan_plan
                where bank_id = $id AND status != 2";
    
}
else{
    $query = "SELECT LOAN_ID as 'ID',l.BANK_ID as 'BID', MIN_AMOUNT,MAX_AMOUNT,INTEREST,MIN_TERM,MAX_TERM,MINIMUM_SALARY
from loan_plan l 
join (SELECT bank_id,status as 'Bank_Status' from Banks) b
on l.BANK_ID = b.bank_id
where l.bank_id != 1 AND b.Bank_Status = 1 AND l.status != 2";
}

$result = mysqli_query($dbc,$query);
    

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>FRAP | Bank Loan Application</title>

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

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> John Smith <b class="caret"></b></a>

                    <ul class="dropdown-menu">

                        <li>

                            <a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>

                        </li>

                    </ul>

                </li>

            </ul>
            </div>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">

                <ul class="nav navbar-nav side-nav">

                    <li>

                            <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>

                        </li>

                        <li class="divider"></li>

                        <li>

                            <a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>

                        </li>

                    </ul>

                </li>

            </ul>
            </div>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">

                <ul class="nav navbar-nav side-nav">

                    <li id="top">

                        <a href="MEMBER dashboard.php"><i class="fa fa-area-chart" aria-hidden="true"></i> Overview</a>

                    </li>

                    <li>

                        <a href="javascript:;" data-toggle="collapse" data-target="#applicationformsdd"><i class="fa fa-wpforms" aria-hidden="true"></i> Application Forms <i class="fa fa-fw fa-caret-down"></i></a>

                        <ul id="applicationformsdd" class="collapse">

                            <li>
                                <a href="MEMBER FALP application.php"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;FALP Application</a>
                            </li>

                            <li>
                                <a href="MEMBER HA application.php"><i class="fa fa-medkit" aria-hidden="true"></i>&nbsp;&nbsp;Health Aid Application</a>
                            </li>

                            <li>
                                <a href="MEMBER LIFETIME form.php"><i class="fa fa-handshake-o" aria-hidden="true"></i>&nbsp;&nbsp;Lifetime Member Application</a>
                            </li>

                        </ul>

                    </li>

                    <li>

                        <a href="MEMBER BANKLOAN list.php"><i class="fa fa-dollar" aria-hidden="true"></i> Bank Loans</a>

                    </li>

                    <li>

                    <a href="MEMBER DEDUCTION summary.php"><i class="fa fa-book" aria-hidden="true"></i> Salary Deduction Summary</a>

                    </li>

                    <li>

                    <a href="javascript:;" data-toggle="collapse" data-target="#loantrackingdd"><i class="fa fa-money" aria-hidden="true"></i> Loan Tracking <i class="fa fa-fw fa-caret-down"></i></a>

                        <ul id="loantrackingdd" class="collapse">

                            <li>
                                <a href="MEMBER FALP summary.php"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;FALP Loan</a>
                            </li>

                            <li>
                                <a href="MEMBER BANKLOAN summary.php"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;&nbsp;Bank Loan</a>
                            </li>

                        </ul>

                    </li>

                    <li>

                    <a href="javascript:;" data-toggle="collapse" data-target="#servicessummarydd"><i class="fa fa-university" aria-hidden="true"></i> Services Summary <i class="fa fa-fw fa-caret-down"></i></a>

                        <ul id="servicessummarydd" class="collapse">

                            <li>
                                <a href="MEMBER HA summary.php"><i class="fa fa-medkit" aria-hidden="true"></i>&nbsp;&nbsp;Health Aid Summary</a>
                            </li>

                            <li>
                                <a href="MEMBER LIFETIME summary.php"><i class="fa fa-handshake-o" aria-hidden="true"></i>&nbsp;&nbsp;Lifetime Membership Summary</a>
                            </li>

                        </ul>

                    </li>

                    <li>

                        <a href="MEMBER AUDITRAIL.php"><i class="fa fa-backward" aria-hidden="true"></i> Audit Trail</a>

                    </li>

                    <li>

                        <a href="MEMBER FILEREPO.php"><i class="fa fa-folder" aria-hidden="true"></i> File Repository</a>

                    </li>

                </ul>

            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">Bank Loan List</h1>
                    
                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-6">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>Select Bank</b>

                            </div>

                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-lg-9">

                                    <form action="MEMBER BANKLOAN list.php" method="POST">

                                        <select class="form-control" name = "choice">
                                            <?php 
                                            
                                            $query1 = "SELECT * 
                                                            from banks
                                                            where bank_id != 1 AND status != 2";
                                            $result1 = mysqli_query($dbc,$query1);
                                            while($ans = mysqli_fetch_assoc($result1)){
                                            ?>
                                            <option value = <?php echo $ans['BANK_ID'];
                                            if(isset($_POST['choice'])){
                                                if($ans['BANK_ID']==$_POST['choice']){
                                                    echo " selected";
                                                }
                                            }?>><?php echo $ans['BANK_NAME'];echo " ";echo $ans['BANK_ABBV'];?></option>
                                            <?php }; ?>

                                        </select>

                                    

                                    </div>

                                    <div class="col-lg-3">

                                        <input type="submit" class="btn btn-success" name="select_bank" value="Refresh Table">

                                    </div>
                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <form action="MEMBER BANKLOAN calculator.php" method="POST"> <!-- SERVER SELF -->

                        <table id="table" class="table table-bordered table-striped">
                            
                            <thead>

                                <tr>
                                
                                <td align="center"><b>Amount to Borrow (Range)</b></td>
                                <td align="center"><b>Interest (Fixed)</b></td>
                                <td align="center"><b>Payment Terms (Range)</b></td>
                                <td align="center"><b>Minimum Monthly Salary</b></td>
                                <td align="center"><b>Actions</b></td>

                                </tr>

                            </thead>

                            <tbody>
                                <?php while($ans = mysqli_fetch_assoc($result)){?>
                                <tr>
                                
                                <td align="center">₱ <?php echo $ans['MIN_AMOUNT'];?><input type = "text" name = "min" value = <?php echo $ans['MIN_AMOUNT'];?> hidden> - ₱ <?php echo $ans['MAX_AMOUNT'];?><input type = "text" name = "max" value = <?php echo $ans['MAX_AMOUNT'];?> hidden></td>
                                <td align="center"><?php echo $ans['INTEREST'];?>%</td>
                                <td align="center"><?php echo $ans['MIN_TERM'];?> months - <?php echo $ans['MAX_TERM'];?> months</td>
                                <td align="center">₱ <?php echo $ans['MINIMUM_SALARY'];?>
                                <input type = "text" name = "interest" value = <?php echo $ans['INTEREST'];?> hidden>
                                <input type = "text" name = "minT" value = <?php echo $ans['MIN_TERM'];?> hidden>
                                <input type = "text" name = "maxT" value = <?php echo $ans['MAX_TERM'];?> hidden>
                                <input type = "text" name = "minS" value = <?php echo $ans['MINIMUM_SALARY'];?> hidden>
                                </td>
                                <td align="center">&nbsp;&nbsp;&nbsp;<button type="submit" name="details" class="btn btn-success" value=<?php echo $ans['ID'];?>>Details</button>&nbsp;&nbsp;&nbsp;</td>
                                
                                </tr>
                                <?php };?>
                            </tbody>

                        </table>

                        </form>

                    </div>

                </div>

                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

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
