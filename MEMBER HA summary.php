<?php
    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';

    $query = "SELECT MAX(RECORD_ID),APP_STATUS from health_aid WHERE MEMBER_ID = {$_SESSION['idnum']} ";
    $result = mysqli_query($dbc,$query);
    $row = mysqli_fetch_array($result);


    //checks if you have not applied for one yet.
     if($row['APP_STATUS'] == 1){ // it you have a pending HA application

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA appsent.php");

     }else if(empty($row)){ // it means that hindi ka pa nagaaply.

         header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA application.php");

     }


$page_title = 'Loans - Health Aid Summary';
include 'GLOBAL_TEMPLATE_Header.php';
include 'LOAN_TEMPLATE_NAVIGATION_Member.php';

?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">Health Aid Program Summary</h1>
                    
                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="alert alert-info">

                            The names listed below are eligible to enjoy the benefits of the Health Aid Program.

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-2">

                    </div>

                    <div class="col-lg-8">

                        <div class="well" align="center">
                            <?php
                                $query = "SELECT DATE_APPROVED FROM HEALTH_AID WHERE MEMBER_ID = ". $_SESSION['idnum'] .";";
                                $result = mysqli_query($dbc, $query);
                                $row = mysqli_fetch_array($result);
                            ?>
                            <b>Health Aid Program Member Since:</b> <?php echo $row['DATE_APPROVED']; ?>

                            <div>
                                <!-- Insert code here to update every month -->
                                <b>Total Health Aid Contribution:</b> ₱ 1,800.00

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12 col-1">

                        <div class="panel panel-default">

                            <div class="panel-heading">

                                <b>Health Aid Program Beneficiaries</b>

                            </div>

                        <div class="panel-body">

                        <div class="row">

                            <div class="col-lg-1 col-1">

                            </div>

                            <?php
                                $query = "SELECT * FROM FATHER WHERE MEMBER_ID =" . $_SESSION['idnum'].";";
                                $result = mysqli_query($dbc, $query);
                                $row = mysqli_fetch_array($result);

                                $today = date("Y-m-d");
                                $diff = date_diff(date_create($row['BIRTHDATE']), date_create($today));
                                $diff->format('%y'); //Displays computed age

                                if(!empty($row)){
                            ?>

                            <div class="col-lg-10 col-2">

                                <div class="panel panel-primary">

                                    <div class="panel-heading">

                                        Father's Information

                                    </div>

                                    <div class="panel-body">

                                        <table class="table table-bordered">

                                            <thread>

                                                <tr>

                                                    <td align="center"><b>Name</b></td>
                                                    <td align="center"><b>Age</b></td>
                                                    <td align="center"><b>Birthday</b></td>

                                                </tr>

                                            </thread>

                                            <tbody>

                                            <tr>

                                                <td align="center"><?php echo $row['FIRSTNAME'] . " " . $row['LASTNAME']; ?></td>
                                                <td align="center"><?php echo $diff->format('%y'); ?></td>
                                                <td align="center"><?php echo $row['BIRTHDATE']; ?></td>

                                            </tr>

                                            </tbody>

                                        </table>

                                    </div>

                                </div>
                                <?php
                                }
                                ?>


                                <?php

                                    $query = "SELECT * FROM MOTHER WHERE MEMBER_ID =" . $_SESSION['idnum'].";";
                                    $result = mysqli_query($dbc, $query);
                                    $row = mysqli_fetch_array($result);

                                    $today = date("Y-m-d");
                                    $diff = date_diff(date_create($row['BIRTHDATE']), date_create($today));
                                    $diff->format('%y');

                                    if(!empty($row)) {
                                        ?>


                                        <div class="panel panel-primary">

                                            <div class="panel-heading">

                                                Mother's Information

                                            </div>

                                            <div class="panel-body">

                                                <table class="table table-bordered">

                                                    <thread>

                                                        <tr>

                                                            <td align="center"><b>Name</b></td>
                                                            <td align="center"><b>Age</b></td>
                                                            <td align="center"><b>Birthday</b></td>

                                                        </tr>

                                                    </thread>

                                                    <tbody>

                                                    <tr>


                                                        <td align="center"><?php echo $row['FIRSTNAME'] . " " . $row['LASTNAME']; ?></td>
                                                        <td align="center"><?php echo $diff->format('%y'); ?></td>
                                                        <td align="center"><?php echo $row['BIRTHDATE']; ?></td>

                                                    </tr>

                                                    </tbody>

                                                </table>

                                            </div>

                                        </div>
                                        <?php
                                    }
                                ?>

                                <?php
                                    $query = "SELECT * FROM SPOUSE WHERE MEMBER_ID =" . $_SESSION['idnum'].";";
                                    $result = mysqli_query($dbc, $query);
                                    $row = mysqli_fetch_array($result);

                                    $today = date("Y-m-d");
                                    $diff = date_diff(date_create($row['BIRTHDATE']), date_create($today));

                                    if(!empty($row)) {
                                        ?>

                                        <div class="panel panel-primary">

                                            <div class="panel-heading">

                                                Spouse's Information

                                            </div>

                                            <div class="panel-body">

                                                <table class="table table-bordered">

                                                    <thread>

                                                        <tr>

                                                            <td align="center"><b>Name</b></td>
                                                            <td align="center"><b>Age</b></td>
                                                            <td align="center"><b>Birthday</b></td>

                                                        </tr>

                                                    </thread>

                                                    <tbody>

                                                    <tr>


                                                        <td align="center"><?php echo $row['FIRSTNAME'] . " " . $row['LASTNAME']; ?></td>
                                                        <td align="center"><?php echo $diff->format('%y'); ?></td>
                                                        <td align="center"><?php echo $row['BIRTHDATE']; ?></td>

                                                    </tr>

                                                    </tbody>

                                                </table>

                                            </div>

                                        </div>
                                        <?php
                                    }
                                ?>

                                <?php
                                    $query = "SELECT * FROM SIBLINGS WHERE MEMBER_ID =" . $_SESSION['idnum'].";";
                                    $result = mysqli_query($dbc, $query);
                                    $row = mysqli_fetch_array($result);
                                    if(!empty($row)) {
                                        ?>


                                        <div class="panel panel-primary">

                                            <div class="panel-heading">

                                                Sibling's Information

                                            </div>

                                            <div class="panel-body">

                                                <table class="table table-bordered">

                                                    <thread>

                                                        <tr>

                                                            <td align="center"><b>Name</b></td>
                                                            <td align="center"><b>Age</b></td>
                                                            <td align="center"><b>Birthday</b></td>

                                                        </tr>

                                                    </thread>

                                                    <tbody>

                                                    <tr>

                                                        <?php


                                                        foreach ($result as $resultRow) {
                                                            $today = date("Y-m-d");
                                                            $diff = date_diff(date_create($resultRow['BIRTHDATE']), date_create($today));

                                                            echo "
                                                                <tr>
                                                                    <td align='center'>" . $resultRow['FIRSTNAME'] . " " . $resultRow['LASTNAME'] . "</td>
                                                                    <td align='center'>" . $diff->format('%y') . "</td>
                                                                    <td align='center'>" . $resultRow['BIRTHDATE'] . "</td>
                                                                </tr>
                                                            ";
                                                        }
                                                        ?>

                                                    </tr>

                                                    </tbody>

                                                </table>

                                            </div>

                                        </div>
                                        <?php
                                    }
                                ?>

                                <?php
                                    $query = "SELECT * FROM CHILDREN WHERE MEMBER_ID =" . $_SESSION['idnum'].";";
                                    $result = mysqli_query($dbc, $query);
                                    $row = mysqli_fetch_array($result);

                                    if(!empty($row)) {
                                        ?>


                                        <div class="panel panel-primary">

                                            <div class="panel-heading">

                                                Children's Information

                                            </div>

                                            <div class="panel-body">

                                                <table class="table table-bordered">

                                                    <thread>

                                                        <tr>

                                                            <td align="center"><b>Name</b></td>
                                                            <td align="center"><b>Age</b></td>
                                                            <td align="center"><b>Birthday</b></td>

                                                        </tr>

                                                    </thread>

                                                    <tbody>

                                                    <tr>

                                                        <?php

                                                        foreach ($result as $resultRow) {
                                                            $today = date("Y-m-d");
                                                            $diff = date_diff(date_create($resultRow['BIRTHDATE']), date_create($today));

                                                            echo "
                                                                <tr>
                                                                    <td align='center'>" . $resultRow['FIRSTNAME'] . " " . $resultRow['LASTNAME'] . "</td>
                                                                    <td align='center'>" . $diff->format('%y') . "</td>
                                                                    <td align='center'>" . $resultRow['BIRTHDATE'] . "</td>
                                                                </tr>
                                                            ";
                                                        }
                                                        ?>

                                                    </tr>

                                                    </tbody>

                                                </table>

                                            </div>

                                        </div>
                                        <?php
                                    }
                                ?>

                                </div>

                            </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12" align="center">

                         <a href="MEMBER dashboard.php" class="btn btn-default" role="button">Go Back</a>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">
                        &nbsp;
                    </div>

                </div>

                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

<?php include 'GLOBAL_TEMPLATE_Footer.php' ?>
