<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php if(isset($page_title)){ echo $page_title; } ?></title>

    <link href="css/montserrat.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sb-admin.css" rel="stylesheet">
    <link href="css/cards.css" rel="stylesheet">
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/moment.js"></script>
    <script src="js/bootstrap-datetimepicker.min.js"></script>

    <link href="datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <script src="datatables/jquery.dataTables.js"></script>
    <script src="datatables/dataTables.bootstrap4.js"></script>

    <script src="js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <link href="js/jquery-ui-1.12.1.custom/jquery-ui.min.css" rel="stylesheet">
    <link href="js/jquery-ui-1.12.1.custom/jquery-ui.structure.min.css" rel="stylesheet">
    <link href="js/jquery-ui-1.12.1.custom/jquery-ui.theme.min.css" rel="stylesheet">

    <!-- Include Editor style. -->

    <link href="froala/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
    <link href="froala/css/froala_style.min.css" rel="stylesheet" type="text/css" />

    <!-- Include JS file. -->

    <script type="text/javascript" src="froala/js/froala_editor.pkgd.min.js"></script>
</head>
<style>
    head{
        background-color: #f0f0f0;
    }

    body{
        padding-top: 1rem;
        padding-bottom: 1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        background-color: #f0f0f0;
    }




</style>

<body>
<div id="wrapper">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header"> <!-- Logo -->

            <img src="images/I-FA Logo Edited.png" id="ifalogo">


            <ul class="nav navbar-right top-nav"> <!-- Top Menu Items / Notifications area -->

                <li>
                    <a href="#"> Home </a>
                </li>

                <li class="dropdown sideicons">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Admin Tools <b class="caret"></b></a>

                    <ul class="dropdown-menu alert-dropdown">


                        <li>
                            <a href="#"> <i class="fa fa-money" aria-hidden="true"></i> Loans </a>
                        </li>

                        <li>
                            <a href="#"> <i class="fa fa-newspaper-o" aria-hidden="true"></i> Santinig Content </a>
                        </li>

                        <li>
                            <a href="#"> <i class="fa fa-file-text" aria-hidden="true"></i> Documents</a>
                        </li>

                    </ul>

                </li>



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
                    <?php
                    $query = "SELECT LASTNAME, FIRSTNAME FROM employee
                                    
                        WHERE MEMBER_ID =" . $_SESSION['idnum'].";";

                    $result = mysqli_query($dbc, $query);
                    $row = mysqli_fetch_array($result);

                    $displayName = $row['LASTNAME']." , ".$row['FIRSTNAME'][0].". ";

                    ?>

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $displayName; ?> <b class="caret"></b></a>

                    <ul class="dropdown-menu">

                        <li>

                            <a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>

                        </li>

                    </ul>

                </li>

            </ul>

