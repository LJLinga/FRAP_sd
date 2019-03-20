<?php
    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';


    //checks the status of the application that you have sent
    $query = "SELECT MAX(RECORD_ID), APP_STATUS from health_aid WHERE MEMBER_ID = {$_SESSION['idnum']} ";
    $result = mysqli_query($dbc,$query);
    $row = mysqli_fetch_array($result);

    if($row['APP_STATUS'] == 2){ // it means you have an approved HA - youll be sent to the HA summary

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA summary.php");

    }else if(empty($row)){ // it means you have have not applied yet for HA, youll be sent to HA application

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA application.php");

    }




$page_title = 'Loans - Health Aid Application Sent';
include 'GLOBAL_HEADER.php';
include 'FRAP_USER_SIDEBAR.php';
?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row"> <!-- Title & Breadcrumb -->
                    
                    <div class="col-lg-12">

                        <h1 class="page-header">Health Aid Application Form</h1>

                    </div>

                </div>


                <div class = "row">

                    <div class="col-lg-8">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>Loan Calculator</b>

                            </div>


                            <div class="panel-body">



                            </div>

                        </div>

                    </div>


                    <div class= "col-lg-4">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>Loan Calculator</b>

                            </div>


                            <div class="panel-body">


                            </div>

                        </div>

                    </div>

                </div>

            </div>



            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
<?php include 'GLOBAL_FOOTER.php' ?>