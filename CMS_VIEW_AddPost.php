<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

include_once('DB_CLASS_CRUD.php');
$crud = new DB_CLASS_CRUD();

if(isset($_POST['btnAddPost'])){

    //$postId = $_POST['postId'];
    $postId = "";
    $authorId = $_POST['userId'];
    $title = $_POST['title'];
    $body = $_POST['body'];

    if($postId==null || $postId==""){

        $result = $crud->execute("INSERT INTO posts(authorId, title, body) values('$authorId','$title','$body')");
        echo 'Added new post! <br>';
        echo $_POST['userId'].', '.$_POST['title'].', '.$_POST['body'];

    }else if($postId!=""){

        $result = $crud->execute("UPDATE posts SET
                      title = '$title',
                      body = '$body'
                      WHERE id = '$postId' ");
        echo 'Updated the post! <br>';
        echo $_POST['userId'].', '.$_POST['title'].', '.$_POST['body'];
    }
}

?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Santinig - Add Posts</title>

    <link href="css/montserrat.css" rel="stylesheet">
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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
                    <a href="ADMIN FALP manual.php"><i class="fa fa-gears" aria-hidden="true"></i> Add Member & FALP Account</a>
                </li>
                <li>
                    <a href="ADMIN MEMBERS viewmembers.php"><i class="fa fa-group" aria-hidden="true"></i>&nbsp;&nbsp;View All Members</a>
                </li>
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#loans"><i class="fa fa-money" aria-hidden="true"></i>&nbsp;On-going Loans<i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="loans" class="collapse">
                        <li>
                            <a href="ADMIN FALP viewactive.php"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;&nbsp;FALP Loans</a>
                        </li>
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

            </ul>

        </div>
        <!-- /.navbar-collapse -->
    </nav>

    <div id="page-wrapper">

        <div class="container-fluid">

            <div class="row">

                <div class="col-lg-12">

                    <h1 class="page-header">
                        Add Post
                    </h1>
                    <?php
                    if(isset($message)){
                        echo"  
                                <div class='alert alert-warning'>
                                    ". $message ."
                                </div>
                                ";
                    }
                    ?>
                </div>

            </div>
            <!-- alert -->
            <div class="row">
                <div class="col-lg-12">

                    <p><i>Fields with <big class="req">*</big> are required to be filled out and those without are optional.</i></p>

                    <!--Insert success page-->
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="postId" id="postId" value="<?php echo $_POST['postId']; ?>">
                        <input type="hidden" name="userId" id="userId" value="1">
                        <div class="addaccountdiv">
                            <label class="signfieldlabel">Title</label><big class="req"> *</big>
                            <input type="text" name="title" id="title" class="form-control signupfield" placeholder="Post Title" required>
                        </div><br>
                        <div class="addaccountdiv">
                            <label class="signfieldlabel">Content</label><big class="req"> *</big>
                            <input type="textarea" name="body" id="body" class="form-control signupfield" placeholder="Write content here..." required>
                        </div><br>
                        <div id="subbutton">
                            <button type="submit" class="btn btn-success" name="btnAddPost">
                                Submit
                            </button>
                        </div>

                    </form>

                </div>
            </div>


        </div>

    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
</body>

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
</html>



