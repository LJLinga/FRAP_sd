<?php

    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';

    error_reporting(NULL);


    $page_title = 'Loans - Dashboard';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_USER_SIDEBAR.php';

?>
        <div id="content-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">

                        <h3 class="page-header"> Member Account Overview</h3>
                    
                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-4 col-1">

                        <div class="well" align="center" style="background-color: white">

                            <b>Current Employee Department</b>

                            <div>

                                &nbsp;

                            </div>

                            <div>

                                <?php
                                    $query3 = "SELECT M.DATE_APPROVED, D.DEPT_NAME, US.STATUS
                                    FROM MEMBER M
                                    JOIN ref_department D
                                    ON M.DEPT_ID = D.DEPT_ID
                                    JOIN user_status US
                                    ON M.USER_STATUS = US.STATUS_ID
                                    WHERE M.MEMBER_ID = {$_SESSION['idnum']}";

                                    $result3 = mysqli_query($dbc, $query3);
                                    $row3 = mysqli_fetch_array($result3);

                                    echo "Faculty - ";
                                    echo $row3["DEPT_NAME"];

                                ?>


                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4 col-2">

                        <div class="well" align="center" style="background-color: white">

                            <b>Faculty Association Member Since</b>
                            
                            <div>

                                <br>

                            </div>

                            <div>

                                <?php
                                    $date = date_create($row3['DATE_APPROVED']);
                                    echo date_format($date, 'F  j,  Y');

                                ?>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4 col-3">

                        <div class="well" align="center" style="background-color: white">

                            <b>Member Type:</b>
                            
                            <div>

                                &nbsp;

                            </div>

                            <div>

                                <?php
                                echo $row3['STATUS'];

                                ?>

                            </div>

                        </div>

                    </div>

                </div>

                <hr>



                <div class="row">


                    <div class="col-lg-4 col-md-6">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <div class="row">

                                    <div class="col-xs-3">

                                        <i class="fa fa-money fa-5x"></i>

                                    </div>

                                    <div class="col-xs-9 text-right">

                                        <div class="huge">FALP</div>

                                        <div>Loan Program</div>

                                    </div>

                                </div>

                            </div>

                            <a href="MEMBER%20FALP%20application.php">
                    
                                <div class="panel-footer">
                    
                                    <span class="pull-left">View Details</span>

                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                    <div class="clearfix"></div>
                    
                                </div>
                    
                            </a>
                    
                        </div>
                    
                    </div>




                    <div class="col-lg-4 col-md-6">

                        <div class="panel panel-red">

                            <div class="panel-heading">

                                <div class="row">

                                    <div class="col-xs-3">

                                        <i class="fa fa-medkit fa-5x"></i>

                                    </div>

                                    <div class="col-xs-9 text-right">

                                        <div class="huge" id="dashboardhealthaid">Health</div>
                                        <div>Aid Fund Program</div>

                                    </div>

                                </div>

                            </div>

                            <a href="MEMBER%20HA%20application.php">

                                <div class="panel-footer">

                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                    <div class="clearfix"></div>

                                </div>

                            </a>

                        </div>

                    </div>

                    <div class="col-lg-4 col-md-6">

                        <div class="panel panel-primary">

                            <div class="panel-heading">

                                <div class="row">

                                    <div class="col-xs-3">

                                        <i class="fa fa-id-card-o fa-5x"></i>

                                    </div>

                                    <div class="col-xs-9 text-right">

                                        <div class="huge">Lifetime</div>
                                        <div>Membership</div>

                                    </div>

                                </div>

                            </div>

                            <a href="MEMBER%20LIFETIME%20form.php">

                                <div class="panel-footer">

                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                    <div class="clearfix"></div>

                                </div>

                            </a>

                        </div>

                    </div>

                </div>    

                <hr>

                <div class="row">

                    <div class="col-lg-12 col-1">

                        <!-- PUT DEDUCTIONS SUMMARY HERE -->

                    </div>

                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
<?php include 'GLOBAL_FOOTER.php' ?>
