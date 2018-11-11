<!DOCTYPE html>
<html lang="en">

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

<?php

    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';
    include 'GLOBAL_FRAP_ADMIN_CHECKING.php';

    require_once('mysql_connect_FA.php');

    $queryMem = "SELECT M.MEMBER_ID, M.LASTNAME, M.FIRSTNAME, M.MIDDLENAME, M.DATE_APPLIED, D.DEPT_NAME FROM MEMBER AS M
                 JOIN REF_DEPARTMENT AS D
                 ON M.DEPT_ID = D.DEPT_ID
                 WHERE USER_STATUS = '1' AND MEMBERSHIP_STATUS = '1';";

    $resultMem = mysqli_query($dbc, $queryMem);

    if (isset($_POST['submit'])) {

        $_SESSION['memapp_selected_id'] = $_POST['submit'];

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN MEMBERSHIP appdetails.php");

    }
$page_title = 'Loans - Membership Applications';
include 'GLOBAL_HEADER.php';
include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';
?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Pending Membership Applications
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">

                       <div class="row">

                            <div class="col-lg-12">

                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> <!-- SERVER SELF -->

                                <table id="table" class="table table-bordered table-striped">
                                    
                                    <thead>

                                        <tr>

                                        <td align="center"><b>ID Number</b></td>
                                        <td align="center" width="300px"><b>Name</b></td>
                                        <td align="center"><b>Department</b></td>
                                        <td align="center"><b>Date Applied</b></td>
                                        <td align="center"><b>Actions</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>

                                    <?php foreach ($resultMem as $resultRow) { ?>

                                        <tr>

                                        <td align="center"><?php echo $resultRow['MEMBER_ID']; ?></td>
                                        <td align="center"><?php echo $resultRow['FIRSTNAME'] . " " . $resultRow['LASTNAME']; ?></td>
                                        <td align="center"><?php echo $resultRow['DEPT_NAME']; ?></td>
                                        <td align="center"><?php echo $resultRow['DATE_APPLIED']; ?></td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<button type="submit" name="submit" class="btn btn-success" value="<?php echo $resultRow['MEMBER_ID']; ?>">Details</button>&nbsp;&nbsp;&nbsp;</td>

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
